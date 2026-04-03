{{-- resources/views/amil/kas-harian/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Kas Harian')

@section('content')
<div class="space-y-4 px-3 sm:px-0">

    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">

        {{-- ===== CARD HEADER ===== --}}
        <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
                <div class="min-w-0">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Riwayat Kas Harian</h2>
                    <p class="text-xs text-gray-500 mt-0.5 break-words">Laporan kas harian per tanggal</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap shrink-0">
                    <a href="{{ route('kas-harian.index') }}"
                       class="inline-flex items-center justify-center px-2.5 sm:px-3 py-1.5 sm:py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kas Hari Ini
                    </a>
                    <a href="{{ route('kas-harian.export-excel', request()->all()) }}"
                       class="inline-flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </a>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-6">

            {{-- ===== PROFILE HEADER / SUMMARY ===== --}}
            <div class="pb-4 sm:pb-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="w-full min-w-0">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 break-words">Riwayat Kas Harian</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1 break-words">Rekap seluruh kas harian masjid</p>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2 mt-2 sm:mt-3">
                            <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $kasHarian->total() }} Total Data
                            </span>
                            @if($kasHarian->count() > 0)
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Saldo Terakhir: {{ $kasHarian->first()->saldo_akhir_formatted }}
                                </span>
                            @endif
                            @if(request('start_date') || request('end_date') || request('status'))
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-0.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filter Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TABS ===== --}}
            <div class="mt-4 sm:mt-6">
                <div class="border-b border-gray-200 overflow-x-auto scrollbar-hide">
                    <nav class="-mb-px flex space-x-3 sm:space-x-8 min-w-max" aria-label="Tabs">
                        <button type="button" onclick="switchTab('ringkasan')" id="tab-ringkasan"
                            class="tab-button whitespace-nowrap py-2.5 sm:py-3 px-1 border-b-2 border-primary text-primary font-medium text-xs sm:text-sm focus:outline-none">
                            Ringkasan
                        </button>
                        <button type="button" onclick="switchTab('grafik')" id="tab-grafik"
                            class="tab-button whitespace-nowrap py-2.5 sm:py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-xs sm:text-sm focus:outline-none">
                            Grafik Saldo
                        </button>
                        <button type="button" onclick="switchTab('daftar')" id="tab-daftar"
                            class="tab-button whitespace-nowrap py-2.5 sm:py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-xs sm:text-sm focus:outline-none">
                            Daftar Kas
                            @if($kasHarian->total() > 0)
                                <span class="ml-1 inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                    {{ $kasHarian->total() }}
                                </span>
                            @endif
                        </button>
                        <button type="button" onclick="switchTab('filter')" id="tab-filter"
                            class="tab-button whitespace-nowrap py-2.5 sm:py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-xs sm:text-sm focus:outline-none">
                            Filter
                            @if(request('start_date') || request('end_date') || request('status'))
                                <span class="ml-1 inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    Aktif
                                </span>
                            @endif
                        </button>
                    </nav>
                </div>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- TAB: Ringkasan                                          --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <div id="content-ringkasan" class="tab-content mt-4 sm:mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                        {{-- Statistik --}}
                        <div class="space-y-3 sm:space-y-4">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider">Statistik Periode</h4>
                            <div class="space-y-2 sm:space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="ml-2 sm:ml-3 min-w-0">
                                        <p class="text-xs text-gray-500">Total Hari Tercatat</p>
                                        <p class="text-sm sm:text-base font-medium text-gray-900">{{ $kasHarian->total() }} hari</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-green-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-2 sm:ml-3 min-w-0">
                                        <p class="text-xs text-gray-500">Total Penerimaan (semua)</p>
                                        <p class="text-sm sm:text-base font-medium text-green-700 break-words">
                                            Rp {{ number_format($ringkasan['total_penerimaan'] ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        </svg>
                                    </div>
                                    <div class="ml-2 sm:ml-3 min-w-0">
                                        <p class="text-xs text-gray-500">Total Penyaluran (semua)</p>
                                        <p class="text-sm sm:text-base font-medium text-orange-700 break-words">
                                            Rp {{ number_format($ringkasan['total_penyaluran'] ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-2 sm:ml-3 min-w-0">
                                        <p class="text-xs text-gray-500">Saldo Kas Terakhir</p>
                                        <p class="text-sm sm:text-base font-medium text-purple-700 break-words">
                                            {{ $kasHarian->count() > 0 ? $kasHarian->first()->saldo_akhir_formatted : 'Rp 0' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Progress & Status --}}
                        <div class="space-y-3 sm:space-y-4">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider">Komposisi Kas</h4>
                            <div class="space-y-3 sm:space-y-4">
                                @php
                                    $totalPenerimaan = $ringkasan['total_penerimaan'] ?? 0;
                                    $totalPenyaluran = $ringkasan['total_penyaluran'] ?? 0;
                                    $totalArus       = $totalPenerimaan + $totalPenyaluran;
                                    $pctPenerimaan   = $totalArus > 0 ? round($totalPenerimaan / $totalArus * 100) : 0;
                                    $pctPenyaluran   = $totalArus > 0 ? round($totalPenyaluran / $totalArus * 100) : 0;
                                @endphp

                                <div class="p-3 sm:p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-medium text-gray-700">Porsi Penerimaan</span>
                                        <span class="text-sm font-bold text-green-600">{{ $pctPenerimaan }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 sm:h-3 mb-2">
                                        <div class="bg-green-500 h-2 sm:h-3 rounded-full transition-all duration-500"
                                            style="width: {{ $pctPenerimaan }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span class="font-medium break-words">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</span>
                                        <span>dari total arus</span>
                                    </div>
                                </div>

                                <div class="p-3 sm:p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-medium text-gray-700">Porsi Penyaluran</span>
                                        <span class="text-sm font-bold text-orange-600">{{ $pctPenyaluran }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 sm:h-3 mb-2">
                                        <div class="bg-orange-500 h-2 sm:h-3 rounded-full transition-all duration-500"
                                            style="width: {{ $pctPenyaluran }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span class="font-medium break-words">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</span>
                                        <span>dari total arus</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status Breakdown --}}
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3 sm:mb-4">Status Kas</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 bg-green-50 border border-green-200 rounded-xl">
                                <p class="text-xs text-green-600 font-medium mb-1">Kas Ditutup (Closed)</p>
                                <p class="text-xl sm:text-2xl font-bold text-green-700">{{ $ringkasan['total_closed'] ?? 0 }}</p>
                                <p class="text-xs text-green-500 mt-1">hari</p>
                            </div>
                            <div class="p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <p class="text-xs text-blue-600 font-medium mb-1">Kas Terbuka (Open)</p>
                                <p class="text-xl sm:text-2xl font-bold text-blue-700">{{ $ringkasan['total_open'] ?? 0 }}</p>
                                <p class="text-xs text-blue-500 mt-1">hari</p>
                            </div>
                            <div class="p-3 sm:p-4 bg-purple-50 border border-purple-200 rounded-xl">
                                <p class="text-xs text-purple-600 font-medium mb-1">Total Transaksi</p>
                                <p class="text-xl sm:text-2xl font-bold text-purple-700">{{ $ringkasan['total_transaksi'] ?? 0 }}</p>
                                <p class="text-xs text-purple-500 mt-1">transaksi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- TAB: Grafik Saldo                                       --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <div id="content-grafik" class="tab-content hidden mt-4 sm:mt-6">
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider">
                                Grafik Saldo 30 Hari Terakhir
                            </h4>
                            <span class="text-xs font-semibold px-2 sm:px-3 py-1 rounded-full text-center"
                                  style="color:#17a34a; background:#f0fdf4; border:1px solid #dcfce7;">
                                30 Hari Terakhir
                            </span>
                        </div>

                        {{-- Panel chart --}}
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-3 sm:px-5 py-2.5 sm:py-3 border-b border-gray-100"
                                 style="background:#f9fafb;">
                                <p class="text-xs sm:text-sm font-bold text-gray-900 break-words">
                                    Trend Saldo, Penerimaan &amp; Penyaluran
                                </p>
                            </div>
                            <div class="p-3 sm:p-5">
                                <div style="height: 250px; min-height: 250px; position: relative;">
                                    <canvas id="chartSaldo"></canvas>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 text-center">Menampilkan data 30 hari terakhir. Gunakan filter untuk rentang tertentu.</p>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- TAB: Daftar Kas                                         --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <div id="content-daftar" class="tab-content hidden mt-4 sm:mt-6">

                    @if($kasHarian->count() > 0)
                        {{-- Desktop Table --}}
                        <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penyaluran</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trx</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($kasHarian as $kas)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-gray-900 text-sm">{{ $kas->tanggal->translatedFormat('d M Y') }}</p>
                                            <p class="text-xs text-gray-400">{{ $kas->tanggal->translatedFormat('l') }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-600">{{ $kas->saldo_awal_formatted }}</td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-green-700">{{ $kas->total_penerimaan_formatted }}</td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-orange-700">{{ $kas->total_penyaluran_formatted }}</td>
                                        <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">{{ $kas->saldo_akhir_formatted }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="text-xs text-gray-500">
                                                <span class="text-green-600 font-medium">+{{ $kas->jumlah_transaksi_masuk }}</span>
                                                /
                                                <span class="text-orange-600 font-medium">-{{ $kas->jumlah_transaksi_keluar }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">{!! $kas->status_badge !!}</td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('kas-harian.index', ['tanggal' => $kas->tanggal->format('Y-m-d')]) }}"
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="md:hidden space-y-3">
                            @foreach($kasHarian as $kas)
                            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                                <div class="flex items-start justify-between mb-3 flex-wrap gap-2">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm break-words">{{ $kas->tanggal->translatedFormat('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $kas->tanggal->translatedFormat('l') }}</p>
                                    </div>
                                    <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                        {!! $kas->status_badge !!}
                                        <a href="{{ route('kas-harian.index', ['tanggal' => $kas->tanggal->format('Y-m-d')]) }}"
                                           class="text-xs text-primary hover:underline font-medium whitespace-nowrap">Detail →</a>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="bg-gray-50 rounded-lg p-2">
                                        <p class="text-gray-400 mb-0.5 text-[10px] sm:text-xs">Saldo Awal</p>
                                        <p class="font-medium text-gray-700 text-xs sm:text-sm break-words">{{ $kas->saldo_awal_formatted }}</p>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-2">
                                        <p class="text-gray-400 mb-0.5 text-[10px] sm:text-xs">Penerimaan</p>
                                        <p class="font-medium text-green-700 text-xs sm:text-sm break-words">{{ $kas->total_penerimaan_formatted }}</p>
                                    </div>
                                    <div class="bg-orange-50 rounded-lg p-2">
                                        <p class="text-gray-400 mb-0.5 text-[10px] sm:text-xs">Penyaluran</p>
                                        <p class="font-medium text-orange-700 text-xs sm:text-sm break-words">{{ $kas->total_penyaluran_formatted }}</p>
                                    </div>
                                    <div class="bg-purple-50 rounded-lg p-2">
                                        <p class="text-gray-400 mb-0.5 text-[10px] sm:text-xs">Saldo Akhir</p>
                                        <p class="font-bold text-purple-700 text-xs sm:text-sm break-words">{{ $kas->saldo_akhir_formatted }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($kasHarian->hasPages())
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            {{ $kasHarian->appends(request()->except('page'))->links() }}
                        </div>
                        @endif

                    @else
                        <div class="text-center py-8 sm:py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 sm:mt-3 text-sm font-medium text-gray-700">Belum Ada Riwayat</h3>
                            <p class="mt-1 text-xs text-gray-400 px-3">
                                @if(request('start_date') || request('end_date') || request('status'))
                                    Tidak ada data yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada kas harian yang tercatat.
                                @endif
                            </p>
                            @if(request('start_date') || request('end_date') || request('status'))
                                <a href="{{ route('kas-harian.history') }}"
                                   class="inline-flex items-center mt-3 sm:mt-4 px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-medium rounded-lg transition-all">
                                    Reset Filter
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- ═══════════════════════════════════════════════════════ --}}
                {{-- TAB: Filter                                             --}}
                {{-- ═══════════════════════════════════════════════════════ --}}
                <div id="content-filter" class="tab-content hidden mt-4 sm:mt-6">
                    <div class="w-full">
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3 sm:mb-4">Filter Data</h4>
                        <form method="GET" action="{{ route('kas-harian.history') }}" id="filter-form">
                            <div class="space-y-3 sm:space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status Kas</label>
                                    <select name="status"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                        <option value="">Semua Status</option>
                                        <option value="open"   {{ request('status') === 'open'   ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3 pt-2">
                                    <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-5 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                        </svg>
                                        Terapkan Filter
                                    </button>
                                    @if(request('start_date') || request('end_date') || request('status'))
                                        <a href="{{ route('kas-harian.history') }}"
                                           class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-all">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        @if(request('start_date') || request('end_date') || request('status'))
                        <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-yellow-800 mb-1">Filter Aktif</p>
                                    <div class="text-xs text-yellow-700 space-y-0.5">
                                        @if(request('start_date'))
                                            <p class="break-words">Dari: <span class="font-medium">{{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') }}</span></p>
                                        @endif
                                        @if(request('end_date'))
                                            <p class="break-words">Sampai: <span class="font-medium">{{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') }}</span></p>
                                        @endif
                                        @if(request('status'))
                                            <p class="break-words">Status: <span class="font-medium capitalize">{{ request('status') }}</span></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- end tabs --}}
        </div>{{-- end p-3 sm:p-6 --}}

        {{-- ===== FOOTER ACTIONS ===== --}}
        <div class="px-3 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
                <a href="{{ route('kas-harian.index') }}"
                    class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Kas Harian
                </a>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('kas-harian.export-excel', request()->all()) }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- CDN sama dengan dashboard amil --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── Tab switching ─────────────────────────────────────────────────────
    let chartInitialized = false;

    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(b => {
            b.classList.remove('border-primary', 'text-primary');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('content-' + tabName).classList.remove('hidden');
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('border-primary', 'text-primary');
        activeTab.classList.remove('border-transparent', 'text-gray-500');

        // Init chart hanya saat tab grafik dibuka pertama kali
        if (tabName === 'grafik' && !chartInitialized) {
            chartInitialized = true;
            setTimeout(initChart, 100);
        }
    }

    // ── Auto switch tab jika ada param ?tab= ─────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        const validTabs = ['ringkasan', 'grafik', 'daftar', 'filter'];

        if (activeTab && validTabs.includes(activeTab)) {
            switchTab(activeTab);
        }

        @if(request('start_date') || request('end_date') || request('status'))
            if (!activeTab) switchTab('filter');
        @endif
    });

    // ── Chart Saldo ── identik gaya dengan chartTrend dashboard amil ──────
    function initChart() {
        const chartData = @json($chart30Hari);
        const canvas    = document.getElementById('chartSaldo');

        if (!canvas) return;

        if (!chartData || chartData.length === 0) {
            const parent = canvas.closest('.p-3') || canvas.closest('.p-5');
            if (parent) {
                parent.innerHTML = '<p class="text-center text-sm text-gray-400 py-8">Belum ada data untuk ditampilkan.</p>';
            }
            return;
        }

        // ── Global font defaults (sama dengan dashboard) ──────────────────
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.font.size   = 11;
        Chart.defaults.font.weight = '500';

        // ── Palet warna ───────────────────────────────────────────────────
        const C_SALDO  = '#7c3aed';   // ungu  — Saldo Akhir
        const C_MASUK  = '#17a34a';   // hijau — Penerimaan
        const C_KELUAR = '#ea580c';   // oranye — Penyaluran
        const GRID     = '#f3f4f6';
        const TICK     = '#9ca3af';

        // ── Tooltip gelap ──────────────────────────────────────────────────
        const tooltipCfg = {
            backgroundColor : '#111827',
            titleColor      : '#ffffff',
            bodyColor       : 'rgba(255,255,255,.65)',
            borderColor     : 'rgba(23,163,74,.25)',
            borderWidth     : 1,
            padding         : 10,
            cornerRadius    : 8,
            titleFont : { family: "'Poppins', sans-serif", weight: '700', size: 12 },
            bodyFont  : { family: "'Poppins', sans-serif", size: 11 },
            callbacks : {
                label: ctx => ' ' + ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID')
            }
        };

        const labels     = chartData.map(d => {
            const date = new Date(d.tanggal);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const saldoAkhir = chartData.map(d => parseFloat(d.saldo_akhir));
        const penerimaan = chartData.map(d => parseFloat(d.total_penerimaan));
        const penyaluran = chartData.map(d => parseFloat(d.total_penyaluran));

        const ctx = canvas.getContext('2d');

        // ── Gradient fill ──────────────────────────────────────────────────
        const gradSaldo = ctx.createLinearGradient(0, 0, 0, 250);
        gradSaldo.addColorStop(0, 'rgba(124,58,237,.18)');
        gradSaldo.addColorStop(1, 'rgba(124,58,237,0)');

        const gradMasuk = ctx.createLinearGradient(0, 0, 0, 250);
        gradMasuk.addColorStop(0, 'rgba(23,163,74,.18)');
        gradMasuk.addColorStop(1, 'rgba(23,163,74,0)');

        const gradKeluar = ctx.createLinearGradient(0, 0, 0, 250);
        gradKeluar.addColorStop(0, 'rgba(234,88,12,.15)');
        gradKeluar.addColorStop(1, 'rgba(234,88,12,0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label                : 'Saldo Akhir',
                        data                 : saldoAkhir,
                        borderColor          : C_SALDO,
                        backgroundColor      : gradSaldo,
                        fill                 : true,
                        tension              : 0.42,
                        borderWidth          : 2.5,
                        pointRadius          : 4,
                        pointBackgroundColor : '#fff',
                        pointBorderColor     : C_SALDO,
                        pointBorderWidth     : 2,
                        pointHoverRadius     : 6,
                    },
                    {
                        label                : 'Penerimaan',
                        data                 : penerimaan,
                        borderColor          : C_MASUK,
                        backgroundColor      : gradMasuk,
                        fill                 : true,
                        tension              : 0.42,
                        borderWidth          : 2.5,
                        pointRadius          : 4,
                        pointBackgroundColor : '#fff',
                        pointBorderColor     : C_MASUK,
                        pointBorderWidth     : 2,
                        pointHoverRadius     : 6,
                    },
                    {
                        label                : 'Penyaluran',
                        data                 : penyaluran,
                        borderColor          : C_KELUAR,
                        backgroundColor      : gradKeluar,
                        fill                 : true,
                        tension              : 0.42,
                        borderWidth          : 2.5,
                        pointRadius          : 4,
                        pointBackgroundColor : '#fff',
                        pointBorderColor     : C_KELUAR,
                        pointBorderWidth     : 2,
                        pointHoverRadius     : 6,
                    },
                ]
            },
            options: {
                responsive          : true,
                maintainAspectRatio : false,

                animation  : false,
                animations : false,
                transitions: {
                    active : { animation: { duration: 0 } },
                    resize : { animation: { duration: 0 } },
                    show   : { animation: { duration: 0 } },
                    hide   : { animation: { duration: 0 } },
                },

                interaction: { mode: 'index', intersect: false },

                plugins: {
                    tooltip: tooltipCfg,
                    legend : {
                        position: 'top',
                        labels: {
                            color        : TICK,
                            font         : { family: "'Poppins', sans-serif", size: 10, weight: '600' },
                            usePointStyle: true,
                            pointStyle   : 'circle',
                            padding      : 10,
                            boxWidth     : 8,
                        }
                    }
                },

                scales: {
                    x: {
                        grid : { display: false },
                        ticks: { color: TICK, maxRotation: 45, minRotation: 45, font: { size: 9 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid       : { color: GRID },
                        ticks      : {
                            color   : TICK,
                            font    : { size: 9 },
                            callback: val => {
                                if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                                if (val >= 1000)    return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                                return 'Rp ' + val;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush