<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;

use App\Livewire\SuperAdmin\Dashboard as SuperAdminDashboard;
use App\Livewire\SuperAdmin\LogViewer as SuperAdminLogViewer;
use App\Livewire\SuperAdmin\Application as SuperAdminApplication;
use App\Livewire\SuperAdmin\KafkaMonitor as SuperAdminKafkaMonitor;


use App\Livewire\Auditor\Dashboard as AuditorDashboard;
use App\Livewire\Auditor\LogViewer as AuditorLogViewer;


Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', Logout::class)->name('logout');
});

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super_admin.')
    ->group(function () {
        Route::get('/dashboard', SuperAdminDashboard::class)->name('dashboard');
        Route::get('/logs', SuperAdminLogViewer::class)->name('logs');
        Route::get('/logs/{logId}', SuperAdminLogViewer::class)->name('logs.detail');
        Route::get('/applications', SuperAdminApplication::class)->name('applications');
        Route::get('/applications/{applicationId}', SuperAdminApplication::class)->name('applications.detail');
        Route::get('/applications/{applicationId}/edit', SuperAdminApplication::class)->name('applications.edit');
        Route::get('/kafka', SuperAdminKafkaMonitor::class)->name('kafka');
    });
Route::middleware(['auth', 'role:auditor'])
    ->prefix('auditor')
    ->name('auditor.')
    ->group(function () {
        Route::get('/dashboard', AuditorDashboard::class)->name('dashboard');
        Route::get('/logs', AuditorLogViewer::class)->name('logs');
        Route::get('/logs/{logId}', AuditorLogViewer::class)->name('logs.detail');
    });

Route::get('/api/v1/logs', function () {
    return response()
        ->view('errors.api-post-only', [
            'endpoint' => '/api/v1/logs',
            'method'   => 'POST',
        ], 200);
});
