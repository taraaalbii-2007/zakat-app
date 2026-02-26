{{-- resources/views/amil/pemantauan-transaksi/index.blade.php --}}
{{-- 
    HANYA MENAMPILKAN KESELURUHAN METODE (DATANG LANGSUNG, DIJEMPUT, DARING)
    TANPA BUTTON CREATE DAN TOMBOL AKSI
    UNTUK KEPERLUAN PEMANTAUAN
--}}

@extends('layouts.app')

@section('title', 'Pemantauan Transaksi Penerimaan')

@section('content')
    <div class="space-y-4 sm:space-y-6">


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

                        {{-- ── Tombol Export (dropdown) ── --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2">Export</span>
                                <svg class="w-4 h-4 ml-1" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    {{-- Export PDF — teruskan semua filter aktif --}}
                                    <a href="{{ route('pemantauan-transaksi.export.pdf', request()->query()) }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-red-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Export PDF
                                    </a>
                                    {{-- Export Excel — teruskan semua filter aktif --}}
                                    <a href="{{ route('pemantauan-transaksi.export.excel', request()->query()) }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-green-600 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
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
                        {{ request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'status', 'metode_penerimaan', 'konfirmasi_status', 'start_date', 'end_date', 'periode', 'tahun']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('pemantauan-transaksi.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['jenis_zakat_id', 'metode_pembayaran', 'status', 'metode_penerimaan', 'konfirmasi_status', 'start_date', 'end_date', 'periode', 'tahun'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari nama, no transaksi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny([
                                            'q',
                                            'jenis_zakat_id',
                                            'metode_pembayaran',
                                            'status',
                                            'metode_penerimaan',
                                            'konfirmasi_status',
                                            'start_date',
                                            'end_date',
                                            'periode',
                                        ]))
                                        <a href="{{ route('pemantauan-transaksi.index') }}"
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
                class="{{ request()->hasAny(['jenis_zakat_id', 'metode_pembayaran', 'status', 'metode_penerimaan', 'konfirmasi_status', 'start_date', 'end_date', 'periode']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('pemantauan-transaksi.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Penerimaan</label>
                            <select name="metode_penerimaan"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Metode</option>
                                <option value="datang_langsung"
                                    {{ request('metode_penerimaan') == 'datang_langsung' ? 'selected' : '' }}>Datang
                                    Langsung</option>
                                <option value="dijemput"
                                    {{ request('metode_penerimaan') == 'dijemput' ? 'selected' : '' }}>Dijemput</option>
                                <option value="daring" {{ request('metode_penerimaan') == 'daring' ? 'selected' : '' }}>
                                    Daring</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
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
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Konfirmasi</label>
                            <select name="konfirmasi_status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="menunggu_konfirmasi"
                                    {{ request('konfirmasi_status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu
                                    Konfirmasi</option>
                                <option value="dikonfirmasi"
                                    {{ request('konfirmasi_status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi
                                </option>
                                <option value="ditolak" {{ request('konfirmasi_status') == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="transfer"
                                    {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Periode</label>
                            <input type="month" name="periode" value="{{ request('periode') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach (range(date('Y'), 2020) as $year)
                                    <option value="{{ $year }}"
                                        {{ request('tahun') == $year ? 'selected' : '' }}>
                                        {{ $year }}
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

                    @if (request()->hasAny([
                            'jenis_zakat_id',
                            'metode_pembayaran',
                            'status',
                            'metode_penerimaan',
                            'konfirmasi_status',
                            'start_date',
                            'end_date',
                            'periode',
                        ]))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('pemantauan-transaksi.index', request('q') ? ['q' => request('q')] : []) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
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
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
                                    $menungguKonfirmasi =
                                        $trx->metode_penerimaan === 'daring' &&
                                        $trx->konfirmasi_status === 'menunggu_konfirmasi';
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                {{ $menungguKonfirmasi ? 'bg-blue-50/30' : '' }}"
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
                                                @if ($trx->waktu_transaksi)
                                                    &middot; {{ $trx->waktu_transaksi->format('H:i') }}
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    &middot; <span class="font-semibold text-gray-700">Rp
                                                        {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                                @elseif($trx->jumlah_beras_kg > 0)
                                                    &middot; <span
                                                        class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }}
                                                        kg</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_badge ??
                                                    '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' .
                                                        ucfirst($trx->status) .
                                                        '</span>' !!}
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full
                                                @if ($trx->metode_penerimaan == 'datang_langsung') bg-blue-100 text-blue-800
                                                @elseif($trx->metode_penerimaan == 'dijemput') bg-amber-100 text-amber-800
                                                @elseif($trx->metode_penerimaan == 'daring') bg-purple-100 text-purple-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $trx->metode_penerimaan)) }}
                                                </span>
                                                @if ($menungguKonfirmasi)
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                        Perlu Konfirmasi
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('pemantauan-transaksi.show', $trx->uuid) }}"
                                            class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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

                                                    {{-- Kolom 1: Data Muzakki --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Data Muzakki
                                                        </h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
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
                                                            @if ($trx->muzakki_telepon)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Telepon</p>
                                                                        <p class="text-sm font-medium text-gray-900">
                                                                            {{ $trx->muzakki_telepon }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->muzakki_alamat)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Alamat</p>
                                                                        <p class="text-sm text-gray-700">
                                                                            {{ $trx->muzakki_alamat }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Detail Transaksi --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Transaksi
                                                        </h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">No. Transaksi</p>
                                                                    <p class="text-sm font-mono text-gray-900">
                                                                        {{ $trx->no_transaksi }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Tanggal</p>
                                                                    <p class="text-sm font-medium text-gray-900">
                                                                        {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                                        @if ($trx->waktu_transaksi)
                                                                            <span class="text-gray-500">
                                                                                ({{ $trx->waktu_transaksi->format('H:i') }})</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            @if ($trx->jenisZakat)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                        <p class="text-sm font-medium text-gray-900">
                                                                            {{ $trx->jenisZakat->nama }}
                                                                            @if ($trx->tipeZakat)
                                                                                <span
                                                                                    class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Jumlah (Uang)</p>
                                                                        <p class="text-sm font-semibold text-green-600">
                                                                            Rp
                                                                            {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                                        </p>
                                                                        @if ($trx->jumlah_infaq > 0)
                                                                            <p class="text-xs text-gray-500">+ Infaq Rp
                                                                                {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($trx->jumlah_beras_kg > 0)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 mr-2 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
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
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Metode & Status --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Metode & Status
                                                        </h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Metode Penerimaan</p>
                                                                    <span
                                                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                                                    @if ($trx->metode_penerimaan == 'datang_langsung') bg-blue-100 text-blue-800
                                                                    @elseif($trx->metode_penerimaan == 'dijemput') bg-amber-100 text-amber-800
                                                                    @elseif($trx->metode_penerimaan == 'daring') bg-purple-100 text-purple-800 @endif">
                                                                        {{ ucfirst(str_replace('_', ' ', $trx->metode_penerimaan)) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if ($trx->metode_pembayaran)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Metode Pembayaran
                                                                        </p>
                                                                        <span
                                                                            class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                                                    @if ($trx->metode_pembayaran == 'tunai') bg-gray-100 text-gray-800
                                                                    @elseif($trx->metode_pembayaran == 'transfer') bg-indigo-100 text-indigo-800
                                                                    @elseif($trx->metode_pembayaran == 'qris') bg-emerald-100 text-emerald-800 @endif">
                                                                            {{ ucfirst($trx->metode_pembayaran) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="text-xs text-gray-500 mb-1">Status</p>
                                                                {!! $trx->status_badge ??
                                                                    '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' .
                                                                        ucfirst($trx->status) .
                                                                        '</span>' !!}
                                                            </div>
                                                            @if ($trx->metode_penerimaan == 'daring' && $trx->konfirmasi_status)
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Status Konfirmasi
                                                                    </p>
                                                                    <span
                                                                        class="px-2 py-1 text-xs font-medium rounded-full
                                                                @if ($trx->konfirmasi_status == 'menunggu_konfirmasi') bg-yellow-100 text-yellow-800
                                                                @elseif($trx->konfirmasi_status == 'dikonfirmasi') bg-green-100 text-green-800
                                                                @elseif($trx->konfirmasi_status == 'ditolak') bg-red-100 text-red-800 @endif">
                                                                        {{ ucfirst(str_replace('_', ' ', $trx->konfirmasi_status)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            @if ($trx->metode_penerimaan == 'dijemput' && $trx->status_penjemputan)
                                                                <div>
                                                                    <p class="text-xs text-gray-500 mb-1">Status
                                                                        Penjemputan</p>
                                                                    <span
                                                                        class="px-2 py-1 text-xs font-medium rounded-full
                                                                @if ($trx->status_penjemputan == 'menunggu') bg-gray-100 text-gray-800
                                                                @elseif($trx->status_penjemputan == 'diterima') bg-blue-100 text-blue-800
                                                                @elseif($trx->status_penjemputan == 'dalam_perjalanan') bg-yellow-100 text-yellow-800
                                                                @elseif($trx->status_penjemputan == 'sampai_lokasi') bg-purple-100 text-purple-800
                                                                @elseif($trx->status_penjemputan == 'selesai') bg-green-100 text-green-800 @endif">
                                                                        {{ ucfirst(str_replace('_', ' ', $trx->status_penjemputan)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            @if ($trx->status == 'rejected' && $trx->alasan_penolakan)
                                                                <div
                                                                    class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                                    <p class="text-xs text-red-600 font-medium">Alasan
                                                                        Ditolak:</p>
                                                                    <p class="text-xs text-red-700">
                                                                        {{ $trx->alasan_penolakan }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
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
                    $menungguKonfirmasi =
                        $trx->metode_penerimaan === 'daring' && $trx->konfirmasi_status === 'menunggu_konfirmasi';
                @endphp
                <div class="expandable-card {{ $menungguKonfirmasi ? 'bg-blue-50/30' : '' }}">
                    <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                        data-target="detail-mobile-{{ $trx->uuid }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                        {{ $trx->muzakki_nama ?? '-' }}</h3>
                                    {!! $trx->status_badge ??
                                        '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' .
                                            ucfirst($trx->status) .
                                            '</span>' !!}
                                </div>
                                <div class="flex items-center mt-1 flex-wrap gap-2">
                                    <span
                                        class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                    @if ($trx->jumlah > 0)
                                        <span class="text-xs font-semibold text-green-600">Rp
                                            {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                    @elseif($trx->jumlah_beras_kg > 0)
                                        <span class="text-xs font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }}
                                            kg</span>
                                    @endif
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full
                                            @if ($trx->metode_penerimaan == 'datang_langsung') bg-blue-100 text-blue-800
                                            @elseif($trx->metode_penerimaan == 'dijemput') bg-amber-100 text-amber-800
                                            @elseif($trx->metode_penerimaan == 'daring') bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $trx->metode_penerimaan)) }}
                                    </span>
                                </div>
                                @if ($menungguKonfirmasi)
                                    <div class="mt-1">
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            Perlu Konfirmasi
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 ml-2">
                                <a href="{{ route('pemantauan-transaksi.show', $trx->uuid) }}"
                                    class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
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
                                            <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-gray-500">No. Transaksi</p>
                                                <p class="text-sm font-mono text-gray-900">{{ $trx->no_transaksi }}</p>
                                            </div>
                                        </div>
                                        @if ($trx->muzakki_telepon)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Telepon</p>
                                                    <p class="text-sm text-gray-900">{{ $trx->muzakki_telepon }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($trx->muzakki_alamat)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $trx->jenisZakat->nama }}
                                                        @if ($trx->tipeZakat)
                                                            <span
                                                                class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($trx->jumlah > 0)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Jumlah (Uang)</p>
                                                    <p class="text-sm font-semibold text-green-600">
                                                        Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                    </p>
                                                    @if ($trx->jumlah_infaq > 0)
                                                        <p class="text-xs text-gray-500">+ Infaq Rp
                                                            {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if ($trx->jumlah_beras_kg > 0)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-amber-500 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                        @if ($trx->metode_pembayaran)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-gray-500">Metode Pembayaran</p>
                                                    <span
                                                        class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                                                        @if ($trx->metode_pembayaran == 'tunai') bg-gray-100 text-gray-800
                                                        @elseif($trx->metode_pembayaran == 'transfer') bg-indigo-100 text-indigo-800
                                                        @elseif($trx->metode_pembayaran == 'qris') bg-emerald-100 text-emerald-800 @endif">
                                                        {{ ucfirst($trx->metode_pembayaran) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($trx->metode_penerimaan == 'daring' && $trx->konfirmasi_status)
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Status Konfirmasi</p>
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if ($trx->konfirmasi_status == 'menunggu_konfirmasi') bg-yellow-100 text-yellow-800
                                                    @elseif($trx->konfirmasi_status == 'dikonfirmasi') bg-green-100 text-green-800
                                                    @elseif($trx->konfirmasi_status == 'ditolak') bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $trx->konfirmasi_status)) }}
                                                </span>
                                            </div>
                                        @endif
                                        @if ($trx->metode_penerimaan == 'dijemput' && $trx->status_penjemputan)
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Status Penjemputan</p>
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if ($trx->status_penjemputan == 'menunggu') bg-gray-100 text-gray-800
                                                    @elseif($trx->status_penjemputan == 'diterima') bg-blue-100 text-blue-800
                                                    @elseif($trx->status_penjemputan == 'dalam_perjalanan') bg-yellow-100 text-yellow-800
                                                    @elseif($trx->status_penjemputan == 'sampai_lokasi') bg-purple-100 text-purple-800
                                                    @elseif($trx->status_penjemputan == 'selesai') bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $trx->status_penjemputan)) }}
                                                </span>
                                            </div>
                                        @endif
                                        @if ($trx->status == 'rejected' && $trx->alasan_penolakan)
                                            <div class="p-2 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                <p class="text-xs text-red-700">{{ $trx->alasan_penolakan }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-200 flex gap-2">
                                    <a href="{{ route('pemantauan-transaksi.show', $trx->uuid) }}"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            @if (request()->hasAny([
                    'q',
                    'jenis_zakat_id',
                    'metode_pembayaran',
                    'status',
                    'metode_penerimaan',
                    'konfirmasi_status',
                    'start_date',
                    'end_date',
                    'periode',
                ]))
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                <p class="text-sm text-gray-500 mb-6">Tidak ada transaksi yang sesuai dengan filter yang dipilih</p>
                <a href="{{ route('pemantauan-transaksi.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset Pencarian
                </a>
            @else
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi</h3>
                <p class="text-sm text-gray-500">Belum ada data transaksi penerimaan zakat</p>
            @endif
        </div>
        @endif
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Desktop expandable rows ───────────────────────────────────
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

            // ── Mobile expandable cards ───────────────────────────────────
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

        // Inisialisasi Alpine.js untuk dropdown jika belum ada
        document.addEventListener('alpine:init', function() {
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            }));
        });
    </script>
@endpush
