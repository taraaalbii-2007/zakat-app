{{-- resources/views/pages/laporan.blade.php --}}

@extends('layouts.guest')

@section('title', 'Laporan Keuangan Lembaga Zakat')
@section('meta_description', 'Laporan keuangan resmi lembaga-lembaga zakat terdaftar. Terbuka dan transparan untuk publik.')

@section('content')

    @include('partials.landing.page-hero', [
        'heroTitle'    => 'Laporan Keuangan',
        'heroSubtitle' => 'Laporan keuangan resmi yang telah dipublikasikan oleh setiap lembaga zakat terdaftar — terbuka, transparan, dan dapat diakses oleh seluruh masyarakat.',
    ])

    <style>
        /* ── WRAPPER HALAMAN ─────────────────────────── */
        .lrk-page {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── FILTER + SEARCH WRAP (sticky) ──────────── */
        .lrk-filter-wrap {
            background: #ffffff;
            padding: 0.75rem 0 0.5rem;
            position: sticky;
            top: 0;
            z-index: 40;
            border-bottom: 1px solid #f3f4f6;
        }

        /* ── SEARCH ROW (baris atas, centered) ─────── */
        .lrk-search-row {
            display: flex;
            justify-content: center;
            padding: 0 1rem;
            margin-bottom: 0.6rem;
        }

        @media (min-width: 640px) {
            .lrk-search-row {
                padding: 0 2.5rem;
            }
        }

        @media (min-width: 1024px) {
            .lrk-search-row {
                padding: 0 5rem;
            }
        }

        .lrk-search-wrap {
            position: relative;
            width: 480px;
            max-width: 100%;
        }

        .lrk-search-input {
            width: 100%;
            border: 1.5px solid #d1d5db;
            border-radius: 99px;
            background: #ffffff;
            outline: none;
            padding: 0.62rem 3rem 0.62rem 1.35rem;
            font-size: 0.88rem;
            color: #374151;
            transition: border-color .18s, box-shadow .18s;
            box-sizing: border-box;
        }

        .lrk-search-input::placeholder {
            color: #9ca3af;
        }

        .lrk-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
        }

        .lrk-search-btn {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            padding: 0;
            color: #9ca3af;
            cursor: pointer;
            transition: color .18s;
            line-height: 1;
        }

        .lrk-search-btn:hover {
            color: #16a34a;
        }

        /* ── FILTER PILLS ROW (baris bawah) ─────────── */
        .lrk-filter-inner {
            width: 100%;
            box-sizing: border-box;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            min-height: 36px;
        }

        @media (min-width: 640px) {
            .lrk-filter-inner {
                padding: 0 2.5rem;
            }
        }

        @media (min-width: 1024px) {
            .lrk-filter-inner {
                padding: 0 5rem;
            }
        }

        /* Result text */
        .lrk-result-txt {
            font-size: 0.82rem;
            color: #6b7280;
            font-weight: 500;
        }

        .lrk-result-txt strong {
            color: #111827;
            font-weight: 700;
        }

        /* Filter dropdowns group */
        .lrk-filter-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .lrk-select {
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 99px;
            padding: 0.45rem 2rem 0.45rem 1.2rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.9rem center;
            background-size: 14px;
        }

        .lrk-select:hover {
            border-color: #16a34a;
            background-color: #f0fdf4;
        }

        .lrk-select:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        /* ── SECTION UTAMA ──────────────────────────── */
        .lrk-section {
            width: 100%;
            box-sizing: border-box;
            padding: 1.5rem 1rem 0;
            flex: 1;
        }

        @media (min-width: 640px) {
            .lrk-section {
                padding: 1.5rem 2.5rem 0;
            }
        }

        @media (min-width: 1024px) {
            .lrk-section {
                padding: 1.5rem 5rem 0;
            }
        }

        /* ── GRID 3 KOLOM ───────────────────────────── */
        .lrk-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }

        @media (min-width: 640px) {
            .lrk-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .lrk-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ── CARD ───────────────────────────────────── */
        .lrk-card {
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .22s, box-shadow .22s, border-color .22s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            height: 100%;
        }

        .lrk-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(22, 163, 74, 0.13);
            border-color: #86efac;
        }

        /* Card head */
        .lrk-card__head {
            padding: 1.25rem 1.25rem 0.75rem;
        }

        .lrk-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .lrk-card__kode {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
        }

        .lrk-card__badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.75rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 99px;
            font-size: 0.68rem;
            font-weight: 700;
            color: #16a34a;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .lrk-card__badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse 2.4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(0.9);
            }
        }

        .lrk-card__nama {
            font-size: 1.05rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.35;
            margin: 0 0 0.3rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s;
        }

        .lrk-card:hover .lrk-card__nama {
            color: #16a34a;
        }

        .lrk-card__kota {
            font-size: 0.7rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Card body */
        .lrk-card__body {
            padding: 0 1.25rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
        }

        .lrk-divider {
            height: 1px;
            background: #f3f4f6;
        }

        /* Stat pair */
        .lrk-stat-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1rem;
        }

        .lrk-stat__label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }

        .lrk-stat__val {
            font-size: 0.95rem;
            font-weight: 800;
            color: #111827;
        }

        .v-green {
            color: #16a34a;
        }

        .v-red {
            color: #dc2626;
        }

        /* Rasio block */
        .lrk-rasio-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
        }

        .lrk-rasio-num {
            font-size: 2rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .lrk-rasio-num sup {
            font-size: 0.85rem;
            font-weight: 700;
            vertical-align: super;
        }

        .rn-high {
            color: #16a34a;
        }

        .rn-mid {
            color: #f59e0b;
        }

        .rn-low {
            color: #d1d5db;
        }

        .lrk-rasio-lbl {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #9ca3af;
            margin-top: 0.25rem;
        }

        /* Bar */
        .lrk-bar {
            height: 4px;
            background: #f3f4f6;
            border-radius: 99px;
            overflow: hidden;
        }

        .lrk-bar__fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .bf-high {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .bf-mid {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
        }

        .bf-low {
            background: #d1d5db;
        }

        /* People */
        .lrk-people {
            display: flex;
            gap: 1.5rem;
        }

        .lrk-people__num {
            font-size: 1rem;
            font-weight: 800;
            color: #111827;
        }

        .lrk-people__lbl {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #9ca3af;
            margin-top: 0.15rem;
        }

        /* Card footer */
        .lrk-card__footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.25rem;
            background: #fafafa;
            border-top: 1px solid #f3f4f6;
        }

        .lrk-card__date {
            font-size: 0.72rem;
            color: #9ca3af;
        }

        .lrk-link {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.78rem;
            font-weight: 700;
            color: #16a34a;
            text-decoration: none;
            transition: all 0.2s;
        }

        .lrk-link svg {
            transition: transform 0.2s;
        }

        .lrk-link:hover {
            gap: 0.5rem;
        }

        .lrk-link:hover svg {
            transform: translateX(3px);
        }

        /* ── EMPTY STATE ────────────────────────────── */
        .lrk-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 1rem;
            color: #9ca3af;
        }

        .lrk-empty svg {
            margin: 0 auto 1rem;
            display: block;
        }

        .lrk-empty p {
            font-size: 0.95rem;
        }

        .lrk-empty a {
            color: #16a34a;
            text-decoration: none;
            font-weight: 600;
        }

        /* ── PAGINATION ─────────────────────────────── */
        .lrk-pagination {
            width: 100%;
            box-sizing: border-box;
            margin: 3rem 0 2rem;
            padding: 0 1rem;
            display: flex;
            justify-content: center;
        }

        @media (min-width: 640px) {
            .lrk-pagination {
                padding: 0 2.5rem;
            }
        }

        @media (min-width: 1024px) {
            .lrk-pagination {
                padding: 0 5rem;
            }
        }

        .lrk-pagination .pagination {
            gap: 0.35rem;
        }

        .lrk-pagination .page-link {
            border-radius: 8px !important;
            border: 1.5px solid #bbf7d0 !important;
            color: #16a34a !important;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.4rem 0.85rem;
            transition: all .18s;
        }

        .lrk-pagination .page-link:hover,
        .lrk-pagination .page-item.active .page-link {
            background: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
        }

        /* Menambahkan min-height pada section agar bisa scroll */
        .lrk-section {
            min-height: calc(100vh - 300px);
        }
        
        /* Memastikan body bisa scroll */
        html, body {
            height: auto;
            min-height: 100vh;
            overflow-y: auto !important;
        }
        
        /* Footer spacer untuk memastikan scroll */
        .lrk-footer-spacer {
            height: 2rem;
        }
    </style>

    <div class="lrk-page">

        {{-- ── SEARCH + FILTER BAR (sticky) ──────────── --}}
        <div class="lrk-filter-wrap">

            {{-- Baris 1: Search bar — center --}}
            <div class="lrk-search-row">
                <form action="{{ route('laporan.index') }}" method="GET" style="width:100%;display:flex;justify-content:center;">
                    @if (request('tahun'))
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    @endif
                    @if (request('bulan'))
                        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    @endif
                    <div class="lrk-search-wrap">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama lembaga..." class="lrk-search-input">
                        <button type="submit" class="lrk-search-btn" aria-label="Cari">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Baris 2: Result info + Filter pills (tahun & bulan) --}}
            <div class="lrk-filter-inner">
                @if($laporan->total() > 0)
                    <div class="lrk-result-txt">
                        Menampilkan <strong>{{ $laporan->firstItem() }}–{{ $laporan->lastItem() }}</strong>
                        dari <strong>{{ $laporan->total() }}</strong> laporan
                    </div>
                @else
                    <div class="lrk-result-txt">
                        <strong>0</strong> laporan ditemukan
                    </div>
                @endif

                <div class="lrk-filter-group">
                    <form action="{{ route('laporan.index') }}" method="GET" style="margin:0;">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('bulan'))
                            <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                        @endif
                        <select name="tahun" class="lrk-select" onchange="this.form.submit()">
                            @foreach($availableTahun as $thn)
                                <option value="{{ $thn }}" {{ request('tahun', date('Y')) == $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <form action="{{ route('laporan.index') }}" method="GET" style="margin:0;">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('tahun'))
                            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        @endif
                        <select name="bulan" class="lrk-select" onchange="this.form.submit()">
                            <option value="" {{ !request('bulan') ? 'selected' : '' }}>Semua bulan</option>
                            @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $nama)
                                <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

        </div>

        {{-- ── GRID LAPORAN ───────────────────────── --}}
        <section class="lrk-section">
            <div class="lrk-grid">
                @forelse($laporan as $item)
                    @php
                        $bulanNama = [
                            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
                            7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',
                        ][$item->bulan] ?? '-';

                        $rasio = $item->total_penerimaan > 0
                            ? min(round(($item->total_penyaluran / $item->total_penerimaan) * 100), 100)
                            : 0;
                        $rc = $rasio >= 80 ? 'high' : ($rasio >= 50 ? 'mid' : 'low');

                        $lembagaNama = $item->lembaga->nama ?? 'Lembaga';
                        $lembagaKode = $item->lembaga->kode_lembaga ?? null;
                        $kota        = $item->lembaga->kota_nama ?? null;
                        $publishedAt = $item->published_at
                            ? \Carbon\Carbon::parse($item->published_at)->isoFormat('D MMM YYYY')
                            : '-';
                    @endphp

                    <article class="lrk-card">
                        {{-- HEAD --}}
                        <div class="lrk-card__head">
                            <div class="lrk-card__top">
                                <span class="lrk-card__kode">
                                    @if($lembagaKode){{ $lembagaKode }} &middot; @endif{{ $bulanNama }} {{ $item->tahun }}
                                </span>
                                <span class="lrk-card__badge">
                                    <span class="lrk-card__badge-dot"></span>
                                    Publik
                                </span>
                            </div>
                            <h3 class="lrk-card__nama">{{ $lembagaNama }}</h3>
                            @if($kota)
                                <div class="lrk-card__kota">{{ $kota }}</div>
                            @endif
                        </div>

                        {{-- BODY --}}
                        <div class="lrk-card__body">
                            <div class="lrk-divider"></div>

                            {{-- Penerimaan & Penyaluran --}}
                            <div class="lrk-stat-row">
                                <div>
                                    <div class="lrk-stat__label">Penerimaan</div>
                                    <div class="lrk-stat__val v-green">Rp {{ number_format($item->total_penerimaan, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <div class="lrk-stat__label">Penyaluran</div>
                                    <div class="lrk-stat__val">Rp {{ number_format($item->total_penyaluran, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            {{-- Saldo + Rasio --}}
                            <div class="lrk-rasio-row">
                                <div>
                                    <div class="lrk-stat__label">Saldo Akhir</div>
                                    <div class="lrk-stat__val {{ $item->saldo_akhir < 0 ? 'v-red' : '' }}">
                                        Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div style="text-align:right;">
                                    <div class="lrk-rasio-num rn-{{ $rc }}">{{ $rasio }}<sup>%</sup></div>
                                    <div class="lrk-rasio-lbl">Rasio Salur</div>
                                </div>
                            </div>

                            {{-- Progress bar --}}
                            <div class="lrk-bar">
                                <div class="lrk-bar__fill bf-{{ $rc }}" style="width:{{ $rasio }}%"></div>
                            </div>

                            {{-- Muzakki & Mustahik --}}
                            <div class="lrk-people">
                                <div>
                                    <div class="lrk-people__num">{{ number_format($item->jumlah_muzakki) }}</div>
                                    <div class="lrk-people__lbl">Muzakki</div>
                                </div>
                                <div>
                                    <div class="lrk-people__num">{{ number_format($item->jumlah_mustahik) }}</div>
                                    <div class="lrk-people__lbl">Mustahik</div>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="lrk-card__footer">
                            <span class="lrk-card__date">Terbit {{ $publishedAt }}</span>
                            @if($item->lembaga)
                                <a href="{{ route('laporan.detail', $item->lembaga->id) }}" class="lrk-link">
                                    Lihat detail
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </article>

                @empty
                    <div class="lrk-empty">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="56" height="56" rx="16" fill="#f0fdf4" />
                            <path d="M18 20h20M18 28h14M18 36h8" stroke="#86efac" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                        <p>
                            @if(request('q'))
                                Tidak ditemukan laporan untuk "<strong>{{ request('q') }}</strong>".
                            @else
                                Belum ada laporan keuangan yang dipublikasikan untuk periode ini.
                            @endif
                        </p>
                        @if(request()->hasAny(['q','bulan']) || request('tahun') != date('Y'))
                            <a href="{{ route('laporan.index') }}">Lihat semua laporan →</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ── PAGINATION ───────────────────────────── --}}
        @if($laporan->hasPages())
            <div class="lrk-pagination">
                {{ $laporan->appends(request()->query())->links() }}
            </div>
        @endif
        
        {{-- Spacer untuk memastikan bisa scroll meskipun hanya 1 card --}}
        <div class="lrk-footer-spacer"></div>

    </div>

    <script>
        // Memastikan body bisa scroll
        document.documentElement.style.overflowY = 'auto';
        document.body.style.overflowY = 'auto';
        
        // Jika ada navbar fixed, sesuaikan posisi sticky
        setTimeout(function() {
            const navbar = document.querySelector('nav.navbar, nav, header');
            if (navbar && window.getComputedStyle(navbar).position === 'fixed') {
                const navbarHeight = navbar.offsetHeight;
                const filterWrap = document.querySelector('.lrk-filter-wrap');
                if (filterWrap) {
                    filterWrap.style.top = navbarHeight + 'px';
                }
            }
        }, 100);
    </script>

@endsection