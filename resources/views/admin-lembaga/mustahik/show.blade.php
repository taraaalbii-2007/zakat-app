{{-- resources/views/mustahik/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Mustahik - ' . $mustahik->nama_lengkap)

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Mustahik</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap data mustahik</p>
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
                                {{ strtoupper(substr($mustahik->nama_lengkap, 0, 1)) }}
                            </span>
                        </div>
                        <div class="w-full">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                                {{ $mustahik->nama_lengkap }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $mustahik->no_registrasi }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                {{-- Badge Kategori --}}
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $mustahik->kategoriMustahik->nama ?? '-' }}
                                </span>

                                {{-- Badge Status Verifikasi --}}
                                @php
                                    $verifikasiLabel = match($mustahik->status_verifikasi) {
                                        'verified' => 'Terverifikasi',
                                        'pending'  => 'Pending',
                                        'rejected' => 'Ditolak',
                                        default    => ucfirst($mustahik->status_verifikasi),
                                    };
                                    $verifikasiColor = match($mustahik->status_verifikasi) {
                                        'verified' => 'bg-green-100 text-green-800',
                                        'pending'  => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default    => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $verifikasiColor }}">
                                    {{ $verifikasiLabel }}
                                </span>

                                {{-- Badge Status Aktif --}}
                                @php
                                    $aktifLabel = $mustahik->is_active ? 'Aktif' : 'Tidak Aktif';
                                    $aktifColor = $mustahik->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $aktifColor }}">
                                    {{ $aktifLabel }}
                                </span>
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
                            <button type="button" onclick="switchTab('penyaluran')" id="tab-penyaluran"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Riwayat Penyaluran
                                @if($riwayatPenyaluran->count() > 0)
                                    <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        {{ $riwayatPenyaluran->count() }}
                                    </span>
                                @endif
                            </button>
                            <button type="button" onclick="switchTab('kunjungan')" id="tab-kunjungan"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Riwayat Kunjungan
                                @if($riwayatKunjungan->count() > 0)
                                    <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $riwayatKunjungan->count() }}
                                    </span>
                                @endif
                            </button>
                        </nav>
                    </div>

                    {{-- ── TAB: Data Pribadi ── --}}
                    <div id="content-pribadi" class="tab-content mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kolom kiri --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Dasar</h4>
                                <div class="space-y-3">

                                    {{-- No Registrasi --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">No. Registrasi</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->no_registrasi }}</p>
                                        </div>
                                    </div>

                                    {{-- NIK --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">NIK</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->nik ?: '-' }}</p>
                                        </div>
                                    </div>

                                    {{-- No. KK --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">No. KK</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->kk ?: '-' }}</p>
                                        </div>
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->gender_label }}</p>
                                        </div>
                                    </div>

                                    {{-- Tempat, Tanggal Lahir --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $mustahik->tempat_lahir ?: '-' }},
                                                {{ $mustahik->tanggal_lahir ? $mustahik->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                                            </p>
                                            @if($mustahik->tanggal_lahir)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    Usia: {{ $mustahik->tanggal_lahir->age }} tahun
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Telepon --}}
                                    @if ($mustahik->telepon)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Telepon</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $mustahik->telepon }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Kolom kanan --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Alamat & Domisili</h4>
                                <div class="space-y-3">

                                    {{-- Alamat --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Alamat Lengkap</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->alamat }}</p>
                                        </div>
                                    </div>

                                    {{-- RT/RW --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">RT / RW</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->rt_rw ?: '-' }}</p>
                                        </div>
                                    </div>

                                    {{-- Kelurahan/Kecamatan/Kota --}}
                                    @if($mustahik->kelurahan || $mustahik->kecamatan || $mustahik->kota)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Wilayah</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $mustahik->kelurahan ?: '-' }}{{ $mustahik->kecamatan ? ', ' . $mustahik->kecamatan : '' }}{{ $mustahik->kota ? ', ' . $mustahik->kota : '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Kode Pos --}}
                                    @if ($mustahik->kode_pos)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Kode Pos</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $mustahik->kode_pos }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Data Sosial Ekonomi --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Data Sosial Ekonomi</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    {{-- Pekerjaan --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Pekerjaan</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->pekerjaan ?: '-' }}</p>
                                        </div>
                                    </div>

                                    {{-- Penghasilan --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Penghasilan Per Bulan</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $mustahik->penghasilan_perbulan ? 'Rp ' . number_format($mustahik->penghasilan_perbulan, 0, ',', '.') : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    {{-- Tanggungan --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Jumlah Tanggungan</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->jumlah_tanggungan }} orang</p>
                                        </div>
                                    </div>

                                    {{-- Status Rumah --}}
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Status Rumah</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->status_rumah_label }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Catatan --}}
                            @if ($mustahik->catatan)
                                <div class="mt-4 flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Catatan</p>
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $mustahik->catatan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Dokumen Pendukung --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Dokumen Pendukung</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {{-- Foto KTP --}}
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Foto KTP</p>
                                    @if($mustahik->foto_ktp)
                                        <a href="{{ Storage::url($mustahik->foto_ktp) }}" target="_blank" class="block">
                                            <img src="{{ Storage::url($mustahik->foto_ktp) }}" alt="KTP" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                    @else
                                        <div class="w-full h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <p class="text-xs text-gray-400">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Foto KK --}}
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Foto KK</p>
                                    @if($mustahik->foto_kk)
                                        <a href="{{ Storage::url($mustahik->foto_kk) }}" target="_blank" class="block">
                                            <img src="{{ Storage::url($mustahik->foto_kk) }}" alt="KK" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                    @else
                                        <div class="w-full h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <p class="text-xs text-gray-400">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Foto Rumah --}}
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Foto Rumah</p>
                                    @if($mustahik->foto_rumah)
                                        <a href="{{ Storage::url($mustahik->foto_rumah) }}" target="_blank" class="block">
                                            <img src="{{ Storage::url($mustahik->foto_rumah) }}" alt="Rumah" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                    @else
                                        <div class="w-full h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <p class="text-xs text-gray-400">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Dokumen Lainnya --}}
                            @if($mustahik->dokumen_lainnya && count($mustahik->dokumen_lainnya) > 0)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 mb-2">Dokumen Lainnya</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($mustahik->dokumen_lainnya as $dokumen)
                                            <a href="{{ Storage::url($dokumen) }}" target="_blank" 
                                                class="flex items-center p-2 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="text-xs text-gray-700 truncate">{{ basename($dokumen) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Status Verifikasi --}}
                        @if($mustahik->status_verifikasi !== 'pending')
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Verifikasi</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-xs text-gray-500">Diverifikasi oleh</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $mustahik->verifiedBy->username ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Verifikasi</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $mustahik->verified_at ? $mustahik->verified_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                                    </div>
                                    @if($mustahik->status_verifikasi === 'rejected' && $mustahik->alasan_penolakan)
                                        <div class="md:col-span-2">
                                            <p class="text-xs text-gray-500">Alasan Penolakan</p>
                                            <p class="text-sm font-medium text-red-600">{{ $mustahik->alasan_penolakan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Timestamps --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                <div>
                                    <span>Tanggal Registrasi:</span>
                                    <span class="font-medium text-gray-700">{{ $mustahik->tanggal_registrasi->translatedFormat('d F Y') }}</span>
                                </div>
                                <div>
                                    <span>Dibuat:</span>
                                    <span class="font-medium text-gray-700">{{ $mustahik->created_at->translatedFormat('d F Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span>Diperbarui:</span>
                                    <span class="font-medium text-gray-700">{{ $mustahik->updated_at->translatedFormat('d F Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Riwayat Penyaluran ── --}}
                    <div id="content-penyaluran" class="tab-content hidden mt-6">
                        @if($riwayatPenyaluran->count() > 0)
                            {{-- Ringkasan --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                                <div class="bg-green-50 rounded-xl p-3 text-center border border-green-100">
                                    <p class="text-xs text-green-600 font-medium">Total Penyaluran</p>
                                    <p class="text-lg font-bold text-green-700">{{ $riwayatPenyaluran->count() }}</p>
                                </div>
                                <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-100">
                                    <p class="text-xs text-blue-600 font-medium">Total Disalurkan</p>
                                    <p class="text-sm font-bold text-blue-700">
                                        Rp {{ number_format($riwayatPenyaluran->whereIn('status', ['disetujui','disalurkan'])->sum('jumlah'), 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-teal-50 rounded-xl p-3 text-center border border-teal-100">
                                    <p class="text-xs text-teal-600 font-medium">Selesai</p>
                                    <p class="text-lg font-bold text-teal-700">{{ $riwayatPenyaluran->where('status', 'disalurkan')->count() }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-xl p-3 text-center border border-yellow-100">
                                    <p class="text-xs text-yellow-600 font-medium">Draft/Disetujui</p>
                                    <p class="text-lg font-bold text-yellow-700">{{ $riwayatPenyaluran->whereIn('status', ['draft','disetujui'])->count() }}</p>
                                </div>
                            </div>

                            {{-- Desktop Table --}}
                            <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($riwayatPenyaluran as $penyaluran)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-xs font-mono text-gray-700">{{ $penyaluran->no_transaksi }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                    {{ $penyaluran->tanggal_penyaluran->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-600">
                                                    {{ $penyaluran->programZakat->nama_program ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @php
                                                        $metodeClass = match($penyaluran->metode_penyaluran) {
                                                            'tunai'    => 'bg-green-100 text-green-700',
                                                            'transfer' => 'bg-blue-100 text-blue-700',
                                                            'barang'   => 'bg-orange-100 text-orange-700',
                                                            default    => 'bg-gray-100 text-gray-700',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $metodeClass }}">
                                                        {{ ucfirst($penyaluran->metode_penyaluran) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                                    @if($penyaluran->metode_penyaluran === 'barang')
                                                        @if($penyaluran->nilai_barang)
                                                            <span class="text-sm font-semibold text-gray-800">≈ Rp {{ number_format($penyaluran->nilai_barang, 0, ',', '.') }}</span>
                                                        @else
                                                            <span class="text-xs text-gray-500">Barang</span>
                                                        @endif
                                                    @else
                                                        <span class="text-sm font-semibold text-gray-800">Rp {{ number_format($penyaluran->jumlah, 0, ',', '.') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @php
                                                        $statusClass = match($penyaluran->status) {
                                                            'disalurkan' => 'bg-green-100 text-green-700',
                                                            'disetujui'  => 'bg-blue-100 text-blue-700',
                                                            'draft'      => 'bg-yellow-100 text-yellow-700',
                                                            'dibatalkan' => 'bg-red-100 text-red-700',
                                                            default      => 'bg-gray-100 text-gray-700',
                                                        };
                                                        $statusLabel = match($penyaluran->status) {
                                                            'disalurkan' => 'Disalurkan',
                                                            'disetujui'  => 'Disetujui',
                                                            'draft'      => 'Draft',
                                                            'dibatalkan' => 'Dibatalkan',
                                                            default      => $penyaluran->status,
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <a href="{{ route('transaksi-penyaluran.show', $penyaluran->uuid) }}" target="_blank"
                                                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                追赶
                            </div>

                            {{-- Mobile Cards --}}
                            <div class="md:hidden space-y-3">
                                @foreach($riwayatPenyaluran as $penyaluran)
                                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <p class="text-xs font-mono text-gray-500">{{ $penyaluran->no_transaksi }}</p>
                                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $penyaluran->programZakat->nama_program ?? '-' }}</p>
                                            </div>
                                            <div class="text-right">
                                                @if($penyaluran->metode_penyaluran === 'barang')
                                                    @if($penyaluran->nilai_barang)
                                                        <p class="text-sm font-bold text-gray-800">≈ Rp {{ number_format($penyaluran->nilai_barang, 0, ',', '.') }}</p>
                                                    @else
                                                        <p class="text-sm font-bold text-orange-700">Barang</p>
                                                    @endif
                                                @else
                                                    <p class="text-sm font-bold text-gray-800">Rp {{ number_format($penyaluran->jumlah, 0, ',', '.') }}</p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $penyaluran->tanggal_penyaluran->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex gap-1.5 flex-wrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $metodeClass }}">
                                                    {{ ucfirst($penyaluran->metode_penyaluran) }}
                                                </span>
                                            </div>
                                            <a href="{{ route('transaksi-penyaluran.show', $penyaluran->uuid) }}" target="_blank"
                                                class="text-xs text-primary hover:underline">Detail →</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada riwayat penyaluran</h3>
                                <p class="mt-1 text-xs text-gray-400">Transaksi penyaluran untuk mustahik ini akan tampil di sini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- ── TAB: Riwayat Kunjungan ── --}}
                    <div id="content-kunjungan" class="tab-content hidden mt-6">
                        @if($riwayatKunjungan->count() > 0)
                            {{-- Ringkasan --}}
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-100">
                                    <p class="text-xs text-blue-600 font-medium">Total Kunjungan</p>
                                    <p class="text-lg font-bold text-blue-700">{{ $riwayatKunjungan->count() }}</p>
                                </div>
                                <div class="bg-green-50 rounded-xl p-3 text-center border border-green-100">
                                    <p class="text-xs text-green-600 font-medium">Selesai</p>
                                    <p class="text-lg font-bold text-green-700">{{ $riwayatKunjungan->where('status', 'selesai')->count() }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-xl p-3 text-center border border-yellow-100">
                                    <p class="text-xs text-yellow-600 font-medium">Direncanakan</p>
                                    <p class="text-lg font-bold text-yellow-700">{{ $riwayatKunjungan->where('status', 'direncanakan')->count() }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                @foreach($riwayatKunjungan as $kunjungan)
                                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="flex flex-wrap gap-2 mb-2">
                                                    @php
                                                        $tujuanClass = match($kunjungan->tujuan) {
                                                            'verifikasi' => 'bg-purple-100 text-purple-700',
                                                            'penyaluran' => 'bg-green-100 text-green-700',
                                                            'monitoring' => 'bg-blue-100 text-blue-700',
                                                            default      => 'bg-gray-100 text-gray-700',
                                                        };
                                                        $tujuanLabel = match($kunjungan->tujuan) {
                                                            'verifikasi' => 'Verifikasi',
                                                            'penyaluran' => 'Penyaluran',
                                                            'monitoring' => 'Monitoring',
                                                            'lainnya'    => 'Lainnya',
                                                            default      => ucfirst($kunjungan->tujuan),
                                                        };
                                                        $statusKunjunganClass = match($kunjungan->status) {
                                                            'selesai'      => 'bg-green-100 text-green-700',
                                                            'direncanakan' => 'bg-yellow-100 text-yellow-700',
                                                            'dibatalkan'   => 'bg-red-100 text-red-700',
                                                            default        => 'bg-gray-100 text-gray-700',
                                                        };
                                                        $statusKunjunganLabel = match($kunjungan->status) {
                                                            'selesai'      => 'Selesai',
                                                            'direncanakan' => 'Direncanakan',
                                                            'dibatalkan'   => 'Dibatalkan',
                                                            default        => $kunjungan->status,
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $tujuanClass }}">
                                                        {{ $tujuanLabel }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusKunjunganClass }}">
                                                        {{ $statusKunjunganLabel }}
                                                    </span>
                                                </div>

                                                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $kunjungan->tanggal_kunjungan->format('d M Y') }}
                                                        @if($kunjungan->waktu_mulai)
                                                            , {{ substr($kunjungan->waktu_mulai, 0, 5) }}
                                                            @if($kunjungan->waktu_selesai) - {{ substr($kunjungan->waktu_selesai, 0, 5) }} @endif
                                                        @endif
                                                    </span>
                                                    @if($kunjungan->amil)
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            {{ $kunjungan->amil->pengguna->name ?? $kunjungan->amil->nama_lengkap ?? 'Amil' }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($kunjungan->catatan)
                                                    <p class="mt-2 text-xs text-gray-600">
                                                        <span class="font-medium text-gray-700">Catatan:</span> {{ $kunjungan->catatan }}
                                                    </p>
                                                @endif

                                                @if($kunjungan->hasil_kunjungan)
                                                    <p class="mt-1 text-xs text-gray-600">
                                                        <span class="font-medium text-gray-700">Hasil:</span> {{ $kunjungan->hasil_kunjungan }}
                                                    </p>
                                                @endif
                                            </div>

                                            <a href="{{ route('amil.kunjungan.show', $kunjungan->uuid) }}" target="_blank"
                                                class="flex-shrink-0 inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Detail
                                            </a>
                                        </div>

                                        @if($kunjungan->foto_dokumentasi && count($kunjungan->foto_dokumentasi) > 0)
                                            <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                                                @foreach(array_slice($kunjungan->foto_dokumentasi, 0, 4) as $foto)
                                                    <a href="{{ Storage::url($foto) }}" target="_blank" class="flex-shrink-0">
                                                        <img src="{{ Storage::url($foto) }}" alt="Foto kunjungan"
                                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                                    </a>
                                                @endforeach
                                                @if(count($kunjungan->foto_dokumentasi) > 4)
                                                    <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                                        <span class="text-xs text-gray-500 font-medium">+{{ count($kunjungan->foto_dokumentasi) - 4 }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-3 text-sm font-medium text-gray-700">Belum ada riwayat kunjungan</h3>
                                <p class="mt-1 text-xs text-gray-400">Kunjungan amil untuk mustahik ini akan tampil di sini.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ── Footer Actions ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('mustahik.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($permissions['canVerify'] && $mustahik->status_verifikasi === 'pending')
                            <button type="button" onclick="verifyMustahik()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Verifikasi
                            </button>
                            <button type="button" onclick="showRejectModal()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak
                            </button>
                        @endif

                        @if($permissions['canEdit'])
                            <a href="{{ route('mustahik.edit', $mustahik->uuid) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        @endif

                        @if($permissions['canDelete'])
                            <button type="button" onclick="confirmDelete()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        @endif
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
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 text-center">Hapus Mustahik</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Hapus data mustahik "<span class="font-semibold text-gray-700">{{ $mustahik->nama_lengkap }}</span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form action="{{ route('mustahik.destroy', $mustahik->uuid) }}" method="POST" class="inline">
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

        // ── Verifikasi Mustahik ──
        async function verifyMustahik() {
            if (!confirm('Verifikasi mustahik ini? Data akan disetujui dan mustahik akan aktif.')) return;
            
            const button = event.currentTarget;
            const originalText = button.innerHTML;
            button.innerHTML = 'Memproses...';
            button.disabled = true;

            try {
                const response = await fetch(`{{ route('mustahik.verify', $mustahik->uuid) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Gagal memverifikasi mustahik');
                }
            } catch (error) {
                alert(error.message);
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }

        // ── Reject Modal ──
        function showRejectModal() {
            const alasan = prompt('Masukkan alasan penolakan:');
            if (!alasan) return;
            
            fetch(`{{ route('mustahik.reject', $mustahik->uuid) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ alasan_penolakan: alasan })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.reload();
                else alert(data.message || 'Gagal menolak mustahik');
            })
            .catch(() => alert('Terjadi kesalahan'));
        }
    </script>
@endpush