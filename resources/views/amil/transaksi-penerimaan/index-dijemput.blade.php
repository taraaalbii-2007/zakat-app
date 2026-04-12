{{--
    resources/views/amil/transaksi-penerimaan/index-dijemput.blade.php

    DIPAKAI OLEH  : Amil / Admin Masjid
    CONTROLLER    : indexDijemput() — filter byMetodePenerimaan('dijemput')
    ROUTE         : GET /transaksi-dijemput
--}}

@extends('layouts.app')

@section('title', 'Transaksi Dijemput')

@php
    $labelMetode = [
        'tunai' => 'Tunai',
        'transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
        'beras' => 'Beras',
        'makanan_matang' => 'Makanan Siap Santap',
        'bahan_mentah' => 'Bahan Makanan Mentah',
    ];

    $badgeMetode = [
        'tunai' => 'bg-green-100 text-green-800',
        'transfer' => 'bg-blue-100 text-blue-800',
        'qris' => 'bg-purple-100 text-purple-800',
        'beras' => 'bg-amber-100 text-amber-800',
        'makanan_matang' => 'bg-orange-100 text-orange-800',
        'bahan_mentah' => 'bg-yellow-100 text-yellow-800',
    ];
@endphp

@section('content')
    <div class="space-y-6">

        {{-- ── Alert: Perlu Dilengkapi ── --}}
        @if (isset($stats['perlu_dilengkapi']) && $stats['perlu_dilengkapi'] > 0)
            <div class="flex items-center gap-3 px-5 py-3 bg-orange-50 border border-orange-200 rounded-xl">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-orange-800">
                        {{ $stats['perlu_dilengkapi'] }} transaksi sudah dijemput tapi belum dilengkapi detail zakatnya
                    </p>
                    <p class="text-xs text-orange-600 mt-0.5">Lengkapi data zakat setelah penjemputan selesai</p>
                </div>
                <a href="{{ route('transaksi-dijemput.index', ['status_penjemputan' => 'selesai', 'perlu_dilengkapi' => 1]) }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-orange-100 hover:bg-orange-200 text-orange-800 text-xs font-medium rounded-lg transition-all">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

        {{-- ── Statistics Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Total</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</p>
                        <p class="text-xs text-green-600 mt-0.5">Transaksi Dijemput</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Menunggu</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['menunggu'], 0, ',', '.') }}</p>
                        <p class="text-xs text-amber-600 mt-0.5">Belum dijemput</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Dalam Proses</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['dalam_proses'], 0, ',', '.') }}</p>
                        <p class="text-xs text-blue-600 mt-0.5">Sedang berjalan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Selesai</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['selesai'], 0, ',', '.') }}</p>
                        <p class="text-xs text-green-600 mt-0.5">Terverifikasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Perlu Dilengkapi</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['perlu_dilengkapi'], 0, ',', '.') }}</p>
                        <p class="text-xs text-orange-600 mt-0.5">Belum ada zakat</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Transaksi Dijemput</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola transaksi zakat yang dijemput oleh amil</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                            @if (request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date', 'q']))
                                <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-green-600 text-white rounded-full">
                                    {{ collect(['status_penjemputan', 'amil_id', 'start_date', 'end_date', 'q'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </button>
                        <a href="{{ route('transaksi-dijemput.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($stats['total']) }}</span>
                        <span class="text-sm text-gray-500">Transaksi</span>
                    </div>
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                            <span class="text-xs text-gray-500">Menunggu:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['menunggu']) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <span class="text-xs text-gray-500">Proses:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['dalam_proses']) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Selesai:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['selesai']) }}</span>
                        </div>
                        @if (isset($stats['perlu_dilengkapi']) && $stats['perlu_dilengkapi'] > 0)
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                <span class="text-xs text-gray-500">Perlu Dilengkapi:</span>
                                <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['perlu_dilengkapi']) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date', 'q']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('transaksi-dijemput.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Transaksi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari no. transaksi / muzakki..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status Penjemputan</label>
                            <select name="status_penjemputan"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status_penjemputan') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diterima" {{ request('status_penjemputan') == 'diterima' ? 'selected' : '' }}>Diterima Amil</option>
                                <option value="dalam_perjalanan" {{ request('status_penjemputan') == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                <option value="sampai_lokasi" {{ request('status_penjemputan') == 'sampai_lokasi' ? 'selected' : '' }}>Sampai Lokasi</option>
                                <option value="selesai" {{ request('status_penjemputan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Amil</label>
                            <select name="amil_id"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Amil</option>
                                @foreach ($amilList as $amil)
                                    <option value="{{ $amil->id }}" {{ request('amil_id') == $amil->id ? 'selected' : '' }}>
                                        {{ $amil->nama_lengkap }}
                                    </option>
                                @endforeach
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
                        @if (request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date', 'q']))
                            <a href="{{ route('transaksi-dijemput.index') }}"
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
            @if (request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date', 'q']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">x</button>
                            </div>
                        @endif
                        @if (request('status_penjemputan'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(str_replace('_', ' ', request('status_penjemputan'))) }}
                                <button onclick="removeFilter('status_penjemputan')" class="hover:text-green-900 ml-1">x</button>
                            </div>
                        @endif
                        @if (request('amil_id') && isset($amilList))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Amil: {{ $amilList->firstWhere('id', request('amil_id'))?->nama_lengkap ?? request('amil_id') }}
                                <button onclick="removeFilter('amil_id')" class="hover:text-green-900 ml-1">x</button>
                            </div>
                        @endif
                        @if (request('start_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Mulai: {{ request('start_date') }}
                                <button onclick="removeFilter('start_date')" class="hover:text-green-900 ml-1">x</button>
                            </div>
                        @endif
                        @if (request('end_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Akhir: {{ request('end_date') }}
                                <button onclick="removeFilter('end_date')" class="hover:text-green-900 ml-1">x</button>
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">MUZAKKI &amp; TRANSAKSI</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-28">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
                                    // ── SEMUA VARIABEL DIDEFINISIKAN DI SINI, DI AWAL FOREACH ──
                                    $statusPenjemputan = $trx->status_penjemputan ?? 'menunggu';
                                    $sudahDijemput     = in_array($statusPenjemputan, ['sampai_lokasi', 'selesai']);
                                    $isAutoVerified    = $trx->status == 'verified' && $trx->metode_pembayaran;

                                    $isBeras       = $trx->metode_pembayaran === 'beras' || ($trx->jumlah_beras_kg > 0 && $trx->metode_pembayaran === 'tunai');
                                    $isFidyah      = !empty($trx->fidyah_tipe);
                                    $isMakananMatang = $trx->metode_pembayaran === 'makanan_matang';
                                    $isBahanMentah = $trx->metode_pembayaran === 'bahan_mentah';

                                    $perluLengkapi = $sudahDijemput && !$trx->jenis_zakat_id && $trx->status !== 'verified' && !$isFidyah;

                                    // ✅ $isBlockedSelesai didefinisikan di awal agar tersedia di seluruh baris (badge, tombol, dll)
                                    $isBlockedSelesai = ($statusPenjemputan === 'sampai_lokasi' && !$trx->jenis_zakat_id && !$isFidyah);

                                    $nextStatus = [
                                        'menunggu'        => ['diterima', 'Terima'],
                                        'diterima'        => ['dalam_perjalanan', 'Berangkat'],
                                        'dalam_perjalanan'=> ['sampai_lokasi', 'Sampai'],
                                        'sampai_lokasi'   => ['selesai', 'Selesai'],
                                    ][$statusPenjemputan] ?? null;

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

                                    $metodeKey  = $trx->metode_pembayaran ?? '';
                                    if ($trx->jumlah_beras_kg > 0 && $metodeKey === 'tunai') {
                                        $metodeKey = 'beras';
                                    }
                                    $namaMetode = $labelMetode[$metodeKey] ?? ucfirst($metodeKey);
                                    $classBadge = $badgeMetode[$metodeKey] ?? 'bg-gray-100 text-gray-600';

                                    $fidyahLabel = match ($trx->fidyah_tipe ?? '') {
                                        'mentah' => 'Bahan Mentah',
                                        'matang' => 'Makanan Matang',
                                        'tunai'  => 'Tunai',
                                        default  => '',
                                    };
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $isBlockedSelesai ? 'bg-orange-50/30' : '' }}
                                    {{ $perluLengkapi && !$isBlockedSelesai ? 'bg-orange-50/30' : '' }}
                                    {{ $isAutoVerified && !$perluLengkapi && !$isBlockedSelesai ? 'bg-green-50/20' : '' }}"
                                    data-target="detail-{{ $trx->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
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
                                                    &middot; {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}
                                                @endif
                                                @if ($trx->waktu_request)
                                                    &middot; Request: {{ \Carbon\Carbon::parse($trx->waktu_request)->format('H:i') }}
                                                @endif
                                                @if ($isFidyah)
                                                    &middot; <span class="font-semibold text-amber-600">Fidyah {{ $trx->fidyah_jumlah_hari }} hari</span>
                                                    @if ($trx->fidyah_tipe === 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                        &middot; <span class="text-amber-500">{{ $trx->fidyah_total_berat_kg }} kg</span>
                                                    @elseif($trx->fidyah_tipe === 'matang' && $trx->fidyah_jumlah_box > 0)
                                                        &middot; <span class="text-amber-500">{{ $trx->fidyah_jumlah_box }} box</span>
                                                    @elseif($trx->jumlah > 0)
                                                        &middot; <span class="font-semibold text-gray-700">Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</span>
                                                    @endif
                                                @else
                                                    @if ($trx->jumlah > 0)
                                                        &middot; <span class="font-semibold text-gray-700">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                                    @elseif($trx->jumlah_beras_kg > 0)
                                                        &middot; <span class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                                    @endif
                                                    @if ($hasNamaJiwa)
                                                        &middot; <span class="text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                                    @endif
                                                @endif
                                            </div>

                                            {{-- Badge area - $isBlockedSelesai sudah tersedia di sini --}}
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_penjemputan_badge !!}

                                                @if ($isAutoVerified)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                                        Auto Verified
                                                    </span>
                                                @endif

                                                @if ($isBlockedSelesai)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                        Lengkapi Zakat Dulu
                                                    </span>
                                                @elseif ($perluLengkapi)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                        Perlu Dilengkapi
                                                    </span>
                                                @endif

                                                @if ($isFidyah)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                        Fidyah {{ $fidyahLabel }}
                                                    </span>
                                                @endif

                                                {{-- Badge metode: hanya tampil jika sudah diisi --}}
                                                @if ($metodeKey)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $classBadge }} border">
                                                        {{ $namaMetode }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Tombol Update Status / Paksa Lengkapi Zakat --}}
                                            @if ($nextStatus && (auth()->user()->isAmil() || auth()->user()->isAdminMasjid()))
                                                <div class="relative group/tooltip">
                                                    @if ($isBlockedSelesai)
                                                        {{-- Sampai lokasi tapi belum ada zakat: wajib lengkapi dulu, tidak bisa klik Selesai --}}
                                                        <a href="{{ route('transaksi-dijemput.edit', $trx->uuid) }}"
                                                            class="flex items-center justify-center p-1.5 text-orange-500 hover:text-orange-700 hover:bg-orange-50 rounded-lg transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-orange-700 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                            Lengkapi Zakat Dulu
                                                            <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-orange-700"></div>
                                                        </div>
                                                    @else
                                                        <button type="button"
                                                            onclick="updateStatusPenjemputan('{{ $trx->uuid }}', '{{ $nextStatus[0] }}', this)"
                                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                        </button>
                                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                            {{ $nextStatus[1] }}
                                                            <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Tombol Lengkapi Zakat: hanya muncul saat status selesai tapi belum dilengkapi --}}
                                            @if ($perluLengkapi && !$isBlockedSelesai)
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('transaksi-dijemput.edit', $trx->uuid) }}"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Lengkapi Zakat
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Tombol Detail --}}
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('transaksi-dijemput.show', $trx->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Detail
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            {{-- Tombol Maps --}}
                                            @if ($trx->latitude && $trx->longitude)
                                                <div class="relative group/tooltip">
                                                    <a href="https://maps.google.com/?q={{ $trx->latitude }},{{ $trx->longitude }}" target="_blank"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    </a>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Buka Maps
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

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
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi — {{ $trx->muzakki_nama ?? '-' }}</h3>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Data Muzakki --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Muzakki</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $trx->muzakki_nama ?? '-' }}</p>
                                                        </div>
                                                        @if ($trx->muzakki_nik)
                                                            <div>
                                                                <p class="text-xs text-gray-400">NIK</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $trx->muzakki_nik }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_telepon)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Telepon</p>
                                                                <a href="https://wa.me/62{{ ltrim($trx->muzakki_telepon, '0') }}" target="_blank"
                                                                    class="text-sm font-medium text-green-600 hover:underline">{{ $trx->muzakki_telepon }}</a>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_alamat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Alamat</p>
                                                                <p class="text-sm text-gray-700">{{ $trx->muzakki_alamat }}</p>
                                                                @if ($trx->latitude && $trx->longitude)
                                                                    <a href="https://maps.google.com/?q={{ $trx->latitude }},{{ $trx->longitude }}" target="_blank"
                                                                        class="text-xs text-blue-600 hover:underline mt-0.5 inline-block">Buka di Maps</a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_email)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Email</p>
                                                                <p class="text-sm text-gray-800">{{ $trx->muzakki_email }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Transaksi --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Detail Transaksi</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">No. Transaksi</p>
                                                            <p class="text-sm font-mono font-medium text-gray-800">{{ $trx->no_transaksi }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                                @if ($trx->waktu_transaksi)
                                                                    <span class="text-gray-500">({{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }})</span>
                                                                @endif
                                                            </p>
                                                            @if ($trx->waktu_request)
                                                                <p class="text-xs text-gray-400 mt-0.5">Request: {{ \Carbon\Carbon::parse($trx->waktu_request)->format('H:i') }}</p>
                                                            @endif
                                                        </div>
                                                        @if ($trx->jenisZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $trx->jenisZakat->nama }}
                                                                    @if ($trx->tipeZakat)
                                                                        <span class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jenis Zakat</p>
                                                                <p class="text-xs text-gray-400 italic">
                                                                    {{ ($perluLengkapi || $isBlockedSelesai) ? 'Perlu dilengkapi' : 'Diisi saat penjemputan' }}
                                                                </p>
                                                            </div>
                                                        @endif

                                                        @if ($isFidyah)
                                                            <div class="mt-1 bg-amber-50 border border-amber-200 rounded-lg p-3 space-y-2">
                                                                <div class="flex items-center gap-2">
                                                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <p class="text-xs font-semibold text-amber-800">Fidyah {{ $fidyahLabel }} — {{ $trx->fidyah_jumlah_hari }} hari</p>
                                                                </div>
                                                                @if ($trx->fidyah_tipe === 'mentah')
                                                                    @if ($trx->fidyah_nama_bahan)
                                                                        <p class="text-xs text-amber-700">Bahan: <strong>{{ $trx->fidyah_nama_bahan }}</strong></p>
                                                                    @endif
                                                                    <p class="text-xs text-amber-700">
                                                                        {{ $trx->fidyah_berat_per_hari_gram ?? 675 }} gram/hari
                                                                        @if ($trx->fidyah_total_berat_kg > 0)
                                                                            = <strong>{{ $trx->fidyah_total_berat_kg }} kg</strong> total
                                                                        @endif
                                                                    </p>
                                                                @elseif($trx->fidyah_tipe === 'matang')
                                                                    @if ($trx->fidyah_jumlah_box)
                                                                        <p class="text-xs text-amber-700">Jumlah: <strong>{{ $trx->fidyah_jumlah_box }} box</strong></p>
                                                                    @endif
                                                                    @if ($trx->fidyah_menu_makanan)
                                                                        <p class="text-xs text-amber-700">Menu: {{ $trx->fidyah_menu_makanan }}</p>
                                                                    @endif
                                                                @elseif($trx->fidyah_tipe === 'tunai')
                                                                    @if ($trx->jumlah > 0)
                                                                        <p class="text-xs text-amber-700">Total Dibayar: <strong>Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</strong></p>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @else
                                                            @if ($hasNamaJiwa)
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Nama Jiwa <span class="text-gray-400">({{ count($namaJiwaList) }} orang)</span></p>
                                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                                        @foreach ($namaJiwaList as $idx => $nama)
                                                                            @if ($nama && trim($nama) !== '')
                                                                                <span class="inline-flex items-center bg-white border border-gray-200 rounded px-2 py-0.5 text-xs">
                                                                                    <span class="text-gray-400 mr-1">{{ $idx + 1 }}.</span>{{ $nama }}
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
                                                                        Nilai Harta: Rp {{ number_format($trx->dataZakatMal['nilai_harta'] ?? 0, 0, ',', '.') }}
                                                                        @if (!empty($trx->dataZakatMal['persentase']))
                                                                            &middot; {{ $trx->dataZakatMal['persentase'] }}%
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah > 0)
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Jumlah Zakat</p>
                                                                    <p class="text-sm font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                                                    @if ($trx->jumlah_dibayar && $trx->jumlah_dibayar != $trx->jumlah)
                                                                        <p class="text-xs text-gray-500">Dibayar: Rp {{ number_format($trx->jumlah_dibayar, 0, ',', '.') }}</p>
                                                                    @endif
                                                                    @if ($trx->jumlah_infaq > 0)
                                                                        <p class="text-xs text-gray-500">+ Infaq Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah_beras_kg > 0 && $trx->metode_pembayaran !== 'tunai')
                                                                <div>
                                                                    <p class="text-xs text-gray-400">Jumlah Beras</p>
                                                                    <p class="text-sm font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</p>
                                                                </div>
                                                            @endif
                                                        @endif

                                                        @if ($trx->keterangan)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Keterangan</p>
                                                                <p class="text-sm text-gray-700">{{ $trx->keterangan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Metode & Status --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Metode &amp; Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode Penerimaan</p>
                                                            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                                Dijemput
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode Pembayaran</p>
                                                            @if ($metodeKey)
                                                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full {{ $classBadge }} border">
                                                                    {{ $namaMetode }}
                                                                </span>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">Belum diisi</span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status Penjemputan</p>
                                                            {!! $trx->status_penjemputan_badge !!}
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status Transaksi</p>
                                                            {!! $trx->status_badge !!}
                                                            @if ($isAutoVerified)
                                                                <span class="ml-1 text-xs text-green-600">(Auto Verified)</span>
                                                            @endif
                                                        </div>
                                                        @if ($trx->amil)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Amil</p>
                                                                <p class="text-sm text-gray-800">{{ $trx->amil->nama_lengkap }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->waktu_diterima_amil || $trx->waktu_berangkat || $trx->waktu_sampai || $trx->waktu_selesai)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Tracking Waktu</p>
                                                                <div class="space-y-0.5 text-xs text-gray-600">
                                                                    @if ($trx->waktu_diterima_amil)
                                                                        <p>Diterima: {{ \Carbon\Carbon::parse($trx->waktu_diterima_amil)->format('H:i') }}</p>
                                                                    @endif
                                                                    @if ($trx->waktu_berangkat)
                                                                        <p>Berangkat: {{ \Carbon\Carbon::parse($trx->waktu_berangkat)->format('H:i') }}</p>
                                                                    @endif
                                                                    @if ($trx->waktu_sampai)
                                                                        <p>Sampai: {{ \Carbon\Carbon::parse($trx->waktu_sampai)->format('H:i') }}</p>
                                                                    @endif
                                                                    @if ($trx->waktu_selesai)
                                                                        <p>Selesai: {{ \Carbon\Carbon::parse($trx->waktu_selesai)->format('H:i') }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-2 border-t border-gray-200">
                                                <p class="text-xs text-gray-400">ID Transaksi: <span class="font-medium text-gray-600">{{ $trx->id }}</span></p>
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
                            // ── SEMUA VARIABEL DIDEFINISIKAN DI SINI, DI AWAL FOREACH MOBILE ──
                            $statusPenjemputan = $trx->status_penjemputan ?? 'menunggu';
                            $sudahDijemput     = in_array($statusPenjemputan, ['sampai_lokasi', 'selesai']);
                            $isAutoVerified    = $trx->status == 'verified' && $trx->metode_pembayaran;

                            $isBeras  = $trx->metode_pembayaran === 'beras' || ($trx->jumlah_beras_kg > 0 && $trx->metode_pembayaran === 'tunai');
                            $isFidyah = !empty($trx->fidyah_tipe);

                            $perluLengkapi = $sudahDijemput && !$trx->jenis_zakat_id && $trx->status !== 'verified' && !$isFidyah;

                            // ✅ $isBlockedSelesai didefinisikan di awal agar tersedia di seluruh bagian mobile
                            $isBlockedSelesai = ($statusPenjemputan === 'sampai_lokasi' && !$trx->jenis_zakat_id && !$isFidyah);

                            $nextStatus = [
                                'menunggu'         => ['diterima', 'Terima'],
                                'diterima'         => ['dalam_perjalanan', 'Berangkat'],
                                'dalam_perjalanan' => ['sampai_lokasi', 'Sampai'],
                                'sampai_lokasi'    => ['selesai', 'Selesai'],
                            ][$statusPenjemputan] ?? null;

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

                            $metodeKey  = $trx->metode_pembayaran ?? '';
                            if ($trx->jumlah_beras_kg > 0 && $metodeKey === 'tunai') {
                                $metodeKey = 'beras';
                            }
                            $namaMetode  = $labelMetode[$metodeKey] ?? ucfirst($metodeKey);
                            $classBadge  = $badgeMetode[$metodeKey] ?? 'bg-gray-100 text-gray-600';
                            $fidyahLabel = match ($trx->fidyah_tipe ?? '') {
                                'mentah' => 'Bahan Mentah',
                                'matang' => 'Makanan Matang',
                                'tunai'  => 'Tunai',
                                default  => '',
                            };
                        @endphp

                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $isBlockedSelesai ? 'bg-orange-50/30' : '' }}
                            {{ $perluLengkapi && !$isBlockedSelesai ? 'bg-orange-50/30' : '' }}
                            {{ $isAutoVerified && !$perluLengkapi && !$isBlockedSelesai ? 'bg-green-50/20' : '' }}"
                            data-target="detail-mobile-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $trx->muzakki_nama ?? '-' }}</h3>
                                        {!! $trx->status_penjemputan_badge !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if ($isFidyah)
                                            <span class="text-xs font-semibold text-amber-600">Fidyah {{ $fidyahLabel }} {{ $trx->fidyah_jumlah_hari }}h</span>
                                            @if ($trx->fidyah_tipe === 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                <span class="text-xs text-amber-500">{{ $trx->fidyah_total_berat_kg }} kg</span>
                                            @elseif($trx->fidyah_tipe === 'matang' && $trx->fidyah_jumlah_box > 0)
                                                <span class="text-xs text-amber-500">{{ $trx->fidyah_jumlah_box }} box</span>
                                            @elseif($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</span>
                                            @endif
                                        @else
                                            @if ($trx->jumlah > 0)
                                                <span class="text-xs font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                            @elseif($trx->jumlah_beras_kg > 0)
                                                <span class="text-xs font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                            @endif
                                            @if ($hasNamaJiwa)
                                                <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @if ($isAutoVerified)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Auto Verified</span>
                                        @endif
                                        @if ($isBlockedSelesai)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">Lengkapi Zakat Dulu</span>
                                        @elseif ($perluLengkapi)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">Perlu Dilengkapi</span>
                                        @endif
                                        @if ($isFidyah)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">Fidyah</span>
                                        @endif
                                        {{-- Badge metode: hanya tampil jika sudah diisi --}}
                                        @if ($metodeKey)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $classBadge }} border">{{ $namaMetode }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk detail</div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button"
                                        class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-uuid="{{ $trx->uuid }}"
                                        data-nama="{{ $trx->muzakki_nama }}"
                                        data-perlu-lengkapi="{{ ($perluLengkapi || $isBlockedSelesai) ? '1' : '0' }}"
                                        data-no-transaksi="{{ $trx->no_transaksi }}"
                                        data-jumlah="{{ $trx->jumlah_formatted ?? number_format($trx->jumlah, 0, ',', '.') }}"
                                        data-can-delete="{{ in_array($trx->status, ['pending', 'rejected']) ? '1' : '0' }}"
                                        data-next-status="{{ ($nextStatus && !$isBlockedSelesai) ? $nextStatus[0] : '' }}"
                                        data-next-label="{{ ($nextStatus && !$isBlockedSelesai) ? $nextStatus[1] : '' }}"
                                        data-lat="{{ $trx->latitude }}"
                                        data-lng="{{ $trx->longitude }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $trx->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data Muzakki</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Nama:</span> {{ $trx->muzakki_nama ?? '-' }}</p>
                                            @if ($trx->muzakki_nik)
                                                <p><span class="text-gray-500">NIK:</span> {{ $trx->muzakki_nik }}</p>
                                            @endif
                                            @if ($trx->muzakki_telepon)
                                                <p><span class="text-gray-500">Telepon:</span>
                                                    <a href="https://wa.me/62{{ ltrim($trx->muzakki_telepon, '0') }}" target="_blank" class="text-green-600 hover:underline">{{ $trx->muzakki_telepon }}</a>
                                                </p>
                                            @endif
                                            @if ($trx->muzakki_alamat)
                                                <p><span class="text-gray-500">Alamat:</span> {{ $trx->muzakki_alamat }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Detail Transaksi</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">No. Transaksi:</span> {{ $trx->no_transaksi }}</p>
                                            <p><span class="text-gray-500">Tanggal:</span> {{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                            @if ($trx->jenisZakat)
                                                <p><span class="text-gray-500">Jenis Zakat:</span> {{ $trx->jenisZakat->nama }}</p>
                                            @else
                                                <p><span class="text-gray-500">Jenis Zakat:</span>
                                                    <span class="text-gray-400 italic">{{ ($perluLengkapi || $isBlockedSelesai) ? 'Perlu dilengkapi' : 'Diisi saat penjemputan' }}</span>
                                                </p>
                                            @endif
                                            @if ($isFidyah)
                                                <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg space-y-1">
                                                    <p class="text-xs font-semibold text-amber-800">Fidyah {{ $fidyahLabel }} — {{ $trx->fidyah_jumlah_hari }} hari</p>
                                                    @if ($trx->fidyah_tipe === 'mentah')
                                                        @if ($trx->fidyah_nama_bahan)
                                                            <p class="text-xs text-amber-700">Bahan: <strong>{{ $trx->fidyah_nama_bahan }}</strong></p>
                                                        @endif
                                                        @if ($trx->fidyah_total_berat_kg > 0)
                                                            <p class="text-xs text-amber-700">Total: <strong>{{ $trx->fidyah_total_berat_kg }} kg</strong></p>
                                                        @endif
                                                    @elseif($trx->fidyah_tipe === 'matang')
                                                        @if ($trx->fidyah_jumlah_box)
                                                            <p class="text-xs text-amber-700"><strong>{{ $trx->fidyah_jumlah_box }} box</strong></p>
                                                        @endif
                                                        @if ($trx->fidyah_menu_makanan)
                                                            <p class="text-xs text-amber-700">{{ $trx->fidyah_menu_makanan }}</p>
                                                        @endif
                                                    @elseif($trx->fidyah_tipe === 'tunai')
                                                        @if ($trx->jumlah > 0)
                                                            <p class="text-xs text-amber-700">Total Dibayar: <strong>Rp {{ number_format($trx->jumlah_dibayar ?? $trx->jumlah, 0, ',', '.') }}</strong></p>
                                                        @endif
                                                    @endif
                                                </div>
                                            @else
                                                @if ($trx->jumlah > 0)
                                                    <p><span class="text-gray-500">Jumlah Zakat:</span> <span class="font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span></p>
                                                @endif
                                                @if ($trx->jumlah_beras_kg > 0 && $trx->metode_pembayaran !== 'tunai')
                                                    <p><span class="text-gray-500">Jumlah Beras:</span> <span class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span></p>
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    <div class="mt-1">
                                                        <p class="text-xs text-gray-500">Nama Jiwa ({{ count($namaJiwaList) }} orang)</p>
                                                        <div class="text-xs text-gray-700 mt-1 space-y-0.5">
                                                            @foreach ($namaJiwaList as $idx => $nama)
                                                                @if ($nama && trim($nama) !== '')
                                                                    <div class="flex gap-1"><span class="text-gray-400">{{ $idx + 1 }}.</span><span>{{ $nama }}</span></div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi Mobile --}}
                                    <div class="pt-2 border-t border-gray-200 flex gap-2">
                                        @if ($nextStatus && (auth()->user()->isAmil() || auth()->user()->isAdminMasjid()))
                                            @if ($isBlockedSelesai)
                                                {{-- Wajib lengkapi zakat dulu --}}
                                                <a href="{{ route('transaksi-dijemput.edit', $trx->uuid) }}"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Lengkapi Zakat
                                                </a>
                                            @else
                                                <button type="button"
                                                    onclick="updateStatusPenjemputan('{{ $trx->uuid }}', '{{ $nextStatus[0] }}', this)"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                    </svg>
                                                    {{ $nextStatus[1] }}
                                                </button>
                                            @endif
                                        @endif

                                        {{-- Tombol lengkapi: hanya muncul saat selesai tapi belum dilengkapi --}}
                                        @if ($perluLengkapi && !$isBlockedSelesai)
                                            <a href="{{ route('transaksi-dijemput.edit', $trx->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Lengkapi
                                            </a>
                                        @endif

                                        <a href="{{ route('transaksi-dijemput.show', $trx->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
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
                    @endforeach
                </div>

                @if ($transaksis->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $transaksis->withQueryString()->links() }}
                    </div>
                @endif

            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date', 'q']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('transaksi-dijemput.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada transaksi dijemput</p>
                        <a href="{{ route('transaksi-dijemput.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah penjemputan sekarang
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
                <a href="#" id="dd-detail" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <button type="button" id="dd-next-status" class="flex items-center w-full px-4 py-2.5 text-sm text-blue-700 hover:bg-blue-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span id="dd-next-label">Update Status</span>
                </button>
                <a href="#" id="dd-lengkapi" class="flex items-center px-4 py-2.5 text-sm text-orange-600 hover:bg-orange-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Lengkapi Zakat
                </a>
                <a href="#" id="dd-maps" class="flex items-center px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition-colors hidden" target="_blank">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Buka di Maps
                </a>
                <div class="border-t border-gray-100 my-1" id="dd-divider-delete" style="display:none;"></div>
                <button type="button" id="dd-delete" class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal: Delete ── --}}
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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

    {{-- ── Toast Notifikasi AJAX ── --}}
    <div id="toast-status" class="fixed bottom-5 right-5 z-50 hidden transition-all">
        <div class="bg-gray-900 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-2">
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toast-msg">Status berhasil diupdate</span>
        </div>
    </div>

    <style>
        .rotate-90  { transform: rotate(90deg); }
        .rotate-180 { transform: rotate(180deg); }
    </style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const filterBtn = document.getElementById('filterButton');
        if (filterBtn) filterBtn.addEventListener('click', toggleFilter);

        // Desktop expandable rows
        document.querySelectorAll('.expandable-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, button, .dropdown-toggle')) return;
                var target = document.getElementById(this.dataset.target);
                var icon   = this.querySelector('.expand-icon');
                if (target && icon) {
                    target.classList.toggle('hidden');
                    icon.classList.toggle('rotate-90');
                }
            });
        });

        // Mobile expandable cards
        document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, button, .dropdown-toggle')) return;
                var target = document.getElementById(this.dataset.target);
                var icon   = this.querySelector('.expand-icon-mobile');
                if (target && icon) {
                    target.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            });
        });
    });

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

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
    }

    function openDeleteModal(uuid, nama) {
        document.getElementById('modal-delete-nama').textContent = nama;
        document.getElementById('delete-form').action = `/transaksi-dijemput/${uuid}`;
        openModal('delete-modal');
    }

    function updateStatusPenjemputan(uuid, status, btn) {
        const label = status.replace(/_/g, ' ');
        if (!confirm('Update status penjemputan ke "' + label + '"?')) return;

        const orig = btn.innerHTML;
        btn.disabled  = true;
        btn.textContent = 'Memproses...';

        fetch(`/transaksi-dijemput/${uuid}/update-status-penjemputan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: status })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Status diupdate: ' + label);
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.error || 'Gagal update status.');
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        })
        .catch(() => {
            alert('Gagal menghubungi server.');
            btn.disabled = false;
            btn.innerHTML = orig;
        });
    }

    function showToast(msg) {
        const toast    = document.getElementById('toast-status');
        const toastMsg = document.getElementById('toast-msg');
        if (toast && toastMsg) {
            toastMsg.textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2500);
        }
    }
</script>
@endpush