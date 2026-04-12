{{-- resources/views/muzakki/riwayat-transaksi/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Transaksi Zakat Saya')

@section('content')
    <div class="space-y-6">

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Riwayat Transaksi Zakat</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Total: {{ $transaksis->total() }} transaksi</p>
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
                            @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                                <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-green-600 text-white rounded-full">
                                    {{ collect(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total Transaksi:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($transaksis->total()) }}</span>
                    </div>

                    <!-- Stats Ringkasan Desktop -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($transaksis->where('status', 'pending')->count()) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Terverifikasi:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($transaksis->where('status', 'verified')->count()) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-xs text-gray-500">Ditolak:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($transaksis->where('status', 'rejected')->count()) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($transaksis->sum('jumlah'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('riwayat-transaksi-muzakki.index') }}" id="filter-form">
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
                                    placeholder="Cari no. transaksi..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>

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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Jenis</option>
                                @foreach ($jenisZakatList ?? [] as $jenis)
                                    <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Penerimaan</label>
                            <select name="metode_penerimaan"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Metode</option>
                                <option value="daring" {{ request('metode_penerimaan') == 'daring' ? 'selected' : '' }}>Daring (Transfer/QRIS)</option>
                                <option value="dijemput" {{ request('metode_penerimaan') == 'dijemput' ? 'selected' : '' }}>Dijemput Amil</option>
                                <option value="langsung" {{ request('metode_penerimaan') == 'langsung' ? 'selected' : '' }}>Datang Langsung</option>
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
                        @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                            <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
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
            @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('status'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('jenis_zakat_id') && isset($jenisZakatList))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Jenis Zakat: {{ $jenisZakatList->firstWhere('id', request('jenis_zakat_id'))?->nama ?? request('jenis_zakat_id') }}
                                <button onclick="removeFilter('jenis_zakat_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('metode_penerimaan'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Metode: {{ ucfirst(request('metode_penerimaan')) }}
                                <button onclick="removeFilter('metode_penerimaan')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('start_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Mulai: {{ request('start_date') }}
                                <button onclick="removeFilter('start_date')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('end_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">TRANSAKSI</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">JENIS &amp; METODE</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">JUMLAH</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-28">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
                                    $isPending = $trx->status === 'pending';
                                    $isVerified = $trx->status === 'verified';
                                    $isRejected = $trx->status === 'rejected';
                                    $isDaring = $trx->metode_penerimaan === 'daring';
                                    $isDijemput = $trx->metode_penerimaan === 'dijemput';
                                    $isLangsung = $trx->metode_penerimaan === 'langsung';

                                    $hasNamaJiwa = false;
                                    $namaJiwaList = [];
                                    if (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                        $hasNamaJiwa = true;
                                        $namaJiwaList = $trx->nama_jiwa_json;
                                    } elseif (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                        $hasNamaJiwa = true;
                                        $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                                    }

                                    $labelMetode = match ($trx->metode_penerimaan ?? '') {
                                        'daring' => 'Daring',
                                        'dijemput' => 'Dijemput Amil',
                                        'langsung' => 'Datang Langsung',
                                        default => '-',
                                    };
                                    $warnaMetode = match ($trx->metode_penerimaan ?? '') {
                                        'daring' => 'bg-indigo-100 text-indigo-800',
                                        'dijemput' => 'bg-orange-100 text-orange-800',
                                        'langsung' => 'bg-gray-100 text-gray-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $warnaStatus = match ($trx->status ?? '') {
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'verified' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $labelStatus = match ($trx->status ?? '') {
                                        'pending' => 'Menunggu',
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        default => '-',
                                    };
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $isPending ? 'bg-amber-50/30' : '' }}
                                    {{ $isRejected ? 'bg-red-50/30' : '' }}"
                                    data-target="detail-{{ $trx->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-mono font-medium text-gray-800">
                                                {{ $trx->no_transaksi }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->tanggal_transaksi)
                                                    &middot; {{ $trx->tanggal_transaksi->format('H:i') }}
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    &middot; <span class="text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                                @endif
                                            </div>
                                            @if ($trx->programZakat)
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $trx->programZakat->nama_program }}</div>
                                            @endif
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaStatus }} border">
                                                    {{ $labelStatus }}
                                                </span>
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }} border">
                                                    {{ $labelMetode }}
                                                </span>
                                                @if ($trx->jenisZakat)
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700 border">
                                                        {{ $trx->jenisZakat->nama }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if ($trx->jenisZakat)
                                                <p class="text-sm font-medium text-gray-900">{{ $trx->jenisZakat->nama }}</p>
                                                @if ($trx->tipeZakat)
                                                    <p class="text-xs text-gray-500">{{ $trx->tipeZakat->nama }}</p>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                            @if ($isDaring && $trx->metode_pembayaran)
                                                <p class="text-xs text-gray-400 uppercase mt-1">via {{ $trx->metode_pembayaran }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if ($trx->jumlah > 0)
                                            <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                        @elseif($trx->jumlah_beras_kg > 0)
                                            <p class="text-sm font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</p>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                        @if ($trx->jumlah_infaq > 0)
                                            <p class="text-xs text-amber-600 mt-0.5">+ Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }} infaq</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $warnaStatus }} border">
                                                {{ $labelStatus }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $trx->id }}" class="hidden expandable-content border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="4" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi — {{ $trx->no_transaksi }}</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Info Transaksi --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Info Transaksi</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">No. Transaksi</p>
                                                            <p class="text-sm font-mono font-medium text-gray-800">
                                                                {{ $trx->no_transaksi }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal & Waktu</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                                @if ($trx->tanggal_transaksi)
                                                                    <span class="text-gray-500">({{ $trx->tanggal_transaksi->format('H:i') }} WIB)</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        @if ($trx->programZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Program Zakat</p>
                                                                <p class="text-sm text-gray-800">
                                                                    {{ $trx->programZakat->nama_program }}</p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode Penerimaan</p>
                                                            <p class="text-sm text-gray-800">{{ $labelMetode }}</p>
                                                        </div>
                                                        @if ($isDaring && $trx->metode_pembayaran)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Metode Pembayaran</p>
                                                                <p class="text-sm text-gray-800 uppercase">{{ $trx->metode_pembayaran }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Zakat --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Detail Zakat</h4>
                                                    <div class="space-y-3">
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
                                                        @endif

                                                        {{-- DATA ZAKAT FITRAH (Jiwa) --}}
                                                        @if ($hasNamaJiwa)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Nama Jiwa
                                                                    <span class="text-gray-400">({{ count($namaJiwaList) }} orang)</span>
                                                                </p>
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

                                                        {{-- DATA ZAKAT MAL --}}
                                                        @if (!empty($trx->dataZakatMal))
                                                            <div>
                                                                <p class="text-xs text-gray-400">Detail Mal</p>
                                                                <p class="text-xs text-gray-700">
                                                                    Nilai Harta: Rp {{ number_format($trx->dataZakatMal['nilai_harta'] ?? 0, 0, ',', '.') }}
                                                                    @if (!empty($trx->dataZakatMal['persentase']))
                                                                        &middot; {{ $trx->dataZakatMal['persentase'] }}%
                                                                    @endif
                                                                    @if (!empty($trx->dataZakatMal['sudah_haul']))
                                                                        &middot; Sudah Haul
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @endif

                                                        @if ($trx->keterangan)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Keterangan</p>
                                                                <p class="text-sm text-gray-700">{{ $trx->keterangan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Nominal & Status --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Nominal &amp; Status</h4>
                                                    <div class="space-y-3">
                                                        @if ($trx->jumlah > 0)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jumlah Zakat</p>
                                                                <p class="text-sm font-semibold text-green-600">
                                                                    Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                                </p>
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
                                                        @if ($trx->jumlah_infaq > 0)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Infaq</p>
                                                                <p class="text-sm text-gray-700">
                                                                    Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status</p>
                                                            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaStatus }} border">
                                                                {{ $labelStatus }}
                                                            </span>
                                                        </div>
                                                        @if ($isRejected && $trx->alasan_penolakan)
                                                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                                <p class="text-xs font-medium text-red-800">Alasan Penolakan</p>
                                                                <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->catatan_konfirmasi)
                                                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                                <p class="text-xs font-medium text-blue-800">Catatan Amil</p>
                                                                <p class="text-xs text-blue-700 mt-0.5">{{ $trx->catatan_konfirmasi }}</p>
                                                            </div>
                                                        @endif
                                                        <div class="pt-2 border-t border-gray-200 mt-2">
                                                            <p class="text-xs text-gray-400">Dibuat: {{ $trx->created_at->format('d/m/Y H:i') }}</p>
                                                            @if ($trx->updated_at != $trx->created_at)
                                                                <p class="text-xs text-gray-400 mt-0.5">Diperbarui: {{ $trx->updated_at->format('d/m/Y H:i') }}</p>
                                                            @endif
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

                {{-- ── MOBILE VIEW ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($transaksis as $trx)
                        @php
                            $isPending = $trx->status === 'pending';
                            $isVerified = $trx->status === 'verified';
                            $isRejected = $trx->status === 'rejected';
                            $isDaring = $trx->metode_penerimaan === 'daring';
                            $isDijemput = $trx->metode_penerimaan === 'dijemput';

                            $hasNamaJiwa = false;
                            $namaJiwaList = [];
                            if (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                $hasNamaJiwa = true;
                                $namaJiwaList = $trx->nama_jiwa_json;
                            } elseif (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                $hasNamaJiwa = true;
                                $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                            }

                            $warnaStatus = match ($trx->status ?? '') {
                                'pending' => 'bg-amber-100 text-amber-800',
                                'verified' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-600',
                            };
                            $labelStatus = match ($trx->status ?? '') {
                                'pending' => 'Menunggu',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                                default => '-',
                            };
                            $labelMetode = match ($trx->metode_penerimaan ?? '') {
                                'daring' => 'Daring',
                                'dijemput' => 'Dijemput',
                                'langsung' => 'Langsung',
                                default => '-',
                            };
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $isPending ? 'bg-amber-50/30' : '' }}
                            {{ $isRejected ? 'bg-red-50/30' : '' }}"
                            data-target="detail-mobile-{{ $trx->id }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-mono font-semibold text-gray-800 truncate">
                                            {{ $trx->no_transaksi }}
                                        </h3>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaStatus }} border flex-shrink-0">
                                            {{ $labelStatus }}
                                        </span>
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</span>
                                        @if ($hasNamaJiwa)
                                            <span class="text-xs text-blue-600">{{ count($namaJiwaList) }} jiwa</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $labelMetode }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @if ($trx->jenisZakat)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700 border">
                                                {{ $trx->jenisZakat->nama }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        @if ($trx->jumlah > 0)
                                            <span class="text-sm font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span>
                                        @elseif($trx->jumlah_beras_kg > 0)
                                            <span class="text-sm font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span>
                                        @endif
                                        @if ($trx->jumlah_infaq > 0)
                                            <span class="text-xs text-amber-600">+{{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</span>
                                        @endif
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
                            <div id="detail-mobile-{{ $trx->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Info Transaksi</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">No. Transaksi:</span> {{ $trx->no_transaksi }}</p>
                                            <p><span class="text-gray-500">Tanggal:</span> {{ $trx->tanggal_transaksi->format('d F Y') }}
                                                @if ($trx->tanggal_transaksi)
                                                    <span class="text-gray-400">({{ $trx->tanggal_transaksi->format('H:i') }} WIB)</span>
                                                @endif
                                            </p>
                                            @if ($trx->programZakat)
                                                <p><span class="text-gray-500">Program:</span> {{ $trx->programZakat->nama_program }}</p>
                                            @endif
                                            <p><span class="text-gray-500">Metode:</span> {{ $labelMetode }}</p>
                                            @if ($isDaring && $trx->metode_pembayaran)
                                                <p><span class="text-gray-500">Pembayaran:</span> {{ ucfirst($trx->metode_pembayaran) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Detail Zakat</h4>
                                        <div class="space-y-1 text-sm">
                                            @if ($trx->jenisZakat)
                                                <p><span class="text-gray-500">Jenis:</span> {{ $trx->jenisZakat->nama }}
                                                    @if ($trx->tipeZakat)
                                                        ({{ $trx->tipeZakat->nama }})
                                                    @endif
                                                </p>
                                            @endif
                                            @if ($hasNamaJiwa)
                                                <div class="mt-1">
                                                    <p class="text-xs text-gray-500">Nama Jiwa ({{ count($namaJiwaList) }} orang)</p>
                                                    <div class="text-xs text-gray-700 mt-1 space-y-0.5">
                                                        @foreach ($namaJiwaList as $idx => $nama)
                                                            @if ($nama && trim($nama) !== '')
                                                                <div class="flex gap-1">
                                                                    <span class="text-gray-400">{{ $idx + 1 }}.</span><span>{{ $nama }}</span>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($trx->keterangan)
                                                <p><span class="text-gray-500">Keterangan:</span> {{ $trx->keterangan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Nominal & Status</h4>
                                        <div class="space-y-1 text-sm">
                                            @if ($trx->jumlah > 0)
                                                <p><span class="text-gray-500">Jumlah Zakat:</span> <span class="font-semibold text-green-600">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</span></p>
                                            @endif
                                            @if ($trx->jumlah_beras_kg > 0)
                                                <p><span class="text-gray-500">Jumlah Beras:</span> <span class="font-semibold text-amber-600">{{ $trx->jumlah_beras_kg }} kg</span></p>
                                            @endif
                                            @if ($trx->jumlah_infaq > 0)
                                                <p><span class="text-gray-500">Infaq:</span> Rp {{ number_format($trx->jumlah_infaq, 0, ',', '.') }}</p>
                                            @endif
                                            <p><span class="text-gray-500">Status:</span> 
                                                <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaStatus }} border">
                                                    {{ $labelStatus }}
                                                </span>
                                            </p>
                                        </div>
                                        @if ($isRejected && $trx->alasan_penolakan)
                                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-xs font-medium text-red-800">Alasan Penolakan</p>
                                                <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                            </div>
                                        @endif
                                        @if ($trx->catatan_konfirmasi)
                                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                <p class="text-xs font-medium text-blue-800">Catatan Amil</p>
                                                <p class="text-xs text-blue-700 mt-0.5">{{ $trx->catatan_konfirmasi }}</p>
                                            </div>
                                        @endif
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
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>

                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada riwayat transaksi zakat</p>
                        <a href="{{ route('zakat.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Bayar Zakat sekarang
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

        // ── Filter functions ──
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