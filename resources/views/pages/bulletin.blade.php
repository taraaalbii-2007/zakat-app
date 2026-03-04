{{-- resources/views/pages/bulletin.blade.php --}}

@extends('layouts.guest')

@section('title', 'Bulletin & Artikel')

@section('content')

{{-- ══════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════ --}}
@include('partials.landing.page-hero', [
    'heroTitle'    => 'Bulletin & Artikel',
    'heroSubtitle' => 'Informasi terkini, edukasi zakat, dan panduan seputar pengelolaan zakat, infak, serta sedekah untuk kehidupan yang lebih berkah.',
])

{{-- ══════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════ --}}
<style>

    /* ── WRAPPER HALAMAN ─────────────────────────── */
    .bul-page {
        background: #ffffff;
        padding-bottom: 5rem;
    }

    /* ── FILTER + SEARCH WRAP (sticky) ──────────── */
    .bul-filter-wrap {
        background: #ffffff;
        position: sticky;
        top: 0;
        z-index: 40;
        padding: 0.75rem 0 0.5rem;
    }

    /* ── SEARCH ROW (baris atas, centered) ─────── */
    .bul-search-row {
        display: flex;
        justify-content: center;
        padding: 0 1rem;
        margin-bottom: 0.6rem;
    }
    @media (min-width: 640px)  { .bul-search-row { padding: 0 2.5rem; } }
    @media (min-width: 1024px) { .bul-search-row { padding: 0 5rem; } }

    .bul-search-wrap {
        position: relative;
        width: 480px;
        max-width: 100%;
    }
    .bul-search-input {
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
    .bul-search-input::placeholder { color: #9ca3af; }
    .bul-search-input:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 3px rgba(156,163,175,0.15);
    }
    .bul-search-btn {
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
    .bul-search-btn:hover { color: #6b7280; }

    /* ── FILTER PILLS ROW (baris bawah) ─────────── */
    .bul-filter-inner {
        width: 100%;
        box-sizing: border-box;
        padding: 0 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        min-height: 36px;
    }
    @media (min-width: 640px)  { .bul-filter-inner { padding: 0 2.5rem; } }
    @media (min-width: 1024px) { .bul-filter-inner { padding: 0 5rem; } }

    .bul-filter__btn {
        display: inline-flex;
        align-items: center;
        padding: 0.32rem 1rem;
        border-radius: 99px;
        border: 1.5px solid #bbf7d0;
        background: #ffffff;
        color: #4b7a52;
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .18s;
        text-decoration: none;
        white-space: nowrap;
    }
    .bul-filter__btn:hover,
    .bul-filter__btn.active {
        background: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    /* ── SECTION UTAMA ──────────────────────────── */
    .bul-section {
        width: 100%;
        box-sizing: border-box;
        padding: 1.5rem 1rem 0;
    }
    @media (min-width: 640px)  { .bul-section { padding: 1.25rem 2.5rem 0; } }
    @media (min-width: 1024px) { .bul-section { padding: 1.5rem 5rem 0; } }

    /* ── GRID 3 KOLOM ───────────────────────────── */
    .bul-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.75rem;
    }
    @media (min-width: 640px)  { .bul-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .bul-grid { grid-template-columns: repeat(3, 1fr); } }

    /* ── CARD ───────────────────────────────────── */
    .bul-card {
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .22s, box-shadow .22s, border-color .22s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        height: 100%;
    }
    .bul-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(22,163,74,0.13);
        border-color: #86efac;
    }

    /* Thumbnail */
    .bul-card__thumb-link {
        display: block;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: #f0fdf4;
        flex-shrink: 0;
    }
    .bul-card__thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .bul-card:hover .bul-card__thumb { transform: scale(1.04); }
    /* Caption sumber foto - muncul saat hover */
    .bul-card__thumb-link {
        position: relative;
    }
    .bul-card__caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.38) 70%, transparent 100%);
        color: #ffffff;
        font-size: 0.72rem;
        font-style: italic;
        font-weight: 400;
        padding: 1.5rem 0.85rem 0.55rem;
        display: flex;
        align-items: flex-end;
        gap: 0.3rem;
        line-height: 1.45;
        letter-spacing: 0.01em;
        opacity: 0;
        transform: translateY(6px);
        transition: opacity .25s ease, transform .25s ease;
    }
    .bul-card:hover .bul-card__caption {
        opacity: 1;
        transform: translateY(0);
    }

    .bul-card__thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #f0fdf4;
        color: #4b7a52;
        font-size: 0.78rem;
    }

    /* Body */
    .bul-card__body {
        padding: 1.1rem 1.25rem 1.1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.55rem;
    }

    /* Top row: kategori + meta sejajar */
    .bul-card__top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Kategori badge */
    .bul-card__kategori {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.65rem;
        border-radius: 99px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        font-size: 0.70rem;
        font-weight: 700;
        color: #15803d;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        line-height: 1;
        white-space: nowrap;
    }

    /* Meta */
    .bul-card__meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }
    .bul-card__meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.28rem;
        font-size: 0.75rem;
        color: #9ca3af;
        font-weight: 400;
    }

    /* Judul */
    .bul-card__judul {
        font-size: 0.975rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.5;
        margin: 0;
        flex: 0 0 auto;
        /* Clamp 2 baris agar tinggi card seragam */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bul-card__judul a {
        color: inherit;
        text-decoration: none;
        transition: color .18s;
    }
    .bul-card__judul a:hover { color: #16a34a; }

    /* Excerpt */
    .bul-card__excerpt {
        font-size: 0.78rem;
        color: #9ca3af;
        line-height: 1.65;
        margin: 0;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer */
    .bul-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.85rem;
        border-top: 1px solid #f3f4f6;
        margin-top: auto;
        gap: 0.5rem;
    }

    /* Author */
    .bul-card__author {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        min-width: 0;
    }
    .bul-card__author-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px; height: 26px;
        border-radius: 50%;
        background: #dcfce7;
        color: #16a34a;
        flex-shrink: 0;
    }
    .bul-card__author-nama {
        font-size: 0.78rem;
        font-weight: 500;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }

    /* Tombol baca */
    .bul-card__baca {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #16a34a;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: gap .18s, color .18s;
    }
    .bul-card__baca:hover {
        gap: 0.5rem;
        color: #15803d;
    }

    /* ── EMPTY STATE ────────────────────────────── */
    .bul-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: #9ca3af;
    }
    .bul-empty svg { margin: 0 auto 1rem; display: block; }
    .bul-empty p { font-size: 0.95rem; }

    /* ── PAGINATION ─────────────────────────────── */
    .bul-pagination {
        width: 100%;
        box-sizing: border-box;
        margin: 3rem 0 0;
        padding: 0 1rem;
        display: flex;
        justify-content: center;
    }
    @media (min-width: 640px)  { .bul-pagination { padding: 0 2.5rem; } }
    @media (min-width: 1024px) { .bul-pagination { padding: 0 5rem; } }

    .bul-pagination .pagination { gap: 0.35rem; }
    .bul-pagination .page-link {
        border-radius: 8px !important;
        border: 1.5px solid #bbf7d0 !important;
        color: #16a34a !important;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.4rem 0.85rem;
        transition: all .18s;
    }
    .bul-pagination .page-link:hover,
    .bul-pagination .page-item.active .page-link {
        background: #16a34a !important;
        border-color: #16a34a !important;
        color: #ffffff !important;
    }

