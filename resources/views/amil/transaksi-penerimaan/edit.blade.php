{{--
    resources/views/amil/transaksi-penerimaan/edit.blade.php
    PERUBAHAN:
    1. Langsung ke step 2 karena data muzakki sudah diisi
    2. Data muzakki dikirim sebagai hidden input
    3. Progress dots menunjukkan step 1 selesai, aktif di step 2
    4. Menampilkan ringkasan data muzakki yang sudah diisi
    5. QRIS dari KonfigurasiQris (tanpa input no referensi)
    6. Fitur nama jiwa seperti di create
    7. [TAMBAHAN] Panel fidyah (mentah/matang/tunai) + hidden inputs fidyah
--}}

@extends('layouts.app')

@php
    $isMuzakki = auth()->user()->isMuzakki();
    $isAmil = !$isMuzakki;
    $defaultMode = $transaksi->metode_penerimaan;

    $headerTitle = match ($defaultMode) {
        'dijemput' => 'Lengkapi Data Zakat Penjemputan',
        'daring' => 'Edit Pembayaran Zakat Daring',
        default => 'Edit Transaksi Datang Langsung',
    };
@endphp

@section('title', $headerTitle)

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 overflow-hidden">

            {{-- HEADER --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">{{ $headerTitle }}</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $masjid->nama }} &middot; No: {{ $noTransaksiPreview }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('transaksi-dijemput.index') }}"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>Kembali
                        </a>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Dijemput
                        </span>
                    </div>
                </div>
            </div>

            {{-- FORM --}}
            <form id="mainForm"
                action="{{ route('transaksi-dijemput.update', $transaksi->uuid) }}"
                method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                @method('PUT')
                
                {{-- Hidden inputs untuk data muzakki yang sudah diisi --}}
                <input type="hidden" name="no_transaksi" value="{{ $noTransaksiPreview }}">
                <input type="hidden" name="tanggal_transaksi" value="{{ $transaksi->tanggal_transaksi->format('Y-m-d') }}">
                <input type="hidden" name="waktu_transaksi" value="{{ $transaksi->waktu_transaksi->format('H:i:s') }}">
                <input type="hidden" name="metode_penerimaan" id="hdnMode" value="{{ $defaultMode }}">
                <input type="hidden" name="is_pembayaran_beras" id="hdnBeras" value="0">
                <input type="hidden" name="muzakki_nama" value="{{ $transaksi->muzakki_nama }}">
                <input type="hidden" name="muzakki_telepon" value="{{ $transaksi->muzakki_telepon }}">
                <input type="hidden" name="muzakki_email" value="{{ $transaksi->muzakki_email }}">
                <input type="hidden" name="muzakki_alamat" value="{{ $transaksi->muzakki_alamat }}">
                <input type="hidden" name="muzakki_nik" value="{{ $transaksi->muzakki_nik }}">
                <input type="hidden" name="latitude" value="{{ $transaksi->latitude }}">
                <input type="hidden" name="longitude" value="{{ $transaksi->longitude }}">
                <input type="hidden" name="amil_id" value="{{ $transaksi->amil_id }}">

                {{-- [TAMBAHAN] Hidden inputs untuk fidyah --}}
                <input type="hidden" name="fidyah_jumlah_hari" id="hdnFidyahHari" value="{{ old('fidyah_jumlah_hari', $transaksi->fidyah_jumlah_hari) }}">
                <input type="hidden" name="fidyah_tipe" id="hdnFidyahTipe" value="{{ old('fidyah_tipe', $transaksi->fidyah_tipe) }}">
                <input type="hidden" name="fidyah_nama_bahan" id="hdnFidyahNamaBahan" value="{{ old('fidyah_nama_bahan', $transaksi->fidyah_nama_bahan) }}">
                <input type="hidden" name="fidyah_berat_per_hari_gram" id="hdnFidyahBeratPerHari" value="{{ old('fidyah_berat_per_hari_gram', $transaksi->fidyah_berat_per_hari_gram ?? $fidyahInfo['berat_per_hari_gram']) }}">
                <input type="hidden" name="fidyah_jumlah_box" id="hdnFidyahJumlahBox" value="{{ old('fidyah_jumlah_box', $transaksi->fidyah_jumlah_box) }}">
                <input type="hidden" name="fidyah_menu_makanan" id="hdnFidyahMenu" value="{{ old('fidyah_menu_makanan', $transaksi->fidyah_menu_makanan) }}">
                <input type="hidden" name="fidyah_harga_per_box" id="hdnFidyahHargaBox" value="{{ old('fidyah_harga_per_box', $transaksi->fidyah_harga_per_box ?? 0) }}">
                <input type="hidden" name="fidyah_cara_serah" id="hdnFidyahCaraSerah" value="{{ old('fidyah_cara_serah', $transaksi->fidyah_cara_serah) }}">

                {{-- PROGRESS DOTS (Langsung ke step 2) --}}
                <div class="mb-8">
                    <div class="flex items-center max-w-lg mx-auto">
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot1"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-green-600 text-white">
                                ✓</div>
                            <span class="text-xs mt-1 font-medium text-green-600 text-center leading-tight">Data Muzakki</span>
                        </div>
                        <div id="line12" class="flex-1 h-0.5 bg-green-600"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot2"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-blue-600 text-white">
                                2</div>
                            <span class="text-xs mt-1 font-medium text-blue-600 text-center leading-tight">Detail Zakat</span>
                        </div>
                        <div id="line23" class="flex-1 h-0.5 bg-gray-200"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot3"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">
                                3</div>
                            <span class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Pembayaran</span>
                        </div>
                    </div>
                </div>

                {{-- RINGKASAN DATA MUZAKKI (sudah diisi) --}}
                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Data Muzakki (Sudah Diisi)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-xs text-gray-500">Nama</span>
                            <p class="font-medium text-gray-900">{{ $transaksi->muzakki_nama }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">NIK</span>
                            <p class="font-medium text-gray-900">{{ $transaksi->muzakki_nik ?: '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Telepon</span>
                            <p class="font-medium text-gray-900">{{ $transaksi->muzakki_telepon ?: '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Email</span>
                            <p class="font-medium text-gray-900">{{ $transaksi->muzakki_email ?: '-' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-xs text-gray-500">Alamat</span>
                            <p class="font-medium text-gray-900">{{ $transaksi->muzakki_alamat }}</p>
                        </div>
                        @if($transaksi->latitude && $transaksi->longitude)
                        <div class="sm:col-span-2">
                            <span class="text-xs text-gray-500">Koordinat</span>
                            <p class="font-medium text-gray-900">{{ $transaksi->latitude }}, {{ $transaksi->longitude }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ERROR SUMMARY --}}
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-semibold text-sm">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside text-sm mt-1 space-y-0.5">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- ═══════════ STEP 2 — DETAIL ZAKAT ═══════════ --}}
                <div id="step2" class="step-panel">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                        <span class="inline-flex w-5 h-5 rounded-full bg-blue-600 text-white text-xs items-center justify-center mr-1.5 font-bold">2</span>
                        Detail Zakat
                    </h3>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Zakat <span
                                        class="text-red-500">*</span></label>
                                <select name="jenis_zakat_id" id="jenisId"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 @error('jenis_zakat_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach ($jenisZakatList as $jz)
                                        <option value="{{ $jz->id }}" data-nama="{{ strtolower($jz->nama) }}"
                                            {{ old('jenis_zakat_id', $transaksi->jenis_zakat_id) == $jz->id ? 'selected' : '' }}>
                                            {{ $jz->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_zakat_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="wrapTipe" class="{{ $transaksi->jenis_zakat_id ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe / Jenis Spesifik <span
                                        class="text-red-500">*</span></label>
                                <select name="tipe_zakat_id" id="tipeId"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 @error('tipe_zakat_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Tipe --</option>
                                </select>
                                @error('tipe_zakat_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Zakat <span
                                    class="text-xs text-gray-400">(opsional)</span></label>
                            <select name="program_zakat_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                <option value="">-- Tidak memilih program tertentu --</option>
                                @foreach ($programZakatList as $prog)
                                    <option value="{{ $prog->id }}"
                                        {{ old('program_zakat_id', $transaksi->program_zakat_id) == $prog->id ? 'selected' : '' }}>
                                        {{ $prog->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <hr class="border-gray-200">

                        {{-- ══ PANEL BERAS ══ --}}
                        <div id="panelBeras" class="hidden space-y-4">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-900 mb-3">Ketentuan Zakat Fitrah Beras</p>
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_kg'] }} kg</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa</p>
                                    </div>
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa</p>
                                    </div>
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-green-700">Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa (BAZNAS)</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600">Ketetapan BAZNAS: {{ $zakatFitrahInfo['beras_kg'] }} kg
                                    atau {{ $zakatFitrahInfo['beras_liter'] }} liter beras per jiwa</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Jiwa <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_jiwa" id="berasJiwa"
                                        value="{{ old('jumlah_jiwa', $transaksi->jumlah_jiwa ?? 1) }}" min="1" step="1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Beras (kg) <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_beras_kg" id="berasKg"
                                        value="{{ old('jumlah_beras_kg', $transaksi->jumlah_beras_kg ?? $zakatFitrahInfo['beras_kg']) }}" 
                                        min="0.1" step="0.1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            {{-- ★ DAFTAR NAMA JIWA - BERAS ★ --}}
                            <div id="wrapNamaBerasJiwa" class="hidden">
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-700">Daftar Nama per Jiwa</span>
                                                <span class="text-xs text-gray-400 ml-2">Opsional, tapi disarankan</span>
                                            </div>
                                            <span class="text-xs text-gray-400" id="counterNamaBeras">0 diisi</span>
                                        </div>
                                    </div>
                                    <div id="listNamaBeras" class="p-4 space-y-2 bg-white"></div>
                                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
                                        <p class="text-xs text-gray-400">Jumlah baris nama akan otomatis menyesuaikan
                                            dengan jumlah jiwa di atas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ══ PANEL FITRAH TUNAI ══ --}}
                        <div id="panelFitrahTunai" class="hidden space-y-4">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-900 mb-3">Ketentuan Zakat Fitrah (Dibayar Uang)</p>
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_kg'] }} kg</p>
                                        <p class="text-xs text-gray-500 mt-0.5">setara beras</p>
                                    </div>
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p>
                                        <p class="text-xs text-gray-500 mt-0.5">setara beras</p>
                                    </div>
                                    <div class="bg-white rounded border border-green-200 p-2 text-center">
                                        <p class="text-base font-bold text-green-700">Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600">BAZNAS menetapkan {{ $zakatFitrahInfo['beras_kg'] }} kg /
                                    {{ $zakatFitrahInfo['beras_liter'] }} liter beras = <strong>Rp
                                        {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}/jiwa</strong>
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Jiwa <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_jiwa" id="tunaiJiwa"
                                        value="{{ old('jumlah_jiwa', $transaksi->jumlah_jiwa ?? 1) }}" min="1" step="1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal per Jiwa (Rp) <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nominal_per_jiwa" id="tunaiNominal"
                                            value="{{ old('nominal_per_jiwa', $transaksi->nominal_per_jiwa ?? $zakatFitrahInfo['nominal_per_jiwa']) }}"
                                            min="1000" step="1000"
                                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- ★ DAFTAR NAMA JIWA - TUNAI ★ --}}
                            <div id="wrapNamaTunaiJiwa" class="hidden">
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-700">Daftar Nama per Jiwa</span>
                                                <span class="text-xs text-gray-400 ml-2">Opsional, tapi disarankan</span>
                                            </div>
                                            <span class="text-xs text-gray-400" id="counterNamaTunai">0 diisi</span>
                                        </div>
                                    </div>
                                    <div id="listNamaTunai" class="p-4 space-y-2 bg-white"></div>
                                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
                                        <p class="text-xs text-gray-400">Jumlah baris nama akan otomatis menyesuaikan
                                            dengan jumlah jiwa di atas.</p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="jumlah" id="hdnJumlahTunai" value="{{ $transaksi->jumlah ?? 0 }}">
                        </div>

                        {{-- ══ PANEL MAL ══ --}}
                        <div id="panelMal" class="hidden space-y-4">
                            <div id="nisabBox" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <p class="text-xs font-medium text-gray-700 mb-1">Informasi Nisab</p>
                                <div id="nisabIsi" class="text-xs text-gray-600 space-y-0.5"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Nilai Harta (Rp) <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                    <input type="number" name="nilai_harta" id="malHarta"
                                        value="{{ old('nilai_harta', $transaksi->nilai_harta) }}" min="0" step="1000"
                                        class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 @error('nilai_harta') border-red-500 @enderror">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nisab Saat Ini (Rp) <span
                                            class="text-xs text-gray-400">(opsional)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nisab_saat_ini" value="{{ old('nisab_saat_ini', $transaksi->nisab_saat_ini) }}"
                                            min="0" step="1000"
                                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Persentase Zakat (%)</label>
                                    <input type="number" name="persentase_zakat" id="malPersen"
                                        value="{{ old('persentase_zakat', $transaksi->tipeZakat->persentase_zakat ?? 2.5) }}" 
                                        min="0" max="100" step="0.1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex items-start gap-3">
                                <input type="checkbox" name="sudah_haul" id="sudahHaul" value="1"
                                    {{ old('sudah_haul', $transaksi->sudah_haul) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded mt-0.5">
                                <div>
                                    <label for="sudahHaul" class="text-sm font-medium text-gray-900 cursor-pointer">Harta
                                        sudah mencapai haul (1 tahun hijriyah)</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Centang jika sudah dimiliki lebih dari atau
                                        sama dengan 1 tahun penuh</p>
                                </div>
                            </div>
                            <div id="wrapHaul" class="{{ old('sudah_haul', $transaksi->sudah_haul) ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Haul</label>
                                <input type="date" name="tanggal_mulai_haul" value="{{ old('tanggal_mulai_haul', $transaksi->tanggal_mulai_haul ? $transaksi->tanggal_mulai_haul->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-700 mb-1">Total Zakat Mal</p>
                                <p class="text-2xl font-bold text-blue-600" id="malTotalDisp">
                                    Rp {{ number_format($transaksi->jumlah ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5" id="malDetailDisp"></p>
                                <input type="hidden" name="jumlah" id="hdnJumlahMal" value="{{ $transaksi->jumlah ?? 0 }}">
                            </div>
                        </div>

                        {{-- [TAMBAHAN] ══ PANEL FIDYAH ══ --}}
                        <div id="panelFidyah" class="hidden space-y-4">
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-amber-800">Pembayaran Fidyah</p>
                                </div>
                                <p class="text-xs text-amber-700">
                                    Fidyah: 1 mud ({{ $fidyahInfo['berat_per_hari_gram'] }} gram) bahan pokok per hari,
                                    atau makanan siap santap sekali makan, atau uang senilai makanan.
                                </p>
                            </div>

                            {{-- Jumlah Hari --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Hari <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="fidyahHari" min="1" step="1"
                                    value="{{ old('fidyah_jumlah_hari', $transaksi->fidyah_jumlah_hari ?? 1) }}"
                                    class="w-full sm:w-48 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500"
                                    placeholder="Jumlah hari puasa yang ditinggalkan">
                            </div>

                            {{-- Tipe Fidyah --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cara Pembayaran Fidyah <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <label id="fidyahCardMentah"
                                        class="fidyah-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all border-gray-200 hover:bg-gray-50">
                                        <input type="radio" name="_fidyah_tipe_ui" value="mentah" class="hidden fidyah-radio"
                                            {{ old('fidyah_tipe', $transaksi->fidyah_tipe) === 'mentah' ? 'checked' : '' }}>
                                        <div class="w-11 h-11 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900">Bahan Mentah</p>
                                        <p class="text-xs text-gray-500 text-center">Bahan pokok (beras, dll)</p>
                                    </label>
                                    <label id="fidyahCardMatang"
                                        class="fidyah-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all border-gray-200 hover:bg-gray-50">
                                        <input type="radio" name="_fidyah_tipe_ui" value="matang" class="hidden fidyah-radio"
                                            {{ old('fidyah_tipe', $transaksi->fidyah_tipe) === 'matang' ? 'checked' : '' }}>
                                        <div class="w-11 h-11 rounded-full bg-orange-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900">Makanan Siap Santap</p>
                                        <p class="text-xs text-gray-500 text-center">Makanan matang/box</p>
                                    </label>
                                    <label id="fidyahCardTunai"
                                        class="fidyah-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all border-gray-200 hover:bg-gray-50">
                                        <input type="radio" name="_fidyah_tipe_ui" value="tunai" class="hidden fidyah-radio"
                                            {{ old('fidyah_tipe', $transaksi->fidyah_tipe) === 'tunai' ? 'checked' : '' }}>
                                        <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900">Tunai / Uang</p>
                                        <p class="text-xs text-gray-500 text-center">Uang senilai makanan</p>
                                    </label>
                                </div>
                            </div>

                            {{-- Sub-panel MENTAH --}}
                            <div id="fidyahPanelMentah" class="hidden space-y-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Bahan Pokok <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="fidyahNamaBahan"
                                        value="{{ old('fidyah_nama_bahan', $transaksi->fidyah_nama_bahan) }}"
                                        placeholder="Contoh: Beras putih, Beras merah, dll"
                                        maxlength="100"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Berat per Hari (gram)
                                            <span class="text-xs text-gray-400">(default: {{ $fidyahInfo['berat_per_hari_gram'] }}g = 1 mud)</span>
                                        </label>
                                        <input type="number" id="fidyahBeratPerHari"
                                            value="{{ old('fidyah_berat_per_hari_gram', $transaksi->fidyah_berat_per_hari_gram ?? $fidyahInfo['berat_per_hari_gram']) }}"
                                            min="100" max="2000" step="1"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Berat</label>
                                        <div class="px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg">
                                            <span id="fidyahTotalBeratDisp" class="font-semibold text-amber-700">0 kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Sub-panel MATANG --}}
                            <div id="fidyahPanelMatang" class="hidden space-y-4 bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Jumlah Box <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="fidyahJumlahBox"
                                            value="{{ old('fidyah_jumlah_box', $transaksi->fidyah_jumlah_box) }}"
                                            min="1" step="1"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500"
                                            placeholder="Otomatis = jumlah hari">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Harga per Box (Rp) <span class="text-xs text-gray-400">(opsional)</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                            <input type="number" id="fidyahHargaBox"
                                                value="{{ old('fidyah_harga_per_box', $transaksi->fidyah_harga_per_box ?? 0) }}"
                                                min="0" step="1000"
                                                class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Menu Makanan <span class="text-xs text-gray-400">(opsional)</span>
                                    </label>
                                    <input type="text" id="fidyahMenu"
                                        value="{{ old('fidyah_menu_makanan', $transaksi->fidyah_menu_makanan) }}"
                                        placeholder="Contoh: Nasi + lauk + sayur"
                                        maxlength="200"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Cara Serah <span class="text-red-500">*</span>
                                    </label>
                                    <select id="fidyahCaraSerah"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-amber-500">
                                        <option value="">-- Pilih cara penyerahan --</option>
                                        <option value="dibagikan" {{ old('fidyah_cara_serah', $transaksi->fidyah_cara_serah) === 'dibagikan' ? 'selected' : '' }}>Dibagikan kepada mustahik</option>
                                        <option value="dijamu"    {{ old('fidyah_cara_serah', $transaksi->fidyah_cara_serah) === 'dijamu'    ? 'selected' : '' }}>Dijamu (makan bersama)</option>
                                        <option value="via_lembaga" {{ old('fidyah_cara_serah', $transaksi->fidyah_cara_serah) === 'via_lembaga' ? 'selected' : '' }}>Diserahkan via lembaga/masjid</option>
                                    </select>
                                </div>
                                <div class="bg-white border border-orange-200 rounded-lg p-3">
                                    <p class="text-xs text-gray-600">
                                        Total: <strong id="fidyahTotalMatangDisp" class="text-orange-700">0 box</strong>
                                    </p>
                                </div>
                            </div>

                            {{-- Sub-panel TUNAI --}}
                            <div id="fidyahPanelTunai" class="hidden space-y-4 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Harga per Hari (Rp) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                            <input type="number" id="fidyahHargaPerHari"
                                                value="{{ old('_fidyah_harga_per_hari', $transaksi->fidyah_jumlah_hari > 0 && $transaksi->jumlah > 0 ? intval($transaksi->jumlah / $transaksi->fidyah_jumlah_hari) : 0) }}"
                                                min="0" step="1000"
                                                class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-green-500"
                                                placeholder="Harga makanan per hari">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Fidyah</label>
                                        <div class="px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg">
                                            <span id="fidyahTotalTunaiDisp" class="font-semibold text-green-700">Rp 0</span>
                                        </div>
                                        <input type="hidden" name="jumlah" id="hdnJumlahFidyahTunai" value="{{ old('jumlah', $transaksi->jumlah ?? 0) }}">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600">
                                    Total = harga per hari × jumlah hari. Pembayaran dilanjutkan ke step berikutnya.
                                </p>
                            </div>
                        </div>{{-- /panelFidyah --}}

                    </div>{{-- /space-y-5 --}}

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="history.back()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>
                        <div class="flex gap-3">
                            <button type="button" id="btnS2Next" onclick="goToStep3()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            {{-- [TAMBAHAN] label sekarang pakai <span> agar bisa diganti JS --}}
                            <button type="submit" id="btnBerasSave"
                                class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span id="btnBerasSaveLabel">Simpan Transaksi Beras</span>
                            </button>
                        </div>
                    </div>
                </div>{{-- /step2 --}}

                {{-- ═══════════ STEP 3 — PEMBAYARAN ═══════════ --}}
                <div id="step3" class="step-panel hidden">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                        <span class="inline-flex w-5 h-5 rounded-full bg-blue-600 text-white text-xs items-center justify-center mr-1.5 font-bold">3</span>
                        Metode Pembayaran
                    </h3>
                    <div class="space-y-5">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Cara Pembayaran <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label id="cardTunai"
                                    class="pay-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === 'tunai' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="metode_pembayaran" value="tunai"
                                        class="hidden pay-radio"
                                        {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === 'tunai' ? 'checked' : '' }}>
                                    <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Tunai</p>
                                    <p class="text-xs text-gray-500 text-center">Bayar langsung</p>
                                </label>
                                <label id="cardTransfer"
                                    class="pay-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="metode_pembayaran" value="transfer"
                                        class="hidden pay-radio"
                                        {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === 'transfer' ? 'checked' : '' }}>
                                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4m-9 4v10" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Transfer Bank</p>
                                    <p class="text-xs text-gray-500 text-center">Ke rekening masjid</p>
                                </label>
                                <label id="cardQris"
                                    class="pay-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === 'qris' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="metode_pembayaran" value="qris"
                                        class="hidden pay-radio" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === 'qris' ? 'checked' : '' }}>
                                    <div class="w-11 h-11 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">QRIS</p>
                                    <p class="text-xs text-gray-500 text-center">Scan QR masjid</p>
                                </label>
                            </div>
                            @error('metode_pembayaran')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- JUMLAH DIBAYAR --}}
                        <div id="wrapJmlDibayar" class="{{ $transaksi->metode_pembayaran ? '' : 'hidden' }}">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-sm font-medium text-gray-700">Jumlah yang Diserahkan (Rp)</label>
                                        <button type="button" id="btnBayarPas"
                                            class="text-xs font-semibold text-blue-600 hover:underline">Isi Sesuai Zakat</button>
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="jumlah_dibayar" id="jmlDibayar"
                                            value="{{ old('jumlah_dibayar', $transaksi->jumlah_dibayar) }}" min="0" step="1000"
                                            placeholder="Kosongkan = bayar pas sesuai zakat wajib"
                                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Jika membayar <strong>lebih</strong>, kelebihan
                                        otomatis dicatat sebagai <strong>infaq sukarela</strong>.</p>
                                </div>
                                <div id="boxKalkulasiInfaq" class="hidden rounded-lg border p-3 mt-3">
                                    <div id="teksInfaq" class="text-sm"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Tunai --}}
                        <div id="infoTunaiSec" class="{{ $transaksi->metode_pembayaran === 'tunai' ? '' : 'hidden' }} bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-800">Pembayaran Tunai</p>
                            <p class="text-xs text-gray-600 mt-1">Transaksi akan <strong>langsung terverifikasi</strong> setelah disimpan.</p>
                        </div>

                        {{-- Info Transfer --}}
                        <div id="infoTransferSec" class="{{ $transaksi->metode_pembayaran === 'transfer' ? '' : 'hidden' }} space-y-3">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-800 mb-3">Rekening Transfer Masjid</p>
                                @if ($rekeningList->isNotEmpty())
                                    @foreach ($rekeningList as $rek)
                                        <div class="bg-white border border-gray-200 rounded p-3 flex items-center justify-between mb-2 last:mb-0">
                                            <div>
                                                <p class="text-xs font-bold text-gray-800">{{ $rek->nama_bank }}</p>
                                                <p class="text-sm font-mono font-bold text-gray-900 tracking-wider mt-0.5">
                                                    {{ $rek->nomor_rekening }}</p>
                                                <p class="text-xs text-gray-500">a.n. {{ $rek->nama_pemilik }}</p>
                                            </div>
                                            <button type="button" onclick="salin('{{ $rek->nomor_rekening }}')"
                                                class="text-xs text-blue-600 hover:bg-blue-100 px-2 py-1 rounded transition-all font-medium">Salin</button>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-xs text-gray-500">Hubungi amil untuk info rekening.</p>
                                @endif
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-xs text-gray-600">Transaksi akan <strong>langsung terverifikasi</strong> setelah disimpan.</p>
                            </div>
                        </div>

                        {{-- ★ Info QRIS — dari KonfigurasiQris (tanpa no referensi) ★ --}}
                        <div id="infoQrisSec" class="{{ $transaksi->metode_pembayaran === 'qris' ? '' : 'hidden' }} space-y-3">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-800 mb-4">QRIS {{ $masjid->nama }}</p>
                                @php $qrisImageUrl = $qrisConfig?->qris_image_url ?? null; @endphp
                                @if ($qrisImageUrl)
                                    <div class="flex justify-center">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 inline-flex flex-col items-center gap-3">
                                            <img src="{{ $qrisImageUrl }}" class="w-48 h-48 object-contain"
                                                alt="QRIS {{ $masjid->nama }}" loading="lazy">
                                            <div class="text-center">
                                                <p class="text-xs font-medium text-gray-800">{{ $masjid->nama }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Scan QR di atas untuk membayar</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex justify-center">
                                        <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                                            <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-500">QRIS belum dikonfigurasi</p>
                                            <p class="text-xs text-gray-400 mt-1">Tunjukkan QRIS fisik masjid kepada muzakki</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-xs text-gray-600">Transaksi akan <strong>langsung terverifikasi</strong> setelah disimpan.</p>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan / Keterangan <span
                                    class="text-xs text-gray-400">(opsional)</span></label>
                            <textarea name="keterangan" rows="2" placeholder="Untuk program tertentu, atas nama keluarga, dll."
                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 resize-none">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                        </div>

                    </div>{{-- /space-y-5 --}}

                    {{-- TOMBOL NAVIGASI STEP 3 --}}
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="goToStep2()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>
                        <button type="submit" id="btnFinalSave"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Transaksi
                        </button>
                    </div>
                </div>{{-- /step3 --}}

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ══════════════════════════════════════════════════════════════
        // KONSTANTA & STATE
        // ══════════════════════════════════════════════════════════════
        const BAZNAS = {
            nominalPerJiwa: {{ $zakatFitrahInfo['nominal_per_jiwa'] }},
            berasKg: {{ $zakatFitrahInfo['beras_kg'] }},
            berasLiter: {{ $zakatFitrahInfo['beras_liter'] }},
        };
        // [TAMBAHAN] konstanta fidyah
        const FIDYAH_BERAT_DEFAULT = {{ $fidyahInfo['berat_per_hari_gram'] }};
        const TIPE_DATA = @json($tipeZakatList ?? []);
        const TRANSaksi = @json($transaksi);

        let activePanelZ = null; // 'beras' | 'tunaiF' | 'mal' | 'fidyah' | null
        // [TAMBAHAN] state fidyah
        let activeFidyahTipe = null; // 'mentah' | 'matang' | 'tunai' | null
        let currentStep = 2; // Mulai dari step 2

        // ══════════════════════════════════════════════════════════════
        // FORMAT
        // ══════════════════════════════════════════════════════════════
        function fmt(n) {
            return new Intl.NumberFormat('id-ID').format(Math.round(n || 0));
        }

        // ══════════════════════════════════════════════════════════════
        // NAVIGASI STEP
        // ══════════════════════════════════════════════════════════════
        function goToStep3() {
            if (!validateStep(2)) return;
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.remove('hidden');
            
            // Update dots
            document.getElementById('dot2').classList.remove('bg-blue-600');
            document.getElementById('dot2').classList.add('bg-green-600');
            document.getElementById('dot2').textContent = '✓';
            document.getElementById('dot3').classList.remove('bg-gray-200', 'text-gray-500');
            document.getElementById('dot3').classList.add('bg-blue-600', 'text-white');
            document.getElementById('line23').classList.add('bg-green-600');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goToStep2() {
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            
            // Update dots
            document.getElementById('dot3').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('dot3').classList.add('bg-gray-200', 'text-gray-500');
            document.getElementById('dot2').classList.remove('bg-green-600');
            document.getElementById('dot2').classList.add('bg-blue-600', 'text-white');
            document.getElementById('dot2').textContent = '2';
            document.getElementById('line23').classList.remove('bg-green-600');
            document.getElementById('line23').classList.add('bg-gray-200');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ══════════════════════════════════════════════════════════════
        // VALIDASI
        // ══════════════════════════════════════════════════════════════
        function validateStep(step) {
            if (step === 2) {
                if (!document.getElementById('jenisId').value) {
                    alert('Pilih jenis zakat.');
                    return false;
                }
                if (!document.getElementById('tipeId').value) {
                    alert('Pilih tipe zakat.');
                    return false;
                }
                if (activePanelZ === 'beras') {
                    if ((parseFloat(document.getElementById('berasKg').value) || 0) <= 0) {
                        alert('Jumlah beras harus > 0.');
                        return false;
                    }
                // [TAMBAHAN] validasi fidyah
                } else if (activePanelZ === 'fidyah') {
                    const hari = parseInt(document.getElementById('fidyahHari').value) || 0;
                    if (hari <= 0) { alert('Jumlah hari fidyah harus > 0.'); return false; }
                    if (!activeFidyahTipe) { alert('Pilih cara pembayaran fidyah.'); return false; }
                    if (activeFidyahTipe === 'mentah') {
                        if (!document.getElementById('fidyahNamaBahan').value.trim()) {
                            alert('Nama bahan pokok harus diisi.'); return false;
                        }
                    } else if (activeFidyahTipe === 'matang') {
                        if ((parseInt(document.getElementById('fidyahJumlahBox').value) || 0) <= 0) {
                            alert('Jumlah box harus > 0.'); return false;
                        }
                        if (!document.getElementById('fidyahCaraSerah').value) {
                            alert('Pilih cara penyerahan fidyah.'); return false;
                        }
                    } else if (activeFidyahTipe === 'tunai') {
                        if ((parseFloat(document.getElementById('fidyahHargaPerHari').value) || 0) <= 0) {
                            alert('Harga per hari fidyah tunai harus diisi.'); return false;
                        }
                    }
                } else {
                    if (getJumlahZakat() <= 0) {
                        alert('Jumlah zakat tidak valid.');
                        return false;
                    }
                }
                return true;
            }
            return true;
        }

        function getJumlahZakat() {
            if (activePanelZ === 'tunaiF') return parseFloat(document.getElementById('hdnJumlahTunai').value) || 0;
            if (activePanelZ === 'mal') return parseFloat(document.getElementById('hdnJumlahMal').value) || 0;
            // [TAMBAHAN]
            if (activePanelZ === 'fidyah' && activeFidyahTipe === 'tunai')
                return parseFloat(document.getElementById('hdnJumlahFidyahTunai').value) || 0;
            return 0;
        }

        // ══════════════════════════════════════════════════════════════
        // GPS
        // ══════════════════════════════════════════════════════════════
        document.getElementById('btnGps')?.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolocation.');
                return;
            }
            
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Mengambil lokasi...';
            
            navigator.geolocation.getCurrentPosition(
                pos => {
                    document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
                    document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
                    this.disabled = false;
                    this.innerHTML = 'Lokasi berhasil diambil';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                },
                err => {
                    this.disabled = false;
                    this.innerHTML = originalText;
                    alert('Gagal mengambil lokasi: ' + err.message);
                }
            );
        });

        // ══════════════════════════════════════════════════════════════
        // ★ FUNGSI: RENDER DAFTAR NAMA JIWA ★
        // ══════════════════════════════════════════════════════════════
        function renderNamaJiwa(listId, counterId, wrapId, jumlah, existingNames = []) {
            const wrap = document.getElementById(wrapId);
            const listEl = document.getElementById(listId);

            if (jumlah <= 1) {
                wrap.classList.add('hidden');
                listEl.innerHTML = '';
                return;
            }

            wrap.classList.remove('hidden');
            listEl.innerHTML = '';

            for (let i = 0; i < jumlah; i++) {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-3';
                row.innerHTML = `
                    <div class="flex-shrink-0 w-6 text-xs font-medium text-gray-400">${i + 1}.</div>
                    <input type="text"
                        name="nama_jiwa_json[]"
                        value="${existingNames[i] ? existingNames[i].replace(/"/g, '&quot;') : ''}"
                        placeholder="Nama jiwa ke-${i + 1}"
                        maxlength="100"
                        autocomplete="off"
                        class="flex-1 px-3 py-2 text-sm border border-gray-200 bg-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-200 transition-all">
                `;
                listEl.appendChild(row);
            }

            updateNamaCounter(listId, counterId, jumlah);
            
            listEl.querySelectorAll('input').forEach(inp => {
                inp.addEventListener('input', () => updateNamaCounter(listId, counterId, jumlah));
            });
        }

        function updateNamaCounter(listId, counterId, total) {
            const filled = [...document.getElementById(listId).querySelectorAll('input')].filter(i => i.value.trim() !== '').length;
            const el = document.getElementById(counterId);
            if (el) el.textContent = filled > 0 ? `${filled}/${total} diisi` : '0 diisi';
        }

        // ══════════════════════════════════════════════════════════════
        // STEP 2: JENIS & TIPE ZAKAT
        // ══════════════════════════════════════════════════════════════
        document.getElementById('jenisId').addEventListener('change', function() {
            const jenisId = this.value;
            const tipeEl = document.getElementById('tipeId');
            const wrapTipe = document.getElementById('wrapTipe');
            tipeEl.innerHTML = '<option value="">-- Pilih Tipe --</option>';
            resetPanelZakat();
            
            if (!jenisId) {
                wrapTipe.classList.add('hidden');
                return;
            }
            
            const list = TIPE_DATA[jenisId] || [];
            if (list.length > 0) {
                list.forEach(t => {
                    const o = new Option(t.nama, t.uuid);
                    o.dataset.nama = t.nama.toLowerCase();
                    o.dataset.persentase = t.persentase_zakat || 2.5;
                    o.dataset.nisabEmas = t.nisab_emas_gram || 0;
                    o.dataset.requireHaul = t.requires_haul ? '1' : '0';
                    tipeEl.appendChild(o);
                });
                wrapTipe.classList.remove('hidden');
            } else {
                wrapTipe.classList.add('hidden');
            }
        });

        document.getElementById('tipeId').addEventListener('change', function() {
            const jenisEl = document.getElementById('jenisId');
            const namaJenis = (jenisEl.options[jenisEl.selectedIndex]?.dataset.nama || '').toLowerCase();
            const namaTipe = (this.options[this.selectedIndex]?.dataset.nama || '').toLowerCase();
            
            resetPanelZakat();
            
            if (!this.value) return;
            
            const isFitrah = namaJenis.includes('fitrah');
            const isMal = namaJenis.includes('mal');
            // [TAMBAHAN]
            const isFidyah = namaJenis.includes('fidyah');
            const isBeras = namaTipe.includes('beras');
            
            if (isFitrah && isBeras) tampilPanelBeras();
            else if (isFitrah) tampilPanelFitrahTunai();
            else if (isMal) tampilPanelMal(this.options[this.selectedIndex]);
            // [TAMBAHAN]
            else if (isFidyah) tampilPanelFidyah();
        });

        function resetPanelZakat() {
            // [TAMBAHAN] tambah 'panelFidyah'
            ['panelBeras', 'panelFitrahTunai', 'panelMal', 'panelFidyah'].forEach(id =>
                document.getElementById(id)?.classList.add('hidden')
            );
            document.getElementById('btnBerasSave').classList.add('hidden');
            document.getElementById('btnS2Next').classList.remove('hidden');
            document.getElementById('hdnBeras').value = '0';
            // [TAMBAHAN] reset state fidyah
            document.getElementById('hdnFidyahHari').value = '0';
            document.getElementById('hdnFidyahTipe').value = '';
            activePanelZ = null;
            activeFidyahTipe = null;
        }

        // ─── PANEL BERAS ───────────────────────────────────────
        function tampilPanelBeras() {
            activePanelZ = 'beras';
            document.getElementById('hdnBeras').value = '1';
            document.getElementById('panelBeras').classList.remove('hidden');
            document.getElementById('btnBerasSave').classList.remove('hidden');
            document.getElementById('btnS2Next').classList.add('hidden');
            // [TAMBAHAN] set label tombol
            document.getElementById('btnBerasSaveLabel').textContent = 'Simpan Transaksi Beras';

            const jiwa = parseInt(document.getElementById('berasJiwa').value) || 1;
            const existingNames = TRANSaksi?.nama_jiwa_json || [];
            renderNamaJiwa('listNamaBeras', 'counterNamaBeras', 'wrapNamaBerasJiwa', jiwa, existingNames);
        }

        ['berasJiwa', 'berasKg'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function() {
                if (id === 'berasJiwa') {
                    const jiwa = parseInt(this.value) || 1;
                    const existingNames = TRANSaksi?.nama_jiwa_json || [];
                    renderNamaJiwa('listNamaBeras', 'counterNamaBeras', 'wrapNamaBerasJiwa', jiwa, existingNames);
                }
            });
        });

        // ─── PANEL FITRAH TUNAI ──────────────────────────────────
        function tampilPanelFitrahTunai() {
            activePanelZ = 'tunaiF';
            document.getElementById('panelFitrahTunai').classList.remove('hidden');
            hitungFitrahTunai();
            
            const jiwa = parseInt(document.getElementById('tunaiJiwa').value) || 1;
            const existingNames = TRANSaksi?.nama_jiwa_json || [];
            renderNamaJiwa('listNamaTunai', 'counterNamaTunai', 'wrapNamaTunaiJiwa', jiwa, existingNames);
        }

        ['tunaiJiwa', 'tunaiNominal'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function() {
                hitungFitrahTunai();
                if (id === 'tunaiJiwa') {
                    const jiwa = parseInt(this.value) || 1;
                    const existingNames = TRANSaksi?.nama_jiwa_json || [];
                    renderNamaJiwa('listNamaTunai', 'counterNamaTunai', 'wrapNamaTunaiJiwa', jiwa, existingNames);
                }
            });
        });

        function hitungFitrahTunai() {
            const jiwa = parseFloat(document.getElementById('tunaiJiwa').value) || 0;
            const nominal = parseFloat(document.getElementById('tunaiNominal').value) || 0;
            const total = jiwa * nominal;
            document.getElementById('hdnJumlahTunai').value = Math.round(total);
        }

        // ─── PANEL MAL ───────────────────────────────────────────
        function tampilPanelMal(tipeOpt) {
            activePanelZ = 'mal';
            document.getElementById('panelMal').classList.remove('hidden');
            document.getElementById('malPersen').value = tipeOpt.dataset.persentase || 2.5;
            
            let nisabHtml = '';
            if (tipeOpt.dataset.nisabEmas > 0) {
                nisabHtml += `<p>Nisab emas: ${tipeOpt.dataset.nisabEmas} gram</p>`;
            }
            if (tipeOpt.dataset.requireHaul === '1') {
                nisabHtml += `<p><strong>Membutuhkan haul</strong> (1 tahun hijriyah)</p>`;
            }
            
            const box = document.getElementById('nisabBox');
            if (nisabHtml) {
                document.getElementById('nisabIsi').innerHTML = nisabHtml;
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
            
            hitungMal();
        }

        ['malHarta', 'malPersen'].forEach(id =>
            document.getElementById(id)?.addEventListener('input', hitungMal)
        );

        function hitungMal() {
            const h = parseFloat(document.getElementById('malHarta').value) || 0;
            const p = parseFloat(document.getElementById('malPersen').value) || 2.5;
            const t = h * (p / 100);
            document.getElementById('malTotalDisp').textContent = 'Rp ' + fmt(t);
            document.getElementById('malDetailDisp').textContent = `${p}% x Rp ${fmt(h)} = Rp ${fmt(t)}`;
            document.getElementById('hdnJumlahMal').value = Math.round(t);
        }

        document.getElementById('sudahHaul')?.addEventListener('change', function() {
            document.getElementById('wrapHaul').classList.toggle('hidden', !this.checked);
        });

        // ══════════════════════════════════════════════════════════════
        // [TAMBAHAN] PANEL FIDYAH
        // ══════════════════════════════════════════════════════════════
        function tampilPanelFidyah() {
            activePanelZ = 'fidyah';
            document.getElementById('panelFidyah').classList.remove('hidden');

            // Restore tipe fidyah jika edit mode
            const existingTipe = TRANSaksi?.fidyah_tipe || null;
            if (existingTipe) {
                const radio = document.querySelector(`.fidyah-radio[value="${existingTipe}"]`);
                if (radio) {
                    radio.checked = true;
                    pilihTipeFidyah(existingTipe);
                }
            }
            hitungFidyah();
        }

        document.querySelectorAll('.fidyah-radio').forEach(r => {
            r.addEventListener('change', function() {
                pilihTipeFidyah(this.value);
            });
        });

        function pilihTipeFidyah(tipe) {
            activeFidyahTipe = tipe;
            document.getElementById('hdnFidyahTipe').value = tipe;

            // Warna kartu
            document.querySelectorAll('.fidyah-card').forEach(c => {
                const checked = c.querySelector('input').checked;
                c.classList.toggle('border-amber-500', checked);
                c.classList.toggle('bg-amber-50', checked);
                c.classList.toggle('border-gray-200', !checked);
            });

            // Sembunyikan semua sub-panel fidyah
            ['fidyahPanelMentah', 'fidyahPanelMatang', 'fidyahPanelTunai'].forEach(id =>
                document.getElementById(id).classList.add('hidden')
            );

            if (tipe === 'mentah') {
                document.getElementById('fidyahPanelMentah').classList.remove('hidden');
                document.getElementById('btnBerasSave').classList.remove('hidden');
                document.getElementById('btnS2Next').classList.add('hidden');
                document.getElementById('btnBerasSaveLabel').textContent = 'Simpan Fidyah Bahan Mentah';
            } else if (tipe === 'matang') {
                document.getElementById('fidyahPanelMatang').classList.remove('hidden');
                const hari = parseInt(document.getElementById('fidyahHari').value) || 1;
                const boxEl = document.getElementById('fidyahJumlahBox');
                if (!boxEl.value || parseInt(boxEl.value) <= 0) boxEl.value = hari;
                document.getElementById('btnBerasSave').classList.remove('hidden');
                document.getElementById('btnS2Next').classList.add('hidden');
                document.getElementById('btnBerasSaveLabel').textContent = 'Simpan Fidyah Makanan Matang';
            } else if (tipe === 'tunai') {
                document.getElementById('fidyahPanelTunai').classList.remove('hidden');
                document.getElementById('btnBerasSave').classList.add('hidden');
                document.getElementById('btnS2Next').classList.remove('hidden');
                hitungFidyahTunai();
            }
            hitungFidyah();
        }

        document.getElementById('fidyahHari')?.addEventListener('input', function() {
            document.getElementById('hdnFidyahHari').value = this.value;
            if (activeFidyahTipe === 'matang') {
                document.getElementById('fidyahJumlahBox').value = parseInt(this.value) || 1;
                hitungFidyahMatang();
            }
            hitungFidyah();
        });

        document.getElementById('fidyahBeratPerHari')?.addEventListener('input', function() {
            document.getElementById('hdnFidyahBeratPerHari').value = this.value;
            hitungFidyahMentah();
        });

        document.getElementById('fidyahNamaBahan')?.addEventListener('input', function() {
            document.getElementById('hdnFidyahNamaBahan').value = this.value;
        });

        document.getElementById('fidyahJumlahBox')?.addEventListener('input', function() {
            document.getElementById('hdnFidyahJumlahBox').value = this.value;
            hitungFidyahMatang();
        });

        document.getElementById('fidyahMenu')?.addEventListener('input', function() {
            document.getElementById('hdnFidyahMenu').value = this.value;
        });

        document.getElementById('fidyahHargaBox')?.addEventListener('input', function() {
            document.getElementById('hdnFidyahHargaBox').value = this.value;
            hitungFidyahMatang();
        });

        document.getElementById('fidyahCaraSerah')?.addEventListener('change', function() {
            document.getElementById('hdnFidyahCaraSerah').value = this.value;
        });

        document.getElementById('fidyahHargaPerHari')?.addEventListener('input', hitungFidyahTunai);

        function hitungFidyah() {
            const hari = parseInt(document.getElementById('fidyahHari').value) || 0;
            document.getElementById('hdnFidyahHari').value = hari;
            if (activeFidyahTipe === 'mentah') hitungFidyahMentah();
            if (activeFidyahTipe === 'matang') hitungFidyahMatang();
            if (activeFidyahTipe === 'tunai')  hitungFidyahTunai();
        }

        function hitungFidyahMentah() {
            const hari  = parseInt(document.getElementById('fidyahHari').value) || 0;
            const berat = parseInt(document.getElementById('fidyahBeratPerHari').value) || FIDYAH_BERAT_DEFAULT;
            const totalKg = ((hari * berat) / 1000).toFixed(2);
            document.getElementById('fidyahTotalBeratDisp').textContent = totalKg + ' kg';
            document.getElementById('hdnFidyahBeratPerHari').value = berat;
        }

        function hitungFidyahMatang() {
            const box = parseInt(document.getElementById('fidyahJumlahBox').value) || 0;
            document.getElementById('fidyahTotalMatangDisp').textContent = box + ' box';
            document.getElementById('hdnFidyahJumlahBox').value = box;
            document.getElementById('hdnFidyahHargaBox').value = parseFloat(document.getElementById('fidyahHargaBox').value) || 0;
        }

        function hitungFidyahTunai() {
            const hari  = parseInt(document.getElementById('fidyahHari').value) || 0;
            const harga = parseFloat(document.getElementById('fidyahHargaPerHari').value) || 0;
            const total = hari * harga;
            document.getElementById('fidyahTotalTunaiDisp').textContent = 'Rp ' + fmt(total);
            document.getElementById('hdnJumlahFidyahTunai').value = Math.round(total);
        }

        function syncHiddenFidyah() {
            document.getElementById('hdnFidyahHari').value = document.getElementById('fidyahHari').value || 0;
            document.getElementById('hdnFidyahTipe').value = activeFidyahTipe || '';
            if (activeFidyahTipe === 'mentah') {
                document.getElementById('hdnFidyahNamaBahan').value  = document.getElementById('fidyahNamaBahan').value;
                document.getElementById('hdnFidyahBeratPerHari').value = document.getElementById('fidyahBeratPerHari').value;
            } else if (activeFidyahTipe === 'matang') {
                document.getElementById('hdnFidyahJumlahBox').value  = document.getElementById('fidyahJumlahBox').value;
                document.getElementById('hdnFidyahMenu').value       = document.getElementById('fidyahMenu').value;
                document.getElementById('hdnFidyahHargaBox').value   = document.getElementById('fidyahHargaBox').value;
                document.getElementById('hdnFidyahCaraSerah').value  = document.getElementById('fidyahCaraSerah').value;
            }
        }

        // ══════════════════════════════════════════════════════════════
        // STEP 3: METODE PEMBAYARAN
        // ══════════════════════════════════════════════════════════════
        document.querySelectorAll('.pay-radio').forEach(r => {
            r.addEventListener('change', function() {
                const val = this.value;
                
                document.querySelectorAll('.pay-card').forEach(c => {
                    const chk = c.querySelector('input').checked;
                    c.classList.toggle('border-blue-500', chk);
                    c.classList.toggle('bg-blue-50', chk);
                    c.classList.toggle('border-gray-200', !chk);
                });

                ['infoTunaiSec', 'infoTransferSec', 'infoQrisSec'].forEach(id =>
                    document.getElementById(id).classList.add('hidden')
                );

                document.getElementById('wrapJmlDibayar').classList.remove('hidden');

                if (val === 'tunai') {
                    document.getElementById('infoTunaiSec').classList.remove('hidden');
                } else if (val === 'transfer') {
                    document.getElementById('infoTransferSec').classList.remove('hidden');
                } else if (val === 'qris') {
                    document.getElementById('infoQrisSec').classList.remove('hidden');
                }

                const jd = document.getElementById('jmlDibayar');
                const jz = getJumlahZakat();
                if ((!jd.value || parseFloat(jd.value) === 0) && jz > 0) {
                    jd.value = jz;
                }
                
                hitungKalkulasiInfaq();
            });
        });

        document.getElementById('btnBayarPas')?.addEventListener('click', () => {
            document.getElementById('jmlDibayar').value = getJumlahZakat();
            hitungKalkulasiInfaq();
        });

        document.getElementById('jmlDibayar')?.addEventListener('input', hitungKalkulasiInfaq);

        function hitungKalkulasiInfaq() {
            const jz = getJumlahZakat();
            const jd = parseFloat(document.getElementById('jmlDibayar').value) || 0;
            const box = document.getElementById('boxKalkulasiInfaq');
            const teks = document.getElementById('teksInfaq');

            if (jz <= 0 || jd <= 0) {
                box.classList.add('hidden');
                return;
            }

            const infaq = Math.max(0, jd - jz);
            const kurang = Math.max(0, jz - jd);

            box.classList.remove('hidden');
            box.classList.remove('bg-amber-50', 'border-amber-200', 'bg-red-50', 'border-red-200', 'bg-green-50', 'border-green-200');

            if (infaq > 0) {
                box.classList.add('bg-amber-50', 'border-amber-200');
                teks.innerHTML = `Kelebihan <strong>Rp ${fmt(infaq)}</strong> otomatis dicatat sebagai <strong>infaq sukarela</strong>.`;
            } else if (kurang > 0) {
                box.classList.add('bg-red-50', 'border-red-200');
                teks.innerHTML = `Kurang <strong>Rp ${fmt(kurang)}</strong> dari zakat wajib (Rp ${fmt(jz)}).`;
            } else {
                box.classList.add('bg-green-50', 'border-green-200');
                teks.innerHTML = `Pembayaran <strong>pas</strong> sesuai zakat wajib (Rp ${fmt(jz)}).`;
            }
        }

        // ══════════════════════════════════════════════════════════════
        // FORM SUBMIT
        // ══════════════════════════════════════════════════════════════
        document.getElementById('mainForm').addEventListener('submit', function(e) {
            if (activePanelZ === 'beras') {
                if ((parseFloat(document.getElementById('berasKg').value) || 0) <= 0) {
                    e.preventDefault();
                    alert('Jumlah beras harus > 0.');
                    return false;
                }
                spinBtn(document.getElementById('btnBerasSave'), 'Menyimpan...');
                return true;
            }

            // [TAMBAHAN] fidyah non-tunai — langsung submit tanpa step 3
            if (activePanelZ === 'fidyah' && activeFidyahTipe !== 'tunai') {
                if (!validateStep(2)) { e.preventDefault(); return false; }
                syncHiddenFidyah();
                spinBtn(document.getElementById('btnBerasSave'), 'Menyimpan...');
                return true;
            }

            if (!document.getElementById('jenisId').value) {
                e.preventDefault();
                alert('Pilih jenis zakat.');
                return false;
            }
            
            if (!document.getElementById('tipeId').value) {
                e.preventDefault();
                alert('Pilih tipe zakat.');
                return false;
            }
            
            // [TAMBAHAN] fidyah tunai ikut ke step 3
            if (activePanelZ !== 'fidyah' && getJumlahZakat() <= 0) {
                e.preventDefault();
                alert('Jumlah zakat tidak valid.');
                return false;
            }

            const bayarRadio = document.querySelector('.pay-radio:checked');
            if (!bayarRadio) {
                e.preventDefault();
                alert('Pilih metode pembayaran.');
                return false;
            }

            // [TAMBAHAN] sync hidden fidyah tunai sebelum submit
            if (activePanelZ === 'fidyah' && activeFidyahTipe === 'tunai') {
                syncHiddenFidyah();
            }

            spinBtn(document.getElementById('btnFinalSave'), 'Menyimpan...');
            return true;
        });

        // ══════════════════════════════════════════════════════════════
        // UTILITAS
        // ══════════════════════════════════════════════════════════════
        function spinBtn(btn, txt) {
            if (!btn) return;
            btn._orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> ${txt}`;
        }

        function salin(teks) {
            navigator.clipboard.writeText(teks).then(() => {
                const el = document.createElement('div');
                el.textContent = teks + ' disalin!';
                el.className = 'fixed bottom-5 right-5 bg-gray-900 text-white text-xs px-4 py-2.5 rounded-lg shadow-xl z-50';
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 2000);
            });
        }

        // ══════════════════════════════════════════════════════════════
        // INISIALISASI
        // ══════════════════════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial panel based on existing data
            @if($transaksi->jenis_zakat_id)
                setTimeout(() => {
                    document.getElementById('jenisId').value = '{{ $transaksi->jenis_zakat_id }}';
                    document.getElementById('jenisId').dispatchEvent(new Event('change'));
                    
                    setTimeout(() => {
                        @if($transaksi->tipe_zakat_id)
                            const tipeSelect = document.getElementById('tipeId');
                            for (let i = 0; i < tipeSelect.options.length; i++) {
                                if (tipeSelect.options[i].value === '{{ $transaksi->tipe_zakat_id }}') {
                                    tipeSelect.selectedIndex = i;
                                    tipeSelect.dispatchEvent(new Event('change'));
                                    break;
                                }
                            }
                        @endif
                    }, 150);
                }, 100);
            @endif

            // [TAMBAHAN] skip restore metode pembayaran jika fidyah non-tunai
            @if($transaksi->metode_pembayaran && !in_array($transaksi->metode_pembayaran, ['beras','makanan_matang','bahan_mentah']))
                setTimeout(() => {
                    const radio = document.querySelector(`.pay-radio[value="{{ $transaksi->metode_pembayaran }}"]`);
                    if (radio) radio.dispatchEvent(new Event('change'));
                }, 300);
            @endif
        });
    </script>
@endpush