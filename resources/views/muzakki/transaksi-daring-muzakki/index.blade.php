{{-- resources/views/muzakki/transaksi-daring-muzakki/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Transaksi Zakat Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Alert: Ada transaksi menunggu konfirmasi ── --}}
        @if (isset($stats['total_pending']) && $stats['total_pending'] > 0)
            <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl animate-slide-up">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-amber-800">
                        {{ $stats['total_pending'] }} transaksi menunggu konfirmasi dari amil
                    </p>
                    <p class="text-xs text-amber-600 mt-0.5">Amil sedang memverifikasi pembayaran Anda</p>
                </div>
                <a href="{{ route('transaksi-daring-muzakki.index', ['status' => 'pending']) }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                    Lihat
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
                        <p class="text-xs font-medium text-gray-500 truncate">Menunggu</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total_pending'], 0, ',', '.') }}</p>
                        @if ($stats['total_pending'] > 0)
                            <p class="text-xs text-amber-600 mt-0.5">Proses konfirmasi</p>
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
                        <p class="text-xs font-medium text-gray-500 truncate">Total Dibayar</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Terverifikasi</p>
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
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Riwayat Transaksi Zakat</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Bayar Zakat Baru --}}
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Bayar Zakat Baru</span>
                        </a>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('transaksi-daring-muzakki.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date'] as $filter)
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
                                            id="search-input" placeholder="Cari no. transaksi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                                        <a href="{{ route('transaksi-daring-muzakki.index') }}"
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
                class="{{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('transaksi-daring-muzakki.index') }}" id="filter-form">
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
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Penerimaan</label>
                            <select name="metode_penerimaan"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Metode</option>
                                <option value="daring" {{ request('metode_penerimaan') == 'daring' ? 'selected' : '' }}>Daring (Transfer/QRIS)</option>
                                <option value="dijemput" {{ request('metode_penerimaan') == 'dijemput' ? 'selected' : '' }}>Dijemput Amil</option>
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
                    @if (request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('transaksi-daring-muzakki.index', request('q') ? ['q' => request('q')] : []) }}"
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
                                    $isPending  = $trx->status === 'pending';
                                    $isVerified = $trx->status === 'verified';
                                    $isRejected = $trx->status === 'rejected';
                                    $isDaring   = $trx->metode_penerimaan === 'daring';
                                    $isDijemput = $trx->metode_penerimaan === 'dijemput';

                                    // Deteksi nama jiwa
                                    $hasNamaJiwa  = false;
                                    $namaJiwaList = [];
                                    if (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                        $hasNamaJiwa  = true;
                                        $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                                    } elseif (!empty($trx->dataZakatFitrahTunai['nama_jiwa'])) {
                                        $hasNamaJiwa  = true;
                                        $namaJiwaList = $trx->dataZakatFitrahTunai['nama_jiwa'];
                                    } elseif (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                        $hasNamaJiwa  = true;
                                        $namaJiwaList = $trx->nama_jiwa_json;
                                    }
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $isPending  ? 'bg-amber-50/30'  : '' }}
                                    {{ $isRejected ? 'bg-red-50/30'    : '' }}"
                                    data-target="detail-{{ $trx->uuid }}">
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
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama ?? '-' }}</span>
                                                @if ($isDaring)
                                                    <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">Daring</span>
                                                @elseif ($isDijemput)
                                                    <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-700 border border-orange-200">Dijemput</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->jenisZakat)
                                                    &middot; {{ $trx->jenisZakat->nama }}
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    &middot; <span class="font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                                @endif
                                                @if ($trx->jumlah_infaq > 0)
                                                    &middot; <span class="text-amber-600">+Infaq {{ $trx->jumlah_infaq_formatted }}</span>
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    &middot; <span class="text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                @if ($isDaring && isset($trx->konfirmasi_status_badge))
                                                    {!! $trx->konfirmasi_status_badge !!}
                                                @endif
                                                @if ($isDijemput && $trx->status_penjemputan)
                                                    @php
                                                        $pjBadge = [
                                                            'menunggu'         => 'bg-gray-100 text-gray-700',
                                                            'diterima'         => 'bg-blue-100 text-blue-700',
                                                            'dalam_perjalanan' => 'bg-indigo-100 text-indigo-700',
                                                            'sampai_lokasi'    => 'bg-purple-100 text-purple-700',
                                                            'selesai'          => 'bg-green-100 text-green-700',
                                                        ][$trx->status_penjemputan] ?? 'bg-gray-100 text-gray-700';
                                                        $pjLabel = [
                                                            'menunggu'         => 'Menunggu Amil',
                                                            'diterima'         => 'Diterima Amil',
                                                            'dalam_perjalanan' => 'Dalam Perjalanan',
                                                            'sampai_lokasi'    => 'Amil di Lokasi',
                                                            'selesai'          => 'Selesai',
                                                        ][$trx->status_penjemputan] ?? $trx->status_penjemputan;
                                                    @endphp
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $pjBadge }}">{{ $pjLabel }}</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $trx->uuid }}"
                                            data-nama="{{ $trx->muzakki_nama ?? '-' }}"
                                            data-no="{{ $trx->no_transaksi }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                {{-- ── Expandable Content Row ── --}}
                                <tr id="detail-{{ $trx->uuid }}" class="hidden expandable-content">
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
                                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama ?? '-' }}</p>
                                                                </div>
                                                            </div>

                                                            {{-- Nama Jiwa --}}
                                                            @if ($hasNamaJiwa)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Nama Jiwa
                                                                            <span class="text-xs text-gray-400 ml-1">({{ count($namaJiwaList) }} orang)</span>
                                                                        </p>
                                                                        <div class="text-sm text-gray-700 mt-1 flex flex-wrap gap-1">
                                                                            @foreach ($namaJiwaList as $index => $nama)
                                                                                @if ($nama && trim($nama) !== '')
                                                                                    <span class="inline-flex items-center bg-white border border-gray-200 rounded-lg px-2.5 py-1 text-xs">
                                                                                        <span class="font-medium text-gray-500 mr-1">{{ $index + 1 }}.</span>
                                                                                        {{ $nama }}
                                                                                    </span>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($trx->muzakki_telepon)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Telepon</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_telepon }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->muzakki_email)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Email</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_email }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->muzakki_nik)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">NIK</p>
                                                                        <p class="text-sm font-mono font-medium text-gray-900">{{ $trx->muzakki_nik }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->muzakki_alamat)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Alamat</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ Str::limit($trx->muzakki_alamat, 80) }}</p>
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
                                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($trx->jenisZakat)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->jenisZakat->nama }}</p>
                                                                        @if ($trx->tipeZakat)
                                                                            <p class="text-xs text-gray-400 mt-0.5">{{ $trx->tipeZakat->nama }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jumlah Zakat</p>
                                                                        <p class="text-sm font-semibold text-green-600">{{ $trx->jumlah_formatted }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah_infaq > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a4 4 0 00-4-4H6" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Infaq</p>
                                                                        <p class="text-sm font-medium text-amber-600">{{ $trx->jumlah_infaq_formatted }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($isDijemput && $trx->amil)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Amil Penjemput</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->amil->pengguna->username ?? '-' }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Status & Pembayaran --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Status &amp; Pembayaran</h4>
                                                        <div class="space-y-3">
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status</p>
                                                                {!! $trx->status_badge !!}
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Metode</p>
                                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $isDaring ? 'bg-indigo-100 text-indigo-800' : 'bg-orange-100 text-orange-800' }}">
                                                                    {{ $isDaring ? 'Daring' : 'Dijemput' }}
                                                                </span>
                                                            </div>
                                                            @if ($isDaring && $trx->metode_pembayaran)
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Metode Bayar</p>
                                                                    <span class="text-sm font-medium text-gray-900 uppercase">{{ $trx->metode_pembayaran }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($isDaring && isset($trx->konfirmasi_status_badge))
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Konfirmasi</p>
                                                                    {!! $trx->konfirmasi_status_badge !!}
                                                                </div>
                                                            @endif
                                                            @if ($trx->catatan_konfirmasi)
                                                                <div class="p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                                    <p class="text-xs text-blue-600 font-medium">Catatan Amil:</p>
                                                                    <p class="text-xs text-blue-800 mt-0.5">{{ $trx->catatan_konfirmasi }}</p>
                                                                </div>
                                                            @endif
                                                            @if ($isRejected && $trx->alasan_penolakan)
                                                                <div class="p-2 bg-red-50 border border-red-200 rounded-lg">
                                                                    <p class="text-xs text-red-600 font-medium">Alasan Penolakan:</p>
                                                                    <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tombol Aksi di Expandable --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                    <div class="text-xs text-gray-500">
                                                        No. Transaksi: <span class="font-mono font-medium text-gray-700">{{ $trx->no_transaksi }}</span>
                                                    </div>
                                                    <div class="flex gap-2 flex-wrap">
                                                        <a href="{{ route('transaksi-daring-muzakki.show', $trx->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Lihat Detail
                                                        </a>
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
                    @foreach ($transaksis as $trx)
                        @php
                            $isPending  = $trx->status === 'pending';
                            $isRejected = $trx->status === 'rejected';
                            $isDaring   = $trx->metode_penerimaan === 'daring';
                            $isDijemput = $trx->metode_penerimaan === 'dijemput';

                            // Deteksi nama jiwa
                            $hasNamaJiwa  = false;
                            $namaJiwaList = [];
                            if (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                $hasNamaJiwa  = true;
                                $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                            } elseif (!empty($trx->dataZakatFitrahTunai['nama_jiwa'])) {
                                $hasNamaJiwa  = true;
                                $namaJiwaList = $trx->dataZakatFitrahTunai['nama_jiwa'];
                            } elseif (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                $hasNamaJiwa  = true;
                                $namaJiwaList = $trx->nama_jiwa_json;
                            }
                        @endphp

                        <div class="expandable-card {{ $isPending ? 'bg-amber-50/30' : '' }} {{ $isRejected ? 'bg-red-50/30' : '' }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $trx->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                                {{ $trx->muzakki_nama ?? '-' }}
                                            </h3>
                                            {!! $trx->status_badge !!}
                                        </div>
                                        <div class="flex items-center mt-1 flex-wrap gap-2">
                                            <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                            @if ($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                            @endif
                                            @if ($hasNamaJiwa)
                                                <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                            @endif
                                            @if ($isDaring)
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700">Daring</span>
                                            @elseif ($isDijemput)
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-700">Dijemput</span>
                                            @endif
                                        </div>
                                        @if ($trx->jenisZakat)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $trx->jenisZakat->nama }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $trx->uuid }}"
                                            data-nama="{{ $trx->muzakki_nama ?? '-' }}"
                                            data-no="{{ $trx->no_transaksi }}">
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
                            <div id="detail-mobile-{{ $trx->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">

                                        {{-- Nama Jiwa Mobile --}}
                                        @if ($hasNamaJiwa)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Nama Jiwa</h4>
                                                <div class="flex items-start text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-xs text-gray-500">{{ count($namaJiwaList) }} orang</p>
                                                        <div class="text-xs text-gray-700 mt-1 space-y-1">
                                                            @foreach ($namaJiwaList as $index => $nama)
                                                                @if ($nama && trim($nama) !== '')
                                                                    <div class="flex items-start">
                                                                        <span class="text-gray-400 w-4 flex-shrink-0">{{ $index + 1 }}.</span>
                                                                        <span>{{ $nama }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Data Muzakki --}}
                                        @if ($trx->muzakki_telepon || $trx->muzakki_email || $trx->muzakki_nik || $trx->muzakki_alamat)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Data Muzakki</h4>
                                                <div class="space-y-2">
                                                    @if ($trx->muzakki_telepon)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <span class="text-gray-600">{{ $trx->muzakki_telepon }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($trx->muzakki_email)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="text-gray-600">{{ $trx->muzakki_email }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($trx->muzakki_nik)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1" />
                                                            </svg>
                                                            <span class="font-mono text-gray-600">NIK: {{ $trx->muzakki_nik }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($trx->muzakki_alamat)
                                                        <div class="flex items-start text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            </svg>
                                                            <span class="text-gray-600">{{ Str::limit($trx->muzakki_alamat, 60) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Detail Zakat --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Zakat</h4>
                                            <div class="space-y-2">
                                                @if ($trx->jenisZakat)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                        </svg>
                                                        <span class="text-gray-800">{{ $trx->jenisZakat->nama }}{{ $trx->tipeZakat ? ' — ' . $trx->tipeZakat->nama : '' }}</span>
                                                    </div>
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8" />
                                                        </svg>
                                                        <span class="font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span>
                                                        @if ($trx->jumlah_infaq > 0)
                                                            <span class="text-amber-600 ml-1">(+Infaq {{ $trx->jumlah_infaq_formatted }})</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if ($isDaring && $trx->metode_pembayaran)
                                                    <div class="flex items-center text-sm gap-2">
                                                        <span class="text-gray-500">Bayar via:</span>
                                                        <span class="font-medium text-gray-700 uppercase">{{ $trx->metode_pembayaran }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($isRejected && $trx->alasan_penolakan)
                                            <div class="p-2.5 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-xs font-medium text-red-800">Alasan Penolakan:</p>
                                                <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                            </div>
                                        @endif

                                        <div class="pt-3 border-t border-gray-200">
                                            <a href="{{ route('transaksi-daring-muzakki.show', $trx->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center w-full px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($transaksis->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $transaksis->withQueryString()->links() }}
                    </div>
                @endif

            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-green-50 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                        <a href="{{ route('transaksi-daring-muzakki.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Zakat</h3>
                        <p class="text-sm text-gray-500 mb-6">Bayar zakat pertama Anda dan raih keberkahan.</p>
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Bayar Zakat Sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── Dropdown Container ── --}}
    <div id="dropdown-container" class="fixed hidden z-[9999]" style="min-width:200px;">
        <div class="w-52 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="py-1">
                <a href="#" id="dd-detail"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Search & Filter ───────────────────────────────────────────────
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

        // ── DOMContentLoaded ─────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, .dropdown-toggle, button')) return;
                    const target = document.getElementById(this.dataset.target);
                    const icon   = this.querySelector('.expand-icon');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-90');
                    }
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, .dropdown-toggle, button')) return;
                    const target = document.getElementById(this.dataset.target);
                    const icon   = this.querySelector('.expand-icon-mobile');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });

            // ── Dropdown ─────────────────────────────────────────────────
            const dropdown = document.getElementById('dropdown-container');
            const ddDetail = document.getElementById('dd-detail');

            function closeDropdown() {
                if (dropdown) {
                    dropdown.classList.add('hidden');
                    dropdown.removeAttribute('data-uuid');
                }
            }

            function positionDropdown(toggle) {
                if (!dropdown) return;
                const rect   = toggle.getBoundingClientRect();
                const ddW    = 208;
                const ddH    = dropdown.offsetHeight || 80;
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
                    const uuid = toggle.dataset.uuid;

                    if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                        closeDropdown();
                        return;
                    }

                    dropdown.dataset.uuid = uuid;
                    if (ddDetail) ddDetail.href = '/transaksi-daring-muzakki/' + uuid;

                    dropdown.classList.remove('hidden');
                    positionDropdown(toggle);

                } else if (!dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            window.addEventListener('scroll', closeDropdown, true);
            window.addEventListener('resize', closeDropdown);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('dropdown-container');
                if (dropdown) dropdown.classList.add('hidden');
            }
        });
    </script>
@endpush