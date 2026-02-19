{{-- resources/views/admin-masjid/setor-kas/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Setoran Kas')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- ── Card Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Setoran Kas</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap setoran kas amil</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">

            {{-- ── Profile Header ── --}}
            <div class="pb-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    {{-- Avatar/Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl
                            @if($setorKas->status === 'diterima') bg-green-100
                            @elseif($setorKas->status === 'ditolak') bg-red-100
                            @else bg-amber-100 @endif
                            flex items-center justify-center border-2
                            @if($setorKas->status === 'diterima') border-green-200
                            @elseif($setorKas->status === 'ditolak') border-red-200
                            @else border-amber-200 @endif">
                            @if($setorKas->status === 'diterima')
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($setorKas->status === 'ditolak')
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Info Utama --}}
                    <div class="w-full">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                            {{ $setorKas->amil?->nama_lengkap ?? $setorKas->amil?->pengguna?->username ?? '-' }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 font-mono">{{ $setorKas->no_setor }}</p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            {{-- Status Badge --}}
                            @if($setorKas->status === 'diterima')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Diterima
                                </span>
                            @elseif($setorKas->status === 'ditolak')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu Review
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                {{ $setorKas->jumlah_disetor_formatted }}
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $setorKas->periode_formatted ?? ($setorKas->periode_dari->format('M Y').' – '.$setorKas->periode_sampai->format('M Y')) }}
                            </span>
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
                            Informasi Setoran
                        </button>
                        <button type="button" onclick="switchTab('amil')" id="tab-amil"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Data Amil
                        </button>
                        <button type="button" onclick="switchTab('ttd')" id="tab-ttd"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Tanda Tangan
                        </button>
                        @if($setorKas->bukti_foto_url ?? null)
                        <button type="button" onclick="switchTab('bukti')" id="tab-bukti"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Bukti Foto
                        </button>
                        @endif
                    </nav>
                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- TAB: Informasi Setoran                  --}}
                {{-- ═══════════════════════════════════════ --}}
                <div id="content-info" class="tab-content mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Informasi Dasar --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Informasi Dasar</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">No. Setoran</p>
                                        <p class="text-sm font-medium text-gray-900 font-mono">{{ $setorKas->no_setor }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Tanggal Setor</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->tanggal_setor->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Periode</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $setorKas->periode_dari->format('d M Y') }} — {{ $setorKas->periode_sampai->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Tanggal Diajukan</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                @if($setorKas->keterangan)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Keterangan</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->keterangan }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Rincian Jumlah --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Rincian Jumlah</h4>
                            <div class="space-y-3">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Datang Langsung</span>
                                        <span class="font-medium text-gray-900">{{ $setorKas->jumlah_dari_datang_langsung_formatted }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Dijemput</span>
                                        <span class="font-medium text-gray-900">{{ $setorKas->jumlah_dari_dijemput_formatted }}</span>
                                    </div>
                                    <div class="pt-2 border-t border-gray-200 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-gray-900">Total Disetor</span>
                                        <span class="text-base font-bold text-primary">{{ $setorKas->jumlah_disetor_formatted }}</span>
                                    </div>
                                </div>

                                @if(!is_null($setorKas->jumlah_dihitung_fisik))
                                @php $selisih = $setorKas->jumlah_dihitung_fisik - $setorKas->jumlah_disetor; @endphp
                                <div class="p-4 rounded-xl border {{ $selisih == 0 ? 'bg-green-50 border-green-200' : ($selisih > 0 ? 'bg-blue-50 border-blue-200' : 'bg-red-50 border-red-200') }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Pengecekan Fisik</span>
                                        @if($selisih == 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Cocok</span>
                                        @elseif($selisih > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Lebih</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Kurang</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Dihitung Fisik</span>
                                        <span class="font-semibold text-gray-900">Rp {{ number_format($setorKas->jumlah_dihitung_fisik, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm mt-1">
                                        <span class="text-gray-600">Selisih</span>
                                        <span class="font-bold {{ $selisih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @else
                                <div class="p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                                    <p class="text-xs text-gray-400 italic">Belum dilakukan pengecekan fisik</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Status Detail Section --}}
                    @if($setorKas->status === 'diterima' || $setorKas->status === 'ditolak')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                            {{ $setorKas->status === 'diterima' ? 'Konfirmasi Penerimaan' : 'Informasi Penolakan' }}
                        </h4>

                        @if($setorKas->status === 'diterima')
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-900">Setoran dikonfirmasi</p>
                                    <p class="text-xs text-green-600">{{ $setorKas->diterima_at?->format('d M Y, H:i') ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-green-700">Diterima oleh</span>
                                <span class="font-semibold text-green-900">{{ $setorKas->penerimaSetoran?->username ?? '-' }}</span>
                            </div>
                        </div>
                        @else
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-900 mb-1">Alasan Penolakan</p>
                                    <p class="text-sm text-red-700">{{ $setorKas->alasan_penolakan ?? 'Tidak ada keterangan.' }}</p>
                                    @if($setorKas->ditolak_at)
                                        <p class="text-xs text-red-400 mt-2">{{ $setorKas->ditolak_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Timestamps --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                            <div>
                                <span>Dibuat:</span>
                                <span class="font-medium text-gray-700">{{ $setorKas->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div>
                                <span>Diperbarui:</span>
                                <span class="font-medium text-gray-700">{{ $setorKas->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- TAB: Data Amil                          --}}
                {{-- ═══════════════════════════════════════ --}}
                <div id="content-amil" class="tab-content hidden mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Profil Amil</h4>

                            {{-- Avatar Amil --}}
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                @if($setorKas->amil?->foto)
                                    <img src="{{ asset('storage/' . $setorKas->amil->foto) }}"
                                        class="w-14 h-14 rounded-full object-cover border-2 border-white shadow"
                                        alt="Foto Amil">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center border-2 border-white shadow flex-shrink-0">
                                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-base font-bold text-gray-900">{{ $setorKas->amil?->nama_lengkap ?? $setorKas->amil?->pengguna?->username ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $setorKas->amil?->kode_amil ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->amil?->email ?? $setorKas->amil?->pengguna?->email ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Telepon</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->amil?->telepon ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Masjid</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->masjid?->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($setorKas->status === 'diterima' && $setorKas->penerimaSetoran)
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Admin Penerima</h4>
                            <div class="p-4 bg-green-50 border border-green-200 rounded-xl space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-green-900">{{ $setorKas->penerimaSetoran?->username ?? '-' }}</p>
                                        <p class="text-xs text-green-600">Admin Masjid</p>
                                    </div>
                                </div>
                                <div class="text-xs text-green-700 pt-2 border-t border-green-200">
                                    Menerima setoran pada <span class="font-semibold">{{ $setorKas->diterima_at?->format('d M Y, H:i') ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- TAB: Tanda Tangan                       --}}
                {{-- ═══════════════════════════════════════ --}}
                <div id="content-ttd" class="tab-content hidden mt-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- TTD Amil --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Tanda Tangan Amil</h4>
                            @if($setorKas->tanda_tangan_amil_url ?? null)
                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 flex items-center justify-center" style="min-height:160px">
                                    <img src="{{ $setorKas->tanda_tangan_amil_url }}"
                                        alt="Tanda Tangan Amil"
                                        class="max-h-36 object-contain">
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 flex flex-col items-center justify-center text-center" style="min-height:160px">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    <p class="text-sm text-gray-400">Tidak ada tanda tangan amil</p>
                                </div>
                            @endif
                        </div>

                        {{-- TTD Penerima --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Tanda Tangan Penerima</h4>
                            @if($setorKas->tanda_tangan_penerima_url ?? null)
                                <div class="bg-green-50 rounded-xl border border-green-200 p-4 flex items-center justify-center" style="min-height:160px">
                                    <img src="{{ $setorKas->tanda_tangan_penerima_url }}"
                                        alt="Tanda Tangan Penerima"
                                        class="max-h-36 object-contain">
                                </div>
                                <p class="text-xs text-gray-400 mt-2 text-center">
                                    Ditandatangani {{ $setorKas->diterima_at?->format('d M Y, H:i') ?? '' }}
                                </p>
                            @else
                                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 flex flex-col items-center justify-center text-center" style="min-height:160px">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    <p class="text-sm text-gray-400">
                                        @if($setorKas->status === 'pending') Belum diproses
                                        @elseif($setorKas->status === 'ditolak') Setoran ditolak, tidak ada tanda tangan
                                        @else Tidak ada tanda tangan penerima
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════ --}}
                {{-- TAB: Bukti Foto                         --}}
                {{-- ═══════════════════════════════════════ --}}
                @if($setorKas->bukti_foto_url ?? null)
                <div id="content-bukti" class="tab-content hidden mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Bukti Foto Setoran</h4>
                    <div class="max-w-lg">
                        <a href="{{ $setorKas->bukti_foto_url }}" target="_blank"
                            class="block rounded-xl overflow-hidden border border-gray-200 hover:border-primary transition-all hover:shadow-md">
                            <img src="{{ $setorKas->bukti_foto_url }}"
                                alt="Bukti Setor"
                                class="w-full object-contain max-h-96 bg-gray-50">
                        </a>
                        <p class="text-xs text-gray-400 mt-2 text-center">Klik gambar untuk membuka di tab baru</p>
                    </div>
                </div>
                @endif

            </div>{{-- end .mt-6 tabs --}}
        </div>{{-- end .p-4 sm:p-6 --}}

        {{-- ── Footer Actions ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                {{-- Tombol Kembali --}}
                @if($setorKas->status === 'pending')
                    <a href="{{ route('admin-masjid.setor-kas.pending') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Pending
                    </a>
                @else
                    <a href="{{ route('admin-masjid.setor-kas.riwayat') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Riwayat
                    </a>
                @endif

                {{-- Tombol Aksi --}}
                @if($setorKas->status === 'pending')
                    <button type="button"
                        data-uuid="{{ $setorKas->uuid }}"
                        data-no="{{ $setorKas->no_setor }}"
                        data-amil="{{ $setorKas->amil?->nama_lengkap ?? $setorKas->amil?->pengguna?->username ?? '-' }}"
                        data-jumlah="{{ $setorKas->jumlah_disetor_formatted }}"
                        data-periode="{{ $setorKas->periode_dari->format('d M Y').' – '.$setorKas->periode_sampai->format('d M Y') }}"
                        onclick="openReviewModal(this)"
                        class="inline-flex items-center justify-center px-5 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Review & Proses Setoran
                    </button>
                @endif
            </div>
        </div>

    </div>{{-- end main card --}}
</div>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MODAL REVIEW (hanya muncul jika status pending)                  --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
@if($setorKas->status === 'pending')
<div id="review-modal"
    class="fixed inset-0 hidden z-[10000] flex items-end sm:items-center justify-center p-0 sm:p-4"
    style="background: rgba(17,24,39,.6); backdrop-filter: blur(2px);">
    <div class="bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-2xl flex flex-col"
        style="max-height: 90vh; overflow-y: auto;">

        {{-- Modal Header --}}
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Review Setoran Kas</h3>
                    <p class="text-xs text-gray-400 font-mono" id="modal-no-setor"></p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()"
                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="review-form" method="POST">
            @csrf
            <div class="p-5 space-y-5">

                {{-- Alert --}}
                <div id="modal-alert" class="hidden px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"></div>

                {{-- Info Setoran --}}
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Amil</span>
                        <span class="font-medium text-gray-900" id="modal-amil"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Disetor</span>
                        <span class="font-bold text-lg text-amber-700" id="modal-jumlah"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Periode</span>
                        <span class="text-gray-900 text-right text-xs" id="modal-periode"></span>
                    </div>
                </div>

                {{-- Jumlah Fisik --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jumlah Dihitung Fisik
                        <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="number" name="jumlah_dihitung_fisik" id="jumlah_dihitung_fisik"
                            placeholder="0"
                            min="0" step="1000"
                            class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                {{-- Keputusan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keputusan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label id="label-diterima"
                            class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all">
                            <input type="radio" name="aksi" value="diterima" class="w-4 h-4 text-green-600" onchange="toggleAksi(this)">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Terima</p>
                                <p class="text-xs text-gray-400">Konfirmasi setoran</p>
                            </div>
                        </label>
                        <label id="label-ditolak"
                            class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all">
                            <input type="radio" name="aksi" value="ditolak" class="w-4 h-4 text-red-600" onchange="toggleAksi(this)">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Tolak</p>
                                <p class="text-xs text-gray-400">Kembalikan ke amil</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Alasan Penolakan --}}
                <div id="alasan-container" class="hidden">
                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3"
                        placeholder="Jelaskan alasan penolakan..."
                        class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400 transition-all resize-none"></textarea>
                </div>

                {{-- Tanda Tangan --}}
                <div id="ttd-container" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanda Tangan Penerima
                        <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <div class="relative rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 overflow-hidden cursor-crosshair">
                        <canvas id="signature-pad-penerima"
                            class="block w-full touch-none" style="height:140px;"></canvas>
                        <div id="ttd-placeholder"
                            class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-gray-300 select-none">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <p class="text-xs">Tanda tangan di sini</p>
                        </div>
                    </div>
                    <input type="hidden" name="tanda_tangan_penerima" id="ttd_penerima_input">
                    <button type="button" onclick="clearSignaturePenerima()"
                        class="mt-2 text-xs text-gray-400 hover:text-gray-700 underline">Hapus tanda tangan</button>
                </div>

                {{-- Info keputusan --}}
                <div id="modal-keputusan-info" class="hidden px-3 py-2 rounded-lg bg-gray-50 border border-gray-100">
                    <p id="modal-keputusan-text" class="text-xs text-gray-500"></p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between flex-shrink-0">
                <div id="modal-keputusan-footer" class="text-xs text-gray-400"></div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeReviewModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-review"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-600 rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Keputusan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// ── Tab Switching ──────────────────────────────────────────────────
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(b => {
        b.classList.remove('border-primary', 'text-primary');
        b.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('border-primary', 'text-primary');
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
}

@if($setorKas->status === 'pending')
// ── Review Modal ───────────────────────────────────────────────────
function openReviewModal(btn) {
    document.getElementById('modal-no-setor').textContent = btn.dataset.no;
    document.getElementById('modal-amil').textContent     = btn.dataset.amil;
    document.getElementById('modal-jumlah').textContent   = btn.dataset.jumlah;
    document.getElementById('modal-periode').textContent  = btn.dataset.periode;
    document.getElementById('review-form').action         = `/admin-setor-kas/${btn.dataset.uuid}/proses`;

    document.querySelectorAll('input[name="aksi"]').forEach(r => r.checked = false);
    document.getElementById('alasan-container').classList.add('hidden');
    document.getElementById('ttd-container').classList.add('hidden');
    document.getElementById('modal-keputusan-info').classList.add('hidden');
    document.getElementById('alasan_penolakan').value      = '';
    document.getElementById('alasan_penolakan').classList.remove('ring-2','ring-red-400');
    document.getElementById('jumlah_dihitung_fisik').value = '';
    document.getElementById('ttd_penerima_input').value    = '';

    const btnSubmit = document.getElementById('btn-submit-review');
    btnSubmit.disabled = false;
    btnSubmit.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Keputusan';

    document.getElementById('label-diterima').className = 'flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all';
    document.getElementById('label-ditolak').className  = 'flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all';
    document.getElementById('modal-alert').classList.add('hidden');

    document.getElementById('review-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => initSignaturePad(), 120);
}

function closeReviewModal() {
    document.getElementById('review-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function toggleAksi(radio) {
    const alasanBox = document.getElementById('alasan-container');
    const ttdBox    = document.getElementById('ttd-container');
    const infoEl    = document.getElementById('modal-keputusan-info');
    const textEl    = document.getElementById('modal-keputusan-text');

    document.getElementById('label-diterima').className = 'flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all';
    document.getElementById('label-ditolak').className  = 'flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all';

    if (radio.value === 'diterima') {
        document.getElementById('label-diterima').classList.remove('border-gray-200');
        document.getElementById('label-diterima').classList.add('border-green-400','bg-green-50');
        alasanBox.classList.add('hidden');
        ttdBox.classList.remove('hidden');
        infoEl.classList.remove('hidden');
        textEl.textContent = '✓ Setoran akan dikonfirmasi';
        textEl.className = 'text-xs text-green-600 font-medium';
        setTimeout(initSignaturePad, 80);
    } else {
        document.getElementById('label-ditolak').classList.remove('border-gray-200');
        document.getElementById('label-ditolak').classList.add('border-red-400','bg-red-50');
        alasanBox.classList.remove('hidden');
        ttdBox.classList.add('hidden');
        infoEl.classList.remove('hidden');
        textEl.textContent = '✕ Setoran akan dikembalikan ke amil';
        textEl.className = 'text-xs text-red-600 font-medium';
        setTimeout(() => document.getElementById('alasan_penolakan').focus(), 50);
    }
}

// ── Form Submit via fetch ──────────────────────────────────────────
document.getElementById('review-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    const aksi = form.querySelector('input[name="aksi"]:checked');

    if (!aksi) {
        showModalAlert('Pilih keputusan terlebih dahulu (Terima atau Tolak).');
        return;
    }
    if (aksi.value === 'ditolak') {
        const alasan = document.getElementById('alasan_penolakan').value.trim();
        if (!alasan) {
            document.getElementById('alasan_penolakan').focus();
            document.getElementById('alasan_penolakan').classList.add('ring-2','ring-red-400');
            return;
        }
    }

    const ttdInput = document.getElementById('ttd_penerima_input');
    if (sigCanvas && ttdInput.value.length < 100) {
        try { ttdInput.value = sigCanvas.toDataURL('image/png'); } catch(_) {}
    }

    const btnSubmit = document.getElementById('btn-submit-review');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Menyimpan...';

    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json, text/html' },
        credentials: 'same-origin',
    })
    .then(res => {
        if (res.redirected) { window.location.href = res.url; return; }
        if (res.ok) { window.location.reload(); return; }
        throw new Error('Error ' + res.status);
    })
    .catch(() => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Keputusan';
        showModalAlert('Gagal menyimpan. Coba lagi.');
    });
});

document.getElementById('alasan_penolakan').addEventListener('input', function () {
    this.classList.remove('ring-2','ring-red-400');
});

function showModalAlert(msg) {
    const el = document.getElementById('modal-alert');
    el.textContent = msg;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 4000);
}

document.getElementById('review-modal').addEventListener('click', function (e) {
    if (e.target === this) closeReviewModal();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeReviewModal();
});

// ── Signature Pad ──────────────────────────────────────────────────
let sigCanvas, sigCtx, sigDrawing = false, sigLastX = 0, sigLastY = 0;

function initSignaturePad() {
    sigCanvas = document.getElementById('signature-pad-penerima');
    if (!sigCanvas) return;

    const container = sigCanvas.parentElement;
    const rect      = container.getBoundingClientRect();
    const dpr       = window.devicePixelRatio || 1;

    sigCanvas.width  = rect.width  * dpr;
    sigCanvas.height = 140         * dpr;
    sigCanvas.style.width  = rect.width + 'px';
    sigCanvas.style.height = '140px';

    const newCanvas = sigCanvas.cloneNode(true);
    sigCanvas.parentNode.replaceChild(newCanvas, sigCanvas);
    sigCanvas = newCanvas;
    sigCtx    = sigCanvas.getContext('2d');
    sigCtx.scale(dpr, dpr);

    document.getElementById('ttd-placeholder').style.display = 'flex';
    document.getElementById('ttd_penerima_input').value = '';

    function getPos(e) {
        const r = sigCanvas.getBoundingClientRect();
        if (e.touches) return { x: e.touches[0].clientX - r.left, y: e.touches[0].clientY - r.top };
        return { x: e.clientX - r.left, y: e.clientY - r.top };
    }
    function startDraw(e) {
        e.preventDefault(); sigDrawing = true;
        document.getElementById('ttd-placeholder').style.display = 'none';
        const p = getPos(e); sigLastX = p.x; sigLastY = p.y;
    }
    function draw(e) {
        e.preventDefault(); if (!sigDrawing) return;
        const p = getPos(e);
        sigCtx.beginPath(); sigCtx.moveTo(sigLastX, sigLastY); sigCtx.lineTo(p.x, p.y);
        sigCtx.strokeStyle = '#1e293b'; sigCtx.lineWidth = 2; sigCtx.lineCap = 'round'; sigCtx.lineJoin = 'round'; sigCtx.stroke();
        sigLastX = p.x; sigLastY = p.y;
    }
    function endDraw() {
        if (!sigDrawing) return; sigDrawing = false;
        document.getElementById('ttd_penerima_input').value = sigCanvas.toDataURL('image/png');
    }
    sigCanvas.addEventListener('mousedown', startDraw);
    sigCanvas.addEventListener('mousemove', draw);
    sigCanvas.addEventListener('mouseup', endDraw);
    sigCanvas.addEventListener('mouseleave', endDraw);
    sigCanvas.addEventListener('touchstart', startDraw, { passive: false });
    sigCanvas.addEventListener('touchmove', draw, { passive: false });
    sigCanvas.addEventListener('touchend', endDraw);
}

function clearSignaturePenerima() {
    if (!sigCanvas || !sigCtx) return;
    const dpr = window.devicePixelRatio || 1;
    sigCtx.clearRect(0, 0, sigCanvas.width / dpr, sigCanvas.height / dpr);
    document.getElementById('ttd_penerima_input').value = '';
    document.getElementById('ttd-placeholder').style.display = 'flex';
}
@endif
</script>
@endpush