</style>

{{-- ══════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════ --}}
<div class="bul-page">

    {{-- ── SEARCH + FILTER BAR (sticky) ──────────── --}}
    <div class="bul-filter-wrap">

        {{-- Baris 1: Search bar — center --}}
        <div class="bul-search-row">
            <form action="{{ route('artikel.index') }}" method="GET" style="width:100%;display:flex;justify-content:center;">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <div class="bul-search-wrap">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari buletin..."
                        class="bul-search-input"
                    >
                    <button type="submit" class="bul-search-btn" aria-label="Cari">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Baris 2: Filter pills --}}
        <div class="bul-filter-inner">
            <a href="{{ route('artikel.index', ['q' => request('q')]) }}"
               class="bul-filter__btn {{ !request('kategori') ? 'active' : '' }}">
                Semua Kategori
            </a>

            @foreach($kategoriList as $kat)
                <a href="{{ route('artikel.index', ['kategori' => $kat->id, 'q' => request('q')]) }}"
                   class="bul-filter__btn {{ request('kategori') == $kat->id ? 'active' : '' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        </div>

    </div>

    {{-- ── GRID BULLETIN ───────────────────────── --}}
    <section class="bul-section">
        <div class="bul-grid">
            @forelse($bulletins as $bulletin)
                @include('partials.landing.card-bulletin', ['bulletin' => $bulletin])
            @empty
                <div class="bul-empty">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect width="56" height="56" rx="16" fill="#f0fdf4"/>
                        <path d="M18 20h20M18 28h14M18 36h8" stroke="#86efac" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    <p>Belum ada artikel yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ── PAGINATION ───────────────────────────── --}}
    @if($bulletins->hasPages())
        <div class="bul-pagination">
            {{ $bulletins->appends(request()->query())->links() }}
        </div>
    @endif

</div>

@endsection