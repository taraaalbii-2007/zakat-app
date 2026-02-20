<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#2d6a2d] text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-xl flex flex-col">
    <!-- Logo & Brand -->
    <div class="px-4 py-5 border-b border-white/10">
        <div class="flex items-center space-x-3">
            {{-- Jika ada logo, tampilkan gambar. Jika tidak, tampilkan inisial --}}
            @if(!empty($appConfig->logo_aplikasi))
                <img src="{{ asset('storage/' . $appConfig->logo_aplikasi) }}"
                     alt="{{ $appConfig->nama_aplikasi }}"
                     class="w-11 h-11 rounded-full object-cover flex-shrink-0 ring-2 ring-white/20">
            @else
                <div class="w-11 h-11 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0">
                    <span class="text-base font-bold text-white">
                        {{ strtoupper(substr($appConfig->nama_aplikasi ?? 'App', 0, 2)) }}
                    </span>
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-base font-bold tracking-tight text-white truncate">
                    {{ $appConfig->nama_aplikasi ?? 'Aplikasi Zakat' }}
                </h1>
                <p class="text-xs text-white/60 mt-0.5 truncate">
                    {{ $appConfig->tagline ?? '' }}
                </p>
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

                // Superadmin dropdown states
                $dataMasterSuperadminRoutes = ['jenis-zakat', 'tipe-zakat', 'kategori-mustahik', 'harga-emas-perak'];
                $isSuperadminMasterOpen = collect($dataMasterSuperadminRoutes)->contains(fn($r) => str_contains($currentRoute, $r));

                $dataTransaksiRoutes = ['transaksi-penerimaan', 'transaksi-penyaluran'];
                $isSuperadminTransaksiOpen = collect($dataTransaksiRoutes)->contains(fn($r) => str_contains($currentRoute, $r));

                // Admin Masjid dropdown states
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

            <!-- ============================================ -->
            <!-- SUPERADMIN MENU -->
            <!-- ============================================ -->
            @if($isSuperadmin)

                <!-- DASHBOARD -->
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Dashboard</p>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
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

                <!-- DATA PENGGUNA -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Pengguna</p>
                </li>
                <li>
                    <a href="{{ route('pengguna.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'pengguna') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Pengguna</span>
                    </a>
                </li>

                <!-- DATA MASJID -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Masjid</p>
                </li>
                <li>
                    <a href="{{ route('masjid.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'masjid') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Kelola Masjid</span>
                    </a>
                </li>

                <!-- DATA AMIL -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Data Amil</p>
                </li>
                <li>
                    <a href="{{ route('superadmin.amil.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'amil') && !str_contains($currentRoute, 'profil') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
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
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'mustahik') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
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
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'muzaki') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
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
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span class="flex-1 ml-2.5">Transaksi</span>
                            <svg class="w-3.5 h-3.5 flex-shrink-0 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="{{ $subBorder }}">
                            <li>
                                <a href="{{ route('superadmin.transaksi-penerimaan.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'transaksi-penerimaan') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <span>Transaksi Penerimaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.transaksi-penyaluran.index') }}"
                                   class="flex items-center space-x-2 px-2 py-1.5 text-xs rounded transition-colors {{ str_contains($currentRoute, 'transaksi-penyaluran') ? $subActive : $subInactive }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <span>Transaksi Penyaluran</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- LAPORAN KEUANGAN -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Laporan Keuangan</p>
                </li>
                <li>
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'laporan-konsolidasi') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Keuangan Seluruh Masjid</span>
                    </a>
                </li>

                <!-- RIWAYAT -->
                <li class="mt-4 mb-1">
                    <p class="{{ $sectionLabel }}">Riwayat</p>
                </li>
                <li>
                    <a href="{{ route('log-aktivitas.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'log-aktivitas') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'konfigurasi-global') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Konfigurasi Aplikasi</span>
                    </a>
                </li>

            @endif

            <!-- ============================================ -->
            <!-- ADMIN MASJID MENU -->
            <!-- ============================================ -->
            @if($isAdminMasjid)
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Menu Utama</p>
                </li>

                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Admin Masjid</p>
                </li>

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

                <li>
                    <a href="{{ route('amil.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'amil') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kelola Amil</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Laporan</p>
                </li>
                <li>
                    <a href="{{ route('laporan-keuangan.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'laporan-keuangan') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Pengaturan</p>
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
            @endif

            <!-- ============================================ -->
            <!-- AMIL MENU -->
            <!-- ============================================ -->
            @if($isAmil)
                <li class="mb-1">
                    <p class="{{ $sectionLabel }}">Menu Utama</p>
                </li>

                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Data Penerima</p>
                </li>

                <li>
                    <a href="{{ route('mustahik.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'mustahik') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Transaksi Zakat</p>
                </li>

                <li>
                    <a href="{{ route('transaksi-penerimaan.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'transaksi-penerimaan') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Transaksi Penerimaan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi-penyaluran.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'transaksi-penyaluran') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Transaksi Penyaluran</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Kas</p>
                </li>

                <li>
                    <a href="{{ route('kas-harian.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'kas-harian') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kas Harian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('amil.setor-kas.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'setor-kas') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        <span>Setor Kas</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Kunjungan</p>
                </li>
                <li>
                    <a href="{{ route('amil.kunjungan.index') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'kunjungan') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Kunjungan Mustahik</span>
                    </a>
                </li>

                <li class="mt-3 mb-1">
                    <p class="{{ $sectionLabel }}">Pengaturan</p>
                </li>
                <li>
                    <a href="{{ route('profil.show') }}"
                       class="flex items-center space-x-2.5 px-2 py-2 rounded-md transition-colors text-[13px] {{ str_contains($currentRoute, 'profil') ? $activeClass : $inactiveClass }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                </li>
            @endif

        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-4 py-3 border-t border-white/10">
        <div class="text-center">
            <p class="text-xs text-white/40">© {{ date('Y') }} {{ $appConfig->nama_aplikasi ?? 'Niat Zakat' }}</p>
            <p class="text-xs text-white/40 mt-0.5">v{{ $appConfig->versi ?? '1.0.0' }}</p>
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