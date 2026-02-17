@extends('layouts.app')

@section('title', 'Konfigurasi Aplikasi')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="" 
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500">Pengaturan</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-900">Konfigurasi Aplikasi</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Konfigurasi Aplikasi</h1>
                        <p class="text-sm text-gray-600 mt-1">Kelola pengaturan umum dan konfigurasi sistem</p>
                    </div>
                    <a href="{{ route('konfigurasi-global.edit') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Konfigurasi
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-10">
                <!-- Branding Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Branding</h2>
                        <p class="text-sm text-gray-600">Logo dan identitas visual aplikasi</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Logo -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-1">Logo Aplikasi</h3>
                                <p class="text-sm text-gray-500">Logo utama aplikasi (300×100px)</p>
                            </div>
                            @if ($config && $config->logo_aplikasi)
                                <div class="relative">
                                    <img src="{{ Storage::url($config->logo_aplikasi) }}" 
                                         alt="Logo Aplikasi"
                                         class="max-h-24 w-auto object-contain">
                                </div>
                            @else
                                <div class="flex items-center justify-center p-8">
                                    <div class="text-center">
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500">Logo belum diunggah</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Favicon -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-1">Favicon</h3>
                                <p class="text-sm text-gray-500">Icon browser (64×64px)</p>
                            </div>
                            @if ($config && $config->favicon)
                                <div class="relative">
                                    <img src="{{ Storage::url($config->favicon) }}" 
                                         alt="Favicon"
                                         class="w-16 h-16 object-contain">
                                </div>
                            @else
                                <div class="flex items-center justify-center p-8">
                                    <div class="text-center">
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500">Favicon belum diunggah</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Application Info Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Informasi Aplikasi</h2>
                        <p class="text-sm text-gray-600">Detail identitas aplikasi</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <span class="text-sm font-medium text-gray-900">Nama Aplikasi</span>
                                <p class="text-base text-gray-700">{{ $config->nama_aplikasi ?? 'Belum diatur' }}</p>
                            </div>
                            <div class="space-y-2">
                                <span class="text-sm font-medium text-gray-900">Versi</span>
                                <p class="text-base text-gray-700">{{ $config->versi ?? 'Belum diatur' }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Tagline</span>
                            <p class="text-base text-gray-700">{{ $config->tagline ?? 'Belum diatur' }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Deskripsi</span>
                            <div class="mt-2">
                                @if ($config && $config->deskripsi_aplikasi)
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $config->deskripsi_aplikasi }}</p>
                                @else
                                    <p class="text-gray-400 italic">Belum diatur</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Kontak</h2>
                        <p class="text-sm text-gray-600">Informasi kontak admin</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Email Admin</span>
                            @if ($config && $config->email_admin)
                                <a href="mailto:{{ $config->email_admin }}"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $config->email_admin }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Telepon Admin</span>
                            @if ($config && $config->telepon_admin)
                                <a href="tel:{{ $config->telepon_admin }}"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $config->telepon_admin }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">WhatsApp Support</span>
                            @if ($config && $config->whatsapp_support)
                                <a href="https://wa.me/{{ $config->whatsapp_support }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $config->whatsapp_support }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2 space-y-2">
                            <span class="text-sm font-medium text-gray-900">Alamat Kantor</span>
                            @if ($config && $config->alamat_kantor)
                                <p class="text-gray-700 whitespace-pre-line mt-1">{{ $config->alamat_kantor }}</p>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social Media Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Media Sosial</h2>
                        <p class="text-sm text-gray-600">Tautan media sosial resmi</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Facebook</span>
                            @if ($config && $config->facebook_url)
                                <a href="{{ $config->facebook_url }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline truncate">
                                    {{ $config->facebook_url }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Instagram</span>
                            @if ($config && $config->instagram_url)
                                <a href="{{ $config->instagram_url }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline truncate">
                                    {{ $config->instagram_url }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Twitter</span>
                            @if ($config && $config->twitter_url)
                                <a href="{{ $config->twitter_url }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline truncate">
                                    {{ $config->twitter_url }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">YouTube</span>
                            @if ($config && $config->youtube_url)
                                <a href="{{ $config->youtube_url }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline truncate">
                                    {{ $config->youtube_url }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- reCAPTCHA Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">reCAPTCHA</h2>
                        <p class="text-sm text-gray-600">Kunci untuk verifikasi reCAPTCHA</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Site Key</span>
                            @if ($recaptcha && $recaptcha->RECAPTCHA_SITE_KEY)
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-700 font-mono truncate flex-1" id="site-key-text">
                                        @php
                                            $siteKey = $recaptcha->RECAPTCHA_SITE_KEY;
                                            $maskedSiteKey = substr($siteKey, 0, 5) . str_repeat('•', max(0, strlen($siteKey) - 5));
                                        @endphp
                                        {{ $maskedSiteKey }}
                                    </p>
                                    <button type="button"
                                        onclick="toggleVisibility('site-key', '{{ $recaptcha->RECAPTCHA_SITE_KEY }}')"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg id="site-key-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="site-key-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Secret Key</span>
                            @if ($recaptcha && $recaptcha->RECAPTCHA_SECRET_KEY)
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-700 font-mono truncate flex-1" id="secret-key-text">
                                        @php
                                            $secretKey = $recaptcha->RECAPTCHA_SECRET_KEY;
                                            $maskedSecretKey = substr($secretKey, 0, 5) . str_repeat('•', max(0, strlen($secretKey) - 5));
                                        @endphp
                                        {{ $maskedSecretKey }}
                                    </p>
                                    <button type="button"
                                        onclick="toggleVisibility('secret-key', '{{ $recaptcha->RECAPTCHA_SECRET_KEY }}')"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg id="secret-key-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="secret-key-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Google OAuth Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Google OAuth</h2>
                        <p class="text-sm text-gray-600">Kredensial untuk login dengan Google</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <span class="text-sm font-medium text-gray-900">Client ID</span>
                                @if ($google && $google->GOOGLE_CLIENT_ID)
                                    <div class="flex items-center gap-2">
                                        <p class="text-gray-700 font-mono truncate flex-1" id="client-id-text">
                                            @php
                                                $clientId = $google->GOOGLE_CLIENT_ID;
                                                $maskedClientId = substr($clientId, 0, 5) . str_repeat('•', max(0, strlen($clientId) - 5));
                                            @endphp
                                            {{ $maskedClientId }}
                                        </p>
                                        <button type="button"
                                            onclick="toggleVisibility('client-id', '{{ $google->GOOGLE_CLIENT_ID }}')"
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <svg id="client-id-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="client-id-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <p class="text-gray-400">Belum diatur</p>
                                @endif
                            </div>
                            
                            <div class="space-y-2">
                                <span class="text-sm font-medium text-gray-900">Client Secret</span>
                                @if ($google && $google->GOOGLE_CLIENT_SECRET)
                                    <div class="flex items-center gap-2">
                                        <p class="text-gray-700 font-mono truncate flex-1" id="client-secret-text">
                                            @php
                                                $clientSecret = $google->GOOGLE_CLIENT_SECRET;
                                                $maskedClientSecret = substr($clientSecret, 0, 5) . str_repeat('•', max(0, strlen($clientSecret) - 5));
                                            @endphp
                                            {{ $maskedClientSecret }}
                                        </p>
                                        <button type="button"
                                            onclick="toggleVisibility('client-secret', '{{ $google->GOOGLE_CLIENT_SECRET }}')"
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <svg id="client-secret-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="client-secret-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <p class="text-gray-400">Belum diatur</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Redirect URI</span>
                            @if ($google && $google->GOOGLE_REDIRECT_URI)
                                <p class="text-gray-700 font-mono break-all">{{ $google->GOOGLE_REDIRECT_URI }}</p>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Email SMTP Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Konfigurasi Email (SMTP)</h2>
                        <p class="text-sm text-gray-600">Pengaturan server email untuk pengiriman</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Mailer</span>
                            <p class="text-gray-700">{{ $mail->MAIL_MAILER ?? 'smtp' }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Host</span>
                            @if ($mail && $mail->MAIL_HOST)
                                <p class="text-gray-700">{{ $mail->MAIL_HOST }}</p>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Port</span>
                            <p class="text-gray-700">{{ $mail->MAIL_PORT ?? '587' }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Username</span>
                            @if ($mail && $mail->MAIL_USERNAME)
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-700 font-mono truncate flex-1" id="mail-username-text">
                                        @php
                                            $mailUsername = $mail->MAIL_USERNAME;
                                            $maskedMailUsername = substr($mailUsername, 0, 3) . str_repeat('•', max(0, strlen($mailUsername) - 3));
                                        @endphp
                                        {{ $maskedMailUsername }}
                                    </p>
                                    <button type="button"
                                        onclick="toggleVisibility('mail-username', '{{ $mail->MAIL_USERNAME }}')"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg id="mail-username-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="mail-username-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Encryption</span>
                            @if ($mail && $mail->MAIL_ENCRYPTION)
                                <p class="text-gray-700 uppercase">{{ $mail->MAIL_ENCRYPTION }}</p>
                            @else
                                <p class="text-gray-400">None</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">From Address</span>
                            @if ($mail && $mail->MAIL_FROM_ADDRESS)
                                <p class="text-gray-700">{{ $mail->MAIL_FROM_ADDRESS }}</p>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2 md:col-span-2 lg:col-span-3">
                            <span class="text-sm font-medium text-gray-900">From Name</span>
                            @if ($mail && $mail->MAIL_FROM_NAME)
                                <p class="text-gray-700">{{ $mail->MAIL_FROM_NAME }}</p>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const maskedValues = {
        'mail-username': '{{ isset($mail->MAIL_USERNAME) ? substr($mail->MAIL_USERNAME, 0, 3) . "••••••" : "" }}',
        'site-key': '{{ isset($recaptcha->RECAPTCHA_SITE_KEY) ? substr($recaptcha->RECAPTCHA_SITE_KEY, 0, 5) . "••••••" : "" }}',
        'secret-key': '{{ isset($recaptcha->RECAPTCHA_SECRET_KEY) ? substr($recaptcha->RECAPTCHA_SECRET_KEY, 0, 5) . "••••••" : "" }}',
        'client-id': '{{ isset($google->GOOGLE_CLIENT_ID) ? substr($google->GOOGLE_CLIENT_ID, 0, 5) . "••••••" : "" }}',
        'client-secret': '{{ isset($google->GOOGLE_CLIENT_SECRET) ? substr($google->GOOGLE_CLIENT_SECRET, 0, 5) . "••••••" : "" }}'
    };

    function toggleVisibility(fieldId, fullValue) {
        const textElement = document.getElementById(fieldId + '-text');
        const eyeOpen = document.getElementById(fieldId + '-eye-open');
        const eyeClosed = document.getElementById(fieldId + '-eye-closed');

        if (eyeOpen.classList.contains('hidden')) {
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
            textElement.textContent = maskedValues[fieldId];
        } else {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
            textElement.textContent = fullValue;
        }
    }
</script>
@endpush