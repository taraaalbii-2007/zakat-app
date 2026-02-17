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
                    <a href="{{ route('transaksi-penerimaan.show', $transaksi->uuid) }}"
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

        <form id="formTransaksi"
              action="{{ route('transaksi-penerimaan.update', $transaksi->uuid) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="is_pembayaran_beras" id="is_pembayaran_beras" value="0">

            {{-- ============================================================
                 MODE A: LENGKAPI ZAKAT (dijemput, data belum lengkap)
                 ============================================================ --}}
            @if($needsZakatData)

                {{-- Progress Steps --}}
                <div class="mb-6 sm:mb-8">
                    <div class="flex items-center justify-between max-w-3xl mx-auto relative">
                        {{-- Step 1 (sudah selesai) --}}
                        <div class="flex flex-col items-center relative flex-1 z-10">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-500 text-white flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm mt-1 sm:mt-2 font-medium text-green-600">Data Muzakki</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-primary -mx-4" id="line1"></div>
                        {{-- Step 2 --}}
                        <div class="flex flex-col items-center relative flex-1 z-10">
                            <div class="step-indicator w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm sm:text-base font-semibold ring-4 ring-primary/30" data-step="2">2</div>
                            <span class="step-label text-xs sm:text-sm mt-1 sm:mt-2 font-medium text-primary" data-step="2">Detail Zakat</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-300 -mx-4" id="line2"></div>
                        {{-- Step 3 --}}
                        <div class="flex flex-col items-center relative flex-1 z-10">
                            <div class="step-indicator w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm sm:text-base font-semibold" data-step="3">3</div>
                            <span class="step-label text-xs sm:text-sm mt-1 sm:mt-2 font-medium text-gray-500" data-step="3">Pembayaran</span>
                        </div>
                    </div>
                </div>

                {{-- Error summary --}}
                @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-medium">Terdapat kesalahan pada form:</p>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
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

                {{-- ==================== STEP 2: DETAIL ZAKAT ==================== --}}
                <div class="step-content" data-step="2">
                    <div class="mb-6">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                            Detail Zakat
                        </h3>

                        <div class="space-y-4 sm:space-y-6">
                            <div>
                                <label for="jenis_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Jenis Zakat <span class="text-red-500">*</span></label>
                                <select name="jenis_zakat_id" id="jenis_zakat_id" onchange="loadTipeZakat()"
                                    class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jenis_zakat_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Jenis Zakat --</option>
                                    @foreach($jenisZakatList as $jenis)
                                        <option value="{{ $jenis->id }}"
                                            data-nama="{{ strtolower($jenis->nama) }}"
                                            {{ old('jenis_zakat_id', $transaksi->jenis_zakat_id) == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_zakat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div id="tipeZakatContainer" class="{{ old('jenis_zakat_id', $transaksi->jenis_zakat_id) ? '' : 'hidden' }}">
                                <label for="tipe_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Tipe Zakat <span class="text-red-500">*</span></label>
                                <select name="tipe_zakat_id" id="tipe_zakat_id" onchange="handleTipeZakatChange()"
                                    class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('tipe_zakat_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Tipe Zakat --</option>
                                </select>
                                @error('tipe_zakat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="program_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Program Zakat (Opsional)</label>
                                <select name="program_zakat_id" id="program_zakat_id"
                                    class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                    <option value="">-- Pilih Program (Opsional) --</option>
                                    @foreach($programZakatList as $program)
                                        <option value="{{ $program->id }}" {{ old('program_zakat_id', $transaksi->program_zakat_id) == $program->id ? 'selected' : '' }}>
                                            {{ $program->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="border-gray-200">

                            {{-- INFO BERAS --}}
                            <div id="infoBerasStep2" class="hidden bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-green-800">Pembayaran Bentuk Beras (In-Kind)</p>
                                        <p class="text-xs text-green-700 mt-1">Zakat dibayarkan dalam bentuk beras. Tidak memerlukan metode pembayaran — langsung simpan setelah mengisi detail.</p>
                                    </div>
                                </div>
                            </div>

                            <div id="nisabInfoContainer" class="bg-blue-50 p-4 rounded-xl border border-blue-200 hidden">
                                <h5 class="text-xs font-medium text-blue-800 mb-2">Informasi Nisab</h5>
                                <div id="nisabInfoContent" class="text-sm text-blue-700 space-y-1"></div>
                            </div>

                            {{-- Detail Fitrah --}}
                            <div id="detailFitrahContainer" class="space-y-4 hidden">
                                <h4 class="text-sm font-semibold text-gray-900">Detail Zakat Fitrah</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label for="jumlah_jiwa" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Jiwa <span class="text-red-500">*</span></label>
                                        <input type="number" name="jumlah_jiwa" id="jumlah_jiwa"
                                            value="{{ old('jumlah_jiwa', $transaksi->jumlah_jiwa) }}"
                                            min="1" step="1" onchange="hitungJumlah()"
                                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jumlah_jiwa') border-red-500 @enderror">
                                        @error('jumlah_jiwa')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="nominal_per_jiwa" class="block text-sm font-medium text-gray-700 mb-2">Nominal per Jiwa (Rp)</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                            <input type="number" name="nominal_per_jiwa" id="nominal_per_jiwa"
                                                value="{{ old('nominal_per_jiwa', $transaksi->nominal_per_jiwa ?? 50000) }}"
                                                min="0" step="1000" onchange="hitungJumlah()"
                                                class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label for="jumlah_beras_kg" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Beras (kg)</label>
                                        <input type="number" name="jumlah_beras_kg" id="jumlah_beras_kg"
                                            value="{{ old('jumlah_beras_kg', $transaksi->jumlah_beras_kg ?? 2.5) }}"
                                            min="0" step="0.1" onchange="hitungJumlah()"
                                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jumlah_beras_kg') border-red-500 @enderror">
                                        @error('jumlah_beras_kg')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="harga_beras_per_kg" class="block text-sm font-medium text-gray-700 mb-2">Harga Beras per Kg (Rp)</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                            <input type="number" name="harga_beras_per_kg" id="harga_beras_per_kg"
                                                value="{{ old('harga_beras_per_kg', $transaksi->harga_beras_per_kg) }}"
                                                min="0" step="1000" onchange="hitungJumlah()"
                                                class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Mal --}}
                            <div id="detailMalContainer" class="space-y-4 hidden">
                                <h4 class="text-sm font-semibold text-gray-900">Detail Zakat Mal</h4>
                                <div>
                                    <label for="nilai_harta" class="block text-sm font-medium text-gray-700 mb-2">Total Nilai Harta (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="nilai_harta" id="nilai_harta"
                                            value="{{ old('nilai_harta', $transaksi->nilai_harta) }}"
                                            min="0" step="1000" onchange="hitungJumlah()"
                                            class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('nilai_harta') border-red-500 @enderror">
                                    </div>
                                    @error('nilai_harta')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label for="nisab_saat_ini" class="block text-sm font-medium text-gray-700 mb-2">Nisab Saat Ini (Rp)</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                            <input type="number" name="nisab_saat_ini" id="nisab_saat_ini"
                                                value="{{ old('nisab_saat_ini', $transaksi->nisab_saat_ini) }}"
                                                min="0" step="1000"
                                                class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak tahu</p>
                                    </div>
                                    <div>
                                        <label for="persentase_zakat" class="block text-sm font-medium text-gray-700 mb-2">Persentase Zakat (%)</label>
                                        <input type="number" name="persentase_zakat" id="persentase_zakat"
                                            value="{{ old('persentase_zakat', 2.5) }}"
                                            min="0" max="100" step="0.1" onchange="hitungJumlah()"
                                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                                    <div class="flex items-start">
                                        <input type="checkbox" name="sudah_haul" id="sudah_haul" value="1"
                                            {{ old('sudah_haul', $transaksi->sudah_haul) ? 'checked' : '' }}
                                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-0.5">
                                        <div class="ml-3">
                                            <label for="sudah_haul" class="text-sm font-medium text-gray-900">Harta sudah mencapai haul (1 tahun hijriyah)</label>
                                            <p class="text-xs text-gray-600">Centang jika harta sudah dimiliki selama 1 tahun</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="tanggalHaulContainer" class="{{ old('sudah_haul', $transaksi->sudah_haul) ? '' : 'hidden' }}">
                                    <label for="tanggal_mulai_haul" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Haul</label>
                                    <input type="date" name="tanggal_mulai_haul" id="tanggal_mulai_haul"
                                        value="{{ old('tanggal_mulai_haul', $transaksi->tanggal_mulai_haul?->format('Y-m-d')) }}"
                                        class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="bg-primary-50 p-4 sm:p-6 rounded-xl border border-primary-200">
                                <label class="block text-xs font-medium text-primary-700 uppercase tracking-wider mb-2">Total yang Dibayarkan</label>
                                <div class="text-2xl sm:text-3xl font-bold text-primary-700" id="totalJumlahDisplay">
                                    @php
                                        $j = $transaksi->jumlah ?? 0;
                                        if ($transaksi->isBayarBeras) {
                                            $kg   = $transaksi->jumlah_beras_kg ?? 2.5;
                                            $jiwa = $transaksi->jumlah_jiwa ?? 1;
                                            echo ($jiwa * $kg) . ' kg beras';
                                        } else {
                                            echo 'Rp ' . number_format($j, 0, ',', '.');
                                        }
                                    @endphp
                                </div>
                                <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah', $transaksi->jumlah ?? 0) }}">
                                <p class="text-xs text-primary-600 mt-1" id="detailPerhitungan"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
                        <div></div>{{-- spacer --}}
                        <div class="flex gap-3">
                            <button type="button" id="step2NextBtn" onclick="nextStep(3)"
                                class="inline-flex items-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                                Selanjutnya
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <button type="submit" id="step2SaveBtn"
                                class="hidden inline-flex items-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-green-500/30">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ==================== STEP 3: PEMBAYARAN ==================== --}}
                <div class="step-content hidden" data-step="3">
                    <div class="mb-6">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                            Metode Pembayaran
                        </h3>

                        <div class="space-y-4 sm:space-y-6">
                            {{-- Pilih Metode --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Metode Pembayaran <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    {{-- Tunai --}}
                                    <label class="metode-pembayaran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_pembayaran') == 'tunai' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                        <input type="radio" name="metode_pembayaran" value="tunai" class="hidden metode-pembayaran-radio" {{ old('metode_pembayaran') == 'tunai' ? 'checked' : '' }}>
                                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-2">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">Tunai</span>
                                        <span class="text-xs text-gray-500 mt-1 text-center">Bayar langsung di tempat</span>
                                    </label>
                                    {{-- Transfer --}}
                                    <label class="metode-pembayaran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_pembayaran') == 'transfer' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                        <input type="radio" name="metode_pembayaran" value="transfer" class="hidden metode-pembayaran-radio" {{ old('metode_pembayaran') == 'transfer' ? 'checked' : '' }}>
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4m-9 4v10"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">Transfer Bank</span>
                                        <span class="text-xs text-gray-500 mt-1 text-center">Transfer ke rekening masjid</span>
                                    </label>
                                    {{-- QRIS --}}
                                    <label class="metode-pembayaran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_pembayaran') == 'qris' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                        <input type="radio" name="metode_pembayaran" value="qris" class="hidden metode-pembayaran-radio" {{ old('metode_pembayaran') == 'qris' ? 'checked' : '' }}>
                                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-2">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">QRIS</span>
                                        <span class="text-xs text-gray-500 mt-1 text-center">Scan QRIS masjid</span>
                                    </label>
                                </div>
                                @error('metode_pembayaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- INFO TUNAI --}}
                            <div id="infoTunaiSection" class="hidden bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-green-800">Pembayaran Tunai</p>
                                        <p class="text-xs text-green-700 mt-1">Muzakki membayar langsung kepada amil. Transaksi akan langsung terverifikasi setelah disimpan.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- INFO TRANSFER --}}
                            <div id="infoTransferSection" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-start gap-3 mb-4">
                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-800">Instruksi Transfer Bank</p>
                                            <p class="text-xs text-blue-700 mt-1">Muzakki mentransfer langsung ke rekening masjid. Dana masuk 100% tanpa potongan.</p>
                                        </div>
                                    </div>
                                    @if($rekeningList->isNotEmpty())
                                    <div class="space-y-2 mb-4">
                                        @foreach($rekeningList as $rekening)
                                        <div class="bg-white border border-blue-200 rounded-lg p-3 flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-800">{{ $rekening->nama_bank }}</p>
                                                <p class="text-sm font-bold text-gray-900 tracking-wider mt-0.5">{{ $rekening->nomor_rekening }}</p>
                                                <p class="text-xs text-gray-500">a.n. {{ $rekening->nama_pemilik }}</p>
                                            </div>
                                            <button type="button" onclick="copyToClipboard('{{ $rekening->nomor_rekening }}')"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded-lg hover:bg-blue-100 transition-all flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                                Salin
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="bg-white border border-blue-200 rounded-lg p-3 mb-4">
                                        <p class="text-xs text-gray-500 text-center">Hubungi amil untuk informasi rekening masjid</p>
                                    </div>
                                    @endif
                                    <div>
                                        <label for="no_referensi_transfer" class="block text-xs font-medium text-gray-700 mb-1.5">Nomor Referensi Transfer (Opsional)</label>
                                        <input type="text" name="no_referensi_transfer" id="no_referensi_transfer"
                                            value="{{ old('no_referensi_transfer') }}"
                                            placeholder="Contoh: 123456789012"
                                            class="block w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                        <p class="mt-1 text-xs text-gray-500">Isi nomor referensi dari slip transfer jika ada</p>
                                    </div>
                                </div>
                                {{-- Upload Bukti --}}
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Bukti Transfer
                                        <span class="text-xs text-gray-500 font-normal ml-1">(Opsional namun disarankan)</span>
                                    </label>
                                    <div class="space-y-3">
                                        <div id="buktiTransferPreview" class="h-32 w-full rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                            <div class="text-center">
                                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="mt-1 text-xs text-gray-500">Belum ada file dipilih</p>
                                            </div>
                                        </div>
                                        <input type="file" name="bukti_transfer" id="bukti_transfer_input" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewFile(this, 'buktiTransferPreview')">
                                        <label for="bukti_transfer_input" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12"/>
                                            </svg>
                                            Pilih File Bukti Transfer
                                        </label>
                                        <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks 2MB</p>
                                    </div>
                                </div>
                                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <p class="text-xs text-yellow-800"><strong>Perlu konfirmasi amil:</strong> Setelah muzakki transfer, amil akan memverifikasi dana masuk ke rekening masjid secara manual.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- INFO QRIS --}}
                            <div id="infoQrisSection" class="hidden">
                                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-start gap-3 mb-4">
                                        <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-purple-800">Instruksi Pembayaran QRIS</p>
                                            <p class="text-xs text-purple-700 mt-1">Muzakki scan QRIS statis milik masjid. Dana masuk langsung ke rekening masjid tanpa potongan biaya.</p>
                                        </div>
                                    </div>
                                    @php $rekeningQris = $rekeningList->where('jenis', 'qris')->first() ?? $rekeningList->first(); @endphp
                                    @if($rekeningQris && !empty($rekeningQris->qris_image))
                                    <div class="flex justify-center mb-4">
                                        <div class="bg-white p-3 rounded-xl border border-purple-200 inline-block">
                                            <img src="{{ Storage::url($rekeningQris->qris_image) }}" alt="QRIS Masjid" class="w-40 h-40 object-contain">
                                            <p class="text-xs text-center text-gray-500 mt-2">QRIS {{ $masjid->nama }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <div class="bg-white border border-purple-200 rounded-lg p-4 text-center mb-4">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                        <p class="text-xs text-gray-500">Tunjukkan QRIS masjid kepada muzakki untuk di-scan</p>
                                    </div>
                                    @endif
                                    <div>
                                        <label for="no_referensi_qris" class="block text-xs font-medium text-gray-700 mb-1.5">Nomor Referensi QRIS (Opsional)</label>
                                        <input type="text" name="no_referensi_transfer" id="no_referensi_qris"
                                            value="{{ old('no_referensi_transfer') }}"
                                            placeholder="Contoh: REF-20250217-001"
                                            class="block w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                        <p class="mt-1 text-xs text-gray-500">Isi nomor referensi dari notifikasi QRIS jika ada</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Bukti Scan QRIS
                                        <span class="text-xs text-gray-500 font-normal ml-1">(Opsional namun disarankan)</span>
                                    </label>
                                    <div class="space-y-3">
                                        <div id="buktiQrisPreview" class="h-32 w-full rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                            <div class="text-center">
                                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="mt-1 text-xs text-gray-500">Screenshot bukti pembayaran QRIS</p>
                                            </div>
                                        </div>
                                        <input type="file" name="bukti_transfer" id="bukti_qris_input" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewFile(this, 'buktiQrisPreview')">
                                        <label for="bukti_qris_input" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12"/>
                                            </svg>
                                            Pilih Screenshot Bukti QRIS
                                        </label>
                                        <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks 2MB</p>
                                    </div>
                                </div>
                                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <p class="text-xs text-yellow-800"><strong>Perlu konfirmasi amil:</strong> Setelah muzakki scan QRIS, amil akan memverifikasi dana masuk ke rekening masjid secara manual.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Keterangan --}}
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan / Catatan (Opsional)</label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    placeholder="Contoh: Zakat untuk program beasiswa, dll"
                                    class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                            </div>

                            {{-- Ringkasan --}}
                            <div id="ringSummary" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Ringkasan Transaksi</p>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <span class="text-gray-500">Total Zakat</span>
                                    <span class="font-semibold text-gray-900" id="ringSummaryJumlah">-</span>
                                    <span class="text-gray-500">Metode</span>
                                    <span class="font-semibold text-gray-900" id="ringSummaryMetode">-</span>
                                    <span class="text-gray-500">Status</span>
                                    <span id="ringSummaryStatus" class="font-semibold">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
                        <button type="button" onclick="prevStep(2)"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Sebelumnya
                        </button>
                        <button type="submit" id="submitBtn" disabled
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30 opacity-50 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

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
                        <div>
                            <label for="muzakki_nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="muzakki_nama" id="muzakki_nama"
                                value="{{ old('muzakki_nama', $transaksi->muzakki_nama) }}"
                                placeholder="Masukkan nama lengkap"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_nama') border-red-500 @enderror">
                            @error('muzakki_nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
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
                                    @if($transaksi->isBayarBeras)
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
                                <div>{!! $transaksi->statusBadge !!}</div>
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
// ============================================
// VARIABLES
// ============================================
let currentStep       = 2;
let isPembayaranBeras = false;
const tipeZakatData   = @json($tipeZakatList ?? []);

// ============================================
// UTILITIES
// ============================================
function formatNumber(n) {
    return new Intl.NumberFormat('id-ID').format(Math.round(n || 0));
}

function getJenisNama(jenisId) {
    const opt = document.querySelector(`#jenis_zakat_id option[value="${jenisId}"]`);
    return opt ? (opt.dataset.nama || opt.textContent.toLowerCase()) : '';
}
function isFitrah(jenisId) { return getJenisNama(jenisId).includes('fitrah'); }
function isMal(jenisId)    { return getJenisNama(jenisId).includes('mal'); }
function getTipeNama() {
    const sel = document.getElementById('tipe_zakat_id');
    return sel?.options[sel.selectedIndex]?.textContent?.toLowerCase() || '';
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const el = document.createElement('div');
        el.textContent = 'Nomor rekening disalin!';
        el.className = 'fixed bottom-4 right-4 bg-gray-800 text-white text-sm px-4 py-2 rounded-lg shadow-lg z-50';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2000);
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
    });
}

