{{-- resources/views/amil/transaksi-penyaluran/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Transaksi Penyaluran')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ============================================================
         PANEL MENUNGGU PERSETUJUAN ADMIN
         Tampil jika status masih draft
         ============================================================ --}}
    @if($transaksi->status === 'draft')
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="px-4 sm:px-6 py-3 border-b border-yellow-200 bg-yellow-100/60 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="text-sm font-semibold text-yellow-800">Menunggu Persetujuan Admin Masjid</h3>
        </div>
        <div class="p-4 sm:p-6">
            <p class="text-sm text-yellow-800 mb-4">
                Transaksi penyaluran ini masih berstatus <strong>Draft</strong> dan perlu disetujui oleh Admin Masjid sebelum dapat dikonfirmasi penyalurannya.
            </p>
            @can('admin_masjid')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Form Approve --}}
                <form method="POST" action="{{ route('admin.transaksi-penyaluran.approve', $transaksi->uuid) }}">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Setujui transaksi penyaluran ini?')"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Setujui Penyaluran
                    </button>
                </form>
                {{-- Form Tolak --}}
                <form method="POST" action="{{ route('admin.transaksi-penyaluran.reject', $transaksi->uuid) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-yellow-700 mb-1.5">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <input type="text" name="alasan_pembatalan" required
                            placeholder="Contoh: Data mustahik belum lengkap"
                            class="block w-full px-3 py-2 text-sm border border-yellow-300 bg-white rounded-lg focus:outline-none focus:border-red-400 focus:ring-0 placeholder:text-gray-400">
                    </div>
                    <button type="submit"
                        onclick="return confirm('Tolak penyaluran ini?')"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak Penyaluran
                    </button>
                </form>
            </div>
            @else
            <p class="text-xs text-yellow-700">Hubungi Admin Masjid untuk proses persetujuan.</p>
            @endcan
        </div>
    </div>
    @endif

    {{-- Panel: Disetujui --}}
    @if($transaksi->status === 'disetujui')
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-blue-800">Penyaluran Telah Disetujui — Siap Disalurkan</p>
            @if($transaksi->approvedBy)
            <p class="text-xs text-blue-700 mt-0.5">
                Oleh: <strong>{{ $transaksi->approvedBy->nama ?? $transaksi->approvedBy->name }}</strong>
                @if($transaksi->approved_at)· {{ \Carbon\Carbon::parse($transaksi->approved_at)->translatedFormat('d F Y H:i') }}@endif
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Panel: Disalurkan --}}
    @if($transaksi->status === 'disalurkan')
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-green-800">Zakat Telah Berhasil Disalurkan</p>
            @if($transaksi->disalurkanOleh)
            <p class="text-xs text-green-700 mt-0.5">
                Dikonfirmasi oleh: <strong>{{ $transaksi->disalurkanOleh->nama ?? $transaksi->disalurkanOleh->name }}</strong>
                @if($transaksi->disalurkan_at)· {{ \Carbon\Carbon::parse($transaksi->disalurkan_at)->translatedFormat('d F Y H:i') }}@endif
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Panel: Dibatalkan --}}
    @if($transaksi->status === 'dibatalkan')
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-red-800">Penyaluran Dibatalkan</p>
            @if($transaksi->alasan_pembatalan)
            <p class="text-xs text-red-700 mt-0.5">Alasan: {{ $transaksi->alasan_pembatalan }}</p>
            @endif
            @if($transaksi->dibatalkanOleh && $transaksi->dibatalkan_at)
            <p class="text-xs text-red-600 mt-0.5">
                Oleh: {{ $transaksi->dibatalkanOleh->nama ?? $transaksi->dibatalkanOleh->name }}
                · {{ \Carbon\Carbon::parse($transaksi->dibatalkan_at)->translatedFormat('d F Y H:i') }}
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- ============================================================
         MAIN CARD
         ============================================================ --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Detail Transaksi Penyaluran</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap transaksi penyaluran zakat</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <a href="{{ route('transaksi-penyaluran.index') }}"
                        class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>

                    @if($transaksi->status === 'disalurkan')
                    <a href="{{ route('transaksi-penyaluran.cetak', $transaksi->uuid) }}" target="_blank"
                        class="inline-flex items-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Kwitansi
                    </a>
                    @endif

                    @if($transaksi->status === 'draft')
                    @can('amil')
                    <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                        class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    @endcan
                    @endif

                    {{-- Tombol Konfirmasi Disalurkan --}}
                    @if($transaksi->status === 'disetujui')
                    @can('amil')
                    <form method="POST" action="{{ route('transaksi-penyaluran.konfirmasi', $transaksi->uuid) }}" class="inline">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Konfirmasi bahwa zakat sudah benar-benar diterima oleh mustahik?')"
                            class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Konfirmasi Disalurkan
                        </button>
                    </form>
                    @endcan
                    @endif
                </div>
            </div>
        </div>

        {{-- Content Body --}}
        <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

            {{-- No Transaksi & Badges --}}
            <div class="space-y-3">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $transaksi->nomor_transaksi }}</h2>
                <div class="flex flex-wrap items-center gap-2">
                    @php
                        $statusBadge = [
                            'draft'      => 'bg-yellow-100 text-yellow-800 border-yellow-200|Draft',
                            'disetujui'  => 'bg-blue-100 text-blue-800 border-blue-200|Disetujui',
                            'disalurkan' => 'bg-green-100 text-green-800 border-green-200|✓ Disalurkan',
                            'dibatalkan' => 'bg-red-100 text-red-800 border-red-200|✕ Dibatalkan',
                        ];
                        $metodeBadge = [
                            'tunai'    => 'bg-green-100 text-green-800 border-green-200|Tunai',
                            'transfer' => 'bg-blue-100 text-blue-800 border-blue-200|Transfer',
                            'barang'   => 'bg-orange-100 text-orange-800 border-orange-200|Barang',
                        ];
                        [$sbClass, $sbLabel] = explode('|', $statusBadge[$transaksi->status] ?? 'bg-gray-100 text-gray-800 border-gray-200|' . ucfirst($transaksi->status));
                        [$mbClass, $mbLabel] = explode('|', $metodeBadge[$transaksi->metode_penyaluran] ?? 'bg-gray-100 text-gray-800 border-gray-200|' . ucfirst($transaksi->metode_penyaluran));
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $sbClass }}">{{ $sbLabel }}</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $mbClass }}">{{ $mbLabel }}</span>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Info Cards (3 kolom) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Penyaluran</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($transaksi->tanggal_penyaluran)->format('d F Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $transaksi->waktu_penyaluran ? \Carbon\Carbon::parse($transaksi->waktu_penyaluran)->format('H:i') . ' WIB' : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Penyaluran</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div>
                            @if($transaksi->metode_penyaluran !== 'barang')
                            <p class="font-semibold text-primary">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                            @else
                            <p class="font-semibold text-orange-600 text-xs leading-tight">{{ $transaksi->detail_barang ?? 'Barang In-Kind' }}</p>
                            @if($transaksi->nilai_barang)
                            <p class="text-xs text-gray-500">≈ Rp {{ number_format($transaksi->nilai_barang, 0, ',', '.') }}</p>
                            @endif
                            @endif
                            <p class="text-xs text-gray-500">{{ ucfirst($transaksi->metode_penyaluran) }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis Zakat</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <div>
                            @if($transaksi->jenisZakat)
                            <p class="font-medium">{{ $transaksi->jenisZakat->nama }}</p>
                            @else
                            <p class="text-gray-400 italic">Belum diisi</p>
                            @endif
                            @if($transaksi->periode)
                            @php
                                [$thn, $bln] = explode('-', $transaksi->periode);
                                $nb = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                            @endphp
                            <p class="text-xs text-gray-500">{{ $nb[(int)$bln] }} {{ $thn }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Informasi Mustahik --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Informasi Mustahik</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $transaksi->mustahik->nama_lengkap ?? '-' }}</p>
                        </div>
                        @if($transaksi->mustahik?->nik)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">NIK</label>
                            <p class="text-sm text-gray-900">{{ $transaksi->mustahik->nik }}</p>
                        </div>
                        @endif
                        @if($transaksi->kategoriMustahik)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kategori Mustahik</label>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                {{ $transaksi->kategoriMustahik->nama }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @if($transaksi->mustahik?->telepon)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Telepon</label>
                            <div class="flex items-center text-sm text-gray-900 gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $transaksi->mustahik->telepon }}
                            </div>
                        </div>
                        @endif
                        @if($transaksi->mustahik?->alamat)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat</label>
                            <div class="flex items-start text-sm text-gray-900 gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $transaksi->mustahik->alamat }}</span>
                            </div>
                        </div>
                        @endif
                        <div>
                            <a href="{{ route('mustahik.show', $transaksi->mustahik->uuid) }}"
                                class="inline-flex items-center text-xs text-primary hover:underline">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Lihat Profil Mustahik
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Detail Zakat --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Detail Penyaluran</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        @if($transaksi->programZakat)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Program Zakat</label>
                            <p class="text-sm text-gray-900">{{ $transaksi->programZakat->nama }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jumlah Penyaluran</label>
                            @if($transaksi->metode_penyaluran !== 'barang')
                            <p class="text-sm font-semibold text-primary">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                            @else
                            <p class="text-sm text-gray-900">{{ $transaksi->detail_barang ?? '-' }}</p>
                            @if($transaksi->nilai_barang)
                            <p class="text-xs text-gray-600 mt-0.5">≈ Rp {{ number_format($transaksi->nilai_barang, 0, ',', '.') }}</p>
                            @endif
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Metode Penyaluran</label>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $mbClass }}">{{ $mbLabel }}</span>
                            @if($transaksi->metode_penyaluran === 'transfer')
                            <div class="mt-2 space-y-1 text-xs text-gray-600">
                                @if($transaksi->nama_bank)<p>Bank: <span class="font-medium text-gray-900">{{ $transaksi->nama_bank }}</span></p>@endif
                                @if($transaksi->nomor_rekening)<p>No. Rek: <span class="font-mono text-gray-900">{{ $transaksi->nomor_rekening }}</span></p>@endif
                                @if($transaksi->nama_pemilik_rekening)<p>A/N: <span class="text-gray-900">{{ $transaksi->nama_pemilik_rekening }}</span></p>@endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Amil Penyalur</label>
                            @if($transaksi->amil)
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-md flex-shrink-0 bg-primary/10 flex items-center justify-center">
                                    @if($transaksi->amil->foto_url ?? null)
                                    <img src="{{ $transaksi->amil->foto_url }}" alt="Foto Amil" class="w-full h-full object-cover">
                                    @else
                                    <span class="text-primary font-bold">{{ strtoupper(substr($transaksi->amil->nama_lengkap ?? 'A', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaksi->amil->nama_lengkap ?? '-' }}</p>
                                    @if($transaksi->amil->kode_amil ?? null)<p class="text-xs text-gray-500">Kode: {{ $transaksi->amil->kode_amil }}</p>@endif
                                </div>
                            </div>
                            @else
                            <p class="text-sm text-gray-500 italic">Tidak ada amil yang ditugaskan</p>
                            @endif
                        </div>
                        @if($transaksi->keterangan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Keterangan</label>
                            <p class="text-sm text-gray-700">{{ $transaksi->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Riwayat Status & Status Transaksi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Riwayat Status --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Riwayat Status</h4>
                    <ol class="relative border-l border-gray-200 space-y-5 ml-3">
                        <li class="pl-6">
                            <div class="absolute -left-1.5 w-3 h-3 bg-gray-400 rounded-full border-2 border-white"></div>
                            <p class="text-xs font-semibold text-gray-900">Dibuat (Draft)</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $transaksi->created_at->translatedFormat('d F Y H:i') }}</p>
                            @if($transaksi->amil)<p class="text-xs text-gray-500">oleh {{ $transaksi->amil->nama }}</p>@endif
                        </li>
                        @if($transaksi->approved_at)
                        <li class="pl-6">
                            <div class="absolute -left-1.5 w-3 h-3 bg-blue-500 rounded-full border-2 border-white"></div>
                            <p class="text-xs font-semibold text-gray-900">Disetujui</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($transaksi->approved_at)->translatedFormat('d F Y H:i') }}</p>
                            @if($transaksi->approvedBy)<p class="text-xs text-gray-500">oleh {{ $transaksi->approvedBy->nama ?? $transaksi->approvedBy->name }}</p>@endif
                        </li>
                        @endif
                        @if($transaksi->disalurkan_at)
                        <li class="pl-6">
                            <div class="absolute -left-1.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                            <p class="text-xs font-semibold text-gray-900">Dikonfirmasi Disalurkan</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($transaksi->disalurkan_at)->translatedFormat('d F Y H:i') }}</p>
                            @if($transaksi->disalurkanOleh)<p class="text-xs text-gray-500">oleh {{ $transaksi->disalurkanOleh->nama ?? $transaksi->disalurkanOleh->name }}</p>@endif
                        </li>
                        @endif
                        @if($transaksi->dibatalkan_at)
                        <li class="pl-6">
                            <div class="absolute -left-1.5 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
                            <p class="text-xs font-semibold text-gray-900">Dibatalkan</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($transaksi->dibatalkan_at)->translatedFormat('d F Y H:i') }}</p>
                            @if($transaksi->dibatalkanOleh)<p class="text-xs text-gray-500">oleh {{ $transaksi->dibatalkanOleh->nama ?? $transaksi->dibatalkanOleh->name }}</p>@endif
                            @if($transaksi->alasan_pembatalan)
                            <div class="mt-1 bg-red-50 border border-red-200 rounded-lg p-2">
                                <p class="text-xs text-red-700">{{ $transaksi->alasan_pembatalan }}</p>
                            </div>
                            @endif
                        </li>
                        @endif
                    </ol>
                </div>

                {{-- Status Transaksi --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Status Transaksi</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1.5">Status Penyaluran</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $sbClass }}">{{ $sbLabel }}</span>
                        </div>

                        @if($transaksi->approvedBy || $transaksi->approved_at)
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Disetujui Oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $transaksi->approvedBy->nama ?? $transaksi->approvedBy->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $transaksi->approved_at ? \Carbon\Carbon::parse($transaksi->approved_at)->format('d F Y H:i') : '-' }}</p>
                        </div>
                        @endif

                        @if($transaksi->disalurkanOleh || $transaksi->disalurkan_at)
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Dikonfirmasi Disalurkan Oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $transaksi->disalurkanOleh->nama ?? $transaksi->disalurkanOleh->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $transaksi->disalurkan_at ? \Carbon\Carbon::parse($transaksi->disalurkan_at)->format('d F Y H:i') : '-' }}</p>
                        </div>
                        @endif

                        @if($transaksi->status === 'dibatalkan' && $transaksi->alasan_pembatalan)
                        <div class="pt-3 border-t border-gray-200">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-1">Alasan Pembatalan</p>
                                <p class="text-sm text-red-700">{{ $transaksi->alasan_pembatalan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bukti & Dokumentasi --}}
            @if($transaksi->foto_bukti || $transaksi->path_tanda_tangan || $transaksi->dokumentasi->count() > 0)
            <hr class="border-gray-200">
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Bukti & Dokumentasi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($transaksi->foto_bukti)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Foto Bukti Penyaluran</label>
                        <a href="{{ Storage::url($transaksi->foto_bukti) }}" target="_blank"
                           class="inline-block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <img src="{{ Storage::url($transaksi->foto_bukti) }}" alt="Bukti Penyaluran" class="h-48 w-auto object-cover">
                        </a>
                    </div>
                    @endif
                    @if($transaksi->path_tanda_tangan)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Tanda Tangan Mustahik</label>
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 inline-block">
                            <img src="{{ Storage::url($transaksi->path_tanda_tangan) }}" alt="Tanda Tangan" class="h-24 object-contain">
                        </div>
                    </div>
                    @endif
                    @if($transaksi->dokumentasi->count() > 0)
                    <div class="{{ ($transaksi->foto_bukti || $transaksi->path_tanda_tangan) ? 'md:col-span-2' : '' }}">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">
                            Foto Dokumentasi ({{ $transaksi->dokumentasi->count() }})
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($transaksi->dokumentasi as $dok)
                            <a href="{{ Storage::url($dok->path_foto) }}" target="_blank"
                               class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow aspect-square">
                                <img src="{{ Storage::url($dok->path_foto) }}" alt="Dokumentasi" class="h-full w-full object-cover">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Timestamps --}}
            <hr class="border-gray-200">
            <div class="text-xs text-gray-500 flex flex-col sm:flex-row flex-wrap gap-4">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Dibuat: {{ $transaksi->created_at->translatedFormat('d F Y H:i') }}
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Diperbarui: {{ $transaksi->updated_at->translatedFormat('d F Y H:i') }}
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                    @if($transaksi->status === 'draft')
                    @can('amil')
                    <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Transaksi
                    </a>
                    @endcan
                    @endif

                    @if($transaksi->status === 'disalurkan')
                    <a href="{{ route('transaksi-penyaluran.cetak', $transaksi->uuid) }}" target="_blank"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary border border-transparent shadow-sm text-sm font-medium rounded-lg text-white hover:bg-primary-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Kwitansi
                    </a>
                    @endif

                    @if($transaksi->status === 'draft')
                    @can('amil')
                    <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Transaksi
                    </button>
                    @endcan
                    @endif
                </div>
            </div>

        </div>{{-- end content body --}}
    </div>{{-- end main card --}}
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl bg-white">
        <div class="flex justify-center mb-4">
            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Transaksi</h3>
        <p class="text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus transaksi<br>
            "<span class="font-semibold text-gray-700">{{ $transaksi->nomor_transaksi }}</span>"?
        </p>
        <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeDeleteModal()"
                class="w-28 rounded-lg border border-gray-300 px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form method="POST" action="{{ route('transaksi-penyaluran.destroy', $transaksi->uuid) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-28 rounded-lg px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
document.getElementById('delete-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endpush