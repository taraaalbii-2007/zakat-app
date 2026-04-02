{{-- resources/views/pages/laporan-detail.blade.php --}}

@extends('layouts.guest')

@section('title', ($lembaga->nama ?? 'Detail Laporan') . ' — Laporan Keuangan')

@section('content')

{{-- PAGE HERO --}}
@include('partials.landing.page-hero', [
    'heroTitle'    => \Illuminate\Support\Str::limit($lembaga->nama ?? 'Detail Laporan', 55),
    'heroSubtitle' => 'Laporan Keuangan & Transparansi',
])

@php
    $bulanMap = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
        7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
    ];

    $latest = $latestLaporan ?? $laporan->first();
    $rasio  = ($latest && $latest->total_penerimaan > 0)
        ? min(round(($latest->total_penyaluran / $latest->total_penerimaan) * 100), 100)
        : 0;
    $rasioClass = $rasio >= 80 ? 'high' : ($rasio >= 50 ? 'mid' : 'low');

    $tglTerbit = $latest->published_at ?? ($latest->created_at ?? now());
    $carbonTgl = \Carbon\Carbon::parse($tglTerbit);
    $hariInd = [
        'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
        'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
    ];
    $hariId     = $hariInd[$carbonTgl->format('l')] ?? $carbonTgl->format('l');
    $tanggalFmt = $hariId . ', ' . $carbonTgl->isoFormat('D MMMM YYYY');
@endphp

