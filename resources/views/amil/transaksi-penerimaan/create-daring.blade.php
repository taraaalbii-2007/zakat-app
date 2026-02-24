{{--
    resources/views/amil/transaksi-penerimaan/create-daring.blade.php
    CONTROLLER    : createDaring() - mode fix = daring (hidden field)
    FLOW          : Step1(muzakki) → Step2(detail zakat) → Step3(Transfer/QRIS SAJA)
    TIDAK ADA     : pilihan metode_penerimaan, opsi TUNAI, koordinat GPS, pilih amil
--}}
@extends('layouts.app')
@section('title', 'Tambah Transaksi Zakat Daring')
@section('content')
<div class="space-y-4 sm:space-y-6">
<div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">

{{-- HEADER --}}
<div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-indigo-50 via-blue-50 to-violet-50 border-b border-indigo-100">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 border border-indigo-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900">Tambah Transaksi Zakat Daring</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">🌐 Daring</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">{{ $masjid->nama }} &middot; No: <span class="font-mono font-semibold text-gray-700">{{ $noTransaksiPreview }}</span></p>
            </div>
        </div>
        <a href="{{ route('transaksi-daring.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all self-start sm:self-auto">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>
</div>
<div class="px-4 sm:px-6 py-2.5 bg-indigo-50 border-b border-indigo-100">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs text-indigo-800"><strong>Mode Daring:</strong> Pembayaran hanya via <strong>Transfer Bank</strong> atau <strong>QRIS</strong>. Status awal <strong>Pending</strong> sampai amil konfirmasi.</p>
    </div>
</div>

