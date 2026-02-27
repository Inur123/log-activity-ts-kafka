<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Services\KafkaHealthService;

#[Layout('components.layouts.super-admin')]
#[Title('Kafka Monitor')]
class KafkaMonitor extends Component
{
    public array  $status     = [];
    public bool   $loading    = false;
    public string $lastUpdate = '';

    public function mount(): void
    {
        $this->refresh();
    }

    /**
     * Dipanggil oleh wire:poll tiap 10 detik
     */
    public function refresh(): void
    {
        $this->status     = app(KafkaHealthService::class)->getStatus();
        $this->lastUpdate = now()->format('H:i:s');
    }

    public function render()
    {
        return view('livewire.super-admin.kafka-monitor', [
            'status'     => $this->status,
            'lastUpdate' => $this->lastUpdate,
        ]);
    }
}
