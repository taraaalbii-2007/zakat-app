@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')
@php
    $typeConfig = match($log->aktivitas) {
        'login' => ['color' => 'blue', 'label' => 'Login'],
        'logout' => ['color' => 'gray', 'label' => 'Logout'],
        'create' => ['color' => 'green', 'label' => 'Tambah Data'],
        'update' => ['color' => 'amber', 'label' => 'Perbarui Data'],
        'delete' => ['color' => 'red', 'label' => 'Hapus Data'],
        'approve' => ['color' => 'purple', 'label' => 'Approve'],
        'view' => ['color' => 'cyan', 'label' => 'Lihat Data'],
        default => ['color' => 'gray', 'label' => ucfirst(str_replace('_', ' ', $log->aktivitas))]
    };
    $color = $typeConfig['color'];
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('log-aktivitas.index') }}" 
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Log Aktivitas
        </a>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Section --}}
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-700">
                            {{ $typeConfig['label'] }}
                        </span>
                        <span class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $log->modul) }}</span>
                    </div>
                    <h1 class="text-lg font-semibold text-gray-900">{{ $log->deskripsi ?? 'Detail Aktivitas' }}</h1>
                    <p class="text-sm text-gray-500 mt-2">
                        {{ $log->created_at->format('d F Y') }} pukul {{ $log->created_at->format('H:i:s') }}
                        <span class="text-gray-400 mx-1">•</span>
                        {{ $log->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
            {{-- Left Column - Detail --}}
            <div class="lg:col-span-2 p-6 space-y-6">
                {{-- Informasi Aktivitas --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Informasi Aktivitas</h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <div class="px-4 py-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <span class="text-sm font-medium text-gray-500">Tipe Aktivitas</span>
                                <span class="text-sm text-gray-900 sm:col-span-2 capitalize">
                                    {{ str_replace('_', ' ', $log->aktivitas) }}
                                </span>
                            </div>
                            <div class="px-4 py-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <span class="text-sm font-medium text-gray-500">Modul</span>
                                <span class="text-sm text-gray-900 sm:col-span-2 capitalize">
                                    {{ str_replace('_', ' ', $log->modul) }}
                                </span>
                            </div>
                            <div class="px-4 py-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <span class="text-sm font-medium text-gray-500">Deskripsi</span>
                                <span class="text-sm text-gray-900 sm:col-span-2">
                                    {{ $log->deskripsi ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Info --}}
            <div class="p-6 space-y-6 bg-gray-50/50">
                {{-- Pengguna --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Pengguna</h3>
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-full bg-{{ $color }}-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-{{ $color }}-700">
                                {{ strtoupper(substr($log->nama_pengguna, 0, 2)) }}
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $log->nama_pengguna }}</p>
                            @if($log->email_pengguna)
                                <p class="text-xs text-gray-500 truncate">{{ $log->email_pengguna }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $log->peran) }}</p>
                        </div>
                    </div>

                    @if($log->pengguna)
                        <a href="{{ route('pengguna.show', $log->pengguna->uuid) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-primary bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Lihat Profil
                        </a>
                    @endif
                </div>

                {{-- Waktu --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Waktu</h3>
                    
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-{{ $color }}-100 mb-3">
                                <svg class="w-6 h-6 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $log->created_at->format('H:i') }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $log->created_at->format('d F Y') }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection