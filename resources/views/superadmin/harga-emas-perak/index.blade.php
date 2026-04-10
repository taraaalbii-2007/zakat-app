@extends('layouts.app')

@section('title', 'Kelola Harga Emas & Perak')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Harga Emas & Perak</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Data referensi harga untuk perhitungan nisab zakat</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-xs font-medium rounded-lg transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('harga-emas-perak.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-6 py-4 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $hargaEmasPerak->total() }}</span>
                        <span class="text-sm text-gray-500">Data Harga</span>
                    </div>

                    <!-- Active Filters Tags -->
                    @if (request('search') || request('status') || request('tanggal') || request('sumber') || (request('sort_by') && request('sort_by') != 'tanggal') || (request('sort_order') && request('sort_order') != 'desc'))
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-400">Filter aktif:</span>
                            
                            @if (request('search'))
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    "{{ request('search') }}"
                                    <button onclick="removeFilter('search')" class="hover:text-green-900 transition-colors ml-1">×</button>
                                </div>
                            @endif
                            
                            @if (request('status'))
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                    Status: {{ request('status') == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                    <button onclick="removeFilter('status')" class="hover:text-green-900 transition-colors ml-1">×</button>
                                </div>
                            @endif
                            
                            @if (request('tanggal'))
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                    Tgl: {{ \Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y') }}
                                    <button onclick="removeFilter('tanggal')" class="hover:text-green-900 transition-colors ml-1">×</button>
                                </div>
                            @endif
                            
                            @if (request('sumber'))
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                    Sumber: {{ request('sumber') }}
                                    <button onclick="removeFilter('sumber')" class="hover:text-green-900 transition-colors ml-1">×</button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="px-5 py-3 border-b border-gray-100 bg-green-50/30 hidden">
                <form method="GET" action="{{ route('harga-emas-perak.index') }}" class="flex flex-col gap-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                        <!-- Search Field -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Sumber</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="filterSearchInput" value="{{ request('search') }}"
                                    placeholder="Cari sumber..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <!-- Tanggal Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                        </div>

                        <!-- Sumber Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Sumber</label>
                            <select name="sumber"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                                <option value="">Semua Sumber</option>
                                @foreach ($sumberList as $sumber)
                                    @if ($sumber)
                                        <option value="{{ $sumber }}" {{ request('sumber') == $sumber ? 'selected' : '' }}>
                                            {{ $sumber }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Urut Berdasarkan</label>
                            <select name="sort_by"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                                <option value="tanggal" {{ request('sort_by', 'tanggal') === 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                                <option value="harga_emas_pergram" {{ request('sort_by') === 'harga_emas_pergram' ? 'selected' : '' }}>Harga Emas</option>
                                <option value="harga_perak_pergram" {{ request('sort_by') === 'harga_perak_pergram' ? 'selected' : '' }}>Harga Perak</option>
                                <option value="sumber" {{ request('sort_by') === 'sumber' ? 'selected' : '' }}>Sumber</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Urutan</label>
                            <select name="sort_order"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                                <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>Menurun (Terbaru)</option>
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Menaik (Terlama)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button type="submit"
                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" id="closeFilterPanelBtn"
                            class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel -->
            @if ($hargaEmasPerak->count() > 0)
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HARGA EMAS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HARGA PERAK</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SUMBER</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($hargaEmasPerak as $index => $harga)
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300 cursor-pointer expandable-row"
                                    data-target="detail-{{ $harga->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                {{ $harga->tanggal->format('d/m/Y') }}
                                            </span>
                                            <div class="mt-1">
                                                @if ($harga->is_active)
                                                    <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded-full border border-green-100">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-800">
                                            Rp {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}
                                        </span>
                                        <div class="text-xs text-gray-400">/gram</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-800">
                                            Rp {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}
                                        </span>
                                        <div class="text-xs text-gray-400">/gram</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $harga->sumber ?? '-' }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Ikon Edit dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('harga-emas-perak.edit', $harga->uuid) }}"
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

                                            <!-- Ikon Toggle Status dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <form action="{{ route('harga-emas-perak.toggle-status', $harga->uuid) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="flex items-center justify-center p-1.5 {{ $harga->is_active ? 'text-amber-500 hover:text-amber-600 hover:bg-amber-50' : 'text-green-500 hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-all duration-200">
                                                        @if ($harga->is_active)
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    {{ $harga->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            <!-- Ikon Hapus dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $harga->uuid }}" data-tanggal="{{ $harga->tanggal->format('d/m/Y') }}">
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

                                <!-- Baris Expandable Desktop -->
                                <tr id="detail-{{ $harga->uuid }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="5" class="px-6 py-4 bg-gray-50/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Kolom 1: Informasi Dasar -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                                <div class="space-y-1.5">
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Tanggal</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $harga->tanggal->format('d F Y') }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Sumber</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $harga->sumber ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Status</span>
                                                        <span class="text-xs font-medium {{ $harga->is_active ? 'text-green-600' : 'text-gray-500' }}">
                                                            {{ $harga->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kolom 2: Detail Harga -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Detail Harga</h4>
                                                <div class="space-y-1.5">
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Harga Emas</span>
                                                        <span class="text-xs font-semibold text-gray-800">Rp {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}/gram</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Harga Perak</span>
                                                        <span class="text-xs font-semibold text-gray-800">Rp {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}/gram</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kolom 3: Keterangan & Riwayat -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Keterangan</h4>
                                                @if ($harga->keterangan)
                                                    <p class="text-xs text-gray-600 mb-3">{{ $harga->keterangan }}</p>
                                                @else
                                                    <p class="text-xs text-gray-400 italic mb-3">Tidak ada keterangan</p>
                                                @endif
                                                
                                                <div class="pt-2 border-t border-gray-200">
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Riwayat</h4>
                                                    <div class="text-xs text-gray-400 space-y-0.5">
                                                        <div>Dibuat: {{ $harga->created_at->format('d/m/Y H:i') }}</div>
                                                        @if ($harga->updated_at != $harga->created_at)
                                                            <div>Diperbarui: {{ $harga->updated_at->format('d/m/Y H:i') }}</div>
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

                <!-- Mobile Card View -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($hargaEmasPerak as $index => $harga)
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $harga->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Harga Emas & Perak</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words">
                                            {{ $harga->tanggal->format('d/m/Y') }}
                                        </h3>
                                        
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            @if ($harga->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Nonaktif</span>
                                            @endif
                                            <span class="text-xs text-gray-500">Emas: Rp {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-300">•</span>
                                            <span class="text-xs text-gray-500">Perak: Rp {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Edit dengan Tooltip -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('harga-emas-perak.edit', $harga->uuid) }}"
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

                                        <!-- Toggle Status dengan Tooltip -->
                                        <div class="relative group/tooltip">
                                            <form action="{{ route('harga-emas-perak.toggle-status', $harga->uuid) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="flex items-center justify-center p-1.5 {{ $harga->is_active ? 'text-amber-500 hover:text-amber-600 hover:bg-amber-50' : 'text-green-500 hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-all">
                                                    @if ($harga->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                {{ $harga->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>

                                        <!-- Hapus dengan Tooltip -->
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $harga->uuid }}" data-tanggal="{{ $harga->tanggal->format('d/m/Y') }}">
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
                            <div id="detail-mobile-{{ $harga->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Tanggal</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $harga->tanggal->format('d F Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Sumber</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $harga->sumber ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Status</span>
                                                <span class="text-xs font-medium {{ $harga->is_active ? 'text-green-600' : 'text-gray-500' }}">{{ $harga->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Detail Harga</h4>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Harga Emas</span>
                                                <span class="text-xs font-semibold text-gray-800">Rp {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}/gram</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Harga Perak</span>
                                                <span class="text-xs font-semibold text-gray-800">Rp {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}/gram</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($harga->keterangan)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Keterangan</h4>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <p class="text-xs text-gray-600">{{ Str::limit($harga->keterangan, 100) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="pt-1 text-xs text-gray-400 space-y-0.5 border-t border-gray-200">
                                        <div>Dibuat: {{ $harga->created_at->format('d/m/Y H:i') }}</div>
                                        @if ($harga->updated_at != $harga->created_at)
                                            <div>Diperbarui: {{ $harga->updated_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($hargaEmasPerak->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $hargaEmasPerak->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    @if (request('search') || request('status') || request('tanggal') || request('sumber'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <button onclick="resetAllFilters()"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset filter
                        </button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data harga emas & perak</p>
                        <a href="{{ route('harga-emas-perak.create') }}"
                            class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
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
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Data Harga</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus data harga tanggal "<span id="modal-tanggal" class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                            Hapus
                        </button>
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
                if (e.target.closest('.delete-btn') || e.target.closest('a') || e.target.closest('button[type="submit"]')) return;
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
                if (e.target.closest('.delete-btn') || e.target.closest('a') || e.target.closest('button[type="submit"]')) return;
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
                const tanggal = this.getAttribute('data-tanggal');
                document.getElementById('modal-tanggal').textContent = tanggal;
                deleteForm.action = `/harga-emas-perak/${uuid}`;
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
        url.searchParams.delete('status');
        url.searchParams.delete('tanggal');
        url.searchParams.delete('sumber');
        url.searchParams.delete('sort_by');
        url.searchParams.delete('sort_order');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
@endpush