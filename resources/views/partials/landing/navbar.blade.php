{{-- partials/landing/navbar.blade.php --}}
<nav id="mainNavbar" class="fixed top-0 left-0 right-0 z-100 bg-transparent transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-nz group-hover:bg-primary-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-bold text-primary-500 text-lg tracking-tight">Niat Zakat</span>
                    <span class="text-neutral-500 text-[10px] font-medium tracking-wider uppercase">Digital</span>
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="#fitur" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Fitur</a>
                <a href="#cara-kerja" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Cara Kerja</a>
                <a href="#statistik" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Statistik</a>
                <a href="#testimoni" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Testimoni</a>
                <a href="#kontak" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Kontak</a>
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
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-1">
            <a href="#fitur" class="px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Fitur</a>
            <a href="#cara-kerja" class="px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Cara Kerja</a>
            <a href="#statistik" class="px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Statistik</a>
            <a href="#testimoni" class="px-4 py-2.5 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all duration-200">Testimoni</a>
            <div class="flex flex-col gap-2 pt-3 border-t border-neutral-100 mt-2">
                <a href="{{ route('login') }}" class="px-4 py-2.5 text-center text-sm font-semibold text-primary-500 border border-primary-500 rounded-xl hover:bg-primary-50 transition-all duration-200">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2.5 text-center text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 shadow-nz transition-all duration-200">Daftar Gratis</a>
            </div>
        </div>
    </div>
</nav>