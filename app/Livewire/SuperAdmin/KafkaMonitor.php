<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Services\KafkaHealthService;
use App\Models\EmergencyLog;
use App\Jobs\ProcessUnifiedLog;

#[Layout('components.layouts.super-admin')]
#[Title('Kafka Monitor')]
class KafkaMonitor extends Component
{
    public array  $status           = [];
    public bool   $loading          = false;
    public string $lastUpdate       = '';
    public int    $emergencyCount   = 0;
    public bool   $isSyncing        = false;

    public function mount(): void
    {
        $this->refresh();
    }

    /**
     * Dipanggil oleh wire:poll tiap 10 detik atau Reverb event
     */
    #[On('echo:dashboard,.log.received')]
    public function refresh(): void
    {
        $this->status         = app(KafkaHealthService::class)->getStatus();
        $this->emergencyCount = EmergencyLog::query()->count();
        $this->lastUpdate     = now()->format('H:i:s');
    }

    /**
     * Memindahkan log dari MySQL ke Kafka
     */
    public function syncEmergencyLogs(): void
    {
        if ($this->emergencyCount === 0) return;

        $this->isSyncing = true;

        try {
            $logs = EmergencyLog::limit(100)->get(); // Ambil bertahap agar tidak timeout

            foreach ($logs as $log) {
                ProcessUnifiedLog::dispatch($log->payload)->onQueue('logs');
                $log->delete();
            }

            $this->refresh();
            session()->flash('success', $logs->count() . ' log berhasil dikirim ke Kafka.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        } finally {
            $this->isSyncing = false;
        }
    }

    public function render()
    {
        return view('livewire.super-admin.kafka-monitor', [
            'status'         => $this->status,
            'lastUpdate'     => $this->lastUpdate,
            'emergencyCount' => $this->emergencyCount,
        ]);
    }
}
