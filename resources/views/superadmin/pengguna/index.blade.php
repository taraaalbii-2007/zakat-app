{{-- resources/views/superadmin/pengguna/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Pengguna</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $pengguna->total() }} Pengguna</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        {{-- Tambah Pengguna --}}
                        <a href="{{ route('pengguna.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Tambah Pengguna
                            </span>
                        </a>

                        {{-- Filter --}}
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

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('pengguna.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach(['peran','status','masjid_id'] as $f)
                                    @if(request($f))
                                        <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
                                    @endif
                                @endforeach
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                        placeholder="Cari email, username..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filter-panel"
                class="{{ (request('peran') || request('status') || request('masjid_id')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('pengguna.index') }}" id="filter-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        {{-- Filter Peran --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Peran</label>
                            <select name="peran"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Peran</option>
                                <option value="superadmin"   {{ request('peran') === 'superadmin'   ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin_masjid" {{ request('peran') === 'admin_masjid' ? 'selected' : '' }}>Admin Masjid</option>
                                <option value="amil"         {{ request('peran') === 'amil'         ? 'selected' : '' }}>Amil</option>
                            </select>
                        </div>
                        {{-- Filter Status --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        {{-- Filter Masjid --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Masjid</label>
                            <select name="masjid_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Masjid</option>
                                @foreach($masjidList as $masjid)
                                    <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>
                                        {{ $masjid->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(request('peran') || request('status') || request('masjid_id'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('pengguna.index', request('q') ? ['q' => request('q')] : []) }}"
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

            @if ($pengguna->count() > 0)
                {{-- ── Desktop Table ── --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masjid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pengguna as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center">
                                                <span class="text-sm font-semibold text-primary">
                                                    {{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->username ?? '-' }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                @if($user->is_google_user)
                                                    <span class="inline-flex items-center text-xs text-blue-600 mt-0.5">
                                                        <svg class="w-3 h-3 mr-0.5" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                                        </svg>
                                                        Google
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {!! $user->peran !!}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $user->masjid->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {!! $user->status_badge !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button type="button"
                                            data-dropdown-toggle="{{ $user->uuid }}"
                                            data-nama="{{ $user->username ?? $user->email }}"
                                            data-is-self="{{ $user->id === auth()->id() ? '1' : '0' }}"
                                            data-is-active="{{ $user->is_active ? '1' : '0' }}"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── Mobile Cards ── --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($pengguna as $user)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="text-sm font-semibold text-primary">
                                            {{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $user->username ?? '-' }}
                                        </h3>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            {!! $user->peran_badge !!}
                                            {!! $user->status_badge !!}
                                        </div>
                                        @if($user->masjid)
                                            <p class="text-xs text-gray-500 mt-1.5">{{ $user->masjid->nama }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button type="button"
                                    data-dropdown-toggle="{{ $user->uuid }}"
                                    data-nama="{{ $user->username ?? $user->email }}"
                                    data-is-self="{{ $user->id === auth()->id() ? '1' : '0' }}"
                                    data-is-active="{{ $user->is_active ? '1' : '0' }}"
                                    class="dropdown-toggle flex-shrink-0 ml-2 inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($pengguna->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $pengguna->links() }}
                    </div>
                @endif

            @else
                {{-- ── Empty State ── --}}
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    @if(request('q') || request('peran') || request('status') || request('masjid_id'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if(request('q'))
                                Tidak ada pengguna yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada pengguna yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('pengguna.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Pengguna</h3>
                        <p class="text-sm text-gray-500 mb-6">Tambahkan pengguna pertama untuk sistem ini.</p>
                        <a href="{{ route('pengguna.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Pengguna
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── Dropdown Context Menu ── --}}
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#" id="dropdown-detail-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button type="button" id="dropdown-toggle-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <span id="dropdown-toggle-label">Toggle Status</span>
                </button>
                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── Delete Modal ── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 text-center">Hapus Pengguna</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Hapus pengguna "<span id="modal-pengguna-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="w-24 sm:w-28 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentUuid = null;
    let currentNama = null;

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownContainer = document.getElementById('dropdown-container');
        const detailLink        = document.getElementById('dropdown-detail-link');
        const editLink          = document.getElementById('dropdown-edit-link');
        const toggleBtn         = document.getElementById('dropdown-toggle-btn');
        const toggleLabel       = document.getElementById('dropdown-toggle-label');
        const deleteBtn         = document.getElementById('dropdown-delete-btn');
        const tableContainer    = document.getElementById('table-container');

        // ── Dropdown ─────────────────────────────────────────────────────────
        document.addEventListener('click', function (e) {
            const toggle = e.target.closest('.dropdown-toggle');

            if (toggle) {
                e.stopPropagation();

                const uuid     = toggle.getAttribute('data-dropdown-toggle');
                const nama     = toggle.getAttribute('data-nama');
                const isSelf   = toggle.getAttribute('data-is-self') === '1';
                const isActive = toggle.getAttribute('data-is-active') === '1';

                if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                    !dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                    return;
                }

                currentUuid = uuid;
                currentNama = nama;
                dropdownContainer.setAttribute('data-current-uuid', uuid);

                // Posisi dropdown
                const rect      = toggle.getBoundingClientRect();
                const dropdownW = window.innerWidth < 640 ? 176 : 192;
                const dropdownH = 160;
                let top  = rect.bottom + window.scrollY;
                let left = rect.left + window.scrollX;

                if (rect.left + dropdownW > window.innerWidth) {
                    left = window.innerWidth - dropdownW - 10 + window.scrollX;
                }
                if (rect.bottom + dropdownH > window.innerHeight) {
                    top = rect.top - dropdownH + window.scrollY;
                }

                dropdownContainer.style.top  = top + 'px';
                dropdownContainer.style.left = left + 'px';

                detailLink.href = `/pengguna/${uuid}`;
                editLink.href   = `/pengguna/${uuid}/edit`;

                // Toggle status label
                toggleLabel.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
                toggleBtn.classList.toggle('hidden', isSelf);
                deleteBtn.classList.toggle('hidden', isSelf);

                dropdownContainer.classList.remove('hidden');

            } else if (!dropdownContainer.contains(e.target)) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        });

        // ── Toggle Status ─────────────────────────────────────────────────────
        toggleBtn.addEventListener('click', function () {
            if (!currentUuid) return;
            dropdownContainer.classList.add('hidden');

            const form    = document.createElement('form');
            form.method   = 'POST';
            form.action   = `/pengguna/${currentUuid}/toggle-status`;

            const csrf    = document.createElement('input');
            csrf.type     = 'hidden';
            csrf.name     = '_token';
            csrf.value    = '{{ csrf_token() }}';

            const method  = document.createElement('input');
            method.type   = 'hidden';
            method.name   = '_method';
            method.value  = 'PATCH';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });

        // ── Delete ────────────────────────────────────────────────────────────
        deleteBtn.addEventListener('click', function () {
            if (!currentUuid) return;
            dropdownContainer.classList.add('hidden');
            document.getElementById('modal-pengguna-name').textContent = currentNama;
            document.getElementById('delete-modal').classList.remove('hidden');
        });

        document.getElementById('confirm-delete-btn').addEventListener('click', function () {
            if (!currentUuid) return;
            const form    = document.createElement('form');
            form.method   = 'POST';
            form.action   = `/pengguna/${currentUuid}`;

            const csrf    = document.createElement('input');
            csrf.type     = 'hidden';
            csrf.name     = '_token';
            csrf.value    = '{{ csrf_token() }}';

            const method  = document.createElement('input');
            method.type   = 'hidden';
            method.name   = '_method';
            method.value  = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });

        document.getElementById('cancel-delete-btn').addEventListener('click', () =>
            document.getElementById('delete-modal').classList.add('hidden')
        );
        document.getElementById('delete-modal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // ── Close on scroll / resize ──────────────────────────────────────────
        const closeDropdown = () => {
            if (!dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        };
        window.addEventListener('scroll', closeDropdown, true);
        window.addEventListener('resize', closeDropdown);
        tableContainer?.addEventListener('scroll', closeDropdown, true);
    });

    // ── Search / Filter helpers ───────────────────────────────────────────────
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
            if (!'{{ request('q') }}') input.value = '';
            form.classList.add('hidden');
            btn.classList.remove('hidden');
            container.style.minWidth = 'auto';
        }
    }

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }
</script>
@endpush