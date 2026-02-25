@extends('layouts.app')

@section('title', 'Edit Konfigurasi Integrasi')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Konfigurasi Integrasi</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui pengaturan WhatsApp dan QRIS</p>
            </div>
            <a href="{{ route('konfigurasi-integrasi.show') }}"
               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <form action="{{ route('konfigurasi-integrasi.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-8">

                {{-- WHATSAPP CONFIGURATION --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Konfigurasi WhatsApp</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="whatsapp_api_key" class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                            <input type="password" name="whatsapp_api_key" id="whatsapp_api_key"
                                value="{{ old('whatsapp_api_key', $whatsapp->api_key) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan API Key">
                            @error('whatsapp_api_key') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="whatsapp_nomor_pengirim" class="block text-sm font-medium text-gray-700 mb-2">Nomor Pengirim</label>
                            <input type="text" name="whatsapp_nomor_pengirim" id="whatsapp_nomor_pengirim"
                                value="{{ old('whatsapp_nomor_pengirim', $whatsapp->nomor_pengirim) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="628xxxxxxxxxx">
                            @error('whatsapp_nomor_pengirim') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="whatsapp_api_url" class="block text-sm font-medium text-gray-700 mb-2">URL API</label>
                            <input type="url" name="whatsapp_api_url" id="whatsapp_api_url"
                                value="{{ old('whatsapp_api_url', $whatsapp->api_url) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="https://api.fonnte.com/send">
                            @error('whatsapp_api_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="whatsapp_nomor_tujuan_default" class="block text-sm font-medium text-gray-700 mb-2">Nomor Tujuan Default</label>
                            <input type="text" name="whatsapp_nomor_tujuan_default" id="whatsapp_nomor_tujuan_default"
                                value="{{ old('whatsapp_nomor_tujuan_default', $whatsapp->nomor_tujuan_default) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="628xxxxxxxxxx">
                            @error('whatsapp_nomor_tujuan_default') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="whatsapp_is_active" value="1"
                                {{ $whatsapp->is_active ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Aktifkan WhatsApp Integration</span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- QRIS CONFIGURATION --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Integrasi QRIS</h2>

                    {{-- Preview gambar saat ini --}}
                    @if($qris->qris_image_url)
                    <div class="mb-6">
                        <label class="text-sm font-medium text-gray-700 block mb-2">Gambar QRIS Saat Ini</label>
                        <div class="w-32 h-32 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                            <img src="{{ $qris->qris_image_url }}" alt="QRIS" class="w-full h-full object-contain p-2">
                        </div>
                    </div>
                    @endif

                    {{-- Upload file --}}
                    <div class="mb-6">
                        <label for="qris_image" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar QRIS</label>
                        <input type="file" name="qris_image" id="qris_image" accept="image/*" class="hidden"
                               onchange="previewQrisImage(this)">
                        <button type="button" onclick="document.getElementById('qris_image').click()"
                            class="px-4 py-2 border-2 border-dashed border-blue-400 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-50">
                            Pilih Gambar
                        </button>
                        <p class="text-xs text-gray-500 mt-2">JPG, JPEG, PNG, GIF · Maks. 5MB</p>
                        @error('qris_image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Preview upload baru --}}
                    <div id="qris-preview-container"></div>

                    {{-- Hapus gambar lama --}}
                    @if($qris->qris_image_url)
                    <div class="mb-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="hapus_qris_image" value="1"
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-sm text-red-600 font-medium">Hapus gambar QRIS saat ini</span>
                        </label>
                    </div>
                    @endif

                    {{-- Aktifkan QRIS --}}
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="qris_is_active" value="1"
                                {{ $qris->is_active ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Aktifkan QRIS Integration</span>
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                        Simpan Konfigurasi
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewQrisImage(input) {
    const container = document.getElementById('qris-preview-container');
    container.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'mb-6';
            div.innerHTML = `
                <label class="text-sm font-medium text-gray-700 block mb-2">Preview Gambar Baru</label>
                <div class="w-32 h-32 rounded-lg border border-blue-300 bg-blue-50 flex items-center justify-center overflow-hidden">
                    <img src="${e.target.result}" alt="Preview QRIS" class="w-full h-full object-contain p-2">
                </div>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush