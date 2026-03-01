{{-- resources/views/partials/landing/page-hero.blade.php --}}

@php
    $heroTitle    = $heroTitle    ?? 'Judul Halaman';
    $heroSubtitle = $heroSubtitle ?? null;
@endphp

<style>
    /* ══════════════════════════════════════════
       PAGE HERO — Light, Curved, Textured
    ══════════════════════════════════════════ */

    .page-hero-wrap {
        position: relative;
        overflow: hidden;
        background: #f0fdf4;
        clip-path: ellipse(110% 100% at 50% 0%);
        padding-top: 7rem;
        padding-bottom: 5.5rem;
        text-align: center;
    }

    /* ── Layer 1: Gradient base ─────────────── */
    .page-hero-wrap::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 60% at 50% -10%, rgba(34,197,94,0.20) 0%, transparent 70%),
            radial-gradient(ellipse 40% 40% at 10% 80%,  rgba(74,222,128,0.12) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 90% 70%,  rgba(16,185,129,0.10) 0%, transparent 60%),
            linear-gradient(180deg, #ffffff 0%, #f0fdf4 60%, #dcfce7 100%);
        pointer-events: none;
    }

    /* ── Layer 2: Wavy SVG lines ── */
    .hero-waves-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: 0.4;
    }

    /* ── Layer 3: Dot grid ───────────────────── */
    .hero-dot-grid {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(22,163,74,0.20) 1.2px, transparent 1.2px);
        background-size: 32px 32px;
        mask-image: radial-gradient(ellipse 75% 85% at 50% 50%, black 20%, transparent 100%);
        -webkit-mask-image: radial-gradient(ellipse 75% 85% at 50% 50%, black 20%, transparent 100%);
        pointer-events: none;
    }

    /* ── Layer 4: Floating orbs ──────────────── */
    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        pointer-events: none;
    }
    .hero-orb-1 {
        width: 300px; height: 300px;
        background: rgba(34,197,94,0.12);
        top: -60px; left: -80px;
        animation: orbDrift 10s ease-in-out infinite;
    }
    .hero-orb-2 {
        width: 240px; height: 240px;
        background: rgba(74,222,128,0.09);
        bottom: -40px; right: -60px;
        animation: orbDrift 12s ease-in-out infinite reverse;
    }
    @keyframes orbDrift {
        0%,100% { transform: translate(0,0); }
        50%      { transform: translate(16px, -16px); }
    }

    /* ── Floating Particles (6 buah, lebih jarang) ── */
    .hero-particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(34,197,94,0.45);
        animation: floatUp linear infinite;
    }
    .particle:nth-child(1) { width:5px;  height:5px;  left:8%;   bottom:-10px; animation-duration:8s;   animation-delay:0s;   opacity:0.55; }
    .particle:nth-child(2) { width:4px;  height:4px;  left:28%;  bottom:-10px; animation-duration:10s;  animation-delay:2s;   opacity:0.45; background:rgba(74,222,128,0.5); }
    .particle:nth-child(3) { width:6px;  height:6px;  left:50%;  bottom:-10px; animation-duration:9s;   animation-delay:1s;   opacity:0.50; }
    .particle:nth-child(4) { width:4px;  height:4px;  left:68%;  bottom:-10px; animation-duration:11s;  animation-delay:3.5s; opacity:0.40; background:rgba(16,185,129,0.5); }
    .particle:nth-child(5) { width:5px;  height:5px;  left:82%;  bottom:-10px; animation-duration:7.5s; animation-delay:1.5s; opacity:0.55; }
    .particle:nth-child(6) { width:3px;  height:3px;  left:42%;  bottom:-10px; animation-duration:12s;  animation-delay:5s;   opacity:0.35; }

    @keyframes floatUp {
        0%   { transform: translateY(0) translateX(0); opacity: 0; }
        10%  { opacity: 1; }
        85%  { opacity: 0.6; }
        100% { transform: translateY(-430px) translateX(25px); opacity: 0; }
    }

    /* ── Sparkle Stars (4 buah, lebih halus) ── */
    .hero-sparkle {
        position: absolute;
        pointer-events: none;
        animation: sparkleAnim ease-in-out infinite;
    }
    .hero-sparkle svg { fill: rgba(34,197,94,0.45); }

    .hero-sparkle-1 { top: 20%; left: 8%;   width: 15px; animation-duration: 4.5s; animation-delay: 0s;   }
    .hero-sparkle-2 { top: 35%; right: 9%;  width: 11px; animation-duration: 5.5s; animation-delay: 1.8s; }
    .hero-sparkle-3 { top: 65%; left: 16%;  width: 9px;  animation-duration: 4.8s; animation-delay: 0.8s; }
    .hero-sparkle-4 { top: 25%; right: 18%; width: 13px; animation-duration: 6s;   animation-delay: 2.8s; }

    @keyframes sparkleAnim {
        0%,100% { opacity: 0; transform: scale(0) rotate(0deg); }
        40%,60% { opacity: 0.8; transform: scale(1) rotate(180deg); }
    }

    /* ── Expanding rings (2 buah, di pinggir saja) ── */
    .hero-rings {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .hero-ring {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(34,197,94,0.13);
        animation: ringExpand linear infinite;
    }
    .hero-ring-1 { width: 80px; height: 80px; top: 18%; left: 6%;  animation-duration: 7s;  animation-delay: 0s; }
    .hero-ring-2 { width: 60px; height: 60px; top: 52%; right: 7%; animation-duration: 9s;  animation-delay: 3s; }

    @keyframes ringExpand {
        0%   { transform: scale(0.7); opacity: 0.55; }
        100% { transform: scale(2.5); opacity: 0; }
    }

    /* ── Floating geometric shapes (3 buah, sangat halus) ── */
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
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 9px solid rgba(34,197,94,0.22);
    }
    .shape-dia {
        width: 8px; height: 8px;
        background: rgba(74,222,128,0.20);
        transform: rotate(45deg);
    }
    .shape-sq {
        width: 7px; height: 7px;
        border: 1.5px solid rgba(16,185,129,0.22);
    }
    .hero-shape-1 { top: 15%; left: 22%;  animation-duration: 11s; animation-delay: 0s;   }
    .hero-shape-2 { top: 60%; right: 8%;  animation-duration: 13s; animation-delay: 3.5s; }
    .hero-shape-3 { top: 40%; left: 58%;  animation-duration: 10s; animation-delay: 1.5s; }

    @keyframes shapeFloat {
        0%   { opacity: 0; transform: translateY(0) rotate(0deg); }
        20%  { opacity: 0.7; }
        50%  { transform: translateY(-16px) rotate(180deg); }
        80%  { opacity: 0.7; }
        100% { opacity: 0; transform: translateY(-28px) rotate(360deg); }
    }

    /* ── Content ─────────────────────────────── */
    .page-hero-inner {
        position: relative;
        z-index: 10;
        max-width: 44rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ── Title ───────────────────────────────── */
    .hero-h1 {
        font-size: clamp(2.2rem, 5.5vw, 3.5rem);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin: 0;
        color: #166534; /* primary-800 dari tailwind config */

        opacity: 0;
        transform: translateY(28px);
        animation: heroUp 0.75s cubic-bezier(0.22,1,0.36,1) 0.18s forwards;
    }

    /* ── Word gradient (sesuai tailwind primary palette) ── */
    .hero-word {
        display: inline-block;
        margin: 0 0.05em;
        background: linear-gradient(135deg, #14532d 0%, #17a34a 55%, #15803d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;

        opacity: 0;
        transform: translateY(24px) rotateX(-15deg);
        animation: wordReveal 0.6s cubic-bezier(0.22,1,0.36,1) forwards;
    }
    @keyframes wordReveal {
        to { opacity: 1; transform: translateY(0) rotateX(0deg); }
    }

    /* ── Underline accent ────────────────────── */
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
            lineGrow 0.55s cubic-bezier(0.22,1,0.36,1) 0.7s forwards,
            lineShimmer 2.5s linear 1.5s infinite;
        opacity: 0;
        transform: scaleX(0);
        transform-origin: center;
    }
    @keyframes lineGrow {
        to { opacity: 1; transform: scaleX(1); }
    }
    @keyframes lineShimmer {
        0%   { background-position: 0% 0; }
        100% { background-position: 200% 0; }
    }

    /* ── Subtitle ────────────────────────────── */
    .hero-subtitle {
        margin-top: 1.4rem;
        color: #27612e; /* secondary-600 dari tailwind config */
        font-size: 1.05rem;
        font-weight: 450;
        line-height: 1.85;
        max-width: 38rem;
        margin-left: auto;
        margin-right: auto;
        letter-spacing: 0.01em;

        opacity: 0;
        transform: translateY(16px);
        animation: heroUp 0.65s cubic-bezier(0.22,1,0.36,1) 0.5s forwards;
    }

    /* ── Keyframes ───────────────────────────── */
    @keyframes heroUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="page-hero-wrap">

    {{-- Floating orbs --}}
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>

    {{-- Dot grid --}}
    <div class="hero-dot-grid"></div>

    {{-- Wavy organic SVG lines --}}
    <svg class="hero-waves-bg" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,160 C240,220 480,80 720,160 C960,240 1200,100 1440,160 L1440,0 L0,0 Z"
              fill="rgba(34,197,94,0.07)" />
        <path d="M0,200 C200,140 440,260 720,200 C1000,140 1240,260 1440,200 L1440,0 L0,0 Z"
              fill="rgba(74,222,128,0.05)" />
    </svg>

    {{-- Floating particles (6 buah) --}}
    <div class="hero-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    {{-- Expanding rings (2 buah, di pinggir) --}}
    <div class="hero-rings">
        <div class="hero-ring hero-ring-1"></div>
        <div class="hero-ring hero-ring-2"></div>
    </div>

    {{-- Geometric shapes (3 buah, halus) --}}
    <div class="hero-shapes">
        <div class="hero-shape hero-shape-1 shape-tri"></div>
        <div class="hero-shape hero-shape-2 shape-dia"></div>
        <div class="hero-shape hero-shape-3 shape-sq"></div>
    </div>

    {{-- Sparkle stars (4 buah, opacity rendah) --}}
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

    {{-- Content --}}
    <div class="page-hero-inner">

        {{-- Title: word-by-word staggered reveal --}}
        <h1 class="hero-h1" aria-label="{{ $heroTitle }}">
            <span class="hero-h1-inner">
                @php
                    $words = explode(' ', $heroTitle);
                    $baseDelay = 0.2;
                @endphp
                @foreach($words as $i => $word)
                    <span class="hero-word" style="animation-delay: {{ $baseDelay + ($i * 0.12) }}s">{{ $word }}</span>
                @endforeach
            </span>
        </h1>

        <span class="hero-underline"></span>

        @if($heroSubtitle)
            <p class="hero-subtitle">{{ $heroSubtitle }}</p>
        @endif

    </div>

</section>