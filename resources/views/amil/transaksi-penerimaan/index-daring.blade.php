@php use Illuminate\Support\Facades\Storage; @endphp

{{--
    resources/views/amil/transaksi-daring/index-daring.blade.php

    DIPAKAI OLEH  : Amil / Admin Masjid
    CONTROLLER    : indexDaring() — hanya menampilkan transaksi mode daring
    ROUTE         : GET /transaksi-daring

    PENTING       : TIDAK ADA tombol "Tambah Transaksi" / Create.
                    Transaksi daring dibuat oleh muzakki sendiri (via portal muzakki).
                    Amil hanya bisa: LIHAT (index) + DETAIL (show) + KONFIRMASI pembayaran.

    VARIABEL      : $transaksis (paginated), $jenisZakatList, $stats
--}}

@extends('layouts.app')

@section('title', 'Transaksi Zakat Daring')

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
                <a href="{{ route('transaksi-daring.index', ['konfirmasi_status' => 'menunggu_konfirmasi']) }}"
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
                        <p class="text-xs font-medium text-gray-500 truncate">Total</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</p>
                        <p class="text-xs text-indigo-600 mt-0.5">Daring</p>
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
                        <p class="text-xs text-green-600 mt-0.5">Dikonfirmasi</p>
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
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['menunggu_konfirmasi'], 0, ',', '.') }}</p>
                        <p class="text-xs text-amber-600 mt-0.5">Perlu diproses</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4 col-span-2 lg:col-span-1">
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
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Transaksi Zakat Daring</h2>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                via Muzakki
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
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
                                @foreach (['jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date'] as $filter)
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
                                            id="search-input" placeholder="Cari nama, no transaksi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date']))
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
                class="{{ request()->hasAny(['jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
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
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Konfirmasi</label>
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

                    @if (request()->hasAny(['jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date']))
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
                            @foreach ($transaksis as $trx)
                                @php
                                    $needsKonfirmasi = $trx->konfirmasi_status === 'menunggu_konfirmasi';
                                    $buktiUrl = $trx->bukti_transfer ? Storage::url($trx->bukti_transfer) : '';

                                    // Deteksi nama jiwa
                                    $hasNamaJiwa = false;
                                    $namaJiwaList = [];
                                    if (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                        $hasNamaJiwa = true;
                                        $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                                    } elseif (!empty($trx->dataZakatFitrahTunai['nama_jiwa'])) {
                                        $hasNamaJiwa = true;
                                        $namaJiwaList = $trx->dataZakatFitrahTunai['nama_jiwa'];
                                    } elseif (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                        $hasNamaJiwa = true;
                                        $namaJiwaList = $trx->nama_jiwa_json;
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $needsKonfirmasi ? 'bg-amber-50/30' : '' }}"
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
                                            <div class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->waktu_transaksi)
                                                    · {{ $trx->waktu_transaksi->format('H:i') }}
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    · <span class="font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                                @endif
                                                @if ($trx->jumlah_infaq > 0)
                                                    · <span class="text-amber-600 font-medium">+Infaq {{ $trx->jumlah_infaq_formatted }}</span>
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    · <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                {!! $trx->metode_pembayaran_badge !!}
                                                {!! $trx->konfirmasi_status_badge !!}
                                                @if ($needsKonfirmasi)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
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
                                            data-uuid="{{ $trx->uuid }}"
                                            data-nama="{{ $trx->muzakki_nama }}"
                                            data-can-konfirmasi="{{ $needsKonfirmasi ? '1' : '0' }}"
                                            data-metode="{{ $trx->metode_pembayaran }}"
                                            data-bukti="{{ $buktiUrl }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
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
                                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama }}</p>
                                                                </div>
                                                            </div>
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
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
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
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Email</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_email }}</p>
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
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_alamat }}</p>
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
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jumlah Zakat</p>
                                                                        <p class="text-sm font-semibold text-green-600">{{ $trx->jumlah_formatted }}</p>
                                                                        @if ($trx->jumlah_infaq > 0)
                                                                            <p class="text-xs text-amber-600 mt-0.5">
                                                                                Dibayar: {{ $trx->jumlah_dibayar_formatted }}
                                                                                <span class="font-medium">(+Infaq {{ $trx->jumlah_infaq_formatted }})</span>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->no_transaksi)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">No. Transaksi</p>
                                                                        <p class="text-sm font-mono text-gray-900">{{ $trx->no_transaksi }}</p>
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
                                                                <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                                                                {!! $trx->metode_pembayaran_badge !!}
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status Konfirmasi</p>
                                                                {!! $trx->konfirmasi_status_badge !!}
                                                                @if ($trx->no_referensi_transfer)
                                                                    <p class="text-xs text-gray-400 mt-1">Ref: {{ $trx->no_referensi_transfer }}</p>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status Verifikasi</p>
                                                                {!! $trx->status_badge !!}
                                                            </div>
                                                            @if ($trx->keterangan)
                                                                <div class="mt-2 p-2 bg-gray-100 border border-gray-200 rounded-lg">
                                                                    <p class="text-xs text-gray-500 font-medium">Keterangan:</p>
                                                                    <p class="text-xs text-gray-700">{{ $trx->keterangan }}</p>
                                                                </div>
                                                            @endif
                                                            @if ($trx->catatan_konfirmasi)
                                                                <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                                    <p class="text-xs text-blue-600 font-medium">Catatan Konfirmasi:</p>
                                                                    <p class="text-xs text-blue-700">{{ $trx->catatan_konfirmasi }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tombol Aksi di Expandable --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                    <div class="text-xs text-gray-500">
                                                        No. Transaksi: <span class="font-medium text-gray-700">{{ $trx->no_transaksi }}</span>
                                                    </div>
                                                    <div class="flex gap-2 flex-wrap">
                                                        @if ($needsKonfirmasi)
                                                            <button type="button"
                                                                onclick="openKonfirmasiModal('{{ $trx->uuid }}', '{{ $trx->muzakki_nama }}', '{{ $trx->metode_pembayaran }}', '{{ $buktiUrl }}')"
                                                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Review & Konfirmasi
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('transaksi-daring.show', $trx->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Detail
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
                            $needsKonfirmasi = $trx->konfirmasi_status === 'menunggu_konfirmasi';
                            $buktiUrl = $trx->bukti_transfer ? Storage::url($trx->bukti_transfer) : '';

                            // Deteksi nama jiwa
                            $hasNamaJiwa = false;
                            $namaJiwaList = [];
                            if (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                $hasNamaJiwa = true;
                                $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                            } elseif (!empty($trx->dataZakatFitrahTunai['nama_jiwa'])) {
                                $hasNamaJiwa = true;
                                $namaJiwaList = $trx->dataZakatFitrahTunai['nama_jiwa'];
                            } elseif (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                $hasNamaJiwa = true;
                                $namaJiwaList = $trx->nama_jiwa_json;
                            }
                        @endphp
                        <div class="expandable-card {{ $needsKonfirmasi ? 'bg-amber-50/30' : '' }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $trx->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $trx->muzakki_nama }}</h3>
                                            {!! $trx->status_badge !!}
                                        </div>
                                        <div class="flex items-center mt-1 flex-wrap gap-2">
                                            <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                            @if ($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span>
                                            @endif
                                            @if ($trx->jumlah_infaq > 0)
                                                <span class="text-xs text-amber-600 font-medium">+Infaq</span>
                                            @endif
                                            @if ($hasNamaJiwa)
                                                <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                            {!! $trx->metode_pembayaran_badge !!}
                                            {!! $trx->konfirmasi_status_badge !!}
                                            @if ($needsKonfirmasi)
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                    Perlu Konfirmasi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $trx->uuid }}"
                                            data-nama="{{ $trx->muzakki_nama }}"
                                            data-can-konfirmasi="{{ $needsKonfirmasi ? '1' : '0' }}"
                                            data-metode="{{ $trx->metode_pembayaran }}"
                                            data-bukti="{{ $buktiUrl }}">
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

                                        {{-- Nama Jiwa --}}
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

                                        @if ($trx->muzakki_telepon || $trx->muzakki_email)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Kontak</h4>
                                                <div class="space-y-2">
                                                    @if ($trx->muzakki_telepon)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <span class="text-gray-900">{{ $trx->muzakki_telepon }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($trx->muzakki_email)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="text-gray-900">{{ $trx->muzakki_email }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Zakat</h4>
                                            <div class="space-y-2">
                                                @if ($trx->jenisZakat)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                        <span class="text-gray-900">{{ $trx->jenisZakat->nama }}
                                                            @if ($trx->tipeZakat) — {{ $trx->tipeZakat->nama }} @endif
                                                        </span>
                                                    </div>
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <div>
                                                            <span class="font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span>
                                                            @if ($trx->jumlah_infaq > 0)
                                                                <span class="text-xs text-amber-600 ml-1">(+Infaq {{ $trx->jumlah_infaq_formatted }})</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1">Status Konfirmasi</p>
                                                    {!! $trx->konfirmasi_status_badge !!}
                                                    @if ($trx->no_referensi_transfer)
                                                        <span class="text-xs text-gray-400 ml-1">Ref: {{ $trx->no_referensi_transfer }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 flex gap-2">
                                            @if ($needsKonfirmasi)
                                                <button type="button"
                                                    onclick="openKonfirmasiModal('{{ $trx->uuid }}', '{{ $trx->muzakki_nama }}', '{{ $trx->metode_pembayaran }}', '{{ $buktiUrl }}')"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Konfirmasi
                                                </button>
                                            @endif
                                            <a href="{{ route('transaksi-daring.show', $trx->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail Lengkap
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
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-indigo-50 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                        <a href="{{ route('transaksi-daring.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Daring</h3>
                        <p class="text-sm text-gray-500 mb-2">Transaksi akan muncul saat muzakki melakukan pembayaran daring.</p>
                        <p class="text-xs text-gray-400">Amil tidak dapat membuat transaksi daring secara manual.</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Info Card ── --}}
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 animate-slide-up">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-indigo-900 mb-1">Tentang Transaksi Daring</p>
                    <p class="text-xs text-indigo-800">Transaksi daring dibuat oleh muzakki melalui portal mereka sendiri. Pembayaran hanya tersedia via Transfer Bank atau QRIS. Tugas amil adalah memverifikasi bukti pembayaran yang dikirimkan dan menekan tombol Konfirmasi setelah dana masuk ke rekening.</p>
                </div>
            </div>
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
                <div class="border-t border-gray-100 my-1" id="dd-divider-konfirmasi" style="display:none;"></div>
                <button type="button" id="dd-konfirmasi"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-amber-700 hover:bg-amber-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Review & Konfirmasi
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal: Review & Konfirmasi Pembayaran ── --}}
    <div id="konfirmasi-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="w-full max-w-md shadow-xl rounded-2xl bg-white overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Review & Konfirmasi Pembayaran</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            <span id="modal-konfirmasi-metode" class="font-semibold text-amber-700"></span>
                            dari "<span id="modal-konfirmasi-nama" class="font-semibold text-gray-700"></span>"
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('konfirmasi-modal')"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-6 pt-4">
                <p class="text-xs font-medium text-gray-700 mb-2">Bukti Pembayaran</p>
                <div id="modal-bukti-container" class="hidden">
                    <a id="modal-bukti-link" href="#" target="_blank"
                        class="block relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-zoom-in">
                        <img id="modal-bukti-img" src="" alt="Bukti Pembayaran"
                            class="w-full max-h-64 object-contain rounded-xl">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 text-white text-xs px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Buka di tab baru
                            </span>
                        </div>
                    </a>
                    <p class="text-xs text-gray-400 mt-1.5 text-center">Klik gambar untuk memperbesar</p>
                </div>
                <div id="modal-bukti-empty" class="hidden">
                    <div class="flex items-center justify-center h-24 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-gray-400">Bukti pembayaran tidak tersedia</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-6 mt-3 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-xs text-amber-700">
                    <span class="font-semibold">⚠️ Pastikan</span> dana sudah masuk ke rekening/QRIS masjid sebelum mengkonfirmasi.
                </p>
            </div>

            <div class="px-6 py-4">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Catatan (opsional)</label>
                <input type="text" id="konfirmasi-catatan"
                    placeholder="Misal: Dana sudah masuk pukul 10.30"
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 mb-4">
                <form method="POST" id="konfirmasi-form">
                    @csrf
                    <input type="hidden" name="catatan_konfirmasi" id="konfirmasi-catatan-hidden">
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('konfirmasi-modal')"
                            class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 rounded-lg px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-sm font-medium text-white transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ============================================================
        // FUNGSI GLOBAL
        // ============================================================
        function openKonfirmasiModal(uuid, nama, metode, buktiUrl) {
            document.getElementById('modal-konfirmasi-nama').textContent   = nama;
            document.getElementById('modal-konfirmasi-metode').textContent = metode === 'qris' ? 'QRIS' : 'Transfer Bank';
            document.getElementById('konfirmasi-form').action = `/transaksi-daring/${uuid}/konfirmasi-pembayaran`;
            document.getElementById('konfirmasi-catatan').value = '';

            const buktiContainer = document.getElementById('modal-bukti-container');
            const buktiEmpty     = document.getElementById('modal-bukti-empty');
            const buktiImg       = document.getElementById('modal-bukti-img');
            const buktiLink      = document.getElementById('modal-bukti-link');

            if (buktiUrl && buktiUrl.trim() !== '') {
                buktiImg.src   = buktiUrl;
                buktiLink.href = buktiUrl;
                buktiContainer.classList.remove('hidden');
                buktiEmpty.classList.add('hidden');
            } else {
                buktiContainer.classList.add('hidden');
                buktiEmpty.classList.remove('hidden');
            }

            document.getElementById('konfirmasi-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

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

        // ============================================================
        // DOMContentLoaded
        // ============================================================
        document.addEventListener('DOMContentLoaded', function () {
            const dropdown            = document.getElementById('dropdown-container');
            const ddDetail            = document.getElementById('dd-detail');
            const ddKonfirmasi        = document.getElementById('dd-konfirmasi');
            const ddDividerKonfirmasi = document.getElementById('dd-divider-konfirmasi');

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(row => {
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
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
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

            function closeDropdown() {
                if (dropdown) {
                    dropdown.classList.add('hidden');
                    dropdown.removeAttribute('data-uuid');
                }
            }

            function positionDropdown(toggle) {
                if (!dropdown) return;
                const rect   = toggle.getBoundingClientRect();
                const ddW    = 224;
                const ddH    = dropdown.offsetHeight || 120;
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
                    const canKonfirmasi = toggle.dataset.canKonfirmasi === '1';
                    const metode        = toggle.dataset.metode || '';
                    const bukti         = toggle.dataset.bukti || '';

                    if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                        closeDropdown(); return;
                    }

                    dropdown.dataset.uuid = uuid;
                    if (ddDetail) ddDetail.href = `/transaksi-daring/${uuid}`;

                    if (canKonfirmasi) {
                        ddKonfirmasi.classList.remove('hidden');
                        ddDividerKonfirmasi.style.display = '';
                        ddKonfirmasi.onclick = () => {
                            closeDropdown();
                            openKonfirmasiModal(uuid, nama, metode, bukti);
                        };
                    } else {
                        ddKonfirmasi.classList.add('hidden');
                        ddDividerKonfirmasi.style.display = 'none';
                    }

                    dropdown.classList.remove('hidden');
                    positionDropdown(toggle);

                } else if (!dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            window.addEventListener('scroll', closeDropdown, true);
            window.addEventListener('resize', closeDropdown);

            document.getElementById('konfirmasi-form')?.addEventListener('submit', function () {
                document.getElementById('konfirmasi-catatan-hidden').value =
                    document.getElementById('konfirmasi-catatan').value;
            });

            document.getElementById('konfirmasi-modal')?.addEventListener('click', function (e) {
                if (e.target === this) closeModal('konfirmasi-modal');
            });
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal('konfirmasi-modal');
        });
    </script>
@endpush