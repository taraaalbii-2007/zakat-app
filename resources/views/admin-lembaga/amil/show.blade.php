{{-- resources/views/amil/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Amil - ' . $amil->nama_lengkap)

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Amil</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap data amil</p>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">

                {{-- ── Profile Card ── --}}
                <div class="pb-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        {{-- Avatar --}}
                        <div
                            class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-primary/10 flex items-center justify-center">
                            <span class="text-2xl sm:text-3xl font-bold text-primary">
                                {{ strtoupper(substr($amil->nama_lengkap, 0, 1)) }}
                            </span>
                        </div>
                        <div class="w-full">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                                {{ $amil->nama_lengkap }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $amil->kode_amil }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                {{-- Badge Status --}}
                                @php
                                    $statusLabel = match ($amil->status) {
                                        'aktif'    => 'Aktif',
                                        'nonaktif' => 'Nonaktif',
                                        'cuti'     => 'Cuti',
                                        default    => ucfirst($amil->status),
                                    };
                                    $statusColor = match ($amil->status) {
                                        'aktif'    => 'bg-green-100 text-green-800',
                                        'nonaktif' => 'bg-red-100 text-red-800',
                                        'cuti'     => 'bg-yellow-100 text-yellow-800',
                                        default    => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>

                                {{-- Badge Jenis Kelamin --}}
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $amil->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>

                                {{-- Badge Masjid --}}
                                @if ($amil->masjid)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $amil->masjid->nama }}
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
                            <button type="button" onclick="switchTab('pribadi')" id="tab-pribadi"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none">
                                Data Pribadi
                            </button>
                            <button type="button" onclick="switchTab('tugas')" id="tab-tugas"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Data Tugas
                            </button>
                            @if ($amil->lembaga)
                                <button type="button" onclick="switchTab('lembaga')" id="tab-lembaga"
                                    class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                    Lembaga
                                </button>
                            @endif
                        </nav>
                    </div>

                    {{-- ── TAB: Data Pribadi ── --}}
                    <div id="content-pribadi" class="tab-content mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kolom kiri --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Dasar</h4>
                                <div class="space-y-3">

                                    {{-- Kode Amil --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Kode Amil</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $amil->kode_amil }}</p>
                                        </div>
                                    </div>

                                    {{-- Nama Lengkap --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Nama Lengkap</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</p>
                                        </div>
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $amil->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Tempat, Tanggal Lahir --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $amil->tempat_lahir }},
                                                {{ $amil->tanggal_lahir->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Usia: {{ $amil->tanggal_lahir->age }} tahun
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Alamat --}}
                                    @if ($amil->alamat)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Alamat</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->alamat }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Kolom kanan --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Kontak & Status</h4>
                                <div class="space-y-3">

                                    {{-- Status Amil --}}
                                    <div
                                        class="p-4 rounded-xl border {{ $amil->status === 'aktif' ? 'bg-green-50 border-green-200' : ($amil->status === 'cuti' ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200') }}">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p
                                                    class="text-xs font-medium {{ $amil->status === 'aktif' ? 'text-green-700' : ($amil->status === 'cuti' ? 'text-yellow-700' : 'text-red-700') }}">
                                                    Status Tugas</p>
                                                <p
                                                    class="text-sm font-bold mt-0.5 {{ $amil->status === 'aktif' ? 'text-green-900' : ($amil->status === 'cuti' ? 'text-yellow-900' : 'text-red-900') }}">
                                                    {{ $statusLabel }}
                                                </p>
                                            </div>
                                            <div
                                                class="w-10 h-10 rounded-full {{ $amil->status === 'aktif' ? 'bg-green-200' : ($amil->status === 'cuti' ? 'bg-yellow-200' : 'bg-red-200') }} flex items-center justify-center">
                                                @if ($amil->status === 'aktif')
                                                    <svg class="w-5 h-5 text-green-700" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @elseif ($amil->status === 'cuti')
                                                    <svg class="w-5 h-5 text-yellow-700" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-700" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Telepon --}}
                                    @if ($amil->telepon)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Telepon</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->telepon }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Email --}}
                                    @if ($amil->email)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Email</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->email }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Timestamps --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Dibuat / Diperbarui</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $amil->created_at->translatedFormat('d F Y, H:i') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Diperbarui: {{ $amil->updated_at->translatedFormat('d F Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Data Tugas ── --}}
                    <div id="content-tugas" class="tab-content hidden mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kolom kiri --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Penempatan</h4>
                                <div class="space-y-3">

                                    {{-- Masjid Bertugas --}}
                                    @if ($amil->masjid)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Masjid Bertugas</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->masjid->nama }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $amil->masjid->kode_masjid }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Wilayah Tugas --}}
                                    @if ($amil->wilayah_tugas)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Wilayah Tugas</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->wilayah_tugas }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Status Tugas --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Status Tugas</p>
                                            <div class="mt-0.5">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom kanan --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Periode Tugas</h4>
                                <div class="space-y-3">

                                    {{-- Tanggal Mulai --}}
                                    @if ($amil->tanggal_mulai_tugas)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Tanggal Mulai Tugas</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $amil->tanggal_mulai_tugas->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Tanggal Selesai --}}
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Tanggal Selesai Tugas</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                @if ($amil->tanggal_selesai_tugas)
                                                    {{ $amil->tanggal_selesai_tugas->translatedFormat('d F Y') }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum ditentukan</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Masa Tugas --}}
                                    @if ($amil->tanggal_mulai_tugas)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Masa Tugas</p>
                                                @php
                                                    $endDate   = $amil->tanggal_selesai_tugas ?? now();
                                                    $masaTugas = $amil->tanggal_mulai_tugas->diffForHumans($endDate, true);
                                                @endphp
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $masaTugas }}
                                                    @if (!$amil->tanggal_selesai_tugas)
                                                        <span class="text-xs text-gray-400">(hingga saat ini)</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Keterangan --}}
                                    @if ($amil->keterangan)
                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Keterangan</p>
                                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $amil->keterangan }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Lembaga (Optional) ── --}}
                    @if ($amil->lembaga)
                        <div id="content-lembaga" class="tab-content hidden mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Lembaga</h4>
                                    <div class="space-y-3">

                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Kode Lembaga</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->lembaga->kode_lembaga }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Nama Lembaga</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $amil->lembaga->nama }}</p>
                                            </div>
                                        </div>

                                        @if ($amil->lembaga->alamat)
                                            <div class="flex items-start">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-xs text-gray-500">Alamat Lembaga</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->lembaga->alamat }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── Footer Actions ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('amil.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                    <div class="flex items-center gap-2 flex-wrap">

                        {{-- Toggle Status --}}
                        <button type="button" onclick="toggleStatus()" id="toggle-status-btn"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-colors shadow-sm
                            {{ $amil->status === 'aktif' ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span id="toggle-status-text">
                                {{ $amil->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </span>
                        </button>

                        {{-- Hapus --}}
                        <button type="button" onclick="confirmDelete()"
                            class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>

                        {{-- Edit --}}
                        <a href="{{ route('amil.edit', $amil->uuid) }}"
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
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 text-center">Hapus Amil</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Hapus data amil "<span class="font-semibold text-gray-700">{{ $amil->nama_lengkap }}</span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form action="{{ route('amil.destroy', $amil->uuid) }}" method="POST" class="inline">
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
        // ── Tab Switching ──
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

        // ── Delete Modal ──
        function confirmDelete() {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('delete-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });

        // ── Toggle Status (AJAX) ──
        async function toggleStatus() {
            const button = document.getElementById('toggle-status-btn');
            const buttonText = document.getElementById('toggle-status-text');
            const originalText = buttonText.textContent.trim();

            buttonText.textContent = 'Memproses...';
            button.disabled = true;

            try {
                const response = await fetch(`{{ route('amil.toggle-status', $amil->uuid) }}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Update badge di profile card
                    const statusBadge = document.getElementById('status-badge');
                    if (statusBadge && data.status_badge_html) {
                        statusBadge.innerHTML = data.status_badge_html;
                    }

                    // Update tombol
                    if (data.new_status === 'aktif') {
                        buttonText.textContent = 'Nonaktifkan';
                        button.className = button.className
                            .replace('bg-green-600 hover:bg-green-700', 'bg-yellow-500 hover:bg-yellow-600');
                    } else {
                        buttonText.textContent = 'Aktifkan';
                        button.className = button.className
                            .replace('bg-yellow-500 hover:bg-yellow-600', 'bg-green-600 hover:bg-green-700');
                    }

                    showFlash(data.message || 'Status berhasil diubah!', 'success');
                } else {
                    throw new Error(data.message || 'Gagal mengubah status');
                }
            } catch (error) {
                showFlash(error.message, 'error');
                buttonText.textContent = originalText;
            } finally {
                button.disabled = false;
            }
        }

        // ── Flash Message ──
        function showFlash(message, type) {
            document.querySelectorAll('.flash-dynamic').forEach(el => el.remove());

            const isSuccess = type === 'success';
            const div = document.createElement('div');
            div.className = `flash-dynamic ${isSuccess ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'} border px-4 py-3 rounded-xl flex items-start animate-slide-down`;

            const iconPath = isSuccess
                ? 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                : 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z';

            div.innerHTML = `
                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd" />
                </svg>
                <span>${message}</span>`;

            const wrapper = document.querySelector('.space-y-4');
            if (wrapper) wrapper.insertBefore(div, wrapper.firstChild);

            setTimeout(() => {
                div.style.transition = 'all 0.3s ease';
                div.style.opacity = '0';
                div.style.transform = 'translateY(-10px)';
                setTimeout(() => div.remove(), 300);
            }, 5000);
        }

        // ── Auto-hide session flash ──
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                ['flash-success', 'flash-error'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.style.transition = 'all 0.3s ease';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 300);
                    }
                });
            }, 5000);
        });
    </script>
@endpush