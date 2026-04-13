@extends('layouts.app')

@section('title', 'Laporan Konsolidasi')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Laporan Konsolidasi</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Laporan keuangan konsolidasi semua lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['search', 'lembaga_id', 'bulan']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($grandTotal['lembaga'] ?? $laporanPerLembaga->count()) }}</span>
                        <span class="text-sm text-gray-500">Lembaga</span>
                        <span class="text-sm text-gray-400 mx-1">•</span>
                        <span class="text-sm text-gray-600">Tahun:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $tahun }}</span>
                    </div>

                    <!-- Stats Ringkasan Desktop -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Penerimaan:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($grandTotal['total_penerimaan'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Penyaluran:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($grandTotal['total_penyaluran'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="{{ request()->hasAny(['search', 'lembaga_id', 'bulan']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('laporan-konsolidasi.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Lembaga</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ $search }}"
                                        placeholder="Cari nama lembaga..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Lembaga -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Lembaga</label>
                                <select name="lembaga_id"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Lembaga</option>
                                    @foreach($allLembagas as $l)
                                        <option value="{{ $l->id }}" {{ $lembagaId == $l->id ? 'selected' : '' }}>
                                            {{ $l->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Tahun -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                                <select name="tahun"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    @foreach($availableYears as $y)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                                <select name="bulan"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Bulan</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nm)
                                        <option value="{{ $i + 1 }}" {{ $bulan == ($i + 1) ? 'selected' : '' }}>{{ $nm }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if($search || $lembagaId || $bulan)
                            <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}"
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

            <!-- Active Filter Tags -->
            @if($search || $lembagaId || $bulan)
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if($search)
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ $search }}"
                                <button onclick="removeFilter('search')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if($lembagaId)
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Lembaga: {{ $allLembagas->firstWhere('id', $lembagaId)?->nama ?? $lembagaId }}
                                <button onclick="removeFilter('lembaga_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if($bulan)
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Bulan: {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan - 1] ?? $bulan }}
                                <button onclick="removeFilter('bulan')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(count($laporanPerLembaga) > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-4 text-center w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500">LEMBAGA</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500">PENERIMAAN</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500">PENYALURAN</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 hidden lg:table-cell">SETOR KAS</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500">SALDO AKHIR</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporanPerLembaga as $item)
                                @php $lembaga = $item['lembaga']; @endphp
                                
                                <tr class="border-b border-gray-100 hover:bg-green-50/20 cursor-pointer expandable-row"
                                    data-target="detail-{{ $lembaga->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800 group-hover:text-green-700">
                                                {{ $lembaga->nama }}
                                            </span>
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $lembaga->kode_lembaga ?? 'LBL-' . str_pad($lembaga->id, 3, '0', STR_PAD_LEFT) }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat rincian per bulan</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-semibold text-green-600">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-semibold text-red-600">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right hidden lg:table-cell">
                                        <span class="text-sm font-semibold text-indigo-600">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-bold text-blue-600">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                        <a href="{{ route('laporan-konsolidasi.detail', $lembaga->id) }}?tahun={{ $tahun }}&bulan={{ $bulan }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>

                                <!-- Expandable Row dengan Rincian per Bulan -->
                                <tr id="detail-{{ $lembaga->id }}" class="hidden border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="6" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Rincian per Bulan — {{ $lembaga->nama }}</h3>
                                            </div>

                                            @if(count($item['periodes']) === 0)
                                                <div class="text-center py-8 text-sm text-gray-400 bg-white rounded-xl border">Belum ada data transaksi untuk periode yang dipilih</div>
                                            @else
                                                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">PERIODE</th>
                                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">PENERIMAAN</th>
                                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">PENYALURAN</th>
                                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 hidden md:table-cell">SETOR KAS</th>
                                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">SALDO</th>
                                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 hidden lg:table-cell">MUZAKKI / MUSTAHIK</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-100">
                                                            @foreach($item['periodes'] as $periode)
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-4 py-3">
                                                                        <span class="text-sm font-medium text-gray-800">{{ $periode['bulan_nama'] }} {{ $periode['tahun'] }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-right">
                                                                        <span class="text-sm text-green-600 font-medium">Rp {{ number_format($periode['total_penerimaan'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-right">
                                                                        <span class="text-sm text-red-600 font-medium">Rp {{ number_format($periode['total_penyaluran'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-right hidden md:table-cell">
                                                                        <span class="text-sm text-indigo-600 font-medium">Rp {{ number_format($periode['total_setor_kas'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-right">
                                                                        <span class="text-sm font-bold text-blue-700">Rp {{ number_format($periode['saldo_akhir'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-center hidden lg:table-cell">
                                                                        <div class="flex items-center justify-center gap-1">
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700">{{ number_format($periode['jumlah_muzakki'] ?? 0) }}</span>
                                                                            <span class="text-xs text-gray-400">/</span>
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">{{ number_format($periode['jumlah_mustahik'] ?? 0) }}</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                                            <tr>
                                                                <td class="px-4 py-3 text-xs font-bold text-gray-700 uppercase">TOTAL</td>
                                                                <td class="px-4 py-3 text-right text-sm font-bold text-green-700">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-3 text-right text-sm font-bold text-red-700">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-3 text-right text-sm font-bold text-indigo-700 hidden md:table-cell">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-3 text-right text-sm font-bold text-blue-800">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-3 text-center hidden lg:table-cell">
                                                                    <div class="flex items-center justify-center gap-1">
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold bg-purple-200 text-purple-800">{{ number_format($item['jumlah_muzakki']) }}</span>
                                                                        <span class="text-xs text-gray-400">/</span>
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold bg-orange-200 text-orange-800">{{ number_format($item['jumlah_mustahik']) }}</span>
                                                                    </div>
                                                                 </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="flex justify-end">
                                                    <a href="{{ route('laporan-konsolidasi.detail', $lembaga->id) }}?tahun={{ $tahun }}&bulan={{ $bulan }}"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Lihat Detail Lengkap (Chart & Breakdown)
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW (DIPERBAIKI) ==================== -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach($laporanPerLembaga as $item)
                        @php $lembaga = $item['lembaga']; @endphp
                        
                        <div class="p-4">
                            <!-- Header Card (klik untuk expand) - HANYA SATU ICON -->
                            <div class="expandable-row-mobile cursor-pointer" 
                                data-target="detail-mobile-{{ $lembaga->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-400">Lembaga</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words pr-2">
                                            {{ $lembaga->nama }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="text-xs text-gray-500">Saldo Akhir:</span>
                                            <span class="text-sm font-bold text-blue-600">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span class="text-xs text-green-600">+Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</span>
                                            <span class="text-xs text-red-600">-Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- HANYA SATU CHEVRON (tidak ada icon lain) -->
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile-chevron" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $lembaga->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <!-- Ringkasan -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="bg-gray-50 rounded-lg p-2.5">
                                            <p class="text-xs text-gray-500">Penerimaan</p>
                                            <p class="text-sm font-bold text-green-600">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2.5">
                                            <p class="text-xs text-gray-500">Penyaluran</p>
                                            <p class="text-sm font-bold text-red-600">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2.5">
                                            <p class="text-xs text-gray-500">Setor Kas</p>
                                            <p class="text-sm font-bold text-indigo-600">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2.5">
                                            <p class="text-xs text-gray-500">Muzakki / Mustahik</p>
                                            <p class="text-sm font-bold">
                                                <span class="text-purple-600">{{ number_format($item['jumlah_muzakki']) }}</span>
                                                <span class="text-gray-400">/</span>
                                                <span class="text-orange-600">{{ number_format($item['jumlah_mustahik']) }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Rincian per Bulan -->
                                    @if(count($item['periodes']) > 0)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Rincian per Bulan</h4>
                                            <div class="space-y-2">
                                                @foreach($item['periodes'] as $periode)
                                                    <div class="bg-white border border-gray-200 rounded-lg p-2.5">
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs font-semibold text-gray-700">{{ $periode['bulan_nama'] }} {{ $periode['tahun'] }}</span>
                                                            <span class="text-xs font-bold text-blue-600">Rp {{ number_format($periode['saldo_akhir'], 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="flex justify-between mt-1">
                                                            <span class="text-xs text-green-600">+Rp {{ number_format($periode['total_penerimaan'], 0, ',', '.') }}</span>
                                                            <span class="text-xs text-red-600">-Rp {{ number_format($periode['total_penyaluran'], 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="flex justify-between mt-1 text-xs text-gray-500">
                                                            <span>Muzakki: {{ number_format($periode['jumlah_muzakki'] ?? 0) }}</span>
                                                            <span>Mustahik: {{ number_format($periode['jumlah_mustahik'] ?? 0) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <a href="{{ route('laporan-konsolidasi.detail', $lembaga->id) }}?tahun={{ $tahun }}&bulan={{ $bulan }}"
                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail Lengkap
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @if($search || $lembagaId || $bulan)
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}" class="text-sm text-green-600 hover:text-green-700">Reset semua filter</a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data transaksi</p>
                        <p class="text-xs text-gray-400">Tidak ada data transaksi untuk tahun {{ $tahun }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .rotate-90 { transform: rotate(90deg); }
        .rotate-180 { transform: rotate(180deg); }
    </style>
@endsection

@push('scripts')
<script>
// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterPanel = document.getElementById('filterPanel');
    
    if (filterButton && filterPanel) {
        filterButton.addEventListener('click', () => filterPanel.classList.toggle('hidden'));
    }

    // Desktop expandable
    document.querySelectorAll('.expandable-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const targetId = this.getAttribute('data-target');
            const targetRow = document.getElementById(targetId);
            const icon = this.querySelector('.expand-icon');
            if (targetRow) {
                const isHidden = targetRow.classList.contains('hidden');
                targetRow.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-90');
            }
        });
    });

    // Mobile expandable - HANYA SATU ICON (chevron)
    document.querySelectorAll('.expandable-row-mobile').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const chevron = this.querySelector('.expand-icon-mobile-chevron');
            if (targetContent) {
                const isHidden = targetContent.classList.contains('hidden');
                targetContent.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-90');
            }
        });
    });
});

function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    url.searchParams.set('tahun', '{{ $tahun }}');
    window.location.href = url.toString();
}

function toggleFilter() {
    const filterPanel = document.getElementById('filterPanel');
    if (filterPanel) {
        filterPanel.classList.add('hidden');
    }
}
</script>
@endpush