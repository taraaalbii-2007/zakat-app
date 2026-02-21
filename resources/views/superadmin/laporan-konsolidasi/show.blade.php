@extends('layouts.app')

@section('title', 'Detail Laporan - ' . $masjid->nama)

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ============================================================
         MAIN CARD
         ============================================================ --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Detail Laporan Konsolidasi</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $masjid->nama }} - 12 Bulan Terakhir</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                        class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>

                    {{-- DROPDOWN EXPORT --}}
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <div>
                            <button type="button" @click="open = !open"
                                class="inline-flex items-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        <div x-show="open" 
                            @click.outside="open = false"
                            @keydown.escape.window="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="display: none;">
                            <div class="py-1">
                                {{-- PDF Options --}}
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    Format PDF
                                </div>
                                <a href="{{ route('laporan-konsolidasi.export', ['masjidId' => $masjid->id, 'format' => 'pdf', 'type' => 'konsolidasi', 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Konsolidasi (Ringkasan)
                                </a>
                                <a href="{{ route('laporan-konsolidasi.export', ['masjidId' => $masjid->id, 'format' => 'pdf', 'type' => 'penerimaan', 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Detail Penerimaan
                                </a>
                                <a href="{{ route('laporan-konsolidasi.export', ['masjidId' => $masjid->id, 'format' => 'pdf', 'type' => 'penyaluran', 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Detail Penyaluran
                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                {{-- Excel Options --}}
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                    Format Excel
                                </div>
                                <a href="{{ route('laporan-konsolidasi.export', ['masjidId' => $masjid->id, 'format' => 'excel', 'type' => 'konsolidasi', 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Konsolidasi (Ringkasan)
                                </a>
                                <a href="{{ route('laporan-konsolidasi.export', ['masjidId' => $masjid->id, 'format' => 'excel', 'type' => 'penerimaan', 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Detail Penerimaan
                                </a>
                                <a href="{{ route('laporan-konsolidasi.export', ['masjidId' => $masjid->id, 'format' => 'excel', 'type' => 'penyaluran', 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Detail Penyaluran
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- END DROPDOWN EXPORT --}}
                </div>
            </div>
        </div>

        {{-- Content Body --}}
        <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

            {{-- Info Masjid --}}
            <div>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900">{{ $masjid->nama }}</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $masjid->kode_masjid }}</p>
                        <p class="text-sm text-gray-500 mt-1 flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $masjid->alamat_lengkap }}
                        </p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Summary Cards (3 kolom) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Periode Laporan</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium">12 Bulan Terakhir</p>
                            <p class="text-xs text-gray-500">{{ now()->subMonths(11)->format('M Y') }} - {{ now()->format('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Penerimaan</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-600">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($totalMuzakki, 0, ',', '.') }} Muzakki</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Penyaluran</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-600">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($totalMustahik, 0, ',', '.') }} Mustahik</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Summary Cards Grid --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-green-700 uppercase tracking-wider mb-1">Total Penerimaan</p>
                                <p class="text-xl font-bold text-green-900">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</p>
                                <p class="text-xs text-green-600 mt-1">12 bulan terakhir</p>
                            </div>
                            <div class="w-12 h-12 bg-green-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-red-700 uppercase tracking-wider mb-1">Total Penyaluran</p>
                                <p class="text-xl font-bold text-red-900">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</p>
                                <p class="text-xs text-red-600 mt-1">12 bulan terakhir</p>
                            </div>
                            <div class="w-12 h-12 bg-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-blue-700 uppercase tracking-wider mb-1">Saldo Terakhir</p>
                                <p class="text-xl font-bold text-blue-900">Rp {{ number_format($saldoTerakhir, 0, ',', '.') }}</p>
                                <p class="text-xs text-blue-600 mt-1">{{ $laporanBulanan->last()->periode ?? '-' }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-purple-700 uppercase tracking-wider mb-1">Total Muzakki</p>
                                <p class="text-xl font-bold text-purple-900">{{ number_format($totalMuzakki, 0, ',', '.') }}</p>
                                <p class="text-xs text-purple-600 mt-1">Pemberi zakat</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200 shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-orange-700 uppercase tracking-wider mb-1">Total Mustahik</p>
                                <p class="text-xl font-bold text-orange-900">{{ number_format($totalMustahik, 0, ',', '.') }}</p>
                                <p class="text-xs text-orange-600 mt-1">Penerima zakat</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl border border-indigo-200 shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-indigo-700 uppercase tracking-wider mb-1">Rata-rata / Bulan</p>
                                <p class="text-xl font-bold text-indigo-900">Rp {{ number_format($totalPenerimaan / 12, 0, ',', '.') }}</p>
                                <p class="text-xs text-indigo-600 mt-1">Penerimaan</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Chart Section --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Grafik Penerimaan & Penyaluran</h4>
                <div class="h-80 bg-gray-50 rounded-lg p-4">
                    <canvas id="chartKeuangan"></canvas>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Tabel Laporan Bulanan --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Laporan Bulanan (12 Bulan Terakhir)</h4>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penyaluran</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Muzakki</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($laporanBulanan as $laporan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $laporan->bulan_nama }} {{ $laporan->tahun }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-green-600">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-red-600">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-blue-600">Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ number_format($laporan->jumlah_muzakki, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            {{ number_format($laporan->jumlah_mustahik, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Belum ada data laporan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Breakdown Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Breakdown Jenis Zakat --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Breakdown per Jenis Zakat</h4>
                    @if ($breakdownJenisZakat->count() > 0)
                        <div class="space-y-3">
                            @foreach ($breakdownJenisZakat as $jenis)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $jenis->nama_zakat }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-primary">Rp {{ number_format($jenis->total, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500">Belum ada data breakdown jenis zakat</p>
                        </div>
                    @endif
                </div>

                {{-- Breakdown Kategori Mustahik --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Breakdown per Kategori Mustahik</h4>
                    @if ($breakdownMustahik->count() > 0)
                        <div class="space-y-3">
                            @foreach ($breakdownMustahik as $kategori)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $kategori->nama_kategori }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ number_format($kategori->jumlah_penerima, 0, ',', '.') }} penerima</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-primary">Rp {{ number_format($kategori->total, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm text-gray-500">Belum ada data breakdown kategori mustahik</p>
                        </div>
                    @endif
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Timestamps --}}
            <div class="text-xs text-gray-500 flex flex-col sm:flex-row flex-wrap gap-4">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Laporan dibuat: {{ now()->translatedFormat('d F Y H:i') }}
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                    <a href="{{ route('laporan-konsolidasi.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

        </div>{{-- end content body --}}
    </div>{{-- end main card --}}
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartKeuangan');

            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                                label: 'Penerimaan',
                                data: {!! json_encode($chartPenerimaan) !!},
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Penyaluran',
                                data: {!! json_encode($chartPenyaluran) !!},
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                            notation: 'compact'
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush