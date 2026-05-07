<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Auto-sync emergency logs ke Kafka setiap 5 menit.
 * Hanya berjalan jika ada data di tabel emergency_logs DAN Kafka sudah UP.
 */
Schedule::command('logs:sync-emergency')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();
