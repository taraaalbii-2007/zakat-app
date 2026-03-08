@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- ── Card Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Profil Saya</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi profil, akun dan data masjid</p>
                </div>
                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin-masjid.profil.edit') }}"
                       class="inline-flex items-center px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil
                    </a>
                    @if(!$user->is_google_user)
                    <a href="{{ route('admin-masjid.profil.email.edit') }}"
                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Ubah Email
                    </a>
                    <a href="{{ route('admin-masjid.profil.password.edit') }}"
                       class="inline-flex items-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Ubah Password
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-8">

            {{-- ══════════════════════════════════
                 BAGIAN 1 — DATA AKUN
            ══════════════════════════════════ --}}
            <div>
                {{-- Profile Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden ring-4 ring-emerald-100 bg-gray-100 mx-auto sm:mx-0">
                            <img src="{{ $masjid->admin_foto_url }}"
                                 alt="Foto Admin"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($masjid->admin_nama ?? $user->username) }}&background=059669&color=fff&size=96'">
                        </div>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-0.5">
                            {{ $masjid->admin_nama ?? $user->username }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mb-3">{{ $user->email }}</p>
                        <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Admin Masjid
                            </span>
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Tidak Aktif</span>
                            @endif
                            @if($user->is_google_user)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24">
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

                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    Data Akun
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Username</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->username ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email Akun</p>
                        <div class="flex items-center gap-2">
                            <p class="text-sm text-gray-900 font-medium">{{ $user->email ?? '-' }}</p>
                            @if(!$user->is_google_user)
                            <a href="{{ route('admin-masjid.profil.email.edit') }}"
                               class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition flex-shrink-0">
                                Ubah
                            </a>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Status Akun</p>
                        @if($user->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Tidak Aktif</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                        <p class="text-sm text-gray-900">{{ optional($user->created_at)->format('d F Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Terakhir Diperbarui</p>
                        <p class="text-sm text-gray-900">{{ optional($user->updated_at)->format('d F Y, H:i') ?? '-' }}</p>
                    </div>

                    {{-- Keamanan Akun --}}
                    @if(!$user->is_google_user)
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Keamanan</p>
                        <a href="{{ route('admin-masjid.profil.password.edit') }}"
                           class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Ubah Password
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- ══════════════════════════════════
                 BAGIAN 2 — DATA ADMIN MASJID
            ══════════════════════════════════ --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    Data Admin Masjid
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Admin</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $masjid->admin_nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">No. Telepon Admin</p>
                        <p class="text-sm text-gray-900">{{ $masjid->admin_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email Admin</p>
                        <p class="text-sm text-gray-900">{{ $masjid->admin_email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- ══════════════════════════════════
                 BAGIAN 3 — DATA MASJID
            ══════════════════════════════════ --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    Data Masjid
                </h3>

                @if($masjid->foto_count > 0)
                <div class="mb-5">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Foto Masjid</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($masjid->foto_urls as $i => $url)
                        <div class="relative">
                            <img src="{{ $url }}" alt="Foto {{ $i+1 }}"
                                 class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-xl border border-gray-200 shadow-sm">
                            @if($i === 0)
                                <span class="absolute top-1.5 left-1.5 bg-emerald-600 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded-md">Utama</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div class="md:col-span-2 lg:col-span-3">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Masjid</p>
                        <p class="text-base text-gray-900 font-semibold">{{ $masjid->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kode Masjid</p>
                        <span class="text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded-md inline-block border border-gray-100">{{ $masjid->kode_masjid ?? '-' }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Telepon Masjid</p>
                        <p class="text-sm text-gray-900">{{ $masjid->telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email Masjid</p>
                        <p class="text-sm text-gray-900">{{ $masjid->email ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Alamat Lengkap</p>
                        <p class="text-sm text-gray-900">{{ $masjid->alamat_lengkap ?? '-' }}</p>
                    </div>
                    @if($masjid->deskripsi)
                    <div class="md:col-span-2 lg:col-span-3">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Deskripsi</p>
                        <p class="text-sm text-gray-900 leading-relaxed">{{ $masjid->deskripsi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- ══════════════════════════════════
                 BAGIAN 4 — SEJARAH MASJID
            ══════════════════════════════════ --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    Sejarah Masjid
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Tahun Berdiri</p>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $masjid->tahun_berdiri ?? '-' }}
                            @if($masjid->usia_masjid)
                                <span class="text-xs text-gray-400 ml-1">({{ $masjid->usia_masjid }} tahun)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Pendiri</p>
                        <p class="text-sm text-gray-900">{{ $masjid->pendiri ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kapasitas Jamaah</p>
                        <p class="text-sm text-gray-900">
                            {{ $masjid->kapasitas_jamaah ? number_format($masjid->kapasitas_jamaah, 0, ',', '.') . ' orang' : '-' }}
                        </p>
                    </div>
                    @if($masjid->sejarah)
                    <div class="md:col-span-2 lg:col-span-3">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Sejarah</p>
                        <div class="text-sm text-gray-900 leading-relaxed bg-gray-50 rounded-xl p-4 border border-gray-100">
                            {!! nl2br(e($masjid->sejarah)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection