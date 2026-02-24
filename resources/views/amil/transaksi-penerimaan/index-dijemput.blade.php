{{--
    resources/views/amil/transaksi-penerimaan/index-dijemput.blade.php

    DIPAKAI OLEH  : Amil / Admin Masjid
    CONTROLLER    : indexDijemput() — filter byMetodePenerimaan('dijemput')
    ROUTE         : GET /transaksi-dijemput

    FITUR KHUSUS  :
      - Kolom status_penjemputan (menunggu/diterima/dalam_perjalanan/sampai_lokasi/selesai)
      - Tombol "Lengkapi Zakat" untuk transaksi yang belum ada detail zakatnya
      - Tombol update status penjemputan (AJAX)
      - Ada tombol Create baru (berbeda dengan daring)

    VARIABEL      : $transaksis (paginated), $amilList, $stats
--}}

@extends('layouts.app')

@section('title', 'Transaksi Dijemput')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Alert: Perlu Dilengkapi ── --}}
        @if (isset($stats['perlu_dilengkapi']) && $stats['perlu_dilengkapi'] > 0)
            <div class="flex items-center gap-3 px-4 py-3 bg-orange-50 border border-orange-200 rounded-xl animate-slide-up">
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
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 animate-slide-up">
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
                        <p class="text-xs text-green-600 mt-0.5">Dijemput</p>
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
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['menunggu'], 0, ',', '.') }}</p>
                        <p class="text-xs text-amber-600 mt-0.5">Belum dijemput</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Dalam Proses</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['dalam_proses'], 0, ',', '.') }}</p>
                        <p class="text-xs text-blue-600 mt-0.5">Sedang berjalan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Perlu Dilengkapi</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ number_format($stats['perlu_dilengkapi'], 0, ',', '.') }}</p>
                        <p class="text-xs text-orange-600 mt-0.5">Belum ada zakat</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4 col-span-2 lg:col-span-1">
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
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Transaksi Dijemput</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Tambah --}}
                        <a href="{{ route('transaksi-dijemput.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Tambah</span>
                        </a>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('transaksi-dijemput.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status_penjemputan', 'amil_id', 'start_date', 'end_date'] as $filter)
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
                                    @if (request()->hasAny(['q', 'status_penjemputan', 'amil_id', 'start_date', 'end_date']))
                                        <a href="{{ route('transaksi-dijemput.index') }}"
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
                class="{{ request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('transaksi-dijemput.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Penjemputan</label>
                            <select name="status_penjemputan"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="menunggu"         {{ request('status_penjemputan') == 'menunggu'         ? 'selected' : '' }}>Menunggu</option>
                                <option value="diterima"         {{ request('status_penjemputan') == 'diterima'         ? 'selected' : '' }}>Diterima Amil</option>
                                <option value="dalam_perjalanan" {{ request('status_penjemputan') == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                <option value="sampai_lokasi"    {{ request('status_penjemputan') == 'sampai_lokasi'    ? 'selected' : '' }}>Sampai Lokasi</option>
                                <option value="selesai"          {{ request('status_penjemputan') == 'selesai'          ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Amil</label>
                            <select name="amil_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Amil</option>
                                @foreach ($amilList as $amil)
                                    <option value="{{ $amil->id }}" {{ request('amil_id') == $amil->id ? 'selected' : '' }}>
                                        {{ $amil->nama_lengkap }}
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

                    @if (request()->hasAny(['status_penjemputan', 'amil_id', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('transaksi-dijemput.index', request('q') ? ['q' => request('q')] : []) }}"
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
                                    $statusPenjemputan = $trx->status_penjemputan ?? 'menunggu';
                                    $sudahDijemput     = in_array($statusPenjemputan, ['sampai_lokasi', 'selesai']);
                                    $perluLengkapi     = $sudahDijemput && !$trx->jenis_zakat_id;
                                    $nextStatus = [
                                        'menunggu'         => ['diterima',         'Terima'],
                                        'diterima'         => ['dalam_perjalanan', 'Berangkat'],
                                        'dalam_perjalanan' => ['sampai_lokasi',    'Sampai'],
                                        'sampai_lokasi'    => ['selesai',          'Selesai'],
                                    ][$statusPenjemputan] ?? null;
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $perluLengkapi ? 'bg-orange-50/30' : '' }}"
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
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                {!! $trx->status_penjemputan_badge !!}
                                                @if ($perluLengkapi)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                        Perlu Dilengkapi
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $trx->uuid }}"
                                            data-nama="{{ $trx->muzakki_nama }}"
                                            data-perlu-lengkapi="{{ $perluLengkapi ? '1' : '0' }}"
                                            data-can-delete="{{ in_array($trx->status, ['pending', 'rejected']) ? '1' : '0' }}"
                                            data-next-status="{{ $nextStatus ? $nextStatus[0] : '' }}"
                                            data-next-label="{{ $nextStatus ? $nextStatus[1] : '' }}"
                                            data-lat="{{ $trx->latitude }}"
                                            data-lng="{{ $trx->longitude }}">
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
                                                            @if ($trx->muzakki_telepon)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Telepon</p>
                                                                        <a href="https://wa.me/62{{ ltrim($trx->muzakki_telepon, '0') }}" target="_blank"
                                                                            class="text-sm font-medium text-green-600 hover:underline">
                                                                            {{ $trx->muzakki_telepon }}
                                                                        </a>
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
                                                                        @if ($trx->latitude && $trx->longitude)
                                                                            <a href="https://maps.google.com/?q={{ $trx->latitude }},{{ $trx->longitude }}" target="_blank"
                                                                                class="text-xs text-blue-600 hover:underline mt-0.5 inline-block">Buka di Maps</a>
                                                                        @endif
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
                                                                    @if ($trx->waktu_request)
                                                                        <p class="text-xs text-gray-400 mt-0.5">Request: {{ \Carbon\Carbon::parse($trx->waktu_request)->format('H:i') }}</p>
                                                                    @endif
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
                                                            @else
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                        <p class="text-xs text-gray-400 italic">{{ $perluLengkapi ? 'Perlu dilengkapi' : 'Diisi saat penjemputan' }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jumlah</p>
                                                                        <p class="text-sm font-semibold text-green-600">{{ $trx->jumlah_formatted }}</p>
                                                                    </div>
                                                                </div>
                                                            @elseif (isset($trx->jumlah_beras_kg) && $trx->jumlah_beras_kg > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jumlah Beras</p>
                                                                        <p class="text-sm font-semibold text-amber-700">{{ $trx->jumlah_beras_kg }} kg</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Status Penjemputan --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Status & Amil</h4>
                                                        <div class="space-y-3">
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status Penjemputan</p>
                                                                {!! $trx->status_penjemputan_badge !!}
                                                            </div>
                                                            @if ($trx->amil)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Amil</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $trx->amil->nama_lengkap }}</p>
                                                                        @if ($trx->amil->kode_amil)
                                                                            <p class="text-xs text-gray-400 font-mono">{{ $trx->amil->kode_amil }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            {{-- Tracking waktu --}}
                                                            @if ($trx->waktu_selesai || $trx->waktu_sampai || $trx->waktu_berangkat || $trx->waktu_diterima_amil)
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Tracking Waktu</p>
                                                                    <div class="space-y-0.5">
                                                                        @if ($trx->waktu_diterima_amil)
                                                                            <p class="text-xs text-gray-400">Diterima: {{ \Carbon\Carbon::parse($trx->waktu_diterima_amil)->format('H:i') }}</p>
                                                                        @endif
                                                                        @if ($trx->waktu_berangkat)
                                                                            <p class="text-xs text-gray-400">Berangkat: {{ \Carbon\Carbon::parse($trx->waktu_berangkat)->format('H:i') }}</p>
                                                                        @endif
                                                                        @if ($trx->waktu_sampai)
                                                                            <p class="text-xs text-gray-400">Sampai: {{ \Carbon\Carbon::parse($trx->waktu_sampai)->format('H:i') }}</p>
                                                                        @endif
                                                                        @if ($trx->waktu_selesai)
                                                                            <p class="text-xs text-gray-400">Selesai: {{ \Carbon\Carbon::parse($trx->waktu_selesai)->format('H:i') }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if ($trx->keterangan)
                                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                                <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                                                                <p class="text-sm text-gray-600">{{ $trx->keterangan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Tombol Aksi di Expandable --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                    <div class="text-xs text-gray-500">
                                                        No. Transaksi: <span class="font-medium text-gray-700">{{ $trx->no_transaksi }}</span>
                                                    </div>
                                                    <div class="flex gap-2 flex-wrap">
                                                        @if ($nextStatus && (auth()->user()->isAmil() || auth()->user()->isAdminMasjid()))
                                                            <button type="button"
                                                                onclick="updateStatusPenjemputan('{{ $trx->uuid }}', '{{ $nextStatus[0] }}', this)"
                                                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                </svg>
                                                                {{ $nextStatus[1] }}
                                                            </button>
                                                        @endif
                                                        @if ($perluLengkapi)
                                                            <a href="{{ route('transaksi-penerimaan.edit', $trx->uuid) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-orange-100 hover:bg-orange-200 text-orange-700 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Lengkapi Zakat
                                                            </a>
                                                        @endif
                                                        @if ($trx->latitude && $trx->longitude)
                                                            <a href="https://maps.google.com/?q={{ $trx->latitude }},{{ $trx->longitude }}" target="_blank"
                                                                class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                Buka Maps
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('transaksi-penerimaan.show', $trx->uuid) }}"
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
                            $statusPenjemputan = $trx->status_penjemputan ?? 'menunggu';
                            $sudahDijemput     = in_array($statusPenjemputan, ['sampai_lokasi', 'selesai']);
                            $perluLengkapi     = $sudahDijemput && !$trx->jenis_zakat_id;
                            $nextStatus = [
                                'menunggu'         => ['diterima',         'Terima'],
                                'diterima'         => ['dalam_perjalanan', 'Berangkat'],
                                'dalam_perjalanan' => ['sampai_lokasi',    'Sampai'],
                                'sampai_lokasi'    => ['selesai',          'Selesai'],
                            ][$statusPenjemputan] ?? null;
                        @endphp
                        <div class="expandable-card {{ $perluLengkapi ? 'bg-orange-50/30' : '' }}">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $trx->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $trx->muzakki_nama }}</h3>
                                            {!! $trx->status_badge !!}
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                            @if ($trx->jumlah > 0)
                                                <span class="text-xs text-gray-500 mx-2">·</span>
                                                <span class="text-xs font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                            {!! $trx->status_penjemputan_badge !!}
                                            @if ($perluLengkapi)
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800 border border-orange-200">Perlu Dilengkapi</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $trx->uuid }}"
                                            data-nama="{{ $trx->muzakki_nama }}"
                                            data-perlu-lengkapi="{{ $perluLengkapi ? '1' : '0' }}"
                                            data-can-delete="{{ in_array($trx->status, ['pending', 'rejected']) ? '1' : '0' }}"
                                            data-next-status="{{ $nextStatus ? $nextStatus[0] : '' }}"
                                            data-next-label="{{ $nextStatus ? $nextStatus[1] : '' }}"
                                            data-lat="{{ $trx->latitude }}"
                                            data-lng="{{ $trx->longitude }}">
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
                                        @if ($trx->muzakki_telepon || $trx->muzakki_alamat)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Kontak & Lokasi</h4>
                                                <div class="space-y-2">
                                                    @if ($trx->muzakki_telepon)
                                                        <div class="flex items-center text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <a href="https://wa.me/62{{ ltrim($trx->muzakki_telepon, '0') }}" target="_blank"
                                                                class="text-green-600 hover:underline">{{ $trx->muzakki_telepon }}</a>
                                                        </div>
                                                    @endif
                                                    @if ($trx->muzakki_alamat)
                                                        <div class="flex items-start text-sm">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            <span class="text-gray-900">{{ $trx->muzakki_alamat }}</span>
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
                                                        <span class="font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span>
                                                    </div>
                                                @endif
                                                @if ($trx->amil)
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        <span class="text-gray-700">Amil: <span class="font-medium">{{ $trx->amil->nama_lengkap }}</span></span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200">
                                            <div class="flex gap-2 flex-wrap">
                                                @if ($nextStatus && (auth()->user()->isAmil() || auth()->user()->isAdminMasjid()))
                                                    <button type="button"
                                                        onclick="updateStatusPenjemputan('{{ $trx->uuid }}', '{{ $nextStatus[0] }}', this)"
                                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                        {{ $nextStatus[1] }}
                                                    </button>
                                                @endif
                                                @if ($perluLengkapi)
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'status_penjemputan', 'amil_id', 'start_date', 'end_date']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                        <a href="{{ route('transaksi-dijemput.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Dijemput</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan permintaan penjemputan zakat</p>
                        <a href="{{ route('transaksi-dijemput.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Penjemputan
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

                <button type="button" id="dd-next-status"
                    class="flex items-center w-full px-4 py-2.5 text-sm text-blue-700 hover:bg-blue-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span id="dd-next-label">Update Status</span>
                </button>

                <a href="#" id="dd-lengkapi"
                    class="flex items-center px-4 py-2.5 text-sm text-orange-600 hover:bg-orange-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Lengkapi Zakat
                </a>

                <a href="#" id="dd-maps"
                    class="flex items-center px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition-colors hidden" target="_blank">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Buka di Maps
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

    {{-- ── Toast Notifikasi AJAX ── --}}
    <div id="toast-status" class="fixed bottom-5 right-5 z-50 hidden transition-all">
        <div class="bg-gray-900 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-2">
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toast-msg">Status berhasil diupdate</span>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const dropdown         = document.getElementById('dropdown-container');
            const ddDetail         = document.getElementById('dd-detail');
            const ddNextStatus     = document.getElementById('dd-next-status');
            const ddNextLabel      = document.getElementById('dd-next-label');
            const ddLengkapi       = document.getElementById('dd-lengkapi');
            const ddMaps           = document.getElementById('dd-maps');
            const ddDelete         = document.getElementById('dd-delete');
            const ddDividerDelete  = document.getElementById('dd-divider-delete');

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
                const ddH    = dropdown.offsetHeight || 200;
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

                    const uuid         = toggle.dataset.uuid;
                    const nama         = toggle.dataset.nama;
                    const perluLengkapi = toggle.dataset.perluLengkapi === '1';
                    const canDelete    = toggle.dataset.canDelete     === '1';
                    const nextStatus   = toggle.dataset.nextStatus    || '';
                    const nextLabel    = toggle.dataset.nextLabel     || '';
                    const lat          = toggle.dataset.lat;
                    const lng          = toggle.dataset.lng;

                    if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                        closeDropdown(); return;
                    }

                    dropdown.dataset.uuid = uuid;
                    ddDetail.href = `/transaksi-dijemput/${uuid}`;

                    if (nextStatus) {
                        show(ddNextStatus);
                        ddNextLabel.textContent = nextStatus === 'selesai'
                            ? 'Tandai Selesai'
                            : 'Update: ' + nextLabel;
                        ddNextStatus.onclick = () => {
                            closeDropdown();
                            updateStatusPenjemputanFromDropdown(uuid, nextStatus);
                        };
                    } else {
                        hide(ddNextStatus);
                    }

                    perluLengkapi
                        ? (ddLengkapi.href = `/transaksi-penerimaan/${uuid}/edit`, show(ddLengkapi))
                        : hide(ddLengkapi);

                    if (lat && lng) {
                        ddMaps.href = `https://maps.google.com/?q=${lat},${lng}`;
                        show(ddMaps);
                    } else {
                        hide(ddMaps);
                    }

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

            function openDeleteModal(uuid, nama) {
                document.getElementById('modal-delete-nama').textContent = nama;
                document.getElementById('delete-form').action = `/transaksi-penerimaan/${uuid}`;
                openModal('delete-modal');
            }

            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            document.getElementById('delete-modal')?.addEventListener('click', function (e) {
                if (e.target === this) closeModal('delete-modal');
            });

            window.openDeleteModal = openDeleteModal;

            // Update status dari dropdown (wrapper untuk updateStatusPenjemputan)
            window.updateStatusPenjemputanFromDropdown = function (uuid, status) {
                updateStatusPenjemputan(uuid, status, { disabled: false, textContent: '' });
            };
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

        // ── UPDATE STATUS PENJEMPUTAN via AJAX ──
        function updateStatusPenjemputan(uuid, status, btn) {
            const label = status.replace(/_/g, ' ');
            if (!confirm('Update status penjemputan ke "' + label + '"?')) return;

            const orig = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            fetch(`/transaksi-penerimaan/${uuid}/update-status-penjemputan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Status diupdate: ' + label);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(data.error || 'Gagal update status.');
                    btn.disabled = false;
                    btn.textContent = orig;
                }
            })
            .catch(() => {
                alert('Gagal menghubungi server.');
                btn.disabled = false;
                btn.textContent = orig;
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('toast-status');
            document.getElementById('toast-msg').textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2500);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal('delete-modal');
        });
    </script>
@endpush