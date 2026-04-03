{{-- resources/views/admin-lembaga/mustahik/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Data Mustahik')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Import Error Alert ─────────────────────────────────────────── --}}
        @if (session('import_errors') && count(session('import_errors')) > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl px-5 py-4">
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

        {{-- ── Main Card ──────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Card Header ──────────────────────────────────────────── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Mustahik</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $mustahiks->total() }} Mustahik</p>
                        @if (($permissions['pendingCount'] ?? 0) > 0)
                            <p class="text-xs text-yellow-600 mt-0.5">
                                {{ $permissions['pendingCount'] }} mustahik menunggu verifikasi
                            </p>
                        @endif
                    </div>

                    {{-- ── Action Buttons ──────────────────────────────── --}}
                    <div class="grid grid-cols-1 gap-2 sm:flex sm:flex-row sm:gap-2">
                        {{-- Tambah Mustahik --}}
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.create') }}"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600
                   text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="ml-2">Tambah</span>
                            </a>
                        @endif

                        {{-- Download Template --}}
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.import.template') }}"
                                class="group inline-flex items-center justify-center px-3 py-2
                   bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium
                   rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                               a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="ml-2">Template</span>
                            </a>
                        @endif

                        {{-- Import Data --}}
                        @if ($permissions['canCreate'])
                            <button type="button"
                                onclick="document.getElementById('modal-import').classList.remove('hidden')"
                                class="group inline-flex items-center justify-center px-3 py-2
                   bg-green-600 hover:bg-green-700 text-white text-sm font-medium
                   rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 11l3 3m0 0l3-3m-3 3V4" />
                                </svg>
                                <span class="ml-2">Import</span>
                            </button>
                        @endif

                        {{-- Export Excel --}}
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.export.excel', request()->query()) }}"
                                class="group inline-flex items-center justify-center px-3 py-2
                   bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium
                   rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                       a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="ml-2">Export</span>
                            </a>
                        @endif

                        {{-- Verifikasi Semua Pending --}}
                        @if (($permissions['canVerifyAll'] ?? false) && ($permissions['pendingCount'] ?? 0) > 0)
                            <button type="button" onclick="confirmVerifyAllPending()"
                                class="group inline-flex items-center justify-center px-3 py-2
                       bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                       rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="ml-2">Verifikasi Semua Pending ({{ $permissions['pendingCount'] }})</span>
                            </button>
                        @endif

                        {{-- Bulk Verify Button --}}
                        @if ($permissions['canBulkVerify'] ?? false)
                            <button type="button" id="bulk-verify-btn" onclick="bulkVerifySelected()"
                                class="hidden group inline-flex items-center justify-center px-3 py-2
                       bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium
                       rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="ml-2">Verifikasi Terpilih (<span id="selected-count">0</span>)</span>
                            </button>
                        @endif

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2
               bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium
               rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="ml-2">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2
                   bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium
                   rounded-lg transition-all w-full {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span id="search-button-text" class="ml-2">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('mustahik.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if (request('kategori_id'))
                                    <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                                @endif
                                @if (request('status_verifikasi'))
                                    <input type="hidden" name="status_verifikasi"
                                        value="{{ request('status_verifikasi') }}">
                                @endif
                                @if (request('is_active'))
                                    <input type="hidden" name="is_active" value="{{ request('is_active') }}">
                                @endif
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            id="search-input" placeholder="Cari nama, NIK, no registrasi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white
                               placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary
                               focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ──────────────────────────────────────────── --}}
            <div id="filter-panel"
                class="{{ request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin') ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('mustahik.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Verifikasi</label>
                            <select name="status_verifikasi"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status_verifikasi') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="verified"
                                    {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected"
                                    {{ request('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Aktif</label>
                            <select name="is_active"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white
                                       focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>
                    </div>
                    @if (request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('mustahik.index', request('q') ? ['q' => request('q')] : []) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600
                                       hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- ── Active Search Badge ───────────────────────────────────── --}}
            @if ($mustahiks->count() > 0 && request('q'))
                <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                    <div class="flex items-center flex-wrap gap-2">
                        <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            Pencarian: "{{ request('q') }}"
                            <button type="button" onclick="removeFilter('q')"
                                class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                        </span>
                    </div>
                </div>
            @endif

            {{-- ── Table / Cards / Empty ─────────────────────────────────── --}}
            @if ($mustahiks->count() > 0)

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if ($permissions['canBulkVerify'] ?? false)
                                    <th class="px-4 py-3 text-left">
                                        <input type="checkbox" id="select-all-checkbox"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            onclick="toggleSelectAll(this)">
                                    </th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Registrasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mustahik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($mustahiks as $item)
                                @php
                                    $actions = $item->actions;
                                    $colors = [
                                        'bg-blue-500',
                                        'bg-green-500',
                                        'bg-yellow-500',
                                        'bg-red-500',
                                        'bg-purple-500',
                                        'bg-pink-500',
                                        'bg-indigo-500',
                                        'bg-orange-500',
                                        'bg-teal-500',
                                        'bg-cyan-500',
                                        'bg-emerald-500',
                                        'bg-rose-500',
                                    ];
                                    $initial = strtoupper(substr($item->nama_lengkap, 0, 1));
                                    $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                                    $canVerifyInRow =
                                        $item->status_verifikasi === 'pending' &&
                                        ($permissions['canBulkVerify'] ?? false);
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors" data-mustahik-uuid="{{ $item->uuid }}"
                                    data-status="{{ $item->status_verifikasi }}">
                                    @if ($permissions['canBulkVerify'] ?? false)
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if ($canVerifyInRow)
                                                <input type="checkbox"
                                                    class="mustahik-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                    data-uuid="{{ $item->uuid }}"
                                                    data-name="{{ $item->nama_lengkap }}">
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->no_registrasi }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item->tanggal_registrasi->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                    <span
                                                        class="text-sm font-medium text-white">{{ $initial }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    NIK: {{ $item->nik ?? '-' }}
                                                    @if ($item->telepon)
                                                        | {{ $item->telepon }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->kategoriMustahik->nama }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ Str::limit($item->alamat, 40) }}</div>
                                        @if ($item->rt_rw)
                                            <div class="text-xs text-gray-500">RT/RW: {{ $item->rt_rw }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            {!! $item->status_badge !!}
                                            {!! $item->active_badge !!}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button type="button" data-dropdown-toggle="{{ $item->uuid }}"
                                            data-nama="{{ $item->nama_lengkap }}"
                                            data-actions="{{ json_encode($actions) }}"
                                            data-status="{{ $item->status_verifikasi }}"
                                            data-is-active="{{ $item->is_active }}"
                                            data-user-role="{{ $permissions['userRole'] }}"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400
                                                   hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                </path>
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
                    @foreach ($mustahiks as $item)
                        @php
                            $actions = $item->actions;
                            $colors = [
                                'bg-blue-500',
                                'bg-green-500',
                                'bg-yellow-500',
                                'bg-red-500',
                                'bg-purple-500',
                                'bg-pink-500',
                                'bg-indigo-500',
                                'bg-orange-500',
                                'bg-teal-500',
                                'bg-cyan-500',
                                'bg-emerald-500',
                                'bg-rose-500',
                            ];
                            $initial = strtoupper(substr($item->nama_lengkap, 0, 1));
                            $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                            $canVerifyInRow =
                                $item->status_verifikasi === 'pending' && ($permissions['canBulkVerify'] ?? false);
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors" data-mustahik-uuid="{{ $item->uuid }}"
                            data-status="{{ $item->status_verifikasi }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    @if (($permissions['canBulkVerify'] ?? false) && $canVerifyInRow)
                                        <div class="flex-shrink-0 pt-1">
                                            <input type="checkbox"
                                                class="mustahik-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mt-1"
                                                data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama_lengkap }}">
                                        </div>
                                    @endif
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                            <span class="text-base font-medium text-white">{{ $initial }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->nama_lengkap }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $item->no_registrasi }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($item->alamat, 50) }}</p>
                                        <div class="flex flex-wrap gap-1 mt-1.5">
                                            {!! $item->status_badge !!}
                                            {!! $item->active_badge !!}
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $item->kategoriMustahik->nama }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" data-dropdown-toggle="{{ $item->uuid }}"
                                    data-nama="{{ $item->nama_lengkap }}" data-actions="{{ json_encode($actions) }}"
                                    data-status="{{ $item->status_verifikasi }}" data-is-active="{{ $item->is_active }}"
                                    data-user-role="{{ $permissions['userRole'] }}"
                                    class="dropdown-toggle flex-shrink-0 ml-2 inline-flex items-center p-1.5
                                           text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($mustahiks->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $mustahiks->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="p-8 sm:p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    @if (request('q') || request('kategori_id') || request('status_verifikasi') || request('is_active'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if (request('q'))
                                Tidak ada mustahik yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada mustahik yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('mustahik.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700
                                   text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Mustahik</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan data mustahik penerima zakat.</p>
                        @if ($permissions['canCreate'])
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('mustahik.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white
                                           text-sm font-medium rounded-lg transition-all shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Mustahik
                                </a>
                                <button type="button"
                                    onclick="document.getElementById('modal-import').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white
                                           text-sm font-medium rounded-lg transition-all shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 11l3 3m0 0l3-3m-3 3V4" />
                                    </svg>
                                    Import dari Excel
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>{{-- end main card --}}
    </div>{{-- end space-y --}}


    {{-- ════════════════════════════════════════════════════════════════
         DROPDOWN MENU
    ════════════════════════════════════════════════════════════════ --}}
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#" id="dropdown-view-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>

                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>

                <button type="button" id="dropdown-verify-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verifikasi
                </button>

                <button type="button" id="dropdown-reject-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak
                </button>

                <button type="button" id="dropdown-toggle-active-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="toggle-active-text">Aktifkan</span>
                </button>

                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════════════
         MODAL — DELETE
    ════════════════════════════════════════════════════════════════ --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Mustahik</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus mustahik
                "<span id="modal-mustahik-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white
                           text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600
                               text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════════════
         MODAL — REJECT / TOLAK VERIFIKASI
    ════════════════════════════════════════════════════════════════ --}}
    <div id="reject-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-md shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Tolak Verifikasi
                Mustahik</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Tolak verifikasi mustahik
                "<span id="modal-reject-mustahik-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <div class="mt-4">
                <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="3" placeholder="Masukkan alasan penolakan..."
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    required></textarea>
            </div>
            <div class="flex justify-center gap-2 sm:gap-3 mt-6">
                <button type="button" id="cancel-reject-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white
                           text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-reject-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600
                           text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Tolak
                </button>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════════════
         MODAL — BULK VERIFY CONFIRMATION
    ════════════════════════════════════════════════════════════════ --}}
    <div id="bulk-verify-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-md shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-indigo-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Verifikasi Massal</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin memverifikasi
                <span id="bulk-count" class="font-semibold text-indigo-600"></span> mustahik?
            </p>
            <p class="text-xs text-gray-500 mb-5 sm:mb-6 text-center">
                Mustahik yang dipilih akan diverifikasi dan dapat digunakan untuk penyaluran.
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-bulk-verify-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white
                           text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-bulk-verify-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-indigo-600
                           text-xs sm:text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    Verifikasi
                </button>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════════════
         MODAL — VERIFY ALL PENDING CONFIRMATION
    ════════════════════════════════════════════════════════════════ --}}
    <div id="verify-all-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-md shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-indigo-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Verifikasi Semua
                Pending</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin memverifikasi SEMUA mustahik dengan status pending?
            </p>
            <p class="text-xs text-gray-500 mb-5 sm:mb-6 text-center">
                <span id="verify-all-count" class="font-semibold text-indigo-600"></span> mustahik akan diverifikasi.
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-verify-all-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white
                           text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-verify-all-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-indigo-600
                           text-xs sm:text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    Verifikasi Semua
                </button>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════════════
         MODAL — IMPORT DATA DARI EXCEL
    ════════════════════════════════════════════════════════════════ --}}
    <div id="modal-import"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-md">

            {{-- Header — TANPA tombol X --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 11l3 3m0 0l3-3m-3 3V4" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Import Data Mustahik</h3>
                </div>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('mustahik.import.upload') }}" enctype="multipart/form-data"
                id="form-upload-import">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    {{-- Panduan --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 text-xs text-blue-700 space-y-1">
                        <p class="font-semibold text-blue-800">Panduan Import:</p>
                        <p>• Download template terlebih dahulu dari tombol <strong>Template</strong> di halaman ini.</p>
                        <p>• Isi data sesuai format, lalu upload file di sini.</p>
                        <p>• Format file: .xlsx atau .xls (maks. 500 MB).</p>
                        <p>• Setelah upload, Anda akan diarahkan ke halaman pemetaan kolom.</p>
                    </div>

                    {{-- Drop Zone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            File Excel <span class="text-red-500">*</span>
                        </label>
                        <div id="import-drop-zone"
                            class="relative flex flex-col items-center justify-center w-full h-36
                                   border-2 border-dashed border-gray-300 rounded-xl
                                   hover:border-green-400 hover:bg-green-50/50
                                   transition-all cursor-pointer bg-gray-50"
                            onclick="document.getElementById('file-input-import').click()">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                               a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 text-center px-4">Klik atau seret file Excel ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">.xlsx / .xls — maks. 500 MB</p>
                            <input type="file" name="file_import" id="file-input-import" accept=".xlsx,.xls"
                                class="hidden" required onchange="onImportFileSelected(this)">
                        </div>

                        {{-- File preview --}}
                        <div id="import-file-preview"
                            class="hidden mt-2 flex items-center gap-2 text-sm text-green-700 font-medium
                                   bg-green-50 border border-green-100 rounded-lg px-3 py-2">
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

                    {{-- Error validasi upload --}}
                    @error('file_import')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Footer — TANPA link Download Template --}}
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300
                               rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-upload-submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white
                               bg-green-600 hover:bg-green-700 rounded-xl shadow-sm transition-all
                               disabled:opacity-50 disabled:cursor-not-allowed"
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

@endsection

@push('scripts')
    <script>
        // ══════════════════════════════════════════════════════════
        // DEFINE FUNCTIONS IN GLOBAL SCOPE
        // ══════════════════════════════════════════════════════════

        // IMPORT MODAL HELPERS (MUST BE GLOBAL)
        window.closeImportModal = function() {
            const modal = document.getElementById('modal-import');
            if (modal) modal.classList.add('hidden');
            window.clearImportFile();
        };

        window.clearImportFile = function() {
            const input = document.getElementById('file-input-import');
            const preview = document.getElementById('import-file-preview');
            const btnSubmit = document.getElementById('btn-upload-submit');
            if (input) input.value = '';
            if (preview) preview.classList.add('hidden');
            if (btnSubmit) btnSubmit.disabled = true;
        };

        window.onImportFileSelected = function(input) {
            const preview = document.getElementById('import-file-preview');
            const nameSpan = document.getElementById('import-file-name');
            const btnSubmit = document.getElementById('btn-upload-submit');

            if (input.files && input.files[0]) {
                if (nameSpan) nameSpan.textContent = input.files[0].name;
                if (preview) preview.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        };

        // SEARCH & FILTER
        window.toggleSearch = function() {
            const searchButton = document.getElementById('search-button');
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');
            if (searchForm && searchForm.classList.contains('hidden')) {
                if (searchButton) searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                if (searchContainer) searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput && searchInput.focus(), 50);
            } else if (searchForm) {
                if (!'{{ request('q') }}' && searchInput) searchInput.value = '';
                searchForm.classList.add('hidden');
                if (searchButton) searchButton.classList.remove('hidden');
                if (searchContainer) searchContainer.style.minWidth = 'auto';
            }
        };

        window.toggleFilter = function() {
            const panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        };

        window.removeFilter = function(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        // BULK VERIFY FUNCTIONS
        let selectedUuids = new Set();

        window.updateSelectedCount = function() {
            const count = selectedUuids.size;
            const selectedCountSpan = document.getElementById('selected-count');
            const bulkBtn = document.getElementById('bulk-verify-btn');
            if (selectedCountSpan) selectedCountSpan.innerText = count;
            if (bulkBtn) {
                if (count > 0) {
                    bulkBtn.classList.remove('hidden');
                } else {
                    bulkBtn.classList.add('hidden');
                }
            }
        };

        window.toggleSelectAll = function(checkbox) {
            const checkboxes = document.querySelectorAll('.mustahik-checkbox');
            if (checkbox.checked) {
                checkboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.checked = true;
                        const uuid = cb.getAttribute('data-uuid');
                        if (uuid) selectedUuids.add(uuid);
                    }
                });
            } else {
                checkboxes.forEach(cb => {
                    cb.checked = false;
                    const uuid = cb.getAttribute('data-uuid');
                    if (uuid) selectedUuids.delete(uuid);
                });
            }
            window.updateSelectedCount();
        };

        window.bulkVerifySelected = function() {
            const count = selectedUuids.size;
            if (count === 0) {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show('Tidak ada mustahik yang dipilih', 'warning');
                } else {
                    alert('Tidak ada mustahik yang dipilih');
                }
                return;
            }

            const countSpan = document.getElementById('bulk-count');
            const modal = document.getElementById('bulk-verify-modal');
            if (countSpan) countSpan.innerText = count;
            if (modal) modal.classList.remove('hidden');
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
                    body: JSON.stringify({
                        uuids: uuids
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (typeof ToastNotification !== 'undefined') {
                        ToastNotification.show(data.message, data.success ? 'success' : 'error');
                    } else {
                        alert(data.message);
                    }
                    if (data.success) {
                        selectedUuids.clear();
                        window.updateSelectedCount();
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(() => {
                    if (typeof ToastNotification !== 'undefined') {
                        ToastNotification.show('Terjadi kesalahan saat memverifikasi', 'error');
                    } else {
                        alert('Terjadi kesalahan saat memverifikasi');
                    }
                });

            const modal = document.getElementById('bulk-verify-modal');
            if (modal) modal.classList.add('hidden');
        };

        window.confirmVerifyAllPending = function() {
            const count = {{ $permissions['pendingCount'] ?? 0 }};
            if (count === 0) {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show('Tidak ada mustahik dengan status pending', 'warning');
                } else {
                    alert('Tidak ada mustahik dengan status pending');
                }
                return;
            }
            const countSpan = document.getElementById('verify-all-count');
            const modal = document.getElementById('verify-all-modal');
            if (countSpan) countSpan.innerText = count;
            if (modal) modal.classList.remove('hidden');
        };

        window.executeVerifyAllPending = function() {
            fetch('{{ route('mustahik.verify-all-pending') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (typeof ToastNotification !== 'undefined') {
                        ToastNotification.show(data.message, data.success ? 'success' : 'error');
                    } else {
                        alert(data.message);
                    }
                    if (data.success) {
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(() => {
                    if (typeof ToastNotification !== 'undefined') {
                        ToastNotification.show('Terjadi kesalahan saat memverifikasi', 'error');
                    } else {
                        alert('Terjadi kesalahan saat memverifikasi');
                    }
                });

            const modal = document.getElementById('verify-all-modal');
            if (modal) modal.classList.add('hidden');
        };

        // API ACTIONS
        window.verifyMustahik = function(uuid) {
            fetch(`/mustahik/${uuid}/verify`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(data => {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show(data.message, data.success ? 'success' : 'error');
                } else {
                    alert(data.message);
                }
                if (data.success) setTimeout(() => location.reload(), 1500);
            }).catch(() => {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show('Terjadi kesalahan saat memverifikasi', 'error');
                } else {
                    alert('Terjadi kesalahan saat memverifikasi');
                }
            });
        };

        window.rejectMustahik = function(uuid, alasan) {
            fetch(`/mustahik/${uuid}/reject`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    alasan_penolakan: alasan
                })
            }).then(r => r.json()).then(data => {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show(data.message, data.success ? 'success' : 'error');
                } else {
                    alert(data.message);
                }
                if (data.success) {
                    const modal = document.getElementById('reject-modal');
                    if (modal) modal.classList.add('hidden');
                    setTimeout(() => location.reload(), 1500);
                }
            }).catch(() => {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show('Terjadi kesalahan saat menolak', 'error');
                } else {
                    alert('Terjadi kesalahan saat menolak');
                }
            });
        };

        window.toggleActiveMustahik = function(uuid) {
            fetch(`/mustahik/${uuid}/toggle-active`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(data => {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show(data.message, data.success ? 'success' : 'error');
                } else {
                    alert(data.message);
                }
                if (data.success) setTimeout(() => location.reload(), 1500);
            }).catch(() => {
                if (typeof ToastNotification !== 'undefined') {
                    ToastNotification.show('Terjadi kesalahan saat mengubah status', 'error');
                } else {
                    alert('Terjadi kesalahan saat mengubah status');
                }
            });
        };

        // ══════════════════════════════════════════════════════════
        // DOMContentLoaded EVENT
        // ══════════════════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function() {
            // DROPDOWN LOGIC
            const dropdownContainer = document.getElementById('dropdown-container');
            const viewLink = document.getElementById('dropdown-view-link');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const verifyBtn = document.getElementById('dropdown-verify-btn');
            const rejectBtn = document.getElementById('dropdown-reject-btn');
            const toggleActiveBtn = document.getElementById('dropdown-toggle-active-btn');
            const tableContainer = document.getElementById('table-container');

            let currentDropdownData = null;
            let currentMustahikUuid = null;

            // Modal buttons
            const cancelBulkBtn = document.getElementById('cancel-bulk-verify-btn');
            const confirmBulkBtn = document.getElementById('confirm-bulk-verify-btn');
            const cancelVerifyAllBtn = document.getElementById('cancel-verify-all-btn');
            const confirmVerifyAllBtn = document.getElementById('confirm-verify-all-btn');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const confirmRejectBtn = document.getElementById('confirm-reject-btn');
            const cancelRejectBtn = document.getElementById('cancel-reject-btn');

            if (cancelBulkBtn) {
                cancelBulkBtn.addEventListener('click', () => {
                    const modal = document.getElementById('bulk-verify-modal');
                    if (modal) modal.classList.add('hidden');
                });
            }
            if (confirmBulkBtn) {
                confirmBulkBtn.addEventListener('click', window.confirmBulkVerify);
            }
            if (cancelVerifyAllBtn) {
                cancelVerifyAllBtn.addEventListener('click', () => {
                    const modal = document.getElementById('verify-all-modal');
                    if (modal) modal.classList.add('hidden');
                });
            }
            if (confirmVerifyAllBtn) {
                confirmVerifyAllBtn.addEventListener('click', window.executeVerifyAllPending);
            }

            // Event listener untuk checkbox individual
            document.addEventListener('change', function(e) {
                if (e.target.classList && e.target.classList.contains('mustahik-checkbox')) {
                    const uuid = e.target.getAttribute('data-uuid');
                    if (e.target.checked) {
                        selectedUuids.add(uuid);
                    } else {
                        selectedUuids.delete(uuid);
                    }
                    window.updateSelectedCount();

                    // Update select all checkbox
                    const allCheckboxes = document.querySelectorAll('.mustahik-checkbox');
                    const selectAllCheckbox = document.getElementById('select-all-checkbox');
                    if (selectAllCheckbox) {
                        const allChecked = Array.from(allCheckboxes).length > 0 &&
                            Array.from(allCheckboxes).every(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                }
            });

            // ── Open/Close dropdown ─────────────────────────────
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();

                    const dropdownUuid = toggle.getAttribute('data-dropdown-toggle');
                    const mustahikName = toggle.getAttribute('data-nama');
                    const actions = JSON.parse(toggle.getAttribute('data-actions') || '{}');
                    const status = toggle.getAttribute('data-status');
                    const isActive = toggle.getAttribute('data-is-active') === '1';
                    const userRole = toggle.getAttribute('data-user-role');

                    if (dropdownContainer && dropdownContainer.getAttribute('data-current-uuid') ===
                        dropdownUuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    currentMustahikUuid = dropdownUuid;
                    currentDropdownData = {
                        uuid: dropdownUuid,
                        name: mustahikName,
                        actions,
                        status,
                        isActive,
                        userRole
                    };
                    if (dropdownContainer) dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);

                    // ── POSISI DROPDOWN (FIXED) ──────────────────
                    // getBoundingClientRect() sudah relatif ke viewport,
                    // dan dropdown pakai position:fixed → JANGAN tambah scrollY/scrollX
                    const rect = toggle.getBoundingClientRect();
                    const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
                    const dropdownHeight = 220;

                    // Rata kanan dengan tombol, 4px gap di bawah
                    let top = rect.bottom + 4;
                    let left = rect.right - dropdownWidth;

                    // Jaga agar tidak keluar layar kiri
                    if (left < 4) left = 4;

                    // Jaga agar tidak keluar layar kanan
                    if (left + dropdownWidth > window.innerWidth) {
                        left = window.innerWidth - dropdownWidth - 10;
                    }

                    // Jika tidak cukup ruang di bawah → tampilkan di atas tombol
                    if (rect.bottom + dropdownHeight > window.innerHeight) {
                        top = rect.top - dropdownHeight - 4;
                    }

                    // Jaga agar tidak keluar layar atas
                    if (top < 4) top = 4;

                    if (dropdownContainer) {
                        dropdownContainer.style.top = top + 'px';
                        dropdownContainer.style.left = left + 'px';
                    }

                    // Set links & visibility
                    if (viewLink) viewLink.href = `/mustahik/${dropdownUuid}`;
                    if (editLink) {
                        if (actions.can_edit) {
                            editLink.href = `/mustahik/${dropdownUuid}/edit`;
                            editLink.classList.remove('hidden');
                        } else {
                            editLink.classList.add('hidden');
                        }
                    }
                    if (verifyBtn) verifyBtn.classList.toggle('hidden', !actions.can_verify);
                    if (rejectBtn) rejectBtn.classList.toggle('hidden', !actions.can_reject);

                    if (toggleActiveBtn) {
                        if (actions.can_toggle_active) {
                            toggleActiveBtn.classList.remove('hidden');
                            const toggleText = document.getElementById('toggle-active-text');
                            if (toggleText) toggleText.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
                        } else {
                            toggleActiveBtn.classList.add('hidden');
                        }
                    }
                    if (deleteBtn) deleteBtn.classList.toggle('hidden', !actions.can_delete);
                    if (dropdownContainer) dropdownContainer.classList.remove('hidden');

                } else if (dropdownContainer && !dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });

            // ── Hapus ───────────────────────────────────────────
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    if (!currentDropdownData) return;
                    if (dropdownContainer) dropdownContainer.classList.add('hidden');
                    const nameSpan = document.getElementById('modal-mustahik-name');
                    const deleteForm = document.getElementById('delete-form');
                    const deleteModal = document.getElementById('delete-modal');
                    if (nameSpan) nameSpan.textContent = currentDropdownData.name;
                    if (deleteForm) deleteForm.action = `/mustahik/${currentMustahikUuid}`;
                    if (deleteModal) deleteModal.classList.remove('hidden');
                });
            }

            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', () => {
                    const modal = document.getElementById('delete-modal');
                    if (modal) modal.classList.add('hidden');
                });
            }

            const deleteModal = document.getElementById('delete-modal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            }

            // ── Verifikasi ──────────────────────────────────────
            if (verifyBtn) {
                verifyBtn.addEventListener('click', function() {
                    if (!currentMustahikUuid) return;
                    if (dropdownContainer) dropdownContainer.classList.add('hidden');
                    if (confirm('Verifikasi mustahik ini?')) window.verifyMustahik(currentMustahikUuid);
                });
            }

            // ── Tolak ───────────────────────────────────────────
            if (rejectBtn) {
                rejectBtn.addEventListener('click', function() {
                    if (!currentDropdownData) return;
                    if (dropdownContainer) dropdownContainer.classList.add('hidden');
                    const nameSpan = document.getElementById('modal-reject-mustahik-name');
                    const alasanTextarea = document.getElementById('alasan_penolakan');
                    const rejectModal = document.getElementById('reject-modal');
                    if (nameSpan) nameSpan.textContent = currentDropdownData.name;
                    if (alasanTextarea) alasanTextarea.value = '';
                    if (rejectModal) rejectModal.classList.remove('hidden');
                });
            }

            if (confirmRejectBtn) {
                confirmRejectBtn.addEventListener('click', function() {
                    if (!currentMustahikUuid) return;
                    const alasan = document.getElementById('alasan_penolakan');
                    if (!alasan || !alasan.value.trim()) {
                        if (typeof ToastNotification !== 'undefined') {
                            ToastNotification.show('Harap masukkan alasan penolakan', 'warning');
                        } else {
                            alert('Harap masukkan alasan penolakan');
                        }
                        return;
                    }
                    window.rejectMustahik(currentMustahikUuid, alasan.value.trim());
                });
            }

            if (cancelRejectBtn) {
                cancelRejectBtn.addEventListener('click', () => {
                    const modal = document.getElementById('reject-modal');
                    if (modal) modal.classList.add('hidden');
                });
            }

            const rejectModal = document.getElementById('reject-modal');
            if (rejectModal) {
                rejectModal.addEventListener('click', function(e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            }

            // ── Toggle Aktif ────────────────────────────────────
            if (toggleActiveBtn) {
                toggleActiveBtn.addEventListener('click', function() {
                    if (!currentMustahikUuid) return;
                    if (dropdownContainer) dropdownContainer.classList.add('hidden');
                    const actionText = currentDropdownData.isActive ? 'Nonaktifkan' : 'Aktifkan';
                    if (confirm(`${actionText} mustahik ini?`)) window.toggleActiveMustahik(currentMustahikUuid);
                });
            }

            // ── Hide dropdown on scroll/resize ──────────────────
            const hideDropdown = () => {
                if (dropdownContainer && !dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            };
            window.addEventListener('scroll', hideDropdown, true);
            window.addEventListener('resize', hideDropdown);
            if (tableContainer) tableContainer.addEventListener('scroll', hideDropdown, true);

            // ── Import error toggle ──────────────────────────────
            const btnToggleErrors = document.getElementById('btn-toggle-import-errors');
            if (btnToggleErrors) {
                btnToggleErrors.addEventListener('click', function() {
                    const list = document.getElementById('import-errors-list');
                    if (list) {
                        list.classList.toggle('hidden');
                        this.textContent = list.classList.contains('hidden') ? 'Lihat detail error ▾' :
                            'Sembunyikan ▴';
                    }
                });
            }

            // ── Close modals on backdrop click ───────────────────
            const importModal = document.getElementById('modal-import');
            if (importModal) {
                importModal.addEventListener('click', function(e) {
                    if (e.target === this) window.closeImportModal();
                });
            }

            // ── Drag & drop for import ──────────────────────────
            const zone = document.getElementById('import-drop-zone');
            const input = document.getElementById('file-input-import');
            if (zone && input) {
                ['dragenter', 'dragover'].forEach(evt => {
                    zone.addEventListener(evt, e => {
                        e.preventDefault();
                        zone.classList.add('border-green-400', 'bg-green-50');
                    });
                });
                ['dragleave', 'drop'].forEach(evt => {
                    zone.addEventListener(evt, e => {
                        e.preventDefault();
                        zone.classList.remove('border-green-400', 'bg-green-50');
                    });
                });
                zone.addEventListener('drop', e => {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        input.files = files;
                        window.onImportFileSelected(input);
                    }
                });
            }
        });
    </script>
@endpush
