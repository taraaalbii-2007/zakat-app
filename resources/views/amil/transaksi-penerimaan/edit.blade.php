@extends('layouts.app')

@section('title', 'Edit Transaksi Penerimaan Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- HEADER --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">
                        @if($needsZakatData)
                            Lanjutkan Pembayaran — {{ $transaksi->no_transaksi }}
                        @else
                            Edit Transaksi — {{ $transaksi->no_transaksi }}
                        @endif
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        @if($needsZakatData)
                            Lengkapi detail zakat dan metode pembayaran
                        @else
                            Perbarui data muzakki dan catatan transaksi
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('transaksi-dijemput.index') }}"
                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $transaksi->masjid->nama }}
                    </span>
                    @if($needsZakatData)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Data Belum Lengkap
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <form id="mainForm"
              action="{{ route('transaksi-dijemput.update', $transaksi->uuid) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="no_transaksi" value="{{ $transaksi->no_transaksi }}">
            <input type="hidden" name="metode_penerimaan" value="dijemput">
            <input type="hidden" name="is_pembayaran_beras" id="hdnBeras" value="0">

            {{-- ============================================================
                 MODE A: LENGKAPI ZAKAT (dijemput, data belum lengkap)
                 ============================================================ --}}
            @if($needsZakatData)

                {{-- Progress Steps --}}
                <div class="mb-7">
                    <div class="flex items-center max-w-lg mx-auto">
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot1" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold ring-4 ring-primary/20 bg-primary text-white">1</div>
                            <span class="text-xs mt-1 font-medium text-primary text-center leading-tight">Data Muzakki</span>
                        </div>
                        <div id="line12" class="flex-1 h-0.5 bg-gray-200 transition-colors"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot2" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">2</div>
                            <span class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Detail Zakat</span>
                        </div>
                        <div id="line23" class="flex-1 h-0.5 bg-gray-200 transition-colors"></div>
                        <div class="flex flex-col items-center flex-1">
                            <div id="dot3" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">3</div>
                            <span class="text-xs mt-1 font-medium text-gray-500 text-center leading-tight">Pembayaran</span>
                        </div>
                    </div>
                </div>

                {{-- Error summary --}}
                @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-sm mt-1 space-y-0.5">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Info Muzakki (sudah tersimpan) --}}
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Data Muzakki (Sudah Tersimpan)</p>
                            <div class="text-xs text-green-700 mt-2 space-y-1">
                                <p><strong>Nama:</strong> {{ $transaksi->muzakki_nama }}</p>
                                @if($transaksi->muzakki_telepon)<p><strong>Telepon:</strong> {{ $transaksi->muzakki_telepon }}</p>@endif
                                @if($transaksi->muzakki_alamat)<p><strong>Alamat:</strong> {{ $transaksi->muzakki_alamat }}</p>@endif
                                @if($transaksi->amil)<p><strong>Amil:</strong> {{ $transaksi->amil->nama_lengkap }}</p>@endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ==================== STEP 1 (SUDAH LEWAT) ==================== --}}
                {{-- Step 1 sudah selesai, langsung ke Step 2 --}}

                {{-- ==================== STEP 2: DETAIL ZAKAT ==================== --}}
                <div id="step2" class="step-panel">

                    <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                        <span class="inline-flex w-5 h-5 rounded-full bg-primary text-white text-xs items-center justify-center mr-1.5 font-bold">2</span>
                        Detail Zakat
                    </h3>

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Jenis Zakat --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Zakat <span class="text-red-500">*</span></label>
                                <select name="jenis_zakat_id" id="jenisId"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all @error('jenis_zakat_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisZakatList as $jz)
                                        <option value="{{ $jz->id }}" data-nama="{{ strtolower($jz->nama) }}"
                                            {{ old('jenis_zakat_id', $transaksi->jenis_zakat_id) == $jz->id ? 'selected' : '' }}>{{ $jz->nama }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_zakat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            {{-- Tipe Zakat --}}
                            <div id="wrapTipe" class="{{ old('jenis_zakat_id', $transaksi->jenis_zakat_id) ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe / Jenis Spesifik <span class="text-red-500">*</span></label>
                                <select name="tipe_zakat_id" id="tipeId"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all @error('tipe_zakat_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Tipe --</option>
                                </select>
                                @error('tipe_zakat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Program Zakat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Zakat <span class="text-xs text-gray-400">(opsional)</span></label>
                            <select name="program_zakat_id" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                <option value="">-- Tidak memilih program tertentu --</option>
                                @foreach($programZakatList as $prog)
                                    <option value="{{ $prog->id }}" {{ old('program_zakat_id', $transaksi->program_zakat_id) == $prog->id ? 'selected' : '' }}>{{ $prog->nama_program }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="border-gray-100">

                        {{-- PANEL: ZAKAT FITRAH — BERAS --}}
                        <div id="panelBeras" class="hidden space-y-4">
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                <p class="text-sm font-bold text-amber-900 mb-3 flex items-center gap-2">🌾 Ketentuan Zakat Fitrah Beras</p>
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center">
                                        <p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_kg'] }} kg</p>
                                        <p class="text-xs text-amber-700 mt-0.5">per jiwa</p>
                                    </div>
                                    <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center">
                                        <p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p>
                                        <p class="text-xs text-amber-700 mt-0.5">per jiwa</p>
                                    </div>
                                    <div class="bg-white rounded-lg border border-green-200 p-2.5 text-center">
                                        <p class="text-base font-bold text-green-700">≈ Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa (BAZNAS)</p>
                                    </div>
                                </div>
                                <p class="text-xs text-amber-700">ℹ️ Ketetapan BAZNAS: {{ $zakatFitrahInfo['beras_kg'] }} kg atau {{ $zakatFitrahInfo['beras_liter'] }} liter beras per jiwa ≈ <strong>Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}</strong></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Jiwa <span class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_jiwa" id="berasJiwa"
                                        value="{{ old('jumlah_jiwa', $transaksi->jumlah_jiwa ?? 1) }}" min="1" step="1"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Total Beras Diserahkan (kg) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="jumlah_beras_kg" id="berasKg"
                                        value="{{ old('jumlah_beras_kg', $transaksi->jumlah_beras_kg ?? $zakatFitrahInfo['beras_kg']) }}"
                                        min="0.1" step="0.1"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Harga Beras per Kg (Rp) <span class="text-xs text-gray-400">(opsional, untuk konversi uang)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                    <input type="number" name="harga_beras_per_kg" id="berasHarga"
                                        value="{{ old('harga_beras_per_kg', $transaksi->harga_beras_per_kg ?? 0) }}" min="0" step="500"
                                        placeholder="Isi untuk lihat konversi ke rupiah"
                                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                            </div>

                            <div id="infoBerasRingkas" class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-900 space-y-1.5">
                                <p class="text-xs font-semibold text-green-800 uppercase tracking-wide mb-2">📊 Ringkasan</p>
                                <p id="berasRingkasText">Isi data di atas untuk melihat ringkasan.</p>
                            </div>

                            <input type="hidden" name="jumlah" value="0">
                            <input type="hidden" name="nominal_per_jiwa" value="0">
                        </div>

                        {{-- PANEL: ZAKAT FITRAH — TUNAI (UANG) --}}
                        <div id="panelFitrahTunai" class="hidden space-y-4">
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                <p class="text-sm font-bold text-amber-900 mb-3 flex items-center gap-2">💰 Ketentuan Zakat Fitrah (Dibayar Uang)</p>
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center">
                                        <p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_kg'] }} kg</p>
                                        <p class="text-xs text-amber-700 mt-0.5">setara beras</p>
                                    </div>
                                    <div class="bg-white rounded-lg border border-amber-200 p-2.5 text-center">
                                        <p class="text-base font-bold text-amber-800">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p>
                                        <p class="text-xs text-amber-700 mt-0.5">setara beras</p>
                                    </div>
                                    <div class="bg-white rounded-lg border border-green-200 p-2.5 text-center">
                                        <p class="text-base font-bold text-green-700">Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">per jiwa (BAZNAS)</p>
                                    </div>
                                </div>
                                <p class="text-xs text-amber-700">ℹ️ BAZNAS menetapkan nilai {{ $zakatFitrahInfo['beras_kg'] }} kg / {{ $zakatFitrahInfo['beras_liter'] }} liter beras = <strong>Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}/jiwa</strong></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Jiwa <span class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_jiwa" id="tunaiJiwa"
                                        value="{{ old('jumlah_jiwa', $transaksi->jumlah_jiwa ?? 1) }}" min="1" step="1"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal per Jiwa (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nominal_per_jiwa" id="tunaiNominal"
                                            value="{{ old('nominal_per_jiwa', $transaksi->nominal_per_jiwa ?? $zakatFitrahInfo['nominal_per_jiwa']) }}"
                                            min="1000" step="1000"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">BAZNAS: Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'],0,',','.') }}/jiwa</p>
                                </div>
                            </div>

                            <div id="infoTunaiRingkas" class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                                <p class="text-xs font-semibold text-primary uppercase tracking-wide mb-2">📊 Ringkasan</p>
                                <div id="tunaiRingkasText" class="text-sm text-gray-500">Isi data di atas untuk melihat ringkasan.</div>
                            </div>

                            <input type="hidden" name="jumlah" id="hdnJumlahTunai" value="0">
                        </div>

                        {{-- PANEL: ZAKAT MAL --}}
                        <div id="panelMal" class="hidden space-y-4">
                            <div id="nisabBox" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-3">
                                <p class="text-xs font-semibold text-blue-800 mb-1">Informasi Nisab</p>
                                <div id="nisabIsi" class="text-xs text-blue-700 space-y-0.5"></div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Nilai Harta (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                    <input type="number" name="nilai_harta" id="malHarta"
                                        value="{{ old('nilai_harta', $transaksi->nilai_harta) }}" min="0" step="1000"
                                        placeholder="Total semua harta yang wajib dizakatkan"
                                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all @error('nilai_harta') border-red-500 @enderror">
                                </div>
                                @error('nilai_harta')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nisab Saat Ini (Rp) <span class="text-xs text-gray-400">(opsional)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nisab_saat_ini" value="{{ old('nisab_saat_ini', $transaksi->nisab_saat_ini) }}" min="0" step="1000"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Persentase Zakat (%)</label>
                                    <input type="number" name="persentase_zakat" id="malPersen"
                                        value="{{ old('persentase_zakat', 2.5) }}" min="0" max="100" step="0.1"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 flex items-start gap-3">
                                <input type="checkbox" name="sudah_haul" id="sudahHaul" value="1"
                                    {{ old('sudah_haul', $transaksi->sudah_haul) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded mt-0.5">
                                <div>
                                    <label for="sudahHaul" class="text-sm font-medium text-gray-900 cursor-pointer">Harta sudah mencapai haul (1 tahun hijriyah)</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Centang jika sudah dimiliki ≥ 1 tahun penuh</p>
                                </div>
                            </div>
                            <div id="wrapHaul" class="{{ old('sudah_haul', $transaksi->sudah_haul) ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Mulai Haul</label>
                                <input type="date" name="tanggal_mulai_haul" value="{{ old('tanggal_mulai_haul', $transaksi->tanggal_mulai_haul?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                            </div>

                            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                                <p class="text-xs font-semibold text-primary uppercase tracking-wide mb-1">Total Zakat Mal</p>
                                <p class="text-2xl font-bold text-primary" id="malTotalDisp">Rp 0</p>
                                <p class="text-xs text-gray-500 mt-0.5" id="malDetailDisp"></p>
                                <input type="hidden" name="jumlah" id="hdnJumlahMal" value="0">
                            </div>
                        </div>

                    </div>{{-- /space-y-5 --}}

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="goStep(1)"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </button>
                        <div class="flex gap-3 ml-auto">
                            {{-- Tombol lanjut ke step 3 (non-beras) --}}
                            <button type="button" id="btnS2Next" onclick="goStep(3)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary to-primary-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary/25 transition-all">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            {{-- Tombol simpan langsung (beras) — controller langsung verified --}}
                            <button type="submit" id="btnBerasSave"
                                class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-green-500/25 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Transaksi Beras
                            </button>
                        </div>
                    </div>
                </div>{{-- /step2 --}}

                {{-- ==================== STEP 3: METODE PEMBAYARAN ==================== --}}
                <div id="step3" class="step-panel hidden">

                    <h3 class="text-sm font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                        <span class="inline-flex w-5 h-5 rounded-full bg-primary text-white text-xs items-center justify-center mr-1.5 font-bold">3</span>
                        Metode Pembayaran
                    </h3>

                    <div class="space-y-5">

                        {{-- Kartu metode pembayaran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Cara Pembayaran <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                {{-- TUNAI --}}
                                <label id="cardTunai" class="pay-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all
                                    {{ old('metode_pembayaran')==='tunai' ? 'border-primary bg-primary/5' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_pembayaran" value="tunai" class="hidden pay-radio" {{ old('metode_pembayaran')==='tunai' ? 'checked' : '' }}>
                                    <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Tunai</p>
                                    <p class="text-xs text-gray-500 text-center">Bayar langsung ke amil</p>
                                </label>

                                {{-- TRANSFER --}}
                                <label id="cardTransfer" class="pay-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all
                                    {{ old('metode_pembayaran')==='transfer' ? 'border-primary bg-primary/5' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="hidden pay-radio" {{ old('metode_pembayaran')==='transfer' ? 'checked' : '' }}>
                                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4m-9 4v10"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Transfer Bank</p>
                                    <p class="text-xs text-gray-500 text-center">Ke rekening masjid</p>
                                </label>

                                {{-- QRIS --}}
                                <label id="cardQris" class="pay-card flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all
                                    {{ old('metode_pembayaran')==='qris' ? 'border-primary bg-primary/5' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_pembayaran" value="qris" class="hidden pay-radio" {{ old('metode_pembayaran')==='qris' ? 'checked' : '' }}>
                                    <div class="w-11 h-11 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">QRIS</p>
                                    <p class="text-xs text-gray-500 text-center">Scan QR masjid</p>
                                </label>
                            </div>
                            @error('metode_pembayaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- JUMLAH DIBAYAR + KALKULASI INFAQ --}}
                        <div id="wrapJmlDibayar" class="hidden">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-3">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="text-sm font-medium text-gray-700">Jumlah yang Diserahkan (Rp)</label>
                                        <button type="button" id="btnBayarPas" class="text-xs font-semibold text-primary hover:underline">
                                            Isi Sesuai Zakat
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="jumlah_dibayar" id="jmlDibayar"
                                            value="{{ old('jumlah_dibayar', $transaksi->jumlah_dibayar) }}" min="0" step="1000"
                                            placeholder="Kosongkan = bayar pas sesuai zakat wajib"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Jika membayar <strong>lebih</strong> dari kewajiban zakat, kelebihan otomatis dicatat sebagai <strong>infaq sukarela</strong>.
                                    </p>
                                </div>

                                <div id="boxKalkulasiInfaq" class="hidden rounded-xl border p-3 flex items-start gap-2 text-sm">
                                    <span id="ikonInfaq" class="mt-0.5 text-base leading-none">💰</span>
                                    <div id="teksInfaq"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Tunai --}}
                        <div id="infoTunaiSec" class="hidden bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-800">Pembayaran Tunai</p>
                                <p class="text-xs text-green-700 mt-0.5">Transaksi akan <strong>langsung terverifikasi</strong> setelah disimpan.</p>
                            </div>
                        </div>

                        {{-- Info Transfer --}}
                        <div id="infoTransferSec" class="hidden space-y-3">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-sm font-semibold text-blue-800 mb-3">🏦 Rekening Transfer Masjid</p>
                                @if($rekeningList->isNotEmpty())
                                    @foreach($rekeningList as $rek)
                                    <div class="bg-white border border-blue-200 rounded-lg p-3 flex items-center justify-between mb-2 last:mb-0">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800">{{ $rek->nama_bank }}</p>
                                            <p class="text-sm font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $rek->nomor_rekening }}</p>
                                            <p class="text-xs text-gray-500">a.n. {{ $rek->nama_pemilik }}</p>
                                        </div>
                                        <button type="button" onclick="salin('{{ $rek->nomor_rekening }}')"
                                            class="text-xs text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-all font-medium">Salin</button>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-xs text-gray-500">Hubungi amil untuk info rekening.</p>
                                @endif
                                <div class="mt-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Referensi Transfer <span class="text-gray-400">(opsional)</span></label>
                                    <input type="text" name="no_referensi_transfer" id="noRefTransfer" value="{{ old('no_referensi_transfer', $transaksi->no_referensi_transfer) }}"
                                        placeholder="Nomor dari struk/slip transfer"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bukti Transfer <span class="text-xs text-gray-400">(opsional)</span></label>
                                <div id="prvTransfer" class="h-28 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer" onclick="document.getElementById('inpTransfer').click()">
                                    <p class="text-xs text-gray-400">Klik untuk upload</p>
                                </div>
                                <input type="file" name="bukti_transfer" id="inpTransfer" accept="image/*" class="hidden" onchange="prvBukti(this,'prvTransfer')">
                                <p class="text-xs text-gray-500">Format: JPG, PNG. Maks 2MB.</p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-start gap-2">
                                <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <p class="text-xs text-yellow-800">Status <strong>pending</strong> — amil akan konfirmasi setelah dana masuk.</p>
                            </div>
                        </div>

                        {{-- Info QRIS --}}
                        <div id="infoQrisSec" class="hidden space-y-3">
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                                <p class="text-sm font-semibold text-purple-800 mb-3">📱 QRIS Masjid</p>
                                @php $rekeningQris = $rekeningList->where('jenis','qris')->first() ?? $rekeningList->first(); @endphp
                                @if($rekeningQris && !empty($rekeningQris->qris_image))
                                    <div class="flex justify-center mb-3">
                                        <div class="bg-white p-3 rounded-xl border border-purple-200">
                                            <img src="{{ Storage::url($rekeningQris->qris_image) }}" class="w-36 h-36 object-contain" alt="QRIS">
                                            <p class="text-xs text-center text-gray-500 mt-1">QRIS {{ $masjid->nama }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-white border border-purple-200 rounded-lg p-4 text-center mb-3">
                                        <p class="text-xs text-gray-500">Tunjukkan QRIS masjid kepada muzakki untuk di-scan.</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Referensi QRIS <span class="text-gray-400">(opsional)</span></label>
                                    <input type="text" id="noRefQris" value="{{ old('no_referensi_transfer', $transaksi->no_referensi_transfer) }}"
                                        placeholder="Nomor dari notifikasi QRIS"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Screenshot Bukti QRIS <span class="text-xs text-gray-400">(opsional)</span></label>
                                <div id="prvQris" class="h-28 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer" onclick="document.getElementById('inpQris').click()">
                                    <p class="text-xs text-gray-400">Klik untuk upload</p>
                                </div>
                                <input type="file" name="bukti_transfer" id="inpQris" accept="image/*" class="hidden" onchange="prvBukti(this,'prvQris')">
                                <p class="text-xs text-gray-500">Format: JPG, PNG. Maks 2MB.</p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-start gap-2">
                                <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <p class="text-xs text-yellow-800">Status <strong>pending</strong> — amil akan konfirmasi setelah dana masuk.</p>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan / Keterangan <span class="text-xs text-gray-400">(opsional)</span></label>
                            <textarea name="keterangan" rows="2" placeholder="Untuk program tertentu, atas nama keluarga, dll."
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary transition-all resize-none">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                        </div>

                        {{-- RINGKASAN FINAL --}}
                        <div id="boxRingSummary" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-3">✅ Ringkasan Transaksi</p>
                            <table class="w-full text-sm">
                                <tr class="border-b border-gray-100">
                                    <td class="text-gray-500 py-1.5 w-1/2">Zakat Wajib</td>
                                    <td class="font-semibold text-gray-900" id="ringJumlah">-</td>
                                </tr>
                                <tr id="ringRowDibayar" class="hidden border-b border-gray-100">
                                    <td class="text-gray-500 py-1.5">Jumlah Diserahkan</td>
                                    <td class="font-semibold text-gray-900" id="ringDibayar">-</td>
                                </tr>
                                <tr id="ringRowInfaq" class="hidden border-b border-gray-100">
                                    <td class="text-amber-600 py-1.5">+ Infaq Sukarela</td>
                                    <td class="font-semibold text-amber-600" id="ringInfaq">-</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="text-gray-500 py-1.5">Metode</td>
                                    <td class="font-semibold text-gray-900 capitalize" id="ringMetode">-</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-500 py-1.5">Status setelah simpan</td>
                                    <td class="font-semibold" id="ringStatus">-</td>
                                </tr>
                            </table>
                        </div>

                    </div>{{-- /space-y-5 --}}

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="goStep(2)"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </button>
                        <button type="submit" id="btnFinalSave" disabled
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary to-primary-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary/25 transition-all opacity-50 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Transaksi
                        </button>
                    </div>
                </div>{{-- /step3 --}}

            @else
            {{-- ============================================================
                 MODE B: EDIT DATA MUZAKKI BIASA
                 ============================================================ --}}

                @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-medium">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Info status transaksi --}}
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-800">Mode Edit Data Muzakki</p>
                            <p class="text-xs text-blue-700 mt-1">
                                Hanya data identitas muzakki dan catatan yang dapat diubah.
                                Detail zakat dan metode pembayaran tidak dapat diubah di sini.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 pb-2 border-b border-gray-200">Data Muzakki</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
{{-- Di bagian MODE B: EDIT DATA MUZAKKI BIASA --}}
<div>
    <label for="muzakki_nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
    <input type="text" name="muzakki_nama" id="muzakki_nama"
        value="{{ old('muzakki_nama', $transaksi->muzakki_nama) }}"
        placeholder="Masukkan nama lengkap"
        class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_nama') border-red-500 @enderror">
    @error('muzakki_nama')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
                        <div>
                            <label for="muzakki_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/WhatsApp</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">+62</span>
                                <input type="tel" name="muzakki_telepon" id="muzakki_telepon"
                                    value="{{ old('muzakki_telepon', $transaksi->muzakki_telepon) }}"
                                    placeholder="81234567890"
                                    class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_telepon') border-red-500 @enderror">
                            </div>
                            @error('muzakki_telepon')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="muzakki_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="muzakki_email" id="muzakki_email"
                                value="{{ old('muzakki_email', $transaksi->muzakki_email) }}"
                                placeholder="contoh@email.com"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_email') border-red-500 @enderror">
                            @error('muzakki_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="muzakki_alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea name="muzakki_alamat" id="muzakki_alamat" rows="2"
                                placeholder="Alamat lengkap muzakki"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">{{ old('muzakki_alamat', $transaksi->muzakki_alamat) }}</textarea>
                        </div>
                    </div>

                    {{-- Program --}}
                    <div>
                        <label for="program_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Program Zakat (Opsional)</label>
                        <select name="program_zakat_id" id="program_zakat_id"
                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                            <option value="">-- Pilih Program (Opsional) --</option>
                            @foreach($programZakatList as $program)
                                <option value="{{ $program->id }}" {{ old('program_zakat_id', $transaksi->program_zakat_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan / Catatan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            placeholder="Catatan tambahan..."
                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                    </div>

                    {{-- Info detail zakat (readonly) --}}
                    @if($transaksi->jenis_zakat_id)
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Detail Zakat (Tidak Dapat Diubah)</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-500">Jenis Zakat</p>
                                <p class="font-medium text-gray-900">{{ $transaksi->jenisZakat?->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tipe Zakat</p>
                                <p class="font-medium text-gray-900">{{ $transaksi->tipeZakat?->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jumlah</p>
                                <p class="font-medium text-gray-900">
                                    @if($transaksi->isBayarBeras())
                                        {{ $transaksi->jumlah_beras_kg }} kg beras
                                    @else
                                        Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Metode Pembayaran</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $transaksi->metode_pembayaran ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Status</p>
                                <div>{!! $transaksi->statusBadge() !!}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" id="submitBtnEdit"
                        class="inline-flex items-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            @endif
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
    nominalPerJiwa : {{ $zakatFitrahInfo['nominal_per_jiwa'] }},
    berasKg        : {{ $zakatFitrahInfo['beras_kg'] }},
    berasLiter     : {{ $zakatFitrahInfo['beras_liter'] }},
};
const TIPE_DATA    = @json($tipeZakatList ?? []);

let activeStep     = 2;
let activePanelZ   = null; // 'beras' | 'tunaiF' | 'mal' | null

// ══════════════════════════════════════════════════════════════
// FORMAT ANGKA
// ══════════════════════════════════════════════════════════════
function fmt(n) { return new Intl.NumberFormat('id-ID').format(Math.round(n || 0)); }

// ══════════════════════════════════════════════════════════════
// NAVIGASI STEP
// ══════════════════════════════════════════════════════════════
function goStep(n) {
    if (n > activeStep && !validateStep(activeStep)) return;
    document.querySelectorAll('.step-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('step' + n).classList.remove('hidden');
    activeStep = n;
    refreshDots(n);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function refreshDots(active) {
    [1,2,3].forEach(i => {
        const d = document.getElementById('dot' + i);
        if (!d) return;
        d.classList.remove('bg-primary','text-white','ring-4','ring-primary/20','bg-green-500','bg-gray-200','text-gray-500');
        if      (i < active)  { d.classList.add('bg-green-500','text-white'); d.textContent = '✓'; }
        else if (i === active) { d.classList.add('bg-primary','text-white','ring-4','ring-primary/20'); d.textContent = i; }
        else                   { d.classList.add('bg-gray-200','text-gray-500'); d.textContent = i; }

        const ln = document.getElementById(i === 1 ? 'line12' : 'line23');
        if (ln) ln.classList.toggle('bg-primary', i < active);
    });
}

// ══════════════════════════════════════════════════════════════
// VALIDASI PER STEP
// ══════════════════════════════════════════════════════════════
function validateStep(step) {
    if (step === 2) {
        if (!document.getElementById('jenisId').value) { alert('Pilih jenis zakat.'); return false; }
        if (!document.getElementById('tipeId').value)  { alert('Pilih tipe zakat.'); return false; }
        if (activePanelZ === 'beras') {
            if ((parseFloat(document.getElementById('berasKg').value) || 0) <= 0) { alert('Jumlah beras harus > 0.'); return false; }
        } else {
            if (getJumlahZakat() <= 0) { alert('Jumlah zakat tidak valid. Periksa detail zakat.'); return false; }
        }
        return true;
    }
    return true;
}

function getJumlahZakat() {
    if (activePanelZ === 'beras')  return 0;
    if (activePanelZ === 'tunaiF') return parseFloat(document.getElementById('hdnJumlahTunai').value) || 0;
    if (activePanelZ === 'mal')    return parseFloat(document.getElementById('hdnJumlahMal').value)   || 0;
    return 0;
}

// ══════════════════════════════════════════════════════════════
// STEP 2: JENIS & TIPE ZAKAT
// ══════════════════════════════════════════════════════════════
document.getElementById('jenisId').addEventListener('change', function () {
    const jenisId   = this.value;
    const tipeEl    = document.getElementById('tipeId');
    const wrapTipe  = document.getElementById('wrapTipe');

    tipeEl.innerHTML = '<option value="">-- Pilih Tipe --</option>';
    resetPanelZakat();

    if (!jenisId) { wrapTipe.classList.add('hidden'); return; }

    const list = TIPE_DATA[jenisId] || [];
    if (list.length > 0) {
        list.forEach(t => {
            const o = new Option(t.nama, t.uuid);
            o.dataset.nama        = t.nama.toLowerCase();
            o.dataset.persentase  = t.persentase_zakat || 2.5;
            o.dataset.nisabEmas   = t.nisab_emas_gram  || 0;
            o.dataset.requireHaul = t.requires_haul ? '1' : '0';
            tipeEl.appendChild(o);
        });
        wrapTipe.classList.remove('hidden');
    } else {
        wrapTipe.classList.add('hidden');
    }
});

document.getElementById('tipeId').addEventListener('change', function () {
    const jenisEl   = document.getElementById('jenisId');
    const namaJenis = (jenisEl.options[jenisEl.selectedIndex]?.dataset.nama || '').toLowerCase();
    const namaTipe  = (this.options[this.selectedIndex]?.dataset.nama || '').toLowerCase();

    resetPanelZakat();
    if (!this.value) return;

    const isFitrah = namaJenis.includes('fitrah');
    const isMal    = namaJenis.includes('mal');
    const isBeras  = namaTipe.includes('beras');

    if      (isFitrah && isBeras) tampilPanelBeras();
    else if (isFitrah)            tampilPanelFitrahTunai();
    else if (isMal)               tampilPanelMal(this.options[this.selectedIndex]);
});

function resetPanelZakat() {
    ['panelBeras','panelFitrahTunai','panelMal'].forEach(id => document.getElementById(id).classList.add('hidden'));
    document.getElementById('btnS2Next').classList.remove('hidden');
    document.getElementById('btnBerasSave').classList.add('hidden');
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
    hitungBeras();
}
['berasJiwa','berasKg','berasHarga'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', hitungBeras);
});
function hitungBeras() {
    const jiwa  = parseFloat(document.getElementById('berasJiwa').value)  || 0;
    const kg    = parseFloat(document.getElementById('berasKg').value)    || 0;
    const harga = parseFloat(document.getElementById('berasHarga').value) || 0;

    const minKg  = jiwa * BAZNAS.berasKg;
    const minLtr = jiwa * BAZNAS.berasLiter;
    const konvUang = kg * (harga > 0 ? harga : (BAZNAS.nominalPerJiwa / BAZNAS.berasKg));
    const baznasTot = jiwa * BAZNAS.nominalPerJiwa;

    let html = '';
    if (jiwa > 0 && kg > 0) {
        html += `<p>👤 <strong>${jiwa} jiwa</strong> × ${BAZNAS.berasKg} kg = minimum <strong>${minKg.toFixed(1)} kg</strong> (${minLtr.toFixed(1)} liter)</p>`;
        html += `<p>🌾 Beras diserahkan: <strong>${kg} kg</strong> (~${(kg * BAZNAS.berasLiter / BAZNAS.berasKg).toFixed(1)} liter)</p>`;
        if (harga > 0) html += `<p>💵 Konversi uang @Rp ${fmt(harga)}/kg: <strong>Rp ${fmt(konvUang)}</strong></p>`;
        html += `<p>📊 Estimasi BAZNAS (Rp ${fmt(BAZNAS.nominalPerJiwa)}/jiwa): <strong>Rp ${fmt(baznasTot)}</strong></p>`;
        if (kg < minKg) html += `<p class="text-red-700 font-semibold">⚠️ Beras kurang dari minimum (${minKg.toFixed(1)} kg)</p>`;
    } else {
        html = 'Isi jumlah jiwa dan beras untuk melihat ringkasan.';
    }
    document.getElementById('berasRingkasText').innerHTML = html;
}

// ─── PANEL FITRAH TUNAI ──────────────────────────────────
function tampilPanelFitrahTunai() {
    activePanelZ = 'tunaiF';
    document.getElementById('panelFitrahTunai').classList.remove('hidden');
    hitungFitrahTunai();
}
['tunaiJiwa','tunaiNominal'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', hitungFitrahTunai);
});
function hitungFitrahTunai() {
    const jiwa    = parseFloat(document.getElementById('tunaiJiwa').value)    || 0;
    const nominal = parseFloat(document.getElementById('tunaiNominal').value) || 0;
    const total   = jiwa * nominal;

    let html = '';
    if (jiwa > 0 && nominal > 0) {
        html += `<p class="font-semibold text-gray-800">💰 ${jiwa} jiwa × Rp ${fmt(nominal)} = <span class="text-primary text-lg">Rp ${fmt(total)}</span></p>`;
        html += `<p class="text-xs text-gray-500 mt-1">🌾 Setara beras: ~${(jiwa * BAZNAS.berasKg).toFixed(1)} kg / ${(jiwa * BAZNAS.berasLiter).toFixed(1)} liter</p>`;
        if (nominal < BAZNAS.nominalPerJiwa)
            html += `<p class="text-xs text-amber-700 mt-1">⚠️ Nominal di bawah ketetapan BAZNAS (Rp ${fmt(BAZNAS.nominalPerJiwa)}/jiwa)</p>`;
    } else {
        html = '<p class="text-sm text-gray-400">Isi jumlah jiwa dan nominal untuk melihat ringkasan.</p>';
    }
    document.getElementById('tunaiRingkasText').innerHTML = html;
    document.getElementById('hdnJumlahTunai').value = Math.round(total);
}

// ─── PANEL MAL ───────────────────────────────────────────
function tampilPanelMal(tipeOpt) {
    activePanelZ = 'mal';
    document.getElementById('panelMal').classList.remove('hidden');
    document.getElementById('malPersen').value = tipeOpt.dataset.persentase || 2.5;

    let nisabHtml = '';
    if (tipeOpt.dataset.nisabEmas > 0)    nisabHtml += `<p>• Nisab emas: ${tipeOpt.dataset.nisabEmas} gram</p>`;
    if (tipeOpt.dataset.requireHaul==='1') nisabHtml += `<p>• <strong>Membutuhkan haul</strong> (1 tahun hijriyah)</p>`;
    const box = document.getElementById('nisabBox');
    if (nisabHtml) { document.getElementById('nisabIsi').innerHTML = nisabHtml; box.classList.remove('hidden'); }
    else box.classList.add('hidden');

    hitungMal();
}
['malHarta','malPersen'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', hitungMal);
});
function hitungMal() {
    const h = parseFloat(document.getElementById('malHarta').value) || 0;
    const p = parseFloat(document.getElementById('malPersen').value) || 2.5;
    const t = h * (p / 100);
    document.getElementById('malTotalDisp').textContent = 'Rp ' + fmt(t);
    document.getElementById('malDetailDisp').textContent = `${p}% × Rp ${fmt(h)} = Rp ${fmt(t)}`;
    document.getElementById('hdnJumlahMal').value = Math.round(t);
}
document.getElementById('sudahHaul').addEventListener('change', function () {
    document.getElementById('wrapHaul').classList.toggle('hidden', !this.checked);
});

// ══════════════════════════════════════════════════════════════
// STEP 3: METODE PEMBAYARAN
// ══════════════════════════════════════════════════════════════
document.querySelectorAll('.pay-radio').forEach(r => {
    r.addEventListener('change', function () {
        const val = this.value;

        // Update visual
        document.querySelectorAll('.pay-card').forEach(c => {
            const chk = c.querySelector('input').checked;
            c.classList.toggle('border-primary', chk);
            c.classList.toggle('bg-primary/5', chk);
            c.classList.toggle('border-gray-200', !chk);
        });

        // Sembunyikan semua info section
        ['infoTunaiSec','infoTransferSec','infoQrisSec'].forEach(id => document.getElementById(id).classList.add('hidden'));

        // Tampil section + field jumlah dibayar
        document.getElementById('wrapJmlDibayar').classList.remove('hidden');
        if      (val === 'tunai')    document.getElementById('infoTunaiSec').classList.remove('hidden');
        else if (val === 'transfer') document.getElementById('infoTransferSec').classList.remove('hidden');
        else if (val === 'qris')     document.getElementById('infoQrisSec').classList.remove('hidden');

        // Prefill jumlah_dibayar
        const jd = document.getElementById('jmlDibayar');
        const jz = getJumlahZakat();
        if ((!jd.value || parseFloat(jd.value) === 0) && jz > 0) jd.value = jz;

        hitungKalkulasiInfaq();
        refreshRingSummary(val);

        // Enable submit
        const btn = document.getElementById('btnFinalSave');
        btn.disabled = false;
        btn.classList.remove('opacity-50','cursor-not-allowed');
    });
});

// Tombol bayar pas
document.getElementById('btnBayarPas').addEventListener('click', () => {
    const jz = getJumlahZakat();
    document.getElementById('jmlDibayar').value = jz;
    hitungKalkulasiInfaq();
    const m = document.querySelector('.pay-radio:checked')?.value;
    if (m) refreshRingSummary(m);
});

document.getElementById('jmlDibayar').addEventListener('input', function () {
    hitungKalkulasiInfaq();
    const m = document.querySelector('.pay-radio:checked')?.value;
    if (m) refreshRingSummary(m);
});

function hitungKalkulasiInfaq() {
    const jz   = getJumlahZakat();
    const jd   = parseFloat(document.getElementById('jmlDibayar').value) || 0;
    const box  = document.getElementById('boxKalkulasiInfaq');
    const ikon = document.getElementById('ikonInfaq');
    const teks = document.getElementById('teksInfaq');

    if (jz <= 0 || jd <= 0) { box.classList.add('hidden'); return; }

    const infaq = Math.max(0, jd - jz);
    const kurang = Math.max(0, jz - jd);
    box.classList.remove('hidden','bg-amber-50','border-amber-200','bg-red-50','border-red-200','bg-green-50','border-green-200');

    if (infaq > 0) {
        box.classList.add('bg-amber-50','border-amber-200');
        ikon.textContent = '🎉';
        teks.innerHTML = `Kelebihan <strong>Rp ${fmt(infaq)}</strong> otomatis dicatat sebagai <strong>infaq sukarela</strong>. Jazakallah khairan! 🙏`;
    } else if (kurang > 0) {
        box.classList.add('bg-red-50','border-red-200');
        ikon.textContent = '⚠️';
        teks.innerHTML = `Kurang <strong>Rp ${fmt(kurang)}</strong> dari zakat wajib (Rp ${fmt(jz)}).`;
    } else {
        box.classList.add('bg-green-50','border-green-200');
        ikon.textContent = '✅';
        teks.innerHTML = `Pembayaran <strong>pas</strong> sesuai zakat wajib (Rp ${fmt(jz)}).`;
    }
}

function refreshRingSummary(metode) {
    const jz    = getJumlahZakat();
    const jd    = parseFloat(document.getElementById('jmlDibayar').value) || jz;
    const infaq = Math.max(0, jd - jz);

    document.getElementById('boxRingSummary').classList.remove('hidden');
    document.getElementById('ringJumlah').textContent = activePanelZ === 'beras'
        ? (document.getElementById('berasKg').value + ' kg beras')
        : 'Rp ' + fmt(jz);

    if (jd !== jz && jd > 0 && activePanelZ !== 'beras') {
        document.getElementById('ringRowDibayar').classList.remove('hidden');
        document.getElementById('ringDibayar').textContent = 'Rp ' + fmt(jd);
    } else document.getElementById('ringRowDibayar').classList.add('hidden');

    if (infaq > 0 && activePanelZ !== 'beras') {
        document.getElementById('ringRowInfaq').classList.remove('hidden');
        document.getElementById('ringInfaq').textContent = 'Rp ' + fmt(infaq);
    } else document.getElementById('ringRowInfaq').classList.add('hidden');

    const mLabel = { tunai:'💵 Tunai', transfer:'🏦 Transfer Bank', qris:'📱 QRIS' };
    document.getElementById('ringMetode').textContent = mLabel[metode] || metode;

    const stEl = document.getElementById('ringStatus');
    if (metode === 'tunai')
        stEl.innerHTML = '<span class="text-green-600">✓ Langsung terverifikasi</span>';
    else
        stEl.innerHTML = '<span class="text-yellow-600">⏳ Pending — menunggu konfirmasi amil</span>';
}

// ══════════════════════════════════════════════════════════════
// FORM SUBMIT
// ══════════════════════════════════════════════════════════════
document.getElementById('mainForm').addEventListener('submit', function (e) {
    @if($needsZakatData)
        // Beras → set metode tunai (controller handle otomatis), submit
        if (activePanelZ === 'beras') {
            if ((parseFloat(document.getElementById('berasKg').value) || 0) <= 0) {
                e.preventDefault(); alert('Jumlah beras harus > 0.'); goStep(2); return;
            }
            if (!this.querySelector('input[name="metode_pembayaran"]')) {
                const h = document.createElement('input');
                h.type = 'hidden'; h.name = 'metode_pembayaran'; h.value = 'tunai';
                this.appendChild(h);
            }
            const btn = document.getElementById('btnBerasSave');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
            }
            return true;
        }

        // Validasi step 2
        if (!document.getElementById('jenisId').value) { e.preventDefault(); alert('Pilih jenis zakat.'); goStep(2); return; }
        if (!document.getElementById('tipeId').value)  { e.preventDefault(); alert('Pilih tipe zakat.');  goStep(2); return; }
        if (getJumlahZakat() <= 0)                      { e.preventDefault(); alert('Jumlah zakat tidak valid.'); goStep(2); return; }

        // Validasi step 3
        const bayarRadio = document.querySelector('.pay-radio:checked');
        if (!bayarRadio) { e.preventDefault(); alert('Pilih metode pembayaran.'); goStep(3); return; }

        // Sinkronkan no_referensi QRIS
        if (bayarRadio.value === 'qris') {
            const refQ  = document.getElementById('noRefQris')?.value;
            const refTr = document.getElementById('noRefTransfer');
            if (refQ && refTr) refTr.value = refQ;
        }

        const btn = document.getElementById('btnFinalSave');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
        }
        return true;
    @else
        // Mode B: Edit muzakki biasa
        const nama = document.getElementById('muzakki_nama')?.value?.trim();
        if (!nama) {
            e.preventDefault();
            alert('Nama muzakki harus diisi');
            return;
        }
        const btn = document.getElementById('submitBtnEdit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
        }
        return true;
    @endif
});

// ══════════════════════════════════════════════════════════════
// UTILITAS UI
// ══════════════════════════════════════════════════════════════
function prvBukti(input, previewId) {
    const el = document.getElementById(previewId);
    if (input.files?.[0]) {
        if (input.files[0].size > 2097152) { alert('Ukuran file maks 2MB.'); input.value = ''; return; }
        const r = new FileReader();
        r.onload = e => { el.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-contain">`; };
        r.readAsDataURL(input.files[0]);
    }
}

function salin(teks) {
    navigator.clipboard.writeText(teks).then(() => {
        const el = document.createElement('div');
        el.textContent = teks + ' disalin!';
        el.className = 'fixed bottom-5 right-5 bg-gray-900 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl z-50 animate-bounce';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2000);
    });
}

// ══════════════════════════════════════════════════════════════
// INISIALISASI
// ══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    @if($needsZakatData)
        // Load tipe zakat jika sudah ada jenis zakat
        const jenisId = '{{ old("jenis_zakat_id", $transaksi->jenis_zakat_id) }}';
        const tipeUuid = '{{ old("tipe_zakat_id", $transaksi->tipeZakat?->uuid ?? "") }}';

        if (jenisId) {
            document.getElementById('jenisId').value = jenisId;
            document.getElementById('jenisId').dispatchEvent(new Event('change'));
            
            if (tipeUuid) {
                setTimeout(() => {
                    const sel = document.getElementById('tipeId');
                    const match = Array.from(sel.options).find(o => o.value === tipeUuid);
                    if (match) {
                        sel.value = match.value;
                        sel.dispatchEvent(new Event('change'));
                    }
                }, 150);
            }
        }

        // Trigger metode pembayaran jika sudah dipilih
        const selectedMetode = document.querySelector('input[name="metode_pembayaran"]:checked');
        if (selectedMetode) {
            setTimeout(() => selectedMetode.dispatchEvent(new Event('change')), 200);
        }

        // Set step awal ke step 2
        goStep(2);
    @endif
});
</script>
@endpush