<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#2d6a2d] text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-xl flex flex-col">
    <!-- Logo & Brand -->
    <div class="px-4 py-5 border-b border-white/10">
        <div class="flex items-center space-x-3">
            <div class="w-11 h-11 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0">
                <span class="text-base font-bold text-white">NZ</span>
            </div>
            <div>
                <h1 class="text-base font-bold tracking-tight text-white">Niat Zakata</h1>
                <p class="text-xs text-white/60 mt-0.5">Dari Niat Timbul Manfaat</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-2 py-3 overflow-y-auto">
        <ul class="space-y-0.5">
            @php
                $currentRoute = request()->route()->getName() ?? '';
                $isSuperadmin = auth()->user() && auth()->user()->peran === 'superadmin';
                $isAdminMasjid = auth()->user() && auth()->user()->peran === 'admin_masjid';
                $isAmil = auth()->user() && auth()->user()->peran === 'amil';

                $dataMasterSuperadminRoutes = ['jenis-zakat', 'tipe-zakat', 'kategori-mustahik', 'harga-emas-perak'];
                $isSuperadminMasterOpen = collect($dataMasterSuperadminRoutes)->contains(fn($r) => str_contains($currentRoute, $r));

                $dataMasterAdminRoutes = ['program-zakat', 'rekening-masjid'];
                $isAdminMasterOpen = collect($dataMasterAdminRoutes)->contains(fn($r) => str_contains($currentRoute, $r));

                $inputTransaksiRoutes = ['zakat-fitrah', 'zakat-mal', 'infaq'];
                $isInputTransaksiOpen = collect($inputTransaksiRoutes)->contains(fn($r) => str_contains($currentRoute, $r));

                $activeClass    = 'bg-white/20 text-white font-semibold';
                $inactiveClass  = 'text-white/80 hover:bg-white/10 hover:text-white';
                $subActive      = 'bg-white/20 text-white font-semibold';
                $subInactive    = 'text-white/70 hover:bg-white/10 hover:text-white';
                $sectionLabel   = 'px-2 text-[10px] font-semibold text-white/40 uppercase tracking-wider';
                $summaryClass   = 'flex items-center w-full px-2 py-2 rounded-md hover:bg-white/10 hover:text-white cursor-pointer text-sm text-white/80 list-none';
                $subBorder      = 'ml-4 mt-0.5 space-y-0.5 pl-3 border-l border-white/15';
            @endphp

            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- SUPERADMIN MENU -->
            @if($isSuperadmin)
                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Superadmin</p>
                </li>

                <!-- Konfigurasi Aplikasi -->
                <li>
                    <a href="{{ route('konfigurasi-global.show') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'konfigurasi-global') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Konfigurasi Aplikasi</span>
                    </a>
                </li>

                <!-- Data Master Superadmin -->
                <li>
                    <details class="group" {{ $isSuperadminMasterOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="flex-1 ml-2.5">Data Master</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('jenis-zakat.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'jenis-zakat') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span>Jenis Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tipe-zakat.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'tipe-zakat') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    <span>Tipe Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kategori-mustahik.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'kategori-mustahik') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Kategori Mustahik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('harga-emas-perak.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'harga-emas-perak') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Harga Emas & Perak</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- Kelola Masjid -->
                <li>
                    <a href="{{ route('masjid.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'masjid') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Kelola Masjid</span>
                    </a>
                </li>

                <!-- Log Aktivitas -->
                <li>
                    <a href="{{ route('log-aktivitas.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'log-aktivitas') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                </li>

                <!-- Laporan Konsolidasi -->
                <li>
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'laporan-konsolidasi') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Laporan Konsolidasi</span>
                    </a>
                </li>
            @endif

            <!-- ADMIN MASJID MENU -->
            @if($isAdminMasjid)
                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Admin Masjid</p>
                </li>

                <!-- Data Master Admin -->
                <li>
                    <details class="group" {{ $isAdminMasterOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="flex-1 ml-2.5">Data Master</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('program-zakat.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'program-zakat') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>Program Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rekening-masjid.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'rekening-masjid') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <span>Rekening Masjid</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- Kelola Amil -->
                <li>
                    <a href="{{ route('amil.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'amil') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kelola Amil</span>
                    </a>
                </li>
            @endif

            <!-- AMIL MENU (Tampil untuk Amil dan Admin Masjid) -->
            @if($isAmil || $isAdminMasjid)

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Konfigurasi</p>
                </li>

                <li>
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'konfigurasi-integrasi') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Konfigurasi Masjid</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Data Penerima</p>
                </li>

                <!-- Mustahik -->
                <li>
                    <a href="{{ route('mustahik.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'mustahik') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Transaksi Zakat</p>
                </li>

                <!-- Input Transaksi -->
                <li>
                    <details class="group" {{ $isInputTransaksiOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="flex-1 ml-2.5">Input Transaksi</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            {{-- tambahkan sub-menu transaksi di sini --}}
                        </ul>
                    </details>
                </li>

                <!-- Laporan -->
                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Laporan</p>
                </li>
            @endif

            <!-- Pengaturan Umum -->
            <li class="mt-3 mb-1">
                <p class="{{ $sectionLabel }}">Pengaturan</p>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-left text-[13px] text-white/80 hover:bg-white/10 hover:text-white">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-4 py-3 border-t border-white/10">
        <div class="text-center">
            <p class="text-xs text-white/40">© {{ date('Y') }} Niat Zakata</p>
            <p class="text-xs text-white/40 mt-0.5">v1.0.0</p>
        </div>
    </div>
</aside>

<!-- Mobile Toggle Button -->
<button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-[#2d6a2d] text-white shadow-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"></div>

@push('scripts')
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    document.addEventListener('click', (e) => {
        if (window.innerWidth < 1024 && 
            !sidebar.contains(e.target) && 
            !toggleBtn.contains(e.target) &&
            !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });
</script>
@endpush