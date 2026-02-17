@extends('layouts.app')

@section('title', 'Edit Konfigurasi Aplikasi')

@push('styles')
<style>
    .file-upload {
        @apply border-2 border-dashed border-neutral-300 rounded-lg p-6 text-center transition duration-150 ease-in-out cursor-pointer;
    }
    
    .file-upload:hover {
        @apply border-primary bg-primary/5;
    }
    
    .file-upload-active {
        @apply border-primary bg-primary/10;
    }
    
    .preview-container {
        @apply relative inline-block;
    }
    
    .remove-btn {
        @apply absolute -top-2 -right-2 bg-danger text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-danger-dark transition duration-150;
    }
    
    .file-info {
        @apply text-xs text-neutral-500 mt-2;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle file preview
    const logoInput = document.getElementById('logo_aplikasi');
    const faviconInput = document.getElementById('favicon');
    const logoPreview = document.getElementById('logo-preview');
    const faviconPreview = document.getElementById('favicon-preview');
    const logoFileName = document.getElementById('logo-file-name');
    const faviconFileName = document.getElementById('favicon-file-name');
    const existingLogo = @json($config->logo_aplikasi ? true : false);
    const existingFavicon = @json($config->favicon ? true : false);
    
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.innerHTML = `<img src="${e.target.result}" class="max-h-32 mx-auto" alt="Preview Logo">`;
                    logoFileName.textContent = this.files[0].name;
                    logoFileName.classList.remove('hidden');
                }.bind(this);
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    if (faviconInput) {
        faviconInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    faviconPreview.innerHTML = `<img src="${e.target.result}" class="w-16 h-16 mx-auto" alt="Preview Favicon">`;
                    faviconFileName.textContent = this.files[0].name;
                    faviconFileName.classList.remove('hidden');
                }.bind(this);
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Handle remove buttons
    document.querySelectorAll('.remove-file-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.type;
            const confirmDelete = confirm(`Yakin ingin menghapus ${type === 'logo' ? 'logo' : 'favicon'}?`);
            
            if (confirmDelete) {
                fetch(`/konfigurasi/${type}/hapus`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus file');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            }
        });
    });
});
</script>
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-neutral-900">Edit Konfigurasi Aplikasi</h1>
    <p class="text-neutral-600 mt-1">Ubah pengaturan umum aplikasi</p>
</div>

