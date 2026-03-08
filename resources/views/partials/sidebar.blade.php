<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-gray-800 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col"
    style="border-right: 1px solid #eef0f2; box-shadow: 8px 0 32px rgba(0,0,0,0.07);">

    <!-- Logo & Brand -->
    <div class="px-5 pt-6 pb-5" style="border-bottom: 1px solid #f3f4f6;">
        <div class="flex items-center space-x-3.5">
            @if (!empty($appConfig->logo_aplikasi))
                <img src="{{ asset('storage/' . $appConfig->logo_aplikasi) }}" alt="{{ $appConfig->nama_aplikasi }}"
                    class="w-12 h-12 rounded-2xl object-cover flex-shrink-0"
                    style="box-shadow: 0 4px 14px rgba(23,163,74,0.3);">
            @else
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                    style="background: linear-gradient(135deg, #17a34a 0%, #22c55e 100%); box-shadow: 0 4px 14px rgba(23,163,74,0.35);">
                    <span class="text-sm font-bold text-white tracking-wide">
                        {{ strtoupper(substr($appConfig->nama_aplikasi ?? 'App', 0, 2)) }}
                    </span>
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <h1 class="text-[15px] font-bold text-gray-900 truncate leading-tight"
                    style="letter-spacing: -0.025em;">
                    {{ $appConfig->nama_aplikasi ?? 'Aplikasi Zakat' }}
                </h1>
                <p class="text-[11px] text-gray-400 mt-0.5 truncate leading-snug">
                    {{ $appConfig->tagline ?? '' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #e5e7eb transparent;">
        <ul class="space-y-0.5">
            @php
                $currentRoute = request()->route()->getName() ?? '';
                $isSuperadmin = auth()->user() && auth()->user()->peran === 'superadmin';
                $isAdminLembaga = auth()->user() && auth()->user()->peran === 'admin_lembaga';
                $isAmil = auth()->user() && auth()->user()->peran === 'amil';

                // Superadmin dropdown states
                $dataMasterSuperadminRoutes = ['jenis-zakat', 'tipe-zakat', 'kategori-mustahik', 'harga-emas-perak'];
                $isSuperadminMasterOpen = collect($dataMasterSuperadminRoutes)->contains(
                    fn($r) => str_contains($currentRoute, $r),
                );

                $dataTransaksiRoutes = ['transaksi-penerimaan', 'transaksi-penyaluran'];
                $isSuperadminTransaksiOpen = collect($dataTransaksiRoutes)->contains(
                    fn($r) => str_contains($currentRoute, $r),
                );

                // Admin Lembaga dropdown states
                $dataMasterAdminRoutes = ['program-zakat', 'rekening-lembaga'];
                $isAdminMasterOpen = collect($dataMasterAdminRoutes)->contains(
                    fn($r) => str_contains($currentRoute, $r),
                );

                $inputTransaksiRoutes = ['zakat-fitrah', 'zakat-mal', 'infaq'];
                $isInputTransaksiOpen = collect($inputTransaksiRoutes)->contains(
                    fn($r) => str_contains($currentRoute, $r),
                );

                // UPDATED: active colors sesuai primary palette tailwind.config
                // primary.800 (#166534) untuk teks aktif, primary.50 (#f0fdf4) untuk bg aktif
                $activeClass = 'text-[#166534] font-semibold';
                $inactiveClass = 'text-gray-700 hover:text-gray-900 hover:bg-gray-100';
                $subActive = 'text-[#166534] font-semibold bg-[#f0fdf4]';
                $subInactive = 'text-gray-600 hover:text-gray-900 hover:bg-gray-100';
                $sectionLabel = 'px-3 text-[9.5px] font-bold text-gray-400 uppercase tracking-[0.12em]';
                $summaryClass =
                    'flex items-center w-full px-3 py-2.5 rounded-xl hover:bg-gray-50/80 hover:text-gray-800 cursor-pointer text-[13px] text-gray-700 list-none transition-all duration-150';
                // UPDATED: border-left sub-menu dari primary.100
                $subBorder = 'ml-4 mt-1 space-y-0.5 pl-3.5 border-l-2 border-[#dcfce7]';
            @endphp

            <!-- ============================================ -->
            <!-- SUPERADMIN MENU -->
            <!-- ============================================ -->
            @if ($isSuperadmin)
                <!-- DASHBOARD -->
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Dashboard</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'dashboard')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'dashboard') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DATA MASTER (Dropdown) -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Master</p>
                </li>
                <li>
                    <details class="group" {{ $isSuperadminMasterOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="flex-1 ml-2.5">Data Master</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform duration-200 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('jenis-zakat.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'jenis-zakat') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span>Jenis Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tipe-zakat.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'tipe-zakat') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    <span>Tipe Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kategori-mustahik.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'kategori-mustahik') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Kategori Mustahik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('harga-emas-perak.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'harga-emas-perak') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Harga Emas & Perak</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- DATA PENGGUNA -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Pengguna</p>
                </li>
                <li>
                    <a href="{{ route('pengguna.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'pengguna') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'pengguna')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'pengguna') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Pengguna</span>
                    </a>
                </li>

                <!-- DATA ARTIKEL (Dropdown) -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Artikel</p>
                </li>
                <li>
                    <details class="group"
                        {{ str_contains($currentRoute, 'bulletin') || str_contains($currentRoute, 'kategori-bulletin') ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <span class="flex-1 ml-2.5">Bulletin</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform duration-200 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('superadmin.kategori-bulletin.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'kategori-bulletin') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span>Kategori Bulletin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.bulletin.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'superadmin.bulletin') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Kelola Bulletin</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- DATA LEMBAGA -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Lembaga</p>
                </li>
                <li>
                    <a href="{{ route('lembaga.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'lembaga') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'lembaga')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'lembaga') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kelola Lembaga</span>
                    </a>
                </li>

                <!-- DATA AMIL -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Amil</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.amil.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'amil') && !str_contains($currentRoute, 'profil') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'amil') && !str_contains($currentRoute, 'profil')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'amil') && !str_contains($currentRoute, 'profil') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Kelola Amil</span>
                    </a>
                </li>

                <!-- DATA MUSTAHIK -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Mustahik</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.mustahik.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'mustahik') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'mustahik')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'mustahik') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Kelola Mustahik</span>
                    </a>
                </li>

                <!-- DATA MUZAKI -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Muzaki</p>
                </li>
                <li>
                    <a href="{{ route('muzaki.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'muzaki') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'muzaki')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'muzaki') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Kelola Muzaki</span>
                    </a>
                </li>

                <!-- DATA TRANSAKSI (Dropdown) -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Transaksi</p>
                </li>
                <li>
                    <details class="group" {{ $isSuperadminTransaksiOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="flex-1 ml-2.5">Transaksi</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform duration-200 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('superadmin.transaksi-penerimaan.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'transaksi-penerimaan') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Transaksi Penerimaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.transaksi-penyaluran.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'transaksi-penyaluran') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Transaksi Penyaluran</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Testimoni</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.testimoni.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px]
            {{ str_contains($currentRoute, 'superadmin-testimoni') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'superadmin-testimoni')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0
            {{ str_contains($currentRoute, 'superadmin-testimoni') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>Kelola Testimoni</span>
                        {{-- Badge pending count --}}
                        @php $pendingCount = \App\Models\Testimoni::where('is_approved', false)->count(); @endphp
                        @if ($pendingCount > 0)
                            <span
                                class="ml-auto inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-amber-400 text-white rounded-full">
                                {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <!-- LAPORAN KEUANGAN -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Laporan Keuangan</p>
                </li>
                <li>
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'laporan-konsolidasi') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'laporan-konsolidasi')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'laporan-konsolidasi') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Keuangan Seluruh Lembaga</span>
                    </a>
                </li>

                <!-- RIWAYAT -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Riwayat</p>
                </li>
                <li>
                    <a href="{{ route('log-aktivitas.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'log-aktivitas') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'log-aktivitas')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'log-aktivitas') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                </li>

                <!-- PENGATURAN -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Pengaturan</p>
                </li>
                <li>
                    <a href="{{ route('konfigurasi-global.show') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'konfigurasi-global') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'konfigurasi-global')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'konfigurasi-global') ? 'text-[#17a34a]' : 'text-gray-500' }}"
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
            <!-- ADMIN LEMBAGA MENU -->
            <!-- ============================================ -->
            @if ($isAdminLembaga)
                {{-- ===== MENU UTAMA ===== --}}
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Menu Utama</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'dashboard')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'dashboard') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- ===== DATA UTAMA ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Utama</p>
                </li>
                <li>
                    <details class="group" {{ $isAdminMasterOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="flex-1 ml-2.5">Data Master</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform duration-200 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('program-zakat.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'program-zakat') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>Program Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rekening-lembaga.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'rekening-lembaga') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Rekening Lembaga</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                {{-- ===== KELOLA AMIL ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Kelola Amil</p>
                </li>
                <li>
                    <a href="{{ route('amil.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'amil') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'amil')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'amil') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Data Amil</span>
                    </a>
                </li>

                {{-- ===== KELOLA MUSTAHIK ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Kelola Mustahik</p>
                </li>
                <li>
                    <a href="{{ route('mustahik.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'mustahik') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'mustahik')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'mustahik') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                {{-- ===== KELOLA MUZAKI ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Kelola Muzaki</p>
                </li>
                <li>
                    <a href="{{ route('admin-lembaga.muzaki.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'admin-lembaga.muzaki') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'admin-lembaga.muzaki')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'admin-lembaga.muzaki') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Data Muzaki</span>
                    </a>
                </li>

                {{-- ===== TERIMA PENYALURAN ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Terima Penyaluran</p>
                </li>
                <li>
                    <a href="{{ route('transaksi-penyaluran.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'transaksi-penyaluran') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'transaksi-penyaluran')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'transaksi-penyaluran') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="flex-1">Penyaluran</span>
                        @if (!empty($pendingApprovalCount) && $pendingApprovalCount > 0)
                            <span
                                class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold rounded-full bg-red-500 text-white leading-none">
                                {{ $pendingApprovalCount > 99 ? '99+' : $pendingApprovalCount }}
                            </span>
                        @endif
                    </a>
                </li>

                {{-- ===== TERIMA SETOR KAS ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Terima Setor Kas</p>
                </li>
                <li>
                    <a href="{{ route('admin-lembaga.setor-kas.pending') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'setor-kas') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'setor-kas')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'setor-kas') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                        <span>Setor Kas Amil</span>
                    </a>
                </li>

                {{-- ===== LAPORAN KEUANGAN ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Laporan Keuangan</p>
                </li>
                <li>
                    <a href="{{ route('laporan-keuangan.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'laporan-keuangan') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'laporan-keuangan')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'laporan-keuangan') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Keuangan</span>
                    </a>
                </li>

                {{-- ===== PENGATURAN ===== --}}
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Pengaturan</p>
                </li>
                <li>
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'konfigurasi-integrasi') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'konfigurasi-integrasi')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'konfigurasi-integrasi') ? 'text-[#17a34a]' : 'text-gray-500' }}"
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
            <!-- AMIL MENU -->
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
                    $isTransaksiOpen = collect($transaksiRoutes)->contains(fn($r) => str_contains($currentRoute, $r));
                @endphp

                <!-- MENU UTAMA -->
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Menu Utama</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'dashboard')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'dashboard') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- DATA PENERIMA -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Penerima</p>
                </li>
                <li>
                    <a href="{{ route('mustahik.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'mustahik') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'mustahik')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'mustahik') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                <!-- KELOLA TRANSAKSI (Dropdown Utama) -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Kelola Transaksi</p>
                </li>
                <li>
                    <details class="group" {{ $isTransaksiOpen ? 'open' : '' }}>
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="flex-1 ml-2.5">Kelola Transaksi</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform duration-200 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }} space-y-0.5">
                            <li class="mt-2 mb-0.5 px-2">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">KESELURUHAN
                                </p>
                            </li>
                            <li>
                                <a href="{{ route('pemantauan-transaksi.index') }}"
                                    class="flex items-center space-x-2 px-2 py-1 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'pemantauan-transaksi') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>Pemantauan Transaksi</span>
                                </a>
                            </li>
                            <li class="mt-3 mb-0.5 px-2">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">METODE
                                    PENERIMAAN</p>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-datang-langsung.index') }}"
                                    class="flex items-center space-x-2 px-2 py-1 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'transaksi-datang-langsung') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="flex-1">Datang Langsung</span>
                                    @if (($sidebarCounts['datang_langsung'] ?? 0) > 0)
                                        <span
                                            class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full">
                                            {{ $sidebarCounts['datang_langsung'] > 99 ? '99+' : $sidebarCounts['datang_langsung'] }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-daring.index') }}"
                                    class="flex items-center space-x-2 px-2 py-1 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'transaksi-daring') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <span class="flex-1">Daring</span>
                                    @if (($sidebarCounts['daring'] ?? 0) > 0)
                                        <span
                                            class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full">
                                            {{ $sidebarCounts['daring'] > 99 ? '99+' : $sidebarCounts['daring'] }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-dijemput.index') }}"
                                    class="flex items-center space-x-2 px-2 py-1 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'transaksi-dijemput') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="flex-1">Dijemput</span>
                                    @if (($sidebarCounts['dijemput'] ?? 0) > 0)
                                        <span
                                            class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full">
                                            {{ $sidebarCounts['dijemput'] > 99 ? '99+' : $sidebarCounts['dijemput'] }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li class="mt-3 mb-0.5 px-2">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">TRANSAKSI
                                </p>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-penyaluran.index') }}"
                                    class="flex items-center space-x-2 px-2 py-1 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'transaksi-penyaluran') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Transaksi Penyaluran</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- KAS ANDA (Dropdown) -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Kas Anda</p>
                </li>
                <li>
                    <details class="group">
                        <summary class="{{ $summaryClass }} text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="flex-1 ml-2.5">Kas</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform duration-200 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }} space-y-0.5">
                            <li>
                                <a href="{{ route('kas-harian.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'kas-harian') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>Kas Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('amil.setor-kas.index') }}"
                                    class="flex items-center space-x-2 px-3 py-1.5 text-xs rounded-lg transition-all duration-150 {{ str_contains($currentRoute, 'setor-kas') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
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
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Kunjungan</p>
                </li>
                <li>
                    <a href="{{ route('amil.kunjungan.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'kunjungan') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'kunjungan')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'kunjungan') ? 'text-[#17a34a]' : 'text-gray-500' }}"
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
            <!-- MUZAKKI MENU -->
            <!-- ============================================ -->
            @if (auth()->user() && auth()->user()->peran === 'muzakki')
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Menu Utama</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'dashboard')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'dashboard') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Zakat Saya</p>
                </li>
                <li>
                    <a href="{{ route('transaksi-daring-muzakki.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'transaksi-penerimaan') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'transaksi-penerimaan')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'transaksi-penerimaan') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Bayar Zakat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px] {{ str_contains($currentRoute, 'riwayat-transaksi-muzakki') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'riwayat-transaksi-muzakki')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0 {{ str_contains($currentRoute, 'riwayat-transaksi-muzakki') ? 'text-[#17a34a]' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Riwayat Zakat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('muzakki.testimoni.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 text-[13px]
            {{ str_contains($currentRoute, 'testimoni-saya') ? $activeClass : $inactiveClass }}"
                        @if (str_contains($currentRoute, 'testimoni-saya')) style="background: #f0fdf4;" @endif>
                        <svg class="w-4 h-4 flex-shrink-0
            {{ str_contains($currentRoute, 'testimoni-saya') ? 'text-[#17a34a]' : 'text-gray-500' }}"
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
    <div class="px-5 py-4" style="border-top: 1px solid #f3f4f6;">
        <div class="flex items-center justify-between">
            <p class="text-[11px] text-gray-400 font-medium">© {{ date('Y') }}
                {{ $appConfig->nama_aplikasi ?? 'Niat Zakat' }}</p>
            <span class="text-[10px] text-gray-300 font-semibold px-2 py-0.5 rounded-full"
                style="background:#f9fafb;">v{{ $appConfig->versi ?? '1.0.0' }}</span>
        </div>
    </div>
</aside>

<!-- Mobile Toggle Button -->
<button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-xl bg-white text-gray-700 shadow-lg"
    style="border: 1px solid #e5e7eb;">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black/30 z-40 hidden backdrop-blur-sm"></div>

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
