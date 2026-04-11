{{-- resources/views/amil/transaksi-penyaluran/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Transaksi Penyaluran Zakat')

@section('content')
    <div class="space-y-6">

        {{-- ── Alert: Ada transaksi disetujui menunggu konfirmasi ── --}}
        @if (isset($stats['total_disetujui']) && $stats['total_disetujui'] > 0)
            <div class="flex items-center gap-3 px-5 py-3 bg-blue-50 border border-blue-200 rounded-xl">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-blue-800">
                        {{ $stats['total_disetujui'] }} transaksi sudah disetujui, menunggu konfirmasi penyaluran
                    </p>
                    <p class="text-xs text-blue-600 mt-0.5">Konfirmasi setelah dana/barang sudah diserahkan ke mustahik</p>
                </div>
                <a href="{{ route('transaksi-penyaluran.index', ['status' => 'disetujui']) }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-all">
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
                        <h1 class="text-base font-semibold text-gray-800">Transaksi Penyaluran Zakat</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola penyaluran zakat kepada mustahik</p>
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
                        </button>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('transaksi-penyaluran.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </a>

                        <!-- DROPDOWN EXPORT -->
                        <div class="relative" id="importExportDropdown">
                            <button type="button" id="dropdownToggleBtn"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                                <svg class="w-3 h-3 ml-1 transition-transform duration-200" id="dropdownIcon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="dropdownMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden hidden z-20">
                                <div class="py-1">
                                    <a href="{{ route('transaksi-penyaluran.export.pdf', request()->query()) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Export PDF
                                    </a>
                                    <a href="{{ route('transaksi-penyaluran.export.excel', request()->query()) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
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
                            <span class="text-xs text-gray-500">Disalurkan:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($stats['total_disalurkan']) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($stats['total_draft'] + ($stats['total_disetujui'] ?? 0)) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <span class="text-xs text-gray-500">Disetujui:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($stats['total_disetujui'] ?? 0) }}</span>
                        </div>
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
                class="{{ request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('transaksi-penyaluran.index') }}" id="filter-form">
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
                                    placeholder="Cari no. transaksi / mustahik..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                    (Menunggu)</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui</option>
                                <option value="disalurkan" {{ request('status') == 'disalurkan' ? 'selected' : '' }}>
                                    Disalurkan</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Penyaluran</label>
                            <select name="metode_penyaluran"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Metode</option>
                                <option value="tunai" {{ request('metode_penyaluran') == 'tunai' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="transfer"
                                    {{ request('metode_penyaluran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="barang" {{ request('metode_penyaluran') == 'barang' ? 'selected' : '' }}>
                                    Barang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
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
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Periode (Bulan)</label>
                            <input type="month" name="periode" value="{{ request('periode') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']))
                            <a href="{{ route('transaksi-penyaluran.index') }}"
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
            @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']))
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
                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('metode_penyaluran'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Metode: {{ ucfirst(request('metode_penyaluran')) }}
                                <button onclick="removeFilter('metode_penyaluran')"
                                    class="hover:text-green-900 ml-1">×</button>
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">MUSTAHIK &amp;
                                    TRANSAKSI</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-28">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaksis as $transaksi)
                                @php
                                    $canEdit = $transaksi->bisa_diedit;
                                    $canDelete = $transaksi->bisa_dihapus;
                                    $canDisalurkan = $transaksi->bisa_disalurkan;
                                    $canApprove =
                                        auth()->user()->peran === 'admin_lembaga' && $transaksi->status === 'draft';
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $canDisalurkan ? 'bg-blue-50/30' : ($canApprove ? 'bg-yellow-50/30' : '') }}"
                                    data-target="detail-{{ $transaksi->uuid }}">
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
                                                {{ $transaksi->mustahik->nama_lengkap ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $transaksi->tanggal_penyaluran->format('d/m/Y') }}
                                                @if ($transaksi->jumlah > 0)
                                                    &middot; <span
                                                        class="font-semibold text-gray-700">{{ $transaksi->jumlah_formatted }}</span>
                                                @endif
                                                @if ($transaksi->metode_penyaluran === 'barang')
                                                    &middot; <span class="font-semibold text-orange-600">Barang</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                {!! $transaksi->status_badge !!}
                                                {!! $transaksi->metode_penyaluran_badge !!}
                                                @if ($canDisalurkan)
                                                    <span
                                                        class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                        Perlu Konfirmasi
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if ($canApprove)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        onclick="openApproveModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Setujui
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        onclick="openRejectModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Tolak
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($canDisalurkan)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        onclick="openDisalurkanModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Konfirmasi
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="relative group/tooltip">
                                                <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}"
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

                                            @if ($canEdit)
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Edit
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($canDelete)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        onclick="openDeleteModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Hapus
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $transaksi->uuid }}"
                                    class="hidden expandable-content border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi —
                                                    {{ $transaksi->mustahik->nama_lengkap ?? '-' }}</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Data Mustahik --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Data Mustahik</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $transaksi->mustahik->nama_lengkap ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Kategori</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $transaksi->kategoriMustahik->nama ?? '-' }}</p>
                                                        </div>
                                                        @if ($transaksi->mustahik->telepon)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Telepon</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $transaksi->mustahik->telepon }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Penyaluran --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Detail Penyaluran</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal Penyaluran</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $transaksi->tanggal_penyaluran->format('d F Y') }}</p>
                                                        </div>
                                                        @if ($transaksi->jenisZakat)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Jenis Zakat</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $transaksi->jenisZakat->nama }}</p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs text-gray-400">Jumlah</p>
                                                            <p class="text-sm font-semibold text-green-600">
                                                                {{ $transaksi->jumlah_formatted }}</p>
                                                        </div>
                                                        @if ($transaksi->metode_penyaluran === 'barang' && $transaksi->detail_barang)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Detail Barang</p>
                                                                <p class="text-sm text-gray-700">
                                                                    {{ $transaksi->detail_barang }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status & Penanggung Jawab --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Status &amp; Penanggung Jawab</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status</p>
                                                            <div class="mt-1">{!! $transaksi->status_badge !!}</div>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Metode</p>
                                                            <div class="mt-1">{!! $transaksi->metode_penyaluran_badge !!}</div>
                                                        </div>
                                                        @if ($transaksi->amil)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Amil</p>
                                                                <p class="text-sm font-medium text-gray-800">
                                                                    {{ $transaksi->amil->nama_lengkap }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($transaksi->status === 'dibatalkan' && $transaksi->alasan_pembatalan)
                                                            <div
                                                                class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                                <p class="text-xs text-red-600 font-medium">Alasan Ditolak:
                                                                </p>
                                                                <p class="text-xs text-red-700">
                                                                    {{ $transaksi->alasan_pembatalan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-2 border-t border-gray-200">
                                                <p class="text-xs text-gray-400">No. Transaksi: <span
                                                        class="font-medium text-gray-600">{{ $transaksi->no_transaksi }}</span>
                                                </p>
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
                    @foreach ($transaksis as $transaksi)
                        @php
                            $canEdit = $transaksi->bisa_diedit;
                            $canDelete = $transaksi->bisa_dihapus;
                            $canDisalurkan = $transaksi->bisa_disalurkan;
                            $canApprove = auth()->user()->peran === 'admin_lembaga' && $transaksi->status === 'draft';
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $canDisalurkan ? 'bg-blue-50/30' : ($canApprove ? 'bg-yellow-50/30' : '') }}"
                            data-target="detail-mobile-{{ $transaksi->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">
                                            {{ $transaksi->mustahik->nama_lengkap ?? '-' }}
                                        </h3>
                                        {!! $transaksi->status_badge !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span
                                            class="text-xs text-gray-500">{{ $transaksi->tanggal_penyaluran->format('d/m/Y') }}</span>
                                        @if ($transaksi->jumlah > 0)
                                            <span
                                                class="text-xs font-semibold text-gray-700">{{ $transaksi->jumlah_formatted }}</span>
                                        @endif
                                        {!! $transaksi->metode_penyaluran_badge !!}
                                    </div>
                                    @if ($canDisalurkan)
                                        <div class="mt-1">
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">Perlu
                                                Konfirmasi</span>
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
                            <div id="detail-mobile-{{ $transaksi->uuid }}"
                                class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data
                                            Mustahik</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Nama:</span>
                                                {{ $transaksi->mustahik->nama_lengkap ?? '-' }}</p>
                                            <p><span class="text-gray-500">Kategori:</span>
                                                {{ $transaksi->kategoriMustahik->nama ?? '-' }}</p>
                                            @if ($transaksi->mustahik->telepon)
                                                <p><span class="text-gray-500">Telepon:</span>
                                                    {{ $transaksi->mustahik->telepon }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Detail Penyaluran</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Tanggal:</span>
                                                {{ $transaksi->tanggal_penyaluran->format('d F Y') }}</p>
                                            @if ($transaksi->jenisZakat)
                                                <p><span class="text-gray-500">Jenis Zakat:</span>
                                                    {{ $transaksi->jenisZakat->nama }}</p>
                                            @endif
                                            <p><span class="text-gray-500">Jumlah:</span> <span
                                                    class="font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</span>
                                            </p>
                                            @if ($transaksi->metode_penyaluran === 'barang' && $transaksi->detail_barang)
                                                <p><span class="text-gray-500">Detail Barang:</span>
                                                    {{ $transaksi->detail_barang }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Status &amp; Penanggung Jawab</h4>
                                        <div class="space-y-1 text-sm">
                                            <div>{!! $transaksi->status_badge !!}</div>
                                            <div>{!! $transaksi->metode_penyaluran_badge !!}</div>
                                            @if ($transaksi->amil)
                                                <p><span class="text-gray-500">Amil:</span>
                                                    {{ $transaksi->amil->nama_lengkap }}</p>
                                            @endif
                                            @if ($transaksi->status === 'dibatalkan' && $transaksi->alasan_pembatalan)
                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                    <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                    <p class="text-xs text-red-700">{{ $transaksi->alasan_pembatalan }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="pt-2 flex items-center gap-2 flex-wrap">
                                        @if ($canApprove)
                                            <button type="button"
                                                onclick="openApproveModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-xs ml-1">Setujui</span>
                                            </button>
                                            <button type="button"
                                                onclick="openRejectModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="text-xs ml-1">Tolak</span>
                                            </button>
                                        @endif

                                        @if ($canDisalurkan)
                                            <button type="button"
                                                onclick="openDisalurkanModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-xs ml-1">Konfirmasi</span>
                                            </button>
                                        @endif

                                        <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}"
                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span class="text-xs ml-1">Detail</span>
                                        </a>

                                        @if ($canEdit)
                                            <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="text-xs ml-1">Edit</span>
                                            </a>
                                        @endif

                                        @if ($canDelete)
                                            <button type="button"
                                                onclick="openDeleteModal('{{ $transaksi->uuid }}', '{{ addslashes($transaksi->mustahik->nama_lengkap ?? '-') }}')"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="text-xs ml-1">Hapus</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($transaksis->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $transaksis->links() }}
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

                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penyaluran', 'start_date', 'end_date', 'periode']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('transaksi-penyaluran.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada transaksi penyaluran</p>
                        <a href="{{ route('transaksi-penyaluran.create') }}"
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

    {{-- ── Modal: Setujui ── --}}
    <div id="approve-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Setujui Penyaluran</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Setujui transaksi penyaluran untuk
                    "<span id="modal-approve-nama" class="font-semibold text-gray-700"></span>"?
                </p>
                <form method="POST" id="approve-form">
                    @csrf
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('approve-modal')"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-xl text-sm font-medium text-white transition-all">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal: Tolak ── --}}
    <div id="reject-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Tolak Penyaluran</h3>
                <p class="text-sm text-gray-500 mb-4 text-center">
                    Tolak transaksi penyaluran untuk
                    "<span id="modal-reject-nama" class="font-semibold text-gray-700"></span>"?
                </p>
                <form method="POST" id="reject-form">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_pembatalan" rows="3" required placeholder="Tuliskan alasan penolakan..."
                            class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('reject-modal')"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-xl text-sm font-medium text-white transition-all">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal: Konfirmasi Disalurkan ── --}}
    <div id="disalurkan-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Konfirmasi Penyaluran</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Konfirmasi bahwa dana/barang sudah diserahkan kepada
                    "<span id="modal-disalurkan-nama" class="font-semibold text-gray-700"></span>"?
                </p>
                <form method="POST" id="disalurkan-form">
                    @csrf
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('disalurkan-modal')"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-sm font-medium text-white transition-all">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal: Delete ── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Transaksi</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Hapus transaksi penyaluran untuk "<span id="modal-delete-nama"
                        class="font-semibold text-gray-700"></span>"?
                </p>
                <form method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('delete-modal')"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-xl text-sm font-medium text-white transition-all">
                            Hapus
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

            // ── Dropdown Import/Export ──
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

            // Tambahkan ini di dalam DOMContentLoaded, setelah bagian dropdown
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

            // ── Modal functions ──
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            window.closeModal = function(id) {
                document.getElementById(id).classList.add('hidden');
                document.body.style.overflow = '';
            };

            window.openApproveModal = function(uuid, nama) {
                document.getElementById('modal-approve-nama').textContent = nama;
                document.getElementById('approve-form').action = '/transaksi-penyaluran/' + uuid + '/approve';
                openModal('approve-modal');
            };

            window.openRejectModal = function(uuid, nama) {
                document.getElementById('modal-reject-nama').textContent = nama;
                document.getElementById('reject-form').action = '/transaksi-penyaluran/' + uuid + '/reject';
                const textarea = document.querySelector('#reject-form textarea');
                if (textarea) textarea.value = '';
                openModal('reject-modal');
            };

            window.openDisalurkanModal = function(uuid, nama) {
                document.getElementById('modal-disalurkan-nama').textContent = nama;
                document.getElementById('disalurkan-form').action = '/transaksi-penyaluran/' + uuid +
                    '/konfirmasi-disalurkan';
                openModal('disalurkan-modal');
            };

            window.openDeleteModal = function(uuid, nama) {
                document.getElementById('modal-delete-nama').textContent = nama;
                document.getElementById('delete-form').action = '/transaksi-penyaluran/' + uuid;
                openModal('delete-modal');
            };

            // Backdrop click menutup modal
            ['approve-modal', 'reject-modal', 'disalurkan-modal', 'delete-modal'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) {
                    el.addEventListener('click', function(e) {
                        if (e.target === this) closeModal(id);
                    });
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal('approve-modal');
                    closeModal('reject-modal');
                    closeModal('disalurkan-modal');
                    closeModal('delete-modal');
                }
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
