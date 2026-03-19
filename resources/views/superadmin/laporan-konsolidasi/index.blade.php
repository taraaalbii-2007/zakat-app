@extends('layouts.app')

@section('title', 'Laporan Konsolidasi')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Laporan Konsolidasi</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            {{ $grandTotal['lembaga'] }} Lembaga · Tahun {{ $tahun }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ ($search || $lembagaId || $bulan) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ $search ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ $search ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('laporan-konsolidasi.index') }}" id="search-form"
                                class="{{ $search ? '' : 'hidden' }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                @if($bulan)<input type="hidden" name="bulan" value="{{ $bulan }}">@endif
                                @if($lembagaId)<input type="hidden" name="lembaga_id" value="{{ $lembagaId }}">@endif
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <input type="search" name="search" value="{{ $search }}"
                                            id="search-input" placeholder="Cari lembaga..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if($search)
                                        <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}"
                                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ── --}}
            <div id="filter-panel"
                class="{{ ($search || $lembagaId || $bulan) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('laporan-konsolidasi.index') }}" id="filter-form">
                    @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lembaga</label>
                            <select name="lembaga_id" onchange="document.getElementById('filter-form').submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Lembaga</option>
                                @foreach($allLembagas as $m)
                                    <option value="{{ $m->id }}" {{ $lembagaId == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" onchange="document.getElementById('filter-form').submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                @foreach($availableYears as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" onchange="document.getElementById('filter-form').submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Bulan</option>
                                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nm)
                                    <option value="{{ $i + 1 }}" {{ $bulan == ($i + 1) ? 'selected' : '' }}>{{ $nm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($search || $lembagaId || $bulan)
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- ── Tabel ── --}}
            @if(count($laporanPerLembaga) > 0)

                {{-- Desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-10 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lembaga</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Penyaluran</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Setor Kas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($laporanPerLembaga as $item)
                                @php $lembaga = $item['lembaga']; @endphp

                                {{-- Baris Lembaga --}}
                                <tr class="lembaga-row cursor-pointer hover:bg-primary/5 transition-colors"
                                    onclick="toggleLembaga('lembaga-{{ $lembaga->id }}', this)">
                                    <td class="px-4 py-3">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $lembaga->kode_lembaga }} · Klik untuk lihat per bulan</div>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-semibold text-green-600">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-semibold text-red-600">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap hidden lg:table-cell">
                                        <span class="text-sm font-semibold text-indigo-600">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-blue-600">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-center" onclick="event.stopPropagation()">
                                        <a href="{{ route('laporan-konsolidasi.detail', $lembaga->id) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>

                                {{-- Expandable: Tabel per Bulan --}}
                                <tr id="lembaga-{{ $lembaga->id }}" class="hidden lembaga-content-row">
                                    <td colspan="7" class="p-0">
                                        <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="w-1 h-5 bg-primary rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">
                                                    Rincian per Bulan — {{ $lembaga->nama }}
                                                </h3>
                                            </div>

                                            @if(count($item['periodes']) === 0)
                                                <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                    Belum ada data transaksi untuk lembaga ini pada periode yang dipilih
                                                </div>
                                            @else
                                                <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-white">
                                                            <tr>
                                                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Penerimaan</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Penyaluran</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Setor Kas</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Saldo</th>
                                                                <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Muzakki / Mustahik</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-100">
                                                            @foreach($item['periodes'] as $periode)
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-4 py-2.5">
                                                                        <span class="text-sm font-medium text-gray-800">{{ $periode['bulan_nama'] }} {{ $periode['tahun'] }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right">
                                                                        <span class="text-sm text-green-600 font-medium">Rp {{ number_format($periode['total_penerimaan'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right">
                                                                        <span class="text-sm text-red-600 font-medium">Rp {{ number_format($periode['total_penyaluran'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right hidden md:table-cell">
                                                                        <span class="text-sm text-indigo-600 font-medium">Rp {{ number_format($periode['total_setor_kas'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right">
                                                                        <span class="text-sm text-blue-700 font-bold">Rp {{ number_format($periode['saldo_akhir'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-center hidden lg:table-cell">
                                                                        <span class="text-xs text-purple-700 font-semibold">{{ number_format($periode['jumlah_muzakki']) }}</span>
                                                                        <span class="text-xs text-gray-400 mx-1">/</span>
                                                                        <span class="text-xs text-orange-600 font-semibold">{{ number_format($periode['jumlah_mustahik']) }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                                            <tr>
                                                                <td class="px-4 py-2.5 text-xs font-bold text-gray-700 uppercase">Subtotal</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-green-700">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-red-700">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-indigo-700 hidden md:table-cell">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-blue-800">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-center hidden lg:table-cell">
                                                                    <span class="text-xs font-bold text-purple-800">{{ number_format($item['jumlah_muzakki']) }}</span>
                                                                    <span class="text-xs text-gray-400 mx-1">/</span>
                                                                    <span class="text-xs font-bold text-orange-800">{{ number_format($item['jumlah_mustahik']) }}</span>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="mt-3 flex justify-end">
                                                    <a href="{{ route('laporan-konsolidasi.detail', $lembaga->id) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                {{-- Mobile --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach($laporanPerLembaga as $item)
                        @php $lembaga = $item['lembaga']; @endphp
                        <div>
                            <div class="p-4 hover:bg-primary/5 cursor-pointer transition-colors"
                                onclick="toggleLembagaMobile('mob-{{ $lembaga->id }}', this)">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $lembaga->nama }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Saldo: <span class="font-semibold text-blue-600">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 mob-chevron flex-shrink-0 ml-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div id="mob-{{ $lembaga->id }}" class="hidden bg-gray-50 border-t border-gray-100 px-4 py-4 space-y-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="bg-white rounded-lg p-2.5 border border-gray-200">
                                        <p class="text-xs text-gray-500">Penerimaan</p>
                                        <p class="text-sm font-bold text-green-600 mt-0.5">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-2.5 border border-gray-200">
                                        <p class="text-xs text-gray-500">Penyaluran</p>
                                        <p class="text-sm font-bold text-red-600 mt-0.5">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-2.5 border border-gray-200">
                                        <p class="text-xs text-gray-500">Setor Kas</p>
                                        <p class="text-sm font-bold text-indigo-600 mt-0.5">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-2.5 border border-gray-200">
                                        <p class="text-xs text-gray-500">Muzakki / Mustahik</p>
                                        <p class="text-sm font-bold text-purple-600 mt-0.5">{{ $item['jumlah_muzakki'] }} / {{ $item['jumlah_mustahik'] }}</p>
                                    </div>
                                </div>

                                @if(count($item['periodes']) > 0)
                                    <div class="rounded-lg border border-gray-200 overflow-hidden">
                                        <div class="bg-gray-100 px-3 py-2">
                                            <p class="text-xs font-semibold text-gray-600 uppercase">Rincian per Bulan</p>
                                        </div>
                                        <div class="divide-y divide-gray-100">
                                            @foreach($item['periodes'] as $periode)
                                                <div class="px-3 py-2.5 bg-white">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs font-medium text-gray-700">{{ $periode['bulan_nama'] }} {{ $periode['tahun'] }}</span>
                                                        <span class="text-xs font-bold text-blue-600">Rp {{ number_format($periode['saldo_akhir'], 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex gap-3 mt-1 text-xs">
                                                        <span class="text-green-600">+{{ number_format($periode['total_penerimaan'], 0, ',', '.') }}</span>
                                                        <span class="text-red-600">-{{ number_format($periode['total_penyaluran'], 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('laporan-konsolidasi.detail', $lembaga->id) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Detail Lengkap
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @if($search || $lembagaId || $bulan)
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada data yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Filter
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data</h3>
                        <p class="text-sm text-gray-500">Tidak ada data transaksi untuk tahun {{ $tahun }}.</p>
                    @endif
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleLembaga(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.lembaga-chevron');
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);
    }

    function toggleLembagaMobile(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.mob-chevron');
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-180', isHidden);
    }

    function toggleSearch() {
        var btn       = document.getElementById('search-button');
        var form      = document.getElementById('search-form');
        var input     = document.getElementById('search-input');
        var container = document.getElementById('search-container');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            container.style.minWidth = '280px';
            setTimeout(function() { input.focus(); }, 50);
        } else {
            form.classList.add('hidden');
            btn.classList.remove('hidden');
            container.style.minWidth = '';
        }
    }

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var form      = document.getElementById('search-form');
            var btn       = document.getElementById('search-button');
            var container = document.getElementById('search-container');
            if (!form.classList.contains('hidden')) {
                form.classList.add('hidden');
                btn.classList.remove('hidden');
                container.style.minWidth = '';
            }
        }
    });
</script>
@endpush