{{-- ============================================================
     SECTION: FITUR (MODERN CAROUSEL + CLICK ANIMATION)
     ============================================================ --}}

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    .nz-reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .nz-reveal.nz-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .bg-hex-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='104' viewBox='0 0 60 104' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 104V88m0-68V0M0 52h16m28 0h16M15 26L4 19.5m52 32.5l-11-6.5m-22 39l-11 6.5m52-32.5l-11-6.5' stroke='%232d6936' stroke-width='1.2' stroke-opacity='0.08' fill='none'/%3E%3Cpath d='M60 26L30 8L0 26v36l30 18 30-18V26z' stroke='%232d6936' stroke-width='0.8' stroke-opacity='0.05' fill='none'/%3E%3C/svg%3E");
    }

    /* ── UNDERLINE ANIMASI NIAT ZAKAT — sekali saat scroll ── */
    .nz-underline-svg {
        display: block;
        width: 100%;
        height: 10px;
        overflow: visible;
        margin-top: 2px;
    }

    .nz-underline-path {
        fill: none;
        stroke: #16a34a;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-dasharray: 300;
        stroke-dashoffset: 300;
    }

    .nz-underline-path.nz-draw {
        animation: drawUnderline 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        animation-delay: 0.3s;
    }

    @keyframes drawUnderline {
        from {
            stroke-dashoffset: 300;
        }

        to {
            stroke-dashoffset: 0;
        }
    }

    /* ── CARD STYLING — hijau cerah seperti btn daftar gratis ── */
    .feature-card {
        transition: all 0.4s ease;
        border: none;
        cursor: pointer;
        position: relative;
        height: 100%;
        border-radius: 1.75rem;
        overflow: hidden;
        background: linear-gradient(145deg, #22c55e 0%, #16a34a 60%, #15803d 100%);
        box-shadow: 0 8px 32px rgba(22, 163, 74, 0.22);
        color: white;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(22, 163, 74, 0.32);
    }

    /* Blob decoration */
    .feature-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='200' height='200' viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cellipse cx='160' cy='40' rx='120' ry='80' fill='rgba(255,255,255,0.07)'/%3E%3Cellipse cx='40' cy='170' rx='100' ry='60' fill='rgba(255,255,255,0.05)'/%3E%3C/svg%3E") no-repeat center/cover;
        pointer-events: none;
        z-index: 0;
    }

    .feature-card>* {
        position: relative;
        z-index: 1;
    }

    /* Icon box */
    .feature-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    /* Number watermark */
    .feature-num {
        font-size: 4rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.08);
        position: absolute;
        top: 0.75rem;
        right: 1.5rem;
        font-style: italic;
        line-height: 1;
        pointer-events: none;
    }

    /* ── PANAH OVERLAY — muncul saat hover area carousel ── */
    .fitur-carousel-wrap {
        position: relative;
    }

    .fitur-nav-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 30;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .fitur-nav-overlay.left {
        left: -10px;
    }

    .fitur-nav-overlay.right {
        right: -10px;
    }

    /* Tampilkan saat hover wrapper */
    .fitur-carousel-wrap:hover .fitur-nav-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .fitur-nav-btn {
        width: 48px;
        height: 48px;
        background: white;
        border: none;
        border-radius: 50%;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.13);
        cursor: pointer;
        transition: background 0.25s ease, color 0.25s ease, transform 0.2s ease;
    }

    .fitur-nav-btn:hover {
        background: white;
        color: #16a34a;
        transform: scale(1.1);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.18);
    }

    /* ── PAGINATION ── */
    .fitur-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin-top: 28px;
    }

    .fitur-pagination .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #bbf7d0;
        border-radius: 99px;
        opacity: 1;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .fitur-pagination .swiper-pagination-bullet-active {
        background: #16a34a !important;
        width: 24px !important;
        border-radius: 99px !important;
    }
</style>

<section id="fitur" class="relative py-24 bg-white overflow-hidden">
    <div class="absolute inset-0 bg-hex-pattern opacity-100 pointer-events-none"></div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20 mx-auto">

