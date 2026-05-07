<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUnifiedLog;
use App\Models\EmergencyLog;
use App\Services\KafkaHealthService;
use Illuminate\Console\Command;

class SyncEmergencyLogs extends Command
{
    protected $signature = 'logs:sync-emergency';

    protected $description = 'Otomatis sinkronisasi emergency logs ke Kafka jika broker sudah aktif';

    public function handle(): int
    {
        $count = EmergencyLog::query()->count();

        if ($count === 0) {
            $this->info('Tidak ada emergency logs. Skip.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$count} emergency logs. Mengecek koneksi Kafka...");

        // Cek apakah Kafka sudah hidup
        $health = app(KafkaHealthService::class)->getStatus();

        if (!($health['connected'] ?? false)) {
            $this->warn('Kafka masih DOWN. Menunggu cron berikutnya.');
            return self::SUCCESS;
        }

        $this->info('Kafka UP! Memulai sinkronisasi...');

        $synced = 0;
        $failed = 0;

        // Proses dalam chunk kecil agar tidak membebani RAM
        EmergencyLog::query()->chunk(50, function ($logs) use (&$synced, &$failed) {
            foreach ($logs as $log) {
                try {
                    ProcessUnifiedLog::dispatch($log->payload)->onQueue('logs');
                    $log->delete();
                    $synced++;
                } catch (\Throwable $e) {
                    $failed++;
                    $this->error("Gagal sync log #{$log->id}: {$e->getMessage()}");

                    // Jika gagal, kemungkinan Kafka mati lagi. Stop proses.
                    $this->warn('Menghentikan proses. Sisa data akan dilanjutkan di cron berikutnya.');
                    return false; // Stop chunking
                }
            }
        });

        $this->info("Selesai. Berhasil: {$synced}, Gagal: {$failed}");

        return self::SUCCESS;
    }
}
