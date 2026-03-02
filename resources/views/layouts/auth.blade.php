<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Auth') - Niat Zakat</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* ══════════════════════════════════════════
           BACKGROUND — Hijau cerah primary #17a34a
           Sesuai tailwind.config: primary DEFAULT #17a34a
        ══════════════════════════════════════════ */
        .animated-bg {
            /* Base hijau cerah — primary dari tailwind.config */
            background: #17a34a;
            position: relative;
            isolation: isolate;
        }

        /* Layer 1 — Gradient mesh cerah */
        .animated-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -3;
            background:
                radial-gradient(ellipse 90% 70% at 15% 15%,  #22c55e 0%,  transparent 55%),
                radial-gradient(ellipse 70% 90% at 85% 85%,  #15803d 0%,  transparent 55%),
                radial-gradient(ellipse 60% 60% at 50% 50%,  #1aad50 0%,  transparent 65%),
                linear-gradient(145deg, #22c55e 0%, #17a34a 45%, #15803d 100%);
            animation: bgBreath 12s ease-in-out infinite;
        }

        @keyframes bgBreath {
            0%, 100% { opacity: 1; }
            50%       { opacity: .88; }
        }

        /* Layer 2 — Orb kiri atas, lebih terang */
        .bg-orb-1 {
            position: fixed;
            width: 600px; height: 600px;
            top: -180px; left: -180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(134,239,172,.35) 0%, transparent 65%);
            filter: blur(60px);
            z-index: -2;
            pointer-events: none;
            animation: orbFloat1 16s ease-in-out infinite;
        }

        /* Layer 3 — Orb kanan bawah */
        .bg-orb-2 {
            position: fixed;
            width: 700px; height: 700px;
            bottom: -200px; right: -200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(21,128,61,.45) 0%, transparent 60%);
            filter: blur(80px);
            z-index: -2;
            pointer-events: none;
            animation: orbFloat2 20s ease-in-out infinite;
        }

        /* Layer 4 — Orb tengah kecil cerah */
        .bg-orb-3 {
            position: fixed;
            width: 400px; height: 400px;
            top: 35%; left: 58%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(187,247,208,.20) 0%, transparent 65%);
            filter: blur(50px);
            z-index: -2;
            pointer-events: none;
            animation: orbFloat3 11s ease-in-out infinite;
        }

        @keyframes orbFloat1 {
            0%, 100% { transform: translate(0,    0)    scale(1);    }
            40%       { transform: translate(70px, 90px) scale(1.08); }
            70%       { transform: translate(-30px,40px) scale(.95);  }
        }
        @keyframes orbFloat2 {
            0%, 100% { transform: translate(0,     0)     scale(1);    }
            35%       { transform: translate(-90px,-70px) scale(1.10); }
            70%       { transform: translate(40px, -20px) scale(.93);  }
        }
        @keyframes orbFloat3 {
            0%, 100% { transform: translate(0,    0)    scale(1);   opacity:.8; }
            50%       { transform: translate(-60px,70px) scale(1.2); opacity: 1; }
        }

        /* Layer 5 — Dot grid pattern */
        .bg-pattern {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-image: radial-gradient(circle, rgba(255,255,255,.10) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse 85% 85% at 50% 50%, black 20%, transparent 100%);
        }

        /* Layer 6 — Garis diagonal halus */
        .bg-lines {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-image: repeating-linear-gradient(
                -52deg,
                transparent,
                transparent 55px,
                rgba(255,255,255,.03) 55px,
                rgba(255,255,255,.03) 56px
            );
        }

        /* Layer 7 — Ornamen konsentris kanan atas (berputar lambat) */
        .bg-ornament {
            position: fixed;
            top: -100px; right: -100px;
            width: 480px; height: 480px;
            z-index: -1;
            pointer-events: none;
            opacity: .10;
            animation: ornSpin 70s linear infinite;
        }

        /* Layer 8 — Ornamen kiri bawah */
        .bg-ornament-2 {
            position: fixed;
            bottom: -120px; left: -120px;
            width: 420px; height: 420px;
            z-index: -1;
            pointer-events: none;
            opacity: .07;
            animation: ornSpin 90s linear infinite reverse;
        }

        @keyframes ornSpin {
            from { transform: rotate(0deg);   }
            to   { transform: rotate(360deg); }
        }

        /* ══════════════════════════════════════════
           GLASS CARD
        ══════════════════════════════════════════ */
        .glass-effect {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* nz-xl shadow dari tailwind.config */
        .glass-effect.rounded-3xl {
            box-shadow:
                0 20px 40px -8px rgba(23,163,74,.25),
                0 8px 16px -4px rgba(23,163,74,.12),
                0 0 0 1px rgba(255,255,255,.15);
        }

        /* ══════════════════════════════════════════
           LEGACY — kept
        ══════════════════════════════════════════ */
        @keyframes float {
            0%, 100% { transform: translateY(0px);   }
            50%       { transform: translateY(-20px); }
        }
        .float-animation { animation: float 6s ease-in-out infinite; }

        @keyframes pulse-ring {
            0%   { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        .pulse-ring { animation: pulse-ring 2s cubic-bezier(0.4,0,0.6,1) infinite; }

        /* Complete profile layout */
        .complete-profile-layout .main-container    { max-width: none !important; width: 100% !important; align-items: flex-start !important; }
        .complete-profile-layout .content-container { max-width: none !important; width: 100% !important; }

        /* ══════════════════════════════════════════
           TOAST — nz-shadow
        ══════════════════════════════════════════ */
        .toast-container {
            position: fixed; top: 1.5rem; right: 1.5rem;
            z-index: 99998;
            display: flex; flex-direction: column; gap: .75rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            min-width: 280px; max-width: 350px;
            padding: .875rem 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(23,163,74,.15), 0 4px 6px -2px rgba(23,163,74,.10);
            display: flex; align-items: flex-start; gap: .75rem;
            animation: slideInRight .3s ease-out;
        }
        .toast.toast-exit { animation: slideOutRight .3s ease-in forwards; }

        @keyframes slideInRight {
            from { transform: translateX(400px); opacity: 0; }
            to   { transform: translateX(0);     opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0);     opacity: 1; }
            to   { transform: translateX(400px); opacity: 0; }
        }

        .toast-icon    { flex-shrink:0; width:1.25rem; height:1.25rem; }
        .toast-content { flex:1; font-size:.875rem; line-height:1.4; }
        .toast-close   { flex-shrink:0; width:1.25rem; height:1.25rem; cursor:pointer; opacity:.5; transition:opacity .2s; }
        .toast-close:hover { opacity:1; }

        .toast-success              { border-left: 4px solid #17a34a; }
        .toast-error                { border-left: 4px solid #ef4444; }
        .toast-success .toast-icon  { color: #17a34a; }
        .toast-error   .toast-icon  { color: #ef4444; }

        .toast-content-title   { font-weight:600; color:#111827; margin-bottom:.125rem; }
        .toast-content-message { color:#6b7280; font-size:.8125rem; }

        /* ══════════════════════════════════════════
           RECAPTCHA & Z-INDEX
        ══════════════════════════════════════════ */
        .recaptcha-badge {
            position:fixed !important; bottom:25px !important; right:25px !important;
            z-index:100000 !important;
            background:rgba(0,0,0,.8) !important; color:white !important;
            padding:6px 12px !important; border-radius:6px !important;
            font-size:11px !important; font-family:'Poppins',sans-serif !important;
            backdrop-filter:blur(10px) !important; -webkit-backdrop-filter:blur(10px) !important;
            border:1px solid rgba(255,255,255,.2) !important;
            pointer-events:none !important;
            box-shadow:0 4px 12px rgba(0,0,0,.3) !important;
        }

        .auth-card              { z-index:10; position:relative; }
        .content-container      { z-index:20; position:relative; }
        .absolute.inset-0.overflow-hidden { z-index:1 !important; }

        /* ══════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════ */
        @media (max-width: 768px) {
            .recaptcha-badge   { bottom:70px !important; right:15px !important; font-size:10px !important; padding:5px 10px !important; }
            .toast-container   { right:1rem; left:1rem; top:1rem; }
            .toast             { min-width:auto; width:100%; }
            .bg-orb-1          { width:300px; height:300px; }
            .bg-orb-2          { width:350px; height:350px; }
            .bg-ornament,
            .bg-ornament-2     { opacity:.05; }
        }
        @media (min-width: 1400px) {
            .complete-profile-layout .content-container { padding-left:2rem; padding-right:2rem; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen animated-bg overflow-x-hidden {{ request()->routeIs('complete-profile.*') ? 'complete-profile-layout' : '' }}">

    {{-- ── BACKGROUND LAYERS ── --}}
    <div class="bg-orb-1"></div>
    <div class="bg-orb-2"></div>
    <div class="bg-orb-3"></div>
    <div class="bg-pattern"></div>
    <div class="bg-lines"></div>

    {{-- Ornamen konsentris kanan atas --}}
    <div class="bg-ornament">
        <svg viewBox="0 0 480 480" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="240" cy="240" r="230" stroke="white" stroke-width="1.5"/>
            <circle cx="240" cy="240" r="195" stroke="white" stroke-width="1.5"/>
            <circle cx="240" cy="240" r="160" stroke="white" stroke-width="1"/>
            <circle cx="240" cy="240" r="125" stroke="white" stroke-width="1"/>
            <circle cx="240" cy="240" r="90"  stroke="white" stroke-width="1"/>
            <circle cx="240" cy="240" r="55"  stroke="white" stroke-width=".8"/>
            <line x1="10"  y1="240" x2="470" y2="240" stroke="white" stroke-width=".7" opacity=".5"/>
            <line x1="240" y1="10"  x2="240" y2="470" stroke="white" stroke-width=".7" opacity=".5"/>
            <line x1="75"  y1="75"  x2="405" y2="405" stroke="white" stroke-width=".5" opacity=".3"/>
            <line x1="405" y1="75"  x2="75"  y2="405" stroke="white" stroke-width=".5" opacity=".3"/>
        </svg>
    </div>

    {{-- Ornamen kiri bawah --}}
    <div class="bg-ornament-2">
        <svg viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="210" cy="210" r="200" stroke="white" stroke-width="1.5"/>
            <circle cx="210" cy="210" r="165" stroke="white" stroke-width="1"/>
            <circle cx="210" cy="210" r="130" stroke="white" stroke-width="1"/>
            <circle cx="210" cy="210" r="95"  stroke="white" stroke-width=".8"/>
            <circle cx="210" cy="210" r="60"  stroke="white" stroke-width=".8"/>
            <polygon points="210,25 390,295 30,295"  stroke="white" stroke-width=".7" fill="none" opacity=".4"/>
            <polygon points="210,395 30,125 390,125" stroke="white" stroke-width=".7" fill="none" opacity=".4"/>
        </svg>
    </div>

    @include('partials.splash-screen')

    <div class="toast-container" id="toastContainer"></div>

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">

        @if(!request()->routeIs('complete-profile.*'))
        <div class="absolute inset-0 overflow-hidden pointer-events-none" style="z-index:1;">
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary-400 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -top-10 -right-10 w-96 h-96 bg-secondary-400 rounded-full opacity-15 blur-3xl float-animation"></div>
            <div class="absolute -bottom-20 -left-10 w-80 h-80 bg-accent-400 rounded-full opacity-10 blur-3xl" style="animation-delay:2s;"></div>
            <div class="absolute -bottom-32 -right-20 w-72 h-72 bg-primary-300 rounded-full opacity-20 blur-3xl float-animation" style="animation-delay:4s;"></div>
            <div class="absolute top-1/4 right-1/4 opacity-5">
                <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="80" stroke="white" stroke-width="2"/>
                    <circle cx="100" cy="100" r="60" stroke="white" stroke-width="2"/>
                    <circle cx="100" cy="100" r="40" stroke="white" stroke-width="2"/>
                    <circle cx="100" cy="100" r="20" stroke="white" stroke-width="2"/>
                </svg>
            </div>
        </div>
        @endif

        <!-- Content Container -->
        <div class="w-full {{ request()->routeIs('complete-profile.*') ? 'max-w-full px-0' : 'max-w-md relative z-10' }}" style="z-index:20;">

            @if(request()->routeIs('complete-profile.*'))
            <div class="bg-white shadow-sm mb-8 py-6 px-8">
                <div class="max-w-6xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary-100 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
                                <circle cx="12" cy="14" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Niat Zakat</h1>
                            <p class="text-gray-600 text-sm">Platform Pengelolaan Zakat Digital</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-bold text-gray-800">Lengkapi Profil</h2>
                        <p class="text-gray-600 text-sm">Selesaikan pendaftaran Anda</p>
                    </div>
                </div>
            </div>
            @else
            <!-- Auth Card -->
            <div class="glass-effect rounded-3xl p-8 sm:p-10 animate-scale-in" style="z-index:10;">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-neutral-800 mb-2">
                        @yield('card-title', 'Welcome Back')
                    </h2>
                    <p class="text-neutral-600 text-sm">
                        @yield('card-subtitle', 'Silakan login untuk melanjutkan')
                    </p>
                </div>
                @yield('content')
            </div>
            @endif

            @if(request()->routeIs('complete-profile.*'))
                @yield('content')
            @endif

            <div class="mt-6 text-center animate-fade-in" style="z-index:20;">
                @yield('footer-links')
            </div>

            <div class="mt-8 text-center" style="z-index:20;">
                <p class="text-primary-100 text-xs">
                    &copy; {{ date('Y') }} Niat Zakat. All rights reserved.
                </p>
            </div>

        </div>
    </div>

    <script>
        const ToastNotification = {
            container: null,
            init() { this.container = document.getElementById('toastContainer'); },
            show(message, type = 'success', duration = 3000) {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                const icon = type === 'success'
                    ? `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`
                    : `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`;
                toast.innerHTML = `${icon}<div class="toast-content"><div class="toast-content-message">${message}</div></div><svg class="toast-close" fill="currentColor" viewBox="0 0 20 20" onclick="this.parentElement.remove()"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>`;
                this.container.appendChild(toast);
                setTimeout(() => this.hide(toast), duration);
            },
            hide(toast) {
                toast.classList.add('toast-exit');
                setTimeout(() => { if (toast.parentElement) toast.remove(); }, 300);
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            ToastNotification.init();
            @if(session('success')) ToastNotification.show("{{ session('success') }}", 'success'); @endif
            @if(session('error'))   ToastNotification.show("{{ session('error') }}", 'error');   @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    ToastNotification.show("{{ $error }}", 'error');
                @endforeach
            @endif
            document.documentElement.style.scrollBehavior = 'smooth';
            document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"]').forEach(input => {
                input.addEventListener('focus', function() { this.parentElement.classList.add('ring-2','ring-primary-300'); });
                input.addEventListener('blur',  function() { this.parentElement.classList.remove('ring-2','ring-primary-300'); });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>