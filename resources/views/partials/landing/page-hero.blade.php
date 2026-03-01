{{-- resources/views/partials/landing/page-hero.blade.php --}}

@php
    $heroTitle    = $heroTitle    ?? 'Judul Halaman';
    $heroSubtitle = $heroSubtitle ?? null;
@endphp

<style>
    .page-hero-wrap {
        position: relative;
        overflow: hidden;
        background: #f0fdf4;
        clip-path: ellipse(110% 100% at 50% 0%);
        padding-top: 7rem;
        padding-bottom: 5.5rem;
        text-align: center;
    }

    .page-hero-wrap::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 60% at 50% -10%, rgba(34,197,94,0.25) 0%, transparent 70%),
            radial-gradient(ellipse 40% 40% at 10% 80%,  rgba(74,222,128,0.20) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 90% 70%,  rgba(16,185,129,0.18) 0%, transparent 60%),
            linear-gradient(180deg, #ffffff 0%, #f0fdf4 60%, #dcfce7 100%);
        pointer-events: none;
    }

    .hero-waves-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: 0.75;
    }

    .hero-dot-grid {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(22,163,74,0.45) 1.5px, transparent 1.5px);
        background-size: 28px 28px;
        mask-image: radial-gradient(ellipse 90% 95% at 50% 50%, black 30%, transparent 100%);
        -webkit-mask-image: radial-gradient(ellipse 90% 95% at 50% 50%, black 30%, transparent 100%);
        pointer-events: none;
    }

    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        pointer-events: none;
    }
    .hero-orb-1 {
        width: 350px; height: 350px;
        background: rgba(34,197,94,0.22);
        top: -60px; left: -80px;
        animation: orbDrift 10s ease-in-out infinite;
    }
    .hero-orb-2 {
        width: 280px; height: 280px;
        background: rgba(74,222,128,0.18);
        bottom: -40px; right: -60px;
        animation: orbDrift 12s ease-in-out infinite reverse;
    }
    @keyframes orbDrift {
        0%,100% { transform: translate(0,0); }
        50%      { transform: translate(16px, -16px); }
    }

    .hero-particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(34,197,94,0.55);
        animation: floatUp linear infinite;
    }
    .particle:nth-child(1) { width:5px;  height:5px;  left:8%;   bottom:-10px; animation-duration:8s;   animation-delay:0s;   opacity:0.65; }
    .particle:nth-child(2) { width:4px;  height:4px;  left:28%;  bottom:-10px; animation-duration:10s;  animation-delay:2s;   opacity:0.55; background:rgba(74,222,128,0.6); }
    .particle:nth-child(3) { width:6px;  height:6px;  left:50%;  bottom:-10px; animation-duration:9s;   animation-delay:1s;   opacity:0.60; }
    .particle:nth-child(4) { width:4px;  height:4px;  left:68%;  bottom:-10px; animation-duration:11s;  animation-delay:3.5s; opacity:0.50; background:rgba(16,185,129,0.6); }
    .particle:nth-child(5) { width:5px;  height:5px;  left:82%;  bottom:-10px; animation-duration:7.5s; animation-delay:1.5s; opacity:0.65; }
    .particle:nth-child(6) { width:3px;  height:3px;  left:42%;  bottom:-10px; animation-duration:12s;  animation-delay:5s;   opacity:0.45; }

    @keyframes floatUp {
        0%   { transform: translateY(0) translateX(0); opacity: 0; }
        10%  { opacity: 1; }
        85%  { opacity: 0.6; }
        100% { transform: translateY(-430px) translateX(25px); opacity: 0; }
    }

    .hero-sparkle {
        position: absolute;
        pointer-events: none;
        animation: sparkleAnim ease-in-out infinite;
    }
    .hero-sparkle svg { fill: rgba(34,197,94,0.75); }
    .hero-sparkle-1 { top: 20%; left: 8%;   width: 15px; animation-duration: 4.5s; animation-delay: 0s;   }
    .hero-sparkle-2 { top: 35%; right: 9%;  width: 11px; animation-duration: 5.5s; animation-delay: 1.8s; }
    .hero-sparkle-3 { top: 65%; left: 16%;  width: 9px;  animation-duration: 4.8s; animation-delay: 0.8s; }
    .hero-sparkle-4 { top: 25%; right: 18%; width: 13px; animation-duration: 6s;   animation-delay: 2.8s; }

    @keyframes sparkleAnim {
        0%,100% { opacity: 0; transform: scale(0) rotate(0deg); }
        40%,60% { opacity: 1; transform: scale(1) rotate(180deg); }
    }

    .hero-rings {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .hero-ring {
        position: absolute;
        border-radius: 50%;
        border: 1.5px solid rgba(34,197,94,0.30);
        animation: ringExpand linear infinite;
    }
    .hero-ring-1 { width: 80px; height: 80px; top: 18%; left: 6%;  animation-duration: 7s;  animation-delay: 0s; }
    .hero-ring-2 { width: 60px; height: 60px; top: 52%; right: 7%; animation-duration: 9s;  animation-delay: 3s; }

    @keyframes ringExpand {
        0%   { transform: scale(0.7); opacity: 0.6; }
        100% { transform: scale(2.8); opacity: 0; }
    }

    .hero-shapes {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .hero-shape {
        position: absolute;
        opacity: 0;
        animation: shapeFloat ease-in-out infinite;
    }
    .shape-tri {
        width: 0; height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 11px solid rgba(34,197,94,0.40);
    }
    .shape-dia {
        width: 9px; height: 9px;
        background: rgba(74,222,128,0.38);
        transform: rotate(45deg);
    }
    .shape-sq {
        width: 8px; height: 8px;
        border: 2px solid rgba(16,185,129,0.40);
    }
    .hero-shape-1 { top: 15%; left: 22%;  animation-duration: 11s; animation-delay: 0s;   }
    .hero-shape-2 { top: 60%; right: 8%;  animation-duration: 13s; animation-delay: 3.5s; }
    .hero-shape-3 { top: 40%; left: 58%;  animation-duration: 10s; animation-delay: 1.5s; }

    @keyframes shapeFloat {
        0%   { opacity: 0; transform: translateY(0) rotate(0deg); }
        20%  { opacity: 0.8; }
        50%  { transform: translateY(-16px) rotate(180deg); }
        80%  { opacity: 0.8; }
        100% { opacity: 0; transform: translateY(-28px) rotate(360deg); }
    }

    /* ══ CONTENT ══════════════════════════════ */
    .page-hero-inner {
        position: relative;
        z-index: 10;
        max-width: 44rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ══ TITLE ════════════════════════════════ */
    .hero-h1 {
        font-size: clamp(2.2rem, 5.5vw, 3.5rem);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin: 0;
        color: #16a34a;
    }

    .hero-word {
        display: inline-block;
        margin: 0 0.05em;
        color: #16a34a;
        opacity: 0;
        transform: translateY(24px);
        animation: wordReveal 0.6s cubic-bezier(0.22,1,0.36,1) both;
    }

    @keyframes wordReveal {
        0%   { opacity: 0; transform: translateY(24px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* ══ UNDERLINE ════════════════════════════ */
    .hero-underline {
        display: block;
        width: 48px;
        height: 3px;
        border-radius: 99px;
        background: linear-gradient(90deg, #16a34a, #4ade80, #16a34a);
        background-size: 200% 100%;
        margin: 1.1rem auto 0;
        box-shadow: 0 0 12px rgba(34,197,94,0.35);
        animation:
            lineGrow 0.55s cubic-bezier(0.22,1,0.36,1) 0.7s both,
            lineShimmer 2.5s linear 1.5s infinite;
    }
    @keyframes lineGrow {
        0%   { opacity: 0; transform: scaleX(0); }
        100% { opacity: 1; transform: scaleX(1); }
    }
    @keyframes lineShimmer {
        0%   { background-position: 0% 0; }
        100% { background-position: 200% 0; }
    }

    /* ══ SUBTITLE ═════════════════════════════ */
    .hero-subtitle {
        margin-top: 1.4rem;
        color: #27612e;
        font-size: 1.05rem;
        font-weight: 450;
        line-height: 1.85;
        max-width: 38rem;
        margin-left: auto;
        margin-right: auto;
        letter-spacing: 0.01em;
        opacity: 0;
        transform: translateY(16px);
        animation: heroUp 0.65s cubic-bezier(0.22,1,0.36,1) 0.5s both;
    }

    @keyframes heroUp {
        0%   { opacity: 0; transform: translateY(16px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="page-hero-wrap">

    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-dot-grid"></div>

    <svg class="hero-waves-bg" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,160 C240,220 480,80 720,160 C960,240 1200,100 1440,160 L1440,0 L0,0 Z"
              fill="rgba(34,197,94,0.15)" />
        <path d="M0,200 C200,140 440,260 720,200 C1000,140 1240,260 1440,200 L1440,0 L0,0 Z"
              fill="rgba(74,222,128,0.12)" />
    </svg>

    <div class="hero-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="hero-rings">
        <div class="hero-ring hero-ring-1"></div>
        <div class="hero-ring hero-ring-2"></div>
    </div>

    <div class="hero-shapes">
        <div class="hero-shape hero-shape-1 shape-tri"></div>
        <div class="hero-shape hero-shape-2 shape-dia"></div>
        <div class="hero-shape hero-shape-3 shape-sq"></div>
    </div>

    <div class="hero-sparkle hero-sparkle-1">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2 L13.5 10 L22 12 L13.5 14 L12 22 L10.5 14 L2 12 L10.5 10 Z"/></svg>
    </div>
    <div class="hero-sparkle hero-sparkle-2">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2 L13.5 10 L22 12 L13.5 14 L12 22 L10.5 14 L2 12 L10.5 10 Z"/></svg>
    </div>
    <div class="hero-sparkle hero-sparkle-3">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2 L13.5 10 L22 12 L13.5 14 L12 22 L10.5 14 L2 12 L10.5 10 Z"/></svg>
    </div>
    <div class="hero-sparkle hero-sparkle-4">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2 L13.5 10 L22 12 L13.5 14 L12 22 L10.5 14 L2 12 L10.5 10 Z"/></svg>
    </div>

    <div class="page-hero-inner">

        <h1 class="hero-h1" aria-label="{{ $heroTitle }}">
            @php
                $words = explode(' ', $heroTitle);
                $baseDelay = 0.2;
            @endphp
            @foreach($words as $i => $word)
                <span class="hero-word" style="animation-delay: {{ $baseDelay + ($i * 0.12) }}s">{{ $word }}</span>
            @endforeach
        </h1>

        <span class="hero-underline"></span>

        @if($heroSubtitle)
            <p class="hero-subtitle">{{ $heroSubtitle }}</p>
        @endif

    </div>

</section>