{{-- FORM --}}
<form id="mainForm" action="{{ route('transaksi-penerimaan.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
    @csrf
    <input type="hidden" name="metode_penerimaan"   value="daring">
    <input type="hidden" name="tanggal_transaksi"   value="{{ $tanggalHariIni }}">
    <input type="hidden" name="waktu_transaksi"     value="{{ now()->format('H:i:s') }}">
    <input type="hidden" name="no_transaksi"        value="{{ $noTransaksiPreview }}">
    <input type="hidden" name="is_pembayaran_beras" id="hdnBeras" value="0">

    {{-- PROGRESS DOTS --}}
    <div class="mb-7">
        <div class="flex items-center max-w-lg mx-auto">
            <div class="flex flex-col items-center flex-1">
                <div id="dot1" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold ring-4 ring-indigo-500/20 bg-indigo-600 text-white">1</div>
                <span class="text-xs mt-1 font-medium text-indigo-600 text-center leading-tight">Data Muzakki</span>
            </div>
            <div id="line12" class="flex-1 h-0.5 bg-gray-200 transition-colors duration-300"></div>
            <div class="flex flex-col items-center flex-1">
                <div id="dot2" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">2</div>
                <span class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Detail Zakat</span>
            </div>
            <div id="line23" class="flex-1 h-0.5 bg-gray-200 transition-colors duration-300"></div>
            <div class="flex flex-col items-center flex-1">
                <div id="dot3" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">3</div>
                <span class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Pembayaran</span>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <div><p class="text-sm font-semibold text-red-800">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside text-sm text-red-700 mt-1 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    {{-- ==================== STEP 1: DATA MUZAKKI ==================== --}}
    <div id="step1" class="step-panel">
        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="inline-flex w-6 h-6 rounded-full bg-indigo-600 text-white text-xs items-center justify-center font-bold flex-shrink-0">1</span>
            Data Muzakki (Pemberi Zakat)
        </h3>
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="muzakki_nama" id="muzakkiNama" value="{{ old('muzakki_nama') }}" placeholder="Nama lengkap pemberi zakat"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all @error('muzakki_nama') border-red-500 bg-red-50 @enderror">
                    @error('muzakki_nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="muzakki_nik" value="{{ old('muzakki_nik') }}" placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 transition-all">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Telepon / WhatsApp</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">+62</span>
                        <input type="tel" name="muzakki_telepon" value="{{ old('muzakki_telepon') }}" placeholder="81234567890"
                            class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-xs text-gray-400 font-normal">(kwitansi)</span></label>
                    <input type="email" name="muzakki_email" value="{{ old('muzakki_email') }}" placeholder="email@contoh.com"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="muzakki_alamat" rows="2" placeholder="Jl. ... RT/RW, Kelurahan, Kecamatan"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all resize-none">{{ old('muzakki_alamat') }}</textarea>
            </div>
        </div>
        <div class="flex justify-end mt-5 pt-4 border-t border-gray-100">
            <button type="button" id="btnStep1Next" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all">
                Selanjutnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    {{-- ==================== STEP 2: DETAIL ZAKAT ==================== --}}
    <div id="step2" class="step-panel hidden">
        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="inline-flex w-6 h-6 rounded-full bg-indigo-600 text-white text-xs items-center justify-center font-bold flex-shrink-0">2</span>
            Detail Zakat
        </h3>
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Zakat <span class="text-red-500">*</span></label>
                    <select name="jenis_zakat_id" id="jenisId" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all @error('jenis_zakat_id') border-red-500 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenisZakatList as $jz)
                            <option value="{{ $jz->id }}" data-nama="{{ strtolower($jz->nama) }}" {{ old('jenis_zakat_id') == $jz->id ? 'selected' : '' }}>{{ $jz->nama }}</option>
                        @endforeach
                    </select>
                    @error('jenis_zakat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div id="wrapTipe" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Spesifik <span class="text-red-500">*</span></label>
                    <select name="tipe_zakat_id" id="tipeId" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all @error('tipe_zakat_id') border-red-500 @enderror">
                        <option value="">-- Pilih Tipe --</option>
                    </select>
                    @error('tipe_zakat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Zakat <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                <select name="program_zakat_id" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    <option value="">-- Tidak memilih program tertentu --</option>
                    @foreach($programZakatList as $prog)
                        <option value="{{ $prog->id }}" {{ old('program_zakat_id') == $prog->id ? 'selected' : '' }}>{{ $prog->nama_program }}</option>
                    @endforeach
                </select>
            </div>
            <hr class="border-gray-100">

            {{-- PANEL FITRAH BERAS --}}
            <div id="panelBeras" class="hidden space-y-4">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-amber-900 mb-3">🌾 Ketentuan Zakat Fitrah Beras</p>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center"><p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_kg'] }} kg</p><p class="text-xs text-amber-700 mt-0.5">per jiwa</p></div>
                        <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center"><p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p><p class="text-xs text-amber-700 mt-0.5">per jiwa</p></div>
                        <div class="bg-white rounded-lg border border-green-200 p-2.5 text-center"><p class="text-base font-bold text-green-700">≈ Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}</p><p class="text-xs text-gray-500 mt-0.5">per jiwa (BAZNAS)</p></div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Jiwa <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_jiwa" id="berasJiwa" value="{{ old('jumlah_jiwa', 1) }}" min="1" step="1"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Beras (kg) <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_beras_kg" id="berasKg" value="{{ old('jumlah_beras_kg', $zakatFitrahInfo['beras_kg']) }}" min="0.1" step="0.1"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Beras/Kg <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                        <input type="number" name="harga_beras_per_kg" id="berasHarga" value="{{ old('harga_beras_per_kg', 0) }}" min="0" step="500"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-2">📊 Ringkasan</p>
                    <p id="berasRingkasText" class="text-xs text-gray-500">Isi data di atas untuk melihat ringkasan.</p>
                </div>
                <input type="hidden" name="jumlah" value="0">
                <input type="hidden" name="nominal_per_jiwa" value="0">
            </div>

            {{-- PANEL FITRAH TUNAI --}}
            <div id="panelFitrahTunai" class="hidden space-y-4">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-amber-900 mb-3">💰 Ketentuan Zakat Fitrah (Uang)</p>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center"><p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_kg'] }} kg</p><p class="text-xs text-amber-700 mt-0.5">setara beras</p></div>
                        <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center"><p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p><p class="text-xs text-amber-700 mt-0.5">setara beras</p></div>
                        <div class="bg-white rounded-lg border border-green-200 p-2.5 text-center"><p class="text-base font-bold text-green-700">Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}</p><p class="text-xs text-gray-500 mt-0.5">per jiwa (BAZNAS)</p></div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Jiwa <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_jiwa" id="tunaiJiwa" value="{{ old('jumlah_jiwa', 1) }}" min="1" step="1"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal per Jiwa (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                            <input type="number" name="nominal_per_jiwa" id="tunaiNominal" value="{{ old('nominal_per_jiwa', $zakatFitrahInfo['nominal_per_jiwa']) }}" min="1000" step="1000"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">BAZNAS: Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}/jiwa</p>
                    </div>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-indigo-800 uppercase tracking-wide mb-2">📊 Ringkasan</p>
                    <div id="tunaiRingkasText" class="text-xs text-gray-500">Isi data di atas untuk melihat ringkasan.</div>
                </div>
                <input type="hidden" name="jumlah" id="hdnJumlahTunai" value="0">
            </div>

            {{-- PANEL MAL --}}
            <div id="panelMal" class="hidden space-y-4">
                <div id="nisabBox" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-3">
                    <p class="text-xs font-semibold text-blue-800 mb-1">Informasi Nisab</p>
                    <div id="nisabIsi" class="text-xs text-blue-700 space-y-0.5"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Nilai Harta (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                        <input type="number" name="nilai_harta" id="malHarta" value="{{ old('nilai_harta') }}" min="0" step="1000" placeholder="Total harta yang wajib dizakatkan"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all @error('nilai_harta') border-red-500 @enderror">
                    </div>
                    @error('nilai_harta')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nisab Saat Ini (Rp) <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                        <div class="relative"><span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                            <input type="number" name="nisab_saat_ini" value="{{ old('nisab_saat_ini') }}" min="0" step="1000"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Persentase Zakat (%)</label>
                        <input type="number" name="persentase_zakat" id="malPersen" value="{{ old('persentase_zakat', 2.5) }}" min="0" max="100" step="0.1"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 flex items-start gap-3">
                    <input type="checkbox" name="sudah_haul" id="sudahHaul" value="1" {{ old('sudah_haul') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded mt-0.5">
                    <div>
                        <label for="sudahHaul" class="text-sm font-medium text-gray-900 cursor-pointer">Harta sudah mencapai haul (1 tahun hijriyah)</label>
                        <p class="text-xs text-gray-500 mt-0.5">Centang jika sudah dimiliki minimal 1 tahun penuh</p>
                    </div>
                </div>
                <div id="wrapHaul" class="{{ old('sudah_haul') ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Mulai Haul</label>
                    <input type="date" name="tanggal_mulai_haul" value="{{ old('tanggal_mulai_haul') }}"
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-indigo-800 uppercase tracking-wide mb-1">Total Zakat Mal</p>
                    <p class="text-2xl font-bold text-indigo-700" id="malTotalDisp">Rp 0</p>
                    <p class="text-xs text-gray-500 mt-0.5" id="malDetailDisp"></p>
                    <input type="hidden" name="jumlah" id="hdnJumlahMal" value="0">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
            <button type="button" onclick="goStep(1)" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </button>
            <div class="flex gap-3">
                <button type="button" id="btnS2Next" onclick="lanjutKeStep3()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all">
                    Selanjutnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <button type="submit" id="btnBerasSave" class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-green-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Transaksi Beras
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== STEP 3: PEMBAYARAN ==================== --}}
    <div id="step3" class="step-panel hidden">
        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="inline-flex w-6 h-6 rounded-full bg-indigo-600 text-white text-xs items-center justify-center font-bold flex-shrink-0">3</span>
            Metode Pembayaran
        </h3>
        <div class="space-y-5">

            {{-- Notif: tidak ada tunai --}}
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs text-indigo-800">Transaksi daring hanya mendukung <strong>Transfer Bank</strong> atau <strong>QRIS</strong>. Status <strong>pending</strong> sampai amil konfirmasi dana masuk.</p>
            </div>

            {{-- Pilihan metode: Transfer & QRIS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Cara Pembayaran <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md">
                    <label id="cardTransfer" class="pay-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all {{ old('metode_pembayaran') === 'transfer' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="metode_pembayaran" value="transfer" class="hidden pay-radio" {{ old('metode_pembayaran') === 'transfer' ? 'checked' : '' }}>
                        <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4m-9 4v10"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900">Transfer Bank</p>
                        <p class="text-xs text-gray-500 text-center">Ke rekening masjid</p>
                    </label>
                    <label id="cardQris" class="pay-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all {{ old('metode_pembayaran') === 'qris' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="metode_pembayaran" value="qris" class="hidden pay-radio" {{ old('metode_pembayaran') === 'qris' ? 'checked' : '' }}>
                        <div class="w-11 h-11 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900">QRIS</p>
                        <p class="text-xs text-gray-500 text-center">Scan QR masjid</p>
                    </label>
                </div>
                @error('metode_pembayaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Jumlah dibayar + infaq --}}
            <div id="wrapJmlDibayar" class="hidden">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-medium text-gray-700">Jumlah yang Diserahkan (Rp)</label>
                            <button type="button" id="btnBayarPas" class="text-xs font-bold text-indigo-600 hover:underline">Isi Sesuai Zakat</button>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                            <input type="number" name="jumlah_dibayar" id="jmlDibayar" value="{{ old('jumlah_dibayar') }}" min="0" step="1000" placeholder="Kosongkan = bayar pas sesuai zakat"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Bayar <strong>lebih</strong> dari kewajiban → kelebihan otomatis dicatat sebagai <strong>infaq sukarela</strong>.</p>
                    </div>
                    <div id="boxKalkulasiInfaq" class="hidden rounded-xl border p-3 flex items-start gap-2">
                        <span id="ikonInfaq" class="mt-0.5 text-base leading-none">💰</span>
                        <div id="teksInfaq" class="text-sm"></div>
                    </div>
                </div>
            </div>

            {{-- Info Transfer --}}
            <div id="infoTransferSec" class="hidden space-y-3">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-blue-800 mb-3">🏦 Rekening Transfer Masjid</p>
                    @if($rekeningList->isNotEmpty())
                        @foreach($rekeningList as $rek)
                        <div class="bg-white border border-blue-200 rounded-lg p-3 flex items-center justify-between mb-2 last:mb-0">
                            <div>
                                <p class="text-xs font-bold text-gray-800">{{ $rek->nama_bank }}</p>
                                <p class="text-sm font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $rek->nomor_rekening }}</p>
                                <p class="text-xs text-gray-500">a.n. {{ $rek->nama_pemilik }}</p>
                            </div>
                            <button type="button" onclick="salin('{{ $rek->nomor_rekening }}')" class="text-xs text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-all font-semibold">Salin</button>
                        </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-500 italic">Belum ada rekening aktif terdaftar.</p>
                    @endif
                    <div class="mt-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">No. Referensi Transfer <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="no_referensi_transfer" id="noRefTransfer" value="{{ old('no_referensi_transfer') }}" placeholder="Nomor dari struk/slip transfer"
                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bukti Transfer <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <div id="prvTransfer" class="h-32 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer hover:border-indigo-400 transition-all" onclick="document.getElementById('inpTransfer').click()">
                        <div class="text-center"><svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-xs text-gray-400">Klik untuk upload</p></div>
                    </div>
                    <input type="file" name="bukti_transfer" id="inpTransfer" accept="image/*" class="hidden" onchange="prvBukti(this,'prvTransfer')">
                    <p class="text-xs text-gray-400">Format: JPG, PNG. Maks 2MB.</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-start gap-2">
                    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs text-yellow-800">Status <strong>pending</strong> — konfirmasi dari amil setelah dana masuk.</p>
                </div>
            </div>

            {{-- Info QRIS --}}
            <div id="infoQrisSec" class="hidden space-y-3">
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <p class="text-sm font-bold text-purple-800 mb-3">📱 QRIS Masjid</p>
                    @php $rekeningQris = $rekeningList->where('jenis','qris')->first() ?? $rekeningList->first(); @endphp
                    @if($rekeningQris && !empty($rekeningQris->qris_image))
                    <div class="flex justify-center mb-3"><div class="bg-white p-3 rounded-xl border border-purple-200 shadow-sm"><img src="{{ Storage::url($rekeningQris->qris_image) }}" class="w-40 h-40 object-contain" alt="QRIS"><p class="text-xs text-center text-gray-500 mt-1.5">QRIS {{ $masjid->nama }}</p></div></div>
                    @else
                    <div class="bg-white border border-purple-200 rounded-lg p-4 text-center mb-3"><p class="text-xs text-gray-500">Tunjukkan QRIS masjid kepada muzakki untuk di-scan.</p></div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">No. Referensi QRIS <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" id="noRefQris" placeholder="Nomor dari notifikasi QRIS"
                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Screenshot Bukti QRIS <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                    <div id="prvQris" class="h-32 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer hover:border-indigo-400 transition-all" onclick="document.getElementById('inpQris').click()">
                        <div class="text-center"><svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-xs text-gray-400">Klik untuk upload</p></div>
                    </div>
                    <input type="file" name="bukti_transfer" id="inpQris" accept="image/*" class="hidden" onchange="prvBukti(this,'prvQris')">
                    <p class="text-xs text-gray-400">Format: JPG, PNG. Maks 2MB.</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-start gap-2">
                    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs text-yellow-800">Status <strong>pending</strong> — konfirmasi dari amil setelah dana masuk.</p>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan / Keterangan <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="keterangan" rows="2" placeholder="Untuk program tertentu, atas nama keluarga, dll."
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-indigo-500 transition-all resize-none">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Ringkasan final --}}
            <div id="boxRingSummary" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-3">✅ Ringkasan Transaksi</p>
                <table class="w-full text-sm">
                    <tr class="border-b border-gray-100"><td class="text-gray-500 py-1.5 w-1/2">Zakat Wajib</td><td class="font-bold text-gray-900" id="ringJumlah">-</td></tr>
                    <tr id="ringRowDibayar" class="hidden border-b border-gray-100"><td class="text-gray-500 py-1.5">Jumlah Diserahkan</td><td class="font-bold text-gray-900" id="ringDibayar">-</td></tr>
                    <tr id="ringRowInfaq" class="hidden border-b border-gray-100"><td class="text-amber-600 py-1.5">+ Infaq Sukarela</td><td class="font-bold text-amber-600" id="ringInfaq">-</td></tr>
                    <tr class="border-b border-gray-100"><td class="text-gray-500 py-1.5">Metode</td><td class="font-bold text-gray-900" id="ringMetode">-</td></tr>
                    <tr><td class="text-gray-500 py-1.5">Status</td><td class="font-bold text-amber-600">⏳ Pending — menunggu konfirmasi</td></tr>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
            <button type="button" onclick="goStep(2)" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </button>
            <button type="submit" id="btnFinalSave" disabled class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all opacity-50 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Transaksi
            </button>
        </div>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
const BAZNAS = { nominalPerJiwa:{{ $zakatFitrahInfo['nominal_per_jiwa'] }}, berasKg:{{ $zakatFitrahInfo['beras_kg'] }}, berasLiter:{{ $zakatFitrahInfo['beras_liter'] }} };
const TIPE_DATA = @json($tipeZakatList ?? []);
let activeStep = 1, activePanelZ = null;

function fmt(n){ return new Intl.NumberFormat('id-ID').format(Math.round(n||0)); }

function goStep(n){
    if(n > activeStep && !validateStep(activeStep)) return;
    document.querySelectorAll('.step-panel').forEach(p=>p.classList.add('hidden'));
    document.getElementById('step'+n).classList.remove('hidden');
    activeStep = n; refreshDots(n);
    window.scrollTo({top:0,behavior:'smooth'});
}
function lanjutKeStep3(){
    if(!validateStep(2)) return;
    goStep(3);
    const jz=getJumlahZakat(), jd=document.getElementById('jmlDibayar');
    if(jz>0 && (!jd.value||parseFloat(jd.value)===0)) jd.value=jz;
}
function refreshDots(active){
    [1,2,3].forEach(i=>{
        const d=document.getElementById('dot'+i);
        if(!d) return;
        d.className='w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold';
        if(i<active){d.classList.add('bg-green-500','text-white');d.textContent='✓';}
        else if(i===active){d.classList.add('bg-indigo-600','text-white','ring-4','ring-indigo-500/20');d.textContent=i;}
        else{d.classList.add('bg-gray-200','text-gray-500');d.textContent=i;}
        const ln=document.getElementById(i===1?'line12':'line23');
        if(ln){ln.classList.toggle('bg-indigo-500',i<active);ln.classList.toggle('bg-gray-200',i>=active);}
    });
}
function validateStep(step){
    if(step===1){
        const n=document.getElementById('muzakkiNama').value.trim();
        if(!n){alert('Nama muzakki wajib diisi.');document.getElementById('muzakkiNama').focus();return false;}
        return true;
    }
    if(step===2){
        if(!document.getElementById('jenisId').value){alert('Pilih jenis zakat.');return false;}
        if(!document.getElementById('tipeId').value){alert('Pilih tipe zakat.');return false;}
        if(activePanelZ==='beras'){if((parseFloat(document.getElementById('berasKg').value)||0)<=0){alert('Jumlah beras harus > 0.');return false;}}
        else{if(getJumlahZakat()<=0){alert('Jumlah zakat tidak valid.');return false;}}
        return true;
    }
    return true;
}
function getJumlahZakat(){
    if(activePanelZ==='beras') return 0;
    if(activePanelZ==='tunaiF') return parseFloat(document.getElementById('hdnJumlahTunai').value)||0;
    if(activePanelZ==='mal') return parseFloat(document.getElementById('hdnJumlahMal').value)||0;
    return 0;
}

document.getElementById('btnStep1Next').addEventListener('click',()=>{if(validateStep(1))goStep(2);});

document.getElementById('jenisId').addEventListener('change',function(){
    const jenisId=this.value, tipeEl=document.getElementById('tipeId'), wrapTipe=document.getElementById('wrapTipe');
    tipeEl.innerHTML='<option value="">-- Pilih Tipe --</option>';
    resetPanelZakat();
    if(!jenisId){wrapTipe.classList.add('hidden');return;}
    const list=TIPE_DATA[jenisId]||[];
    if(list.length>0){
        list.forEach(t=>{const o=new Option(t.nama,t.uuid);o.dataset.nama=t.nama.toLowerCase();o.dataset.persentase=t.persentase_zakat||2.5;o.dataset.nisabEmas=t.nisab_emas_gram||0;o.dataset.requireHaul=t.requires_haul?'1':'0';tipeEl.appendChild(o);});
        wrapTipe.classList.remove('hidden');
    } else wrapTipe.classList.add('hidden');
});

document.getElementById('tipeId').addEventListener('change',function(){
    const jenisEl=document.getElementById('jenisId');
    const namaJenis=(jenisEl.options[jenisEl.selectedIndex]?.dataset.nama||'').toLowerCase();
    const namaTipe=(this.options[this.selectedIndex]?.dataset.nama||'').toLowerCase();
    resetPanelZakat();
    if(!this.value) return;
    if(namaJenis.includes('fitrah')&&namaTipe.includes('beras')) tampilPanelBeras();
    else if(namaJenis.includes('fitrah')) tampilPanelFitrahTunai();
    else if(namaJenis.includes('mal')) tampilPanelMal(this.options[this.selectedIndex]);
});

function resetPanelZakat(){
    ['panelBeras','panelFitrahTunai','panelMal'].forEach(id=>document.getElementById(id).classList.add('hidden'));
    document.getElementById('btnS2Next').classList.remove('hidden');
    document.getElementById('btnBerasSave').classList.add('hidden');
    document.getElementById('hdnBeras').value='0';
    activePanelZ=null;
}
function tampilPanelBeras(){
    activePanelZ='beras'; document.getElementById('hdnBeras').value='1';
    document.getElementById('panelBeras').classList.remove('hidden');
    document.getElementById('btnS2Next').classList.add('hidden');
    document.getElementById('btnBerasSave').classList.remove('hidden');
    hitungBeras();
}
['berasJiwa','berasKg','berasHarga'].forEach(id=>document.getElementById(id)?.addEventListener('input',hitungBeras));
function hitungBeras(){
    const jiwa=parseFloat(document.getElementById('berasJiwa').value)||0;
    const kg=parseFloat(document.getElementById('berasKg').value)||0;
    const harga=parseFloat(document.getElementById('berasHarga').value)||0;
    const minKg=jiwa*BAZNAS.berasKg;
    let html='';
    if(jiwa>0&&kg>0){
        html+=`<p>👤 ${jiwa} jiwa × ${BAZNAS.berasKg} kg = minimum <strong>${minKg.toFixed(1)} kg</strong></p>`;
        html+=`<p>🌾 Diserahkan: <strong>${kg} kg</strong></p>`;
        if(harga>0) html+=`<p>💵 @Rp ${fmt(harga)}/kg: <strong>Rp ${fmt(kg*harga)}</strong></p>`;
        if(kg<minKg) html+=`<p class="text-red-700 font-bold">⚠️ Kurang dari minimum (${minKg.toFixed(1)} kg)</p>`;
    } else html='Isi jumlah jiwa dan beras.';
    document.getElementById('berasRingkasText').innerHTML=html;
}
function tampilPanelFitrahTunai(){
    activePanelZ='tunaiF';
    document.getElementById('panelFitrahTunai').classList.remove('hidden');
    hitungFitrahTunai();
}
['tunaiJiwa','tunaiNominal'].forEach(id=>document.getElementById(id)?.addEventListener('input',hitungFitrahTunai));
function hitungFitrahTunai(){
    const jiwa=parseFloat(document.getElementById('tunaiJiwa').value)||0;
    const nominal=parseFloat(document.getElementById('tunaiNominal').value)||0;
    const total=jiwa*nominal;
    let html='';
    if(jiwa>0&&nominal>0){
        html+=`<p class="font-bold text-gray-800">💰 ${jiwa} × Rp ${fmt(nominal)} = <span class="text-indigo-700 text-base">Rp ${fmt(total)}</span></p>`;
        if(nominal<BAZNAS.nominalPerJiwa) html+=`<p class="text-xs text-amber-700 mt-1">⚠️ Di bawah BAZNAS (Rp ${fmt(BAZNAS.nominalPerJiwa)}/jiwa)</p>`;
    } else html='<p class="text-gray-400">Isi jumlah jiwa dan nominal.</p>';
    document.getElementById('tunaiRingkasText').innerHTML=html;
    document.getElementById('hdnJumlahTunai').value=Math.round(total);
}
function tampilPanelMal(tipeOpt){
    activePanelZ='mal';
    document.getElementById('panelMal').classList.remove('hidden');
    document.getElementById('malPersen').value=tipeOpt.dataset.persentase||2.5;
    let nisabHtml='';
    if(tipeOpt.dataset.nisabEmas>0) nisabHtml+=`<p>• Nisab emas: ${tipeOpt.dataset.nisabEmas} gram</p>`;
    if(tipeOpt.dataset.requireHaul==='1') nisabHtml+=`<p>• Membutuhkan haul (1 tahun hijriyah)</p>`;
    const box=document.getElementById('nisabBox');
    if(nisabHtml){document.getElementById('nisabIsi').innerHTML=nisabHtml;box.classList.remove('hidden');}
    else box.classList.add('hidden');
    hitungMal();
}
['malHarta','malPersen'].forEach(id=>document.getElementById(id)?.addEventListener('input',hitungMal));
function hitungMal(){
    const h=parseFloat(document.getElementById('malHarta').value)||0;
    const p=parseFloat(document.getElementById('malPersen').value)||2.5;
    const t=h*(p/100);
    document.getElementById('malTotalDisp').textContent='Rp '+fmt(t);
    document.getElementById('malDetailDisp').textContent=`${p}% × Rp ${fmt(h)} = Rp ${fmt(t)}`;
    document.getElementById('hdnJumlahMal').value=Math.round(t);
}
document.getElementById('sudahHaul').addEventListener('change',function(){document.getElementById('wrapHaul').classList.toggle('hidden',!this.checked);});

document.querySelectorAll('.pay-radio').forEach(r=>{
    r.addEventListener('change',function(){
        const val=this.value;
        document.querySelectorAll('.pay-card').forEach(c=>{
            const chk=c.querySelector('input').checked;
            c.classList.toggle('border-indigo-500',chk);c.classList.toggle('bg-indigo-50',chk);c.classList.toggle('border-gray-200',!chk);
        });
        ['infoTransferSec','infoQrisSec'].forEach(id=>document.getElementById(id).classList.add('hidden'));
        document.getElementById('wrapJmlDibayar').classList.remove('hidden');
        if(val==='transfer') document.getElementById('infoTransferSec').classList.remove('hidden');
        if(val==='qris') document.getElementById('infoQrisSec').classList.remove('hidden');
        const jd=document.getElementById('jmlDibayar'), jz=getJumlahZakat();
        if((!jd.value||parseFloat(jd.value)===0)&&jz>0) jd.value=jz;
        hitungKalkulasiInfaq(); refreshRingSummary(val);
        const btn=document.getElementById('btnFinalSave');
        btn.disabled=false; btn.classList.remove('opacity-50','cursor-not-allowed');
    });
});

document.getElementById('btnBayarPas').addEventListener('click',()=>{
    document.getElementById('jmlDibayar').value=getJumlahZakat();
    hitungKalkulasiInfaq();
    const m=document.querySelector('.pay-radio:checked')?.value;
    if(m) refreshRingSummary(m);
});
document.getElementById('jmlDibayar').addEventListener('input',function(){
    hitungKalkulasiInfaq();
    const m=document.querySelector('.pay-radio:checked')?.value;
    if(m) refreshRingSummary(m);
});
function hitungKalkulasiInfaq(){
    const jz=getJumlahZakat(), jd=parseFloat(document.getElementById('jmlDibayar').value)||0;
    const box=document.getElementById('boxKalkulasiInfaq');
    if(jz<=0||jd<=0){box.classList.add('hidden');return;}
    const infaq=Math.max(0,jd-jz), kurang=Math.max(0,jz-jd);
    box.classList.remove('hidden','bg-amber-50','border-amber-200','bg-red-50','border-red-200','bg-green-50','border-green-200');
    if(infaq>0){box.classList.add('bg-amber-50','border-amber-200');document.getElementById('ikonInfaq').textContent='🎉';document.getElementById('teksInfaq').innerHTML=`Kelebihan <strong>Rp ${fmt(infaq)}</strong> otomatis dicatat sebagai <strong>infaq sukarela</strong>. Jazakallah khairan! 🙏`;}
    else if(kurang>0){box.classList.add('bg-red-50','border-red-200');document.getElementById('ikonInfaq').textContent='⚠️';document.getElementById('teksInfaq').innerHTML=`Kurang <strong>Rp ${fmt(kurang)}</strong> dari zakat wajib (Rp ${fmt(jz)}).`;}
    else{box.classList.add('bg-green-50','border-green-200');document.getElementById('ikonInfaq').textContent='✅';document.getElementById('teksInfaq').innerHTML=`Pembayaran <strong>pas</strong> sesuai zakat wajib (Rp ${fmt(jz)}).`;}
}
function refreshRingSummary(metode){
    const jz=getJumlahZakat(), jd=parseFloat(document.getElementById('jmlDibayar').value)||jz, infaq=Math.max(0,jd-jz);
    document.getElementById('boxRingSummary').classList.remove('hidden');
    document.getElementById('ringJumlah').textContent='Rp '+fmt(jz);
    if(jd!==jz&&jd>0){document.getElementById('ringRowDibayar').classList.remove('hidden');document.getElementById('ringDibayar').textContent='Rp '+fmt(jd);}
    else document.getElementById('ringRowDibayar').classList.add('hidden');
    if(infaq>0){document.getElementById('ringRowInfaq').classList.remove('hidden');document.getElementById('ringInfaq').textContent='Rp '+fmt(infaq);}
    else document.getElementById('ringRowInfaq').classList.add('hidden');
    document.getElementById('ringMetode').textContent={transfer:'🏦 Transfer Bank',qris:'📱 QRIS'}[metode]||metode;
}

document.getElementById('mainForm').addEventListener('submit',function(e){
    if(!document.getElementById('muzakkiNama').value.trim()){e.preventDefault();alert('Nama muzakki wajib diisi.');goStep(1);return;}
    if(activePanelZ==='beras'){
        if((parseFloat(document.getElementById('berasKg').value)||0)<=0){e.preventDefault();alert('Jumlah beras harus > 0.');goStep(2);return;}
        if(!this.querySelector('input[name="metode_pembayaran"]')){const h=document.createElement('input');h.type='hidden';h.name='metode_pembayaran';h.value='tunai';this.appendChild(h);}
        spinBtn(document.getElementById('btnBerasSave'),'Menyimpan...');return;
    }
    if(!document.getElementById('jenisId').value){e.preventDefault();alert('Pilih jenis zakat.');goStep(2);return;}
    if(!document.getElementById('tipeId').value){e.preventDefault();alert('Pilih tipe zakat.');goStep(2);return;}
    if(getJumlahZakat()<=0){e.preventDefault();alert('Jumlah zakat tidak valid.');goStep(2);return;}
    const bayarRadio=document.querySelector('.pay-radio:checked');
    if(!bayarRadio){e.preventDefault();alert('Pilih metode pembayaran (Transfer atau QRIS).');goStep(3);return;}
    if(bayarRadio.value==='qris'){const refQ=document.getElementById('noRefQris')?.value, refTr=document.getElementById('noRefTransfer');if(refQ&&refTr)refTr.value=refQ;}
    spinBtn(document.getElementById('btnFinalSave'),'Menyimpan...');
});

function spinBtn(btn,txt){btn._orig=btn.innerHTML;btn.disabled=true;btn.innerHTML=`<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> ${txt}`;}
function prvBukti(input,previewId){const el=document.getElementById(previewId);if(input.files?.[0]){if(input.files[0].size>2097152){alert('Ukuran file maks 2MB.');input.value='';return;}const r=new FileReader();r.onload=e=>{el.innerHTML=`<img src="${e.target.result}" class="h-full w-full object-contain">`;};r.readAsDataURL(input.files[0]);}}
function salin(teks){navigator.clipboard.writeText(teks).then(()=>{const el=document.createElement('div');el.textContent=teks+' disalin!';el.className='fixed bottom-5 right-5 bg-gray-900 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl z-50';document.body.appendChild(el);setTimeout(()=>el.remove(),2000);});}

document.addEventListener('DOMContentLoaded',()=>{
    @if(old('jenis_zakat_id'))
    setTimeout(()=>{document.getElementById('jenisId').value='{{ old("jenis_zakat_id") }}';document.getElementById('jenisId').dispatchEvent(new Event('change'));setTimeout(()=>{@if(old('tipe_zakat_id'))document.getElementById('tipeId').value='{{ old("tipe_zakat_id") }}';document.getElementById('tipeId').dispatchEvent(new Event('change'));@endif goStep(2);},150);},100);
    @endif
    @if(old('metode_pembayaran'))
    setTimeout(()=>{const r=document.querySelector('.pay-radio[value="{{ old("metode_pembayaran") }}"]');if(r)r.dispatchEvent(new Event('change'));goStep(3);},300);
    @endif
});
</script>
@endpush