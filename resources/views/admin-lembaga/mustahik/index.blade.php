{{-- resources/views/admin-lembaga/mustahik/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Data Mustahik')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- ── Import Error Alert ─────────────────────────────────────────── --}}
            @if (session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-3">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-yellow-800">
                                {{ count(session('import_errors')) }} baris dilewati saat import
                            </p>
                            <button type="button" id="btn-toggle-import-errors"
                                class="text-xs text-yellow-600 hover:text-yellow-800 mt-1">
                                Lihat detail error ▾
                            </button>
                            <ul id="import-errors-list" class="hidden mt-2 space-y-0.5 text-xs text-yellow-700">
                                @foreach (session('import_errors') as $err)
                                    <li>• {{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">Data Mustahik</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola mustahik penerima zakat</p>
                        @if (($permissions['pendingCount'] ?? 0) > 0)
                            <p class="text-xs text-yellow-600 mt-0.5">
                                {{ $permissions['pendingCount'] }} mustahik menunggu verifikasi
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah -->
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.create') }}"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </a>
                        @endif

                        <!-- DROPDOWN IMPORT/EXPORT -->
                        @if ($permissions['canCreate'])
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
                                        <a href="{{ route('mustahik.import.template') }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-150">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download Template
                                        </a>

                                        <!-- Import -->
                                        <button type="button" onclick="document.getElementById('modal-import').classList.remove('hidden')"
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
                                        <a href="{{ route('mustahik.export.excel', request()->query()) }}"
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
                        @endif

                        <!-- Bulk Verify Button (muncul hanya jika ada checkbox tercentang) -->
                        @if ($permissions['canBulkVerify'] ?? false)
                            <button type="button" id="bulk-verify-btn" onclick="openBulkVerifyModal()"
                                class="hidden inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verifikasi Terpilih (<span id="selected-count">0</span>)
                            </button>
                        @endif

                        <!-- Verifikasi Semua Pending -->
                        @if (($permissions['canVerifyAll'] ?? false) && ($permissions['pendingCount'] ?? 0) > 0)
                            <button type="button" onclick="confirmVerifyAllPending()"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verifikasi Semua ({{ $permissions['pendingCount'] }})
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-6 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $mustahiks->total() }}</span>
                        <span class="text-sm text-gray-500">Mustahik</span>
                    </div>

                    <!-- Active Filters Tags -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if (request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                        @if (request('kategori_id'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Kategori: {{ $kategoris->firstWhere('id', request('kategori_id'))?->nama ?? 'ID: '.request('kategori_id') }}
                                <button onclick="removeFilter('kategori_id')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                        @if (request('status_verifikasi'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status_verifikasi')) }}
                                <button onclick="removeFilter('status_verifikasi')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                        @if (request('is_active'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Aktif: {{ request('is_active') == '1' ? 'Aktif' : 'Nonaktif' }}
                                <button onclick="removeFilter('is_active')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                        @if (request('jenis_kelamin'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                {{ request('jenis_kelamin') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                <button onclick="removeFilter('jenis_kelamin')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter Panel (dengan Search di dalamnya) -->
            <div id="filter-panel"
                class="{{ request('q') || request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin') ? '' : 'hidden' }} px-6 py-4 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('mustahik.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Field -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Mustahik</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari nama, NIK, no registrasi..."
                                    class="pl-8 pr-3 py-2 w-full text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                            <select name="kategori_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status Verifikasi</label>
                            <select name="status_verifikasi"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status_verifikasi') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status Aktif</label>
                            <select name="is_active"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                        @if (request('q') || request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin'))
                            <a href="{{ route('mustahik.index') }}"
                                class="px-4 py-2 text-gray-500 hover:text-red-600 text-sm font-medium transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabel dengan Expandable Row -->
            @if ($mustahiks->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                @if ($permissions['canBulkVerify'] ?? false)
                                    <th class="px-4 py-3 text-center w-10">
                                        <input type="checkbox" id="select-all-checkbox"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            onclick="toggleSelectAll(this)">
                                    </th>
                                @endif
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NO. REGISTRASI</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">MUSTAHIK</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KATEGORI</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ALAMAT</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($mustahiks as $item)
                                @php
                                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500'];
                                    $initial = strtoupper(substr($item->nama_lengkap, 0, 1));
                                    $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                                    $canVerifyInRow = $item->status_verifikasi === 'pending' && ($permissions['canBulkVerify'] ?? false);
                                @endphp
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200"
                                    data-mustahik-uuid="{{ $item->uuid }}" data-status="{{ $item->status_verifikasi }}">
                                    @if ($permissions['canBulkVerify'] ?? false)
                                        <td class="px-4 py-4 text-center">
                                            @if ($canVerifyInRow)
                                                <input type="checkbox"
                                                    class="mustahik-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                    data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}">
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800">{{ $item->no_registrasi }}</div>
                                        <div class="text-xs text-gray-400">{{ $item->tanggal_registrasi->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                    <span class="text-sm font-medium text-white">{{ $initial }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors">
                                                    {{ $item->nama_lengkap }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-0.5">
                                                    NIK: {{ $item->nik ?? '-' }}
                                                    @if ($item->telepon)
                                                        | {{ $item->telepon }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                            {{ $item->kategoriMustahik->nama }}
                                        </span>
                                     </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">{{ Str::limit($item->alamat, 40) }}</div>
                                        @if ($item->rt_rw)
                                            <div class="text-xs text-gray-400">RT/RW: {{ $item->rt_rw }}</div>
                                        @endif
                                     </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            {!! $item->status_badge !!}
                                            {!! $item->active_badge !!}
                                        </div>
                                     </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Detail -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('mustahik.show', $item->uuid) }}"
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
                                            @if ($item->actions['can_edit'] ?? false)
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('mustahik.edit', $item->uuid) }}"
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

                                            <!-- Ubah Status (Toggle Active) -->
                                            @if (($item->actions['can_toggle_active'] ?? false) || ($permissions['canToggleActive'] ?? false))
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="toggle-status-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all"
                                                        data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}"
                                                        data-is-active="{{ $item->is_active }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Ubah Status
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Verifikasi Single -->
                                            @if ($item->status_verifikasi === 'pending' && ($permissions['canBulkVerify'] ?? false))
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="verify-single-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                                        data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Verifikasi
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Hapus -->
                                            @if ($item->actions['can_delete'] ?? false)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                        data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}">
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

                <!-- MOBILE CARD VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($mustahiks as $item)
                        @php
                            $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500'];
                            $initial = strtoupper(substr($item->nama_lengkap, 0, 1));
                            $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                        @endphp
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if ($permissions['canBulkVerify'] ?? false)
                                            @if ($item->status_verifikasi === 'pending')
                                                <div class="flex-shrink-0">
                                                    <input type="checkbox"
                                                        class="mustahik-checkbox-mobile rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                        data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}">
                                                </div>
                                            @endif
                                        @endif
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                <span class="text-base font-medium text-white">{{ $initial }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-semibold text-gray-800 break-words">{{ $item->nama_lengkap }}</h3>
                                            <p class="text-xs text-gray-400">{{ $item->no_registrasi }}</p>
                                            <div class="flex flex-wrap gap-1 mt-1.5">
                                                {!! $item->status_badge !!}
                                                {!! $item->active_badge !!}
                                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                                    {{ $item->kategoriMustahik->nama }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($item->alamat, 50) }}</p>
                                </div>

                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <!-- Detail -->
                                    <div class="relative group/tooltip">
                                        <a href="{{ route('mustahik.show', $item->uuid) }}"
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
                                    @if ($item->actions['can_edit'] ?? false)
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('mustahik.edit', $item->uuid) }}"
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

                                    <!-- Ubah Status -->
                                    @if (($item->actions['can_toggle_active'] ?? false) || ($permissions['canToggleActive'] ?? false))
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="toggle-status-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all"
                                                data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}"
                                                data-is-active="{{ $item->is_active }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Ubah Status
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hapus -->
                                    @if ($item->actions['can_delete'] ?? false)
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}">
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
                @if ($mustahiks->hasPages())
                    <div class="px-6 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $mustahiks->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>

                    @if (request('q') || request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('mustahik.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data mustahik</p>
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.create') }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah mustahik sekarang
                            </a>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Mustahik</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus mustahik "<span id="modal-mustahik-name" class="font-semibold text-gray-700"></span>"?
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

    <!-- Modal Bulk Verify -->
    <div id="bulk-verify-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Verifikasi Massal</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin memverifikasi <span id="bulk-count" class="font-semibold text-indigo-600"></span> mustahik yang dipilih?
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-bulk-verify-btn"
                        class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="button" id="confirm-bulk-verify-btn"
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                        Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div id="modal-import"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Import Data Mustahik</h2>
                <p class="text-xs text-gray-500 mt-0.5">Upload file Excel untuk import data mustahik</p>
            </div>
            <form method="POST" action="{{ route('mustahik.import.upload') }}" enctype="multipart/form-data" id="form-upload-import">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 text-xs text-blue-700 space-y-1">
                        <p class="font-semibold text-blue-800">Panduan Import:</p>
                        <p>• Download template terlebih dahulu, isi data, lalu upload.</p>
                        <p>• Format file: .xlsx atau .xls (maks. 500 MB).</p>
                        <p>• Setelah upload, Anda akan diarahkan ke halaman pemetaan kolom.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            File Excel <span class="text-red-500">*</span>
                        </label>
                        <div id="import-drop-zone"
                            class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl hover:border-green-400 hover:bg-green-50/50 transition-all cursor-pointer bg-gray-50"
                            onclick="document.getElementById('file-input-import').click()">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 text-center px-4">Klik atau seret file Excel ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">.xlsx / .xls — maks. 500 MB</p>
                            <input type="file" name="file_import" id="file-input-import" accept=".xlsx,.xls" class="hidden" required>
                        </div>
                        <div id="import-file-preview" class="hidden mt-2 flex items-center gap-2 text-sm text-green-700 font-medium bg-green-50 border border-green-100 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span id="import-file-name" class="truncate"></span>
                            <button type="button" onclick="clearImportFile()" class="ml-auto text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        Lanjut
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
    // ============================================
    // DROPDOWN IMPORT/EXPORT
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
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

        // ============================================
        // BULK VERIFY VARIABLES & FUNCTIONS
        // ============================================
        let selectedUuids = new Set();

        window.updateSelectedCount = function() {
            const count = selectedUuids.size;
            const selectedCountSpan = document.getElementById('selected-count');
            const bulkBtn = document.getElementById('bulk-verify-btn');
            if (selectedCountSpan) selectedCountSpan.innerText = count;
            if (bulkBtn) {
                if (count > 0) bulkBtn.classList.remove('hidden');
                else bulkBtn.classList.add('hidden');
            }
        };

        window.toggleSelectAll = function(checkbox) {
            const checkboxes = document.querySelectorAll('.mustahik-checkbox');
            checkboxes.forEach(cb => {
                if (checkbox.checked) {
                    if (!cb.checked) cb.checked = true;
                    const uuid = cb.getAttribute('data-uuid');
                    if (uuid) selectedUuids.add(uuid);
                } else {
                    cb.checked = false;
                    const uuid = cb.getAttribute('data-uuid');
                    if (uuid) selectedUuids.delete(uuid);
                }
            });
            updateSelectedCount();
        };

        window.openBulkVerifyModal = function() {
            const count = selectedUuids.size;
            if (count === 0) {
                alert('Tidak ada mustahik yang dipilih');
                return;
            }
            document.getElementById('bulk-count').innerText = count;
            document.getElementById('bulk-verify-modal').classList.remove('hidden');
        };

        window.confirmBulkVerify = function() {
            const uuids = Array.from(selectedUuids);
            if (uuids.length === 0) return;

            fetch('{{ route('mustahik.bulk-verify') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ uuids: uuids })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            }).catch(() => {
                alert('Terjadi kesalahan saat memverifikasi');
            });

            document.getElementById('bulk-verify-modal').classList.add('hidden');
        };

        window.confirmVerifyAllPending = function() {
            const count = {{ $permissions['pendingCount'] ?? 0 }};
            if (count === 0) {
                alert('Tidak ada mustahik dengan status pending');
                return;
            }
            if (confirm(`Verifikasi semua (${count}) mustahik yang pending?`)) {
                fetch('{{ route('mustahik.verify-all-pending') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                }).catch(() => {
                    alert('Terjadi kesalahan saat memverifikasi');
                });
            }
        };

        // ============================================
        // CHECKBOX INDIVIDUAL HANDLER
        // ============================================
        document.addEventListener('change', function(e) {
            if (e.target.classList && e.target.classList.contains('mustahik-checkbox')) {
                const uuid = e.target.getAttribute('data-uuid');
                if (e.target.checked) {
                    selectedUuids.add(uuid);
                } else {
                    selectedUuids.delete(uuid);
                }
                updateSelectedCount();
                
                const selectAll = document.getElementById('select-all-checkbox');
                if (selectAll) {
                    const allCheckboxes = document.querySelectorAll('.mustahik-checkbox');
                    selectAll.checked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
                }
            }
        });

        // ============================================
        // MODAL BUTTONS
        // ============================================
        const cancelBulkBtn = document.getElementById('cancel-bulk-verify-btn');
        if (cancelBulkBtn) {
            cancelBulkBtn.addEventListener('click', () => {
                document.getElementById('bulk-verify-modal').classList.add('hidden');
            });
        }

        const confirmBulkBtn = document.getElementById('confirm-bulk-verify-btn');
        if (confirmBulkBtn) {
            confirmBulkBtn.addEventListener('click', window.confirmBulkVerify);
        }

        // ============================================
        // FILTER PANEL
        // ============================================
        window.toggleFilter = function() {
            const panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        };

        // ============================================
        // DELETE MODAL
        // ============================================
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        let currentUuid = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                currentUuid = this.getAttribute('data-uuid');
                const name = this.getAttribute('data-name');
                document.getElementById('modal-mustahik-name').textContent = name;
                deleteForm.action = `/mustahik/${currentUuid}`;
                deleteModal.classList.remove('hidden');
            });
        });

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', () => deleteModal.classList.add('hidden'));
        }

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) deleteModal.classList.add('hidden');
        });

        // ============================================
        // TOGGLE STATUS (AKTIF/NONAKTIF)
        // ============================================
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const name = this.getAttribute('data-name');
                const isActive = this.getAttribute('data-is-active') === '1';
                const actionText = isActive ? 'Nonaktifkan' : 'Aktifkan';
                
                if (confirm(`${actionText} mustahik "${name}"?`)) {
                    fetch(`/mustahik/${uuid}/toggle-active`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        if (data.success) location.reload();
                        else alert(data.message);
                    }).catch(() => alert('Terjadi kesalahan'));
                }
            });
        });

        // ============================================
        // SINGLE VERIFY
        // ============================================
        document.querySelectorAll('.verify-single-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const name = this.getAttribute('data-name');
                if (confirm(`Verifikasi mustahik "${name}"?`)) {
                    fetch(`/mustahik/${uuid}/verify`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        if (data.success) location.reload();
                        else alert(data.message);
                    }).catch(() => alert('Terjadi kesalahan'));
                }
            });
        });
    });

    // ============================================
    // GLOBAL FUNCTIONS
    // ============================================
    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    // ============================================
    // IMPORT MODAL FUNCTIONS
    // ============================================
    function closeImportModal() {
        document.getElementById('modal-import').classList.add('hidden');
        clearImportFile();
    }

    function clearImportFile() {
        const fileInput = document.getElementById('file-input-import');
        if (fileInput) fileInput.value = '';
        document.getElementById('import-file-preview').classList.add('hidden');
        const submitBtn = document.getElementById('btn-upload-submit');
        if (submitBtn) submitBtn.disabled = true;
    }

    function onImportFileSelected(input) {
        if (input.files && input.files[0]) {
            document.getElementById('import-file-name').textContent = input.files[0].name;
            document.getElementById('import-file-preview').classList.remove('hidden');
            document.getElementById('btn-upload-submit').disabled = false;
        }
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

    // Toggle import errors
    const btnToggleErrors = document.getElementById('btn-toggle-import-errors');
    if (btnToggleErrors) {
        btnToggleErrors.addEventListener('click', function() {
            const list = document.getElementById('import-errors-list');
            if (list) {
                list.classList.toggle('hidden');
                this.textContent = list.classList.contains('hidden') ? 'Lihat detail error ▾' : 'Sembunyikan ▴';
            }
        });
    }
</script>
@endpush