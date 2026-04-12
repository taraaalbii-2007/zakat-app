{{-- resources/views/amil/pemantauan-transaksi/index.blade.php --}}
{{-- 
    HANYA MENAMPILKAN KESELURUHAN METODE (DATANG LANGSUNG, DIJEMPUT, DARING)
    TANPA BUTTON CREATE DAN TOMBOL AKSI
    UNTUK KEPERLUAN PEMANTAUAN
--}}

@extends('layouts.app')

@section('title', 'Pemantauan Transaksi Penerimaan')

@section('content')
    <div class="space-y-6">

        {{-- ── Stats Cards ── --}}
        {{-- Toggle button khusus mobile --}}
        <div class="sm:hidden">
            <button type="button" onclick="toggleStatsMobile()"
                class="w-full flex items-center justify-between px-4 py-2.5 bg-white rounded-xl border border-gray-100 shadow-sm text-sm font-medium text-gray-700">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Lihat Statistik
                </span>
                <svg id="stats-chevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <div id="stats-mobile-panel" class="hidden sm:block">
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 sm:gap-4">
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 sm:py-4 flex flex-col gap-1 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs text-gray-500 font-medium">Total Transaksi</span>
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</span>
                </div>
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 sm:py-4 flex flex-col gap-1 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs text-gray-500 font-medium">Total Nominal</span>
                    <span class="text-lg font-bold text-green-600">Rp
                        {{ number_format($stats['total_nominal'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 sm:py-4 flex flex-col gap-1 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs text-gray-500 font-medium">Datang Langsung</span>
                    <span
                        class="text-2xl font-bold text-blue-600">{{ number_format($stats['datang_langsung'] ?? 0) }}</span>
                </div>
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 sm:py-4 flex flex-col gap-1 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs text-gray-500 font-medium">Dijemput</span>
                    <span class="text-2xl font-bold text-amber-600">{{ number_format($stats['dijemput'] ?? 0) }}</span>
                </div>
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 sm:py-4 flex flex-col gap-1 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs text-gray-500 font-medium">Daring</span>
                    <span class="text-2xl font-bold text-purple-600">{{ number_format($stats['daring'] ?? 0) }}</span>
                    @if (($stats['menunggu_konfirmasi'] ?? 0) > 0)
                        <span class="text-xs text-amber-600 font-medium mt-0.5">{{ $stats['menunggu_konfirmasi'] }} perlu
                            konfirmasi</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Beras --}}
        <div id="stats-mobile-extra" class="hidden sm:block space-y-4">

            @if (($stats['total_beras_kg'] ?? 0) > 0 || ($stats['total_transaksi_beras'] ?? 0) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div
                        class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-4 transition-all duration-300 hover:shadow-md">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 font-medium block">Total Beras Diterima</span>
                            <span class="text-xl font-bold text-amber-600">
                                {{ number_format($stats['total_beras_kg'] ?? 0, 1, ',', '.') }} kg
                            </span>
                            <span class="text-xs text-gray-400 block mt-0.5">
                                dari {{ number_format($stats['total_transaksi_beras'] ?? 0) }} transaksi
                            </span>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-4 transition-all duration-300 hover:shadow-md">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 font-medium block">Estimasi Jiwa (Beras)</span>
                            <span class="text-xl font-bold text-amber-700">
                                {{ number_format(($stats['total_beras_kg'] ?? 0) > 0 ? floor(($stats['total_beras_kg'] ?? 0) / 2.5) : 0) }}
                                jiwa
                            </span>
                            <span class="text-xs text-gray-400 block mt-0.5">@ 2,5 kg/jiwa (BAZNAS)</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stats Fidyah (Tambahan) --}}
            @if (($stats['total_fidyah'] ?? 0) > 0)
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-2.5 transition-all duration-300 hover:bg-gray-100">
                        <span class="text-xs text-gray-600 font-medium block mb-1">Fidyah Mentah</span>
                        <span
                            class="block text-lg font-bold text-gray-800">{{ number_format($stats['fidyah_mentah'] ?? 0) }}</span>
                    </div>
                    <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-2.5 transition-all duration-300 hover:bg-gray-100">
                        <span class="text-xs text-gray-600 font-medium block mb-1">Fidyah Matang</span>
                        <span
                            class="block text-lg font-bold text-gray-800">{{ number_format($stats['fidyah_matang'] ?? 0) }}</span>
                    </div>
                    <div class="bg-gray-50 rounded-xl border border-gray-200 px-4 py-2.5 transition-all duration-300 hover:bg-gray-100">
                        <span class="text-xs text-gray-600 font-medium block mb-1">Fidyah Tunai</span>
                        <span
                            class="block text-lg font-bold text-gray-800">{{ number_format($stats['fidyah_tunai'] ?? 0) }}</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">Daftar Transaksi Penerimaan</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Total: {{ $transaksis->total() }} Transaksi</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        {{-- ── Tombol Export (dropdown) ── --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                                <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden z-20">
                                <div class="py-1">
                                    <a href="{{ route('pemantauan-transaksi.export.pdf', request()->query()) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Export PDF
                                    </a>
                                    <a href="{{ route('pemantauan-transaksi.export.excel', request()->query()) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                            @if (request()->hasAny([
                                    'jenis_zakat_id',
                                    'metode_pembayaran',
                                    'status',
                                    'metode_penerimaan',
                                    'konfirmasi_status',
                                    'status_penjemputan',
                                    'start_date',
                                    'end_date',
                                    'periode',
                                    'fidyah_tipe',
                                ]))
                                <span
                                    class="ml-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-green-600 text-white rounded-full">
                                    {{ collect(['jenis_zakat_id', 'metode_pembayaran', 'status', 'metode_penerimaan', 'konfirmasi_status', 'status_penjemputan', 'start_date', 'periode', 'fidyah_tipe'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-6 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $transaksis->total() }}</span>
                        <span class="text-sm text-gray-500">Transaksi</span>
                    </div>

                    <!-- Active Filters Tags -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if (request('q'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('metode_penerimaan'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Metode: {{ ucfirst(str_replace('_', ' ', request('metode_penerimaan'))) }}
                                <button onclick="removeFilter('metode_penerimaan')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('jenis_zakat_id'))
                            @php
                                $jenisZakat = ($jenisZakatList ?? collect())->firstWhere('id', request('jenis_zakat_id'));
                            @endphp
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Jenis: {{ $jenisZakat?->nama ?? 'N/A' }}
                                <button onclick="removeFilter('jenis_zakat_id')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('periode'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Periode: {{ \Carbon\Carbon::parse(request('periode'))->format('F Y') }}
                                <button onclick="removeFilter('periode')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filterPanel" class="px-6 py-4 border-b border-gray-100 bg-green-50/30 hidden">
                <form method="GET" action="{{ route('pemantauan-transaksi.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        <!-- Search Field -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Transaksi</label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari nama, no transaksi..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Penerimaan</label>
                            <select name="metode_penerimaan"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Fidyah</label>
                            <select name="fidyah_tipe"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua</option>
                                <option value="mentah" {{ request('fidyah_tipe') == 'mentah' ? 'selected' : '' }}>Mentah
                                </option>
                                <option value="matang" {{ request('fidyah_tipe') == 'matang' ? 'selected' : '' }}>Matang
                                </option>
                                <option value="tunai" {{ request('fidyah_tipe') == 'tunai' ? 'selected' : '' }}>Tunai
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status Konfirmasi (Daring)</label>
                            <select name="konfirmasi_status"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status Penjemputan
                                (Dijemput)</label>
                            <select name="status_penjemputan"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua</option>
                                <option value="menunggu"
                                    {{ request('status_penjemputan') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diterima"
                                    {{ request('status_penjemputan') == 'diterima' ? 'selected' : '' }}>Diterima Amil
                                </option>
                                <option value="dalam_perjalanan"
                                    {{ request('status_penjemputan') == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam
                                    Perjalanan</option>
                                <option value="sampai_lokasi"
                                    {{ request('status_penjemputan') == 'sampai_lokasi' ? 'selected' : '' }}>Sampai Lokasi
                                </option>
                                <option value="selesai"
                                    {{ request('status_penjemputan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua</option>
                                <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="transfer"
                                    {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS
                                </option>
                                <option value="beras" {{ request('metode_pembayaran') == 'beras' ? 'selected' : '' }}>
                                    Beras</option>
                                <option value="makanan_matang"
                                    {{ request('metode_pembayaran') == 'makanan_matang' ? 'selected' : '' }}>Makanan Matang
                                </option>
                                <option value="bahan_mentah"
                                    {{ request('metode_pembayaran') == 'bahan_mentah' ? 'selected' : '' }}>Bahan Mentah
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Periode (Bulan)</label>
                            <input type="month" name="periode" value="{{ request('periode') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                            <select name="tahun"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>
                    </div>

                    <!-- Tombol di ujung kanan -->
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" id="closeFilterPanelBtn"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                        @if (request()->hasAny([
                                'q',
                                'jenis_zakat_id',
                                'metode_pembayaran',
                                'status',
                                'metode_penerimaan',
                                'konfirmasi_status',
                                'status_penjemputan',
                                'start_date',
                                'end_date',
                                'periode',
                                'fidyah_tipe',
                            ]))
                            <a href="{{ route('pemantauan-transaksi.index') }}"
                                class="px-4 py-2 text-gray-500 hover:text-red-600 text-sm font-medium transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($transaksis->count() > 0)

                {{-- ── Desktop View dengan Expandable Table ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th
                                    class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Muzakki & Transaksi</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($transaksis as $trx)
                                @php
                                    $menungguKonfirmasi =
                                        $trx->metode_penerimaan === 'daring' &&
                                        $trx->konfirmasi_status === 'menunggu_konfirmasi';
                                    $isPending = $trx->status === 'pending';
                                    $isFidyah = !is_null($trx->fidyah_tipe);

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

                                    // Badge untuk status
                                    $statusBadge = match ($trx->status) {
                                        'verified'
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Verified</span>',
                                        'pending'
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                                        'rejected'
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>',
                                        default
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' .
                                            ucfirst($trx->status) .
                                            '</span>',
                                    };

                                    // Badge untuk konfirmasi (daring)
                                    $konfirmasiBadge = '';
                                    if ($trx->metode_penerimaan === 'daring' && $trx->konfirmasi_status) {
                                        $konfirmasiBadge = match ($trx->konfirmasi_status) {
                                            'menunggu_konfirmasi'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Menunggu Konfirmasi</span>',
                                            'dikonfirmasi'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">Dikonfirmasi</span>',
                                            'ditolak'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-rose-100 text-rose-800">Ditolak</span>',
                                            default => '',
                                        };
                                    }

                                    // Badge untuk status penjemputan (dijemput)
                                    $penjemputanBadge = '';
                                    if ($trx->metode_penerimaan === 'dijemput' && $trx->status_penjemputan) {
                                        $penjemputanBadge = match ($trx->status_penjemputan) {
                                            'menunggu'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Menunggu</span>',
                                            'diterima'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Diterima Amil</span>',
                                            'dalam_perjalanan'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">Dalam Perjalanan</span>',
                                            'sampai_lokasi'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Sampai Lokasi</span>',
                                            'selesai'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Selesai</span>',
                                            default => '',
                                        };
                                    }

                                    // Badge untuk fidyah
                                    $fidyahBadge = '';
                                    if ($isFidyah) {
                                        $fidyahBadge = match ($trx->fidyah_tipe) {
                                            'mentah'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Fidyah Mentah</span>',
                                            'matang'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Fidyah Matang</span>',
                                            'tunai'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">Fidyah Tunai</span>',
                                            default => '',
                                        };
                                    }

                                    // Badge untuk metode pembayaran khusus
                                    $metodeKhususBadge = '';
                                    if (
                                        in_array($trx->metode_pembayaran, ['beras', 'makanan_matang', 'bahan_mentah'])
                                    ) {
                                        $metodeKhususBadge = match ($trx->metode_pembayaran) {
                                            'beras'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Beras</span>',
                                            'makanan_matang'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Makanan Matang</span>',
                                            'bahan_mentah'
                                                => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Bahan Mentah</span>',
                                            default => '',
                                        };
                                    }

                                    // Warna metode penerimaan
                                    $warnaMetode = match ($trx->metode_penerimaan) {
                                        'datang_langsung' => 'bg-blue-100 text-blue-800',
                                        'dijemput' => 'bg-amber-100 text-amber-800',
                                        'daring' => 'bg-purple-100 text-purple-800',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 group cursor-pointer expandable-row
                                {{ $menungguKonfirmasi || $isPending ? 'bg-yellow-50/30' : '' }}
                                {{ $isFidyah ? 'bg-amber-50/20' : '' }}"
                                    data-target="detail-{{ $trx->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                {{ $trx->muzakki_nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                                @if ($trx->waktu_transaksi)
                                                    &middot;
                                                    {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}
                                                @endif

                                                {{-- Tampilkan jumlah sesuai tipe --}}
                                                @if ($isFidyah && $trx->fidyah_tipe == 'mentah' && $trx->fidyah_total_berat_kg > 0)
                                                    &middot; <span
                                                        class="font-semibold text-amber-600">{{ $trx->fidyah_total_berat_kg }}
                                                        kg</span>
                                                @elseif ($isFidyah && $trx->fidyah_tipe == 'matang' && $trx->fidyah_jumlah_box > 0)
                                                    &middot; <span
                                                        class="font-semibold text-orange-600">{{ $trx->fidyah_jumlah_box }}
                                                        box</span>
                                                @elseif ($trx->jumlah > 0)
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
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $statusBadge !!}
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                                    {{ ucfirst(str_replace('_', ' ', $trx->metode_penerimaan)) }}
                                                </span>
                                                @if ($fidyahBadge)
                                                    {!! $fidyahBadge !!}
                                                @endif
                                                @if ($metodeKhususBadge)
                                                    {!! $metodeKhususBadge !!}
                                                @endif
                                                @if ($trx->metode_penerimaan === 'daring' && $trx->konfirmasi_status === 'menunggu_konfirmasi')
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                        Perlu Konfirmasi
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Detail -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('pemantauan-transaksi.show', $trx->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
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
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris Expandable (Detail) --}}
                                <tr id="detail-{{ $trx->uuid }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-gray-50/30"></td>
                                    <td class="px-6 py-4 align-top bg-gray-50/30" colspan="2">
                                        <div class="space-y-3">
                                            <div>
                                                <h4
                                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                                    Informasi Lengkap</h4>
                                                <div class="bg-white rounded-lg p-3 border border-gray-100 space-y-2">
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <span class="text-xs text-gray-500">No. Transaksi</span>
                                                            <p class="text-sm font-mono text-gray-900 mt-0.5">
                                                                {{ $trx->no_transaksi }}</p>
                                                        </div>
                                                        @if ($trx->muzakki_telepon)
                                                            <div>
                                                                <span class="text-xs text-gray-500">Telepon</span>
                                                                <p class="text-sm text-gray-900 mt-0.5">
                                                                    {{ $trx->muzakki_telepon }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->jenisZakat)
                                                            <div>
                                                                <span class="text-xs text-gray-500">Jenis Zakat</span>
                                                                <p class="text-sm font-medium text-gray-900 mt-0.5">
                                                                    {{ $trx->jenisZakat->nama }}
                                                                    @if ($trx->tipeZakat)
                                                                        <span
                                                                            class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <span class="text-xs text-gray-500">Metode Penerimaan</span>
                                                            <p class="text-sm text-gray-900 mt-0.5">
                                                                <span
                                                                    class="inline-block px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $trx->metode_penerimaan)) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($isFidyah || $trx->jumlah > 0 || $trx->jumlah_beras_kg > 0)
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                                        Detail Pembayaran</h4>
                                                    <div class="bg-white rounded-lg p-3 border border-gray-100 space-y-2">
                                                        {{-- Fidyah Mentah --}}
                                                        @if ($isFidyah && $trx->fidyah_tipe == 'mentah')
                                                            <div>
                                                                <span class="text-xs text-gray-500">Fidyah Bahan Mentah</span>
                                                                <p class="text-sm font-semibold text-amber-600 mt-0.5">
                                                                    {{ $trx->fidyah_jumlah_hari }} hari ×
                                                                    {{ $trx->fidyah_berat_per_hari_gram }} gram
                                                                </p>
                                                                <p class="text-sm text-gray-700 mt-0.5">
                                                                    Total: {{ $trx->fidyah_total_berat_kg }} kg
                                                                    ({{ $trx->fidyah_nama_bahan }})
                                                                </p>
                                                            </div>
                                                        @elseif ($isFidyah && $trx->fidyah_tipe == 'matang')
                                                            <div>
                                                                <span class="text-xs text-gray-500">Fidyah Makanan Matang</span>
                                                                <p class="text-sm font-semibold text-orange-600 mt-0.5">
                                                                    {{ $trx->fidyah_jumlah_hari }} hari ×
                                                                    {{ $trx->fidyah_jumlah_box }} box
                                                                </p>
                                                                <p class="text-sm text-gray-700 mt-0.5">
                                                                    Menu: {{ $trx->fidyah_menu_makanan ?? '-' }}
                                                                </p>
                                                            </div>
                                                        @elseif ($isFidyah && $trx->fidyah_tipe == 'tunai')
                                                            <div>
                                                                <span class="text-xs text-gray-500">Fidyah Tunai</span>
                                                                <p class="text-sm font-semibold text-emerald-600 mt-0.5">
                                                                    {{ $trx->fidyah_jumlah_hari }} hari × Rp
                                                                    {{ number_format($trx->jumlah / $trx->fidyah_jumlah_hari, 0, ',', '.') }}
                                                                </p>
                                                                <p class="text-sm text-gray-700 mt-0.5">
                                                                    Total: Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                                </p>
                                                            </div>
                                                        @elseif ($trx->jumlah > 0)
                                                            <div>
                                                                <span class="text-xs text-gray-500">Jumlah (Uang)</span>
                                                                <p class="text-sm font-semibold text-green-600 mt-0.5">
                                                                    Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if ($trx->jumlah_beras_kg > 0)
                                                            <div>
                                                                <span class="text-xs text-gray-500">Jumlah (Beras)</span>
                                                                <p class="text-sm font-semibold text-amber-600 mt-0.5">
                                                                    {{ $trx->jumlah_beras_kg }} kg
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($trx->verified_at || $konfirmasiBadge || $penjemputanBadge)
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                                        Status & Verifikasi</h4>
                                                    <div class="bg-white rounded-lg p-3 border border-gray-100 space-y-2">
                                                        @if ($konfirmasiBadge)
                                                            <div>
                                                                <span class="text-xs text-gray-500 block mb-1">Status
                                                                    Konfirmasi</span>
                                                                {!! $konfirmasiBadge !!}
                                                            </div>
                                                        @endif
                                                        @if ($penjemputanBadge)
                                                            <div>
                                                                <span class="text-xs text-gray-500 block mb-1">Status
                                                                    Penjemputan</span>
                                                                {!! $penjemputanBadge !!}
                                                            </div>
                                                        @endif
                                                        @if ($trx->verified_at)
                                                            <div>
                                                                <span class="text-xs text-gray-500">Diverifikasi Pada</span>
                                                                <p class="text-sm text-gray-900 mt-0.5">
                                                                    {{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}
                                                                </p>
                                                                @if ($trx->amil)
                                                                    <p class="text-xs text-gray-500 mt-0.5">Oleh:
                                                                        {{ $trx->amil->nama_lengkap }}</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── Mobile View dengan Expandable Cards ── --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($transaksis as $trx)
                        @php
                            $menungguKonfirmasi =
                                $trx->metode_penerimaan === 'daring' &&
                                $trx->konfirmasi_status === 'menunggu_konfirmasi';
                            $isPending = $trx->status === 'pending';
                            $isFidyah = !is_null($trx->fidyah_tipe);

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

                            $statusBadge = match ($trx->status) {
                                'verified'
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Verified</span>',
                                'pending'
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                                'rejected'
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>',
                                default
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' .
                                    ucfirst($trx->status) .
                                    '</span>',
                            };

                            $warnaMetode = match ($trx->metode_penerimaan) {
                                'datang_langsung' => 'bg-blue-100 text-blue-800',
                                'dijemput' => 'bg-amber-100 text-amber-800',
                                'daring' => 'bg-purple-100 text-purple-800',
                                default => 'bg-gray-100 text-gray-600',
                            };

                            // Badge fidyah mobile
                            $fidyahBadgeMobile = '';
                            if ($isFidyah) {
                                $fidyahBadgeMobile = match ($trx->fidyah_tipe) {
                                    'mentah'
                                        => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Fidyah Mentah</span>',
                                    'matang'
                                        => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Fidyah Matang</span>',
                                    'tunai'
                                        => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">Fidyah Tunai</span>',
                                    default => '',
                                };
                            }
                        @endphp
                        <div
                            class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 {{ $menungguKonfirmasi || $isPending ? 'bg-yellow-50/30' : '' }} {{ $isFidyah ? 'bg-amber-50/20' : '' }}">
                            <div class="expandable-row-mobile cursor-pointer"
                                data-target="detail-mobile-{{ $trx->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <h3 class="text-sm font-semibold text-gray-800 break-words flex-1">
                                                {{ $trx->muzakki_nama ?? '-' }}</h3>
                                        </div>
                                        <p class="text-xs text-gray-400 ml-6">{{ $trx->no_transaksi }}</p>
                                        <div class="flex items-center gap-2 mt-2 ml-6 flex-wrap">
                                            {!! $statusBadge !!}
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full {{ $warnaMetode }}">
                                                {{ ucfirst(str_replace('_', ' ', $trx->metode_penerimaan)) }}
                                            </span>
                                            {!! $fidyahBadgeMobile !!}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Detail -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('pemantauan-transaksi.show', $trx->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Detail
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $trx->uuid }}"
                                class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                                            @if ($trx->waktu_transaksi)
                                                {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    @if ($trx->muzakki_telepon)
                                        <div>
                                            <p class="text-xs text-gray-500">Telepon</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trx->muzakki_telepon }}</p>
                                        </div>
                                    @endif
                                    @if ($trx->jenisZakat)
                                        <div>
                                            <p class="text-xs text-gray-500">Jenis Zakat</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $trx->jenisZakat->nama }}
                                                @if ($trx->tipeZakat)
                                                    <span class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                                                @endif
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Detail jumlah --}}
                                    @if ($isFidyah && $trx->fidyah_tipe == 'mentah')
                                        <div>
                                            <p class="text-xs text-gray-500">Fidyah Mentah</p>
                                            <p class="text-sm text-gray-900">{{ $trx->fidyah_jumlah_hari }} hari</p>
                                            <p class="text-sm text-amber-600">{{ $trx->fidyah_total_berat_kg }} kg
                                                ({{ $trx->fidyah_nama_bahan }})
                                            </p>
                                        </div>
                                    @elseif($isFidyah && $trx->fidyah_tipe == 'matang')
                                        <div>
                                            <p class="text-xs text-gray-500">Fidyah Matang</p>
                                            <p class="text-sm text-gray-900">{{ $trx->fidyah_jumlah_hari }} hari</p>
                                            <p class="text-sm text-orange-600">{{ $trx->fidyah_jumlah_box }} box</p>
                                        </div>
                                    @elseif($isFidyah && $trx->fidyah_tipe == 'tunai')
                                        <div>
                                            <p class="text-xs text-gray-500">Fidyah Tunai</p>
                                            <p class="text-sm font-semibold text-emerald-600">Rp
                                                {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                        </div>
                                    @elseif($trx->jumlah > 0)
                                        <div>
                                            <p class="text-xs text-gray-500">Jumlah</p>
                                            <p class="text-sm font-semibold text-green-600">Rp
                                                {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                        </div>
                                    @endif

                                    @if ($trx->jumlah_beras_kg > 0)
                                        <div>
                                            <p class="text-xs text-gray-500">Beras</p>
                                            <p class="text-sm font-semibold text-amber-600">
                                                {{ $trx->jumlah_beras_kg }} kg</p>
                                        </div>
                                    @endif

                                    @if ($trx->verified_at)
                                        <div class="pt-2 text-xs text-gray-400">
                                            <p>Diverifikasi:
                                                {{ \Carbon\Carbon::parse($trx->verified_at)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($transaksis->hasPages())
                    <div class="px-6 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $transaksis->withQueryString()->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
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

                    @if (request()->hasAny([
                            'q',
                            'jenis_zakat_id',
                            'metode_pembayaran',
                            'status',
                            'metode_penerimaan',
                            'konfirmasi_status',
                            'status_penjemputan',
                            'start_date',
                            'end_date',
                            'periode',
                            'fidyah_tipe',
                        ]))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('pemantauan-transaksi.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data transaksi</p>
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

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scale-in {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }

        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter Panel
            const filterButton = document.getElementById('filterButton');
            const filterPanel = document.getElementById('filterPanel');
            const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

            if (filterButton && filterPanel) {
                filterButton.addEventListener('click', function() {
                    filterPanel.classList.toggle('hidden');
                });
            }

            if (closeFilterPanelBtn) {
                closeFilterPanelBtn.addEventListener('click', function() {
                    filterPanel.classList.add('hidden');
                });
            }

            // Desktop Expandable row
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a')) return;

                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon');

                    if (targetRow) {
                        targetRow.classList.toggle('hidden');
                        if (icon) {
                            icon.classList.toggle('rotate-90');
                        }
                    }
                });
            });

            // Mobile Expandable Cards
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a')) return;

                    const targetId = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon-mobile');

                    if (targetContent) {
                        targetContent.classList.toggle('hidden');
                        if (icon) {
                            icon.classList.toggle('rotate-180');
                        }
                    }
                });
            });
        });

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        function toggleStatsMobile() {
            var panel = document.getElementById('stats-mobile-panel');
            var extra = document.getElementById('stats-mobile-extra');
            var chevron = document.getElementById('stats-chevron');
            var isHidden = panel.classList.contains('hidden');
            panel.classList.toggle('hidden', !isHidden);
            if (extra) extra.classList.toggle('hidden', !isHidden);
            chevron.classList.toggle('rotate-180', isHidden);
        }
    </script>
@endpush