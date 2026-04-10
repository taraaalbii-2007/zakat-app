{{-- resources/views/superadmin/pengguna/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="space-y-5">
        <!-- Container utama -->
        <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-soft transition-all duration-300">

<!-- Header + Button (tanpa search) -->
<div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-neutral-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-neutral-800">Manajemen Pengguna</h1>
            <p class="text-xs sm:text-sm text-neutral-500 mt-0.5 sm:mt-1">Kelola dan konfigurasi pengguna sistem</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Button Filter -->
            <button type="button" id="filter-button"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-sm font-medium rounded-lg transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="hidden sm:inline">Filter</span>
            </button>

            <a href="{{ route('pengguna.create') }}"
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
    <form id="filter-form" method="GET" action="{{ route('pengguna.index') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Field -->
            <div>
                <label class="block text-xs font-medium text-neutral-600 mb-1.5">Cari Pengguna</label>
                <input type="text" id="filter-search" name="q" value="{{ request('q') }}"
                    placeholder="Cari email, username..."
                    class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
            </div>
            
            <!-- Filter Peran -->
            <div>
                <label class="block text-xs font-medium text-neutral-600 mb-1.5">Peran</label>
                <select name="peran" id="filter-peran"
                    class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                    <option value="">Semua Peran</option>
                    <option value="superadmin" {{ request('peran') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin_lembaga" {{ request('peran') === 'admin_lembaga' ? 'selected' : '' }}>Admin Lembaga</option>
                    <option value="amil" {{ request('peran') === 'amil' ? 'selected' : '' }}>Amil</option>
                    <option value="muzakki" {{ request('peran') === 'muzakki' ? 'selected' : '' }}>Muzakki</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-medium text-neutral-600 mb-1.5">Status</label>
                <select name="status" id="filter-status"
                    class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Filter Lembaga -->
            <div>
                <label class="block text-xs font-medium text-neutral-600 mb-1.5">Lembaga</label>
                <select name="lembaga_id" id="filter-lembaga"
                    class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                    <option value="">Semua Lembaga</option>
                    @foreach ($lembagaList as $lembaga)
                        <option value="{{ $lembaga->id }}" {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                            {{ $lembaga->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <!-- Sorting -->
            <div>
                <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urut Berdasarkan</label>
                <select name="sort_by" id="filter-sort-by"
                    class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                    <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                    <option value="username" {{ request('sort_by') === 'username' ? 'selected' : '' }}>Username</option>
                    <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urutan</label>
                <select name="sort_order" id="filter-sort-order"
                    class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                    <option value="asc" {{ request('sort_order', 'asc') === 'asc' ? 'selected' : '' }}>Menaik (A-Z)</option>
                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Menurun (Z-A)</option>
                </select>
            </div>

            <!-- Tombol Aksi Filter -->
            <div class="flex items-end gap-2 lg:col-span-2">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Terapkan Filter
                </button>
                
                @if (request('q') || request('peran') || request('status') || request('lembaga_id') || (request('sort_by') && request('sort_by') != 'created_at') || (request('sort_order') && request('sort_order') != 'asc'))
                    <a href="{{ route('pengguna.index') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-neutral-500 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Filter
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

            <!-- Total + Active Filters -->
            <div class="px-4 sm:px-6 py-3 border-b border-neutral-100 bg-neutral-50/30">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-neutral-600">Total:</span>
                        <span class="text-sm font-semibold text-neutral-800">{{ $pengguna->total() }}</span>
                        <span class="text-sm text-neutral-500">pengguna</span>
                    </div>
                    
                    <!-- Active Filters Badges -->
                    @if (request('q') || request('peran') || request('status') || request('lembaga_id') || request('sort_by') || request('sort_order'))
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-neutral-400">Filter aktif:</span>
                            
                            @if (request('q'))
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Pencarian: "{{ request('q') }}"
                                    <button onclick="removeFilter('q')" class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif
                            
                            @if (request('peran'))
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Peran: {{ ucfirst(str_replace('_', ' ', request('peran'))) }}
                                    <button onclick="removeFilter('peran')" class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif
                            
                            @if (request('status'))
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Status: {{ request('status') }}
                                    <button onclick="removeFilter('status')" class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif
                            
                            @if (request('lembaga_id'))
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Lembaga: {{ $lembagaList->find(request('lembaga_id'))?->nama }}
                                    <button onclick="removeFilter('lembaga_id')" class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif
                            
                            @if (request('sort_by') && request('sort_by') != 'created_at')
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Urutan: {{ request('sort_by') == 'username' ? 'Username' : 'Email' }} 
                                    ({{ request('sort_order') == 'asc' ? 'A-Z' : 'Z-A' }})
                                    <button onclick="removeFilter('sort_by'); removeFilter('sort_order')" class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif
                            
                            <button onclick="resetAllFilters()" class="text-xs text-neutral-500 hover:text-neutral-700 transition-colors">
                                Reset semua
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabel - Responsive -->
            @if ($pengguna->count() > 0)
                <!-- Desktop Table (hidden di mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-neutral-200 bg-neutral-50">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">NO</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">PENGGUNA</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">PERAN</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">LEMBAGA</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">STATUS</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-neutral-700 w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengguna as $index => $user)
                                @php
                                    $peranLabel = match ($user->peran) {
                                        'superadmin' => 'Super Admin',
                                        'admin_lembaga' => 'Admin Lembaga',
                                        'amil' => 'Amil',
                                        'muzakki' => 'Muzakki',
                                        default => ucfirst($user->peran),
                                    };
                                    $peranColor = match ($user->peran) {
                                        'superadmin' => 'bg-purple-100 text-purple-700',
                                        'admin_lembaga' => 'bg-blue-100 text-blue-700',
                                        'amil' => 'bg-green-100 text-green-700',
                                        'muzakki' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <tr class="border-b border-neutral-100 hover:bg-primary-50/20 transition-all duration-200 group">
                                    <td class="px-6 py-4 text-sm text-neutral-600">
                                        {{ $pengguna->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-semibold text-primary-600">
                                                    {{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-neutral-800 group-hover:text-primary-600 transition-colors">
                                                        {{ $user->username ?? '-' }}
                                                    </span>
                                                    @if ($user->id === auth()->id())
                                                        <span class="text-xs px-1.5 py-0.5 bg-purple-100 text-purple-600 rounded-full">Anda</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-neutral-500">{{ $user->email }}</div>
                                                @if ($user->is_google_user)
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
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $peranColor }}">
                                            {{ $peranLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-neutral-600">
                                        {{ $user->lembaga->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->is_active)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block">
                                            <button type="button"
                                                class="dropdown-toggle p-1.5 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                                data-uuid="{{ $user->uuid }}" 
                                                data-nama="{{ $user->username ?? $user->email }}"
                                                data-is-self="{{ $user->id === auth()->id() ? '1' : '0' }}"
                                                data-is-active="{{ $user->is_active ? '1' : '0' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    @foreach ($pengguna as $index => $user)
                        @php
                            $peranLabel = match ($user->peran) {
                                'superadmin' => 'Super Admin',
                                'admin_lembaga' => 'Admin Lembaga',
                                'amil' => 'Amil',
                                'muzakki' => 'Muzakki',
                                default => ucfirst($user->peran),
                            };
                            $peranColor = match ($user->peran) {
                                'superadmin' => 'bg-purple-100 text-purple-700',
                                'admin_lembaga' => 'bg-blue-100 text-blue-700',
                                'amil' => 'bg-green-100 text-green-700',
                                'muzakki' => 'bg-amber-100 text-amber-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <div class="p-4 hover:bg-primary-50/20 transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs text-neutral-400">#{{ $pengguna->firstItem() + $index }}</span>
                                        @if ($user->id === auth()->id())
                                            <span class="text-xs px-1.5 py-0.5 bg-purple-100 text-purple-600 rounded-full">Anda</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-semibold text-primary-600">
                                                {{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-neutral-800 break-words">
                                                {{ $user->username ?? '-' }}
                                            </h3>
                                            <p class="text-xs text-neutral-500 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $peranColor }}">
                                            {{ $peranLabel }}
                                        </span>
                                        @if ($user->is_active)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                    @if ($user->lembaga)
                                        <p class="text-xs text-neutral-500 mt-2">
                                            <span class="font-medium">Lembaga:</span> {{ $user->lembaga->nama }}
                                        </p>
                                    @endif
                                    @if ($user->is_google_user)
                                        <p class="text-xs text-blue-600 mt-1">
                                            <span class="font-medium">Akun Google</span>
                                        </p>
                                    @endif
                                </div>
                                <div class="relative inline-block flex-shrink-0">
                                    <button type="button"
                                        class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                        data-uuid="{{ $user->uuid }}" 
                                        data-nama="{{ $user->username ?? $user->email }}"
                                        data-is-self="{{ $user->id === auth()->id() ? '1' : '0' }}"
                                        data-is-active="{{ $user->is_active ? '1' : '0' }}">
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
                @if ($pengguna->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-neutral-200 bg-neutral-50/30">
                        {{ $pengguna->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-14 text-center animate-fade-in">
                    <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    @if (request('q') || request('peran') || request('status') || request('lembaga_id'))
                        <p class="text-sm text-neutral-500">Tidak ada hasil untuk filter yang dipilih</p>
                        <button onclick="resetAllFilters()"
                            class="mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                            Reset semua filter
                        </button>
                    @else
                        <p class="text-sm text-neutral-500">Belum ada data pengguna</p>
                        <a href="{{ route('pengguna.create') }}"
                            class="inline-block mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                            Tambah pengguna
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdown-container" class="fixed hidden z-50 bg-white rounded-lg shadow-lg border border-neutral-200 min-w-[140px]">
        <div class="py-1">
            <a href="#" id="dropdown-detail-link"
                class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Detail
            </a>
            <a href="#" id="dropdown-edit-link"
                class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <button type="button" id="dropdown-toggle-btn"
                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <span id="dropdown-toggle-label">Toggle Status</span>
            </button>
            <button type="button" id="dropdown-delete-btn"
                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/30 hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-xl max-w-sm w-full shadow-modal transform transition-all duration-300 animate-scale-in">
            <div class="p-5">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2 text-center">Hapus Pengguna</h3>
                <p class="text-sm text-neutral-500 mb-2 text-center">
                    Yakin ingin menghapus "<span id="modal-pengguna-name" class="font-semibold text-neutral-700"></span>"?
                </p>
                <p class="text-xs text-neutral-400 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
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
    let currentUuid = null;
    let currentNama = null;
    const baseUrl = "{{ rtrim(route('pengguna.index'), '/') }}";

    document.addEventListener('DOMContentLoaded', function() {
        const dropdownContainer = document.getElementById('dropdown-container');
        const detailLink = document.getElementById('dropdown-detail-link');
        const editLink = document.getElementById('dropdown-edit-link');
        const toggleBtn = document.getElementById('dropdown-toggle-btn');
        const toggleLabel = document.getElementById('dropdown-toggle-label');
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

        // Dropdown logic
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.dropdown-toggle');
            if (toggle) {
                e.stopPropagation();
                const dropdownUuid = toggle.getAttribute('data-uuid');
                const userName = toggle.getAttribute('data-nama');
                const isSelf = toggle.getAttribute('data-is-self') === '1';
                const isActive = toggle.getAttribute('data-is-active') === '1';

                if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid &&
                    !dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                    return;
                }

                currentUuid = dropdownUuid;
                currentNama = userName;
                dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);
                
                const rect = toggle.getBoundingClientRect();

                detailLink.href = `${baseUrl}/${dropdownUuid}`;
                editLink.href = `${baseUrl}/${dropdownUuid}/edit`;
                
                toggleLabel.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
                toggleBtn.style.display = isSelf ? 'none' : 'flex';
                deleteBtn.style.display = isSelf ? 'none' : 'flex';

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
            } else if (!dropdownContainer.contains(e.target)) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        });

        // Toggle Status
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!currentUuid) return;
            dropdownContainer.classList.add('hidden');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${baseUrl}/${currentUuid}/toggle-status`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PATCH';
            
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });

        // Delete handler
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!currentUuid) return;
            dropdownContainer.classList.add('hidden');
            document.getElementById('modal-pengguna-name').textContent = currentNama;
            deleteForm.action = `${baseUrl}/${currentUuid}`;
            document.getElementById('delete-modal').classList.remove('hidden');
        });

        // Modal handlers
        document.getElementById('cancel-delete-btn').addEventListener('click', function() {
            document.getElementById('delete-modal').classList.add('hidden');
        });

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // Close dropdown on scroll/resize
        window.addEventListener('scroll', () => {
            if (!dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
            }
        }, true);
        
        window.addEventListener('resize', () => {
            dropdownContainer.classList.add('hidden');
            dropdownContainer.removeAttribute('data-current-uuid');
        });
    });

    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        
        if (filterName === 'q') {
            const filterSearch = document.getElementById('filter-search');
            if (filterSearch) filterSearch.value = '';
        }
        
        // Jika menghapus sort_by, hapus juga sort_order
        if (filterName === 'sort_by') {
            url.searchParams.delete('sort_order');
        }
        
        window.location.href = url.toString();
    }

    function resetAllFilters() {
        const url = new URL(window.location.href);
        url.searchParams.delete('q');
        url.searchParams.delete('peran');
        url.searchParams.delete('status');
        url.searchParams.delete('lembaga_id');
        url.searchParams.delete('sort_by');
        url.searchParams.delete('sort_order');
        url.searchParams.set('page', '1');
        
        // Reset input values
        const filterSearch = document.getElementById('filter-search');
        const filterPeran = document.getElementById('filter-peran');
        const filterStatus = document.getElementById('filter-status');
        const filterLembaga = document.getElementById('filter-lembaga');
        const filterSortBy = document.getElementById('filter-sort-by');
        const filterSortOrder = document.getElementById('filter-sort-order');
        
        if (filterSearch) filterSearch.value = '';
        if (filterPeran) filterPeran.value = '';
        if (filterStatus) filterStatus.value = '';
        if (filterLembaga) filterLembaga.value = '';
        if (filterSortBy) filterSortBy.value = 'created_at';
        if (filterSortOrder) filterSortOrder.value = 'asc';
        
        window.location.href = url.toString();
    }
</script>
@endpush