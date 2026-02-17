@extends('layouts.app')

@section('title', 'Edit Konfigurasi Aplikasi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Konfigurasi Aplikasi</h1>
                    <p class="text-gray-600 mt-1">Perbarui pengaturan global aplikasi dan konfigurasi sistem</p>
                </div>
                <a href="{{ route('konfigurasi-global.show') }}"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Form Edit -->
            <form action="{{ route('konfigurasi-global.update') }}" method="POST" enctype="multipart/form-data"
                id="konfigurasiForm">
                @csrf

                <!-- Informasi Aplikasi -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Informasi Aplikasi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Aplikasi -->
                        <div>
                            <label for="nama_aplikasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Aplikasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_aplikasi" id="nama_aplikasi"
                                value="{{ old('nama_aplikasi', $config->nama_aplikasi ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('nama_aplikasi') border-red-500 @enderror"
                                placeholder="Masukkan nama aplikasi" required>
                            @error('nama_aplikasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tagline -->
                        <div>
                            <label for="tagline" class="block text-sm font-medium text-gray-700 mb-2">
                                Tagline
                            </label>
                            <input type="text" name="tagline" id="tagline"
                                value="{{ old('tagline', $config->tagline ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tagline') border-red-500 @enderror"
                                placeholder="Masukkan tagline aplikasi">
                            @error('tagline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Aplikasi -->
                        <div class="md:col-span-2">
                            <label for="deskripsi_aplikasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Aplikasi
                            </label>
                            <textarea name="deskripsi_aplikasi" id="deskripsi_aplikasi" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('deskripsi_aplikasi') border-red-500 @enderror"
                                placeholder="Deskripsi singkat tentang aplikasi">{{ old('deskripsi_aplikasi', $config->deskripsi_aplikasi ?? '') }}</textarea>
                            @error('deskripsi_aplikasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Versi -->
                        <div>
                            <label for="versi" class="block text-sm font-medium text-gray-700 mb-2">
                                Versi Aplikasi
                            </label>
                            <input type="text" name="versi" id="versi"
                                value="{{ old('versi', $config->versi ?? '1.0.0') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('versi') border-red-500 @enderror"
                                placeholder="Contoh: 1.0.0">
                            @error('versi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kontak & Support -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Kontak & Support
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email Admin -->
                        <div>
                            <label for="email_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Admin
                            </label>
                            <input type="email" name="email_admin" id="email_admin"
                                value="{{ old('email_admin', $config->email_admin ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('email_admin') border-red-500 @enderror"
                                placeholder="admin@example.com">
                            @error('email_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon Admin -->
                        <div>
                            <label for="telepon_admin" class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon Admin
                            </label>
                            <input type="text" name="telepon_admin" id="telepon_admin"
                                value="{{ old('telepon_admin', $config->telepon_admin ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('telepon_admin') border-red-500 @enderror"
                                placeholder="081234567890">
                            @error('telepon_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp Support -->
                        <div>
                            <label for="whatsapp_support" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Support
                            </label>
                            <input type="text" name="whatsapp_support" id="whatsapp_support"
                                value="{{ old('whatsapp_support', $config->whatsapp_support ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('whatsapp_support') border-red-500 @enderror"
                                placeholder="081234567890">
                            <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp untuk layanan support</p>
                            @error('whatsapp_support')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Kantor -->
                        <div class="md:col-span-2">
                            <label for="alamat_kantor" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Kantor
                            </label>
                            <textarea name="alamat_kantor" id="alamat_kantor" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('alamat_kantor') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap kantor">{{ old('alamat_kantor', $config->alamat_kantor ?? '') }}</textarea>
                            @error('alamat_kantor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Media Sosial -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Media Sosial
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Facebook -->
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Facebook
                            </label>
                            <input type="url" name="facebook_url" id="facebook_url"
                                value="{{ old('facebook_url', $config->facebook_url ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('facebook_url') border-red-500 @enderror"
                                placeholder="https://facebook.com/namapage">
                            @error('facebook_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Instagram
                            </label>
                            <input type="url" name="instagram_url" id="instagram_url"
                                value="{{ old('instagram_url', $config->instagram_url ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('instagram_url') border-red-500 @enderror"
                                placeholder="https://instagram.com/username">
                            @error('instagram_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Twitter -->
                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Twitter/X
                            </label>
                            <input type="url" name="twitter_url" id="twitter_url"
                                value="{{ old('twitter_url', $config->twitter_url ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('twitter_url') border-red-500 @enderror"
                                placeholder="https://twitter.com/username">
                            @error('twitter_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL YouTube
                            </label>
                            <input type="url" name="youtube_url" id="youtube_url"
                                value="{{ old('youtube_url', $config->youtube_url ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('youtube_url') border-red-500 @enderror"
                                placeholder="https://youtube.com/channel">
                            @error('youtube_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Logo & Favicon -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Logo & Favicon
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Logo Aplikasi -->
                        <div>
                            <label for="logo_aplikasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Aplikasi
                            </label>
                            <div class="space-y-4">
                                @if ($config->logo_aplikasi ?? false)
                                    <div id="logo-container">
                                        <p class="text-sm text-gray-600 mb-2">Logo saat ini:</p>
                                        <div class="relative inline-block">
                                            <img src="{{ asset('storage/' . $config->logo_aplikasi) }}"
                                                alt="Logo Aplikasi"
                                                class="w-48 h-24 object-contain border border-gray-300 rounded-lg p-4 bg-white">
                                            <button type="button" onclick="hapusFile('logo')"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition duration-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="hidden" name="hapus_logo" id="hapus_logo" value="0">
                                    </div>
                                @endif
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 hover:bg-primary-50 transition duration-200 cursor-pointer file-upload-area"
                                    onclick="document.getElementById('logo_aplikasi').click()">
                                    <input type="file" name="logo_aplikasi" id="logo_aplikasi" accept="image/*,.webp"
                                        class="hidden"
                                        onchange="previewImage(this, 'logo-preview'); validateFileSize(this, 15, 'logo');">
                                    <div id="logo-preview" class="mb-3">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-600 mt-2">Klik untuk mengunggah logo baru</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, SVG, WebP (Max: 15MB)</p>
                                <div id="logo-error" class="text-sm text-red-600 mt-1 hidden"></div>
                                @error('logo_aplikasi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div>
                            <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                Favicon
                            </label>
                            <div class="space-y-4">
                                @if ($config->favicon ?? false)
                                    <div id="favicon-container">
                                        <p class="text-sm text-gray-600 mb-2">Favicon saat ini:</p>
                                        <div class="relative inline-block">
                                            <img src="{{ asset('storage/' . $config->favicon) }}" alt="Favicon"
                                                class="w-16 h-16 object-contain border border-gray-300 rounded-lg p-2 bg-white">
                                            <button type="button" onclick="hapusFile('favicon')"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition duration-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="hidden" name="hapus_favicon" id="hapus_favicon" value="0">
                                    </div>
                                @endif
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 hover:bg-primary-50 transition duration-200 cursor-pointer file-upload-area"
                                    onclick="document.getElementById('favicon').click()">
                                    <input type="file" name="favicon" id="favicon" accept=".ico,image/*,.webp"
                                        class="hidden"
                                        onchange="previewImage(this, 'favicon-preview'); validateFileSize(this, 15, 'favicon');">
                                    <div id="favicon-preview" class="mb-3">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-600 mt-2">Klik untuk mengunggah favicon baru</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Format: ICO, PNG, SVG, JPEG, JPG, WebP (Max: 15MB)</p>
                                <div id="favicon-error" class="text-sm text-red-600 mt-1 hidden"></div>
                                @error('favicon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi ReCAPTCHA -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Konfigurasi ReCAPTCHA
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ReCAPTCHA Site Key -->
                        <div>
                            <label for="RECAPTCHA_SITE_KEY" class="block text-sm font-medium text-gray-700 mb-2">
                                ReCAPTCHA Site Key
                            </label>
                            <input type="text" name="RECAPTCHA_SITE_KEY" id="RECAPTCHA_SITE_KEY"
                                value="{{ old('RECAPTCHA_SITE_KEY', $recaptcha->RECAPTCHA_SITE_KEY ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('RECAPTCHA_SITE_KEY') border-red-500 @enderror"
                                placeholder="Masukkan ReCAPTCHA Site Key">
                            @error('RECAPTCHA_SITE_KEY')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ReCAPTCHA Secret Key -->
                        <div>
                            <label for="RECAPTCHA_SECRET_KEY" class="block text-sm font-medium text-gray-700 mb-2">
                                ReCAPTCHA Secret Key
                            </label>
                            <div class="relative">
                                <input type="password" name="RECAPTCHA_SECRET_KEY" id="RECAPTCHA_SECRET_KEY"
                                    value="{{ old('RECAPTCHA_SECRET_KEY', $recaptcha->RECAPTCHA_SECRET_KEY ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('RECAPTCHA_SECRET_KEY') border-red-500 @enderror"
                                    placeholder="Masukkan ReCAPTCHA Secret Key">
                                <button type="button" onclick="togglePassword('RECAPTCHA_SECRET_KEY')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-RECAPTCHA_SECRET_KEY" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-RECAPTCHA_SECRET_KEY" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('RECAPTCHA_SECRET_KEY')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi Google OAuth -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Konfigurasi Google OAuth
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Google Client ID -->
                        <div>
                            <label for="GOOGLE_CLIENT_ID" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Client ID
                            </label>
                            <input type="text" name="GOOGLE_CLIENT_ID" id="GOOGLE_CLIENT_ID"
                                value="{{ old('GOOGLE_CLIENT_ID', $google->GOOGLE_CLIENT_ID ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('GOOGLE_CLIENT_ID') border-red-500 @enderror"
                                placeholder="Masukkan Google Client ID">
                            @error('GOOGLE_CLIENT_ID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Client Secret -->
                        <div>
                            <label for="GOOGLE_CLIENT_SECRET" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Client Secret
                            </label>
                            <div class="relative">
                                <input type="password" name="GOOGLE_CLIENT_SECRET" id="GOOGLE_CLIENT_SECRET"
                                    value="{{ old('GOOGLE_CLIENT_SECRET', $google->GOOGLE_CLIENT_SECRET ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('GOOGLE_CLIENT_SECRET') border-red-500 @enderror"
                                    placeholder="Masukkan Google Client Secret">
                                <button type="button" onclick="togglePassword('GOOGLE_CLIENT_SECRET')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-GOOGLE_CLIENT_SECRET" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-GOOGLE_CLIENT_SECRET" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('GOOGLE_CLIENT_SECRET')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Google Redirect URI -->
                        <div class="md:col-span-2">
                            <label for="GOOGLE_REDIRECT_URI" class="block text-sm font-medium text-gray-700 mb-2">
                                Google Redirect URI
                            </label>
                            <input type="url" name="GOOGLE_REDIRECT_URI" id="GOOGLE_REDIRECT_URI"
                                value="{{ old('GOOGLE_REDIRECT_URI', $google->GOOGLE_REDIRECT_URI ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('GOOGLE_REDIRECT_URI') border-red-500 @enderror"
                                placeholder="https://domain-anda.com/auth/google/callback">
                            @error('GOOGLE_REDIRECT_URI')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi Mail -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Konfigurasi Mail (SMTP)
                    </h2>

                    <!-- Info Box untuk Mailtrap -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Menggunakan Mailtrap Email Sending</p>
                                <p>Untuk mengirim email ke alamat asli (Gmail, Yahoo, dll), gunakan <strong>Email
                                        Sending</strong> dari Mailtrap dengan host <code
                                        class="bg-blue-100 px-1 rounded">live.smtp.mailtrap.io</code>. Untuk testing,
                                    gunakan <code class="bg-blue-100 px-1 rounded">sandbox.smtp.mailtrap.io</code>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- MAIL_MAILER -->
                        <div>
                            <label for="MAIL_MAILER" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Mailer
                            </label>
                            <select name="MAIL_MAILER" id="MAIL_MAILER"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_MAILER') border-red-500 @enderror">
                                <option value="smtp"
                                    {{ old('MAIL_MAILER', $mail->MAIL_MAILER ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP
                                </option>
                                <option value="sendmail"
                                    {{ old('MAIL_MAILER', $mail->MAIL_MAILER ?? '') == 'sendmail' ? 'selected' : '' }}>
                                    Sendmail</option>
                                <option value="mailgun"
                                    {{ old('MAIL_MAILER', $mail->MAIL_MAILER ?? '') == 'mailgun' ? 'selected' : '' }}>
                                    Mailgun</option>
                                <option value="ses"
                                    {{ old('MAIL_MAILER', $mail->MAIL_MAILER ?? '') == 'ses' ? 'selected' : '' }}>Amazon
                                    SES</option>
                                <option value="postmark"
                                    {{ old('MAIL_MAILER', $mail->MAIL_MAILER ?? '') == 'postmark' ? 'selected' : '' }}>
                                    Postmark</option>
                            </select>
                            @error('MAIL_MAILER')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_HOST -->
                        <div>
                            <label for="MAIL_HOST" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Host
                            </label>
                            <input type="text" name="MAIL_HOST" id="MAIL_HOST"
                                value="{{ old('MAIL_HOST', $mail->MAIL_HOST ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_HOST') border-red-500 @enderror"
                                placeholder="live.smtp.mailtrap.io">
                            @error('MAIL_HOST')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_PORT -->
                        <div>
                            <label for="MAIL_PORT" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Port
                            </label>
                            <input type="text" name="MAIL_PORT" id="MAIL_PORT"
                                value="{{ old('MAIL_PORT', $mail->MAIL_PORT ?? '587') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_PORT') border-red-500 @enderror"
                                placeholder="587">
                            @error('MAIL_PORT')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_USERNAME -->
                        <div>
                            <label for="MAIL_USERNAME" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Username
                            </label>
                            <input type="text" name="MAIL_USERNAME" id="MAIL_USERNAME"
                                value="{{ old('MAIL_USERNAME', $mail->MAIL_USERNAME ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_USERNAME') border-red-500 @enderror"
                                placeholder="api atau username">
                            @error('MAIL_USERNAME')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_PASSWORD -->
                        <div>
                            <label for="MAIL_PASSWORD" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Password
                            </label>
                            <div class="relative">
                                <input type="password" name="MAIL_PASSWORD" id="MAIL_PASSWORD" value=""
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_PASSWORD') border-red-500 @enderror"
                                    placeholder="{{ $mail->MAIL_PASSWORD ?? false ? '••••••••' : 'Masukkan password' }}">
                                <button type="button" onclick="togglePassword('MAIL_PASSWORD')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-MAIL_PASSWORD" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-MAIL_PASSWORD" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                            @error('MAIL_PASSWORD')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_ENCRYPTION -->
                        <div>
                            <label for="MAIL_ENCRYPTION" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail Encryption
                            </label>
                            <select name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_ENCRYPTION') border-red-500 @enderror">
                                <option value=""
                                    {{ old('MAIL_ENCRYPTION', $mail->MAIL_ENCRYPTION ?? '') == '' ? 'selected' : '' }}>None
                                </option>
                                <option value="tls"
                                    {{ old('MAIL_ENCRYPTION', $mail->MAIL_ENCRYPTION ?? '') == 'tls' ? 'selected' : '' }}>
                                    TLS</option>
                                <option value="ssl"
                                    {{ old('MAIL_ENCRYPTION', $mail->MAIL_ENCRYPTION ?? '') == 'ssl' ? 'selected' : '' }}>
                                    SSL</option>
                            </select>
                            @error('MAIL_ENCRYPTION')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_FROM_ADDRESS -->
                        <div>
                            <label for="MAIL_FROM_ADDRESS" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail From Address
                            </label>
                            <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS"
                                value="{{ old('MAIL_FROM_ADDRESS', $mail->MAIL_FROM_ADDRESS ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_FROM_ADDRESS') border-red-500 @enderror"
                                placeholder="noreply@domain.com">
                            @error('MAIL_FROM_ADDRESS')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAIL_FROM_NAME -->
                        <div>
                            <label for="MAIL_FROM_NAME" class="block text-sm font-medium text-gray-700 mb-2">
                                Mail From Name
                            </label>
                            <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME"
                                value="{{ old('MAIL_FROM_NAME', $mail->MAIL_FROM_NAME ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('MAIL_FROM_NAME') border-red-500 @enderror"
                                placeholder="Nama Aplikasi">
                            <p class="text-xs text-gray-500 mt-1">Nama yang muncul sebagai pengirim email</p>
                            @error('MAIL_FROM_NAME')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between space-x-4 pt-6 border-t border-gray-200">
                    <div class="flex space-x-4">
                        <a href="{{ route('konfigurasi-global.show') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                            Batal
                        </a>
                        <button type="button" onclick="resetKonfigurasi()"
                            class="px-6 py-3 border border-red-300 text-red-700 font-medium rounded-lg hover:bg-red-50 transition duration-200">
                            Reset ke Default
                        </button>
                    </div>

                    <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Style untuk file input */
        input[type="file"]::file-selector-button {
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #e5e7eb;
        }

        /* Preview container */
        .preview-container {
            @apply relative inline-block;
        }

        .remove-btn {
            @apply absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition duration-200;
        }

        /* File upload area */
        .file-upload-area {
            transition: all 0.2s ease-in-out;
        }

        .file-upload-area:hover {
            border-color: #3b82f6;
            background-color: rgba(59, 130, 246, 0.05);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Fungsi untuk toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-' + fieldId);
            const eyeSlashIcon = document.getElementById('eye-slash-' + fieldId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        // Preview gambar sebelum upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img src="${e.target.result}" 
                             class="max-h-32 mx-auto border border-gray-300 rounded-lg p-2 bg-white" 
                             alt="Preview">
                        <p class="text-xs text-gray-500 mt-2">${file.name}</p>
                    </div>
                `;
                }

                reader.readAsDataURL(file);
            }
        }

        // Validasi ukuran file sebelum upload
        function validateFileSize(input, maxSizeMB, type) {
            const errorElement = document.getElementById(`${type}-error`);

            if (input.files.length > 0) {
                const file = input.files[0];
                const fileSizeMB = file.size / (1024 * 1024); // Convert bytes to MB

                if (fileSizeMB > maxSizeMB) {
                    // Tampilkan error
                    errorElement.textContent =
                        `Ukuran file terlalu besar (${fileSizeMB.toFixed(2)}MB). Maksimal ${maxSizeMB}MB.`;
                    errorElement.classList.remove('hidden');

                    // Reset input file
                    input.value = '';

                    // Reset preview
                    const preview = document.getElementById(`${type}-preview`);
                    preview.innerHTML = `
                    <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Klik untuk mengunggah ${type === 'logo' ? 'logo' : 'favicon'} baru</p>
                `;

                    return false;
                } else {
                    // Sembunyikan error jika ada
                    errorElement.classList.add('hidden');
                }
            }

            return true;
        }

        // Hapus file (logo/favicon)
        function hapusFile(type) {
            const confirmDelete = confirm(`Yakin ingin menghapus ${type === 'logo' ? 'logo' : 'favicon'}?`);

            if (confirmDelete) {
                const inputId = type === 'logo' ? 'hapus_logo' : 'hapus_favicon';
                document.getElementById(inputId).value = '1';

                // Sembunyikan gambar yang dihapus
                const container = document.getElementById(`${type}-container`);
                if (container) {
                    container.style.display = 'none';
                }

                alert(`${type === 'logo' ? 'Logo' : 'Favicon'} akan dihapus saat Anda menyimpan perubahan.`);
            }
        }

        // Reset konfigurasi ke default
        function resetKonfigurasi() {
            if (confirm(
                    'Yakin ingin mereset semua konfigurasi ke nilai default? Perubahan yang belum disimpan akan hilang.')) {
                // Membuat form untuk POST ke route reset
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('konfigurasi-global.reset') }}";

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = "{{ csrf_token() }}";

                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Tambahkan hidden input untuk file removal
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo_aplikasi');
            const faviconInput = document.getElementById('favicon');

            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    // Reset hapus_logo jika user mengupload file baru
                    document.getElementById('hapus_logo').value = '0';
                });
            }

            if (faviconInput) {
                faviconInput.addEventListener('change', function() {
                    // Reset hapus_favicon jika user mengupload file baru
                    document.getElementById('hapus_favicon').value = '0';
                });
            }

            // Validasi sebelum submit
            const form = document.getElementById('konfigurasiForm');
            form.addEventListener('submit', function(e) {
                // Validasi nama aplikasi
                const namaAplikasi = document.getElementById('nama_aplikasi').value.trim();
                if (!namaAplikasi) {
                    e.preventDefault();
                    alert('Nama Aplikasi wajib diisi!');
                    document.getElementById('nama_aplikasi').focus();
                    return false;
                }

                // Validasi ukuran file sebelum submit
                const logoInput = document.getElementById('logo_aplikasi');
                const faviconInput = document.getElementById('favicon');

                if (logoInput && logoInput.files.length > 0) {
                    if (!validateFileSize(logoInput, 15, 'logo')) {
                        e.preventDefault();
                        return false;
                    }
                }

                if (faviconInput && faviconInput.files.length > 0) {
                    if (!validateFileSize(faviconInput, 15, 'favicon')) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Drag and drop untuk file upload
            const fileAreas = document.querySelectorAll('.file-upload-area');
            fileAreas.forEach(area => {
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('border-primary-500', 'bg-primary-50');
                });

                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-primary-500', 'bg-primary-50');
                });

                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-primary-500', 'bg-primary-50');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const input = this.querySelector('input[type="file"]');
                        if (input) {
                            input.files = files;

                            // Trigger change event
                            const event = new Event('change', {
                                bubbles: true
                            });
                            input.dispatchEvent(event);
                        }
                    }
                });
            });
        });
    </script>
@endpush
