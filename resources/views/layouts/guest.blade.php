<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Niat Zakat - Platform Digital Pengelolaan Zakat Transparan & Amanah')">
    <title>@yield('title', 'Niat Zakat - Sistem Zakat Digital')</title>

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        }
        html {
            scroll-behavior: smooth;
        }
    </style>

    @yield('styles')
</head>

<body class="bg-white text-neutral-900 antialiased">

    @include('partials.splash-screen')

    {{-- Navbar --}}
    @include('partials.landing.navbar')

    {{-- Konten utama --}}
    @hasSection('content')
        @yield('content')
    @else
        @include('partials.landing.hero')
        @include('partials.landing.content')
    @endif

    {{-- Footer --}}
    @include('partials.landing.footer')

    {{-- Back to Top Button --}}
    <button id="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 z-50 w-11 h-11 bg-primary-500 text-white rounded-full shadow-nz-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:bg-primary-600"
        aria-label="Kembali ke atas">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <script>
        // ---- Back to top ----
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTop.classList.remove('opacity-0', 'pointer-events-none');
                backToTop.classList.add('opacity-100');
            } else {
                backToTop.classList.add('opacity-0', 'pointer-events-none');
                backToTop.classList.remove('opacity-100');
            }
        });

        // ---- Splash screen → hero reveal ----
        // Fungsi ini dipanggil dari splash-screen.blade.php saat splash selesai
        // Tambahkan ke splash screen: document.dispatchEvent(new Event('splashDone'))
        // Fallback otomatis sudah ada di hero.blade.php
        window.onSplashDone = function () {
            document.dispatchEvent(new Event('splashDone'));
        };
    </script>

    @yield('scripts')
</body>

</html>