{{--
    resources/views/amil/transaksi-penerimaan/index-datang-langsung.blade.php

    DIPAKAI OLEH  : Amil / Admin Masjid
    CONTROLLER    : TransaksiPenerimaanController@indexDatangLangsung
    ROUTE         : GET /transaksi-datang-langsung

    SESUAI CONTROLLER:
      - Filter: q, jenis_zakat_id, metode_pembayaran, status, start_date, end_date, fidyah_tipe
      - Stats: total, tunai, non_tunai, total_nominal, total_fidyah
      - Semua transaksi datang_langsung otomatis terverifikasi
      - Menampilkan data fidyah (tipe, jumlah hari, detail mentah/matang/tunai)
      - Hanya tombol Detail & Kwitansi (tanpa Verifikasi / Tolak / Edit / Hapus)
--}}

@extends('layouts.app')

@section('title', 'Transaksi Datang Langsung')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ── Stats Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Total Transaksi</span>
            <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</span>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Total Nominal</span>
            <span class="text-lg font-bold text-green-600">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</span>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Tunai</span>
            <span class="text-2xl font-bold text-gray-800">{{ number_format($stats['tunai']) }}</span>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Non-Tunai (Transfer/QRIS)</span>
            <span class="text-2xl font-bold text-blue-600">{{ number_format($stats['non_tunai']) }}</span>
            @if(($stats['total_fidyah'] ?? 0) > 0)
                <span class="text-xs text-amber-600 font-medium mt-0.5">{{ $stats['total_fidyah'] }} fidyah</span>
            @endif
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Transaksi Datang Langsung</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                    {{-- Tambah --}}
                    <a href="{{ route('transaksi-datang-langsung.create') }}"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Tambah</span>
                    </a>

                    {{-- Filter --}}
                    <button type="button" onclick="toggleFilter()"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                        {{ request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']) ? 'ring-2 ring-primary' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        @if(request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                            <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-primary text-white rounded-full">
                                {{ collect(['jenis_zakat_id','metode_pembayaran','start_date','fidyah_tipe'])->filter(fn($k) => request($k))->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Search --}}
                    <div id="search-container" class="transition-all duration-300"
                        style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                        <button type="button" onclick="toggleSearch()" id="search-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2">Cari</span>
                        </button>
                        <form method="GET" action="{{ route('transaksi-datang-langsung.index') }}" id="search-form"
                            class="{{ request('q') ? '' : 'hidden' }}">
                            @foreach (['jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe'] as $filter)
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
                                @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                                    <a href="{{ route('transaksi-datang-langsung.index') }}"
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
            class="{{ request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('transaksi-datang-langsung.index') }}" id="filter-form">
                @if (request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                        <select name="jenis_zakat_id"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisZakatList ?? [] as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="tunai"          {{ request('metode_pembayaran') == 'tunai'          ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer"       {{ request('metode_pembayaran') == 'transfer'       ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris"           {{ request('metode_pembayaran') == 'qris'           ? 'selected' : '' }}>QRIS</option>
                            <option value="beras"          {{ request('metode_pembayaran') == 'beras'          ? 'selected' : '' }}>Beras</option>
                            <option value="makanan_matang" {{ request('metode_pembayaran') == 'makanan_matang' ? 'selected' : '' }}>Makanan Siap Santap</option>
                            <option value="bahan_mentah"   {{ request('metode_pembayaran') == 'bahan_mentah'   ? 'selected' : '' }}>Bahan Makanan Mentah</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Fidyah</label>
                        <select name="fidyah_tipe"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="mentah" {{ request('fidyah_tipe') == 'mentah' ? 'selected' : '' }}>Fidyah Mentah</option>
                            <option value="matang" {{ request('fidyah_tipe') == 'matang' ? 'selected' : '' }}>Fidyah Matang</option>
                            <option value="tunai"  {{ request('fidyah_tipe') == 'tunai'  ? 'selected' : '' }}>Fidyah Tunai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                    </div>
                </div>

                @if (request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('transaksi-datang-langsung.index', request('q') ? ['q' => request('q')] : []) }}"
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Muzakki & Transaksi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transaksis as $trx)
                            @php
                                // ── Cek data nama jiwa ──
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

                                // ── Cek fidyah ──
                                $isFidyah    = !empty($trx->fidyah_tipe);
                                $fidyahLabel = match($trx->fidyah_tipe ?? '') {
                                    'mentah' => 'Bahan Mentah',
                                    'matang' => 'Makanan Matang',
                                    'tunai'  => 'Tunai',
                                    default  => '',
                                };

                                // ── Label & warna metode pembayaran ──
                                $labelMetode = [
                                    'tunai'          => 'Tunai',
                                    'transfer'       => 'Transfer Bank',
                                    'qris'           => 'QRIS',
                                    'makanan_matang' => 'Makanan Siap Santap',
                                    'bahan_mentah'   => 'Bahan Makanan Mentah',
                                    'beras'          => 'Beras',
                                ];
                                $warnaMetode = match($trx->metode_pembayaran ?? '') {
                                    'tunai'          => 'bg-green-100 text-green-800',
                                    'transfer'       => 'bg-blue-100 text-blue-800',
                                    'qris'           => 'bg-purple-100 text-purple-800',
                                    'beras'          => 'bg-amber-100 text-amber-800',
                                    'makanan_matang' => 'bg-orange-100 text-orange-800',
                                    'bahan_mentah'   => 'bg-yellow-100 text-yellow-800',
                                    default          => 'bg-gray-100 text-gray-600',
                                };
                                $namaMetode = $labelMetode[$trx->metode_pembayaran ?? ''] ?? ucfirst($trx->metode_pembayaran ?? '-');
                            @endphp

                            {{-- Parent Row --}}
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
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
                                        <div class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                            @if($trx->waktu_transaksi)
                                                &middot; {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}
                                            @endif
                                            @if($isFidyah)
                                                &middot; <span class="font-semibold text-amber-600">Fidyah {{ $trx->fidyah_jumlah_hari }} hari</span>
                                                @if($trx->fidyah_tipe === 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                    &middot; <span class="text-amber-500">{{ $trx->fidyah_total_berat_kg }} kg</span>
                                                @elseif($trx->fidyah_tipe === 'matang' && $trx->fidyah_jumlah_box > 0)
                                                    &middot; <span class="text-amber-500">{{ $trx->fidyah_jumlah_box }} box</span>
                                                @elseif($trx->jumlah > 0)
                                                    &middot; <span class="font-semibold text-gray-700">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                                @endif
                                            @else
                                                @if ($trx->jumlah > 0)
                                                    &middot; <span class="font-semibold text-gray-700">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                                @elseif($trx->jumlah_beras_kg > 0)
                                                    &middot; <span class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                                @endif
                                                @if($hasNamaJiwa)
                                                    &middot; <span class="text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                Terverifikasi
                                            </span>
                                            @if($isFidyah)
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">
                                                    Fidyah {{ $fidyahLabel }}
                                                </span>
                                            @endif
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                                {{ $namaMetode }}
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                Datang Langsung
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">Klik baris untuk melihat detail</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-uuid="{{ $trx->uuid }}"
                                        data-nama="{{ $trx->muzakki_nama ?? '-' }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            {{-- Expandable Detail Row --}}
                            <tr id="detail-{{ $trx->uuid }}" class="hidden expandable-content">
                                <td colspan="3" class="px-0 py-0">
                                    <div class="bg-gray-50 border-y border-gray-100">
                                        <div class="px-6 py-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                {{-- Kolom 1: Data Muzakki --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Data Muzakki</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                        @if($trx->muzakki_nik)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">NIK</p>
                                                                <p class="text-sm text-gray-900">{{ $trx->muzakki_nik }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($trx->muzakki_telepon)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Telepon</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_telepon }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($trx->muzakki_alamat)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Alamat</p>
                                                                <p class="text-sm text-gray-700">{{ $trx->muzakki_alamat }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($trx->muzakki_email)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Email</p>
                                                                <p class="text-sm text-gray-900">{{ $trx->muzakki_email }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Transaksi / Zakat --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Detail Transaksi</h4>
                                                    <div class="space-y-3">
                                                        {{-- No Transaksi --}}
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">No. Transaksi</p>
                                                                <p class="text-sm font-mono text-gray-900">{{ $trx->no_transaksi }}</p>
                                                            </div>
                                                        </div>
                                                        {{-- Tanggal --}}
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Tanggal</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                                    @if($trx->waktu_transaksi)
                                                                        <span class="text-gray-500">({{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        {{-- Jenis & Tipe Zakat --}}
                                                        @if($trx->jenisZakat)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $trx->jenisZakat->nama }}
                                                                    @if($trx->tipeZakat)
                                                                        <span class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        {{-- Program Zakat --}}
                                                        @if($trx->programZakat)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Program Zakat</p>
                                                                <p class="text-sm text-gray-900">{{ $trx->programZakat->nama_program }}</p>
                                                            </div>
                                                        </div>
                                                        @endif

                                                        {{-- ── DATA FIDYAH ── --}}
                                                        @if($isFidyah)
                                                        <div class="mt-1 bg-amber-50 border border-amber-200 rounded-lg p-3 space-y-2">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <p class="text-xs font-semibold text-amber-800">
                                                                    Fidyah {{ $fidyahLabel }} — {{ $trx->fidyah_jumlah_hari }} hari
                                                                </p>
                                                            </div>
                                                            @if($trx->fidyah_tipe === 'mentah')
                                                                @if($trx->fidyah_nama_bahan)
                                                                    <p class="text-xs text-amber-700">Bahan: <strong>{{ $trx->fidyah_nama_bahan }}</strong></p>
                                                                @endif
                                                                <p class="text-xs text-amber-700">
                                                                    {{ $trx->fidyah_berat_per_hari_gram ?? 675 }} gram/hari
                                                                    @if($trx->fidyah_total_berat_kg > 0)
                                                                        = <strong>{{ $trx->fidyah_total_berat_kg }} kg</strong> total
                                                                    @endif
                                                                </p>
                                                            @elseif($trx->fidyah_tipe === 'matang')
                                                                @if($trx->fidyah_jumlah_box)
                                                                    <p class="text-xs text-amber-700">Jumlah: <strong>{{ $trx->fidyah_jumlah_box }} box</strong></p>
                                                                @endif
                                                                @if($trx->fidyah_menu_makanan)
                                                                    <p class="text-xs text-amber-700">Menu: {{ $trx->fidyah_menu_makanan }}</p>
                                                                @endif
                                                                @if($trx->fidyah_harga_per_box > 0)
                                                                    <p class="text-xs text-amber-700">Harga/box: Rp {{ number_format($trx->fidyah_harga_per_box, 0, ',', '.') }}</p>
                                                                @endif
                                                                @if($trx->fidyah_cara_serah)
                                                                    <p class="text-xs text-amber-700">Cara: {{ ucfirst(str_replace('_', ' ', $trx->fidyah_cara_serah)) }}</p>
                                                                @endif
                                                            @elseif($trx->fidyah_tipe === 'tunai')
                                                                @if($trx->jumlah > 0)
                                                                    <p class="text-xs text-amber-700">Total Dibayar: <strong>Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</strong></p>
                                                                @endif
                                                                @if($trx->jumlah_infaq > 0)
                                                                    <p class="text-xs text-amber-700">Termasuk infaq: Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                                @endif
                                                            @endif
                                                        </div>

                                                        @else
                                                        {{-- ── DATA ZAKAT BIASA ── --}}

                                                        {{-- Nama Jiwa --}}
                                                        @if($hasNamaJiwa)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama Jiwa <span class="text-gray-400">({{ count($namaJiwaList) }} orang)</span></p>
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    @foreach($namaJiwaList as $idx => $nama)
                                                                        @if($nama && trim($nama) !== '')
                                                                            <span class="inline-flex items-center bg-white border border-gray-200 rounded px-2 py-0.5 text-xs">
                                                                                <span class="text-gray-400 mr-1">{{ $idx + 1 }}.</span>{{ $nama }}
                                                                            </span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif

                                                        {{-- Zakat Mal --}}
                                                        @if($trx->dataZakatMal)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Detail Mal</p>
                                                                <p class="text-xs text-gray-700">
                                                                    Nilai Harta: Rp {{ number_format($trx->dataZakatMal['nilai_harta'] ?? 0, 0, ',', '.') }}
                                                                    @if(!empty($trx->dataZakatMal['persentase'])) &middot; {{ $trx->dataZakatMal['persentase'] }}% @endif
                                                                    @if(!empty($trx->dataZakatMal['sudah_haul'])) &middot; Sudah Haul @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @endif

                                                        {{-- Jumlah Uang --}}
                                                        @if($trx->jumlah > 0)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jumlah Zakat</p>
                                                                <p class="text-sm font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                                                @if($trx->jumlah_dibayar && $trx->jumlah_dibayar != $trx->jumlah)
                                                                    <p class="text-xs text-gray-500">Dibayar: Rp {{ number_format($trx->jumlah_dibayar, 0, ',', '.') }}</p>
                                                                @endif
                                                                @if($trx->jumlah_infaq > 0)
                                                                    <p class="text-xs text-gray-500">+ Infaq Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif

                                                        {{-- Beras --}}
                                                        @if($trx->jumlah_beras_kg > 0)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jumlah Beras</p>
                                                                <p class="text-sm font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</p>
                                                                @if(($trx->jumlah_beras_liter ?? 0) > 0)
                                                                    <p class="text-xs text-gray-500">{{ $trx->jumlah_beras_liter }} liter</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @endif {{-- /isFidyah else --}}

                                                        {{-- Keterangan --}}
                                                        @if($trx->keterangan)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Keterangan</p>
                                                                <p class="text-sm text-gray-700">{{ $trx->keterangan }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Metode & Status --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Metode & Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Metode Penerimaan</p>
                                                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Datang Langsung</span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                                                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                                                {{ $namaMetode }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Status</p>
                                                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Terverifikasi</span>
                                                        </div>
                                                        @if($trx->verified_at)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Diverifikasi Pada</p>
                                                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}</p>
                                                                @if($trx->amil)
                                                                    <p class="text-xs text-gray-500">Oleh: {{ $trx->amil->nama_lengkap }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif

                                                        {{-- Info Jiwa untuk Fitrah Tunai --}}
                                                        @if(!$isFidyah && $trx->jumlah_jiwa > 0)
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jumlah Jiwa</p>
                                                                <p class="text-sm text-gray-900">{{ $trx->jumlah_jiwa }}</p>
                                                                @if($trx->nominal_per_jiwa > 0)
                                                                    <p class="text-xs text-gray-400">@ Rp {{ number_format($trx->nominal_per_jiwa, 0, ',', '.') }}/jiwa</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>{{-- /grid --}}

                                            {{-- Tombol Aksi — hanya Detail & Kwitansi --}}
                                            <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2 flex-wrap">
                                                <a href="{{ route('transaksi-datang-langsung.show', $trx->uuid) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('transaksi-datang-langsung.print', $trx->uuid) }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                    Cetak Kwitansi
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
                        $isFidyah    = !empty($trx->fidyah_tipe);
                        $fidyahLabel = match($trx->fidyah_tipe ?? '') {
                            'mentah' => 'Bahan Mentah',
                            'matang' => 'Makanan Matang',
                            'tunai'  => 'Tunai',
                            default  => '',
                        };
                        $labelMetode = [
                            'tunai'          => 'Tunai',
                            'transfer'       => 'Transfer Bank',
                            'qris'           => 'QRIS',
                            'makanan_matang' => 'Makanan Siap Santap',
                            'bahan_mentah'   => 'Bahan Makanan Mentah',
                            'beras'          => 'Beras',
                        ];
                        $warnaMetode = match($trx->metode_pembayaran ?? '') {
                            'tunai'          => 'bg-green-100 text-green-800',
                            'transfer'       => 'bg-blue-100 text-blue-800',
                            'qris'           => 'bg-purple-100 text-purple-800',
                            'beras'          => 'bg-amber-100 text-amber-800',
                            'makanan_matang' => 'bg-orange-100 text-orange-800',
                            'bahan_mentah'   => 'bg-yellow-100 text-yellow-800',
                            default          => 'bg-gray-100 text-gray-600',
                        };
                        $namaMetode = $labelMetode[$trx->metode_pembayaran ?? ''] ?? ucfirst($trx->metode_pembayaran ?? '-');
                    @endphp

                    <div class="expandable-card">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $trx->uuid }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $trx->muzakki_nama ?? '-' }}</h3>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 flex-shrink-0">Terverifikasi</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1">
                                        <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if($isFidyah)
                                            <span class="text-xs font-semibold text-amber-600">Fidyah {{ $fidyahLabel }} {{ $trx->fidyah_jumlah_hari }}h</span>
                                            @if($trx->fidyah_tipe === 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                <span class="text-xs text-amber-500">{{ $trx->fidyah_total_berat_kg }} kg</span>
                                            @elseif($trx->fidyah_tipe === 'matang' && $trx->fidyah_jumlah_box > 0)
                                                <span class="text-xs text-amber-500">{{ $trx->fidyah_jumlah_box }} box</span>
                                            @elseif($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</span>
                                            @endif
                                        @else
                                            @if($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                            @elseif($trx->jumlah_beras_kg > 0)
                                                <span class="text-xs font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                            @endif
                                            @if($hasNamaJiwa)
                                                <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @if($isFidyah)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Fidyah</span>
                                        @endif
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                            {{ $namaMetode }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 ml-1 flex-shrink-0">
                                    <button type="button"
                                        class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                                        data-uuid="{{ $trx->uuid }}"
                                        data-nama="{{ $trx->muzakki_nama ?? '-' }}">
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

                        {{-- Mobile Expandable --}}
                        <div id="detail-mobile-{{ $trx->uuid }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-3">

                                {{-- No Transaksi --}}
                                <div class="flex items-start gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">No. Transaksi</p>
                                        <p class="text-sm font-mono text-gray-900">{{ $trx->no_transaksi }}</p>
                                    </div>
                                </div>

                                @if($trx->muzakki_telepon)
                                <div class="flex items-start gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Telepon</p>
                                        <p class="text-sm text-gray-900">{{ $trx->muzakki_telepon }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($trx->jenisZakat)
                                <div class="flex items-start gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Jenis Zakat</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $trx->jenisZakat->nama }}</p>
                                        @if($trx->tipeZakat)
                                            <p class="text-xs text-gray-500">{{ $trx->tipeZakat->nama }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                {{-- Metode Pembayaran Mobile --}}
                                <div class="flex items-start gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Metode Pembayaran</p>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                            {{ $namaMetode }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Fidyah Mobile --}}
                                @if($isFidyah)
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 space-y-1.5">
                                    <p class="text-xs font-semibold text-amber-800">Fidyah {{ $fidyahLabel }} — {{ $trx->fidyah_jumlah_hari }} hari</p>
                                    @if($trx->fidyah_tipe === 'mentah')
                                        @if($trx->fidyah_nama_bahan) <p class="text-xs text-amber-700">Bahan: <strong>{{ $trx->fidyah_nama_bahan }}</strong></p> @endif
                                        @if($trx->fidyah_total_berat_kg > 0) <p class="text-xs text-amber-700">Total: <strong>{{ $trx->fidyah_total_berat_kg }} kg</strong></p> @endif
                                    @elseif($trx->fidyah_tipe === 'matang')
                                        @if($trx->fidyah_jumlah_box) <p class="text-xs text-amber-700"><strong>{{ $trx->fidyah_jumlah_box }} box</strong></p> @endif
                                        @if($trx->fidyah_menu_makanan) <p class="text-xs text-amber-700">{{ $trx->fidyah_menu_makanan }}</p> @endif
                                        @if($trx->fidyah_cara_serah) <p class="text-xs text-amber-700">{{ ucfirst(str_replace('_', ' ', $trx->fidyah_cara_serah)) }}</p> @endif
                                    @elseif($trx->fidyah_tipe === 'tunai')
                                        @if($trx->jumlah > 0)
                                            <p class="text-xs text-amber-700">Total Dibayar: <strong>Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</strong></p>
                                        @endif
                                        @if($trx->jumlah_infaq > 0)
                                            <p class="text-xs text-amber-700">Termasuk infaq: Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                        @endif
                                    @endif
                                </div>
                                @else
                                    {{-- Jumlah Uang / Beras --}}
                                    @if($trx->jumlah > 0)
                                    <div class="flex items-start gap-2 text-sm">
                                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Jumlah Zakat</p>
                                            <p class="text-sm font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                            @if($trx->jumlah_infaq > 0)
                                                <p class="text-xs text-gray-500">+ Infaq Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if($trx->jumlah_beras_kg > 0)
                                    <div class="flex items-start gap-2 text-sm">
                                        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Jumlah Beras</p>
                                            <p class="text-sm font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($hasNamaJiwa)
                                    <div class="flex items-start gap-2 text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Nama Jiwa ({{ count($namaJiwaList) }} orang)</p>
                                            <div class="text-xs text-gray-700 mt-1 space-y-0.5">
                                                @foreach($namaJiwaList as $idx => $nama)
                                                    @if($nama && trim($nama) !== '')
                                                        <div class="flex gap-1"><span class="text-gray-400">{{ $idx + 1 }}.</span><span>{{ $nama }}</span></div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endif

                                @if($trx->keterangan)
                                <div class="flex items-start gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Keterangan</p>
                                        <p class="text-sm text-gray-700">{{ $trx->keterangan }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($trx->verified_at)
                                <div class="flex items-start gap-2 text-sm">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Diverifikasi</p>
                                        <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                @endif

                                {{-- Tombol Mobile --}}
                                <div class="pt-2 border-t border-gray-200 flex gap-2">
                                    <a href="{{ route('transaksi-datang-langsung.show', $trx->uuid) }}"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('transaksi-datang-langsung.print', $trx->uuid) }}" target="_blank"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Kwitansi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($transaksis->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $transaksis->withQueryString()->links() }}
                </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-50 mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('transaksi-datang-langsung.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Pencarian
                    </a>
                @else
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Datang Langsung</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai tambahkan transaksi zakat datang langsung.</p>
                    <a href="{{ route('transaksi-datang-langsung.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Transaksi
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- ── Dropdown (hanya Detail & Kwitansi) ── --}}
<div id="dropdown-container" class="fixed hidden z-[9999]" style="min-width:192px;">
    <div class="w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="py-1">
            <a href="#" id="dd-detail"
                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Detail
            </a>
            <a href="#" id="dd-print" target="_blank"
                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Kwitansi
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var dropdown = document.getElementById('dropdown-container');
    var ddDetail = document.getElementById('dd-detail');
    var ddPrint  = document.getElementById('dd-print');

    // ── Desktop expand ──────────────────────────────────────────
    document.querySelectorAll('.expandable-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, .dropdown-toggle, button')) return;
            var target = document.getElementById(this.dataset.target);
            var icon   = this.querySelector('.expand-icon');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        });
    });

    // ── Mobile expand ───────────────────────────────────────────
    document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, .dropdown-toggle, button')) return;
            var target = document.getElementById(this.dataset.target);
            var icon   = this.querySelector('.expand-icon-mobile');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    // ── Dropdown ────────────────────────────────────────────────
    function closeDropdown() {
        dropdown.classList.add('hidden');
        dropdown.removeAttribute('data-uuid');
    }

    function positionDropdown(toggle) {
        var rect   = toggle.getBoundingClientRect();
        var ddW    = 192;
        var ddH    = dropdown.offsetHeight || 100;
        var margin = 6;
        var vpW    = window.innerWidth;
        var vpH    = window.innerHeight;
        var left   = rect.right - ddW;
        if (left < margin) left = margin;
        if (left + ddW > vpW - margin) left = vpW - ddW - margin;
        var top = rect.bottom + margin;
        if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
        if (top < margin) top = margin;
        dropdown.style.top  = top  + 'px';
        dropdown.style.left = left + 'px';
    }

    document.addEventListener('click', function (e) {
        var toggle = e.target.closest('.dropdown-toggle');
        if (toggle) {
            e.stopPropagation();
            var uuid = toggle.dataset.uuid;
            if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                closeDropdown(); return;
            }
            dropdown.dataset.uuid = uuid;
            ddDetail.href = '/transaksi-datang-langsung/' + uuid;
            ddPrint.href  = '/transaksi-datang-langsung/' + uuid + '/print';
            dropdown.classList.remove('hidden');
            positionDropdown(toggle);
        } else if (!dropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    window.addEventListener('scroll', closeDropdown, true);
    window.addEventListener('resize', closeDropdown);
});

// ── Search & Filter ─────────────────────────────────────────
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
</script>
@endpush