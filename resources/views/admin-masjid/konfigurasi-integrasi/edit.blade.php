@extends('layouts.app')

@section('title', 'Edit Konfigurasi Integrasi')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Konfigurasi Integrasi</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui pengaturan QRIS</p>
            </div>
            <a href="{{ route('konfigurasi-integrasi.show') }}"
               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <form action="{{ route('konfigurasi-integrasi.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-8">

                {{-- QRIS CONFIGURATION --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Integrasi QRIS</h2>
                    <p class="text-sm text-gray-500 mb-6">Upload gambar QRIS untuk ditampilkan pada halaman pembayaran</p>

                    {{-- Preview gambar saat ini --}}
                    @if($qris->qris_image_url)
                    <div class="mb-6">
                        <label class="text-sm font-medium text-gray-700 block mb-2">Gambar QRIS Saat Ini</label>
                        <div class="w-40 h-40 rounded-xl border-2 border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shadow-sm">
                            <img src="{{ $qris->qris_image_url }}" alt="QRIS" class="w-full h-full object-contain p-2"
                                 onerror="this.parentElement.innerHTML='<p class=\'text-xs text-red-500 p-4 text-center\'>Gambar tidak ditemukan</p>'">
                        </div>
                    </div>
                    @endif

                    {{-- Upload file --}}
                    <div class="mb-6">
                        <label for="qris_image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $qris->qris_image_url ? 'Ganti Gambar QRIS' : 'Upload Gambar QRIS' }}
                        </label>

                        <input type="file" name="qris_image" id="qris_image" accept="image/*" class="hidden"
                               onchange="previewQrisImage(this)">

                        <button type="button" onclick="document.getElementById('qris_image').click()"
                                class="inline-flex items-center px-4 py-2.5 border-2 border-dashed border-blue-400 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Pilih Gambar
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG, GIF &bull; Maks. 5MB</p>

                        @error('qris_image')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Preview upload baru --}}
                    <div id="qris-preview-container"></div>

                    {{-- Hapus gambar lama --}}
                    @if($qris->qris_image_url)
                    <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="hapus_qris_image" value="1"
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-sm text-red-700 font-medium">Hapus gambar QRIS saat ini</span>
                        </label>
                        <p class="text-xs text-red-500 mt-1 ml-6">Centang ini hanya jika ingin menghapus gambar tanpa menggantinya</p>
                    </div>
                    @endif

                    {{-- Aktifkan QRIS --}}
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="qris_is_active" value="1"
                                   {{ $qris->is_active ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Aktifkan QRIS</span>
                                <p class="text-xs text-gray-500">QRIS akan ditampilkan pada halaman pembayaran</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('konfigurasi-integrasi.show') }}"
                       class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
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
            container.innerHTML = `
                <div class="mb-6">
                    <label class="text-sm font-medium text-gray-700 block mb-2">Preview Gambar Baru</label>
                    <div class="w-40 h-40 rounded-xl border-2 border-blue-300 bg-blue-50 flex items-center justify-center overflow-hidden shadow-sm">
                        <img src="${e.target.result}" alt="Preview QRIS" class="w-full h-full object-contain p-2">
                    </div>
                    <p class="text-xs text-blue-600 mt-1">${input.files[0].name}</p>
                </div>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush