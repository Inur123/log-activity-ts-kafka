<div class="space-y-6" wire:poll.10000ms="refresh">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <div class="text-xs text-slate-500">Super Admin • Kafka Monitor</div>
            <h1 class="text-2xl font-bold text-slate-900">Kafka Monitor</h1>
            <p class="text-sm text-slate-600">Status realtime broker, consumer group, dan antrian log.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Status Badge Besar --}}
            @if ($status['connected'] ?? false)
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    Kafka UP
                </span>
            @else
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    Kafka DOWN
                </span>
            @endif

            {{-- Manual Refresh --}}
            <button wire:click="refresh" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-medium text-slate-700">
                <i class="fa-solid fa-rotate-right" wire:loading.class="animate-spin"></i>
                <span wire:loading.remove>Refresh</span>
                <span wire:loading>Loading...</span>
            </button>
        </div>
    </div>

    {{-- Last checked info --}}
    <div class="text-xs text-slate-400">
        Auto-refresh tiap 10 detik &mdash; Terakhir dicek: <span class="font-mono">{{ $lastUpdate }}</span>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Status --}}
        <div
            class="rounded-2xl border p-5 {{ $status['connected'] ?? false ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }}">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs {{ $status['connected'] ?? false ? 'text-emerald-600' : 'text-red-500' }}">
                        Koneksi</div>
                    <div
                        class="mt-1 text-2xl font-bold {{ $status['connected'] ?? false ? 'text-emerald-700' : 'text-red-700' }}">
                        {{ $status['status'] ?? 'DOWN' }}
                    </div>
                </div>
                <div
                    class="h-10 w-10 rounded-2xl flex items-center justify-center {{ $status['connected'] ?? false ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                    <i class="fa-solid fa-{{ $status['connected'] ?? false ? 'check' : 'xmark' }}"></i>
                </div>
            </div>
        </div>

        {{-- Topic --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-slate-500">Topic</div>
                    <div class="mt-1 text-2xl font-bold text-slate-900 font-mono">
                        {{ $status['topic'] ?? '-' }}
                    </div>
                    <div class="text-xs text-slate-400 mt-1">{{ $status['partitions'] ?? 0 }} partition</div>
                </div>
                <div class="h-10 w-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>

        {{-- LAG --}}
        <div
            class="rounded-2xl border p-5
            {{ ($status['lag'] ?? -1) === 0
                ? 'border-emerald-200 bg-emerald-50'
                : (($status['lag'] ?? -1) > 0
                    ? 'border-amber-200 bg-amber-50'
                    : 'border-slate-200 bg-white') }}">
            <div class="flex items-start justify-between">
                <div>
                    <div
                        class="text-xs
                        {{ ($status['lag'] ?? -1) === 0 ? 'text-emerald-600' : (($status['lag'] ?? -1) > 0 ? 'text-amber-600' : 'text-slate-500') }}">
                        Queue LAG
                    </div>
                    <div
                        class="mt-1 text-2xl font-bold
                        {{ ($status['lag'] ?? -1) === 0 ? 'text-emerald-700' : (($status['lag'] ?? -1) > 0 ? 'text-amber-700' : 'text-slate-400') }}">
                        @if (($status['lag'] ?? -1) === -1)
                            N/A
                        @else
                            {{ number_format($status['lag']) }}
                        @endif
                    </div>
                    <div
                        class="text-xs mt-1
                        {{ ($status['lag'] ?? -1) === 0 ? 'text-emerald-500' : 'text-slate-400' }}">
                        @if (($status['lag'] ?? -1) === 0)
                            Semua terproses ✓
                        @elseif (($status['lag'] ?? -1) > 0)
                            pesan belum diproses
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div
                    class="h-10 w-10 rounded-2xl flex items-center justify-center
                    {{ ($status['lag'] ?? -1) === 0 ? 'bg-emerald-500 text-white' : (($status['lag'] ?? -1) > 0 ? 'bg-amber-500 text-white' : 'bg-slate-900 text-white') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
            </div>
        </div>

        {{-- Broker Count --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-slate-500">Broker Aktif</div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">
                        {{ count($status['brokers'] ?? []) }}
                    </div>
                    <div class="text-xs text-slate-400 mt-1">node terhubung</div>
                </div>
                <div class="h-10 w-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Detail Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Broker Detail --}}
        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="font-semibold text-slate-900">Broker Info</div>
                <div class="text-xs text-slate-500">Daftar broker yang terhubung</div>
            </div>
            <div class="p-5 space-y-3">
                @if (!empty($status['brokers']))
                    @foreach ($status['brokers'] as $i => $broker)
                        <div
                            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-8 w-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                    #{{ $i + 1 }}
                                </div>
                                <span class="text-sm font-mono font-semibold text-slate-800">{{ $broker }}</span>
                            </div>
                            <span
                                class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6 text-slate-400">
                        <i class="fa-solid fa-server text-2xl mb-2 block"></i>
                        Tidak ada broker yang terhubung
                    </div>
                @endif
            </div>
        </div>

        {{-- Consumer Group Detail --}}
        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="font-semibold text-slate-900">Consumer Group</div>
                <div class="text-xs text-slate-500">Info group yang memproses antrian</div>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Group ID</span>
                    <span
                        class="text-sm font-mono font-semibold text-slate-800">{{ $status['consumer_group'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Topic</span>
                    <span class="text-sm font-mono font-semibold text-slate-800">{{ $status['topic'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-sm text-slate-500">Partitions</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $status['partitions'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-500">LAG Total</span>
                    @php $lag = $status['lag'] ?? -1; @endphp
                    @if ($lag === -1)
                        <span class="text-sm font-semibold text-slate-400">N/A</span>
                    @elseif ($lag === 0)
                        <span class="text-sm font-semibold text-emerald-600">0 — Semua terproses ✓</span>
                    @else
                        <span class="text-sm font-semibold text-amber-600">{{ number_format($lag) }} pesan
                            menunggu</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- LAG Info Box --}}
    @if (($status['lag'] ?? -1) > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <div class="font-semibold text-amber-800">Queue LAG Terdeteksi</div>
                <div class="text-sm text-amber-700 mt-1">
                    Ada <strong>{{ number_format($status['lag']) }} pesan</strong> di antrian yang belum diproses oleh
                    worker.
                    Pastikan queue worker sedang berjalan dengan perintah:
                </div>
                <code class="mt-2 block text-xs bg-amber-100 text-amber-900 px-3 py-2 rounded-lg font-mono">
                    php artisan queue:work kafka --queue=logs
                </code>
            </div>
        </div>
    @elseif (!($status['connected'] ?? false))
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 flex items-start gap-4">
            <div class="h-10 w-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div>
                <div class="font-semibold text-red-800">Kafka Tidak Terhubung</div>
                <div class="text-sm text-red-700 mt-1">
                    Sistem tidak dapat terhubung ke broker Kafka. Periksa service Kafka di server:
                </div>
                <code class="mt-2 block text-xs bg-red-100 text-red-900 px-3 py-2 rounded-lg font-mono">
                    sudo systemctl status kafka
                </code>
            </div>
        </div>
    @endif

</div>
