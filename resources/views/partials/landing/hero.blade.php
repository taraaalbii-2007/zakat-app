{{-- partials/landing/hero.blade.php --}}
<section class="relative min-h-screen bg-white pt-20 lg:pt-0 flex items-center overflow-hidden">

    {{-- Background: tangan.jpg --}}
    <div class="absolute inset-0 z-0">
        <img
            src="{{ asset('image/tangan.jpg') }}"
            alt=""
            class="w-full h-full object-cover object-center"
            aria-hidden="true"
        >
        <div class="absolute inset-0"
             style="background: linear-gradient(
                 105deg,
                 rgba(255,255,255,0.97) 0%,
                 rgba(255,255,255,0.93) 30%,
                 rgba(255,255,255,0.70) 55%,
                 rgba(255,255,255,0.15) 80%,
                 rgba(255,255,255,0.05) 100%
             );"></div>
    </div>

    {{-- Background decorative shapes --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-50 rounded-full opacity-60"></div>
        <div class="absolute top-1/2 -left-32 w-64 h-64 bg-secondary-50 rounded-full opacity-40"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle, #2d6936 1px, transparent 1px); background-size: 32px 32px; opacity: 0.04;"></div>
    </div>

    <div class="relative z-20 w-full px-4 sm:px-10 lg:px-20 py-20 lg:min-h-screen flex items-center">

        <div class="relative w-full flex items-center">

            {{-- Teks kiri --}}
            <div class="w-full lg:w-[58%] text-left">

                <h1 class="font-extrabold text-neutral-900 leading-tight tracking-tight mb-2"
                    style="font-size: clamp(2.2rem, 4.5vw, 3.8rem); white-space: nowrap;">
                    Kelola Zakat
                    <span class="block text-primary-500">Lebih Mudah,</span>
                    <span class="block">Lebih Amanah.</span>
                </h1>

                {{-- Garis lengkung dekoratif --}}
                <div class="mb-8">
                    <svg viewBox="0 0 520 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                         aria-hidden="true" style="width: 100%; max-width: 520px; height: 20px; display: block;">
                        <path class="nz-curve-path"
                              d="M4 15 Q 80 4, 160 12 T 340 9 T 516 13"
                              stroke="#2d6936" stroke-width="3.5" stroke-linecap="round" fill="none"/>
                        <path class="nz-curve-path-2"
                              d="M4 15 Q 80 4, 160 12 T 340 9 T 516 13"
                              stroke="#86efac" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.6"/>
                    </svg>
                </div>

                <p class="text-neutral-500 text-base sm:text-lg leading-relaxed mb-8 max-w-lg">
                    Sistem manajemen zakat digital untuk masjid dan lembaga amil zakat. Dari penerimaan, penyaluran, hingga laporan konsolidasi — semua dalam satu platform transparan.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-primary-500 text-white font-semibold text-sm rounded-xl shadow-nz-lg hover:bg-primary-600 transition-colors duration-200">
                        Mulai Gratis Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="#fitur"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white text-primary-500 font-semibold text-sm rounded-xl border border-neutral-200 hover:border-primary-300 hover:bg-primary-50 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lihat Fitur
                    </a>
                </div>
            </div>

            {{-- Foto kanan + dekorasi tumpukan kotak di belakang --}}
            <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 nz-photo-area" style="width: 46%;">

                {{-- === DEKORASI TUMPUKAN KOTAK DI BELAKANG FOTO === --}}

                {{-- Kotak besar rotasi — paling belakang --}}
                <div class="nz-box nz-box-1 absolute"
                     style="width: 88%; height: 88%; top: 6%; left: 6%;
                            border: 2.5px solid rgba(45,105,54,0.18);
                            border-radius: 28px;
                            transform: rotate(6deg);
                            background: rgba(45,105,54,0.04);
                            z-index: 1;">
                </div>

                {{-- Kotak medium rotasi berlawanan --}}
                <div class="nz-box nz-box-2 absolute"
                     style="width: 82%; height: 84%; top: 8%; left: 9%;
                            border: 2px solid rgba(45,105,54,0.12);
                            border-radius: 24px;
                            transform: rotate(-4deg);
                            background: rgba(134,239,172,0.06);
                            z-index: 2;">
                </div>

                {{-- Dot grid dekoratif pojok kanan bawah --}}
                <div class="absolute z-1"
                     style="right: -18px; bottom: -18px; width: 90px; height: 90px;
                            background-image: radial-gradient(circle, #2d6936 1.5px, transparent 1.5px);
                            background-size: 14px 14px;
                            opacity: 0.25;">
                </div>

                {{-- Dot grid pojok kiri atas --}}
                <div class="absolute z-1"
                     style="left: -14px; top: -14px; width: 70px; height: 70px;
                            background-image: radial-gradient(circle, #2d6936 1.5px, transparent 1.5px);
                            background-size: 14px 14px;
                            opacity: 0.18;">
                </div>

                {{-- Aksen garis diagonal pojok kanan atas --}}
                <div class="absolute z-1"
                     style="right: 8px; top: -10px;">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <line x1="0" y1="48" x2="48" y2="0" stroke="#2d6936" stroke-width="2" stroke-dasharray="5 5" opacity="0.3"/>
                        <line x1="12" y1="48" x2="48" y2="12" stroke="#2d6936" stroke-width="1.5" stroke-dasharray="4 6" opacity="0.18"/>
                    </svg>
                </div>

                {{-- Lingkaran aksen pojok kiri bawah --}}
                <div class="absolute z-1"
                     style="left: -10px; bottom: 30px;
                            width: 28px; height: 28px;
                            border-radius: 50%;
                            border: 2.5px solid rgba(45,105,54,0.3);
                            background: transparent;">
                </div>
                <div class="absolute z-1"
                     style="left: -22px; bottom: 18px;
                            width: 16px; height: 16px;
                            border-radius: 50%;
                            background: rgba(45,105,54,0.15);">
                </div>

                {{-- FOTO UTAMA — di atas semua dekorasi --}}
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl"
                     style="border: 2px solid rgba(255,255,255,0.8);">
                    <img
                        src="{{ asset('image/zakat.jpg') }}"
                        alt="Pengelolaan Zakat Digital"
                        class="w-full object-cover"
                        style="height: 26rem;"
                    >
                </div>

            </div>

        </div>
    </div>

</section>

<style>
    .nz-curve-path,
    .nz-curve-path-2 {
        stroke-dasharray: 600;
        stroke-dashoffset: 600;
        animation: nz-draw 1.1s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
    }
    .nz-curve-path-2 { animation-delay: 0.5s; }

    @keyframes nz-draw {
        to { stroke-dashoffset: 0; }
    }

    /* Kotak dekorasi: subtle float animasi */
    @keyframes nz-sway-1 {
        0%, 100% { transform: rotate(6deg) translateY(0px); }
        50%       { transform: rotate(6deg) translateY(-6px); }
    }
    @keyframes nz-sway-2 {
        0%, 100% { transform: rotate(-4deg) translateY(0px); }
        50%       { transform: rotate(-4deg) translateY(-4px); }
    }
    .nz-box-1 { animation: nz-sway-1 5s ease-in-out infinite; }
    .nz-box-2 { animation: nz-sway-2 6s ease-in-out 0.5s infinite; }
</style>