@extends('layouts.app')

@section('title', 'Dashboard Admin Masjid')

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

        .metric-card.--gold .metric-icon {
            background: var(--gold-bg);
            border-color: var(--gold-border);
        }

        .metric-card.--gold .metric-val {
            color: var(--gold);
            font-size: 1.05rem;
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

        /* ── NISAB CARD ── */
        .nisab-block {
            background: var(--c-50);
            border: 1px solid var(--c-100);
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
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--c-700);
            letter-spacing: -.03em;
            line-height: 1;
        }

        .nisab-sub {
            font-size: .70rem;
            font-weight: 500;
            color: var(--n-400);
            margin-top: 5px;
        }

        /* ── LIST ITEM ── */
        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 0;
            border-bottom: 1px solid var(--n-100);
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-name {
            font-size: .83rem;
            font-weight: 600;
            color: var(--n-900);
        }

        .list-sub {
            font-size: .70rem;
            font-weight: 500;
            color: var(--n-400);
            margin-top: 2px;
        }

        .list-badge {
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .04em;
            color: var(--c-700);
            background: var(--c-50);
            border: 1px solid var(--c-100);
            border-radius: 6px;
            padding: 3px 9px;
            white-space: nowrap;
        }

        .list-badge.--pct {
            color: var(--gold);
            background: var(--gold-bg);
            border-color: var(--gold-border);
        }

        /* ── APPROVAL TABLE ── */
        .apv-table {
            width: 100%;
            border-collapse: collapse;
        }

        .apv-table thead tr {
            border-bottom: 1px solid var(--n-200);
        }

        .apv-table th {
            padding: .55rem .9rem;
            text-align: left;
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: var(--n-400);
        }

        .apv-table td {
            padding: .85rem .9rem;
            border-bottom: 1px solid var(--n-100);
            vertical-align: middle;
        }

        .apv-table tbody tr:last-child td {
            border-bottom: none;
        }

        .apv-table tbody tr {
            transition: background .15s;
        }

        .apv-table tbody tr:hover {
            background: var(--n-50);
        }

        .apv-name {
            font-size: .83rem;
            font-weight: 600;
            color: var(--n-900);
        }

        .apv-sub {
            font-size: .70rem;
            font-weight: 500;
            color: var(--n-400);
            margin-top: 1px;
        }

        .apv-nominal {
            font-size: .83rem;
            font-weight: 700;
            color: var(--c-700);
        }

        /* ── WARNING ALERT (pending badge) ── */
        .badge-warn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 999px;
            padding: 2px 9px;
        }

        .badge-warn-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #f59e0b;
            flex-shrink: 0;
        }

        /* ── ACTION BUTTONS ── */
        .btn-approve {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .68rem;
            font-weight: 700;
            color: var(--c-700);
            background: var(--c-50);
            border: 1px solid var(--c-100);
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-approve:hover {
            background: var(--c-100);
            border-color: var(--c-400);
        }

        .btn-reject {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .68rem;
            font-weight: 700;
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-reject:hover {
            background: #fee2e2;
        }

        /* ── SUMMARY CHIP (panel-head info) ── */
        .apv-summary {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .apv-count {
            font-size: .64rem;
            font-weight: 700;
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 999px;
            padding: 2px 9px;
        }

        .panel-tag-link {
            font-size: .75rem;
            font-weight: 600;
            color: var(--c-700);
            text-decoration: none;
            transition: color .15s;
        }

        .panel-tag-link:hover {
            color: var(--c-600);
            text-decoration: underline;
        }

        /* ── MODAL REJECT ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: var(--radius);
            padding: 1.75rem;
            width: 100%;
            max-width: 440px;
            box-shadow: var(--shadow-lg);
            margin: 1rem;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--n-900);
            margin-bottom: .35rem;
        }

        .modal-sub {
            font-size: .78rem;
            color: var(--n-500);
            margin-bottom: 1.1rem;
        }

        .modal-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--n-700);
            margin-bottom: .35rem;
            display: block;
        }

        .modal-textarea {
            width: 100%;
            padding: .65rem .85rem;
            border: 1px solid var(--n-200);
            border-radius: var(--radius-sm);
            font-size: .82rem;
            color: var(--n-900);
            resize: vertical;
            min-height: 90px;
            font-family: inherit;
            transition: border .15s;
            outline: none;
        }

        .modal-textarea:focus {
            border-color: var(--c-400);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 1rem;
        }

        .modal-cancel {
            padding: .5rem 1.1rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--n-200);
            background: #fff;
            font-size: .78rem;
            font-weight: 600;
            color: var(--n-500);
            cursor: pointer;
            transition: all .15s;
            font-family: inherit;
        }

        .modal-cancel:hover {
            background: var(--n-100);
        }

        .modal-submit-reject {
            padding: .5rem 1.1rem;
            border-radius: var(--radius-sm);
            border: none;
            background: #dc2626;
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: all .15s;
            font-family: inherit;
        }

        .modal-submit-reject:hover {
            background: #b91c1c;
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
                        Admin Masjid
                    </div>
                    <h1 class="hero-title">{{ $masjid->nama }}</h1>
                    <p class="hero-sub">Kode: {{ $masjid->kode_masjid }} &mdash; Kelola zakat digital dengan efisien</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp
                            {{ number_format($stats['total_penerimaan_bulan_ini'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Penerimaan Bulan Ini</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp
                            {{ number_format($stats['total_penyaluran_bulan_ini'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Penyaluran Bulan Ini</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <p class="stat-val --money">Rp {{ number_format($stats['saldo_zakat'], 0, ',', '.') }}</p>
                        <p class="stat-lbl">Saldo Saat Ini</p>
                    </div>
                    <span class="stat-badge">Kas</span>
                </div>

            </div>
        </div>

        {{-- Secondary Metrics --}}
        <div>
            <p class="sec-label">Statistik SDM</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="metric-card">
                    <div class="metric-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color:var(--c-700)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="metric-val">{{ number_format($stats['jumlah_muzakki']) }}</p>
                        <p class="metric-lbl">Total Muzakki</p>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color:var(--c-700)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="metric-val">{{ number_format($stats['jumlah_mustahik']) }}</p>
                        <p class="metric-lbl">Total Mustahik</p>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color:var(--c-700)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="metric-val">{{ number_format($stats['total_amil']) }}</p>
                        <p class="metric-lbl">Total Amil</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Approval Penyaluran --}}
        @if ($totalPendingApproval > 0)
            <div>
                <p class="sec-label">Perlu Persetujuan</p>
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Transaksi Penyaluran Menunggu Approval</p>
                        <div class="apv-summary">
                            <span class="apv-count">{{ $totalPendingApproval }} pending</span>
                            <a href="{{ route('transaksi-penyaluran.index') }}" class="panel-tag-link">Lihat Semua →</a>
                        </div>
                    </div>

                    {{-- Summary chips --}}
                    <div
                        style="padding: .9rem 1.5rem; border-bottom: 1px solid var(--n-100); background: #fffbeb; display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;">
                        <div class="badge-warn">
                            <span class="badge-warn-dot"></span>
                            {{ $totalPendingApproval }} transaksi menunggu
                        </div>
                        <span style="font-size:.72rem; font-weight:500; color:var(--n-500);">
                            Total nominal:
                            <strong style="color:#92400e;">Rp
                                {{ number_format($totalNominalPending, 0, ',', '.') }}</strong>
                        </span>
                    </div>

                    <table class="apv-table">
                        <thead>
                            <tr>
                                <th>Mustahik / Program</th>
                                <th>Amil</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyaluranPendingApproval as $trx)
                                <tr>
                                    <td>
                                        <p class="apv-name">
                                            {{ $trx->mustahik?->nama_lengkap ?? 'Mustahik tidak tersedia' }}
                                        </p>
                                        <p class="apv-sub">
                                            {{ $trx->no_transaksi }}
                                            @if ($trx->kategoriMustahik)
                                                &middot; {{ $trx->kategoriMustahik->nama }}
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <p class="apv-sub" style="color:var(--n-700); font-weight:600;">
                                            {{ $trx->amil?->nama_lengkap ?? '-' }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="apv-nominal">
                                            Rp
                                            {{ number_format(
                                                $trx->metode_penyaluran === 'barang' ? $trx->nilai_barang ?? 0 : $trx->jumlah ?? 0,
                                                0,
                                                ',',
                                                '.',
                                            ) }}
                                        </p>
                                        @if ($trx->metode_penyaluran === 'barang')
                                            <p class="apv-sub">Barang</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="apv-sub" style="color:var(--n-700);">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_penyaluran)->translatedFormat('d M Y') }}
                                        </p>
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:6px; align-items:center;">
                                            {{-- Approve --}}
                                            <form action="{{ route('transaksi-penyaluran.approve', $trx) }}"
                                                method="POST"
                                                onsubmit="return confirm('Setujui transaksi {{ $trx->no_transaksi }}?')">
                                                @csrf
                                                <button type="submit" class="btn-approve">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>

                                            {{-- Reject — opens modal --}}
                                            <button type="button" class="btn-reject"
                                                onclick="openRejectModal('{{ $trx->no_transaksi }}', '{{ route('transaksi-penyaluran.reject', $trx) }}')">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($totalPendingApproval > 10)
                        <div style="padding: .75rem 1.5rem; border-top: 1px solid var(--n-100); text-align: center;">
                            <a href="{{ route('transaksi-penyaluran.index') }}" class="panel-tag-link">
                                + {{ $totalPendingApproval - 10 }} transaksi lainnya menunggu persetujuan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Modal Reject --}}
        <div class="modal-overlay" id="rejectModal">
            <div class="modal-box">
                <p class="modal-title">Tolak Transaksi Penyaluran</p>
                <p class="modal-sub" id="rejectModalSub">Berikan alasan penolakan untuk transaksi ini.</p>
                <form id="rejectForm" method="POST">
                    @csrf
                    <label class="modal-label" for="alasan_pembatalan">Alasan Penolakan <span
                            style="color:#ef4444">*</span></label>
                    <textarea name="alasan_pembatalan" id="alasan_pembatalan" class="modal-textarea"
                        placeholder="Tulis alasan penolakan transaksi ini..." required maxlength="500"></textarea>
                    <p style="font-size:.68rem; color:var(--n-400); margin-top: 4px;" id="charCount">0 / 500 karakter</p>
                    <div class="modal-footer">
                        <button type="button" class="modal-cancel" onclick="closeRejectModal()">Batal</button>
                        <button type="submit" class="modal-submit-reject">Tolak Transaksi</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Chart --}}
        @if ($trendPenerimaan->count() > 0)
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


        {{-- Harga Nisab --}}
        @if ($hargaTerkini)
            <div>
                <p class="sec-label">Harga Nisab Terkini</p>
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Referensi Harga Emas & Perak</p>
                        <span class="panel-tag">Live Update</span>
                    </div>
                    <div class="panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="nisab-block">
                                <p class="nisab-label">Harga Emas / gram</p>
                                <p class="nisab-val">Rp
                                    {{ number_format($hargaTerkini->harga_emas_pergram, 0, ',', '.') }}</p>
                                <p class="nisab-sub">Nisab 85 gram &rarr; Rp
                                    {{ number_format($hargaTerkini->harga_emas_pergram * 85, 0, ',', '.') }}</p>
                            </div>
                            <div class="nisab-block">
                                <p class="nisab-label">Harga Perak / gram</p>
                                <p class="nisab-val">Rp
                                    {{ number_format($hargaTerkini->harga_perak_pergram, 0, ',', '.') }}</p>
                                <p class="nisab-sub">Nisab 595 gram &rarr; Rp
                                    {{ number_format($hargaTerkini->harga_perak_pergram * 595, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Jenis Zakat & Kategori Mustahik --}}
        <div>
            <p class="sec-label">Master Data</p>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Jenis Zakat --}}
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Jenis Zakat Aktif</p>
                        <span class="panel-tag">{{ $jenisZakatAktif->count() }} jenis</span>
                    </div>
                    <div class="panel-body">
                        @forelse($jenisZakatAktif as $jz)
                            <div class="list-item">
                                <div>
                                    <p class="list-name">{{ $jz->nama }}</p>
                                    <p class="list-sub">{{ $jz->kode }}</p>
                                </div>
                                @if ($jz->nominal_minimal)
                                    <span class="list-badge">Min Rp
                                        {{ number_format($jz->nominal_minimal, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        @empty
                            <p
                                style="text-align:center; padding: 2rem 0; color: var(--n-400); font-size:.82rem; font-weight:500;">
                                Belum ada jenis zakat
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Kategori Mustahik --}}
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Kategori Mustahik</p>
                        <span class="panel-tag">{{ $kategoriMustahik->count() }} kategori</span>
                    </div>
                    <div class="panel-body">
                        @forelse($kategoriMustahik as $km)
                            <div class="list-item">
                                <div>
                                    <p class="list-name">{{ $km->nama }}</p>
                                    <p class="list-sub">{{ $km->kode }}</p>
                                </div>
                                @if ($km->persentase_default)
                                    <span class="list-badge --pct">{{ $km->persentase_default }}%</span>
                                @endif
                            </div>
                        @empty
                            <p
                                style="text-align:center; padding: 2rem 0; color: var(--n-400); font-size:.82rem; font-weight:500;">
                                Belum ada kategori mustahik
                            </p>
                        @endforelse
                    </div>
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
                titleFont: {
                    family: "'Poppins', sans-serif",
                    weight: '700',
                    size: 12
                },
                bodyFont: {
                    family: "'Poppins', sans-serif",
                    size: 11
                },
                callbacks: {
                    label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            };

            // Chart Trend
            const trendData = @json($trendPenerimaan);
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
                            },
                            {
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
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: tooltipCfg,
                            legend: {
                                labels: {
                                    color: TICK,
                                    font: {
                                        family: "'Poppins', sans-serif",
                                        size: 11,
                                        weight: '600'
                                    },
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 20
                                }
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
                                    callback: value => 'Rp ' + (value / 1000000 >= 1 ?
                                        (value / 1000000).toLocaleString('id-ID') + ' jt' :
                                        value.toLocaleString('id-ID'))
                                }
                            }
                        }
                    }
                });
            }
        });

        // ── Reject Modal ─────────────────────────────────────────────────────────
        function openRejectModal(noTrx, actionUrl) {
            document.getElementById('rejectModalSub').textContent =
                'Berikan alasan penolakan untuk transaksi ' + noTrx + '.';
            document.getElementById('rejectForm').action = actionUrl;
            document.getElementById('alasan_pembatalan').value = '';
            document.getElementById('charCount').textContent = '0 / 500 karakter';
            document.getElementById('rejectModal').classList.add('active');
            setTimeout(() => document.getElementById('alasan_pembatalan').focus(), 80);
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.remove('active');
        }

        // Char counter
        document.getElementById('alasan_pembatalan')?.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length + ' / 500 karakter';
        });

        // Close modal on overlay click
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });

        // Close modal on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRejectModal();
        });
    </script>
@endpush
