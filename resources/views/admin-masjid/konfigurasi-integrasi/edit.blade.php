@extends('layouts.app')

@section('title', 'Edit Konfigurasi Integrasi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Konfigurasi Integrasi</h1>
                    <p class="text-gray-600 mt-1">Perbarui pengaturan WhatsApp dan Midtrans untuk {{ $masjid->nama }}</p>
                </div>
                <a href="{{ route('konfigurasi-integrasi.show') }}"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Form Edit -->
            <form action="{{ route('konfigurasi-integrasi.update') }}" method="POST" id="konfigurasiForm">
                @csrf

                <!-- Informasi Masjid -->
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Informasi Masjid
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Masjid (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Masjid
                            </label>
                            <input type="text" value="{{ $masjid->nama }}" readonly
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <!-- Kode Masjid (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Masjid
                            </label>
                            <input type="text" value="{{ $masjid->kode_masjid }}" readonly
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 font-mono cursor-not-allowed">
                        </div>

                        <!-- Email (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="text" value="{{ $masjid->email ?? 'Belum diatur' }}" readonly
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <!-- Telepon (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon
                            </label>
                            <input type="text" value="{{ $masjid->telepon ?? 'Belum diatur' }}" readonly
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <!-- Alamat (Read Only) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea rows="2" readonly
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">{{ $masjid->alamat }}{{ $masjid->kelurahan_nama ? ', ' . $masjid->kelurahan_nama : '' }}{{ $masjid->kecamatan_nama ? ', ' . $masjid->kecamatan_nama : '' }}{{ $masjid->kota_nama ? ', ' . $masjid->kota_nama : '' }}{{ $masjid->provinsi_nama ? ', ' . $masjid->provinsi_nama : '' }}{{ $masjid->kode_pos ? ' - ' . $masjid->kode_pos : '' }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Informasi</p>
                                <p>Data masjid bersifat read-only. Untuk mengubah data masjid, silakan hubungi administrator sistem.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Konfigurasi WhatsApp -->
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Konfigurasi WhatsApp</h2>
                            <p class="text-sm text-gray-600 mt-1">Integrasi WhatsApp menggunakan Fonnte API</p>
                        </div>
                        <div class="flex items-center">
                            <label for="whatsapp_is_active" class="text-sm font-medium text-gray-700 mr-3">
                                Status Aktif
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="whatsapp_is_active" id="whatsapp_is_active" 
                                    class="sr-only peer" value="1"
                                    {{ old('whatsapp_is_active', $whatsapp->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Info Box Fonnte -->
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-green-800">
                                <p class="font-medium mb-1">Cara Mendapatkan API Key Fonnte</p>
                                <ol class="list-decimal ml-4 space-y-1">
                                    <li>Daftar akun di <a href="https://fonnte.com" target="_blank" class="underline hover:text-green-900">fonnte.com</a></li>
                                    <li>Beli paket sesuai kebutuhan atau gunakan free trial</li>
                                    <li>Masuk ke dashboard dan copy API Key Anda</li>
                                    <li>Hubungkan nomor WhatsApp dengan scan QR Code</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- API Key WhatsApp -->
                        <div>
                            <label for="whatsapp_api_key" class="block text-sm font-medium text-gray-700 mb-2">
                                API Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="whatsapp_api_key" id="whatsapp_api_key"
                                    value="{{ old('whatsapp_api_key', $whatsapp->api_key) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('whatsapp_api_key') border-red-500 @enderror"
                                    placeholder="Masukkan API Key dari Fonnte">
                                <button type="button" onclick="togglePasswordField('whatsapp_api_key')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-whatsapp_api_key" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-whatsapp_api_key" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('whatsapp_api_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Pengirim -->
                        <div>
                            <label for="whatsapp_nomor_pengirim" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="whatsapp_nomor_pengirim" id="whatsapp_nomor_pengirim"
                                value="{{ old('whatsapp_nomor_pengirim', $whatsapp->nomor_pengirim) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('whatsapp_nomor_pengirim') border-red-500 @enderror"
                                placeholder="Contoh: 081234567890">
                            <p class="text-xs text-gray-500 mt-1">Nomor yang terhubung dengan Fonnte (tanpa +62)</p>
                            @error('whatsapp_nomor_pengirim')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- API URL -->
                        <div>
                            <label for="whatsapp_api_url" class="block text-sm font-medium text-gray-700 mb-2">
                                API URL
                            </label>
                            <input type="url" name="whatsapp_api_url" id="whatsapp_api_url"
                                value="{{ old('whatsapp_api_url', $whatsapp->api_url ?? 'https://api.fonnte.com/send') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('whatsapp_api_url') border-red-500 @enderror"
                                placeholder="https://api.fonnte.com/send">
                            @error('whatsapp_api_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Tujuan Default -->
                        <div>
                            <label for="whatsapp_nomor_tujuan_default" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Tujuan Default
                            </label>
                            <input type="text" name="whatsapp_nomor_tujuan_default" id="whatsapp_nomor_tujuan_default"
                                value="{{ old('whatsapp_nomor_tujuan_default', $whatsapp->nomor_tujuan_default) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('whatsapp_nomor_tujuan_default') border-red-500 @enderror"
                                placeholder="Contoh: 081234567890">
                            <p class="text-xs text-gray-500 mt-1">Nomor untuk menerima notifikasi admin (opsional)</p>
                            @error('whatsapp_nomor_tujuan_default')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Test Connection Button -->
                    <div class="mt-4">
                        <button type="button" onclick="testWhatsappConnection()"
                            class="inline-flex items-center px-4 py-2 border border-green-300 text-green-700 font-medium rounded-lg hover:bg-green-50 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Test Koneksi WhatsApp
                        </button>
                    </div>
                </div>

                <!-- Konfigurasi Midtrans -->
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Konfigurasi Midtrans</h2>
                            <p class="text-sm text-gray-600 mt-1">Payment Gateway untuk menerima donasi online</p>
                        </div>
                        <div class="flex items-center">
                            <label for="midtrans_is_active" class="text-sm font-medium text-gray-700 mr-3">
                                Status Aktif
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="midtrans_is_active" id="midtrans_is_active" 
                                    class="sr-only peer" value="1"
                                    {{ old('midtrans_is_active', $midtrans->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Info Box Midtrans -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Cara Mendapatkan Kredensial Midtrans</p>
                                <ol class="list-decimal ml-4 space-y-1">
                                    <li>Daftar akun di <a href="https://dashboard.midtrans.com/register" target="_blank" class="underline hover:text-blue-900">dashboard.midtrans.com</a></li>
                                    <li>Verifikasi akun dan lengkapi data bisnis</li>
                                    <li>Buka menu <strong>Settings → Access Keys</strong></li>
                                    <li>Copy <strong>Merchant ID</strong>, <strong>Client Key</strong>, dan <strong>Server Key</strong></li>
                                    <li>Gunakan <strong>Sandbox</strong> untuk testing, <strong>Production</strong> untuk live</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Merchant ID -->
                        <div>
                            <label for="midtrans_merchant_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Merchant ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="midtrans_merchant_id" id="midtrans_merchant_id"
                                value="{{ old('midtrans_merchant_id', $midtrans->merchant_id) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('midtrans_merchant_id') border-red-500 @enderror"
                                placeholder="Contoh: M123456">
                            @error('midtrans_merchant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Environment -->
                        <div>
                            <label for="midtrans_environment" class="block text-sm font-medium text-gray-700 mb-2">
                                Environment <span class="text-red-500">*</span>
                            </label>
                            <select name="midtrans_environment" id="midtrans_environment"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('midtrans_environment') border-red-500 @enderror">
                                <option value="sandbox" {{ old('midtrans_environment', $midtrans->environment) == 'sandbox' ? 'selected' : '' }}>
                                    Sandbox (Testing)
                                </option>
                                <option value="production" {{ old('midtrans_environment', $midtrans->environment) == 'production' ? 'selected' : '' }}>
                                    Production (Live)
                                </option>
                            </select>
                            @error('midtrans_environment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Client Key -->
                        <div>
                            <label for="midtrans_client_key" class="block text-sm font-medium text-gray-700 mb-2">
                                Client Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="midtrans_client_key" id="midtrans_client_key"
                                    value="{{ old('midtrans_client_key', $midtrans->client_key) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('midtrans_client_key') border-red-500 @enderror"
                                    placeholder="SB-Mid-client-xxxxx">
                                <button type="button" onclick="togglePasswordField('midtrans_client_key')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-midtrans_client_key" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-midtrans_client_key" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('midtrans_client_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Server Key -->
                        <div>
                            <label for="midtrans_server_key" class="block text-sm font-medium text-gray-700 mb-2">
                                Server Key <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="midtrans_server_key" id="midtrans_server_key"
                                    value="{{ old('midtrans_server_key', $midtrans->server_key) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('midtrans_server_key') border-red-500 @enderror"
                                    placeholder="SB-Mid-server-xxxxx">
                                <button type="button" onclick="togglePasswordField('midtrans_server_key')"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                    <svg id="eye-midtrans_server_key" class="w-5 h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg id="eye-slash-midtrans_server_key" class="w-5 h-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jangan bagikan Server Key kepada siapapun</p>
                            @error('midtrans_server_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Test Connection Button -->
                    <div class="mt-4">
                        <button type="button" onclick="testMidtransConnection()"
                            class="inline-flex items-center px-4 py-2 border border-blue-300 text-blue-700 font-medium rounded-lg hover:bg-blue-50 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Test Koneksi Midtrans
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password visibility
        function togglePasswordField(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-' + fieldId);
            const eyeSlashIcon = document.getElementById('eye-slash-' + fieldId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        // Test WhatsApp Connection
        async function testWhatsappConnection() {
            const apiKey = document.getElementById('whatsapp_api_key').value;
            const nomorTujuan = document.getElementById('whatsapp_nomor_tujuan_default').value || 
                              document.getElementById('whatsapp_nomor_pengirim').value;

            if (!apiKey) {
                alert('Mohon isi API Key terlebih dahulu');
                return;
            }

            if (!nomorTujuan) {
                alert('Mohon isi Nomor Pengirim atau Nomor Tujuan Default');
                return;
            }

            const button = event.target;
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Testing...
            `;

            try {
                const response = await fetch('{{ route('konfigurasi-integrasi.test-whatsapp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        api_key: apiKey,
                        nomor_tujuan: nomorTujuan
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (error) {
                alert('❌ Terjadi kesalahan: ' + error.message);
            } finally {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }

        // Test Midtrans Connection
        async function testMidtransConnection() {
            const serverKey = document.getElementById('midtrans_server_key').value;
            const environment = document.getElementById('midtrans_environment').value;

            if (!serverKey) {
                alert('Mohon isi Server Key terlebih dahulu');
                return;
            }

            const button = event.target;
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Testing...
            `;

            try {
                const response = await fetch('{{ route('konfigurasi-integrasi.test-midtrans') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        server_key: serverKey,
                        environment: environment
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (error) {
                alert('❌ Terjadi kesalahan: ' + error.message);
            } finally {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }

        // Form validation before submit
        document.getElementById('konfigurasiForm').addEventListener('submit', function(e) {
            const whatsappActive = document.getElementById('whatsapp_is_active').checked;
            const midtransActive = document.getElementById('midtrans_is_active').checked;

            if (whatsappActive) {
                const apiKey = document.getElementById('whatsapp_api_key').value;
                const nomorPengirim = document.getElementById('whatsapp_nomor_pengirim').value;

                if (!apiKey || !nomorPengirim) {
                    e.preventDefault();
                    alert('Mohon lengkapi API Key dan Nomor Pengirim WhatsApp sebelum mengaktifkan');
                    return false;
                }
            }

            if (midtransActive) {
                const merchantId = document.getElementById('midtrans_merchant_id').value;
                const clientKey = document.getElementById('midtrans_client_key').value;
                const serverKey = document.getElementById('midtrans_server_key').value;

                if (!merchantId || !clientKey || !serverKey) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua kredensial Midtrans sebelum mengaktifkan');
                    return false;
                }
            }
        });
    </script>
@endpush