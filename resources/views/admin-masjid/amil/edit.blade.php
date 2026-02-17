@extends('layouts.app')
@section('title', 'Edit Data Amil')
@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Data Amil</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi data amil</p>
                    </div>
                    <a href="{{ route('amil.show', $amil->uuid) }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </a>
                </div>
            </div>
            
            <form action="{{ route('amil.update', $amil->uuid) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    
                    <!-- SECTION: INFO MASJID -->
                    @if(auth()->user()->role === 'superadmin')
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Data Masjid
                            </h3>
                            <div>
                                <label for="masjid_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Masjid <span class="text-red-500">*</span>
                                </label>
                                <select name="masjid_id" id="masjid_id" required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('masjid_id') border-red-500 @enderror">
                                    <option value="">Pilih Masjid</option>
                                    @foreach($masjids as $masjid)
                                        <option value="{{ $masjid->id }}" {{ old('masjid_id', $amil->masjid_id) == $masjid->id ? 'selected' : '' }}>
                                            {{ $masjid->nama }} ({{ $masjid->kode_masjid }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('masjid_id')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="masjid_id" value="{{ $amil->masjid_id }}">
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-blue-700">
                                    Masjid: <strong>{{ $amil->masjid->nama ?? 'N/A' }}</strong>
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- SECTION: KODE AMIL (READ ONLY) -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Identitas Amil
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Amil
                            </label>
                            <div class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 font-medium">
                                {{ $amil->kode_amil }}
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Kode amil tidak dapat diubah</p>
                        </div>
                    </div>

                    <!-- SECTION: DATA PRIBADI -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $amil->nama_lengkap) }}" required
                                    placeholder="Masukkan nama lengkap amil"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nama_lengkap') border-red-500 @enderror">
                                @error('nama_lengkap')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kelamin" id="jenis_kelamin" required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('jenis_kelamin') border-red-500 @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $amil->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $amil->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tempat Lahir --}}
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $amil->tempat_lahir) }}" required
                                    placeholder="Kota tempat lahir"
                                    maxlength="100"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('tempat_lahir') border-red-500 @enderror">
                                @error('tempat_lahir')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $amil->tanggal_lahir->format('Y-m-d')) }}" required
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('tanggal_lahir') border-red-500 @enderror">
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat" id="alamat" rows="3" required
                                    placeholder="Masukkan alamat lengkap"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('alamat') border-red-500 @enderror">{{ old('alamat', $amil->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: KONTAK & FOTO -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kontak & Foto
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Telepon --}}
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="telepon" id="telepon" value="{{ old('telepon', $amil->telepon) }}" required
                                    placeholder="08123456789"
                                    maxlength="20"
                                    pattern="[0-9]{10,13}"
                                    title="Masukkan nomor telepon yang valid (10-13 digit)"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('telepon') border-red-500 @enderror">
                                @error('telepon')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $amil->email) }}" required
                                    placeholder="amil@example.com"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Foto --}}
                            <div class="md:col-span-2">
                                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Profil
                                </label>
                                
                                {{-- Current Photo --}}
                                @if($amil->foto)
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500 mb-2">Foto saat ini:</p>
                                        <div class="relative inline-block">
                                            <img src="{{ $amil->foto_url }}" alt="{{ $amil->nama_lengkap }}" 
                                                class="w-24 h-24 rounded-xl object-cover border border-gray-200">
                                            <div class="absolute top-0 right-0 -mt-2 -mr-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex items-center space-x-4">
                                    <input type="file" name="foto" id="foto" 
                                        accept="image/jpeg,image/jpg,image/png"
                                        class="block w-full text-sm text-gray-500 
                                               file:mr-4 file:py-2 file:px-4 
                                               file:rounded-lg file:border-0 
                                               file:text-sm file:font-medium 
                                               file:bg-primary file:text-white 
                                               hover:file:bg-primary-600 
                                               @error('foto') border-red-500 @enderror">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.
                                </p>
                                @error('foto')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                {{-- Preview New Foto --}}
                                <div id="foto-preview" class="mt-3 hidden">
                                    <p class="text-xs text-gray-500 mb-2">Preview foto baru:</p>
                                    <div class="relative inline-block">
                                        <div class="w-24 h-24 border border-gray-300 rounded-xl overflow-hidden">
                                            <img id="foto-preview-image" class="w-full h-full object-cover">
                                        </div>
                                        <button type="button" id="remove-foto" 
                                            class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: DATA TUGAS -->
                    <div class="pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Data Tugas
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tanggal Mulai Tugas --}}
                            <div>
                                <label for="tanggal_mulai_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai Tugas <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai_tugas" id="tanggal_mulai_tugas" 
                                    value="{{ old('tanggal_mulai_tugas', $amil->tanggal_mulai_tugas ? $amil->tanggal_mulai_tugas->format('Y-m-d') : '') }}" required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('tanggal_mulai_tugas') border-red-500 @enderror">
                                @error('tanggal_mulai_tugas')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal Selesai Tugas --}}
                            <div>
                                <label for="tanggal_selesai_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai Tugas
                                </label>
                                <input type="date" name="tanggal_selesai_tugas" id="tanggal_selesai_tugas" 
                                    value="{{ old('tanggal_selesai_tugas', $amil->tanggal_selesai_tugas ? $amil->tanggal_selesai_tugas->format('Y-m-d') : '') }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('tanggal_selesai_tugas') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika masih bertugas</p>
                                @error('tanggal_selesai_tugas')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('status') border-red-500 @enderror">
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" {{ old('status', $amil->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $amil->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="cuti" {{ old('status', $amil->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Wilayah Tugas --}}
                            <div>
                                <label for="wilayah_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Wilayah Tugas
                                </label>
                                <input type="text" name="wilayah_tugas" id="wilayah_tugas" value="{{ old('wilayah_tugas', $amil->wilayah_tugas) }}"
                                    placeholder="Contoh: RT 01, Kelurahan X"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('wilayah_tugas') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Opsional, bisa diisi untuk amil dengan tugas khusus wilayah</p>
                                @error('wilayah_tugas')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Keterangan --}}
                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan Tambahan
                                </label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    placeholder="Masukkan keterangan tambahan jika diperlukan"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $amil->keterangan) }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: KONFIRMASI -->
                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl animate-pulse">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.698-.833-2.464 0L4.338 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <p class="text-sm font-medium text-red-700">
                                    Terdapat kesalahan dalam pengisian form
                                </p>
                            </div>
                            <ul class="mt-2 ml-6 list-disc text-xs text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- SECTION: CATATAN -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-yellow-700">Catatan</p>
                        </div>
                        <ul class="mt-2 ml-6 list-disc text-xs text-yellow-600">
                            <li>Field dengan tanda (<span class="text-red-500">*</span>) wajib diisi</li>
                            <li>Perubahan data akan tercatat dalam log sistem</li>
                            <li>Pastikan data yang diisi sudah benar sebelum menyimpan</li>
                        </ul>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between space-y-3 space-y-reverse sm:space-y-0 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <div class="flex flex-col-reverse sm:flex-row gap-2">
                        <a href="{{ route('amil.index') }}"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <a href="{{ route('amil.show', $amil->uuid) }}"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Perbarui Data Amil
                    </button>
                </div>
            </form>
        </div>

        {{-- Activity Log (Optional) --}}
        @if(isset($amil->activities) && $amil->activities->count() > 0)
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Riwayat Perubahan</h3>
                <p class="text-xs text-gray-500 mt-1">Log aktivitas perubahan data</p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="space-y-3">
                    @foreach($amil->activities->take(5) as $activity)
                        <div class="flex items-start space-x-3 text-sm">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 mt-2 rounded-full bg-primary"></div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-900">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $activity->created_at->diffForHumans() }} • 
                                    {{ $activity->causer->name ?? 'System' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initForm();
    });

    function initForm() {
        setupFotoPreview();
        setupTanggalValidation();
    }

    function setupFotoPreview() {
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('foto-preview');
        const fotoPreviewImage = document.getElementById('foto-preview-image');
        const removeFotoBtn = document.getElementById('remove-foto');

        if (fotoInput) {
            fotoInput.addEventListener('change', handleFotoSelection);
        }

        if (removeFotoBtn) {
            removeFotoBtn.addEventListener('click', function() {
                fotoInput.value = '';
                if (fotoPreview) {
                    fotoPreview.classList.add('hidden');
                }
            });
        }
    }

    function handleFotoSelection(event) {
        const file = event.target.files[0];
        const fotoPreview = document.getElementById('foto-preview');
        const fotoPreviewImage = document.getElementById('foto-preview-image');

        if (!file) return;

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid. Harap pilih file JPG, JPEG, atau PNG.');
            event.target.value = '';
            return;
        }

        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File terlalu besar. Maksimal 2MB.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            if (fotoPreviewImage) {
                fotoPreviewImage.src = e.target.result;
            }
            if (fotoPreview) {
                fotoPreview.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    function setupTanggalValidation() {
        const mulaiTugasInput = document.getElementById('tanggal_mulai_tugas');
        const selesaiTugasInput = document.getElementById('tanggal_selesai_tugas');

        if (mulaiTugasInput && selesaiTugasInput) {
            mulaiTugasInput.addEventListener('change', function() {
                if (this.value) {
                    selesaiTugasInput.min = this.value;
                    
                    if (selesaiTugasInput.value && selesaiTugasInput.value < this.value) {
                        selesaiTugasInput.value = '';
                    }
                }
            });

            if (mulaiTugasInput.value) {
                selesaiTugasInput.min = mulaiTugasInput.value;
            }
        }
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memperbarui...';
            }
        });
    }
</script>
@endpush