<style>
    .ld-wrap {
        width: 100%;
        padding: 2.5rem 1rem 3rem;
    }
    @media (min-width: 640px)  { .ld-wrap { padding-left: 2.5rem; padding-right: 2.5rem; } }
    @media (min-width: 1024px) { .ld-wrap { padding-left: 5rem;   padding-right: 5rem;   } }

    /* ── Single container ── */
    .ld-container {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
    }

    /* ── Hero / header ── */
    .ld-hero {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.75rem 1.75rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .ld-hero-code {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        margin-bottom: 0.35rem;
    }

    .ld-hero-name {
        font-size: 1.65rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 0.5rem;
    }
    @media (min-width: 640px) { .ld-hero-name { font-size: 1.9rem; } }

    .ld-hero-loc {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    .ld-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #16a34a;
        flex-shrink: 0;
    }

    .ld-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #15803d;
        white-space: nowrap;
        margin-top: 4px;
    }
    .ld-badge-dot { width: 5px; height: 5px; border-radius: 50%; background: #16a34a; }

    /* ── Section wrapper ── */
    .ld-section {
        padding: 1.4rem 1.75rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .ld-section:last-child { border-bottom: none; }

    .ld-section-label {
        font-size: 0.62rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    /* ── Stats row ── */
    .ld-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }
    @media (min-width: 640px) { .ld-stats { grid-template-columns: repeat(4, 1fr); } }

    .ld-stat {
        padding: 0.5rem 1rem 0.5rem 0;
        border-right: 1px solid #f1f5f9;
    }
    .ld-stat:first-child { padding-left: 0; }
    .ld-stat:last-child  { border-right: none; }

    @media (max-width: 639px) {
        .ld-stat:nth-child(2) { border-right: none; }
        .ld-stat:nth-child(3) { padding-left: 0; border-top: 1px solid #f1f5f9; padding-top: 0.75rem; margin-top: 0.5rem; border-right: none; }
        .ld-stat:nth-child(4) { border-top: 1px solid #f1f5f9; padding-top: 0.75rem; margin-top: 0.5rem; }
    }

    .ld-stat-lbl {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        margin-bottom: 5px;
    }

    .ld-stat-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
    }
    .ld-stat-val.green { color: #16a34a; }
    .ld-stat-val.red   { color: #dc2626; }
    .ld-stat-val.sm    { font-size: 0.8rem; font-weight: 500; }

    /* ── Rasio row ── */
    .ld-rasio {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.25rem 1.75rem;
        background: #fafafa;
        border-bottom: 1px solid #f1f5f9;
    }

    .ld-rasio-num {
        font-size: 2.8rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.02em;
        min-width: 80px;
    }
    .ld-rasio-num sup { font-size: 0.9rem; vertical-align: super; }
    .ld-rasio-num.high { color: #16a34a; }
    .ld-rasio-num.mid  { color: #f59e0b; }
    .ld-rasio-num.low  { color: #cbd5e1; }

    .ld-rasio-info { flex: 1; }

    .ld-rasio-lbl {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .ld-bar-bg {
        height: 4px;
        background: #e2e8f0;
        border-radius: 99px;
        overflow: hidden;
        margin-bottom: 5px;
    }
    .ld-bar-fill { height: 100%; border-radius: 99px; }
    .ld-bar-fill.high { background: #16a34a; }
    .ld-bar-fill.mid  { background: #f59e0b; }
    .ld-bar-fill.low  { background: #cbd5e1; }

    .ld-rasio-sub { font-size: 0.72rem; color: #94a3b8; }

    /* ── Two column: info + statistik ── */
    .ld-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-bottom: 1px solid #f1f5f9;
    }
    @media (max-width: 639px) { .ld-two { grid-template-columns: 1fr; } }

    .ld-two .ld-section { border-bottom: none; }
    .ld-two .ld-col-left {
        border-right: 1px solid #f1f5f9;
    }
    @media (max-width: 639px) {
        .ld-two .ld-col-left { border-right: none; border-bottom: 1px solid #f1f5f9; }
    }

    .ld-info-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 1rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8fafc;
        font-size: 0.82rem;
    }
    .ld-info-row:last-child { border-bottom: none; }
    .ld-info-k { color: #64748b; font-size: 0.75rem; white-space: nowrap; }
    .ld-info-v { font-weight: 600; color: #0f172a; text-align: right; word-break: break-word; font-size: 0.78rem; }

    /* ── People pair ── */
    .ld-people {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }
    .ld-people-tile {
        background: #f8fafc;
        border-radius: 14px;
        padding: 1.1rem 0.75rem;
        text-align: center;
    }
    .ld-people-num {
        font-size: 1.8rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
    }
    .ld-people-lbl {
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #94a3b8;
        margin-top: 4px;
    }

    /* ── Year tabs ── */
    .ld-year-tabs { display: flex; gap: 4px; flex-wrap: wrap; }
    .ld-year-tab {
        padding: 3px 12px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        background: transparent;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s;
    }
    .ld-year-tab:hover { background: #f1f5f9; }
    .ld-year-tab.active { background: #15803d; border-color: #15803d; color: #fff; }

    /* ── Table ── */
    .ld-tbl-wrap { overflow-x: auto; }

    .ld-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }

    .ld-table thead tr { background: #fafafa; }

    .ld-table th {
        text-align: left;
        padding: 0.65rem 1rem;
        font-size: 0.62rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #94a3b8;
        white-space: nowrap;
        border-bottom: 1px solid #f1f5f9;
    }

    .ld-table td {
        padding: 0.8rem 1rem;
        border-top: 1px solid #f8fafc;
        color: #334155;
        white-space: nowrap;
        vertical-align: middle;
    }

    .ld-table tbody tr:hover td { background: #fafafa; }

    .td-periode { font-weight: 700; color: #0f172a; }
    .td-green   { color: #16a34a; font-weight: 600; }
    .td-red     { color: #dc2626; font-weight: 600; }

    .badge-rasio {
        display: inline-block;
        padding: 0.18rem 0.6rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    .badge-high { background: #dcfce7; color: #15803d; }
    .badge-mid  { background: #fef3c7; color: #b45309; }
    .badge-low  { background: #f1f5f9; color: #94a3b8; }

    .empty-row td {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* ── Pagination ── */
    .ld-pagination {
        padding: 1rem 1.75rem;
        border-top: 1px solid #f1f5f9;
    }

    /* ── Back button row ── */
    .ld-back-row {
        display: flex;
        justify-content: flex-start;
        margin-top: 1.5rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1.25rem;
        background: #f1f5f9;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .btn-back:hover { background: #e2e8f0; color: #0f172a; }
</style>

<div class="ld-wrap">
    <div class="ld-container">

        {{-- ── Hero / Header ── --}}
        <div class="ld-hero">
            <div>
                <div class="ld-hero-code">{{ $lembaga->kode_lembaga ?? '-' }}</div>
                <h1 class="ld-hero-name">{{ $lembaga->nama ?? 'Detail Laporan' }}</h1>
                @if(!empty($lembaga->kota_nama))
                    <div class="ld-hero-loc">
                        <span class="ld-dot"></span>
                        {{ $lembaga->kota_nama }}
                    </div>
                @endif
            </div>
            <div class="ld-badge">
                <span class="ld-badge-dot"></span>
                Terverifikasi
            </div>
        </div>

        {{-- ── Laporan Terbaru ── --}}
        @if($latest)
        <div class="ld-section">
            <div class="ld-section-label">
                Laporan Terbaru
                @if(isset($bulanMap[$latest->bulan])) — {{ $bulanMap[$latest->bulan] }} {{ $latest->tahun }} @endif
            </div>
            <div class="ld-stats">
                <div class="ld-stat">
                    <div class="ld-stat-lbl">Total Penerimaan</div>
                    <div class="ld-stat-val green">Rp {{ number_format($latest->total_penerimaan, 0, ',', '.') }}</div>
                </div>
                <div class="ld-stat">
                    <div class="ld-stat-lbl">Total Penyaluran</div>
                    <div class="ld-stat-val">Rp {{ number_format($latest->total_penyaluran, 0, ',', '.') }}</div>
                </div>
                <div class="ld-stat">
                    <div class="ld-stat-lbl">Saldo Akhir</div>
                    <div class="ld-stat-val {{ $latest->saldo_akhir < 0 ? 'red' : '' }}">
                        Rp {{ number_format($latest->saldo_akhir, 0, ',', '.') }}
                    </div>
                </div>
                <div class="ld-stat">
                    <div class="ld-stat-lbl">Diterbitkan</div>
                    <div class="ld-stat-val sm">{{ $tanggalFmt }}</div>
                </div>
            </div>
        </div>

        {{-- ── Rasio Penyaluran ── --}}
        <div class="ld-rasio">
            <div class="ld-rasio-num {{ $rasioClass }}">{{ $rasio }}<sup>%</sup></div>
            <div class="ld-rasio-info">
                <div class="ld-rasio-lbl">Rasio Penyaluran</div>
                <div class="ld-bar-bg">
                    <div class="ld-bar-fill {{ $rasioClass }}" style="width: {{ $rasio }}%"></div>
                </div>
                <div class="ld-rasio-sub">
                    Rp {{ number_format($latest->total_penyaluran, 0, ',', '.') }}
                    dari Rp {{ number_format($latest->total_penerimaan, 0, ',', '.') }} tersalurkan
                </div>
            </div>
        </div>
        @endif

        {{-- ── Info Lembaga + Statistik (2 kolom) ── --}}
        <div class="ld-two">
            <div class="ld-col-left ld-section">
                <div class="ld-section-label">Informasi Lembaga</div>
                <div class="ld-info-row">
                    <span class="ld-info-k">Kode Lembaga</span>
                    <span class="ld-info-v">{{ $lembaga->kode_lembaga ?? '-' }}</span>
                </div>
                <div class="ld-info-row">
                    <span class="ld-info-k">Kota / Kabupaten</span>
                    <span class="ld-info-v">{{ $lembaga->kota_nama ?? '-' }}</span>
                </div>
                @if(!empty($lembaga->alamat))
                <div class="ld-info-row">
                    <span class="ld-info-k">Alamat</span>
                    <span class="ld-info-v">{{ $lembaga->alamat }}</span>
                </div>
                @endif
                @if(!empty($lembaga->telepon))
                <div class="ld-info-row">
                    <span class="ld-info-k">Telepon</span>
                    <span class="ld-info-v">{{ $lembaga->telepon }}</span>
                </div>
                @endif
                @if(!empty($lembaga->email))
                <div class="ld-info-row">
                    <span class="ld-info-k">Email</span>
                    <span class="ld-info-v">{{ $lembaga->email }}</span>
                </div>
                @endif
                <div class="ld-info-row">
                    <span class="ld-info-k">Total Laporan</span>
                    <span class="ld-info-v">{{ $laporan->total() }} laporan</span>
                </div>
            </div>

            @if($latest)
            <div class="ld-section">
                <div class="ld-section-label">
                    Statistik
                    @if(isset($bulanMap[$latest->bulan])) {{ $bulanMap[$latest->bulan] }} {{ $latest->tahun }} @endif
                </div>
                <div class="ld-people">
                    <div class="ld-people-tile">
                        <div class="ld-people-num">{{ number_format($latest->jumlah_muzakki) }}</div>
                        <div class="ld-people-lbl">Muzakki</div>
                    </div>
                    <div class="ld-people-tile">
                        <div class="ld-people-num">{{ number_format($latest->jumlah_mustahik) }}</div>
                        <div class="ld-people-lbl">Mustahik</div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- ── Riwayat Laporan Keuangan ── --}}
        <div class="ld-section" style="padding-bottom: 0;">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem;">
                <div class="ld-section-label" style="margin-bottom:0;">Riwayat Laporan Keuangan</div>
                @if(!empty($availableTahun))
                <div class="ld-year-tabs">
                    @foreach($availableTahun as $thn)
                        <a href="{{ route('laporan.detail', ['lembaga' => $lembaga->id, 'tahun' => $thn]) }}"
                           class="ld-year-tab {{ (request('tahun', date('Y')) == $thn) ? 'active' : '' }}">
                            {{ $thn }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="ld-tbl-wrap">
            <table class="ld-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Penerimaan</th>
                        <th>Penyaluran</th>
                        <th>Saldo Akhir</th>
                        <th>Rasio</th>
                        <th>Muzakki</th>
                        <th>Mustahik</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                        @php
                            $r  = $row->total_penerimaan > 0
                                ? min(round(($row->total_penyaluran / $row->total_penerimaan) * 100), 100)
                                : 0;
                            $rc = $r >= 80 ? 'high' : ($r >= 50 ? 'mid' : 'low');
                        @endphp
                        <tr>
                            <td class="td-periode">{{ $bulanMap[$row->bulan] ?? '-' }} {{ $row->tahun }}</td>
                            <td class="td-green">Rp {{ number_format($row->total_penerimaan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row->total_penyaluran, 0, ',', '.') }}</td>
                            <td class="{{ $row->saldo_akhir < 0 ? 'td-red' : '' }}" style="font-weight:600;">
                                Rp {{ number_format($row->saldo_akhir, 0, ',', '.') }}
                            </td>
                            <td><span class="badge-rasio badge-{{ $rc }}">{{ $r }}%</span></td>
                            <td>{{ number_format($row->jumlah_muzakki) }}</td>
                            <td>{{ number_format($row->jumlah_mustahik) }}</td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="7">Belum ada laporan keuangan yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporan instanceof \Illuminate\Pagination\LengthAwarePaginator && $laporan->hasPages())
            <div class="ld-pagination">
                {{ $laporan->appends(request()->query())->links() }}
            </div>
        @endif

    </div>{{-- end .ld-container --}}

    {{-- ── Tombol Kembali ── --}}
    <div class="ld-back-row">
        <a href="{{ route('laporan.index') }}" class="btn-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Laporan
        </a>
    </div>

</div>

@endsection