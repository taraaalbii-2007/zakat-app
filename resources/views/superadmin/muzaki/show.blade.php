@extends('layouts.app')

@section('title', 'Detail Muzaki - ' . $biodata->muzakki_nama)

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- BREADCRUMB --}}
    <nav class="flex items-center gap-1.5 text-sm text-gray-500">
        <a href="{{ route('muzaki.index') }}" class="hover:text-primary transition-colors">Kelola Muzaki</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 font-medium truncate">{{ $biodata->muzakki_nama }}</span>
    </nav>

    {{-- HEADER CARD --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-primary">
                            {{ strtoupper(substr($biodata->muzakki_nama, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">{{ $biodata->muzakki_nama }}</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ $masjid->nama }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('muzaki.index') }}"
                       class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- BIODATA --}}
        <div class="px-4 sm:px-6 py-4 bg-gray-50 border-b border-gray-100">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-3">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Telepon</p>
                    <p class="text-sm font-medium text-gray-800">{{ $biodata->muzakki_telepon ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email</p>
                    <p class="text-sm font-medium text-gray-800">{{ $biodata->muzakki_email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">NIK</p>
                    <p class="text-sm font-medium text-gray-800">{{ $biodata->muzakki_nik ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Alamat</p>
                    <p class="text-sm font-medium text-gray-800">{{ $biodata->muzakki_alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- SUMMARY STATS --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-gray-100">
            <div class="px-4 sm:px-6 py-4">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($summary->total_transaksi) }}</p>
                <div class="flex gap-2 mt-1.5 flex-wrap">
                    @if($summary->total_verified > 0)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            {{ $summary->total_verified }} verified
                        </span>
                    @endif
                    @if($summary->total_pending > 0)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                            {{ $summary->total_pending }} pending
                        </span>
                    @endif
                    @if($summary->total_rejected > 0)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            {{ $summary->total_rejected }} ditolak
                        </span>
                    @endif
                </div>
            </div>
            <div class="px-4 sm:px-6 py-4">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Total Nominal</p>
                <p class="text-xl sm:text-2xl font-bold text-primary">
                    Rp {{ number_format($summary->total_nominal, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1.5">hanya transaksi verified</p>
            </div>
            <div class="px-4 sm:px-6 py-4">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Transaksi Pertama</p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ $summary->transaksi_pertama ? \Carbon\Carbon::parse($summary->transaksi_pertama)->translatedFormat('d M Y') : '-' }}
                </p>
            </div>
            <div class="px-4 sm:px-6 py-4">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Transaksi Terakhir</p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ $summary->transaksi_terakhir ? \Carbon\Carbon::parse($summary->transaksi_terakhir)->translatedFormat('d M Y') : '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- CONTENT GRID: Breakdown + Riwayat Transaksi --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- BREAKDOWN JENIS ZAKAT --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Breakdown per Jenis Zakat</h3>
                <p class="text-xs text-gray-500 mt-0.5">Hanya transaksi verified</p>
            </div>

            <div class="px-4 sm:px-6 py-4">
                @if($breakdownJenis->isEmpty())
                    <div class="py-8 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Belum ada data breakdown</p>
                    </div>
                @else
                    @php $totalBreakdown = $breakdownJenis->sum('total_nominal'); @endphp
                    <div class="space-y-4">
                        @foreach($breakdownJenis as $item)
                            @php $pct = $totalBreakdown > 0 ? ($item->total_nominal / $totalBreakdown * 100) : 0; @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $item->nama }}</span>
                                    <span class="text-xs font-semibold text-gray-700">
                                        {{ number_format($pct, 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs text-gray-400">{{ $item->jumlah_transaksi }} transaksi</p>
                                    <p class="text-xs font-medium text-gray-600">
                                        Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- RIWAYAT TRANSAKSI --}}
        <div class="lg:col-span-2 bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Riwayat Transaksi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Total {{ number_format($summary->total_transaksi) }} transaksi</p>
            </div>

            @if($transaksi->count() > 0)
                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Zakat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
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
                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="font-mono text-xs text-gray-600">{{ $t->no_transaksi }}</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900">{{ $t->jenis_zakat ?? '-' }}</div>
                                        @if($t->tipe_zakat)
                                            <div class="text-xs text-gray-400">{{ $t->tipe_zakat }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="text-xs text-gray-500">{{ ucfirst($t->metode_pembayaran ?? '-') }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-200">
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
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="font-mono text-xs text-gray-500">{{ $t->no_transaksi }}</span>
                                    <p class="text-sm font-semibold text-gray-900 mt-0.5">
                                        Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
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
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $transaksi->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi</h3>
                    <p class="text-sm text-gray-500">Muzaki ini belum memiliki riwayat transaksi.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection