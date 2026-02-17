<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-primary text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-nz-xl flex flex-col">
    <!-- Logo & Brand -->
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-nz-radial flex items-center justify-center">
                <span class="text-xl font-bold">NZ</span>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Niat Zakata</h1>
                <p class="text-xs text-white/70 mt-0.5">Dari Niat Timbul Manfaat</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-1">
            @php
                $currentRoute = request()->route()->getName() ?? '';
                $isSuperadmin = auth()->user() && auth()->user()->peran === 'superadmin';
                $isAdminMasjid = auth()->user() && auth()->user()->peran === 'admin_masjid';
                $isAmil = auth()->user() && auth()->user()->peran === 'amil';
            @endphp

            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'dashboard') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- SUPERADMIN MENU -->
            @if($isSuperadmin)
                <li class="mt-4">
                    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Superadmin</p>
                </li>
                
                <!-- Konfigurasi -->
                <li>
                    <a href="{{ route('konfigurasi-global.show') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'konfigurasi-global') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Konfigurasi Aplikasi</span>
                    </a>
                </li>
                
                <!-- Data Master Superadmin -->
                <li>
                    <details class="group">
                        <summary class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Data Master</span>
                            <svg class="w-4 h-4 ml-auto transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="ml-8 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('jenis-zakat.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'jenis-zakat') ? 'bg-white/10' : '' }}">
                                    <span>Jenis Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tipe-zakat.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'harga-emas-perak') ? 'bg-white/10' : '' }}">
                                    <span>Tipe Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kategori-mustahik.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'kategori-mustahik') ? 'bg-white/10' : '' }}">
                                    <span>Kategori Mustahik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('harga-emas-perak.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'harga-emas-perak') ? 'bg-white/10' : '' }}">
                                    <span>Harga Emas & Perak</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
                
                <!-- Manajemen -->
                <li>
                    <a href="{{ route('masjid.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'masjid') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Kelola Masjid</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('pengguna.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'pengguna') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8.304a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Kelola Pengguna</span>
                    </a>
                </li> --}}
                
                <!-- Monitoring -->
                <li>
                    <a href="{{ route('log-aktivitas.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'log-aktivitas') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'laporan-konsolidasi') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Laporan Konsolidasi</span>
                    </a>
                </li>
            @endif

            <!-- ADMIN MASJID MENU -->
            @if($isAdminMasjid)
                <li class="mt-4">
                    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Admin Masjid</p>
                </li>
                
                <!-- Data Master Admin -->
                <li>
                    <details class="group">
                        <summary class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Data Master</span>
                            <svg class="w-4 h-4 ml-auto transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="ml-8 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('program-zakat.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'program-zakat') ? 'bg-white/10' : '' }}">
                                    <span>Program Zakat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rekening-masjid.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'rekening-masjid') ? 'bg-white/10' : '' }}">
                                    <span>Rekening Masjid</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
                
                <!-- Konfigurasi -->
                {{-- <li>
                    <a href="{{ route('konfigurasi.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'konfigurasi') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Konfigurasi Masjid</span>
                    </a>
                </li>
                 --}}
                <!-- Manajemen Tim -->
                <li>
                    <a href="{{ route('amil.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'amil') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kelola Amil</span>
                    </a>
                </li>
            @endif

            <!-- AMIL MENU (Tampil untuk Amil dan Admin Masjid) -->
            @if($isAmil || $isAdminMasjid)

            <li class="mt-4">
                    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Konfigurasi</p>
            </li>

                  <li>
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'mustahik') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Konfigurasi Masjid</span>
                    </a>
                </li>


                <li class="mt-4">
                    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Data Penerima</p>
                </li>
                
                <!-- Mustahik -->
                <li>
                    <a href="{{ route('mustahik.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'mustahik') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Data Mustahik</span>
                    </a>
                </li>

                <li class="mt-4">
                    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Transaksi Zakat</p>
                </li>
                
                <!-- Input Transaksi -->
                <li>
                    <details class="group">
                        <summary class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Input Transaksi</span>
                            <svg class="w-4 h-4 ml-auto transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="ml-8 mt-1 space-y-1">
                            {{-- <li>
                                <a href="{{ route('zakat-fitrah.create') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10">
                                    <span>Zakat Fitrah</span>
                                </a>
                            </li> --}}
                            {{-- <li>
                                <a href="{{ route('zakat-mal.create') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10">
                                    <span>Zakat Mal</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('infaq.create') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10">
                                    <span>Infaq/Sedekah</span>
                                </a>
                            </li> --}}
                        </ul>
                    </details>
                </li>
                
                <!-- Data Transaksi -->
                {{-- <li>
                    <a href="{{ route('transaksi.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'transaksi') && !str_contains($currentRoute, 'create') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Data Transaksi</span>
                    </a>
                </li>
                 --}}
                <!-- Data Muzaki -->
                {{-- <li>
                    <a href="{{ route('muzaki.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'muzaki') ? 'bg-white/10' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Data Muzaki</span>
                    </a>
                </li> --}}

                <!-- Laporan -->
                <li class="mt-4">
                    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Laporan</p>
                </li>
                
                <li>
                    {{-- <details class="group">
                        <summary class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Laporan</span>
                            <svg class="w-4 h-4 ml-auto transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <ul class="ml-8 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('laporan.harian') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'laporan.harian') ? 'bg-white/10' : '' }}">
                                    <span>Laporan Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.bulanan') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'laporan.bulanan') ? 'bg-white/10' : '' }}">
                                    <span>Laporan Bulanan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.tahunan') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'laporan.tahunan') ? 'bg-white/10' : '' }}">
                                    <span>Laporan Tahunan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('laporan.keuangan.index') }}"
                                   class="flex items-center space-x-2 p-2 text-sm rounded hover:bg-white/10 {{ str_contains($currentRoute, 'laporan.keuangan') ? 'bg-white/10' : '' }}">
                                    <span>Laporan Keuangan</span>
                                </a>
                            </li>
                        </ul>
                    </details> --}}
                </li>
            @endif

            <!-- Pengaturan Umum -->
            <li class="mt-4">
                <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Pengaturan</p>
            </li>
            {{-- <li>
                <a href="{{ route('profile.show') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors {{ str_contains($currentRoute, 'profile') ? 'bg-white/10' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profil Saya</span>
                </a>
            </li> --}}

            <li>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-white/10 transition-colors text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-white/10">
        <div class="text-center">
            <p class="text-xs text-white/50">© {{ date('Y') }} Niat Zakata</p>
            <p class="text-xs text-white/50 mt-1">v1.0.0</p>
        </div>
    </div>
</aside>

<!-- Mobile Toggle Button -->
<button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-gradient-primary text-white shadow-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"></div>

@push('scripts')
<script>
    // Toggle sidebar on mobile
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

    // Close sidebar when clicking outside on mobile
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