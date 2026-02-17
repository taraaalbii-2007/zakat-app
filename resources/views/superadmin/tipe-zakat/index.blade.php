@extends('layouts.app')

@section('title', 'Tipe Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tipe Zakat</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $tipeZakat->total() }} Data</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('tipe-zakat.create') }}"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                            Tambah Tipe
                        </span>
                    </a>
                    <button type="button" onclick="toggleFilter()"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                            Filter
                        </span>
                    </button>
                    <div id="search-container" class="transition-all duration-300"
                        style="{{ request('search') ? 'min-width: 280px;' : '' }}">
                        <button type="button" onclick="toggleSearch()" id="search-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('search') ? 'hidden' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span id="search-button-text"
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Cari
                            </span>
                        </button>
                        <form method="GET" action="{{ route('tipe-zakat.index') }}" id="search-form"
                            class="{{ request('search') ? '' : 'hidden' }}">
                            @if(request('jenis_zakat_id'))
                                <input type="hidden" name="jenis_zakat_id" value="{{ request('jenis_zakat_id') }}">
                            @endif
                            @if(request('requires_haul'))
                                <input type="hidden" name="requires_haul" value="{{ request('requires_haul') }}">
                            @endif
                            @if(request('sort_by'))
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            @endif
                            @if(request('sort_order'))
                                <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                            @endif
                            <div class="flex items-center">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="search" name="search" value="{{ request('search') }}" id="search-input"
                                        placeholder="Cari nama tipe atau ketentuan..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div id="filter-panel" class="{{ (request('jenis_zakat_id') || request('requires_haul') || request('sort_by')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('tipe-zakat.index') }}" id="filter-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                        <select name="jenis_zakat_id" id="filter-jenis-zakat"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisZakatList as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Haul</label>
                        <select name="requires_haul" id="filter-haul"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Semua</option>
                            <option value="true" {{ request('requires_haul') == 'true' ? 'selected' : '' }}>Memerlukan Haul</option>
                            <option value="false" {{ request('requires_haul') == 'false' ? 'selected' : '' }}>Tanpa Haul</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Urut Berdasarkan</label>
                        <select name="sort_by" id="filter-sort-by"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                            <option value="persentase_zakat" {{ request('sort_by') == 'persentase_zakat' ? 'selected' : '' }}>Persentase</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Urutan</label>
                        <select name="sort_order" id="filter-sort-order"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Menaik (A-Z)</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                        </select>
                    </div>
                </div>
                @if(request('jenis_zakat_id') || request('requires_haul') || request('sort_by'))
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('tipe-zakat.index', request('search') ? ['search' => request('search')] : []) }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if ($tipeZakat->count() > 0)
            {{-- Info Filter Aktif --}}
            @if(request('jenis_zakat_id') || request('requires_haul') || request('search'))
                <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Pencarian: "{{ request('search') }}"
                                    <button type="button" onclick="removeFilter('search')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                        ×
                                    </button>
                                </span>
                            @endif
                            @if(request('jenis_zakat_id'))
                                @php
                                    $jenis = $jenisZakatList->firstWhere('id', request('jenis_zakat_id'));
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Jenis: {{ $jenis->nama ?? 'Unknown' }}
                                    <button type="button" onclick="removeFilter('jenis_zakat_id')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                        ×
                                    </button>
                                </span>
                            @endif
                            @if(request('requires_haul'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Haul: {{ request('requires_haul') == 'true' ? 'Memerlukan' : 'Tanpa' }}
                                    <button type="button" onclick="removeFilter('requires_haul')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                        ×
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Desktop View --}}
            <div class="hidden md:block overflow-x-auto" id="table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Informasi Tipe
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nisab
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Persentase
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Haul
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tipeZakat as $tipe)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row" 
                                data-target="detail-{{ $tipe->uuid }}">
                                <td class="px-4 py-4">
                                    <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $tipe->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $tipe->jenisZakat->nama ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk detail</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $nisabTypes = $tipe->active_nisab_types;
                                    @endphp
                                    @if(!empty($nisabTypes))
                                        <div class="space-y-1">
                                            @foreach(array_slice($nisabTypes, 0, 2) as $nisab)
                                                <div class="text-xs text-gray-600">{{ $nisab }}</div>
                                            @endforeach
                                            @if(count($nisabTypes) > 2)
                                                <div class="text-xs text-gray-400">+{{ count($nisabTypes) - 2 }} lainnya</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($tipe->persentase_zakat)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $tipe->formatted_persentase }}
                                        </div>
                                        @if($tipe->persentase_alternatif)
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                Alt: {{ number_format($tipe->persentase_alternatif, 2) }}%
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($tipe->requires_haul)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Ya (1 tahun)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Tidak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $tipe->uuid }}"
                                            data-nama="{{ $tipe->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            {{-- Expandable Content Row --}}
                            <tr id="detail-{{ $tipe->uuid }}" class="hidden expandable-content">
                                <td colspan="6" class="px-0 py-0">
                                    <div class="bg-gray-50 border-y border-gray-100">
                                        <div class="px-6 py-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Informasi Dasar --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Dasar</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $tipe->jenisZakat->nama ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama Tipe</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $tipe->nama }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- Kolom 2: Nisab Detail --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Nisab</h4>
                                                    <div class="space-y-2">
                                                        @if($tipe->nisab_emas_gram)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">Emas:</span>
                                                                <span class="font-medium text-gray-900">{{ number_format($tipe->nisab_emas_gram, 2) }} gram</span>
                                                            </div>
                                                        @endif
                                                        @if($tipe->nisab_perak_gram)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">Perak:</span>
                                                                <span class="font-medium text-gray-900">{{ number_format($tipe->nisab_perak_gram, 2) }} gram</span>
                                                            </div>
                                                        @endif
                                                        @if($tipe->nisab_pertanian_kg)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">Pertanian:</span>
                                                                <span class="font-medium text-gray-900">{{ number_format($tipe->nisab_pertanian_kg, 2) }} kg</span>
                                                            </div>
                                                        @endif
                                                        @if($tipe->nisab_kambing_min)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">Kambing:</span>
                                                                <span class="font-medium text-gray-900">min {{ number_format($tipe->nisab_kambing_min) }} ekor</span>
                                                            </div>
                                                        @endif
                                                        @if($tipe->nisab_sapi_min)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">Sapi:</span>
                                                                <span class="font-medium text-gray-900">min {{ number_format($tipe->nisab_sapi_min) }} ekor</span>
                                                            </div>
                                                        @endif
                                                        @if($tipe->nisab_unta_min)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">Unta:</span>
                                                                <span class="font-medium text-gray-900">min {{ number_format($tipe->nisab_unta_min) }} ekor</span>
                                                            </div>
                                                        @endif
                                                        @if(!$tipe->nisab_emas_gram && !$tipe->nisab_perak_gram && !$tipe->nisab_pertanian_kg && !$tipe->nisab_kambing_min && !$tipe->nisab_sapi_min && !$tipe->nisab_unta_min)
                                                            <p class="text-sm text-gray-400 italic">Tidak ada nisab</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                {{-- Kolom 3: Persentase & Haul --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ketentuan Zakat</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Persentase</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $tipe->formatted_persentase }}
                                                                    @if($tipe->keterangan_persentase)
                                                                        <span class="text-xs text-gray-500 ml-1">({{ $tipe->keterangan_persentase }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Haul</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $tipe->requires_haul ? 'Memerlukan haul (1 tahun)' : 'Tidak memerlukan haul' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($tipe->ketentuan_khusus)
                                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                                            <h5 class="text-xs font-medium text-gray-700 mb-2">Ketentuan Khusus</h5>
                                                            <p class="text-sm text-gray-600">{{ Str::limit($tipe->ketentuan_khusus, 150) }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile View --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach ($tipeZakat as $tipe)
                    <div class="expandable-card">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $tipe->uuid }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                                {{ $tipe->nama }}
                                            </h3>
                                            @if($tipe->requires_haul)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                                    Haul
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5 truncate">
                                            {{ $tipe->jenisZakat->nama ?? '-' }}
                                        </div>
                                        <div class="flex items-center mt-1">
                                            @if($tipe->persentase_zakat)
                                                <span class="text-xs font-medium text-gray-700">{{ $tipe->formatted_persentase }}</span>
                                                @if($tipe->persentase_alternatif)
                                                    <span class="text-xs text-gray-500 ml-1">(Alt: {{ number_format($tipe->persentase_alternatif, 0) }}%)</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 ml-2">
                                    <button type="button"
                                        class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-uuid="{{ $tipe->uuid }}"
                                        data-nama="{{ $tipe->nama }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Mobile Expandable Content --}}
                        <div id="detail-mobile-{{ $tipe->uuid }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    {{-- Informasi Nisab --}}
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Nisab</h4>
                                        <div class="space-y-2">
                                            @if($tipe->nisab_emas_gram)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Emas:</span>
                                                    <span class="font-medium text-gray-900">{{ number_format($tipe->nisab_emas_gram, 2) }} gram</span>
                                                </div>
                                            @endif
                                            @if($tipe->nisab_perak_gram)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Perak:</span>
                                                    <span class="font-medium text-gray-900">{{ number_format($tipe->nisab_perak_gram, 2) }} gram</span>
                                                </div>
                                            @endif
                                            @if($tipe->nisab_pertanian_kg)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Pertanian:</span>
                                                    <span class="font-medium text-gray-900">{{ number_format($tipe->nisab_pertanian_kg, 2) }} kg</span>
                                                </div>
                                            @endif
                                            @if($tipe->nisab_kambing_min)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Kambing:</span>
                                                    <span class="font-medium text-gray-900">min {{ number_format($tipe->nisab_kambing_min) }} ekor</span>
                                                </div>
                                            @endif
                                            @if($tipe->nisab_sapi_min)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Sapi:</span>
                                                    <span class="font-medium text-gray-900">min {{ number_format($tipe->nisab_sapi_min) }} ekor</span>
                                                </div>
                                            @endif
                                            @if($tipe->nisab_unta_min)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Unta:</span>
                                                    <span class="font-medium text-gray-900">min {{ number_format($tipe->nisab_unta_min) }} ekor</span>
                                                </div>
                                            @endif
                                            @if(!$tipe->nisab_emas_gram && !$tipe->nisab_perak_gram && !$tipe->nisab_pertanian_kg && !$tipe->nisab_kambing_min && !$tipe->nisab_sapi_min && !$tipe->nisab_unta_min)
                                                <p class="text-sm text-gray-400 italic">Tidak ada nisab</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Informasi Persentase --}}
                                    @if($tipe->persentase_zakat || $tipe->persentase_alternatif || $tipe->keterangan_persentase)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Persentase Zakat</h4>
                                            <div class="space-y-2">
                                                @if($tipe->persentase_zakat)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-600">Persentase:</span>
                                                        <span class="font-medium text-gray-900">{{ $tipe->formatted_persentase }}</span>
                                                    </div>
                                                @endif
                                                @if($tipe->persentase_alternatif)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-600">Alternatif:</span>
                                                        <span class="font-medium text-gray-900">{{ number_format($tipe->persentase_alternatif, 2) }}%</span>
                                                    </div>
                                                @endif
                                                @if($tipe->keterangan_persentase)
                                                    <div class="text-sm">
                                                        <span class="text-gray-600">Keterangan:</span>
                                                        <p class="text-gray-900 mt-1">{{ $tipe->keterangan_persentase }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    {{-- Ketentuan Khusus --}}
                                    @if($tipe->ketentuan_khusus)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Ketentuan Khusus</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($tipe->ketentuan_khusus, 100) }}</p>
                                        </div>
                                    @endif
                                    
                                    {{-- Tombol Aksi --}}
                                    <div class="pt-3 border-t border-gray-200">
                                        <div class="flex gap-2">
                                            <a href="{{ route('tipe-zakat.edit', $tipe->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <button type="button"
                                                onclick="showDeleteModal('{{ $tipe->uuid }}', '{{ $tipe->nama }}')"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($tipeZakat->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $tipeZakat->links() }}
                </div>
            @endif
        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                @if(request('search') || request('jenis_zakat_id') || request('requires_haul'))
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Tidak ada data tipe zakat yang cocok dengan filter yang dipilih.
                    </p>
                    <a href="{{ route('tipe-zakat.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Pencarian
                    </a>
                @else
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Tipe Zakat</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai tambahkan tipe zakat untuk melengkapi master data.</p>
                    <a href="{{ route('tipe-zakat.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Tipe Zakat
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Dropdown Container --}}
<div id="dropdown-container" class="fixed hidden z-50">
    <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            <a href="#" id="dropdown-edit-link"
                class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit
            </a>
            <button type="button" id="dropdown-delete-btn"
                class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Hapus
            </button>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
        <div class="flex justify-center mb-3 sm:mb-4">
            <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Tipe Zakat</h3>
        <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus tipe zakat
            "<span id="modal-nama" class="font-semibold text-gray-700"></span>"?
        </p>
        <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">
            Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex justify-center gap-2 sm:gap-3">
            <button type="button" id="cancel-delete-btn"
                class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Batal
            </button>
            <form id="delete-form" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentDropdownData = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownContainer = document.getElementById('dropdown-container');
        const editLink = document.getElementById('dropdown-edit-link');
        const deleteBtn = document.getElementById('dropdown-delete-btn');
        const deleteForm = document.getElementById('delete-form');
        const tableContainer = document.getElementById('table-container');
        
        // Filter elements
        const filterJenisZakat = document.getElementById('filter-jenis-zakat');
        const filterHaul = document.getElementById('filter-haul');
        const filterSortBy = document.getElementById('filter-sort-by');
        const filterSortOrder = document.getElementById('filter-sort-order');
        const filterForm = document.getElementById('filter-form');
        
        // Handle filter changes
        if (filterJenisZakat) {
            filterJenisZakat.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        if (filterHaul) {
            filterHaul.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        if (filterSortBy) {
            filterSortBy.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        if (filterSortOrder) {
            filterSortOrder.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        // Desktop Expandable Rows
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('.dropdown-toggle') || e.target.closest('button[type="submit"]')) return;
                
                const targetId = this.getAttribute('data-target');
                const targetRow = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon');
                
                if (targetRow.classList.contains('hidden')) {
                    targetRow.classList.remove('hidden');
                    icon.classList.add('rotate-90');
                } else {
                    targetRow.classList.add('hidden');
                    icon.classList.remove('rotate-90');
                }
            });
        });

        // Mobile Expandable Cards
        document.querySelectorAll('.expandable-row-mobile').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('.dropdown-toggle') || e.target.closest('button[type="submit"]')) return;
                
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                
                if (targetContent.classList.contains('hidden')) {
                    targetContent.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    targetContent.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        });
        
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.dropdown-toggle');
            if (toggle) {
                e.stopPropagation();
                const dropdownUuid = toggle.getAttribute('data-uuid');
                const nama = toggle.getAttribute('data-nama');
                
                if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid &&
                    !dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                    return;
                }
                
                dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);
                const rect = toggle.getBoundingClientRect();
                
                let top = rect.bottom;
                let left = rect.left;
                
                const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
                const dropdownHeight = 88;
                
                if (left + dropdownWidth > window.innerWidth) {
                    left = window.innerWidth - dropdownWidth - 10;
                }
                
                if (top + dropdownHeight > window.innerHeight) {
                    top = rect.top - dropdownHeight;
                }
                
                dropdownContainer.style.top = top + 'px';
                dropdownContainer.style.left = left + 'px';
                
                // PERBAIKAN: Gunakan route helper
                editLink.href = '{{ route("tipe-zakat.edit", ":uuid") }}'.replace(':uuid', dropdownUuid);
                
                currentDropdownData = {
                    uuid: dropdownUuid,
                    nama: nama
                };
                
                dropdownContainer.classList.remove('hidden');
            } else {
                if (!dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            }
        });
        
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const uuid = currentDropdownData?.uuid;
            const nama = currentDropdownData?.nama;
            
            if (!uuid) return;
            
            dropdownContainer.classList.add('hidden');
            dropdownContainer.removeAttribute('data-current-uuid');
            
            showDeleteModal(uuid, nama);
        });
        
        document.getElementById('cancel-delete-btn').addEventListener('click', function() {
            document.getElementById('delete-modal').classList.add('hidden');
        });
        
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        window.addEventListener('scroll', function() {
            if (!dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        }, true);
        
        if (tableContainer) {
            tableContainer.addEventListener('scroll', function() {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            }, true);
        }
        
        window.addEventListener('resize', function() {
            if (!dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        });
    });
    
    function toggleSearch() {
        const searchButton = document.getElementById('search-button');
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const searchContainer = document.getElementById('search-container');
        
        if (searchForm.classList.contains('hidden')) {
            searchButton.classList.add('hidden');
            searchForm.classList.remove('hidden');
            searchContainer.style.minWidth = '280px';
            setTimeout(() => searchInput.focus(), 50);
        } else {
            const hasQuery = '{{ request('search') }}' !== '';
            if (!hasQuery) {
                searchInput.value = '';
            }
            searchForm.classList.add('hidden');
            searchButton.classList.remove('hidden');
            searchContainer.style.minWidth = 'auto';
        }
    }

    function toggleFilter() {
        const filterPanel = document.getElementById('filter-panel');
        filterPanel.classList.toggle('hidden');
    }
    
    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
    
    function showDeleteModal(uuid, nama) {
        document.getElementById('modal-nama').textContent = nama;
        // PERBAIKAN: Gunakan route helper
        document.getElementById('delete-form').action = '{{ route("tipe-zakat.destroy", ":uuid") }}'.replace(':uuid', uuid);
        document.getElementById('delete-modal').classList.remove('hidden');
    }
</script>
@endpush