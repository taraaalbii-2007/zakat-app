@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

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
        --c-50:  #f3faf0;
        --gold:  #b8860b;
        --gold-bg: #fdf6e3;
        --gold-border: #e8d5a0;
        --n-900: #111827;
        --n-700: #374151;
        --n-500: #6b7280;
        --n-400: #9ca3af;
        --n-200: #e5e7eb;
        --n-100: #f3f4f6;
        --n-50:  #f9fafb;
        --white: #ffffff;
        --radius: 16px;
        --radius-sm: 10px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 16px -2px rgba(26,61,34,0.10), 0 2px 6px -1px rgba(26,61,34,0.06);
        --shadow-lg: 0 12px 32px -6px rgba(26,61,34,0.18), 0 4px 12px -2px rgba(26,61,34,0.10);
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
    .hero::before { display: none; }
    .hero-decor {
        position: absolute; right: -48px; top: -64px;
        width: 260px; height: 260px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.06);
        pointer-events: none;
    }
    .hero-decor::after {
        content: '';
        position: absolute; inset: 30px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.04);
    }
    .hero-pill {
        display: inline-flex;
        align-items: center; gap: 7px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 999px;
        padding: 4px 14px 4px 9px;
        font-size: .68rem; font-weight: 600;
        color: rgba(255,255,255,.80);
        letter-spacing: .07em; text-transform: uppercase;
        margin-bottom: .85rem;
    }
    .hero-dot {
        width: 7px; height: 7px;
        background: #7cb342; border-radius: 50%;
        animation: blink 2s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
    .hero-title {
        font-size: 1.85rem; font-weight: 800;
        color: #fff; letter-spacing: -.03em; line-height: 1.15;
        margin-bottom: .3rem;
    }
    .hero-sub { font-size: .85rem; font-weight: 400; color: rgba(255,255,255,.55); }
    .hero-time { text-align: right; position: relative; z-index: 1; }
    .hero-date-lbl { font-size: .72rem; font-weight: 500; color: rgba(255,255,255,.50); margin-bottom: 2px; }
    .hero-clock    { font-size: 1.6rem; font-weight: 700; color: #fff; letter-spacing: .02em; }

    /* ── SECTION LABEL ── */
    .sec-label {
        font-size: .67rem; font-weight: 700;
        letter-spacing: .10em; text-transform: uppercase;
        color: var(--n-400); margin-bottom: .75rem; padding-left: 2px;
    }

    /* ── STAT CARD ── */
    .stat-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--n-200);
        box-shadow: var(--shadow-sm);
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        position: relative; overflow: hidden;
        transition: transform .22s ease, box-shadow .22s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
    .stat-card::after {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--c-700), var(--c-400));
        opacity: 0; transition: opacity .22s;
    }
    .stat-card:hover::after { opacity: 1; }
    .stat-icon {
        width: 52px; height: 52px; flex-shrink: 0;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--c-700), var(--c-400));
        box-shadow: 0 4px 12px -2px rgba(45,105,54,.35);
    }
    .stat-body { flex: 1; min-width: 0; }
    .stat-badge {
        position: absolute; top: .85rem; right: .85rem;
        font-size: .62rem; font-weight: 700;
        letter-spacing: .04em; text-transform: uppercase;
        color: var(--c-700); background: var(--c-50);
        border: 1px solid var(--c-100); border-radius: 999px;
        padding: 2px 8px;
    }
    .stat-val {
        font-size: 1.75rem; font-weight: 800;
        color: var(--n-900); letter-spacing: -.04em;
        line-height: 1; margin-bottom: .2rem;
    }
    .stat-lbl { font-size: .75rem; font-weight: 600; color: var(--n-500); }

    /* ── METRIC CARD ── */
    .metric-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--n-200);
        box-shadow: var(--shadow-sm);
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        transition: transform .22s, box-shadow .22s;
    }
    .metric-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
    .metric-icon {
        width: 46px; height: 46px; border-radius: var(--radius-sm);
        background: var(--c-50); border: 1px solid var(--c-100);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .metric-val { font-size: 1.3rem; font-weight: 800; color: var(--n-900); letter-spacing: -.03em; line-height: 1; }
    .metric-lbl { font-size: .73rem; font-weight: 500; color: var(--n-500); margin-top: 3px; }
    .metric-card.--gold .metric-icon { background: var(--gold-bg); border-color: var(--gold-border); }
    .metric-card.--gold .metric-val  { color: var(--gold); font-size: 1.05rem; }

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
        display: flex; align-items: center; justify-content: space-between;
        background: var(--n-50);
    }
    .panel-title { font-size: .875rem; font-weight: 700; color: var(--n-900); letter-spacing: -.01em; }
    .panel-tag {
        font-size: .64rem; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        color: var(--c-700); background: var(--c-50);
        border: 1px solid var(--c-100); border-radius: 999px;
        padding: 3px 10px;
    }
    .panel-tag-link {
        font-size: .75rem; font-weight: 600;
        color: var(--c-700);
        text-decoration: none;
        transition: color .15s;
    }
    .panel-tag-link:hover { color: var(--c-600); text-decoration: underline; }
    .panel-body { padding: 1.5rem; }

    /* ── TABLE ── */
    .dt { width: 100%; border-collapse: collapse; }
    .dt thead tr { border-bottom: 1px solid var(--n-200); }
    .dt th {
        padding: .55rem .9rem;
        text-align: left;
        font-size: .64rem; font-weight: 700;
        letter-spacing: .09em; text-transform: uppercase;
        color: var(--n-400);
    }
    .dt td {
        padding: .9rem .9rem;
        border-bottom: 1px solid var(--n-100);
        vertical-align: middle;
    }
    .dt tbody tr:last-child td { border-bottom: none; }
    .dt tbody tr { transition: background .15s; }
    .dt tbody tr:hover { background: var(--n-50); }
    .dt-name { font-size: .83rem; font-weight: 600; color: var(--n-900); }
    .dt-sub  { font-size: .70rem; font-weight: 500; color: var(--n-400); margin-top: 1px; }
    .dt-loc  { font-size: .80rem; font-weight: 500; color: var(--n-700); }
    .role-tag {
        display: inline-flex;
        font-size: .64rem; font-weight: 700;
        letter-spacing: .05em; text-transform: uppercase;
        color: var(--c-700); background: var(--c-50);
        border: 1px solid var(--c-100); border-radius: 6px;
        padding: 3px 10px;
    }
    .empty-row {
        text-align: center; padding: 2.5rem 0;
        color: var(--n-400); font-size: .82rem; font-weight: 500;
    }
    .pag-wrap {
        display: flex; align-items: center; justify-content: space-between;
        padding: .75rem 1rem;
        border-top: 1px solid var(--n-100);
    }
    .pag-info { font-size: .72rem; font-weight: 500; color: var(--n-400); }
    .pag-btns { display: flex; gap: 4px; }
    .pag-btn {
        width: 28px; height: 28px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 6px;
        border: 1px solid var(--n-200);
        background: var(--white);
        font-size: .72rem; font-weight: 600;
        color: var(--n-500);
        cursor: pointer; transition: all .15s;
    }
    .pag-btn:hover:not(:disabled) { background: var(--c-50); border-color: var(--c-100); color: var(--c-700); }
    .pag-btn.active { background: var(--c-700); border-color: var(--c-700); color: #fff; }
    .pag-btn:disabled { opacity: .35; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Hero --}}
    <div class="hero">
        <div class="hero-decor"></div>
        <div class="flex items-center justify-between relative" style="z-index:1">
            <div>
                <h1 class="hero-title">Dashboard Superadmin</h1>
                <p class="hero-sub">Kelola seluruh ekosistem zakat digital dengan efisien</p>
            </div>
            <div class="hero-time hidden sm:block">
                <p class="hero-date-lbl">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="hero-clock" id="liveTime">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
    </div>

    {{-- Primary Stats --}}
    <div>
        <p class="sec-label">Statistik Utama</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="stat-body">
                    <p class="stat-val">{{ number_format($stats['total_masjid']) }}</p>
                    <p class="stat-lbl">Total Masjid</p>
                </div>
                <span class="stat-badge">{{ $stats['masjid_aktif'] }} aktif</span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="stat-body">
                    <p class="stat-val">{{ number_format($stats['total_pengguna']) }}</p>
                    <p class="stat-lbl">Total Pengguna</p>
                </div>
                <span class="stat-badge">{{ $stats['total_admin_masjid'] + $stats['total_amil'] }} staf</span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="stat-body">
                    <p class="stat-val">{{ number_format($stats['total_admin_masjid']) }}</p>
                    <p class="stat-lbl">Admin Masjid</p>
                </div>
                <span class="stat-badge">{{ $stats['total_masjid'] > 0 ? number_format($stats['total_admin_masjid'] / $stats['total_masjid'], 1) : 0 }} / masjid</span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="stat-body">
                    <p class="stat-val">{{ number_format($stats['total_amil']) }}</p>
                    <p class="stat-lbl">Total Amil</p>
                </div>
                <span class="stat-badge">{{ $stats['total_masjid'] > 0 ? number_format($stats['total_amil'] / $stats['total_masjid'], 1) : 0 }} / masjid</span>
            </div>

        </div>
    </div>

    {{-- Secondary Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="metric-card">
            <div class="metric-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="metric-val">{{ $stats['total_jenis_zakat'] }}</p>
                <p class="metric-lbl">Jenis Zakat</p>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="metric-val">{{ $stats['total_kategori_mustahik'] }}</p>
                <p class="metric-lbl">Kategori Mustahik</p>
            </div>
        </div>

        <div class="metric-card --gold">
            <div class="metric-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--gold)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="metric-val">
                    {{ $stats['harga_emas_terkini'] ? 'Rp ' . number_format($stats['harga_emas_terkini']->harga_emas_pergram, 0, ',', '.') : 'N/A' }}
                </p>
                <p class="metric-lbl">Harga Emas / gram</p>
            </div>
        </div>

    </div>

    {{-- Charts --}}
    <div>
        <p class="sec-label">Analitik</p>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Distribusi Masjid per Provinsi</p>
                    <span class="panel-tag">Top 5</span>
                </div>
                <div class="panel-body">
                    <div style="height:268px; position:relative;">
                        <canvas id="chartMasjidProvinsi"></canvas>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Trend Registrasi Masjid</p>
                    <span class="panel-tag">6 Bulan Terakhir</span>
                </div>
                <div class="panel-body">
                    <div style="height:268px; position:relative;">
                        <canvas id="chartTrendMasjid"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Tables --}}
    <div>
        <p class="sec-label">Data Terbaru</p>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Masjid Terbaru Terdaftar</p>
                    <a href="{{ route('masjid.index') }}" class="panel-tag-link">Lihat Semua →</a>
                </div>
                <table class="dt" id="tbl-masjid">
                    <thead><tr>
                        <th>Nama Masjid</th>
                        <th>Lokasi</th>
                    </tr></thead>
                    <tbody>
                        @forelse($masjidTerbaru as $m)
                        <tr>
                            <td>
                                <p class="dt-name">{{ $m->nama }}</p>
                                <p class="dt-sub">{{ $m->kode_masjid }}</p>
                            </td>
                            <td class="dt-loc">{{ $m->kota_nama }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="empty-row">Belum ada data masjid</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($masjidTerbaru->count() > 5)
                <div class="pag-wrap" id="pag-masjid">
                    <span class="pag-info" id="pag-masjid-info"></span>
                    <div class="pag-btns" id="pag-masjid-btns"></div>
                </div>
                @endif
            </div>

            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Pengguna Terbaru Terdaftar</p>
                    <a href="{{ route('pengguna.index') }}" class="panel-tag-link">Lihat Semua →</a>
                </div>
                <table class="dt" id="tbl-pengguna">
                    <thead><tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                    </tr></thead>
                    <tbody>
                        @forelse($penggunaTerbaru as $p)
                        <tr>
                            <td>
                                <p class="dt-name">{{ $p->username }}</p>
                                <p class="dt-sub">{{ $p->email }}</p>
                            </td>
                            <td>
                                <span class="role-tag">{{ $p->peran }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="empty-row">Belum ada data pengguna</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($penggunaTerbaru->count() > 5)
                <div class="pag-wrap" id="pag-pengguna">
                    <span class="pag-info" id="pag-pengguna-info"></span>
                    <div class="pag-btns" id="pag-pengguna-btns"></div>
                </div>
                @endif
            </div>

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

    // Global Chart Defaults — Poppins everywhere
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.font.weight = '500';

    const C700 = '#2d6a2d';
    const GRID = '#f3f4f6';
    const TICK = '#9ca3af';

    const tooltipCfg = {
        backgroundColor : '#111827',
        titleColor      : '#ffffff',
        bodyColor       : 'rgba(255,255,255,.65)',
        borderColor     : 'rgba(124,179,66,.25)',
        borderWidth     : 1,
        padding         : 10,
        cornerRadius    : 8,
        titleFont: { family: "'Poppins', sans-serif", weight: '700', size: 12 },
        bodyFont : { family: "'Poppins', sans-serif", size: 11 },
    };

    const scalesCfg = {
        x: { grid: { display: false }, ticks: { color: TICK } },
        y: { grid: { color: GRID }, ticks: { color: TICK, stepSize: 1 }, beginAtZero: true }
    };

    // Bar: Masjid per Provinsi
    const masjidData = @json($masjidPerProvinsi);
    if (masjidData.length > 0) {
        new Chart(document.getElementById('chartMasjidProvinsi'), {
            type: 'bar',
            data: {
                labels: masjidData.map(i => i.nama),
                datasets: [{
                    label: 'Jumlah Masjid',
                    data: masjidData.map(i => i.jumlah),
                    backgroundColor: masjidData.map((_, idx) => `rgba(45,106,45,${.85 - idx * .12})`),
                    borderRadius: 8,
                    borderSkipped: false,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg },
                scales: scalesCfg,
            }
        });
    }

    // Line: Trend Registrasi
    const trendData = @json($trendMasjid);
    if (trendData.length > 0) {
        const ctx  = document.getElementById('chartTrendMasjid').getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 268);
        grad.addColorStop(0, 'rgba(45,106,45,.15)');
        grad.addColorStop(1, 'rgba(45,106,45,0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendData.map(i => i.bulan),
                datasets: [{
                    label: 'Registrasi',
                    data: trendData.map(i => i.jumlah),
                    borderColor: C700, backgroundColor: grad,
                    fill: true, tension: 0.42, borderWidth: 2.5,
                    pointRadius: 5, pointBackgroundColor: '#fff',
                    pointBorderColor: C700, pointBorderWidth: 2.5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg },
                scales: scalesCfg,
            }
        });
    }
    // ── Client-side Pagination ──────────────────────
    function initPagination(tableId, pagInfoId, pagBtnsId, perPage) {
        const tbody = document.querySelector('#' + tableId + ' tbody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length <= perPage) return;

        let current = 1;
        const totalPages = Math.ceil(rows.length / perPage);

        function render(page) {
            current = page;
            rows.forEach((r, i) => {
                r.style.display = (i >= (page-1)*perPage && i < page*perPage) ? '' : 'none';
            });
            // Info
            const infoEl = document.getElementById(pagInfoId);
            if (infoEl) {
                const from = (page-1)*perPage + 1;
                const to   = Math.min(page*perPage, rows.length);
                infoEl.textContent = from + '–' + to + ' dari ' + rows.length;
            }
            // Buttons
            const btnsEl = document.getElementById(pagBtnsId);
            if (!btnsEl) return;
            btnsEl.innerHTML = '';
            // Prev
            const prev = document.createElement('button');
            prev.className = 'pag-btn'; prev.textContent = '‹';
            prev.disabled = page === 1;
            prev.onclick = () => render(page - 1);
            btnsEl.appendChild(prev);
            // Pages
            for (let p = 1; p <= totalPages; p++) {
                const btn = document.createElement('button');
                btn.className = 'pag-btn' + (p === page ? ' active' : '');
                btn.textContent = p;
                btn.onclick = () => render(p);
                btnsEl.appendChild(btn);
            }
            // Next
            const next = document.createElement('button');
            next.className = 'pag-btn'; next.textContent = '›';
            next.disabled = page === totalPages;
            next.onclick = () => render(page + 1);
            btnsEl.appendChild(next);
        }

        render(1);
    }

    initPagination('tbl-masjid',   'pag-masjid-info',   'pag-masjid-btns',   5);
    initPagination('tbl-pengguna', 'pag-pengguna-info', 'pag-pengguna-btns', 5);
});
</script>
@endpush