@extends('layouts.app')

@section('title', 'Tambah Transaksi Penerimaan Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Transaksi Penerimaan Zakat</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi data muzakki dan detail pembayaran zakat</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('transaksi-penerimaan.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $masjid->nama }}
                    </span>
                </div>
            </div>
        </div>

        <form id="formTransaksi" action="{{ route('transaksi-penerimaan.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="no_transaksi" value="{{ $noTransaksiPreview }}">
            <input type="hidden" name="tanggal_transaksi" value="{{ $tanggalHariIni }}">
            <input type="hidden" name="waktu_transaksi" value="{{ now()->format('H:i:s') }}">
            <input type="hidden" name="is_pembayaran_beras" id="is_pembayaran_beras" value="0">

            {{-- Progress Steps --}}
            <div class="mb-6 sm:mb-8" id="progressSteps">
                <div class="flex items-center justify-between max-w-3xl mx-auto relative">
                    {{-- Step 1 --}}
                    <div class="flex flex-col items-center relative flex-1 z-10">
                        <div class="step-indicator w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm sm:text-base font-semibold step-active" data-step="1">1</div>
                        <span class="text-xs sm:text-sm mt-1 sm:mt-2 font-medium text-primary">Data Muzakki</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-300 -mx-4 step-line" id="line1"></div>
                    {{-- Step 2 --}}
                    <div class="flex flex-col items-center relative flex-1 z-10" id="step2Indicator">
                        <div class="step-indicator w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm sm:text-base font-semibold" data-step="2">2</div>
                        <span class="text-xs sm:text-sm mt-1 sm:mt-2 font-medium text-gray-500">Detail Zakat</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-300 -mx-4 step-line" id="line2"></div>
                    {{-- Step 3 --}}
                    <div class="flex flex-col items-center relative flex-1 z-10" id="step3Indicator">
                        <div class="step-indicator w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm sm:text-base font-semibold" data-step="3">3</div>
                        <span class="text-xs sm:text-sm mt-1 sm:mt-2 font-medium text-gray-500">Pembayaran</span>
                    </div>
                </div>
            </div>

            {{-- Error Summary --}}
            @if($errors->any())
            <div class="mb-4 sm:mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-medium">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- ===================== STEP 1: DATA MUZAKKI ===================== --}}
            <div class="step-content" data-step="1">
                <div class="mb-6">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Data Muzakki (Pemberi Zakat)
                    </h3>

                    <div class="space-y-4 sm:space-y-6">
                        {{-- Metode Penerimaan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Metode Penerimaan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="metode-penerimaan-option relative flex items-center justify-between p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_penerimaan', 'datang_langsung') == 'datang_langsung' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_penerimaan" value="datang_langsung" class="hidden metode-penerimaan-radio" {{ old('metode_penerimaan', 'datang_langsung') == 'datang_langsung' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Datang Langsung</p>
                                            <p class="text-xs text-gray-500">Muzakki datang ke masjid</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ old('metode_penerimaan', 'datang_langsung') == 'datang_langsung' ? 'border-primary' : 'border-gray-300' }}">
                                        <div class="w-2.5 h-2.5 rounded-full {{ old('metode_penerimaan', 'datang_langsung') == 'datang_langsung' ? 'bg-primary' : 'bg-transparent' }}"></div>
                                    </div>
                                </label>

                                <label class="metode-penerimaan-option relative flex items-center justify-between p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_penerimaan') == 'dijemput' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_penerimaan" value="dijemput" class="hidden metode-penerimaan-radio" {{ old('metode_penerimaan') == 'dijemput' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Dijemput</p>
                                            <p class="text-xs text-gray-500">Amil jemput ke lokasi</p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ old('metode_penerimaan') == 'dijemput' ? 'border-primary' : 'border-gray-300' }}">
                                        <div class="w-2.5 h-2.5 rounded-full {{ old('metode_penerimaan') == 'dijemput' ? 'bg-primary' : 'bg-transparent' }}"></div>
                                    </div>
                                </label>
                            </div>
                            @error('metode_penerimaan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- INFO BOX: DIJEMPUT --}}
                        <div id="infoDijemputStep1" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-800">Metode Penjemputan</p>
                                    <p class="text-xs text-blue-700 mt-1">Untuk metode dijemput, Anda <strong>hanya perlu mengisi data muzakki</strong>. Detail zakat dan pembayaran akan diisi oleh amil saat tiba di lokasi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="muzakki_nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="muzakki_nama" id="muzakki_nama" value="{{ old('muzakki_nama') }}" placeholder="Masukkan nama lengkap" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_nama') border-red-500 @enderror">
                                @error('muzakki_nama')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="muzakki_nik" class="block text-sm font-medium text-gray-700 mb-2">NIK (16 Digit)</label>
                                <input type="text" name="muzakki_nik" id="muzakki_nik" value="{{ old('muzakki_nik') }}" placeholder="Masukkan 16 digit NIK" maxlength="16" pattern="[0-9]{16}" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_nik') border-red-500 @enderror">
                                @error('muzakki_nik')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="muzakki_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">+62</span>
                                    <input type="tel" name="muzakki_telepon" id="muzakki_telepon" value="{{ old('muzakki_telepon') }}" placeholder="81234567890" class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_telepon') border-red-500 @enderror">
                                </div>
                                @error('muzakki_telepon')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="muzakki_email" class="block text-sm font-medium text-gray-700 mb-2">Email (Untuk Kwitansi Digital)</label>
                                <input type="email" name="muzakki_email" id="muzakki_email" value="{{ old('muzakki_email') }}" placeholder="contoh@email.com" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_email') border-red-500 @enderror">
                                @error('muzakki_email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat & Koordinat (hanya dijemput) --}}
                        <div id="alamatContainer" class="{{ old('metode_penerimaan') == 'dijemput' ? '' : 'hidden' }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Alamat Lengkap <span id="alamatRequired" class="{{ old('metode_penerimaan') == 'dijemput' ? 'text-red-500' : 'text-gray-400' }}">*</span></label>
                                <textarea name="muzakki_alamat" id="muzakki_alamat" rows="3" placeholder="Masukkan alamat lengkap (termasuk jalan, RT/RW, kelurahan, kecamatan)" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('muzakki_alamat') border-red-500 @enderror">{{ old('muzakki_alamat') }}</textarea>
                                @error('muzakki_alamat')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="latitude" class="block text-xs font-medium text-gray-600 mb-1">Latitude <span class="text-red-500">*</span></label>
                                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Contoh: -6.2088" class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('latitude') border-red-500 @enderror">
                                        @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="longitude" class="block text-xs font-medium text-gray-600 mb-1">Longitude <span class="text-red-500">*</span></label>
                                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Contoh: 106.8456" class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('longitude') border-red-500 @enderror">
                                        @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <button type="button" id="getLocationBtn" class="mt-3 inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ambil Koordinat Saat Ini
                                </button>
                            </div>
                        </div>

                        {{-- Pilih Amil (hanya dijemput) --}}
                        <div id="amilContainer" class="{{ old('metode_penerimaan') == 'dijemput' ? '' : 'hidden' }}">
                            <div>
                                <label for="amil_id" class="block text-sm font-medium text-gray-700 mb-3">Pilih Amil yang Bertugas <span class="text-red-500">*</span></label>
                                <select name="amil_id" id="amil_id" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('amil_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Amil --</option>
                                    @foreach($amilList as $amil)
                                        <option value="{{ $amil->id }}" {{ old('amil_id') == $amil->id ? 'selected' : '' }}>
                                            {{ $amil->nama_lengkap }} ({{ $amil->kode_amil }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('amil_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="step1NextBtn" onclick="handleStep1Next()" class="inline-flex items-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                        <span id="step1NextBtnText">Selanjutnya</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ===================== STEP 2: DETAIL ZAKAT ===================== --}}
            <div class="step-content hidden" data-step="2">
                <div class="mb-6">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Detail Zakat
                    </h3>

                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <label for="jenis_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Jenis Zakat <span class="text-red-500">*</span></label>
                            <select name="jenis_zakat_id" id="jenis_zakat_id" onchange="loadTipeZakat()" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jenis_zakat_id') border-red-500 @enderror">
                                <option value="">-- Pilih Jenis Zakat --</option>
                                @foreach($jenisZakatList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis_zakat_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="tipeZakatContainer" class="{{ old('jenis_zakat_id') ? '' : 'hidden' }}">
                            <label for="tipe_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Tipe Zakat <span class="text-red-500">*</span></label>
                            <select name="tipe_zakat_id" id="tipe_zakat_id" onchange="handleTipeZakatChange()" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('tipe_zakat_id') border-red-500 @enderror">
                                <option value="">-- Pilih Tipe Zakat --</option>
                            </select>
                            @error('tipe_zakat_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Program Zakat (Opsional)</label>
                            <select name="program_zakat_id" id="program_zakat_id" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                <option value="">-- Pilih Program (Opsional) --</option>
                                @foreach($programZakatList as $program)
                                    <option value="{{ $program->id }}" {{ old('program_zakat_id') == $program->id ? 'selected' : '' }}>{{ $program->nama_program }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="border-gray-200">

                        {{-- INFO BOX: PEMBAYARAN BERAS --}}
                        <div id="infoBerasStep2" class="hidden bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">Pembayaran Bentuk Beras (In-Kind)</p>
                                    <p class="text-xs text-green-700 mt-1">Zakat ini dibayarkan dalam bentuk beras. Tidak memerlukan metode pembayaran — langsung simpan setelah mengisi detail.</p>
                                </div>
                            </div>
                        </div>

                        <div id="nisabInfoContainer" class="bg-blue-50 p-4 rounded-xl border border-blue-200 hidden">
                            <h5 class="text-xs font-medium text-blue-800 mb-2">Informasi Nisab</h5>
                            <div id="nisabInfoContent" class="text-sm text-blue-700 space-y-1"></div>
                        </div>

                        {{-- Detail Zakat Fitrah --}}
                        <div id="detailFitrahContainer" class="space-y-4 hidden">
                            <h4 class="text-sm font-semibold text-gray-900">Detail Zakat Fitrah</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="jumlah_jiwa" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Jiwa <span class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_jiwa" id="jumlah_jiwa" value="{{ old('jumlah_jiwa') }}" min="1" step="1" onchange="hitungJumlah()" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('jumlah_jiwa') border-red-500 @enderror">
                                    @error('jumlah_jiwa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div id="nominalPerJiwaWrapper">
                                    <label for="nominal_per_jiwa" class="block text-sm font-medium text-gray-700 mb-2">Nominal per Jiwa (Rp)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="nominal_per_jiwa" id="nominal_per_jiwa" value="{{ old('nominal_per_jiwa', 50000) }}" min="0" step="1000" onchange="hitungJumlah()" class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6" id="berasFieldsWrapper">
                                <div>
                                    <label for="jumlah_beras_kg" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Beras (kg) <span id="berasRequired" class="text-gray-400">*</span></label>
                                    <input type="number" name="jumlah_beras_kg" id="jumlah_beras_kg" value="{{ old('jumlah_beras_kg', 2.5) }}" min="0" step="0.1" onchange="hitungJumlah()" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('jumlah_beras_kg') border-red-500 @enderror">
                                    @error('jumlah_beras_kg') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="harga_beras_per_kg" class="block text-sm font-medium text-gray-700 mb-2">Harga Beras per Kg (Rp)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="harga_beras_per_kg" id="harga_beras_per_kg" value="{{ old('harga_beras_per_kg') }}" min="0" step="1000" onchange="hitungJumlah()" class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Zakat Mal --}}
                        <div id="detailMalContainer" class="space-y-4 hidden">
                            <h4 class="text-sm font-semibold text-gray-900">Detail Zakat Mal</h4>
                            <div>
                                <label for="nilai_harta" class="block text-sm font-medium text-gray-700 mb-2">Total Nilai Harta (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                    <input type="number" name="nilai_harta" id="nilai_harta" value="{{ old('nilai_harta') }}" min="0" step="1000" onchange="hitungJumlah()" class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('nilai_harta') border-red-500 @enderror">
                                </div>
                                @error('nilai_harta') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="nisab_saat_ini" class="block text-sm font-medium text-gray-700 mb-2">Nisab Saat Ini (Rp)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="nisab_saat_ini" id="nisab_saat_ini" value="{{ old('nisab_saat_ini') }}" min="0" step="1000" class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak tahu</p>
                                </div>
                                <div>
                                    <label for="persentase_zakat" class="block text-sm font-medium text-gray-700 mb-2">Persentase Zakat (%)</label>
                                    <input type="number" name="persentase_zakat" id="persentase_zakat" value="{{ old('persentase_zakat', 2.5) }}" min="0" max="100" step="0.1" onchange="hitungJumlah()" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                                <div class="flex items-start">
                                    <input type="checkbox" name="sudah_haul" id="sudah_haul" value="1" {{ old('sudah_haul') ? 'checked' : '' }} class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary mt-0.5">
                                    <div class="ml-3">
                                        <label for="sudah_haul" class="text-sm font-medium text-gray-900">Harta sudah mencapai haul (1 tahun hijriyah)</label>
                                        <p class="text-xs text-gray-600">Centang jika harta sudah dimiliki selama 1 tahun</p>
                                    </div>
                                </div>
                            </div>
                            <div id="tanggalHaulContainer" class="{{ old('sudah_haul') ? '' : 'hidden' }}">
                                <label for="tanggal_mulai_haul" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Haul</label>
                                <input type="date" name="tanggal_mulai_haul" id="tanggal_mulai_haul" value="{{ old('tanggal_mulai_haul') }}" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="bg-primary-50 p-4 sm:p-6 rounded-xl border border-primary-200">
                            <label class="block text-xs font-medium text-primary-700 uppercase tracking-wider mb-2">Total yang Dibayarkan</label>
                            <div class="text-2xl sm:text-3xl font-bold text-primary-700" id="totalJumlahDisplay">Rp 0</div>
                            <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah', 0) }}">
                            <p class="text-xs text-primary-600 mt-1" id="detailPerhitungan"></p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
                    <button type="button" onclick="prevStep(1)" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Sebelumnya
                    </button>

                    <div class="flex gap-3">
                        {{-- Tombol Next (non-beras) --}}
                        <button type="button" id="step2NextBtn" onclick="nextStep(3)" class="inline-flex items-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                            Selanjutnya
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        {{-- Tombol Simpan langsung (beras) --}}
                        <button type="submit" id="step2SaveBtn" class="hidden inline-flex items-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-green-500/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===================== STEP 3: METODE PEMBAYARAN ===================== --}}
            <div class="step-content hidden" data-step="3">
                <div class="mb-6">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                        Metode Pembayaran
                    </h3>

                    <div class="space-y-4 sm:space-y-6">

                        {{-- PILIH METODE PEMBAYARAN --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Metode Pembayaran <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                                {{-- TUNAI --}}
                                <label class="metode-pembayaran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_pembayaran') == 'tunai' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_pembayaran" value="tunai" class="hidden metode-pembayaran-radio" {{ old('metode_pembayaran') == 'tunai' ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Tunai</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Bayar langsung di tempat</span>
                                </label>

                                {{-- TRANSFER BANK --}}
                                <label class="metode-pembayaran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_pembayaran') == 'transfer' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="hidden metode-pembayaran-radio" {{ old('metode_pembayaran') == 'transfer' ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4m-9 4v10" />
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">QRIS</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Scan QRIS masjid</span>
                                </label>
                            </div>
                            @error('metode_pembayaran')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- INFO TUNAI --}}
                        <div id="infoTunaiSection" class="hidden bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">Pembayaran Tunai</p>
                                    <p class="text-xs text-green-700 mt-1">Muzakki membayar langsung kepada amil/petugas. Transaksi akan langsung terverifikasi setelah disimpan.</p>
                                </div>
                            </div>
                        </div>

                        {{-- INFO TRANSFER BANK: tampilkan rekening masjid --}}
                        <div id="infoTransferSection" class="hidden">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-5">
                                <div class="flex items-start gap-3 mb-4">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800">Instruksi Transfer Bank</p>
                                        <p class="text-xs text-blue-700 mt-1">Muzakki mentransfer langsung ke rekening masjid berikut. Dana masuk 100% tanpa potongan.</p>
                                    </div>
                                </div>

                                {{-- Rekening Masjid --}}
                                @if($rekeningList->isNotEmpty())
                                <div class="space-y-2 mb-4">
                                    @foreach($rekeningList as $rekening)
                                    <div class="bg-white border border-blue-200 rounded-lg p-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-800">{{ $rekening->nama_bank }}</p>
                                            <p class="text-sm font-bold text-gray-900 tracking-wider mt-0.5">{{ $rekening->nomor_rekening }}</p>
                                            <p class="text-xs text-gray-500">a.n. {{ $rekening->nama_pemilik }}</p>
                                        </div>
                                        <button type="button" onclick="copyToClipboard('{{ $rekening->nomor_rekening }}')" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded-lg hover:bg-blue-100 transition-all flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
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

                                {{-- No Referensi Transfer --}}
                                <div>
                                    <label for="no_referensi_transfer" class="block text-xs font-medium text-gray-700 mb-1.5">Nomor Referensi Transfer (Opsional)</label>
                                    <input type="text" name="no_referensi_transfer" id="no_referensi_transfer" value="{{ old('no_referensi_transfer') }}" placeholder="Contoh: 123456789012" class="block w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                    <p class="mt-1 text-xs text-gray-500">Isi nomor referensi dari slip/bukti transfer jika ada</p>
                                </div>
                            </div>

                            {{-- Upload Bukti Transfer --}}
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti Transfer
                                    <span class="text-xs text-gray-500 font-normal ml-1">(Opsional namun disarankan)</span>
                                </label>
                                <div class="space-y-3">
                                    <div id="buktiTransferPreview" class="h-32 w-full rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-1 text-xs text-gray-500">Belum ada file dipilih</p>
                                        </div>
                                    </div>
                                    <input type="file" name="bukti_transfer" id="bukti_transfer_input" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewBuktiTransfer(this)">
                                    <label for="bukti_transfer_input" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12" />
                                        </svg>
                                        Pilih File Bukti Transfer
                                    </label>
                                    <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                                </div>
                            </div>

                            {{-- Info konfirmasi manual --}}
                            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-xs text-yellow-800"><strong>Perlu konfirmasi amil:</strong> Setelah muzakki transfer, amil akan memverifikasi dana masuk ke rekening masjid secara manual.</p>
                                </div>
                            </div>
                        </div>

                        {{-- INFO QRIS: tampilkan QRIS masjid --}}
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

                                {{-- Tampilkan QRIS Image jika ada --}}
                                @php
                                    $rekeningQris = $rekeningList->where('jenis', 'qris')->first() ?? $rekeningList->first();
                                @endphp
                                @if($rekeningQris && $rekeningQris->qris_image)
                                <div class="flex justify-center mb-4">
                                    <div class="bg-white p-3 rounded-xl border border-purple-200 inline-block">
                                        <img src="{{ Storage::url($rekeningQris->qris_image) }}" alt="QRIS Masjid" class="w-40 h-40 object-contain">
                                        <p class="text-xs text-center text-gray-500 mt-2">QRIS {{ $masjid->nama }}</p>
                                    </div>
                                </div>
                                @else
                                <div class="bg-white border border-purple-200 rounded-lg p-4 text-center mb-4">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <p class="text-xs text-gray-500">Tunjukkan QRIS masjid kepada muzakki untuk di-scan</p>
                                </div>
                                @endif

                                {{-- No Referensi QRIS --}}
                                <div>
                                    <label for="no_referensi_qris" class="block text-xs font-medium text-gray-700 mb-1.5">Nomor Referensi QRIS (Opsional)</label>
                                    <input type="text" name="no_referensi_transfer" id="no_referensi_qris" value="{{ old('no_referensi_transfer') }}" placeholder="Contoh: REF-20250217-001" class="block w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">
                                    <p class="mt-1 text-xs text-gray-500">Isi nomor referensi dari notifikasi QRIS jika ada</p>
                                </div>
                            </div>

                            {{-- Upload Bukti Scan QRIS --}}
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti Scan QRIS
                                    <span class="text-xs text-gray-500 font-normal ml-1">(Opsional namun disarankan)</span>
                                </label>
                                <div class="space-y-3">
                                    <div id="buktiQrisPreview" class="h-32 w-full rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-1 text-xs text-gray-500">Screenshot bukti pembayaran QRIS</p>
                                        </div>
                                    </div>
                                    <input type="file" name="bukti_transfer" id="bukti_qris_input" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewBuktiQris(this)">
                                    <label for="bukti_qris_input" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12" />
                                        </svg>
                                        Pilih Screenshot Bukti QRIS
                                    </label>
                                    <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                                </div>
                            </div>

                            {{-- Info konfirmasi manual --}}
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
                            <textarea name="keterangan" id="keterangan" rows="3" placeholder="Contoh: Zakat untuk program beasiswa, dll" class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- Ringkasan sebelum submit --}}
                        <div id="ringSummary" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Ringkasan Transaksi</p>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <span class="text-gray-500">Total Zakat</span>
                                <span class="font-semibold text-gray-900" id="ringSummaryJumlah">-</span>
                                <span class="text-gray-500">Metode</span>
                                <span class="font-semibold text-gray-900 capitalize" id="ringSummaryMetode">-</span>
                                <span class="text-gray-500">Status</span>
                                <span id="ringSummaryStatus" class="font-semibold">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
                    <button type="button" onclick="prevStep(2)" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Sebelumnya
                    </button>
                    <button type="submit" id="submitBtn" disabled class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30 opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
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
let currentStep    = 1;
let isPembayaranBeras = false;
let tipeZakatData  = @json($tipeZakatList ?? []);

// ============================================
// STEP NAVIGATION
// ============================================
function handleStep1Next() {
    if (!validateStep(1)) return;
    const isDijemput = isMetodeDijemput();
    if (isDijemput) {
        const btn = document.getElementById('step1NextBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
        document.getElementById('formTransaksi').submit();
    } else {
        showStep(2);
    }
}

function nextStep(step) {
    if (!validateStep(currentStep)) return;
    showStep(step);
}

function prevStep(step) {
    showStep(step);
}

function showStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    document.querySelector(`.step-content[data-step="${step}"]`).classList.remove('hidden');
    updateStepIndicators(step);
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepIndicators(activeStep) {
    document.querySelectorAll('.step-indicator').forEach((el, index) => {
        const stepNum  = index + 1;
        const stepLabel = el.nextElementSibling;
        el.classList.remove('bg-gray-200', 'text-gray-600', 'bg-primary', 'text-white', 'ring-4', 'ring-primary/30');

        if (stepNum < activeStep) {
            el.classList.add('bg-primary', 'text-white');
            stepLabel?.classList.replace('text-gray-500', 'text-primary');
        } else if (stepNum === activeStep) {
            el.classList.add('bg-primary', 'text-white', 'ring-4', 'ring-primary/30');
            stepLabel?.classList.replace('text-gray-500', 'text-primary');
        } else {
            el.classList.add('bg-gray-200', 'text-gray-600');
            stepLabel?.classList.replace('text-primary', 'text-gray-500');
        }
    });

    const line1 = document.getElementById('line1');
    const line2 = document.getElementById('line2');
    if (line1) line1.classList.toggle('bg-primary', activeStep >= 2);
    if (line1) line1.classList.toggle('bg-gray-300', activeStep < 2);
    if (line2) line2.classList.toggle('bg-primary', activeStep >= 3);
    if (line2) line2.classList.toggle('bg-gray-300', activeStep < 3);
}

// ============================================
// VALIDASI PER STEP
// ============================================
function validateStep(step) {
    if (step === 1) {
        if (!document.getElementById('muzakki_nama').value.trim()) {
            alert('Nama muzakki harus diisi');
            return false;
        }
        const metode = document.querySelector('input[name="metode_penerimaan"]:checked');
        if (!metode) { alert('Metode penerimaan harus dipilih'); return false; }

        if (metode.value === 'dijemput') {
            const alamat = document.getElementById('muzakki_alamat').value.trim();
            const lat    = document.getElementById('latitude').value.trim();
            const lon    = document.getElementById('longitude').value.trim();
            const amil   = document.getElementById('amil_id').value;
            if (!alamat || !lat || !lon || !amil) {
                alert('Untuk metode dijemput: alamat lengkap, koordinat, dan amil harus diisi');
                return false;
            }
        }
    }

    if (step === 2) {
        const jenisZakat = document.getElementById('jenis_zakat_id').value;
        const tipeZakat  = document.getElementById('tipe_zakat_id').value;
        if (!jenisZakat) { alert('Jenis Zakat harus dipilih'); return false; }
        if (!tipeZakat)  { alert('Tipe Zakat harus dipilih');  return false; }

        if (isPembayaranBeras) {
            const kg = parseFloat(document.getElementById('jumlah_beras_kg').value) || 0;
            if (kg <= 0) { alert('Jumlah beras harus diisi'); return false; }
        } else {
            const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
            if (jumlah <= 0) { alert('Jumlah pembayaran tidak valid. Silakan isi detail zakat.'); return false; }
        }
    }

    return true;
}

// ============================================
// HELPER
// ============================================
function isMetodeDijemput() {
    const metode = document.querySelector('input[name="metode_penerimaan"]:checked');
    return metode && metode.value === 'dijemput';
}

// ============================================
// UPDATE UI UNTUK METODE PENERIMAAN
// ============================================
function updateUIForMetodePenerimaan() {
    const isDijemput = isMetodeDijemput();
    document.getElementById('infoDijemputStep1').classList.toggle('hidden', !isDijemput);
    document.getElementById('step1NextBtnText').textContent = isDijemput ? 'Simpan Request Penjemputan' : 'Selanjutnya';
    document.getElementById('step2Indicator')?.classList.toggle('hidden', isDijemput);
    document.getElementById('step3Indicator')?.classList.toggle('hidden', isDijemput);
    document.getElementById('line1')?.classList.toggle('hidden', isDijemput);
    document.getElementById('line2')?.classList.toggle('hidden', isDijemput);
}

// ============================================
// METODE PENERIMAAN EVENTS
// ============================================
document.querySelectorAll('.metode-penerimaan-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const isDijemput = this.value === 'dijemput';

        document.querySelectorAll('.metode-penerimaan-option').forEach(option => {
            const checked = option.querySelector('input').checked;
            option.classList.toggle('border-primary', checked);
            option.classList.toggle('bg-primary-50', checked);
            option.classList.toggle('border-gray-200', !checked);
            option.classList.toggle('bg-white', !checked);
            const circle = option.querySelector('.w-5.h-5.rounded-full');
            const dot    = circle?.querySelector('.w-2\\.5');
            circle?.classList.toggle('border-primary', checked);
            circle?.classList.toggle('border-gray-300', !checked);
            dot?.classList.toggle('bg-primary', checked);
            dot?.classList.toggle('bg-transparent', !checked);
        });

        document.getElementById('alamatContainer').classList.toggle('hidden', !isDijemput);
        document.getElementById('amilContainer').classList.toggle('hidden', !isDijemput);

        const alamatReq  = document.getElementById('alamatRequired');
        const alamatField = document.getElementById('muzakki_alamat');
        const latField   = document.getElementById('latitude');
        const lonField   = document.getElementById('longitude');
        const amilField  = document.getElementById('amil_id');

        if (isDijemput) {
            alamatReq.classList.add('text-red-500'); alamatReq.classList.remove('text-gray-400');
            alamatField.required = latField.required = lonField.required = amilField.required = true;
        } else {
            alamatReq.classList.remove('text-red-500'); alamatReq.classList.add('text-gray-400');
            alamatField.required = latField.required = lonField.required = amilField.required = false;
        }

        updateUIForMetodePenerimaan();
    });
});

// ============================================
// GEOLOCATION
// ============================================
document.getElementById('getLocationBtn')?.addEventListener('click', function () {
    if (!navigator.geolocation) { alert('Browser tidak mendukung geolocation'); return; }

    const btn = this;
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Mengambil lokasi...';

    navigator.geolocation.getCurrentPosition(
        pos => {
            document.getElementById('latitude').value  = pos.coords.latitude.toFixed(6);
            document.getElementById('longitude').value = pos.coords.longitude.toFixed(6);
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Koordinat berhasil diambil';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        },
        err => { btn.disabled = false; btn.innerHTML = orig; alert('Gagal mendapatkan lokasi: ' + err.message); }
    );
});

// ============================================
// JENIS & TIPE ZAKAT
// ============================================
function loadTipeZakat() {
    const jenisId    = document.getElementById('jenis_zakat_id').value;
    const tipeSelect = document.getElementById('tipe_zakat_id');
    const tipeCont   = document.getElementById('tipeZakatContainer');

    tipeSelect.innerHTML = '<option value="">-- Pilih Tipe Zakat --</option>';
    document.getElementById('detailFitrahContainer').classList.add('hidden');
    document.getElementById('detailMalContainer').classList.add('hidden');
    document.getElementById('nisabInfoContainer').classList.add('hidden');
    document.getElementById('infoBerasStep2').classList.add('hidden');
    document.getElementById('step2NextBtn').classList.remove('hidden');
    document.getElementById('step2SaveBtn').classList.add('hidden');
    isPembayaranBeras = false;
    document.getElementById('is_pembayaran_beras').value = '0';
    resetJumlah();

    if (!jenisId) { tipeCont.classList.add('hidden'); return; }

    if (tipeZakatData[jenisId]?.length > 0) {
        tipeZakatData[jenisId].forEach(tipe => {
            const opt = document.createElement('option');
            opt.value = tipe.uuid;
            opt.textContent = tipe.nama;
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

function handleTipeZakatChange() {
    const tipeSelect  = document.getElementById('tipe_zakat_id');
    const selected    = tipeSelect.options[tipeSelect.selectedIndex];
    const jenisId     = document.getElementById('jenis_zakat_id').value;
    const infoBerasBox = document.getElementById('infoBerasStep2');
    const step2Next   = document.getElementById('step2NextBtn');
    const step2Save   = document.getElementById('step2SaveBtn');

    document.getElementById('detailFitrahContainer').classList.add('hidden');
    document.getElementById('detailMalContainer').classList.add('hidden');
    document.getElementById('nisabInfoContainer').classList.add('hidden');
    infoBerasBox.classList.add('hidden');
    step2Next.classList.remove('hidden');
    step2Save.classList.add('hidden');
    isPembayaranBeras = false;
    document.getElementById('is_pembayaran_beras').value = '0';
    resetJumlah();

    if (!selected?.value) return;

    const tipeNama = selected.textContent.toLowerCase();

    if (jenisId == 1) { // FITRAH
        document.getElementById('detailFitrahContainer').classList.remove('hidden');

        const jumlahBerasField = document.getElementById('jumlah_beras_kg');
        const hargaBerasField  = document.getElementById('harga_beras_per_kg');
        const nominalField     = document.getElementById('nominal_per_jiwa');
        const jiwaField        = document.getElementById('jumlah_jiwa');

        if (tipeNama.includes('beras')) {
            // BERAS: langsung simpan tanpa step 3
            isPembayaranBeras = true;
            document.getElementById('is_pembayaran_beras').value = '1';
            infoBerasBox.classList.remove('hidden');
            step2Next.classList.add('hidden');
            step2Save.classList.remove('hidden');

            jumlahBerasField.readOnly = false;
            hargaBerasField.readOnly  = true;
            hargaBerasField.value     = 0;
            nominalField.readOnly     = true;
            nominalField.value        = 0;
            if (!jiwaField.value || jiwaField.value == 0) jiwaField.value = 1;
            if (!jumlahBerasField.value || jumlahBerasField.value == 0) jumlahBerasField.value = 2.5;
        } else {
            // UANG: lanjut ke step 3
            jumlahBerasField.readOnly = true; jumlahBerasField.value = 0;
            hargaBerasField.readOnly  = true; hargaBerasField.value  = 0;
            nominalField.readOnly     = false;
            if (!nominalField.value || nominalField.value == 0) nominalField.value = 50000;
            if (!jiwaField.value || jiwaField.value == 0) jiwaField.value = 1;
        }
        hitungJumlah();

    } else if (jenisId == 2) { // MAL
        document.getElementById('detailMalContainer').classList.remove('hidden');
        document.getElementById('persentase_zakat').value = selected.dataset.persentase || 2.5;

        let nisabHtml = '';
        if (selected.dataset.nisabEmas > 0)      nisabHtml += `<div>• Nisab Emas: ${selected.dataset.nisabEmas} gram</div>`;
        if (selected.dataset.nisabPerak > 0)     nisabHtml += `<div>• Nisab Perak: ${selected.dataset.nisabPerak} gram</div>`;
        if (selected.dataset.nisabPertanian > 0) nisabHtml += `<div>• Nisab Pertanian: ${selected.dataset.nisabPertanian} kg</div>`;
        if (selected.dataset.nisabKambing > 0)   nisabHtml += `<div>• Nisab Kambing: minimal ${selected.dataset.nisabKambing} ekor</div>`;
        if (selected.dataset.nisabSapi > 0)      nisabHtml += `<div>• Nisab Sapi: minimal ${selected.dataset.nisabSapi} ekor</div>`;
        if (selected.dataset.requiresHaul === 'true') nisabHtml += `<div class="font-semibold mt-2">⚠️ Membutuhkan haul (1 tahun hijriyah)</div>`;

        if (nisabHtml) {
            document.getElementById('nisabInfoContent').innerHTML = nisabHtml;
            document.getElementById('nisabInfoContainer').classList.remove('hidden');
        }
    }
}

function resetJumlah() {
    document.getElementById('jumlah').value = 0;
    document.getElementById('totalJumlahDisplay').innerText = 'Rp 0';
    document.getElementById('detailPerhitungan').innerText  = '';
}

function hitungJumlah() {
    const jenisId    = document.getElementById('jenis_zakat_id').value;
    const tipeSelect = document.getElementById('tipe_zakat_id');
    const selected   = tipeSelect.options[tipeSelect.selectedIndex];
    if (!selected?.value) return;

    let total = 0, detail = '';

    if (jenisId == 1) {
        const tipeNama = selected.textContent.toLowerCase();
        if (tipeNama.includes('beras')) {
            const kg   = parseFloat(document.getElementById('jumlah_beras_kg').value) || 0;
            const jiwa = parseFloat(document.getElementById('jumlah_jiwa').value) || 1;
            total  = 0;
            detail = `${jiwa} jiwa × ${kg} kg beras = ${(jiwa * kg).toFixed(1)} kg beras`;
        } else {
            const jiwa    = parseFloat(document.getElementById('jumlah_jiwa').value) || 0;
            const nominal = parseFloat(document.getElementById('nominal_per_jiwa').value) || 0;
            total  = jiwa * nominal;
            detail = `${jiwa} jiwa × Rp ${formatNumber(nominal)} = Rp ${formatNumber(total)}`;
        }
    } else if (jenisId == 2) {
        const harta  = parseFloat(document.getElementById('nilai_harta').value) || 0;
        const persen = parseFloat(document.getElementById('persentase_zakat').value) || 2.5;
        total  = harta * (persen / 100);
        detail = `${persen}% dari Rp ${formatNumber(harta)} = Rp ${formatNumber(total)}`;
    }

    document.getElementById('totalJumlahDisplay').innerText = isPembayaranBeras ? detail : ('Rp ' + formatNumber(total));
    document.getElementById('jumlah').value = Math.round(total);
    document.getElementById('detailPerhitungan').innerText = detail;
}

// ============================================
// METODE PEMBAYARAN EVENTS (Step 3)
// ============================================
document.querySelectorAll('.metode-pembayaran-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const metode = this.value;

        // Update visual card
        document.querySelectorAll('.metode-pembayaran-card').forEach(card => {
            const checked = card.querySelector('input').checked;
            card.classList.toggle('border-primary',     checked);
            card.classList.toggle('bg-primary-50',      checked);
            card.classList.toggle('border-gray-200',    !checked);
            card.classList.toggle('bg-white',           !checked);
        });

        // Sembunyikan semua section
        document.getElementById('infoTunaiSection').classList.add('hidden');
        document.getElementById('infoTransferSection').classList.add('hidden');
        document.getElementById('infoQrisSection').classList.add('hidden');

        // Tampilkan sesuai pilihan
        if (metode === 'tunai') {
            document.getElementById('infoTunaiSection').classList.remove('hidden');
        } else if (metode === 'transfer') {
            document.getElementById('infoTransferSection').classList.remove('hidden');
        } else if (metode === 'qris') {
            document.getElementById('infoQrisSection').classList.remove('hidden');
        }

        // Update ringkasan
        updateRingSummary(metode);

        // Enable submit
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        submitBtn.classList.add('hover:from-primary-600', 'hover:to-primary-700');
    });
});

function updateRingSummary(metode) {
    const ringSummary = document.getElementById('ringSummary');
    const jumlah      = parseFloat(document.getElementById('jumlah').value) || 0;

    ringSummary.classList.remove('hidden');
    document.getElementById('ringSummaryJumlah').textContent = isPembayaranBeras
        ? document.getElementById('detailPerhitungan').textContent
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
// FORM SUBMIT HANDLER
// ============================================
document.getElementById('formTransaksi').addEventListener('submit', function (e) {
    const nama = document.getElementById('muzakki_nama').value.trim();
    if (!nama) { e.preventDefault(); alert('Nama muzakki harus diisi'); showStep(1); return; }

    if (isMetodeDijemput()) return true;

    if (isPembayaranBeras) {
        const jenisZakat = document.getElementById('jenis_zakat_id').value;
        const tipeZakat  = document.getElementById('tipe_zakat_id').value;
        const kg         = parseFloat(document.getElementById('jumlah_beras_kg').value) || 0;
        if (!jenisZakat || !tipeZakat || kg <= 0) {
            e.preventDefault(); alert('Data zakat fitrah beras harus lengkap'); showStep(2); return;
        }
        // Set metode pembayaran otomatis untuk beras
        if (!document.querySelector('input[name="metode_pembayaran"]')) {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'metode_pembayaran'; inp.value = 'tunai';
            this.appendChild(inp);
        }
        const btn = document.getElementById('step2SaveBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...'; }
        return true;
    }

    // Non-beras
    const jenisZakat = document.getElementById('jenis_zakat_id').value;
    const tipeZakat  = document.getElementById('tipe_zakat_id').value;
    const jumlah     = parseFloat(document.getElementById('jumlah').value) || 0;
    if (!jenisZakat) { e.preventDefault(); alert('Jenis Zakat harus dipilih'); showStep(2); return; }
    if (!tipeZakat)  { e.preventDefault(); alert('Tipe Zakat harus dipilih'); showStep(2); return; }
    if (jumlah <= 0) { e.preventDefault(); alert('Jumlah pembayaran tidak valid'); showStep(2); return; }

    const metode = document.querySelector('input[name="metode_pembayaran"]:checked');
    if (!metode)  { e.preventDefault(); alert('Metode pembayaran harus dipilih'); showStep(3); return; }

    // Sinkronkan no_referensi jika QRIS (pakai field berbeda tapi nama sama)
    if (metode.value === 'qris') {
        const refQris = document.getElementById('no_referensi_qris').value;
        if (refQris) document.getElementById('no_referensi_transfer').value = refQris;
    }

    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
    }

    return true;
});

// ============================================
// COPY TO CLIPBOARD
// ============================================
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Tampilkan notif singkat
        const el = document.createElement('div');
        el.textContent = 'Nomor rekening disalin!';
        el.className = 'fixed bottom-4 right-4 bg-gray-800 text-white text-sm px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2000);
    }).catch(() => {
        // Fallback
        const ta = document.createElement('textarea');
        ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
    });
}

// ============================================
// PREVIEW BUKTI
// ============================================
function previewBuktiTransfer(input) {
    const preview = document.getElementById('buktiTransferPreview');
    handlePreview(input, preview);
}

function previewBuktiQris(input) {
    const preview = document.getElementById('buktiQrisPreview');
    handlePreview(input, preview);
}

function handlePreview(input, previewEl) {
    if (input.files && input.files[0]) {
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            previewEl.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-contain rounded-xl" alt="Preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ============================================
// UTILITIES
// ============================================
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(Math.round(number || 0));
}

document.getElementById('sudah_haul')?.addEventListener('change', function () {
    document.getElementById('tanggalHaulContainer').classList.toggle('hidden', !this.checked);
});

// ============================================
// PAGE LOAD
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    updateUIForMetodePenerimaan();

    // Restore old values
    @if(old('jenis_zakat_id'))
        setTimeout(() => {
            document.getElementById('jenis_zakat_id').value = '{{ old("jenis_zakat_id") }}';
            loadTipeZakat();
            @if(old('tipe_zakat_id'))
                setTimeout(() => {
                    document.getElementById('tipe_zakat_id').value = '{{ old("tipe_zakat_id") }}';
                    handleTipeZakatChange();
                    @if(old('jumlah') > 0) hitungJumlah(); @endif
                }, 100);
            @endif
        }, 100);
    @endif

    // Trigger metode pembayaran jika sudah dipilih
    const selectedMetode = document.querySelector('.metode-pembayaran-radio:checked');
    if (selectedMetode) {
        setTimeout(() => selectedMetode.dispatchEvent(new Event('change')), 150);
    }
});
</script>
@endpush