@extends('layouts.app')

@section('title', 'Dashboard Amil Masjid')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }

        :root {
            --c-900: #0f2714;
            --c-800: #1a3d22;
            --c-700: #2d6936;
            --c-600: #3d8b40;
            --c-400: #7cb342;
            --c-100: #e6f4ea;
            --c-50: #f3faf0;
            --gold: #b8860b;
            --gold-bg: #fdf6e3;
            --gold-border: #e8d5a0;
            --n-900: #111827;
            --n-700: #374151;
            --n-500: #6b7280;
            --n-400: #9ca3af;
            --n-200: #e5e7eb;
            --n-100: #f3f4f6;
            --n-50: #f9fafb;
            --white: #ffffff;
            --radius: 16px;
            --radius-sm: 10px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 16px -2px rgba(26, 61, 34, 0.10), 0 2px 6px -1px rgba(26, 61, 34, 0.06);
            --shadow-lg: 0 12px 32px -6px rgba(26, 61, 34, 0.18), 0 4px 12px -2px rgba(26, 61, 34, 0.10);
        }

        /* ── HERO ── */
        .hero {
            background: #2d6a2d;
            border-radius: var(--radius);
            padding: 2.25rem 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .hero-decor {
            position: absolute;
            right: -48px;
            top: -64px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .06);
            pointer-events: none;
        }

        .hero-decor::after {
            content: '';
            position: absolute;
            inset: 30px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .04);
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 999px;
            padding: 4px 14px 4px 9px;
            font-size: .68rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .80);
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: .85rem;
        }

        .hero-dot {
            width: 7px;
            height: 7px;
            background: #7cb342;
            border-radius: 50%;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1 }
            50% { opacity: .3 }
        }

        .hero-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.03em;
            line-height: 1.15;
            margin-bottom: .3rem;
        }

        .hero-sub {
            font-size: .85rem;
            font-weight: 400;
            color: rgba(255, 255, 255, .55);
        }

        .hero-time {
            text-align: right;
            position: relative;
            z-index: 1;
        }

        .hero-date-lbl {
            font-size: .72rem;
            font-weight: 500;
            color: rgba(255, 255, 255, .50);
            margin-bottom: 2px;
        }

        .hero-clock {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: .02em;
        }

        /* ── SECTION LABEL ── */
        .sec-label {
            font-size: .67rem;
            font-weight: 700;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: var(--n-400);
            margin-bottom: .75rem;
            padding-left: 2px;
        }

        /* ── STAT CARD ── */
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--n-200);
            box-shadow: var(--shadow-sm);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
            transition: transform .22s ease, box-shadow .22s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--c-700), var(--c-400));
            opacity: 0;
            transition: opacity .22s;
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--c-700), var(--c-400));
            box-shadow: 0 4px 12px -2px rgba(45, 105, 54, .35);
        }

        .stat-body {
            flex: 1;
            min-width: 0;
        }

        .stat-badge {
            position: absolute;
            top: .85rem;
            right: .85rem;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--c-700);
            background: var(--c-50);
            border: 1px solid var(--c-100);
            border-radius: 999px;
            padding: 2px 8px;
        }

        .stat-val {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--n-900);
            letter-spacing: -.04em;
            line-height: 1;
            margin-bottom: .2rem;
        }

        .stat-val.--money {
            font-size: 1.15rem;
        }

        .stat-lbl {
            font-size: .75rem;
            font-weight: 600;
            color: var(--n-500);
        }

        /* ── prevent horizontal scroll ── */
        .space-y-6 { max-width: 100%; overflow-x: hidden; }

        /* ── STATS SDM GRID — auto-fit, no overflow ── */
        .stats-sdm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        /* ── METRIC CARD ── */
        .metric-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--n-200);
            box-shadow: var(--shadow-sm);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform .22s, box-shadow .22s;
            min-width: 0;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: var(--radius-sm);
            background: var(--c-50);
            border: 1px solid var(--c-100);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-val {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--n-900);
            letter-spacing: -.03em;
            line-height: 1;
        }

        .metric-lbl {
            font-size: .73rem;
            font-weight: 500;
            color: var(--n-500);
            margin-top: 3px;
        }

        /* ── PANEL ── */
        .panel {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--n-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .panel-head {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--n-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--n-50);
        }

        .panel-title {
            font-size: .875rem;
            font-weight: 700;
            color: var(--n-900);
            letter-spacing: -.01em;
        }

        .panel-tag {
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--c-700);
            background: var(--c-50);
            border: 1px solid var(--c-100);
            border-radius: 999px;
            padding: 3px 10px;
        }

        .panel-body {
            padding: 1.25rem 1.5rem;
        }

        /* ── QUICK ACTION ── */
        .qa-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .qa-item {
            background: var(--white);
            border: 1px solid var(--n-200);
            border-radius: var(--radius-sm);
            padding: 1rem 0.5rem;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none;
            display: block;
        }

        .qa-item:hover {
            transform: translateY(-3px);
            border-color: var(--c-400);
            box-shadow: var(--shadow-md);
            background: var(--c-50);
        }

        .qa-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.5rem;
            background: linear-gradient(135deg, var(--c-700), var(--c-400));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px -2px rgba(45, 105, 54, .30);
        }

        .qa-label {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--n-700);
            line-height: 1.3;
        }

    </style>
@endpush

@section('content')
    <div class="space-y-6">

        {{-- Hero --}}
        <div class="hero">
            <div class="hero-decor"></div>
            <div class="flex items-center justify-between relative" style="z-index:1">
                <div>
                    <div class="hero-pill">
                        <span class="hero-dot"></span>
                        Amil Masjid
                    </div>
                    <h1 class="hero-title">{{ $masjid->nama }}</h1>
                    <p class="hero-sub">Selamat datang, {{ $user->name ?? $user->username }} &mdash; Kelola zakat dengan amanah</p>
                </div>
                <div class="hero-time hidden sm:block">
                    <p class="hero-date-lbl">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="hero-clock" id="liveTime">{{ now()->format('H:i:s') }}</p>
                </div>
            </div>
        </div>

        {{-- Primary Stats --}}
        <div>
            <p class="sec-label">Keuangan Bulan Ini</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp {{ number_format($stats['total_penerimaan_bulan_ini'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Penerimaan Bulan Ini</p>
                    </div>
                    <span class="stat-badge">Masuk</span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp {{ number_format($stats['total_penyaluran_bulan_ini'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Penyaluran Bulan Ini</p>
                    </div>
                    <span class="stat-badge">Keluar</span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp {{ number_format($stats['saldo_saat_ini'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Saldo Saat Ini</p>
                    </div>
                    <span class="stat-badge">Kas</span>
                </div>

            </div>
        </div>

        {{-- Secondary Metrics --}}
        <div>
            <p class="sec-label">Statistik SDM</p>
            <div class="stats-sdm-grid">

                @foreach($quickStats as $stat)
                <div class="metric-card">
                    <div class="metric-icon">
                        @if($stat['icon'] === 'users')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        @elseif($stat['icon'] === 'user-group')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="metric-val">{{ is_numeric($stat['value']) ? number_format($stat['value']) : $stat['value'] }}</p>
                        <p class="metric-lbl">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        {{-- Chart --}}
        @if(isset($trendData) && $trendData->count() > 0)
        <div>
            <p class="sec-label">Analitik</p>
            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Trend Penerimaan & Penyaluran</p>
                    <span class="panel-tag">6 Bulan Terakhir</span>
                </div>
                <div class="panel-body">
                    <div style="height: 280px; position: relative;">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Aksi Cepat --}}
        <div>
            <p class="sec-label">Aksi & Tugas</p>
            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Aksi Cepat</p>
                    <span class="panel-tag">Menu</span>
                </div>
                <div class="panel-body">
                        <div class="qa-grid">

                            <a href="{{ route('transaksi-datang-langsung.create') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Input Penerimaan</span>
                            </a>

                            <a href="{{ route('transaksi-penyaluran.create') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Input Penyaluran</span>
                            </a>

                            <a href="{{ route('mustahik.index') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Data Mustahik</span>
                            </a>

                            <a href="{{ route('amil.kunjungan.index') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Kunjungan</span>
                            </a>

                            <a href="{{ route('kas-harian.index') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Kas Harian</span>
                            </a>

                            <a href="{{ route('amil.setor-kas.index') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Setor Kas</span>
                            </a>

                            <a href="{{ route('transaksi-datang-langsung.index') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Riwayat Penerimaan</span>
                            </a>

                            <a href="{{ route('transaksi-penyaluran.index') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Riwayat Penyaluran</span>
                            </a>

                            <a href="{{ route('profil.show') }}" class="qa-item">
                                <div class="qa-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="qa-label">Profil Saya</span>
                            </a>

                        </div>
                    </div>
                </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Live Clock
            const clockEl = document.getElementById('liveTime');
            if (clockEl) {
                setInterval(() => {
                    clockEl.textContent = new Date().toLocaleTimeString('id-ID');
                }, 1000);
            }

            // Global Chart Defaults
            Chart.defaults.font.family = "'Poppins', sans-serif";
            Chart.defaults.font.size = 11;
            Chart.defaults.font.weight = '500';

            const C700 = '#2d6a2d';
            const C400 = '#7cb342';
            const GRID = '#f3f4f6';
            const TICK = '#9ca3af';

            const tooltipCfg = {
                backgroundColor: '#111827',
                titleColor: '#ffffff',
                bodyColor: 'rgba(255,255,255,.65)',
                borderColor: 'rgba(124,179,66,.25)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
                titleFont: { family: "'Poppins', sans-serif", weight: '700', size: 12 },
                bodyFont: { family: "'Poppins', sans-serif", size: 11 },
                callbacks: {
                    label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            };

            // Chart Trend
            @if(isset($trendData) && $trendData->count() > 0)
            const trendData = @json($trendData);
            if (trendData.length > 0) {
                const ctx = document.getElementById('chartTrend').getContext('2d');

                const gradIn = ctx.createLinearGradient(0, 0, 0, 280);
                gradIn.addColorStop(0, 'rgba(45,106,45,.18)');
                gradIn.addColorStop(1, 'rgba(45,106,45,0)');

                const gradOut = ctx.createLinearGradient(0, 0, 0, 280);
                gradOut.addColorStop(0, 'rgba(124,179,66,.15)');
                gradOut.addColorStop(1, 'rgba(124,179,66,0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: trendData.map(i => i.bulan),
                        datasets: [{
                            label: 'Penerimaan',
                            data: trendData.map(i => i.penerimaan),
                            borderColor: C700,
                            backgroundColor: gradIn,
                            fill: true,
                            tension: 0.42,
                            borderWidth: 2.5,
                            pointRadius: 5,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: C700,
                            pointBorderWidth: 2.5,
                            pointHoverRadius: 7,
                        }, {
                            label: 'Penyaluran',
                            data: trendData.map(i => i.penyaluran),
                            borderColor: C400,
                            backgroundColor: gradOut,
                            fill: true,
                            tension: 0.42,
                            borderWidth: 2.5,
                            pointRadius: 5,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: C400,
                            pointBorderWidth: 2.5,
                            pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: tooltipCfg,
                            legend: {
                                labels: {
                                    color: TICK,
                                    font: { family: "'Poppins', sans-serif", size: 11, weight: '600' },
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 20
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: TICK }
                            },
                            y: {
                                grid: { color: GRID },
                                beginAtZero: true,
                                ticks: {
                                    color: TICK,
                                    callback: value => 'Rp ' + (value / 1000000 >= 1 ?
                                        (value / 1000000).toLocaleString('id-ID') + ' jt' :
                                        value.toLocaleString('id-ID'))
                                }
                            }
                        }
                    }
                });
            }
            @endif
        });
    </script>
@endpush