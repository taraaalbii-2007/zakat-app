{{-- resources/views/amil/pemantauan-transaksi/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Transaksi Penerimaan')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ============================================================
         PANEL KONFIRMASI AMIL — Menunggu
         Tampil jika metode transfer/QRIS & belum dikonfirmasi
         ============================================================ --}}
    @if(in_array($transaksi->metode_pembayaran, ['transfer', 'qris']) && $transaksi->konfirmasi_status === 'menunggu')
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl sm:rounded-2xl overflow-hidden">
        <div class="px-4 sm:px-6 py-3 border-b border-yellow-200 bg-yellow-100/60">
            <h3 class="text-sm font-semibold text-yellow-800">Menunggu Konfirmasi Amil</h3>
        </div>
        <div class="p-4 sm:p-6">
            <p class="text-sm text-yellow-800 mb-4">
                Muzakki telah melakukan pembayaran via
                <strong>{{ $transaksi->metode_pembayaran === 'qris' ? 'QRIS' : 'Transfer Bank' }}</strong>.
                Silakan periksa rekening lembaga dan konfirmasi penerimaan dana.
            </p>

            @if($transaksi->bukti_transfer)
            <div class="mb-4">
                <p class="text-xs font-medium text-yellow-700 uppercase tracking-wider mb-2">Bukti Pembayaran dari Muzakki</p>
                <a href="{{ asset('storage/' . $transaksi->bukti_transfer) }}" target="_blank"
                   class="inline-block border border-yellow-300 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/' . $transaksi->bukti_transfer) }}" alt="Bukti Bayar" class="h-40 w-auto object-cover">
                </a>
            </div>
            @endif

            @if($transaksi->no_referensi_transfer)
            <div class="mb-4">
                <p class="text-xs font-medium text-yellow-700 uppercase tracking-wider mb-1">Nomor Referensi</p>
                <p class="text-sm font-mono text-gray-900 bg-white border border-yellow-200 px-3 py-1.5 rounded-lg inline-block">{{ $transaksi->no_referensi_transfer }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Panel: Sudah Dikonfirmasi --}}
    @if(in_array($transaksi->metode_pembayaran, ['transfer', 'qris']) && $transaksi->konfirmasi_status === 'dikonfirmasi')
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div>
            <p class="text-sm font-semibold text-green-800">Pembayaran Telah Dikonfirmasi</p>
            @if($transaksi->dikonfirmasi_oleh)
            <p class="text-xs text-green-700 mt-0.5">
                Oleh: <strong>{{ $transaksi->dikonfirmasiOleh?->username ?? $transaksi->dikonfirmasi_oleh }}</strong>
                @if($transaksi->konfirmasi_at)· {{ $transaksi->konfirmasi_at->translatedFormat('d F Y H:i') }}@endif
            </p>
            @endif
            @if($transaksi->catatan_konfirmasi)
            <p class="text-xs text-green-700 mt-0.5">Catatan: {{ $transaksi->catatan_konfirmasi }}</p>
            @endif
        </div>
    </div>
    @endif

    {{-- Panel: Ditolak --}}
    @if($transaksi->konfirmasi_status === 'ditolak')
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div>
            <p class="text-sm font-semibold text-red-800">Pembayaran Ditolak</p>
            @if($transaksi->catatan_konfirmasi)
            <p class="text-xs text-red-700 mt-0.5">Alasan: {{ $transaksi->catatan_konfirmasi }}</p>
            @endif
            @if($transaksi->dikonfirmasi_oleh && $transaksi->konfirmasi_at)
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
                    <a href="{{ route('transaksi-daring.index') }}"
                        class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Content Body --}}
        <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

            {{-- No Transaksi & Badges --}}
            <div class="space-y-3">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $transaksi->no_transaksi }}</h2>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                        {{ $transaksi->no_kwitansi ?? $transaksi->no_transaksi }}
                    </span>
                    {!! $transaksi->status_badge !!}
                    {!! $transaksi->metode_penerimaan_badge !!}
                    {{-- Badge Konfirmasi Transfer/QRIS --}}
                    @if(in_array($transaksi->metode_pembayaran, ['transfer', 'qris']))
                        @php
                            $konfBadge = match($transaksi->konfirmasi_status) {
                                'menunggu'     => 'bg-yellow-100 text-yellow-800 border-yellow-200|Menunggu Konfirmasi',
                                'dikonfirmasi' => 'bg-green-100 text-green-800 border-green-200|Dikonfirmasi Amil',
                                'ditolak'      => 'bg-red-100 text-red-800 border-red-200|Ditolak',
                                default        => null,
                            };
                        @endphp
                        @if($konfBadge)
                        @php [$kClass, $kLabel] = explode('|', $konfBadge); @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $kClass }}">{{ $kLabel }}</span>
                        @endif
                    @endif
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Info Cards (3 kolom) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                    <div>
                        <p class="font-medium text-sm text-gray-900">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $transaksi->waktu_transaksi ? $transaksi->waktu_transaksi->format('H:i') . ' WIB' : '-' }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Pembayaran</label>
                    <div>
                        <p class="font-semibold text-green-600 text-sm">{{ $transaksi->jumlah_formatted }}</p>
                        @if($transaksi->metode_pembayaran)
                        <p class="text-xs text-gray-500">{{ $transaksi->metode_pembayaran_label ?? ucfirst($transaksi->metode_pembayaran) }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis Zakat</label>
                    <div>
                        @if($transaksi->jenisZakat)
                        <p class="font-medium text-sm text-gray-900">{{ $transaksi->jenisZakat->nama }}</p>
                        @if($transaksi->tipeZakat)<p class="text-xs text-gray-500">{{ $transaksi->tipeZakat->nama }}</p>@endif
                        @else
                        <p class="text-gray-400 italic text-sm">Belum diisi</p>
                        @endif
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
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $transaksi->muzakki_nama }}</p>
                        </div>
                        @if($transaksi->muzakki_nik)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">NIK</label>
                            <p class="text-sm text-gray-900">{{ $transaksi->muzakki_nik }}</p>
                        </div>
                        @endif
                        @if($transaksi->muzakki_telepon)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Telepon</label>
                            <div class="text-sm text-gray-900">
                                {{ $transaksi->muzakki_telepon }}
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @if($transaksi->muzakki_email)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</label>
                            <div class="text-sm text-gray-900">
                                {{ $transaksi->muzakki_email }}
                            </div>
                        </div>
                        @endif
                        @if($transaksi->muzakki_alamat)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat</label>
                            <div class="text-sm text-gray-900">
                                <span>{{ $transaksi->muzakki_alamat }}</span>
                            </div>
                        </div>
                        @endif
                        @if($transaksi->metode_penerimaan === 'dijemput' && $transaksi->latitude && $transaksi->longitude)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Lokasi Penjemputan</label>
                            <p class="text-xs text-gray-600 mb-1">Lat: {{ $transaksi->latitude }}, Long: {{ $transaksi->longitude }}</p>
                            <a href="https://www.google.com/maps?q={{ $transaksi->latitude }},{{ $transaksi->longitude }}" target="_blank"
                               class="inline-flex items-center text-xs text-primary hover:underline">
                                Buka di Google Maps
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── Nama Jiwa (Zakat Fitrah) ── --}}
                @if(!empty($transaksi->nama_jiwa_json))
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 block">
                        Nama Jiwa — {{ count($transaksi->nama_jiwa_json) }} Jiwa
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($transaksi->nama_jiwa_json as $index => $namaJiwa)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm bg-green-50 text-green-800 border border-green-200">
                            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-green-200 text-green-800 text-xs font-bold flex-shrink-0">{{ $index + 1 }}</span>
                            {{ $namaJiwa }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <hr class="border-gray-200">

            {{-- Detail Zakat --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Detail Zakat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        @if($transaksi->programZakat)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Program Zakat</label>
                            <p class="text-sm text-gray-900">{{ $transaksi->programZakat->nama_program }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jumlah Pembayaran</label>
                            <p class="text-sm font-semibold text-green-600">{{ $transaksi->jumlah_formatted }}</p>
                            @if($transaksi->jumlah_infaq > 0)
                            <p class="text-xs text-amber-600 mt-0.5">
                                Dibayar: {{ $transaksi->jumlah_dibayar_formatted }}
                                <span class="font-medium">(+Infaq {{ $transaksi->jumlah_infaq_formatted }})</span>
                            </p>
                            @endif
                            @if($transaksi->jumlah_beras_kg)
                            <p class="text-xs text-gray-600 mt-0.5">{{ $transaksi->jumlah_beras_kg }} kg beras</p>
                            @endif
                        </div>
                        @if($transaksi->jumlah_jiwa)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jumlah Jiwa</label>
                            <p class="text-sm text-gray-900">{{ $transaksi->jumlah_jiwa }} orang</p>
                            @if($transaksi->nominal_per_jiwa)
                            <p class="text-xs text-gray-600 mt-0.5">@ Rp {{ number_format($transaksi->nominal_per_jiwa, 0, ',', '.') }}/jiwa</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @if($transaksi->nilai_harta)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nilai Harta</label>
                            <p class="text-sm text-gray-900">Rp {{ number_format($transaksi->nilai_harta, 0, ',', '.') }}</p>
                            @if($transaksi->nisab_saat_ini)
                            <p class="text-xs text-gray-600 mt-0.5">Nisab: Rp {{ number_format($transaksi->nisab_saat_ini, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        @endif
                        @if(isset($transaksi->sudah_haul))
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Haul</label>
                            <p class="text-sm text-gray-900">{{ $transaksi->sudah_haul ? 'Sudah Haul' : 'Belum Haul' }}</p>
                            @if($transaksi->tanggal_mulai_haul)
                            <p class="text-xs text-gray-600 mt-0.5">Mulai: {{ \Carbon\Carbon::parse($transaksi->tanggal_mulai_haul)->format('d F Y') }}</p>
                            @endif
                        </div>
                        @endif
                        @if($transaksi->metode_pembayaran)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Metode Pembayaran</label>
                            {!! $transaksi->metode_pembayaran_badge !!}
                            @if($transaksi->no_referensi_transfer)
                            <p class="text-xs text-gray-600 mt-1 font-mono">Ref: {{ $transaksi->no_referensi_transfer }}</p>
                            @endif
                        </div>
                        @endif
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

            {{-- Amil & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Informasi Amil --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Informasi Amil</h4>
                    @if($transaksi->amil)
                    @php
                        $namaAmil = $transaksi->amil->nama_lengkap ?? optional($transaksi->amil->pengguna)->name ?? '-';
                        $inisialAmil = strtoupper(substr($namaAmil, 0, 1));
                        $bgColors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-orange-500'];
                        $bgColor = $bgColors[ord($inisialAmil) % count($bgColors)];
                    @endphp
                    <div class="flex items-center gap-3 mb-3">
                        @if($transaksi->amil->foto_url && file_exists(public_path('storage/' . $transaksi->amil->foto_url)))
                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                            <img src="{{ asset('storage/' . $transaksi->amil->foto_url) }}"
                                 alt="Foto Amil" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-12 h-12 rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-lg shadow-md flex-shrink-0">
                            {{ $inisialAmil }}
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $namaAmil }}
                            </p>
                            @if($transaksi->amil->kode_amil)
                            <p class="text-xs text-gray-500">Kode: {{ $transaksi->amil->kode_amil }}</p>
                            @endif
                            @if($transaksi->amil->telepon)
                            <p class="text-xs text-gray-500">{{ $transaksi->amil->telepon }}</p>
                            @endif
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-gray-500 italic">Tidak ada amil yang ditugaskan</p>
                    @endif

                    {{-- Status Penjemputan --}}
                    @if($transaksi->metode_penerimaan === 'dijemput' && $transaksi->status_penjemputan)
                    <div class="mt-5">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Status Penjemputan</h4>
                        <div class="mb-2">{!! $transaksi->status_penjemputan_badge !!}</div>
                        <div class="space-y-1.5 text-xs text-gray-600">
                            @foreach([
                                ['waktu_request',       'Request'],
                                ['waktu_diterima_amil', 'Diterima'],
                                ['waktu_berangkat',     'Berangkat'],
                                ['waktu_sampai',        'Sampai'],
                                ['waktu_selesai',       'Selesai'],
                            ] as [$field, $label])
                                @if($transaksi->$field)
                                <div class="flex items-center gap-2">
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
                        @if(in_array($transaksi->metode_pembayaran, ['transfer', 'qris']))
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1.5">Status Konfirmasi Pembayaran</p>
                            @php
                                [$kClass, $kLabel] = match($transaksi->konfirmasi_status) {
                                    'menunggu'     => ['bg-yellow-100 text-yellow-800', 'Menunggu Konfirmasi'],
                                    'dikonfirmasi' => ['bg-green-100  text-green-800',  'Dikonfirmasi'],
                                    'ditolak'      => ['bg-red-100    text-red-800',    'Ditolak'],
                                    default        => ['bg-gray-100   text-gray-600',   '—'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $kClass }}">{{ $kLabel }}</span>
                            @if($transaksi->dikonfirmasi_oleh && $transaksi->konfirmasi_at)
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $transaksi->konfirmator?->name ?? $transaksi->dikonfirmasi_oleh }}
                                · {{ $transaksi->konfirmasi_at->format('d/m/Y H:i') }}
                            </p>
                            @endif
                            @if($transaksi->catatan_konfirmasi)
                            <p class="text-xs text-gray-600 mt-0.5 italic">"{{ $transaksi->catatan_konfirmasi }}"</p>
                            @endif
                        </div>
                        @endif

                        {{-- Verifikator --}}
                        @if($transaksi->verified_by)
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Diverifikasi Oleh</p>
                            @php $v = $transaksi->verifiedBy ?? null; @endphp
                            <p class="text-sm font-medium text-gray-900">{{ $v ? ($v->name ?? $v->username ?? 'System') : 'System' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $transaksi->verified_at?->format('d F Y H:i') ?? '-' }}</p>
                        </div>
                        @endif

                        {{-- Alasan Penolakan --}}
                        @if($transaksi->status === 'rejected' && $transaksi->alasan_penolakan)
                        <div class="pt-3 border-t border-gray-200">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-1">Alasan Penolakan</p>
                                <p class="text-sm text-red-700">{{ $transaksi->alasan_penolakan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bukti & Dokumentasi --}}
            @if($transaksi->bukti_transfer || ($transaksi->foto_dokumentasi && count($transaksi->foto_dokumentasi) > 0))
            <hr class="border-gray-200">
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Bukti & Dokumentasi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($transaksi->bukti_transfer)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">
                            Bukti {{ $transaksi->metode_pembayaran === 'qris' ? 'Scan QRIS' : 'Transfer' }}
                        </label>
                        <a href="{{ asset('storage/' . $transaksi->bukti_transfer) }}" target="_blank"
                           class="inline-block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <img src="{{ asset('storage/' . $transaksi->bukti_transfer) }}" alt="Bukti Pembayaran" class="h-48 w-auto object-cover">
                        </a>
                    </div>
                    @endif
                    @if($transaksi->foto_dokumentasi && count($transaksi->foto_dokumentasi) > 0)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">
                            Foto Dokumentasi ({{ count($transaksi->foto_dokumentasi) }})
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($transaksi->foto_dokumentasi as $foto)
                            <a href="{{ asset('storage/' . $foto) }}" target="_blank"
                               class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow aspect-square">
                                <img src="{{ asset('storage/' . $foto) }}" alt="Dokumentasi" class="h-full w-full object-cover">
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

        </div>{{-- end content body --}}
    </div>{{-- end main card --}}
</div>
@endsection

@push('scripts')
<script>
// Halaman read-only, tidak diperlukan interaksi
</script>
@endpush