{{-- resources/views/muzakki/riwayat-transaksi/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Transaksi Zakat Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Card Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Riwayat</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $transaksis->total() }} transaksi</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Tombol Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Filter
                            </span>
                            @if (request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                                <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-primary text-white text-[10px] font-bold">
                                    {{ collect(['status','jenis_zakat_id','metode_penerimaan','start_date','end_date'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
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
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Cari
                                </span>
                            </button>
                            <form method="GET" action="{{ route('riwayat-transaksi-muzakki.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date'] as $f)
                                    @if (request($f))
                                        <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari no. transaksi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('riwayat-transaksi-muzakki.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Status</option>
                                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode</label>
                            <select name="metode_penerimaan" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Metode</option>
                                <option value="daring"   {{ request('metode_penerimaan') == 'daring'   ? 'selected' : '' }}>Daring (Transfer/QRIS)</option>
                                <option value="dijemput" {{ request('metode_penerimaan') == 'dijemput' ? 'selected' : '' }}>Dijemput Amil</option>
                                <option value="langsung" {{ request('metode_penerimaan') == 'langsung' ? 'selected' : '' }}>Datang Langsung</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
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
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    onchange="this.form.submit()"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sampai</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    onchange="this.form.submit()"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            </div>
                        </div>
                    </div>
                    @if (request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('riwayat-transaksi-muzakki.index', request('q') ? ['q' => request('q')] : []) }}"
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

                {{-- Info Filter Aktif --}}
                @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                    <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                            @if (request('q'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Pencarian: "{{ request('q') }}"
                                    <button type="button" onclick="removeFilter('q')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if (request('status'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Status: {{ ucfirst(request('status')) }}
                                    <button type="button" onclick="removeFilter('status')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if (request('metode_penerimaan'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Metode: {{ ucfirst(request('metode_penerimaan')) }}
                                    <button type="button" onclick="removeFilter('metode_penerimaan')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if (request('jenis_zakat_id'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Jenis: {{ $jenisZakatList->find(request('jenis_zakat_id'))->nama ?? 'Unknown' }}
                                    <button type="button" onclick="removeFilter('jenis_zakat_id')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if (request('start_date') || request('end_date'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Tanggal: {{ request('start_date') ?? '...' }} – {{ request('end_date') ?? '...' }}
                                    <button type="button" onclick="removeFilters(['start_date','end_date'])" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ── Desktop Table (Expandable Rows) ── --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis & Metode</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaksis as $trx)
                                @php
                                    $isPending  = $trx->status === 'pending';
                                    $isVerified = $trx->status === 'verified';
                                    $isRejected = $trx->status === 'rejected';
                                    $isDaring   = $trx->metode_penerimaan === 'daring';
                                    $isDijemput = $trx->metode_penerimaan === 'dijemput';
                                    $isLangsung = $trx->metode_penerimaan === 'langsung';

                                    $hasNamaJiwa  = false;
                                    $namaJiwaList = [];
                                    if (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                        $hasNamaJiwa  = true;
                                        $namaJiwaList = $trx->nama_jiwa_json;
                                    } elseif (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                        $hasNamaJiwa  = true;
                                        $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                                    }
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row
                                    {{ $isPending  ? 'bg-amber-50/40'  : '' }}
                                    {{ $isRejected ? 'bg-red-50/40'    : '' }}"
                                    data-target="detail-{{ $trx->id }}">
                                    <td class="px-4 py-4">
                                        <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </td>

                                    {{-- No. Transaksi & Tanggal --}}
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-mono font-medium text-gray-800">{{ $trx->no_transaksi }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $trx->tanggal_transaksi->format('d M Y') }}</p>
                                        @if ($trx->programZakat)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $trx->programZakat->nama_program }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</p>
                                    </td>

                                    {{-- Jenis & Metode --}}
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                @if ($trx->jenisZakat)
                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->jenisZakat->nama }}</p>
                                                @else
                                                    <span class="text-xs text-gray-400">—</span>
                                                @endif
                                                @if ($hasNamaJiwa)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                        {{ count($namaJiwaList) }} jiwa
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($trx->tipeZakat)
                                                <p class="text-xs text-gray-500">{{ $trx->tipeZakat->nama }}</p>
                                            @endif
                                            <div>
                                                @if ($isDaring)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Daring</span>
                                                    @if ($trx->metode_pembayaran)
                                                        <span class="text-xs text-gray-400 ml-1 uppercase">{{ $trx->metode_pembayaran }}</span>
                                                    @endif
                                                @elseif ($isDijemput)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Dijemput</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Langsung</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Jumlah --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if ($trx->jumlah > 0)
                                            <p class="text-sm font-semibold text-gray-900">{{ $trx->jumlah_formatted }}</p>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                        @if ($trx->jumlah_infaq > 0)
                                            <p class="text-xs text-amber-600 mt-0.5">+{{ $trx->jumlah_infaq_formatted }} infaq</p>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 text-center">
                                        {!! $trx->status_badge !!}
                                    </td>
                                </tr>

                                {{-- Expandable Detail Row --}}
                                <tr id="detail-{{ $trx->id }}" class="hidden expandable-content">
                                    <td colspan="5" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                    {{-- Kolom 1: Info Transaksi --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Info Transaksi</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">No. Transaksi</p>
                                                                    <p class="text-sm font-mono font-medium text-gray-900">{{ $trx->no_transaksi }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Tanggal & Waktu</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->tanggal_transaksi->format('d F Y') }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($trx->programZakat)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Program Zakat</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->programZakat->nama_program }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Zakat & Pembayaran --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Zakat & Pembayaran</h4>
                                                        <div class="space-y-3">
                                                            @if ($trx->jenisZakat)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Jenis Zakat</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $trx->jenisZakat->nama }}</p>
                                                                    @if ($trx->tipeZakat)
                                                                        <p class="text-xs text-gray-500">{{ $trx->tipeZakat->nama }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @endif

                                                            @if ($hasNamaJiwa)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Jiwa Zakat Fitrah ({{ count($namaJiwaList) }})</p>
                                                                    <div class="mt-1 space-y-0.5">
                                                                        @foreach ($namaJiwaList as $jiwa)
                                                                            <p class="text-xs text-gray-700">• {{ $jiwa }}</p>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Metode Pembayaran</p>
                                                                    <p class="text-sm font-medium text-gray-900">
                                                                        @if ($isDaring) Daring (Transfer/QRIS)
                                                                        @elseif ($isDijemput) Dijemput Amil
                                                                        @else Datang Langsung
                                                                        @endif
                                                                    </p>
                                                                    @if ($isDaring && $trx->metode_pembayaran)
                                                                        <p class="text-xs text-gray-500 uppercase">via {{ $trx->metode_pembayaran }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Nominal & Status --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Nominal & Status</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Jumlah Zakat</p>
                                                                    <p class="text-sm font-bold text-gray-900">
                                                                        @if ($trx->jumlah > 0) {{ $trx->jumlah_formatted }}
                                                                        @else <span class="font-normal text-gray-400">—</span>
                                                                        @endif
                                                                    </p>
                                                                    @if ($trx->jumlah_infaq > 0)
                                                                        <p class="text-xs text-amber-600 mt-0.5">+{{ $trx->jumlah_infaq_formatted }} infaq</p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Status</p>
                                                                    <div class="mt-1">{!! $trx->status_badge !!}</div>
                                                                </div>
                                                            </div>

                                                            @if ($isRejected && $trx->alasan_penolakan)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Alasan Penolakan</p>
                                                                    <p class="text-xs text-red-700 mt-0.5">{{ $trx->alasan_penolakan }}</p>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            @if ($trx->catatan_konfirmasi)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-blue-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Catatan Amil</p>
                                                                    <p class="text-xs text-blue-700 mt-0.5">{{ $trx->catatan_konfirmasi }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>

                                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                                            <div class="text-xs text-gray-500 space-y-1">
                                                                <div>Dibuat: {{ $trx->created_at->format('d/m/Y H:i') }}</div>
                                                                @if ($trx->updated_at != $trx->created_at)
                                                                    <div>Diperbarui: {{ $trx->updated_at->format('d/m/Y H:i') }}</div>
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

                {{-- ── Mobile Cards (Expandable) ── --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($transaksis as $trx)
                        @php
                            $isPending  = $trx->status === 'pending';
                            $isRejected = $trx->status === 'rejected';
                            $isDaring   = $trx->metode_penerimaan === 'daring';
                            $isDijemput = $trx->metode_penerimaan === 'dijemput';

                            $hasNamaJiwa  = false;
                            $namaJiwaList = [];
                            if (!empty($trx->nama_jiwa_json) && is_array($trx->nama_jiwa_json)) {
                                $hasNamaJiwa  = true;
                                $namaJiwaList = $trx->nama_jiwa_json;
                            } elseif (!empty($trx->dataZakatFitrah['nama_jiwa'])) {
                                $hasNamaJiwa  = true;
                                $namaJiwaList = $trx->dataZakatFitrah['nama_jiwa'];
                            }
                        @endphp

                        <div class="expandable-card">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                                {{ $isPending  ? 'bg-amber-50/30'  : '' }}
                                {{ $isRejected ? 'bg-red-50/30'    : '' }}"
                                data-target="detail-mobile-{{ $trx->id }}">

                                {{-- Baris pertama: No. transaksi + chevron --}}
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-mono text-gray-500">{{ $trx->no_transaksi }}</p>
                                    <div class="flex items-center gap-2">
                                        {!! $trx->status_badge !!}
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Baris kedua: Jenis zakat + jumlah --}}
                                <div class="flex items-center justify-between mt-2">
                                    <div>
                                        @if ($trx->jenisZakat)
                                            <p class="text-sm font-semibold text-gray-900">{{ $trx->jenisZakat->nama }}</p>
                                            @if ($trx->tipeZakat)
                                                <p class="text-xs text-gray-400">{{ $trx->tipeZakat->nama }}</p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-500">—</p>
                                        @endif
                                        @if ($hasNamaJiwa)
                                            <p class="text-xs text-blue-500 mt-0.5">{{ count($namaJiwaList) }} jiwa</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if ($trx->jumlah > 0)
                                            <p class="text-sm font-bold text-gray-900">{{ $trx->jumlah_formatted }}</p>
                                        @endif
                                        @if ($trx->jumlah_infaq > 0)
                                            <p class="text-xs text-amber-600">+{{ $trx->jumlah_infaq_formatted }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Baris ketiga: Tanggal + metode --}}
                                <div class="flex items-center gap-3 mt-2 flex-wrap">
                                    <span class="text-xs text-gray-400">{{ $trx->tanggal_transaksi->format('d M Y') }}</span>
                                    @if ($isDaring)
                                        <span class="px-1.5 py-0.5 rounded text-xs bg-indigo-50 text-indigo-600">Daring</span>
                                    @elseif ($isDijemput)
                                        <span class="px-1.5 py-0.5 rounded text-xs bg-orange-50 text-orange-600">Dijemput</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">Langsung</span>
                                    @endif
                                    @if ($isDaring && $trx->metode_pembayaran)
                                        <span class="text-xs text-gray-400 uppercase">{{ $trx->metode_pembayaran }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $trx->id }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">

                                        {{-- Info Transaksi --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Info Transaksi</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $trx->tanggal_transaksi->format('d F Y') }}, {{ $trx->tanggal_transaksi->format('H:i') }} WIB</span>
                                                </div>
                                                @if ($trx->programZakat)
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $trx->programZakat->nama_program }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Jiwa Zakat Fitrah --}}
                                        @if ($hasNamaJiwa)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Jiwa Zakat Fitrah</h4>
                                            <div class="space-y-1">
                                                @foreach ($namaJiwaList as $jiwa)
                                                    <p class="text-xs text-gray-700">• {{ $jiwa }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        {{-- Metode Pembayaran --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Metode Pembayaran</h4>
                                            <p class="text-sm text-gray-700">
                                                @if ($isDaring) Daring (Transfer/QRIS)
                                                @elseif ($isDijemput) Dijemput Amil
                                                @else Datang Langsung
                                                @endif
                                            </p>
                                            @if ($isDaring && $trx->metode_pembayaran)
                                                <p class="text-xs text-gray-500 uppercase mt-0.5">via {{ $trx->metode_pembayaran }}</p>
                                            @endif
                                        </div>

                                        {{-- Alasan penolakan --}}
                                        @if ($isRejected && $trx->alasan_penolakan)
                                            <div class="px-3 py-2 bg-red-50 border border-red-100 rounded-lg">
                                                <p class="text-xs text-red-700">
                                                    <span class="font-medium">Alasan penolakan:</span> {{ $trx->alasan_penolakan }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- Catatan amil --}}
                                        @if ($trx->catatan_konfirmasi)
                                            <div class="px-3 py-2 bg-blue-50 border border-blue-100 rounded-lg">
                                                <p class="text-xs text-blue-700">
                                                    <span class="font-medium">Catatan amil:</span> {{ $trx->catatan_konfirmasi }}
                                                </p>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($transaksis->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $transaksis->withQueryString()->links() }}
                    </div>
                @endif

            @else
                {{-- Empty State --}}
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if (request('q'))
                                Tidak ada transaksi yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada transaksi yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Transaksi</h3>
                        <p class="text-sm text-gray-500">Anda belum memiliki transaksi zakat apapun.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Desktop Expandable Rows ──
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('button[type="submit"]')) return;

                    const targetId  = this.getAttribute('data-target');
                    const targetRow = document.getElementById(targetId);
                    const icon      = this.querySelector('.expand-icon');

                    if (targetRow.classList.contains('hidden')) {
                        targetRow.classList.remove('hidden');
                        icon.classList.add('rotate-90');
                    } else {
                        targetRow.classList.add('hidden');
                        icon.classList.remove('rotate-90');
                    }
                });
            });

            // ── Mobile Expandable Cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('button[type="submit"]')) return;

                    const targetId      = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon          = this.querySelector('.expand-icon-mobile');

                    if (targetContent.classList.contains('hidden')) {
                        targetContent.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        targetContent.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                });
            });

        });

        // ── Toggle Filter Panel ──
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // ── Toggle Search Bar ──
        function toggleSearch() {
            const searchButton    = document.getElementById('search-button');
            const searchForm      = document.getElementById('search-form');
            const searchInput     = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');

            if (searchForm.classList.contains('hidden')) {
                searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput.focus(), 50);
            } else {
                const hasQuery = '{{ request('q') }}' !== '';
                if (!hasQuery) searchInput.value = '';
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        // ── Remove single filter chip ──
        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        // ── Remove multiple filter chips (e.g. start_date + end_date) ──
        function removeFilters(filterNames) {
            const url = new URL(window.location.href);
            filterNames.forEach(name => url.searchParams.delete(name));
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endpush