@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ── Hero Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Profil Saya</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi profil dan data akun Super Admin</p>
                </div>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <a href="{{ route('superadmin.profil.edit') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil
                    </a>
                    
                    {{-- Tombol Ubah Email --}}
                    @if(!$user->is_google_user)
                    <a href="{{ route('superadmin.profil.email.edit') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Ubah Email
                    </a>
                    @endif

                    {{-- Tombol Ubah Password --}}
                    @if(!$user->is_google_user)
                    <a href="{{ route('superadmin.profil.password.edit') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-amber-300 text-amber-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-amber-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ubah Password
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm">
                {{ session('info') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg text-sm">
                {{ session('warning') }}
            </div>
        @endif

        {{-- Content Body --}}
        <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

            {{-- ── Profile Header (dengan avatar fallback) ── --}}
            <div>
                @php
                    $inisial = strtoupper(substr($user->username, 0, 1));
                    $avatarColors = [
                        'A' => 'bg-gradient-to-br from-blue-500 to-blue-600',
                        'B' => 'bg-gradient-to-br from-green-500 to-green-600', 
                        'C' => 'bg-gradient-to-br from-purple-500 to-purple-600',
                        'D' => 'bg-gradient-to-br from-pink-500 to-pink-600',
                        'E' => 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                        'F' => 'bg-gradient-to-br from-teal-500 to-teal-600',
                        'G' => 'bg-gradient-to-br from-orange-500 to-orange-600',
                        'H' => 'bg-gradient-to-br from-cyan-500 to-cyan-600',
                        'I' => 'bg-gradient-to-br from-rose-500 to-rose-600',
                        'J' => 'bg-gradient-to-br from-amber-500 to-amber-600',
                        'K' => 'bg-gradient-to-br from-lime-500 to-lime-600',
                        'L' => 'bg-gradient-to-br from-emerald-500 to-emerald-600',
                        'M' => 'bg-gradient-to-br from-sky-500 to-sky-600',
                        'N' => 'bg-gradient-to-br from-violet-500 to-violet-600',
                        'O' => 'bg-gradient-to-br from-fuchsia-500 to-fuchsia-600',
                        'P' => 'bg-gradient-to-br from-rose-500 to-rose-600',
                        'Q' => 'bg-gradient-to-br from-cyan-500 to-cyan-600',
                        'R' => 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                        'S' => 'bg-gradient-to-br from-blue-500 to-blue-600',
                        'T' => 'bg-gradient-to-br from-teal-500 to-teal-600',
                        'U' => 'bg-gradient-to-br from-purple-500 to-purple-600',
                        'V' => 'bg-gradient-to-br from-pink-500 to-pink-600',
                        'W' => 'bg-gradient-to-br from-emerald-500 to-emerald-600',
                        'X' => 'bg-gradient-to-br from-orange-500 to-orange-600',
                        'Y' => 'bg-gradient-to-br from-amber-500 to-amber-600',
                        'Z' => 'bg-gradient-to-br from-lime-500 to-lime-600',
                    ];
                    $avatarColor = $avatarColors[$inisial] ?? 'bg-gradient-to-br from-primary to-primary-600';
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 mx-auto sm:mx-0">
                        <div class="relative">
                            <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full overflow-hidden shadow-sm {{ $avatarColor }} flex items-center justify-center">
                                <span class="text-white text-2xl sm:text-3xl font-bold">{{ $inisial }}</span>
                            </div>
                            @if($user->is_active)
                                <span class="absolute bottom-0.5 right-0.5 w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-white bg-emerald-500"></span>
                            @else
                                <span class="absolute bottom-0.5 right-0.5 w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-white bg-red-500"></span>
                            @endif
                        </div>
                    </div>

                    {{-- User Info --}}
                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                                {{ $user->username }}
                            </h2>
                        </div>

                        <p class="text-xs sm:text-sm text-gray-500 mb-3">
                            {{ $user->email }}
                        </p>

                        <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Super Admin
                            </span>
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    Tidak Aktif
                                </span>
                            @endif
                            @if($user->is_google_user)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                    </svg>
                                    Google
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- ── Data Akun ── --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Username</label>
                        <p class="text-sm text-gray-900">{{ $user->username ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email</label>
                        <p class="text-sm text-gray-900">{{ $user->email ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Peran</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                            Super Admin
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status Akun</label>
                        @if($user->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                Tidak Aktif
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email Terverifikasi</label>
                        @if($user->is_email_verified)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                Belum Terverifikasi
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis Login</label>
                        @if($user->is_google_user)
                            <p class="text-sm text-gray-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Google OAuth
                            </p>
                        @else
                            <p class="text-sm text-gray-900">Username &amp; Password</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bergabung Sejak</label>
                        <p class="text-sm text-gray-900">
                            {{ optional($user->created_at)->format('d F Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Terakhir Diperbarui</label>
                        <p class="text-sm text-gray-900">
                            {{ optional($user->updated_at)->format('d F Y, H:i') ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection