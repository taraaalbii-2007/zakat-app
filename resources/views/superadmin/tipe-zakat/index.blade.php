@extends('layouts.app')

@section('title', 'Tipe Zakat')

@section('content')
    <div class="space-y-5">
        <!-- Container utama -->
        <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-soft transition-all duration-300">

            <!-- Header + Search + Button -->
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-neutral-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-neutral-800">Tipe Zakat</h1>
                        <p class="text-xs sm:text-sm text-neutral-500 mt-0.5 sm:mt-1">Kelola dan konfigurasi tipe zakat</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Search -->
                        <div class="relative w-full sm:w-auto">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" id="search-input" value="{{ request('search') }}"
                                placeholder="Cari tipe zakat..."
                                class="pl-9 pr-4 py-2 w-full sm:w-64 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                        </div>

                        <!-- Button Filter -->
                        <button type="button" id="filter-button"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('tipe-zakat.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-soft hover:shadow-md active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Tambah Baru</span>
                            <span class="sm:hidden">Tambah</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - Hidden by default -->
            <div id="filter-panel" class="hidden px-4 sm:px-6 py-4 border-b border-neutral-200 bg-neutral-50/30">
                <div class="flex flex-wrap items-end gap-4">
                    <div class="min-w-[160px] flex-1 sm:flex-none">
                        <label class="block text-xs font-medium text-neutral-600 mb-1.5">Jenis Zakat</label>
                        <select id="filter-jenis-zakat"
                            class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                            <option value="">Semua Jenis</option>
                            @foreach ($jenisZakatList as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[140px] flex-1 sm:flex-none">
                        <label class="block text-xs font-medium text-neutral-600 mb-1.5">Haul</label>
                        <select id="filter-haul"
                            class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                            <option value="">Semua Haul</option>
                            <option value="true" {{ request('requires_haul') == 'true' ? 'selected' : '' }}>Perlu Haul (1 tahun)</option>
                            <option value="false" {{ request('requires_haul') == 'false' ? 'selected' : '' }}>Tanpa Haul</option>
                        </select>
                    </div>
                    <div class="min-w-[150px] flex-1 sm:flex-none">
                        <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urut Berdasarkan</label>
                        <select id="filter-sort-by"
                            class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                            <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                            <option value="persentase_zakat" {{ request('sort_by') == 'persentase_zakat' ? 'selected' : '' }}>Persentase</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                        </select>
                    </div>
                    <div class="min-w-[130px] flex-1 sm:flex-none">
                        <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urutan</label>
                        <select id="filter-sort-order"
                            class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Menaik (A-Z)</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                        </select>
                    </div>
                    @if (request('jenis_zakat_id') || request('requires_haul') || request('sort_by') || request('sort_order'))
                        <div class="flex items-center">
                            <a href="{{ route('tipe-zakat.index', request('search') ? ['search' => request('search')] : []) }}"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Total -->
            <div class="px-4 sm:px-6 py-3 border-b border-neutral-100 bg-neutral-50/30">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-neutral-600">Total:</span>
                    <span class="text-sm font-semibold text-neutral-800">{{ $tipeZakat->total() }}</span>
                    <span class="text-sm text-neutral-500">tipe zakat</span>
                </div>
            </div>

            <!-- Tabel dengan Expandable Row -->
            @if ($tipeZakat->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-neutral-200 bg-neutral-50">
                                <th class="px-4 py-4 text-center text-sm font-semibold text-neutral-700 w-10"></th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">INFORMASI TIPE</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">NISAB</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">PERSENTASE</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">HAUL</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-neutral-700 w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipeZakat as $tipe)
                                <!-- Baris Utama -->
                                <tr class="border-b border-neutral-100 hover:bg-primary-50/20 transition-all duration-200 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $tipe->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-neutral-400 transform transition-transform duration-200 expand-icon inline-block" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-neutral-800">{{ $tipe->nama }}</div>
                                        <div class="text-xs text-neutral-400 mt-0.5">{{ $tipe->jenisZakat->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $nisabTypes = $tipe->active_nisab_types; @endphp
                                        @if (!empty($nisabTypes))
                                            <div class="space-y-0.5">
                                                @foreach (array_slice($nisabTypes, 0, 2) as $nisab)
                                                    <div class="text-xs text-neutral-600">{{ $nisab }}</div>
                                                @endforeach
                                                @if (count($nisabTypes) > 2)
                                                    <div class="text-xs text-neutral-400">+{{ count($nisabTypes) - 2 }} lainnya</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($tipe->persentase_zakat)
                                            <div class="text-sm font-semibold text-neutral-800">{{ $tipe->formatted_persentase }}</div>
                                            @if ($tipe->persentase_alternatif)
                                                <div class="text-xs text-neutral-500">Alt: {{ number_format($tipe->persentase_alternatif, 2) }}%</div>
                                            @endif
                                        @else
                                            <span class="text-xs text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($tipe->requires_haul)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-full border border-amber-100">Ya (1 tahun)</span>
                                        @else
                                            <span class="text-xs text-neutral-500">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                            data-uuid="{{ $tipe->uuid }}" data-nama="{{ $tipe->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Baris Expandable Desktop -->
                                <tr id="detail-{{ $tipe->uuid }}" class="hidden border-b border-neutral-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-neutral-50/50"></td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50">
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-xs text-neutral-400">Jenis Zakat</p>
                                                <p class="text-sm font-medium text-neutral-800">{{ $tipe->jenisZakat->nama ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-neutral-400">Nama Tipe</p>
                                                <p class="text-sm font-medium text-neutral-800">{{ $tipe->nama }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50">
                                        <div class="space-y-2">
                                            @if ($tipe->nisab_emas_gram)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-neutral-600">Emas:</span>
                                                    <span class="text-sm font-medium text-neutral-800">{{ number_format($tipe->nisab_emas_gram, 2) }} gram</span>
                                                </div>
                                            @endif
                                            @if ($tipe->nisab_perak_gram)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-neutral-600">Perak:</span>
                                                    <span class="text-sm font-medium text-neutral-800">{{ number_format($tipe->nisab_perak_gram, 2) }} gram</span>
                                                </div>
                                            @endif
                                            @if ($tipe->nisab_pertanian_kg)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-neutral-600">Pertanian:</span>
                                                    <span class="text-sm font-medium text-neutral-800">{{ number_format($tipe->nisab_pertanian_kg, 2) }} kg</span>
                                                </div>
                                            @endif
                                            @if ($tipe->nisab_kambing_min)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-neutral-600">Kambing:</span>
                                                    <span class="text-sm font-medium text-neutral-800">min {{ number_format($tipe->nisab_kambing_min) }} ekor</span>
                                                </div>
                                            @endif
                                            @if ($tipe->nisab_sapi_min)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-neutral-600">Sapi:</span>
                                                    <span class="text-sm font-medium text-neutral-800">min {{ number_format($tipe->nisab_sapi_min) }} ekor</span>
                                                </div>
                                            @endif
                                            @if ($tipe->nisab_unta_min)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-neutral-600">Unta:</span>
                                                    <span class="text-sm font-medium text-neutral-800">min {{ number_format($tipe->nisab_unta_min) }} ekor</span>
                                                </div>
                                            @endif
                                            @if (!$tipe->nisab_emas_gram && !$tipe->nisab_perak_gram && !$tipe->nisab_pertanian_kg && !$tipe->nisab_kambing_min && !$tipe->nisab_sapi_min && !$tipe->nisab_unta_min)
                                                <p class="text-sm text-neutral-400 italic">Tidak ada nisab</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50">
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-xs text-neutral-400">Persentase Utama</p>
                                                <p class="text-sm font-semibold text-neutral-800">{{ $tipe->formatted_persentase }}</p>
                                            </div>
                                            @if ($tipe->persentase_alternatif)
                                                <div>
                                                    <p class="text-xs text-neutral-400">Persentase Alternatif</p>
                                                    <p class="text-sm font-medium text-neutral-800">{{ number_format($tipe->persentase_alternatif, 2) }}%</p>
                                                </div>
                                            @endif
                                            @if ($tipe->keterangan_persentase)
                                                <div>
                                                    <p class="text-xs text-neutral-400">Keterangan</p>
                                                    <p class="text-sm text-neutral-600">{{ $tipe->keterangan_persentase }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50">
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-xs text-neutral-400">Status Haul</p>
                                                <p class="text-sm text-neutral-700">{{ $tipe->requires_haul ? 'Memerlukan haul (1 tahun)' : 'Tidak memerlukan haul' }}</p>
                                            </div>
                                            @if ($tipe->ketentuan_khusus)
                                                <div>
                                                    <p class="text-xs text-neutral-400">Ketentuan Khusus</p>
                                                    <p class="text-sm text-neutral-600">{{ Str::limit($tipe->ketentuan_khusus, 150) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 bg-neutral-50/50"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW ==================== -->
                <div class="block md:hidden divide-y divide-neutral-100">
                    @foreach ($tipeZakat as $tipe)
                        <div class="p-4 hover:bg-primary-50/20 transition-all duration-200">
                            <!-- Header Card (klik untuk expand) -->
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $tipe->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-neutral-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-neutral-400">Tipe Zakat</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-neutral-800 break-words pr-2">
                                            {{ $tipe->nama }}
                                        </h3>
                                        <p class="text-xs text-neutral-500 mt-0.5">{{ $tipe->jenisZakat->nama ?? '-' }}</p>
                                        
                                        <!-- Badges -->
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            @if ($tipe->persentase_zakat)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                                                    {{ $tipe->formatted_persentase }}
                                                </span>
                                            @endif
                                            @if ($tipe->persentase_alternatif)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">
                                                    Alt: {{ number_format($tipe->persentase_alternatif, 0) }}%
                                                </span>
                                            @endif
                                            @if ($tipe->requires_haul)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-full border border-amber-100">
                                                    Haul
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Dropdown Button -->
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                            class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                            data-uuid="{{ $tipe->uuid }}" data-nama="{{ $tipe->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $tipe->uuid }}" class="hidden mt-3 pt-3 border-t border-neutral-100">
                                <div class="space-y-4">
                                    <!-- Informasi Dasar -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                        <div class="bg-neutral-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Jenis Zakat</span>
                                                <span class="text-xs font-medium text-neutral-700">{{ $tipe->jenisZakat->nama ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Status Haul</span>
                                                <span class="text-xs font-medium text-neutral-700">{{ $tipe->requires_haul ? 'Memerlukan haul (1 tahun)' : 'Tidak memerlukan haul' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detail Nisab -->
                                    @php
                                        $hasNisab = $tipe->nisab_emas_gram || $tipe->nisab_perak_gram || $tipe->nisab_pertanian_kg || 
                                                    $tipe->nisab_kambing_min || $tipe->nisab_sapi_min || $tipe->nisab_unta_min;
                                    @endphp
                                    @if ($hasNisab)
                                        <div>
                                            <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Detail Nisab</h4>
                                            <div class="bg-neutral-50 rounded-lg p-3 space-y-2">
                                                @if ($tipe->nisab_emas_gram)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Emas</span>
                                                        <span class="text-xs font-medium text-neutral-700">{{ number_format($tipe->nisab_emas_gram, 2) }} gram</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_perak_gram)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Perak</span>
                                                        <span class="text-xs font-medium text-neutral-700">{{ number_format($tipe->nisab_perak_gram, 2) }} gram</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_pertanian_kg)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Pertanian</span>
                                                        <span class="text-xs font-medium text-neutral-700">{{ number_format($tipe->nisab_pertanian_kg, 2) }} kg</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_kambing_min)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Kambing</span>
                                                        <span class="text-xs font-medium text-neutral-700">min {{ number_format($tipe->nisab_kambing_min) }} ekor</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_sapi_min)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Sapi</span>
                                                        <span class="text-xs font-medium text-neutral-700">min {{ number_format($tipe->nisab_sapi_min) }} ekor</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->nisab_unta_min)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Unta</span>
                                                        <span class="text-xs font-medium text-neutral-700">min {{ number_format($tipe->nisab_unta_min) }} ekor</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Persentase -->
                                    @if ($tipe->persentase_zakat || $tipe->persentase_alternatif)
                                        <div>
                                            <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Persentase</h4>
                                            <div class="bg-neutral-50 rounded-lg p-3 space-y-2">
                                                @if ($tipe->persentase_zakat)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Utama</span>
                                                        <span class="text-xs font-semibold text-neutral-800">{{ $tipe->formatted_persentase }}</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->persentase_alternatif)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-neutral-500">Alternatif</span>
                                                        <span class="text-xs font-medium text-neutral-700">{{ number_format($tipe->persentase_alternatif, 2) }}%</span>
                                                    </div>
                                                @endif
                                                @if ($tipe->keterangan_persentase)
                                                    <div class="pt-1">
                                                        <p class="text-xs text-neutral-500 mb-0.5">Keterangan</p>
                                                        <p class="text-xs text-neutral-600">{{ $tipe->keterangan_persentase }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Ketentuan Khusus -->
                                    @if ($tipe->ketentuan_khusus)
                                        <div>
                                            <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Ketentuan Khusus</h4>
                                            <div class="bg-neutral-50 rounded-lg p-3">
                                                <p class="text-xs text-neutral-600">{{ Str::limit($tipe->ketentuan_khusus, 200) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tombol Aksi Mobile -->
                                    <div class="flex gap-2 pt-2">
                                        <a href="{{ route('tipe-zakat.edit', $tipe->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <button type="button"
                                            onclick="showDeleteModal('{{ $tipe->uuid }}', '{{ addslashes($tipe->nama) }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 border border-red-100 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($tipeZakat->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-neutral-200 bg-neutral-50/30">
                        {{ $tipeZakat->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-14 text-center animate-fade-in">
                    <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    @if (request('search') || request('jenis_zakat_id') || request('requires_haul'))
                        <p class="text-sm text-neutral-500">Tidak ada hasil untuk filter yang dipilih</p>
                        <button onclick="removeAllFilters()" class="mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Reset filter</button>
                    @else
                        <p class="text-sm text-neutral-500">Belum ada data tipe zakat</p>
                        <a href="{{ route('tipe-zakat.create') }}" class="inline-block mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Tambah data</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdown-container" class="fixed hidden z-50 bg-white rounded-lg shadow-lg border border-neutral-200 min-w-[120px]">
        <div class="py-1">
            <a href="#" id="dropdown-edit-link" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <button type="button" id="dropdown-delete-btn" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black/30 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-sm w-full">
            <div class="p-5">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2 text-center">Hapus Tipe Zakat</h3>
                <p class="text-sm text-neutral-500 mb-5 text-center">Yakin ingin menghapus "<span id="modal-zakat-name" class="font-semibold text-neutral-700"></span>"?</p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn" class="flex-1 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50">Batal</button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium text-white">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentDropdownData = null;
    let searchTimeout = null;
    const editBaseUrl = "{{ rtrim(route('tipe-zakat.index'), '/') }}";

    document.addEventListener('DOMContentLoaded', function() {
        const dropdownContainer = document.getElementById('dropdown-container');
        const editLink = document.getElementById('dropdown-edit-link');
        const deleteBtn = document.getElementById('dropdown-delete-btn');
        const deleteForm = document.getElementById('delete-form');
        const filterButton = document.getElementById('filter-button');
        const filterPanel = document.getElementById('filter-panel');

        // Toggle filter panel
        if (filterButton && filterPanel) {
            filterButton.addEventListener('click', function() {
                filterPanel.classList.toggle('hidden');
            });
        }

        // Search dengan debounce
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchValue = this.value;
                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (searchValue) url.searchParams.set('search', searchValue);
                    else url.searchParams.delete('search');
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }, 400);
            });
        }

        // Filter auto-submit dengan tombol Terapkan
        const applyFilters = () => {
            const url = new URL(window.location.href);
            const jenisZakat = document.getElementById('filter-jenis-zakat')?.value;
            const haul = document.getElementById('filter-haul')?.value;
            const sortBy = document.getElementById('filter-sort-by')?.value;
            const sortOrder = document.getElementById('filter-sort-order')?.value;
            
            if (jenisZakat && jenisZakat !== '') url.searchParams.set('jenis_zakat_id', jenisZakat);
            else url.searchParams.delete('jenis_zakat_id');
            
            if (haul && haul !== '') url.searchParams.set('requires_haul', haul);
            else url.searchParams.delete('requires_haul');
            
            if (sortBy && sortBy !== 'nama') url.searchParams.set('sort_by', sortBy);
            else url.searchParams.delete('sort_by');
            
            if (sortOrder && sortOrder !== 'asc') url.searchParams.set('sort_order', sortOrder);
            else url.searchParams.delete('sort_order');
            
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        // Event listener untuk filter (change langsung)
        ['filter-jenis-zakat', 'filter-haul', 'filter-sort-by', 'filter-sort-order'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', applyFilters);
            }
        });

        // Desktop Expandable row
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.dropdown-toggle')) return;
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
                if (e.target.closest('.dropdown-toggle')) return;
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                }
            });
        });

        // Dropdown
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.dropdown-toggle');
            if (toggle) {
                e.stopPropagation();
                const dropdownUuid = toggle.getAttribute('data-uuid');
                const zakatName = toggle.getAttribute('data-nama');

                if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid && !dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                    return;
                }

                dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);
                const rect = toggle.getBoundingClientRect();

                dropdownContainer.style.visibility = 'hidden';
                dropdownContainer.classList.remove('hidden');

                requestAnimationFrame(() => {
                    const dropdownWidth = dropdownContainer.offsetWidth;
                    const dropdownHeight = dropdownContainer.offsetHeight;
                    let top = rect.bottom + 6;
                    let left = rect.right - dropdownWidth;
                    if (left < 10) left = 10;
                    if (left + dropdownWidth > window.innerWidth - 10) left = window.innerWidth - dropdownWidth - 10;
                    if (rect.bottom + dropdownHeight > window.innerHeight) top = rect.top - dropdownHeight - 6;
                    if (top < 6) top = 6;
                    dropdownContainer.style.top = top + 'px';
                    dropdownContainer.style.left = left + 'px';
                    dropdownContainer.style.visibility = '';
                });

                currentDropdownData = { uuid: dropdownUuid, name: zakatName };
                editLink.href = `${editBaseUrl}/${dropdownUuid}/edit`;
            } else if (!dropdownContainer.contains(e.target)) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        });

        // Delete handler
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!currentDropdownData?.uuid) return;
            dropdownContainer.classList.add('hidden');
            document.getElementById('modal-zakat-name').textContent = currentDropdownData.name;
            deleteForm.action = `/tipe-zakat/${currentDropdownData.uuid}`;
            document.getElementById('delete-modal').classList.remove('hidden');
        });

        // Modal handlers
        document.getElementById('cancel-delete-btn').addEventListener('click', function() {
            document.getElementById('delete-modal').classList.add('hidden');
        });

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });

        window.addEventListener('scroll', () => dropdownContainer.classList.add('hidden'), true);
        window.addEventListener('resize', () => dropdownContainer.classList.add('hidden'));
    });

    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    function removeAllFilters() {
        window.location.href = "{{ route('tipe-zakat.index') }}";
    }

    function showDeleteModal(uuid, nama) {
        document.getElementById('modal-zakat-name').textContent = nama;
        document.getElementById('delete-form').action = `/tipe-zakat/${uuid}`;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
</script>
@endpush