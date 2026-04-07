<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Running — {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            overflow: hidden;
        }

        .bg-glow {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
        }

        .bg-glow-1 {
            top: -100px;
            right: -100px;
            background: #22d3ee;
        }

        .bg-glow-2 {
            bottom: -100px;
            left: -100px;
            background: #8b5cf6;
        }

        .card {
            position: relative;
            text-align: center;
            max-width: 420px;
            padding: 48px 40px;
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(20px);
        }

        .pulse-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(34, 197, 94, 0.1);
            margin-bottom: 24px;
            position: relative;
        }

        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid rgba(34, 197, 94, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0; }
        }

        .dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
        }

        .status {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #22c55e;
            margin-bottom: 8px;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-top: 1px solid rgba(148, 163, 184, 0.08);
            font-size: 13px;
        }

        .info-row:last-child {
            border-bottom: 1px solid rgba(148, 163, 184, 0.08);
            margin-bottom: 24px;
        }

        .info-label {
            color: #64748b;
        }

        .info-value {
            color: #cbd5e1;
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 12px;
        }

        .info-value.method {
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-docs {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        }

        .btn-docs:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(99, 102, 241, 0.4);
        }

        .btn-docs svg {
            width: 16px;
            height: 16px;
        }
    </style>
</head>

<body>
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>

    <div class="card">
        <div class="pulse-ring">
            <div class="dot"></div>
        </div>

        <div class="status">● Online</div>
        <h1>API is Running</h1>
        <p class="subtitle">Unified Logging API aktif dan siap menerima request.</p>

        <div class="info-row">
            <span class="info-label">Endpoint</span>
            <span class="info-value">{{ $endpoint ?? '/api/v1/logs' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Method</span>
            <span class="info-value method">{{ $method ?? 'POST' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Version</span>
            <span class="info-value">v1</span>
        </div>

        <a href="{{ url('/docs') }}" target="_blank" class="btn-docs">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            Lihat Dokumentasi
        </a>
    </div>
</body>
</html>
