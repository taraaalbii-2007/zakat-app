<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-gray-800 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col shadow-lg"
    style="border-right: 1px solid #eef0f2;">

    <!-- Logo & Brand - Dengan border dan jarak proporsional -->
    <div class="px-5 pt-5 pb-4">
        <div class="flex items-center space-x-3">
            @if (!empty($appConfig->logo_aplikasi))
                <img src="{{ asset('storage/' . $appConfig->logo_aplikasi) }}" alt="{{ $appConfig->nama_aplikasi }}"
                    class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-200 shadow-sm">
            @else
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-primary border border-white/20 shadow-sm">
                    <span class="text-sm font-bold text-white tracking-wide">
                        {{ strtoupper(substr($appConfig->nama_aplikasi ?? 'App', 0, 2)) }}
                    </span>
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <h1 class="text-sm font-bold text-gray-900 truncate leading-tight">
                    {{ $appConfig->nama_aplikasi ?? 'Aplikasi Zakat' }}
                </h1>
                <p class="text-[10px] text-gray-400 mt-0.5 truncate">
                    {{ $appConfig->tagline ?? 'Dari Niat Timbul Manfaat' }}
                </p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-4 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
        <ul class="space-y-1">
            @php
                $currentRoute = request()->route()->getName() ?? '';
                $isSuperadmin = auth()->user() && auth()->user()->peran === 'superadmin';
                $isAdminLembaga = auth()->user() && auth()->user()->peran === 'admin_lembaga';
                $isAmil = auth()->user() && auth()->user()->peran === 'amil';

                // Helper untuk classes menu aktif
                $isActive = function ($routes, $exact = false) use ($currentRoute) {
                    if ($exact) {
                        return $currentRoute === $routes;
                    }
                    foreach ((array) $routes as $route) {
                        if (str_contains($currentRoute, $route)) {
                            return true;
                        }
                    }
                    return false;
                };

                $menuItemClass =
                    'flex items-center w-full gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group';
                $activeMenuItemClass = 'bg-primary-50 text-primary-700';
                $inactiveMenuItemClass = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';

                $subMenuItemClass = 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all duration-200';
                $activeSubClass = 'text-primary-700 bg-primary-50/50 font-medium';
                $inactiveSubClass = 'text-gray-500 hover:text-gray-900 hover:bg-gray-50';

                $sectionLabel = 'px-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2';
            @endphp

            <!-- ============================================ -->
            <!-- SUPERADMIN MENU                              -->
            <!-- ============================================ -->
            @if ($isSuperadmin)
                <!-- DASHBOARD -->
                <li>
                    <p class="{{ $sectionLabel }}">DASHBOARD</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ $menuItemClass }} {{ $isActive('dashboard') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DATA MASTER -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA MASTER</p>
                </li>
                <li>
                    @php $isMasterOpen = $isActive(['jenis-zakat', 'tipe-zakat', 'kategori-mustahik', 'harga-emas-perak']); @endphp
                    <details class="group" {{ $isMasterOpen ? 'open' : '' }}>
                        <summary
                            class="{{ $menuItemClass }} {{ $isMasterOpen ? $activeMenuItemClass : $inactiveMenuItemClass }} list-none cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $isMasterOpen ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="flex-1 text-left">Data Master</span>
                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 group-open:rotate-180 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-6 space-y-1 border-l-2 border-primary-100 pl-3">
                            <li>
                                <a href="{{ route('jenis-zakat.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('jenis-zakat') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                    <span>Jenis Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tipe-zakat.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('tipe-zakat') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span>Tipe Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kategori-mustahik.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('kategori-mustahik') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Kategori Mustahik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('harga-emas-perak.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('harga-emas-perak') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3 .895 3 2s-1.343 2-3 2m0-4v2m0 4v2m0-6V4M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    <span>Harga Emas & Perak</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- DATA PENGGUNA -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA PENGGUNA</p>
                </li>
                <li>
                    <a href="{{ route('pengguna.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('pengguna') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('pengguna') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Pengguna</span>
                    </a>
                </li>

                <!-- DATA ARTIKEL -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA ARTIKEL</p>
                </li>
                <li>
                    @php $isArtikelOpen = $isActive(['bulletin', 'kategori-bulletin']); @endphp
                    <details class="group" {{ $isArtikelOpen ? 'open' : '' }}>
                        <summary
                            class="{{ $menuItemClass }} {{ $isArtikelOpen ? $activeMenuItemClass : $inactiveMenuItemClass }} list-none cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $isArtikelOpen ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <span class="flex-1 text-left">Bulletin</span>
                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 group-open:rotate-180 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-6 space-y-1 border-l-2 border-primary-100 pl-3">
                            <li>
                                <a href="{{ route('superadmin.kategori-bulletin.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('kategori-bulletin') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                    <span>Kategori Bulletin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.bulletin.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('superadmin.bulletin') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    <span>Kelola Bulletin</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- DATA LEMBAGA -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA LEMBAGA</p>
                </li>
                <li>
                    <a href="{{ route('lembaga.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('lembaga') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('lembaga') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Lembaga</span>
                    </a>
                </li>

                <!-- DATA AMIL -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA AMIL</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.amil.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('superadmin.amil') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('superadmin.amil') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Amil</span>
                    </a>
                </li>

                <!-- DATA MUSTAHIK -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA MUSTAHIK</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.mustahik.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('superadmin.mustahik') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('superadmin.mustahik') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Mustahik</span>
                    </a>
                </li>

                <!-- DATA MUZAKI -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA MUZAKI</p>
                </li>
                <li>
                    <a href="{{ route('muzaki.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('muzaki') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('muzaki') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Muzaki</span>
                    </a>
                </li>

                <!-- DATA TRANSAKSI -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA TRANSAKSI</p>
                </li>
                <li>
                    @php $isTransaksiOpen = $isActive(['transaksi-penerimaan', 'transaksi-penyaluran']); @endphp
                    <details class="group" {{ $isTransaksiOpen ? 'open' : '' }}>
                        <summary
                            class="{{ $menuItemClass }} {{ $isTransaksiOpen ? $activeMenuItemClass : $inactiveMenuItemClass }} list-none cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $isTransaksiOpen ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="flex-1 text-left">Transaksi</span>
                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 group-open:rotate-180 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-6 space-y-1 border-l-2 border-primary-100 pl-3">
                            <li>
                                <a href="{{ route('superadmin.transaksi-penerimaan.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('transaksi-penerimaan') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Transaksi Penerimaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.transaksi-penyaluran.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('transaksi-penyaluran') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Transaksi Penyaluran</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- TESTIMONI -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">TESTIMONI</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.testimoni.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('superadmin-testimoni') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('superadmin-testimoni') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>Kelola Testimoni</span>
                    </a>
                </li>

                <!-- LAPORAN KEUANGAN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">LAPORAN KEUANGAN</p>
                </li>
                <li>
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('laporan-konsolidasi') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('laporan-konsolidasi') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Keuangan Seluruh Lembaga</span>
                    </a>
                </li>

                <!-- RIWAYAT -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">RIWAYAT</p>
                </li>
                <li>
                    <a href="{{ route('log-aktivitas.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('log-aktivitas') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('log-aktivitas') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                </li>

                <!-- PENGATURAN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">PENGATURAN</p>
                </li>
                <li>
                    <a href="{{ route('konfigurasi-global.show') }}"
                        class="{{ $menuItemClass }} {{ $isActive('konfigurasi-global') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('konfigurasi-global') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Konfigurasi Aplikasi</span>
                    </a>
                </li>
            @endif

            <!-- ============================================ -->
            <!-- ADMIN LEMBAGA MENU                           -->
            <!-- ============================================ -->
            @if ($isAdminLembaga)
                <!-- MENU UTAMA -->
                <li>
                    <p class="{{ $sectionLabel }}">MENU UTAMA</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ $menuItemClass }} {{ $isActive('dashboard') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DATA UTAMA -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA UTAMA</p>
                </li>
                <li>
                    @php $isAdminMasterOpen = $isActive(['program-zakat', 'rekening-lembaga']); @endphp
                    <details class="group" {{ $isAdminMasterOpen ? 'open' : '' }}>
                        <summary
                            class="{{ $menuItemClass }} {{ $isAdminMasterOpen ? $activeMenuItemClass : $inactiveMenuItemClass }} list-none cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $isAdminMasterOpen ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="flex-1 text-left">Data Master</span>
                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 group-open:rotate-180 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-6 space-y-1 border-l-2 border-primary-100 pl-3">
                            <li>
                                <a href="{{ route('program-zakat.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('program-zakat') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                    <span>Program Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rekening-lembaga.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('rekening-lembaga') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Rekening Lembaga</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- BULLETIN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">BULLETIN</p>
                </li>
                <li>
                    <a href="{{ route('admin-lembaga.bulletin.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive(['admin-lembaga.bulletin', 'bulletin-saya']) ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive(['admin-lembaga.bulletin', 'bulletin-saya']) ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Bulletin Saya</span>
                    </a>
                </li>

                <!-- KELOLA AMIL -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">KELOLA AMIL</p>
                </li>
                <li>
                    <a href="{{ route('amil.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('amil') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('amil') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Data Amil</span>
                    </a>
                </li>

                <!-- KELOLA MUSTAHIK -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">KELOLA MUSTAHIK</p>
                </li>
                <li>
                    <a href="{{ route('mustahik.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('mustahik') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('mustahik') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                <!-- KELOLA MUZAKI -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">KELOLA MUZAKI</p>
                </li>
                <li>
                    <a href="{{ route('admin-lembaga.muzaki.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('admin-lembaga.muzaki') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('admin-lembaga.muzaki') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Data Muzaki</span>
                    </a>
                </li>

                <!-- PENYALURAN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">PENYALURAN</p>
                </li>
                <li>
                    <a href="{{ route('transaksi-penyaluran.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('transaksi-penyaluran') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('transaksi-penyaluran') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Penyaluran</span>
                    </a>
                </li>

                <!-- SETOR KAS -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">SETOR KAS</p>
                </li>
                <li>
                    <a href="{{ route('admin-lembaga.setor-kas.pending') }}"
                        class="{{ $menuItemClass }} {{ $isActive('setor-kas') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('setor-kas') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                        <span>Setor Kas Amil</span>
                    </a>
                </li>

                <!-- LAPORAN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">LAPORAN</p>
                </li>
                <li>
                    <a href="{{ route('laporan-keuangan.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('laporan-keuangan') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('laporan-keuangan') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Keuangan</span>
                    </a>
                </li>

                <!-- PENGATURAN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">PENGATURAN</p>
                </li>
                <li>
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                        class="{{ $menuItemClass }} {{ $isActive('konfigurasi-integrasi') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('konfigurasi-integrasi') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Konfigurasi Lembaga</span>
                    </a>
                </li>
            @endif

            <!-- ============================================ -->
            <!-- AMIL MENU                                    -->
            <!-- ============================================ -->
            @if ($isAmil)
                @php
                    $transaksiRoutes = [
                        'pemantauan-transaksi',
                        'transaksi-datang-langsung',
                        'transaksi-daring',
                        'transaksi-dijemput',
                        'transaksi-penyaluran',
                    ];
                    $isTransaksiOpen = $isActive($transaksiRoutes);
                    $isKasOpen = $isActive(['kas-harian', 'setor-kas']);
                @endphp

                <!-- MENU UTAMA -->
                <li>
                    <p class="{{ $sectionLabel }}">MENU UTAMA</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ $menuItemClass }} {{ $isActive('dashboard') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DATA PENERIMA -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">DATA PENERIMA</p>
                </li>
                <li>
                    <a href="{{ route('mustahik.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('mustahik') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('mustahik') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                <!-- KELOLA TRANSAKSI -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">KELOLA TRANSAKSI</p>
                </li>
                <li>
                    <details class="group" {{ $isTransaksiOpen ? 'open' : '' }}>
                        <summary
                            class="{{ $menuItemClass }} {{ $isTransaksiOpen ? $activeMenuItemClass : $inactiveMenuItemClass }} list-none cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $isTransaksiOpen ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="flex-1 text-left">Kelola Transaksi</span>
                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 group-open:rotate-180 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-6 space-y-1 border-l-2 border-primary-100 pl-3">
                            <li class="mt-0.5 mb-0.5 px-2">
                                <p class="text-[9px] font-semibold uppercase tracking-wider text-gray-400">KESELURUHAN
                                </p>
                            </li>
                            <li>
                                <a href="{{ route('pemantauan-transaksi.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('pemantauan-transaksi') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Pemantauan Transaksi</span>
                                </a>
                            </li>
                            <li class="mt-1 mb-0.5 px-2">
                                <p class="text-[9px] font-semibold uppercase tracking-wider text-gray-400">METODE</p>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-datang-langsung.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('transaksi-datang-langsung') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span>Datang Langsung</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-daring.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('transaksi-daring') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9" />
                                    </svg>
                                    <span>Daring</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-dijemput.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('transaksi-dijemput') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Dijemput</span>
                                </a>
                            </li>
                            <li class="mt-1 mb-0.5 px-2">
                                <p class="text-[9px] font-semibold uppercase tracking-wider text-gray-400">TRANSAKSI
                                </p>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-penyaluran.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('transaksi-penyaluran') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Transaksi Penyaluran</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- KAS ANDA -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">KAS ANDA</p>
                </li>
                <li>
                    <details class="group" {{ $isKasOpen ? 'open' : '' }}>
                        <summary
                            class="{{ $menuItemClass }} {{ $isKasOpen ? $activeMenuItemClass : $inactiveMenuItemClass }} list-none cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $isKasOpen ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="flex-1 text-left">Kas</span>
                            <svg class="w-4 h-4 flex-shrink-0 transform transition-transform duration-200 group-open:rotate-180 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="mt-1 ml-6 space-y-1 border-l-2 border-primary-100 pl-3">
                            <li>
                                <a href="{{ route('kas-harian.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('kas-harian') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Kas Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('amil.setor-kas.index') }}"
                                    class="{{ $subMenuItemClass }} {{ $isActive('setor-kas') ? $activeSubClass : $inactiveSubClass }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                    </svg>
                                    <span>Setor Kas</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- KUNJUNGAN -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">KUNJUNGAN</p>
                </li>
                <li>
                    <a href="{{ route('amil.kunjungan.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('kunjungan') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('kunjungan') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Kunjungan Mustahik</span>
                    </a>
                </li>
            @endif

            <!-- ============================================ -->
            <!-- MUZAKKI MENU                                 -->
            <!-- ============================================ -->
            @if (auth()->user() && auth()->user()->peran === 'muzakki')
                <!-- MENU UTAMA -->
                <li>
                    <p class="{{ $sectionLabel }}">MENU UTAMA</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ $menuItemClass }} {{ $isActive('dashboard') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- ZAKAT SAYA -->
                <li class="mt-4">
                    <p class="{{ $sectionLabel }}">ZAKAT SAYA</p>
                </li>
                <li>
                    <a href="{{ route('transaksi-daring-muzakki.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('transaksi-daring-muzakki') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('transaksi-daring-muzakki') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Bayar Zakat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('riwayat-transaksi-muzakki') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('riwayat-transaksi-muzakki') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Riwayat Zakat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('muzakki.testimoni.index') }}"
                        class="{{ $menuItemClass }} {{ $isActive('testimoni-saya') ? $activeMenuItemClass : $inactiveMenuItemClass }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isActive('testimoni-saya') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span>Testimoni Saya</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-4 py-3 border-t border-gray-100">
        <p class="text-[10px] text-gray-400 text-center">© {{ date('Y') }}
            {{ $appConfig->nama_aplikasi ?? 'Niat Zakat' }}</p>
    </div>
</aside>

<!-- Mobile Toggle Button -->
<button id="sidebar-toggle"
    class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-xl bg-white text-gray-600 shadow-md hover:shadow-lg transition-shadow duration-200"
    style="border: 1px solid #eef0f2;">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black/40 z-40 hidden backdrop-blur-sm"></div>

@push('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024 &&
                sidebar && toggleBtn &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target) &&
                !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }
        });
    </script>
@endpush