function previewFile(input, previewId) {
    const previewEl = document.getElementById(previewId);
    if (!previewEl || !input.files?.[0]) return;
    if (input.files[0].size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB'); input.value = ''; return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        previewEl.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-contain rounded-xl" alt="Preview">`;
    };
    reader.readAsDataURL(input.files[0]);
}

@if($needsZakatData)
// ============================================
// STEP NAVIGATION
// ============================================
function nextStep(step) {
    if (!validateStep(currentStep)) return;
    showStep(step);
}
function prevStep(step) { showStep(step); }

function showStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.querySelector(`.step-content[data-step="${step}"]`)?.classList.remove('hidden');
    updateStepIndicators(step);
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepIndicators(activeStep) {
    document.querySelectorAll('.step-indicator').forEach(el => {
        const stepNum = parseInt(el.dataset.step);
        const label   = document.querySelector(`.step-label[data-step="${stepNum}"]`);
        el.classList.remove('bg-gray-200','text-gray-600','bg-primary','text-white','ring-4','ring-primary/30');

        if (stepNum < activeStep) {
            el.classList.add('bg-primary','text-white');
            label?.classList.replace('text-gray-500','text-primary');
        } else if (stepNum === activeStep) {
            el.classList.add('bg-primary','text-white','ring-4','ring-primary/30');
            label?.classList.replace('text-gray-500','text-primary');
        } else {
            el.classList.add('bg-gray-200','text-gray-600');
            label?.classList.replace('text-primary','text-gray-500');
        }
    });

    const line2 = document.getElementById('line2');
    if (line2) {
        line2.classList.toggle('bg-primary',    activeStep >= 3);
        line2.classList.toggle('bg-gray-300',   activeStep < 3);
    }
}