<form action="{{ route('konfigurasi-global.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    
    <!-- Informasi Aplikasi -->
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
            Informasi Aplikasi
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_aplikasi" class="block text-sm font-medium text-neutral-700 mb-2">
                    Nama Aplikasi *
                </label>
                <input type="text" 
                       id="nama_aplikasi" 
                       name="nama_aplikasi" 
                       value="{{ old('nama_aplikasi', $config->nama_aplikasi) }}"
                       required
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150">
                @error('nama_aplikasi')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="tagline" class="block text-sm font-medium text-neutral-700 mb-2">
                    Tagline
                </label>
                <input type="text" 
                       id="tagline" 
                       name="tagline" 
                       value="{{ old('tagline', $config->tagline) }}"
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150"
                       placeholder="Contoh: Membantu Pengelolaan Zakat Digital">
                @error('tagline')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="deskripsi_aplikasi" class="block text-sm font-medium text-neutral-700 mb-2">
                    Deskripsi Aplikasi
                </label>
                <textarea id="deskripsi_aplikasi" 
                          name="deskripsi_aplikasi" 
                          rows="3"
                          class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150">{{ old('deskripsi_aplikasi', $config->deskripsi_aplikasi) }}</textarea>
                @error('deskripsi_aplikasi')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Branding -->
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
            Branding
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Logo -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Logo Aplikasi
                </label>
                
                @if($config->logo_aplikasi)
                    <div class="mb-4">
                        <p class="text-sm text-neutral-600 mb-2">Logo saat ini:</p>
                        <div class="relative inline-block">
                            <img src="{{ Storage::url($config->logo_aplikasi) }}" 
                                 alt="Logo saat ini" 
                                 class="max-h-32 border border-neutral-200 rounded">
                            <button type="button" 
                                    class="remove-file-btn remove-btn" 
                                    data-type="logo">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <p class="file-info">Unggah file baru untuk mengganti</p>
                    </div>
                @endif
                
                <label for="logo_aplikasi" class="file-upload block">
                    <div id="logo-preview" class="mb-3">
                        <svg class="w-12 h-12 text-neutral-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-neutral-600 mt-2">
                            Klik untuk mengunggah logo
                            <br>
                            <span class="text-xs text-neutral-500">Format: JPG, PNG, SVG (max: 2MB)</span>
                        </p>
                    </div>
                    <input type="file" 
                           id="logo_aplikasi" 
                           name="logo_aplikasi" 
                           accept="image/*"
                           class="hidden">
                </label>
                <p id="logo-file-name" class="file-info hidden"></p>
                @error('logo_aplikasi')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Favicon -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Favicon
                </label>
                
                @if($config->favicon)
                    <div class="mb-4">
                        <p class="text-sm text-neutral-600 mb-2">Favicon saat ini:</p>
                        <div class="relative inline-block">
                            <img src="{{ Storage::url($config->favicon) }}" 
                                 alt="Favicon saat ini" 
                                 class="w-16 h-16 border border-neutral-200 rounded">
                            <button type="button" 
                                    class="remove-file-btn remove-btn" 
                                    data-type="favicon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <p class="file-info">Unggah file baru untuk mengganti</p>
                    </div>
                @endif
                
                <label for="favicon" class="file-upload block">
                    <div id="favicon-preview" class="mb-3">
                        <svg class="w-12 h-12 text-neutral-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        <p class="text-sm text-neutral-600 mt-2">
                            Klik untuk mengunggah favicon
                            <br>
                            <span class="text-xs text-neutral-500">Format: ICO, PNG (max: 1MB)</span>
                        </p>
                    </div>
                    <input type="file" 
                           id="favicon" 
                           name="favicon" 
                           accept=".ico,image/*"
                           class="hidden">
                </label>
                <p id="favicon-file-name" class="file-info hidden"></p>
                @error('favicon')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Kontak & Support -->
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
            Kontak & Support
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email_support" class="block text-sm font-medium text-neutral-700 mb-2">
                    Email Support
                </label>
                <input type="email" 
                       id="email_support" 
                       name="email_support" 
                       value="{{ old('email_support', $config->email_support) }}"
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150"
                       placeholder="support@example.com">
                @error('email_support')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="telepon_support" class="block text-sm font-medium text-neutral-700 mb-2">
                    Telepon Support
                </label>
                <input type="text" 
                       id="telepon_support" 
                       name="telepon_support" 
                       value="{{ old('telepon_support', $config->telepon_support) }}"
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150"
                       placeholder="081234567890">
                @error('telepon_support')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="whatsapp_support" class="block text-sm font-medium text-neutral-700 mb-2">
                    WhatsApp Support
                </label>
                <input type="text" 
                       id="whatsapp_support" 
                       name="whatsapp_support" 
                       value="{{ old('whatsapp_support', $config->whatsapp_support) }}"
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150"
                       placeholder="081234567890">
                <p class="file-info">Nomor WhatsApp untuk chat support</p>
                @error('whatsapp_support')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Media Sosial -->
    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
            Media Sosial
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="facebook_url" class="block text-sm font-medium text-neutral-700 mb-2">
                    URL Facebook
                </label>
                <input type="url" 
                       id="facebook_url" 
                       name="facebook_url" 
                       value="{{ old('facebook_url', $config->facebook_url) }}"
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150"
                       placeholder="https://facebook.com/namapage">
                @error('facebook_url')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="instagram_url" class="block text-sm font-medium text-neutral-700 mb-2">
                    URL Instagram
                </label>
                <input type="url" 
                       id="instagram_url" 
                       name="instagram_url" 
                       value="{{ old('instagram_url', $config->instagram_url) }}"
                       class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition duration-150"
                       placeholder="https://instagram.com/username">
                @error('instagram_url')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-6 border-t border-neutral-200">
        <a href="{{ route('konfigurasi-global.show') }}" 
           class="px-5 py-2.5 border border-neutral-300 rounded-lg font-medium text-neutral-700 hover:bg-neutral-50 transition duration-150">
            Batal
        </a>
        
        <button type="submit" 
                class="px-5 py-2.5 bg-primary border border-transparent rounded-lg font-semibold text-white hover:bg-primary-dark transition duration-150">
            Simpan Perubahan
        </button>
    </div>
</form>
@endsection