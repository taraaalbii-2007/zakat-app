@extends('layouts.app')

@section('title', 'Dashboard Muzakki')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif !important; }

    :root {
        --c-900: #1a3d1a;
        --c-800: #2d6a2d;
        --c-700: #388e3c;
        --c-600: #43a047;
        --c-400: #66bb6a;
        --c-100: #e8f5e9;
        --c-50:  #f1f8f1;
        --n-900: #111827;
        --n-700: #374151;
        --n-500: #6b7280;
        --n-400: #9ca3af;
        --n-200: #e5e7eb;
        --n-100: #f3f4f6;
        --n-50:  #f9fafb;
        --white: #ffffff;
        --radius:    16px;
        --radius-sm: 10px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md: 0 4px 16px -2px rgba(45,106,45,.10), 0 2px 6px -1px rgba(45,106,45,.06);
        --shadow-lg: 0 12px 32px -6px rgba(45,106,45,.18), 0 4px 12px -2px rgba(45,106,45,.10);
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
        right: -48px; top: -64px;
        width: 260px; height: 260px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.06);
        pointer-events: none;
    }

    .hero-decor::after {
        content: '';
        position: absolute;
        inset: 30px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.04);
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 999px;
        padding: 4px 14px 4px 9px;
        font-size: .68rem;
        font-weight: 600;
        color: rgba(255,255,255,.80);
        letter-spacing: .07em;
        text-transform: uppercase;
        margin-bottom: .85rem;
    }

    .hero-dot {
        width: 7px; height: 7px;
        background: #66bb6a;
        border-radius: 50%;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%,100% { opacity: 1 }
        50%      { opacity: .3 }
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
        color: rgba(255,255,255,.55);
    }

    .hero-time { text-align: right; position: relative; z-index: 1; }
    .hero-date-lbl { font-size: .72rem; font-weight: 500; color: rgba(255,255,255,.50); margin-bottom: 2px; }
    .hero-clock { font-size: 1.6rem; font-weight: 700; color: #fff; letter-spacing: .02em; }

    /* ── SEC LABEL ── */
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
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--c-800), var(--c-400));
        opacity: 0;
        transition: opacity .22s;
    }

    .stat-card:hover::after { opacity: 1; }

    .stat-icon {
        width: 52px; height: 52px;
        flex-shrink: 0;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--c-800), var(--c-400));
        box-shadow: 0 4px 12px -2px rgba(45,106,45,.35);
    }

    .stat-body { flex: 1; min-width: 0; }

    .stat-val {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--n-900);
        letter-spacing: -.04em;
        line-height: 1;
        margin-bottom: .2rem;
    }

    .stat-val.--money { font-size: 1.15rem; }

    .stat-lbl {
        font-size: .75rem;
        font-weight: 600;
        color: var(--n-500);
    }

    .stat-badge {
        position: absolute;
        top: .85rem; right: .85rem;
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--c-800);
        background: var(--c-50);
        border: 1px solid var(--c-100);
        border-radius: 999px;
        padding: 2px 8px;
    }

    .stat-badge.--warn { color: #92400e; background: #fef3c7; border-color: #fde68a; }

    /* ── prevent horizontal scroll ── */
    .space-y-6 { max-width: 100%; overflow-x: hidden; }

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

    .panel-title { font-size: .875rem; font-weight: 700; color: var(--n-900); letter-spacing: -.01em; }

    .panel-tag {
        font-size: .64rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--c-800);
        background: var(--c-50);
        border: 1px solid var(--c-100);
        border-radius: 999px;
        padding: 3px 10px;
    }

    .panel-body { padding: 1.25rem 1.5rem; }

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

    .badge-status.verified   { color: #166534; background: #dcfce7; border: 1px solid #bbf7d0; }
    .badge-status.pending    { color: #92400e; background: #fef3c7; border: 1px solid #fde68a; }
    .badge-status.menunggu   { color: #1a3d1a; background: #e8f5e9; border: 1px solid #c8e6c9; }
    .badge-status.ditolak    { color: #991b1b; background: #fee2e2; border: 1px solid #fecaca; }

    /* ── RIWAYAT TABLE ── */
    .riwayat-table { width: 100%; border-collapse: collapse; }
    .riwayat-table thead tr { border-bottom: 1px solid var(--n-200); }
    .riwayat-table th {
        padding: .55rem .9rem;
        text-align: left;
        font-size: .64rem;
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
    .riwayat-table tbody tr:last-child td { border-bottom: none; }
    .riwayat-table tbody tr { transition: background .15s; }
    .riwayat-table tbody tr:hover { background: var(--n-50); }

    .trx-no      { font-size: .78rem; font-weight: 600; color: var(--n-900); }
    .trx-sub     { font-size: .70rem; font-weight: 500; color: var(--n-400); margin-top: 1px; }
    .trx-nominal { font-size: .83rem; font-weight: 700; color: var(--c-800); }

    /* ── INFO MASJID ── */
    .masjid-card {
        background: linear-gradient(135deg, var(--c-50) 0%, #fff 100%);
        border: 1px solid var(--c-100);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .masjid-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--c-800), var(--c-400));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .masjid-name { font-size: .9rem; font-weight: 700; color: var(--n-900); }
    .masjid-loc  { font-size: .72rem; font-weight: 500; color: var(--n-500); margin-top: 2px; }

    /* ── NISAB BLOCK ── */
    .nisab-block {
        background: var(--c-50);
        border: 1px solid var(--c-100);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
    }

    .nisab-label { font-size: .72rem; font-weight: 500; color: var(--n-500); margin-bottom: 4px; }
    .nisab-val   { font-size: 1.25rem; font-weight: 800; color: var(--c-800); letter-spacing: -.03em; line-height: 1; }
    .nisab-sub   { font-size: .70rem; font-weight: 500; color: var(--n-400); margin-top: 5px; }

    /* ── CTA BUTTON ── */
    .btn-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, var(--c-800), var(--c-400));
        color: #fff;
        font-size: .8rem;
        font-weight: 600;
        padding: .6rem 1.25rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 12px rgba(45,106,45,.3);
    }

    .btn-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(45,106,45,.4);
    }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--n-400);
    }

    .empty-state svg { width: 2.5rem; height: 2.5rem; margin: 0 auto .75rem; opacity: .4; }
    .empty-state p   { font-size: .82rem; font-weight: 500; }

    /* ── QUICK ACTION ── */
    .qa-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    @media (max-width: 640px) {
        .qa-grid { grid-template-columns: repeat(2, 1fr); }
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
        width: 40px; height: 40px;
        margin: 0 auto 0.5rem;
        background: linear-gradient(135deg, var(--c-800), var(--c-400));
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px -2px rgba(45,106,45,.30);
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

    .pending-alert svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; }

    /* ── METRIC CARD ── */
    .metric-icon {
        width: 46px; height: 46px;
        border-radius: var(--radius-sm);
        background: var(--c-50);
        border: 1px solid var(--c-100);
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
        <div class="flex items-center justify-between relative" style="z-index:1">
            <div>
                <div class="hero-pill">
                    <span class="hero-dot"></span>
                    Muzakki
                </div>
                <h1 class="hero-title">Assalamu'alaikum, {{ $muzakki->nama_singkat }}!</h1>
                <p class="hero-sub">
                    Semoga zakat Anda menjadi berkah &mdash;
                    {{ $masjid?->nama ?? 'Masjid belum dipilih' }}
                </p>
            </div>
            <div class="hero-time hidden sm:block">
                <p class="hero-date-lbl">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="hero-clock" id="liveTime">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
    </div>

    {{-- ── PENDING ALERT ── --}}
    @if($stats['transaksi_pending'] > 0)
    <div class="pending-alert">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>
            Anda memiliki <strong>{{ $stats['transaksi_pending'] }} transaksi</strong>
            yang menunggu konfirmasi pembayaran dari amil.
        </span>
    </div>
    @endif

    {{-- ── STATS ── --}}
    <div>
        <p class="sec-label">Ringkasan Zakat Anda</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p class="stat-val --money">Rp {{ number_format($stats['total_zakat_dibayar'], 0, ',', '.') }}</p>
                    <p class="stat-lbl">Total Zakat Dibayar</p>
                </div>
                <span class="stat-badge">All Time</span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
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
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-body">
                    <p class="stat-val">{{ number_format($stats['transaksi_pending']) }}</p>
                    <p class="stat-lbl">Menunggu Konfirmasi</p>
                </div>
                @if($stats['transaksi_pending'] > 0)
                <span class="stat-badge --warn">Pending</span>
                @endif
            </div>

        </div>
    </div>

    {{-- ── QUICK ACTIONS ── --}}
    <div>
        <p class="sec-label">Aksi Cepat</p>
        <div class="panel">
            <div class="panel-head">
                <p class="panel-title">Aksi Cepat</p>
                <span class="panel-tag">Menu</span>
            </div>
            <div class="panel-body">
                <div class="qa-grid">

                    <a href="" class="qa-item">
                        <div class="qa-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="qa-label">Bayar Zakat</span>
                    </a>

                    <a href="" class="qa-item">
                        <div class="qa-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="qa-label">Minta Jemput</span>
                    </a>

                    <a href="" class="qa-item">
                        <div class="qa-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="qa-label">Riwayat Transaksi</span>
                    </a>

                    <a href="" class="qa-item">
                        <div class="qa-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="qa-label">Profil Saya</span>
                    </a>

                </div>
            </div>
        </div>
    </div>

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
                    @if($masjid)
                    <div class="masjid-card">
                        <div class="masjid-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="masjid-name">{{ $masjid->nama }}</p>
                            <p class="masjid-loc">
                                {{ $masjid->kecamatan_nama }}, {{ $masjid->kota_nama }}
                            </p>
                        </div>
                    </div>
                    @else
                    <p style="font-size:.8rem; color:var(--n-400); text-align:center; padding:.5rem 0;">
                        Belum memilih masjid
                    </p>
                    @endif
                </div>
            </div>

            {{-- Nisab --}}
            @if($hargaTerkini)
            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Info Nisab Terkini</p>
                    <span class="panel-tag">Emas</span>
                </div>
                <div class="panel-body space-y-3">
                    <div class="nisab-block">
                        <p class="nisab-label">Harga Emas / gram</p>
                        <p class="nisab-val">Rp {{ number_format($hargaTerkini->harga_emas_pergram, 0, ',', '.') }}</p>
                        <p class="nisab-sub">
                            Nisab 85 gram = Rp {{ number_format($hargaTerkini->harga_emas_pergram * 85, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ── RIWAYAT TRANSAKSI TERBARU ── --}}
    <div>
        <p class="sec-label">Transaksi Terbaru</p>
        <div class="panel">
            <div class="panel-head">
                <p class="panel-title">5 Transaksi Terakhir</p>
                <a href=""
                   style="font-size:.75rem; font-weight:600; color:var(--c-800); text-decoration:none;">
                    Lihat Semua →
                </a>
            </div>

            @if($riwayatTerbaru->count() > 0)
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
                    @foreach($riwayatTerbaru as $trx)
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
                                    @case('daring')     Transfer Online @break
                                    @case('dijemput')   Dijemput Amil   @break
                                    @case('datang_langsung') Datang Langsung @break
                                    @default {{ $trx->metode_penerimaan }}
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
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
document.addEventListener('DOMContentLoaded', function () {

    // Live Clock
    const clockEl = document.getElementById('liveTime');
    if (clockEl) {
        setInterval(() => {
            clockEl.textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);
    }

    // Chart defaults
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.font.weight = '500';

    const C800  = '#2d6a2d';
    const C400  = '#66bb6a';
    const GRID  = '#f3f4f6';
    const TICK  = '#9ca3af';

    const tooltipCfg = {
        backgroundColor: '#111827',
        titleColor:  '#ffffff',
        bodyColor:   'rgba(255,255,255,.65)',
        borderColor: 'rgba(102,187,106,.25)',
        borderWidth: 1,
        padding: 10,
        cornerRadius: 8,
        titleFont: { family: "'Poppins',sans-serif", weight: '700', size: 12 },
        bodyFont:  { family: "'Poppins',sans-serif", size: 11 },
        callbacks: {
            label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
        }
    };

    // Chart Trend Zakat Muzakki
    const trendData = @json($trendZakat);
    if (trendData.length > 0) {
        const ctx = document.getElementById('chartTrend').getContext('2d');

        const grad = ctx.createLinearGradient(0, 0, 0, 280);
        grad.addColorStop(0, 'rgba(45,106,45,.18)');
        grad.addColorStop(1, 'rgba(45,106,45,0)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trendData.map(i => i.bulan),
                datasets: [{
                    label: 'Zakat Dibayar',
                    data: trendData.map(i => i.jumlah),
                    backgroundColor: trendData.map(i =>
                        i.jumlah > 0 ? 'rgba(45,106,45,.85)' : 'rgba(45,106,45,.12)'
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
                    legend: { display: false }
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
                            callback: value => value === 0 ? '0' :
                                'Rp ' + (value >= 1000000
                                    ? (value / 1000000).toLocaleString('id-ID') + ' jt'
                                    : value.toLocaleString('id-ID'))
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush