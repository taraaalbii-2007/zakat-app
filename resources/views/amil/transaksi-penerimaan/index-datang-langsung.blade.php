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
    <div class="space-y-6">

        {{-- ── Alert: Informasi Penting ── --}}
        @if (($stats['total_fidyah'] ?? 0) > 0)
            <div class="flex items-center gap-3 px-5 py-3 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-amber-800">
                        Terdapat {{ number_format($stats['total_fidyah']) }} transaksi fidyah
                    </p>
                    <p class="text-xs text-amber-600 mt-0.5">Pastikan data fidyah terisi dengan lengkap</p>
                </div>
                <a href="{{ route('transaksi-datang-langsung.index', ['fidyah_tipe' => 'mentah']) }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                    Lihat Fidyah
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Transaksi Datang Langsung</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola transaksi zakat yang diterima langsung di tempat</p>
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
                            @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                                <span
                                    class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-green-600 text-white rounded-full">
                                    {{ collect(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'fidyah_tipe'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </button>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('transaksi-datang-langsung.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>

                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($stats['total']) }}</span>
                        <span class="text-sm text-gray-500">Transaksi</span>
                    </div>

                    <!-- Stats Ringkasan Desktop -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Tunai:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['tunai']) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <span class="text-xs text-gray-500">Non-Tunai:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['non_tunai']) }}</span>
                        </div>
                        @if (($stats['total_fidyah'] ?? 0) > 0)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                <span class="text-xs text-gray-500">Fidyah:</span>
                                <span
                                    class="text-xs font-semibold text-gray-700">{{ number_format($stats['total_fidyah']) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp
                                {{ number_format($stats['total_nominal'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('transaksi-datang-langsung.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Transaksi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari no. transaksi / muzakki..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Jenis</option>
                                @foreach ($jenisZakatList ?? [] as $jenis)
                                    <option value="{{ $jenis->id }}"
                                        {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Metode</option>
                                <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="transfer"
                                    {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank
                                </option>
                                <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS
                                </option>
                                <option value="beras" {{ request('metode_pembayaran') == 'beras' ? 'selected' : '' }}>
                                    Beras</option>
                                <option value="makanan_matang"
                                    {{ request('metode_pembayaran') == 'makanan_matang' ? 'selected' : '' }}>Makanan Siap
                                    Santap</option>
                                <option value="bahan_mentah"
                                    {{ request('metode_pembayaran') == 'bahan_mentah' ? 'selected' : '' }}>Bahan Makanan
                                    Mentah</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Fidyah</label>
                            <select name="fidyah_tipe"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Tipe</option>
                                <option value="mentah" {{ request('fidyah_tipe') == 'mentah' ? 'selected' : '' }}>Fidyah
                                    Mentah (Bahan)</option>
                                <option value="matang" {{ request('fidyah_tipe') == 'matang' ? 'selected' : '' }}>Fidyah
                                    Matang (Makanan)</option>
                                <option value="tunai" {{ request('fidyah_tipe') == 'tunai' ? 'selected' : '' }}>Fidyah
                                    Tunai (Uang)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                            <a href="{{ route('transaksi-datang-langsung.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            {{-- Active Filters Tags --}}
            @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('q'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('jenis_zakat_id') && isset($jenisZakatList))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Jenis Zakat:
                                {{ $jenisZakatList->firstWhere('id', request('jenis_zakat_id'))?->nama ?? request('jenis_zakat_id') }}
                                <button onclick="removeFilter('jenis_zakat_id')"
                                    class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('metode_pembayaran'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Metode: {{ ucfirst(str_replace('_', ' ', request('metode_pembayaran'))) }}
                                <button onclick="removeFilter('metode_pembayaran')"
                                    class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('fidyah_tipe'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Fidyah: {{ ucfirst(request('fidyah_tipe')) }}
                                <button onclick="removeFilter('fidyah_tipe')"
                                    class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('start_date'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Mulai: {{ request('start_date') }}
                                <button onclick="removeFilter('start_date')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('end_date'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Akhir: {{ request('end_date') }}
                                <button onclick="removeFilter('end_date')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($transaksis->count() > 0)

                {{-- ── DESKTOP VIEW ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-3 text-center w-10"></th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">MUZAKKI &amp;
                                    TRANSAKSI</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-28">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
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

                                    $isFidyah = !empty($trx->fidyah_tipe);
                                    $fidyahLabel = match ($trx->fidyah_tipe ?? '') {
                                        'mentah' => 'Bahan Mentah',
                                        'matang' => 'Makanan Matang',
                                        'tunai' => 'Tunai',
                                        default => '',
                                    };

                                    $labelMetode = [
                                        'tunai' => 'Tunai',
                                        'transfer' => 'Transfer Bank',
                                        'qris' => 'QRIS',
                                        'makanan_matang' => 'Makanan Siap Santap',
                                        'bahan_mentah' => 'Bahan Makanan Mentah',
                                        'beras' => 'Beras',
                                    ];
                                    $warnaMetode = match ($trx->metode_pembayaran ?? '') {
                                        'tunai' => 'bg-green-100 text-green-800',
                                        'transfer' => 'bg-blue-100 text-blue-800',
                                        'qris' => 'bg-purple-100 text-purple-800',
                                        'beras' => 'bg-amber-100 text-amber-800',
                                        'makanan_matang' => 'bg-orange-100 text-orange-800',
                                        'bahan_mentah' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $namaMetode =
                                        $labelMetode[$trx->metode_pembayaran ?? ''] ??
                                        ucfirst($trx->metode_pembayaran ?? '-');
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $isFidyah ? 'bg-amber-50/30' : '' }}"
                                    data-target="detail-{{ $trx->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800">
                                                {{ $trx->muzakki_nama ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->waktu_transaksi)
                                                    &middot;
                                                    {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}
                                                @endif
                                                @if ($isFidyah)
                                                    &middot; <span class="font-semibold text-amber-600">Fidyah
                                                        {{ $trx->fidyah_jumlah_hari }} hari</span>
                                                    @if ($trx->fidyah_tipe === 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                        &middot; <span
                                                            class="text-amber-500">{{ $trx->fidyah_total_berat_kg }}
                                                            kg</span>
                                                    @elseif($trx->fidyah_tipe === 'matang' && $trx->fidyah_jumlah_box > 0)
                                                        &middot; <span
                                                            class="text-amber-500">{{ $trx->fidyah_jumlah_box }}
                                                            box</span>
                                                    @elseif($trx->jumlah > 0)
                                                        &middot; <span class="font-semibold text-gray-700">Rp
                                                            {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</span>
                                                    @endif
                                                @else
                                                    @if ($trx->jumlah > 0)
                                                        &middot; <span class="font-semibold text-gray-700">Rp
                                                            {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                                    @elseif($trx->jumlah_beras_kg > 0)
                                                        &middot; <span
                                                            class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }}
                                                            kg</span>
                                                    @endif
                                                    @if ($hasNamaJiwa)
                                                        &middot; <span class="text-blue-600">{{ count($namaJiwaList) }}
                                                            jiwa</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Terverifikasi
                                                </span>
                                                @if ($isFidyah)
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                        Fidyah {{ $fidyahLabel }}
                                                    </span>
                                                @endif
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }} border">
                                                    {{ $namaMetode }}
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    Datang Langsung
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('transaksi-datang-langsung.show', $trx->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
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

                                            <div class="relative group/tooltip">
                                                <a href="{{ route('transaksi-datang-langsung.print', $trx->uuid) }}"
                                                    target="_blank"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                </a>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Cetak Kwitansi
                                                    <div
                                                        class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $trx->uuid }}" class="hidden expandable-content border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi —
                                                    {{ $trx->muzakki_nama ?? '-' }}</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Data Muzakki --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Data Muzakki</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $trx->muzakki_nama ?? '-' }}</p>
                                                        </div>
                                                        @if ($trx->muzakki_nik)
                                                            <div>
                                                                <p class="text-xs text-gray-400">NIK</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $trx->muzakki_nik }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_telepon)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Telepon</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $trx->muzakki_telepon }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_alamat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Alamat</p>
                                                                <p class="text-sm text-gray-700">
                                                                    {{ $trx->muzakki_alamat }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_email)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Email</p>
                                                                <p class="text-sm text-gray-800">
                                                                    {{ $trx->muzakki_email }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Transaksi --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Detail Transaksi</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">No. Transaksi</p>
                                                            <p class="text-sm font-mono font-medium text-gray-800">
                                                                {{ $trx->no_transaksi }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                                @if ($trx->waktu_transaksi)
                                                                    <span
                                                                        class="text-gray-500">({{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }})</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        @if ($trx->jenisZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $trx->jenisZakat->nama }}
                                                                    @if ($trx->tipeZakat)
                                                                        <span
                                                                            class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->programZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Program Zakat</p>
                                                                <p class="text-sm text-gray-800">
                                                                    {{ $trx->programZakat->nama_program }}</p>
                                                            </div>
                                                        @endif

                                                        {{-- DATA FIDYAH --}}
                                                        @if ($isFidyah)
                                                            <div
                                                                class="mt-1 bg-amber-50 border border-amber-200 rounded-lg p-3 space-y-2">
                                                                <div class="flex items-center gap-2">
                                                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <p class="text-xs font-semibold text-amber-800">
                                                                        Fidyah {{ $fidyahLabel }} —
                                                                        {{ $trx->fidyah_jumlah_hari }} hari
                                                                    </p>
                                                                </div>
                                                                @if ($trx->fidyah_tipe === 'mentah')
                                                                    @if ($trx->fidyah_nama_bahan)
                                                                        <p class="text-xs text-amber-700">Bahan:
                                                                            <strong>{{ $trx->fidyah_nama_bahan }}</strong>
                                                                        </p>
                                                                    @endif
                                                                    <p class="text-xs text-amber-700">
                                                                        {{ $trx->fidyah_berat_per_hari_gram ?? 675 }}
                                                                        gram/hari
                                                                        @if ($trx->fidyah_total_berat_kg > 0)
                                                                            = <strong>{{ $trx->fidyah_total_berat_kg }}
                                                                                kg</strong> total
                                                                        @endif
                                                                    </p>
                                                                @elseif($trx->fidyah_tipe === 'matang')
                                                                    @if ($trx->fidyah_jumlah_box)
                                                                        <p class="text-xs text-amber-700">Jumlah:
                                                                            <strong>{{ $trx->fidyah_jumlah_box }}
                                                                                box</strong>
                                                                        </p>
                                                                    @endif
                                                                    @if ($trx->fidyah_menu_makanan)
                                                                        <p class="text-xs text-amber-700">Menu:
                                                                            {{ $trx->fidyah_menu_makanan }}</p>
                                                                    @endif
                                                                    @if ($trx->fidyah_harga_per_box > 0)
                                                                        <p class="text-xs text-amber-700">Harga/box: Rp
                                                                            {{ number_format($trx->fidyah_harga_per_box, 0, ',', '.') }}
                                                                        </p>
                                                                    @endif
                                                                    @if ($trx->fidyah_cara_serah)
                                                                        <p class="text-xs text-amber-700">Cara:
                                                                            {{ ucfirst(str_replace('_', ' ', $trx->fidyah_cara_serah)) }}
                                                                        </p>
                                                                    @endif
                                                                @elseif($trx->fidyah_tipe === 'tunai')
                                                                    @if ($trx->jumlah > 0)
                                                                        <p class="text-xs text-amber-700">Total Dibayar:
                                                                            <strong>Rp
                                                                                {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</strong>
                                                                        </p>
                                                                    @endif
                                                                    @if ($trx->jumlah_infaq > 0)
                                                                        <p class="text-xs text-amber-700">Termasuk infaq:
                                                                            Rp
                                                                            {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}
                                                                        </p>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @else
                                                            {{-- DATA ZAKAT BIASA --}}
                                                            @if ($hasNamaJiwa)
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Nama Jiwa
                                                                        <span
                                                                            class="text-gray-400">({{ count($namaJiwaList) }}
                                                                            orang)</span>
                                                                    </p>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        @foreach ($namaJiwaList as $idx => $nama)
                                                                            @if ($nama && trim($nama) !== '')
                                                                                <span
                                                                                    class="inline-flex items-center bg-white border border-gray-200 rounded px-2 py-0.5 text-xs">
                                                                                    <span
                                                                                        class="text-gray-400 mr-1">{{ $idx + 1 }}.</span>{{ $nama }}
                                                                                </span>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->dataZakatMal)
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Detail Mal</p>
                                                                    <p class="text-xs text-gray-700">
                                                                        Nilai Harta: Rp
                                                                        {{ number_format($trx->dataZakatMal['nilai_harta'] ?? 0, 0, ',', '.') }}
                                                                        @if (!empty($trx->dataZakatMal['persentase']))
                                                                            &middot;
                                                                            {{ $trx->dataZakatMal['persentase'] }}%
                                                                        @endif
                                                                        @if (!empty($trx->dataZakatMal['sudah_haul']))
                                                                            &middot; Sudah Haul
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah > 0)
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Jumlah Zakat</p>
                                                                    <p class="text-sm font-semibold text-green-600">
                                                                        Rp
                                                                        {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                                    </p>
                                                                    @if ($trx->jumlah_dibayar && $trx->jumlah_dibayar != $trx->jumlah)
                                                                        <p class="text-xs text-gray-500">Dibayar: Rp
                                                                            {{ number_format($trx->jumlah_dibayar, 0, ',', '.') }}
                                                                        </p>
                                                                    @endif
                                                                    @if ($trx->jumlah_infaq > 0)
                                                                        <p class="text-xs text-gray-500">+ Infaq Rp
                                                                            {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah_beras_kg > 0 && $trx->metode_pembayaran !== 'tunai')
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Jumlah Beras</p>
                                                                    <p class="text-sm font-semibold text-amber-600">
                                                                        {{ $trx->jumlah_beras_kg }} kg
                                                                        @if (($trx->jumlah_beras_liter ?? 0) > 0)
                                                                            <span class="text-gray-500">
                                                                                ({{ $trx->jumlah_beras_liter }} liter)
                                                                            </span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        @endif

                                                        @if ($trx->keterangan)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Keterangan</p>
                                                                <p class="text-sm text-gray-700">
                                                                    {{ $trx->keterangan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Metode & Status --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Metode &amp; Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode Penerimaan</p>
                                                            <span
                                                                class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                                Datang Langsung
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode Pembayaran</p>
                                                            <span
                                                                class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }} border">
                                                                {{ $namaMetode }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status</p>
                                                            <span
                                                                class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                                                Terverifikasi
                                                            </span>
                                                        </div>
                                                        @if ($trx->verified_at)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Diverifikasi Pada</p>
                                                                <p class="text-sm text-gray-800">
                                                                    {{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}
                                                                </p>
                                                                @if ($trx->amil)
                                                                    <p class="text-xs text-gray-500">Oleh:
                                                                        {{ $trx->amil->nama_lengkap }}</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if (!$isFidyah && $trx->jumlah_jiwa > 0)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jumlah Jiwa</p>
                                                                <p class="text-sm text-gray-800">
                                                                    {{ $trx->jumlah_jiwa }}
                                                                    @if ($trx->nominal_per_jiwa > 0)
                                                                        <span class="text-xs text-gray-500">
                                                                            (@ Rp {{ number_format($trx->nominal_per_jiwa, 0, ',', '.') }}/jiwa)
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            </div>
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

                {{-- ── MOBILE VIEW ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($transaksis as $trx)
                        @php
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
                            $isFidyah = !empty($trx->fidyah_tipe);
                            $fidyahLabel = match ($trx->fidyah_tipe ?? '') {
                                'mentah' => 'Bahan Mentah',
                                'matang' => 'Makanan Matang',
                                'tunai' => 'Tunai',
                                default => '',
                            };
                            $labelMetode = [
                                'tunai' => 'Tunai',
                                'transfer' => 'Transfer Bank',
                                'qris' => 'QRIS',
                                'makanan_matang' => 'Makanan Siap Santap',
                                'bahan_mentah' => 'Bahan Makanan Mentah',
                                'beras' => 'Beras',
                            ];
                            $warnaMetode = match ($trx->metode_pembayaran ?? '') {
                                'tunai' => 'bg-green-100 text-green-800',
                                'transfer' => 'bg-blue-100 text-blue-800',
                                'qris' => 'bg-purple-100 text-purple-800',
                                'beras' => 'bg-amber-100 text-amber-800',
                                'makanan_matang' => 'bg-orange-100 text-orange-800',
                                'bahan_mentah' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-600',
                            };
                            $namaMetode =
                                $labelMetode[$trx->metode_pembayaran ?? ''] ?? ucfirst($trx->metode_pembayaran ?? '-');
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $isFidyah ? 'bg-amber-50/30' : '' }}"
                            data-target="detail-mobile-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">
                                            {{ $trx->muzakki_nama ?? '-' }}
                                        </h3>
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200 flex-shrink-0">Terverifikasi</span>
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span
                                            class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if ($isFidyah)
                                            <span class="text-xs font-semibold text-amber-600">Fidyah
                                                {{ $fidyahLabel }} {{ $trx->fidyah_jumlah_hari }}h</span>
                                            @if ($trx->fidyah_tipe === 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                <span
                                                    class="text-xs text-amber-500">{{ $trx->fidyah_total_berat_kg }}
                                                    kg</span>
                                            @elseif($trx->fidyah_tipe === 'matang' && $trx->fidyah_jumlah_box > 0)
                                                <span
                                                    class="text-xs text-amber-500">{{ $trx->fidyah_jumlah_box }}
                                                    box</span>
                                            @elseif($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-gray-700">Rp
                                                    {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</span>
                                            @endif
                                        @else
                                            @if ($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-green-600">Rp
                                                    {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                            @elseif($trx->jumlah_beras_kg > 0)
                                                <span
                                                    class="text-xs font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }}
                                                    kg</span>
                                            @endif
                                            @if ($hasNamaJiwa)
                                                <span class="text-xs text-blue-600">{{ count($namaJiwaList) }}
                                                    jiwa</span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @if ($isFidyah)
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">Fidyah</span>
                                        @endif
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }} border">
                                            {{ $namaMetode }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk detail</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $trx->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data
                                            Muzakki</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Nama:</span>
                                                {{ $trx->muzakki_nama ?? '-' }}</p>
                                            @if ($trx->muzakki_nik)
                                                <p><span class="text-gray-500">NIK:</span> {{ $trx->muzakki_nik }}</p>
                                            @endif
                                            @if ($trx->muzakki_telepon)
                                                <p><span class="text-gray-500">Telepon:</span>
                                                    {{ $trx->muzakki_telepon }}</p>
                                            @endif
                                            @if ($trx->muzakki_alamat)
                                                <p><span class="text-gray-500">Alamat:</span> {{ $trx->muzakki_alamat }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Detail Transaksi</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">No. Transaksi:</span>
                                                {{ $trx->no_transaksi }}</p>
                                            <p><span class="text-gray-500">Tanggal:</span>
                                                {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                @if ($trx->waktu_transaksi)
                                                    <span class="text-gray-400">
                                                        ({{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }})
                                                    </span>
                                                @endif
                                            </p>
                                            @if ($trx->jenisZakat)
                                                <p><span class="text-gray-500">Jenis Zakat:</span>
                                                    {{ $trx->jenisZakat->nama }}
                                                    @if ($trx->tipeZakat)
                                                        ({{ $trx->tipeZakat->nama }})
                                                    @endif
                                                </p>
                                            @endif

                                            @if ($isFidyah)
                                                <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg space-y-1">
                                                    <p class="text-xs font-semibold text-amber-800">Fidyah
                                                        {{ $fidyahLabel }} — {{ $trx->fidyah_jumlah_hari }} hari</p>
                                                    @if ($trx->fidyah_tipe === 'mentah')
                                                        @if ($trx->fidyah_nama_bahan)
                                                            <p class="text-xs text-amber-700">Bahan:
                                                                <strong>{{ $trx->fidyah_nama_bahan }}</strong>
                                                            </p>
                                                        @endif
                                                        @if ($trx->fidyah_total_berat_kg > 0)
                                                            <p class="text-xs text-amber-700">Total:
                                                                <strong>{{ $trx->fidyah_total_berat_kg }} kg</strong>
                                                            </p>
                                                        @endif
                                                    @elseif($trx->fidyah_tipe === 'matang')
                                                        @if ($trx->fidyah_jumlah_box)
                                                            <p class="text-xs text-amber-700">
                                                                <strong>{{ $trx->fidyah_jumlah_box }} box</strong>
                                                            </p>
                                                        @endif
                                                        @if ($trx->fidyah_menu_makanan)
                                                            <p class="text-xs text-amber-700">{{ $trx->fidyah_menu_makanan }}
                                                            </p>
                                                        @endif
                                                    @elseif($trx->fidyah_tipe === 'tunai')
                                                        @if ($trx->jumlah > 0)
                                                            <p class="text-xs text-amber-700">Total Dibayar:
                                                                <strong>Rp
                                                                    {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</strong>
                                                            </p>
                                                        @endif
                                                    @endif
                                                </div>
                                            @else
                                                @if ($trx->jumlah > 0)
                                                    <p><span class="text-gray-500">Jumlah Zakat:</span> <span
                                                            class="font-semibold text-green-600">Rp
                                                            {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                                    </p>
                                                    @if ($trx->jumlah_infaq > 0)
                                                        <p><span class="text-gray-500">+ Infaq:</span> Rp
                                                            {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                    @endif
                                                @endif
                                                @if ($trx->jumlah_beras_kg > 0 && $trx->metode_pembayaran !== 'tunai')
                                                    <p><span class="text-gray-500">Jumlah Beras:</span>
                                                        <span class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }}
                                                            kg</span>
                                                    </p>
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    <div class="mt-1">
                                                        <p class="text-xs text-gray-500">Nama Jiwa
                                                            ({{ count($namaJiwaList) }} orang)</p>
                                                        <div class="text-xs text-gray-700 mt-1 space-y-0.5">
                                                            @foreach ($namaJiwaList as $idx => $nama)
                                                                @if ($nama && trim($nama) !== '')
                                                                    <div class="flex gap-1">
                                                                        <span
                                                                            class="text-gray-400">{{ $idx + 1 }}.</span><span>{{ $nama }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            @if ($trx->keterangan)
                                                <p><span class="text-gray-500">Keterangan:</span> {{ $trx->keterangan }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Tombol Mobile --}}
                                    <div class="pt-2 border-t border-gray-200 flex gap-2">
                                        <a href="{{ route('transaksi-datang-langsung.show', $trx->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('transaksi-datang-langsung.print', $trx->uuid) }}"
                                            target="_blank"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Kwitansi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($transaksis->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $transaksis->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>

                    @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'fidyah_tipe']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('transaksi-datang-langsung.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada transaksi datang langsung</p>
                        <a href="{{ route('transaksi-datang-langsung.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah transaksi sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Filter button
            const filterBtn = document.getElementById('filterButton');
            if (filterBtn) {
                filterBtn.addEventListener('click', toggleFilter);
            }

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon = this.querySelector('.expand-icon');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-90');
                    }
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon = this.querySelector('.expand-icon-mobile');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });
        });

        // ── Search & Filter ──
        function toggleFilter() {
            const panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        }

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endpush