// ============================================
// VALIDASI STEP 2
// ============================================
function validateStep(step) {
    if (step !== 2) return true;

    const jenisId = document.getElementById('jenis_zakat_id').value;
    const tipeId  = document.getElementById('tipe_zakat_id').value;
    const jumlah  = parseFloat(document.getElementById('jumlah').value) || 0;

    if (!jenisId) { alert('Jenis Zakat harus dipilih'); return false; }
    if (!tipeId)  { alert('Tipe Zakat harus dipilih');  return false; }

    if (isPembayaranBeras) {
        const kg = parseFloat(document.getElementById('jumlah_beras_kg').value) || 0;
        if (kg <= 0) { alert('Jumlah beras harus diisi'); return false; }
    } else {
        if (jumlah <= 0) { alert('Jumlah pembayaran tidak valid'); return false; }
        if (isFitrah(jenisId) && !getTipeNama().includes('beras')) {
            if ((parseFloat(document.getElementById('jumlah_jiwa').value)||0) <= 0 ||
                (parseFloat(document.getElementById('nominal_per_jiwa').value)||0) <= 0) {
                alert('Jumlah jiwa dan nominal per jiwa harus diisi'); return false;
            }
        }
        if (isMal(jenisId) && (parseFloat(document.getElementById('nilai_harta').value)||0) <= 0) {
            alert('Nilai harta harus diisi untuk zakat mal'); return false;
        }
    }
    return true;
}

