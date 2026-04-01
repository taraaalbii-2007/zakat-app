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
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $hariId = $hariInd[$carbonTgl->format('l')] ?? $carbonTgl->format('l');
    $tanggalFmt = $hariId . ', ' . $carbonTgl->isoFormat('D MMMM YYYY');
@endphp

<style>
    /* ── Container — sama persis dengan navbar ── */
    .laporan-wrap {
        width: 100%;
        padding-left: 1rem;   /* px-4 */
        padding-right: 1rem;
        padding-top: 2.5rem;
        padding-bottom: 3rem;
    }
    @media (min-width: 640px) {
        .laporan-wrap { padding-left: 2.5rem; padding-right: 2.5rem; } /* sm:px-10 */
    }
    @media (min-width: 1024px) {
        .laporan-wrap { padding-left: 5rem; padding-right: 5rem; }    /* lg:px-20 */
    }

    /* ── Breadcrumb / back row ── */
    .laporan-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .laporan-meta-code {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 0.3rem;
    }

    .laporan-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.35rem;
        line-height: 1.2;
    }

    @media (min-width: 640px) {
        .laporan-title { font-size: 1.9rem; }
    }

    .laporan-location {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .laporan-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #22c55e;
        flex-shrink: 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 1.1rem;
        background: #f1f5f9;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        white-space: nowrap;
    }
    .btn-back:hover { background: #e2e8f0; color: #0f172a; }

    /* ── Divider ── */
    .section-divider {
        height: 1px;
        background: #f1f5f9;
        margin-bottom: 2rem;
    }

    /* ── Two-column grid ── */
    .laporan-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    @media (min-width: 1024px) {
        .laporan-grid { grid-template-columns: 1fr 320px; }
    }

    /* ── Card ── */
    .card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .card:last-child { margin-bottom: 0; }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 1rem 1.4rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
    }

    .card-body { padding: 1.4rem; }

    /* ── Stats grid (latest laporan) ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem 1.5rem;
    }
    @media (min-width: 640px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); }
    }

    .stat-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        margin-bottom: 0.3rem;
    }

    .stat-value {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
    }
    .stat-green  { color: #16a34a; }
    .stat-red    { color: #dc2626; }

    /* ── Rasio bar ── */
    .rasio-block {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        padding: 1.25rem 1.4rem;
        background: #fafafa;
        border-top: 1px solid #f1f5f9;
    }

    .rasio-pct {
        font-size: 2.75rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.02em;
    }
    .rasio-pct sup { font-size: 1.1rem; font-weight: 700; vertical-align: super; }
    .rasio-high { color: #16a34a; }
    .rasio-mid  { color: #f59e0b; }
    .rasio-low  { color: #cbd5e1; }

    .rasio-right { flex: 1; min-width: 120px; }
    .rasio-txt {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }

    .rasio-bar-bg {
        height: 5px;
        background: #e2e8f0;
        border-radius: 99px;
        overflow: hidden;
    }
    .rasio-bar-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 0.7s cubic-bezier(0.16,1,0.3,1);
    }
    .fill-high { background: linear-gradient(90deg,#22c55e,#16a34a); }
    .fill-mid  { background: linear-gradient(90deg,#fbbf24,#f59e0b); }
    .fill-low  { background: #cbd5e1; }

    /* ── Period pills ── */
    .period-pills {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .period-pill {
        padding: 0.22rem 0.85rem;
        background: #f1f5f9;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .period-pill:hover { background: #dcfce7; color: #16a34a; }
    .period-pill.active { background: #16a34a; color: #fff; }

    /* ── Table ── */
    .tbl-wrap { overflow-x: auto; }

    .laporan-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }

    .laporan-table thead tr {
        background: #fafafa;
    }

    .laporan-table th {
        text-align: left;
        padding: 0.7rem 1rem;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        white-space: nowrap;
        border-bottom: 1px solid #f1f5f9;
    }

    .laporan-table td {
        padding: 0.8rem 1rem;
        border-top: 1px solid #f8fafc;
        color: #334155;
        white-space: nowrap;
        vertical-align: middle;
    }

    .laporan-table tbody tr:hover td { background: #fafafa; }

    .td-periode { font-weight: 700; color: #0f172a; }

    .badge-rasio {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.72rem;
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

    /* ── Sidebar info list ── */
    .info-list { display: flex; flex-direction: column; }

    .info-item {
        padding: 0.8rem 0;
        border-bottom: 1px solid #f8fafc;
    }
    .info-item:last-child { border-bottom: none; }

    .info-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        margin-bottom: 0.2rem;
    }
    .info-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: #0f172a;
        word-break: break-word;
    }

    /* ── People tiles ── */
    .people-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .people-tile {
        background: #f8fafc;
        border-radius: 14px;
        padding: 1.1rem 0.75rem;
        text-align: center;
    }
    .people-num {
        font-size: 1.6rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
    }
    .people-lbl {
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #94a3b8;
        margin-top: 0.3rem;
    }

    /* ── Pagination tweak ── */
    .pagination-wrap {
        padding: 1rem 1.4rem;
        border-top: 1px solid #f1f5f9;
    }
</style>

<div class="laporan-wrap">

    {{-- Top bar: meta + back button --}}
    <div class="laporan-topbar">
        <div>
            <div class="laporan-meta-code">{{ $lembaga->kode_lembaga ?? 'MSJ20260001' }}</div>
            <h1 class="laporan-title">{{ $lembaga->nama ?? 'Masjid Jami Nurul' }}</h1>
            @if(!empty($lembaga->kota_nama))
                <div class="laporan-location">
                    <span class="laporan-dot"></span>
                    {{ $lembaga->kota_nama }}
                </div>
            @endif
        </div>

        <a href="{{ route('laporan.index') }}" class="btn-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="section-divider"></div>

    {{-- Main Grid --}}
    <div class="laporan-grid">

        {{-- ── Left Column ── --}}
        <div>

            {{-- Laporan Terbaru --}}
            @if($latest)
            <div class="card">
                <div class="card-head">
                    <span class="card-label">
                        Laporan Terbaru
                        @if(isset($bulanMap[$latest->bulan]))
                            — {{ $bulanMap[$latest->bulan] }} {{ $latest->tahun }}
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div>
                            <div class="stat-label">Total Penerimaan</div>
                            <div class="stat-value stat-green">Rp {{ number_format($latest->total_penerimaan, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="stat-label">Total Penyaluran</div>
                            <div class="stat-value">Rp {{ number_format($latest->total_penyaluran, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="stat-label">Saldo Akhir</div>
                            <div class="stat-value {{ $latest->saldo_akhir < 0 ? 'stat-red' : '' }}">
                                Rp {{ number_format($latest->saldo_akhir, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div class="stat-label">Diterbitkan</div>
                            <div class="stat-value">{{ $tanggalFmt }}</div>
                        </div>
                    </div>
                </div>
                <div class="rasio-block">
                    <div>
                        <div class="rasio-pct rasio-{{ $rasioClass }}">{{ $rasio }}<sup>%</sup></div>
                    </div>
                    <div class="rasio-right">
                        <div class="rasio-txt">Rasio Penyaluran</div>
                        <div class="rasio-bar-bg">
                            <div class="rasio-bar-fill fill-{{ $rasioClass }}" style="width:{{ $rasio }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Riwayat Laporan --}}
            <div class="card">
                <div class="card-head">
                    <span class="card-label">Riwayat Laporan Keuangan</span>
                    @if(!empty($availableTahun))
                    <div class="period-pills">
                        @foreach($availableTahun as $thn)
                            <a href="{{ route('laporan.detail', ['lembaga' => $lembaga->id, 'tahun' => $thn]) }}"
                               class="period-pill {{ (request('tahun', date('Y')) == $thn) ? 'active' : '' }}">
                                {{ $thn }}
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="tbl-wrap">
                    <table class="laporan-table">
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
                                    $r = $row->total_penerimaan > 0
                                        ? min(round(($row->total_penyaluran / $row->total_penerimaan) * 100), 100)
                                        : 0;
                                    $rc = $r >= 80 ? 'high' : ($r >= 50 ? 'mid' : 'low');
                                @endphp
                                <tr>
                                    <td class="td-periode">{{ $bulanMap[$row->bulan] ?? '-' }} {{ $row->tahun }}</td>
                                    <td class="stat-green" style="font-weight:600;">Rp {{ number_format($row->total_penerimaan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row->total_penyaluran, 0, ',', '.') }}</td>
                                    <td class="{{ $row->saldo_akhir < 0 ? 'stat-red' : '' }}" style="font-weight:600;">Rp {{ number_format($row->saldo_akhir, 0, ',', '.') }}</td>
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
                    <div class="pagination-wrap">
                        {{ $laporan->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>

        {{-- ── Right Column (Sidebar) ── --}}
        <div>

            {{-- Informasi Lembaga --}}
            <div class="card">
                <div class="card-head">
                    <span class="card-label">Informasi Lembaga</span>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-label">Kode Lembaga</div>
                            <div class="info-value">{{ $lembaga->kode_lembaga ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Kota / Kabupaten</div>
                            <div class="info-value">{{ $lembaga->kota_nama ?? '-' }}</div>
                        </div>
                        @if(!empty($lembaga->alamat))
                        <div class="info-item">
                            <div class="info-label">Alamat</div>
                            <div class="info-value">{{ $lembaga->alamat }}</div>
                        </div>
                        @endif
                        @if(!empty($lembaga->telepon))
                        <div class="info-item">
                            <div class="info-label">Telepon</div>
                            <div class="info-value">{{ $lembaga->telepon }}</div>
                        </div>
                        @endif
                        @if(!empty($lembaga->email))
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $lembaga->email }}</div>
                        </div>
                        @endif
                        <div class="info-item">
                            <div class="info-label">Total Laporan</div>
                            <div class="info-value">{{ $laporan->total() }} laporan</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik --}}
            @if($latest)
            <div class="card">
                <div class="card-head">
                    <span class="card-label">
                        Statistik
                        @if(isset($bulanMap[$latest->bulan]))
                            {{ $bulanMap[$latest->bulan] }} {{ $latest->tahun }}
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="people-grid">
                        <div class="people-tile">
                            <div class="people-num">{{ number_format($latest->jumlah_muzakki) }}</div>
                            <div class="people-lbl">Muzakki</div>
                        </div>
                        <div class="people-tile">
                            <div class="people-num">{{ number_format($latest->jumlah_mustahik) }}</div>
                            <div class="people-lbl">Mustahik</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection