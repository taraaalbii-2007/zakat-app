@extends('layouts.app')

@section('title', 'Kelola Tipe Zakat')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

           <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Tipe Zakat</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi tipe zakat</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['search', 'jenis_zakat_id', 'requires_haul', 'sort_by', 'sort_order']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah - DIPERBAIKI -->
                        <a href="{{ route('tipe-zakat.create') }}"
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
                        <span class="text-sm font-semibold text-gray-800">{{ $tipeZakat->total() }}</span>
                        <span class="text-sm text-gray-500">Data Tipe Zakat</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel" class="{{ request()->hasAny(['search', 'jenis_zakat_id', 'requires_haul', 'sort_by', 'sort_order']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('tipe-zakat.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Tipe Zakat</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari tipe zakat..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                                <select name="jenis_zakat_id"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisZakatList as $jenis)
                                        <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Haul</label>
                                <select name="requires_haul"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Haul</option>
                                    <option value="true" {{ request('requires_haul') == 'true' ? 'selected' : '' }}>Perlu Haul (1 tahun)</option>
                                    <option value="false" {{ request('requires_haul') == 'false' ? 'selected' : '' }}>Tanpa Haul</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Urut Berdasarkan</label>
                                <select name="sort_by"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="nama" {{ request('sort_by', 'nama') === 'nama' ? 'selected' : '' }}>Nama</option>
                                    <option value="persentase_zakat" {{ request('sort_by') === 'persentase_zakat' ? 'selected' : '' }}>Persentase</option>
                                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Urutan</label>
                                <select name="sort_order"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="asc" {{ request('sort_order', 'asc') === 'asc' ? 'selected' : '' }}>Menaik (A-Z)</option>
                                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['search', 'jenis_zakat_id', 'requires_haul', 'sort_by', 'sort_order']))
                            <a href="{{ route('tipe-zakat.index') }}"
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

            <!-- Active Filter Tags - DITAMBAHKAN -->
            @if (request()->hasAny(['search', 'jenis_zakat_id', 'requires_haul']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('search'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('search') }}"
                                <button onclick="removeFilter('search')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('jenis_zakat_id'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Jenis: {{ $jenisZakatList->find(request('jenis_zakat_id'))?->nama }}
                                <button onclick="removeFilter('jenis_zakat_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('requires_haul'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Haul: {{ request('requires_haul') == 'true' ? 'Perlu Haul' : 'Tanpa Haul' }}
                                <button onclick="removeFilter('requires_haul')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tabel -->
            @if ($tipeZakat->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NAMA TIPE ZAKAT</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipeZakat as $tipe)
                                <!-- Baris Utama -->
                                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $tipe->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                            {{ $tipe->nama }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('tipe-zakat.edit', $tipe->uuid) }}"
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

                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $tipe->uuid }}" data-nama="{{ addslashes($tipe->nama) }}">
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

                                <!-- Baris Expandable Desktop - Detail Lengkap -->
                                <tr id="detail-{{ $tipe->uuid }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Jenis Zakat & Haul -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Jenis Zakat</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $tipe->jenisZakat->nama ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Status Haul</span>
                                                        <span class="text-xs font-medium text-gray-700">
                                                            @if ($tipe->requires_haul)
                                                                <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 text-xs rounded-full">Ya (1 tahun)</span>
                                                            @else
                                                                Tidak
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Persentase -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Persentase</h4>
                                                <div class="space-y-2">
                                                    @if ($tipe->persentase_zakat)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Utama</span>
                                                            <span class="text-xs font-semibold text-gray-800">{{ $tipe->formatted_persentase }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($tipe->persentase_alternatif)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Alternatif</span>
                                                            <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->persentase_alternatif, 2) }}%</span>
                                                        </div>
                                                    @endif
                                                    @if ($tipe->keterangan_persentase)
                                                        <div class="pt-1">
                                                            <p class="text-xs text-gray-500 mb-0.5">Keterangan</p>
                                                            <p class="text-xs text-gray-600">{{ $tipe->keterangan_persentase }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Nisab -->
                                            @php
                                                $hasNisab = $tipe->nisab_emas_gram || $tipe->nisab_perak_gram || $tipe->nisab_pertanian_kg || 
                                                            $tipe->nisab_kambing_min || $tipe->nisab_sapi_min || $tipe->nisab_unta_min;
                                            @endphp
                                            @if ($hasNisab)
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nisab</h4>
                                                    <div class="space-y-1.5">
                                                        @if ($tipe->nisab_emas_gram)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Emas</span>
                                                                <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->nisab_emas_gram, 2) }} gram</span>
                                                            </div>
                                                        @endif
                                                        @if ($tipe->nisab_perak_gram)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Perak</span>
                                                                <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->nisab_perak_gram, 2) }} gram</span>
                                                            </div>
                                                        @endif
                                                        @if ($tipe->nisab_pertanian_kg)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Pertanian</span>
                                                                <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->nisab_pertanian_kg, 2) }} kg</span>
                                                            </div>
                                                        @endif
                                                        @if ($tipe->nisab_kambing_min)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Kambing</span>
                                                                <span class="text-xs font-medium text-gray-700">min {{ number_format($tipe->nisab_kambing_min) }} ekor</span>
                                                            </div>
                                                        @endif
                                                        @if ($tipe->nisab_sapi_min)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Sapi</span>
                                                                <span class="text-xs font-medium text-gray-700">min {{ number_format($tipe->nisab_sapi_min) }} ekor</span>
                                                            </div>
                                                        @endif
                                                        @if ($tipe->nisab_unta_min)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Unta</span>
                                                                <span class="text-xs font-medium text-gray-700">min {{ number_format($tipe->nisab_unta_min) }} ekor</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Ketentuan Khusus (full width) -->
                                        @if ($tipe->ketentuan_khusus)
                                            <div class="mt-4 pt-3 border-t border-gray-200">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ketentuan Khusus</h4>
                                                <div class="bg-white rounded-lg p-3 border border-gray-100">
                                                    <p class="text-xs text-gray-600">{{ $tipe->ketentuan_khusus }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($tipeZakat as $tipe)
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $tipe->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Tipe Zakat</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words">
                                            {{ $tipe->nama }}
                                        </h3>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('tipe-zakat.edit', $tipe->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Edit
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>

                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $tipe->uuid }}" data-nama="{{ addslashes($tipe->nama) }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                            <div id="detail-mobile-{{ $tipe->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Jenis Zakat</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $tipe->jenisZakat->nama ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Status Haul</span>
                                                <span class="text-xs font-medium text-gray-700">
                                                    @if ($tipe->requires_haul)
                                                        <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 text-xs rounded-full">Ya (1 tahun)</span>
                                                    @else
                                                        Tidak
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($tipe->persentase_zakat || $tipe->persentase_alternatif || $tipe->keterangan_persentase)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Persentase</h4>
                                            <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                                @if ($tipe->persentase_zakat)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Utama</span>
                                                        <span class="text-xs font-semibold text-gray-800">{{ $tipe->formatted_persentase }}</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->persentase_alternatif)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Alternatif</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->persentase_alternatif, 2) }}%</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->keterangan_persentase)
                                                    <div class="pt-1">
                                                        <p class="text-xs text-gray-500 mb-0.5">Keterangan</p>
                                                        <p class="text-xs text-gray-600">{{ $tipe->keterangan_persentase }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @php
                                        $hasNisab = $tipe->nisab_emas_gram || $tipe->nisab_perak_gram || $tipe->nisab_pertanian_kg || 
                                                    $tipe->nisab_kambing_min || $tipe->nisab_sapi_min || $tipe->nisab_unta_min;
                                    @endphp
                                    @if ($hasNisab)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nisab</h4>
                                            <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                                @if ($tipe->nisab_emas_gram)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Emas</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->nisab_emas_gram, 2) }} gram</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_perak_gram)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Perak</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->nisab_perak_gram, 2) }} gram</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_pertanian_kg)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Pertanian</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ number_format($tipe->nisab_pertanian_kg, 2) }} kg</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_kambing_min)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Kambing</span>
                                                        <span class="text-xs font-medium text-gray-700">min {{ number_format($tipe->nisab_kambing_min) }} ekor</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_sapi_min)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Sapi</span>
                                                        <span class="text-xs font-medium text-gray-700">min {{ number_format($tipe->nisab_sapi_min) }} ekor</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_unta_min)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Unta</span>
                                                        <span class="text-xs font-medium text-gray-700">min {{ number_format($tipe->nisab_unta_min) }} ekor</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if ($tipe->ketentuan_khusus)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ketentuan Khusus</h4>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <p class="text-xs text-gray-600">{{ Str::limit($tipe->ketentuan_khusus, 200) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($tipeZakat->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $tipeZakat->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>

                    @if (request('search') || request('jenis_zakat_id') || request('requires_haul'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <button onclick="resetAllFilters()" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">Reset filter</button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data tipe zakat</p>
                        <a href="{{ route('tipe-zakat.create') }}" class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah data sekarang
                        </a>
                    @endif
                </div>
            @endif
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
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Tipe Zakat</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus "<span id="modal-zakat-name" class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">Batal</button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

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
                if (e.target.closest('.delete-btn') || e.target.closest('a')) return;
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
                if (e.target.closest('.delete-btn') || e.target.closest('a')) return;
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                }
            });
        });

        // Delete button handler
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const nama = this.getAttribute('data-nama');
                document.getElementById('modal-zakat-name').textContent = nama;
                deleteForm.action = `/tipe-zakat/${uuid}`;
                deleteModal.classList.remove('hidden');
            });
        });

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        }

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    });

    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        if (filterName === 'sort_by') {
            url.searchParams.delete('sort_order');
        }
        window.location.href = url.toString();
    }

    function resetAllFilters() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('jenis_zakat_id');
        url.searchParams.delete('requires_haul');
        url.searchParams.delete('sort_by');
        url.searchParams.delete('sort_order');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
@endpush