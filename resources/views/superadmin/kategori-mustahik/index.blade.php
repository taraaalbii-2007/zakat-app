@extends('layouts.app')

@section('title', 'Kelola Kategori Mustahik')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Kategori Mustahik</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ $kategoriMustahik->total() }} Kategori (8 Asnaf)
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('kategori-mustahik.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2">Tambah</span>
                        </a>

                        {{-- Tombol Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('search') ? 'min-width:280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('search') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('kategori-mustahik.index') }}" id="search-form"
                                class="{{ request('search') ? '' : 'hidden' }}">
                                @if(request('sort_by'))
                                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                @endif
                                @if(request('sort_order'))
                                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                @endif
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="search" name="search" id="search-input"
                                        value="{{ request('search') }}"
                                        placeholder="Cari nama atau kriteria..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ (request('sort_by') || request('sort_order')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('kategori-mustahik.index') }}" id="filter-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Urut Berdasarkan</label>
                            <select name="sort_by" id="filter-sort-by"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="nama"               {{ request('sort_by', 'nama') === 'nama'               ? 'selected' : '' }}>Nama</option>
                                <option value="persentase_default" {{ request('sort_by') === 'persentase_default'          ? 'selected' : '' }}>Persentase</option>
                                <option value="created_at"         {{ request('sort_by') === 'created_at'                  ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Urutan</label>
                            <select name="sort_order" id="filter-sort-order"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="asc"  {{ request('sort_order', 'asc') === 'asc'  ? 'selected' : '' }}>Menaik (A–Z)</option>
                                <option value="desc" {{ request('sort_order') === 'desc'          ? 'selected' : '' }}>Menurun (Z–A)</option>
                            </select>
                        </div>
                    </div>
                    @if(request('sort_by') || request('sort_order'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('kategori-mustahik.index', request('search') ? ['search' => request('search')] : []) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Badge filter aktif --}}
            @if(request('search'))
                <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                    <div class="flex items-center flex-wrap gap-2">
                        <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            Pencarian: "{{ request('search') }}"
                            <button type="button" onclick="removeFilter('search')"
                                class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                        </span>
                    </div>
                </div>
            @endif

            @if ($kategoriMustahik->count() > 0)

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Persentase Default
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($kategoriMustahik as $kategori)
                                {{-- Row utama (expandable) --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                    data-target="detail-{{ $kategori->uuid }}">
                                    <td class="px-4 py-4">
                                        <button type="button" class="p-1 rounded-lg hover:bg-gray-100 transition-all">
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $kategori->nama }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Klik untuk melihat kriteria</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{-- PERBAIKAN: gunakan accessor dari model, bukan inline logic --}}
                                        <span class="text-sm {{ $kategori->persentase_default !== null ? 'text-gray-900' : 'text-gray-400' }}">
                                            {{ $kategori->persentase_formatted }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $kategori->uuid }}"
                                            data-nama="{{ $kategori->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Row detail (expandable content) --}}
                                <tr id="detail-{{ $kategori->uuid }}" class="hidden expandable-content">
                                    <td colspan="4" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100 px-6 py-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Kriteria Penerima</h4>
                                            @if($kategori->kriteria)
                                                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $kategori->kriteria }}</p>
                                            @else
                                                <p class="text-sm text-gray-400 italic">Tidak ada kriteria</p>
                                            @endif

                                            <div class="mt-4 pt-4 border-t border-gray-200 text-xs text-gray-400 space-y-1">
                                                <div>Dibuat: {{ $kategori->created_at->format('d/m/Y H:i') }}</div>
                                                @if($kategori->updated_at->ne($kategori->created_at))
                                                    <div>Diperbarui: {{ $kategori->updated_at->format('d/m/Y H:i') }}</div>
                                                @endif
                                            </div>

                                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end gap-2">
                                                <a href="{{ route('kategori-mustahik.edit', $kategori->uuid) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($kategoriMustahik as $kategori)
                        <div>
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $kategori->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $kategori->nama }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $kategori->persentase_formatted }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $kategori->uuid }}"
                                            data-nama="{{ $kategori->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div id="detail-mobile-{{ $kategori->uuid }}" class="hidden">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-3">
                                    @if($kategori->kriteria)
                                        <div>
                                            <h4 class="text-xs font-medium text-gray-700 mb-1">Kriteria</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($kategori->kriteria, 120) }}</p>
                                        </div>
                                    @endif
                                    <div class="pt-3 border-t border-gray-200 flex gap-2">
                                        <a href="{{ route('kategori-mustahik.edit', $kategori->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($kategoriMustahik->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $kategoriMustahik->links() }}
                    </div>
                @endif

            @else
                {{-- Empty state --}}
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    @if(request('search'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Tidak ada kategori mustahik yang cocok dengan "{{ request('search') }}"
                        </p>
                        <a href="{{ route('kategori-mustahik.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Kategori Mustahik</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan 8 asnaf sesuai QS. At-Taubah: 60.</p>
                        <a href="{{ route('kategori-mustahik.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Kategori Mustahik
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
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Kategori Mustahik</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus
                "<span id="modal-kategori-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-28 rounded-lg border border-gray-300 px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // PERBAIKAN: URL dropdown menggunakan route() via data-attribute yang di-set dari Blade,
    // bukan hardcode string URL di JS

    let currentDropdownData = null;

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownContainer = document.getElementById('dropdown-container');
        const editLink          = document.getElementById('dropdown-edit-link');
        const deleteBtn         = document.getElementById('dropdown-delete-btn');
        const deleteForm        = document.getElementById('delete-form');
        const tableContainer    = document.getElementById('table-container');
        const filterForm        = document.getElementById('filter-form');

        // Auto-submit filter saat select berubah
        ['filter-sort-by', 'filter-sort-order'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', () => filterForm.submit());
        });

        // Desktop: expandable rows
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, .dropdown-toggle, button[type="submit"]')) return;
                const target = document.getElementById(this.dataset.target);
                const icon   = this.querySelector('.expand-icon');
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-90');
            });
        });

        // Mobile: expandable cards
        document.querySelectorAll('.expandable-row-mobile').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, .dropdown-toggle, button[type="submit"]')) return;
                const target = document.getElementById(this.dataset.target);
                const icon   = this.querySelector('.expand-icon-mobile');
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        // Dropdown toggle
        document.addEventListener('click', function (e) {
            const toggle = e.target.closest('.dropdown-toggle');
            if (toggle) {
                e.stopPropagation();
                const uuid = toggle.dataset.uuid;
                const nama = toggle.dataset.nama;

                // Toggle tutup jika klik pada item yang sama
                if (dropdownContainer.dataset.currentUuid === uuid &&
                    !dropdownContainer.classList.contains('hidden')) {
                    closeDropdown();
                    return;
                }

                dropdownContainer.dataset.currentUuid = uuid;
                currentDropdownData = { uuid, nama };

                // Posisikan dropdown
                const rect          = toggle.getBoundingClientRect();
                const dropdownW     = window.innerWidth < 640 ? 176 : 192;
                const dropdownH     = 96;
                let top  = rect.bottom + window.scrollY;
                let left = rect.left + window.scrollX;

                if (rect.left + dropdownW > window.innerWidth) left = window.innerWidth - dropdownW - 10;
                if (rect.bottom + dropdownH > window.innerHeight) top = rect.top - dropdownH + window.scrollY;

                dropdownContainer.style.top  = top + 'px';
                dropdownContainer.style.left = left + 'px';

                // PERBAIKAN: gunakan route name dengan UUID dari data-attribute, bukan hardcode path
                editLink.href = '{{ url("kategori-mustahik") }}/' + uuid + '/edit';

                dropdownContainer.classList.remove('hidden');
            } else if (!dropdownContainer.contains(e.target)) {
                closeDropdown();
            }
        });

        // Hapus
        deleteBtn.addEventListener('click', function () {
            if (!currentDropdownData) return;
            closeDropdown();
            document.getElementById('modal-kategori-name').textContent = currentDropdownData.nama;
            // PERBAIKAN: sama — gunakan URL helper, bukan hardcode
            deleteForm.action = '{{ url("kategori-mustahik") }}/' + currentDropdownData.uuid;
            document.getElementById('delete-modal').classList.remove('hidden');
        });

        document.getElementById('cancel-delete-btn').addEventListener('click', () => {
            document.getElementById('delete-modal').classList.add('hidden');
        });
        document.getElementById('delete-modal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // Tutup dropdown saat scroll atau resize
        ['scroll', 'resize'].forEach(ev => window.addEventListener(ev, closeDropdown, true));
        tableContainer?.addEventListener('scroll', closeDropdown, true);

        function closeDropdown() {
            dropdownContainer.classList.add('hidden');
            delete dropdownContainer.dataset.currentUuid;
        }
    });

    function toggleSearch() {
        const btn       = document.getElementById('search-button');
        const form      = document.getElementById('search-form');
        const input     = document.getElementById('search-input');
        const container = document.getElementById('search-container');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            container.style.minWidth = '280px';
            setTimeout(() => input.focus(), 50);
        } else {
            form.classList.add('hidden');
            btn.classList.remove('hidden');
            container.style.minWidth = 'auto';
            input.value = '';
        }
    }

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

    function removeFilter(name) {
        const url = new URL(window.location.href);
        url.searchParams.delete(name);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
@endpush