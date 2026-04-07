<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLogReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $logCount;
    public string $logType;
    public string $applicationName;

    public function __construct(int $logCount, string $logType, string $applicationName)
    {
        $this->logCount = $logCount;
        $this->logType = $logType;
        $this->applicationName = $applicationName;
    }

    /**
     * Channel publik — semua admin yang buka dashboard bisa dengar.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * Nama event di frontend.
     */
    public function broadcastAs(): string
    {
        return 'log.received';
    }

    /**
     * Data yang dikirim ke browser (ringan).
     */
    public function broadcastWith(): array
    {
        return [
            'log_count'        => $this->logCount,
            'log_type'         => $this->logType,
            'application_name' => $this->applicationName,
            'timestamp'        => now()->toDateTimeString(),
        ];
    }
}
