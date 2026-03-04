{{-- resources/views/muzakki/riwayat-transaksi/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Transaksi Zakat Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Page Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-slide-up">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Riwayat Transaksi Zakat</h1>
                <p class="text-sm text-gray-500 mt-1">Lihat seluruh riwayat pembayaran zakat Anda</p>
            </div>
            {{-- Shortcut ke form bayar zakat --}}
            <a href="{{ route('transaksi-daring-muzakki.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Bayar Zakat Baru
            </a>
        </div>

        {{-- ── Alert: Ada transaksi menunggu konfirmasi ── --}}
        @if ($stats['total_pending'] > 0)
            <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl animate-slide-up">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-amber-800">
                        {{ $stats['total_pending'] }} transaksi sedang menunggu konfirmasi amil
                    </p>
                    <p class="text-xs text-amber-600 mt-0.5">Amil sedang memverifikasi pembayaran Anda</p>
                </div>
            </div>
        @endif

        {{-- ── Statistics Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 animate-slide-up">

            {{-- Total Transaksi --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Total</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Terverifikasi --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Terverifikasi</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total_verified'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Menunggu --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Menunggu</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total_pending'], 0, ',', '.') }}</p>
                        @if ($stats['total_pending'] > 0)
                            <p class="text-xs text-amber-600">Proses konfirmasi</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Total Dibayar --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Total Dibayar</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">Terverifikasi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Card Header: Judul + Filter + Search ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Riwayat</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ $transaksis->total() }} transaksi ditemukan</p>
                    </div>

                    <div class="flex items-center gap-2">

                        {{-- Tombol Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                            @if (request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                                <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-primary text-white text-[10px] font-bold">
                                    {{ collect(['status','jenis_zakat_id','metode_penerimaan','start_date','end_date'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </button>

                        {{-- Search --}}
                        <form method="GET" action="{{ route('riwayat-transaksi-muzakki.index') }}" class="relative">
                            @foreach (['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date'] as $f)
                                @if (request($f))
                                    <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
                                @endif
                            @endforeach
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="search" name="q" value="{{ request('q') }}"
                                    placeholder="Cari no. transaksi..."
                                    class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all sm:w-56">
                            </div>
                        </form>

                        {{-- Reset semua filter --}}
                        @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                            <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
                                class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-lg transition-all">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']) ? '' : 'hidden' }} bg-gray-50 px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <form method="GET" action="{{ route('riwayat-transaksi-muzakki.index') }}">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Semua Status</option>
                                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode</label>
                            <select name="metode_penerimaan" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Semua Metode</option>
                                <option value="daring"   {{ request('metode_penerimaan') == 'daring'   ? 'selected' : '' }}>Daring (Transfer/QRIS)</option>
                                <option value="dijemput" {{ request('metode_penerimaan') == 'dijemput' ? 'selected' : '' }}>Dijemput Amil</option>
                                <option value="langsung" {{ request('metode_penerimaan') == 'langsung' ? 'selected' : '' }}>Datang Langsung</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                            <select name="jenis_zakat_id" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
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
                                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    onchange="this.form.submit()"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    onchange="this.form.submit()"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── Data ── --}}
            @if ($transaksis->count() > 0)

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Zakat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
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
                                <tr class="hover:bg-gray-50 transition-colors
                                    {{ $isPending  ? 'bg-amber-50/40'  : '' }}
                                    {{ $isRejected ? 'bg-red-50/40'    : '' }}">

                                    {{-- No. Transaksi --}}
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-mono font-medium text-gray-800">{{ $trx->no_transaksi }}</p>
                                            @if ($trx->programZakat)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $trx->programZakat->nama_program }}</p>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm text-gray-700">{{ $trx->tanggal_transaksi->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $trx->tanggal_transaksi->format('H:i') ?? '' }}</p>
                                    </td>

                                    {{-- Jenis Zakat --}}
                                    <td class="px-6 py-4">
                                        @if ($trx->jenisZakat)
                                            <p class="text-sm font-medium text-gray-800">{{ $trx->jenisZakat->nama }}</p>
                                            @if ($trx->tipeZakat)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $trx->tipeZakat->nama }}</p>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                        @if ($hasNamaJiwa)
                                            <p class="text-xs text-blue-500 mt-0.5">{{ count($namaJiwaList) }} jiwa</p>
                                        @endif
                                    </td>

                                    {{-- Metode --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($isDaring)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                Daring
                                            </span>
                                            @if ($trx->metode_pembayaran)
                                                <span class="block text-xs text-gray-400 mt-0.5 uppercase">{{ $trx->metode_pembayaran }}</span>
                                            @endif
                                        @elseif ($isDijemput)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                                Dijemput
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                Langsung
                                            </span>
                                        @endif
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
                                        <div class="flex flex-col items-center gap-1">
                                            {!! $trx->status_badge !!}
                                            @if ($isRejected && $trx->alasan_penolakan)
                                                <button type="button"
                                                    class="text-xs text-red-500 hover:underline show-rejection-reason"
                                                    data-reason="{{ $trx->alasan_penolakan }}">
                                                    Lihat alasan
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Baris alasan penolakan (tersembunyi, ditampilkan via JS) --}}
                                @if ($isRejected && $trx->alasan_penolakan)
                                    <tr id="reason-row-{{ $trx->id }}" class="hidden">
                                        <td colspan="6" class="px-6 py-2 bg-red-50">
                                            <p class="text-xs text-red-700">
                                                <span class="font-medium">Alasan penolakan:</span> {{ $trx->alasan_penolakan }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-100">
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

                        <div class="p-4 {{ $isPending ? 'bg-amber-50/30' : '' }} {{ $isRejected ? 'bg-red-50/30' : '' }}">

                            {{-- Baris pertama: No. transaksi + status --}}
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-xs font-mono text-gray-500">{{ $trx->no_transaksi }}</p>
                                {!! $trx->status_badge !!}
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
                                <span class="text-xs text-gray-400">
                                    {{ $trx->tanggal_transaksi->format('d M Y') }}
                                </span>
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

                            {{-- Alasan penolakan (jika ada) --}}
                            @if ($isRejected && $trx->alasan_penolakan)
                                <div class="mt-2 px-3 py-2 bg-red-50 border border-red-100 rounded-lg">
                                    <p class="text-xs text-red-700">
                                        <span class="font-medium">Alasan:</span> {{ $trx->alasan_penolakan }}
                                    </p>
                                </div>
                            @endif

                            {{-- Catatan konfirmasi (jika ada) --}}
                            @if ($trx->catatan_konfirmasi)
                                <div class="mt-2 px-3 py-2 bg-blue-50 border border-blue-100 rounded-lg">
                                    <p class="text-xs text-blue-700">
                                        <span class="font-medium">Catatan amil:</span> {{ $trx->catatan_konfirmasi }}
                                    </p>
                                </div>
                            @endif

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
                <div class="p-10 sm:p-14 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @if (request()->hasAny(['q', 'status', 'jenis_zakat_id', 'metode_penerimaan', 'start_date', 'end_date']))
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Tidak Ada Data</h3>
                        <p class="text-sm text-gray-500 mb-5">Tidak ada riwayat yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ route('riwayat-transaksi-muzakki.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    @else
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Belum Ada Riwayat Transaksi</h3>
                        <p class="text-sm text-gray-500 mb-5">Anda belum memiliki transaksi zakat apapun.</p>
                        <a href="{{ route('transaksi-daring-muzakki.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
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
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // Tampilkan alasan penolakan di desktop (inline di bawah baris)
        document.querySelectorAll('.show-rejection-reason').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const reason = this.dataset.reason;
                // Cari row berikutnya yang tersembunyi dan toggle
                const nextRow = this.closest('tr').nextElementSibling;
                if (nextRow) {
                    nextRow.classList.toggle('hidden');
                    this.textContent = nextRow.classList.contains('hidden') ? 'Lihat alasan' : 'Sembunyikan';
                }
            });
        });
    </script>
@endpush