// ============================================
// JENIS & TIPE ZAKAT
// ============================================
function loadTipeZakat() {
    const jenisId   = document.getElementById('jenis_zakat_id').value;
    const tipeSelect = document.getElementById('tipe_zakat_id');
    const tipeCont  = document.getElementById('tipeZakatContainer');

    tipeSelect.innerHTML = '<option value="">-- Pilih Tipe Zakat --</option>';
    ['detailFitrahContainer','detailMalContainer','nisabInfoContainer','infoBerasStep2']
        .forEach(id => document.getElementById(id)?.classList.add('hidden'));
    document.getElementById('step2NextBtn')?.classList.remove('hidden');
    document.getElementById('step2SaveBtn')?.classList.add('hidden');
    isPembayaranBeras = false;
    document.getElementById('is_pembayaran_beras').value = '0';
    resetJumlah();

    if (!jenisId) { tipeCont.classList.add('hidden'); return; }

    const list = tipeZakatData[jenisId] || [];
    if (list.length) {
        list.forEach(tipe => {
            const opt = document.createElement('option');
            opt.value            = tipe.uuid ?? tipe.id;
            opt.textContent      = tipe.nama;
            opt.dataset.persentase     = tipe.persentase_zakat || 2.5;
            opt.dataset.nisabEmas      = tipe.nisab_emas_gram || 0;
            opt.dataset.nisabPerak     = tipe.nisab_perak_gram || 0;
            opt.dataset.nisabPertanian = tipe.nisab_pertanian_kg || 0;
            opt.dataset.nisabKambing   = tipe.nisab_kambing_min || 0;
            opt.dataset.nisabSapi      = tipe.nisab_sapi_min || 0;
            opt.dataset.requiresHaul   = tipe.requires_haul ? 'true' : 'false';
            tipeSelect.appendChild(opt);
        });
        tipeCont.classList.remove('hidden');
    } else {
        tipeCont.classList.add('hidden');
    }
}

