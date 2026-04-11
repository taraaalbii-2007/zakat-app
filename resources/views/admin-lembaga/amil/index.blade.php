{{-- resources/views/admin-lembaga/amil/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Data Amil')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">Data Amil</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola pengurus zakat (amil)</p>
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
                        <a href="{{ route('amil.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>

                        <!-- DROPDOWN IMPORT/EXPORT -->
                        <div class="relative" id="importExportDropdown">
                            <button type="button" id="dropdownToggleBtn"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 11l3 3m0 0l3-3m-3 3V4" />
                                </svg>
                                Import/Export
                                <svg class="w-3 h-3 ml-1 transition-transform duration-200" id="dropdownIcon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="dropdownMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden hidden z-20 animate-fade-in">
                                <div class="py-1">
                                    <!-- Download Template -->
                                    <a href="{{ route('import.template') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download Template
                                    </a>

                                    <!-- Import -->
                                    <button type="button" onclick="openImportModal()"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150 text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 11l3 3m0 0l3-3m-3 3V4" />
                                        </svg>
                                        Import Data
                                    </button>

                                    <!-- Divider -->
                                    <div class="border-t border-gray-100 my-1"></div>

                                    <!-- Export -->
                                    <a href="{{ route('export.excel', request()->query()) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export Data
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-6 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $amils->total() }}</span>
                        <span class="text-sm text-gray-500">Amil</span>
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

                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('jenis_kelamin'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                {{ request('jenis_kelamin') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                <button onclick="removeFilter('jenis_kelamin')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="px-6 py-4 border-b border-gray-100 bg-green-50/30 hidden">
                <form method="GET" action="{{ route('amil.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search Field -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Amil</label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari nama, kode, email, telepon..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <!-- Filter Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>

                        <!-- Filter Jenis Kelamin -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>
                    </div>

                    @if (auth()->user()->peran === 'superadmin')
                        <div class="mt-4">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Lembaga</label>
                            <select name="lembaga_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Lembaga</option>
                                @foreach ($lembagas as $lembaga)
                                    <option value="{{ $lembaga->id }}"
                                        {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                        {{ $lembaga->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

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
                        @if (request('q') || request('status') || request('jenis_kelamin') || request('lembaga_id'))
                            <a href="{{ route('amil.index') }}"
                                class="px-4 py-2 text-gray-500 hover:text-red-600 text-sm font-medium transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabel dengan Expandable Row -->
            @if ($amils->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th
                                    class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    AMIL</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    KONTAK</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    STATUS</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                    AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($amils as $index => $amil)
                                @php
                                    $colors = [
                                        'bg-blue-500',
                                        'bg-green-500',
                                        'bg-yellow-500',
                                        'bg-red-500',
                                        'bg-purple-500',
                                        'bg-pink-500',
                                    ];
                                    $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                                    $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                                @endphp
                                <!-- Baris Utama (Expandable) -->
                                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $amil->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                @if ($amil->foto)
                                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100"
                                                        src="{{ Storage::url($amil->foto) }}"
                                                        alt="{{ $amil->nama_lengkap }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                        <span
                                                            class="text-sm font-medium text-white">{{ $initial }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div
                                                    class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                    {{ $amil->nama_lengkap }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $amil->kode_amil }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs text-gray-600">{{ $amil->email }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span class="text-xs text-gray-600">{{ $amil->telepon }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($amil->status === 'aktif')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @elseif ($amil->status === 'cuti')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                                Cuti
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Detail -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('amil.show', $amil->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Detail
                                                    <div
                                                        class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tombol Ubah Status -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="status-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $amil->uuid }}"
                                                    data-nama="{{ $amil->nama_lengkap }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Ubah Status
                                                </div>
                                            </div>



                                            <!-- Ikon Edit -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('amil.edit', $amil->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Edit
                                                </div>
                                            </div>

                                            <!-- Ikon Hapus -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $amil->uuid }}"
                                                    data-nama="{{ $amil->nama_lengkap }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Hapus
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Baris Expandable (Detail) -->
                                <tr id="detail-{{ $amil->uuid }}"
                                    class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-gray-50/30"></td>
                                    <td class="px-6 py-4 align-top bg-gray-50/30" colspan="4">
                                        <div class="space-y-3">
                                            <div>
                                                <h4
                                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                                    Informasi Lengkap</h4>
                                                <div class="bg-white rounded-lg p-3 border border-gray-100 space-y-2">
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <span class="text-xs text-gray-500">Alamat</span>
                                                            <p class="text-sm text-gray-700 mt-0.5">
                                                                {{ $amil->alamat ?: '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <span class="text-xs text-gray-500">Jenis Kelamin</span>
                                                            <p class="text-sm text-gray-700 mt-0.5">
                                                                {{ $amil->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                            </p>
                                                        </div>
                                                        @if ($amil->lembaga)
                                                            <div>
                                                                <span class="text-xs text-gray-500">Lembaga</span>
                                                                <p class="text-sm text-gray-700 mt-0.5">
                                                                    {{ $amil->lembaga->nama }}</p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <span class="text-xs text-gray-500">Tanggal Bergabung</span>
                                                            <p class="text-sm text-gray-700 mt-0.5">
                                                                {{ $amil->tanggal_bergabung ? \Carbon\Carbon::parse($amil->tanggal_bergabung)->format('d/m/Y') : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <h4
                                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                                    Riwayat</h4>
                                                <div class="bg-white rounded-lg p-3 border border-gray-100 space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Dibuat pada</span>
                                                        <span
                                                            class="text-xs font-medium text-gray-700">{{ $amil->created_at ? $amil->created_at->format('d/m/Y H:i') : '-' }}</span>
                                                    </div>
                                                    @if ($amil->updated_at && $amil->updated_at->ne($amil->created_at))
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Terakhir diperbarui</span>
                                                            <span
                                                                class="text-xs font-medium text-gray-700">{{ $amil->updated_at->format('d/m/Y H:i') }}</span>
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

                <!-- MOBILE CARD VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($amils as $index => $amil)
                        @php
                            $colors = [
                                'bg-blue-500',
                                'bg-green-500',
                                'bg-yellow-500',
                                'bg-red-500',
                                'bg-purple-500',
                                'bg-pink-500',
                            ];
                            $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                            $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                        @endphp
                        <div
                            class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="expandable-row-mobile cursor-pointer"
                                data-target="detail-mobile-{{ $amil->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex-shrink-0">
                                                @if ($amil->foto)
                                                    <img class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-100"
                                                        src="{{ Storage::url($amil->foto) }}"
                                                        alt="{{ $amil->nama_lengkap }}">
                                                @else
                                                    <div
                                                        class="h-12 w-12 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                        <span
                                                            class="text-base font-medium text-white">{{ $initial }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    <h3 class="text-sm font-semibold text-gray-800 break-words">
                                                        {{ $amil->nama_lengkap }}</h3>
                                                </div>
                                                <p class="text-xs text-gray-400">{{ $amil->kode_amil }}</p>
                                                <div class="mt-2">
                                                    @if ($amil->status === 'aktif')
                                                        <span
                                                            class="inline-flex px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Aktif</span>
                                                    @elseif ($amil->status === 'cuti')
                                                        <span
                                                            class="inline-flex px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Cuti</span>
                                                    @else
                                                        <span
                                                            class="inline-flex px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full">Nonaktif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Detail -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('amil.show', $amil->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Detail
                                                <div
                                                    class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="status-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all"
                                                data-uuid="{{ $amil->uuid }}" data-nama="{{ $amil->nama_lengkap }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </button>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Ubah Status
                                            </div>
                                        </div>

                                        <div class="relative group/tooltip">
                                            <a href="{{ route('amil.edit', $amil->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Edit
                                            </div>
                                        </div>

                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $amil->uuid }}" data-nama="{{ $amil->nama_lengkap }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Hapus
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="detail-mobile-{{ $amil->uuid }}"
                                class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-900 break-all">{{ $amil->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Telepon</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $amil->telepon }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Alamat</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $amil->alamat ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $amil->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                    </div>
                                    @if ($amil->lembaga)
                                        <div>
                                            <p class="text-xs text-gray-500">Lembaga</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $amil->lembaga->nama }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Bergabung</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $amil->tanggal_bergabung ? \Carbon\Carbon::parse($amil->tanggal_bergabung)->format('d/m/Y') : '-' }}
                                        </p>
                                    </div>
                                    <div class="pt-2 text-xs text-gray-400">
                                        <p>Dibuat: {{ $amil->created_at ? $amil->created_at->format('d/m/Y H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($amils->hasPages())
                    <div class="px-6 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $amils->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>

                    @if (request('q') || request('status') || request('jenis_kelamin') || request('lembaga_id'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('amil.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data amil</p>
                        <a href="{{ route('amil.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah data sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ubah Status -->
    <div id="status-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div
            class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Ubah Status Amil</h3>
                <p class="text-sm text-gray-500 mb-4 text-center">
                    Ubah status untuk "<span id="modal-status-name" class="font-semibold text-gray-700"></span>"
                </p>
                <form id="status-form" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-3 mb-6">
                        <label
                            class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="aktif"
                                class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <span class="ml-3 text-sm font-medium text-gray-900">Aktif</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="cuti"
                                class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                            <span class="ml-3 text-sm font-medium text-gray-900">Cuti</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="nonaktif"
                                class="w-4 h-4 text-red-600 focus:ring-red-500">
                            <span class="ml-3 text-sm font-medium text-gray-900">Nonaktif</span>
                        </label>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" id="cancel-status-btn"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div
            class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Amil</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus amil "<span id="modal-amil-name"
                        class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan dan akan menghapus akun login terkait.
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

    <!-- Modal Import -->
    <div id="import-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div
            class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Import Data Amil</h2>
                <p class="text-xs text-gray-500 mt-0.5">Upload file Excel untuk import data amil</p>
            </div>
            <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data"
                id="form-upload-import">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 text-xs text-blue-700 space-y-1">
                        <p class="font-semibold text-blue-800">Panduan Import:</p>
                        <p>• Download template terlebih dahulu, isi data, lalu upload.</p>
                        <p>• Format file: .xlsx atau .xls (maks. 500 MB).</p>
                        <p>• Akun login amil dibuat otomatis, notifikasi dikirim via email.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            File Excel <span class="text-red-500">*</span>
                        </label>
                        <div id="import-drop-zone"
                            class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl hover:border-green-400 hover:bg-green-50/50 transition-all cursor-pointer bg-gray-50"
                            onclick="document.getElementById('file-input-import').click()">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 text-center px-4">Klik atau seret file Excel ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">.xlsx / .xls — maks. 500 MB</p>
                            <input type="file" name="file_import" id="file-input-import" accept=".xlsx,.xls"
                                class="hidden" required>
                        </div>
                        <div id="import-file-preview"
                            class="hidden mt-2 flex items-center gap-2 text-sm text-green-700 font-medium bg-green-50 border border-green-100 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span id="import-file-name" class="truncate"></span>
                            <button type="button" onclick="clearImportFile()"
                                class="ml-auto text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-upload-submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        Lanjut
                    </button>
                </div>
            </form>
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scale-in {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes slide-in-right {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }

        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }

        .animate-slide-in-right {
            animation: slide-in-right 0.3s ease-out;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown Import/Export
            const dropdownToggle = document.getElementById('dropdownToggleBtn');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownIcon = document.getElementById('dropdownIcon');

            if (dropdownToggle && dropdownMenu) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                    if (dropdownIcon) {
                        dropdownIcon.classList.toggle('rotate-180');
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        if (dropdownIcon) {
                            dropdownIcon.classList.remove('rotate-180');
                        }
                    }
                });
            }

            // Filter Panel
            const filterButton = document.getElementById('filterButton');
            const filterPanel = document.getElementById('filterPanel');
            const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

            if (filterButton && filterPanel) {
                filterButton.addEventListener('click', function() {
                    filterPanel.classList.toggle('hidden');
                });
            }

            if (closeFilterPanelBtn) {
                closeFilterPanelBtn.addEventListener('click', function() {
                    filterPanel.classList.add('hidden');
                });
            }

            // Desktop Expandable row
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.delete-btn') || e.target.closest('.status-btn') || e
                        .target.closest('a')) return;

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
                    if (e.target.closest('.delete-btn') || e.target.closest('.status-btn') || e
                        .target.closest('a')) return;

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

            // Status button handler
            const statusModal = document.getElementById('status-modal');
            const statusForm = document.getElementById('status-form');
            const cancelStatusBtn = document.getElementById('cancel-status-btn');

            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const uuid = this.getAttribute('data-uuid');
                    const nama = this.getAttribute('data-nama');

                    document.getElementById('modal-status-name').textContent = nama;
                    statusForm.action = `/amil/${uuid}/toggle-status`;
                    statusModal.classList.remove('hidden');
                });
            });

            if (cancelStatusBtn) {
                cancelStatusBtn.addEventListener('click', function() {
                    statusModal.classList.add('hidden');
                });
            }

            statusModal.addEventListener('click', function(e) {
                if (e.target === statusModal) {
                    statusModal.classList.add('hidden');
                }
            });

            // Delete button handler
            const deleteModal = document.getElementById('delete-modal');
            const deleteForm = document.getElementById('delete-form');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const uuid = this.getAttribute('data-uuid');
                    const nama = this.getAttribute('data-nama');

                    document.getElementById('modal-amil-name').textContent = nama;
                    deleteForm.action = `/amil/${uuid}`;
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
            window.location.href = url.toString();
        }

        function removeAllFilters() {
            window.location.href = '{{ route('amil.index') }}';
        }

        // Import Modal functions
        function openImportModal() {
            document.getElementById('import-modal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('import-modal').classList.add('hidden');
            clearImportFile();
        }

        function onImportFileSelected(input) {
            if (input.files && input.files[0]) {
                document.getElementById('import-file-name').textContent = input.files[0].name;
                document.getElementById('import-file-preview').classList.remove('hidden');
                document.getElementById('btn-upload-submit').disabled = false;
            }
        }

        function clearImportFile() {
            const fileInput = document.getElementById('file-input-import');
            if (fileInput) fileInput.value = '';
            document.getElementById('import-file-preview').classList.add('hidden');
            const submitBtn = document.getElementById('btn-upload-submit');
            if (submitBtn) submitBtn.disabled = true;
        }

        document.getElementById('file-input-import')?.addEventListener('change', function() {
            onImportFileSelected(this);
        });

        const dropZone = document.getElementById('import-drop-zone');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-green-500', 'bg-green-50');
            });
            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-green-500', 'bg-green-50');
            });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-green-500', 'bg-green-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const fileInput = document.getElementById('file-input-import');
                    fileInput.files = files;
                    onImportFileSelected(fileInput);
                }
            });
        }
    </script>
@endpush
