{{-- ============================================================
     PARTIAL: Testimoni Card – Niat Zakat
     resources/views/partials/landing/partials/testimoni-card.blade.php
     Variable: $t (object Testimoni), $i (index 0-based)
     ============================================================ --}}

@php
use Illuminate\Support\Str;
$initial = strtoupper(Str::substr($t->nama_pengirim, 0, 1));

$avatarColors = [
    '#16a34a',
    '#d97706',
    '#7c3aed',
    '#0d9488',
    '#db2777',
    '#2563eb',
];
$avatarColor = $avatarColors[$i % count($avatarColors)];

$dateLabel = isset($t->created_at)
    ? \Carbon\Carbon::parse($t->created_at)->locale('id')->translatedFormat('M Y')
    : null;
@endphp

<div class="swiper-slide h-auto">
    <div class="tc" tabindex="0" role="article">

        {{-- Body --}}
        <div class="tc__body">

            {{-- Quote dekoratif --}}
            <div class="tc__quote" aria-hidden="true">
                <svg width="28" height="22" viewBox="0 0 32 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 24V14.4C0 10.56 0.96 7.28 2.88 4.56C4.88 1.84 7.76 0.16 11.52 0L13.44 2.88C10.72 3.52 8.72 4.72 7.44 6.48C6.24 8.16 5.68 9.92 5.76 11.76H11.52V24H0ZM18.48 24V14.4C18.48 10.56 19.44 7.28 21.36 4.56C23.36 1.84 26.24 0.16 30 0L31.92 2.88C29.2 3.52 27.2 4.72 25.92 6.48C24.72 8.16 24.16 9.92 24.24 11.76H30V24H18.48Z" fill="currentColor"/>
                </svg>
            </div>

            {{-- Teks --}}
            <p class="tc__text">{{ Str::limit($t->isi_testimoni, 150) }}</p>

            {{-- Stars --}}
            <div class="tc__stars" aria-label="Rating {{ $t->rating }} dari 5">
                @for($s = 1; $s <= 5; $s++)
                    <svg class="tc__star {{ $s <= $t->rating ? 'tc__star--filled' : 'tc__star--empty' }}"
                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        </div>

        {{-- Divider --}}
        <div class="tc__divider"></div>

        {{-- Footer --}}
        <div class="tc__footer">
            <div class="tc__avatar" style="background: {{ $avatarColor }}">
                <span class="tc__avatar-text">{{ $initial }}</span>
            </div>

            <div class="tc__info">
                <span class="tc__name">{{ $t->nama_pengirim }}</span>
                @if($t->pekerjaan)
                    <span class="tc__job">{{ $t->pekerjaan }}</span>
                @endif
            </div>

            @if($dateLabel)
                <span class="tc__date">{{ $dateLabel }}</span>
            @endif
        </div>
    </div>
</div>

@once
<style>
.tc {
    --g:          #16a34a;
    --g2:         #0d9488;
    --gold:       #f59e0b;
    --text-h:     #0f172a;
    --text-b:     #475569;
    --text-m:     #94a3b8;
    --bg:         #ffffff;
    --r:          24px;

    /* shadow states */
    --sh-base:
        0 1px 2px  rgba(0,0,0,0.04),
        0 4px 20px -4px rgba(0,0,0,0.07),
        0 0 0 1px  rgba(15,23,42,0.055);
    --sh-hover:
        0 4px 6px  rgba(0,0,0,0.04),
        0 18px 40px -10px rgba(22,163,74,0.13),
        0 0 0 1px  rgba(22,163,74,0.10);
    --sh-active:
        0 2px 8px  rgba(0,0,0,0.05),
        0 10px 30px -8px rgba(22,163,74,0.18),
        0 0 0 2.5px rgba(22,163,74,0.55);
}

/* ── Base card ── */
.tc {
    position: relative;
    background: var(--bg);
    border-radius: var(--r);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: 100%;
    min-height: 255px;
    box-shadow: var(--sh-base);
    outline: none;
    cursor: pointer;
    transition:
        transform  0.4s cubic-bezier(0.22,1,0.36,1),
        box-shadow 0.4s cubic-bezier(0.22,1,0.36,1);
}

.tc:hover {
    transform: translateY(-6px);
    box-shadow: var(--sh-hover);
}

/* Border hijau muncul saat diklik */
.tc:active,
.tc.tc--active,
.tc:focus-visible {
    transform: translateY(-4px) scale(0.995);
    box-shadow: var(--sh-active);
}

/* ── Body ── */
.tc__body {
    padding: 28px 26px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    flex: 1;
}

/* ── Quote icon ── */
.tc__quote {
    color: var(--g);
    opacity: 0.11;
    line-height: 1;
    transition: opacity 0.3s;
}
.tc:hover .tc__quote,
.tc.tc--active .tc__quote { opacity: 0.2; }

/* ── Text ── */
.tc__text {
    font-size: 0.9375rem;
    line-height: 1.8;
    color: var(--text-b);
    font-weight: 400;
    margin: 0;
    flex: 1;
}

/* ── Stars ── */
.tc__stars {
    display: flex;
    gap: 4px;
    margin-top: auto;
    padding-top: 4px;
}
.tc__star { width: 16px; height: 16px; }
.tc__star--filled {
    color: var(--gold);
    filter: drop-shadow(0 1px 2px rgba(245,158,11,0.2));
}
.tc__star--empty { color: #e2e8f0; }

/* ── Divider ── */
.tc__divider {
    height: 1px;
    margin: 0 26px;
    background: #f1f5f9;
    flex-shrink: 0;
}

/* ── Footer ── */
.tc__footer {
    padding: 18px 26px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

/* ── Avatar ── */
.tc__avatar {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 12px -4px rgba(0,0,0,0.22);
    transition:
        border-radius 0.35s cubic-bezier(0.22,1,0.36,1),
        transform     0.35s cubic-bezier(0.22,1,0.36,1);
}
.tc:hover .tc__avatar,
.tc.tc--active .tc__avatar {
    border-radius: 50%;
    transform: scale(1.07);
}
.tc__avatar-text {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.02em;
    text-shadow: 0 1px 3px rgba(0,0,0,0.12);
    position: relative;
    z-index: 1;
}

/* ── Info ── */
.tc__info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.tc__name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--text-h);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: -0.02em;
    line-height: 1.35;
}
.tc__job {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-m);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.5;
}

/* ── Date ── */
.tc__date {
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--text-m);
    flex-shrink: 0;
    align-self: flex-end;
    margin-bottom: 1px;
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .tc__body    { padding: 22px 20px 16px; }
    .tc__footer  { padding: 14px 20px 20px; }
    .tc__divider { margin: 0 20px; }
    .tc__text    { font-size: 0.9rem; }
    .tc__avatar  { width: 40px; height: 40px; border-radius: 12px; }
    .tc__name    { font-size: 0.875rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tc').forEach(card => {
        card.addEventListener('click', () => {
            const isActive = card.classList.contains('tc--active');
            // Hapus semua active dulu
            document.querySelectorAll('.tc').forEach(c => c.classList.remove('tc--active'));
            // Toggle card yang diklik
            if (!isActive) card.classList.add('tc--active');
        });
    });
});
</script>
@endonce