function resetJumlah() {
    document.getElementById('jumlah').value = 0;
    document.getElementById('totalJumlahDisplay').innerText = 'Rp 0';
    document.getElementById('detailPerhitungan').innerText  = '';
}

function hitungJumlah() {
    const jenisId  = document.getElementById('jenis_zakat_id').value;
    const tipeNama = getTipeNama();
    let total = 0, detail = '';

    if (isFitrah(jenisId)) {
        if (tipeNama.includes('beras')) {
            const kg   = parseFloat(document.getElementById('jumlah_beras_kg').value) || 0;
            const jiwa = parseFloat(document.getElementById('jumlah_jiwa').value) || 1;
            detail = `${jiwa} jiwa × ${kg} kg beras = ${(jiwa * kg).toFixed(1)} kg beras`;
            total  = 0;
        } else {
            const jiwa    = parseFloat(document.getElementById('jumlah_jiwa').value) || 0;
            const nominal = parseFloat(document.getElementById('nominal_per_jiwa').value) || 0;
            total  = jiwa * nominal;
            detail = `${jiwa} jiwa × Rp ${formatNumber(nominal)} = Rp ${formatNumber(total)}`;
        }
    } else if (isMal(jenisId)) {
        const harta  = parseFloat(document.getElementById('nilai_harta').value) || 0;
        const persen = parseFloat(document.getElementById('persentase_zakat').value) || 2.5;
        total  = harta * (persen / 100);
        detail = `${persen}% dari Rp ${formatNumber(harta)} = Rp ${formatNumber(total)}`;
    }

    document.getElementById('totalJumlahDisplay').innerText = isPembayaranBeras ? detail : ('Rp ' + formatNumber(total));
    document.getElementById('jumlah').value = Math.round(total);
    document.getElementById('detailPerhitungan').innerText = detail;
}

