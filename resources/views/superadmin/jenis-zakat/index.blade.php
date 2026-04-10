@extends('layouts.app')

@section('title', 'Kelola Jenis Zakat')

@section('content')
    <div class="space-y-5">
        <!-- Container utama -->
        <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-soft transition-all duration-300">

            <!-- Header + Search + Button -->
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-neutral-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-neutral-800">Jenis Zakat</h1>
                        <p class="text-xs sm:text-sm text-neutral-500 mt-0.5 sm:mt-1">Kelola dan konfigurasi jenis zakat</p>
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
                                placeholder="Cari jenis zakat..."
                                class="pl-9 pr-4 py-2 w-full sm:w-64 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                        </div>
                        <a href="{{ route('jenis-zakat.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-soft hover:shadow-md active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total tanpa badge -->
            <div class="px-4 sm:px-6 py-3 border-b border-neutral-100 bg-neutral-50/30">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-neutral-600">Total:</span>
                        <span class="text-sm font-semibold text-neutral-800">{{ $jenisZakat->total() }}</span>
                        <span class="text-sm text-neutral-500">jenis zakat</span>
                    </div>
                    @if (request('search'))
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-neutral-400">Filter:</span>
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                "{{ request('search') }}"
                                <button onclick="removeFilter('search')"
                                    class="hover:text-primary-900 transition-colors">×</button>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabel - Responsive untuk mobile -->
            @if ($jenisZakat->count() > 0)
                <!-- Desktop Table (hidden di mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-neutral-200 bg-neutral-50">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">
                                    NO
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">
                                    NAMA JENIS ZAKAT
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-neutral-700 w-24">
                                    AKSI
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenisZakat as $index => $zakat)
                                <tr
                                    class="border-b border-neutral-100 hover:bg-primary-50/20 transition-all duration-200 group">
                                    <td class="px-6 py-4 text-sm text-neutral-600">
                                        {{ $jenisZakat->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-medium text-neutral-800 group-hover:text-primary-600 transition-colors duration-200">
                                            {{ $zakat->nama }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block">
                                            <button type="button"
                                                class="dropdown-toggle p-1.5 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                                data-uuid="{{ $zakat->uuid }}" data-nama="{{ $zakat->nama }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (muncul di mobile, hidden di desktop) -->
                <div class="block md:hidden divide-y divide-neutral-100">
                    @foreach ($jenisZakat as $index => $zakat)
                        <div class="p-4 hover:bg-primary-50/20 transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="text-xs text-neutral-400">#{{ $jenisZakat->firstItem() + $index }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-neutral-800 break-words">
                                        {{ $zakat->nama }}
                                    </h3>
                                </div>
                                <div class="relative inline-block flex-shrink-0">
                                    <button type="button"
                                        class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                        data-uuid="{{ $zakat->uuid }}" data-nama="{{ $zakat->nama }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($jenisZakat->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-neutral-200 bg-neutral-50/30">
                        {{ $jenisZakat->links() }}
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
                    @if (request('search'))
                        <p class="text-sm text-neutral-500">Tidak ada hasil untuk "<span
                                class="font-medium text-neutral-700">{{ request('search') }}</span>"</p>
                        <button onclick="removeFilter('search')"
                            class="mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Reset
                            pencarian</button>
                    @else
                        <p class="text-sm text-neutral-500">Belum ada data jenis zakat</p>
                        <a href="{{ route('jenis-zakat.create') }}"
                            class="inline-block mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Tambah
                            data</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdown-container" class="fixed hidden z-50 animate-scale-in">
        <div class="w-36 bg-white rounded-lg shadow-lg border border-neutral-100 overflow-hidden">
            <div class="py-1">
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/30 hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div
            class="bg-white rounded-xl max-w-sm w-full shadow-modal transform transition-all duration-300 animate-scale-in">
            <div class="p-5">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2 text-center">Hapus Jenis Zakat</h3>
                <p class="text-sm text-neutral-500 mb-5 text-center">
                    Yakin ingin menghapus "<span id="modal-zakat-name" class="font-semibold text-neutral-700"></span>"?
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-all duration-200">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium text-white transition-all duration-200 active:scale-95">
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
        let currentDropdownData = null;
        let searchTimeout = null;
        const editBaseUrl = "{{ rtrim(route('jenis-zakat.index'), '/') }}";

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const deleteForm = document.getElementById('delete-form');

            // Search dengan auto-submit
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const searchValue = this.value;

                    searchTimeout = setTimeout(() => {
                        const url = new URL(window.location.href);
                        if (searchValue) {
                            url.searchParams.set('search', searchValue);
                        } else {
                            url.searchParams.delete('search');
                        }
                        url.searchParams.set('page', '1');
                        window.location.href = url.toString();
                    }, 400);
                });
            }

            // Dropdown logic
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');
                if (toggle) {
                    e.stopPropagation();
                    const dropdownUuid = toggle.getAttribute('data-uuid');
                    const zakatName = toggle.getAttribute('data-nama');

                    if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid &&
                        !dropdownContainer.classList.contains('hidden')) {
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
                        if (left + dropdownWidth > window.innerWidth - 10) left = window
                            .innerWidth - dropdownWidth - 10;
                        if (rect.bottom + dropdownHeight > window.innerHeight) top = rect.top -
                            dropdownHeight - 6;
                        if (top < 6) top = 6;

                        dropdownContainer.style.top = top + 'px';
                        dropdownContainer.style.left = left + 'px';
                        dropdownContainer.style.visibility = '';
                    });

                    currentDropdownData = {
                        uuid: dropdownUuid,
                        name: zakatName
                    };
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
                deleteForm.action = `/jenis-zakat/${currentDropdownData.uuid}`;
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
            if (searchInput) searchInput.value = '';
            window.location.href = url.toString();
        }
    </script>
@endpush
