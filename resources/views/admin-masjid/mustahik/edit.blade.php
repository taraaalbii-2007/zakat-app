@extends('layouts.app')
@section('title', 'Edit Data Mustahik')
@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white from-primary/5 to-primary/10">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Edit Mustahik</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui field yang diperlukan untuk mengedit data mustahik</p>
                
                {{-- Status Verifikasi --}}
                @if($mustahik->status_verifikasi === 'pending')
                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status: Pending (Menunggu Verifikasi)
                    </div>
                @elseif($mustahik->status_verifikasi === 'verified')
                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Status: Terverifikasi
                    </div>
                @elseif($mustahik->status_verifikasi === 'rejected')
                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Status: Ditolak
                    </div>
                @endif
                
                {{-- Info untuk Amil --}}
                @if(auth()->user()->peran === 'amil')
                    <div class="mt-2 text-xs text-gray-600">
                        @if($mustahik->created_by == auth()->id())
                            <span class="text-blue-600 font-medium">⚠️ Data ini dibuat oleh Anda.</span>
                        @elseif($mustahik->status_verifikasi === 'pending')
                            <span class="text-yellow-600 font-medium">⚠️ Data ini berstatus pending dan bisa Anda edit.</span>
                        @else
                            <span class="text-red-600 font-medium">⚠️ Anda hanya bisa mengedit data yang Anda buat atau data dengan status pending.</span>
                        @endif
                    </div>
                @endif
            </div>
            
            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mx-4 sm:mx-6 mt-4 animate-slide-down">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam pengisian form:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('mustahik.update', $mustahik->uuid) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                @method('PUT')
                
                {{-- Hidden fields untuk existing files --}}
                <input type="hidden" name="existing_foto_ktp" value="{{ $mustahik->foto_ktp }}">
                <input type="hidden" name="existing_foto_kk" value="{{ $mustahik->foto_kk }}">
                <input type="hidden" name="existing_foto_rumah" value="{{ $mustahik->foto_rumah }}">
                <input type="hidden" name="existing_dokumen_lainnya" value="{{ json_encode($mustahik->dokumen_lainnya ?? []) }}">
                
                {{-- Section 1 - Data Pribadi --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Data Pribadi
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- No Registrasi (Read Only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                No. Registrasi
                            </label>
                            <input type="text" value="{{ $mustahik->no_registrasi }}" disabled
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl bg-gray-50 cursor-not-allowed shadow-inner">
                        </div>

                        {{-- NIK & KK --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                    NIK
                                </label>
                                <input type="text" name="nik" id="nik" value="{{ old('nik', $mustahik->nik) }}"
                                    placeholder="16 digit NIK"
                                    maxlength="16"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nik') border-red-500 @enderror">
                                @error('nik')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kk" class="block text-sm font-medium text-gray-700 mb-2">
                                    No. KK
                                </label>
                                <input type="text" name="kk" id="kk" value="{{ old('kk', $mustahik->kk) }}"
                                    placeholder="16 digit No. KK"
                                    maxlength="16"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('kk') border-red-500 @enderror">
                                @error('kk')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $mustahik->nama_lengkap) }}"
                                placeholder="Masukkan nama lengkap"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin - Modern Radio Cards --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary/50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $mustahik->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 peer-checked:bg-primary peer-checked:text-white transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 peer-checked:text-primary">Laki-laki</span>
                                    </div>
                                    <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </label>
                                
                                <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary/50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $mustahik->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-pink-100 text-pink-600 peer-checked:bg-primary peer-checked:text-white transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-700 peer-checked:text-primary">Perempuan</span>
                                    </div>
                                    <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tempat & Tanggal Lahir --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tempat Lahir
                                </label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $mustahik->tempat_lahir) }}"
                                    placeholder="Kota kelahiran"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tempat_lahir') border-red-500 @enderror">
                                @error('tempat_lahir')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $mustahik->tanggal_lahir?->format('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_lahir') border-red-500 @enderror">
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                No. Telepon
                            </label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $mustahik->telepon) }}"
                                placeholder="08xxxxxxxxxx"
                                maxlength="20"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('telepon') border-red-500 @enderror">
                            @error('telepon')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2 - Alamat --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Alamat
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Alamat Lengkap --}}
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3"
                                placeholder="Masukkan alamat lengkap"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('alamat') border-red-500 @enderror">{{ old('alamat', $mustahik->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cascading Wilayah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="provinsi_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Provinsi
                                </label>
                                <select name="provinsi_kode" id="provinsi_kode"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('provinsi_kode') border-red-500 @enderror">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->code }}" {{ old('provinsi_kode', $mustahik->provinsi_kode) == $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('provinsi_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kota_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kota/Kabupaten
                                </label>
                                <div class="relative">
                                    <select name="kota_kode" id="kota_kode"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all disabled:bg-gray-100 disabled:cursor-not-allowed @error('kota_kode') border-red-500 @enderror"
                                        {{ old('provinsi_kode', $mustahik->provinsi_kode) ? '' : 'disabled' }}>
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->code }}" {{ old('kota_kode', $mustahik->kota_kode) == $city->code ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="kota-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p id="kota-hint" class="mt-1 text-xs text-gray-500 {{ old('provinsi_kode', $mustahik->provinsi_kode) ? 'hidden' : '' }}">
                                    Pilih provinsi terlebih dahulu
                                </p>
                                @error('kota_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="kecamatan_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kecamatan
                                </label>
                                <div class="relative">
                                    <select name="kecamatan_kode" id="kecamatan_kode"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all disabled:bg-gray-100 disabled:cursor-not-allowed @error('kecamatan_kode') border-red-500 @enderror"
                                        {{ old('kota_kode', $mustahik->kota_kode) ? '' : 'disabled' }}>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->code }}" {{ old('kecamatan_kode', $mustahik->kecamatan_kode) == $district->code ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="kecamatan-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p id="kecamatan-hint" class="mt-1 text-xs text-gray-500 {{ old('kota_kode', $mustahik->kota_kode) ? 'hidden' : '' }}">
                                    Pilih kota/kabupaten terlebih dahulu
                                </p>
                                @error('kecamatan_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kelurahan_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kelurahan/Desa
                                </label>
                                <div class="relative">
                                    <select name="kelurahan_kode" id="kelurahan_kode"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all disabled:bg-gray-100 disabled:cursor-not-allowed @error('kelurahan_kode') border-red-500 @enderror"
                                        {{ old('kecamatan_kode', $mustahik->kecamatan_kode) ? '' : 'disabled' }}>
                                        <option value="">-- Pilih Kelurahan/Desa --</option>
                                        @foreach($villages as $village)
                                            <option value="{{ $village->code }}" {{ old('kelurahan_kode', $mustahik->kelurahan_kode) == $village->code ? 'selected' : '' }}>
                                                {{ $village->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="kelurahan-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p id="kelurahan-hint" class="mt-1 text-xs text-gray-500 {{ old('kecamatan_kode', $mustahik->kecamatan_kode) ? 'hidden' : '' }}">
                                    Pilih kecamatan terlebih dahulu
                                </p>
                                @error('kelurahan_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- RT/RW & Kode Pos --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="rt_rw" class="block text-sm font-medium text-gray-700 mb-2">
                                    RT/RW
                                </label>
                                <input type="text" name="rt_rw" id="rt_rw" value="{{ old('rt_rw', $mustahik->rt_rw) }}"
                                    placeholder="001/002"
                                    maxlength="10"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('rt_rw') border-red-500 @enderror">
                                @error('rt_rw')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Pos
                                </label>
                                <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $mustahik->kode_pos) }}"
                                    placeholder="12345"
                                    maxlength="10"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('kode_pos') border-red-500 @enderror">
                                @error('kode_pos')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3 - Data Sosial Ekonomi --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                        Data Sosial Ekonomi
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Info Masjid (Read-only) --}}
                        @if(auth()->user()->masjid)
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-blue-900 mb-1">Masjid Terdaftar</p>
                                    <p class="text-sm font-semibold text-blue-700">{{ auth()->user()->masjid->nama }}</p>
                                    @if(auth()->user()->masjid->alamat)
                                    <p class="text-xs text-blue-600 mt-1">{{ auth()->user()->masjid->alamat }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Kategori Mustahik --}}
                        <div>
                            <label for="kategori_mustahik_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori Mustahik <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_mustahik_id" id="kategori_mustahik_id"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('kategori_mustahik_id') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_mustahik_id', $mustahik->kategori_mustahik_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_mustahik_id')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pekerjaan & Penghasilan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pekerjaan
                                </label>
                                <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $mustahik->pekerjaan) }}"
                                    placeholder="Pekerjaan saat ini"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('pekerjaan') border-red-500 @enderror">
                                @error('pekerjaan')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="penghasilan_perbulan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Penghasilan Per Bulan (Rp)
                                </label>
                                <input type="number" name="penghasilan_perbulan" id="penghasilan_perbulan" value="{{ old('penghasilan_perbulan', $mustahik->penghasilan_perbulan) }}"
                                    placeholder="0"
                                    min="0"
                                    step="1000"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('penghasilan_perbulan') border-red-500 @enderror">
                                @error('penghasilan_perbulan')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Jumlah Tanggungan & Status Rumah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="jumlah_tanggungan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Tanggungan
                                </label>
                                <input type="number" name="jumlah_tanggungan" id="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $mustahik->jumlah_tanggungan) }}"
                                    placeholder="0"
                                    min="0"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('jumlah_tanggungan') border-red-500 @enderror">
                                @error('jumlah_tanggungan')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status_rumah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status Rumah
                                </label>
                                <select name="status_rumah" id="status_rumah"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('status_rumah') border-red-500 @enderror">
                                    <option value="">-- Pilih Status Rumah --</option>
                                    <option value="milik_sendiri" {{ old('status_rumah', $mustahik->status_rumah) == 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="kontrak" {{ old('status_rumah', $mustahik->status_rumah) == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="menumpang" {{ old('status_rumah', $mustahik->status_rumah) == 'menumpang' ? 'selected' : '' }}>Menumpang</option>
                                    <option value="lainnya" {{ old('status_rumah', $mustahik->status_rumah) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('status_rumah')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kondisi Kesehatan --}}
                        <div>
                            <label for="kondisi_kesehatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Kondisi Kesehatan
                            </label>
                            <textarea name="kondisi_kesehatan" id="kondisi_kesehatan" rows="2"
                                placeholder="Kondisi kesehatan saat ini"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('kondisi_kesehatan') border-red-500 @enderror">{{ old('kondisi_kesehatan', $mustahik->kondisi_kesehatan) }}</textarea>
                            @error('kondisi_kesehatan')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="2"
                                placeholder="Catatan tambahan"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('catatan') border-red-500 @enderror">{{ old('catatan', $mustahik->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 4 - Upload Dokumen --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">4</span>
                        Upload Dokumen
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Grid 2 Kolom untuk Upload Foto --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            {{-- Foto KTP --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                        Foto KTP
                                    </div>
                                </label>
                                <div class="space-y-3">
                                    <div id="ktp-preview" class="h-40 w-full rounded-lg bg-white flex items-center justify-center overflow-hidden {{ $mustahik->foto_ktp ? 'border border-solid border-gray-300' : 'border-2 border-dashed border-gray-300' }} hover:border-primary/50 transition-colors">
                                        @if($mustahik->foto_ktp)
                                            <img src="{{ Storage::url($mustahik->foto_ktp) }}" class="h-full w-full object-cover" alt="KTP Preview">
                                        @else
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="file" name="foto_ktp" id="foto_ktp" accept="image/jpeg,image/png,image/jpg"
                                            class="hidden" onchange="previewImage(this, 'ktp-preview', 'remove-ktp')">
                                        <label for="foto_ktp"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $mustahik->foto_ktp ? 'Ganti Foto' : 'Pilih Foto' }}
                                        </label>
                                        <button type="button" id="remove-ktp" onclick="removeExistingImage('foto_ktp', 'ktp-preview', 'remove-ktp', 'remove_foto_ktp')" class="{{ $mustahik->foto_ktp ? '' : 'hidden' }} px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="remove_foto_ktp" id="remove_foto_ktp" value="0">
                                    <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks 2MB</p>
                                    @error('foto_ktp')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Foto KK --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Foto KK
                                    </div>
                                </label>
                                <div class="space-y-3">
                                    <div id="kk-preview" class="h-40 w-full rounded-lg bg-white flex items-center justify-center overflow-hidden {{ $mustahik->foto_kk ? 'border border-solid border-gray-300' : 'border-2 border-dashed border-gray-300' }} hover:border-primary/50 transition-colors">
                                        @if($mustahik->foto_kk)
                                            <img src="{{ Storage::url($mustahik->foto_kk) }}" class="h-full w-full object-cover" alt="KK Preview">
                                        @else
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="file" name="foto_kk" id="foto_kk" accept="image/jpeg,image/png,image/jpg"
                                            class="hidden" onchange="previewImage(this, 'kk-preview', 'remove-kk')">
                                        <label for="foto_kk"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $mustahik->foto_kk ? 'Ganti Foto' : 'Pilih Foto' }}
                                        </label>
                                        <button type="button" id="remove-kk" onclick="removeExistingImage('foto_kk', 'kk-preview', 'remove-kk', 'remove_foto_kk')" class="{{ $mustahik->foto_kk ? '' : 'hidden' }} px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="remove_foto_kk" id="remove_foto_kk" value="0">
                                    <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks 2MB</p>
                                    @error('foto_kk')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Foto Rumah --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        Foto Rumah
                                    </div>
                                </label>
                                <div class="space-y-3">
                                    <div id="rumah-preview" class="h-40 w-full rounded-lg bg-white flex items-center justify-center overflow-hidden {{ $mustahik->foto_rumah ? 'border border-solid border-gray-300' : 'border-2 border-dashed border-gray-300' }} hover:border-primary/50 transition-colors">
                                        @if($mustahik->foto_rumah)
                                            <img src="{{ Storage::url($mustahik->foto_rumah) }}" class="h-full w-full object-cover" alt="Rumah Preview">
                                        @else
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="file" name="foto_rumah" id="foto_rumah" accept="image/jpeg,image/png,image/jpg"
                                            class="hidden" onchange="previewImage(this, 'rumah-preview', 'remove-rumah')">
                                        <label for="foto_rumah"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $mustahik->foto_rumah ? 'Ganti Foto' : 'Pilih Foto' }}
                                        </label>
                                        <button type="button" id="remove-rumah" onclick="removeExistingImage('foto_rumah', 'rumah-preview', 'remove-rumah', 'remove_foto_rumah')" class="{{ $mustahik->foto_rumah ? '' : 'hidden' }} px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="remove_foto_rumah" id="remove_foto_rumah" value="0">
                                    <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maks 2MB</p>
                                    @error('foto_rumah')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Dokumen Lainnya --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Dokumen Lainnya
                                    </div>
                                </label>
                                <div class="space-y-3">
                                    {{-- Existing Documents --}}
                                    @if($mustahik->dokumen_lainnya && count($mustahik->dokumen_lainnya) > 0)
                                        <div id="existing-dokumen-container" class="mb-3 space-y-2">
                                            @foreach($mustahik->dokumen_lainnya as $index => $dokumen)
                                                <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200" id="existing-doc-{{ $index }}">
                                                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                                                        @if(str_ends_with($dokumen, '.pdf'))
                                                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                        <a href="{{ Storage::url($dokumen) }}" target="_blank" class="text-xs text-gray-700 hover:text-primary truncate">
                                                            {{ basename($dokumen) }}
                                                        </a>
                                                    </div>
                                                    <button type="button" onclick="removeExistingDokumen({{ $index }})" class="ml-2 text-red-600 hover:text-red-700 flex-shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                    <input type="hidden" name="existing_dokumen[]" value="{{ $dokumen }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="min-h-[160px] p-4 bg-white rounded-lg border-2 border-dashed border-gray-300 hover:border-primary/50 transition-colors">
                                        <input type="file" name="dokumen_lainnya[]" id="dokumen_lainnya" accept="image/jpeg,image/png,image/jpg,application/pdf"
                                            multiple class="hidden" onchange="handleMultipleFiles(this)">
                                        <label for="dokumen_lainnya"
                                            class="flex flex-col items-center justify-center cursor-pointer py-4">
                                            <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <span class="text-sm text-gray-600 font-medium">Klik untuk upload</span>
                                            <span class="text-xs text-gray-500 mt-1">atau drag & drop</span>
                                        </label>
                                        <div id="dokumen-list" class="mt-3 space-y-2"></div>
                                    </div>
                                    
                                    <input type="hidden" name="remove_dokumen_lainnya" id="remove_dokumen_lainnya" value="[]">
                                    
                                    <p class="text-xs text-gray-500">JPG, PNG, PDF. Maks 2MB/file. Max 5 file</p>
                                    @error('dokumen_lainnya')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('dokumen_lainnya.*')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi untuk Amil --}}
                @if(auth()->user()->peran === 'amil')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-yellow-800">
                                    <strong>Perhatian:</strong> Sebagai Amil, ketika Anda mengedit data mustahik, status akan otomatis menjadi <strong>Pending</strong> dan perlu diverifikasi ulang oleh Admin Masjid.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('mustahik.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let removedDokumen = [];

        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth >= 768) {
                document.getElementById('nama_lengkap')?.focus();
            }

            // Cascading Wilayah
            const provinsiSelect = document.getElementById('provinsi_kode');
            const kotaSelect = document.getElementById('kota_kode');
            const kecamatanSelect = document.getElementById('kecamatan_kode');
            const kelurahanSelect = document.getElementById('kelurahan_kode');

            // Enable/disable selects based on existing values
            if (provinsiSelect.value) {
                kotaSelect.disabled = false;
                document.getElementById('kota-hint').classList.add('hidden');
            }
            if (kotaSelect.value) {
                kecamatanSelect.disabled = false;
                document.getElementById('kecamatan-hint').classList.add('hidden');
            }
            if (kecamatanSelect.value) {
                kelurahanSelect.disabled = false;
                document.getElementById('kelurahan-hint').classList.add('hidden');
            }

            provinsiSelect.addEventListener('change', function() {
                fetchCities(this.value);
                kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                kecamatanSelect.disabled = true;
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                kelurahanSelect.disabled = true;
                document.getElementById('kecamatan-hint').classList.remove('hidden');
                document.getElementById('kelurahan-hint').classList.remove('hidden');
            });

            kotaSelect.addEventListener('change', function() {
                fetchDistricts(this.value);
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                kelurahanSelect.disabled = true;
                document.getElementById('kelurahan-hint').classList.remove('hidden');
            });

            kecamatanSelect.addEventListener('change', function() {
                fetchVillages(this.value);
            });

            function fetchCities(provinceCode) {
                if (!provinceCode) {
                    kotaSelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                    kotaSelect.disabled = true;
                    document.getElementById('kota-hint').classList.remove('hidden');
                    return;
                }

                document.getElementById('kota-loading').classList.remove('hidden');
                document.getElementById('kota-hint').classList.add('hidden');
                kotaSelect.disabled = true;

                fetch(`{{ url('/mustahik/api/cities') }}/${provinceCode}`)
                    .then(response => response.json())
                    .then(data => {
                        kotaSelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                        data.forEach(city => {
                            const option = new Option(city.name, city.code);
                            kotaSelect.add(option);
                        });
                        kotaSelect.disabled = false;
                        
                        // Set old value if exists
                        const oldKota = '{{ old('kota_kode', $mustahik->kota_kode) }}';
                        if (oldKota) {
                            kotaSelect.value = oldKota;
                            kotaSelect.dispatchEvent(new Event('change'));
                        }
                        
                        document.getElementById('kota-loading').classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kotaSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                        kotaSelect.disabled = false;
                        document.getElementById('kota-loading').classList.add('hidden');
                    });
            }

            function fetchDistricts(cityCode) {
                if (!cityCode) {
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    kecamatanSelect.disabled = true;
                    document.getElementById('kecamatan-hint').classList.remove('hidden');
                    return;
                }

                document.getElementById('kecamatan-loading').classList.remove('hidden');
                document.getElementById('kecamatan-hint').classList.add('hidden');
                kecamatanSelect.disabled = true;

                fetch(`{{ url('/mustahik/api/districts') }}/${cityCode}`)
                    .then(response => response.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                        data.forEach(district => {
                            const option = new Option(district.name, district.code);
                            kecamatanSelect.add(option);
                        });
                        kecamatanSelect.disabled = false;
                        
                        // Set old value if exists
                        const oldKecamatan = '{{ old('kecamatan_kode', $mustahik->kecamatan_kode) }}';
                        if (oldKecamatan) {
                            kecamatanSelect.value = oldKecamatan;
                            kecamatanSelect.dispatchEvent(new Event('change'));
                        }
                        
                        document.getElementById('kecamatan-loading').classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kecamatanSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                        kecamatanSelect.disabled = false;
                        document.getElementById('kecamatan-loading').classList.add('hidden');
                    });
            }

            function fetchVillages(districtCode) {
                if (!districtCode) {
                    kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                    kelurahanSelect.disabled = true;
                    document.getElementById('kelurahan-hint').classList.remove('hidden');
                    return;
                }

                document.getElementById('kelurahan-loading').classList.remove('hidden');
                document.getElementById('kelurahan-hint').classList.add('hidden');
                kelurahanSelect.disabled = true;

                fetch(`{{ url('/mustahik/api/villages') }}/${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                        data.forEach(village => {
                            const option = new Option(village.name, village.code);
                            kelurahanSelect.add(option);
                        });
                        kelurahanSelect.disabled = false;
                        
                        // Set old value if exists
                        const oldKelurahan = '{{ old('kelurahan_kode', $mustahik->kelurahan_kode) }}';
                        if (oldKelurahan) {
                            kelurahanSelect.value = oldKelurahan;
                        }
                        
                        document.getElementById('kelurahan-loading').classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        kelurahanSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                        kelurahanSelect.disabled = false;
                        document.getElementById('kelurahan-loading').classList.add('hidden');
                    });
            }

            // Load initial data
            const oldProvinsi = '{{ old('provinsi_kode', $mustahik->provinsi_kode) }}';
            if (oldProvinsi) {
                fetchCities(oldProvinsi);
            }
        });

        function previewImage(input, previewId, removeBtnId) {
            const preview = document.getElementById(previewId);
            const removeBtn = document.getElementById(removeBtnId);
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    input.value = '';
                    return;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPG, JPEG, atau PNG');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-cover" alt="Preview">`;
                    preview.classList.remove('border-dashed', 'border-2');
                    preview.classList.add('border-solid', 'border');
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeExistingImage(inputId, previewId, removeBtnId, hiddenInputId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const removeBtn = document.getElementById(removeBtnId);
            const hiddenInput = document.getElementById(hiddenInputId);
            
            input.value = '';
            preview.innerHTML = `
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            `;
            preview.classList.add('border-dashed', 'border-2');
            preview.classList.remove('border-solid', 'border');
            removeBtn.classList.add('hidden');
            hiddenInput.value = '1';
            
            showToast('Foto berhasil dihapus. Klik Simpan Perubahan untuk menyimpan perubahan.');
        }

        function removeExistingDokumen(index) {
            if (confirm('Hapus dokumen ini?')) {
                removedDokumen.push(index);
                document.getElementById('remove_dokumen_lainnya').value = JSON.stringify(removedDokumen);
                document.getElementById('existing-doc-' + index).remove();
                
                // Check if all existing docs are removed
                const existingDocs = document.querySelectorAll('[id^="existing-doc-"]');
                if (existingDocs.length === 0) {
                    document.getElementById('existing-dokumen-container')?.remove();
                }
                
                showToast('Dokumen berhasil dihapus dari daftar. Klik Simpan Perubahan untuk menyimpan perubahan.');
            }
        }

        function handleMultipleFiles(input) {
            const container = document.getElementById('dokumen-list');
            container.innerHTML = '';
            
            if (input.files.length > 5) {
                alert('Maksimal 5 file');
                input.value = '';
                return;
            }
            
            Array.from(input.files).forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} melebihi 2MB`);
                    input.value = '';
                    container.innerHTML = '';
                    return;
                }
                
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-200';
                div.innerHTML = `
                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                        ${file.type.includes('pdf') ? 
                            `<svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>` :
                            `<svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>`
                        }
                        <span class="text-xs text-gray-700 truncate">${file.name}</span>
                    </div>
                    <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${(file.size / 1024).toFixed(1)} KB</span>
                `;
                container.appendChild(div);
            });
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg z-50 animate-slide-in-right flex items-center space-x-2';
            toast.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush