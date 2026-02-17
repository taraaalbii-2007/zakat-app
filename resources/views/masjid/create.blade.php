@extends('layouts.app')
@section('title', 'Tambah Data Masjid')
@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Tambah Masjid</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi field yang diperlukan untuk menambahkan data masjid</p>
            </div>
            <form action="{{ route('masjid.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                <div class="space-y-6">
                    <!-- SECTION: DATA ADMIN MASJID -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Admin / Penanggung Jawab Masjid
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama Admin --}}
                            <div>
                                <label for="admin_nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Admin
                                </label>
                                <input type="text" name="admin_nama" id="admin_nama" value="{{ old('admin_nama') }}"
                                    placeholder="Masukkan nama admin/ketua DKM"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('admin_nama') border-red-500 @enderror">
                                @error('admin_nama')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Telepon Admin --}}
                            <div>
                                <label for="admin_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon Admin
                                </label>
                                <input type="text" name="admin_telepon" id="admin_telepon" value="{{ old('admin_telepon') }}"
                                    placeholder="08123456789"
                                    maxlength="20"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('admin_telepon') border-red-500 @enderror">
                                @error('admin_telepon')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email Admin --}}
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Admin
                                </label>
                                <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}"
                                    placeholder="admin.masjid@example.com"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('admin_email') border-red-500 @enderror">
                                @error('admin_email')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Foto Admin --}}
                            <div>
                                <label for="admin_foto" class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Admin
                                </label>
                                <input type="file" name="admin_foto" id="admin_foto" 
                                    accept="image/jpeg,image/jpg,image/png"
                                    class="block w-full text-sm text-gray-500 
                                           file:mr-4 file:py-2 file:px-4 
                                           file:rounded-lg file:border-0 
                                           file:text-sm file:font-medium 
                                           file:bg-primary file:text-white 
                                           hover:file:bg-primary-600 
                                           @error('admin_foto') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: JPG, JPEG, PNG. Maksimal 2MB.
                                </p>
                                @error('admin_foto')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                {{-- Preview Foto Admin --}}
                                <div id="admin-foto-preview" class="mt-3 hidden">
                                    <p class="text-xs text-gray-500 mb-2">Preview:</p>
                                    <div class="relative w-24 h-24 border border-gray-300 rounded-xl overflow-hidden">
                                        <img id="admin-foto-preview-image" class="w-full h-full object-cover">
                                        <button type="button" id="remove-admin-foto" 
                                            class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: DATA SEJARAH MASJID -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Data Sejarah Masjid
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Sejarah --}}
                            <div class="md:col-span-2">
                                <label for="sejarah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sejarah Berdiri
                                </label>
                                <textarea name="sejarah" id="sejarah" rows="4"
                                    placeholder="Ceritakan sejarah berdirinya masjid..."
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('sejarah') border-red-500 @enderror">{{ old('sejarah') }}</textarea>
                                @error('sejarah')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tahun Berdiri --}}
                            <div>
                                <label for="tahun_berdiri" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun Berdiri
                                </label>
                                <input type="number" name="tahun_berdiri" id="tahun_berdiri" 
                                    value="{{ old('tahun_berdiri') }}"
                                    placeholder="1990"
                                    min="1000"
                                    max="{{ date('Y') + 1 }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('tahun_berdiri') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: 4 digit (contoh: 1990)</p>
                                @error('tahun_berdiri')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Pendiri --}}
                            <div>
                                <label for="pendiri" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Pendiri
                                </label>
                                <input type="text" name="pendiri" id="pendiri" value="{{ old('pendiri') }}"
                                    placeholder="Nama pendiri masjid"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('pendiri') border-red-500 @enderror">
                                @error('pendiri')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kapasitas Jamaah --}}
                            <div>
                                <label for="kapasitas_jamaah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kapasitas Jamaah
                                </label>
                                <input type="number" name="kapasitas_jamaah" id="kapasitas_jamaah" 
                                    value="{{ old('kapasitas_jamaah') }}"
                                    placeholder="500"
                                    min="0"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('kapasitas_jamaah') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Dalam satuan orang</p>
                                @error('kapasitas_jamaah')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: DATA MASJID -->
                    <div class="pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Data Utama Masjid
                        </h3>
                        
                        {{-- Nama Masjid --}}
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Masjid <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                placeholder="Masukkan nama masjid"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3"
                                placeholder="Masukkan alamat lengkap masjid"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bagian Wilayah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            {{-- Provinsi --}}
                            <div>
                                <label for="provinsi_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select name="provinsi_kode" id="provinsi_kode"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('provinsi_kode') border-red-500 @enderror">
                                    <option value="">Pilih Provinsi</option>
                                    @isset($provinces)
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->code }}" {{ old('provinsi_kode') == $province->code ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('provinsi_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kota/Kabupaten --}}
                            <div>
                                <label for="kota_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <select name="kota_kode" id="kota_kode"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('kota_kode') border-red-500 @enderror"
                                    {{ !old('provinsi_kode') ? 'disabled' : '' }}>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                    @if(old('kota_kode') && old('provinsi_kode'))
                                        <option value="{{ old('kota_kode') }}" selected>{{ old('kota_kode') }}</option>
                                    @endif
                                </select>
                                @error('kota_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kecamatan --}}
                            <div>
                                <label for="kecamatan_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kecamatan
                                </label>
                                <select name="kecamatan_kode" id="kecamatan_kode"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('kecamatan_kode') border-red-500 @enderror"
                                    {{ !old('kota_kode') ? 'disabled' : '' }}>
                                    <option value="">Pilih Kecamatan</option>
                                    @if(old('kecamatan_kode') && old('kota_kode'))
                                        <option value="{{ old('kecamatan_kode') }}" selected>{{ old('kecamatan_kode') }}</option>
                                    @endif
                                </select>
                                @error('kecamatan_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kelurahan --}}
                            <div>
                                <label for="kelurahan_kode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kelurahan/Desa
                                </label>
                                <select name="kelurahan_kode" id="kelurahan_kode"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('kelurahan_kode') border-red-500 @enderror"
                                    {{ !old('kecamatan_kode') ? 'disabled' : '' }}>
                                    <option value="">Pilih Kelurahan/Desa</option>
                                    @if(old('kelurahan_kode') && old('kecamatan_kode'))
                                        <option value="{{ old('kelurahan_kode') }}" selected>{{ old('kelurahan_kode') }}</option>
                                    @endif
                                </select>
                                @error('kelurahan_kode')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kode Pos, Telepon, Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Pos
                                </label>
                                <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                                    placeholder="12345"
                                    maxlength="10"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('kode_pos') border-red-500 @enderror">
                                @error('kode_pos')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon Masjid
                                </label>
                                <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                                    placeholder="(021) 1234567"
                                    maxlength="20"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('telepon') border-red-500 @enderror">
                                @error('telepon')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Masjid
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    placeholder="masjid@example.com"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Tambahan
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                placeholder="Masukkan deskripsi tambahan tentang masjid (fasilitas, kegiatan, dll)"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto Masjid (Multiple) --}}
                        <div class="mb-4">
                            <label for="fotos" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Masjid <span class="text-sm font-normal text-gray-500">(Maksimal {{ \App\Models\Masjid::MAX_FOTO }} foto)</span>
                            </label>
                            
                            <input type="file" 
                                   name="fotos[]" 
                                   id="fotos" 
                                   multiple 
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="block w-full text-sm text-gray-500 
                                          file:mr-4 file:py-2 file:px-4 
                                          file:rounded-lg file:border-0 
                                          file:text-sm file:font-medium 
                                          file:bg-primary file:text-white 
                                          hover:file:bg-primary-600 
                                          @error('fotos') border-red-500 @enderror
                                          @error('fotos.*') border-red-500 @enderror">
                            
                            <p class="mt-1 text-xs text-gray-500">
                                Format: JPG, JPEG, PNG. Maksimal 2MB per foto. 
                                Pilih beberapa foto sekaligus (CTRL+Click).
                            </p>
                            
                            @error('fotos')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @error('fotos.*')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            {{-- Preview Area --}}
                            <div id="foto-preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-{{ \App\Models\Masjid::MAX_FOTO }} gap-3">
                                <!-- Preview akan ditampilkan di sini via JavaScript -->
                            </div>
                            
                            {{-- Counter --}}
                            <div id="foto-counter" class="mt-2 text-xs text-gray-500">
                                <span id="selected-count">0</span>/{{ \App\Models\Masjid::MAX_FOTO }} foto terpilih
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-600">
                                Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.
                            </p>
                            <ul class="mt-2 text-xs text-red-600">
                                @foreach($errors->all() as $error)
                                    <li class="ml-2">• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('masjid.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .foto-preview-item {
        position: relative;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    .foto-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    
    .foto-remove-btn {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: rgba(239, 68, 68, 0.9);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
        transition: background-color 0.2s;
    }
    
    .foto-remove-btn:hover {
        background-color: rgba(220, 38, 38, 0.9);
    }
    
    .foto-name {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize
        initForm();
    });

    function initForm() {
        // Auto-focus pertama
        if (window.innerWidth >= 768) {
            document.getElementById('admin_nama')?.focus();
        }

        // Setup foto admin preview
        setupAdminFotoPreview();
        
        // Setup multiple foto preview
        setupMultipleFotoPreview();
        
        // Setup wilayah dropdowns
        setupWilayahDropdowns();
        
        // Load old values for wilayah jika ada
        loadOldWilayahValues();
    }

    // ================================
    // FUNGSI FOTO ADMIN HANDLING
    // ================================
    function setupAdminFotoPreview() {
        const adminFotoInput = document.getElementById('admin_foto');
        const adminFotoPreview = document.getElementById('admin-foto-preview');
        const adminFotoPreviewImage = document.getElementById('admin-foto-preview-image');
        const removeAdminFotoBtn = document.getElementById('remove-admin-foto');

        if (adminFotoInput) {
            adminFotoInput.addEventListener('change', handleAdminFotoSelection);
        }

        if (removeAdminFotoBtn) {
            removeAdminFotoBtn.addEventListener('click', function() {
                adminFotoInput.value = '';
                adminFotoPreview.classList.add('hidden');
            });
        }
    }

    function handleAdminFotoSelection(event) {
        const file = event.target.files[0];
        const adminFotoPreview = document.getElementById('admin-foto-preview');
        const adminFotoPreviewImage = document.getElementById('admin-foto-preview-image');

        if (!file) return;

        // Validate file type
        if (!file.type.match('image/jpeg') && !file.type.match('image/jpg') && !file.type.match('image/png')) {
            alert('Format file tidak valid. Harap pilih file JPG, JPEG, atau PNG.');
            event.target.value = '';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File terlalu besar. Maksimal 2MB.');
            event.target.value = '';
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            adminFotoPreviewImage.src = e.target.result;
            adminFotoPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // ================================
    // FUNGSI MULTIPLE FOTO HANDLING
    // ================================
    function setupMultipleFotoPreview() {
        const fotoInput = document.getElementById('fotos');
        if (fotoInput) {
            fotoInput.addEventListener('change', handleFotoSelection);
        }
    }

    function handleFotoSelection(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('foto-preview-container');
        const counterSpan = document.getElementById('selected-count');
        const MAX_FOTOS = {{ \App\Models\Masjid::MAX_FOTO }};
        
        // Clear existing previews
        previewContainer.innerHTML = '';
        
        // Validate file count
        if (files.length > MAX_FOTOS) {
            alert(`Maksimal ${MAX_FOTOS} foto yang diizinkan. Silakan pilih ulang.`);
            event.target.value = '';
            counterSpan.textContent = '0';
            return;
        }
        
        // Update counter
        counterSpan.textContent = files.length;
        
        // Validate each file
        let validFiles = [];
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            // Validate file type
            if (!file.type.match('image/jpeg') && !file.type.match('image/jpg') && !file.type.match('image/png')) {
                alert(`File "${file.name}" bukan format gambar yang valid. Format yang diterima: JPG, JPEG, PNG.`);
                continue;
            }
            
            // Validate file size (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert(`File "${file.name}" terlalu besar. Maksimal 2MB per file.`);
                continue;
            }
            
            validFiles.push({
                file: file,
                index: i
            });
        }
        
        // If some files were invalid, update the file input
        if (validFiles.length !== files.length) {
            const dataTransfer = new DataTransfer();
            validFiles.forEach(validFile => {
                dataTransfer.items.add(validFile.file);
            });
            event.target.files = dataTransfer.files;
            counterSpan.textContent = validFiles.length;
        }
        
        // Create previews for valid files
        validFiles.forEach((validFile, displayIndex) => {
            createFotoPreview(validFile.file, validFile.index, displayIndex);
        });
    }
    
    function createFotoPreview(file, originalIndex, displayIndex) {
        const reader = new FileReader();
        const previewContainer = document.getElementById('foto-preview-container');
        
        reader.onload = function(e) {
            // Create preview item
            const previewItem = document.createElement('div');
            previewItem.className = 'foto-preview-item';
            previewItem.dataset.index = originalIndex;
            
            // Create image
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = `Preview foto ${displayIndex + 1}`;
            
            // Create remove button
            const removeBtn = document.createElement('div');
            removeBtn.className = 'foto-remove-btn';
            removeBtn.innerHTML = '×';
            removeBtn.title = 'Hapus foto ini';
            removeBtn.addEventListener('click', function() {
                removeFotoPreview(originalIndex);
            });
            
            // Create file name
            const fileName = document.createElement('div');
            fileName.className = 'foto-name';
            fileName.textContent = file.name.length > 15 ? 
                file.name.substring(0, 12) + '...' : file.name;
            
            // Assemble preview item
            previewItem.appendChild(img);
            previewItem.appendChild(removeBtn);
            previewItem.appendChild(fileName);
            
            // Add to container
            previewContainer.appendChild(previewItem);
        };
        
        reader.readAsDataURL(file);
    }
    
    function removeFotoPreview(indexToRemove) {
        const fotoInput = document.getElementById('fotos');
        const previewContainer = document.getElementById('foto-preview-container');
        const counterSpan = document.getElementById('selected-count');
        
        if (!fotoInput || !previewContainer) return;
        
        // Remove preview item
        const previewItem = previewContainer.querySelector(`[data-index="${indexToRemove}"]`);
        if (previewItem) {
            previewItem.remove();
        }
        
        // Update files in input
        const dataTransfer = new DataTransfer();
        const files = Array.from(fotoInput.files);
        
        // Reindex remaining files
        files.forEach((file, index) => {
            if (index !== indexToRemove) {
                dataTransfer.items.add(file);
            }
        });
        
        fotoInput.files = dataTransfer.files;
        
        // Update counter
        const newCount = files.length - 1;
        counterSpan.textContent = newCount;
        
        // Recreate previews with correct indexes
        if (newCount > 0) {
            previewContainer.innerHTML = '';
            Array.from(fotoInput.files).forEach((file, newIndex) => {
                createFotoPreview(file, newIndex, newIndex);
            });
        } else {
            previewContainer.innerHTML = '';
        }
    }

    // ================================
    // FUNGSI WILAYAH DROPDOWNS
    // ================================
    function setupWilayahDropdowns() {
        const provinsiSelect = document.getElementById('provinsi_kode');
        const kotaSelect = document.getElementById('kota_kode');
        const kecamatanSelect = document.getElementById('kecamatan_kode');
        const kelurahanSelect = document.getElementById('kelurahan_kode');

        if (provinsiSelect) {
            provinsiSelect.addEventListener('change', handleProvinsiChange);
        }
        if (kotaSelect) {
            kotaSelect.addEventListener('change', handleKotaChange);
        }
        if (kecamatanSelect) {
            kecamatanSelect.addEventListener('change', handleKecamatanChange);
        }
        if (kelurahanSelect) {
            kelurahanSelect.addEventListener('change', handleKelurahanChange);
        }
    }

    async function handleProvinsiChange(event) {
        const provinsiKode = event.target.value;
        const provinsiNama = event.target.options[event.target.selectedIndex]?.text || '';
        
        console.log('Provinsi changed:', provinsiKode, provinsiNama);
        
        // Reset dropdown berikutnya
        resetSelect('kota_kode');
        resetSelect('kecamatan_kode');
        resetSelect('kelurahan_kode');
        
        // Reset kode pos
        document.getElementById('kode_pos').value = '';
        
        if (provinsiKode) {
            await loadKota(provinsiKode);
            document.getElementById('kota_kode').disabled = false;
        } else {
            document.getElementById('kota_kode').disabled = true;
            document.getElementById('kecamatan_kode').disabled = true;
            document.getElementById('kelurahan_kode').disabled = true;
        }
    }

    async function handleKotaChange(event) {
        const kotaKode = event.target.value;
        const kotaNama = event.target.options[event.target.selectedIndex]?.text || '';
        
        console.log('Kota changed:', kotaKode, kotaNama);
        
        // Reset dropdown berikutnya
        resetSelect('kecamatan_kode');
        resetSelect('kelurahan_kode');
        
        if (kotaKode) {
            await loadKecamatan(kotaKode);
            document.getElementById('kecamatan_kode').disabled = false;
        } else {
            document.getElementById('kecamatan_kode').disabled = true;
            document.getElementById('kelurahan_kode').disabled = true;
        }
    }

    async function handleKecamatanChange(event) {
        const kecamatanKode = event.target.value;
        const kecamatanNama = event.target.options[event.target.selectedIndex]?.text || '';
        
        console.log('Kecamatan changed:', kecamatanKode, kecamatanNama);
        
        // Reset dropdown berikutnya
        resetSelect('kelurahan_kode');
        
        if (kecamatanKode) {
            await loadKelurahan(kecamatanKode);
            document.getElementById('kelurahan_kode').disabled = false;
        } else {
            document.getElementById('kelurahan_kode').disabled = true;
        }
    }

    async function handleKelurahanChange(event) {
        const kelurahanKode = event.target.value;
        const kelurahanNama = event.target.options[event.target.selectedIndex]?.text || '';
        
        console.log('Kelurahan changed:', kelurahanKode, kelurahanNama);
        
        if (kelurahanKode) {
            await getKodePos(kelurahanKode);
        }
    }

    async function loadKota(provinsiKode, selectedKotaKode = null) {
        try {
            console.log('Loading kota for province:', provinsiKode);
            
            // Gunakan absolute URL untuk menghindari masalah path
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/cities/${provinsiKode}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Kota data received:', data);
            
            const select = document.getElementById('kota_kode');
            select.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            
            // Tambahkan semua kota
            data.forEach(kota => {
                const option = new Option(kota.name, kota.code);
                select.add(option);
            });
            
            // Set selected value jika ada
            if (selectedKotaKode) {
                select.value = selectedKotaKode;
            }
            
            // Enable select
            select.disabled = false;
            
            return data;
        } catch (error) {
            console.error('Error loading kota:', error);
            showError('Gagal memuat data kota. Silakan coba lagi atau hubungi administrator.');
            return [];
        }
    }

    async function loadKecamatan(kotaKode, selectedKecamatanKode = null) {
        try {
            console.log('Loading kecamatan for city:', kotaKode);
            
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/districts/${kotaKode}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Kecamatan data received:', data);
            
            const select = document.getElementById('kecamatan_kode');
            select.innerHTML = '<option value="">Pilih Kecamatan</option>';
            
            data.forEach(kec => {
                const option = new Option(kec.name, kec.code);
                select.add(option);
            });
            
            // Set selected value jika ada
            if (selectedKecamatanKode) {
                select.value = selectedKecamatanKode;
            }
            
            // Enable select
            select.disabled = false;
            
            return data;
        } catch (error) {
            console.error('Error loading kecamatan:', error);
            showError('Gagal memuat data kecamatan. Silakan coba lagi.');
            return [];
        }
    }

    async function loadKelurahan(kecamatanKode, selectedKelurahanKode = null) {
        try {
            console.log('Loading kelurahan for district:', kecamatanKode);
            
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/villages/${kecamatanKode}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Kelurahan data received:', data);
            
            const select = document.getElementById('kelurahan_kode');
            select.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
            
            data.forEach(kel => {
                const option = new Option(kel.name, kel.code);
                select.add(option);
            });
            
            // Set selected value jika ada
            if (selectedKelurahanKode) {
                select.value = selectedKelurahanKode;
            }
            
            // Enable select
            select.disabled = false;
            
            return data;
        } catch (error) {
            console.error('Error loading kelurahan:', error);
            showError('Gagal memuat data kelurahan. Silakan coba lagi.');
            return [];
        }
    }

    async function getKodePos(kelurahanKode) {
        try {
            console.log('Getting postal code for village:', kelurahanKode);
            
            const baseUrl = window.location.origin;
            const response = await fetch(`${baseUrl}/api/postal-code/${kelurahanKode}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Postal code data:', data);
            
            if (data.kode_pos) {
                const kodePosInput = document.getElementById('kode_pos');
                if (kodePosInput && !kodePosInput.value) {
                    kodePosInput.value = data.kode_pos;
                    console.log('Auto-filled kode_pos:', data.kode_pos);
                }
            }
        } catch (error) {
            console.error('Error getting kode pos:', error);
        }
    }

    function resetSelect(id) {
        const select = document.getElementById(id);
        const label = document.querySelector(`label[for="${id}"]`)?.textContent.replace(' *', '').trim() || 'Pilihan';
        select.innerHTML = `<option value="">Pilih ${label}</option>`;
        select.disabled = true;
    }

    function showError(message) {
        alert(message);
    }

    // ================================
    // LOAD OLD VALUES
    // ================================
    function loadOldWilayahValues() {
        const oldProvinsiKode = '{{ old("provinsi_kode") }}';
        const oldKotaKode = '{{ old("kota_kode") }}';
        const oldKecamatanKode = '{{ old("kecamatan_kode") }}';
        const oldKelurahanKode = '{{ old("kelurahan_kode") }}';

        if (oldProvinsiKode) {
            // Set provinsi value
            const provinsiSelect = document.getElementById('provinsi_kode');
            if (provinsiSelect) {
                provinsiSelect.value = oldProvinsiKode;
                
                // Enable kota select
                document.getElementById('kota_kode').disabled = false;
                
                // Auto load kota setelah DOM siap
                setTimeout(async () => {
                    await loadKota(oldProvinsiKode, oldKotaKode);
                    
                    if (oldKotaKode) {
                        // Enable kecamatan select
                        document.getElementById('kecamatan_kode').disabled = false;
                        
                        setTimeout(async () => {
                            await loadKecamatan(oldKotaKode, oldKecamatanKode);
                            
                            if (oldKecamatanKode) {
                                // Enable kelurahan select
                                document.getElementById('kelurahan_kode').disabled = false;
                                
                                setTimeout(async () => {
                                    await loadKelurahan(oldKecamatanKode, oldKelurahanKode);
                                }, 300);
                            }
                        }, 300);
                    }
                }, 300);
            }
        }
    }
</script>
@endpush