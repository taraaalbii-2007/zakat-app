{{-- resources/views/amil/setor-kas/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Setoran Kas')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- ── Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Setoran Kas</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi lengkap transaksi setoran kas</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">

            {{-- ── Profile Card ── --}}
            <div class="pb-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    {{-- Avatar / Icon --}}
                    <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-primary/10 flex items-center justify-center">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="w-full">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 font-mono">
                            {{ $setorKas->no_setor }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $setorKas->amil->nama_lengkap ?? $setorKas->amil->pengguna->username ?? '-' }}
                            &mdash; {{ $setorKas->periode_formatted }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            {{-- Badge Status --}}
                            {!! $setorKas->status_badge !!}

                            {{-- Badge Jumlah --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $setorKas->jumlah_disetor_formatted }}
                            </span>

                            {{-- Badge Tanggal --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $setorKas->tanggal_setor->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tabs ── --}}
            <div class="mt-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                        <button type="button" onclick="switchTab('informasi')" id="tab-informasi"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-primary text-primary font-medium text-sm focus:outline-none">
                            Informasi Setoran
                        </button>
                        <button type="button" onclick="switchTab('rincian')" id="tab-rincian"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Rincian Jumlah
                        </button>
                        <button type="button" onclick="switchTab('timeline')" id="tab-timeline"
                            class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                            Timeline Status
                        </button>
                        @if($setorKas->bukti_foto_url || $setorKas->tanda_tangan_amil_url || $setorKas->tanda_tangan_penerima_url)
                            <button type="button" onclick="switchTab('bukti')" id="tab-bukti"
                                class="tab-button whitespace-nowrap py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm focus:outline-none">
                                Bukti & TTD
                            </button>
                        @endif
                    </nav>
                </div>

                {{-- ── TAB: Informasi Setoran ── --}}
                <div id="content-informasi" class="tab-content mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom kiri --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Data Setoran</h4>
                            <div class="space-y-3">

                                {{-- No Setor --}}
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Nomor Setoran</p>
                                        <p class="text-sm font-medium text-gray-900 font-mono">{{ $setorKas->no_setor }}</p>
                                    </div>
                                </div>

                                {{-- Tanggal Setor --}}
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Tanggal Setor</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->tanggal_setor->format('d M Y') }}</p>
                                    </div>
                                </div>

                                {{-- Periode --}}
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Periode</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->periode_formatted }}</p>
                                    </div>
                                </div>

                                {{-- Amil Penyetor --}}
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Amil Penyetor</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $setorKas->amil->nama_lengkap ?? $setorKas->amil->pengguna->username ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Keterangan --}}
                                @if($setorKas->keterangan)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Keterangan</p>
                                            <p class="text-sm text-gray-900">{{ $setorKas->keterangan }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom kanan --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Penerimaan & Status</h4>
                            <div class="space-y-3">

                                {{-- Status Card --}}
                                @php
                                    $statusBg = match($setorKas->status) {
                                        'diterima'  => 'bg-green-50 border-green-200',
                                        'ditolak'   => 'bg-red-50 border-red-200',
                                        'menunggu'  => 'bg-yellow-50 border-yellow-200',
                                        default     => 'bg-blue-50 border-blue-200',
                                    };
                                    $statusTextLabel = match($setorKas->status) {
                                        'diterima'  => 'text-green-700',
                                        'ditolak'   => 'text-red-700',
                                        'menunggu'  => 'text-yellow-700',
                                        default     => 'text-blue-700',
                                    };
                                    $statusTextValue = match($setorKas->status) {
                                        'diterima'  => 'text-green-900',
                                        'ditolak'   => 'text-red-900',
                                        'menunggu'  => 'text-yellow-900',
                                        default     => 'text-blue-900',
                                    };
                                    $statusIconBg = match($setorKas->status) {
                                        'diterima'  => 'bg-green-200',
                                        'ditolak'   => 'bg-red-200',
                                        'menunggu'  => 'bg-yellow-200',
                                        default     => 'bg-blue-200',
                                    };
                                    $statusIconColor = match($setorKas->status) {
                                        'diterima'  => 'text-green-700',
                                        'ditolak'   => 'text-red-700',
                                        'menunggu'  => 'text-yellow-700',
                                        default     => 'text-blue-700',
                                    };
                                @endphp
                                <div class="p-4 rounded-xl border {{ $statusBg }}">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-medium {{ $statusTextLabel }}">Status Setoran</p>
                                            <p class="text-sm font-bold {{ $statusTextValue }} mt-0.5">
                                                {{ ucfirst($setorKas->status) }}
                                            </p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full {{ $statusIconBg }} flex items-center justify-center">
                                            @if($setorKas->status === 'diterima')
                                                <svg class="w-5 h-5 {{ $statusIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @elseif($setorKas->status === 'ditolak')
                                                <svg class="w-5 h-5 {{ $statusIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 {{ $statusIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Penerima --}}
                                @if($setorKas->penerimaSetoran)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Diterima Oleh</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $setorKas->penerimaSetoran->username }}</p>
                                            @if($setorKas->diterima_at)
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $setorKas->diterima_at->format('d M Y, H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Alasan Penolakan --}}
                                @if($setorKas->status === 'ditolak' && $setorKas->alasan_penolakan)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Alasan Penolakan</p>
                                            <p class="text-sm font-medium text-red-700">{{ $setorKas->alasan_penolakan }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── TAB: Rincian Jumlah ── --}}
                <div id="content-rincian" class="tab-content hidden mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom kiri --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Jumlah Setoran</h4>
                            <div class="space-y-3">

                                {{-- Jumlah Disetor (highlight) --}}
                                <div class="p-4 rounded-xl border bg-blue-50 border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-medium text-blue-700">Total Disetor</p>
                                            <p class="text-xl font-bold text-blue-900 mt-0.5">
                                                {{ $setorKas->jumlah_disetor_formatted }}
                                            </p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Datang Langsung --}}
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Datang Langsung</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->jumlah_dari_datang_langsung_formatted }}</p>
                                    </div>
                                </div>

                                {{-- Dijemput --}}
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Dijemput</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $setorKas->jumlah_dari_dijemput_formatted }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom kanan --}}
                        @if(!is_null($setorKas->jumlah_dihitung_fisik))
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Verifikasi Fisik</h4>
                                <div class="space-y-3">

                                    {{-- Jumlah Dihitung Fisik --}}
                                    @php
                                        $selisih = $setorKas->selisih_jumlah;
                                        $selisihBg    = $selisih >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
                                        $selisihLabel = $selisih >= 0 ? 'text-green-700' : 'text-red-700';
                                        $selisihValue = $selisih >= 0 ? 'text-green-900' : 'text-red-900';
                                        $selisihIcon  = $selisih >= 0 ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700';
                                    @endphp

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Jumlah Dihitung Fisik</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $setorKas->jumlah_dihitung_fisik_formatted }}</p>
                                        </div>
                                    </div>

                                    {{-- Selisih Card --}}
                                    @if($selisih != 0)
                                        <div class="p-4 rounded-xl border {{ $selisihBg }}">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-xs font-medium {{ $selisihLabel }}">Selisih</p>
                                                    <p class="text-sm font-bold {{ $selisihValue }} mt-0.5">
                                                        {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="w-10 h-10 rounded-full {{ $selisihIcon }} flex items-center justify-center">
                                                    @if($selisih >= 0)
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-4 rounded-xl border bg-green-50 border-green-200">
                                            <p class="text-xs font-medium text-green-700">Selisih</p>
                                            <p class="text-sm font-bold text-green-900 mt-0.5">Tidak ada selisih</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── TAB: Timeline Status ── --}}
                <div id="content-timeline" class="tab-content hidden mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom kiri: Timeline --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Alur Status</h4>
                            <div class="space-y-3">
                                @foreach($timeline as $i => $step)
                                    @php
                                        $stepBg    = 'bg-gray-100';
                                        $stepText  = 'text-gray-400';
                                        $dotBorder = 'border-gray-300';
                                        if ($step['active']) {
                                            $stepBg    = match($step['color']) {
                                                'green'  => 'bg-green-100',
                                                'red'    => 'bg-red-100',
                                                'yellow' => 'bg-yellow-100',
                                                default  => 'bg-blue-100',
                                            };
                                            $stepText  = match($step['color']) {
                                                'green'  => 'text-green-600',
                                                'red'    => 'text-red-600',
                                                'yellow' => 'text-yellow-600',
                                                default  => 'text-blue-600',
                                            };
                                            $dotBorder = match($step['color']) {
                                                'green'  => 'border-green-400',
                                                'red'    => 'border-red-400',
                                                'yellow' => 'border-yellow-400',
                                                default  => 'border-blue-400',
                                            };
                                        }
                                    @endphp
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ $stepBg }} flex items-center justify-center">
                                            @if($step['icon'] === 'check')
                                                <svg class="w-5 h-5 {{ $stepText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @elseif($step['icon'] === 'x')
                                                <svg class="w-5 h-5 {{ $stepText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @elseif($step['icon'] === 'clock')
                                                <svg class="w-5 h-5 {{ $stepText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 {{ $stepText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500">Langkah {{ $i + 1 }}</p>
                                            <p class="text-sm font-medium {{ $step['active'] ? 'text-gray-900' : 'text-gray-400' }}">
                                                {{ $step['label'] }}
                                            </p>
                                            @if($step['date'])
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $step['date'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Kolom kanan: Alasan penolakan jika ada --}}
                        @if($setorKas->status === 'ditolak' && $setorKas->alasan_penolakan)
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Keterangan Penolakan</h4>
                                <div class="p-4 rounded-xl border bg-red-50 border-red-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-red-700">Alasan Penolakan</p>
                                        <div class="w-8 h-8 rounded-full bg-red-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm text-red-800">{{ $setorKas->alasan_penolakan }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── TAB: Bukti & Tanda Tangan ── --}}
                @if($setorKas->bukti_foto_url || $setorKas->tanda_tangan_amil_url || $setorKas->tanda_tangan_penerima_url)
                    <div id="content-bukti" class="tab-content hidden mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Dokumen & Bukti</h4>
                                <div class="space-y-3">
                                    @if($setorKas->bukti_foto_url)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500 mb-2">Foto Bukti Setor</p>
                                                <a href="{{ $setorKas->bukti_foto_url }}" target="_blank">
                                                    <img src="{{ $setorKas->bukti_foto_url }}" alt="Bukti Setor"
                                                        class="w-full h-40 object-cover rounded-xl border border-gray-200 hover:border-primary transition-colors">
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Tanda Tangan</h4>
                                <div class="space-y-3">
                                    @if($setorKas->tanda_tangan_amil_url)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500 mb-2">Tanda Tangan Amil</p>
                                                <div class="h-36 rounded-xl border border-gray-200 bg-white flex items-center justify-center p-2">
                                                    <img src="{{ $setorKas->tanda_tangan_amil_url }}" alt="TTD Amil" class="max-h-full object-contain">
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($setorKas->tanda_tangan_penerima_url)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-xs text-gray-500 mb-2">Tanda Tangan Penerima</p>
                                                <div class="h-36 rounded-xl border border-gray-200 bg-white flex items-center justify-center p-2">
                                                    <img src="{{ $setorKas->tanda_tangan_penerima_url }}" alt="TTD Penerima" class="max-h-full object-contain">
                                                </div>
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
                <a href="{{ route('amil.setor-kas.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <div class="flex items-center gap-2 flex-wrap">
                    @if($setorKas->bisa_dihapus)
                        <button type="button" onclick="confirmDelete()"
                            class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    @endif
                    @if($setorKas->bisa_diedit)
                        <a href="{{ route('amil.setor-kas.edit', $setorKas->uuid) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 text-center">Hapus Setoran</h3>
        <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
            Hapus setoran "<span class="font-semibold text-gray-700">{{ $setorKas->no_setor }}</span>"?
        </p>
        <p class="text-xs sm:text-sm text-gray-500 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-2 sm:gap-3">
            <button type="button" onclick="closeDeleteModal()"
                class="w-24 sm:w-28 rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form action="{{ route('amil.setor-kas.destroy', $setorKas->uuid) }}" method="POST" class="inline">
                @csrf @method('DELETE')
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
</script>
@endpush