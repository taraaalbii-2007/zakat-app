@extends('layouts.app')

@section('title', 'Konfigurasi Aplikasi - Superadmin')

@section('page-title', 'Konfigurasi Aplikasi')
@section('page-description', 'Kelola pengaturan aplikasi zakat digital')

@section('breadcrumbs')
    <nav class="breadcrumb">
        <a href="#" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-item active">Konfigurasi Aplikasi</span>
    </nav>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-modal p-6 animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Konfigurasi</h2>
            <p class="text-gray-600">Informasi pengaturan aplikasi</p>
        </div>
        <a href="{{ route('superadmin.konfigurasi.edit') }}" 
           class="btn-gradient-primary px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Konfigurasi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informasi Aplikasi -->
        <div class="space-y-6">
            <div class="border-l-4 border-gradient-start pl-4">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Aplikasi</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama Aplikasi</label>
                    <p class="mt-1 text-gray-900">{{ $konfigurasi->nama_aplikasi }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tagline</label>
                    <p class="mt-1 text-gray-900">{{ $konfigurasi->tagline ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                    <p class="mt-1 text-gray-900">{{ $konfigurasi->deskripsi_aplikasi ?? '-' }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Logo Aplikasi</label>
                        @if($konfigurasi->logo_aplikasi)
                            <div class="mt-2">
                                <img src="{{ Storage::url($konfigurasi->logo_aplikasi) }}" 
                                     alt="Logo Aplikasi" 
                                     class="h-20 w-auto object-contain">
                            </div>
                        @else
                            <p class="mt-1 text-gray-500 italic">Belum ada logo</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Favicon</label>
                        @if($konfigurasi->favicon)
                            <div class="mt-2">
                                <img src="{{ Storage::url($konfigurasi->favicon) }}" 
                                     alt="Favicon" 
                                     class="h-16 w-auto object-contain">
                            </div>
                        @else
                            <p class="mt-1 text-gray-500 italic">Belum ada favicon</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak & Sosial Media -->
        <div class="space-y-6">
            <div class="border-l-4 border-gradient-start pl-4">
                <h3 class="text-lg font-semibold text-gray-800">Kontak & Sosial Media</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email Support</label>
                    <p class="mt-1 text-gray-900">{{ $konfigurasi->email_support ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Telepon Support</label>
                    <p class="mt-1 text-gray-900">{{ $konfigurasi->telepon_support ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">WhatsApp Support</label>
                    <p class="mt-1 text-gray-900">{{ $konfigurasi->whatsapp_support ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Facebook</label>
                    @if($konfigurasi->facebook_url)
                        <a href="{{ $konfigurasi->facebook_url }}" target="_blank" 
                           class="mt-1 text-gradient-start hover:underline flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            {{ $konfigurasi->facebook_url }}
                        </a>
                    @else
                        <p class="mt-1 text-gray-500 italic">Belum diatur</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Instagram</label>
                    @if($konfigurasi->instagram_url)
                        <a href="{{ $konfigurasi->instagram_url }}" target="_blank" 
                           class="mt-1 text-gradient-start hover:underline flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            {{ $konfigurasi->instagram_url }}
                        </a>
                    @else
                        <p class="mt-1 text-gray-500 italic">Belum diatur</p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection