{{-- resources/views/admin-masjid/setor-kas/pending.blade.php --}}
@extends('layouts.app')
@section('title', 'Setoran Kas Pending')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ── Statistics Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 animate-slide-up">
        @php
            $statPending  = $summary['pending']  ?? null;
            $statDiterima = $summary['diterima'] ?? null;
            $statDitolak  = $summary['ditolak']  ?? null;
        @endphp

        {{-- Total Pending --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Menunggu Review</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $statPending->total ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $statPending ? 'Rp '.number_format($statPending->jumlah,0,',','.') : 'Rp 0' }}</p>
                </div>
            </div>
        </div>

        {{-- Total Diterima --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Total Diterima</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $statDiterima->total ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $statDiterima ? 'Rp '.number_format($statDiterima->jumlah,0,',','.') : 'Rp 0' }}</p>
                </div>
            </div>
        </div>

        {{-- Total Ditolak --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Ditolak</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $statDitolak->total ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $statDitolak ? 'Rp '.number_format($statDitolak->jumlah,0,',','.') : 'Rp 0' }}</p>
                </div>
            </div>
        </div>

        {{-- Riwayat Lengkap --}}
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 truncate">Riwayat Lengkap</p>
                    <a href="{{ route('admin-masjid.setor-kas.riwayat') }}"
                        class="text-sm font-semibold text-primary hover:underline">Semua Setoran →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Setoran Kas Menunggu Review</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $setorans->total() }} setoran perlu ditindaklanjuti</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    {{-- Search --}}
                    <div id="search-container" class="transition-all duration-300"
                        style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                        <button type="button" onclick="toggleSearch()" id="search-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                        </button>
                        <form method="GET" action="{{ route('admin-masjid.setor-kas.pending') }}" id="search-form"
                            class="{{ request('q') ? '' : 'hidden' }}">
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="search" name="q" value="{{ request('q') }}"
                                        id="search-input" placeholder="Cari no. setor / amil..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                </div>
                                @if(request('q'))
                                    <a href="{{ route('admin-masjid.setor-kas.pending') }}"
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($setorans->count() > 0)

            {{-- ── Desktop View ── --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amil & Setoran</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($setorans as $setor)
                            {{-- Parent Row --}}
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                data-target="detail-{{ $setor->uuid }}">
                                <td class="px-4 py-4">
                                    <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $setor->tanggal_setor->format('d/m/Y') }} ·
                                            <span class="font-mono">{{ $setor->no_setor }}</span> ·
                                            <span class="font-semibold text-gray-700">{{ $setor->jumlah_disetor_formatted }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                Menunggu Review
                                            </span>
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                                                {{ $setor->periode_dari->format('d M Y') }} s/d {{ $setor->periode_sampai->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-uuid="{{ $setor->uuid }}"
                                        data-no="{{ $setor->no_setor }}"
                                        data-amil="{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}"
                                        data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                        data-periode="{{ $setor->periode_formatted }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            {{-- Expandable Content Row --}}
                            <tr id="detail-{{ $setor->uuid }}" class="hidden expandable-content">
                                <td colspan="3" class="px-0 py-0">
                                    <div class="bg-gray-50 border-y border-gray-100">
                                        <div class="px-6 py-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                {{-- Kolom 1: Info Amil --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Data Amil</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">No. Setoran</p>
                                                                <p class="text-sm font-medium font-mono text-gray-900">{{ $setor->no_setor }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Dibuat</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $setor->created_at->format('d M Y, H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Setoran --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Setoran</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Tanggal Setor</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $setor->tanggal_setor->format('d F Y') }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Periode</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $setor->periode_dari->format('d M Y') }}</p>
                                                                <p class="text-xs text-gray-400">s/d {{ $setor->periode_sampai->format('d M Y') }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Jumlah Disetor</p>
                                                                <p class="text-sm font-semibold text-green-600">{{ $setor->jumlah_disetor_formatted }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status --}}
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Status Review</p>
                                                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                                Menunggu Review
                                                            </span>
                                                        </div>
                                                        @if($setor->catatan)
                                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                                <p class="text-xs text-gray-500 mb-1">Catatan</p>
                                                                <p class="text-sm text-gray-600">{{ $setor->catatan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tombol Aksi di Expandable --}}
                                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center flex-wrap gap-3">
                                                <div class="text-xs text-gray-500">
                                                    No. Setoran: <span class="font-medium font-mono text-gray-700">{{ $setor->no_setor }}</span>
                                                </div>
                                                <div class="flex gap-2 flex-wrap">
                                                    <button type="button"
                                                        data-uuid="{{ $setor->uuid }}"
                                                        data-no="{{ $setor->no_setor }}"
                                                        data-amil="{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}"
                                                        data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                                        data-periode="{{ $setor->periode_formatted }}"
                                                        onclick="openReviewModal(this)"
                                                        class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Review
                                                    </button>
                                                    <a href="{{ route('admin-masjid.setor-kas.show', $setor->uuid) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ── Mobile View ── --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($setorans as $setor)
                    <div class="expandable-card">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $setor->uuid }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                            {{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}
                                        </h3>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200 whitespace-nowrap">
                                            Pending
                                        </span>
                                    </div>
                                    <div class="flex items-center mt-1 gap-2">
                                        <span class="text-xs text-gray-500">{{ $setor->tanggal_setor->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-500">•</span>
                                        <span class="text-xs font-semibold text-gray-700">{{ $setor->jumlah_disetor_formatted }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $setor->no_setor }}</p>
                                </div>
                                <div class="flex items-center gap-1 ml-2">
                                    <button type="button"
                                        class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-uuid="{{ $setor->uuid }}"
                                        data-no="{{ $setor->no_setor }}"
                                        data-amil="{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}"
                                        data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                        data-periode="{{ $setor->periode_formatted }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Expandable Content --}}
                        <div id="detail-mobile-{{ $setor->uuid }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Setoran</h4>
                                        <div class="space-y-2">
                                            <div class="flex items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-gray-600 mr-1">Periode:</span>
                                                <span class="text-gray-900">{{ $setor->periode_dari->format('d M Y') }} s/d {{ $setor->periode_sampai->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex items-center text-sm">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="font-semibold text-green-600">{{ $setor->jumlah_disetor_formatted }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-3 border-t border-gray-200">
                                        <div class="flex gap-2">
                                            <button type="button"
                                                data-uuid="{{ $setor->uuid }}"
                                                data-no="{{ $setor->no_setor }}"
                                                data-amil="{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}"
                                                data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                                data-periode="{{ $setor->periode_formatted }}"
                                                onclick="openReviewModal(this)"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Review
                                            </button>
                                            <a href="{{ route('admin-masjid.setor-kas.show', $setor->uuid) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($setorans->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $setorans->links() }}
                </div>
            @endif

        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-green-100 mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if(request('q'))
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">Tidak ada setoran yang sesuai pencarian</p>
                    <a href="{{ route('admin-masjid.setor-kas.pending') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset Pencarian
                    </a>
                @else
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Semua setoran sudah diproses</h3>
                    <p class="text-sm text-gray-500">Tidak ada setoran kas yang menunggu konfirmasi.</p>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- ── Dropdown Container ── --}}
<div id="dropdown-container" class="fixed hidden z-[9999]" style="min-width:200px;">
    <div class="w-52 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="py-1">
            <a href="#" id="dd-detail"
                class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>
            <button type="button" id="dd-review"
                class="flex items-center w-full px-4 py-2.5 text-sm text-amber-700 hover:bg-amber-50 transition-colors">
                <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Review Setoran
            </button>
        </div>
    </div>
</div>

{{-- ── Modal: Review Setoran ── --}}
<div id="review-modal" class="fixed inset-0 bg-gray-900/60 hidden z-[10000] flex items-center justify-center p-4" style="backdrop-filter: blur(2px);">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col" style="max-height: 90vh;">

        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Review Setoran Kas</h3>
                    <p class="text-xs text-gray-400 mt-0.5 font-mono" id="modal-no-setor"></p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable Body --}}
        <div class="overflow-y-auto flex-1">
            <form id="review-form" method="POST">
                @csrf
                <div class="p-6 space-y-5">

                    {{-- Alert error --}}
                    <div id="modal-alert" class="hidden px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700"></div>

                    {{-- Info Setoran --}}
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 space-y-2.5 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Amil</span>
                            <span class="font-semibold text-gray-900" id="modal-amil"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Jumlah Disetor</span>
                            <span class="font-bold text-lg text-amber-700" id="modal-jumlah"></span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Periode</span>
                            <span class="text-gray-900 text-right font-medium" id="modal-periode"></span>
                        </div>
                    </div>

                    {{-- Hitung Fisik --}}
                    <div>
                        <label for="jumlah_dihitung_fisik" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jumlah Dihitung Fisik
                            <span class="text-xs font-normal text-gray-400 ml-1">(opsional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                            <input type="number" name="jumlah_dihitung_fisik" id="jumlah_dihitung_fisik"
                                placeholder="0"
                                min="0" step="1000"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Bandingkan dengan jumlah yang disetor amil untuk verifikasi fisik</p>
                    </div>

                    {{-- Radio: Terima / Tolak --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keputusan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label id="label-diterima" class="flex items-center gap-3 p-3.5 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-green-300">
                                <input type="radio" name="aksi" value="diterima" class="w-4 h-4 text-green-600 flex-shrink-0" onchange="toggleAksi(this)">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">✓ Terima</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Konfirmasi setoran</p>
                                </div>
                            </label>
                            <label id="label-ditolak" class="flex items-center gap-3 p-3.5 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-red-300">
                                <input type="radio" name="aksi" value="ditolak" class="w-4 h-4 text-red-600 flex-shrink-0" onchange="toggleAksi(this)">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">✕ Tolak</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Kembalikan ke amil</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Alasan Penolakan (muncul jika tolak) --}}
                    <div id="alasan-container" class="hidden">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3"
                            placeholder="Jelaskan alasan penolakan setoran ini kepada amil..."
                            class="block w-full px-3 py-2.5 text-sm border border-red-200 bg-red-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400 transition-all resize-none"></textarea>
                    </div>

                    {{-- Tanda Tangan Penerima (muncul jika terima) --}}
                    <div id="ttd-container" class="hidden">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">
                                Tanda Tangan Penerima
                                <span class="text-xs font-normal text-gray-400 ml-1">(opsional)</span>
                            </label>
                            <button type="button" onclick="clearSignaturePenerima()"
                                class="text-xs text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                        <div class="relative rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 overflow-hidden group">
                            <canvas id="signature-pad-penerima"
                                class="block w-full cursor-crosshair touch-none"
                                style="height: 140px;"></canvas>
                            {{-- Placeholder hint --}}
                            <div id="ttd-placeholder" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    <p class="text-xs text-gray-400">Tanda tangan di sini</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="tanda_tangan_penerima" id="ttd_penerima_input">
                        <p class="mt-1.5 text-xs text-gray-400">Gunakan mouse atau sentuh layar untuk membuat tanda tangan</p>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between gap-3 sticky bottom-0">
                    <div id="modal-keputusan-info" class="text-xs text-gray-400 hidden">
                        <span id="modal-keputusan-text"></span>
                    </div>
                    <div class="flex gap-3 ml-auto">
                        <button type="button" onclick="closeReviewModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-review"
                            class="px-5 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-600 rounded-lg transition-colors shadow-sm inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Referensi elemen ──────────────────────────────────────────
    const dropdown = document.getElementById('dropdown-container');
    const ddDetail = document.getElementById('dd-detail');
    const ddReview = document.getElementById('dd-review');

    // ── Desktop expandable rows ───────────────────────────────────
    document.querySelectorAll('.expandable-row').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, .dropdown-toggle, button')) return;
            const target = document.getElementById(this.dataset.target);
            const icon   = this.querySelector('.expand-icon');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        });
    });

    // ── Mobile expandable cards ───────────────────────────────────
    document.querySelectorAll('.expandable-row-mobile').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, .dropdown-toggle, button')) return;
            const target = document.getElementById(this.dataset.target);
            const icon   = this.querySelector('.expand-icon-mobile');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    // ── Dropdown ─────────────────────────────────────────────────
    function closeDropdown() {
        dropdown.classList.add('hidden');
        dropdown.removeAttribute('data-uuid');
    }

    function positionDropdown(toggle) {
        const rect   = toggle.getBoundingClientRect();
        const ddW    = 208;
        const ddH    = dropdown.offsetHeight || 120;
        const margin = 6;
        const vpW    = window.innerWidth;
        const vpH    = window.innerHeight;

        let left = rect.right - ddW;
        if (left < margin) left = margin;
        if (left + ddW > vpW - margin) left = vpW - ddW - margin;

        let top = rect.bottom + margin;
        if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
        if (top < margin) top = margin;

        dropdown.style.top  = top  + 'px';
        dropdown.style.left = left + 'px';
    }

    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('.dropdown-toggle');

        if (toggle) {
            e.stopPropagation();

            const uuid    = toggle.dataset.uuid;
            const no      = toggle.dataset.no;
            const amil    = toggle.dataset.amil;
            const jumlah  = toggle.dataset.jumlah;
            const periode = toggle.dataset.periode;

            if (dropdown.dataset.uuid === uuid && !dropdown.classList.contains('hidden')) {
                closeDropdown(); return;
            }

            dropdown.dataset.uuid = uuid;

            ddDetail.href = `/admin-setor-kas/${uuid}`;
            ddReview.onclick = () => {
                closeDropdown();
                openReviewModal({ dataset: { uuid, no, amil, jumlah, periode } });
            };

            dropdown.classList.remove('hidden');
            positionDropdown(toggle);

        } else if (!dropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    window.addEventListener('scroll', closeDropdown, true);
    window.addEventListener('resize', closeDropdown);

    // ── Backdrop klik modal ───────────────────────────────────────
    document.getElementById('review-modal').addEventListener('click', function (e) {
        if (e.target === this) closeReviewModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeReviewModal();
    });

    // ── Validasi sebelum submit ───────────────────────────────────
    document.getElementById('review-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Selalu prevent dulu, baru submit manual

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
                document.getElementById('alasan_penolakan').classList.add('ring-2', 'ring-red-400');
                return;
            }
        }

        // Pastikan action sudah ter-set
        if (!form.action || form.action.endsWith('#') || !form.action.includes('/proses')) {
            showModalAlert('Terjadi kesalahan konfigurasi form. Coba tutup dan buka modal kembali.');
            return;
        }

        // Simpan TTD ke hidden field
        const ttdInput = document.getElementById('ttd_penerima_input');
        if (sigCanvas && ttdInput.value.length < 100) {
            try { ttdInput.value = sigCanvas.toDataURL('image/png'); } catch(_) {}
        }

        // Submit manual via fetch agar method POST terjamin
        const formData = new FormData(form);

        const btnSubmit = document.getElementById('btn-submit-review');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Menyimpan...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html',
            },
            credentials: 'same-origin',
        })
        .then(res => {
            if (res.redirected) {
                window.location.href = res.url;
                return;
            }
            if (res.ok) {
                // Redirect ke halaman yang sama untuk refresh data
                window.location.reload();
                return;
            }
            return res.text().then(text => {
                throw new Error('Server error: ' + res.status);
            });
        })
        .catch(err => {
            console.error(err);
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Keputusan';
            showModalAlert('Gagal menyimpan. Silakan coba lagi.');
        });
    });

    document.getElementById('alasan_penolakan').addEventListener('input', function () {
        this.classList.remove('ring-2', 'ring-red-400');
    });

    function showModalAlert(msg) {
        const el = document.getElementById('modal-alert');
        if (el) {
            el.textContent = msg;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 4000);
        } else {
            alert(msg);
        }
    }
});

