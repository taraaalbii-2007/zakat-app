{{-- resources/views/partials/landing/card-bulletin.blade.php --}}
{{--
    PROPS:
    $bulletin = object Bulletin (dengan relasi kategoriBulletin & author sudah di-load)
--}}

@php
    $thumbnailUrl  = $bulletin->thumbnail ? asset('storage/' . $bulletin->thumbnail) : null;
    $kategoriNama  = $bulletin->kategoriBulletin->nama_kategori ?? null;
    $kontenBersih  = strip_tags($bulletin->konten ?? '');
    $excerpt       = \Illuminate\Support\Str::limit($kontenBersih, 130);
    $authorNama    = $bulletin->author->nama ?? $bulletin->author->name ?? 'Admin';
    $tanggal       = $bulletin->published_at
                        ? \Carbon\Carbon::parse($bulletin->published_at)->isoFormat('DD MMM YYYY')
                        : \Carbon\Carbon::parse($bulletin->created_at)->isoFormat('DD MMM YYYY');
    $views         = $bulletin->view_count ?? 0;
    $url           = route('artikel.show', $bulletin->uuid);
@endphp

<article class="bul-card">

    {{-- ── THUMBNAIL ─────────────────────────────── --}}
    <a href="{{ $url }}" class="bul-card__thumb-link" tabindex="-1" aria-hidden="true">
        @if($thumbnailUrl)
            <img
                src="{{ $thumbnailUrl }}"
                alt="{{ $bulletin->judul }}"
                class="bul-card__thumb"
                loading="lazy"
            >
        @else
            {{-- Placeholder jika tidak ada thumbnail --}}
            <div class="bul-card__thumb-placeholder">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="48" height="48" rx="10" fill="#dcfce7"/>
                    <path d="M10 36 L18 22 L24 30 L30 22 L38 36 Z" fill="#86efac"/>
                    <circle cx="32" cy="16" r="5" fill="#bbf7d0"/>
                </svg>
                <span>Tidak ada gambar</span>
            </div>
        @endif
    </a>

    {{-- ── BODY ─────────────────────────────────── --}}
    <div class="bul-card__body">

        {{-- Kategori --}}
        @if($kategoriNama)
            <span class="bul-card__kategori">{{ $kategoriNama }}</span>
        @endif

        {{-- Meta: tanggal & views --}}
        <div class="bul-card__meta">
            <span class="bul-card__meta-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                {{ $tanggal }}
            </span>
            <span class="bul-card__meta-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
                {{ $views }}
            </span>
        </div>

        {{-- Judul --}}
        <h3 class="bul-card__judul">
            <a href="{{ $url }}">{{ $bulletin->judul }}</a>
        </h3>

        {{-- Excerpt --}}
        @if($excerpt)
            <p class="bul-card__excerpt">{{ $excerpt }}</p>
        @endif

        {{-- ── FOOTER ─────────────────────────────── --}}
        <div class="bul-card__footer">
            {{-- Author --}}
            <div class="bul-card__author">
                <span class="bul-card__author-avatar" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <span class="bul-card__author-nama">{{ $authorNama }}</span>
            </div>

            {{-- Tombol baca --}}
            <a href="{{ $url }}" class="bul-card__baca" aria-label="Baca selengkapnya: {{ $bulletin->judul }}">
                Baca selengkapnya
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>

    </div>
</article>