function handleTipeZakatChange() {
    const jenisId    = document.getElementById('jenis_zakat_id').value;
    const tipeSelect = document.getElementById('tipe_zakat_id');
    const selectedOpt= tipeSelect.options[tipeSelect.selectedIndex];
    const infoBerasBox= document.getElementById('infoBerasStep2');
    const step2Next  = document.getElementById('step2NextBtn');
    const step2Save  = document.getElementById('step2SaveBtn');

    ['detailFitrahContainer','detailMalContainer','nisabInfoContainer']
        .forEach(id => document.getElementById(id)?.classList.add('hidden'));
    infoBerasBox.classList.add('hidden');
    step2Next.classList.remove('hidden');
    step2Save.classList.add('hidden');
    isPembayaranBeras = false;
    document.getElementById('is_pembayaran_beras').value = '0';
    resetJumlah();

    if (!selectedOpt?.value) return;
    const tipeNama = selectedOpt.textContent.toLowerCase();

    if (isFitrah(jenisId)) {
        document.getElementById('detailFitrahContainer').classList.remove('hidden');

        const berasEl  = document.getElementById('jumlah_beras_kg');
        const hargaEl  = document.getElementById('harga_beras_per_kg');
        const nominalEl= document.getElementById('nominal_per_jiwa');
        const jiwaEl   = document.getElementById('jumlah_jiwa');

        if (tipeNama.includes('beras')) {
            isPembayaranBeras = true;
            document.getElementById('is_pembayaran_beras').value = '1';
            infoBerasBox.classList.remove('hidden');
            step2Next.classList.add('hidden');
            step2Save.classList.remove('hidden');
            berasEl.readOnly  = false;
            hargaEl.readOnly  = true;  hargaEl.value  = 0;
            nominalEl.readOnly= true;  nominalEl.value= 0;
            if (!jiwaEl.value  || jiwaEl.value  == 0) jiwaEl.value  = 1;
            if (!berasEl.value || berasEl.value == 0) berasEl.value = 2.5;
        } else {
            berasEl.readOnly = true;  berasEl.value  = 0;
            hargaEl.readOnly = true;  hargaEl.value  = 0;
            nominalEl.readOnly = false;
            if (!nominalEl.value || nominalEl.value == 0) nominalEl.value = 50000;
            if (!jiwaEl.value   || jiwaEl.value   == 0) jiwaEl.value   = 1;
        }
        hitungJumlah();

    } else if (isMal(jenisId)) {
        document.getElementById('detailMalContainer').classList.remove('hidden');
        document.getElementById('persentase_zakat').value = selectedOpt.dataset.persentase || 2.5;

        let nisabHtml = '';
        if (selectedOpt.dataset.nisabEmas > 0)      nisabHtml += `<div>• Nisab Emas: ${selectedOpt.dataset.nisabEmas} gram</div>`;
        if (selectedOpt.dataset.nisabPerak > 0)     nisabHtml += `<div>• Nisab Perak: ${selectedOpt.dataset.nisabPerak} gram</div>`;
        if (selectedOpt.dataset.nisabPertanian > 0) nisabHtml += `<div>• Nisab Pertanian: ${selectedOpt.dataset.nisabPertanian} kg</div>`;
        if (selectedOpt.dataset.nisabKambing > 0)   nisabHtml += `<div>• Nisab Kambing: minimal ${selectedOpt.dataset.nisabKambing} ekor</div>`;
        if (selectedOpt.dataset.nisabSapi > 0)      nisabHtml += `<div>• Nisab Sapi: minimal ${selectedOpt.dataset.nisabSapi} ekor</div>`;
        if (selectedOpt.dataset.requiresHaul === 'true') nisabHtml += `<div class="font-semibold mt-2">⚠️ Membutuhkan haul (1 tahun hijriyah)</div>`;
        if (nisabHtml) {
            document.getElementById('nisabInfoContent').innerHTML = nisabHtml;
            document.getElementById('nisabInfoContainer').classList.remove('hidden');
        }
    }
}

