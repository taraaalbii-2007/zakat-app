{{-- ============================================================
     SECTION: CARA KERJA
     resources/views/partials/landing/sections/cara-kerja.blade.php
     Card hijau gradient (sama dengan fitur), tanpa nomor di card,
     karakter ilustrasi unik per step.
     ============================================================ --}}

<style>
    @keyframes numberPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(45, 105, 54, 0.35);
        }

        50% {
            box-shadow: 0 0 0 12px rgba(45, 105, 54, 0);
        }
    }

    @keyframes nzFloatA {

        0%,
        100% {
            transform: rotate(12deg) translateY(0px);
        }

        50% {
            transform: rotate(12deg) translateY(-9px);
        }
    }

    @keyframes nzFloatB {

        0%,
        100% {
            transform: rotate(-8deg) translateY(0px);
        }

        50% {
            transform: rotate(-8deg) translateY(-7px);
        }
    }

    @keyframes nzFloatC {

        0%,
        100% {
            transform: rotate(5deg) translateY(0px);
        }

        50% {
            transform: rotate(5deg) translateY(-11px);
        }
    }

    @keyframes nzFloatD {

        0%,
        100% {
            transform: rotate(-14deg) translateY(0px);
        }

        50% {
            transform: rotate(-14deg) translateY(-6px);
        }
    }

    /* Underline draw */
    .ck-underline-path {
        fill: none;
        stroke: #16a34a;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-dasharray: 260;
        stroke-dashoffset: 260;
    }

    .ck-underline-path.ck-draw {
        animation: ckDrawLine 1.1s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
    }

    @keyframes ckDrawLine {
        from {
            stroke-dashoffset: 260;
        }

        to {
            stroke-dashoffset: 0;
        }
    }

    .nz-fb-a {
        animation: nzFloatA 5s ease-in-out infinite;
    }

    .nz-fb-b {
        animation: nzFloatB 6s ease-in-out 0.8s infinite;
    }

    .nz-fb-c {
        animation: nzFloatC 7s ease-in-out 1.6s infinite;
    }

    .nz-fb-d {
        animation: nzFloatD 5.5s ease-in-out 0.4s infinite;
    }

    .how-num-badge {
        animation: numberPulse 2.8s ease-in-out infinite;
    }

    .how-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #aed581;
        display: inline-block;
    }

    .how-dot-sm {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #dcedc8;
        display: inline-block;
    }

    /* Slide-in animations */
    @keyframes slideFromRight {
        from {
            opacity: 0;
            transform: translateX(140px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideFromLeft {
        from {
            opacity: 0;
            transform: translateX(-140px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .ck-slide-right,
    .ck-slide-left,
    .ck-badge-slide-left,
    .ck-badge-slide-right {
        opacity: 0;
    }

    .ck-slide-right.ck-visible {
        animation: slideFromRight 0.75s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .ck-slide-left.ck-visible {
        animation: slideFromLeft 0.75s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .ck-badge-slide-left.ck-visible {
        animation: slideFromLeft 0.75s cubic-bezier(0.22, 1, 0.36, 1) 0.1s forwards;
    }

    .ck-badge-slide-right.ck-visible {
        animation: slideFromRight 0.75s cubic-bezier(0.22, 1, 0.36, 1) 0.1s forwards;
    }

    /* ── Step Card — hijau gradient persis seperti fitur card ── */
    .step-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 55%, #15803d 100%);
        border-radius: 1.25rem;
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        min-height: 200px;
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease;
        box-shadow: 0 4px 24px rgba(22, 163, 74, 0.22), 0 1px 4px rgba(0, 0, 0, 0.08);
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(22, 163, 74, 0.32), 0 2px 8px rgba(0, 0, 0, 0.10);
    }

    /* Shine overlay */
    .step-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 80% 60% at 20% 20%, rgba(255, 255, 255, 0.13) 0%, transparent 70%);
        pointer-events: none;
        border-radius: inherit;
        z-index: 0;
    }

    /* ── Dekorasi garis lengkung di dalam card ── */
    .step-card-deco {
        position: absolute;
        inset: 0;
        pointer-events: none;
        border-radius: inherit;
        overflow: hidden;
        z-index: 1;
    }

    .step-card-deco svg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Icon box (sama dengan fitur) */
    .step-icon-box {
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
        margin-bottom: auto;
        position: relative;
        z-index: 2;
    }

    .step-icon-box svg {
        width: 22px;
        height: 22px;
        stroke: white;
    }

    /* Teks */
    .step-card-body {
        margin-top: 2rem;
        position: relative;
        z-index: 2;
    }

    .step-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .step-card-desc {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.6;
    }

    /* Garis dekoratif bawah */
    .step-card-footer {
        margin-top: 1.25rem;
        display: flex;
        align-items: center;
        gap: 6px;
        position: relative;
        z-index: 2;
    }

    .step-card-line {
        height: 3px;
        border-radius: 99px;
        background: rgba(255, 255, 255, 0.5);
    }

    .step-card-line-sm {
        height: 3px;
        border-radius: 99px;
        background: rgba(255, 255, 255, 0.25);
    }
</style>

<section id="cara-kerja" class="relative pt-4 pb-24 md:pt-10 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="ck-diag-pat" x="0" y="0" width="56" height="56" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="56" x2="56" y2="0" stroke="rgba(45,105,54,0.055)"
                        stroke-width="1" stroke-dasharray="4 7" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#ck-diag-pat)" />
        </svg>
        <div class="nz-fb-a absolute rounded-2xl border-2"
            style="width:88px;height:88px;top:7%;left:4%;border-color:rgba(45,105,54,0.12);background:rgba(45,105,54,0.025);transform:rotate(12deg);">
        </div>
        <div class="nz-fb-b absolute rounded-xl border-2"
            style="width:60px;height:60px;top:15%;right:6%;border-color:rgba(45,105,54,0.09);background:rgba(45,105,54,0.02);transform:rotate(-8deg);">
        </div>
        <div class="nz-fb-c absolute rounded-2xl border"
            style="width:110px;height:110px;bottom:10%;left:2%;border-color:rgba(45,105,54,0.08);background:rgba(45,105,54,0.015);transform:rotate(5deg);">
        </div>
        <div class="nz-fb-d absolute rounded-xl border"
            style="width:68px;height:68px;bottom:18%;right:4%;border-color:rgba(45,105,54,0.1);background:rgba(45,105,54,0.02);transform:rotate(-14deg);">
        </div>
        <div class="absolute inset-0"
            style="background:radial-gradient(ellipse 65% 70% at 50% 50%, rgba(255,255,255,0.78) 0%, transparent 100%);">
        </div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">

        {{-- Heading --}}
        <div class="text-center mb-16 nz-reveal">
            <span
                class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Mudah
                &amp; Cepat</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                Cara Kerja
                <span class="relative inline-block text-primary-600">
                    Niat Zakat
                    <svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11"
                        preserveAspectRatio="none">
                        <path class="ck-underline-path" id="ckUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6" />
                    </svg>
                </span>
            </h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">Mulai kelola zakat lembaga Anda hanya dalam 3 langkah
            </p>
        </div>

        <div class="flex flex-col items-center space-y-12 md:space-y-0">

            {{-- ─────────── Step 1 — badge kiri, card kanan ─────────── --}}
            <div class="w-full flex flex-col md:flex-row items-center gap-4 md:gap-5" data-ck-step="1">
                <div class="flex-shrink-0 flex flex-col items-center md:items-end ck-badge-slide-left">
                    <div class="flex items-center gap-3 md:flex-row-reverse">
                        <div
                            class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz">
                            <span class="text-2xl font-black text-white">1</span>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end"><span class="how-dot"></span><span
                                class="how-dot-sm"></span></div>
                    </div>
                </div>

                {{-- Card 1 — Daftar & Verifikasi --}}
                <div class="step-card ck-slide-right" style="width: 55%; min-width: 320px;">

                    {{-- Dekorasi garis lengkung unik: step 1 — kurva besar kanan bawah + lingkaran & arc halus --}}
                    <div class="step-card-deco" aria-hidden="true">
                        <svg viewBox="0 0 500 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Busur besar kanan bawah -->
                            <path d="M380,200 Q500,120 500,-10" stroke="rgba(255,255,255,0.10)" stroke-width="2.5"
                                fill="none" />
                            <!-- Kurva menengah kanan -->
                            <path d="M300,200 Q460,100 480,0" stroke="rgba(255,255,255,0.07)" stroke-width="1.5"
                                fill="none" />
                            <!-- Lengkungan lembut dari kiri bawah ke kanan -->
                            <path d="M-10,170 Q160,110 340,190" stroke="rgba(255,255,255,0.06)" stroke-width="1.5"
                                fill="none" />
                            <!-- Garis putus-putus tipis melengkung -->
                            <path d="M-10,140 Q200,80 500,130" stroke="rgba(255,255,255,0.07)" stroke-width="1"
                                fill="none" stroke-dasharray="5 10" />
                            <!-- Lingkaran dekoratif pojok kanan atas -->
                            <circle cx="460" cy="30" r="55" stroke="rgba(255,255,255,0.07)"
                                stroke-width="1.5" fill="none" />
                            <circle cx="460" cy="30" r="35" stroke="rgba(255,255,255,0.05)"
                                stroke-width="1" fill="none" />
                            <!-- Arc kecil kiri bawah -->
                            <path d="M0,200 Q60,150 100,200" stroke="rgba(255,255,255,0.08)" stroke-width="1.5"
                                fill="none" />
                        </svg>
                    </div>

                    {{-- Icon box --}}
                    <div class="step-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div class="step-card-body">
                        <h3 class="step-card-title">Daftar &amp; Verifikasi</h3>
                        <p class="step-card-desc">Daftarkan lembaga atau masjid Anda. Tim kami akan memverifikasi dan
                            mengaktifkan akun dalam 1&times;24 jam.</p>
                    </div>
                    <div class="step-card-footer">
                        <div class="step-card-line" style="width:28px;"></div>
                        <div class="step-card-line-sm" style="width:14px;"></div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex justify-center">
                <div class="w-px h-12 bg-gradient-to-b from-primary-300 to-primary-100 opacity-60"></div>
            </div>

            {{-- ─────────── Step 2 — badge kanan, card kiri ─────────── --}}
            <div class="w-full flex flex-col md:flex-row-reverse items-center gap-4 md:gap-5" data-ck-step="2">
                <div class="flex-shrink-0 flex flex-col items-center md:items-start ck-badge-slide-right">
                    <div class="flex items-center gap-3">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz"
                            style="animation-delay:0.7s">
                            <span class="text-2xl font-black text-white">2</span>
                        </div>
                        <div class="flex flex-col gap-2"><span class="how-dot"></span><span
                                class="how-dot-sm"></span></div>
                    </div>
                </div>

                {{-- Card 2 — Setup & Konfigurasi --}}
                <div class="step-card ck-slide-left" style="width: 55%; min-width: 320px;">

                    {{-- Dekorasi garis lengkung unik: step 2 — gelombang S + lingkaran tersebar --}}
                    <div class="step-card-deco" aria-hidden="true">
                        <svg viewBox="0 0 500 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Gelombang S besar dari kiri ke kanan -->
                            <path d="M-10,60 Q120,10 250,80 Q380,150 510,60" stroke="rgba(255,255,255,0.09)"
                                stroke-width="2" fill="none" />
                            <!-- Gelombang S kecil sejajar di bawah -->
                            <path d="M-10,110 Q120,65 250,120 Q380,175 510,110" stroke="rgba(255,255,255,0.06)"
                                stroke-width="1.5" fill="none" stroke-dasharray="6 9" />
                            <!-- Busur melingkar pojok kanan bawah -->
                            <path d="M420,200 Q510,140 510,30" stroke="rgba(255,255,255,0.08)" stroke-width="2"
                                fill="none" />
                            <!-- Lingkaran besar transparan kanan -->
                            <circle cx="480" cy="160" r="70" stroke="rgba(255,255,255,0.06)"
                                stroke-width="1.5" fill="none" />
                            <!-- Lingkaran kecil kiri atas -->
                            <circle cx="30" cy="25" r="22" stroke="rgba(255,255,255,0.08)"
                                stroke-width="1" fill="none" />
                            <!-- Titik dekoratif -->
                            <circle cx="80" cy="175" r="4" fill="rgba(255,255,255,0.10)" />
                            <circle cx="110" cy="165" r="2.5" fill="rgba(255,255,255,0.07)" />
                            <circle cx="60" cy="185" r="2" fill="rgba(255,255,255,0.07)" />
                        </svg>
                    </div>

                    <div class="step-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="step-card-body">
                        <h3 class="step-card-title">Setup &amp; Konfigurasi</h3>
                        <p class="step-card-desc">Atur jenis zakat, kategori mustahik, data amil, dan konfigurasi
                            masjid sesuai kebutuhan lembaga Anda.</p>
                    </div>
                    <div class="step-card-footer">
                        <div class="step-card-line" style="width:28px;"></div>
                        <div class="step-card-line-sm" style="width:14px;"></div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex justify-center">
                <div class="w-px h-12 bg-gradient-to-b from-primary-300 to-primary-100 opacity-60"></div>
            </div>

            {{-- ─────────── Step 3 — badge kiri, card kanan ─────────── --}}
            <div class="w-full flex flex-col md:flex-row items-center gap-4 md:gap-5" data-ck-step="3">
                <div class="flex-shrink-0 flex flex-col items-center md:items-end ck-badge-slide-left">
                    <div class="flex items-center gap-3 md:flex-row-reverse">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz"
                            style="animation-delay:1.4s">
                            <span class="text-2xl font-black text-white">3</span>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end"><span class="how-dot"></span><span
                                class="how-dot-sm"></span></div>
                    </div>
                </div>

                {{-- Card 3 — Kelola & Laporkan --}}
                <div class="step-card ck-slide-right" style="width: 55%; min-width: 320px;">

                    {{-- Dekorasi garis lengkung unik: step 3 — garis diagonal melengkung + grid dots --}}
                    <div class="step-card-deco" aria-hidden="true">
                        <svg viewBox="0 0 500 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Kurva diagonal lebar dari kiri atas ke kanan bawah -->
                            <path d="M-10,0 Q200,80 510,150" stroke="rgba(255,255,255,0.09)" stroke-width="2.5"
                                fill="none" />
                            <!-- Kurva paralel sedikit di bawahnya -->
                            <path d="M-10,30 Q200,110 510,180" stroke="rgba(255,255,255,0.06)" stroke-width="1.5"
                                fill="none" stroke-dasharray="7 8" />
                            <!-- Arc melingkar pojok kiri bawah -->
                            <path d="M0,120 Q-10,200 80,200" stroke="rgba(255,255,255,0.08)" stroke-width="2"
                                fill="none" />
                            <!-- Lingkaran besar transparan kiri -->
                            <circle cx="40" cy="170" r="65" stroke="rgba(255,255,255,0.06)"
                                stroke-width="1.5" fill="none" />
                            <!-- Busur kecil pojok kanan atas -->
                            <path d="M430,0 Q510,0 510,70" stroke="rgba(255,255,255,0.08)" stroke-width="1.5"
                                fill="none" />
                            <path d="M460,0 Q510,0 510,40" stroke="rgba(255,255,255,0.06)" stroke-width="1"
                                fill="none" />
                            <!-- Titik dekoratif kanan atas -->
                            <circle cx="420" cy="20" r="4" fill="rgba(255,255,255,0.10)" />
                            <circle cx="440" cy="35" r="2.5" fill="rgba(255,255,255,0.07)" />
                            <circle cx="405" cy="38" r="2" fill="rgba(255,255,255,0.07)" />
                            <!-- Garis pendek melengkung kanan bawah -->
                            <path d="M400,200 Q460,170 510,190" stroke="rgba(255,255,255,0.07)" stroke-width="1.5"
                                fill="none" />
                        </svg>
                    </div>

                    <div class="step-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <div class="step-card-body">
                        <h3 class="step-card-title">Kelola &amp; Laporkan</h3>
                        <p class="step-card-desc">Mulai catat penerimaan, salurkan ke mustahik, dan <em>generate</em>
                            laporan konsolidasi kapan saja — transparan dan akuntabel.</p>
                    </div>
                    <div class="step-card-footer">
                        <div class="step-card-line" style="width:28px;"></div>
                        <div class="step-card-line-sm" style="width:14px;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Underline draw ──
        var underlinePath = document.getElementById('ckUnderlinePath');
        if (underlinePath) {
            var ulObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        underlinePath.classList.add('ck-draw');
                        ulObs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });
            ulObs.observe(document.getElementById('cara-kerja'));
        }

        // ── Step slide-in ──
        if ('IntersectionObserver' in window) {
            var stepObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.querySelectorAll(
                            '.ck-slide-right, .ck-slide-left, .ck-badge-slide-left, .ck-badge-slide-right'
                        ).forEach(function(el) {
                            el.classList.add('ck-visible');
                        });
                        stepObs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });
            document.querySelectorAll('[data-ck-step]').forEach(function(row) {
                stepObs.observe(row);
            });
        } else {
            document.querySelectorAll(
                    '.ck-slide-right,.ck-slide-left,.ck-badge-slide-left,.ck-badge-slide-right')
                .forEach(function(el) {
                    el.classList.add('ck-visible');
                });
        }

        // ── Reveal observer (guard) ──
        if (!window._nzRevealInit) {
            window._nzRevealInit = true;
            var revealObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) e.target.classList.add('nz-visible');
                });
            }, {
                threshold: 0.1
            });
            document.querySelectorAll('.nz-reveal,.nz-reveal-left,.nz-reveal-right').forEach(function(el) {
                revealObs.observe(el);
            });
        }
    });
</script>
