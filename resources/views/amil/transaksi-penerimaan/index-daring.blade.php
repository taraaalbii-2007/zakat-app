{{-- resources/views/amil/transaksi-daring/index.blade.php --}}
{{-- Menggunakan tampilan seperti index transaksi penyaluran untuk data transaksi daring --}}

@extends('layouts.app')

@section('title', 'Transaksi Zakat Daring')

@section('content')
    <div class="space-y-6">

        {{-- ── Alert: Ada transaksi menunggu konfirmasi ── --}}
        @if (isset($stats['menunggu_konfirmasi']) && $stats['menunggu_konfirmasi'] > 0)
            <div class="flex items-center gap-3 px-5 py-3 bg-amber-50 border border-amber-200 rounded-xl">
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

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-base font-semibold text-gray-800">Transaksi Zakat Daring</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200">
                                via Muzakki
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola konfirmasi pembayaran zakat dari muzakki</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-primary-500 hover:bg-primary-50 text-primary-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar (seperti index) -->
            <div class="px-5 py-3 bg-gradient-to-r from-primary-50/20 to-transparent border-b border-gray-100">
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
                            <span class="text-xs text-gray-500">Dikonfirmasi:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['total_verified']) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                            <span class="text-xs text-gray-500">Menunggu:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['menunggu_konfirmasi']) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-xs text-gray-500">Ditolak:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['total_ditolak'] ?? 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- Filter Panel (disesuaikan untuk daring) --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['q', 'jenis_zakat_id', 'konfirmasi_status', 'start_date', 'end_date', 'metode_pembayaran']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-primary-50/30">
                <form method="GET" action="{{ route('transaksi-daring.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Transaksi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari no. transaksi / muzakki..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status Konfirmasi</label>
                            <select name="konfirmasi_status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="menunggu_konfirmasi" {{ request('konfirmasi_status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="dikonfirmasi" {{ request('konfirmasi_status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="ditolak" {{ request('konfirmasi_status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                                <option value="">Semua Metode</option>
                                <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                                <option value="">Semua Jenis</option>
                                @foreach ($jenisZakatList as $jenis)
                                    <option value="{{ $jenis->id }}"
                                        {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'jenis_zakat_id', 'konfirmasi_status', 'metode_pembayaran', 'start_date', 'end_date']))
                            <a href="{{ route('transaksi-daring.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-primary-500 hover:bg-primary-50 text-primary-600 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            {{-- Active Filters Tags --}}
            @if (request()->hasAny(['q', 'jenis_zakat_id', 'konfirmasi_status', 'metode_pembayaran', 'start_date', 'end_date']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('konfirmasi_status'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Status: {{ ucfirst(str_replace('_', ' ', request('konfirmasi_status'))) }}
                                <button onclick="removeFilter('konfirmasi_status')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('metode_pembayaran'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Metode: {{ ucfirst(request('metode_pembayaran')) }}
                                <button onclick="removeFilter('metode_pembayaran')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('jenis_zakat_id') && isset($jenisZakatList))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Jenis Zakat: {{ $jenisZakatList->firstWhere('id', request('jenis_zakat_id'))?->nama ?? request('jenis_zakat_id') }}
                                <button onclick="removeFilter('jenis_zakat_id')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($transaksis->count() > 0)

                {{-- ── DESKTOP VIEW ── (seperti index) --}}
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

                                {{-- Parent Row --}}
                                <tr class="hover:bg-primary-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $needsKonfirmasi ? 'bg-amber-50/30' : '' }}"
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
                                                {{ $trx->muzakki_nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->waktu_transaksi)
                                                    &middot; {{ $trx->waktu_transaksi->format('H:i') }}
                                                @endif
                                                @if ($trx->jumlah > 0)
                                                    &middot; <span class="font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                                @endif
                                                @if ($trx->jumlah_infaq > 0)
                                                    &middot; <span class="text-amber-600 font-medium">+Infaq {{ $trx->jumlah_infaq_formatted }}</span>
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    &middot; <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                {!! $trx->metode_pembayaran_badge !!}
                                                {!! $trx->konfirmasi_status_badge !!}
                                                @if ($needsKonfirmasi)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                        Perlu Konfirmasi
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if ($needsKonfirmasi)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        onclick="openKonfirmasiModal('{{ $trx->uuid }}', '{{ addslashes($trx->muzakki_nama) }}', '{{ $trx->metode_pembayaran }}', '{{ $buktiUrl }}')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Konfirmasi
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="relative group/tooltip">
                                                <a href="{{ route('transaksi-daring.show', $trx->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Detail
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
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
                                                <div class="w-1 h-5 bg-primary-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi — {{ $trx->muzakki_nama }}</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Data Muzakki --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Muzakki</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $trx->muzakki_nama }}</p>
                                                        </div>
                                                        @if ($trx->muzakki_telepon)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Telepon</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $trx->muzakki_telepon }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_email)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Email</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $trx->muzakki_email }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($hasNamaJiwa)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Nama Jiwa <span class="text-gray-400">({{ count($namaJiwaList) }} orang)</span></p>
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
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Zakat --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Detail Zakat</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal Transaksi</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                                        </div>
                                                        @if ($trx->jenisZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $trx->jenisZakat->nama }}
                                                                    @if ($trx->tipeZakat) — {{ $trx->tipeZakat->nama }} @endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs text-gray-400">Jumlah Zakat</p>
                                                            <p class="text-sm font-semibold text-green-600">{{ $trx->jumlah_formatted }}</p>
                                                            @if ($trx->jumlah_infaq > 0)
                                                                <p class="text-xs text-amber-600 mt-0.5">+ Infaq: {{ $trx->jumlah_infaq_formatted }}</p>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">No. Transaksi</p>
                                                            <p class="text-sm font-mono text-gray-800">{{ $trx->no_transaksi }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status & Metode --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Status &amp; Metode</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode Pembayaran</p>
                                                            <div class="mt-1">{!! $trx->metode_pembayaran_badge !!}</div>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status Konfirmasi</p>
                                                            <div class="mt-1">{!! $trx->konfirmasi_status_badge !!}</div>
                                                            @if ($trx->no_referensi_transfer)
                                                                <p class="text-xs text-gray-400 mt-1">No. Referensi: {{ $trx->no_referensi_transfer }}</p>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status Verifikasi</p>
                                                            <div class="mt-1">{!! $trx->status_badge !!}</div>
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── MOBILE VIEW ── (seperti index) --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($transaksis as $trx)
                        @php
                            $needsKonfirmasi = $trx->konfirmasi_status === 'menunggu_konfirmasi';
                            $buktiUrl = $trx->bukti_transfer ? Storage::url($trx->bukti_transfer) : '';
                            
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
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $needsKonfirmasi ? 'bg-amber-50/30' : '' }}"
                            data-target="detail-mobile-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">
                                            {{ $trx->muzakki_nama }}
                                        </h3>
                                        {!! $trx->status_badge !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if ($trx->jumlah > 0)
                                            <span class="text-xs font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                        @endif
                                        {!! $trx->metode_pembayaran_badge !!}
                                        {!! $trx->konfirmasi_status_badge !!}
                                    </div>
                                    @if ($needsKonfirmasi)
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                Perlu Konfirmasi
                                            </span>
                                        </div>
                                    @endif
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
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data Muzakki</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Nama:</span> {{ $trx->muzakki_nama }}</p>
                                            @if ($trx->muzakki_telepon)
                                                <p><span class="text-gray-500">Telepon:</span> {{ $trx->muzakki_telepon }}</p>
                                            @endif
                                            @if ($hasNamaJiwa)
                                                <p><span class="text-gray-500">Nama Jiwa:</span> {{ count($namaJiwaList) }} orang</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Detail Zakat</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Tanggal:</span> {{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                            @if ($trx->jenisZakat)
                                                <p><span class="text-gray-500">Jenis Zakat:</span> {{ $trx->jenisZakat->nama }}</p>
                                            @endif
                                            <p><span class="text-gray-500">Jumlah:</span> <span class="font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span></p>
                                            @if ($trx->jumlah_infaq > 0)
                                                <p><span class="text-gray-500">Infaq:</span> {{ $trx->jumlah_infaq_formatted }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="pt-2 flex items-center gap-2 flex-wrap">
                                        @if ($needsKonfirmasi)
                                            <button type="button"
                                                onclick="openKonfirmasiModal('{{ $trx->uuid }}', '{{ addslashes($trx->muzakki_nama) }}', '{{ $trx->metode_pembayaran }}', '{{ $buktiUrl }}')"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-xs ml-1">Konfirmasi</span>
                                            </button>
                                        @endif

                                        <a href="{{ route('transaksi-daring.show', $trx->uuid) }}"
                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span class="text-xs ml-1">Detail</span>
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
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                    </div>

                    @if (request()->hasAny(['q', 'jenis_zakat_id', 'konfirmasi_status', 'metode_pembayaran', 'start_date', 'end_date']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('transaksi-daring.index') }}"
                            class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada transaksi zakat daring</p>
                        <p class="text-xs text-gray-400">Transaksi akan muncul saat muzakki melakukan pembayaran daring.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── Modal: Konfirmasi Pembayaran (Responsive dengan scroll) ── --}}
    <div id="konfirmasi-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl flex flex-col max-h-[90vh]">
            <div class="p-6 flex flex-col h-full">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Konfirmasi Pembayaran</h3>
                <p class="text-sm text-gray-500 mb-4 text-center">
                    Konfirmasi pembayaran dari
                    "<span id="modal-konfirmasi-nama" class="font-semibold text-gray-700"></span>"
                    via <span id="modal-konfirmasi-metode" class="font-semibold text-amber-700"></span>
                </p>

                {{-- Scrollable content area --}}
                <div class="flex-1 overflow-y-auto">
                    <div id="modal-bukti-container" class="hidden mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Bukti Pembayaran</label>
                        <a id="modal-bukti-link" href="#" target="_blank"
                            class="block relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-zoom-in">
                            <img id="modal-bukti-img" src="" alt="Bukti Pembayaran"
                                class="w-full object-contain rounded-xl max-h-48">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 text-white text-xs px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Buka di tab baru
                                </span>
                            </div>
                        </a>
                    </div>
                    <div id="modal-bukti-empty" class="hidden mb-4">
                        <div class="flex items-center justify-center h-24 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs text-gray-400">Bukti pembayaran tidak tersedia</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-700">
                            <span class="font-semibold">⚠️ Pastikan</span> dana sudah masuk ke rekening/QRIS masjid sebelum mengkonfirmasi.
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Catatan (opsional)</label>
                        <input type="text" id="konfirmasi-catatan" placeholder="Misal: Dana sudah masuk pukul 10.30"
                            class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                    </div>
                </div>

                <form method="POST" id="konfirmasi-form" class="mt-4">
                    @csrf
                    <input type="hidden" name="catatan_konfirmasi" id="konfirmasi-catatan-hidden">
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('konfirmasi-modal')"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-xl text-sm font-medium text-white transition-all">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
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
        // Dropdown Import/Export
        const dropdownToggle = document.getElementById('dropdownToggleBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');

        if (dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
                if (dropdownIcon) {
                    dropdownIcon.classList.toggle('rotate-180');
                }
            });

            document.addEventListener('click', function(e) {
                if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                    if (dropdownIcon) {
                        dropdownIcon.classList.remove('rotate-180');
                    }
                }
            });
        }

        // Filter button
        const filterBtn = document.getElementById('filterButton');
        if (filterBtn) {
            filterBtn.addEventListener('click', toggleFilter);
        }

        // Desktop expandable rows
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

        // Mobile expandable cards
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

        // Konfirmasi form submit
        document.getElementById('konfirmasi-form')?.addEventListener('submit', function() {
            document.getElementById('konfirmasi-catatan-hidden').value =
                document.getElementById('konfirmasi-catatan').value;
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

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openKonfirmasiModal(uuid, nama, metode, buktiUrl) {
        document.getElementById('modal-konfirmasi-nama').textContent = nama;
        const metodeText = metode === 'qris' ? 'QRIS' : 'Transfer Bank';
        document.getElementById('modal-konfirmasi-metode').textContent = metodeText;
        document.getElementById('konfirmasi-form').action = '/transaksi-daring/' + uuid + '/konfirmasi-pembayaran';
        document.getElementById('konfirmasi-catatan').value = '';

        const buktiContainer = document.getElementById('modal-bukti-container');
        const buktiEmpty = document.getElementById('modal-bukti-empty');
        const buktiImg = document.getElementById('modal-bukti-img');
        const buktiLink = document.getElementById('modal-bukti-link');

        if (buktiUrl && buktiUrl.trim() !== '') {
            buktiImg.src = buktiUrl;
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('konfirmasi-modal');
        }
    });

    document.getElementById('konfirmasi-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal('konfirmasi-modal');
    });
</script>
@endpush