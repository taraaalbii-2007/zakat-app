@extends('layouts.app')
@section('title', 'Tambah Data Lembaga')
@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Tambah Lembaga</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi field yang diperlukan untuk menambahkan data lembaga</p>
            </div>
            <form action="{{ route('lembaga.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                <div class="space-y-6">
                    <!-- SECTION: DATA ADMIN LEMBAGA -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Admin / Penanggung Jawab Lembaga
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
                                    placeholder="admin.lembaga@example.com"
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

                    <!-- SECTION: DATA SEJARAH LEMBAGA -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Data Sejarah Lembaga
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Sejarah --}}
                            <div class="md:col-span-2">
                                <label for="sejarah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sejarah Berdiri
                                </label>
                                <textarea name="sejarah" id="sejarah" rows="4"
                                    placeholder="Ceritakan sejarah berdirinya lembaga..."
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
                                    placeholder="Nama pendiri lembaga"
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

                    <!-- SECTION: DATA LEMBAGA -->
                    <div class="pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Data Utama Lembaga
                        </h3>
                        
                        {{-- Nama Lembaga --}}
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lembaga <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                placeholder="Masukkan nama lembaga"
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
                                placeholder="Masukkan alamat lengkap lembaga"
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
                                    Telepon Lembaga
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
                                    Email Lembaga
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    placeholder="lembaga@example.com"
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
                                placeholder="Masukkan deskripsi tambahan tentang lembaga (fasilitas, kegiatan, dll)"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto Lembaga (Multiple) --}}
                        <div class="mb-4">
                            <label for="fotos" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Lembaga <span class="text-sm font-normal text-gray-500">(Maksimal {{ \App\Models\Lembaga::MAX_FOTO }} foto)</span>
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
                            <div id="foto-preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-{{ \App\Models\Lembaga::MAX_FOTO }} gap-3">
                                <!-- Preview akan ditampilkan di sini via JavaScript -->
                            </div>
                            
                            {{-- Counter --}}
                            <div id="foto-counter" class="mt-2 text-xs text-gray-500">
                                <span id="selected-count">0</span>/{{ \App\Models\Lembaga::MAX_FOTO }} foto terpilih
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
                    <a href="{{ route('lembaga.index') }}"
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
{{-- Letakkan di dalam @push('scripts') --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initForm();
    });

    // ================================
    // HELPER: Normalisasi response API
    // ================================
    function normalizeArray(json) {
        if (Array.isArray(json)) return json;
        if (json && Array.isArray(json.data)) return json.data;
        if (json && Array.isArray(json.cities)) return json.cities;
        if (json && Array.isArray(json.districts)) return json.districts;
        if (json && Array.isArray(json.villages)) return json.villages;
        console.warn('Format response tidak dikenal:', json);
        return [];
    }

    // ================================
    // INIT
    // ================================
    function initForm() {
        if (window.innerWidth >= 768) {
            document.getElementById('admin_nama')?.focus();
        }
        setupAdminFotoPreview();
        setupMultipleFotoPreview();
        setupWilayahDropdowns();
        loadOldWilayahValues();
    }

    // ================================
    // FOTO ADMIN
    // ================================
    function setupAdminFotoPreview() {
        const adminFotoInput   = document.getElementById('admin_foto');
        const adminFotoPreview = document.getElementById('admin-foto-preview');
        const removeBtn        = document.getElementById('remove-admin-foto');

        adminFotoInput?.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                alert('Format file tidak valid. Harap pilih file JPG, JPEG, atau PNG.');
                event.target.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('File terlalu besar. Maksimal 2MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('admin-foto-preview-image').src = e.target.result;
                adminFotoPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        removeBtn?.addEventListener('click', function () {
            adminFotoInput.value = '';
            adminFotoPreview.classList.add('hidden');
        });
    }

    // ================================
    // MULTIPLE FOTO LEMBAGA
    // ================================
    function setupMultipleFotoPreview() {
        document.getElementById('fotos')?.addEventListener('change', handleFotoSelection);
    }

    function handleFotoSelection(event) {
        const files          = event.target.files;
        const previewContainer = document.getElementById('foto-preview-container');
        const counterSpan    = document.getElementById('selected-count');
        const MAX_FOTOS      = {{ \App\Models\Lembaga::MAX_FOTO }};

        previewContainer.innerHTML = '';

        if (files.length > MAX_FOTOS) {
            alert(`Maksimal ${MAX_FOTOS} foto yang diizinkan. Silakan pilih ulang.`);
            event.target.value = '';
            counterSpan.textContent = '0';
            return;
        }

        const validFiles = [];
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                alert(`File "${file.name}" bukan format gambar yang valid.`);
                continue;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert(`File "${file.name}" terlalu besar. Maksimal 2MB.`);
                continue;
            }
            validFiles.push({ file, index: i });
        }

        if (validFiles.length !== files.length) {
            const dt = new DataTransfer();
            validFiles.forEach(vf => dt.items.add(vf.file));
            event.target.files = dt.files;
        }

        counterSpan.textContent = validFiles.length;
        validFiles.forEach((vf, di) => createFotoPreview(vf.file, vf.index, di));
    }

    function createFotoPreview(file, originalIndex, displayIndex) {
        const previewContainer = document.getElementById('foto-preview-container');
        const reader = new FileReader();
        reader.onload = function (e) {
            const item = document.createElement('div');
            item.className = 'foto-preview-item';
            item.dataset.index = originalIndex;

            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = `Preview foto ${displayIndex + 1}`;

            const removeBtn = document.createElement('div');
            removeBtn.className = 'foto-remove-btn';
            removeBtn.innerHTML = '×';
            removeBtn.title = 'Hapus foto ini';
            removeBtn.addEventListener('click', () => removeFotoPreview(originalIndex));

            const nameEl = document.createElement('div');
            nameEl.className = 'foto-name';
            nameEl.textContent = file.name.length > 15 ? file.name.substring(0, 12) + '...' : file.name;

            item.appendChild(img);
            item.appendChild(removeBtn);
            item.appendChild(nameEl);
            previewContainer.appendChild(item);
        };
        reader.readAsDataURL(file);
    }

    function removeFotoPreview(indexToRemove) {
        const fotoInput        = document.getElementById('fotos');
        const previewContainer = document.getElementById('foto-preview-container');
        const counterSpan      = document.getElementById('selected-count');
        if (!fotoInput || !previewContainer) return;

        previewContainer.querySelector(`[data-index="${indexToRemove}"]`)?.remove();

        const dt = new DataTransfer();
        Array.from(fotoInput.files).forEach((file, i) => {
            if (i !== indexToRemove) dt.items.add(file);
        });
        fotoInput.files = dt.files;

        const newCount = fotoInput.files.length;
        counterSpan.textContent = newCount;

        previewContainer.innerHTML = '';
        Array.from(fotoInput.files).forEach((file, i) => createFotoPreview(file, i, i));
    }

    // ================================
    // WILAYAH DROPDOWNS
    // ================================
    function setupWilayahDropdowns() {
        document.getElementById('provinsi_kode')?.addEventListener('change', handleProvinsiChange);
        document.getElementById('kota_kode')?.addEventListener('change', handleKotaChange);
        document.getElementById('kecamatan_kode')?.addEventListener('change', handleKecamatanChange);
        document.getElementById('kelurahan_kode')?.addEventListener('change', handleKelurahanChange);
    }

    async function handleProvinsiChange(event) {
        const provinsiKode = event.target.value;
        resetSelect('kota_kode');
        resetSelect('kecamatan_kode');
        resetSelect('kelurahan_kode');
        document.getElementById('kode_pos').value = '';

        if (provinsiKode) {
            await loadKota(provinsiKode);
        } else {
            ['kota_kode', 'kecamatan_kode', 'kelurahan_kode'].forEach(id => {
                document.getElementById(id).disabled = true;
            });
        }
    }

    async function handleKotaChange(event) {
        const kotaKode = event.target.value;
        resetSelect('kecamatan_kode');
        resetSelect('kelurahan_kode');

        if (kotaKode) {
            await loadKecamatan(kotaKode);
        } else {
            ['kecamatan_kode', 'kelurahan_kode'].forEach(id => {
                document.getElementById(id).disabled = true;
            });
        }
    }

    async function handleKecamatanChange(event) {
        const kecamatanKode = event.target.value;
        resetSelect('kelurahan_kode');

        if (kecamatanKode) {
            await loadKelurahan(kecamatanKode);
        } else {
            document.getElementById('kelurahan_kode').disabled = true;
        }
    }

    async function handleKelurahanChange(event) {
        const kelurahanKode = event.target.value;
        if (kelurahanKode) await getKodePos(kelurahanKode);
    }

    // ================================
    // FETCH HELPERS
    // ================================
    async function fetchJson(url) {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${url}`);
        return response.json();
    }

    async function loadKota(provinsiKode, selectedValue = null) {
        try {
            const json = await fetchJson(`${window.location.origin}/api/wilayah/cities/${provinsiKode}`);
            const data = normalizeArray(json);
            populateSelect('kota_kode', data, 'Pilih Kota/Kabupaten', selectedValue);
        } catch (error) {
            console.error('loadKota error:', error);
            alert('Gagal memuat data kota. Silakan coba lagi.');
        }
    }

    async function loadKecamatan(kotaKode, selectedValue = null) {
        try {
            const json = await fetchJson(`${window.location.origin}/api/wilayah/districts/${kotaKode}`);
            const data = normalizeArray(json);
            populateSelect('kecamatan_kode', data, 'Pilih Kecamatan', selectedValue);
        } catch (error) {
            console.error('loadKecamatan error:', error);
            alert('Gagal memuat data kecamatan. Silakan coba lagi.');
        }
    }

    async function loadKelurahan(kecamatanKode, selectedValue = null) {
        try {
            const json = await fetchJson(`${window.location.origin}/api/wilayah/villages/${kecamatanKode}`);
            const data = normalizeArray(json);
            populateSelect('kelurahan_kode', data, 'Pilih Kelurahan/Desa', selectedValue);
        } catch (error) {
            console.error('loadKelurahan error:', error);
            alert('Gagal memuat data kelurahan. Silakan coba lagi.');
        }
    }

    async function getKodePos(kelurahanKode) {
        try {
            const data = await fetchJson(`${window.location.origin}/api/wilayah/postal-code/${kelurahanKode}`);
            const kodePosInput = document.getElementById('kode_pos');
            if (data.kode_pos && kodePosInput && !kodePosInput.value) {
                kodePosInput.value = data.kode_pos;
            }
        } catch (error) {
            // Kode pos gagal diisi — tidak perlu alert
            console.warn('getKodePos error:', error);
        }
    }

    // ================================
    // UTILS
    // ================================
    function populateSelect(id, data, placeholder, selectedValue = null) {
        const select = document.getElementById(id);
        select.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(item => {
            const option = new Option(item.name, item.code);
            select.add(option);
        });
        if (selectedValue) select.value = selectedValue;
        select.disabled = false;
    }

    function resetSelect(id) {
        const select = document.getElementById(id);
        const labelText = document.querySelector(`label[for="${id}"]`)
            ?.textContent.replace(/\s*\*\s*/g, '').trim() || 'Pilihan';
        select.innerHTML = `<option value="">Pilih ${labelText}</option>`;
        select.disabled = true;
    }

    // ================================
    // RESTORE OLD VALUES (setelah validasi gagal)
    // ================================
    async function loadOldWilayahValues() {
        const oldProvinsi   = '{{ old("provinsi_kode") }}';
        const oldKota       = '{{ old("kota_kode") }}';
        const oldKecamatan  = '{{ old("kecamatan_kode") }}';
        const oldKelurahan  = '{{ old("kelurahan_kode") }}';

        if (!oldProvinsi) return;

        const provinsiSelect = document.getElementById('provinsi_kode');
        provinsiSelect.value = oldProvinsi;

        await loadKota(oldProvinsi, oldKota);

        if (oldKota) {
            await loadKecamatan(oldKota, oldKecamatan);

            if (oldKecamatan) {
                await loadKelurahan(oldKecamatan, oldKelurahan);
            }
        }
    }
</script>
@endpush