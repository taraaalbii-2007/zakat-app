{{-- partials/landing/navbar.blade.php --}}
@php
    $config = \App\Models\KonfigurasiAplikasi::getConfig();
@endphp

<style>
    #mainNavbar {
        transition: background 0.4s cubic-bezier(0.16, 1, 0.3, 1),
            box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1),
            backdrop-filter 0.4s ease;
    }

    /* Nav link — soft/transparan saat belum scroll */
    .nav-link-item {
        position: relative;
        padding: 0.4rem 0.85rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: rgba(30, 30, 30, 0.55);
        border-radius: 0.6rem;
        transition: color 0.25s ease;
        white-space: nowrap;
        background: transparent !important;
    }

    .nav-link-item:hover {
        color: #16a34a;
        background: transparent !important;
    }

    /* Saat sudah scroll, warna penuh */
    #mainNavbar.is-scrolled .nav-link-item {
        color: #2d2d2d;
    }

    #mainNavbar.is-scrolled .nav-link-item:hover {
        color: #16a34a;
    }

    /* Aktif: cukup garis bawah hijau, tidak ada background */
    .nav-link-item.active {
        color: #16a34a;
        font-weight: 600;
        background: transparent !important;
    }

    .nav-link-item.active::after {
        content: '';
        position: absolute;
        bottom: 1px;
        left: 0.85rem;
        right: 0.85rem;
        height: 2px;
        border-radius: 2px;
        background: #16a34a;
    }

    /* Dropdown */
    .nav-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%) translateY(-6px);
        min-width: 200px;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 0.4rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1),
            transform 0.22s cubic-bezier(0.16, 1, 0.3, 1),
            visibility 0.22s;
        z-index: 60;
    }

    .nav-dropdown-wrap:hover .nav-dropdown,
    .nav-dropdown-wrap:focus-within .nav-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .nav-dropdown a {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 0.85rem;
        border-radius: 0.65rem;
        font-size: 0.84rem;
        font-weight: 500;
        color: #404040;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .nav-dropdown a:hover {
        background: rgba(22, 163, 74, 0.08);
        color: #16a34a;
    }

    /* CTA Buttons */
    .btn-masuk {
        padding: 0.45rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 600;
        color: rgba(22, 163, 74, 0.75);
        border: 1.5px solid rgba(22, 163, 74, 0.3);
        border-radius: 0.75rem;
        background: transparent;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        white-space: nowrap;
    }

    .btn-masuk:hover {
        background: rgba(22, 163, 74, 0.08);
        border-color: #16a34a;
        color: #16a34a;
    }

    #mainNavbar.is-scrolled .btn-masuk {
        color: #16a34a;
        border-color: rgba(22, 163, 74, 0.5);
    }

    .btn-daftar {
        padding: 0.45rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 600;
        color: #ffffff;
        background: #16a34a;
        border: 1.5px solid transparent;
        border-radius: 0.75rem;
        box-shadow: 0 2px 12px rgba(22, 163, 74, 0.30);
        transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        white-space: nowrap;
    }

    .btn-daftar:hover {
        background: #15803d;
        box-shadow: 0 4px 18px rgba(22, 163, 74, 0.40);
        transform: translateY(-1px);
    }

    .btn-daftar:active {
        transform: translateY(0);
    }

    /* Navbar scrolled state */
    #mainNavbar.is-scrolled {
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(14px) saturate(180%);
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.07), 0 4px 24px rgba(0, 0, 0, 0.05);
    }

    /* Divider vertikal antara links & CTA */
    .nav-divider {
        width: 1px;
        height: 20px;
        background: rgba(0, 0, 0, 0.08);
        margin: 0 0.25rem;
    }

    /* Chevron */
    .nav-chevron {
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        opacity: 0.4;
    }

    .nav-dropdown-wrap:hover .nav-chevron {
        transform: rotate(180deg);
        opacity: 0.8;
    }

    /* Mobile menu */
    #mobileMenu {
        transform: translateY(-8px);
        opacity: 0;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1),
            opacity 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }

    #mobileMenu.is-open {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    .mobile-nav-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.9rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #404040;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .mobile-nav-link:hover,
    .mobile-nav-link.active {
        background: rgba(22, 163, 74, 0.08);
        color: #16a34a;
    }

    /* Hamburger icon morph */
    .hamburger-bar {
        display: block;
        width: 22px;
        height: 2px;
        background: #404040;
        border-radius: 2px;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1),
            opacity 0.2s ease;
        transform-origin: center;
    }

    #mobileMenuBtn.is-open .hamburger-bar:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
    }

    #mobileMenuBtn.is-open .hamburger-bar:nth-child(2) {
        opacity: 0;
        transform: scaleX(0);
    }

    #mobileMenuBtn.is-open .hamburger-bar:nth-child(3) {
        transform: translateY(-7px) rotate(-45deg);
    }
