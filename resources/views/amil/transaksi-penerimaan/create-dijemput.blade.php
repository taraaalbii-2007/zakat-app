{{--
    resources/views/amil/transaksi-datang-langsung/create-dijemput.blade.php

    DIPAKAI OLEH  : Amil / Admin Masjid SAJA
    CONTROLLER    : createDijemput() → return view('...create-dijemput')
    STORE         : POST route('transaksi-datang-langsung.store') dengan metode_penerimaan=dijemput (hidden)

    FLOW          : 1 Step saja — isi data muzakki + lokasi + pilih amil → Submit
                    Controller store() deteksi isDijemput → simpan status=pending,
                    status_penjemputan=menunggu. Detail zakat & pembayaran diisi
                    amil saat tiba via edit/completePickupTransaction().

    TIDAK ADA     : Pilihan metode_penerimaan (sudah fix = dijemput via hidden field)
                    Step 2 (detail zakat) & Step 3 (pembayaran)

    FIELD WAJIB   : muzakki_nama, amil_id, latitude, longitude
    FIELD OPSIONAL: muzakki_nik, muzakki_telepon, muzakki_email, muzakki_alamat, keterangan

    DATA MUZAKKI  : Sudah dipisah dari tabel transaksi.
                    Snapshot disimpan di kolom muzakki_nama, muzakki_telepon, dst.
                    muzakki_id (FK) diisi jika diinput oleh muzakki (bukan kasus ini).
--}}

@extends('layouts.app')

@section('title', 'Tambah Permintaan Penjemputan Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">

{{-- ══════════════════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">

    <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border-b border-green-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 border border-green-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base sm:text-lg font-bold text-gray-900">Permintaan Penjemputan Zakat</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                            Dijemput
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $masjid->nama }} &middot; No: <span class="font-mono font-semibold text-gray-700">{{ $noTransaksiPreview }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('transaksi-datang-langsung.index') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Info banner --}}
    <div class="px-4 sm:px-6 py-3 bg-green-50 border-b border-green-100">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-green-800">
                <strong>Mode Dijemput:</strong> Simpan permintaan penjemputan muzakki. Detail zakat & pembayaran akan diisi oleh amil saat tiba di lokasi.
                Status awal: <span class="font-semibold">Pending — Menunggu Penjemputan</span>.
            </p>
        </div>
    </div>

{{-- ══════════════════════════════════════════════════════════════
     FORM
══════════════════════════════════════════════════════════════ --}}
    <form id="formDijemput"
          action="{{ route('transaksi-datang-langsung.store') }}"
          method="POST"
          class="p-4 sm:p-6 space-y-6">
        @csrf

        {{-- Hidden: metode sudah fix dijemput, tidak perlu dipilih user --}}
        <input type="hidden" name="metode_penerimaan"  value="dijemput">
        <input type="hidden" name="tanggal_transaksi"  value="{{ $tanggalHariIni }}">
        <input type="hidden" name="waktu_transaksi"    value="{{ now()->format('H:i:s') }}">
        <input type="hidden" name="no_transaksi"       value="{{ $noTransaksiPreview }}">
        <input type="hidden" name="is_pembayaran_beras" value="0">

        {{-- ─── ERROR SUMMARY ─── --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-800">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-sm text-red-700 mt-1 space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════
             SEKSI 1: DATA MUZAKKI
        ══════════════════════════════════════════════════════════ --}}
        <div>
            <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold flex-shrink-0">1</span>
                Data Muzakki (Pemberi Zakat)
            </h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="muzakki_nama" id="muzakkiNama"
                            value="{{ old('muzakki_nama') }}"
                            placeholder="Nama lengkap pemberi zakat"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('muzakki_nama') border-red-500 bg-red-50 @enderror">
                        @error('muzakki_nama')
                            <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            NIK <span class="text-xs text-gray-400 font-normal">(opsional, 16 digit)</span>
                        </label>
                        <input type="text" name="muzakki_nik"
                            value="{{ old('muzakki_nik') }}"
                            placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('muzakki_nik') border-red-500 bg-red-50 @enderror">
                        @error('muzakki_nik')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Telepon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Telepon / WhatsApp</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">+62</span>
                            <input type="tel" name="muzakki_telepon"
                                value="{{ old('muzakki_telepon') }}"
                                placeholder="81234567890"
                                class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Untuk dihubungi amil penjemput</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email <span class="text-xs text-gray-400 font-normal">(untuk kwitansi)</span>
                        </label>
                        <input type="email" name="muzakki_email"
                            value="{{ old('muzakki_email') }}"
                            placeholder="email@contoh.com"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             SEKSI 2: LOKASI PENJEMPUTAN
        ══════════════════════════════════════════════════════════ --}}
        <div>
            <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold flex-shrink-0">2</span>
                Lokasi Penjemputan
            </h3>

            <div class="space-y-4">
                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="muzakki_alamat" rows="3"
                        placeholder="Jl. Contoh No. 12, RT 03/RW 05, Kelurahan, Kecamatan, Kota/Kabupaten"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all resize-none @error('muzakki_alamat') border-red-500 bg-red-50 @enderror">{{ old('muzakki_alamat') }}</textarea>
                    @error('muzakki_alamat')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Koordinat GPS --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-medium text-gray-700">
                            Koordinat Lokasi <span class="text-red-500">*</span>
                        </p>
                        <button type="button" id="btnGps"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-primary hover:text-primary transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Gunakan Lokasi Saat Ini
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="latitude" id="latitude"
                                value="{{ old('latitude') }}" placeholder="-6.2088"
                                class="w-full px-3.5 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all font-mono @error('latitude') border-red-500 bg-red-50 @enderror">
                            @error('latitude')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="longitude" id="longitude"
                                value="{{ old('longitude') }}" placeholder="106.8456"
                                class="w-full px-3.5 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all font-mono @error('longitude') border-red-500 bg-red-50 @enderror">
                            @error('longitude')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-2">
                        💡 Klik "Gunakan Lokasi Saat Ini" atau isi manual dari Google Maps.
                    </p>

                    {{-- Preview peta mini (status GPS) --}}
                    <div id="gpsStatus" class="mt-2 hidden">
                        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="gpsStatusText" class="text-xs text-green-800 font-medium">Lokasi berhasil didapatkan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             SEKSI 3: PENUGASAN AMIL
        ══════════════════════════════════════════════════════════ --}}
        <div>
            <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold flex-shrink-0">3</span>
                Penugasan Amil Penjemput
            </h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Pilih Amil --}}
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Amil Penjemput <span class="text-red-500">*</span>
                        </label>
                        <select name="amil_id" id="amilId"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('amil_id') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Pilih Amil yang Bertugas --</option>
                            @foreach($amilList as $amil)
                                <option value="{{ $amil->id }}"
                                    {{ old('amil_id') == $amil->id ? 'selected' : '' }}>
                                    {{ $amil->nama_lengkap }}
                                    @if($amil->kode_amil) ({{ $amil->kode_amil }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('amil_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        @if($amilList->isEmpty())
                            <p class="mt-1 text-xs text-amber-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Belum ada amil aktif terdaftar.
                            </p>
                        @endif
                    </div>

                    {{-- Tanggal Penjemputan yang Diinginkan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Penjemputan <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="date" name="tanggal_penjemputan"
                            value="{{ old('tanggal_penjemputan', $tanggalHariIni) }}"
                            min="{{ $tanggalHariIni }}"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Tanggal yang diinginkan muzakki</p>
                    </div>
                </div>

                {{-- Info Amil terpilih --}}
                <div id="infoAmilTerpilih" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-blue-800" id="namaAmilTerpilih">-</p>
                        <p class="text-xs text-blue-700 mt-0.5">Amil ini akan mendapat notifikasi tugas penjemputan.</p>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Catatan untuk Amil <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="keterangan" rows="3"
                        placeholder="Contoh: Patokan rumah di samping warung Bu Sari. Pagar besi hijau. Sebaiknya datang setelah Ashar."
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all resize-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             RINGKASAN SEBELUM SIMPAN
        ══════════════════════════════════════════════════════════ --}}
        <div id="boxRingkasan" class="hidden bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
            <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Ringkasan Permintaan Penjemputan
            </p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Muzakki</span>
                    <span class="font-semibold text-gray-900" id="ringSNama">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Amil Penjemput</span>
                    <span class="font-semibold text-gray-900" id="ringSAmil">-</span>
                </div>
                <div class="flex justify-between border-t border-green-200 pt-2 mt-2">
                    <span class="text-gray-600">Status Awal</span>
                    <span class="font-semibold text-amber-700">⏳ Menunggu Penjemputan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Detail Zakat & Bayar</span>
                    <span class="font-semibold text-gray-500 italic">Diisi saat penjemputan</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TOMBOL SUBMIT
        ══════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('transaksi-datang-langsung.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit" id="btnSubmit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5
                    bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold
                    rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/50
                    hover:from-green-600 hover:to-emerald-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Permintaan Penjemputan
            </button>
        </div>

    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
// Data amil untuk info dinamis
const amilData = {
    @foreach($amilList as $amil)
    '{{ $amil->id }}': '{{ $amil->nama_lengkap }}{{ $amil->kode_amil ? " (".$amil->kode_amil.")" : "" }}',
    @endforeach
};

// ── GPS ──────────────────────────────────────────────────────
document.getElementById('btnGps').addEventListener('click', function () {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung geolocation.');
        return;
    }
    const btn = this;
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Mengambil...`;

    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude.toFixed(7);
            const lon = pos.coords.longitude.toFixed(7);
            document.getElementById('latitude').value  = lat;
            document.getElementById('longitude').value = lon;

            // Tampilkan status
            document.getElementById('gpsStatus').classList.remove('hidden');
            document.getElementById('gpsStatusText').textContent =
                `Lokasi: ${lat}, ${lon} (akurasi ±${Math.round(pos.coords.accuracy)}m)`;

            btn.disabled = false;
            btn.innerHTML = `<svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg> Berhasil`;

            setTimeout(() => { btn.innerHTML = orig; }, 3000);
            refreshRingkasan();
        },
        err => {
            btn.disabled = false;
            btn.innerHTML = orig;
            const pesan = {
                1: 'Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser.',
                2: 'Posisi tidak tersedia. Coba lagi atau isi manual.',
                3: 'Timeout. Coba lagi.'
            };
            alert(pesan[err.code] || 'Gagal mendapatkan lokasi: ' + err.message);
        },
        { timeout: 15000, enableHighAccuracy: true }
    );
});

// ── INFO AMIL TERPILIH ────────────────────────────────────────
document.getElementById('amilId').addEventListener('change', function () {
    const box  = document.getElementById('infoAmilTerpilih');
    const nama = document.getElementById('namaAmilTerpilih');
    if (this.value && amilData[this.value]) {
        nama.textContent = amilData[this.value];
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
    refreshRingkasan();
});

// ── INPUT MUZAKKI NAMA ────────────────────────────────────────
document.getElementById('muzakkiNama').addEventListener('input', refreshRingkasan);

// ── RINGKASAN ─────────────────────────────────────────────────
function refreshRingkasan() {
    const nama  = document.getElementById('muzakkiNama').value.trim();
    const amil  = document.getElementById('amilId').value;
    const lat   = document.getElementById('latitude').value.trim();
    const lon   = document.getElementById('longitude').value.trim();

    if (nama || amil) {
        document.getElementById('boxRingkasan').classList.remove('hidden');
        document.getElementById('ringSNama').textContent  = nama || '-';
        document.getElementById('ringSAmil').textContent  = amil ? (amilData[amil] || '-') : '-';
    } else {
        document.getElementById('boxRingkasan').classList.add('hidden');
    }
}

// ── FORM SUBMIT ───────────────────────────────────────────────
document.getElementById('formDijemput').addEventListener('submit', function (e) {
    const nama  = document.getElementById('muzakkiNama').value.trim();
    const lat   = document.getElementById('latitude').value.trim();
    const lon   = document.getElementById('longitude').value.trim();
    const amil  = document.getElementById('amilId').value;

    if (!nama) {
        e.preventDefault();
        alert('Nama muzakki wajib diisi.');
        document.getElementById('muzakkiNama').focus();
        return;
    }
    if (!lat || !lon) {
        e.preventDefault();
        alert('Koordinat lokasi wajib diisi. Gunakan tombol "Gunakan Lokasi Saat Ini" atau isi manual.');
        document.getElementById('latitude').focus();
        return;
    }
    if (!amil) {
        e.preventDefault();
        alert('Pilih amil penjemput terlebih dahulu.');
        document.getElementById('amilId').focus();
        return;
    }

    // Spin button
    const btn  = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Menyimpan...`;
});

// ── INIT ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Trigger info amil jika ada old value
    @if(old('amil_id'))
    document.getElementById('amilId').dispatchEvent(new Event('change'));
    @endif
});
</script>
@endpush