{{-- resources/views/amil/transaksi-penerimaan/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Transaksi Penerimaan Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Alert: Ada transaksi menunggu konfirmasi ── --}}
        @if (isset($stats['menunggu_konfirmasi']) && $stats['menunggu_konfirmasi'] > 0)
            <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl animate-slide-up">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-amber-800">
                        {{ $stats['menunggu_konfirmasi'] }} transaksi menunggu konfirmasi pembayaran
                    </p>
                    <p class="text-xs text-amber-600 mt-0.5">Periksa bukti transfer / screenshot QRIS yang dikirim muzakki</p>
                </div>
                <a href="{{ route('transaksi-penerimaan.index', ['konfirmasi_status' => 'menunggu_konfirmasi']) }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
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
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Terverifikasi</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total_verified'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Pending</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total_pending'], 0, ',', '.') }}</p>
                        @if (isset($stats['menunggu_konfirmasi']) && $stats['menunggu_konfirmasi'] > 0)
                            <p class="text-xs text-amber-600 mt-0.5">
                                {{ $stats['menunggu_konfirmasi'] }} menunggu konfirmasi
                            </p>
                        @endif
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
                        {{-- ▼ BARU: tampilkan infaq jika ada --}}
                        @if (isset($stats['total_infaq']) && $stats['total_infaq'] > 0)
                            <p class="text-xs text-amber-600 mt-0.5">+Infaq: Rp {{ number_format($stats['total_infaq'], 0, ',', '.') }}</p>
                        @endif
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
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Transaksi Penerimaan</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        {{-- Tambah --}}
                        <a href="{{ route('transaksi-penerimaan.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Tambah</span>
                        </a>

                        {{-- Export --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2">Export</span>
                                <svg class="w-4 h-4 ml-1" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('transaksi-penerimaan.export.pdf', request()->query()) }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Export PDF
                                    </a>
                                    <a href="{{ route('transaksi-penerimaan.export.excel', request()->query()) }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan', 'konfirmasi_status']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('transaksi-penerimaan.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan', 'konfirmasi_status'] as $filter)
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
                                            id="search-input" placeholder="Cari transaksi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan', 'konfirmasi_status']))
                                        <a href="{{ route('transaksi-penerimaan.index') }}"
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
                class="{{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan', 'konfirmasi_status']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('transaksi-penerimaan.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Verifikasi</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                                <option value="verified"  {{ request('status') == 'verified'  ? 'selected' : '' }}>Verified</option>
                                <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Konfirmasi Pembayaran</label>
                            <select name="konfirmasi_status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="menunggu_konfirmasi" {{ request('konfirmasi_status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="dikonfirmasi"        {{ request('konfirmasi_status') == 'dikonfirmasi'        ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="ditolak"             {{ request('konfirmasi_status') == 'ditolak'             ? 'selected' : '' }}>Bukti Ditolak</option>
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
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Metode</option>
                                <option value="tunai"    {{ request('metode_pembayaran') == 'tunai'    ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="qris"     {{ request('metode_pembayaran') == 'qris'     ? 'selected' : '' }}>QRIS Statis</option>
                            </select>
                        </div>

                        {{-- ▼ DIPERBAIKI: tambah opsi 'daring' --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Penerimaan</label>
                            <select name="metode_penerimaan"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="datang_langsung" {{ request('metode_penerimaan') == 'datang_langsung' ? 'selected' : '' }}>Datang Langsung</option>
                                <option value="dijemput"        {{ request('metode_penerimaan') == 'dijemput'        ? 'selected' : '' }}>Dijemput</option>
                                <option value="daring"          {{ request('metode_penerimaan') == 'daring'          ? 'selected' : '' }}>Daring (Online)</option>
                            </select>
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

                    @if (request()->hasAny(['status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan', 'konfirmasi_status']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('transaksi-penerimaan.index', request('q') ? ['q' => request('q')] : []) }}"
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
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Muzakki & Transaksi</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaksis as $transaksi)
                                @php
                                    $canEdit     = $transaksi->status === 'pending';
                                    $canDelete   = in_array($transaksi->status, ['pending', 'rejected']);
                                    $canVerify   = $transaksi->status === 'pending'
                                                && $transaksi->metode_penerimaan === 'datang_langsung';
                                    $canContinue = $transaksi->status === 'pending'
                                                && $transaksi->metode_penerimaan === 'dijemput'
                                                && (!$transaksi->jenis_zakat_id || !$transaksi->metode_pembayaran);
                                    $needsKonfirmasi = $transaksi->bisa_dikonfirmasi;
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $needsKonfirmasi ? 'bg-amber-50/30' : '' }}"
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
                                            <div class="text-sm font-medium text-gray-900">{{ $transaksi->muzakki_nama }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $transaksi->tanggal_transaksi->format('d/m/Y') }} ·
                                                {{ $transaksi->waktu_transaksi->format('H:i') }}
                                                @if ($transaksi->jumlah > 0)
                                                    · <span class="font-semibold text-gray-700">{{ $transaksi->jumlah_formatted }}</span>
                                                @endif
                                                {{-- ▼ BARU: tampilkan badge infaq jika ada kelebihan bayar --}}
                                                @if ($transaksi->has_infaq && ($transaksi->jumlah_infaq ?? 0) > 0)
                                                    · <span class="text-amber-600 font-medium">+Infaq {{ $transaksi->jumlah_infaq_formatted }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $transaksi->status_badge !!}
                                                {{-- ▼ BARU: badge metode penerimaan (termasuk daring) --}}
                                                {!! $transaksi->metode_penerimaan_badge !!}
                                                @if ($transaksi->metode_pembayaran && $transaksi->metode_pembayaran !== 'tunai')
                                                    {!! $transaksi->konfirmasi_status_badge !!}
                                                @endif
                                                @if ($canContinue)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                        Data Belum Lengkap
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $transaksi->uuid }}"
                                            data-nama="{{ $transaksi->muzakki_nama }}"
                                            data-can-edit="{{ $canEdit ? '1' : '0' }}"
                                            data-can-delete="{{ $canDelete ? '1' : '0' }}"
                                            data-can-verify="{{ $canVerify ? '1' : '0' }}"
                                            data-can-continue="{{ $canContinue ? '1' : '0' }}"
                                            data-can-konfirmasi="{{ $needsKonfirmasi ? '1' : '0' }}"
                                            data-metode="{{ $transaksi->metode_pembayaran }}">
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

                                                    {{-- Kolom 1: Data Muzakki --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Data Muzakki</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Nama</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $transaksi->muzakki_nama }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($transaksi->muzakki_telepon)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Telepon</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->muzakki_telepon }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($transaksi->muzakki_email)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Email</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->muzakki_email }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($transaksi->muzakki_alamat)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Alamat</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->muzakki_alamat }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Detail Zakat --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Zakat</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Tanggal</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</p>
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
                                                                        @if ($transaksi->tipeZakat)
                                                                            <p class="text-xs text-gray-400 mt-0.5">{{ $transaksi->tipeZakat->nama }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                        <p class="text-xs text-gray-400 italic">Belum diisi</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($transaksi->jumlah > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jumlah Zakat</p>
                                                                        <p class="text-sm font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</p>
                                                                        {{-- ▼ BARU: detail infaq --}}
                                                                        @if ($transaksi->has_infaq && ($transaksi->jumlah_infaq ?? 0) > 0)
                                                                            <p class="text-xs text-amber-600 mt-0.5">
                                                                                Dibayar: {{ $transaksi->jumlah_dibayar_formatted }}
                                                                                <span class="font-medium">(+Infaq {{ $transaksi->jumlah_infaq_formatted }})</span>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Metode & Status --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Metode & Status</h4>
                                                        <div class="space-y-3">
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Metode Penerimaan</p>
                                                                {!! $transaksi->metode_penerimaan_badge !!}
                                                                {{-- ▼ badge "Diinput Muzakki" jika transaksi daring --}}
                                                                @if ($transaksi->diinput_muzakki)
                                                                    <span class="ml-1 px-1.5 py-0.5 text-xs rounded bg-indigo-50 text-indigo-700 border border-indigo-200">via Muzakki</span>
                                                                @endif
                                                            </div>
                                                            @if ($transaksi->metode_pembayaran)
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                                                                    {!! $transaksi->metode_pembayaran_badge !!}
                                                                </div>
                                                            @endif
                                                            @if ($transaksi->metode_pembayaran && $transaksi->metode_pembayaran !== 'tunai')
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Status Konfirmasi</p>
                                                                    {!! $transaksi->konfirmasi_status_badge !!}
                                                                    @if ($transaksi->no_referensi_transfer)
                                                                        <p class="text-xs text-gray-400 mt-1">Ref: {{ $transaksi->no_referensi_transfer }}</p>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status Verifikasi</p>
                                                                {!! $transaksi->status_badge !!}
                                                            </div>
                                                            @if ($transaksi->status_penjemputan)
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Status Penjemputan</p>
                                                                    {!! $transaksi->status_penjemputan_badge !!}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if ($transaksi->keterangan)
                                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                                <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                                                                <p class="text-sm text-gray-600">{{ $transaksi->keterangan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Tombol Aksi di Expandable --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                    <div class="text-xs text-gray-500">
                                                        No. Transaksi: <span class="font-medium text-gray-700">{{ $transaksi->no_transaksi }}</span>
                                                        @if ($transaksi->no_kwitansi)
                                                            · Kwitansi: <span class="font-medium text-gray-700">{{ $transaksi->no_kwitansi }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex gap-2 flex-wrap">
                                                        @if ($needsKonfirmasi)
                                                            <button type="button"
                                                                onclick="openKonfirmasiModal('{{ $transaksi->uuid }}', '{{ $transaksi->muzakki_nama }}', '{{ $transaksi->metode_pembayaran }}')"
                                                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Konfirmasi Pembayaran
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('transaksi-penerimaan.show', $transaksi->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Detail
                                                        </a>
                                                        @if ($canContinue)
                                                            <a href="{{ route('transaksi-penerimaan.edit', $transaksi->uuid) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-orange-100 hover:bg-orange-200 text-orange-700 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                </svg>
                                                                Lanjutkan
                                                            </a>
                                                        @elseif ($canEdit)
                                                            <a href="{{ route('transaksi-penerimaan.edit', $transaksi->uuid) }}"
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
                            $canEdit     = $transaksi->status === 'pending';
                            $canDelete   = in_array($transaksi->status, ['pending', 'rejected']);
                            $canVerify   = $transaksi->status === 'pending' && $transaksi->metode_penerimaan === 'datang_langsung';
                            $canContinue = $transaksi->status === 'pending'
                                        && $transaksi->metode_penerimaan === 'dijemput'
                                        && (!$transaksi->jenis_zakat_id || !$transaksi->metode_pembayaran);
                            $needsKonfirmasi = $transaksi->bisa_dikonfirmasi;
                        @endphp
                        <div class="expandable-card {{ $needsKonfirmasi ? 'bg-amber-50/30' : '' }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $transaksi->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $transaksi->muzakki_nama }}</h3>
                                            {!! $transaksi->status_badge !!}
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs text-gray-500">{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</span>
                                            @if ($transaksi->jumlah > 0)
                                                <span class="text-xs text-gray-500 mx-2">•</span>
                                                <span class="text-xs font-semibold text-gray-700">{{ $transaksi->jumlah_formatted }}</span>
                                            @endif
                                            {{-- ▼ BARU: tampilkan infaq di mobile --}}
                                            @if ($transaksi->has_infaq && ($transaksi->jumlah_infaq ?? 0) > 0)
                                                <span class="text-xs text-gray-500 mx-1">•</span>
                                                <span class="text-xs text-amber-600 font-medium">+Infaq</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                            {!! $transaksi->metode_penerimaan_badge !!}
                                            @if ($transaksi->metode_pembayaran && $transaksi->metode_pembayaran !== 'tunai')
                                                {!! $transaksi->konfirmasi_status_badge !!}
                                            @endif
                                        </div>
                                        @if ($canContinue)
                                            <div class="mt-1">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">Data Belum Lengkap</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $transaksi->uuid }}"
                                            data-nama="{{ $transaksi->muzakki_nama }}"
                                            data-can-edit="{{ $canEdit ? '1' : '0' }}"
                                            data-can-delete="{{ $canDelete ? '1' : '0' }}"
                                            data-can-verify="{{ $canVerify ? '1' : '0' }}"
                                            data-can-continue="{{ $canContinue ? '1' : '0' }}"
                                            data-can-konfirmasi="{{ $needsKonfirmasi ? '1' : '0' }}"
                                            data-metode="{{ $transaksi->metode_pembayaran }}">
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
                                        @if ($transaksi->muzakki_telepon || $transaksi->muzakki_email)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Kontak</h4>
                                                <div class="space-y-2">
                                                    @if ($transaksi->muzakki_telepon)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <span class="text-gray-900">{{ $transaksi->muzakki_telepon }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($transaksi->muzakki_email)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="text-gray-900">{{ $transaksi->muzakki_email }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Zakat</h4>
                                            <div class="space-y-2">
                                                @if ($transaksi->jenisZakat)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                        <span class="text-gray-900">{{ $transaksi->jenisZakat->nama }}
                                                            @if ($transaksi->tipeZakat) — {{ $transaksi->tipeZakat->nama }} @endif
                                                        </span>
                                                    </div>
                                                @endif
                                                @if ($transaksi->jumlah > 0)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <div>
                                                            <span class="font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</span>
                                                            {{-- ▼ BARU: detail infaq di mobile --}}
                                                            @if ($transaksi->has_infaq && ($transaksi->jumlah_infaq ?? 0) > 0)
                                                                <span class="text-xs text-amber-600 ml-1">(+Infaq {{ $transaksi->jumlah_infaq_formatted }})</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    {!! $transaksi->metode_penerimaan_badge !!}
                                                    @if ($transaksi->metode_pembayaran)
                                                        {!! $transaksi->metode_pembayaran_badge !!}
                                                    @endif
                                                </div>
                                                @if ($transaksi->metode_pembayaran && $transaksi->metode_pembayaran !== 'tunai')
                                                    <div class="flex items-center gap-2">
                                                        {!! $transaksi->konfirmasi_status_badge !!}
                                                        @if ($transaksi->no_referensi_transfer)
                                                            <span class="text-xs text-gray-400">Ref: {{ $transaksi->no_referensi_transfer }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200">
                                            <div class="flex gap-2 flex-wrap">
                                                @if ($needsKonfirmasi)
                                                    <button type="button"
                                                        onclick="openKonfirmasiModal('{{ $transaksi->uuid }}', '{{ $transaksi->muzakki_nama }}', '{{ $transaksi->metode_pembayaran }}')"
                                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Konfirmasi
                                                    </button>
                                                @endif
                                                <a href="{{ route('transaksi-penerimaan.show', $transaksi->uuid) }}"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </a>
                                                @if ($canContinue)
                                                    <a href="{{ route('transaksi-penerimaan.edit', $transaksi->uuid) }}"
                                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                        Lanjutkan
                                                    </a>
                                                @elseif ($canEdit)
                                                    <a href="{{ route('transaksi-penerimaan.edit', $transaksi->uuid) }}"
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
                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan', 'konfirmasi_status']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                        <a href="{{ route('transaksi-penerimaan.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai catat transaksi penerimaan zakat baru</p>
                        <a href="{{ route('transaksi-penerimaan.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Transaksi Baru
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

                <a href="#" id="dd-continue"
                    class="flex items-center px-4 py-2.5 text-sm text-orange-600 hover:bg-orange-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Lanjutkan Pembayaran
                </a>

                <a href="#" id="dd-print"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Kwitansi
                </a>

                <div class="border-t border-gray-100 my-1" id="dd-divider-konfirmasi" style="display:none;"></div>
                <button type="button" id="dd-konfirmasi"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-amber-700 hover:bg-amber-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Konfirmasi Pembayaran
                </button>

                <div class="border-t border-gray-100 my-1" id="dd-divider-verify" style="display:none;"></div>
                <button type="button" id="dd-verify"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verifikasi
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

    {{-- ── Modal: Konfirmasi Pembayaran ── --}}
    <div id="konfirmasi-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Konfirmasi Pembayaran</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Konfirmasi <span id="modal-konfirmasi-metode" class="font-semibold text-amber-700"></span> dari
                "<span id="modal-konfirmasi-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs text-gray-400 mb-5 text-center">Pastikan dana sudah masuk ke rekening/QRIS masjid sebelum mengkonfirmasi.</p>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <input type="text" id="konfirmasi-catatan"
                    placeholder="Misal: Dana sudah masuk pukul 10.30"
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">
            </div>
            <form method="POST" id="konfirmasi-form">
                @csrf
                <input type="hidden" name="catatan_konfirmasi" id="konfirmasi-catatan-hidden">
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('konfirmasi-modal')"
                        class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2.5 bg-amber-500 text-sm font-medium text-white hover:bg-amber-600 transition-colors">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal: Verify ── --}}
    <div id="verify-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Verifikasi Transaksi</h3>
            <p class="text-sm text-gray-500 mb-6 text-center">
                Verifikasi transaksi dari "<span id="modal-verify-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <form method="POST" id="verify-form">
                @csrf
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('verify-modal')"
                        class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-28 rounded-lg px-4 py-2.5 bg-green-600 text-sm font-medium text-white hover:bg-green-700 transition-colors">
                        Verifikasi
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
                Hapus transaksi dari "<span id="modal-delete-nama" class="font-semibold text-gray-700"></span>"?
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

            const dropdown            = document.getElementById('dropdown-container');
            const ddDetail            = document.getElementById('dd-detail');
            const ddContinue          = document.getElementById('dd-continue');
            const ddPrint             = document.getElementById('dd-print');
            const ddKonfirmasi        = document.getElementById('dd-konfirmasi');
            const ddVerify            = document.getElementById('dd-verify');
            const ddEdit              = document.getElementById('dd-edit');
            const ddDelete            = document.getElementById('dd-delete');
            const ddDividerKonfirmasi = document.getElementById('dd-divider-konfirmasi');
            const ddDividerVerify     = document.getElementById('dd-divider-verify');
            const ddDividerDelete     = document.getElementById('dd-divider-delete');

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, .dropdown-toggle, button')) return;
                    const target = document.getElementById(this.dataset.target);
                    const icon   = this.querySelector('.expand-icon');
                    target.classList.toggle('hidden');
                    icon.classList.toggle('rotate-90');
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, .dropdown-toggle, button')) return;
                    const target = document.getElementById(this.dataset.target);
                    const icon   = this.querySelector('.expand-icon-mobile');
                    target.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });

            function closeDropdown() {
                dropdown.classList.add('hidden');
                dropdown.removeAttribute('data-uuid');
            }

            function positionDropdown(toggle) {
                const rect   = toggle.getBoundingClientRect();
                const ddW    = 224;
                const ddH    = dropdown.offsetHeight || 280;
                const margin = 6;
                const vpW    = window.innerWidth;
                const vpH    = window.innerHeight;

                let left = rect.right - ddW;
                if (left < margin) left = margin;
                if (left + ddW > vpW - margin) left = vpW - ddW - margin;

                let top = rect.bottom + margin;
                if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
                if (top < margin) top = margin;

                dropdown.style.top  = top  + 'px';
                dropdown.style.left = left + 'px';
            }

            document.addEventListener('click', function (e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();

                    const uuid          = toggle.dataset.uuid;
                    const nama          = toggle.dataset.nama;
                    const canEdit       = toggle.dataset.canEdit       === '1';
                    const canDelete     = toggle.dataset.canDelete     === '1';
                    const canVerify     = toggle.dataset.canVerify     === '1';
                    const canContinue   = toggle.dataset.canContinue   === '1';
                    const canKonfirmasi = toggle.dataset.canKonfirmasi === '1';
                    // ▼ DIPERBAIKI: baca data-metode dari button
                    const metode        = toggle.dataset.metode        || '';

                    if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                        closeDropdown(); return;
                    }

                    dropdown.dataset.uuid = uuid;

                    ddDetail.href = `/transaksi-penerimaan/${uuid}`;
                    ddPrint.href  = `/transaksi-penerimaan/${uuid}/print`;

                    canContinue
                        ? (ddContinue.href = `/transaksi-penerimaan/${uuid}/edit`, show(ddContinue))
                        : hide(ddContinue);

                    if (canKonfirmasi) {
                        show(ddKonfirmasi);
                        ddDividerKonfirmasi.style.display = '';
                        ddKonfirmasi.onclick = () => {
                            closeDropdown();
                            openKonfirmasiModal(uuid, nama, metode);
                        };
                    } else {
                        hide(ddKonfirmasi);
                        ddDividerKonfirmasi.style.display = 'none';
                    }

                    if (canVerify) {
                        show(ddVerify);
                        ddDividerVerify.style.display = '';
                        ddVerify.onclick = () => { closeDropdown(); openVerifyModal(uuid, nama); };
                    } else {
                        hide(ddVerify);
                        ddDividerVerify.style.display = 'none';
                    }

                    canEdit
                        ? (ddEdit.href = `/transaksi-penerimaan/${uuid}/edit`, show(ddEdit))
                        : hide(ddEdit);

                    if (canDelete) {
                        show(ddDelete);
                        ddDividerDelete.style.display = '';
                        ddDelete.onclick = () => { closeDropdown(); openDeleteModal(uuid, nama); };
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

            function show(el) { el.classList.remove('hidden'); }
            function hide(el) { el.classList.add('hidden'); }

            function openKonfirmasiModal(uuid, nama, metode) {
                document.getElementById('modal-konfirmasi-nama').textContent   = nama;
                document.getElementById('modal-konfirmasi-metode').textContent = metode === 'qris' ? 'QRIS' : 'Transfer Bank';
                document.getElementById('konfirmasi-form').action = `/transaksi-penerimaan/${uuid}/konfirmasi-pembayaran`;
                document.getElementById('konfirmasi-catatan').value = '';
                openModal('konfirmasi-modal');
            }

            document.getElementById('konfirmasi-form')?.addEventListener('submit', function () {
                document.getElementById('konfirmasi-catatan-hidden').value =
                    document.getElementById('konfirmasi-catatan').value;
            });

            function openVerifyModal(uuid, nama) {
                document.getElementById('modal-verify-nama').textContent = nama;
                document.getElementById('verify-form').action = `/transaksi-penerimaan/${uuid}/verify`;
                openModal('verify-modal');
            }

            function openDeleteModal(uuid, nama) {
                document.getElementById('modal-delete-nama').textContent = nama;
                document.getElementById('delete-form').action = `/transaksi-penerimaan/${uuid}`;
                openModal('delete-modal');
            }

            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            ['konfirmasi-modal', 'verify-modal', 'delete-modal'].forEach(id => {
                document.getElementById(id)?.addEventListener('click', function (e) {
                    if (e.target === this) closeModal(id);
                });
            });

            window.openKonfirmasiModal = openKonfirmasiModal;
            window.openVerifyModal     = openVerifyModal;
            window.openDeleteModal     = openDeleteModal;
        });

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

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
                form.classList.add('hidden');
                btn.classList.remove('hidden');
                container.style.minWidth = '';
            }
        }

        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('konfirmasi-modal');
                closeModal('verify-modal');
                closeModal('delete-modal');
            }
        });
    </script>
@endpush