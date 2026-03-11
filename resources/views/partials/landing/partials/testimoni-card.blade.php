{{-- ============================================================
     PARTIAL: Testimoni Card
     resources/views/partials/landing/partials/testimoni-card.blade.php
     Variable: $t (object Testimoni), $i (index 0-based)
     ============================================================ --}}

@php
use Illuminate\Support\Str;
$initial = strtoupper(Str::substr($t->nama_pengirim, 0, 1));

$gradients = [
    'linear-gradient(145deg, #34d068 0%, #16a84a 55%, #0d7a35 100%)',
    'linear-gradient(145deg, #2ecc5f 0%, #1aab48 55%, #0e8f38 100%)',
    'linear-gradient(145deg, #3dd674 0%, #18b84f 55%, #0b7a32 100%)',
    'linear-gradient(145deg, #29c45a 0%, #15a043 55%, #0a6e2d 100%)',
    'linear-gradient(145deg, #38d96b 0%, #1db34e 55%, #0f8a3a 100%)',
    'linear-gradient(145deg, #2fc862 0%, #17a645 55%, #0c7831 100%)',
];
$gradient = $gradients[$i % count($gradients)];
@endphp

<div class="swiper-slide h-auto">
    <div class="tcard" style="background: {{ $gradient }}">

        {{-- Dekorasi blob --}}
        <div class="tcard__blob tcard__blob--br"></div>
        <div class="tcard__blob tcard__blob--tl"></div>

        {{-- Ikon quote dekoratif --}}
        <div class="tcard__quote-deco" aria-hidden="true">
            <svg viewBox="0 0 50 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 38V23.222C0 9.889 7.037 2.467 21.111 0l2.815 4.222C16.889 6.333 13.37 10.556 12.667 16.889H22.778V38H0zm27.222 0V23.222C27.222 9.889 34.259 2.467 48.333 0l2.815 4.222C44.111 6.333 40.593 10.556 39.889 16.889H50V38H27.222z" fill="currentColor"/>
            </svg>
        </div>

        {{-- Konten utama --}}
        <div class="tcard__body">
            <p class="tcard__text">{{ Str::limit($t->isi_testimoni, 140) }}</p>

            {{-- Rating bintang --}}
            <div class="tcard__stars" aria-label="Rating {{ $t->rating }} dari 5">
                @for($s = 1; $s <= 5; $s++)
                    <svg class="tcard__star {{ $s <= $t->rating ? 'tcard__star--filled' : 'tcard__star--empty' }}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        </div>

        {{-- Divider --}}
        <div class="tcard__divider"></div>

        {{-- Footer: Avatar hexagon + Nama + Tanggal --}}
        <div class="tcard__footer">
            {{-- Avatar dengan clip-path hexagon --}}
            <div class="tcard__avatar-hex">
                <div class="tcard__avatar-inner">{{ $initial }}</div>
            </div>
            <div class="tcard__info">
                <span class="tcard__name">{{ $t->nama_pengirim }}</span>
                @if($t->pekerjaan)
                    <span class="tcard__job">{{ $t->pekerjaan }}</span>
                @endif
            </div>
            <span class="tcard__date">
                {{ $t->created_at ? $t->created_at->translatedFormat('d M Y') : '' }}
            </span>
        </div>

    </div>
</div>


@once
<style>
/* ── Card Shell ─────────────────────────────────────── */
.tcard {
    position: relative;
    border-radius: 22px;
    overflow: hidden;
    padding: 34px 28px 26px;
    min-height: 290px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow:
        0 8px 30px rgba(14, 110, 45, 0.20),
        0 2px 8px  rgba(0, 0, 0, 0.07);
    transition:
        transform  0.35s cubic-bezier(.22,.8,.25,1),
        box-shadow 0.35s ease;
}
.tcard:hover {
    transform: translateY(-6px) scale(1.012);
    box-shadow:
        0 20px 52px rgba(14, 110, 45, 0.28),
        0 4px 14px  rgba(0, 0, 0, 0.09);
}

/* ── Blob dekoratif ─────────────────────────────────── */
.tcard__blob {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.tcard__blob--br {
    width: 220px; height: 220px;
    bottom: -70px; right: -70px;
    background: rgba(255,255,255,0.07);
}
.tcard__blob--tl {
    width: 120px; height: 120px;
    top: -38px; left: -38px;
    background: rgba(255,255,255,0.05);
}

/* ── Quote dekoratif ────────────────────────────────── */
.tcard__quote-deco {
    position: relative;
    z-index: 1;
    width: 34px;
    color: rgba(255,255,255,0.22);
    margin-bottom: 16px;
    flex-shrink: 0;
}
.tcard__quote-deco svg { width: 100%; height: auto; display: block; }

/* ── Body ───────────────────────────────────────────── */
.tcard__body {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* ── Teks testimoni ─────────────────────────────────── */
.tcard__text {
    font-size: 0.9rem;
    line-height: 1.78;
    color: rgba(255,255,255,0.90);
    font-weight: 400;
    font-style: italic;
    flex: 1;
}

/* ── Bintang ────────────────────────────────────────── */
.tcard__stars        { display: flex; gap: 3px; }
.tcard__star         { width: 16px; height: 16px; }
.tcard__star--filled { color: #fde68a; }
.tcard__star--empty  { color: rgba(255,255,255,0.22); }

/* ── Divider ────────────────────────────────────────── */
.tcard__divider {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 1px;
    background: rgba(255,255,255,0.15);
    margin: 20px 0 18px;
}

/* ── Footer ─────────────────────────────────────────── */
.tcard__footer {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ── Avatar Hexagon ─────────────────────────────────── */
.tcard__avatar-hex {
    width: 46px;
    height: 46px;
    flex-shrink: 0;
    /* Hexagon shape via clip-path */
    clip-path: polygon(50% 0%, 95% 25%, 95% 75%, 50% 100%, 5% 75%, 5% 25%);
    background: rgba(255,255,255,0.28);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.tcard__avatar-inner {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: 0;
    /* Slight frosted look via inner background */
    background: rgba(255,255,255,0.10);
}

.tcard__info {
    flex: 1;
    display: flex; flex-direction: column; gap: 2px;
    min-width: 0;
}

.tcard__name {
    font-size: 0.875rem; font-weight: 700; color: #fff;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.tcard__job {
    font-size: 0.75rem; color: rgba(255,255,255,0.65); font-weight: 400;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.tcard__date {
    font-size: 0.70rem; color: rgba(255,255,255,0.50);
    font-weight: 400; white-space: nowrap; flex-shrink: 0;
}
</style>
@endonce