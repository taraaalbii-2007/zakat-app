{{-- resources/views/admin-masjid/program/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Program Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Program Zakat</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap program zakat</p>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                {{-- Profile Header --}}
                <div class="pb-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        @if (!empty($program->foto_kegiatan[0]))
                            <div class="flex-shrink-0">
                                <img src="{{ Storage::url($program->foto_kegiatan[0]) }}" alt="Poster Program"
                                    class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg border-2 border-gray-200">
                            </div>
                        @endif
                        <div class="w-full">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $program->nama_program }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $program->kode_program }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                {!! $program->status_badge !!}
                                @if ($program->target_dana)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Target: Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                    </span>
                                @endif
                                @if ($program->target_mustahik)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $program->target_mustahik }} Mustahik
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="mt-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                            <button type="button" onclick="switchTab('info')" id="tab-info"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none">
                                Informasi Program
                            </button>
                            <button type="button" onclick="switchTab('penerimaan')" id="tab-penerimaan"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Penerimaan Dana
                                @if($totalPenerimaanTrx > 0)
                                    <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                        {{ $totalPenerimaanTrx }}
                                    </span>
                                @endif
                            </button>
                            <button type="button" onclick="switchTab('penyaluran')" id="tab-penyaluran"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Penyaluran
                                @if($totalPenyaluranTrx > 0)
                                    <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        {{ $totalPenyaluranTrx }}
                                    </span>
                                @endif
                            </button>
                            <button type="button" onclick="switchTab('gallery')" id="tab-gallery"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Gallery
                            </button>
                        </nav>
                    </div>

                    {{-- ═══════════════════════════════════════════════════════ --}}
                    {{-- TAB: Informasi Program                                  --}}
                    {{-- ═══════════════════════════════════════════════════════ --}}
                    <div id="content-info" class="tab-content mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Informasi Dasar --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Dasar</h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Periode Program</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $program->tanggal_mulai->format('d M Y') }}
                                                @if ($program->tanggal_selesai)
                                                    - {{ $program->tanggal_selesai->format('d M Y') }}
                                                @else
                                                    <span class="text-green-600">(Berlangsung)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Kode Program</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $program->kode_program }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Target Dana</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                @if ($program->target_dana)
                                                    Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada target spesifik</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Target Mustahik</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                @if ($program->target_mustahik)
                                                    {{ $program->target_mustahik }} orang
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada target spesifik</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Progress --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Progress Pencapaian</h4>
                                <div class="space-y-4">
                                    @if ($program->target_dana)
                                        <div class="p-4 bg-gradient-to-br from-primary/5 to-primary/10 rounded-lg border border-primary/20">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-xs font-medium text-gray-700">Pencapaian Dana</span>
                                                <span class="text-sm font-bold text-primary">{{ $program->progress_dana }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                                <div class="bg-primary h-3 rounded-full transition-all duration-500"
                                                    style="width: {{ min(100, $program->progress_dana) }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-600">
                                                <span class="font-medium">Rp {{ number_format($program->realisasi_dana, 0, ',', '.') }}</span>
                                                <span>Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-gray-700">Dana Terkumpul</span>
                                                <span class="text-sm font-bold text-green-600">Rp {{ number_format($program->realisasi_dana, 0, ',', '.') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1">Dari {{ $totalPenerimaanTrx }} transaksi penerimaan verified</p>
                                        </div>
                                    @endif

                                    @if ($program->target_mustahik)
                                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-xs font-medium text-gray-700">Pencapaian Mustahik</span>
                                                <span class="text-sm font-bold text-green-600">{{ $program->progress_mustahik }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                                <div class="bg-green-500 h-3 rounded-full transition-all duration-500"
                                                    style="width: {{ min(100, $program->progress_mustahik) }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-600">
                                                <span class="font-medium">{{ $program->realisasi_mustahik }} orang</span>
                                                <span>{{ $program->target_mustahik }} orang</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-gray-700">Mustahik Tersalurkan</span>
                                                <span class="text-sm font-bold text-green-600">{{ $program->realisasi_mustahik }} orang</span>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1">Dari {{ $totalPenyaluranTrx }} transaksi penyaluran</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Deskripsi Program</h4>
                            @if ($program->deskripsi)
                                <div class="prose max-w-none text-sm text-gray-700 leading-relaxed">
                                    {!! nl2br(e($program->deskripsi)) !!}
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">Tidak ada deskripsi program</p>
                            @endif

                            @if ($program->catatan)
                                <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-semibold text-yellow-800 mb-1">Catatan Tambahan</p>
                                            <p class="text-sm text-yellow-700">{{ $program->catatan }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Timestamps --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                <div>
                                    <span>Dibuat:</span>
                                    <span class="font-medium text-gray-700">{{ $program->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span>Diperbarui:</span>
                                    <span class="font-medium text-gray-700">{{ $program->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════════════════ --}}
                    {{-- TAB: Penerimaan Dana                                    --}}
                    {{-- ═══════════════════════════════════════════════════════ --}}
                    <div id="content-penerimaan" class="tab-content hidden mt-6">

                        {{-- Ringkasan Penerimaan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <p class="text-xs text-green-600 font-medium mb-1">Total Dana Masuk (Verified)</p>
                                <p class="text-lg font-bold text-green-700">
                                    Rp {{ number_format($totalPenerimaanVerified, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-xs text-blue-600 font-medium mb-1">Total Transaksi</p>
                                <p class="text-lg font-bold text-blue-700">{{ $totalPenerimaanTrx }}</p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <p class="text-xs text-yellow-600 font-medium mb-1">Menunggu Verifikasi</p>
                                <p class="text-lg font-bold text-yellow-700">{{ $totalPenerimaanPending }}</p>
                            </div>
                        </div>

                        @if ($penerimaanList->count() > 0)
                            {{-- Desktop Table --}}
                            <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Muzakki</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Zakat</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($penerimaanList as $trx)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-xs font-mono text-gray-700">{{ $trx->no_transaksi }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                    {{ $trx->tanggal_transaksi->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $trx->muzakki_nama }}</div>
                                                    @if($trx->muzakki_telepon)
                                                        <div class="text-xs text-gray-400">{{ $trx->muzakki_telepon }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600">
                                                    {{ $trx->jenisZakat->nama ?? '-' }}
                                                    @if($trx->tipeZakat)
                                                        <div class="text-gray-400">{{ $trx->tipeZakat->nama }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    {!! $trx->metode_pembayaran_badge !!}
                                                </td>
                                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                                    @if ($trx->jumlah_beras_kg && $trx->jumlah_beras_kg > 0)
                                                        <span class="text-xs font-semibold text-orange-700">
                                                            {{ number_format($trx->jumlah_beras_kg, 1) }} kg beras
                                                        </span>
                                                    @else
                                                        <span class="text-sm font-semibold text-gray-800">
                                                            Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    {!! $trx->status_badge !!}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <a href="{{ route('transaksi-penerimaan.show', $trx->uuid) }}"
                                                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors"
                                                        target="_blank">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="5" class="px-4 py-3 text-right text-xs font-semibold text-gray-600">
                                                Total Dana Verified:
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-sm font-bold text-green-700">
                                                    Rp {{ number_format($totalPenerimaanVerified, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="md:hidden space-y-3">
                                @foreach ($penerimaanList as $trx)
                                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <p class="text-xs font-mono text-gray-500">{{ $trx->no_transaksi }}</p>
                                                <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $trx->muzakki_nama }}</p>
                                            </div>
                                            <div class="text-right">
                                                @if ($trx->jumlah_beras_kg && $trx->jumlah_beras_kg > 0)
                                                    <p class="text-sm font-bold text-orange-700">{{ number_format($trx->jumlah_beras_kg, 1) }} kg</p>
                                                @else
                                                    <p class="text-sm font-bold text-gray-800">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $trx->tanggal_transaksi->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex gap-1.5 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                {!! $trx->metode_pembayaran_badge !!}
                                            </div>
                                            <a href="{{ route('transaksi-penerimaan.show', $trx->uuid) }}"
                                                class="text-xs text-primary hover:underline" target="_blank">Detail →</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada penerimaan dana</h3>
                                <p class="mt-1 text-xs text-gray-400">
                                    Transaksi penerimaan yang dikaitkan dengan program ini akan tampil di sini.
                                </p>
                                @if ($program->status === 'aktif')
                                    <div class="mt-5">
                                        <a href="{{ route('transaksi-penerimaan.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Catat Penerimaan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ═══════════════════════════════════════════════════════ --}}
                    {{-- TAB: Penyaluran                                         --}}
                    {{-- ═══════════════════════════════════════════════════════ --}}
                    <div id="content-penyaluran" class="tab-content hidden mt-6">

                        {{-- Ringkasan Penyaluran --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <p class="text-xs text-green-600 font-medium mb-1">Total Tersalurkan</p>
                                <p class="text-lg font-bold text-green-700">
                                    Rp {{ number_format($totalPenyaluranNominal, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-xs text-blue-600 font-medium mb-1">Mustahik Unik</p>
                                <p class="text-lg font-bold text-blue-700">{{ $totalMustahikUnik }} orang</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                                <p class="text-xs text-purple-600 font-medium mb-1">Total Transaksi</p>
                                <p class="text-lg font-bold text-purple-700">{{ $totalPenyaluranTrx }}</p>
                            </div>
                        </div>

                        @if ($penyaluranList->count() > 0)
                            {{-- Desktop Table --}}
                            <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mustahik</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($penyaluranList as $trx)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-xs font-mono text-gray-700">{{ $trx->no_transaksi }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                    {{ $trx->tanggal_penyaluran->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $trx->mustahik->nama_lengkap ?? '-' }}
                                                    </div>
                                                    @if($trx->amil)
                                                        <div class="text-xs text-gray-400">
                                                            Amil: {{ $trx->amil->pengguna->name ?? $trx->amil->nama_lengkap ?? '-' }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600">
                                                    {{ $trx->kategoriMustahik->nama ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {!! $trx->metode_penyaluran_badge !!}
                                                </td>
                                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                                    @if ($trx->metode_penyaluran === 'barang')
                                                        <span class="text-sm font-semibold text-orange-700">
                                                            {{ $trx->detail_barang ?? 'Barang' }}
                                                        </span>
                                                        @if($trx->nilai_barang)
                                                            <div class="text-xs text-gray-400">≈ Rp {{ number_format($trx->nilai_barang, 0, ',', '.') }}</div>
                                                        @endif
                                                    @else
                                                        <span class="text-sm font-semibold text-gray-800">
                                                            Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    {!! $trx->status_badge !!}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <a href="{{ route('transaksi-penyaluran.show', $trx->uuid) }}"
                                                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors"
                                                        target="_blank">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="5" class="px-4 py-3 text-right text-xs font-semibold text-gray-600">
                                                Total Tersalurkan (Disetujui + Disalurkan):
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-sm font-bold text-green-700">
                                                    Rp {{ number_format($totalPenyaluranNominal, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="md:hidden space-y-3">
                                @foreach ($penyaluranList as $trx)
                                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <p class="text-xs font-mono text-gray-500">{{ $trx->no_transaksi }}</p>
                                                <p class="text-sm font-semibold text-gray-900 mt-0.5">
                                                    {{ $trx->mustahik->nama_lengkap ?? '-' }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $trx->kategoriMustahik->nama ?? '-' }}</p>
                                            </div>
                                            <div class="text-right">
                                                @if ($trx->metode_penyaluran === 'barang')
                                                    <p class="text-sm font-bold text-orange-700">Barang</p>
                                                    @if($trx->nilai_barang)
                                                        <p class="text-xs text-gray-400">≈ Rp {{ number_format($trx->nilai_barang, 0, ',', '.') }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-sm font-bold text-gray-800">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $trx->tanggal_penyaluran->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex gap-1.5 flex-wrap">
                                                {!! $trx->status_badge !!}
                                                {!! $trx->metode_penyaluran_badge !!}
                                            </div>
                                            <a href="{{ route('transaksi-penyaluran.show', $trx->uuid) }}"
                                                class="text-xs text-primary hover:underline" target="_blank">Detail →</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada penyaluran</h3>
                                <p class="mt-1 text-xs text-gray-400">
                                    Transaksi penyaluran yang dikaitkan dengan program ini akan tampil di sini.
                                </p>
                                @if ($program->status === 'aktif')
                                    <div class="mt-5">
                                        <a href="{{ route('transaksi-penyaluran.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Buat Penyaluran
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ═══════════════════════════════════════════════════════ --}}
                    {{-- TAB: Gallery                                             --}}
                    {{-- ═══════════════════════════════════════════════════════ --}}
                    <div id="content-gallery" class="tab-content hidden mt-6">
                        @if (!empty($program->foto_kegiatan) && count($program->foto_kegiatan) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($program->foto_kegiatan as $index => $foto)
                                    <div class="relative group">
                                        <a href="{{ Storage::url($foto) }}" target="_blank"
                                            class="block rounded-lg overflow-hidden border border-gray-200 hover:border-primary transition-all hover:shadow-md">
                                            <img src="{{ Storage::url($foto) }}" alt="Foto Kegiatan {{ $index + 1 }}"
                                                class="object-cover w-full h-48">
                                        </a>
                                        @if (!in_array($program->status, ['selesai', 'dibatalkan']))
                                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button type="button" onclick="deletePhoto({{ $index }})"
                                                    class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if (!in_array($program->status, ['selesai', 'dibatalkan']))
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Tambah Foto Kegiatan</h4>
                                    <form action="{{ route('program-zakat.upload-foto', $program->uuid) }}"
                                        method="POST" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                            <div class="flex-1 w-full">
                                                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary-600 transition-colors">
                                            </div>
                                            <button type="submit"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                Upload Foto
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500">Format: JPEG, PNG, JPG. Maksimal 2MB per file</p>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada foto kegiatan</h3>
                                @if (!in_array($program->status, ['selesai', 'dibatalkan']))
                                    <div class="mt-5">
                                        <form action="{{ route('program-zakat.upload-foto', $program->uuid) }}"
                                            method="POST" enctype="multipart/form-data" class="max-w-sm mx-auto space-y-3">
                                            @csrf
                                            <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary-600 transition-colors">
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                                Upload Foto Pertama
                                            </button>
                                            <p class="text-xs text-gray-500 text-center">Format: JPEG, PNG, JPG. Maksimal 2MB</p>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('program-zakat.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if (!in_array($program->status, ['selesai', 'dibatalkan']))
                            @if ($program->status === 'draft')
                                <button type="button" onclick="showStatusModal('aktif')"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Aktifkan Program
                                </button>
                            @elseif($program->status === 'aktif')
                                <button type="button" onclick="showStatusModal('selesai')"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tandai Selesai
                                </button>
                                <button type="button" onclick="showStatusModal('dibatalkan')"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batalkan Program
                                </button>
                            @endif

                            <a href="{{ route('program-zakat.edit', $program->uuid) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        @endif

                        @if ($program->status === 'draft')
                            <button type="button" onclick="confirmDelete()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Program</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus program
                "<span class="font-semibold text-gray-700">{{ $program->nama_program }}</span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form action="{{ route('program-zakat.destroy', $program->uuid) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Status Change Modal --}}
    <div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 text-center" id="status-modal-title"></h3>
            <form id="status-form" method="POST" action="{{ route('program-zakat.change-status', $program->uuid) }}">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600" id="status-message"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="3" id="status-catatan"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Berikan catatan jika perlu..."></textarea>
                    </div>
                </div>
                <div class="flex justify-center gap-2 sm:gap-3 mt-5 sm:mt-6">
                    <button type="button" onclick="closeStatusModal()"
                        class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-primary text-xs sm:text-sm font-medium text-white hover:bg-primary-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
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

        // ── Delete Program ────────────────────────────────────────────────────
        function confirmDelete() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        // ── Status Modal ──────────────────────────────────────────────────────
        function showStatusModal(status) {
            const messages = {
                aktif:      { title: 'Aktifkan Program',  msg: 'Program akan diaktifkan dan dapat menerima donasi serta penyaluran.' },
                selesai:    { title: 'Tandai Selesai',    msg: 'Program akan ditandai sebagai selesai dan tidak dapat menerima donasi baru.' },
                dibatalkan: { title: 'Batalkan Program',  msg: 'Program akan dibatalkan dan tidak dapat diaktifkan kembali.' },
            };
            const { title, msg } = messages[status] ?? { title: status, msg: '' };

            document.getElementById('status-modal-title').textContent = title;
            document.getElementById('status-message').textContent = msg;

            const form = document.getElementById('status-form');
            form.querySelectorAll('input[name="status"]').forEach(i => i.remove());

            const hiddenInput    = document.createElement('input');
            hiddenInput.type     = 'hidden';
            hiddenInput.name     = 'status';
            hiddenInput.value    = status;
            form.appendChild(hiddenInput);

            document.getElementById('status-modal').classList.remove('hidden');
        }
        function closeStatusModal() {
            document.getElementById('status-modal').classList.add('hidden');
            document.getElementById('status-catatan').value = '';
        }

        // ── Delete Photo ──────────────────────────────────────────────────────
        function deletePhoto(index) {
            if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) return;

            fetch(`/program-zakat/{{ $program->uuid }}/foto/${index}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message || 'Gagal menghapus foto');
            })
            .catch(() => alert('Terjadi kesalahan saat menghapus foto'));
        }

        // ── Close modals on backdrop click ────────────────────────────────────
        ['delete-modal', 'status-modal'].forEach(id => {
            document.getElementById(id).addEventListener('click', function (e) {
                if (e.target === this) this.classList.add('hidden');
            });
        });
    </script>
@endpush