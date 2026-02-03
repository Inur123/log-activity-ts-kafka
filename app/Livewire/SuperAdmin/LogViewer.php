<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Application;
use App\Models\UnifiedLog;
use App\Services\HashChainService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.super-admin')]
#[Title('Log Viewer')]
class LogViewer extends Component
{
    public string $action = 'index';

    public ?string $logId = null;
    public ?UnifiedLog $selectedLog = null;

    public string $q = '';
    public string $application_id = '';
    public string $log_type = '';

    //  New Filters
    public string $validation_status = ''; // PASSED / FAILED / ''
    public string $validation_stage  = '';

    public string $from = '';
    public string $to = '';
    public int $per_page = 25;
    public string $sort = 'newest';

    public ?string $cursorCreatedAt = null;
    public ?string $cursorId = null;
    public string $cursorDirection = 'next';
    public array $cursorStack = [];
    public bool $hasNext = false;
    public bool $hasPrev = false;
    public int $pageIndex = 1;
    public ?string $currentFirstCreatedAt = null;
    public ?string $currentFirstId = null;
    public ?string $currentLastCreatedAt = null;
    public ?string $currentLastId = null;

    public ?array $chainStatus = null;
    public ?array $logSecurityStatus = null;
    public bool $verifying = false;

    /**
     * Reset page saat filter berubah
     */
    public function updated($name, $value): void
    {
        //  normalize uppercase
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
            $this->resetCursor();
        }
    }

    private function resetCursor(): void
    {
        $this->cursorCreatedAt = null;
        $this->cursorId = null;
        $this->cursorDirection = 'next';
        $this->cursorStack = [];
    }

    public function nextPage(): void
    {
        if ($this->currentLastCreatedAt === null || $this->currentLastId === null) return;

        if ($this->currentFirstCreatedAt !== null && $this->currentFirstId !== null) {
            $this->cursorStack[] = [
                'created_at' => $this->currentFirstCreatedAt,
                'id' => $this->currentFirstId,
            ];
        }

        $this->cursorCreatedAt = $this->currentLastCreatedAt;
        $this->cursorId = $this->currentLastId;
        $this->cursorDirection = 'next';
    }

    public function prevPage(): void
    {
        if (empty($this->cursorStack)) return;
        if ($this->currentFirstCreatedAt === null || $this->currentFirstId === null) return;

        $this->cursorCreatedAt = $this->currentFirstCreatedAt;
        $this->cursorId = $this->currentFirstId;
        $this->cursorDirection = 'prev';
        array_pop($this->cursorStack);
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
        $this->resetCursor();
    }

    /**
     * Verify Chain per Application
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

    public function clearChainStatus(): void
    {
        $this->chainStatus = null;
    }

    /**
     * Show Detail + Verify log security
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

        if ($this->application_id !== '') {
            $query->where('application_id', $this->application_id);
        }

        if ($this->log_type !== '') {
            $query->where('log_type', $this->log_type);
        }

        if ($this->from) $query->whereDate('created_at', '>=', $this->from);
        if ($this->to)   $query->whereDate('created_at', '<=', $this->to);

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
                    ->orWhereHas('application', fn($app) => $app->where('name', 'like', "%$q%"));
            });
        }

        return $query;
    }

    public function getFilteredLogs()
    {
        $base = $this->buildQuery();

        $perPage = $this->per_page;
        $total = (clone $base)->count();
        $lastPage = max(1, (int) ceil($total / $perPage));

        $sortDirection = $this->sort === 'oldest' ? 'asc' : 'desc';
        $queryDirection = ($this->sort === 'oldest' && $this->cursorDirection === 'prev') ? 'desc' : $sortDirection;

        $base->orderBy('created_at', $queryDirection)
            ->orderBy('id', $queryDirection);

        if ($this->cursorCreatedAt !== null && $this->cursorId !== null) {
            $operator = '>';
            if ($this->sort === 'oldest') {
                $operator = $this->cursorDirection === 'next' ? '>' : '<';
            } else {
                $operator = $this->cursorDirection === 'next' ? '<' : '>';
            }

            $base->where(function ($q) use ($operator) {
                $q->where('created_at', $operator, $this->cursorCreatedAt)
                    ->orWhere(function ($qq) use ($operator) {
                        $qq->where('created_at', $this->cursorCreatedAt)
                            ->where('id', $operator, $this->cursorId);
                    });
            });
        }

        $items = $base->limit($perPage + 1)->get();
        $hasMore = $items->count() > $perPage;
        if ($hasMore) {
            $items = $items->take($perPage);
        }

        if ($this->sort === 'oldest' && $this->cursorDirection === 'prev') {
            $items = $items->reverse()->values();
        }

        $this->currentFirstCreatedAt = $items->first()?->created_at?->toDateTimeString();
        $this->currentFirstId = $items->first()?->id;
        $this->currentLastCreatedAt = $items->last()?->created_at?->toDateTimeString();
        $this->currentLastId = $items->last()?->id;

        $this->hasPrev = !empty($this->cursorStack);
        $this->pageIndex = count($this->cursorStack) + 1;

        $this->hasNext = false;
        if ($items->isNotEmpty() && $this->currentLastCreatedAt !== null && $this->currentLastId !== null) {
            $nextCheck = $this->buildQuery();
            $nextOperator = $this->sort === 'oldest' ? '>' : '<';

            $nextCheck->where(function ($q) use ($nextOperator) {
                $q->where('created_at', $nextOperator, $this->currentLastCreatedAt)
                    ->orWhere(function ($qq) use ($nextOperator) {
                        $qq->where('created_at', $this->currentLastCreatedAt)
                            ->where('id', $nextOperator, $this->currentLastId);
                    });
            });

            $this->hasNext = $nextCheck->exists();
        }

        return [$items, $hasMore, $total, $lastPage];
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

    public function render()
    {
        return match ($this->action) {
            'detail' => (function () {
                $payloadArr = $this->payloadToArray($this->selectedLog?->payload);

                return view('livewire.super-admin.log-viewer.detail', [
                    'log' => $this->selectedLog,
                    'payload' => $payloadArr,
                    'logSecurityStatus' => $this->logSecurityStatus,
                ]);
            })(),

            default => (function () {
                [$logs, $hasMore, $total, $lastPage] = $this->getFilteredLogs();

                return view('livewire.super-admin.log-viewer.index', [
                    'logs' => $logs,
                    'applications' => Application::orderBy('name')->get(),
                    'logTypeOptions' => UnifiedLog::query()
                        ->whereNotNull('log_type')
                        ->where('log_type', '!=', '')
                        ->distinct()
                        ->orderBy('log_type')
                        ->pluck('log_type'),
                    'per_page' => $this->per_page,
                    'pageIndex' => $this->pageIndex,
                    'hasPrev' => $this->hasPrev,
                    'hasNext' => $this->hasNext,
                    'total' => $total,
                    'lastPage' => $lastPage,
                    'chainStatus' => $this->chainStatus,
                    'verifying' => $this->verifying,
                ]);
            })(),
        };
    }
}
