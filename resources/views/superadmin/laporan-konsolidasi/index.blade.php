@extends('layouts.app')

@section('title', 'Laporan Konsolidasi')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Laporan Konsolidasi</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $laporan->total() }} Laporan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
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
                            <form method="GET" action="{{ route('laporan-konsolidasi.index') }}" id="search-form"
                                class="{{ request('search') ? '' : 'hidden' }}">
                                @if(request('masjid_id'))
                                    <input type="hidden" name="masjid_id" value="{{ request('masjid_id') }}">
                                @endif
                                @if(request('tahun'))
                                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                @endif
                                @if(request('bulan'))
                                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
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
                                            placeholder="Cari masjid..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel" class="{{ (request('masjid_id') || request('tahun') || request('bulan')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('laporan-konsolidasi.index') }}" id="filter-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Masjid</label>
                            <select name="masjid_id" id="filter-masjid"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Masjid</option>
                                @foreach(\App\Models\Masjid::orderBy('nama')->get() as $masjid)
                                    <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>
                                        {{ $masjid->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" id="filter-tahun"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Tahun</option>
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" id="filter-bulan"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Bulan</option>
                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $bulan)
                                    <option value="{{ $index + 1 }}" {{ request('bulan') == ($index + 1) ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(request('masjid_id') || request('tahun') || request('bulan'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('laporan-konsolidasi.index', request('search') ? ['search' => request('search')] : []) }}"
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

            @if ($laporan->count() > 0)
                {{-- Info Filter Aktif --}}
                @if(request('masjid_id') || request('tahun') || request('bulan') || request('search'))
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
                                @if(request('masjid_id'))
                                    @php
                                        $selectedMasjid = \App\Models\Masjid::find(request('masjid_id'));
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Masjid: {{ $selectedMasjid->nama ?? '-' }}
                                        <button type="button" onclick="removeFilter('masjid_id')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                                @if(request('tahun'))
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Tahun: {{ request('tahun') }}
                                        <button type="button" onclick="removeFilter('tahun')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                                @if(request('bulan'))
                                    @php
                                        $bulanName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][request('bulan') - 1];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Bulan: {{ $bulanName }}
                                        <button type="button" onclick="removeFilter('bulan')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Summary Cards --}}
                @php
                    $totalPenerimaan = $laporan->sum('total_penerimaan');
                    $totalPenyaluran = $laporan->sum('total_penyaluran');
                    $totalSaldo = $laporan->sum('saldo_akhir');
                    $totalMuzakki = $laporan->sum('jumlah_muzakki');
                    $totalMustahik = $laporan->sum('jumlah_mustahik');
                @endphp

                <div class="px-4 sm:px-6 py-4 bg-gradient-to-br from-primary/5 to-primary/10 border-b border-gray-200">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs font-medium text-gray-500 uppercase">Total Penerimaan</p>
                            <p class="text-base sm:text-lg font-bold text-green-600 mt-1">
                                Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs font-medium text-gray-500 uppercase">Total Penyaluran</p>
                            <p class="text-base sm:text-lg font-bold text-red-600 mt-1">
                                Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs font-medium text-gray-500 uppercase">Total Saldo</p>
                            <p class="text-base sm:text-lg font-bold text-blue-600 mt-1">
                                Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs font-medium text-gray-500 uppercase">Muzakki</p>
                            <p class="text-base sm:text-lg font-bold text-purple-600 mt-1">
                                {{ number_format($totalMuzakki, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs font-medium text-gray-500 uppercase">Mustahik</p>
                            <p class="text-base sm:text-lg font-bold text-orange-600 mt-1">
                                {{ number_format($totalMustahik, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Desktop View --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Masjid
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Periode
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penerimaan
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penyaluran
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo Akhir
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($laporan as $item)
                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row" 
                                    data-target="detail-{{ $item->uuid }}">
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
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($item->masjid->foto && count($item->masjid->foto) > 0)
                                                    <img class="h-10 w-10 rounded-lg object-cover border border-gray-200"
                                                        src="{{ asset('storage/' . $item->masjid->foto[0]) }}"
                                                        alt="{{ $item->masjid->nama }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->masjid->nama }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $item->masjid->kode_masjid }}</div>
                                                <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->bulan_nama }} {{ $item->tahun }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-medium text-green-600">
                                            Rp {{ number_format($item->total_penerimaan, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-medium text-red-600">
                                            Rp {{ number_format($item->total_penyaluran, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-medium text-blue-600">
                                            Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                        <a href="{{ route('laporan-konsolidasi.detail', $item->masjid->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $item->uuid }}" class="hidden expandable-content">
                                    <td colspan="7" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                    {{-- Statistik Muzakki & Mustahik --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Statistik</h4>
                                                        <div class="space-y-2">
                                                            <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-xs text-gray-600">Jumlah Muzakki</span>
                                                                <span class="text-sm font-semibold text-purple-600">{{ number_format($item->jumlah_muzakki, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200">
                                                                <span class="text-xs text-gray-600">Jumlah Mustahik</span>
                                                                <span class="text-sm font-semibold text-orange-600">{{ number_format($item->jumlah_mustahik, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Info Masjid --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Info Masjid</h4>
                                                        <div class="space-y-2 text-sm">
                                                            @if($item->masjid->alamat_lengkap)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                    <span class="text-gray-600">{{ Str::limit($item->masjid->alamat_lengkap, 60) }}</span>
                                                                </div>
                                                            @endif
                                                            @if($item->masjid->telepon)
                                                                <div class="flex items-center">
                                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                    <span class="text-gray-600">{{ $item->masjid->telepon }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Ringkasan Keuangan --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan Keuangan</h4>
                                                        <div class="space-y-2">
                                                            <div class="flex justify-between items-center text-sm">
                                                                <span class="text-gray-600">Penerimaan</span>
                                                                <span class="font-medium text-green-600">Rp {{ number_format($item->total_penerimaan, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="flex justify-between items-center text-sm">
                                                                <span class="text-gray-600">Penyaluran</span>
                                                                <span class="font-medium text-red-600">Rp {{ number_format($item->total_penyaluran, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="pt-2 border-t border-gray-200">
                                                                <div class="flex justify-between items-center text-sm">
                                                                    <span class="font-medium text-gray-900">Saldo Akhir</span>
                                                                    <span class="font-bold text-blue-600">Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- Metadata --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200">
                                                    <div class="text-xs text-gray-500 space-y-1">
                                                        <div>Dibuat: {{ $item->created_at->format('d/m/Y H:i') }}</div>
                                                        @if($item->updated_at != $item->created_at)
                                                            <div>Diperbarui: {{ $item->updated_at->format('d/m/Y H:i') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                {{-- Tombol Aksi --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                                                    <a href="{{ route('laporan-konsolidasi.detail', $item->masjid->id) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Lihat Detail Lengkap
                                                    </a>
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
                    @foreach ($laporan as $item)
                        <div class="expandable-card">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $item->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                                            @if($item->masjid->foto && count($item->masjid->foto) > 0)
                                                <img class="h-10 w-10 rounded-lg object-cover border border-gray-200"
                                                    src="{{ asset('storage/' . $item->masjid->foto[0]) }}"
                                                    alt="{{ $item->masjid->nama }}">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $item->masjid->nama }}</h3>
                                            </div>
                                            <div class="flex items-center mt-1">
                                                <span class="text-xs text-gray-500">{{ $item->bulan_nama }} {{ $item->tahun }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $item->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">
                                        {{-- Ringkasan Keuangan --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Ringkasan Keuangan</h4>
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center text-sm p-2 bg-white rounded-lg border border-gray-200">
                                                    <span class="text-gray-600">Penerimaan</span>
                                                    <span class="font-medium text-green-600">Rp {{ number_format($item->total_penerimaan, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between items-center text-sm p-2 bg-white rounded-lg border border-gray-200">
                                                    <span class="text-gray-600">Penyaluran</span>
                                                    <span class="font-medium text-red-600">Rp {{ number_format($item->total_penyaluran, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between items-center text-sm p-2 bg-white rounded-lg border border-gray-200">
                                                    <span class="font-medium text-gray-900">Saldo Akhir</span>
                                                    <span class="font-bold text-blue-600">Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Statistik --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Statistik</h4>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="p-2 bg-white rounded-lg border border-gray-200">
                                                    <span class="text-xs text-gray-600 block">Muzakki</span>
                                                    <span class="text-sm font-semibold text-purple-600">{{ number_format($item->jumlah_muzakki, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="p-2 bg-white rounded-lg border border-gray-200">
                                                    <span class="text-xs text-gray-600 block">Mustahik</span>
                                                    <span class="text-sm font-semibold text-orange-600">{{ number_format($item->jumlah_mustahik, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Tombol Aksi --}}
                                        <div class="pt-3 border-t border-gray-200">
                                            <a href="{{ route('laporan-konsolidasi.detail', $item->masjid->id) }}"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Detail Lengkap
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($laporan->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $laporan->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    @if(request('search') || request('masjid_id') || request('tahun') || request('bulan'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Tidak ada laporan konsolidasi yang sesuai dengan filter yang dipilih
                        </p>
                        <a href="{{ route('laporan-konsolidasi.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Laporan</h3>
                        <p class="text-sm text-gray-500 mb-6">Data laporan konsolidasi akan muncul setelah ada transaksi zakat yang dicatat.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter elements
            const filterMasjid = document.getElementById('filter-masjid');
            const filterTahun = document.getElementById('filter-tahun');
            const filterBulan = document.getElementById('filter-bulan');
            const filterForm = document.getElementById('filter-form');
            
            // Handle filter changes
            if (filterMasjid) {
                filterMasjid.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            if (filterTahun) {
                filterTahun.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            if (filterBulan) {
                filterBulan.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            // Desktop Expandable Rows
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a') || e.target.closest('button[type="button"]')) {
                        if (!e.target.closest('.expand-btn')) return;
                    }
                    
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
                    if (e.target.closest('a')) return;
                    
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
    </script>
@endpush