{{-- HEADER — gaya sama seperti section Cara Kerja --}}
<div class="text-center mb-12 nz-reveal">
    <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
        Fitur Unggulan
    </span>

    <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
        Kenapa Memilih
        <span class="relative inline-block text-primary-600">
            Niat Zakat?
            {{-- SVG underline — draw animasi saat section masuk viewport --}}
            <svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11" preserveAspectRatio="none">
                <path class="nz-underline-path" id="nzUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6" />
            </svg>
        </span>
    </h2>

    <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
        Platform zakat digital yang mudah, transparan, dan terpercaya — dirancang untuk membantu Anda menunaikan kewajiban dengan tenang.
    </p>
</div>

        {{-- CAROUSEL WRAPPER dengan panah overlay kiri/kanan --}}
        <div class="fitur-carousel-wrap nz-reveal" style="transition-delay: 200ms">

            {{-- Panah KIRI --}}
            <div class="fitur-nav-overlay left">
                <button class="fitur-nav-btn btn-prev" aria-label="Sebelumnya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            {{-- Panah KANAN --}}
            <div class="fitur-nav-overlay right">
                <button class="fitur-nav-btn btn-next" aria-label="Berikutnya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            {{-- SWIPER --}}
            <div class="swiper fiturSwiper">
                <div class="swiper-wrapper">
                    @php
                        $features = [
                            [
                                '01',
                                'Transparan & Terpercaya',
                                'Setiap transaksi tercatat detail & real-time. Laporan terbuka untuk semua donatur.',
                                'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                            ],
                            [
                                '02',
                                'Kalkulator Otomatis',
                                'Hitung zakat mal, profesi, & fitrah secara instan berdasarkan nisab terbaru.',
                                'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                            ],
                            [
                                '03',
                                'Pembayaran Fleksibel',
                                'Mendukung QRIS, E-Wallet, & Transfer Bank untuk kemudahan transaksi.',
                                'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                            ],
                            [
                                '04',
                                'Laporan Real-Time',
                                'Pantau penyaluran zakat secara langsung dengan notifikasi transparan.',
                                'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                            ],
                            [
                                '05',
                                'Mustahik Terverifikasi',
                                'Zakat disalurkan kepada yang berhak melalui proses verifikasi ketat.',
                                'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                            ],
                            [
                                '06',
                                'Sertifikat Digital',
                                'Dapatkan bukti pembayaran sah untuk keperluan pengurang pajak.',
                                'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            ],
                        ];
                    @endphp

                    @foreach ($features as $f)
                        <div class="swiper-slide h-auto">
                            <div class="feature-card p-8 flex flex-col justify-between" style="min-height: 280px;">
                                <div class="feature-num">{{ $f[0] }}</div>
                                <div class="mb-6">
                                    <div class="feature-icon-box">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $f[3] }}" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-3 leading-tight">{{ $f[1] }}</h3>
                                    <p class="text-green-50 text-sm leading-relaxed font-light">{{ $f[2] }}</p>
                                </div>
                                <div class="mt-6 flex items-center space-x-2">
                                    <div class="h-1 w-8 rounded-full bg-white/40"></div>
                                    <div class="h-1 w-4 rounded-full bg-white/20"></div>
                                    <div class="h-1 w-2 rounded-full bg-white/10"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- PAGINATION di bawah carousel --}}
            <div class="fitur-pagination swiper-pagination-fitur mt-7"></div>
        </div>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const swiper = new Swiper('.fiturSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination-fitur',
                clickable: true,
            },
            navigation: {
                nextEl: '.btn-next',
                prevEl: '.btn-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 24
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
            }
        });

        // Reveal observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('nz-visible');
            });
        }, {
            threshold: 0.1
        });
        document.querySelectorAll('.nz-reveal').forEach(el => observer.observe(el));

        // Underline animasi — sekali saat section masuk viewport
        const underlinePath = document.getElementById('nzUnderlinePath');
        if (underlinePath) {
            const underlineObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        underlinePath.classList.add('nz-draw');
                        underlineObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });
            underlineObserver.observe(document.getElementById('fitur'));
        }
    });
</script>
