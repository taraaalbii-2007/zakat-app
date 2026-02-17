@extends('layouts.app')

@section('title', 'Edit Konfigurasi - Superadmin')

@section('page-title', 'Edit Konfigurasi Aplikasi')
@section('page-description', 'Ubah pengaturan aplikasi zakat digital')

@section('breadcrumbs')
    <nav class="breadcrumb">
        <a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <a href="{{ route('superadmin.konfigurasi.show') }}" class="breadcrumb-item">Konfigurasi</a>
        <span class="breadcrumb-item active">Edit</span>
    </nav>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-modal p-6 animate-fade-in">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800">Form Edit Konfigurasi</h2>
        <p class="text-gray-600">Isi form di bawah untuk mengubah pengaturan aplikasi</p>
    </div>

    <form action="{{ route('superadmin.konfigurasi.update', $konfigurasi) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-8"
          onsubmit="showLoading()">
        @csrf
        @method('PUT')

        <!-- Informasi Aplikasi -->
        <div class="space-y-6">
            <div class="border-l-4 border-gradient-start pl-4">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Aplikasi</h3>
                <p class="text-sm text-gray-500">Pengaturan dasar aplikasi</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_aplikasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Aplikasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_aplikasi"
                           name="nama_aplikasi"
                           value="{{ old('nama_aplikasi', $konfigurasi->nama_aplikasi) }}"
                           required
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="Masukkan nama aplikasi">
                    @error('nama_aplikasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tagline" class="block text-sm font-medium text-gray-700 mb-2">
                        Tagline
                    </label>
                    <input type="text" 
                           id="tagline"
                           name="tagline"
                           value="{{ old('tagline', $konfigurasi->tagline) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="Contoh: DARI NIAT TIMBUL MANFAA">
                    @error('tagline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="deskripsi_aplikasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Aplikasi
                </label>
                <textarea id="deskripsi_aplikasi"
                          name="deskripsi_aplikasi"
                          rows="3"
                          class="form-textarea w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                          placeholder="Deskripsi lengkap tentang aplikasi zakat digital">{{ old('deskripsi_aplikasi', $konfigurasi->deskripsi_aplikasi) }}</textarea>
                @error('deskripsi_aplikasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="logo_aplikasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Logo Aplikasi
                    </label>
                    <div class="space-y-4">
                        @if($konfigurasi->logo_aplikasi)
                            <div class="flex items-center space-x-4">
                                <img src="{{ Storage::url($konfigurasi->logo_aplikasi) }}" 
                                     alt="Logo Saat Ini" 
                                     class="h-16 w-auto object-contain border rounded-lg p-2">
                                <div class="text-sm text-gray-500">
                                    <p>Logo saat ini</p>
                                    <p class="text-xs">Unggah baru untuk mengganti</p>
                                </div>
                            </div>
                        @endif
                        
                        <input type="file" 
                               id="logo_aplikasi"
                               name="logo_aplikasi"
                               accept="image/*"
                               class="form-file w-full text-gray-500 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-start/10 file:text-gradient-start hover:file:bg-gradient-start/20">
                        <p class="text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, SVG (Max: 2MB)</p>
                        @error('logo_aplikasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                        Favicon
                    </label>
                    <div class="space-y-4">
                        @if($konfigurasi->favicon)
                            <div class="flex items-center space-x-4">
                                <img src="{{ Storage::url($konfigurasi->favicon) }}" 
                                     alt="Favicon Saat Ini" 
                                     class="h-12 w-auto object-contain border rounded-lg p-2">
                                <div class="text-sm text-gray-500">
                                    <p>Favicon saat ini</p>
                                    <p class="text-xs">Unggah baru untuk mengganti</p>
                                </div>
                            </div>
                        @endif
                        
                        <input type="file" 
                               id="favicon"
                               name="favicon"
                               accept=".ico,image/*"
                               class="form-file w-full text-gray-500 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-start/10 file:text-gradient-start hover:file:bg-gradient-start/20">
                        <p class="text-xs text-gray-500">Format: ICO, PNG (Max: 1MB)</p>
                        @error('favicon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak & Sosial Media -->
        <div class="space-y-6">
            <div class="border-l-4 border-gradient-start pl-4">
                <h3 class="text-lg font-semibold text-gray-800">Kontak & Sosial Media</h3>
                <p class="text-sm text-gray-500">Informasi kontak dan media sosial</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email_support" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Support
                    </label>
                    <input type="email" 
                           id="email_support"
                           name="email_support"
                           value="{{ old('email_support', $konfigurasi->email_support) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="support@example.com">
                    @error('email_support')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="telepon_support" class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon Support
                    </label>
                    <input type="text" 
                           id="telepon_support"
                           name="telepon_support"
                           value="{{ old('telepon_support', $konfigurasi->telepon_support) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="+62 812-3456-7890">
                    @error('telepon_support')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="whatsapp_support" class="block text-sm font-medium text-gray-700 mb-2">
                        WhatsApp Support
                    </label>
                    <input type="text" 
                           id="whatsapp_support"
                           name="whatsapp_support"
                           value="{{ old('whatsapp_support', $konfigurasi->whatsapp_support) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="+62 812-3456-7890">
                    @error('whatsapp_support')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL Facebook
                    </label>
                    <input type="url" 
                           id="facebook_url"
                           name="facebook_url"
                           value="{{ old('facebook_url', $konfigurasi->facebook_url) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="https://facebook.com/username">
                    @error('facebook_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL Instagram
                    </label>
                    <input type="url" 
                           id="instagram_url"
                           name="instagram_url"
                           value="{{ old('instagram_url', $konfigurasi->instagram_url) }}"
                           class="form-input w-full rounded-lg border-gray-300 focus:border-gradient-start focus:ring focus:ring-gradient-start/20"
                           placeholder="https://instagram.com/username">
                    @error('instagram_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('superadmin.konfigurasi.show') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="btn-gradient-primary px-6 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection