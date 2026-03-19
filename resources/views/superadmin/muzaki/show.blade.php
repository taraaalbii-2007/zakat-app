@extends('layouts.app')

@section('title', 'Detail Muzaki - ' . $biodata->muzakki_nama)

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- ── Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Muzaki</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap data muzaki</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">

            {{-- ── Profile Header ── --}}
            <div class="pb-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="text-2xl sm:text-3xl font-bold text-primary">
                            {{ strtoupper(substr($biodata->muzakki_nama, 0, 1)) }}
                        </span>
                    </div>
                    <div class="w-full">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $biodata->muzakki_nama }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $lembaga->nama }}</p>
                        {{-- Summary badges --}}
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                {{ number_format($summary->total_transaksi) }} Transaksi
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Rp {{ number_format($summary->total_nominal, 0, ',', '.') }}
                            </span>
                            @if($summary->total_verified > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ $summary->total_verified }} Verified
                                </span>
                            @endif
                            @if($summary->total_pending > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    {{ $summary->total_pending }} Pending
                                </span>
                            @endif
                            @if($summary->total_rejected > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    {{ $summary->total_rejected }} Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tabs ── --}}
            <div class="mt-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                        <button type="button" onclick="switchTab('biodata')" id="tab-biodata"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none">
                            Biodata
                        </button>
                        <button type="button" onclick="switchTab('transaksi')" id="tab-transaksi"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Riwayat Transaksi
                            @if($summary->total_transaksi > 0)
                                <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                    {{ $summary->total_transaksi }}
                                </span>
                            @endif
                        </button>
                        <button type="button" onclick="switchTab('breakdown')" id="tab-breakdown"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Breakdown Zakat
                        </button>
                    </nav>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: Biodata                                               --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                <div id="content-biodata" class="tab-content mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Informasi Pribadi --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Pribadi</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Nama Lengkap</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $biodata->muzakki_nama }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">NIK</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $biodata->muzakki_nik ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Telepon</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $biodata->muzakki_telepon ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $biodata->muzakki_email ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Alamat</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $biodata->muzakki_alamat ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ringkasan Aktivitas --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Ringkasan Aktivitas</h4>
                            <div class="space-y-4">
                                {{-- Total Nominal --}}
                                <div class="p-4 bg-gradient-to-br from-primary/5 to-primary/10 rounded-lg border border-primary/20">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium text-gray-700">Total Nominal (Verified)</span>
                                        <span class="text-sm font-bold text-primary">
                                            Rp {{ number_format($summary->total_nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400">{{ number_format($summary->total_verified) }} transaksi verified</p>
                                </div>

                                {{-- Status Transaksi --}}
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                                    <p class="text-xs font-medium text-gray-700 mb-2">Status Transaksi</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Verified</span>
                                        <span class="text-xs font-semibold text-green-700">{{ number_format($summary->total_verified) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Pending</span>
                                        <span class="text-xs font-semibold text-yellow-700">{{ number_format($summary->total_pending) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Ditolak</span>
                                        <span class="text-xs font-semibold text-red-700">{{ number_format($summary->total_rejected) }}</span>
                                    </div>
                                </div>

                                {{-- Periode --}}
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                                    <p class="text-xs font-medium text-gray-700 mb-2">Periode Transaksi</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Pertama</span>
                                        <span class="text-xs font-semibold text-gray-700">
                                            {{ $summary->transaksi_pertama ? \Carbon\Carbon::parse($summary->transaksi_pertama)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Terakhir</span>
                                        <span class="text-xs font-semibold text-gray-700">
                                            {{ $summary->transaksi_terakhir ? \Carbon\Carbon::parse($summary->transaksi_terakhir)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lembaga --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Lembaga Terkait</h4>
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</p>
                                @if($lembaga->alamat)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $lembaga->alamat }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: Riwayat Transaksi                                     --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                <div id="content-transaksi" class="tab-content hidden mt-6">

                    {{-- Ringkasan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <p class="text-xs text-green-600 font-medium mb-1">Total Nominal (Verified)</p>
                            <p class="text-lg font-bold text-green-700">
                                Rp {{ number_format($summary->total_nominal, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-xs text-blue-600 font-medium mb-1">Total Transaksi</p>
                            <p class="text-lg font-bold text-blue-700">{{ number_format($summary->total_transaksi) }}</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <p class="text-xs text-yellow-600 font-medium mb-1">Menunggu Verifikasi</p>
                            <p class="text-lg font-bold text-yellow-700">{{ number_format($summary->total_pending) }}</p>
                        </div>
                    </div>

                    @if($transaksi->count() > 0)
                        {{-- Desktop Table --}}
                        <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Zakat</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transaksi as $t)
                                        @php
                                            $statusClass = match($t->status) {
                                                'verified' => 'bg-green-100 text-green-700',
                                                'pending'  => 'bg-yellow-100 text-yellow-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                                default    => 'bg-gray-100 text-gray-600',
                                            };
                                            $statusLabel = match($t->status) {
                                                'verified' => 'Verified',
                                                'pending'  => 'Pending',
                                                'rejected' => 'Ditolak',
                                                default    => $t->status,
                                            };
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="font-mono text-xs text-gray-600">{{ $t->no_transaksi }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900">{{ $t->jenis_zakat ?? '-' }}</div>
                                                @if($t->tipe_zakat)
                                                    <div class="text-xs text-gray-400">{{ $t->tipe_zakat }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                {{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                <span class="text-sm font-semibold text-gray-800">
                                                    Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="text-xs text-gray-500">{{ ucfirst($t->metode_pembayaran ?? '-') }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right text-xs font-semibold text-gray-600">
                                            Total Nominal Verified:
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-sm font-bold text-green-700">
                                                Rp {{ number_format($summary->total_nominal, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="md:hidden space-y-3">
                            @foreach($transaksi as $t)
                                @php
                                    $statusClass = match($t->status) {
                                        'verified' => 'bg-green-100 text-green-700',
                                        'pending'  => 'bg-yellow-100 text-yellow-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default    => 'bg-gray-100 text-gray-600',
                                    };
                                    $statusLabel = match($t->status) {
                                        'verified' => 'Verified',
                                        'pending'  => 'Pending',
                                        'rejected' => 'Ditolak',
                                        default    => $t->status,
                                    };
                                @endphp
                                <div class="bg-white border border-gray-200 rounded-xl p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="text-xs font-mono text-gray-500">{{ $t->no_transaksi }}</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">
                                                Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span>{{ $t->jenis_zakat ?? '-' }}</span>
                                        @if($t->tipe_zakat)
                                            <span>&bull; {{ $t->tipe_zakat }}</span>
                                        @endif
                                        <span>{{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') : '-' }}</span>
                                        <span>&bull; {{ ucfirst($t->metode_pembayaran ?? '-') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($transaksi->hasPages())
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                {{ $transaksi->appends(request()->query())->links() }}
                            </div>
                        @endif

                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada riwayat transaksi</h3>
                            <p class="mt-1 text-xs text-gray-400">Transaksi muzaki ini akan tampil di sini.</p>
                        </div>
                    @endif
                </div>

                {{-- ══════════════════════════════════════════════════════════ --}}
                {{-- TAB: Breakdown Zakat                                       --}}
                {{-- ══════════════════════════════════════════════════════════ --}}
                <div id="content-breakdown" class="tab-content hidden mt-6">

                    @if($breakdownJenis->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada data breakdown</h3>
                            <p class="mt-1 text-xs text-gray-400">Data hanya mencakup transaksi verified.</p>
                        </div>
                    @else
                        @php $totalBreakdown = $breakdownJenis->sum('total_nominal'); @endphp

                        {{-- Ringkasan breakdown --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                                <p class="text-xs text-primary font-medium mb-1">Total Jenis Zakat</p>
                                <p class="text-lg font-bold text-primary">{{ $breakdownJenis->count() }} Jenis</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <p class="text-xs text-green-600 font-medium mb-1">Total Nominal</p>
                                <p class="text-lg font-bold text-green-700">
                                    Rp {{ number_format($totalBreakdown, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-xs text-blue-600 font-medium mb-1">Total Transaksi</p>
                                <p class="text-lg font-bold text-blue-700">
                                    {{ number_format($breakdownJenis->sum('jumlah_transaksi')) }}
                                </p>
                            </div>
                        </div>

                        {{-- Progress bars per jenis --}}
                        <div class="rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Distribusi per Jenis Zakat</p>
                                <p class="text-xs text-gray-400 mt-0.5">Hanya transaksi verified</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($breakdownJenis as $item)
                                    @php $pct = $totalBreakdown > 0 ? ($item->total_nominal / $totalBreakdown * 100) : 0; @endphp
                                    <div class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold text-gray-800">{{ $item->nama }}</span>
                                            <span class="text-sm font-bold text-primary">{{ number_format($pct, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                                            <div class="bg-primary h-2 rounded-full transition-all duration-500"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ number_format($item->jumlah_transaksi) }} transaksi</span>
                                            <span class="font-semibold text-gray-700">
                                                Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between">
                                <span class="text-xs font-semibold text-gray-600">Total Keseluruhan</span>
                                <span class="text-sm font-bold text-green-700">
                                    Rp {{ number_format($totalBreakdown, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

            </div>{{-- end tabs --}}
        </div>{{-- end p-4 --}}

        {{-- ── Footer Actions ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('muzaki.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

    </div>{{-- end card --}}
</div>
@endsection

@push('scripts')
<script>
    // ── Tab switching ─────────────────────────────────────────────────────
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
    }
</script>
@endpush