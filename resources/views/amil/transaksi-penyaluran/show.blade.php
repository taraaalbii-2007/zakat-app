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
        <div class="px-4 sm:px-6 py-3 border-b border-yellow-200 bg-yellow-100/60">
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
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
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
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
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
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
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
                        Kembali
                    </a>

                    @if($transaksi->status === 'disalurkan')
                    <a href="{{ route('transaksi-penyaluran.cetak', $transaksi->uuid) }}" target="_blank"
                        class="inline-flex items-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Cetak Kwitansi
                    </a>
                    @endif

                    @if($transaksi->status === 'draft')
                    @can('amil')
                    <a href="{{ route('transaksi-penyaluran.edit', $transaksi->uuid) }}"
                        class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
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
                            'disalurkan' => 'bg-green-100 text-green-800 border-green-200|Disalurkan',
                            'dibatalkan' => 'bg-red-100 text-red-800 border-red-200|Dibatalkan',
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
                    <div>
                        <p class="font-medium text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_penyaluran)->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $transaksi->waktu_penyaluran ? \Carbon\Carbon::parse($transaksi->waktu_penyaluran)->format('H:i') . ' WIB' : '-' }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Penyaluran</label>
                    <div>
                        @if($transaksi->metode_penyaluran !== 'barang')
                        <p class="font-semibold text-primary text-sm">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                        @else
                        <p class="font-semibold text-orange-600 text-xs leading-tight">{{ $transaksi->detail_barang ?? 'Barang In-Kind' }}</p>
                        @if($transaksi->nilai_barang)
                        <p class="text-xs text-gray-500">≈ Rp {{ number_format($transaksi->nilai_barang, 0, ',', '.') }}</p>
                        @endif
                        @endif
                        <p class="text-xs text-gray-500">{{ ucfirst($transaksi->metode_penyaluran) }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis Zakat</label>
                    <div>
                        @if($transaksi->jenisZakat)
                        <p class="font-medium text-sm text-gray-900">{{ $transaksi->jenisZakat->nama }}</p>
                        @else
                        <p class="text-gray-400 italic text-sm">Belum diisi</p>
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
                            <div class="text-sm text-gray-900">
                                {{ $transaksi->mustahik->telepon }}
                            </div>
                        </div>
                        @endif
                        @if($transaksi->mustahik?->alamat)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat</label>
                            <div class="text-sm text-gray-900">
                                <span>{{ $transaksi->mustahik->alamat }}</span>
                            </div>
                        </div>
                        @endif
                        <div>
                            <a href="{{ route('mustahik.show', $transaksi->mustahik->uuid) }}"
                                class="inline-flex items-center text-xs text-primary hover:underline">
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
                            @php
                                $namaAmil = $transaksi->amil->nama_lengkap ?? '-';
                                $inisialAmil = strtoupper(substr($namaAmil, 0, 1));
                                $bgColors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-orange-500'];
                                $bgColor = $bgColors[ord($inisialAmil) % count($bgColors)];
                            @endphp
                            <div class="flex items-center gap-3 mb-3">
                                @if($transaksi->amil->foto_url && file_exists(public_path('storage/' . $transaksi->amil->foto_url)))
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                                    <img src="{{ asset('storage/' . $transaksi->amil->foto_url) }}" alt="Foto Amil" class="w-full h-full object-cover">
                                </div>
                                @else
                                <div class="w-10 h-10 rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-sm shadow-md flex-shrink-0">
                                    {{ $inisialAmil }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $namaAmil }}</p>
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
                <div>
                    Dibuat: {{ $transaksi->created_at->translatedFormat('d F Y H:i') }}
                </div>
                <div>
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
                        Edit Transaksi
                    </a>
                    @endcan
                    @endif

                
                    @if($transaksi->status === 'draft')
                    @can('amil')
                    <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white hover:bg-red-700 transition-colors">
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
            <div class="h-10 w-10 text-red-600"></div>
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