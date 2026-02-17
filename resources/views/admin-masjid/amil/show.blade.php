@extends('layouts.app')

@section('title', 'Detail Amil - ' . $amil->nama_lengkap)

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div id="flash-success" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-start animate-slide-down">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-error" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start animate-slide-down">
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
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Detail Amil</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap tentang amil</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                        <a href="{{ route('amil.index') }}"
                            class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <a href="{{ route('amil.edit', $amil->uuid) }}"
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
                {{-- Foto Profil & Info Utama --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Foto Profil --}}
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 flex flex-col items-center">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-white shadow-md mb-4">
                                <img src="{{ $amil->foto_url ?: asset('images/default-avatar.png') }}" 
                                     alt="Foto {{ $amil->nama_lengkap }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <h5 class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</h5>
                            <p class="text-xs text-gray-500">Amil</p>
                            
                            {{-- Badge Status --}}
                            <div class="mt-3" id="status-badge">
                                @include('amil.partials.status-badge', ['status' => $amil->status])
                            </div>
                        </div>
                    </div>

                    {{-- Info Utama --}}
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Kode Amil</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $amil->kode_amil }}
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $amil->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </div>
                            </div>

                            @if($amil->telepon)
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Telepon</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $amil->telepon }}
                                </div>
                            </div>
                            @endif

                            @if($amil->email)
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $amil->email }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Data Pribadi & Tugas --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Data Pribadi --}}
                    <div>
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Pribadi</h4>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tempat, Tanggal Lahir</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $amil->tempat_lahir }}, {{ $amil->tanggal_lahir->translatedFormat('d F Y') }}
                                    <span class="ml-2 text-xs text-gray-500">({{ $amil->tanggal_lahir->age }} tahun)</span>
                                </div>
                            </div>

                            @if($amil->alamat)
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                                <div class="flex items-start text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $amil->alamat }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Data Tugas --}}
                    <div>
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Tugas</h4>
                        <div class="space-y-4">
                            @if($amil->tanggal_mulai_tugas)
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Mulai Tugas</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $amil->tanggal_mulai_tugas->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            @endif

                            @if($amil->tanggal_selesai_tugas)
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Selesai Tugas</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $amil->tanggal_selesai_tugas->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            @endif

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Masa Tugas</label>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @php
                                        $endDate = $amil->tanggal_selesai_tugas ?? now();
                                        $masaTugas = $amil->tanggal_mulai_tugas ? $amil->tanggal_mulai_tugas->diffForHumans($endDate, true) : '-';
                                    @endphp
                                    {{ $masaTugas }}
                                    @if(!$amil->tanggal_selesai_tugas)
                                        <span class="ml-2 text-xs text-gray-500">(hingga saat ini)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Tambahan --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if($amil->wilayah_tugas)
                    <div>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 h-full">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Wilayah Tugas</label>
                            <div class="flex items-start text-sm text-gray-900">
                                <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $amil->wilayah_tugas }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Info Masjid --}}
                    @if($amil->masjid)
                    <div>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 h-full">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Masjid Penempatan</label>
                            <div class="flex items-start text-sm text-gray-900">
                                <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <div>
                                    <div class="font-medium">{{ $amil->masjid->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $amil->masjid->kode_masjid }}</div>
                                    @if($amil->masjid->alamat)
                                        <div class="text-xs text-gray-600 mt-1">{{ $amil->masjid->alamat }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($amil->keterangan)
                <div>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Keterangan</label>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $amil->keterangan }}</p>
                    </div>
                </div>
                @endif

                <hr class="border-gray-200">

                {{-- Timestamps --}}
                <div class="text-xs text-gray-500">
                    <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dibuat: {{ $amil->created_at->translatedFormat('d F Y H:i') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Diperbarui: {{ $amil->updated_at->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                        <a href="{{ route('amil.edit', $amil->uuid) }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Amil
                        </a>

                        <button type="button" onclick="toggleStatus()" id="toggle-status-btn"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-white border {{ $amil->status == 'aktif' ? 'border-yellow-300 text-yellow-700 hover:bg-yellow-50 focus:ring-yellow-500' : 'border-green-300 text-green-700 hover:bg-green-50 focus:ring-green-500' }} shadow-sm text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span id="toggle-status-text">
                                {{ $amil->status == 'aktif' ? 'Ubah Status' : 'Aktifkan' }}
                            </span>
                        </button>

                        <button type="button" onclick="confirmDelete()"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Amil
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
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Amil</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus amil
                "<span class="font-semibold text-gray-700">{{ $amil->nama_lengkap }}</span>"?
            </p>
            <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-28 rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <form method="POST" action="{{ route('amil.destroy', $amil->uuid) }}" class="inline">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Auto hide flash messages after 5 seconds
        setTimeout(() => {
            const flashSuccess = document.getElementById('flash-success');
            const flashError = document.getElementById('flash-error');
            if (flashSuccess) flashSuccess.style.display = 'none';
            if (flashError) flashError.style.display = 'none';
        }, 5000);
    });

    // Status Toggle
    async function toggleStatus() {
        const button = document.getElementById('toggle-status-btn');
        const buttonText = document.getElementById('toggle-status-text');
        const statusBadgeContainer = document.getElementById('status-badge');
        
        // Show loading state
        const originalText = buttonText.textContent;
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
                // Update status badge
                statusBadgeContainer.innerHTML = data.status_badge_html;
                
                // Update button text and style
                if (data.new_status === 'aktif') {
                    buttonText.textContent = 'Ubah Status';
                    button.className = 'inline-flex items-center justify-center px-4 py-2.5 bg-white border border-yellow-300 text-yellow-700 hover:bg-yellow-50 focus:ring-yellow-500 shadow-sm text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';
                } else {
                    buttonText.textContent = 'Aktifkan';
                    button.className = 'inline-flex items-center justify-center px-4 py-2.5 bg-white border border-green-300 text-green-700 hover:bg-green-50 focus:ring-green-500 shadow-sm text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';
                }
                
                // Show success message
                showFlashMessage('Status berhasil diubah!', 'success');
            } else {
                throw new Error(data.message || 'Gagal mengubah status');
            }
        } catch (error) {
            console.error('Error:', error);
            showFlashMessage(error.message, 'error');
            buttonText.textContent = originalText;
        } finally {
            button.disabled = false;
        }
    }

    // Flash Message Function
    function showFlashMessage(message, type) {
        // Remove existing flash messages
        const existingFlash = document.querySelector('.flash-message');
        if (existingFlash) existingFlash.remove();
        
        // Create new flash message
        const flashDiv = document.createElement('div');
        flashDiv.className = `flash-message bg-${type === 'success' ? 'green' : 'red'}-50 border border-${type === 'success' ? 'green' : 'red'}-200 text-${type === 'success' ? 'green' : 'red'}-800 px-4 py-3 rounded-xl flex items-start animate-slide-down mb-4`;
        
        const icon = type === 'success' ? 
            `<svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>` :
            `<svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>`;
        
        flashDiv.innerHTML = `${icon}<span>${message}</span>`;
        
        // Insert at the top of content
        const contentDiv = document.querySelector('.space-y-4');
        if (contentDiv) {
            contentDiv.insertBefore(flashDiv, contentDiv.firstChild);
        }
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            flashDiv.style.opacity = '0';
            flashDiv.style.transform = 'translateY(-10px)';
            flashDiv.style.transition = 'all 0.3s ease';
            setTimeout(() => flashDiv.remove(), 300);
        }, 5000);
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
</script>
@endpush