</style>

<nav id="mainNavbar" class="fixed top-0 left-0 right-0 z-[100] bg-transparent">
    <div class="w-full px-4 sm:px-10 lg:px-20">
        <div class="flex items-center justify-between h-16 lg:h-20">

            {{-- Logo --}}
            <a href="/" class="flex items-center group flex-shrink-0">
                <img src="{{ asset('image/logo_zakat.png') }}" alt="{{ $config->nama_aplikasi }}"
                    class="h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-[1.04]"
                    style="mix-blend-mode: multiply;">
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden lg:flex items-center gap-0.5">

                <a href="{{ route('landing') }}" data-nav="beranda" class="nav-link-item nav-link">
                    Beranda
                </a>

                <a href="{{ route('hitung-zakat') }}" data-nav="hitung-zakat" class="nav-link-item nav-link">
                    Hitung Zakat
                </a>

                {{-- Panduan Dropdown --}}
                <div class="nav-dropdown-wrap relative" data-nav-group="panduan">
                    <button data-nav="panduan" class="nav-link-item nav-link flex items-center gap-1">
                        Panduan
                        <svg class="nav-chevron w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div class="nav-dropdown">
                        <a href="{{ route('panduan-zakat') }}" data-nav="panduan-zakat" class="nav-link">
                            <svg class="w-4 h-4 opacity-50 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Panduan Zakat
                        </a>
                        <a href="{{ route('artikel.index') }}" data-nav="artikel" class="nav-link">
                            <svg class="w-4 h-4 opacity-50 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Artikel & Buletin
                        </a>
                    </div>
                </div>

                <a href="{{ route('laporan.index') }}" data-nav="laporan" class="nav-link-item nav-link">
                    Laporan
                </a>

                <a href="/kontak" data-nav="kontak" class="nav-link-item nav-link">
                    Kontak
                </a>

            </div>

            {{-- Desktop CTA --}}
            <div class="hidden lg:flex items-center gap-2.5">
                <div class="nav-divider"></div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-daftar flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-masuk">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-daftar">Daftar Gratis</a>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <button id="mobileMenuBtn"
                class="lg:hidden flex flex-col justify-center items-center gap-[5px] w-9 h-9 rounded-lg hover:bg-neutral-100 transition-colors duration-200"
                aria-label="Toggle menu">
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
                <span class="hamburger-bar"></span>
            </button>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="lg:hidden hidden">
        <div class="mx-4 mb-3 bg-white border border-neutral-100 rounded-2xl shadow-xl shadow-black/5 overflow-hidden">
            <div class="p-2 flex flex-col gap-0.5">

                <a href="{{ route('landing') }}" data-nav="beranda" class="mobile-nav-link nav-link">
                    Beranda
                </a>

                <a href="{{ route('hitung-zakat') }}" data-nav="hitung-zakat" class="mobile-nav-link nav-link">
                    Hitung Zakat
                </a>

                {{-- Panduan Accordion --}}
                <div>
                    <button id="panduan-toggle" class="mobile-nav-link w-full text-left">
                        <span>Panduan</span>
                        <svg id="panduan-arrow" class="w-4 h-4 opacity-40 transition-transform duration-200"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="panduan-mobile" class="hidden px-2 pb-1 flex flex-col gap-0.5">
                        <a href="{{ route('panduan-zakat') }}" data-nav="panduan-zakat"
                            class="mobile-nav-link nav-link text-sm text-neutral-500">
                            Panduan Zakat
                        </a>
                        <a href="{{ route('artikel.index') }}" data-nav="artikel"
                            class="mobile-nav-link nav-link text-sm text-neutral-500">
                            Artikel & Buletin
                        </a>
                    </div>
                </div>

                <a href="{{ route('laporan.index') }}" data-nav="laporan" class="mobile-nav-link nav-link">
                    Laporan
                </a>

                <a href="/kontak" data-nav="kontak" class="mobile-nav-link nav-link">
                    Kontak
                </a>

                {{-- Divider --}}
                <div class="h-px bg-neutral-100 my-1 mx-1"></div>

                {{-- Mobile CTA --}}
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="btn-daftar flex items-center justify-center gap-1.5 text-center py-2.5">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-masuk text-center py-2.5 block">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-daftar text-center py-2.5 block mt-1">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    (function() {
        // ── Active nav highlight ────────────────────────────────────────────────
        const path = window.location.pathname;
        let activePage = 'beranda';

        if (path.includes('hitung-zakat')) activePage = 'hitung-zakat';
        else if (path.includes('panduan-zakat')) activePage = 'panduan-zakat';
        else if (path.includes('artikel')) activePage = 'artikel';
        else if (path.includes('laporan')) activePage = 'laporan';
        else if (path.includes('kontak')) activePage = 'kontak';
        else if (path === '/' || path === '') activePage = 'beranda';

        document.querySelectorAll('.nav-link[data-nav]').forEach(function(el) {
            if (el.getAttribute('data-nav') === activePage) {
                el.classList.add('active');

                // Highlight parent dropdown button jika ada
                const group = el.closest('[data-nav-group]');
                if (group) {
                    const btn = group.querySelector('button.nav-link-item');
                    if (btn) btn.classList.add('active');
                }
            }
        });

        // ── Scroll effect ───────────────────────────────────────────────────────
        const navbar = document.getElementById('mainNavbar');

        function handleScroll() {
            if (window.scrollY > 12) {
                navbar.classList.add('is-scrolled');
            } else {
                navbar.classList.remove('is-scrolled');
            }
        }
        window.addEventListener('scroll', handleScroll, {
            passive: true
        });
        handleScroll(); // run once on load

        // ── Mobile menu toggle ──────────────────────────────────────────────────
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');

                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    // Small tick to allow CSS transition to kick in
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function() {
                            mobileMenu.classList.add('is-open');
                        });
                    });
                    mobileBtn.classList.add('is-open');
                } else {
                    mobileMenu.classList.remove('is-open');
                    mobileBtn.classList.remove('is-open');
                    // Wait for transition before hiding
                    mobileMenu.addEventListener('transitionend', function handler() {
                        mobileMenu.classList.add('hidden');
                        mobileMenu.removeEventListener('transitionend', handler);
                    });
                }
            });
        }

        // ── Panduan accordion mobile ────────────────────────────────────────────
        const panduanToggle = document.getElementById('panduan-toggle');
        const panduanMobile = document.getElementById('panduan-mobile');
        const panduanArrow = document.getElementById('panduan-arrow');

        if (panduanToggle && panduanMobile) {
            panduanToggle.addEventListener('click', function() {
                panduanMobile.classList.toggle('hidden');
                if (panduanArrow) panduanArrow.classList.toggle('rotate-180');
            });
        }

        // ── Close mobile menu on outside click ─────────────────────────────────
        document.addEventListener('click', function(e) {
            if (!navbar.contains(e.target) && mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('is-open');
                mobileBtn && mobileBtn.classList.remove('is-open');
                mobileMenu.addEventListener('transitionend', function handler() {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.removeEventListener('transitionend', handler);
                });
            }
        });
    })();
</script>
