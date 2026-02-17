@extends('layouts.app')
@section('title', 'Detail Mustahik')
@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Mustahik</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap data mustahik</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($permissions['canEdit'])
                            <a href="{{ route('mustahik.edit', $mustahik->uuid) }}"
                                class="inline-flex items-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                {{-- Profile Header --}}
                <div class="pb-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <div class="w-full">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $mustahik->nama_lengkap }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $mustahik->no_registrasi }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $mustahik->kategoriMustahik->nama ?? '-' }}
                                </span>
                                {!! $mustahik->status_badge !!}
                                {!! $mustahik->active_badge !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="mt-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                            <button type="button" onclick="switchTab('info')" id="tab-info"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none">
                                Informasi Pribadi
                            </button>
                            <button type="button" onclick="switchTab('penerimaan')" id="tab-penerimaan"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Riwayat Penerimaan
                            </button>
                            <button type="button" onclick="switchTab('kunjungan')" id="tab-kunjungan"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Riwayat Kunjungan
                            </button>
                        </nav>
                    </div>

                    {{-- Tab Content: Informasi Pribadi --}}
                    <div id="content-info" class="tab-content mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Data Pribadi --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Data Pribadi</h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">NIK</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->nik ?: '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">No. KK</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->kk ?: '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->gender_label }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $mustahik->tempat_lahir ?: '-' }}, 
                                                {{ $mustahik->tanggal_lahir ? $mustahik->tanggal_lahir->format('d M Y') : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">No. Telepon</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->telepon ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Data Alamat --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Alamat</h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Alamat Lengkap</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->alamat }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Wilayah</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                @if($mustahik->kelurahan_kode)
                                                    {{ \Laravolt\Indonesia\Models\Village::where('code', $mustahik->kelurahan_kode)->first()->name ?? '-' }},
                                                @endif
                                                @if($mustahik->kecamatan_kode)
                                                    {{ \Laravolt\Indonesia\Models\District::where('code', $mustahik->kecamatan_kode)->first()->name ?? '-' }},
                                                @endif
                                                @if($mustahik->kota_kode)
                                                    {{ \Laravolt\Indonesia\Models\City::where('code', $mustahik->kota_kode)->first()->name ?? '-' }},
                                                @endif
                                                @if($mustahik->provinsi_kode)
                                                    {{ \Laravolt\Indonesia\Models\Province::where('code', $mustahik->provinsi_kode)->first()->name ?? '-' }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">RT/RW</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->rt_rw ?: '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Kode Pos</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->kode_pos ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Sosial Ekonomi --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Data Sosial Ekonomi</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Pekerjaan</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->pekerjaan ?: '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Penghasilan Per Bulan</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $mustahik->penghasilan_perbulan ? 'Rp ' . number_format($mustahik->penghasilan_perbulan, 0, ',', '.') : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Jumlah Tanggungan</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->jumlah_tanggungan }} orang</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Status Rumah</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->status_rumah_label }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Kondisi Kesehatan</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->kondisi_kesehatan ?: '-' }}</p>
                                        </div>
                                    </div>

                                    @if($mustahik->catatan)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-gray-500">Catatan</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $mustahik->catatan }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Gallery Dokumen --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Dokumen Pendukung</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Foto KTP --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Foto KTP</label>
                                    @if($mustahik->foto_ktp)
                                        <a href="{{ Storage::url($mustahik->foto_ktp) }}" target="_blank" class="block">
                                            <img src="{{ Storage::url($mustahik->foto_ktp) }}" alt="KTP" class="w-full h-40 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                    @else
                                        <div class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <p class="text-xs text-gray-500">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Foto KK --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Foto KK</label>
                                    @if($mustahik->foto_kk)
                                        <a href="{{ Storage::url($mustahik->foto_kk) }}" target="_blank" class="block">
                                            <img src="{{ Storage::url($mustahik->foto_kk) }}" alt="KK" class="w-full h-40 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                    @else
                                        <div class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <p class="text-xs text-gray-500">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Foto Rumah --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Foto Rumah</label>
                                    @if($mustahik->foto_rumah)
                                        <a href="{{ Storage::url($mustahik->foto_rumah) }}" target="_blank" class="block">
                                            <img src="{{ Storage::url($mustahik->foto_rumah) }}" alt="Rumah" class="w-full h-40 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                    @else
                                        <div class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                            <p class="text-xs text-gray-500">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Dokumen Lainnya --}}
                            @if($mustahik->dokumen_lainnya && count($mustahik->dokumen_lainnya) > 0)
                                <div class="mt-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Dokumen Lainnya</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @foreach($mustahik->dokumen_lainnya as $dokumen)
                                            <a href="{{ Storage::url($dokumen) }}" target="_blank" 
                                                class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="text-xs text-gray-700 truncate">{{ basename($dokumen) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Status Verifikasi Info --}}
                        @if($mustahik->status_verifikasi !== 'pending')
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Informasi Verifikasi</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Diverifikasi oleh</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->verifiedBy->username ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Tanggal Verifikasi</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $mustahik->verified_at ? $mustahik->verified_at->format('d M Y, H:i') : '-' }}</p>
                                        </div>
                                        @if($mustahik->status_verifikasi === 'rejected' && $mustahik->alasan_penolakan)
                                            <div class="md:col-span-2">
                                                <p class="text-xs text-gray-500">Alasan Penolakan</p>
                                                <p class="text-sm font-medium text-red-600">{{ $mustahik->alasan_penolakan }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Timestamps --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                <div>
                                    <span>Tanggal Registrasi:</span>
                                    <span class="font-medium text-gray-700">{{ $mustahik->tanggal_registrasi->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span>Dibuat:</span>
                                    <span class="font-medium text-gray-700">{{ $mustahik->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span>Diperbarui:</span>
                                    <span class="font-medium text-gray-700">{{ $mustahik->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Content: Riwayat Penerimaan --}}
                    <div id="content-penerimaan" class="tab-content hidden mt-6">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat penerimaan</h3>
                            <p class="mt-1 text-sm text-gray-500">Data penyaluran ke mustahik ini akan muncul di sini</p>
                            @if($permissions['canDistribute'])
                                <div class="mt-6">
                                    <button type="button" onclick="alert('Fitur penyaluran akan segera hadir')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-600 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Salurkan Zakat
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tab Content: Riwayat Kunjungan --}}
                    <div id="content-kunjungan" class="tab-content hidden mt-6">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat kunjungan</h3>
                            <p class="mt-1 text-sm text-gray-500">Data kunjungan amil ke mustahik ini akan muncul di sini</p>
                            @if($permissions['canScheduleVisit'])
                                <div class="mt-6">
                                    <button type="button" onclick="alert('Fitur jadwal kunjungan akan segera hadir')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-600 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Jadwalkan Kunjungan
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


<div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('mustahik.index') }}"
            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <div class="flex items-center gap-2">
            {{-- TAMBAHAN: Tombol Verifikasi untuk Admin --}}
            @if($permissions['canVerify'] && $mustahik->status_verifikasi === 'pending')
                <button type="button" onclick="verifyMustahik('{{ $mustahik->uuid }}')"
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Verifikasi
                </button>
                <button type="button" onclick="showRejectModal()"
                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak
                </button>
            @endif
            
            @if($permissions['canEdit'])
                <a href="{{ route('mustahik.edit', $mustahik->uuid) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Data
                </a>
            @endif
            
            @if($permissions['canDelete'])
                <button type="button" onclick="confirmDelete()"
                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            @endif
        </div>
    </div>
</div>
        </div>
    </div>

    {{-- Delete Modal --}}
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Mustahik</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus data mustahik
                "<span class="font-semibold text-gray-700">{{ $mustahik->nama_lengkap }}</span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <form action="{{ route('mustahik.destroy', $mustahik->uuid) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
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
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-primary', 'text-primary');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active state to selected tab
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

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endpush