// ── Search ────────────────────────────────────────────────────────
function toggleSearch() {
    const btn       = document.getElementById('search-button');
    const form      = document.getElementById('search-form');
    const input     = document.getElementById('search-input');
    const container = document.getElementById('search-container');
    if (form.classList.contains('hidden')) {
        btn.classList.add('hidden');
        form.classList.remove('hidden');
        container.style.minWidth = '280px';
        setTimeout(() => input.focus(), 50);
    } else {
        form.classList.add('hidden');
        btn.classList.remove('hidden');
        container.style.minWidth = '';
    }
}

// ── Review Modal ──────────────────────────────────────────────────
function openReviewModal(btn) {
    document.getElementById('modal-no-setor').textContent = btn.dataset.no;
    document.getElementById('modal-amil').textContent     = btn.dataset.amil;
    document.getElementById('modal-jumlah').textContent   = btn.dataset.jumlah;
    document.getElementById('modal-periode').textContent  = btn.dataset.periode;
    document.getElementById('review-form').action         = `/admin-setor-kas/${btn.dataset.uuid}/proses`;

    // Reset semua field
    document.querySelectorAll('input[name="aksi"]').forEach(r => r.checked = false);
    document.getElementById('alasan-container').classList.add('hidden');
    document.getElementById('ttd-container').classList.add('hidden');
    document.getElementById('alasan_penolakan').value      = '';
    document.getElementById('alasan_penolakan').classList.remove('ring-2', 'ring-red-400');
    document.getElementById('jumlah_dihitung_fisik').value = '';
    document.getElementById('ttd_penerima_input').value    = '';

    // Reset style label radio
    document.getElementById('label-diterima').className = document.getElementById('label-diterima').className
        .replace(/border-green-\d+|bg-green-\d+/g, '').trim() + ' border-gray-200';
    document.getElementById('label-ditolak').className = document.getElementById('label-ditolak').className
        .replace(/border-red-\d+|bg-red-\d+/g, '').trim() + ' border-gray-200';

    document.getElementById('review-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Inisialisasi canvas setelah modal visible
    setTimeout(() => {
        initSignaturePad();
    }, 120);
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

    // Style label
    document.getElementById('label-diterima').classList.remove('border-green-400', 'bg-green-50', 'border-red-400', 'bg-red-50');
    document.getElementById('label-ditolak').classList.remove('border-green-400', 'bg-green-50', 'border-red-400', 'bg-red-50');
    document.getElementById('label-diterima').classList.add('border-gray-200');
    document.getElementById('label-ditolak').classList.add('border-gray-200');

    if (radio.value === 'diterima') {
        document.getElementById('label-diterima').classList.remove('border-gray-200');
        document.getElementById('label-diterima').classList.add('border-green-400', 'bg-green-50');
        alasanBox.classList.add('hidden');
        ttdBox.classList.remove('hidden');
        infoEl.classList.remove('hidden');
        textEl.textContent = '✓ Setoran akan dikonfirmasi';
        textEl.className = 'text-xs text-green-600 font-medium';
        // Re-init canvas ukuran
        setTimeout(initSignaturePad, 80);
    } else {
        document.getElementById('label-ditolak').classList.remove('border-gray-200');
        document.getElementById('label-ditolak').classList.add('border-red-400', 'bg-red-50');
        alasanBox.classList.remove('hidden');
        ttdBox.classList.add('hidden');
        infoEl.classList.remove('hidden');
        textEl.textContent = '✕ Setoran akan dikembalikan ke amil';
        textEl.className = 'text-xs text-red-600 font-medium';
        setTimeout(() => document.getElementById('alasan_penolakan').focus(), 50);
    }
}

// ── Signature Pad ─────────────────────────────────────────────────
let sigCanvas, sigCtx, sigDrawing = false, sigLastX = 0, sigLastY = 0;

function initSignaturePad() {
    sigCanvas = document.getElementById('signature-pad-penerima');
    if (!sigCanvas) return;
    sigCtx = sigCanvas.getContext('2d');

    const container = sigCanvas.parentElement;
    const rect      = container.getBoundingClientRect();
    const dpr       = window.devicePixelRatio || 1;

    // Set ukuran canvas sesuai container (support retina/HiDPI)
    sigCanvas.width  = rect.width  * dpr;
    sigCanvas.height = 140         * dpr;
    sigCanvas.style.width  = rect.width + 'px';
    sigCanvas.style.height = '140px';
    sigCtx.scale(dpr, dpr);

    // Bersihkan & tampilkan placeholder
    sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    document.getElementById('ttd-placeholder').style.display = 'flex';
    document.getElementById('ttd_penerima_input').value = '';

    // Hapus event listener lama (clone trick)
    const newCanvas = sigCanvas.cloneNode(true);
    sigCanvas.parentNode.replaceChild(newCanvas, sigCanvas);
    sigCanvas = newCanvas;
    sigCtx    = sigCanvas.getContext('2d');
    sigCtx.scale(dpr, dpr);

    // Pasang event listeners baru
    function getPos(e) {
        const r = sigCanvas.getBoundingClientRect();
        if (e.touches) return { x: e.touches[0].clientX - r.left, y: e.touches[0].clientY - r.top };
        return { x: e.clientX - r.left, y: e.clientY - r.top };
    }

    function startDraw(e) {
        e.preventDefault();
        sigDrawing = true;
        document.getElementById('ttd-placeholder').style.display = 'none';
        const p = getPos(e);
        sigLastX = p.x; sigLastY = p.y;
    }

    function draw(e) {
        e.preventDefault();
        if (!sigDrawing) return;
        const p = getPos(e);
        sigCtx.beginPath();
        sigCtx.moveTo(sigLastX, sigLastY);
        sigCtx.lineTo(p.x, p.y);
        sigCtx.strokeStyle = '#1e293b';
        sigCtx.lineWidth   = 2;
        sigCtx.lineCap     = 'round';
        sigCtx.lineJoin    = 'round';
        sigCtx.stroke();
        sigLastX = p.x; sigLastY = p.y;
    }

    function endDraw() {
        if (!sigDrawing) return;
        sigDrawing = false;
        // Simpan otomatis ke hidden input
        document.getElementById('ttd_penerima_input').value = sigCanvas.toDataURL('image/png');
    }

    sigCanvas.addEventListener('mousedown',  startDraw);
    sigCanvas.addEventListener('mousemove',  draw);
    sigCanvas.addEventListener('mouseup',    endDraw);
    sigCanvas.addEventListener('mouseleave', endDraw);
    sigCanvas.addEventListener('touchstart', startDraw, { passive: false });
    sigCanvas.addEventListener('touchmove',  draw,      { passive: false });
    sigCanvas.addEventListener('touchend',   endDraw);
}

function signatureHasContent() {
    return sigCanvas
        && document.getElementById('ttd_penerima_input').value.length > 100;
}

function clearSignaturePenerima() {
    if (!sigCanvas || !sigCtx) return;
    const dpr = window.devicePixelRatio || 1;
    sigCtx.clearRect(0, 0, sigCanvas.width / dpr, sigCanvas.height / dpr);
    document.getElementById('ttd_penerima_input').value = '';
    document.getElementById('ttd-placeholder').style.display = 'flex';
}
</script>
@endpush