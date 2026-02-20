{{-- resources/views/superadmin/pengguna/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Pengguna</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap akun pengguna</p>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">

                {{-- ── Profile Card ── --}}
                <div class="pb-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-primary/10 flex items-center justify-center">
                            <span class="text-2xl sm:text-3xl font-bold text-primary">
                                {{ strtoupper(substr($pengguna->username ?? $pengguna->email, 0, 1)) }}
                            </span>
                        </div>
                        <div class="w-full">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                                {{ $pengguna->username ?? '(Belum diset)' }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $pengguna->email }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                @php
                                    $peranLabel = match($pengguna->peran) {
                                        'superadmin' => 'Super Admin',
                                        'admin_masjid' => 'Admin Masjid',
                                        'amil' => 'Amil',
                                        default => ucfirst($pengguna->peran)
                                    };
                                    $peranColor = match($pengguna->peran) {
                                        'superadmin' => 'bg-purple-100 text-purple-800',
                                        'admin_masjid' => 'bg-blue-100 text-blue-800',
                                        'amil' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $peranColor }}">
                                    {{ $peranLabel }}
                                </span>
                                @if($pengguna->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                                @if($pengguna->is_google_user)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        </svg>
                                        Google OAuth
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Tabs ── --}}
                <div class="mt-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                            <button type="button" onclick="switchTab('info')" id="tab-info"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none">
                                Informasi Akun
                            </button>
                            @if($pengguna->peran === 'admin_masjid' && $pengguna->masjid)
                                <button type="button" onclick="switchTab('masjid')" id="tab-masjid"
                                    class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                    Data Masjid
                                </button>
                            @endif
                            @if($pengguna->peran === 'amil' && $pengguna->amil)
                                <button type="button" onclick="switchTab('amil')" id="tab-amil"
                                    class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                    Data Amil
                                </button>
                            @endif
                            <button type="button" onclick="switchTab('keamanan')" id="tab-keamanan"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Keamanan
                            </button>
                        </nav>
                    </div>

                    {{-- ── TAB: Informasi ── --}}
                    <div id="content-info" class="tab-content mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kolom kiri --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Data Akun</h4>
                                <div class="space-y-3">

                                    {{-- Username --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Username</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $pengguna->username ?? '-' }}</p>
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Email</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $pengguna->email }}</p>
                                        </div>
                                    </div>

                                    {{-- Peran --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Peran</p>
                                            <div class="mt-0.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $peranColor }}">
                                                    {{ $peranLabel }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Masjid --}}
                                    @if(in_array($pengguna->peran, ['admin_masjid', 'amil']))
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Masjid</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid?->nama ?? '-' }}</p>
                                                @if($pengguna->masjid)
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $pengguna->masjid->kode_masjid }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Kolom kanan --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Status & Aktivitas</h4>
                                <div class="space-y-3">

                                    {{-- Status Akun --}}
                                    <div class="p-4 rounded-xl border {{ $pengguna->is_active ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs {{ $pengguna->is_active ? 'text-green-700' : 'text-red-700' }} font-medium">Status Akun</p>
                                                <p class="text-sm font-bold {{ $pengguna->is_active ? 'text-green-900' : 'text-red-900' }} mt-0.5">
                                                    {{ $pengguna->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </p>
                                            </div>
                                            <div class="w-10 h-10 rounded-full {{ $pengguna->is_active ? 'bg-green-200' : 'bg-red-200' }} flex items-center justify-center">
                                                @if($pengguna->is_active)
                                                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Verifikasi Email --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Verifikasi Email</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                @if($pengguna->email_verified_at)
                                                    {{ $pengguna->email_verified_at->format('d M Y, H:i') }}
                                                @else
                                                    <span class="text-yellow-600 italic">Belum terverifikasi</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Login Method --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Metode Login</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $pengguna->is_google_user ? 'Google OAuth + Password' : 'Email & Password' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Timestamps --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Dibuat / Diperbarui</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $pengguna->created_at->format('d M Y, H:i') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Diperbarui: {{ $pengguna->updated_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Data Masjid (Admin Masjid Only) ── --}}
                    @if($pengguna->peran === 'admin_masjid' && $pengguna->masjid)
                        <div id="content-masjid" class="tab-content hidden mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Informasi Dasar Masjid --}}
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Masjid</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Nama Masjid</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->nama }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $pengguna->masjid->kode_masjid }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Alamat</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->alamat }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $pengguna->masjid->kelurahan_nama }}, {{ $pengguna->masjid->kecamatan_nama }}, 
                                                    {{ $pengguna->masjid->kota_nama }}, {{ $pengguna->masjid->provinsi_nama }}
                                                    {{ $pengguna->masjid->kode_pos ? ' - ' . $pengguna->masjid->kode_pos : '' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Telepon / Email</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->telepon }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $pengguna->masjid->email }}</p>
                                            </div>
                                        </div>

                                        @if($pengguna->masjid->deskripsi)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Deskripsi</p>
                                                    <p class="text-sm text-gray-900">{{ $pengguna->masjid->deskripsi }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Data Tambahan --}}
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Data Tambahan</h4>
                                    <div class="space-y-3">
                                        @if($pengguna->masjid->tahun_berdiri)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Tahun Berdiri</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->tahun_berdiri }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($pengguna->masjid->pendiri)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Pendiri</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->pendiri }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($pengguna->masjid->kapasitas_jamaah)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Kapasitas Jamaah</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ number_format($pengguna->masjid->kapasitas_jamaah) }} orang</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($pengguna->masjid->sejarah)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Sejarah</p>
                                                    <p class="text-sm text-gray-900">{{ $pengguna->masjid->sejarah }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Admin Masjid Info --}}
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mt-6">Admin Masjid</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Nama Admin</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->admin_nama }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Telepon / Email</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->admin_telepon }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $pengguna->masjid->admin_email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ── TAB: Data Amil (Amil Only) ── --}}
                    @if($pengguna->peran === 'amil' && $pengguna->amil)
                        <div id="content-amil" class="tab-content hidden mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Data Pribadi --}}
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Data Pribadi</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Kode Amil</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->amil->kode_amil }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Nama Lengkap</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->amil->nama_lengkap }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->amil->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $pengguna->amil->tempat_lahir }}, {{ \Carbon\Carbon::parse($pengguna->amil->tanggal_lahir)->format('d M Y') }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    Usia: {{ \Carbon\Carbon::parse($pengguna->amil->tanggal_lahir)->age }} tahun
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Alamat</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->amil->alamat }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Telepon</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->amil->telepon }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Data Tugas --}}
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Data Tugas</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Masjid Bertugas</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $pengguna->masjid->nama }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $pengguna->masjid->kode_masjid }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Status Tugas</p>
                                                <div class="mt-0.5">
                                                    @php
                                                        $statusColor = match($pengguna->amil->status) {
                                                            'aktif' => 'bg-green-100 text-green-800',
                                                            'nonaktif' => 'bg-red-100 text-red-800',
                                                            'cuti' => 'bg-yellow-100 text-yellow-800',
                                                            default => 'bg-gray-100 text-gray-800'
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                        {{ ucfirst($pengguna->amil->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500">Periode Tugas</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    Mulai: {{ \Carbon\Carbon::parse($pengguna->amil->tanggal_mulai_tugas)->format('d M Y') }}
                                                </p>
                                                @if($pengguna->amil->tanggal_selesai_tugas)
                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        Selesai: {{ \Carbon\Carbon::parse($pengguna->amil->tanggal_selesai_tugas)->format('d M Y') }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-gray-400 mt-0.5">Selesai: Belum ditentukan</p>
                                                @endif
                                            </div>
                                        </div>

                                        @if($pengguna->amil->wilayah_tugas)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Wilayah Tugas</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $pengguna->amil->wilayah_tugas }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($pengguna->amil->keterangan)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-xs text-gray-500">Keterangan</p>
                                                    <p class="text-sm text-gray-900">{{ $pengguna->amil->keterangan }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ── TAB: Keamanan ── --}}
                    <div id="content-keamanan" class="tab-content hidden mt-6">
                        <div class="max-w-lg space-y-6">

                            {{-- Reset Password --}}
                            <div class="bg-white border border-gray-200 rounded-xl p-5">
                                <h4 class="text-sm font-semibold text-gray-900 mb-1">Reset Password</h4>
                                <p class="text-xs text-gray-500 mb-4">Set password baru untuk pengguna ini secara manual.</p>

                                <form action="{{ route('pengguna.reset-password', $pengguna->uuid) }}" method="POST"
                                    class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="new-password"
                                                placeholder="Minimal 8 karakter"
                                                class="block w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                            <button type="button" onclick="togglePassword('new-password')"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="confirm-password"
                                                placeholder="Ulangi password baru"
                                                class="block w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                            <button type="button" onclick="togglePassword('confirm-password')"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Reset Password
                                    </button>
                                </form>
                            </div>

                            {{-- Toggle Status --}}
                            @if($pengguna->id !== auth()->id())
                                <div class="bg-white border border-gray-200 rounded-xl p-5">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-1">Status Akun</h4>
                                    <p class="text-xs text-gray-500 mb-4">
                                        {{ $pengguna->is_active ? 'Nonaktifkan akun agar pengguna tidak dapat login.' : 'Aktifkan akun agar pengguna dapat kembali login.' }}
                                    </p>
                                    <form action="{{ route('pengguna.toggle-status', $pengguna->uuid) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors shadow-sm
                                            {{ $pengguna->is_active ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                            @if($pengguna->is_active)
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                Nonaktifkan Akun
                                            @else
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Aktifkan Akun
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Footer Actions ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('pengguna.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- Delete (only draft / non-self) --}}
                        @if($pengguna->id !== auth()->id())
                            <button type="button" onclick="confirmDelete()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        @endif
                        <a href="{{ route('pengguna.edit', $pengguna->uuid) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Delete Modal ── --}}
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 text-center">Hapus Pengguna</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Hapus akun "<span class="font-semibold text-gray-700">{{ $pengguna->username ?? $pengguna->email }}</span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form action="{{ route('pengguna.destroy', $pengguna->uuid) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(b => {
            b.classList.remove('border-primary', 'text-primary');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('content-' + tabName).classList.remove('hidden');
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('border-primary', 'text-primary');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }

    function confirmDelete() {
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    document.getElementById('delete-modal')?.addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush