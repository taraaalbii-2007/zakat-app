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

                {{-- Data Lama --}}
                @if($log->data_lama)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            Data Sebelum Perubahan
                        </h3>
                        <div class="bg-red-50 rounded-lg border border-red-200 p-4">
                            <pre class="text-xs text-gray-800 whitespace-pre-wrap break-words overflow-x-auto">{{ json_encode($log->data_lama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif

                {{-- Data Baru --}}
                @if($log->data_baru)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Data Setelah Perubahan
                        </h3>
                        <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                            <pre class="text-xs text-gray-800 whitespace-pre-wrap break-words overflow-x-auto">{{ json_encode($log->data_baru, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif

                {{-- Perbandingan Data --}}
                @if($log->data_lama && $log->data_baru)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Perubahan Data</h3>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                            <div class="divide-y divide-gray-200">
                                @php
                                    $oldData = is_array($log->data_lama) ? $log->data_lama : [];
                                    $newData = is_array($log->data_baru) ? $log->data_baru : [];
                                    $allKeys = array_unique(array_merge(array_keys($oldData), array_keys($newData)));
                                @endphp
                                @foreach($allKeys as $key)
                                    @php
                                        $oldValue = $oldData[$key] ?? null;
                                        $newValue = $newData[$key] ?? null;
                                        $hasChanged = $oldValue !== $newValue;
                                    @endphp
                                    @if($hasChanged)
                                        <div class="px-4 py-3">
                                            <div class="text-xs font-medium text-gray-500 mb-2">
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div class="bg-red-50 rounded p-2 border border-red-200">
                                                    <div class="text-xs text-red-600 font-medium mb-1">Sebelum</div>
                                                    <div class="text-sm text-gray-900 break-words">
                                                        {{ is_array($oldValue) ? json_encode($oldValue) : ($oldValue ?? '-') }}
                                                    </div>
                                                </div>
                                                <div class="bg-green-50 rounded p-2 border border-green-200">
                                                    <div class="text-xs text-green-600 font-medium mb-1">Sesudah</div>
                                                    <div class="text-sm text-gray-900 break-words">
                                                        {{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? '-') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
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

                {{-- Informasi Teknis --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Informasi Teknis</h3>
                    
                    <div class="space-y-3">
                        @if($log->ip_address)
                            <div class="bg-white rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    <p class="text-xs text-gray-500">IP Address</p>
                                </div>
                                <p class="text-sm font-mono text-gray-900">{{ $log->ip_address }}</p>
                            </div>
                        @endif

                        @if($log->user_agent)
                            <div class="bg-white rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs text-gray-500">User Agent</p>
                                </div>
                                <p class="text-xs text-gray-700 break-words" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 80) }}
                                </p>
                            </div>
                        @endif

                        <div class="bg-white rounded-lg border border-gray-200 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <p class="text-xs text-gray-500">UUID Log</p>
                            </div>
                            <p class="text-xs font-mono text-gray-900 break-all">{{ $log->uuid }}</p>
                        </div>
                    </div>
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