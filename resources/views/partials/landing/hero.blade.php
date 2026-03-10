{{-- partials/landing/hero.blade.php --}}
<section class="relative bg-white overflow-hidden" style="min-height: 100svh;">

    {{-- Background Image --}}
    <div class="absolute inset-0 z-0">
        <img
            src="{{ asset('image/tangan.jpg') }}"
            alt=""
            class="w-full h-full object-cover object-center"
            aria-hidden="true"
        >
        {{-- Overlay Gradien --}}
        <div class="absolute inset-0" 
             style="background: linear-gradient(to right, 
                    rgba(255,255,255,0.92) 0%, 
                    rgba(255,255,255,0.85) 40%, 
                    rgba(255,255,255,0.2) 100%);">
        </div>
    </div>

    {{-- ===================== DOT GRID DEKORASI ===================== --}}

    {{-- [1] Dot Grid PERSEGI — kiri bawah --}}
    <div class="absolute bottom-16 left-8 z-10 opacity-25 pointer-events-none"
         style="width: 120px; height: 120px;
                background-image: radial-gradient(#2d6936 2.2px, transparent 2.2px);
                background-size: 16px 16px;">
    </div>

    {{-- [2] Dot Grid PERSEGI — kanan atas halaman --}}
    <div class="absolute top-24 right-4 z-10 opacity-20 pointer-events-none"
         style="width: 100px; height: 100px;
                background-image: radial-gradient(#2d6936 2px, transparent 2px);
                background-size: 14px 14px;">
    </div>

    {{-- [3] Dot Grid PERSEGI PANJANG — kiri tengah --}}
    <div class="absolute top-1/3 left-0 z-10 opacity-20 pointer-events-none"
         style="width: 80px; height: 160px;
                background-image: radial-gradient(#2d6936 2px, transparent 2px);
                background-size: 16px 16px;">
    </div>

    {{-- [4] Dot Grid PERSEGI PANJANG — atas tengah, horizontal --}}
    <div class="absolute top-6 left-1/3 z-10 opacity-15 pointer-events-none"
         style="width: 220px; height: 60px;
                background-image: radial-gradient(#94a3b8 1.8px, transparent 1.8px);
                background-size: 18px 18px;">
    </div>

    {{-- [5] Dot Grid PERSEGI abu-abu — tengah kanan --}}
    <div class="absolute top-1/2 right-2 z-10 opacity-18 pointer-events-none"
         style="width: 90px; height: 90px;
                background-image: radial-gradient(#94a3b8 2px, transparent 2px);
                background-size: 15px 15px;">
    </div>

    {{-- ===================== END DOT GRID ===================== --}}

    {{-- Konten utama — px disesuaikan dengan navbar (px-4 sm:px-10 lg:px-20) --}}
    <div class="relative z-20 flex items-center w-full px-4 sm:px-10 lg:px-20"
         style="min-height: 100svh; padding-top: 100px; padding-bottom: 100px;">

        <div class="relative w-full flex flex-col lg:flex-row items-center gap-16">

            {{-- KONTEN KIRI --}}
            <div class="w-full lg:w-[55%] text-left order-2 lg:order-1">
                <h1 class="hero-reveal font-black text-slate-900 tracking-tight mb-6"
                    style="font-size: clamp(2.5rem, 4.5vw, 3.8rem); line-height: 1.1;">
                    Kelola Zakat<br>
                    <span class="text-primary-600">Lebih Modern,</span><br>
                    <span class="relative inline-block">
                        Lebih Amanah.
                        {{-- Animated Curved Line SVG --}}
                        <svg class="curved-line-svg absolute left-0 w-full pointer-events-none" 
                             style="bottom: -14px; height: 22px; overflow: visible;"
                             viewBox="0 0 300 22" 
                             preserveAspectRatio="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path 
                                class="curved-line-path"
                                d="M4 14 C40 4, 80 20, 120 10 C160 0, 200 18, 240 9 C270 3, 288 15, 296 12"
                                fill="none"
                                stroke="#2d6936"
                                stroke-width="3"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            {{-- Garis kedua, lebih tipis --}}
                            <path 
                                class="curved-line-path-2"
                                d="M4 18 C40 8, 80 22, 120 14 C160 4, 200 20, 240 13 C270 7, 288 18, 296 16"
                                fill="none"
                                stroke="#86efac"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                opacity="0.6"
                            />
                        </svg>
                    </span>
                </h1>

                <p class="hero-reveal text-slate-700 text-base sm:text-lg font-medium leading-relaxed mb-12 max-w-lg" style="margin-top: 1.5rem;">
                    Transformasi digital untuk lembaga amil zakat dan masjid. 
                    Kelola transparansi laporan secara real-time dalam satu dashboard terintegrasi.
                </p>

                <div class="hero-reveal flex flex-wrap gap-4 items-center">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary-600 text-white font-bold text-base rounded-2xl shadow-xl shadow-primary-200 hover:bg-primary-700 active:scale-95 transition-all duration-300">
                        Mulai Gratis Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    
                    <a href="#fitur"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/90 backdrop-blur-sm text-slate-700 font-bold text-base rounded-2xl border border-slate-200 hover:border-primary-500 hover:text-primary-600 active:scale-95 transition-all duration-300">
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            {{-- FOTO KANAN --}}
            <div class="w-full lg:w-[45%] order-1 lg:order-2 hero-reveal flex justify-center lg:justify-end">
                <div class="relative inline-block">
                    
                    {{-- ===== ELEMEN DEKORATIF DI BELAKANG GAMBAR ===== --}}

                    {{-- Aura samar --}}
                    <div class="absolute z-0"
                         style="width: 520px; height: 520px;
                                top: 50%; left: 50%;
                                transform: translate(-50%, -50%);
                                background: radial-gradient(circle, rgba(45,105,54,0.08) 0%, rgba(45,105,54,0.03) 55%, transparent 75%);">
                    </div>

                    {{-- Garis arc kanan atas --}}
                    <svg class="absolute z-0 pointer-events-none"
                         style="width: 260px; height: 260px; top: -40px; right: -50px; opacity: 0.18;"
                         viewBox="0 0 260 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M30 230 Q130 20, 230 130" stroke="#2d6936" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M50 240 Q150 30, 240 140" stroke="#2d6936" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>

                    {{-- Garis arc kiri bawah --}}
                    <svg class="absolute z-0 pointer-events-none"
                         style="width: 180px; height: 180px; bottom: -30px; left: -40px; opacity: 0.15;"
                         viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M160 20 Q20 20, 20 160" stroke="#2d6936" stroke-width="2" stroke-linecap="round"/>
                        <path d="M150 30 Q30 30, 30 150" stroke="#86efac" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>

                    {{-- Dot Grid PERSEGI — pojok kiri atas foto --}}
                    <div class="absolute -top-10 -left-10 w-32 h-32 z-0 opacity-60" 
                         style="background-image: radial-gradient(#2d6936 2.5px, transparent 2.5px); background-size: 18px 18px;">
                    </div>

                    {{-- Dot Grid PERSEGI — pojok kanan bawah foto --}}
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 z-0 opacity-60" 
                         style="background-image: radial-gradient(#2d6936 2.5px, transparent 2.5px); background-size: 18px 18px;">
                    </div>

                    {{-- Dot Grid PERSEGI PANJANG — bawah foto, horizontal --}}
                    <div class="absolute z-0 opacity-35"
                         style="width: 200px; height: 60px;
                                bottom: -30px; left: 50%;
                                transform: translateX(-50%);
                                background-image: radial-gradient(#2d6936 2px, transparent 2px);
                                background-size: 16px 16px;">
                    </div>

                    {{-- Dot Grid PERSEGI PANJANG — kanan foto, vertikal --}}
                    <div class="absolute z-0 opacity-30"
                         style="width: 55px; height: 180px;
                                right: -35px; top: 50%;
                                transform: translateY(-50%);
                                background-image: radial-gradient(#94a3b8 2px, transparent 2px);
                                background-size: 14px 14px;">
                    </div>

                    {{-- ===== END DEKORATIF ===== --}}

                    {{-- Frame Gambar --}}
                    <div class="relative z-10 rounded-[2.5rem] overflow-hidden shadow-2xl border-[12px] border-white/80 backdrop-blur-md">
                        <img
                            src="{{ asset('image/zakat.jpg') }}"
                            alt="Visual Zakat"
                            class="w-full object-cover aspect-square"
                            style="max-width: 460px;"
                        >
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* ===== Hero Reveal: smooth & soft ===== */
    .hero-reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 1.1s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hero-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ===== Garis melengkung: tersembunyi sampai .curve-animate ditambahkan ===== */
    .curved-line-path,
    .curved-line-path-2 {
        stroke-dasharray: 400;
        stroke-dashoffset: 400;
        opacity: 0;
        /* Tidak ada animasi CSS default — semuanya dikontrol JS */
    }

    /* Kelas yang ditambahkan JS setelah splash selesai */
    .curved-line-path.curve-animate {
        animation: draw-curve-soft 2.8s cubic-bezier(0.16, 1, 0.3, 1) forwards,
                   wave-shimmer-soft 5s ease-in-out 3.0s infinite;
    }
    .curved-line-path-2.curve-animate {
        animation: draw-curve-soft 3.4s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
    }

    @keyframes draw-curve-soft {
        0%   { stroke-dashoffset: 400; opacity: 0; }
        6%   { opacity: 0.8; }
        100% { stroke-dashoffset: 0;   opacity: 1; }
    }

    /* Shimmer terus bernapas, pelan & lembut */
    @keyframes wave-shimmer-soft {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.40; }
    }
</style>

<script>
(function () {
    var heroAnimated = false;

    /**
     * Jalankan semua animasi hero — dipanggil sekali setelah splash selesai
     */
    function runHeroAnimations() {
        if (heroAnimated) return;
        heroAnimated = true;

        // --- Hero reveal dengan stagger cepat setelah splash ---
        var reveals = document.querySelectorAll('.hero-reveal');
        reveals.forEach(function (el, i) {
            // Langsung mulai tanpa jeda awal, stagger 120ms per elemen
            setTimeout(function () {
                el.classList.add('is-visible');
            }, i * 120);
        });

        // --- Garis melengkung: muncul setelah h1 sudah mulai terlihat ---
        setTimeout(function () {
            var path1 = document.querySelector('.curved-line-path');
            var path2 = document.querySelector('.curved-line-path-2');
            if (path1) path1.classList.add('curve-animate');
            if (path2) path2.classList.add('curve-animate');
        }, 500);
    }

    // ── 1. Mendengarkan event dari splash-screen.blade.php ───────────────────
    document.addEventListener('splashHidden', function () {
        runHeroAnimations();
    });

    // ── 2. Callback queue __onSplashHidden (splash juga memanggil ini) ───────
    window.__onSplashHidden = window.__onSplashHidden || [];
    window.__onSplashHidden.push(function () {
        runHeroAnimations();
    });

    // ── 3. Failsafe: jika splash tidak ada / sudah lewat, tetap jalan ────────
    //    Tunggu sampai MAX_MS splash (4500ms) + buffer 200ms, lalu paksa jalan
    window.addEventListener('load', function () {
        setTimeout(function () {
            runHeroAnimations(); // heroAnimated guard memastikan tidak dobel
        }, 4700);
    });
})();
</script>