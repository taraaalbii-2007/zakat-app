@extends('layouts.app')

@section('title', 'Kelola Kategori Mustahik')

@section('content')
    <div class="space-y-5">
        <!-- Container utama -->
        <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-soft transition-all duration-300">

            <!-- Header + Search + Button -->
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-neutral-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-neutral-800">Kategori Mustahik</h1>
                        <p class="text-xs sm:text-sm text-neutral-500 mt-0.5 sm:mt-1">Kelola 8 asnaf penerima zakat</p>
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
                                placeholder="Cari kategori mustahik..."
                                class="pl-9 pr-4 py-2 w-full sm:w-64 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                        </div>

                        <!-- Button Filter -->
                        <button type="button" id="filter-button"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline">Filter</span>
                        </button>

                        <a href="{{ route('kategori-mustahik.create') }}"
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
                    <div class="min-w-[150px] flex-1 sm:flex-none">
                        <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urut Berdasarkan</label>
                        <select id="filter-sort-by" name="sort_by"
                            class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                            <option value="nama" {{ request('sort_by', 'nama') === 'nama' ? 'selected' : '' }}>Nama</option>
                            <option value="persentase_default" {{ request('sort_by') === 'persentase_default' ? 'selected' : '' }}>Persentase</option>
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                        </select>
                    </div>
                    <div class="min-w-[130px] flex-1 sm:flex-none">
                        <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urutan</label>
                        <select id="filter-sort-order" name="sort_order"
                            class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                            <option value="asc" {{ request('sort_order', 'asc') === 'asc' ? 'selected' : '' }}>Menaik (A-Z)</option>
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                        </select>
                    </div>
                    
                    <!-- Tombol Terapkan Filter -->
                    <div class="flex items-center gap-2">
                        <button type="button" id="apply-filter-btn"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Terapkan
                        </button>
                        
                        @if (request('sort_by') || request('sort_order'))
                            <a href="{{ route('kategori-mustahik.index', request('search') ? ['search' => request('search')] : []) }}"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm text-neutral-500 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Total + Active Filters -->
            <div class="px-4 sm:px-6 py-3 border-b border-neutral-100 bg-neutral-50/30">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-neutral-600">Total:</span>
                        <span class="text-sm font-semibold text-neutral-800">{{ $kategoriMustahik->total() }}</span>
                        <span class="text-sm text-neutral-500">kategori mustahik</span>
                    </div>
                    @if (request('search'))
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-neutral-400">Filter aktif:</span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                "{{ request('search') }}"
                                <button onclick="removeFilter('search')" class="hover:text-primary-900 transition-colors">×</button>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabel dengan Expandable Row -->
            @if ($kategoriMustahik->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-neutral-200 bg-neutral-50">
                                <th class="px-4 py-4 text-center text-sm font-semibold text-neutral-700 w-10"></th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">NAMA</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">PERSENTASE DEFAULT</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-neutral-700 w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategoriMustahik as $kategori)
                                <!-- Baris Utama -->
                                <tr class="border-b border-neutral-100 hover:bg-primary-50/20 transition-all duration-200 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $kategori->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-neutral-400 transform transition-transform duration-200 expand-icon inline-block" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-neutral-800 group-hover:text-primary-600 transition-colors duration-200">
                                            {{ $kategori->nama }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm {{ $kategori->persentase_default !== null ? 'font-semibold text-neutral-800' : 'text-neutral-400' }}">
                                            {{ $kategori->persentase_formatted ?? ($kategori->persentase_default ? $kategori->persentase_default . '%' : '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                            data-uuid="{{ $kategori->uuid }}" data-nama="{{ $kategori->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Baris Expandable Desktop -->
                                <tr id="detail-{{ $kategori->uuid }}" class="hidden border-b border-neutral-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-neutral-50/50"></td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50" colspan="2">
                                        <div class="space-y-3">
                                            <div>
                                                <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Kriteria Penerima</h4>
                                                @if ($kategori->kriteria)
                                                    <p class="text-sm text-neutral-600 whitespace-pre-line">{{ $kategori->kriteria }}</p>
                                                @else
                                                    <p class="text-sm text-neutral-400 italic">Tidak ada kriteria</p>
                                                @endif
                                            </div>
                                            <div class="pt-2 text-xs text-neutral-400 space-y-0.5 border-t border-neutral-200">
                                                <div>Dibuat: {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}</div>
                                                @if ($kategori->updated_at && $kategori->updated_at->ne($kategori->created_at))
                                                    <div>Diperbarui: {{ $kategori->updated_at->format('d/m/Y H:i') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50 text-center">
                                        <a href="{{ route('kategori-mustahik.edit', $kategori->uuid) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW ==================== -->
                <div class="block md:hidden divide-y divide-neutral-100">
                    @foreach ($kategoriMustahik as $kategori)
                        <div class="p-4 hover:bg-primary-50/20 transition-all duration-200">
                            <!-- Header Card (klik untuk expand) -->
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $kategori->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-neutral-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-neutral-400">Kategori Mustahik</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-neutral-800 break-words pr-2">
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
                                    
                                    <!-- Dropdown Button -->
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                            class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                            data-uuid="{{ $kategori->uuid }}" data-nama="{{ $kategori->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $kategori->uuid }}" class="hidden mt-3 pt-3 border-t border-neutral-100">
                                <div class="space-y-4">
                                    <!-- Kriteria Penerima -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Kriteria Penerima</h4>
                                        <div class="bg-neutral-50 rounded-lg p-3">
                                            @if ($kategori->kriteria)
                                                <p class="text-sm text-neutral-600 whitespace-pre-line">{{ $kategori->kriteria }}</p>
                                            @else
                                                <p class="text-sm text-neutral-400 italic">Tidak ada kriteria</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Informasi Tambahan -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Informasi</h4>
                                        <div class="bg-neutral-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Persentase Default</span>
                                                <span class="text-xs font-medium text-neutral-700">
                                                    {{ $kategori->persentase_formatted ?? ($kategori->persentase_default ? $kategori->persentase_default . '%' : '-') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Dibuat</span>
                                                <span class="text-xs text-neutral-600">{{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}</span>
                                            </div>
                                            @if ($kategori->updated_at && $kategori->updated_at->ne($kategori->created_at))
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-neutral-500">Diperbarui</span>
                                                    <span class="text-xs text-neutral-600">{{ $kategori->updated_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi Mobile -->
                                    <div class="flex gap-2 pt-2">
                                        <a href="{{ route('kategori-mustahik.edit', $kategori->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <button type="button"
                                            onclick="showDeleteModal('{{ $kategori->uuid }}', '{{ addslashes($kategori->nama) }}')"
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
                @if ($kategoriMustahik->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-neutral-200 bg-neutral-50/30">
                        {{ $kategoriMustahik->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-14 text-center animate-fade-in">
                    <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    @if (request('search'))
                        <p class="text-sm text-neutral-500">Tidak ada hasil untuk "<span class="font-medium text-neutral-700">{{ request('search') }}</span>"</p>
                        <button onclick="removeFilter('search')" class="mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Reset pencarian</button>
                    @else
                        <p class="text-sm text-neutral-500">Belum ada data kategori mustahik</p>
                        <a href="{{ route('kategori-mustahik.create') }}" class="inline-block mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Tambah data</a>
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
                <h3 class="text-lg font-semibold text-neutral-900 mb-2 text-center">Hapus Kategori Mustahik</h3>
                <p class="text-sm text-neutral-500 mb-5 text-center">Yakin ingin menghapus "<span id="modal-kategori-name" class="font-semibold text-neutral-700"></span>"?</p>
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
    const editBaseUrl = "{{ rtrim(route('kategori-mustahik.index'), '/') }}";

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

        // Filter dengan tombol Terapkan
        const filterSortBy = document.getElementById('filter-sort-by');
        const filterSortOrder = document.getElementById('filter-sort-order');
        const applyFilterBtn = document.getElementById('apply-filter-btn');

        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', function() {
                const url = new URL(window.location.href);
                const sortBy = filterSortBy ? filterSortBy.value : 'nama';
                const sortOrder = filterSortOrder ? filterSortOrder.value : 'asc';
                
                if (sortBy && sortBy !== 'nama') {
                    url.searchParams.set('sort_by', sortBy);
                } else {
                    url.searchParams.delete('sort_by');
                }
                
                if (sortOrder && sortOrder !== 'asc') {
                    url.searchParams.set('sort_order', sortOrder);
                } else {
                    url.searchParams.delete('sort_order');
                }
                
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            });
        }

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
                const kategoriName = toggle.getAttribute('data-nama');

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

                currentDropdownData = { uuid: dropdownUuid, name: kategoriName };
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
            document.getElementById('modal-kategori-name').textContent = currentDropdownData.name;
            deleteForm.action = `/kategori-mustahik/${currentDropdownData.uuid}`;
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
        const searchInput = document.getElementById('search-input');
        if (searchInput && filterName === 'search') searchInput.value = '';
        window.location.href = url.toString();
    }

    function showDeleteModal(uuid, nama) {
        document.getElementById('modal-kategori-name').textContent = nama;
        document.getElementById('delete-form').action = `/kategori-mustahik/${uuid}`;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
</script>
@endpush