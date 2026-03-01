@extends('layouts.app')

@section('title', 'Kelola Bulletin')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Bulletin</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $bulletins->total() }} Bulletin</p>
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
                        @if(request('q') || request('kategori'))
                            <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-primary rounded-full">
                                {{ collect([request('q'), request('kategori')])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Tombol Buat --}}
                    <a href="{{ route('superadmin.bulletin.create') }}"
                       class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Buat Bulletin</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div id="filter-panel" class="{{ request('q') || request('kategori') ? '' : 'hidden' }} border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('superadmin.bulletin.index') }}" class="p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Cari Bulletin</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="search" name="q" value="{{ request('q') }}"
                                   placeholder="Cari judul, konten, atau lokasi..."
                                   class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action --}}
                    <div class="md:col-span-2 flex gap-2 pt-2">
                        <a href="{{ route('superadmin.bulletin.index') }}"
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

        @if($bulletins->count() > 0)

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulletin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bulletins as $bulletin)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Thumbnail --}}
                                        <div class="w-10 h-10 rounded-md bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                            @if($bulletin->thumbnail)
                                                <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('superadmin.bulletin.show', $bulletin->uuid) }}"
                                               class="text-sm font-medium text-gray-800 hover:text-primary-600 line-clamp-2 leading-snug">
                                                {{ $bulletin->judul }}
                                            </a>
                                            <div class="mt-1 text-xs text-gray-500 truncate">
                                                {{ $bulletin->author->username ?? 'Admin' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($bulletin->kategoriBulletin)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $bulletin->kategoriBulletin->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($bulletin->lokasi)
                                        <div class="flex items-center gap-1 text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate max-w-[120px]" title="{{ $bulletin->lokasi }}">{{ $bulletin->lokasi }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-900">
                                        {{ ($bulletin->published_at ?? $bulletin->created_at)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ ($bulletin->published_at ?? $bulletin->created_at)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1 text-xs text-gray-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ number_format($bulletin->view_count) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button type="button"
                                            data-dropdown-toggle="{{ $bulletin->uuid }}"
                                            data-judul="{{ $bulletin->judul }}"
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
                @foreach($bulletins as $bulletin)
                    <div class="p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-md bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                        @if($bulletin->thumbnail)
                                            <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="text-xs font-medium text-gray-800 line-clamp-2 leading-tight">
                                        {{ $bulletin->judul }}
                                    </h3>
                                </div>
                                <div class="pl-10 space-y-1">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        @if($bulletin->kategoriBulletin)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-gray-100 text-gray-700">
                                                {{ $bulletin->kategoriBulletin->nama_kategori }}
                                            </span>
                                        @endif
                                        @if($bulletin->lokasi)
                                            <span class="flex items-center gap-0.5 text-[10px] text-gray-500">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ Str::limit($bulletin->lokasi, 20) }}
                                            </span>
                                        @endif
                                        <span class="flex items-center gap-0.5 text-[10px] text-gray-500">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($bulletin->view_count) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400">
                                        <span>{{ $bulletin->author->username ?? 'Admin' }}</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ ($bulletin->published_at ?? $bulletin->created_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                    data-dropdown-toggle="{{ $bulletin->uuid }}"
                                    data-judul="{{ $bulletin->judul }}"
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
            @if($bulletins->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $bulletins->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                @if(request('q') || request('kategori'))
                    <h3 class="text-base font-medium text-gray-900 mb-2">Bulletin Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">Tidak ada bulletin yang cocok dengan filter yang diterapkan.</p>
                    <a href="{{ route('superadmin.bulletin.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        Reset Filter
                    </a>
                @else
                    <h3 class="text-base font-medium text-gray-900 mb-2">Belum Ada Bulletin</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai dengan membuat bulletin pertama.</p>
                    <a href="{{ route('superadmin.bulletin.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Buat Bulletin
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
            <a href="#" id="dropdown-view-link"
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>
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
        <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Bulletin</h3>
        <p class="text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus bulletin
            "<span id="modal-bulletin-name" class="font-semibold text-gray-700"></span>"?
        </p>
        <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" id="cancel-delete-btn"
                    class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" id="confirm-delete-btn"
                    class="px-5 py-2 rounded-lg bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
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
    const viewLink          = document.getElementById('dropdown-view-link');
    const editLink          = document.getElementById('dropdown-edit-link');
    const deleteBtn         = document.getElementById('dropdown-delete-btn');

    // ---- Dropdown toggle ----
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('.dropdown-toggle');

        if (toggle) {
            e.stopPropagation();
            const uuid  = toggle.getAttribute('data-dropdown-toggle');
            const judul = toggle.getAttribute('data-judul');

            if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                !dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
                return;
            }

            dropdownContainer.setAttribute('data-current-uuid', uuid);

            const rect          = toggle.getBoundingClientRect();
            const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
            const dropdownHeight = 144;

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

            viewLink.href = `/bulletin/${uuid}`.replace('bulletin', '{{ Route::has("superadmin.bulletin.show") ? "bulletin" : "bulletin" }}');
            viewLink.href = '{{ route("superadmin.bulletin.index") }}'.replace(/bulletin$/, '') + 'bulletin/' + uuid;
            editLink.href = '{{ route("superadmin.bulletin.index") }}'.replace(/bulletin$/, '') + 'bulletin/' + uuid + '/edit';

            currentDropdownData = { uuid, name: judul };
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

        document.getElementById('modal-bulletin-name').textContent = currentDropdownData.name;
        document.getElementById('delete-modal').classList.remove('hidden');
    });

    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (!currentDropdownData) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("bulletin") }}/' + currentDropdownData.uuid;

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