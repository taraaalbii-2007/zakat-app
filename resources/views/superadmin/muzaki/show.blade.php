@extends('layouts.app')

@section('title', 'Detail Muzaki - ' . $biodata->muzakki_nama)

@section('content')
<div class="p-6 space-y-6">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('superadmin.muzaki.index') }}" class="hover:text-[#2d6a2d] transition-colors">Kelola Muzaki</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 font-medium">{{ $biodata->muzakki_nama }}</span>
    </div>

    {{-- HEADER + BIODATA --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start gap-5">
            {{-- Avatar --}}
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <span class="text-2xl font-bold text-green-700">
                    {{ strtoupper(substr($biodata->muzakki_nama, 0, 1)) }}
                </span>
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-800">{{ $biodata->muzakki_nama }}</h1>
                <p class="text-sm text-[#2d6a2d] font-medium mt-0.5">{{ $masjid->nama }}</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <p class="text-xs text-gray-400">Telepon</p>
                        <p class="text-sm font-medium text-gray-700">{{ $biodata->muzakki_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="text-sm font-medium text-gray-700">{{ $biodata->muzakki_email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">NIK</p>
                        <p class="text-sm font-medium text-gray-700">{{ $biodata->muzakki_nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Alamat</p>
                        <p class="text-sm font-medium text-gray-700">{{ $biodata->muzakki_alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Back button --}}
            <a href="{{ route('superadmin.muzaki.index') }}"
               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- SUMMARY STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-1">
            <p class="text-xs text-gray-400">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary->total_transaksi) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-1">
            <p class="text-xs text-gray-400">Verified</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($summary->total_verified) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-1">
            <p class="text-xs text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-500 mt-1">{{ number_format($summary->total_pending) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-1">
            <p class="text-xs text-gray-400">Ditolak</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ number_format($summary->total_rejected) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-2 md:col-span-2">
            <p class="text-xs text-gray-400">Total Nominal (Verified)</p>
            <p class="text-lg font-bold text-green-700 mt-1">Rp {{ number_format($summary->total_nominal, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-2 md:col-span-2 lg:col-span-2">
            <p class="text-xs text-gray-400">Periode</p>
            <p class="text-sm font-semibold text-gray-700 mt-1">
                {{ $summary->transaksi_pertama ? \Carbon\Carbon::parse($summary->transaksi_pertama)->translatedFormat('d M Y') : '-' }}
            </p>
            <p class="text-xs text-gray-400">s/d</p>
            <p class="text-sm font-semibold text-gray-700">
                {{ $summary->transaksi_terakhir ? \Carbon\Carbon::parse($summary->transaksi_terakhir)->translatedFormat('d M Y') : '-' }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- BREAKDOWN JENIS ZAKAT --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Breakdown per Jenis Zakat</h2>
            @if($breakdownJenis->isEmpty())
                <p class="text-sm text-gray-400 text-center py-4">Belum ada data</p>
            @else
                <div class="space-y-3">
                    @php $totalBreakdown = $breakdownJenis->sum('total_nominal'); @endphp
                    @foreach($breakdownJenis as $item)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-700">{{ $item->nama }}</span>
                                <span class="text-xs font-semibold text-gray-700">
                                    Rp {{ number_format($item->total_nominal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                @php $pct = $totalBreakdown > 0 ? ($item->total_nominal / $totalBreakdown * 100) : 0; @endphp
                                <div class="h-full bg-[#2d6a2d] rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->jumlah_transaksi }} transaksi &bull; {{ number_format($pct, 1) }}%</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- RIWAYAT TRANSAKSI --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Riwayat Transaksi</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Zakat</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transaksi as $t)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="font-mono text-xs text-gray-600">{{ $t->no_transaksi }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-gray-700">{{ $t->jenis_zakat ?? '-' }}</p>
                                    @if($t->tipe_zakat)
                                        <p class="text-xs text-gray-400">{{ $t->tipe_zakat }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-gray-700">
                                        {{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') : '-' }}
                                    </p>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs text-gray-500">
                                        {{ ucfirst($t->metode_pembayaran ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium {{ $statusClass }} rounded-full">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    Tidak ada riwayat transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($transaksi->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $transaksi->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection