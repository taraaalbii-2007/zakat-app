{{--
    resources/views/amil/transaksi-penerimaan/create.blade.php
    PERUBAHAN:
    1. Step 1, 2, 3 seperti sebelumnya
    2. Input list nama per jiwa dengan tombol tambah/hapus seperti contoh gambar
    3. QRIS dari KonfigurasiQris (tanpa input no referensi)
    4. Tampilan minimalis tanpa card berulang dan warna-warni
--}}

@extends('layouts.app')

@php
    $isMuzakki = auth()->user()->isMuzakki();
    $isAmil = !$isMuzakki;
    $defaultMode = old('metode_penerimaan', $mode);

    $headerTitle = match ($defaultMode) {
        'dijemput' => 'Permintaan Penjemputan Zakat',
        'daring' => 'Pembayaran Zakat Daring',
        default => 'Transaksi Datang Langsung',
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
                        <a href="{{ $isMuzakki ? route('muzakki.transaksi.index') : route('transaksi-datang-langsung.index') }}"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>Kembali
                        </a>
                        @if ($defaultMode === 'datang_langsung')
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Datang
                                Langsung</span>
                        @elseif($defaultMode === 'dijemput')
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Dijemput</span>
                        @else
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Daring</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- FORM --}}
            <form id="mainForm"
                action="{{ $isMuzakki
                    ? route('muzakki.transaksi.store')
                    : ($mode === 'daring'
                        ? route('transaksi-daring.store')
                        : ($mode === 'dijemput'
                            ? route('transaksi-dijemput.store')
                            : route('transaksi-datang-langsung.store'))) }}"
                method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                <input type="hidden" name="no_transaksi" value="{{ $noTransaksiPreview }}">
                <input type="hidden" name="tanggal_transaksi" value="{{ $tanggalHariIni }}">
                <input type="hidden" name="waktu_transaksi" value="{{ now()->format('H:i:s') }}">
                <input type="hidden" name="metode_penerimaan" id="hdnMode" value="{{ $defaultMode }}">
                <input type="hidden" name="is_pembayaran_beras" id="hdnBeras" value="0">

                {{-- PROGRESS DOTS --}}
                @php $showMultiStep = !($isAmil && $defaultMode === 'dijemput'); @endphp
                <div class="mb-8">
                    <div class="flex items-center max-w-lg mx-auto">
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot1"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">
                                1</div>
                            <span
                                class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">{{ $isMuzakki ? 'Mode & Data' : 'Data Muzakki' }}</span>
                        </div>
                        @if ($showMultiStep)
                            <div id="line12" class="flex-1 h-0.5 bg-gray-200 transition-colors"></div>
                            <div class="flex flex-col items-center flex-1">
                                <div id="dot2"
                                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">
                                    2</div>
                                <span class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Detail
                                    Zakat</span>
                            </div>
                            <div id="line23" class="flex-1 h-0.5 bg-gray-200 transition-colors"></div>
                            <div class="flex flex-col items-center flex-1">
                                <div id="dot3"
                                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">
                                    3</div>
                                <span
                                    class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Pembayaran</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ERROR SUMMARY --}}
                @if ($errors->any())
                    <div
                        class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-3">
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

                {{-- ═══════════ STEP 1 — DATA MUZAKKI ═══════════ --}}
                <div id="step1" class="step-panel">

                    @if ($isMuzakki)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-100">
                                <span
                                    class="inline-flex w-5 h-5 rounded-full bg-gray-200 text-gray-600 text-xs items-center justify-center mr-1.5 font-bold">1</span>
                                Pilih Cara Bayar Zakat
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label
                                    class="mode-opt flex items-center gap-3 p-4 rounded-lg border cursor-pointer transition-all {{ $defaultMode === 'daring' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="__mode" value="daring" class="hidden"
                                        {{ $defaultMode === 'daring' ? 'checked' : '' }}>
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">Bayar Daring</p>
                                        <p class="text-xs text-gray-500">Transfer bank / QRIS</p>
                                    </div>
                                </label>
                                <label
                                    class="mode-opt flex items-center gap-3 p-4 rounded-lg border cursor-pointer transition-all {{ $defaultMode === 'dijemput' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="__mode" value="dijemput" class="hidden"
                                        {{ $defaultMode === 'dijemput' ? 'checked' : '' }}>
                                    <div
                                        class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">Dijemput Amil</p>
                                        <p class="text-xs text-gray-500">Amil datang ke lokasi Anda</p>
                                    </div>
                                </label>
                            </div>
                            <div id="infoMuzakkiDijemput"
                                class="mt-3 {{ $defaultMode === 'dijemput' ? '' : 'hidden' }} bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-xs text-gray-600">
                                Isi data diri dan lokasi penjemputan. Amil akan datang ke tempat Anda. Detail zakat &
                                pembayaran dilengkapi saat penjemputan.
                            </div>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-100">
                            <span
                                class="inline-flex w-5 h-5 rounded-full bg-gray-200 text-gray-600 text-xs items-center justify-center mr-1.5 font-bold">{{ $isMuzakki ? '2' : '1' }}</span>
                            Data Muzakki (Pemberi Zakat)
                            @if ($muzakkiData)
                                <span
                                    class="ml-2 text-xs font-normal text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-full">Dari
                                    profil Anda</span>
                            @endif
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="muzakki_nama" id="muzakkiNama"
                                        value="{{ old('muzakki_nama', $muzakkiData['nama'] ?? '') }}"
                                        placeholder="Nama lengkap pemberi zakat"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 @error('muzakki_nama') border-red-500 @enderror">
                                    @error('muzakki_nama')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span
                                            class="text-xs text-gray-400">(opsional, 16 digit)</span></label>
                                    <input type="text" name="muzakki_nik"
                                        value="{{ old('muzakki_nik', $muzakkiData['nik'] ?? '') }}"
                                        placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon / WA</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">+62</span>
                                        <input type="tel" name="muzakki_telepon"
                                            value="{{ old('muzakki_telepon', $muzakkiData['telepon'] ?? '') }}"
                                            placeholder="81234567890"
                                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                            class="text-xs text-gray-400">(kwitansi)</span></label>
                                    <input type="email" name="muzakki_email"
                                        value="{{ old('muzakki_email', $muzakkiData['email'] ?? '') }}"
                                        placeholder="email@contoh.com"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div id="wrapAlamat" class="{{ $defaultMode === 'dijemput' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span
                                        class="text-red-500">*</span></label>
                                <textarea name="muzakki_alamat" rows="2" placeholder="Jl. ... RT/RW, Kelurahan, Kecamatan"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 resize-none">{{ old('muzakki_alamat', $muzakkiData['alamat'] ?? '') }}</textarea>
                                <div id="wrapKoordinat" class="mt-3 {{ $defaultMode === 'dijemput' ? '' : 'hidden' }}">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Latitude <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="latitude" id="latitude"
                                                value="{{ old('latitude') }}" placeholder="-6.2088"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Longitude <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="longitude" id="longitude"
                                                value="{{ old('longitude') }}" placeholder="106.8456"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                    </div>
                                    <button type="button" id="btnGps"
                                        class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Gunakan Lokasi Saat Ini
                                    </button>
                                </div>
                            </div>
                            <div id="wrapAmil" class="{{ $defaultMode === 'dijemput' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amil Penjemput <span
                                        class="text-red-500">*</span></label>
                                <select name="amil_id" id="amilId"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">-- Pilih Amil --</option>
                                    @foreach ($amilList as $amil)
                                        <option value="{{ $amil->id }}"
                                            {{ old('amil_id') == $amil->id ? 'selected' : '' }}>{{ $amil->nama_lengkap }}
                                            ({{ $amil->kode_amil }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="wrapCatatanDijemput" class="{{ $defaultMode === 'dijemput' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan untuk Amil <span
                                        class="text-xs text-gray-400">(opsional)</span></label>
                                <textarea name="keterangan" rows="2" placeholder="Patokan rumah, waktu terbaik, dll."
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 resize-none">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-5 pt-4 border-t border-gray-100">
                        <button type="button" id="btnStep1Next"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all">
                            <span id="lblStep1Next">
                                @if ($isAmil && $defaultMode === 'dijemput')
                                    Simpan Permintaan Penjemputan
                                @else
                                    Selanjutnya
                                @endif
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>{{-- /step1 --}}

                {{-- ═══════════ STEP 2 — DETAIL ZAKAT ═══════════ --}}
                <div id="step2" class="step-panel hidden">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                        <span
                            class="inline-flex w-5 h-5 rounded-full bg-gray-200 text-gray-600 text-xs items-center justify-center mr-1.5 font-bold">2</span>
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
                                            {{ old('jenis_zakat_id') == $jz->id ? 'selected' : '' }}>{{ $jz->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_zakat_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="wrapTipe" class="hidden">
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
                                        {{ old('program_zakat_id') == $prog->id ? 'selected' : '' }}>
                                        {{ $prog->nama_program }}</option>
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
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_kg'] }} kg
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa</p>
                                    </div>
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_liter'] }}
                                            ltr</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa</p>
                                    </div>
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-green-700">Rp
                                            {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}</p>
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
                                        value="{{ old('jumlah_jiwa', 1) }}" min="1" step="1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Beras (kg) <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_beras_kg" id="berasKg"
                                        value="{{ old('jumlah_beras_kg', $zakatFitrahInfo['beras_kg']) }}" min="0.1"
                                        step="0.1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            {{-- ★ DAFTAR NAMA JIWA - BERAS (seperti contoh gambar) ★ --}}
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
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_kg'] }} kg
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">setara beras</p>
                                    </div>
                                    <div class="bg-white rounded border border-gray-200 p-2 text-center">
                                        <p class="text-base font-bold text-gray-800">{{ $zakatFitrahInfo['beras_liter'] }}
                                            ltr</p>
                                        <p class="text-xs text-gray-500 mt-0.5">setara beras</p>
                                    </div>
                                    <div class="bg-white rounded border border-green-200 p-2 text-center">
                                        <p class="text-base font-bold text-green-700">Rp
                                            {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}</p>
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
                                        value="{{ old('jumlah_jiwa', 1) }}" min="1" step="1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal per Jiwa (Rp) <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nominal_per_jiwa" id="tunaiNominal"
                                            value="{{ old('nominal_per_jiwa', $zakatFitrahInfo['nominal_per_jiwa']) }}"
                                            min="1000" step="1000"
                                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- ★ DAFTAR NAMA JIWA - TUNAI (seperti contoh gambar) ★ --}}
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

                            <input type="hidden" name="jumlah" id="hdnJumlahTunai" value="0">
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
                                        value="{{ old('nilai_harta') }}" min="0" step="1000"
                                        class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 @error('nilai_harta') border-red-500 @enderror">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nisab Saat Ini (Rp) <span
                                            class="text-xs text-gray-400">(opsional)</span></label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nisab_saat_ini" value="{{ old('nisab_saat_ini') }}"
                                            min="0" step="1000"
                                            class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Persentase Zakat
                                        (%)</label>
                                    <input type="number" name="persentase_zakat" id="malPersen"
                                        value="{{ old('persentase_zakat', 2.5) }}" min="0" max="100"
                                        step="0.1"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex items-start gap-3">
                                <input type="checkbox" name="sudah_haul" id="sudahHaul" value="1"
                                    {{ old('sudah_haul') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded mt-0.5">
                                <div>
                                    <label for="sudahHaul" class="text-sm font-medium text-gray-900 cursor-pointer">Harta
                                        sudah mencapai haul (1 tahun hijriyah)</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Centang jika sudah dimiliki lebih dari atau
                                        sama dengan 1 tahun penuh</p>
                                </div>
                            </div>
                            <div id="wrapHaul" class="{{ old('sudah_haul') ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Haul</label>
                                <input type="date" name="tanggal_mulai_haul" value="{{ old('tanggal_mulai_haul') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-700 mb-1">Total Zakat Mal</p>
                                <p class="text-2xl font-bold text-blue-600" id="malTotalDisp">Rp 0</p>
                                <p class="text-xs text-gray-500 mt-0.5" id="malDetailDisp"></p>
                                <input type="hidden" name="jumlah" id="hdnJumlahMal" value="0">
                            </div>
                        </div>

                    </div>{{-- /space-y-5 --}}

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="goStep(1)"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>
                        <div class="flex gap-3">
                            <button type="button" id="btnS2Next" onclick="goStep(3)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <button type="submit" id="btnBerasSave"
                                class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Transaksi Beras
                            </button>
                        </div>
                    </div>
                </div>{{-- /step2 --}}

                {{-- ═══════════ STEP 3 — PEMBAYARAN ═══════════ --}}
                <div id="step3" class="step-panel hidden">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                        <span
                            class="inline-flex w-5 h-5 rounded-full bg-gray-200 text-gray-600 text-xs items-center justify-center mr-1.5 font-bold">3</span>
                        Metode Pembayaran
                    </h3>
                    <div class="space-y-5">

                        <div id="alertDaringNoTunai"
                            class="{{ $defaultMode === 'daring' ? '' : 'hidden' }} bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                            <p class="text-xs text-gray-600">Pembayaran daring hanya mendukung <strong>Transfer
                                    Bank</strong> atau <strong>QRIS</strong>.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Cara Pembayaran <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label id="cardTunai"
                                    class="pay-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all {{ old('metode_pembayaran') === 'tunai' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }} {{ $defaultMode === 'daring' ? 'hidden' : '' }}">
                                    <input type="radio" name="metode_pembayaran" value="tunai"
                                        class="hidden pay-radio"
                                        {{ old('metode_pembayaran') === 'tunai' ? 'checked' : '' }}>
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
                                    class="pay-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all {{ old('metode_pembayaran') === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="metode_pembayaran" value="transfer"
                                        class="hidden pay-radio"
                                        {{ old('metode_pembayaran') === 'transfer' ? 'checked' : '' }}>
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
                                    class="pay-card flex flex-col items-center gap-2 p-4 rounded-lg border cursor-pointer transition-all {{ old('metode_pembayaran') === 'qris' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" name="metode_pembayaran" value="qris"
                                        class="hidden pay-radio" {{ old('metode_pembayaran') === 'qris' ? 'checked' : '' }}>
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
                        <div id="wrapJmlDibayar" class="hidden">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-sm font-medium text-gray-700">Jumlah yang Diserahkan
                                            (Rp)</label>
                                        <button type="button" id="btnBayarPas"
                                            class="text-xs font-semibold text-blue-600 hover:underline">Isi Sesuai
                                            Zakat</button>
                                    </div>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="jumlah_dibayar" id="jmlDibayar"
                                            value="{{ old('jumlah_dibayar') }}" min="0" step="1000"
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
                        <div id="infoTunaiSec" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-800">Pembayaran Tunai</p>
                            <p class="text-xs text-gray-600 mt-1">Transaksi akan <strong>langsung terverifikasi</strong>
                                setelah disimpan.</p>
                        </div>

                        {{-- Info Transfer --}}
                        <div id="infoTransferSec" class="hidden space-y-3">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-800 mb-3">Rekening Transfer Masjid</p>
                                @if ($rekeningList->isNotEmpty())
                                    @foreach ($rekeningList as $rek)
                                        <div
                                            class="bg-white border border-gray-200 rounded p-3 flex items-center justify-between mb-2 last:mb-0">
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
                                <p class="text-xs text-gray-600">Transaksi akan <strong>langsung terverifikasi</strong>
                                    setelah disimpan.</p>
                            </div>
                        </div>

                        {{-- ★ Info QRIS — dari KonfigurasiQris (tanpa no referensi) ★ --}}
                        <div id="infoQrisSec" class="hidden space-y-3">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-800 mb-4">QRIS {{ $masjid->nama }}</p>
                                @php $qrisImageUrl = $qrisConfig?->qris_image_url ?? null; @endphp
                                @if ($qrisImageUrl)
                                    <div class="flex justify-center">
                                        <div
                                            class="bg-white p-4 rounded-lg border border-gray-200 inline-flex flex-col items-center gap-3">
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
                                            <p class="text-xs text-gray-400 mt-1">Tunjukkan QRIS fisik masjid kepada
                                                muzakki</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <p class="text-xs text-gray-600">Transaksi akan <strong>langsung terverifikasi</strong>
                                    setelah disimpan.</p>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan / Keterangan <span
                                    class="text-xs text-gray-400">(opsional)</span></label>
                            <textarea name="keterangan" rows="2" placeholder="Untuk program tertentu, atas nama keluarga, dll."
                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-blue-500 resize-none">{{ old('keterangan') }}</textarea>
                        </div>

                    </div>{{-- /space-y-5 --}}

                    {{-- TOMBOL NAVIGASI STEP 3 --}}
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="goStep(2)"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>
                        <button type="submit" id="btnFinalSave" disabled
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg opacity-50 cursor-not-allowed">
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
        const IS_MUZAKKI = {{ $isMuzakki ? 'true' : 'false' }};
        const IS_AMIL = {{ $isAmil ? 'true' : 'false' }};
        const TIPE_DATA = @json($tipeZakatList ?? []);

        let currentMode = '{{ $defaultMode }}';
        let activeStep = 1;
        let activePanelZ = null; // 'beras' | 'tunaiF' | 'mal' | null

        // ══════════════════════════════════════════════════════════════
        // FORMAT
        // ══════════════════════════════════════════════════════════════
        function fmt(n) {
            return new Intl.NumberFormat('id-ID').format(Math.round(n || 0));
        }

        // ══════════════════════════════════════════════════════════════
        // NAVIGASI STEP
        // ══════════════════════════════════════════════════════════════
        function goStep(n) {
            if (n > activeStep && !validateStep(activeStep)) return;
            document.querySelectorAll('.step-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('step' + n).classList.remove('hidden');
            activeStep = n;
            refreshDots(n);
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function refreshDots(active) {
            [1, 2, 3].forEach(i => {
                const d = document.getElementById('dot' + i);
                if (!d) return;
                d.classList.remove('bg-blue-600', 'text-white', 'bg-green-600', 'bg-gray-200', 'text-gray-500');
                if (i < active) {
                    d.classList.add('bg-green-600', 'text-white');
                    d.textContent = '✓';
                } else if (i === active) {
                    d.classList.add('bg-blue-600', 'text-white');
                    d.textContent = i;
                } else {
                    d.classList.add('bg-gray-200', 'text-gray-500');
                    d.textContent = i;
                }
                const ln = document.getElementById(i === 1 ? 'line12' : 'line23');
                if (ln) ln.classList.toggle('bg-blue-600', i < active);
            });
        }

        // ══════════════════════════════════════════════════════════════
        // VALIDASI
        // ══════════════════════════════════════════════════════════════
        function validateStep(step) {
            if (step === 1) {
                const nama = document.getElementById('muzakkiNama').value.trim();
                if (!nama) {
                    alert('Nama muzakki wajib diisi.');
                    document.getElementById('muzakkiNama').focus();
                    return false;
                }
                if (currentMode === 'dijemput') {
                    if (!document.getElementById('latitude')?.value.trim() || !document.getElementById('longitude')?.value
                        .trim()) {
                        alert('Koordinat lokasi wajib diisi.');
                        return false;
                    }
                    if (!document.getElementById('amilId')?.value) {
                        alert('Pilih amil penjemput.');
                        return false;
                    }
                }
                return true;
            }
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
            return 0;
        }

        // ══════════════════════════════════════════════════════════════
        // STEP 1 NEXT
        // ══════════════════════════════════════════════════════════════
        document.getElementById('btnStep1Next').addEventListener('click', function() {
            if (!validateStep(1)) return;
            if (currentMode === 'dijemput') {
                spinBtn(this, 'Menyimpan...');
                document.getElementById('mainForm').submit();
            } else goStep(2);
        });

        // ══════════════════════════════════════════════════════════════
        // [MUZAKKI] MODE SELECTOR
        // ══════════════════════════════════════════════════════════════
        @if ($isMuzakki)
            document.querySelectorAll('input[name="__mode"]').forEach(r => {
                r.addEventListener('change', function() {
                    currentMode = this.value;
                    document.getElementById('hdnMode').value = this.value;
                    const isDijemput = this.value === 'dijemput';
                    document.querySelectorAll('.mode-opt').forEach(c => {
                        const chk = c.querySelector('input').checked;
                        c.classList.toggle('border-blue-500', chk);
                        c.classList.toggle('bg-blue-50', chk);
                        c.classList.toggle('border-gray-200', !chk);
                    });
                    document.getElementById('infoMuzakkiDijemput').classList.toggle('hidden', !isDijemput);
                    ['wrapAlamat', 'wrapKoordinat', 'wrapAmil', 'wrapCatatanDijemput'].forEach(id =>
                        document.getElementById(id)?.classList.toggle('hidden', !isDijemput)
                    );
                    ['dot2', 'dot3', 'line12', 'line23'].forEach(id => document.getElementById(id)
                        ?.classList.toggle('hidden', isDijemput));
                    document.getElementById('lblStep1Next').textContent = isDijemput ?
                        'Kirim Permintaan Penjemputan' : 'Selanjutnya';
                    if (!isDijemput) updateTunaiVisibility(this.value);
                });
            });
        @endif

        // ══════════════════════════════════════════════════════════════
        // GPS
        // ══════════════════════════════════════════════════════════════
        document.getElementById('btnGps')?.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolocation.');
                return;
            }
            spinBtn(this, 'Mengambil...');
            navigator.geolocation.getCurrentPosition(
                pos => {
                    document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
                    document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
                    this.disabled = false;
                    this.innerHTML = 'Berhasil';
                    setTimeout(() => {
                        this.innerHTML = 'Gunakan Lokasi Saat Ini';
                    }, 2500);
                },
                err => {
                    this.disabled = false;
                    this.innerHTML = 'Gunakan Lokasi Saat Ini';
                    alert('Gagal: ' + err.message);
                }
            );
        });

        // ══════════════════════════════════════════════════════════════
        // ★ FUNGSI: RENDER DAFTAR NAMA JIWA (seperti contoh gambar) ★
        // ══════════════════════════════════════════════════════════════
        function renderNamaJiwa(listId, counterId, wrapId, jumlah) {
            const wrap = document.getElementById(wrapId);
            const listEl = document.getElementById(listId);

            // Sembunyikan jika hanya 1 jiwa
            if (jumlah <= 1) {
                wrap.classList.add('hidden');
                listEl.innerHTML = '';
                return;
            }

            wrap.classList.remove('hidden');

            // Simpan nilai yang sudah diisi pengguna
            const existing = [];
            listEl.querySelectorAll('input[type="text"]').forEach(inp => existing.push(inp.value));

            listEl.innerHTML = '';

            for (let i = 0; i < jumlah; i++) {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-3';
                row.innerHTML = `
            <div class="flex-shrink-0 w-6 text-xs font-medium text-gray-400">${i + 1}.</div>
            <input type="text"
                name="nama_jiwa_json[]"
                value="${existing[i] ? existing[i].replace(/"/g, '&quot;') : ''}"
                placeholder="Nama jiwa ke-${i + 1}"
                maxlength="100"
                autocomplete="off"
                class="flex-1 px-3 py-2 text-sm border border-gray-200 bg-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-200 transition-all">
        `;
                listEl.appendChild(row);
            }

            // Update counter
            updateNamaCounter(listId, counterId, jumlah);

            // Add input event listeners for counter
            listEl.querySelectorAll('input').forEach(inp => {
                inp.addEventListener('input', () => updateNamaCounter(listId, counterId, jumlah));
            });
        }

        function updateNamaCounter(listId, counterId, total) {
            const filled = [...document.getElementById(listId).querySelectorAll('input')].filter(i => i.value.trim() !== '')
                .length;
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
            const isBeras = namaTipe.includes('beras');
            if (isFitrah && isBeras) tampilPanelBeras();
            else if (isFitrah) tampilPanelFitrahTunai();
            else if (isMal) tampilPanelMal(this.options[this.selectedIndex]);
        });

        function resetPanelZakat() {
            ['panelBeras', 'panelFitrahTunai', 'panelMal'].forEach(id =>
                document.getElementById(id)?.classList.add('hidden')
            );
            document.getElementById('btnS2Next')?.classList.remove('hidden');
            document.getElementById('btnBerasSave')?.classList.add('hidden');
            document.getElementById('hdnBeras').value = '0';
            activePanelZ = null;
        }

        // ─── PANEL BERAS ───────────────────────────────────────
        function tampilPanelBeras() {
            activePanelZ = 'beras';
            document.getElementById('hdnBeras').value = '1';
            document.getElementById('panelBeras').classList.remove('hidden');
            document.getElementById('btnS2Next').classList.add('hidden');
            document.getElementById('btnBerasSave').classList.remove('hidden');

            // Render nama jiwa sesuai nilai saat ini
            const jiwa = parseInt(document.getElementById('berasJiwa').value) || 1;
            renderNamaJiwa('listNamaBeras', 'counterNamaBeras', 'wrapNamaBerasJiwa', jiwa);
        }

        ['berasJiwa', 'berasKg'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function() {
                if (id === 'berasJiwa') {
                    renderNamaJiwa('listNamaBeras', 'counterNamaBeras', 'wrapNamaBerasJiwa', parseInt(this
                        .value) || 1);
                }
            });
        });

        // ─── PANEL FITRAH TUNAI ──────────────────────────────────
        function tampilPanelFitrahTunai() {
            activePanelZ = 'tunaiF';
            document.getElementById('panelFitrahTunai').classList.remove('hidden');
            hitungFitrahTunai();
            const jiwa = parseInt(document.getElementById('tunaiJiwa').value) || 1;
            renderNamaJiwa('listNamaTunai', 'counterNamaTunai', 'wrapNamaTunaiJiwa', jiwa);
        }

        ['tunaiJiwa', 'tunaiNominal'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function() {
                hitungFitrahTunai();
                if (id === 'tunaiJiwa') {
                    renderNamaJiwa('listNamaTunai', 'counterNamaTunai', 'wrapNamaTunaiJiwa', parseInt(this
                        .value) || 1);
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
            if (tipeOpt.dataset.nisabEmas > 0) nisabHtml += `<p>Nisab emas: ${tipeOpt.dataset.nisabEmas} gram</p>`;
            if (tipeOpt.dataset.requireHaul === '1') nisabHtml +=
                `<p><strong>Membutuhkan haul</strong> (1 tahun hijriyah)</p>`;
            const box = document.getElementById('nisabBox');
            if (nisabHtml) {
                document.getElementById('nisabIsi').innerHTML = nisabHtml;
                box.classList.remove('hidden');
            } else box.classList.add('hidden');
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
        // STEP 3: METODE PEMBAYARAN
        // ══════════════════════════════════════════════════════════════
        function updateTunaiVisibility(mode) {
            const isDaring = mode === 'daring';
            document.getElementById('cardTunai')?.classList.toggle('hidden', isDaring);
            document.getElementById('alertDaringNoTunai')?.classList.toggle('hidden', !isDaring);
        }

        document.querySelectorAll('.pay-radio').forEach(r => {
            r.addEventListener('change', function() {
                const val = this.value;
                if (currentMode === 'daring' && val === 'tunai') {
                    this.checked = false;
                    alert('Pembayaran daring tidak mendukung tunai.');
                    return;
                }
                document.querySelectorAll('.pay-card').forEach(c => {
                    const chk = c.querySelector('input').checked;
                    c.classList.toggle('border-blue-500', chk);
                    c.classList.toggle('bg-blue-50', chk);
                    c.classList.toggle('border-gray-200', !chk);
                });

                ['infoTunaiSec', 'infoTransferSec', 'infoQrisSec'].forEach(id =>
                    document.getElementById(id)?.classList.add('hidden')
                );

                document.getElementById('wrapJmlDibayar').classList.remove('hidden');

                if (val === 'tunai') document.getElementById('infoTunaiSec')?.classList.remove('hidden');
                else if (val === 'transfer') document.getElementById('infoTransferSec')?.classList.remove(
                    'hidden');
                else if (val === 'qris') document.getElementById('infoQrisSec')?.classList.remove('hidden');

                const jd = document.getElementById('jmlDibayar');
                const jz = getJumlahZakat();
                if ((!jd.value || parseFloat(jd.value) === 0) && jz > 0) jd.value = jz;
                hitungKalkulasiInfaq();

                const btn = document.getElementById('btnFinalSave');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
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
            box.classList.remove('bg-amber-50', 'border-amber-200', 'bg-red-50', 'border-red-200', 'bg-green-50',
                'border-green-200');

            if (infaq > 0) {
                box.classList.add('bg-amber-50', 'border-amber-200');
                teks.innerHTML =
                    `Kelebihan <strong>Rp ${fmt(infaq)}</strong> otomatis dicatat sebagai <strong>infaq sukarela</strong>.`;
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
            if (!document.getElementById('muzakkiNama').value.trim()) {
                e.preventDefault();
                alert('Nama muzakki wajib diisi.');
                goStep(1);
                return;
            }

            if (currentMode === 'dijemput') return true;

            if (activePanelZ === 'beras') {
                if ((parseFloat(document.getElementById('berasKg').value) || 0) <= 0) {
                    e.preventDefault();
                    alert('Jumlah beras harus > 0.');
                    goStep(2);
                    return;
                }
                spinBtn(document.getElementById('btnBerasSave'), 'Menyimpan...');
                return true;
            }

            if (!document.getElementById('jenisId').value) {
                e.preventDefault();
                alert('Pilih jenis zakat.');
                goStep(2);
                return;
            }
            if (!document.getElementById('tipeId').value) {
                e.preventDefault();
                alert('Pilih tipe zakat.');
                goStep(2);
                return;
            }
            if (getJumlahZakat() <= 0) {
                e.preventDefault();
                alert('Jumlah zakat tidak valid.');
                goStep(2);
                return;
            }

            const bayarRadio = document.querySelector('.pay-radio:checked');
            if (!bayarRadio) {
                e.preventDefault();
                alert('Pilih metode pembayaran.');
                goStep(3);
                return;
            }

            if (currentMode === 'daring' && bayarRadio.value === 'tunai') {
                e.preventDefault();
                alert('Daring tidak mendukung tunai.');
                goStep(3);
                return;
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
            btn.innerHTML =
                `<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> ${txt}`;
        }

        function salin(teks) {
            navigator.clipboard.writeText(teks).then(() => {
                const el = document.createElement('div');
                el.textContent = teks + ' disalin!';
                el.className =
                    'fixed bottom-5 right-5 bg-gray-900 text-white text-xs px-4 py-2.5 rounded-lg shadow-xl z-50';
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 2000);
            });
        }

        // ══════════════════════════════════════════════════════════════
        // INISIALISASI
        // ══════════════════════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function() {
            @if ($isAmil && $defaultMode === 'dijemput')
                ['dot2', 'dot3', 'line12', 'line23'].forEach(id => document.getElementById(id)?.classList.add(
                    'hidden'));
            @endif
            updateTunaiVisibility(currentMode);

            @if (old('jenis_zakat_id'))
                setTimeout(() => {
                    document.getElementById('jenisId').value = '{{ old('jenis_zakat_id') }}';
                    document.getElementById('jenisId').dispatchEvent(new Event('change'));
                    setTimeout(() => {
                        @if (old('tipe_zakat_id'))
                            document.getElementById('tipeId').value = '{{ old('tipe_zakat_id') }}';
                            document.getElementById('tipeId').dispatchEvent(new Event('change'));
                        @endif
                        goStep({{ old('metode_penerimaan') === 'dijemput' ? 1 : 2 }});
                    }, 150);
                }, 100);
            @endif

            @if (old('metode_pembayaran'))
                setTimeout(() => {
                    const r = document.querySelector('.pay-radio[value="{{ old('metode_pembayaran') }}"]');
                    if (r) r.dispatchEvent(new Event('change'));
                }, 300);
            @endif
        });
    </script>
@endpush
