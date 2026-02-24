{{--
    resources/views/muzakki/transaksi/index-daring.blade.php

    DIPAKAI OLEH  : Muzakki
    CONTROLLER    : TransaksiZakatController@index
    ROUTE         : GET /transaksi-daring (muzakki)

    Menampilkan riwayat transaksi zakat muzakki (daring + dijemput)
    + tombol Bayar Zakat Baru
--}}

@extends('layouts.app')

@section('title', 'Zakat Saya')

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
                        <p class="text-xs text-indigo-600 mt-0.5">Semua</p>
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
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Riwayat Transaksi Zakat</h2>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                Saya
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Tombol Bayar Zakat Baru --}}
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary/90 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Bayar Zakat Baru
                        </a>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['jenis_zakat_id', 'status', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('transaksi-daring.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['jenis_zakat_id', 'status', 'start_date', 'end_date'] as $filter)
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
                                    @if (request()->hasAny(['q', 'jenis_zakat_id', 'status', 'start_date', 'end_date']))
                                        <a href="{{ route('transaksi-daring.index') }}"
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
                class="{{ request()->hasAny(['jenis_zakat_id', 'status', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('transaksi-daring.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

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

                    @if (request()->hasAny(['jenis_zakat_id', 'status', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('transaksi-daring.index', request('q') ? ['q' => request('q')] : []) }}"
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
                                    $isPending = $trx->status === 'pending';
                                    $isVerified = $trx->status === 'verified';
                                    $isRejected = $trx->status === 'rejected';
                                    $isDaring = $trx->metode_penerimaan === 'daring';
                                    $isDijemput = $trx->metode_penerimaan === 'dijemput';
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $isPending ? 'bg-amber-50/30' : '' }}
                                    {{ $isRejected ? 'bg-red-50/30' : '' }}"
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
                                                <span class="text-sm font-medium text-gray-900">{{ $trx->no_transaksi }}</span>
                                                @if ($isDaring)
                                                    <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">🌐 Daring</span>
                                                @elseif ($isDijemput)
                                                    <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-700 border border-orange-200">🚗 Dijemput</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->jenisZakat)
                                                    · {{ $trx->jenisZakat->nama }}
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    · <span class="font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                                @endif
                                                @if ($trx->jumlah_infaq > 0)
                                                    · <span class="text-amber-600 font-medium">+Infaq {{ $trx->jumlah_infaq_formatted }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                @if ($isDaring)
                                                    {!! $trx->konfirmasi_status_badge !!}
                                                @endif
                                                @if ($isDijemput && $trx->status_penjemputan)
                                                    @php
                                                        $pjBadge = [
                                                            'menunggu' => 'bg-gray-100 text-gray-700',
                                                            'diterima' => 'bg-blue-100 text-blue-700',
                                                            'dalam_perjalanan' => 'bg-indigo-100 text-indigo-700',
                                                            'sampai_lokasi' => 'bg-purple-100 text-purple-700',
                                                            'selesai' => 'bg-green-100 text-green-700',
                                                        ][$trx->status_penjemputan] ?? 'bg-gray-100 text-gray-700';
                                                        $pjLabel = [
                                                            'menunggu' => 'Menunggu Amil',
                                                            'diterima' => 'Diterima Amil',
                                                            'dalam_perjalanan' => 'Dalam Perjalanan',
                                                            'sampai_lokasi' => 'Amil di Lokasi',
                                                            'selesai' => 'Selesai',
                                                        ][$trx->status_penjemputan] ?? $trx->status_penjemputan;
                                                    @endphp
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $pjBadge }}">{{ $pjLabel }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('transaksi-daring-muzakki.show', $trx->uuid) }}"
                                            class="inline-flex items-center p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $trx->uuid }}" class="hidden expandable-content">
                                    <td colspan="3" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                    {{-- Kolom 1: Info Transaksi --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Info Transaksi</h4>
                                                        <div class="space-y-2 text-xs">
                                                            <div class="flex items-start gap-2">
                                                                <span class="text-gray-500 w-24 flex-shrink-0">No. Transaksi</span>
                                                                <span class="font-mono font-medium text-gray-800">{{ $trx->no_transaksi }}</span>
                                                            </div>
                                                            <div class="flex items-start gap-2">
                                                                <span class="text-gray-500 w-24 flex-shrink-0">Tanggal</span>
                                                                <span class="text-gray-800">{{ $trx->tanggal_transaksi->format('d F Y') }}</span>
                                                            </div>
                                                            <div class="flex items-start gap-2">
                                                                <span class="text-gray-500 w-24 flex-shrink-0">Metode</span>
                                                                <span class="text-gray-800">{{ $isDaring ? 'Daring' : 'Dijemput' }}</span>
                                                            </div>
                                                            @if ($trx->amil)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Amil</span>
                                                                    <span class="text-gray-800">{{ $trx->amil->pengguna->username ?? '-' }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Detail Zakat --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Zakat</h4>
                                                        <div class="space-y-2 text-xs">
                                                            @if ($trx->jenisZakat)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Jenis</span>
                                                                    <span class="text-gray-800">{{ $trx->jenisZakat->nama }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($trx->tipeZakat)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Tipe</span>
                                                                    <span class="text-gray-800">{{ $trx->tipeZakat->nama }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah > 0)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Zakat</span>
                                                                    <span class="font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah_infaq > 0)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Infaq</span>
                                                                    <span class="font-medium text-amber-600">{{ $trx->jumlah_infaq_formatted }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Status & Pembayaran --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Status Pembayaran</h4>
                                                        <div class="space-y-2 text-xs">
                                                            <div class="flex items-start gap-2">
                                                                <span class="text-gray-500 w-24 flex-shrink-0">Status</span>
                                                                <span>{!! $trx->status_badge !!}</span>
                                                            </div>
                                                            @if ($isDaring)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Konfirmasi</span>
                                                                    <span>{!! $trx->konfirmasi_status_badge !!}</span>
                                                                </div>
                                                                @if ($trx->metode_pembayaran)
                                                                    <div class="flex items-start gap-2">
                                                                        <span class="text-gray-500 w-24 flex-shrink-0">Metode Bayar</span>
                                                                        <span class="text-gray-800">{{ strtoupper($trx->metode_pembayaran) }}</span>
                                                                    </div>
                                                                @endif
                                                                @if ($trx->no_referensi_transfer)
                                                                    <div class="flex items-start gap-2">
                                                                        <span class="text-gray-500 w-24 flex-shrink-0">No. Ref</span>
                                                                        <span class="font-mono text-gray-800">{{ $trx->no_referensi_transfer }}</span>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            @if ($trx->catatan_konfirmasi)
                                                                <div class="flex items-start gap-2">
                                                                    <span class="text-gray-500 w-24 flex-shrink-0">Catatan</span>
                                                                    <span class="text-gray-800">{{ $trx->catatan_konfirmasi }}</span>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Alert jika ditolak --}}
                                                        @if ($isRejected && $trx->alasan_penolakan)
                                                            <div class="mt-3 p-2.5 bg-red-50 border border-red-200 rounded-lg">
                                                                <p class="text-xs font-medium text-red-800">Alasan Penolakan:</p>
                                                                <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Tombol Detail --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
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
                            $isPending = $trx->status === 'pending';
                            $isRejected = $trx->status === 'rejected';
                            $isDaring = $trx->metode_penerimaan === 'daring';
                            $isDijemput = $trx->metode_penerimaan === 'dijemput';
                        @endphp
                        <div class="expandable-card {{ $isPending ? 'bg-amber-50/30' : '' }} {{ $isRejected ? 'bg-red-50/30' : '' }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $trx->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-mono font-semibold text-gray-700">{{ $trx->no_transaksi }}</span>
                                            @if ($isDaring)
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700">🌐</span>
                                            @elseif ($isDijemput)
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-700">🚗</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center mt-1 gap-2">
                                            <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                            @if ($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                            {!! $trx->status_badge !!}
                                            @if ($isDaring) {!! $trx->konfirmasi_status_badge !!} @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <a href="{{ route('transaksi-daring-muzakki.show', $trx->uuid) }}"
                                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile Expandable --}}
                            <div id="detail-mobile-{{ $trx->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-3">
                                    @if ($trx->jenisZakat)
                                        <div class="flex items-center text-sm gap-2">
                                            <span class="text-gray-500 text-xs w-20">Jenis</span>
                                            <span class="text-gray-800 text-xs">{{ $trx->jenisZakat->nama }}{{ $trx->tipeZakat ? ' — '.$trx->tipeZakat->nama : '' }}</span>
                                        </div>
                                    @endif
                                    @if ($trx->jumlah > 0)
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-500 text-xs w-20">Zakat</span>
                                            <span class="text-green-600 font-semibold text-xs">{{ $trx->jumlah_formatted }}</span>
                                            @if ($trx->jumlah_infaq > 0)
                                                <span class="text-amber-600 text-xs">(+Infaq {{ $trx->jumlah_infaq_formatted }})</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($isRejected && $trx->alasan_penolakan)
                                        <div class="p-2.5 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-xs font-medium text-red-800">Alasan Penolakan:</p>
                                            <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                        </div>
                                    @endif
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
                    @if (request()->hasAny(['q', 'jenis_zakat_id', 'status', 'start_date', 'end_date']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter</p>
                        <a href="{{ route('transaksi-daring.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Zakat</h3>
                        <p class="text-sm text-gray-500 mb-6">Bayar zakat pertama Anda sekarang dan raih keberkahan.</p>
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary/90 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Bayar Zakat Sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Info Card ── --}}
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 animate-slide-up">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-900 mb-1">Cara Bayar Zakat</p>
                    <p class="text-xs text-green-800">Klik <strong>Bayar Zakat Baru</strong> untuk membayar zakat. Tersedia dua metode: <strong>Daring</strong> (transfer/QRIS, dikonfirmasi amil) atau <strong>Dijemput</strong> (amil datang ke lokasi Anda).</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Desktop expandable rows
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    const target = document.getElementById(this.dataset.target);
                    const icon = this.querySelector('.expand-icon');
                    target.classList.toggle('hidden');
                    icon.classList.toggle('rotate-90');
                });
            });

            // Mobile expandable
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    const target = document.getElementById(this.dataset.target);
                    const icon = this.querySelector('.expand-icon-mobile');
                    target.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });
        });

        function toggleSearch() {
            const btn = document.getElementById('search-button');
            const form = document.getElementById('search-form');
            const input = document.getElementById('search-input');
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
    </script>
@endpush