@extends('layouts.app')

@section('title', 'Laporan Konsolidasi')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ─────────────────────────────────────────────────────── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Laporan Konsolidasi</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            {{ $grandTotal['masjid'] }} Masjid · Tahun {{ $tahun }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        {{-- Buka/Tutup Semua --}}
                        <button type="button" onclick="expandAll()"
                            class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            Buka Semua
                        </button>
                        <button type="button" onclick="collapseAll()"
                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                            Tutup Semua
                        </button>

                        {{-- Search --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <form method="GET" action="{{ route('laporan-konsolidasi.index') }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                @if($bulan)<input type="hidden" name="bulan" value="{{ $bulan }}">@endif
                                @if($masjidId)<input type="hidden" name="masjid_id" value="{{ $masjidId }}">@endif
                                <input type="search" name="search" value="{{ $search }}"
                                    placeholder="Cari masjid..."
                                    onchange="this.form.submit()"
                                    class="pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all w-full sm:w-56">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Filter Panel ─────────────────────────────────────────────── --}}
            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('laporan-konsolidasi.index') }}" id="filter-form">
                    @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        {{-- Masjid --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Masjid</label>
                            <select name="masjid_id" onchange="document.getElementById('filter-form').submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Semua Masjid</option>
                                @foreach($allMasjids as $m)
                                    <option value="{{ $m->id }}" {{ $masjidId == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Tahun --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" onchange="document.getElementById('filter-form').submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                                @foreach($availableYears as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Bulan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" onchange="document.getElementById('filter-form').submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Semua Bulan</option>
                                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nm)
                                    <option value="{{ $i + 1 }}" {{ $bulan == ($i + 1) ? 'selected' : '' }}>{{ $nm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($search || $masjidId || $bulan)
                        <div class="mt-2 flex justify-end">
                            <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}"
                                class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- ── Grand Total Cards ─────────────────────────────────────────── --}}
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-br from-primary/5 to-primary/10 border-b border-gray-200">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Masjid</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ number_format($grandTotal['masjid']) }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Penerimaan</p>
                        <p class="text-base font-bold text-green-600 mt-1">Rp {{ number_format($grandTotal['penerimaan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Penyaluran</p>
                        <p class="text-base font-bold text-red-600 mt-1">Rp {{ number_format($grandTotal['penyaluran'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Setor Kas</p>
                        <p class="text-base font-bold text-indigo-600 mt-1">Rp {{ number_format($grandTotal['setor_kas'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saldo Akhir</p>
                        <p class="text-base font-bold text-blue-600 mt-1">Rp {{ number_format($grandTotal['saldo_akhir'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Muzakki / Mustahik</p>
                        <p class="text-base font-bold text-purple-600 mt-1">
                            {{ number_format($grandTotal['muzakki']) }} / {{ number_format($grandTotal['mustahik']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Tabel Utama (Desktop) ─────────────────────────────────────── --}}
            @if(count($laporanPerMasjid) > 0)
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-10 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masjid</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penerimaan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penyaluran</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Setor Kas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Muzakki / Mustahik</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tbody-masjid">
                            @foreach($laporanPerMasjid as $item)
                                @php $masjid = $item['masjid']; @endphp

                                {{-- ── Baris Masjid (Parent) ── --}}
                                <tr class="masjid-row cursor-pointer hover:bg-primary/5 transition-colors"
                                    data-nama="{{ strtolower($masjid->nama) }}"
                                    onclick="toggleMasjid('masjid-{{ $masjid->id }}', this)">
                                    <td class="px-4 py-3">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 masjid-chevron"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $masjid->nama }}</div>
                                                <div class="text-xs text-gray-400">{{ $masjid->kode_masjid }} · Klik untuk lihat per bulan</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-semibold text-green-600">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-semibold text-red-600">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-semibold text-indigo-600">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-blue-600">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-center whitespace-nowrap">
                                        <span class="text-xs text-purple-700 font-semibold">{{ number_format($item['jumlah_muzakki']) }}</span>
                                        <span class="text-xs text-gray-400 mx-1">/</span>
                                        <span class="text-xs text-orange-600 font-semibold">{{ number_format($item['jumlah_mustahik']) }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-center" onclick="event.stopPropagation()">
                                        <a href="{{ route('laporan-konsolidasi.detail', $masjid->id) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>

                                {{-- ── Baris Expandable: Tabel per Bulan ── --}}
                                <tr id="masjid-{{ $masjid->id }}" class="hidden masjid-content-row">
                                    <td colspan="8" class="p-0">
                                        <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="w-1 h-5 bg-primary rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">
                                                    Rincian per Bulan — {{ $masjid->nama }}
                                                </h3>
                                            </div>

                                            @if(count($item['periodes']) === 0)
                                                <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                    Belum ada data transaksi untuk masjid ini pada periode yang dipilih
                                                </div>
                                            @else
                                                <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-white">
                                                            <tr>
                                                                <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Penerimaan</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Penyaluran</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Setor Kas</th>
                                                                <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Saldo</th>
                                                                <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Muzakki</th>
                                                                <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Mustahik</th>
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
                                                                    <td class="px-4 py-2.5 text-right">
                                                                        <span class="text-sm text-indigo-600 font-medium">Rp {{ number_format($periode['total_setor_kas'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right">
                                                                        <span class="text-sm text-blue-700 font-bold">Rp {{ number_format($periode['saldo_akhir'], 0, ',', '.') }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-center">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                            {{ number_format($periode['jumlah_muzakki']) }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-center">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                            {{ number_format($periode['jumlah_mustahik']) }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                                            <tr>
                                                                <td class="px-4 py-2.5 text-xs font-bold text-gray-700 uppercase">Subtotal</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-green-700">Rp {{ number_format($item['total_penerimaan'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-red-700">Rp {{ number_format($item['total_penyaluran'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-indigo-700">Rp {{ number_format($item['total_setor_kas'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-right text-sm font-bold text-blue-800">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</td>
                                                                <td class="px-4 py-2.5 text-center text-sm font-bold text-purple-800">{{ number_format($item['jumlah_muzakki']) }}</td>
                                                                <td class="px-4 py-2.5 text-center text-sm font-bold text-orange-800">{{ number_format($item['jumlah_mustahik']) }}</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                {{-- Tombol Detail --}}
                                                <div class="mt-3 flex justify-end">
                                                    <a href="{{ route('laporan-konsolidasi.detail', $masjid->id) }}"
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

                {{-- ── Mobile View ───────────────────────────────────────────── --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach($laporanPerMasjid as $item)
                        @php $masjid = $item['masjid']; @endphp
                        <div>
                            <div class="p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                                onclick="toggleMasjidMobile('mob-{{ $masjid->id }}', this)">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $masjid->nama }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Saldo: <span class="font-semibold text-blue-600">Rp {{ number_format($item['saldo_akhir'], 0, ',', '.') }}</span></p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 mob-chevron flex-shrink-0 ml-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div id="mob-{{ $masjid->id }}" class="hidden bg-gray-50 border-t border-gray-100 px-4 py-4 space-y-3">
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

                                {{-- Rincian per Bulan Mobile --}}
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
                                                    <div class="flex gap-3 mt-1 text-xs text-gray-500">
                                                        <span class="text-green-600">+{{ number_format($periode['total_penerimaan'], 0, ',', '.') }}</span>
                                                        <span class="text-red-600">-{{ number_format($periode['total_penyaluran'], 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('laporan-konsolidasi.detail', $masjid->id) }}"
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
                {{-- Empty State --}}
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data</h3>
                    <p class="text-sm text-gray-500">Tidak ada data transaksi yang ditemukan untuk filter yang dipilih.</p>
                    @if($search || $masjidId || $bulan)
                        <a href="{{ route('laporan-konsolidasi.index', ['tahun' => $tahun]) }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                            Reset Filter
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ── Toggle per masjid (desktop) ───────────────────────────────────────
    function toggleMasjid(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.masjid-chevron');
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);
    }

    // ── Toggle per masjid (mobile) ────────────────────────────────────────
    function toggleMasjidMobile(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.mob-chevron');
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-180', isHidden);
    }

    // ── Buka semua ────────────────────────────────────────────────────────
    function expandAll() {
        document.querySelectorAll('.masjid-content-row').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('.masjid-chevron').forEach(el => el.classList.add('rotate-90'));
    }

    // ── Tutup semua ───────────────────────────────────────────────────────
    function collapseAll() {
        document.querySelectorAll('.masjid-content-row').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.masjid-chevron').forEach(el => el.classList.remove('rotate-90'));
    }
</script>
@endpush