{{-- partials/landing/navbar.blade.php --}}
{{-- Ambil konfigurasi aplikasi dari database --}}
@php
    $config = \App\Models\KonfigurasiAplikasi::getConfig();
@endphp

<nav id="mainNavbar" class="fixed top-0 left-0 right-0 z-100 bg-transparent transition-all duration-300">
    {{-- Padding sama persis dengan hero section: px-4 sm:px-10 lg:px-20 --}}
    <div class="w-full px-4 sm:px-10 lg:px-20">
        <div class="flex items-center justify-between h-16 lg:h-20">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 group">
                @if($config->favicon)
                    {{-- Logo dari favicon database --}}
                    <div class="w-12 h-12 rounded-2xl bg-white shadow-md ring-1 ring-neutral-200 flex items-center justify-center overflow-hidden flex-shrink-0 group-hover:shadow-lg transition-shadow duration-200">
                        <img
                            src="{{ asset('storage/' . $config->favicon) }}"
                            alt="{{ $config->nama_aplikasi }}"
                            class="w-9 h-9 object-contain"
                        >
                    </div>
                @else
                    {{-- Fallback logo icon --}}
                    <div class="w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center shadow-md ring-1 ring-primary-400 flex-shrink-0 group-hover:bg-primary-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                @endif

                <div class="flex flex-col leading-none gap-0.5">
                    <span class="font-bold text-neutral-900 text-base tracking-tight leading-none">
                        {{ $config->nama_aplikasi }}
                    </span>
                    @if($config->tagline)
                        <span class="text-neutral-500 text-[10px] font-medium tracking-widest uppercase leading-none">
                            {{ $config->tagline }}
                        </span>
                    @endif
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden lg:flex items-center gap-1">

                {{-- Beranda --}}
                <a href="{{ route('landing') }}"
                   data-nav="beranda"
                   class="nav-link relative px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-500 transition-all duration-200 group">
                    Beranda
                    <span class="nav-underline absolute bottom-0 left-4 right-4 h-0.5 bg-primary-500 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-200 origin-left"></span>
                </a>

                {{-- Hitung Zakat --}}
                <a href="{{ route('hitung-zakat') }}"
                   data-nav="hitung-zakat"
                   class="nav-link relative px-4 py-2 text-sm font-semibold text-neutral-700 hover:text-primary-500 transition-all duration-200 group">
                    Hitung Zakat
                    <span class="nav-underline absolute bottom-0 left-4 right-4 h-0.5 bg-primary-500 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-200 origin-left"></span>
                </a>

                {{-- Panduan Dropdown --}}
                <div class="relative group/panduan" data-nav-group="panduan">
                    <button
                        data-nav="panduan"
                        class="nav-link relative px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-500 transition-all duration-200 flex items-center gap-1">
                        Panduan
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover/panduan:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span class="nav-underline absolute bottom-0 left-4 right-4 h-0.5 bg-primary-500 rounded-full scale-x-0 group-hover/panduan:scale-x-100 transition-transform duration-200 origin-left"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div class="absolute top-full left-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-neutral-100 opacity-0 invisible group-hover/panduan:opacity-100 group-hover/panduan:visible transition-all duration-200 translate-y-1 group-hover/panduan:translate-y-0 z-50">
                        <div class="p-1.5 flex flex-col">
                            <a href="{{ route('panduan-zakat') }}"
                               data-nav="panduan-zakat"
                               class="nav-link px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                                Panduan Zakat
                            </a>
                            <a href=""
                               data-nav="artikel"
                               class="nav-link px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                                Artikel Terkini / Buletin
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <a href="#kontak"
                   data-nav="kontak"
                   class="nav-link relative px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-500 transition-all duration-200 group">
                    Kontak
                    <span class="nav-underline absolute bottom-0 left-4 right-4 h-0.5 bg-primary-500 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-200 origin-left"></span>
                </a>

            </div>

            {{-- Desktop CTA --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-primary-500 border border-primary-500 rounded-xl hover:bg-primary-50 transition-all duration-200">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 shadow-nz transition-all duration-200">
                    Daftar Gratis
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-lg text-neutral-700 hover:bg-neutral-100 transition-colors duration-200" aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="hidden lg:hidden bg-white border-t border-neutral-100 shadow-soft-lg">
        <div class="w-full px-4 sm:px-10 py-4 flex flex-col gap-1">

            <a href="{{ route('landing') }}"
               data-nav="beranda"
               class="nav-link px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                Beranda
            </a>

            <a href="{{ route('hitung-zakat') }}"
               data-nav="hitung-zakat"
               class="nav-link px-4 py-2.5 rounded-lg text-sm font-semibold text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                Hitung Zakat
            </a>

            {{-- Panduan Accordion Mobile --}}
            <div>
                <button id="panduan-toggle"
                        onclick="document.getElementById('panduan-mobile').classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')"
                        class="w-full px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200 flex items-center justify-between">
                    Panduan
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="panduan-mobile" class="hidden pl-4 flex flex-col gap-0.5 mt-0.5">
                    <a href="{{ route('panduan-zakat') }}"
                       data-nav="panduan-zakat"
                       class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                        Panduan Zakat
                    </a>
                    <a href=""
                       data-nav="artikel"
                       class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                        Artikel Terkini / Buletin
                    </a>
                </div>
            </div>

            <a href="#kontak"
               data-nav="kontak"
               class="nav-link px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">
                Kontak
            </a>

            <div class="flex flex-col gap-2 pt-3 border-t border-neutral-100 mt-2">
                <a href="{{ route('login') }}" class="px-4 py-2.5 text-center text-sm font-semibold text-primary-500 border border-primary-500 rounded-xl hover:bg-primary-50 transition-all duration-200">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2.5 text-center text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 shadow-nz transition-all duration-200">Daftar Gratis</a>
            </div>
        </div>
    </div>
</nav>

<script>
(function () {
    const path = window.location.pathname;
    let activePage = 'beranda';

    if      (path.includes('hitung-zakat'))  activePage = 'hitung-zakat';
    else if (path.includes('panduan-zakat')) activePage = 'panduan-zakat';
    else if (path.includes('artikel'))       activePage = 'artikel';
    else if (path.includes('kontak'))        activePage = 'kontak';
    else if (path === '/' || path === '')    activePage = 'beranda';

    document.querySelectorAll('.nav-link[data-nav]').forEach(function (el) {
        const nav = el.getAttribute('data-nav');

        if (nav === activePage) {
            el.classList.remove('text-neutral-700', 'text-neutral-600');
            el.classList.add('text-primary-500', 'font-semibold');

            const underline = el.querySelector('.nav-underline');
            if (underline) {
                underline.classList.remove('scale-x-0');
                underline.classList.add('scale-x-100');
            }

            if (!underline) {
                el.classList.add('bg-primary-50');
            }

            const group = el.closest('[data-nav-group]');
            if (group) {
                const parentBtn = group.querySelector('button.nav-link');
                if (parentBtn) {
                    parentBtn.classList.remove('text-neutral-700');
                    parentBtn.classList.add('text-primary-500', 'font-semibold');
                    const btnUnderline = parentBtn.querySelector('.nav-underline');
                    if (btnUnderline) {
                        btnUnderline.classList.remove('scale-x-0');
                        btnUnderline.classList.add('scale-x-100');
                    }
                }
            }

            document.querySelectorAll('#mobileMenu .nav-link[data-nav="' + nav + '"]').forEach(function (ml) {
                ml.classList.remove('text-neutral-700', 'text-neutral-600');
                ml.classList.add('text-primary-500', 'font-semibold', 'bg-primary-50');
            });
        }
    });

    const mobileBtn  = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 10) {
                navbar.classList.add('bg-white/95', 'backdrop-blur-sm', 'shadow-sm');
                navbar.classList.remove('bg-transparent');
            } else {
                navbar.classList.remove('bg-white/95', 'backdrop-blur-sm', 'shadow-sm');
                navbar.classList.add('bg-transparent');
            }
        });
    }
})();
</script>