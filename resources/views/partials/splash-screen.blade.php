{{-- ============================================
     SPLASH SCREEN - Aplikasi Niat Zakat
     Lokasi file: resources/views/partials/splash-screen.blade.php
     Panggil di app.blade.php dengan: @include('partials.splash-screen')
     ============================================ --}}

@php
    $config = \App\Models\KonfigurasiAplikasi::getConfig();
@endphp

<style>
    /* ============================================
       SPLASH SCREEN - ZAKAT DIGITAL
       Tema: Hijau #2d6936 & Emas — Elegan & Islami
    ============================================ */

    :root {
        --sp-gold:        #F0C060;
        --sp-gold-light:  #F5D080;
        --sp-gold-pale:   #FBE9B0;
        --sp-green:       #2d6936;
        --sp-green-dark:  #1e4a24;
        --sp-green-deep:  #163619;
        --sp-green-mid:   #2d6936;
        --sp-green-light: #3d8f4a;
        --sp-green-pale:  #78cc8a;
        --sp-text:        #ffffff;
        --sp-text-soft:   rgba(255,255,255,0.75);
    }

    /* ---- Base ---- */
    #splash-zakat {
        position: fixed;
        inset: 0;
        background: linear-gradient(150deg, #163619 0%, #2d6936 55%, #1e4a24 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        overflow: hidden;
        font-family: 'Georgia', 'Times New Roman', serif;
        transition: opacity 0.75s cubic-bezier(0.4, 0, 0.2, 1),
                    transform 0.75s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #splash-zakat.sp-hiding {
        opacity: 0;
        transform: scale(1.04);
        pointer-events: none;
    }
    #splash-zakat.sp-hidden {
        display: none !important;
    }

    /* ---- Background glow tengah ---- */
    .sp-bg-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: radial-gradient(
            circle,
            rgba(240, 192, 96, 0.20) 0%,
            rgba(120, 204, 138, 0.12) 45%,
            transparent 70%
        );
        animation: sp-glow-breath 3s ease-in-out infinite;
        pointer-events: none;
    }
    @keyframes sp-glow-breath {
        0%, 100% { opacity: 0.8; transform: translate(-50%, -50%) scale(1); }
        50%       { opacity: 1;   transform: translate(-50%, -50%) scale(1.10); }
    }

    /* ---- Cincin geometris mengembang ---- */
    .sp-geo-ring {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(26, 107, 60, 0.10);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.2);
        opacity: 0;
        animation: sp-ring-expand 3s ease-out forwards;
    }
    .sp-geo-ring:nth-child(1) { width: 260px; height: 260px; animation-delay: 0.00s; border-color: rgba(240,192,96,0.25); }
    .sp-geo-ring:nth-child(2) { width: 360px; height: 360px; animation-delay: 0.18s; border-color: rgba(120,204,138,0.18); }
    .sp-geo-ring:nth-child(3) { width: 460px; height: 460px; animation-delay: 0.36s; border-color: rgba(240,192,96,0.15); }
    .sp-geo-ring:nth-child(4) { width: 560px; height: 560px; animation-delay: 0.54s; border-color: rgba(120,204,138,0.10); }
    .sp-geo-ring:nth-child(5) { width: 660px; height: 660px; animation-delay: 0.72s; border-color: rgba(240,192,96,0.08); }
    @keyframes sp-ring-expand {
        0%   { opacity: 0;   transform: translate(-50%, -50%) scale(0.2); }
        30%  { opacity: 1; }
        100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
    }

    /* ---- Partikel naik ---- */
    .sp-particle {
        position: absolute;
        border-radius: 50%;
        opacity: 0;
        animation: sp-particle-rise 3.5s ease-out forwards;
        pointer-events: none;
    }
    .sp-particle:nth-child(1)  { width: 3px; height: 3px; background: var(--sp-gold);        left: 18%; bottom: 28%; animation-delay: 0.20s; }
    .sp-particle:nth-child(2)  { width: 2px; height: 2px; background: var(--sp-green-light);  left: 32%; bottom: 22%; animation-delay: 0.45s; }
    .sp-particle:nth-child(3)  { width: 4px; height: 4px; background: var(--sp-green-pale);   left: 50%; bottom: 18%; animation-delay: 0.30s; }
    .sp-particle:nth-child(4)  { width: 2px; height: 2px; background: var(--sp-gold);         left: 66%; bottom: 32%; animation-delay: 0.65s; }
    .sp-particle:nth-child(5)  { width: 3px; height: 3px; background: var(--sp-green-light);  left: 80%; bottom: 25%; animation-delay: 0.40s; }
    .sp-particle:nth-child(6)  { width: 2px; height: 2px; background: var(--sp-green-pale);   left: 13%; bottom: 42%; animation-delay: 0.90s; }
    .sp-particle:nth-child(7)  { width: 4px; height: 4px; background: var(--sp-gold);         left: 86%; bottom: 38%; animation-delay: 0.55s; }
    .sp-particle:nth-child(8)  { width: 2px; height: 2px; background: var(--sp-green-light);  left: 40%; bottom: 48%; animation-delay: 1.10s; }
    .sp-particle:nth-child(9)  { width: 3px; height: 3px; background: var(--sp-green-pale);   left: 72%; bottom: 20%; animation-delay: 0.75s; }
    .sp-particle:nth-child(10) { width: 2px; height: 2px; background: var(--sp-gold);         left: 26%; bottom: 52%; animation-delay: 1.30s; }
    .sp-particle:nth-child(11) { width: 3px; height: 3px; background: var(--sp-green-light);  left: 58%; bottom: 45%; animation-delay: 1.00s; }
    .sp-particle:nth-child(12) { width: 2px; height: 2px; background: var(--sp-green-pale);   left: 92%; bottom: 48%; animation-delay: 0.50s; }
    @keyframes sp-particle-rise {
        0%   { opacity: 0; transform: translateY(0) scale(0); }
        20%  { opacity: 1; transform: translateY(-18px) scale(1); }
        70%  { opacity: 0.5; transform: translateY(-55px) scale(0.8); }
        100% { opacity: 0; transform: translateY(-95px) scale(0.2); }
    }

    /* ---- Logo stage ---- */
    .sp-logo-wrap {
        position: relative;
        z-index: 20;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.8rem;
    }

    .sp-logo-stage {
        position: relative;
        width: 130px;
        height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: sp-float 5s ease-in-out 1.5s infinite;
    }
    @keyframes sp-float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-9px); }
    }

    /* Orbit luar — emas, searah jarum jam */
    .sp-orbit-a {
        position: absolute;
        width: 164px;
        height: 164px;
        border-radius: 50%;
        border: 1.5px dashed rgba(200, 151, 58, 0.30);
        animation: sp-cw 14s linear infinite;
    }
    .sp-orbit-a::before,
    .sp-orbit-a::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--sp-gold);
        box-shadow: 0 0 12px 3px rgba(200, 151, 58, 0.8);
    }
    .sp-orbit-a::before { top: -4px;  left: calc(50% - 4px); }
    .sp-orbit-a::after  { bottom: -4px; left: calc(50% - 4px); }

    /* Orbit tengah — hijau, berlawanan */
    .sp-orbit-b {
        position: absolute;
        width: 148px;
        height: 148px;
        border-radius: 50%;
        border: 1px solid rgba(120, 204, 138, 0.35);
        animation: sp-ccw 10s linear infinite;
    }
    .sp-orbit-b::before,
    .sp-orbit-b::after {
        content: '';
        position: absolute;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--sp-green-pale);
        box-shadow: 0 0 8px 2px rgba(120, 204, 138, 0.9);
    }
    .sp-orbit-b::before { top: -2.5px; left: calc(50% - 2.5px); }
    .sp-orbit-b::after  { bottom: -2.5px; left: calc(50% - 2.5px); }

    /* Frame conic-gradient berputar */
    .sp-ring-grad {
        position: absolute;
        width: 128px;
        height: 128px;
        border-radius: 50%;
        padding: 3px;
        background: conic-gradient(
            from 0deg,
            var(--sp-gold),
            var(--sp-gold-light),
            var(--sp-green-pale),
            var(--sp-green-light),
            var(--sp-gold),
            var(--sp-gold-light),
            var(--sp-gold)
        );
        animation: sp-cw 4.5s linear infinite;
        box-sizing: border-box;
    }
    .sp-ring-grad::after {
        content: '';
        position: absolute;
        inset: 3px;
        background: linear-gradient(150deg, #163619 0%, #2d6936 55%, #1e4a24 100%);
        border-radius: 50%;
    }

    /* Denyut di belakang logo */
    .sp-pulse {
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: 0;
        animation: sp-pulse-out 2.4s ease-out infinite;
    }
    .sp-pulse:nth-child(1) { background: radial-gradient(circle, rgba(240,192,96,0.40)   0%, transparent 70%); animation-delay: 0.0s; }
    .sp-pulse:nth-child(2) { background: radial-gradient(circle, rgba(120,204,138,0.30)  0%, transparent 70%); animation-delay: 0.8s; }
    .sp-pulse:nth-child(3) { background: radial-gradient(circle, rgba(240,192,96,0.25)   0%, transparent 70%); animation-delay: 1.6s; }
    @keyframes sp-pulse-out {
        0%   { transform: scale(0.8); opacity: 0.9; }
        70%  { transform: scale(1.7); opacity: 0; }
        100% { transform: scale(1.7); opacity: 0; }
    }

    /* Logo gambar bulat */
    .sp-logo-img {
        position: relative;
        z-index: 10;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        background: #2d6936;
        opacity: 0;
        transform: scale(0.25) rotate(-120deg);
        filter: blur(10px);
        animation: sp-logo-appear 1.3s cubic-bezier(0.34, 1.56, 0.64, 1) 0.5s forwards;
        box-shadow: 0 8px 32px rgba(0,0,0,0.40), 0 0 0 2px rgba(240,192,96,0.20);
    }
    @keyframes sp-logo-appear {
        0%   { opacity: 0; transform: scale(0.25) rotate(-120deg); filter: blur(10px); }
        55%  { opacity: 1; transform: scale(1.1)  rotate(6deg);   filter: blur(0); }
        78%  { transform: scale(0.96) rotate(-3deg); }
        100% { opacity: 1; transform: scale(1)    rotate(0deg);   filter: drop-shadow(0 8px 24px rgba(200,151,58,0.35)); }
    }

    /* ---- Efek melebur (sweep cahaya) ---- */
    .sp-melt {
        position: absolute;
        z-index: 11;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: conic-gradient(
            from 0deg,
            transparent 0%,
            rgba(200, 151, 58, 0.55) 15%,
            rgba(255, 255, 255, 0.85) 30%,
            transparent 50%,
            rgba(200, 151, 58, 0.30) 75%,
            transparent 100%
        );
        mix-blend-mode: overlay;
        pointer-events: none;
        animation: sp-melt-spin 2.8s ease-in-out 1.1s infinite;
    }
    @keyframes sp-melt-spin {
        0%   { transform: rotate(0deg);   opacity: 0; }
        10%  { opacity: 1; }
        60%  { opacity: 0.6; }
        90%  { opacity: 0.1; }
        100% { transform: rotate(360deg); opacity: 0; }
    }

    /* ---- Percikan saat muncul ---- */
    .sp-spark {
        position: absolute;
        z-index: 12;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--sp-gold);
        box-shadow: 0 0 6px 2px rgba(200,151,58,0.7);
        opacity: 0;
        animation: sp-spark-fly 0.9s ease-out forwards;
    }
    .sp-spark:nth-child(1) { animation-delay: 0.55s; --sdx:  0px;   --sdy: -26px; }
    .sp-spark:nth-child(2) { animation-delay: 0.62s; --sdx:  18px;  --sdy: -18px; }
    .sp-spark:nth-child(3) { animation-delay: 0.69s; --sdx:  26px;  --sdy:   0px; background: var(--sp-green-pale); box-shadow: 0 0 6px 2px rgba(76,175,114,0.7); }
    .sp-spark:nth-child(4) { animation-delay: 0.76s; --sdx:  18px;  --sdy:  18px; }
    .sp-spark:nth-child(5) { animation-delay: 0.83s; --sdx:  0px;   --sdy:  26px; }
    .sp-spark:nth-child(6) { animation-delay: 0.90s; --sdx: -18px;  --sdy:  18px; }
    .sp-spark:nth-child(7) { animation-delay: 0.97s; --sdx: -26px;  --sdy:   0px; background: var(--sp-green-pale); box-shadow: 0 0 6px 2px rgba(76,175,114,0.7); }
    .sp-spark:nth-child(8) { animation-delay: 1.04s; --sdx: -18px;  --sdy: -18px; }
    @keyframes sp-spark-fly {
        0%   { opacity: 0; transform: translate(0, 0) scale(0); }
        25%  { opacity: 1; transform: translate(calc(var(--sdx) * 0.5), calc(var(--sdy) * 0.5)) scale(1.3); }
        70%  { opacity: 0.5; }
        100% { opacity: 0; transform: translate(calc(var(--sdx) * 2), calc(var(--sdy) * 2)) scale(0.3); }
    }

    /* Shared spin */
    @keyframes sp-cw  { to { transform: rotate(360deg); } }
    @keyframes sp-ccw { to { transform: rotate(-360deg); } }

    /* ---- Teks & dekorasi ---- */
    .sp-text-wrap {
        text-align: center;
        opacity: 0;
        transform: translateY(18px);
        animation: sp-text-rise 0.9s cubic-bezier(0.34, 1.4, 0.64, 1) 1.2s forwards;
        position: relative;
        z-index: 20;
    }
    .sp-app-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.06em;
        line-height: 1.2;
        margin-bottom: 0.2rem;
        text-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }
    .sp-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin: 0.5rem auto;
        opacity: 0;
        animation: sp-text-rise 0.8s ease 1.45s forwards;
    }
    .sp-divider-line {
        width: 44px;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--sp-gold));
    }
    .sp-divider-line:last-child {
        background: linear-gradient(90deg, var(--sp-gold), transparent);
    }
    .sp-divider-gem {
        width: 7px;
        height: 7px;
        background: var(--sp-gold);
        transform: rotate(45deg);
        box-shadow: 0 0 10px 2px rgba(200,151,58,0.55);
    }
    .sp-tagline {
        font-size: 0.70rem;
        color: var(--sp-gold-light);
        letter-spacing: 0.20em;
        text-transform: uppercase;
        font-weight: 400;
        opacity: 0.85;
    }

    /* ---- Progress bar ---- */
    .sp-progress {
        position: absolute;
        bottom: 2.6rem;
        left: 50%;
        transform: translateX(-50%);
        width: 130px;
        z-index: 20;
        opacity: 0;
        animation: sp-text-rise 0.5s ease 1.5s forwards;
    }
    .sp-progress-track {
        width: 100%;
        height: 2px;
        background: rgba(255,255,255,0.15);
        border-radius: 2px;
        overflow: hidden;
    }
    .sp-progress-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--sp-green-pale), var(--sp-gold));
        border-radius: 2px;
        box-shadow: 0 0 10px rgba(240,192,96,0.60);
        animation: sp-progress-run 2.2s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
    }
    @keyframes sp-progress-run { to { width: 100%; } }
    .sp-progress-lbl {
        text-align: center;
        font-size: 0.58rem;
        color: rgba(240,192,96,0.60);
        margin-top: 0.45rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
    }

    @keyframes sp-text-rise {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ---- Responsive ---- */
    @media (max-width: 640px) {
        .sp-logo-stage { width: 108px; height: 108px; }
        .sp-orbit-a    { width: 140px; height: 140px; }
        .sp-orbit-b    { width: 126px; height: 126px; }
        .sp-ring-grad  { width: 108px; height: 108px; }
        .sp-logo-img   { width: 92px;  height: 92px; }
        .sp-melt       { width: 92px;  height: 92px; }
        .sp-app-name   { font-size: 1.10rem; }
    }
</style>

<div id="splash-zakat" role="status" aria-label="Memuat aplikasi">

    {{-- Latar dekoratif --}}
    <div class="sp-bg-glow"></div>

    <div style="position:absolute;inset:0;overflow:hidden;pointer-events:none;">
        <div class="sp-geo-ring"></div>
        <div class="sp-geo-ring"></div>
        <div class="sp-geo-ring"></div>
        <div class="sp-geo-ring"></div>
        <div class="sp-geo-ring"></div>
    </div>

    <div style="position:absolute;inset:0;pointer-events:none;">
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
        <div class="sp-particle"></div>
    </div>

    {{-- Logo utama --}}
    <div class="sp-logo-wrap">

        <div class="sp-logo-stage">

            {{-- Denyut --}}
            <div class="sp-pulse"></div>
            <div class="sp-pulse"></div>
            <div class="sp-pulse"></div>

            {{-- Orbit --}}
            <div class="sp-orbit-a"></div>
            <div class="sp-orbit-b"></div>

            {{-- Frame berputar --}}
            <div class="sp-ring-grad"></div>

            {{-- Gambar logo bulat --}}
            <img
                id="sp-logo-img"
                src="{{ $config->logo_aplikasi ? asset('storage/' . $config->logo_aplikasi) : asset('assets/images/default-logo.png') }}"
                alt="{{ $config->nama_aplikasi ?? 'Zakat Digital' }}"
                class="sp-logo-img"
                onerror="this.src='{{ asset('assets/images/default-logo.png') }}'"
            >

            {{-- Efek melebur --}}
            <div class="sp-melt"></div>

            {{-- Percikan --}}
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
            <div class="sp-spark"></div>
        </div>

        {{-- Nama & tagline --}}
        <div class="sp-text-wrap">
            <div class="sp-app-name">{{ $config->nama_aplikasi ?? 'Zakat Digital' }}</div>
            <div class="sp-divider">
                <div class="sp-divider-line"></div>
                <div class="sp-divider-gem"></div>
                <div class="sp-divider-line"></div>
            </div>
            <div class="sp-tagline">{{ $config->tagline ?? 'Sistem Zakat Digital' }}</div>
        </div>
    </div>

</div>

<script>
(function () {
    var splash = document.getElementById('splash-zakat');
    if (!splash) return;

    var hidden  = false;
    var MIN_MS  = 2400;   // tampil minimal 2.4 detik agar animasi mulus
    var MAX_MS  = 4500;   // failsafe 4.5 detik
    var t0      = Date.now();

    function hideSplash() {
        if (hidden) return;
        hidden = true;

        splash.classList.add('sp-hiding');

        setTimeout(function () {
            splash.classList.add('sp-hidden');

            // Fade-in konten utama
            var main = document.getElementById('mainContent')
                     || document.querySelector('[data-main-content]')
                     || document.querySelector('main');
            if (main && main.style) {
                main.style.transition = 'opacity 0.6s ease';
                main.style.opacity    = '1';
            }
        }, 800);
    }

    function scheduleHide() {
        var elapsed   = Date.now() - t0;
        var remaining = Math.max(0, MIN_MS - elapsed);
        setTimeout(hideSplash, remaining);
    }

    // Tunggu halaman selesai load
    if (document.readyState === 'complete') {
        scheduleHide();
    } else {
        window.addEventListener('load', scheduleHide);
    }

    // Failsafe
    setTimeout(hideSplash, MAX_MS);
})();
</script>