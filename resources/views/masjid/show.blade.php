@extends('layouts.app')

@section('title', $masjid->nama)

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-start animate-slide-down">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start animate-slide-down">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Detail Masjid</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap tentang masjid</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                        <a href="{{ route('masjid.index') }}"
                            class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <a href="{{ route('masjid.edit', $masjid->uuid) }}"
                            class="inline-flex items-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            {{-- Content Body --}}
            <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">
                {{-- Carousel Foto Masjid --}}
                @if ($masjid->foto && count($masjid->foto) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Galeri Masjid ({{ count($masjid->foto) }})</h4>
                        <div class="carousel-container relative bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                            {{-- Carousel Images --}}
                            <div id="carousel-images" class="relative aspect-video">
                                @foreach ($masjid->foto as $index => $foto)
                                    <div class="carousel-slide absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                        data-index="{{ $index }}">
                                        <img src="{{ asset('storage/' . $foto) }}" 
                                             alt="Foto Masjid {{ $index + 1 }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>

                            {{-- Navigation Buttons --}}
                            <button type="button" id="prev-button"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition-all z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button type="button" id="next-button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition-all z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            {{-- Indicators --}}
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                                @foreach ($masjid->foto as $index => $foto)
                                    <button type="button" class="carousel-indicator w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition-all {{ $index === 0 ? 'bg-opacity-100 w-8' : '' }}"
                                        data-index="{{ $index }}">
                                    </button>
                                @endforeach
                            </div>

                            {{-- Image Counter --}}
                            <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded-lg z-10">
                                <span id="current-slide">1</span>/<span id="total-slides">{{ count($masjid->foto) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Nama Masjid & Kode --}}
                <div class="space-y-4">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $masjid->nama }}</h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            {{ $masjid->kode_masjid }}
                        </span>
                        @if($masjid->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Foto Admin & Data Admin --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Admin Masjid</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Foto Admin --}}
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-white shadow-md mb-3">
                                    <img src="{{ $masjid->admin_foto_url ?: asset('images/default-avatar.png') }}" 
                                         alt="Foto Admin {{ $masjid->admin_nama }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <h5 class="text-sm font-medium text-gray-900">{{ $masjid->admin_nama ?? 'Belum Ditentukan' }}</h5>
                                <p class="text-xs text-gray-500">Admin/Penanggung Jawab</p>
                            </div>
                        </div>

                        {{-- Info Admin --}}
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($masjid->admin_telepon)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Telepon Admin</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $masjid->admin_telepon }}
                                    </div>
                                </div>
                                @endif

                                @if($masjid->admin_email)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email Admin</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $masjid->admin_email }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Data Sejarah --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Sejarah Masjid</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-4">
                                @if($masjid->tahun_berdiri)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tahun Berdiri</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $masjid->tahun_berdiri }}
                                        @if($masjid->usia_masjid)
                                            <span class="ml-2 text-xs text-gray-500">({{ $masjid->usia_masjid }} tahun)</span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($masjid->pendiri)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Pendiri</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $masjid->pendiri }}
                                    </div>
                                </div>
                                @endif

                                @if($masjid->kapasitas_jamaah)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Kapasitas Jamaah</label>
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1.625v-1A5.477 5.477 0 0018 8.625M3 13.125v1A5.477 5.477 0 006 20.625" />
                                        </svg>
                                        {{ number_format($masjid->kapasitas_jamaah, 0, ',', '.') }} orang
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($masjid->sejarah)
                        <div>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 h-full">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Sejarah Berdiri</label>
                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $masjid->sejarah }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Lokasi & Kontak --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Lokasi & Kontak</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                                    <div class="flex items-start text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $masjid->alamat_lengkap }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @if($masjid->telepon)
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Telepon Masjid</label>
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $masjid->telepon }}
                                        </div>
                                    </div>
                                    @endif

                                    @if($masjid->email)
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email Masjid</label>
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $masjid->email }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <h5 class="text-sm font-medium text-gray-900 mb-3">Wilayah Administratif</h5>
                                <div class="space-y-3">
                                    @if($masjid->provinsi_nama)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Provinsi:</span>
                                        <span class="text-gray-900 font-medium">{{ $masjid->provinsi_nama }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($masjid->kota_nama)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Kota/Kab:</span>
                                        <span class="text-gray-900 font-medium">{{ $masjid->kota_nama }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($masjid->kecamatan_nama)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Kecamatan:</span>
                                        <span class="text-gray-900 font-medium">{{ $masjid->kecamatan_nama }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($masjid->kelurahan_nama)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Kelurahan:</span>
                                        <span class="text-gray-900 font-medium">{{ $masjid->kelurahan_nama }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($masjid->kode_pos)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-24 flex-shrink-0">Kode Pos:</span>
                                        <span class="text-gray-900 font-medium">{{ $masjid->kode_pos }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Deskripsi --}}
                @if($masjid->deskripsi)
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Deskripsi & Fasilitas</h4>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $masjid->deskripsi }}</p>
                    </div>
                </div>
                <hr class="border-gray-200">
                @endif

                {{-- Timestamps --}}
                <div class="text-xs text-gray-500 pt-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dibuat: {{ $masjid->created_at->translatedFormat('d F Y H:i') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Diperbarui: {{ $masjid->updated_at->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                        <a href="{{ route('masjid.edit', $masjid->uuid) }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Masjid
                        </a>

                        <button type="button" onclick="confirmDelete()"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Masjid
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl bg-white">
            <div class="flex justify-center mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Masjid</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus masjid
                "<span class="font-semibold text-gray-700">{{ $masjid->nama }}</span>"?
            </p>
            <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-28 rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <form method="POST" action="{{ route('masjid.destroy', $masjid->uuid) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-28 rounded-lg shadow-sm px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        const carouselImages = document.getElementById('carousel-images');
        const prevButton = document.getElementById('prev-button');
        const nextButton = document.getElementById('next-button');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const currentSlideSpan = document.getElementById('current-slide');
        const totalSlidesSpan = document.getElementById('total-slides');
        
        let currentSlide = 0;
        let slideInterval;
        const totalSlides = {{ $masjid->foto ? count($masjid->foto) : 0 }};
        
        if (totalSlides > 0) {
            totalSlidesSpan.textContent = totalSlides;
            
            // Auto slide every 5 seconds
            function startAutoSlide() {
                slideInterval = setInterval(() => {
                    nextSlide();
                }, 5000);
            }
            
            function stopAutoSlide() {
                clearInterval(slideInterval);
            }
            
            function goToSlide(index) {
                currentSlide = index;
                updateCarousel();
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }
            
            function updateCarousel() {
                // Update slides
                document.querySelectorAll('.carousel-slide').forEach((slide, index) => {
                    slide.style.opacity = index === currentSlide ? '1' : '0';
                });
                
                // Update indicators
                indicators.forEach((indicator, index) => {
                    if (index === currentSlide) {
                        indicator.classList.add('bg-opacity-100', 'w-8');
                    } else {
                        indicator.classList.remove('bg-opacity-100', 'w-8');
                    }
                });
                
                // Update counter
                currentSlideSpan.textContent = currentSlide + 1;
            }
            
            // Event Listeners
            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    stopAutoSlide();
                    prevSlide();
                    startAutoSlide();
                });
            }
            
            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    stopAutoSlide();
                    nextSlide();
                    startAutoSlide();
                });
            }
            
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    stopAutoSlide();
                    goToSlide(index);
                    startAutoSlide();
                });
            });
            
            // Pause auto slide on hover
            if (carouselImages) {
                carouselImages.parentElement.addEventListener('mouseenter', stopAutoSlide);
                carouselImages.parentElement.addEventListener('mouseleave', startAutoSlide);
            }
            
            // Start auto slide
            startAutoSlide();
        }

        // Delete Modal
        function confirmDelete() {
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('delete-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .carousel-container {
        height: 400px;
    }
    
    .carousel-slide {
        transition: opacity 0.5s ease-in-out;
    }
    
    .carousel-indicator {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    @media (max-width: 640px) {
        .carousel-container {
            height: 300px;
        }
    }
</style>
@endpush