// ============================================
// METODE PEMBAYARAN (Step 3) - KONFIRMASI MANUAL
// ============================================
document.querySelectorAll('.metode-pembayaran-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const metode = this.value;

        // Update visual card
        document.querySelectorAll('.metode-pembayaran-card').forEach(card => {
            const checked = card.querySelector('input').checked;
            card.classList.toggle('border-primary',  checked);
            card.classList.toggle('bg-primary-50',   checked);
            card.classList.toggle('border-gray-200', !checked);
            card.classList.toggle('bg-white',        !checked);
        });

        // Sembunyikan semua section info
        ['infoTunaiSection','infoTransferSection','infoQrisSection']
            .forEach(id => document.getElementById(id)?.classList.add('hidden'));

        if (metode === 'tunai') {
            document.getElementById('infoTunaiSection')?.classList.remove('hidden');
        } else if (metode === 'transfer') {
            document.getElementById('infoTransferSection')?.classList.remove('hidden');
        } else if (metode === 'qris') {
            document.getElementById('infoQrisSection')?.classList.remove('hidden');
        }

        // Update ringkasan
        updateRingSummary(metode);

        // Enable submit button langsung (tidak perlu Midtrans)
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50','cursor-not-allowed');
            submitBtn.classList.add('hover:from-primary-600','hover:to-primary-700');
        }
    });
});

function updateRingSummary(metode) {
    const ringSummary = document.getElementById('ringSummary');
    if (!ringSummary) return;
    const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;

    ringSummary.classList.remove('hidden');
    document.getElementById('ringSummaryJumlah').textContent = isPembayaranBeras
        ? (document.getElementById('detailPerhitungan').textContent || '-')
        : 'Rp ' + formatNumber(jumlah);

    const metodeLabel = { tunai: 'Tunai', transfer: 'Transfer Bank', qris: 'QRIS' };
    document.getElementById('ringSummaryMetode').textContent = metodeLabel[metode] || metode;

    const statusEl = document.getElementById('ringSummaryStatus');
    if (metode === 'tunai') {
        statusEl.textContent = '✓ Langsung terverifikasi';
        statusEl.className   = 'font-semibold text-green-600';
    } else {
        statusEl.textContent = '⏳ Menunggu konfirmasi amil';
        statusEl.className   = 'font-semibold text-yellow-600';
    }
}

// ============================================
// FORM SUBMIT HANDLER (mode needsZakatData)
// ============================================
document.getElementById('formTransaksi').addEventListener('submit', function(e) {
    if (isPembayaranBeras) {
        const jenisId = document.getElementById('jenis_zakat_id').value;
        const tipeId  = document.getElementById('tipe_zakat_id').value;
        const kg      = parseFloat(document.getElementById('jumlah_beras_kg').value) || 0;

        if (!jenisId || !tipeId || kg <= 0) {
            e.preventDefault();
            alert('Data zakat fitrah beras harus lengkap');
            showStep(2);
            return;
        }

        // Set metode pembayaran otomatis untuk beras
        if (!document.querySelector('input[name="metode_pembayaran"]')) {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'metode_pembayaran'; inp.value = 'tunai';
            this.appendChild(inp);
        }

        const btn = document.getElementById('step2SaveBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
        }
        return;
    }

    if (currentStep === 2) {
        e.preventDefault();
        if (!validateStep(2)) return;
        alert('Silakan lanjut ke Step 3 untuk memilih metode pembayaran');
        return;
    }

    if (!validateStep(2)) {
        e.preventDefault(); showStep(2); return;
    }

    const metode = document.querySelector('input[name="metode_pembayaran"]:checked');
    if (!metode) {
        e.preventDefault(); alert('Metode pembayaran harus dipilih'); return;
    }

    // Sinkronkan no_referensi jika QRIS
    if (metode.value === 'qris') {
        const refQris = document.getElementById('no_referensi_qris')?.value;
        const refField = document.getElementById('no_referensi_transfer');
        if (refQris && refField) refField.value = refQris;
    }

    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
    }
});

// ============================================
// HAUL CHECKBOX
// ============================================
document.getElementById('sudah_haul')?.addEventListener('change', function() {
    document.getElementById('tanggalHaulContainer').classList.toggle('hidden', !this.checked);
});

// ============================================
// PAGE LOAD INIT (restore nilai lama)
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const jenisId  = '{{ old("jenis_zakat_id", $transaksi->jenis_zakat_id) }}';
    const tipeUuid = '{{ old("tipe_zakat_id", $transaksi->tipeZakat->uuid ?? "") }}';

    if (jenisId) {
        document.getElementById('jenis_zakat_id').value = jenisId;
        loadTipeZakat();

        if (tipeUuid) {
            setTimeout(() => {
                const sel   = document.getElementById('tipe_zakat_id');
                const match = Array.from(sel.options).find(o => o.value === tipeUuid);
                if (match) sel.value = match.value;
                handleTipeZakatChange();

                @if(old('jumlah', $transaksi->jumlah ?? 0) > 0 || ($transaksi->isZakatFitrah ?? false))
                setTimeout(() => hitungJumlah(), 50);
                @endif
            }, 150);
        }
    }

    // Trigger metode pembayaran jika sudah dipilih (old value)
    const selectedMetode = document.querySelector('input[name="metode_pembayaran"]:checked');
    if (selectedMetode) {
        setTimeout(() => selectedMetode.dispatchEvent(new Event('change')), 200);
    }
});

@else
// ============================================
// MODE B: Edit muzakki biasa — validasi simpel
// ============================================
document.getElementById('formTransaksi').addEventListener('submit', function(e) {
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
});
@endif
</script>
@endpush