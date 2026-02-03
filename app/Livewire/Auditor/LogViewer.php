<?php

namespace App\Livewire\Auditor;

use App\Models\Application;
use App\Models\UnifiedLog;
use App\Services\HashChainService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.auditor')]
#[Title('Log Viewer')]
class LogViewer extends Component
{
    public string $action = 'index';

    // UUID string
    public ?string $logId = null;
    public ?UnifiedLog $selectedLog = null;

    public string $q = '';

    /**
     * Gunakan string agar aman untuk ID numeric maupun UUID.
     * Default kosong = All
     */
    public string $application_id = '';

    public string $log_type = '';

    //  New Filters (tambahan seperti super-admin)
    public string $validation_status = ''; // PASSED / FAILED / ''
    public string $validation_stage  = '';

    public string $from = '';
    public string $to = '';
    public int $per_page = 25;
    public string $sort = 'newest';
    public int $page = 1;

    //  SECURITY STATUS
    public ?array $chainStatus = null;
    public ?array $logSecurityStatus = null;
    public bool $verifying = false;

    /**
     * Reset page saat filter berubah
     */
    public function updated($name, $value): void
    {
        //  normalize uppercase seperti super admin
        if ($name === 'validation_status') $this->validation_status = strtoupper((string) $value);
        if ($name === 'validation_stage')  $this->validation_stage  = strtoupper((string) $value);

        if (in_array($name, [
            'q',
            'application_id',
            'log_type',
            'validation_status',
            'validation_stage',
            'from',
            'to',
            'per_page',
            'sort'
        ], true)) {
            $this->page = 1;
        }
    }

    public function gotoPage(int $p, int $lastPage): void
    {
        $this->page = max(1, min($p, $lastPage));
    }

    public function nextPage(): void
    {
        // Re-calculate last page to ensure validity, or just increment and let getFilteredLogs handle clamping
        // For simplicity and to fix the crash, we just increment.
        // Ideally we should check against max page, but that requires querying count.
        // The view logic protects against clicking Next if on last page.
        $this->page++;
    }

    public function prevPage(): void
    {
        if ($this->page > 1) $this->page--;
    }

    public function resetFilters(): void
    {
        $this->q = '';
        $this->application_id = '';
        $this->log_type = '';
        $this->validation_status = '';
        $this->validation_stage = '';
        $this->from = '';
        $this->to = '';
        $this->per_page = 25;
        $this->sort = 'newest';
        $this->page = 1;
    }

    /**
     *  Verify Chain per Application
     */
    public function verifySelectedApplicationChain(): void
    {
        $this->chainStatus = null;

        if ($this->application_id === '') {
            $this->chainStatus = [
                'valid' => false,
                'message' => 'Pilih Application dulu untuk verifikasi chain.',
                'errors' => [],
                'total_checked' => 0,
            ];
            return;
        }

        $this->verifying = true;

        try {
            $service = new HashChainService();
            $this->chainStatus = $service->verifyChainByApplication($this->application_id);
        } catch (\Throwable $e) {
            $this->chainStatus = [
                'valid' => false,
                'message' => 'Verify failed: ' . $e->getMessage(),
                'errors' => [],
                'total_checked' => 0,
            ];
        } finally {
            $this->verifying = false;
        }
    }

    /**
     *  Clear chain status
     */
    public function clearChainStatus(): void
    {
        $this->chainStatus = null;
    }

    /**
     *  Show Detail + Verify log security
     */
    public function showDetail(string $id): void
    {
        $this->logId = $id;
        $this->selectedLog = UnifiedLog::with('application')->findOrFail($id);

        try {
            $service = new HashChainService();
            $this->logSecurityStatus = $service->verifySingleLog($this->selectedLog);
        } catch (\Throwable $e) {
            $this->logSecurityStatus = [
                'valid' => false,
                'error' => $e->getMessage(),
            ];
        }

        $this->action = 'detail';
    }

    public function back(): void
    {
        $this->action = 'index';
        $this->logId = null;
        $this->selectedLog = null;
        $this->logSecurityStatus = null;
    }

