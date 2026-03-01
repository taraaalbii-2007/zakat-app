@extends('layouts.app')

@section('title', 'Kelola Kategori Bulletin')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Kategori Bulletin</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $kategoriList->total() }} Kategori</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Tombol Filter --}}
                    <button type="button" onclick="toggleFilter()" id="filter-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        @if(request('q'))
                            <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-primary rounded-full">1</span>
                        @endif
                    </button>

                    {{-- Tombol Tambah --}}
                    <a href="{{ route('superadmin.kategori-bulletin.create') }}"
                       class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Tambah Kategori</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div id="filter-panel" class="{{ request('q') ? '' : 'hidden' }} border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('superadmin.kategori-bulletin.index') }}" class="p-4 sm:p-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Cari Kategori</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="search" name="q" value="{{ request('q') }}"
                                   placeholder="Cari nama kategori..."
                                   class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('superadmin.kategori-bulletin.index') }}"
                           class="flex-1 sm:flex-none px-4 py-2 text-sm text-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Reset
                        </a>
                        <button type="submit"
                                class="flex-1 sm:flex-none px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary-600 transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($kategoriList->count() > 0)

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Bulletin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($kategoriList as $index => $kategori)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $kategoriList->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $kategori->nama_kategori }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($kategori->bulletins_count > 0)
                                        <a href="{{ route('superadmin.bulletin.index', ['kategori' => $kategori->id]) }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ $kategori->bulletins_count }} bulletin
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            0 bulletin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-900">{{ $kategori->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $kategori->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button type="button"
                                            data-dropdown-toggle="{{ $kategori->uuid }}"
                                            data-nama="{{ $kategori->nama_kategori }}"
                                            data-count="{{ $kategori->bulletins_count }}"
                                            class="dropdown-toggle inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($kategoriList as $kategori)
                    <div class="p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-2.5 flex-1 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $kategori->nama_kategori }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] text-gray-400">{{ $kategori->created_at->format('d M Y') }}</span>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-[10px] {{ $kategori->bulletins_count > 0 ? 'text-blue-600 font-medium' : 'text-gray-400' }}">
                                            {{ $kategori->bulletins_count }} bulletin
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                    data-dropdown-toggle="{{ $kategori->uuid }}"
                                    data-nama="{{ $kategori->nama_kategori }}"
                                    data-count="{{ $kategori->bulletins_count }}"
                                    class="dropdown-toggle flex-shrink-0 ml-1.5 inline-flex items-center p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($kategoriList->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $kategoriList->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                @if(request('q'))
                    <h3 class="text-base font-medium text-gray-900 mb-2">Kategori Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">Tidak ada kategori yang cocok dengan filter yang diterapkan.</p>
                    <a href="{{ route('superadmin.kategori-bulletin.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        Reset Filter
                    </a>
                @else
                    <h3 class="text-base font-medium text-gray-900 mb-2">Belum Ada Kategori</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai dengan membuat kategori pertama.</p>
                    <a href="{{ route('superadmin.kategori-bulletin.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Kategori
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Dropdown Menu --}}
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
    <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl bg-white">
        <div class="flex justify-center mb-4">
            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Kategori</h3>
        <p class="text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus kategori
            "<span id="modal-kategori-name" class="font-semibold text-gray-700"></span>"?
        </p>

        {{-- Warning jika masih ada bulletin --}}
        <div id="modal-warning" class="hidden mt-3 mb-2 px-3 py-2.5 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-xs text-yellow-700 text-center">
                <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Kategori ini masih digunakan oleh <span id="modal-bulletin-count" class="font-semibold"></span> bulletin.
                Hapus atau pindahkan bulletin tersebut terlebih dahulu.
            </p>
        </div>

        <p id="modal-confirm-text" class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>

        <div class="flex justify-center gap-3">
            <button type="button" id="cancel-delete-btn"
                    class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" id="confirm-delete-btn"
                    class="px-5 py-2 rounded-lg bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                Hapus
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentDropdownData = null;

document.addEventListener('DOMContentLoaded', function () {
    const dropdownContainer = document.getElementById('dropdown-container');
    const editLink          = document.getElementById('dropdown-edit-link');
    const deleteBtn         = document.getElementById('dropdown-delete-btn');
    const baseUrl           = '{{ url("superadmin/kategori-bulletin") }}';

    // ---- Dropdown toggle ----
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('.dropdown-toggle');

        if (toggle) {
            e.stopPropagation();
            const uuid  = toggle.getAttribute('data-dropdown-toggle');
            const nama  = toggle.getAttribute('data-nama');
            const count = parseInt(toggle.getAttribute('data-count'));

            if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                !dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
                return;
            }

            dropdownContainer.setAttribute('data-current-uuid', uuid);

            const rect           = toggle.getBoundingClientRect();
            const dropdownWidth  = window.innerWidth < 640 ? 176 : 192;
            const dropdownHeight = 96;

            let top  = rect.bottom + window.scrollY;
            let left = rect.left + window.scrollX;

            if (left + dropdownWidth > window.innerWidth) {
                left = window.innerWidth - dropdownWidth - 10;
            }
            if (rect.bottom + dropdownHeight > window.innerHeight) {
                top = rect.top + window.scrollY - dropdownHeight;
            }

            dropdownContainer.style.top  = top + 'px';
            dropdownContainer.style.left = left + 'px';

            editLink.href = baseUrl + '/' + uuid + '/edit';

            currentDropdownData = { uuid, nama, count };
            dropdownContainer.classList.remove('hidden');

        } else if (!dropdownContainer.contains(e.target)) {
            dropdownContainer.classList.add('hidden');
            dropdownContainer.removeAttribute('data-current-uuid');
        }
    });

    // ---- Delete button ----
    deleteBtn.addEventListener('click', function () {
        if (!currentDropdownData) return;
        dropdownContainer.classList.add('hidden');
        dropdownContainer.removeAttribute('data-current-uuid');

        const { nama, count } = currentDropdownData;
        const warning     = document.getElementById('modal-warning');
        const countSpan   = document.getElementById('modal-bulletin-count');
        const confirmText = document.getElementById('modal-confirm-text');
        const confirmBtn  = document.getElementById('confirm-delete-btn');

        document.getElementById('modal-kategori-name').textContent = nama;

        if (count > 0) {
            warning.classList.remove('hidden');
            countSpan.textContent   = count;
            confirmText.textContent = '';
            confirmBtn.disabled     = true;
        } else {
            warning.classList.add('hidden');
            confirmText.textContent = 'Tindakan ini tidak dapat dibatalkan.';
            confirmBtn.disabled     = false;
        }

        document.getElementById('delete-modal').classList.remove('hidden');
    });

    // ---- Confirm delete ----
    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (!currentDropdownData || currentDropdownData.count > 0) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = baseUrl + '/' + currentDropdownData.uuid;

        const csrf = document.createElement('input');
        csrf.type  = 'hidden';
        csrf.name  = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type  = 'hidden';
        method.name  = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    });

    document.getElementById('cancel-delete-btn').addEventListener('click', function () {
        document.getElementById('delete-modal').classList.add('hidden');
    });

    document.getElementById('delete-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    window.addEventListener('scroll', function () {
        if (!dropdownContainer.classList.contains('hidden')) {
            dropdownContainer.classList.add('hidden');
            dropdownContainer.removeAttribute('data-current-uuid');
        }
    }, true);
});

function toggleFilter() {
    document.getElementById('filter-panel').classList.toggle('hidden');
}
</script>
@endpush