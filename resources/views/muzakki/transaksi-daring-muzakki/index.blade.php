{{-- resources/views/muzakki/transaksi-daring-muzakki/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Transaksi Zakat Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Alert: Ada transaksi menunggu konfirmasi ── --}}
        @if (isset($stats['total_pending']) && $stats['total_pending'] > 0)
            <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl">
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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 sm:p-5 flex items-center gap-3 transition-all duration-300">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Total Transaksi</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 sm:p-5 flex items-center gap-3 transition-all duration-300">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Terverifikasi</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total_verified'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 sm:p-5 flex items-center gap-3 transition-all duration-300">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Menunggu</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total_pending'], 0, ',', '.') }}</p>
                    @if ($stats['total_pending'] > 0)
                        <p class="text-xs text-amber-600 mt-0.5">Proses konfirmasi</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 sm:p-5 flex items-center gap-3 transition-all duration-300">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Total Dibayar</p>
                    <p class="text-sm sm:text-base font-bold text-gray-900 break-words">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Terverifikasi</p>
                </div>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Riwayat Transaksi Zakat</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        {{-- Bayar Zakat Baru --}}
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Bayar Zakat
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $transaksis->total() }}</span>
                        <span class="text-sm text-gray-500">Transaksi</span>
                    </div>
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Terverifikasi:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $stats['total_verified'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $stats['total_pending'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date', 'q']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('transaksi-daring-muzakki.index') }}" id="filter-form">
                    <div class="space-y-3">
                        {{-- Search --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Transaksi</label>
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari nomor transaksi, nama muzakki..."
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Metode Penerimaan</label>
                                <select name="metode_penerimaan"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Metode</option>
                                    <option value="daring" {{ request('metode_penerimaan') == 'daring' ? 'selected' : '' }}>Daring (Transfer/QRIS)</option>
                                    <option value="dijemput" {{ request('metode_penerimaan') == 'dijemput' ? 'selected' : '' }}>Dijemput Amil</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                                <select name="jenis_zakat_id"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisZakatList as $jenis)
                                        <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Dari</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Sampai</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                            <a href="{{ route('transaksi-daring-muzakki.index') }}"
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

            {{-- Active Filter Tags --}}
            @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Pencarian: "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('status'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('metode_penerimaan'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Metode: {{ ucfirst(request('metode_penerimaan')) }}
                                <button onclick="removeFilter('metode_penerimaan')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('jenis_zakat_id'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Jenis Zakat dipilih
                                <button onclick="removeFilter('jenis_zakat_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('start_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Dari: {{ request('start_date') }}
                                <button onclick="removeFilter('start_date')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('end_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Sampai: {{ request('end_date') }}
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">TRANSAKSI &amp; DETAIL</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
                                    $isPending  = $trx->status === 'pending';
                                    $isVerified = $trx->status === 'verified';
                                    $isRejected = $trx->status === 'rejected';
                                    $isDaring   = $trx->metode_penerimaan === 'daring';
                                    $isDijemput = $trx->metode_penerimaan === 'dijemput';

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
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $isPending  ? 'bg-yellow-50/30' : '' }}
                                    {{ $isRejected ? 'bg-red-50/30'    : '' }}"
                                    data-target="detail-{{ $trx->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800">{{ $trx->muzakki_nama ?? '-' }}</div>
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
                                                &middot; <span class="text-green-600">{{ count($namaJiwaList) }} jiwa</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            {!! $trx->status_badge !!}
                                            @if ($isDaring)
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-100">Daring</span>
                                            @elseif ($isDijemput)
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-700 border border-orange-100">Dijemput</span>
                                            @endif
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
                                        <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            {{-- Detail --}}
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('transaksi-daring-muzakki.show', $trx->uuid) }}"
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
                                        </div>
                                    </td>
                                </tr>

                                {{-- ── Expandable Content Row ── --}}
                                <tr id="detail-{{ $trx->uuid }}" class="hidden expandable-content border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi —
                                                    <span class="font-mono text-gray-600">{{ $trx->no_transaksi }}</span>
                                                </h3>
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
                                                        @if ($trx->muzakki_nik)
                                                            <div>
                                                                <p class="text-xs text-gray-400">NIK</p>
                                                                <p class="text-sm font-mono font-medium text-gray-800">{{ $trx->muzakki_nik }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->muzakki_alamat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Alamat</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ Str::limit($trx->muzakki_alamat, 80) }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($hasNamaJiwa)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Nama Jiwa <span class="text-gray-300">({{ count($namaJiwaList) }} orang)</span></p>
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    @foreach ($namaJiwaList as $index => $nama)
                                                                        @if ($nama && trim($nama) !== '')
                                                                            <span class="inline-flex items-center bg-white border border-gray-200 rounded-lg px-2 py-0.5 text-xs text-gray-700">
                                                                                <span class="text-gray-400 mr-1">{{ $index + 1 }}.</span>{{ $nama }}
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
                                                            <p class="text-xs text-gray-400">Tanggal</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                                        </div>
                                                        @if ($trx->jenisZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $trx->jenisZakat->nama }}</p>
                                                                @if ($trx->tipeZakat)
                                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $trx->tipeZakat->nama }}</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if ($trx->jumlah > 0)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jumlah Zakat</p>
                                                                <p class="text-sm font-semibold text-green-600">{{ $trx->jumlah_formatted }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->jumlah_infaq > 0)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Infaq</p>
                                                                <p class="text-sm font-medium text-amber-600">{{ $trx->jumlah_infaq_formatted }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($isDijemput && $trx->amil)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Amil Penjemput</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $trx->amil->pengguna->username ?? '-' }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status & Pembayaran --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Status &amp; Pembayaran</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status</p>
                                                            <div class="mt-1">{!! $trx->status_badge !!}</div>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode</p>
                                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $isDaring ? 'bg-indigo-100 text-indigo-800' : 'bg-orange-100 text-orange-800' }}">
                                                                {{ $isDaring ? 'Daring' : 'Dijemput' }}
                                                            </span>
                                                        </div>
                                                        @if ($isDaring && $trx->metode_pembayaran)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Metode Bayar</p>
                                                                <p class="text-sm font-medium text-gray-800 uppercase">{{ $trx->metode_pembayaran }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($isDaring && isset($trx->konfirmasi_status_badge))
                                                            <div>
                                                                <p class="text-xs text-gray-400">Konfirmasi</p>
                                                                <div class="mt-1">{!! $trx->konfirmasi_status_badge !!}</div>
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
                            $isPending  = $trx->status === 'pending';
                            $isRejected = $trx->status === 'rejected';
                            $isDaring   = $trx->metode_penerimaan === 'daring';
                            $isDijemput = $trx->metode_penerimaan === 'dijemput';

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

                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $isPending  ? 'bg-yellow-50/30' : '' }}
                            {{ $isRejected ? 'bg-red-50/30'    : '' }}"
                            data-target="detail-mobile-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $trx->muzakki_nama ?? '-' }}</h3>
                                        {!! $trx->status_badge !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if ($trx->jumlah > 0)
                                            <span class="text-xs font-semibold text-gray-700">{{ $trx->jumlah_formatted }}</span>
                                        @endif
                                        @if ($trx->jenisZakat)
                                            <span class="text-xs text-gray-400">{{ $trx->jenisZakat->nama }}</span>
                                        @endif
                                        @if ($isDaring)
                                            <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700">Daring</span>
                                        @elseif ($isDijemput)
                                            <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-700">Dijemput</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk detail</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $trx->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">

                                    {{-- Data Muzakki --}}
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data Muzakki</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Nama:</span> {{ $trx->muzakki_nama ?? '-' }}</p>
                                            @if ($trx->muzakki_telepon)
                                                <p><span class="text-gray-500">Telepon:</span> {{ $trx->muzakki_telepon }}</p>
                                            @endif
                                            @if ($trx->muzakki_nik)
                                                <p><span class="text-gray-500">NIK:</span> <span class="font-mono">{{ $trx->muzakki_nik }}</span></p>
                                            @endif
                                            @if ($trx->muzakki_alamat)
                                                <p><span class="text-gray-500">Alamat:</span> {{ Str::limit($trx->muzakki_alamat, 60) }}</p>
                                            @endif
                                        </div>
                                        @if ($hasNamaJiwa)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-500 mb-1">Nama Jiwa ({{ count($namaJiwaList) }} orang):</p>
                                                <div class="space-y-0.5">
                                                    @foreach ($namaJiwaList as $index => $nama)
                                                        @if ($nama && trim($nama) !== '')
                                                            <p class="text-xs text-gray-700"><span class="text-gray-400">{{ $index + 1 }}.</span> {{ $nama }}</p>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Detail Zakat --}}
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Detail Zakat</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Tanggal:</span> {{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                            @if ($trx->jenisZakat)
                                                <p><span class="text-gray-500">Jenis:</span> {{ $trx->jenisZakat->nama }}{{ $trx->tipeZakat ? ' — ' . $trx->tipeZakat->nama : '' }}</p>
                                            @endif
                                            @if ($trx->jumlah > 0)
                                                <p><span class="text-gray-500">Jumlah:</span> <span class="font-semibold text-green-600">{{ $trx->jumlah_formatted }}</span>
                                                    @if ($trx->jumlah_infaq > 0)
                                                        <span class="text-amber-600">(+Infaq {{ $trx->jumlah_infaq_formatted }})</span>
                                                    @endif
                                                </p>
                                            @endif
                                            @if ($isDaring && $trx->metode_pembayaran)
                                                <p><span class="text-gray-500">Bayar via:</span> <span class="font-medium uppercase">{{ $trx->metode_pembayaran }}</span></p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</h4>
                                        <div class="space-y-1">
                                            <div>{!! $trx->status_badge !!}</div>
                                            @if ($isDaring && isset($trx->konfirmasi_status_badge))
                                                <div class="mt-1">{!! $trx->konfirmasi_status_badge !!}</div>
                                            @endif
                                            @if ($trx->catatan_konfirmasi)
                                                <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                    <p class="text-xs text-blue-600 font-medium">Catatan Amil:</p>
                                                    <p class="text-xs text-blue-800 mt-0.5">{{ $trx->catatan_konfirmasi }}</p>
                                                </div>
                                            @endif
                                            @if ($isRejected && $trx->alasan_penolakan)
                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                    <p class="text-xs text-red-600 font-medium">Alasan Penolakan:</p>
                                                    <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Mobile Action Buttons --}}
                                    <div class="pt-2 flex items-center gap-2">
                                        <a href="{{ route('transaksi-daring-muzakki.show', $trx->uuid) }}"
                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                {{-- Empty State --}}
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('transaksi-daring-muzakki.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada transaksi zakat</p>
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Bayar Zakat Sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleFilter() {
            var panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        }

        function removeFilter(filterName) {
            var url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function () {

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon   = this.querySelector('.expand-icon');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-90');
                    }
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon   = this.querySelector('.expand-icon-mobile');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') toggleFilter();
        });
    </script>
@endpush