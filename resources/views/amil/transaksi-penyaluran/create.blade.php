{{-- resources/views/amil/transaksi-penyaluran/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Transaksi Penyaluran Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Transaksi Penyaluran Zakat</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi data mustahik dan detail penyaluran zakat</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('transaksi-penyaluran.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
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

        <form id="formPenyaluran" action="{{ route('transaksi-penyaluran.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
            @csrf
            <input type="hidden" name="no_transaksi" value="{{ $noTransaksiPreview }}">

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

            <div class="space-y-6 sm:space-y-8">

                {{-- ===========================
                     BAGIAN 1: MUSTAHIK
                     =========================== --}}
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Pilih Mustahik
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        {{-- Mustahik --}}
                        <div class="sm:col-span-2">
                            <label for="mustahik_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mustahik (Penerima Zakat) <span class="text-red-500">*</span>
                            </label>
                            <select name="mustahik_id" id="mustahik_id" onchange="onMustahikChange()"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('mustahik_id') border-red-500 @enderror">
                                <option value="">-- Pilih Mustahik --</option>
                                @foreach($mustahikList as $mustahik)
                                    <option value="{{ $mustahik->id }}"
                                        data-kategori-id="{{ $mustahik->kategori_mustahik_id }}"
                                        data-kategori-nama="{{ $mustahik->kategoriMustahik->nama ?? '-' }}"
                                        data-telepon="{{ $mustahik->telepon }}"
                                        data-alamat="{{ $mustahik->alamat_lengkap }}"
                                        {{ old('mustahik_id') == $mustahik->id ? 'selected' : '' }}>
                                        {{ $mustahik->nama_lengkap }}
                                        @if($mustahik->kategoriMustahik) — {{ $mustahik->kategoriMustahik->nama }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('mustahik_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            @if($mustahikList->isEmpty())
                                <p class="mt-1 text-xs text-amber-600">
                                    Belum ada mustahik terverifikasi.
                                    <a href="{{ route('mustahik.create') }}" class="underline font-medium">Tambah mustahik</a>
                                </p>
                            @endif
                        </div>

                        {{-- Kategori Mustahik (snapshot) --}}
                        <div>
                            <label for="kategori_mustahik_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori Mustahik <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500 font-normal ml-1">(snapshot saat transaksi)</span>
                            </label>
                            <select name="kategori_mustahik_id" id="kategori_mustahik_id"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('kategori_mustahik_id') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriMustahikList as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_mustahik_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_mustahik_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info mustahik (auto-fill dari pilihan) --}}
                        <div id="mustahikInfoBox" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-3">
                            <p class="text-xs font-semibold text-blue-800 mb-1">Info Mustahik</p>
                            <div class="text-xs text-blue-700 space-y-0.5">
                                <p id="mustahikInfoTelepon"></p>
                                <p id="mustahikInfoAlamat"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===========================
                     BAGIAN 2: DETAIL PENYALURAN
                     =========================== --}}
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Detail Penyaluran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                        {{-- Tanggal Penyaluran --}}
                        <div>
                            <label for="tanggal_penyaluran" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Penyaluran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_penyaluran" id="tanggal_penyaluran"
                                value="{{ old('tanggal_penyaluran', $tanggalHariIni) }}"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('tanggal_penyaluran') border-red-500 @enderror">
                            @error('tanggal_penyaluran')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Waktu Penyaluran --}}
                        <div>
                            <label for="waktu_penyaluran" class="block text-sm font-medium text-gray-700 mb-2">Waktu Penyaluran</label>
                            <input type="time" name="waktu_penyaluran" id="waktu_penyaluran"
                                value="{{ old('waktu_penyaluran') }}"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                        </div>

                        {{-- Periode (format YYYY-MM) --}}
                        <div>
                            <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">
                                Periode
                                <span class="text-xs text-gray-500 font-normal ml-1">(untuk zakat fitrah/periodik)</span>
                            </label>
                            <input type="month" name="periode" id="periode"
                                value="{{ old('periode') }}"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('periode') border-red-500 @enderror">
                            @error('periode')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Zakat --}}
                        <div>
                            <label for="jenis_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Jenis Zakat</label>
                            <select name="jenis_zakat_id" id="jenis_zakat_id"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                <option value="">-- Pilih Jenis Zakat (Opsional) --</option>
                                @foreach($jenisZakatList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Program Zakat --}}
                        <div>
                            <label for="program_zakat_id" class="block text-sm font-medium text-gray-700 mb-2">Program Zakat</label>
                            <select name="program_zakat_id" id="program_zakat_id"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                <option value="">-- Pilih Program (Opsional) --</option>
                                @foreach($programZakatList as $program)
                                    <option value="{{ $program->id }}" {{ old('program_zakat_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Amil --}}
                        <div>
                            <label for="amil_id" class="block text-sm font-medium text-gray-700 mb-2">Amil yang Menyalurkan</label>
                            <select name="amil_id" id="amil_id"
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                <option value="">-- Pilih Amil (Opsional) --</option>
                                @foreach($amilList as $amil)
                                    <option value="{{ $amil->id }}" {{ old('amil_id') == $amil->id ? 'selected' : '' }}>
                                        {{ $amil->nama_lengkap }} ({{ $amil->kode_amil }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ===========================
                     BAGIAN 3: METODE & NOMINAL
                     =========================== --}}
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                        Metode & Nominal Penyaluran
                    </h3>

                    <div class="space-y-4 sm:space-y-6">

                        {{-- Pilih Metode Penyaluran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Metode Penyaluran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                {{-- Tunai --}}
                                <label class="metode-penyaluran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_penyaluran') == 'tunai' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_penyaluran" value="tunai" class="hidden metode-radio" {{ old('metode_penyaluran', 'tunai') == 'tunai' ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Tunai</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Serahkan langsung</span>
                                </label>

                                {{-- Transfer --}}
                                <label class="metode-penyaluran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_penyaluran') == 'transfer' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_penyaluran" value="transfer" class="hidden metode-radio" {{ old('metode_penyaluran') == 'transfer' ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4m-9 4v10" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Transfer Bank</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Transfer ke rekening mustahik</span>
                                </label>

                                {{-- Barang --}}
                                <label class="metode-penyaluran-card relative flex flex-col items-center p-4 rounded-xl border cursor-pointer transition-all {{ old('metode_penyaluran') == 'barang' ? 'border-primary bg-primary-50' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300' }}">
                                    <input type="radio" name="metode_penyaluran" value="barang" class="hidden metode-radio" {{ old('metode_penyaluran') == 'barang' ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Barang</span>
                                    <span class="text-xs text-gray-500 mt-1 text-center">Penyaluran in-kind</span>
                                </label>
                            </div>
                            @error('metode_penyaluran')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nominal (tunai / transfer) --}}
                        <div id="nominalSection">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Penyaluran (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                <input type="number" name="jumlah" id="jumlah"
                                    value="{{ old('jumlah', 0) }}"
                                    min="0" step="1000"
                                    class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jumlah') border-red-500 @enderror">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Jumlah PENUH yang diterima mustahik, tanpa potongan apapun.</p>
                            @error('jumlah')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Detail Barang (khusus metode barang) --}}
                        <div id="barangSection" class="{{ old('metode_penyaluran') === 'barang' ? '' : 'hidden' }} space-y-4">
                            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-orange-700">Penyaluran barang (in-kind). Isi deskripsi barang dan nilai estimasinya untuk pencatatan.</p>
                                </div>
                            </div>
                            <div>
                                <label for="detail_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                    Detail Barang <span class="text-red-500">*</span>
                                </label>
                                <textarea name="detail_barang" id="detail_barang" rows="3"
                                    placeholder="Contoh: Paket sembako (beras 5kg, gula 1kg, minyak 1L, kecap 1 botol)"
                                    class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400 @error('detail_barang') border-red-500 @enderror">{{ old('detail_barang') }}</textarea>
                                @error('detail_barang')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nilai_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai Estimasi Barang (Rp) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                    <input type="number" name="nilai_barang" id="nilai_barang"
                                        value="{{ old('nilai_barang') }}"
                                        min="0" step="1000"
                                        class="block w-full pl-12 pr-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('nilai_barang') border-red-500 @enderror">
                                </div>
                                @error('nilai_barang')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ===========================
                     BAGIAN 4: DOKUMENTASI
                     =========================== --}}
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">4</span>
                        Dokumentasi & Catatan
                        <span class="text-xs text-gray-400 font-normal ml-2">(opsional)</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                        {{-- Foto Bukti --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti Penyerahan</label>
                            <div class="space-y-3">
                                <div id="fotoBuktiPreview" class="h-32 w-full rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-1 text-xs text-gray-500">Belum ada foto dipilih</p>
                                    </div>
                                </div>
                                <input type="file" name="foto_bukti" id="foto_bukti_input" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewFoto(this, 'fotoBuktiPreview')">
                                <label for="foto_bukti_input" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12" />
                                    </svg>
                                    Pilih Foto Bukti
                                </label>
                                <p class="text-xs text-gray-500">JPG, PNG. Maks 2MB</p>
                            </div>
                        </div>

                        {{-- Tanda Tangan Digital --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Digital Mustahik</label>
                            <div class="space-y-3">
                                <div id="tandaTanganPreview" class="h-32 w-full rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <p class="mt-1 text-xs text-gray-500">Tanda tangan mustahik</p>
                                    </div>
                                </div>
                                <input type="file" name="tanda_tangan" id="tanda_tangan_input" accept="image/jpeg,image/png,image/jpg,image/svg+xml" class="hidden" onchange="previewFoto(this, 'tandaTanganPreview')">
                                <label for="tanda_tangan_input" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12" />
                                    </svg>
                                    Upload Tanda Tangan
                                </label>
                                <p class="text-xs text-gray-500">PNG/JPG/SVG. Maks 2MB</p>
                            </div>
                        </div>

                        {{-- Foto Dokumentasi (multiple) --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Dokumentasi (bisa lebih dari 1)</label>
                            <input type="file" name="foto_dokumentasi[]" id="foto_dokumentasi_input" accept="image/jpeg,image/png,image/jpg" class="hidden" multiple onchange="previewMultipleFoto(this)">
                            <label for="foto_dokumentasi_input" class="inline-flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4-4 4m4-4v12" />
                                </svg>
                                Pilih Foto Dokumentasi
                            </label>
                            <div id="fotoDokPreviewContainer" class="mt-3 grid grid-cols-4 sm:grid-cols-6 gap-2 hidden"></div>
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG. Maks 2MB per foto.</p>
                        </div>

                        {{-- Keterangan --}}
                        <div class="sm:col-span-2">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan / Catatan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                placeholder="Catatan tambahan mengenai penyaluran ini..."
                                class="block w-full px-4 py-3 text-sm sm:text-base border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all placeholder:text-gray-400">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- INFO BOX: Draft --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800">Proses Approval</p>
                            <p class="text-xs text-amber-700 mt-1">
                                Transaksi akan disimpan sebagai <strong>Draft</strong> dan menunggu persetujuan Admin Masjid.
                                Setelah disetujui, Anda perlu mengkonfirmasi bahwa dana/barang sudah diserahkan ke mustahik.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('transaksi-penyaluran.index') }}"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Metode Penyaluran ──
document.querySelectorAll('.metode-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.metode-penyaluran-card').forEach(card => {
            const checked = card.querySelector('input').checked;
            card.classList.toggle('border-primary',  checked);
            card.classList.toggle('bg-primary-50',   checked);
            card.classList.toggle('border-gray-200', !checked);
            card.classList.toggle('bg-white',        !checked);
        });

        const isBarang = this.value === 'barang';
        document.getElementById('barangSection').classList.toggle('hidden', !isBarang);
        document.getElementById('nominalSection').classList.toggle('hidden', isBarang);
        if (isBarang) {
            document.getElementById('jumlah').value = 0;
        }
    });
});

// Inisialisasi tampilan awal
(function () {
    const checked = document.querySelector('.metode-radio:checked');
    if (checked) {
        const isBarang = checked.value === 'barang';
        document.getElementById('barangSection').classList.toggle('hidden', !isBarang);
        document.getElementById('nominalSection').classList.toggle('hidden', isBarang);
        document.querySelectorAll('.metode-penyaluran-card').forEach(card => {
            const c = card.querySelector('input').checked;
            card.classList.toggle('border-primary',  c);
            card.classList.toggle('bg-primary-50',   c);
            card.classList.toggle('border-gray-200', !c);
            card.classList.toggle('bg-white',        !c);
        });
    }
})();

// ── Auto-fill kategori dari mustahik ──
function onMustahikChange() {
    const sel     = document.getElementById('mustahik_id');
    const opt     = sel.options[sel.selectedIndex];
    const katId   = opt.dataset.kategoriId;
    const telepon = opt.dataset.telepon;
    const alamat  = opt.dataset.alamat;
    const info    = document.getElementById('mustahikInfoBox');

    if (katId) {
        const katSelect = document.getElementById('kategori_mustahik_id');
        Array.from(katSelect.options).forEach(o => o.selected = (o.value == katId));
    }

    if (telepon || alamat) {
        info.classList.remove('hidden');
        document.getElementById('mustahikInfoTelepon').textContent = telepon ? 'Telepon: ' + telepon : '';
        document.getElementById('mustahikInfoAlamat').textContent  = alamat  ? 'Alamat: '  + alamat  : '';
    } else {
        info.classList.add('hidden');
    }
}

// ── Preview Foto ──
function previewFoto(input, previewId) {
    const el = document.getElementById(previewId);
    if (!input.files?.[0]) return;
    if (input.files[0].size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB'); input.value = ''; return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        el.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-contain rounded-xl" alt="Preview">`;
    };
    reader.readAsDataURL(input.files[0]);
}

function previewMultipleFoto(input) {
    const container = document.getElementById('fotoDokPreviewContainer');
    container.innerHTML = '';
    if (!input.files?.length) { container.classList.add('hidden'); return; }

    container.classList.remove('hidden');
    Array.from(input.files).forEach(file => {
        if (file.size > 2 * 1024 * 1024) return;
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'aspect-square rounded-lg overflow-hidden border border-gray-200';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// ── Form Submit ──
document.getElementById('formPenyaluran').addEventListener('submit', function (e) {
    const mustahik = document.getElementById('mustahik_id').value;
    const kategori = document.getElementById('kategori_mustahik_id').value;
    const metode   = document.querySelector('.metode-radio:checked')?.value;

    if (!mustahik) { e.preventDefault(); alert('Pilih mustahik terlebih dahulu.'); return; }
    if (!kategori) { e.preventDefault(); alert('Pilih kategori mustahik.'); return; }
    if (!metode)   { e.preventDefault(); alert('Pilih metode penyaluran.'); return; }

    if (metode !== 'barang') {
        const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
        if (jumlah <= 0) { e.preventDefault(); alert('Jumlah penyaluran harus lebih dari 0.'); return; }
    } else {
        const detail = document.getElementById('detail_barang').value.trim();
        const nilai  = parseFloat(document.getElementById('nilai_barang').value) || 0;
        if (!detail) { e.preventDefault(); alert('Detail barang harus diisi.'); return; }
        if (nilai <= 0) { e.preventDefault(); alert('Nilai estimasi barang harus diisi.'); return; }
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
});

// Init mustahik info jika ada old value
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('mustahik_id').value) {
        onMustahikChange();
    }
});
</script>
@endpush