    private function buildQuery()
    {
        $query = UnifiedLog::query()->with('application');

        //  Filter Application
        if ($this->application_id !== '') {
            $query->where('application_id', $this->application_id);
        }

        if ($this->log_type !== '') $query->where('log_type', $this->log_type);

        if ($this->from) $query->where('created_at', '>=', $this->from . ' 00:00:00');
        if ($this->to)   $query->where('created_at', '<=', $this->to . ' 23:59:59');

        //  Validation Status Filter
        if ($this->validation_status !== '') {
            $query->whereRaw(
                "JSON_UNQUOTE(JSON_EXTRACT(payload, '$.validation.status')) = ?",
                [$this->validation_status]
            );
        }


        if ($this->q !== '') {
            $q = trim($this->q);

            $query->where(function ($sub) use ($q) {
                $sub->orWhere('id', $q)
                    ->orWhereRaw("CAST(payload AS CHAR) LIKE ?", ["%$q%"])
                    ->orWhereHas(
                        'application',
                        fn($app) =>
                        $app->where('name', 'like', "%$q%")
                    );
            });
        }

        return $query;
    }

    public function getFilteredLogs()
    {
        $base = $this->buildQuery();

        // 1. Hitung total (ringan, COUNT(*))
        $total = (clone $base)->count();
        $perPage = $this->per_page;
        $lastPage = max(1, (int) ceil($total / $perPage));

        // Fix page out of bounds
        if ($this->page > $lastPage) $this->page = $lastPage;

        // 2. Ambil ID saja untuk pagination/sorting (Late Row Lookups)
        // Ini mencegah MySQL kehabisan memory saat sorting JSON payload yang besar
        $this->sort === 'oldest'
            ? $base->oldest('created_at')
            : $base->latest('created_at');

        // Ambil ID-nya saja yang sudah ter-sort & ter-limit
        $ids = $base->forPage($this->page, $perPage)->pluck('id');

        // 3. Ambil data lengkap berdasarkan ID tersebut (tetap menjaga urutan)
        if ($ids->isNotEmpty()) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $items = UnifiedLog::with('application')
                ->whereIn('id', $ids)
                ->orderByRaw("FIELD(id, $placeholders)", $ids->toArray())
                ->get();
        } else {
            $items = collect();
        }

        return [$items, $total, $lastPage];
    }

    private function payloadToArray(mixed $payload): array
    {
        if (is_array($payload)) return $payload;

        if (is_object($payload)) {
            $arr = json_decode(json_encode($payload), true);
            return is_array($arr) ? $arr : ['_raw' => json_encode($payload)];
        }

        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $decoded;
            return ['_raw' => $payload];
        }

        if ($payload === null) return [];
        return ['_raw' => (string) $payload];
    }

    private function buildSummary(array $data): array
    {
        $pick = function (array $keys) use ($data) {
            foreach ($keys as $k) {
                $v = data_get($data, $k);
                if ($v !== null && $v !== '' && $v !== []) return $v;
            }
            return null;
        };

        $summary = [
            'Status' => $pick(['status', 'code', 'http.status', 'response.status']),
            'Method' => $pick(['method', 'http.method', 'request.method']),
            'URL'    => $pick(['url', 'path', 'endpoint', 'http.url', 'request.url']),
            'User'   => $pick(['user.email', 'user.name', 'user_id', 'auth.user_id']),
            'Action' => $pick(['action', 'event', 'type', 'message']),
            'Error'  => $pick(['error.message', 'error', 'exception.message', 'exception']),
        ];

        return array_filter($summary, fn($v) => $v !== null);
    }

    public function render()
    {
        return match ($this->action) {
            'detail' => (function () {
                $payloadArr = $this->payloadToArray($this->selectedLog?->payload);

                return view('livewire.auditor.log-viewer.detail', [
                    'log' => $this->selectedLog,
                    'payload' => $payloadArr,
                    'summary' => $this->buildSummary($payloadArr),
                    'logSecurityStatus' => $this->logSecurityStatus,
                ]);
            })(),

            default => (function () {
                [$logs, $total, $lastPage] = $this->getFilteredLogs();

                return view('livewire.auditor.log-viewer.index', [
                    'logs' => $logs,
                    'applications' => \Illuminate\Support\Facades\Cache::remember('apps_list', 600, fn() => Application::orderBy('name')->get()),
                    'logTypeOptions' => \Illuminate\Support\Facades\Cache::remember('log_types_list', 600, fn() => UnifiedLog::query()
                        ->whereNotNull('log_type')
                        ->where('log_type', '!=', '')
                        ->distinct()
                        ->orderBy('log_type')
                        ->pluck('log_type')),
                    'page' => $this->page,
                    'per_page' => $this->per_page,
                    'total' => $total,
                    'lastPage' => $lastPage,
                    'chainStatus' => $this->chainStatus,
                    'verifying' => $this->verifying,

                    //  New filters untuk view
                    'validation_status' => $this->validation_status,
                    'validation_stage'  => $this->validation_stage,
                ]);
            })(),
        };
    }
}
