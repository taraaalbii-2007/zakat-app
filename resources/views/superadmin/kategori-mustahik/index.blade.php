@extends('layouts.app')

@section('title', 'Kelola Kategori Mustahik')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Kategori Mustahik</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola 8 asnaf penerima zakat</p>
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
                        <a href="{{ route('kategori-mustahik.create') }}"
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
                        <span class="text-sm font-semibold text-gray-800">{{ $kategoriMustahik->total() }}</span>
                        <span class="text-sm text-gray-500">Kategori Mustahik</span>
                    </div>

                    <!-- Active Filters Tags -->
                    @if (request('search') || request('sort_by') || request('sort_order'))
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-400">Filter aktif:</span>
                            
                            @if (request('search'))
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    "{{ request('search') }}"
                                    <button onclick="removeFilter('search')" class="hover:text-green-900 transition-colors ml-1">×</button>
                                </div>
                            @endif

                            @if (request('sort_by') && request('sort_by') != 'nama')
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                    Urutan: {{ request('sort_by') == 'persentase_default' ? 'Persentase' : 'Tanggal' }}
                                    <button onclick="removeFilter('sort_by'); removeFilter('sort_order')" class="hover:text-green-900 transition-colors ml-1">×</button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="px-5 py-3 border-b border-gray-100 bg-green-50/30 hidden">
                <form method="GET" action="{{ route('kategori-mustahik.index') }}" class="space-y-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Kategori</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama kategori..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Urut Berdasarkan</label>
                            <select name="sort_by"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                                <option value="nama" {{ request('sort_by', 'nama') == 'nama' ? 'selected' : '' }}>Nama</option>
                                <option value="persentase_default" {{ request('sort_by') == 'persentase_default' ? 'selected' : '' }}>Persentase Default</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                            </select>
                        </div>

                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Urutan</label>
                            <select name="sort_order"
                                class="px-3 py-1.5 w-full text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                                <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Menaik (A-Z)</option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end">
                        @if (request('search') || request('sort_by') || request('sort_order'))
                            <a href="{{ route('kategori-mustahik.index') }}"
                                class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium rounded-lg transition-all">
                                Reset Filter
                            </a>
                        @endif
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

            <!-- Tabel dengan Expandable Row -->
            @if ($kategoriMustahik->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KATEGORI MUSTAHIK</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">PERSENTASE DEFAULT</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategoriMustahik as $kategori)
                                <!-- Baris Utama (Expandable) -->
                                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $kategori->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                            {{ $kategori->nama }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm {{ $kategori->persentase_default ? 'font-semibold text-gray-800' : 'text-gray-400' }}">
                                            {{ $kategori->persentase_formatted ?? ($kategori->persentase_default ? $kategori->persentase_default . '%' : '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Ikon Edit dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('kategori-mustahik.edit', $kategori->uuid) }}"
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

                                            <!-- Ikon Hapus dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $kategori->uuid }}" data-nama="{{ $kategori->nama }}">
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

                                <!-- Baris Expandable (Detail) - TANPA TOMBOL EDIT LENGKAP -->
                                <tr id="detail-{{ $kategori->uuid }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-gray-50/30"></td>
                                    <td class="px-6 py-4 align-top bg-gray-50/30" colspan="3">
                                        <div class="space-y-3">
                                            <!-- Kriteria Penerima -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Kriteria Penerima
                                                </h4>
                                                @if ($kategori->kriteria)
                                                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                                                        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                                                            {{ $kategori->kriteria }}
                                                        </p>
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-400 italic bg-white rounded-lg p-3 border border-gray-100">
                                                        Tidak ada kriteria yang diisi
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Informasi Tambahan -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Informasi Lainnya
                                                </h4>
                                                <div class="bg-white rounded-lg p-3 border border-gray-100 space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Dibuat pada</span>
                                                        <span class="text-xs font-medium text-gray-700">
                                                            {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}
                                                        </span>
                                                    </div>
                                                    @if ($kategori->updated_at && $kategori->updated_at->ne($kategori->created_at))
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Terakhir diperbarui</span>
                                                            <span class="text-xs font-medium text-gray-700">
                                                                {{ $kategori->updated_at->format('d/m/Y H:i') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW ==================== -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($kategoriMustahik as $kategori)
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <!-- Header Card (klik untuk expand) -->
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $kategori->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Kategori Mustahik</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words pr-2">
                                            {{ $kategori->nama }}
                                        </h3>

                                        <!-- Badge Persentase -->
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            @if ($kategori->persentase_default)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                                                    {{ $kategori->persentase_formatted ?? $kategori->persentase_default . '%' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">
                                                    Persentase belum diatur
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('kategori-mustahik.edit', $kategori->uuid) }}"
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

                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $kategori->uuid }}" data-nama="{{ $kategori->nama }}">
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
                            <div id="detail-mobile-{{ $kategori->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <!-- Kriteria Penerima -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Kriteria Penerima
                                        </h4>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            @if ($kategori->kriteria)
                                                <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                                                    {{ $kategori->kriteria }}
                                                </p>
                                            @else
                                                <p class="text-sm text-gray-400 italic">Tidak ada kriteria</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Informasi Tambahan -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Informasi
                                        </h4>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Persentase Default</span>
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ $kategori->persentase_formatted ?? ($kategori->persentase_default ? $kategori->persentase_default . '%' : '-') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Dibuat</span>
                                                <span class="text-xs text-gray-600">
                                                    {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}
                                                </span>
                                            </div>
                                            @if ($kategori->updated_at && $kategori->updated_at->ne($kategori->created_at))
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-gray-500">Diperbarui</span>
                                                    <span class="text-xs text-gray-600">
                                                        {{ $kategori->updated_at->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($kategoriMustahik->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $kategoriMustahik->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    @if (request('search'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk "<span class="font-medium text-gray-700">{{ request('search') }}</span>"</p>
                        <button onclick="removeFilter('search')"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset pencarian
                        </button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data kategori mustahik</p>
                        <a href="{{ route('kategori-mustahik.create') }}"
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
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Kategori Mustahik</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus "<span id="modal-kategori-name" class="font-semibold text-gray-700"></span>"?
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

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }
        .rotate-180 {
            transform: rotate(180deg);
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scale-in {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }
        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }
    </style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

        // Filter Panel elements
        const filterButton = document.getElementById('filterButton');
        const filterPanel = document.getElementById('filterPanel');
        const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

        // Toggle filter panel
        if (filterButton && filterPanel) {
            filterButton.addEventListener('click', function() {
                filterPanel.classList.toggle('hidden');
            });
        }

        // Tutup filter panel
        if (closeFilterPanelBtn) {
            closeFilterPanelBtn.addEventListener('click', function() {
                filterPanel.classList.add('hidden');
            });
        }

        // Desktop Expandable row
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                // Jangan expand jika klik pada tombol aksi
                if (e.target.closest('.delete-btn') || e.target.closest('a')) return;
                
                const targetId = this.getAttribute('data-target');
                const targetRow = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon');
                
                if (targetRow) {
                    targetRow.classList.toggle('hidden');
                    if (icon) {
                        icon.classList.toggle('rotate-90');
                    }
                }
            });
        });

        // Mobile Expandable Cards
        document.querySelectorAll('.expandable-row-mobile').forEach(row => {
            row.addEventListener('click', function(e) {
                // Jangan expand jika klik pada tombol aksi
                if (e.target.closest('.delete-btn') || e.target.closest('a')) return;
                
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                
                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }
                }
            });
        });

        // Delete button handler
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const nama = this.getAttribute('data-nama');

                document.getElementById('modal-kategori-name').textContent = nama;
                deleteForm.action = `/kategori-mustahik/${uuid}`;
                deleteModal.classList.remove('hidden');
            });
        });

        // Cancel delete
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        }

        // Close modal when clicking outside
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
</script>
@endpush