<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Auth') - {{ ($config ?? \App\Models\KonfigurasiAplikasi::getConfig())->nama_aplikasi }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary:     #17a34a;
            --primary-50:  #f0fdf4;
            --primary-100: #dcfce7;
            --primary-200: #bbf7d0;
            --primary-300: #86efac;
            --primary-400: #4ade80;
            --primary-500: #22c55e;
            --primary-700: #15803d;
            --primary-800: #166534;
            --primary-900: #14532d;
            --neutral-50:  #fafafa;
            --neutral-100: #f5f5f5;
            --neutral-200: #eeeeee;
            --neutral-300: #e0e0e0;
            --neutral-400: #bdbdbd;
            --neutral-500: #9e9e9e;
            --neutral-600: #757575;
            --neutral-700: #616161;
            --neutral-800: #424242;
            --neutral-900: #212121;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Poppins', system-ui, sans-serif;
            overflow: hidden;
        }

        /* ════════════════════════════════════════════
           BACKGROUND — Hijau #17a34a + Animasi Modern
        ════════════════════════════════════════════ */
        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: #17a34a;
            overflow: hidden;
        }

        /* Mesh gradient animasi */
        .auth-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 65% at 10% 10%, #4ade80 0%, transparent 50%),
                radial-gradient(ellipse 65% 80% at 90% 90%, #15803d 0%, transparent 50%),
                radial-gradient(ellipse 50% 50% at 50% 50%, #1db954 0%, transparent 65%);
            animation: meshShift 12s ease-in-out infinite alternate;
        }
        @keyframes meshShift {
            0%   { opacity: 1;   transform: scale(1)    rotate(0deg); }
            50%  { opacity: .88; transform: scale(1.05) rotate(1deg);  }
            100% { opacity: .95; transform: scale(.98)  rotate(-1deg); }
        }

        /* Orb 1 — kiri atas, besar, bergerak */
        .bg-orb-1 {
            position: absolute;
            width: 560px; height: 560px;
            top: -200px; left: -180px;
            border-radius: 50%;
            background: radial-gradient(circle at 40% 40%, rgba(134,239,172,.55) 0%, transparent 60%);
            filter: blur(60px);
            animation: orbDrift1 16s ease-in-out infinite;
        }
        @keyframes orbDrift1 {
            0%,100% { transform: translate(0px, 0px) scale(1); }
            33%      { transform: translate(80px, 60px) scale(1.12); }
            66%      { transform: translate(-30px, 90px) scale(.92); }
        }

        /* Orb 2 — kanan bawah */
        .bg-orb-2 {
            position: absolute;
            width: 640px; height: 640px;
            bottom: -220px; right: -200px;
            border-radius: 50%;
            background: radial-gradient(circle at 60% 60%, rgba(20,83,45,.70) 0%, transparent 60%);
            filter: blur(72px);
            animation: orbDrift2 20s ease-in-out infinite;
        }
        @keyframes orbDrift2 {
            0%,100% { transform: translate(0px, 0px) scale(1); }
            40%      { transform: translate(-90px, -70px) scale(1.10); }
            70%      { transform: translate(40px, -20px) scale(.90); }
        }

        /* Orb 3 — tengah, kecil, cepat */
        .bg-orb-3 {
            position: absolute;
            width: 300px; height: 300px;
            top: 45%; left: 58%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(187,247,208,.30) 0%, transparent 65%);
            filter: blur(40px);
            animation: orbDrift3 9s ease-in-out infinite;
        }
        @keyframes orbDrift3 {
            0%,100% { transform: translate(0,0) scale(1);    opacity: .7; }
            50%      { transform: translate(-70px, 80px) scale(1.25); opacity: 1; }
        }

        /* Orb 4 — atas kanan, aksen terang */
        .bg-orb-4 {
            position: absolute;
            width: 280px; height: 280px;
            top: -60px; right: 15%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(74,222,128,.28) 0%, transparent 65%);
            filter: blur(36px);
            animation: orbDrift4 13s ease-in-out infinite;
        }
        @keyframes orbDrift4 {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(80px) scale(1.15); }
        }

        /* Dot grid pattern */
        .bg-dots {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, rgba(255,255,255,.12) 1.2px, transparent 1.2px);
            background-size: 30px 30px;
            mask-image: radial-gradient(ellipse 90% 90% at 50% 50%, black 30%, transparent 100%);
        }

        /* bg-lines removed — caused visual noise */
        .bg-lines { display: none; }

        /* Ornamen geometris kanan atas — berputar sangat lambat */
        .bg-geo-tr {
            position: absolute;
            top: -100px; right: -100px;
            width: 420px; height: 420px;
            opacity: .10;
            animation: geoSpin 90s linear infinite;
            pointer-events: none;
        }
        /* Ornamen kiri bawah */
        .bg-geo-bl {
            position: absolute;
            bottom: -110px; left: -110px;
            width: 380px; height: 380px;
            opacity: .08;
            animation: geoSpin 110s linear infinite reverse;
            pointer-events: none;
        }
        @keyframes geoSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Partikel mengambang */
        .bg-particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.18);
            animation: particleFloat linear infinite;
            pointer-events: none;
        }
        @keyframes particleFloat {
            0%   { transform: translateY(0) scale(1);    opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-100vh) scale(.6); opacity: 0; }
        }

        /* ══════════════════════════════════════
           PAGE
        ══════════════════════════════════════ */
        .auth-page {
            position: relative;
            z-index: 1;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
        }

        /* ══════════════════════════════════════
           CARD
        ══════════════════════════════════════ */
        .auth-card {
            width: 100%;
            max-width: 1060px;
            height: calc(100vh - 2.5rem);
            max-height: 660px;
            min-height: 500px;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            border-radius: 26px;
            overflow: hidden;
            box-shadow:
                0 32px 64px -12px rgba(0,0,0,.32),
                0 12px 28px  -6px rgba(0,0,0,.18),
                0 0 0 1px rgba(255,255,255,.14);
            animation: cardReveal .65s cubic-bezier(.16,1,.3,1) both;
            isolation: isolate;
        }
        /* reCAPTCHA badge — pastikan selalu bisa diklik */
        .grecaptcha-badge {
            z-index: 99999 !important;
            pointer-events: all !important;
        }

        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(28px) scale(.972); }
            to   { opacity: 1; transform: translateY(0)    scale(1);    }
        }

        /* ══════════════════════════════════════
           LEFT PANEL
        ══════════════════════════════════════ */
        .auth-left {
            position: relative;
            overflow: hidden;
            background: #0f0d0a;
        }

        .auth-left-img {
            position: absolute;
            inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
            transition: transform 14s ease;
        }
        .auth-left:hover .auth-left-img { transform: scale(1.04); }

        .auth-left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,.05)  0%,
                rgba(0,0,0,.08) 30%,
                rgba(0,0,0,.52) 65%,
                rgba(0,0,0,.82) 100%
            );
            z-index: 1;
            pointer-events: none;
        }

        .auth-left-inner {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-start;
            padding: 1.75rem 2rem;
        }

        /* ── Wrapper tengah panel kiri ── */
        .left-center {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
            width: 100%;
        }

        /* ── Logo kiri — BULAT BESAR ── */
        .left-logo {
            display: flex;
            align-items: center;
            gap: .9rem;
            animation: fadeDown .55s cubic-bezier(.16,1,.3,1) .12s both;
        }

        .left-logo-icon {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            overflow: hidden;
            /* bg putih agar mix-blend-mode multiply hapus bg putih logo */
            background: white;
            border: 3px solid rgba(255,255,255,.55);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow:
                0 8px 28px rgba(0,0,0,.30),
                0 2px 8px rgba(0,0,0,.15);
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .left-logo-icon:hover {
            transform: scale(1.06);
            box-shadow: 0 12px 36px rgba(0,0,0,.36);
        }
        .left-logo-icon img {
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center;
            padding: 0;
            /* multiply: bg putih logo lenyap, hanya icon hijau yang tampil */
            mix-blend-mode: multiply;
        }
        .left-logo-icon svg {
            width: 32px; height: 32px;
            fill: white;
        }
        .left-logo-text {
            color: white;
            font-weight: 800;
            font-size: 1.45rem;
            line-height: 1.15;
            text-shadow: 0 1px 10px rgba(0,0,0,.45);
            letter-spacing: -.01em;
        }
        .left-logo-text small {
            display: block;
            font-size: .85rem;
            font-weight: 400;
            opacity: .78;
            margin-top: .22rem;
        }

        /* ── Teks bawah ── */
        .left-bottom {
            animation: fadeUp .6s cubic-bezier(.16,1,.3,1) .2s both;
        }
        .left-accent-line {
            width: 32px; height: 3px;
            background: linear-gradient(to right, var(--primary-300), var(--primary-500));
            border-radius: 2px;
            margin-bottom: .9rem;
        }
        .left-headline {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            line-height: 1.18;
            letter-spacing: -.03em;
            text-shadow: 0 2px 18px rgba(0,0,0,.50);
            margin-bottom: .7rem;
            white-space: normal;
        }
        .left-headline span { color: var(--primary-300); }

        .left-desc {
            font-size: .88rem;
            color: rgba(255,255,255,.72);
            line-height: 1.6;
            max-width: none;
        }

        .left-logo {
    position: absolute;
    top: 1.75rem;
    left: 2rem;
}

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══════════════════════════════════════
           RIGHT PANEL
        ══════════════════════════════════════ */
        .auth-right {
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1.75rem 2.25rem;
            position: relative;
            overflow: hidden;
            overflow-y: auto;
        }
        .auth-right::-webkit-scrollbar { width: 3px; }
        .auth-right::-webkit-scrollbar-thumb { background: var(--neutral-200); border-radius: 4px; }

        .auth-right::before {
            content: '';
            position: absolute;
            top: -70px; right: -70px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(23,163,74,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .auth-right::after {
            content: '';
            position: absolute;
            bottom: -55px; left: -55px;
            width: 170px; height: 170px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(23,163,74,.04) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Logo kanan — juga bulat ── */
        .right-logo-row {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: 1.1rem;
            animation: fadeUp .45s ease .1s both;
        }
        .right-logo-icon {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: white;
            border: 2.5px solid var(--primary-200);
            display: flex; align-items: center; justify-content: center;
            box-shadow:
                0 5px 18px rgba(23,163,74,.20),
                0 2px 6px rgba(23,163,74,.10);
            flex-shrink: 0;
            overflow: hidden;
        }
        .right-logo-icon img {
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center;
            padding: 0;
            mix-blend-mode: multiply;
        }
        .right-logo-icon svg { width: 23px; height: 23px; fill: white; }

        .right-logo-text {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--primary-800);
            line-height: 1.15;
            letter-spacing: -.01em;
        }
        .right-logo-text small {
            display: block;
            font-size: .76rem;
            font-weight: 400;
            color: var(--neutral-500);
            margin-top: .18rem;
        }

        /* ── Heading ── */
        .right-heading {
            margin-bottom: 1.1rem;
            animation: fadeUp .45s ease .14s both;
        }
        .right-heading h1 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--neutral-900);
            line-height: 1.2;
            margin-bottom: .2rem;
            letter-spacing: -.02em;
        }
        .right-heading p {
            font-size: .75rem;
            color: var(--neutral-500);
            line-height: 1.5;
        }

        /* ── Form compact ── */
        .form-group {
            margin-bottom: .75rem;
            animation: fadeUp .4s ease var(--anim-delay, .2s) both;
        }
        .form-label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: .3rem;
        }
        .form-label .req { color: #ef4444; margin-left: 2px; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: .75rem; top: 50%; transform: translateY(-50%);
            color: var(--neutral-400);
            pointer-events: none;
            width: 15px; height: 15px;
            transition: color .2s;
        }
        .form-input {
            width: 100%;
            padding: .6rem .75rem .6rem 2.35rem;
            border: 1.5px solid var(--neutral-300);
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: .78rem;
            color: var(--neutral-900);
            background: var(--neutral-50);
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
            -webkit-appearance: none;
        }
        .form-input::placeholder { color: var(--neutral-400); }
        .form-input:hover  { border-color: var(--neutral-400); }
        .form-input:focus  {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(23,163,74,.11);
        }
        .input-wrap:focus-within .input-icon { color: var(--primary); }

        .pw-toggle {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            color: var(--neutral-400); background: none; border: none;
            cursor: pointer; padding: 0; display: flex; align-items: center;
            transition: color .2s;
        }
        .pw-toggle:hover { color: var(--primary); }
        .pw-toggle svg   { width: 15px; height: 15px; }

        .auth-meta {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1rem;
            animation: fadeUp .4s ease .38s both;
        }
        .remember-label {
            display: flex; align-items: center; gap: .4rem;
            font-size: .72rem; color: var(--neutral-700);
            cursor: pointer; user-select: none;
        }
        .remember-label input[type="checkbox"] {
            width: 13px; height: 13px;
            accent-color: var(--primary); cursor: pointer;
        }
        .forgot-link {
            font-size: .72rem; font-weight: 600;
            color: var(--primary); text-decoration: none; transition: color .2s;
        }
        .forgot-link:hover { color: var(--primary-700); }

        .btn-primary {
            width: 100%;
            padding: .72rem 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-700) 100%);
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: .82rem; font-weight: 600; letter-spacing: .02em;
            border: none; border-radius: 10px; cursor: pointer;
            box-shadow: 0 4px 14px rgba(23,163,74,.35), inset 0 1px 0 rgba(255,255,255,.14);
            transition: transform .18s, box-shadow .18s;
            position: relative; overflow: hidden;
            animation: fadeUp .4s ease .42s both;
        }
        .btn-primary::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.11) 0%, transparent 50%);
            pointer-events: none;
        }
        .btn-primary:hover  { transform: translateY(-1.5px); box-shadow: 0 8px 24px rgba(23,163,74,.42); }
        .btn-primary:active { transform: translateY(0); }

        .auth-divider {
            display: flex; align-items: center; gap: .6rem;
            margin: .85rem 0;
            animation: fadeUp .4s ease .46s both;
        }
        .auth-divider-line { flex: 1; height: 1px; background: var(--neutral-200); }
        .auth-divider span { font-size: .68rem; color: var(--neutral-400); white-space: nowrap; }

        .btn-google {
            width: 100%;
            padding: .62rem 1rem;
            background: white; border: 1.5px solid var(--neutral-300); border-radius: 10px;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            font-family: 'Poppins', sans-serif;
            font-size: .78rem; font-weight: 500; color: var(--neutral-800);
            cursor: pointer; text-decoration: none;
            transition: border-color .18s, background .18s, box-shadow .18s, transform .18s;
            animation: fadeUp .4s ease .50s both;
        }
        .btn-google svg { width: 16px; height: 16px; flex-shrink: 0; }
        .btn-google:hover {
            border-color: var(--primary); background: var(--primary-50);
            transform: translateY(-1px); box-shadow: 0 3px 10px rgba(23,163,74,.10);
        }

        .auth-footer-link {
            text-align: center; margin-top: .85rem;
            font-size: .72rem; color: var(--neutral-500);
            animation: fadeUp .4s ease .54s both;
        }
        .auth-footer-link a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .auth-footer-link a:hover { color: var(--primary-700); text-decoration: underline; }

        .auth-right-slot { position: relative; z-index: 1; }

        /* ══════════════════════════════════════
           TOAST
        ══════════════════════════════════════ */
        .toast-container {
            position: fixed; top: 1.25rem; right: 1.25rem;
            z-index: 99999;
            display: flex; flex-direction: column; gap: .5rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            min-width: 260px; max-width: 340px;
            padding: .75rem .875rem;
            background: white; border-radius: 12px;
            box-shadow: 0 10px 22px -4px rgba(23,163,74,.12), 0 3px 6px -2px rgba(0,0,0,.06);
            display: flex; align-items: flex-start; gap: .625rem;
            animation: toastIn .28s cubic-bezier(.16,1,.3,1);
        }
        .toast.toast-exit { animation: toastOut .22s ease-in forwards; }
        @keyframes toastIn  { from { transform: translateX(110%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes toastOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(110%); opacity: 0; } }
        .toast-icon    { flex-shrink:0; width:1.1rem; height:1.1rem; margin-top:.05rem; }
        .toast-body    { flex:1; font-size:.78rem; color: var(--neutral-700); line-height:1.4; }
        .toast-close   { flex-shrink:0; cursor:pointer; color: var(--neutral-400); width:1rem; height:1rem; transition:color .2s; }
        .toast-close:hover { color: var(--neutral-700); }
        .toast-success { border-left: 3px solid var(--primary); }
        .toast-error   { border-left: 3px solid #ef4444; }
        .toast-success .toast-icon { color: var(--primary); }
        .toast-error   .toast-icon { color: #ef4444; }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */
        @media (max-width: 768px) {
            html, body { overflow: auto; }
            .auth-page  { height: auto; min-height: 100vh; padding: 1rem; }
            .auth-card  { grid-template-columns: 1fr; height: auto; max-height: none; max-width: 460px; }
            .auth-left  { min-height: 220px; }
            .left-desc  { display: none; }
            .left-headline { font-size: 1.3rem; }
            .left-logo-icon { width: 56px; height: 56px; }
            .auth-right { padding: 1.5rem 1.5rem 1.75rem; overflow: visible; }
        }
        @media (max-width: 420px) {
            .auth-page  { padding: .75rem; }
            .left-headline { font-size: 1.15rem; }
            .left-logo-icon { width: 50px; height: 50px; }
            .toast-container { right:.75rem; left:.75rem; top:.75rem; }
            .toast { min-width:auto; width:100%; }
        }

        .left-logo {
    position: absolute;
    top: 1.75rem;
    left: 2rem;
}
    </style>

    @stack('styles')
</head>

<body>

{{-- ════════════════════════════════════
     BACKGROUND HIJAU ANIMASI
════════════════════════════════════ --}}
<div class="auth-bg">
    <div class="bg-orb-1"></div>
    <div class="bg-orb-2"></div>
    <div class="bg-orb-3"></div>
    <div class="bg-orb-4"></div>
    <div class="bg-dots"></div>
    <div class="bg-lines"></div>

    {{-- Ornamen geometris berputar --}}
    <svg class="bg-geo-tr" viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="210" cy="210" r="200" stroke="white" stroke-width="1.4"/>
        <circle cx="210" cy="210" r="168" stroke="white" stroke-width="1.0"/>
        <circle cx="210" cy="210" r="136" stroke="white" stroke-width=".9"/>
        <circle cx="210" cy="210" r="104" stroke="white" stroke-width=".8"/>
        <circle cx="210" cy="210" r="72"  stroke="white" stroke-width=".7"/>
        <circle cx="210" cy="210" r="40"  stroke="white" stroke-width=".6"/>
        <line x1="10"  y1="210" x2="410" y2="210" stroke="white" stroke-width=".6" opacity=".5"/>
        <line x1="210" y1="10"  x2="210" y2="410" stroke="white" stroke-width=".6" opacity=".5"/>
        <line x1="62"  y1="62"  x2="358" y2="358" stroke="white" stroke-width=".5" opacity=".3"/>
        <line x1="358" y1="62"  x2="62"  y2="358" stroke="white" stroke-width=".5" opacity=".3"/>
        <polygon points="210,20 390,300 30,300"  stroke="white" stroke-width=".5" fill="none" opacity=".2"/>
        <polygon points="210,400 30,120 390,120" stroke="white" stroke-width=".5" fill="none" opacity=".2"/>
    </svg>

    <svg class="bg-geo-bl" viewBox="0 0 380 380" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="190" cy="190" r="178" stroke="white" stroke-width="1.4"/>
        <circle cx="190" cy="190" r="145" stroke="white" stroke-width="1.0"/>
        <circle cx="190" cy="190" r="112" stroke="white" stroke-width=".9"/>
        <circle cx="190" cy="190" r="79"  stroke="white" stroke-width=".8"/>
        <circle cx="190" cy="190" r="46"  stroke="white" stroke-width=".7"/>
        <line x1="12"  y1="190" x2="368" y2="190" stroke="white" stroke-width=".6" opacity=".5"/>
        <line x1="190" y1="12"  x2="190" y2="368" stroke="white" stroke-width=".6" opacity=".5"/>
        <polygon points="190,18 358,278 22,278"  stroke="white" stroke-width=".5" fill="none" opacity=".25"/>
        <polygon points="190,362 22,102 358,102" stroke="white" stroke-width=".5" fill="none" opacity=".25"/>
    </svg>

    {{-- Partikel mengambang — dibuat via JS --}}
    <div id="particles"></div>
</div>

<div class="toast-container" id="toastContainer"></div>

@include('partials.splash-screen')

@php $config = \App\Models\KonfigurasiAplikasi::getConfig(); @endphp

<div class="auth-page">
    <div class="auth-card">

        {{-- ══════════════════════
             PANEL KIRI
        ══════════════════════ --}}
        <div class="auth-left">
            <img
                src="{{ asset('image/tangan.jpg') }}"
                alt="{{ $config->nama_aplikasi }}"
                class="auth-left-img"
                loading="eager"
            />
            <div class="auth-left-overlay"></div>

            <div class="auth-left-inner">

                {{-- Konten tengah --}}
                <div class="left-center">

                {{-- Logo atas — BULAT & BESAR --}}
                <div class="left-logo">
                    <div class="left-logo-icon">
                        @if($config->logo_aplikasi)
                            <img src="{{ asset('storage/' . $config->logo_aplikasi) }}" alt="{{ $config->nama_aplikasi }}"/>
                        @else
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="left-logo-text">
                        {{ $config->nama_aplikasi }}
                        @if($config->tagline)
                            <small>{{ $config->tagline }}</small>
                        @endif
                    </div>
                </div>

                {{-- Teks bawah — TANPA stats --}}
                <div class="left-bottom">
                    <div class="left-accent-line"></div>
                    <h2 class="left-headline">
                        Bayar Zakat,<br>
                        <span>Raih Berkah</span><br>
                        Tanpa Batas
                    </h2>
                    <p class="left-desc">
                        {{ $config->deskripsi_aplikasi ?? 'Platform digital untuk kemudahan pembayaran zakat, infaq, dan sedekah — aman, transparan, dan amanah.' }}
                    </p>
                </div>

                </div>{{-- /left-center --}}
            </div>
        </div>


        {{-- ══════════════════════
             PANEL KANAN
        ══════════════════════ --}}
        <div class="auth-right">
            <div class="auth-right-slot">

                <div class="right-heading">
                    <h1>@yield('auth-title', 'Hai, Selamat Datang')</h1>
                    <p>@yield('auth-subtitle', 'Silakan masuk dengan akun Anda untuk melanjutkan')</p>
                </div>

                @yield('auth-content')

                @hasSection('auth-footer')
                    <div class="auth-footer-link">
                        @yield('auth-footer')
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>


<script>
    /* ── Particles ── */
    (function() {
        const container = document.getElementById('particles');
        const count = 18;
        for (let i = 0; i < count; i++) {
            const p = document.createElement('div');
            p.className = 'bg-particle';
            const size = Math.random() * 5 + 3;
            const left = Math.random() * 100;
            const delay = Math.random() * 18;
            const duration = Math.random() * 14 + 10;
            const opacity = Math.random() * 0.22 + 0.08;
            p.style.cssText = `
                width:${size}px; height:${size}px;
                left:${left}%;
                bottom:${Math.random() * -10}%;
                opacity:${opacity};
                animation-duration:${duration}s;
                animation-delay:${delay}s;
            `;
            container.appendChild(p);
        }
    })();

    /* ── Toast ── */
    const Toast = {
        container: null,
        init() { this.container = document.getElementById('toastContainer'); },
        show(msg, type = 'success', duration = 3500) {
            const el = document.createElement('div');
            el.className = `toast toast-${type}`;
            const ok  = `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`;
            const err = `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`;
            const x   = `<svg class="toast-close" fill="currentColor" viewBox="0 0 20 20" onclick="this.closest('.toast').remove()"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>`;
            el.innerHTML = `${type === 'success' ? ok : err}<div class="toast-body">${msg}</div>${x}`;
            this.container.appendChild(el);
            setTimeout(() => this._hide(el), duration);
        },
        _hide(el) {
            el.classList.add('toast-exit');
            setTimeout(() => el.parentElement && el.remove(), 260);
        }
    };

    function togglePassword(inputId = 'password', iconId = 'eye-icon') {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!input) return;
        const hidden = input.type === 'password';
        input.type = hidden ? 'text' : 'password';
        if (icon) {
            icon.innerHTML = hidden
                ? `<path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>`
                : `<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>`;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        Toast.init();
        @if(session('success')) Toast.show(`{{ addslashes(session('success')) }}`, 'success'); @endif
        @if(session('error'))   Toast.show(`{{ addslashes(session('error'))   }}`, 'error');   @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                Toast.show(`{{ addslashes($error) }}`, 'error');
            @endforeach
        @endif
    });
</script>

@stack('scripts')
</body>
</html>