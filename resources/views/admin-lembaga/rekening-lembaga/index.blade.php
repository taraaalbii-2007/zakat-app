{{-- resources/views/admin-masjid/rekening-lembaga/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Rekening Lembaga')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">Rekening Lembaga</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola rekening bank untuk penyaluran dana zakat</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah -->
                        @if ($permissions['canCreate'])
                            <a href="{{ route('rekening-lembaga.create') }}"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Rekening
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-6 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $rekeningLembagas->total() }}</span>
                        <span class="text-sm text-gray-500">Rekening</span>
                    </div>

                    <!-- Active Filters Tags -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if (request('q'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('is_active') !== null)
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ request('is_active') == '1' ? 'Aktif' : 'Nonaktif' }}
                                <button onclick="removeFilter('is_active')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('is_primary') !== null)
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                {{ request('is_primary') == '1' ? 'Rekening Utama' : 'Rekening Biasa' }}
                                <button onclick="removeFilter('is_primary')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="px-6 py-4 border-b border-gray-100 bg-green-50/30 hidden">
                <form method="GET" action="{{ route('rekening-lembaga.index') }}">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search Field -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Rekening</label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari nama bank, nomor rekening, pemilik..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <!-- Filter Status Aktif -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="is_active"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <!-- Filter Rekening Utama -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Rekening</label>
                            <select name="is_primary"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Jenis</option>
                                <option value="1" {{ request('is_primary') == '1' ? 'selected' : '' }}>Rekening Utama</option>
                                <option value="0" {{ request('is_primary') == '0' ? 'selected' : '' }}>Rekening Biasa</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol di ujung kanan -->
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" id="closeFilterPanelBtn"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                        @if (request('q') || request('is_active') !== null || request('is_primary') !== null)
                            <a href="{{ route('rekening-lembaga.index') }}"
                                class="px-4 py-2 text-gray-500 hover:text-red-600 text-sm font-medium transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($rekeningLembagas->count() > 0)
                <!-- Tabel Desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NO</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">BANK & NO. REKENING</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NAMA PEMILIK</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KETERANGAN</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($rekeningLembagas as $index => $item)
                                @php
                                    $canEdit = $item->actions['can_edit'] ?? false;
                                    $canDelete = $item->actions['can_delete'] ?? false;
                                    $canSetPrimary = $item->actions['can_set_primary'] ?? false;
                                    $canToggleActive = $item->actions['can_toggle_active'] ?? false;
                                    
                                    $statusColor = $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500';
                                    $statusLabel = $item->is_active ? 'Aktif' : 'Nonaktif';
                                @endphp
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300">
                                    <td class="px-6 py-3">
                                        <span class="text-sm font-medium text-gray-800">{{ $rekeningLembagas->firstItem() + $index }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors">
                                                {{ $item->nama_bank }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $item->nomor_rekening }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-sm text-gray-600">{{ $item->nama_pemilik }}</div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->keterangan, 40) ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="space-y-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                            @if ($item->is_primary)
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 ml-1">
                                                    Utama
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Ikon Detail -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('rekening-lembaga.show', $item->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Detail
                                                </div>
                                            </div>

                                            <!-- Ikon Edit -->
                                            @if ($canEdit)
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('rekening-lembaga.edit', $item->uuid) }}"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Edit
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Ikon Set Primary -->
                                            @if ($canSetPrimary && !$item->is_primary)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="set-primary-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all duration-200"
                                                        data-uuid="{{ $item->uuid }}"
                                                        data-nama="{{ $item->nama_bank }} - {{ $item->nomor_rekening }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Jadikan Utama
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Ikon Toggle Active -->
                                            @if ($canToggleActive)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="toggle-active-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200"
                                                        data-uuid="{{ $item->uuid }}"
                                                        data-nama="{{ $item->nama_bank }} - {{ $item->nomor_rekening }}"
                                                        data-active="{{ $item->is_active }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            @if ($item->is_active)
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" dM9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            @endif
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Ikon Hapus -->
                                            @if ($canDelete)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                        data-uuid="{{ $item->uuid }}"
                                                        data-nama="{{ $item->nama_bank }} - {{ $item->nomor_rekening }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Hapus
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                     </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($rekeningLembagas as $index => $item)
                        @php
                            $canEdit = $item->actions['can_edit'] ?? false;
                            $canDelete = $item->actions['can_delete'] ?? false;
                            $canSetPrimary = $item->actions['can_set_primary'] ?? false;
                            $canToggleActive = $item->actions['can_toggle_active'] ?? false;
                            
                            $statusColor = $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500';
                            $statusLabel = $item->is_active ? 'Aktif' : 'Nonaktif';
                        @endphp
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-medium text-gray-500">#{{ $rekeningLembagas->firstItem() + $index }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-800 mb-1">
                                        {{ $item->nama_bank }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $item->nomor_rekening }}</p>

                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                        @if ($item->is_primary)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                                Utama
                                            </span>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $item->nama_pemilik }}
                                        </div>
                                        @if ($item->keterangan)
                                            <div class="flex items-start text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="line-clamp-2">{{ $item->keterangan }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <!-- Detail -->
                                    <div class="relative group/tooltip">
                                        <a href="{{ route('rekening-lembaga.show', $item->uuid) }}"
                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                            Detail
                                        </div>
                                    </div>

                                    <!-- Edit -->
                                    @if ($canEdit)
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('rekening-lembaga.edit', $item->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Edit
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Set Primary -->
                                    @if ($canSetPrimary && !$item->is_primary)
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="set-primary-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all"
                                                data-uuid="{{ $item->uuid }}"
                                                data-nama="{{ $item->nama_bank }} - {{ $item->nomor_rekening }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Jadikan Utama
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Toggle Active -->
                                    @if ($canToggleActive)
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="toggle-active-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all"
                                                data-uuid="{{ $item->uuid }}"
                                                data-nama="{{ $item->nama_bank }} - {{ $item->nomor_rekening }}"
                                                data-active="{{ $item->is_active }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if ($item->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hapus -->
                                    @if ($canDelete)
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $item->uuid }}"
                                                data-nama="{{ $item->nama_bank }} - {{ $item->nomor_rekening }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Hapus
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($rekeningLembagas->hasPages())
                    <div class="px-6 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $rekeningLembagas->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>

                    @if (request('q') || request('is_active') !== null || request('is_primary') !== null)
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('rekening-lembaga.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada rekening lembaga</p>
                        @if ($permissions['canCreate'])
                            <a href="{{ route('rekening-lembaga.create') }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah rekening sekarang
                            </a>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Rekening</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus rekening "<span id="modal-rekening-name" class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
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

            // Filter Panel elements
            const filterButton = document.getElementById('filterButton');
            const filterPanel = document.getElementById('filterPanel');
            const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

            // Toggle filter panel
            if (filterButton && filterPanel) {
                filterButton.addEventListener('click', function() {
                    if (filterPanel.classList.contains('hidden')) {
                        filterPanel.classList.remove('hidden');
                    } else {
                        filterPanel.classList.add('hidden');
                    }
                });
            }

            // Tutup filter panel
            if (closeFilterPanelBtn) {
                closeFilterPanelBtn.addEventListener('click', function() {
                    filterPanel.classList.add('hidden');
                });
            }

            // Delete button handler
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const uuid = this.getAttribute('data-uuid');
                    const nama = this.getAttribute('data-nama');

                    document.getElementById('modal-rekening-name').textContent = nama;
                    deleteForm.action = `/rekening-lembaga/${uuid}`;
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

            // Set Primary button handler
            document.querySelectorAll('.set-primary-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const uuid = this.getAttribute('data-uuid');
                    const nama = this.getAttribute('data-nama');
                    
                    if (confirm(`Jadikan rekening "${nama}" sebagai rekening utama?`)) {
                        setPrimaryRekening(uuid);
                    }
                });
            });

            // Toggle Active button handler
            document.querySelectorAll('.toggle-active-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const uuid = this.getAttribute('data-uuid');
                    const nama = this.getAttribute('data-nama');
                    const isActive = this.getAttribute('data-active') === '1';
                    const action = isActive ? 'Nonaktifkan' : 'Aktifkan';
                    
                    if (confirm(`${action} rekening "${nama}"?`)) {
                        toggleActiveRekening(uuid);
                    }
                });
            });
        });

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        function setPrimaryRekening(uuid) {
            fetch(`/rekening-lembaga/${uuid}/set-primary`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat mengatur rekening utama', 'error');
            });
        }

        function toggleActiveRekening(uuid) {
            fetch(`/rekening-lembaga/${uuid}/toggle-active`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat mengubah status', 'error');
            });
        }

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500'
            };

            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in-right`;
            toast.innerHTML = `
                <div class="flex items-center">
                    ${type === 'success' ? 
                        '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' :
                        '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    }
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush