@extends('layouts.app')

@section('title', 'Konfigurasi Aplikasi')

@push('styles')
<style>
    .config-card {
        @apply bg-white rounded-xl border border-neutral-200 shadow-sm p-6;
    }
    
    .config-label {
        @apply text-sm font-medium text-neutral-700 mb-1;
    }
    
    .config-value {
        @apply text-neutral-900;
    }
    
    .config-value-empty {
        @apply text-neutral-400 italic;
    }
    
    .social-icon {
        @apply w-5 h-5 text-neutral-600;
    }
    
    .file-preview {
        @apply bg-neutral-50 border border-neutral-200 rounded-lg p-4;
    }
    
    .file-empty {
        @apply bg-neutral-50 border border-dashed border-neutral-300 rounded-lg p-8 text-center;
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Konfigurasi Aplikasi</h1>
        <p class="text-neutral-600 mt-1">Kelola pengaturan umum aplikasi</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('konfigurasi-global.edit') }}" 
           class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-lg font-semibold text-white hover:bg-primary-dark transition duration-150 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Konfigurasi
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informasi Aplikasi -->
    <div class="lg:col-span-2">
        <div class="config-card">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
                Informasi Aplikasi
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="config-label">Nama Aplikasi</p>
                    <p class="config-value">{{ $config->nama_aplikasi }}</p>
                </div>
                
                <div>
                    <p class="config-label">Tagline</p>
                    <p class="config-value {{ !$config->tagline ? 'config-value-empty' : '' }}">
                        {{ $config->tagline ?? 'Belum diatur' }}
                    </p>
                </div>
                
                <div class="md:col-span-2">
                    <p class="config-label">Deskripsi Aplikasi</p>
                    <p class="config-value {{ !$config->deskripsi_aplikasi ? 'config-value-empty' : '' }}">
                        {{ $config->deskripsi_aplikasi ?? 'Belum diatur' }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Kontak & Support -->
        <div class="config-card mt-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
                Kontak & Support
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="config-label">Email Support</p>
                    <p class="config-value {{ !$config->email_support ? 'config-value-empty' : '' }}">
                        {{ $config->email_support ?? 'Belum diatur' }}
                    </p>
                </div>
                
                <div>
                    <p class="config-label">Telepon Support</p>
                    <p class="config-value {{ !$config->telepon_support ? 'config-value-empty' : '' }}">
                        {{ $config->telepon_support ?? 'Belum diatur' }}
                    </p>
                </div>
                
                <div>
                    <p class="config-label">WhatsApp Support</p>
                    <p class="config-value {{ !$config->whatsapp_support ? 'config-value-empty' : '' }}">
                        {{ $config->whatsapp_support ?? 'Belum diatur' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logo & Media Sosial -->
    <div class="lg:col-span-1">
        <!-- Logo & Favicon -->
        <div class="config-card">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
                Branding
            </h2>
            
            <div class="space-y-6">
                <div>
                    <p class="config-label mb-3">Logo Aplikasi</p>
                    @if($config->logo_aplikasi)
                        <div class="file-preview">
                            <img src="{{ Storage::url($config->logo_aplikasi) }}" 
                                 alt="Logo Aplikasi" 
                                 class="max-h-32 mx-auto">
                        </div>
                    @else
                        <div class="file-empty">
                            <svg class="w-12 h-12 text-neutral-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-neutral-500 text-sm">Logo belum diunggah</p>
                        </div>
                    @endif
                </div>
                
                <div>
                    <p class="config-label mb-3">Favicon</p>
                    @if($config->favicon)
                        <div class="file-preview">
                            <img src="{{ Storage::url($config->favicon) }}" 
                                 alt="Favicon" 
                                 class="w-16 h-16 mx-auto">
                        </div>
                    @else
                        <div class="file-empty">
                            <svg class="w-12 h-12 text-neutral-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                            <p class="text-neutral-500 text-sm">Favicon belum diunggah</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Media Sosial -->
        <div class="config-card mt-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4 pb-3 border-b border-neutral-200">
                Media Sosial
            </h2>
            
            <div class="space-y-4">
                <div>
                    <p class="config-label">Facebook</p>
                    @if($config->facebook_url)
                        <a href="{{ $config->facebook_url }}" target="_blank" class="inline-flex items-center text-primary hover:text-primary-dark">
                            <svg class="social-icon mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="truncate">{{ $config->facebook_url }}</span>
                        </a>
                    @else
                        <p class="config-value-empty">Belum diatur</p>
                    @endif
                </div>
                
                <div>
                    <p class="config-label">Instagram</p>
                    @if($config->instagram_url)
                        <a href="{{ $config->instagram_url }}" target="_blank" class="inline-flex items-center text-primary hover:text-primary-dark">
                            <svg class="social-icon mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span class="truncate">{{ $config->instagram_url }}</span>
                        </a>
                    @else
                        <p class="config-value-empty">Belum diatur</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection