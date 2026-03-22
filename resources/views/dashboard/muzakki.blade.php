@extends('layouts.app')

@section('title', 'Dashboard Muzakki')

@php
    $breadcrumbs = [];
@endphp

@section('page-title', 'Dashboard')
@section('page-description', 'Selamat datang kembali! Pantau aktivitas zakat Anda di sini.')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }

        :root {
            /* ── Diselaraskan dengan tailwind.config.js ── */
            --primary: #17a34a;
            --primary-700: #15803d;
            --primary-800: #166534;
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
            --secondary: #2d6936;
            --accent: #4caf50;

            /* legacy aliases */
            --c-900: #0f2714;
            --c-800: #15803d;
            --c-700: #17a34a;
            --c-600: #22c55e;
            --c-400: #4caf50;
            --c-100: #dcfce7;
            --c-50: #f0fdf4;

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

            /* Shadow dengan tint hijau — sesuai tailwind.config 'nz' shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 16px -2px rgba(23, 163, 74, .10), 0 2px 6px -1px rgba(23, 163, 74, .06);
            --shadow-lg: 0 12px 32px -6px rgba(23, 163, 74, .18), 0 4px 12px -2px rgba(23, 163, 74, .10);
        }

        /* ── HERO ── */
        .hero {
            /* gradient-nz dari tailwind.config */
            background: linear-gradient(135deg, #17a34a 0%, #1d7a3e 45%, #2d6936 100%);
            border-radius: var(--radius);
            padding: 2.25rem 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 48px -8px rgba(23, 163, 74, .25), 0 8px 20px -4px rgba(23, 163, 74, .14);
        }

        /* Radial glow kanan atas */
        .hero::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -60px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(74, 222, 128, .10) 0%, transparent 65%);
            pointer-events: none;
        }

        .hero-decor {
            position: absolute;
            right: -48px;
            top: -64px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .hero-decor::after {
            content: '';
            position: absolute;
            inset: 30px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .04);
        }

        /* Lingkaran dekorasi ke-2 */
        .hero-decor-2 {
            position: absolute;
            right: 60px;
            top: -20px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 999px;
            padding: 4px 14px 4px 9px;
            font-size: .68rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .82);
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: .85rem;
        }

        .hero-dot {
            width: 7px;
            height: 7px;
            background: #86efac;
            border-radius: 50%;
            animation: blink 2s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
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
            letter-spacing: .04em;
            font-variant-numeric: tabular-nums;
        }

        /* ── SEC LABEL ── */
        .sec-label {
            font-size: .64rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--n-400);
            margin-bottom: .75rem;
            padding-left: 2px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* garis setelah label */
        .sec-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--n-200);
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
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-100);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            /* gradient-nz sesuai tailwind.config */
            background: linear-gradient(90deg, var(--primary), var(--accent));
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
            /* gradient-nz sesuai tailwind.config */
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 6px 16px -3px rgba(23, 163, 74, .35);
            transition: box-shadow .22s, transform .22s;
        }

        .stat-card:hover .stat-icon {
            box-shadow: 0 8px 20px -3px rgba(23, 163, 74, .45);
            transform: scale(1.04);
        }

        .stat-body {
            flex: 1;
            min-width: 0;
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
            font-size: .73rem;
            font-weight: 600;
            color: var(--n-500);
        }

        .stat-badge {
            position: absolute;
            top: .85rem;
            right: .85rem;
            font-size: .60rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--primary-700);
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
            border-radius: 999px;
            padding: 2px 9px;
        }

        .stat-badge.--warn {
            color: #92400e;
            background: #fef3c7;
            border-color: #fde68a;
        }

        /* ── prevent horizontal scroll ── */
        .space-y-6 {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* ── PANEL ── */
        .panel {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--n-200);
            /* card shadow dari tailwind.config */
            box-shadow: 0 4px 14px 0 rgba(23, 163, 74, .08);
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
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--primary-700);
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
            border-radius: 999px;
            padding: 3px 10px;
        }

        .panel-body {
            padding: 1.25rem 1.5rem;
        }

        /* ── STATUS BADGE ── */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .04em;
            border-radius: 6px;
            padding: 3px 9px;
        }

        .badge-status.verified {
            color: #166534;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }

        .badge-status.pending {
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
        }

        .badge-status.menunggu {
            color: var(--primary-800);
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
        }

        .badge-status.ditolak {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        /* ── RIWAYAT TABLE ── */
        .riwayat-table {
            width: 100%;
            border-collapse: collapse;
        }

        .riwayat-table thead tr {
            border-bottom: 1px solid var(--n-200);
        }

        .riwayat-table th {
            padding: .55rem .9rem;
            text-align: left;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: var(--n-400);
        }

        .riwayat-table td {
            padding: .85rem .9rem;
            border-bottom: 1px solid var(--n-100);
            vertical-align: middle;
        }

        .riwayat-table tbody tr:last-child td {
            border-bottom: none;
        }

        .riwayat-table tbody tr {
            transition: background .15s;
        }

        .riwayat-table tbody tr:hover {
            background: var(--primary-50);
        }

        .trx-no {
            font-size: .78rem;
            font-weight: 600;
            color: var(--n-900);
        }

        .trx-sub {
            font-size: .70rem;
            font-weight: 500;
            color: var(--n-400);
            margin-top: 1px;
        }

        .trx-nominal {
            font-size: .83rem;
            font-weight: 700;
            color: var(--primary-700);
        }

        /* ── INFO MASJID ── */
        .lembaga-card {
            background: linear-gradient(135deg, var(--primary-50) 0%, #fff 100%);
            border: 1px solid var(--primary-100);
            border-radius: var(--radius-sm);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .lembaga-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 4px 12px -2px rgba(23, 163, 74, .30);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .lembaga-name {
            font-size: .9rem;
            font-weight: 700;
            color: var(--n-900);
        }

        .lembaga-loc {
            font-size: .72rem;
            font-weight: 500;
            color: var(--n-500);
            margin-top: 2px;
        }

        /* ── NISAB BLOCK ── */
        .nisab-block {
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
            border-radius: var(--radius-sm);
            padding: 1rem 1.25rem;
        }

        .nisab-label {
            font-size: .72rem;
            font-weight: 500;
            color: var(--n-500);
            margin-bottom: 4px;
        }

        .nisab-val {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-700);
            letter-spacing: -.03em;
            line-height: 1;
        }

        .nisab-sub {
            font-size: .70rem;
            font-weight: 500;
            color: var(--n-400);
            margin-top: 5px;
        }

        /* ── CTA BUTTON ── */
        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff;
            font-size: .8rem;
            font-weight: 600;
            padding: .6rem 1.25rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .2s;
            /* nz-shadow dari tailwind.config */
            box-shadow: 0 4px 6px -1px rgba(23, 163, 74, .25), 0 2px 4px -1px rgba(23, 163, 74, .15);
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(23, 163, 74, .30), 0 4px 6px -2px rgba(23, 163, 74, .15);
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--n-400);
        }

        .empty-state svg {
            width: 2.5rem;
            height: 2.5rem;
            margin: 0 auto .75rem;
            opacity: .4;
        }

        .empty-state p {
            font-size: .82rem;
            font-weight: 500;
        }

        /* ── QUICK ACTION ── */
        .qa-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }

        @media (max-width: 640px) {
            .qa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
            border-color: var(--primary-100);
            box-shadow: var(--shadow-md);
            background: var(--primary-50);
        }

        .qa-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.5rem;
            /* gradient-nz dari tailwind.config */
            background: linear-gradient(135deg, var(--primary-700), var(--accent));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px -2px rgba(23, 163, 74, .30);
            transition: box-shadow .2s, transform .2s;
        }

        .qa-item:hover .qa-icon {
            box-shadow: 0 6px 16px -2px rgba(23, 163, 74, .42);
            transform: scale(1.06);
        }

        .qa-label {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--n-700);
            line-height: 1.3;
        }

        /* ── PENDING ALERT ── */
        .pending-alert {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: var(--radius-sm);
            padding: .85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .8rem;
            color: #92400e;
            font-weight: 500;
        }

        .pending-alert svg {
            width: 1.1rem;
            height: 1.1rem;
            flex-shrink: 0;
        }

        /* ── METRIC ICON ── */
        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: var(--radius-sm);
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">

        {{-- ── HERO ── --}}
        <div class="hero">
            <div class="hero-decor"></div>
            <div class="hero-decor-2"></div>
            <div class="flex items-center justify-between relative" style="z-index:1">
                <div>
                    <div class="hero-pill">
                        <span class="hero-dot"></span>
                        Muzakki
                    </div>
                    <h1 class="hero-title">Assalamu'alaikum, {{ $muzakki->nama_singkat }}!</h1>
                    <p class="hero-sub">
                        Semoga zakat Anda menjadi berkah &mdash;
                        {{ $lembaga?->nama ?? 'Masjid belum dipilih' }}
                    </p>
                </div>
                <div class="hero-time hidden sm:block">
                    <p class="hero-date-lbl">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="hero-clock" id="liveTime">{{ now()->format('H:i:s') }}</p>
                </div>
            </div>
        </div>

        {{-- ── PENDING ALERT ── --}}
        @if ($stats['transaksi_pending'] > 0)
            <div class="pending-alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>
                    Anda memiliki <strong>{{ $stats['transaksi_pending'] }} transaksi</strong>
                    yang menunggu konfirmasi pembayaran dari amil.
                </span>
            </div>
        @endif

        {{-- ── STATS ── --}}
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp {{ number_format($stats['total_zakat_dibayar'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Total Zakat Dibayar</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val">{{ number_format($stats['total_transaksi']) }}</p>
                        <p class="stat-lbl">Total Transaksi</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp {{ number_format($stats['zakat_tahun_ini'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Zakat Tahun {{ now()->year }}</p>
                    </div>
                    <span class="stat-badge">{{ now()->year }}</span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val">{{ number_format($stats['transaksi_pending']) }}</p>
                        <p class="stat-lbl">Menunggu Konfirmasi</p>
                    </div>
                    @if ($stats['transaksi_pending'] > 0)
                        <span class="stat-badge --warn">Pending</span>
                    @endif
                </div>

            </div>
        </div>

        {{-- ── TRANSPARANSI LEMBAGA ── --}}
        @if (!empty($statsLembaga))
            <div>
                <div class="sec-label">Transparansi Lembaga &mdash; {{ $lembaga?->nama }}</div>

                {{-- 4 Metric Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="stat-body">
                            <p class="stat-val --money">Rp
                                {{ number_format($statsLembaga['total_penerimaan'], 0, ',', '.') }}</p>
                            <p class="stat-lbl">Total Penerimaan Lembaga</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon"
                            style="background:linear-gradient(135deg,#3b82f6,#60a5fa);box-shadow:0 6px 16px -3px rgba(59,130,246,.35);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="stat-body">
                            <p class="stat-val --money">Rp
                                {{ number_format($statsLembaga['total_penyaluran'], 0, ',', '.') }}</p>
                            <p class="stat-lbl">Total Disalurkan ke Mustahik</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="stat-body">
                            <p class="stat-val" style="color:var(--primary);font-size:1.5rem;">
                                {{ $statsLembaga['rasio_penyaluran'] }}%</p>
                            <p class="stat-lbl">Rasio Penyaluran</p>
                        </div>
                    </div>

                    <div class="stat-card" style="border-color:#fde68a;">
                        <div class="stat-icon"
                            style="background:linear-gradient(135deg,#f59e0b,#fbbf24);box-shadow:0 6px 16px -3px rgba(245,158,11,.35);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div class="stat-body">
                            <p class="stat-val --money">Rp {{ number_format($statsLembaga['saldo_kas'], 0, ',', '.') }}
                            </p>
                            <p class="stat-lbl">Saldo Kas Lembaga</p>
                        </div>
                    </div>
                </div>

                {{-- Panel detail --}}
                <div class="panel" style="margin-top:1.25rem;">
                    <div class="panel-head">
                        <p class="panel-title">Ringkasan Keuangan Lembaga</p>
                        <span class="panel-tag">Transparansi</span>
                    </div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Kiri: baris statistik --}}
                            <div>
                                @php
                                    $rows = [
                                        [
                                            'dot' => 'var(--primary)',
                                            'label' => 'Penerimaan bulan ini',
                                            'val' =>
                                                'Rp ' .
                                                number_format($statsLembaga['penerimaan_bulan_ini'], 0, ',', '.'),
                                        ],
                                        [
                                            'dot' => '#3b82f6',
                                            'label' => 'Penyaluran bulan ini',
                                            'val' =>
                                                'Rp ' .
                                                number_format($statsLembaga['penyaluran_bulan_ini'], 0, ',', '.'),
                                        ],
                                        [
                                            'dot' => 'var(--primary)',
                                            'label' => 'Donatur tercatat',
                                            'val' => $statsLembaga['total_muzakki'] . ' muzakki',
                                        ],
                                        [
                                            'dot' => '#3b82f6',
                                            'label' => 'Mustahik penerima manfaat',
                                            'val' => $statsLembaga['total_mustahik'] . ' orang',
                                        ],
                                        [
                                            'dot' => '#f59e0b',
                                            'label' => 'Program zakat aktif',
                                            'val' => $statsLembaga['program_aktif'] . ' program',
                                        ],
                                    ];
                                @endphp
                                @foreach ($rows as $row)
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;padding:.65rem 0;{{ !$loop->last ? 'border-bottom:1px solid var(--n-100);' : '' }}">
                                        <span style="font-size:.78rem;color:var(--n-500);">
                                            <span
                                                style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $row['dot'] }};margin-right:6px;vertical-align:middle;"></span>
                                            {{ $row['label'] }}
                                        </span>
                                        <span
                                            style="font-size:.78rem;font-weight:700;color:var(--n-900);">{{ $row['val'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Kanan: progress bar + catatan --}}
                            <div>
                                @php
                                    $pctBulanIni =
                                        $statsLembaga['total_penerimaan'] > 0
                                            ? round(
                                                ($statsLembaga['penerimaan_bulan_ini'] /
                                                    $statsLembaga['total_penerimaan']) *
                                                    100,
                                                1,
                                            )
                                            : 0;
                                @endphp

                                {{-- Rasio penyaluran --}}
                                <div style="margin-bottom:1rem;">
                                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                        <span style="font-size:.73rem;font-weight:600;color:var(--primary-800);">Penyaluran
                                            dan Penerimaan</span>
                                        <span
                                            style="font-size:.73rem;font-weight:700;color:var(--primary-800);">{{ $statsLembaga['rasio_penyaluran'] }}%</span>
                                    </div>
                                    <div style="height:8px;background:var(--n-100);border-radius:999px;overflow:hidden;">
                                        <div
                                            style="height:100%;width:{{ min($statsLembaga['rasio_penyaluran'], 100) }}%;background:linear-gradient(90deg,var(--primary),var(--accent));border-radius:999px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Porsi bulan ini --}}
                                <div style="margin-bottom:1.25rem;">
                                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                        <span style="font-size:.73rem;font-weight:600;color:#1d4ed8;">Porsi penerimaan
                                            bulan ini</span>
                                        <span
                                            style="font-size:.73rem;font-weight:700;color:#1d4ed8;">{{ $pctBulanIni }}%</span>
                                    </div>
                                    <div style="height:8px;background:var(--n-100);border-radius:999px;overflow:hidden;">
                                        <div
                                            style="height:100%;width:{{ min($pctBulanIni, 100) }}%;background:linear-gradient(90deg,#3b82f6,#60a5fa);border-radius:999px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Info note --}}
                                <div
                                    style="background:var(--primary-50);border:1px solid var(--primary-100);border-radius:var(--radius-sm);padding:.85rem 1rem;display:flex;gap:.6rem;align-items:flex-start;">
                                    <svg style="width:15px;height:15px;flex-shrink:0;margin-top:2px;color:var(--primary);"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p style="font-size:.71rem;color:var(--primary-800);line-height:1.55;margin:0;">
                                        Data ini disajikan secara transparan untuk semua muzakki terdaftar di
                                        <strong>{{ $lembaga?->nama }}</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── CHART + INFO MASJID ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Chart Trend --}}
            <div class="panel lg:col-span-2">
                <div class="panel-head">
                    <p class="panel-title">Riwayat Zakat Saya</p>
                    <span class="panel-tag">6 Bulan Terakhir</span>
                </div>
                <div class="panel-body">
                    <div style="height: 280px; position: relative;">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>
            </div>

            {{-- Info Masjid + Nisab --}}
            <div class="space-y-4">

                {{-- Masjid --}}
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Masjid Saya</p>
                    </div>
                    <div class="panel-body">
                        @if ($lembaga)
                            <div class="lembaga-card">
                                <div class="lembaga-icon">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="lembaga-name">{{ $lembaga->nama }}</p>
                                    <p class="lembaga-loc">
                                        {{ $lembaga->kecamatan_nama }}, {{ $lembaga->kota_nama }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p style="font-size:.8rem; color:var(--n-400); text-align:center; padding:.5rem 0;">
                                Belum memilih lembaga
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Nisab --}}
                @if ($hargaTerkini)
                    <div class="panel">
                        <div class="panel-head">
                            <p class="panel-title">Info Nisab Terkini</p>
                            <span class="panel-tag">Emas</span>
                        </div>
                        <div class="panel-body space-y-3">
                            <div class="nisab-block">
                                <p class="nisab-label">Harga Emas / gram</p>
                                <p class="nisab-val">Rp
                                    {{ number_format($hargaTerkini->harga_emas_pergram, 0, ',', '.') }}</p>
                                <p class="nisab-sub">
                                    Nisab 85 gram = Rp
                                    {{ number_format($hargaTerkini->harga_emas_pergram * 85, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- ── RIWAYAT TRANSAKSI TERBARU ── --}}
        <div>
            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">5 Transaksi Terakhir</p>
                    <a href="riwayat-transaksi-muzakki"
                        style="font-size:.75rem; font-weight:600; color:var(--primary); text-decoration:none;">
                        Lihat Semua →
                    </a>
                </div>

                @if ($riwayatTerbaru->count() > 0)
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Jenis Zakat</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatTerbaru as $trx)
                                <tr>
                                    <td>
                                        <p class="trx-no">{{ $trx->no_transaksi }}</p>
                                    </td>
                                    <td>
                                        <p class="trx-sub" style="color:var(--n-700); font-weight:600;">
                                            {{ $trx->jenisZakat?->nama ?? '-' }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="trx-sub" style="color:var(--n-700);">
                                            @switch($trx->metode_penerimaan)
                                                @case('daring')
                                                    Transfer Online
                                                @break

                                                @case('dijemput')
                                                    Dijemput Amil
                                                @break

                                                @case('datang_langsung')
                                                    Datang Langsung
                                                @break

                                                @default
                                                    {{ $trx->metode_penerimaan }}
                                            @endswitch
                                        </p>
                                    </td>
                                    <td>
                                        <p class="trx-nominal">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <p class="trx-sub" style="color:var(--n-700);">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->translatedFormat('d M Y') }}
                                        </p>
                                    </td>
                                    <td>
                                        @switch($trx->status)
                                            @case('verified')
                                                <span class="badge-status verified">Terverifikasi</span>
                                            @break

                                            @case('pending')
                                                <span class="badge-status pending">Pending</span>
                                            @break

                                            @case('menunggu_konfirmasi')
                                                <span class="badge-status menunggu">Menunggu</span>
                                            @break

                                            @case('ditolak')
                                                <span class="badge-status ditolak">Ditolak</span>
                                            @break

                                            @default
                                                <span class="badge-status pending">{{ $trx->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Belum ada transaksi zakat</p>
                        <a href="" class="btn-cta" style="margin-top:1rem;">
                            Bayar Zakat Sekarang
                        </a>
                    </div>
                @endif
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

            // Chart defaults
            Chart.defaults.font.family = "'Poppins', sans-serif";
            Chart.defaults.font.size = 11;
            Chart.defaults.font.weight = '500';

            const C800 = '#17a34a';
            const C400 = '#4caf50';
            const GRID = '#f3f4f6';
            const TICK = '#9ca3af';

            const tooltipCfg = {
                backgroundColor: '#111827',
                titleColor: '#ffffff',
                bodyColor: 'rgba(255,255,255,.65)',
                borderColor: 'rgba(23,163,74,.25)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
                titleFont: {
                    family: "'Poppins',sans-serif",
                    weight: '700',
                    size: 12
                },
                bodyFont: {
                    family: "'Poppins',sans-serif",
                    size: 11
                },
                callbacks: {
                    label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            };

            // Chart Trend Zakat Muzakki
            const trendData = @json($trendZakat);
            if (trendData.length > 0) {
                const ctx = document.getElementById('chartTrend').getContext('2d');

                const grad = ctx.createLinearGradient(0, 0, 0, 280);
                grad.addColorStop(0, 'rgba(23,163,74,.18)');
                grad.addColorStop(1, 'rgba(23,163,74,0)');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: trendData.map(i => i.bulan),
                        datasets: [{
                            label: 'Zakat Dibayar',
                            data: trendData.map(i => i.jumlah),
                            backgroundColor: trendData.map(i =>
                                i.jumlah > 0 ? 'rgba(23,163,74,.85)' : 'rgba(23,163,74,.12)'
                            ),
                            borderColor: C800,
                            borderWidth: 1.5,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: tooltipCfg,
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: TICK
                                }
                            },
                            y: {
                                grid: {
                                    color: GRID
                                },
                                beginAtZero: true,
                                ticks: {
                                    color: TICK,
                                    callback: value => value === 0 ? '0' : 'Rp ' + (value >= 1000000 ?
                                        (value / 1000000).toLocaleString('id-ID') + ' jt' :
                                        value.toLocaleString('id-ID'))
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
