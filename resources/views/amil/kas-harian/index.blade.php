{{-- resources/views/amil/kas-harian/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kas Harian')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Kas Harian</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola kas harian Anda</p>
        </div>
        {{-- Date Picker --}}
        <form method="GET" action="{{ route('kas-harian.index') }}" class="flex items-center gap-2">
            <input type="date"
                   name="tanggal"
                   value="{{ $tanggal->format('Y-m-d') }}"
                   max="{{ now()->format('Y-m-d') }}"
                   onchange="this.form.submit()"
                   class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
        </form>
    </div>

    {{-- ===== ALERT MESSAGES ===== --}}
    @if(session('success'))
        <div class="flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    {{-- ===== BELUM BUKA KAS ===== --}}
    @if($belumBukaKas)
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Kas Hari Ini Belum Dibuka</h3>
                <p class="text-sm text-gray-500 mb-2">Tanggal: <strong>{{ $tanggal->translatedFormat('l, d F Y') }}</strong></p>
                <p class="text-sm text-gray-500 mb-6">
                    Saldo awal yang akan digunakan:
                    <span class="font-semibold text-gray-800">Rp {{ number_format($saldoAwalEstimasi, 0, ',', '.') }}</span>
                    <span class="text-gray-400">(dari saldo akhir hari kemarin)</span>
                </p>
                <form action="{{ route('kas-harian.buka') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        Buka Kas Hari Ini
                    </button>
                </form>
            </div>
        </div>

    @elseif($kas)
    {{-- ===== KAS ADA ===== --}}

        {{-- Status Bar --}}
        <div class="flex items-center justify-between px-4 py-2.5 rounded-xl border
            {{ $kas->isOpen ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
            <div class="flex items-center gap-2 text-sm font-medium
                {{ $kas->isOpen ? 'text-green-700' : 'text-gray-600' }}">
                <span class="w-2 h-2 rounded-full {{ $kas->isOpen ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                Status Kas: {!! $kas->status_badge !!}
                <span class="font-normal text-gray-500 ml-1">— {{ $tanggal->translatedFormat('l, d F Y') }}</span>
            </div>
            @if($kas->isClosed && $kas->tanggal->isToday())
                <form action="{{ route('kas-harian.buka-kembali', $kas->uuid) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Yakin ingin membuka kembali kas ini?')"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium underline transition-colors">
                        Buka Kembali
                    </button>
                </form>
            @endif
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @php
            $cards = [
                ['label' => 'Saldo Awal', 'value' => $kas->saldo_awal_formatted, 'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-1m6 1l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-1m0-1v1m0 10v1', 'color' => 'blue'],
                ['label' => 'Total Penerimaan', 'value' => $kas->total_penerimaan_formatted, 'icon' => 'M7 11l5-5m0 0l5 5m-5-5v12', 'color' => 'green'],
                ['label' => 'Total Penyaluran', 'value' => $kas->total_penyaluran_formatted, 'icon' => 'M17 13l-5 5m0 0l-5-5m5 5V6', 'color' => 'orange'],
                ['label' => 'Saldo Akhir', 'value' => $kas->saldo_akhir_formatted, 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'color' => 'purple'],
            ];
            $colorMap = [
                'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-500',   'text' => 'text-blue-700'],
                'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-500',  'text' => 'text-green-700'],
                'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-500', 'text' => 'text-orange-700'],
                'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-500', 'text' => 'text-purple-700'],
            ];
            @endphp

            @foreach($cards as $card)
            @php $c = $colorMap[$card['color']]; @endphp
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-card p-4 sm:p-5">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">{{ $card['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-base sm:text-lg font-bold {{ $c['text'] }}">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Counter Transaksi --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
            $counters = [
                ['label' => 'Transaksi Masuk', 'value' => $kas->jumlah_transaksi_masuk, 'color' => 'text-green-600'],
                ['label' => 'Transaksi Keluar', 'value' => $kas->jumlah_transaksi_keluar, 'color' => 'text-red-600'],
                ['label' => 'Penjemputan', 'value' => $kas->jumlah_penjemputan, 'color' => 'text-purple-600'],
                ['label' => 'Datang Langsung', 'value' => $kas->jumlah_datang_langsung, 'color' => 'text-blue-600'],
            ];
            @endphp
            @foreach($counters as $c)
            <div class="bg-white rounded-xl border border-gray-100 shadow-card p-3 text-center">
                <p class="text-2xl font-bold {{ $c['color'] }}">{{ $c['value'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $c['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Tabel Transaksi Penerimaan --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-sm sm:text-base font-semibold text-gray-900">Transaksi Penerimaan Hari Ini</h2>
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2.5 py-1 rounded-full">
                    {{ $transaksiPenerimaan->count() }} transaksi
                </span>
            </div>
            @if($transaksiPenerimaan->isEmpty())
                <div class="py-8 text-center text-sm text-gray-400">Belum ada transaksi penerimaan hari ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Muzakki</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Zakat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($transaksiPenerimaan as $trx)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $trx->no_transaksi }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $trx->muzakki_nama }}</p>
                                    <div class="mt-0.5">{!! $trx->metode_penerimaan_badge !!}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $trx->jenisZakat->nama ?? '-' }}
                                    @if($trx->tipeZakat)
                                        <span class="text-gray-400">· {{ $trx->tipeZakat->nama }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{!! $trx->metode_pembayaran_badge !!}</td>
                                <td class="px-4 py-3 text-right font-semibold text-green-700">
                                    {{ $trx->jumlah_formatted }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('transaksi-penerimaan.show', $trx->uuid) }}"
                                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">Total Penerimaan:</td>
                                <td class="px-4 py-3 text-right font-bold text-green-700">
                                    Rp {{ number_format($transaksiPenerimaan->sum('jumlah'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        {{-- Tabel Transaksi Penyaluran --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-sm sm:text-base font-semibold text-gray-900">Transaksi Penyaluran Hari Ini</h2>
                <span class="text-xs font-medium bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full">
                    {{ $transaksiPenyaluran->count() }} transaksi
                </span>
            </div>
            @if($transaksiPenyaluran->isEmpty())
                <div class="py-8 text-center text-sm text-gray-400">Belum ada transaksi penyaluran hari ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Transaksi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mustahik</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($transaksiPenyaluran as $trx)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $trx->no_transaksi }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $trx->mustahik->nama_lengkap ?? '-' }}</p>
                                    {!! $trx->status_badge !!}
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $trx->kategoriMustahik->nama ?? '-' }}</td>
                                <td class="px-4 py-3">{!! $trx->metode_penyaluran_badge !!}</td>
                                <td class="px-4 py-3 text-right font-semibold text-orange-700">
                                    {{ $trx->jumlah_formatted }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('transaksi-penyaluran.show', $trx->uuid) }}"
                                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">Total Penyaluran:</td>
                                <td class="px-4 py-3 text-right font-bold text-orange-700">
                                    Rp {{ number_format($transaksiPenyaluran->sum('jumlah'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        {{-- Catatan & Tutup Kas --}}
        @if($kas->isOpen && $tanggal->isToday())
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-sm sm:text-base font-semibold text-gray-900">Catatan & Tutup Kas</h2>
            </div>
            <div class="p-4 sm:p-6">
                <form action="{{ route('kas-harian.tutup') }}" method="POST" id="form-tutup-kas">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Kas Harian</label>
                        <textarea name="catatan" rows="3"
                            placeholder="Tambahkan catatan untuk kas hari ini (opsional)..."
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none">{{ $kas->catatan }}</textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button"
                            onclick="simpanCatatan()"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Simpan Catatan
                        </button>
                        <button type="submit"
                            onclick="return confirm('Yakin ingin menutup kas hari ini? Setelah ditutup, saldo akhir tidak bisa berubah kecuali kas dibuka kembali.')"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-xl transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Tutup Kas
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($kas->isClosed)
        {{-- Tampilkan catatan saja jika kas sudah closed --}}
        @if($kas->catatan)
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-sm sm:text-base font-semibold text-gray-900">Catatan</h2>
            </div>
            <div class="p-4 sm:p-6">
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $kas->catatan }}</p>
                @if($kas->closed_at)
                    <p class="text-xs text-gray-400 mt-3">Ditutup pada: {{ $kas->closed_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>
        @endif
        @endif

    @else
    {{-- Tanggal lain yang tidak ada kasnya --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-base font-medium text-gray-900 mb-2">Tidak Ada Data Kas</h3>
            <p class="text-sm text-gray-500">Tidak ada kas yang tercatat pada tanggal <strong>{{ $tanggal->translatedFormat('d F Y') }}</strong>.</p>
        </div>
    </div>
    @endif

    {{-- ===== RIWAYAT 7 HARI TERAKHIR ===== --}}
    @if($riwayat7Hari->count() > 0)
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden" x-data="{ open: false }">
        <button @click="open = !open"
            class="w-full px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
            <h2 class="text-sm sm:text-base font-semibold text-gray-900">Riwayat 7 Hari Terakhir</h2>
            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-collapse>
            <div class="overflow-x-auto border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Saldo Awal</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Penerimaan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Penyaluran</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Saldo Akhir</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($riwayat7Hari as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $r->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $r->saldo_awal_formatted }}</td>
                            <td class="px-4 py-3 text-right text-green-700 font-medium">{{ $r->total_penerimaan_formatted }}</td>
                            <td class="px-4 py-3 text-right text-orange-700 font-medium">{{ $r->total_penyaluran_formatted }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ $r->saldo_akhir_formatted }}</td>
                            <td class="px-4 py-3 text-center">{!! $r->status_badge !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

</div>
@endsection

@push('scripts')
<script>
function simpanCatatan() {
    const form = document.getElementById('form-tutup-kas');
    const formData = new FormData(form);

    fetch('{{ route("kas-harian.simpan-catatan") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => {
        if (res.ok || res.redirected) {
            window.location.href = res.url || '{{ route("kas-harian.index") }}';
        }
    })
    .catch(() => {
        // Fallback: submit form biasa ke route simpan catatan
        form.action = '{{ route("kas-harian.simpan-catatan") }}';
        form.submit();
    });
}
</script>
@endpush