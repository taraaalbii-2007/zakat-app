{{-- resources/views/amil/kas-harian/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kas Harian')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ===== ALERT: Kas belum dibuka hari ini ===== --}}
    @if($belumBukaKas)
        <div class="flex items-center gap-3 px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-xl animate-slide-up">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-yellow-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-yellow-800">Kas hari ini belum dibuka</p>
                <p class="text-xs text-yellow-600 mt-0.5">
                    Saldo awal: <span class="font-semibold">Rp {{ number_format($saldoAwalEstimasi, 0, ',', '.') }}</span>
                    (dari saldo akhir kemarin)
                </p>
            </div>
            <form action="{{ route('kas-harian.buka') }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-medium rounded-lg transition-all">
                    Buka Kas
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>
    @endif

    {{-- ===== ALERT: Kas sudah ditutup, bisa buka kembali ===== --}}
    @if(isset($kas) && $kas && $kas->isClosed && $tanggal->isToday())
        <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl animate-slide-up">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-blue-800">Kas hari ini sudah ditutup</p>
                <p class="text-xs text-blue-600 mt-0.5">
                    @if($kas->closed_at)
                        Ditutup pada {{ $kas->closed_at->format('H:i') }} — saldo akhir terkunci
                    @endif
                </p>
            </div>
            <form action="{{ route('kas-harian.buka-kembali', $kas->uuid) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit"
                    onclick="return confirm('Yakin ingin membuka kembali kas ini?')"
                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-all">
                    Buka Kembali
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>
    @endif

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl animate-slide-up">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="flex-1 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl animate-slide-up">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="flex-1 text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif
    @if(session('info'))
        <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl animate-slide-up">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="flex-1 text-sm font-medium text-blue-800">{{ session('info') }}</p>
        </div>
    @endif

    {{-- ===== BELUM BUKA KAS (STATE KOSONG) ===== --}}
    @if($belumBukaKas)
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="p-8 sm:p-12 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-yellow-100 mb-4">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Kas Hari Ini Belum Dibuka</h3>
            <p class="text-sm text-gray-500 mb-1">{{ $tanggal->translatedFormat('l, d F Y') }}</p>
            <p class="text-sm text-gray-500 mb-6">
                Saldo awal estimasi:
                <span class="font-semibold text-gray-800">Rp {{ number_format($saldoAwalEstimasi, 0, ',', '.') }}</span>
            </p>
            <form action="{{ route('kas-harian.buka') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    Buka Kas Hari Ini
                </button>
            </form>
        </div>
    </div>

    @elseif($kas)
    {{-- ===== KAS ADA: STATISTIK ===== --}}

{{-- Statistics Cards --}}
{{-- Toggle button khusus mobile --}}
<div class="sm:hidden">
    <button type="button" onclick="toggleStatsMobile()"
        class="w-full flex items-center justify-between px-4 py-2.5 bg-white rounded-xl border border-gray-100 shadow-sm text-sm font-medium text-gray-700 mb-3">
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

{{-- Container statistik (bisa toggle di mobile) --}}
<div id="stats-mobile-panel" class="hidden sm:block">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-slide-up">
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-1m6 1l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-1m0-1v1m0 10v1"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Saldo Awal</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $kas->saldo_awal_formatted }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Total Penerimaan</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $kas->total_penerimaan_formatted }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $kas->jumlah_transaksi_masuk }} transaksi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Total Penyaluran</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $kas->total_penyaluran_formatted }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $kas->jumlah_transaksi_keluar }} transaksi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Saldo Akhir</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $kas->saldo_akhir_formatted }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{!! $kas->status_badge !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- ===== MAIN CARD ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Kas Harian</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $tanggal->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                    {{-- Date Picker --}}
                    <form method="GET" action="{{ route('kas-harian.index') }}" class="flex items-center">
                        <input type="date"
                            name="tanggal"
                            value="{{ $tanggal->format('Y-m-d') }}"
                            max="{{ now()->format('Y-m-d') }}"
                            onchange="this.form.submit()"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                    </form>

                    {{-- Tutup / Simpan Catatan --}}
                    @if($kas->isOpen && $tanggal->isToday())
                        <button type="button" onclick="openCatatanModal()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Catatan</span>
                        </button>
                        <button type="button" onclick="openTutupKasModal()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Tutup Kas</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tab Switcher: Penerimaan / Penyaluran --}}
        <div class="px-4 sm:px-6 pt-3 border-b border-gray-200">
            <nav class="flex gap-0" id="kas-tabs">
                <button type="button" onclick="switchTab('penerimaan')" id="tab-penerimaan"
                    class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 border-primary text-primary transition-all">
                    Penerimaan
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">
                        {{ $transaksiPenerimaan->count() }}
                    </span>
                </button>
                <button type="button" onclick="switchTab('penyaluran')" id="tab-penyaluran"
                    class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all">
                    Penyaluran
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-orange-100 text-orange-700">
                        {{ $transaksiPenyaluran->count() }}
                    </span>
                </button>
            </nav>
        </div>

        {{-- ===== TAB: PENERIMAAN ===== --}}
        <div id="panel-penerimaan">
            @if($transaksiPenerimaan->isEmpty())
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Penerimaan</h3>
                    <p class="text-sm text-gray-500">Belum ada transaksi penerimaan pada tanggal ini</p>
                </div>
            @else

                {{-- Desktop View Penerimaan --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Muzakki &amp; Transaksi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaksiPenerimaan as $trx)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                data-target="detail-trx-in-{{ $trx->uuid }}">
                                <td class="px-4 py-4">
                                    <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $trx->created_at->format('H:i') }}
                                        &middot; {{ $trx->jenisZakat->nama ?? '-' }}
                                        @if($trx->tipeZakat)
                                            <span class="text-gray-400">· {{ $trx->tipeZakat->nama }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                        {!! $trx->metode_penerimaan_badge !!}
                                        {!! $trx->metode_pembayaran_badge !!}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-semibold text-green-700">{{ $trx->jumlah_formatted }}</span>
                                </td>
                               {{-- Ganti isi cell aksi --}}
<td class="px-6 py-4 text-center">
    <div class="flex items-center justify-center gap-2">
        {{-- Tombol Detail --}}
        <div class="relative group/tooltip">
            <a href="{{ route('kas-harian.show', $trx->uuid) }}"
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

{{-- Ganti expandable row penerimaan --}}
<tr id="detail-trx-in-{{ $trx->uuid }}" class="hidden expandable-content">
    {{-- Kolom pertama (icon expand) --}}
    <td class="px-4 py-4 bg-gray-50/30"></td>
    
    {{-- Kolom kedua dan ketiga digabung (colspan="2") agar sejajar dengan kolom MUZAKKI & TRANSAKSI --}}
    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
        <div class="space-y-4">
            
            {{-- Header dengan garis hijau --}}
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-800">Detail Transaksi — {{ $trx->muzakki_nama ?? '-' }}</h3>
            </div>

            {{-- Grid 2 kolom agar rapi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                
                {{-- Kolom Kiri --}}
                <div class="space-y-3">
                    <div class="flex">
                        <div class="w-24 text-xs text-gray-400 flex-shrink-0">Nama</div>
                        <div class="text-sm font-medium text-gray-800">{{ $trx->muzakki_nama ?? '-' }}</div>
                    </div>
                    @if($trx->muzakki_alamat)
                    <div class="flex">
                        <div class="w-24 text-xs text-gray-400 flex-shrink-0">Alamat</div>
                        <div class="text-sm text-gray-700">{{ $trx->muzakki_alamat }}</div>
                    </div>
                    @endif
                    @if($trx->muzakki && $trx->muzakki->telepon)
                    <div class="flex">
                        <div class="w-24 text-xs text-gray-400 flex-shrink-0">Telepon</div>
                        <div class="text-sm text-gray-800">{{ $trx->muzakki->telepon }}</div>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-3">
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">No. Transaksi</div>
                        <div class="text-sm font-mono font-medium text-gray-800">{{ $trx->no_transaksi }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Tanggal & Waktu</div>
                        <div class="text-sm text-gray-800">
                            {{ $trx->created_at->format('d F Y') }}
                            <span class="text-gray-500">({{ $trx->created_at->format('H:i') }})</span>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Jenis Zakat</div>
                        <div class="text-sm text-gray-800">
                            {{ $trx->jenisZakat->nama ?? '-' }}
                            @if($trx->tipeZakat)
                                <span class="text-gray-500">({{ $trx->tipeZakat->nama }})</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Jumlah</div>
                        <div class="text-sm font-semibold text-green-600">{{ $trx->jumlah_formatted }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Metode</div>
                        <div class="text-sm flex gap-1">
                            {!! $trx->metode_penerimaan_badge !!}
                            {!! $trx->metode_pembayaran_badge !!}
                        </div>
                    </div>
                    @if($trx->amil)
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Petugas</div>
                        <div class="text-sm text-gray-800">{{ $trx->amil->nama_lengkap }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </td>
    
    {{-- Kolom keempat (aksi) --}}
    <td class="px-6 py-4 bg-gray-50/30"></td>
</tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-6 py-3 text-sm font-semibold text-gray-700 text-right">Total Penerimaan:</td>
                                <td class="px-6 py-3 text-right font-bold text-green-700">
                                    Rp {{ number_format($transaksiPenerimaan->sum('jumlah'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Mobile View Penerimaan --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach($transaksiPenerimaan as $trx)
                    <div class="expandable-card">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-in-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $trx->muzakki_nama }}</h3>
                                        <span class="text-sm font-semibold text-green-700 flex-shrink-0">{{ $trx->jumlah_formatted }}</span>
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $trx->created_at->format('H:i') }}</span>
                                        <span class="text-xs text-gray-500">{{ $trx->jenisZakat->nama ?? '-' }}</span>
                                        {!! $trx->metode_penerimaan_badge !!}
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile ml-2"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <div id="detail-mobile-in-{{ $trx->uuid }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">No. Transaksi</span>
                                        <span class="font-mono text-xs text-gray-700">{{ $trx->no_transaksi }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Metode Pembayaran</span>
                                        <span>{!! $trx->metode_pembayaran_badge !!}</span>
                                    </div>
                                    @if($trx->tipeZakat)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Tipe</span>
                                        <span class="text-gray-700">{{ $trx->tipeZakat->nama }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="pt-3 mt-3 border-t border-gray-200">
                                    <a href="{{ route('kas-harian.show', $trx->uuid) }}"
                                        class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    {{-- Mobile Total Footer --}}
                    <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Total Penerimaan</span>
                        <span class="text-sm font-bold text-green-700">Rp {{ number_format($transaksiPenerimaan->sum('jumlah'), 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== TAB: PENYALURAN ===== --}}
        <div id="panel-penyaluran" class="hidden">
            @if($transaksiPenyaluran->isEmpty())
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Penyaluran</h3>
                    <p class="text-sm text-gray-500">Belum ada transaksi penyaluran pada tanggal ini</p>
                </div>
            @else

                {{-- Desktop View Penyaluran --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik &amp; Transaksi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaksiPenyaluran as $trx)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                data-target="detail-trx-out-{{ $trx->uuid }}">
                                <td class="px-4 py-4">
                                    <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $trx->mustahik->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $trx->tanggal_penyaluran->format('H:i') ?? '' }}
                                        &middot; {{ $trx->kategoriMustahik->nama ?? '-' }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                        {!! $trx->status_badge !!}
                                        {!! $trx->metode_penyaluran_badge !!}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-semibold text-orange-700">{{ $trx->jumlah_formatted }}</span>
                                </td>
                                {{-- Ganti isi cell aksi --}}
<td class="px-6 py-4 text-center">
    <div class="flex items-center justify-center gap-2">
        {{-- Tombol Detail --}}
        <div class="relative group/tooltip">
            <a href="{{ route('transaksi-penyaluran.show', $trx->uuid) }}"
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

<tr id="detail-trx-out-{{ $trx->uuid }}" class="hidden expandable-content">
    <td class="px-4 py-4 bg-gray-50/30"></td>
    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
        <div class="space-y-4">
            
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 bg-orange-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-800">Detail Penyaluran — {{ $trx->mustahik->nama_lengkap ?? '-' }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                
                {{-- Kolom Kiri: Data Mustahik --}}
                <div class="space-y-3">
                    <div class="flex">
                        <div class="w-24 text-xs text-gray-400 flex-shrink-0">Nama</div>
                        <div class="text-sm font-medium text-gray-800">{{ $trx->mustahik->nama_lengkap ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-24 text-xs text-gray-400 flex-shrink-0">Kategori</div>
                        <div class="text-sm text-gray-800">{{ $trx->kategoriMustahik->nama ?? '-' }}</div>
                    </div>
                    @if($trx->mustahik && $trx->mustahik->alamat)
                    <div class="flex">
                        <div class="w-24 text-xs text-gray-400 flex-shrink-0">Alamat</div>
                        <div class="text-sm text-gray-700">{{ $trx->mustahik->alamat }}</div>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Detail Penyaluran --}}
                <div class="space-y-3">
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">No. Transaksi</div>
                        <div class="text-sm font-mono font-medium text-gray-800">{{ $trx->no_transaksi }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Tanggal</div>
                        <div class="text-sm text-gray-800">
                            {{ $trx->tanggal_penyaluran->format('d F Y') }}
                            <span class="text-gray-500">({{ $trx->tanggal_penyaluran->format('H:i') }})</span>
                        </div>
                    </div>
                    @if($trx->jenisZakat)
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Jenis Zakat</div>
                        <div class="text-sm text-gray-800">{{ $trx->jenisZakat->nama }}</div>
                    </div>
                    @endif
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Jumlah</div>
                        <div class="text-sm font-semibold text-orange-600">{{ $trx->jumlah_formatted }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Status</div>
                        <div class="text-sm">{!! $trx->status_badge !!}</div>
                    </div>
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Metode</div>
                        <div class="text-sm">{!! $trx->metode_penyaluran_badge !!}</div>
                    </div>
                    @if($trx->amil)
                    <div class="flex">
                        <div class="w-28 text-xs text-gray-400 flex-shrink-0">Amil</div>
                        <div class="text-sm text-gray-800">{{ $trx->amil->nama_lengkap }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 bg-gray-50/30"></td>
</tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-6 py-3 text-sm font-semibold text-gray-700 text-right">Total Penyaluran:</td>
                                <td class="px-6 py-3 text-right font-bold text-orange-700">
                                    Rp {{ number_format($transaksiPenyaluran->sum('jumlah'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Mobile View Penyaluran --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach($transaksiPenyaluran as $trx)
                    <div class="expandable-card">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-out-{{ $trx->uuid }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $trx->mustahik->nama_lengkap ?? '-' }}</h3>
                                        {!! $trx->status_badge !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs font-semibold text-orange-700">{{ $trx->jumlah_formatted }}</span>
                                        <span class="text-xs text-gray-500">{{ $trx->kategoriMustahik->nama ?? '-' }}</span>
                                        {!! $trx->metode_penyaluran_badge !!}
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile ml-2"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <div id="detail-mobile-out-{{ $trx->uuid }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">No. Transaksi</span>
                                    <span class="font-mono text-xs text-gray-700">{{ $trx->no_transaksi }}</span>
                                </div>
                                @if($trx->jenisZakat)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Jenis Zakat</span>
                                    <span class="text-gray-700">{{ $trx->jenisZakat->nama }}</span>
                                </div>
                                @endif
                                @if($trx->status === 'dibatalkan' && $trx->alasan_pembatalan)
                                <div class="p-2 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-xs text-red-600 font-medium">Alasan: {{ $trx->alasan_pembatalan }}</p>
                                </div>
                                @endif
                                <div class="pt-3 border-t border-gray-200">
                                    <a href="{{ route('transaksi-penyaluran.show', $trx->uuid) }}"
                                        class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Total Penyaluran</span>
                        <span class="text-sm font-bold text-orange-700">Rp {{ number_format($transaksiPenyaluran->sum('jumlah'), 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif
        </div>

    </div>{{-- End Main Card --}}

    {{-- ===== CATATAN (jika kas sudah tutup) ===== --}}
    @if($kas->isClosed && $kas->catatan)
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-sm sm:text-base font-semibold text-gray-900">Catatan Kas</h2>
        </div>
        <div class="p-4 sm:p-6">
            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $kas->catatan }}</p>
            @if($kas->closed_at)
                <p class="text-xs text-gray-400 mt-3">Ditutup pada: {{ $kas->closed_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    </div>
    @endif

    {{-- ===== RIWAYAT 7 HARI ===== --}}
    @if(isset($riwayat7Hari) && $riwayat7Hari->count() > 0)
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up"
        x-data="{ open: false }">
        <button @click="open = !open"
            class="w-full px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
            <div>
                <h2 class="text-sm sm:text-base font-semibold text-gray-900">Riwayat 7 Hari Terakhir</h2>
                <p class="text-xs text-gray-500 mt-0.5">Klik untuk melihat ringkasan kas harian</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-collapse>
            {{-- Desktop Riwayat --}}
            <div class="hidden md:block overflow-x-auto border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penyaluran</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($riwayat7Hari as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">
                                <a href="{{ route('kas-harian.index', ['tanggal' => $r->tanggal->format('Y-m-d')]) }}"
                                    class="hover:text-primary transition-colors">
                                    {{ $r->tanggal->translatedFormat('d M Y') }}
                                </a>
                            </td>
                            <td class="px-6 py-3 text-right text-gray-600">{{ $r->saldo_awal_formatted }}</td>
                            <td class="px-6 py-3 text-right text-green-700 font-medium">{{ $r->total_penerimaan_formatted }}</td>
                            <td class="px-6 py-3 text-right text-orange-700 font-medium">{{ $r->total_penyaluran_formatted }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-gray-900">{{ $r->saldo_akhir_formatted }}</td>
                            <td class="px-6 py-3 text-center">{!! $r->status_badge !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Riwayat --}}
            <div class="md:hidden divide-y divide-gray-100 border-t border-gray-200">
                @foreach($riwayat7Hari as $r)
                <a href="{{ route('kas-harian.index', ['tanggal' => $r->tanggal->format('Y-m-d')]) }}"
                    class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $r->tanggal->translatedFormat('d M Y') }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-green-700">+{{ $r->total_penerimaan_formatted }}</span>
                            <span class="text-xs text-gray-400">·</span>
                            <span class="text-xs text-orange-700">-{{ $r->total_penyaluran_formatted }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ $r->saldo_akhir_formatted }}</p>
                        <div class="mt-0.5">{!! $r->status_badge !!}</div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="px-4 sm:px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('kas-harian.history') }}"
                    class="text-sm text-primary hover:text-primary-600 font-medium transition-colors">
                    Lihat semua riwayat →
                </a>
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- ===== TIDAK ADA DATA KAS UNTUK TANGGAL DIPILIH ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data Kas</h3>
            <p class="text-sm text-gray-500">Tidak ada kas yang tercatat pada tanggal <strong>{{ $tanggal->translatedFormat('d F Y') }}</strong>.</p>
        </div>
    </div>
    @endif

</div>

{{-- ===== MODAL: CATATAN KAS ===== --}}
<div id="catatan-modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Catatan Kas</h3>
                <p class="text-xs text-gray-500">Simpan catatan untuk kas hari ini</p>
            </div>
        </div>
        <form method="POST" id="catatan-form" action="{{ route('kas-harian.simpan-catatan') }}">
            @csrf
            <div class="mb-4">
                <textarea name="catatan" rows="4"
                    placeholder="Tambahkan catatan (opsional)..."
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none">{{ isset($kas) ? $kas->catatan : '' }}</textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('catatan-modal')"
                    class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2.5 rounded-lg bg-primary hover:bg-primary-600 text-sm font-medium text-white transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL: TUTUP KAS ===== --}}
<div id="tutup-kas-modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1 text-center">Tutup Kas Harian</h3>
        <p class="text-sm text-gray-500 mb-2 text-center">Yakin ingin menutup kas hari ini?</p>
        <p class="text-xs text-gray-400 mb-5 text-center">Setelah ditutup, saldo akhir tidak bisa berubah kecuali kas dibuka kembali.</p>

        {{-- Ringkasan Saldo --}}
        @if(isset($kas))
        <div class="bg-gray-50 rounded-xl p-3 mb-5 space-y-1.5">
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">Saldo Awal</span>
                <span class="font-medium text-gray-700">{{ $kas->saldo_awal_formatted }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">Total Penerimaan</span>
                <span class="font-medium text-green-700">+{{ $kas->total_penerimaan_formatted }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">Total Penyaluran</span>
                <span class="font-medium text-orange-700">-{{ $kas->total_penyaluran_formatted }}</span>
            </div>
            <div class="flex justify-between text-xs border-t border-gray-200 pt-1.5 mt-1.5">
                <span class="font-semibold text-gray-700">Saldo Akhir</span>
                <span class="font-bold text-gray-900">{{ $kas->saldo_akhir_formatted }}</span>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('kas-harian.tutup') }}">
            @csrf
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeModal('tutup-kas-modal')"
                    class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="w-28 rounded-lg px-4 py-2.5 bg-red-500 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                    Tutup Kas
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Desktop expandable rows ────────────────────────────────────
    document.querySelectorAll('.expandable-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            var target = document.getElementById(this.dataset.target);
            var icon = this.querySelector('.expand-icon');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        });
    });

    // ── Mobile expandable cards ────────────────────────────────────
    document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            var target = document.getElementById(this.dataset.target);
            var icon = this.querySelector('.expand-icon-mobile');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    // ── Backdrop click tutup modal ─────────────────────────────────
    ['catatan-modal', 'tutup-kas-modal'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function (e) {
                if (e.target === this) closeModal(id);
            });
        }
    });
});

// ── Tab Switcher ───────────────────────────────────────────────────
function switchTab(tab) {
    var panels = ['penerimaan', 'penyaluran'];
    panels.forEach(function (p) {
        var panel = document.getElementById('panel-' + p);
        var btn   = document.getElementById('tab-' + p);
        if (p === tab) {
            panel.classList.remove('hidden');
            btn.classList.add('border-primary', 'text-primary');
            btn.classList.remove('border-transparent', 'text-gray-500');
        } else {
            panel.classList.add('hidden');
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500');
        }
    });
}

// ── Modal helpers ──────────────────────────────────────────────────
function openCatatanModal() {
    document.getElementById('catatan-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function openTutupKasModal() {
    document.getElementById('tutup-kas-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeModal('catatan-modal');
        closeModal('tutup-kas-modal');
    }
});

function toggleStatsMobile() {
    var panel = document.getElementById('stats-mobile-panel');
    var chevron = document.getElementById('stats-chevron');
    if (panel && chevron) {
        var isHidden = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-180', isHidden);
    }
}
</script>
@endpush