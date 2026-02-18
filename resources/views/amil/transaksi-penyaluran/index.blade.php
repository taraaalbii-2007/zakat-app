{{-- resources/views/amil/transaksi-penyaluran/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Transaksi Penyaluran Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Alert: Ada transaksi disetujui menunggu konfirmasi ── --}}
        @if (isset($stats['total_disetujui']) && $stats['total_disetujui'] > 0)
            <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl animate-slide-up">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-blue-800">
                        {{ $stats['total_disetujui'] }} transaksi sudah disetujui, menunggu konfirmasi penyaluran
                    </p>
                    <p class="text-xs text-blue-600 mt-0.5">Konfirmasi setelah dana/barang sudah diserahkan ke mustahik</p>
                </div>
                <a href="{{ route('transaksi-penyaluran.index', ['status' => 'disetujui']) }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-all">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

        {{-- ── Statistics Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 animate-slide-up">
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Total Transaksi</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Menunggu Approval</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total_draft'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Disalurkan</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total_disalurkan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Total Nominal</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Hari ini: Rp {{ number_format($stats['total_hari_ini'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Transaksi Penyaluran</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Tambah --}}
                        <a href="{{ route('transaksi-penyaluran.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Tambah</span>
                        </a>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('transaksi-penyaluran.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            id="search-input" placeholder="Cari no. transaksi / mustahik..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']))
                                        <a href="{{ route('transaksi-penyaluran.index') }}"
                                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('transaksi-penyaluran.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="draft"      {{ request('status') == 'draft'      ? 'selected' : '' }}>Draft (Menunggu)</option>
                                <option value="disetujui"  {{ request('status') == 'disetujui'  ? 'selected' : '' }}>Disetujui</option>
                                <option value="disalurkan" {{ request('status') == 'disalurkan' ? 'selected' : '' }}>Disalurkan</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Penyaluran</label>
                            <select name="metode_penyaluran"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Metode</option>
                                <option value="tunai"    {{ request('metode_penyaluran') == 'tunai'    ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ request('metode_penyaluran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="barang"   {{ request('metode_penyaluran') == 'barang'   ? 'selected' : '' }}>Barang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Jenis</option>
                                @foreach ($jenisZakatList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Periode</label>
                            <input type="month" name="periode" value="{{ request('periode') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                        </div>
                    </div>

                    @if (request()->hasAny(['status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('transaksi-penyaluran.index', request('q') ? ['q' => request('q')] : []) }}"
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

            @if ($transaksis->count() > 0)

                {{-- ── Desktop View ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik &amp; Transaksi</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaksis as $transaksi)
                                @php
                                    $canEdit       = $transaksi->bisa_diedit;
                                    $canDelete     = $transaksi->bisa_dihapus;
                                    $canDisalurkan = $transaksi->bisa_disalurkan;
                                    $canApprove    = auth()->user()->peran === 'admin_masjid' && $transaksi->status === 'draft';
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $canDisalurkan ? 'bg-blue-50/30' : ($canApprove ? 'bg-yellow-50/30' : '') }}"
                                    data-target="detail-{{ $transaksi->uuid }}">
                                    <td class="px-4 py-4">
                                        <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaksi->mustahik->nama_lengkap ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $transaksi->tanggal_penyaluran->format('d/m/Y') }}
                                                @if ($transaksi->jumlah > 0)
                                                    &middot; <span class="font-semibold text-gray-700">{{ $transaksi->jumlah_formatted }}</span>
                                                @endif
                                                @if ($transaksi->metode_penyaluran === 'barang')
                                                    &middot; <span class="font-semibold text-orange-600">Barang</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $transaksi->status_badge !!}
                                                {!! $transaksi->metode_penyaluran_badge !!}
                                                @if ($canDisalurkan)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                        Perlu Konfirmasi
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $transaksi->uuid }}"
                                            data-nama="{{ $transaksi->mustahik->nama_lengkap ?? '-' }}"
                                            data-can-edit="{{ $canEdit ? '1' : '0' }}"
                                            data-can-delete="{{ $canDelete ? '1' : '0' }}"
                                            data-can-disalurkan="{{ $canDisalurkan ? '1' : '0' }}"
                                            data-can-approve="{{ $canApprove ? '1' : '0' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $transaksi->uuid }}" class="hidden expandable-content">
                                    <td colspan="3" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                    {{-- Kolom 1: Data Mustahik --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Data Mustahik</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Nama</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $transaksi->mustahik->nama_lengkap ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Kategori</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $transaksi->kategoriMustahik->nama ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($transaksi->mustahik->telepon)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Telepon</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->mustahik->telepon }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Detail Penyaluran --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Penyaluran</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Tanggal</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $transaksi->tanggal_penyaluran->format('d F Y') }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($transaksi->jenisZakat)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->jenisZakat->nama }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Jumlah</p>
                                                                    <p class="text-sm font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($transaksi->metode_penyaluran === 'barang' && $transaksi->detail_barang)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Detail Barang</p>
                                                                        <p class="text-sm text-gray-700">{{ Str::limit($transaksi->detail_barang, 80) }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Status & Penanggung Jawab --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Status &amp; Penanggung Jawab</h4>
                                                        <div class="space-y-3">
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status</p>
                                                                {!! $transaksi->status_badge !!}
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Metode</p>
                                                                {!! $transaksi->metode_penyaluran_badge !!}
                                                            </div>
                                                            @if ($transaksi->amil)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Amil</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->amil->nama_lengkap }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($transaksi->status === 'dibatalkan' && $transaksi->alasan_pembatalan)
                                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                                    <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                                    <p class="text-xs text-red-700">{{ $transaksi->alasan_pembatalan }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tombol Aksi di Expandable --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                    <div class="text-xs text-gray-500">
                                                        No. Transaksi: <span class="font-medium text-gray-700">{{ $transaksi->no_transaksi }}</span>
                                                    </div>
                                                    <div class="flex gap-2 flex-wrap">
                                                        @if ($canApprove)
                                                            <button type="button"
                                                                onclick="openApproveModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                                class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Setujui
                                                            </button>
                                                            <button type="button"
                                                                onclick="openRejectModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                Tolak
                                                            </button>
                                                        @endif
                                                        @if ($canDisalurkan)
                                                            <button type="button"
                                                                onclick="openDisalurkanModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Konfirmasi Disalurkan
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Detail
                                                        </a>
                                                        @if ($canEdit)
                                                            <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Edit
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── Mobile View ── --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($transaksis as $transaksi)
                        @php
                            $canEdit       = $transaksi->bisa_diedit;
                            $canDelete     = $transaksi->bisa_dihapus;
                            $canDisalurkan = $transaksi->bisa_disalurkan;
                            $canApprove    = auth()->user()->peran === 'admin_masjid' && $transaksi->status === 'draft';
                        @endphp
                        <div class="expandable-card {{ $canDisalurkan ? 'bg-blue-50/30' : ($canApprove ? 'bg-yellow-50/30' : '') }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $transaksi->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $transaksi->mustahik->nama_lengkap ?? '-' }}</h3>
                                            {!! $transaksi->status_badge !!}
                                        </div>
                                        <div class="flex items-center mt-1 flex-wrap gap-2">
                                            <span class="text-xs text-gray-500">{{ $transaksi->tanggal_penyaluran->format('d/m/Y') }}</span>
                                            @if ($transaksi->jumlah > 0)
                                                <span class="text-xs font-semibold text-gray-700">{{ $transaksi->jumlah_formatted }}</span>
                                            @endif
                                            {!! $transaksi->metode_penyaluran_badge !!}
                                        </div>
                                        @if ($canDisalurkan)
                                            <div class="mt-1">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">Perlu Konfirmasi</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $transaksi->uuid }}"
                                            data-nama="{{ $transaksi->mustahik->nama_lengkap ?? '-' }}"
                                            data-can-edit="{{ $canEdit ? '1' : '0' }}"
                                            data-can-delete="{{ $canDelete ? '1' : '0' }}"
                                            data-can-disalurkan="{{ $canDisalurkan ? '1' : '0' }}"
                                            data-can-approve="{{ $canApprove ? '1' : '0' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $transaksi->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Penyaluran</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                    <span class="text-gray-900">{{ $transaksi->kategoriMustahik->nama ?? '-' }}</span>
                                                </div>
                                                @if ($transaksi->jenisZakat)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                        <span class="text-gray-900">{{ $transaksi->jenisZakat->nama }}</span>
                                                    </div>
                                                @endif
                                                @if ($transaksi->jumlah > 0)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</span>
                                                    </div>
                                                @endif
                                                @if ($transaksi->metode_penyaluran === 'barang' && $transaksi->detail_barang)
                                                    <div class="flex items-start text-sm">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                        <span class="text-gray-700">{{ Str::limit($transaksi->detail_barang, 60) }}</span>
                                                    </div>
                                                @endif
                                                @if ($transaksi->status === 'dibatalkan' && $transaksi->alasan_pembatalan)
                                                    <div class="p-2 bg-red-50 border border-red-200 rounded-lg">
                                                        <p class="text-xs text-red-600 font-medium">Alasan Ditolak: {{ $transaksi->alasan_pembatalan }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 flex gap-2 flex-wrap">
                                            @if ($canApprove)
                                                <button type="button"
                                                    onclick="openApproveModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui
                                                </button>
                                                <button type="button"
                                                    onclick="openRejectModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Tolak
                                                </button>
                                            @endif
                                            @if ($canDisalurkan)
                                                <button type="button"
                                                    onclick="openDisalurkanModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Konfirmasi
                                                </button>
                                            @endif
                                            <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail
                                            </a>
                                            @if ($canEdit)
                                                <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($transaksis->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $transaksis->links() }}
                    </div>
                @endif

            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                        <a href="{{ route('transaksi-penyaluran.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Penyaluran</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai catat penyaluran zakat ke mustahik</p>
                        <a href="{{ route('transaksi-penyaluran.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Penyaluran Baru
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── Dropdown Container ── --}}
    <div id="dropdown-container" class="fixed hidden z-[9999]" style="min-width:200px;">
        <div class="w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="py-1">
                <a href="#" id="dd-detail"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>

                <div class="border-t border-gray-100 my-1" id="dd-divider-approve" style="display:none;"></div>
                <button type="button" id="dd-approve"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Setujui
                </button>
                <button type="button" id="dd-reject"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak
                </button>

                <div class="border-t border-gray-100 my-1" id="dd-divider-action" style="display:none;"></div>
                <button type="button" id="dd-disalurkan"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-blue-700 hover:bg-blue-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Konfirmasi Disalurkan
                </button>

                <a href="#" id="dd-edit"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>

                <div class="border-t border-gray-100 my-1" id="dd-divider-delete" style="display:none;"></div>
                <button type="button" id="dd-delete"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal: Setujui ── --}}
    <div id="approve-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Setujui Penyaluran</h3>
            <p class="text-sm text-gray-500 mb-2 text-center">
                Setujui transaksi penyaluran untuk
                "<span id="modal-approve-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs text-gray-400 mb-5 text-center">Status akan berubah dari <strong>Draft</strong> menjadi <strong>Disetujui</strong>.</p>
            <form method="POST" id="approve-form">
                @csrf
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('approve-modal')"
                        class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2.5 bg-green-600 text-sm font-medium text-white hover:bg-green-700 transition-colors">
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Tolak ── --}}
    <div id="reject-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Tolak Penyaluran</h3>
            <p class="text-sm text-gray-500 mb-4 text-center">
                Tolak transaksi penyaluran untuk
                "<span id="modal-reject-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <form method="POST" id="reject-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_pembatalan" rows="3" required
                        placeholder="Tuliskan alasan penolakan..."
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 transition-all resize-none"></textarea>
                </div>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('reject-modal')"
                        class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Konfirmasi Disalurkan ── --}}
    <div id="disalurkan-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Konfirmasi Penyaluran</h3>
            <p class="text-sm text-gray-500 mb-2 text-center">
                Konfirmasi bahwa dana/barang sudah diserahkan kepada
                "<span id="modal-disalurkan-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs text-gray-400 mb-5 text-center">Setelah dikonfirmasi, status tidak dapat diubah kembali.</p>
            <form method="POST" id="disalurkan-form">
                @csrf
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('disalurkan-modal')"
                        class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2.5 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Delete ── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Transaksi</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Hapus transaksi penyaluran untuk "<span id="modal-delete-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-sm text-gray-400 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <form method="POST" id="delete-form">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('delete-modal')"
                        class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Referensi elemen ──────────────────────────────────────────
        var dropdown         = document.getElementById('dropdown-container');
        var ddDetail         = document.getElementById('dd-detail');
        var ddEdit           = document.getElementById('dd-edit');
        var ddApprove        = document.getElementById('dd-approve');
        var ddReject         = document.getElementById('dd-reject');
        var ddDisalurkan     = document.getElementById('dd-disalurkan');
        var ddDelete         = document.getElementById('dd-delete');
        var ddDividerApprove = document.getElementById('dd-divider-approve');
        var ddDividerAction  = document.getElementById('dd-divider-action');
        var ddDividerDelete  = document.getElementById('dd-divider-delete');

        // ── Desktop expandable rows ───────────────────────────────────
        document.querySelectorAll('.expandable-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, .dropdown-toggle, button')) return;
                var target = document.getElementById(this.dataset.target);
                var icon   = this.querySelector('.expand-icon');
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-90');
            });
        });

        // ── Mobile expandable cards ───────────────────────────────────
        document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, .dropdown-toggle, button')) return;
                var target = document.getElementById(this.dataset.target);
                var icon   = this.querySelector('.expand-icon-mobile');
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        function show(el) { el.classList.remove('hidden'); }
        function hide(el) { el.classList.add('hidden'); }

        // ── Tutup dropdown ────────────────────────────────────────────
        function closeDropdown() {
            dropdown.classList.add('hidden');
            dropdown.removeAttribute('data-uuid');
        }

        // ── Posisikan dropdown ────────────────────────────────────────
        function positionDropdown(toggle) {
            var rect   = toggle.getBoundingClientRect();
            var ddW    = 224;
            var ddH    = dropdown.offsetHeight || 220;
            var margin = 6;
            var vpW    = window.innerWidth;
            var vpH    = window.innerHeight;

            var left = rect.right - ddW;
            if (left < margin) left = margin;
            if (left + ddW > vpW - margin) left = vpW - ddW - margin;

            var top = rect.bottom + margin;
            if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
            if (top < margin) top = margin;

            dropdown.style.top  = top  + 'px';
            dropdown.style.left = left + 'px';
        }

        // ── Event klik global ─────────────────────────────────────────
        document.addEventListener('click', function (e) {
            var toggle = e.target.closest('.dropdown-toggle');

            if (toggle) {
                e.stopPropagation();

                var uuid          = toggle.dataset.uuid;
                var nama          = toggle.dataset.nama;
                var canEdit       = toggle.dataset.canEdit       === '1';
                var canDelete     = toggle.dataset.canDelete     === '1';
                var canDisalurkan = toggle.dataset.canDisalurkan === '1';
                var canApprove    = toggle.dataset.canApprove    === '1';

                if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                    closeDropdown();
                    return;
                }

                dropdown.dataset.uuid = uuid;
                ddDetail.href = '/transaksi-penyaluran/' + uuid;

                // Approve / Reject
                if (canApprove) {
                    ddDividerApprove.style.display = '';
                    show(ddApprove);
                    show(ddReject);
                    ddApprove.onclick = function () { closeDropdown(); openApproveModal(uuid, nama); };
                    ddReject.onclick  = function () { closeDropdown(); openRejectModal(uuid, nama); };
                } else {
                    ddDividerApprove.style.display = 'none';
                    hide(ddApprove);
                    hide(ddReject);
                }

                // Konfirmasi Disalurkan
                if (canDisalurkan) {
                    ddDividerAction.style.display = '';
                    show(ddDisalurkan);
                    ddDisalurkan.onclick = function () { closeDropdown(); openDisalurkanModal(uuid, nama); };
                } else {
                    ddDividerAction.style.display = 'none';
                    hide(ddDisalurkan);
                }

                // Edit
                canEdit
                    ? (ddEdit.href = '/transaksi-penyaluran/' + uuid + '/edit', show(ddEdit))
                    : hide(ddEdit);

                // Delete
                if (canDelete) {
                    show(ddDelete);
                    ddDividerDelete.style.display = '';
                    ddDelete.onclick = function () { closeDropdown(); openDeleteModal(uuid, nama); };
                } else {
                    hide(ddDelete);
                    ddDividerDelete.style.display = 'none';
                }

                dropdown.classList.remove('hidden');
                positionDropdown(toggle);

            } else if (!dropdown.contains(e.target)) {
                closeDropdown();
            }
        });

        window.addEventListener('scroll', closeDropdown, true);
        window.addEventListener('resize', closeDropdown);

        // ── Modal: Setujui ───────────────────────────────────────────
        function openApproveModalInner(uuid, nama) {
            document.getElementById('modal-approve-nama').textContent = nama;
            document.getElementById('approve-form').action = '/transaksi-penyaluran/' + uuid + '/approve';
            openModal('approve-modal');
        }

        // ── Modal: Tolak ─────────────────────────────────────────────
        function openRejectModalInner(uuid, nama) {
            document.getElementById('modal-reject-nama').textContent = nama;
            document.getElementById('reject-form').action = '/transaksi-penyaluran/' + uuid + '/reject';
            document.querySelector('#reject-form textarea').value = '';
            openModal('reject-modal');
        }

        // ── Modal: Konfirmasi Disalurkan ─────────────────────────────
        function openDisalurkanModalInner(uuid, nama) {
            document.getElementById('modal-disalurkan-nama').textContent = nama;
            document.getElementById('disalurkan-form').action = '/transaksi-penyaluran/' + uuid + '/konfirmasi-disalurkan';
            openModal('disalurkan-modal');
        }

        // ── Modal: Delete ─────────────────────────────────────────────
        function openDeleteModalInner(uuid, nama) {
            document.getElementById('modal-delete-nama').textContent = nama;
            document.getElementById('delete-form').action = '/transaksi-penyaluran/' + uuid;
            openModal('delete-modal');
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Backdrop click menutup modal
        ['approve-modal', 'reject-modal', 'disalurkan-modal', 'delete-modal'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function (e) {
                    if (e.target === this) closeModal(id);
                });
            }
        });

        window.openApproveModal    = openApproveModalInner;
        window.openRejectModal     = openRejectModalInner;
        window.openDisalurkanModal = openDisalurkanModalInner;
        window.openDeleteModal     = openDeleteModalInner;
    });

    // ── Global closeModal ─────────────────────────────────────────────
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Search & Filter ───────────────────────────────────────────────
    function toggleSearch() {
        var btn       = document.getElementById('search-button');
        var form      = document.getElementById('search-form');
        var input     = document.getElementById('search-input');
        var container = document.getElementById('search-container');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            container.style.minWidth = '280px';
            setTimeout(function () { input.focus(); }, 50);
        } else {
            form.classList.add('hidden');
            btn.classList.remove('hidden');
            container.style.minWidth = '';
        }
    }

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal('approve-modal');
            closeModal('reject-modal');
            closeModal('disalurkan-modal');
            closeModal('delete-modal');
        }
    });
</script>
@endpush