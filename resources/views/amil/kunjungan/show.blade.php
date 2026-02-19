{{-- resources/views/amil/kunjungan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Kunjungan')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Kunjungan</h2>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    {!! $kunjungan->status_badge !!}
                    @if($kunjungan->isEditable())
                        <a href="{{ route('amil.kunjungan.edit', $kunjungan->uuid) }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary hover:bg-primary/5 border border-primary/30 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-6">

            {{-- Info Mustahik & Jadwal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Mustahik --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Mustahik</h3>
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-blue-900">{{ $kunjungan->mustahik->nama_lengkap }}</p>
                                <p class="text-xs text-blue-700 mt-0.5">{{ $kunjungan->mustahik->no_registrasi }}</p>
                                <p class="text-xs text-blue-600 mt-1 flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $kunjungan->mustahik->alamat ?? '-' }}</span>
                                </p>
                                @if($kunjungan->mustahik->telepon)
                                <p class="text-xs text-blue-600 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $kunjungan->mustahik->telepon }}
                                </p>
                                @endif
                                <div class="mt-2">
                                    <a href="{{ route('mustahik.show', $kunjungan->mustahik->uuid) }}"
                                        class="text-xs font-medium text-blue-700 hover:text-blue-900 underline">
                                        Lihat profil mustahik →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jadwal --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Jadwal Kunjungan</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $kunjungan->tanggal_kunjungan->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Waktu</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $kunjungan->waktu_format }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tujuan</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $kunjungan->tujuan_label }}</p>
                            </div>
                        </div>

                        @if($kunjungan->catatan)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Catatan Rencana</p>
                                <p class="text-sm text-gray-700">{{ $kunjungan->catatan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Hasil Kunjungan (jika sudah selesai) --}}
            @if($kunjungan->isSelesai())
            <div>
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Hasil Kunjungan</h3>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-sm text-gray-800 whitespace-pre-line">{{ $kunjungan->hasil_kunjungan ?? '-' }}</p>
                </div>
            </div>

            {{-- Foto Dokumentasi --}}
            @if($kunjungan->foto_dokumentasi_urls)
            <div>
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Foto Dokumentasi</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($kunjungan->foto_dokumentasi_urls as $i => $url)
                    <div class="relative group">
                        <a href="{{ $url }}" target="_blank"
                            class="block aspect-square rounded-xl overflow-hidden border border-gray-200 hover:border-primary transition-colors">
                            <img src="{{ $url }}" alt="Foto {{ $i+1 }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </a>
                        {{-- Tombol hapus foto --}}
                        <form method="POST" action="{{ route('amil.kunjungan.hapus-foto', $kunjungan->uuid) }}"
                            class="absolute top-1 right-1 hidden group-hover:block"
                            onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="index" value="{{ $i }}">
                            <button type="submit"
                                class="w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 shadow">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            {{-- Dibatalkan info --}}
            @if($kunjungan->isDibatalkan())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm text-red-800 font-medium">Kunjungan ini telah dibatalkan.</p>
                @if($kunjungan->catatan)
                    <p class="text-sm text-red-700 mt-1">{{ $kunjungan->catatan }}</p>
                @endif
            </div>
            @endif

        </div>

        {{-- Footer Actions --}}
        <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('amil.kunjungan.index') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>

            <div class="flex gap-2 flex-wrap justify-end">
                {{-- Tambah/Edit Hasil --}}
                @if($kunjungan->isEditable() || $kunjungan->isSelesai())
                <a href="{{ route('amil.kunjungan.finish', $kunjungan->uuid) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-green-700 bg-green-50 border border-green-300 hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $kunjungan->isSelesai() ? 'Edit Hasil' : 'Tandai Selesai' }}
                </a>
                @endif

                {{-- Batalkan --}}
                @if($kunjungan->isEditable())
                <button type="button" onclick="document.getElementById('cancel-modal').classList.remove('hidden')"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-orange-700 bg-orange-50 border border-orange-300 hover:bg-orange-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Batalkan
                </button>
                @endif

                {{-- Hapus --}}
                <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-red-600 bg-white border border-red-300 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Batalkan --}}
<div id="cancel-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-xl">
        <div class="flex justify-center mb-4">
            <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 text-center mb-1">Batalkan Kunjungan?</h3>
        <p class="text-sm text-gray-500 text-center mb-5">Kunjungan dengan <strong>{{ $kunjungan->mustahik->nama_lengkap }}</strong> pada {{ $kunjungan->tanggal_kunjungan->format('d M Y') }} akan dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')"
                class="w-28 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">Tidak</button>
            <form action="{{ route('amil.kunjungan.cancel', $kunjungan->uuid) }}" method="POST">
                @csrf
                <button type="submit" class="w-28 rounded-lg px-4 py-2 bg-orange-500 text-sm font-medium text-white hover:bg-orange-600 transition-colors">Ya, Batalkan</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-xl">
        <div class="flex justify-center mb-4">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 text-center mb-1">Hapus Kunjungan</h3>
        <p class="text-sm text-gray-500 text-center mb-5">Data kunjungan dan semua foto dokumentasi akan dihapus permanen.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                class="w-28 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">Batal</button>
            <form action="{{ route('amil.kunjungan.destroy', $kunjungan->uuid) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="w-28 rounded-lg px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection