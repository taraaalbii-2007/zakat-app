@extends('layouts.app')

@section('title', 'Kelola Data Lembaga')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

             <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Kelola Lembaga</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi data lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'provinsi_kode', 'status']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah - DIPERBAIKI -->
                        <a href="{{ route('lembaga.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar - DIPERBAIKI -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $lembagas->total() }}</span>
                        <span class="text-sm text-gray-500">Lembaga</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'provinsi_kode', 'status']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('lembaga.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Lembaga</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari lembaga..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Provinsi Filter -->
                            @if(isset($provinces) && $provinces->count() > 0)
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Provinsi</label>
                                <select name="provinsi_kode"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Provinsi</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->code }}" {{ request('provinsi_kode') == $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>

                            <div></div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'provinsi_kode', 'status']))
                            <a href="{{ route('lembaga.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Filter Tags - DIPERBAIKI -->
            @if(request()->hasAny(['q', 'provinsi_kode', 'status']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        
                        @if(request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        
                        @if(request('provinsi_kode') && isset($provinces))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Provinsi: {{ $provinces->firstWhere('code', request('provinsi_kode'))?->name ?? request('provinsi_kode') }}
                                <button onclick="removeFilter('provinsi_kode')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        
                        @if(request('status'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ request('status') == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tabel -->
            @if($lembagas->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NAMA LEMBAGA</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KODE LEMBAGA</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($lembagas as $lembaga)
                                <!-- Baris Utama -->
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300 cursor-pointer expandable-row"
                                    data-target="detail-{{ $lembaga->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                {{ $lembaga->nama }}
                                            </span>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk detail lengkap</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $lembaga->kode_lembaga }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($lembaga->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Ikon Lihat Detail -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('lembaga.show', $lembaga->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Lihat Detail
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            <!-- Ikon Edit -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('lembaga.edit', $lembaga->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Edit
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            <!-- Ikon Toggle Status -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="toggle-status-btn flex items-center justify-center p-1.5 text-gray-400 {{ $lembaga->is_active ? 'hover:text-amber-600 hover:bg-amber-50' : 'hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $lembaga->uuid }}" 
                                                    data-nama="{{ addslashes($lembaga->nama) }}"
                                                    data-is-active="{{ $lembaga->is_active ? '1' : '0' }}">
                                                    @if($lembaga->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    {{ $lembaga->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            <!-- Ikon Hapus -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $lembaga->uuid }}" data-nama="{{ addslashes($lembaga->nama) }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Hapus
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Baris Expandable Desktop - Detail Lengkap Rapi -->
                                <tr id="detail-{{ $lembaga->uuid }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td colspan="5" class="px-0 py-0">
                                        <div class="bg-gray-50/50 px-6 py-5">
                                            <!-- Grid 2 Kolom -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                
                                                <!-- Kolom Kiri: Data Admin Lembaga -->
                                                <div>
                                                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-200">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                        </svg>
                                                        <h4 class="text-sm font-semibold text-gray-800">Data Admin Lembaga</h4>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                                            <span class="text-xs text-gray-500 sm:w-1/3">Nama Admin</span>
                                                            <span class="text-sm font-medium text-gray-800 sm:w-2/3">{{ $lembaga->admin_nama ?? '-' }}</span>
                                                        </div>
                                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                                            <span class="text-xs text-gray-500 sm:w-1/3">Telepon Admin</span>
                                                            <span class="text-sm font-medium text-gray-800 sm:w-2/3">{{ $lembaga->admin_telepon ?? '-' }}</span>
                                                        </div>
                                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                                            <span class="text-xs text-gray-500 sm:w-1/3">Email Admin</span>
                                                            <span class="text-sm font-medium text-gray-800 sm:w-2/3 break-all">{{ $lembaga->admin_email ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Kolom Kanan: Lokasi -->
                                                <div>
                                                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-200">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <h4 class="text-sm font-semibold text-gray-800">Lokasi Lembaga</h4>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                                            <span class="text-xs text-gray-500 sm:w-1/3">Alamat Lengkap</span>
                                                            <span class="text-sm font-medium text-gray-800 sm:w-2/3">{{ $lembaga->alamat_lengkap ?? '-' }}</span>
                                                        </div>
                                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                                            <span class="text-xs text-gray-500 sm:w-1/3">Kode Lembaga</span>
                                                            <span class="text-sm font-medium text-gray-800 sm:w-2/3">{{ $lembaga->kode_lembaga ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Deskripsi (full width) -->
                                            @if($lembaga->deskripsi)
                                                <div class="mt-5 pt-4 border-t border-gray-200">
                                                    <div class="flex items-center gap-2 mb-3">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        <h4 class="text-sm font-semibold text-gray-800">Deskripsi Lembaga</h4>
                                                    </div>
                                                    <div class="bg-white rounded-lg p-4 border border-gray-100">
                                                        <p class="text-sm text-gray-600 leading-relaxed">{{ $lembaga->deskripsi }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach($lembagas as $lembaga)
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $lembaga->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Lembaga</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words">
                                            {{ $lembaga->nama }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            @if($lembaga->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Nonaktif</span>
                                            @endif
                                            <span class="text-xs text-gray-500">{{ $lembaga->kode_lembaga }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Lihat Detail -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('lembaga.show', $lembaga->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Lihat
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>

                                        <!-- Edit -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('lembaga.edit', $lembaga->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Edit
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>

                                        <!-- Toggle Status -->
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="toggle-status-btn flex items-center justify-center p-1.5 text-gray-400 {{ $lembaga->is_active ? 'hover:text-amber-600 hover:bg-amber-50' : 'hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-all"
                                                data-uuid="{{ $lembaga->uuid }}" 
                                                data-nama="{{ addslashes($lembaga->nama) }}"
                                                data-is-active="{{ $lembaga->is_active ? '1' : '0' }}">
                                                @if($lembaga->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                {{ $lembaga->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>

                                        <!-- Hapus -->
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $lembaga->uuid }}" data-nama="{{ addslashes($lembaga->nama) }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Hapus
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $lembaga->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <!-- Data Admin -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Data Admin Lembaga</h4>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Nama Admin</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $lembaga->admin_nama ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Telepon Admin</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $lembaga->admin_telepon ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Email Admin</span>
                                                <span class="text-xs font-medium text-gray-700 break-all">{{ $lembaga->admin_email ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lokasi -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Lokasi Lembaga</h4>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Alamat</span>
                                                <span class="text-xs font-medium text-gray-700 text-right">{{ $lembaga->alamat_lengkap ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Kode Lembaga</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $lembaga->kode_lembaga ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    @if($lembaga->deskripsi)
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Deskripsi</h4>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <p class="text-xs text-gray-600 leading-relaxed">{{ Str::limit($lembaga->deskripsi, 200) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($lembagas->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $lembagas->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>

                    @if(request('q') || request('provinsi_kode') || request('status'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada lembaga yang sesuai dengan filter yang dipilih</p>
                        <button onclick="resetAllFilters()"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset filter
                        </button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data lembaga</p>
                        <a href="{{ route('lembaga.create') }}"
                            class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah lembaga sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Toggle Status Modal -->
    <div id="toggle-status-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div id="toggle-status-icon-nonaktif" class="w-14 h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl flex items-center justify-center shadow-inner hidden">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div id="toggle-status-icon-aktif" class="w-14 h-14 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 id="toggle-status-title" class="text-lg font-semibold text-gray-900 mb-2 text-center">Nonaktifkan Lembaga</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin <span id="toggle-status-action" class="font-semibold text-gray-700">menonaktifkan</span>
                    lembaga "<span id="modal-toggle-lembaga-name" class="font-semibold text-gray-700"></span>"?
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-toggle-status-btn"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="button" id="confirm-toggle-status-btn"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Lembaga</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus lembaga "<span id="modal-lembaga-name" class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="button" id="confirm-delete-btn"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentToggleData = null;

    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filterButton');
        const filterPanel = document.getElementById('filterPanel');
        const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

        if (filterButton && filterPanel) {
            filterButton.addEventListener('click', function() {
                filterPanel.classList.toggle('hidden');
            });
        }

        if (closeFilterPanelBtn && filterPanel) {
            closeFilterPanelBtn.addEventListener('click', function() {
                filterPanel.classList.add('hidden');
            });
        }

        // Desktop Expandable row
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn') || e.target.closest('a') || e.target.closest('.toggle-status-btn')) return;
                const targetId = this.getAttribute('data-target');
                const targetRow = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon');
                if (targetRow) {
                    targetRow.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-90');
                }
            });
        });

        // Mobile Expandable Cards
        document.querySelectorAll('.expandable-row-mobile').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn') || e.target.closest('a') || e.target.closest('.toggle-status-btn')) return;
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                }
            });
        });

        // Toggle Status button handler
        const toggleStatusModal = document.getElementById('toggle-status-modal');
        const toggleStatusTitle = document.getElementById('toggle-status-title');
        const toggleStatusAction = document.getElementById('toggle-status-action');
        const confirmToggleBtn = document.getElementById('confirm-toggle-status-btn');
        const cancelToggleBtn = document.getElementById('cancel-toggle-status-btn');
        const iconNonaktif = document.getElementById('toggle-status-icon-nonaktif');
        const iconAktif = document.getElementById('toggle-status-icon-aktif');

        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const nama = this.getAttribute('data-nama');
                const isActive = this.getAttribute('data-is-active') === '1';

                currentToggleData = { uuid, nama, isActive };

                document.getElementById('modal-toggle-lembaga-name').textContent = nama;

                if (isActive) {
                    toggleStatusTitle.textContent = 'Nonaktifkan Lembaga';
                    toggleStatusAction.textContent = 'menonaktifkan';
                    confirmToggleBtn.className = 'flex-1 px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg';
                    iconNonaktif.classList.remove('hidden');
                    iconAktif.classList.add('hidden');
                } else {
                    toggleStatusTitle.textContent = 'Aktifkan Lembaga';
                    toggleStatusAction.textContent = 'mengaktifkan';
                    confirmToggleBtn.className = 'flex-1 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg';
                    iconNonaktif.classList.add('hidden');
                    iconAktif.classList.remove('hidden');
                }

                toggleStatusModal.classList.remove('hidden');
            });
        });

        confirmToggleBtn.addEventListener('click', function() {
            if (!currentToggleData) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/lembaga/${currentToggleData.uuid}/toggle-status`;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PATCH';
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });

        cancelToggleBtn.addEventListener('click', function() {
            toggleStatusModal.classList.add('hidden');
            currentToggleData = null;
        });

        toggleStatusModal.addEventListener('click', function(e) {
            if (e.target === toggleStatusModal) {
                toggleStatusModal.classList.add('hidden');
                currentToggleData = null;
            }
        });

        // Delete button handler
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        let currentDeleteData = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const nama = this.getAttribute('data-nama');
                currentDeleteData = { uuid, nama };
                document.getElementById('modal-lembaga-name').textContent = nama;
                deleteModal.classList.remove('hidden');
            });
        });

        confirmDeleteBtn.addEventListener('click', function() {
            if (!currentDeleteData) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/lembaga/${currentDeleteData.uuid}`;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });

        cancelDeleteBtn.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            currentDeleteData = null;
        });

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
                currentDeleteData = null;
            }
        });
    });

    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    function resetAllFilters() {
        const url = new URL(window.location.href);
        url.searchParams.delete('q');
        url.searchParams.delete('provinsi_kode');
        url.searchParams.delete('status');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
@endpush