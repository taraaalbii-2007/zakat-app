{{--
    resources/views/amil/transaksi-datang-langsung/index.blade.php

    DIPAKAI OLEH  : Amil / Admin Masjid
    CONTROLLER    : index() dengan filter byMetodePenerimaan('datang_langsung')
    ROUTE         : GET /transaksi-datang-langsung

    FITUR KHUSUS  :
      - Hanya menampilkan transaksi dengan metode = datang_langsung
      - Statistik khusus datang langsung
      - Ada tombol Create baru (khusus datang langsung)
      - Tanpa kolom status penjemputan dan konfirmasi daring
      - Tampilan expandable seperti transaksi penyaluran
--}}

@extends('layouts.app')

@section('title', 'Transaksi Datang Langsung')

@section('content')
<div class="space-y-4 sm:space-y-6">


    {{-- ── Main Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

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
                        <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Tambah</span>
                    </a>

                    {{-- Filter --}}
                    <button type="button" onclick="toggleFilter()"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                        {{ request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'status', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
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
                        <form method="GET" action="{{ route('transaksi-datang-langsung.index') }}" id="search-form"
                            class="{{ request('q') ? '' : 'hidden' }}">
                            @foreach (['jenis_zakat_id', 'metode_pembayaran', 'status', 'start_date', 'end_date'] as $filter)
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
                                @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'status', 'start_date', 'end_date']))
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
            class="{{ request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'status', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('transaksi-datang-langsung.index') }}" id="filter-form">
                @if (request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                        <select name="jenis_zakat_id"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
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
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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

                @if (request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'status', 'start_date', 'end_date']))
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

            {{-- ── Desktop View dengan Expandable Table (mirip transaksi penyaluran) ── --}}
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
                                $isPending = $trx->status === 'pending';
                            @endphp

                            {{-- Parent Row --}}
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                {{ $isPending ? 'bg-yellow-50/30' : '' }}"
                                data-target="detail-{{ $trx->uuid }}">
                                <td class="px-4 py-4">
                                    <button type="button"
                                        class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $trx->muzakki_nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                            @if($trx->waktu_transaksi)
                                                &middot; {{ $trx->waktu_transaksi->format('H:i') }}
                                            @endif
                                            @if ($trx->jumlah > 0)
                                                &middot; <span class="font-semibold text-gray-700">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                            @elseif($trx->jumlah_beras_kg > 0)
                                                &middot; <span class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            {!! $trx->status_badge ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">'.ucfirst($trx->status).'</span>' !!}
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($trx->metode_pembayaran == 'tunai') bg-green-100 text-green-800
                                                @elseif($trx->metode_pembayaran == 'transfer') bg-blue-100 text-blue-800
                                                @elseif($trx->metode_pembayaran == 'qris') bg-purple-100 text-purple-800
                                                @endif">
                                                {{ ucfirst($trx->metode_pembayaran) }}
                                            </span>
                                            @if($isPending)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    Perlu Verifikasi
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
                                        data-nama="{{ $trx->muzakki_nama ?? '-' }}"
                                        data-can-verify="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-reject="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-edit="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-delete="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-print="1">
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
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $trx->muzakki_nama ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                        @if($trx->muzakki_telepon)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Telepon</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $trx->muzakki_telepon }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($trx->muzakki_alamat)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Alamat</p>
                                                                <p class="text-sm text-gray-700">
                                                                    {{ $trx->muzakki_alamat }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($trx->muzakki_email)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Email</p>
                                                                <p class="text-sm text-gray-900">{{ $trx->muzakki_email }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Transaksi --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Transaksi</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">No. Transaksi</p>
                                                                <p class="text-sm font-mono text-gray-900">{{ $trx->no_transaksi }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Tanggal</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                                    @if($trx->waktu_transaksi)
                                                                        <span class="text-gray-500"> ({{ $trx->waktu_transaksi->format('H:i') }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @if ($trx->jenisZakat)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                                                        @if ($trx->programZakat)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Program Zakat</p>
                                                                <p class="text-sm text-gray-900">{{ $trx->programZakat->nama }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if ($trx->jumlah > 0)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jumlah (Uang)</p>
                                                                <p class="text-sm font-semibold text-green-600">
                                                                    Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                                </p>
                                                                @if($trx->jumlah_infaq > 0)
                                                                <p class="text-xs text-gray-500">+ Infaq Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if ($trx->jumlah_beras_kg > 0)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-amber-500 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jumlah (Beras)</p>
                                                                <p class="text-sm font-semibold text-amber-600">
                                                                    {{ $trx->jumlah_beras_kg }} kg
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($trx->keterangan)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
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
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Metode & Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Metode Penerimaan</p>
                                                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                                Datang Langsung
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                                                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                                                @if($trx->metode_pembayaran == 'tunai') bg-green-100 text-green-800
                                                                @elseif($trx->metode_pembayaran == 'transfer') bg-blue-100 text-blue-800
                                                                @elseif($trx->metode_pembayaran == 'qris') bg-purple-100 text-purple-800
                                                                @endif">
                                                                {{ ucfirst($trx->metode_pembayaran) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Status</p>
                                                            {!! $trx->status_badge ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">'.ucfirst($trx->status).'</span>' !!}
                                                        </div>
                                                        @if($trx->status == 'rejected' && $trx->alasan_penolakan)
                                                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                            <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                            <p class="text-xs text-red-700">{{ $trx->alasan_penolakan }}</p>
                                                        </div>
                                                        @endif
                                                        @if($trx->verified_at)
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Diverifikasi Pada</p>
                                                                <p class="text-sm text-gray-900">
                                                                    {{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}
                                                                </p>
                                                                @if($trx->verified_by)
                                                                <p class="text-xs text-gray-500">Oleh: {{ $trx->verified_by }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tombol Aksi di Expandable --}}
                                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                <div class="flex gap-2 flex-wrap">
                                                    <a href="{{ route('transaksi-datang-langsung.show', $trx->uuid) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Detail
                                                    </a>

                                                    <a href="{{ route('transaksi-datang-langsung.print', $trx->uuid) }}" target="_blank"
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                        Kwitansi
                                                    </a>

                                                    @if($trx->status === 'pending')
                                                        <button type="button"
                                                            onclick="openVerifyModal('{{ $trx->uuid }}', '{{ addslashes($trx->muzakki_nama ?? '-') }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Verifikasi
                                                        </button>

                                                        <button type="button"
                                                            onclick="openRejectModal('{{ $trx->uuid }}', '{{ addslashes($trx->muzakki_nama ?? '-') }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Tolak
                                                        </button>

                                                        <a href="{{ route('transaksi-datang-langsung.edit', $trx->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Edit
                                                        </a>

                                                        <button type="button"
                                                            onclick="openDeleteModal('{{ $trx->uuid }}', '{{ addslashes($trx->muzakki_nama ?? '-') }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Hapus
                                                        </button>
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

            {{-- ── Mobile View dengan Expandable Cards ── --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach ($transaksis as $trx)
                    @php
                        $isPending = $trx->status === 'pending';
                    @endphp
                    <div class="expandable-card {{ $isPending ? 'bg-yellow-50/30' : '' }}">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                            {{ $trx->muzakki_nama ?? '-' }}</h3>
                                        {!! $trx->status_badge ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">'.ucfirst($trx->status).'</span>' !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if($trx->jumlah > 0)
                                            <span class="text-xs font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                        @elseif($trx->jumlah_beras_kg > 0)
                                            <span class="text-xs font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                        @endif
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                            @if($trx->metode_pembayaran == 'tunai') bg-green-100 text-green-800
                                            @elseif($trx->metode_pembayaran == 'transfer') bg-blue-100 text-blue-800
                                            @elseif($trx->metode_pembayaran == 'qris') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst($trx->metode_pembayaran) }}
                                        </span>
                                    </div>
                                    @if($isPending)
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                Perlu Verifikasi
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 ml-2">
                                    <button type="button"
                                        class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-uuid="{{ $trx->uuid }}"
                                        data-nama="{{ $trx->muzakki_nama ?? '-' }}"
                                        data-can-verify="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-reject="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-edit="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-delete="{{ $trx->status === 'pending' ? '1' : '0' }}"
                                        data-can-print="1">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Expandable Content --}}
                        <div id="detail-mobile-{{ $trx->uuid }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Transaksi</h4>
                                        <div class="space-y-2">
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">No. Transaksi</p>
                                                    <p class="text-sm font-mono text-gray-900">{{ $trx->no_transaksi }}</p>
                                                </div>
                                            </div>
                                            @if($trx->muzakki_telepon)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Telepon</p>
                                                    <p class="text-sm text-gray-900">{{ $trx->muzakki_telepon }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if($trx->muzakki_alamat)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Alamat</p>
                                                    <p class="text-sm text-gray-700">{{ $trx->muzakki_alamat }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if ($trx->jenisZakat)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                                            @if ($trx->jumlah > 0)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Jumlah (Uang)</p>
                                                    <p class="text-sm font-semibold text-green-600">
                                                        Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                    </p>
                                                    @if($trx->jumlah_infaq > 0)
                                                        <p class="text-xs text-gray-500">+ Infaq Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                            @if ($trx->jumlah_beras_kg > 0)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-amber-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Jumlah (Beras)</p>
                                                    <p class="text-sm font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if($trx->status == 'rejected' && $trx->alasan_penolakan)
                                            <div class="p-2 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                <p class="text-xs text-red-700">{{ $trx->alasan_penolakan }}</p>
                                            </div>
                                            @endif
                                            @if($trx->verified_at)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Diverifikasi Pada</p>
                                                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="pt-3 border-t border-gray-200 flex gap-2 flex-wrap">
                                        <a href="{{ route('transaksi-datang-langsung.show', $trx->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('transaksi-datang-langsung.print', $trx->uuid) }}" target="_blank"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Kwitansi
                                        </a>
                                        @if($trx->status === 'pending')
                                            <button type="button"
                                                onclick="openVerifyModal('{{ $trx->uuid }}', '{{ addslashes($trx->muzakki_nama ?? '-') }}')"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Verifikasi
                                            </button>
                                            <a href="{{ route('transaksi-datang-langsung.edit', $trx->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
                    {{ $transaksis->withQueryString()->links() }}
                </div>
            @endif

        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-50 mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                @if (request()->hasAny(['q', 'jenis_zakat_id', 'metode_pembayaran', 'status', 'start_date', 'end_date']))
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                    <a href="{{ route('transaksi-datang-langsung.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Pencarian
                    </a>
                @else
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Datang Langsung</h3>
                    <p class="text-sm text-gray-500 mb-6">Mulai tambahkan transaksi zakat datang langsung</p>
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

{{-- ── Dropdown Container ── --}}
<div id="dropdown-container" class="fixed hidden z-[9999]" style="min-width:200px;">
    <div class="w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="py-1">
            <a href="#" id="dd-detail"
                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Detail
            </a>

            <a href="#" id="dd-print" target="_blank"
                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3 text-green-600 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Kwitansi
            </a>

            <div class="border-t border-gray-100 my-1" id="dd-divider-verify" style="display:none;"></div>
            <button type="button" id="dd-verify"
                class="flex items-center w-full px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition-colors hidden">
                <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Verifikasi
            </button>
            <button type="button" id="dd-reject"
                class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tolak
            </button>

            <div class="border-t border-gray-100 my-1" id="dd-divider-edit" style="display:none;"></div>
            <a href="#" id="dd-edit"
                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors hidden">
                <svg class="w-4 h-4 mr-3 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>

            <div class="border-t border-gray-100 my-1" id="dd-divider-delete" style="display:none;"></div>
            <button type="button" id="dd-delete"
                class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
        </div>
    </div>
</div>

{{-- ── Modal: Verifikasi ── --}}
<div id="verify-modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Verifikasi Transaksi</h3>
        <p class="text-sm text-gray-500 mb-4 text-center">
            Verifikasi transaksi dari
            "<span id="modal-verify-nama" class="font-semibold text-gray-700"></span>"?
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
        <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Tolak Transaksi</h3>
        <p class="text-sm text-gray-500 mb-4 text-center">
            Tolak transaksi dari
            "<span id="modal-reject-nama" class="font-semibold text-gray-700"></span>"?
        </p>
        <form method="POST" id="reject-form">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan..."
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
    document.addEventListener('DOMContentLoaded', function() {

        // ── Referensi elemen ──────────────────────────────────────────
        var dropdown = document.getElementById('dropdown-container');
        var ddDetail = document.getElementById('dd-detail');
        var ddPrint = document.getElementById('dd-print');
        var ddVerify = document.getElementById('dd-verify');
        var ddReject = document.getElementById('dd-reject');
        var ddEdit = document.getElementById('dd-edit');
        var ddDelete = document.getElementById('dd-delete');
        var ddDividerVerify = document.getElementById('dd-divider-verify');
        var ddDividerEdit = document.getElementById('dd-divider-edit');
        var ddDividerDelete = document.getElementById('dd-divider-delete');

        // ── Desktop expandable rows ───────────────────────────────────
        document.querySelectorAll('.expandable-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a, .dropdown-toggle, button')) return;
                var target = document.getElementById(this.dataset.target);
                var icon = this.querySelector('.expand-icon');
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-90');
            });
        });

        // ── Mobile expandable cards ───────────────────────────────────
        document.querySelectorAll('.expandable-row-mobile').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a, .dropdown-toggle, button')) return;
                var target = document.getElementById(this.dataset.target);
                var icon = this.querySelector('.expand-icon-mobile');
                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        function show(el) {
            el.classList.remove('hidden');
        }

        function hide(el) {
            el.classList.add('hidden');
        }

        // ── Tutup dropdown ────────────────────────────────────────────
        function closeDropdown() {
            dropdown.classList.add('hidden');
            dropdown.removeAttribute('data-uuid');
        }

        // ── Posisikan dropdown ────────────────────────────────────────
        function positionDropdown(toggle) {
            var rect = toggle.getBoundingClientRect();
            var ddW = 224;
            var ddH = dropdown.offsetHeight || 220;
            var margin = 6;
            var vpW = window.innerWidth;
            var vpH = window.innerHeight;

            var left = rect.right - ddW;
            if (left < margin) left = margin;
            if (left + ddW > vpW - margin) left = vpW - ddW - margin;

            var top = rect.bottom + margin;
            if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
            if (top < margin) top = margin;

            dropdown.style.top = top + 'px';
            dropdown.style.left = left + 'px';
        }

        // ── Event klik global ─────────────────────────────────────────
        document.addEventListener('click', function(e) {
            var toggle = e.target.closest('.dropdown-toggle');

            if (toggle) {
                e.stopPropagation();

                var uuid = toggle.dataset.uuid;
                var nama = toggle.dataset.nama;
                var canVerify = toggle.dataset.canVerify === '1';
                var canReject = toggle.dataset.canReject === '1';
                var canEdit = toggle.dataset.canEdit === '1';
                var canDelete = toggle.dataset.canDelete === '1';
                var canPrint = toggle.dataset.canPrint === '1';

                if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                    closeDropdown();
                    return;
                }

                dropdown.dataset.uuid = uuid;
                ddDetail.href = '/transaksi-datang-langsung/' + uuid;
                ddPrint.href = '/transaksi-datang-langsung/' + uuid + '/print';

                // Verify / Reject
                if (canVerify || canReject) {
                    ddDividerVerify.style.display = '';
                    
                    if (canVerify) {
                        show(ddVerify);
                        ddVerify.onclick = function() {
                            closeDropdown();
                            openVerifyModal(uuid, nama);
                        };
                    } else {
                        hide(ddVerify);
                    }
                    
                    if (canReject) {
                        show(ddReject);
                        ddReject.onclick = function() {
                            closeDropdown();
                            openRejectModal(uuid, nama);
                        };
                    } else {
                        hide(ddReject);
                    }
                } else {
                    ddDividerVerify.style.display = 'none';
                    hide(ddVerify);
                    hide(ddReject);
                }

                // Edit
                if (canEdit) {
                    ddDividerEdit.style.display = '';
                    ddEdit.href = '/transaksi-datang-langsung/' + uuid + '/edit';
                    show(ddEdit);
                } else {
                    ddDividerEdit.style.display = 'none';
                    hide(ddEdit);
                }

                // Delete
                if (canDelete) {
                    ddDividerDelete.style.display = '';
                    show(ddDelete);
                    ddDelete.onclick = function() {
                        closeDropdown();
                        openDeleteModal(uuid, nama);
                    };
                } else {
                    ddDividerDelete.style.display = 'none';
                    hide(ddDelete);
                }

                dropdown.classList.remove('hidden');
                positionDropdown(toggle);

            } else if (!dropdown.contains(e.target)) {
                closeDropdown();
            }
        });

        window.addEventListener('scroll', closeDropdown, true);
        window.addEventListener('resize', closeDropdown);

        // ── Modal: Verifikasi ─────────────────────────────────────────
        window.openVerifyModal = function(uuid, nama) {
            document.getElementById('modal-verify-nama').textContent = nama;
            document.getElementById('verify-form').action = '/transaksi-datang-langsung/' + uuid + '/verify';
            openModal('verify-modal');
        }

        // ── Modal: Tolak ─────────────────────────────────────────────
        window.openRejectModal = function(uuid, nama) {
            document.getElementById('modal-reject-nama').textContent = nama;
            document.getElementById('reject-form').action = '/transaksi-datang-langsung/' + uuid + '/reject';
            document.querySelector('#reject-form textarea').value = '';
            openModal('reject-modal');
        }

        // ── Modal: Delete ─────────────────────────────────────────────
        window.openDeleteModal = function(uuid, nama) {
            document.getElementById('modal-delete-nama').textContent = nama;
            document.getElementById('delete-form').action = '/transaksi-datang-langsung/' + uuid;
            openModal('delete-modal');
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.closeModal = function(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Backdrop click menutup modal
        ['verify-modal', 'reject-modal', 'delete-modal'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === this) closeModal(id);
                });
            }
        });
    });

    // ── Search & Filter ───────────────────────────────────────────────
    function toggleSearch() {
        var btn = document.getElementById('search-button');
        var form = document.getElementById('search-form');
        var input = document.getElementById('search-input');
        var container = document.getElementById('search-container');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            container.style.minWidth = '280px';
            setTimeout(function() {
                input.focus();
            }, 50);
        } else {
            form.classList.add('hidden');
            btn.classList.remove('hidden');
            container.style.minWidth = '';
        }
    }

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('verify-modal');
            closeModal('reject-modal');
            closeModal('delete-modal');
        }
    });
</script>
@endpush