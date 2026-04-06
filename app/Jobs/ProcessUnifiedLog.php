<?php

namespace App\Jobs;

use App\Models\UnifiedLog;
use App\Services\HashChainService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessUnifiedLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    public $tries = 3;
    public $backoff = [60, 120, 300];

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $hashService = new HashChainService();
        $appId = (string) $this->data['application_id'];

        DB::beginTransaction();

        try {
            // ✅ Step 1: Atomic increment di tabel kecil (lock sangat singkat)
            // Ini menggantikan lockForUpdate() pada tabel unified_logs yang besar
            DB::table('log_sequences')->updateOrInsert(
                ['application_id' => $appId],
                ['last_seq' => DB::raw('last_seq + 1')]
            );

            $nextSeq = (int) DB::table('log_sequences')
                ->where('application_id', $appId)
                ->value('last_seq');

            // ✅ Step 2: Ambil prev_hash tanpa lock (read-only)
            $prevLog = UnifiedLog::where('application_id', $appId)
                ->where('seq', $nextSeq - 1)
                ->first(['hash']);

            $prevHash = $prevLog ? $prevLog->hash : str_repeat('0', 64);

            // ✅ Step 3: Compute hash
            $payload = $this->data['payload'] ?? [];
            $hashService->normalizeArray($payload);

            $hash = $hashService->generateHash(
                applicationId: $appId,
                seq: $nextSeq,
                logType: $this->data['log_type'],
                payload: $payload,
                prevHash: $prevHash
            );

            // ✅ Step 4: Insert log
            UnifiedLog::create([
                'application_id' => $appId,
                'seq' => $nextSeq,
                'log_type' => substr(strtoupper($this->data['log_type']), 0, 100),
                'payload' => $payload,
                'ip_address' => isset($this->data['ip_address']) ? substr($this->data['ip_address'], 0, 45) : null,
                'user_agent' => isset($this->data['user_agent']) ? substr($this->data['user_agent'], 0, 1000) : null,
                'hash' => $hash,
                'prev_hash' => $prevHash,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::critical('ProcessUnifiedLog job failed permanently', [
            'data'  => $this->data,
            'error' => $exception->getMessage(),
        ]);
    }
}
