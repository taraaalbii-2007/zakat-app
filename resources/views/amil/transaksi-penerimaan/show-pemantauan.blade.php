{{-- resources/views/amil/pemantauan-transaksi/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Transaksi Penerimaan')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ============================================================
         PANEL KONFIRMASI AMIL — Menunggu
         Tampil jika metode transfer/QRIS & belum dikonfirmasi
         ============================================================ --}}
        @if (in_array($transaksi->metode_pembayaran, ['transfer', 'qris']) && $transaksi->konfirmasi_status === 'menunggu')
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl sm:rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-6 py-3 border-b border-yellow-200 bg-yellow-100/60 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-yellow-800">Menunggu Konfirmasi Amil</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="text-sm text-yellow-800 mb-4">
                        Muzakki telah melakukan pembayaran via
                        <strong>{{ $transaksi->metode_pembayaran === 'qris' ? 'QRIS' : 'Transfer Bank' }}</strong>.
                        Silakan periksa rekening masjid dan konfirmasi penerimaan dana.
                    </p>

                    @if ($transaksi->bukti_transfer)
                        <div class="mb-4">
                            <p class="text-xs font-medium text-yellow-700 uppercase tracking-wider mb-2">Bukti Pembayaran
                                dari Muzakki</p>
                            <a href="{{ asset('storage/' . $transaksi->bukti_transfer) }}" target="_blank"
                                class="inline-block border border-yellow-300 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                <img src="{{ asset('storage/' . $transaksi->bukti_transfer) }}" alt="Bukti Bayar"
                                    class="h-40 w-auto object-cover">
                            </a>
                        </div>
                    @endif

                    @if ($transaksi->no_referensi_transfer)
                        <div class="mb-4">
                            <p class="text-xs font-medium text-yellow-700 uppercase tracking-wider mb-1">Nomor Referensi</p>
                            <p
                                class="text-sm font-mono text-gray-900 bg-white border border-yellow-200 px-3 py-1.5 rounded-lg inline-block">
                                {{ $transaksi->no_referensi_transfer }}</p>
                        </div>
                    @endif

                    {{-- HAPUS FORM KONFIRMASI DAN TOLAK --}}
                </div>
            </div>
        @endif

        {{-- Panel: Sudah Dikonfirmasi --}}
        @if (in_array($transaksi->metode_pembayaran, ['transfer', 'qris']) && $transaksi->konfirmasi_status === 'dikonfirmasi')
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-green-800">Pembayaran Telah Dikonfirmasi</p>
                    @if ($transaksi->dikonfirmasi_oleh)
                        <p class="text-xs text-green-700 mt-0.5">
                            Oleh:
                            <strong>{{ $transaksi->dikonfirmasiOleh?->username ?? $transaksi->dikonfirmasi_oleh }}</strong>
                            @if ($transaksi->konfirmasi_at)
                                · {{ $transaksi->konfirmasi_at->translatedFormat('d F Y H:i') }}
                            @endif
                        </p>
                    @endif
                    @if ($transaksi->catatan_konfirmasi)
                        <p class="text-xs text-green-700 mt-0.5">Catatan: {{ $transaksi->catatan_konfirmasi }}</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Panel: Ditolak --}}
        @if ($transaksi->konfirmasi_status === 'ditolak')
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Pembayaran Ditolak</p>
                    @if ($transaksi->catatan_konfirmasi)
                        <p class="text-xs text-red-700 mt-0.5">Alasan: {{ $transaksi->catatan_konfirmasi }}</p>
                    @endif
                    @if ($transaksi->dikonfirmasi_oleh && $transaksi->konfirmasi_at)
                        <p class="text-xs text-red-600 mt-0.5">
                            Oleh: {{ $transaksi->konfirmator?->name ?? $transaksi->dikonfirmasi_oleh }}
                            · {{ $transaksi->konfirmasi_at->translatedFormat('d F Y H:i') }}
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
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Detail Transaksi Penerimaan</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap transaksi zakat</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                        {{-- HANYA TOMBOL KEMBALI --}}
                        <a href="{{ route('pemantauan-transaksi.index') }}"
                            class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>

                        {{-- HAPUS SEMUA TOMBOL LAINNYA (Cetak, Edit, Verifikasi) --}}
                    </div>
                </div>
            </div>

            {{-- Content Body --}}
            <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

                {{-- No Transaksi & Badges --}}
                <div class="space-y-3">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $transaksi->no_transaksi }}</h2>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ $transaksi->no_kwitansi ?? $transaksi->no_transaksi }}
                        </span>
                        {!! $transaksi->status_badge !!}
                        {!! $transaksi->metode_penerimaan_badge !!}
                        {{-- Badge Konfirmasi Transfer/QRIS --}}
                        @if (in_array($transaksi->metode_pembayaran, ['transfer', 'qris']))
                            @php
                                $konfBadge = match ($transaksi->konfirmasi_status) {
                                    'menunggu'
                                        => 'bg-yellow-100 text-yellow-800 border-yellow-200|⏳ Menunggu Konfirmasi',
                                    'dikonfirmasi'
                                        => 'bg-green-100 text-green-800 border-green-200|✓ Dikonfirmasi Amil',
                                    'ditolak' => 'bg-red-100 text-red-800 border-red-200|✕ Ditolak',
                                    default => null,
                                };
                            @endphp
                            @if ($konfBadge)
                                @php [$kClass, $kLabel] = explode('|', $konfBadge); @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $kClass }}">{{ $kLabel }}</span>
                            @endif
                        @endif
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Info Cards (3 kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal
                            Transaksi</label>
                        <div class="flex items-start text-sm text-gray-900 gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="font-medium">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $transaksi->waktu_transaksi ? $transaksi->waktu_transaksi->format('H:i') . ' WIB' : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total
                            Pembayaran</label>
                        <div class="flex items-start text-sm text-gray-900 gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</p>
                                @if ($transaksi->metode_pembayaran)
                                    <p class="text-xs text-gray-500">
                                        {{ $transaksi->metode_pembayaran_label ?? ucfirst($transaksi->metode_pembayaran) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis
                            Zakat</label>
                        <div class="flex items-start text-sm text-gray-900 gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <div>
                                @if ($transaksi->jenisZakat)
                                    <p class="font-medium">{{ $transaksi->jenisZakat->nama }}</p>
                                    @if ($transaksi->tipeZakat)
                                        <p class="text-xs text-gray-500">{{ $transaksi->tipeZakat->nama }}</p>
                                    @endif
                                @else
                                    <p class="text-gray-400 italic">Belum diisi</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Informasi Muzakki --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Informasi Muzakki</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama
                                    Lengkap</label>
                                <p class="text-sm text-gray-900 font-medium">{{ $transaksi->muzakki_nama }}</p>
                            </div>
                            @if ($transaksi->muzakki_nik)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">NIK</label>
                                    <p class="text-sm text-gray-900">{{ $transaksi->muzakki_nik }}</p>
                                </div>
                            @endif
                            @if ($transaksi->muzakki_telepon)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Telepon</label>
                                    <div class="flex items-center text-sm text-gray-900 gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $transaksi->muzakki_telepon }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            @if ($transaksi->muzakki_email)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</label>
                                    <div class="flex items-center text-sm text-gray-900 gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $transaksi->muzakki_email }}
                                    </div>
                                </div>
                            @endif
                            @if ($transaksi->muzakki_alamat)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat</label>
                                    <div class="flex items-start text-sm text-gray-900 gap-2">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $transaksi->muzakki_alamat }}</span>
                                    </div>
                                </div>
                            @endif
                            @if ($transaksi->metode_penerimaan === 'dijemput' && $transaksi->latitude && $transaksi->longitude)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Lokasi
                                        Penjemputan</label>
                                    <p class="text-xs text-gray-600 mb-1">Lat: {{ $transaksi->latitude }}, Long:
                                        {{ $transaksi->longitude }}</p>
                                    <a href="https://www.google.com/maps?q={{ $transaksi->latitude }},{{ $transaksi->longitude }}"
                                        target="_blank"
                                        class="inline-flex items-center text-xs text-primary hover:underline">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Buka di Google Maps
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Detail Zakat --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Detail Zakat</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            @if ($transaksi->programZakat)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Program
                                        Zakat</label>
                                    <p class="text-sm text-gray-900">{{ $transaksi->programZakat->nama_program }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jumlah
                                    Pembayaran</label>
                                <p class="text-sm font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</p>
                                @if ($transaksi->jumlah_beras_kg)
                                    <p class="text-xs text-gray-600 mt-0.5">{{ $transaksi->jumlah_beras_kg }} kg beras</p>
                                @endif
                            </div>
                            @if ($transaksi->jumlah_jiwa)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jumlah
                                        Jiwa</label>
                                    <p class="text-sm text-gray-900">{{ $transaksi->jumlah_jiwa }} orang</p>
                                    @if ($transaksi->nominal_per_jiwa)
                                        <p class="text-xs text-gray-600 mt-0.5">@ Rp
                                            {{ number_format($transaksi->nominal_per_jiwa, 0, ',', '.') }}/jiwa</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            @if ($transaksi->nilai_harta)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nilai
                                        Harta</label>
                                    <p class="text-sm text-gray-900">Rp
                                        {{ number_format($transaksi->nilai_harta, 0, ',', '.') }}</p>
                                    @if ($transaksi->nisab_saat_ini)
                                        <p class="text-xs text-gray-600 mt-0.5">Nisab: Rp
                                            {{ number_format($transaksi->nisab_saat_ini, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            @endif
                            @if (isset($transaksi->sudah_haul))
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status
                                        Haul</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $transaksi->sudah_haul ? 'Sudah Haul' : 'Belum Haul' }}</p>
                                    @if ($transaksi->tanggal_mulai_haul)
                                        <p class="text-xs text-gray-600 mt-0.5">Mulai:
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_mulai_haul)->format('d F Y') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                            @if ($transaksi->metode_pembayaran)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Metode
                                        Pembayaran</label>
                                    {!! $transaksi->metode_pembayaran_badge !!}
                                    @if ($transaksi->no_referensi_transfer)
                                        <p class="text-xs text-gray-600 mt-1 font-mono">Ref:
                                            {{ $transaksi->no_referensi_transfer }}</p>
                                    @endif
                                </div>
                            @endif
                            @if ($transaksi->keterangan)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Keterangan</label>
                                    <p class="text-sm text-gray-700">{{ $transaksi->keterangan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Amil & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Informasi Amil --}}
                    <div>
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Informasi Amil</h4>
                        @if ($transaksi->amil)
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                                    <img src="{{ $transaksi->amil->foto_url ?? asset('images/default-avatar.png') }}"
                                        alt="Foto Amil" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $transaksi->amil->nama_lengkap ?? (optional($transaksi->amil->pengguna)->name ?? '-') }}
                                    </p>
                                    @if ($transaksi->amil->kode_amil)
                                        <p class="text-xs text-gray-500">Kode: {{ $transaksi->amil->kode_amil }}</p>
                                    @endif
                                    @if ($transaksi->amil->telepon)
                                        <p class="text-xs text-gray-500">{{ $transaksi->amil->telepon }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Tidak ada amil yang ditugaskan</p>
                        @endif

                        {{-- Status Penjemputan --}}
                        @if ($transaksi->metode_penerimaan === 'dijemput' && $transaksi->status_penjemputan)
                            <div class="mt-5">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Status Penjemputan</h4>
                                <div class="mb-2">{!! $transaksi->status_penjemputan_badge !!}</div>
                                <div class="space-y-1.5 text-xs text-gray-600">
                                    @foreach ([['waktu_request', 'Request', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'], ['waktu_diterima_amil', 'Diterima', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], ['waktu_berangkat', 'Berangkat', 'M13 7l5 5m0 0l-5 5m5-5H6'], ['waktu_sampai', 'Sampai', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'], ['waktu_selesai', 'Selesai', 'M5 13l4 4L19 7']] as [$field, $label, $path])
                                        @if ($transaksi->$field)
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="{{ $path }}" />
                                                </svg>
                                                <span class="text-gray-500 w-16">{{ $label }}:</span>
                                                <span>{{ $transaksi->$field->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Status Transaksi --}}
                    <div>
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Status Transaksi</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1.5">Status Verifikasi</p>
                                {!! $transaksi->status_badge !!}
                            </div>

                            {{-- Status Konfirmasi Pembayaran (transfer/QRIS) --}}
                            @if (in_array($transaksi->metode_pembayaran, ['transfer', 'qris']))
                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1.5">Status Konfirmasi Pembayaran</p>
                                    @php
                                        [$kClass, $kLabel] = match ($transaksi->konfirmasi_status) {
                                            'menunggu' => ['bg-yellow-100 text-yellow-800', '⏳ Menunggu Konfirmasi'],
                                            'dikonfirmasi' => ['bg-green-100  text-green-800', '✓ Dikonfirmasi'],
                                            'ditolak' => ['bg-red-100    text-red-800', '✕ Ditolak'],
                                            default => ['bg-gray-100   text-gray-600', '—'],
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $kClass }}">{{ $kLabel }}</span>
                                    @if ($transaksi->dikonfirmasi_oleh && $transaksi->konfirmasi_at)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $transaksi->konfirmator?->name ?? $transaksi->dikonfirmasi_oleh }}
                                            · {{ $transaksi->konfirmasi_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                    @if ($transaksi->catatan_konfirmasi)
                                        <p class="text-xs text-gray-600 mt-0.5 italic">
                                            "{{ $transaksi->catatan_konfirmasi }}"</p>
                                    @endif
                                </div>
                            @endif

                            {{-- Verifikator --}}
                            @if ($transaksi->verified_by)
                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Diverifikasi Oleh</p>
                                    @php $v = $transaksi->verifiedBy ?? null; @endphp
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $v ? $v->name ?? ($v->username ?? 'System') : 'System' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $transaksi->verified_at?->format('d F Y H:i') ?? '-' }}</p>
                                </div>
                            @endif

                            {{-- Alasan Penolakan --}}
                            @if ($transaksi->status === 'rejected' && $transaksi->alasan_penolakan)
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-1">Alasan
                                            Penolakan</p>
                                        <p class="text-sm text-red-700">{{ $transaksi->alasan_penolakan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bukti & Dokumentasi --}}
                @if ($transaksi->bukti_transfer || ($transaksi->foto_dokumentasi && count($transaksi->foto_dokumentasi) > 0))
                    <hr class="border-gray-200">
                    <div>
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Bukti & Dokumentasi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($transaksi->bukti_transfer)
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">
                                        Bukti {{ $transaksi->metode_pembayaran === 'qris' ? 'Scan QRIS' : 'Transfer' }}
                                    </label>
                                    <a href="{{ asset('storage/' . $transaksi->bukti_transfer) }}" target="_blank"
                                        class="inline-block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                        <img src="{{ asset('storage/' . $transaksi->bukti_transfer) }}"
                                            alt="Bukti Pembayaran" class="h-48 w-auto object-cover">
                                    </a>
                                </div>
                            @endif
                            @if ($transaksi->foto_dokumentasi && count($transaksi->foto_dokumentasi) > 0)
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">
                                        Foto Dokumentasi ({{ count($transaksi->foto_dokumentasi) }})
                                    </label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach ($transaksi->foto_dokumentasi as $foto)
                                            <a href="{{ asset('storage/' . $foto) }}" target="_blank"
                                                class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow aspect-square">
                                                <img src="{{ asset('storage/' . $foto) }}" alt="Dokumentasi"
                                                    class="h-full w-full object-cover">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Dibuat: {{ $transaksi->created_at->translatedFormat('d F Y H:i') }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Diperbarui: {{ $transaksi->updated_at->translatedFormat('d F Y H:i') }}
                    </div>
                </div>

                {{-- HAPUS SEMUA ACTION BUTTONS --}}

            </div>{{-- end content body --}}
        </div>{{-- end main card --}}
    </div>

    {{-- HAPUS DELETE MODAL KARENA TIDAK ADA AKSI HAPUS --}}

@endsection

@push('scripts')
    <script>
        // HAPUS SEMUA FUNGSI JS YANG TIDAK DIPERLUKAN
    </script>
@endpush
