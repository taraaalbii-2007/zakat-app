@extends('layouts.app')

@section('title', 'Konfigurasi Integrasi')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500">Pengaturan</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-900">Konfigurasi Integrasi</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Konfigurasi Integrasi</h1>
                        <p class="text-sm text-gray-600 mt-1">Kelola pengaturan WhatsApp untuk {{ $masjid->nama }}</p>
                    </div>
                    <a href="{{ route('konfigurasi-integrasi.edit') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Konfigurasi
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-10">
                <!-- Data Masjid Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Informasi Masjid</h2>
                        <p class="text-sm text-gray-600">Data masjid yang terdaftar dalam sistem</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Nama Masjid</span>
                            <p class="text-base text-gray-700">{{ $masjid->nama }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Kode Masjid</span>
                            <p class="text-base text-gray-700 font-mono">{{ $masjid->kode_masjid }}</p>
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Email</span>
                            @if ($masjid->email)
                                <a href="mailto:{{ $masjid->email }}"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $masjid->email }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Telepon</span>
                            @if ($masjid->telepon)
                                <a href="tel:{{ $masjid->telepon }}"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $masjid->telepon }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2 space-y-2">
                            <span class="text-sm font-medium text-gray-900">Alamat</span>
                            <p class="text-gray-700">{{ $masjid->alamat }}</p>
                            @if ($masjid->kelurahan_nama || $masjid->kecamatan_nama)
                                <p class="text-sm text-gray-500">
                                    {{ $masjid->kelurahan_nama ? $masjid->kelurahan_nama . ', ' : '' }}
                                    {{ $masjid->kecamatan_nama ? $masjid->kecamatan_nama . ', ' : '' }}
                                    {{ $masjid->kota_nama ? $masjid->kota_nama . ', ' : '' }}
                                    {{ $masjid->provinsi_nama }}
                                    @if ($masjid->kode_pos)
                                        - {{ $masjid->kode_pos }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Configuration Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Konfigurasi WhatsApp</h2>
                                <p class="text-sm text-gray-600">Pengaturan integrasi WhatsApp menggunakan Fonnte API</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $whatsapp->status_badge_class }}">
                                {{ $whatsapp->status_label }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">API Key</span>
                            @if ($whatsapp->api_key)
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-700 font-mono truncate flex-1" id="whatsapp-api-key-text">
                                        {{ str_repeat('•', max(0, strlen($whatsapp->api_key) - 4)) . substr($whatsapp->api_key, -4) }}
                                    </p>
                                    <button type="button"
                                        onclick="toggleVisibility('whatsapp-api-key', '{{ $whatsapp->api_key }}')"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg id="whatsapp-api-key-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="whatsapp-api-key-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Nomor Pengirim</span>
                            @if ($whatsapp->nomor_pengirim)
                                <a href="https://wa.me/{{ $whatsapp->formatted_nomor_pengirim }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $whatsapp->nomor_pengirim }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">API URL</span>
                            @if ($whatsapp->api_url)
                                <p class="text-gray-700 font-mono text-sm break-all">{{ $whatsapp->api_url }}</p>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-gray-900">Nomor Tujuan Default</span>
                            @if ($whatsapp->nomor_tujuan_default)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp->nomor_tujuan_default) }}" target="_blank"
                                    class="block text-gray-700 hover:text-primary hover:underline">
                                    {{ $whatsapp->nomor_tujuan_default }}
                                </a>
                            @else
                                <p class="text-gray-400">Belum diatur</p>
                            @endif
                        </div>
                    </div>

                    @if (!$whatsapp->isConfigurationComplete())
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium mb-1">Konfigurasi Belum Lengkap</p>
                                    <p>Mohon lengkapi API Key dan Nomor Pengirim untuk mengaktifkan integrasi WhatsApp.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const maskedValues = {
        'whatsapp-api-key': '{{ $whatsapp->api_key ? str_repeat("•", max(0, strlen($whatsapp->api_key) - 4)) . substr($whatsapp->api_key, -4) : "" }}',
    };

    function toggleVisibility(fieldId, fullValue) {
        const textElement = document.getElementById(fieldId + '-text');
        const eyeOpen = document.getElementById(fieldId + '-eye-open');
        const eyeClosed = document.getElementById(fieldId + '-eye-closed');

        if (eyeOpen.classList.contains('hidden')) {
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
            textElement.textContent = maskedValues[fieldId];
        } else {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
            textElement.textContent = fullValue;
        }
    }
</script>
@endpush