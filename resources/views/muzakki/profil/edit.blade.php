{{-- resources/views/muzakki/profil/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- ── Header ── --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Profil</h1>
                <p class="text-gray-600 mt-1">Perbarui data diri Anda</p>
            </div>
            <a href="{{ route('muzakki.profil.show') }}"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Profil
            </a>
        </div>

        {{-- ── Alert ── --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════
             FORM EDIT PROFIL + FOTO
        ══════════════════════════════════════════════════ --}}
        <form action="{{ route('muzakki.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ── Upload Foto ── --}}
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Foto Profil</h2>
                <div class="flex items-start space-x-6">
                    <div class="shrink-0">
                        @if($muzakki->foto)
                            <img id="foto-preview"
                                src="{{ $muzakki->foto_url }}"
                                alt="Foto Profil"
                                class="h-24 w-24 object-cover rounded-full border-2 border-gray-200">
                        @else
                            <div class="h-24 w-24 rounded-full bg-primary/20 border-2 border-gray-200 flex items-center justify-center relative">
                                <img id="foto-preview" src="" alt=""
                                    class="h-24 w-24 object-cover rounded-full border-2 border-gray-200 hidden absolute inset-0">
                                <span id="foto-initial" class="text-2xl font-semibold text-primary">
                                    {{ strtoupper(substr($muzakki->nama, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                            Unggah Foto Baru
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('foto') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($muzakki->foto)
                            <div class="mt-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="remove_foto" value="1"
                                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-600">Hapus foto saat ini</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Data Pribadi ── --}}
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Data Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama --}}
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama"
                            value="{{ old('nama', $muzakki->nama) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('nama') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="telepon" id="telepon"
                            value="{{ old('telepon', $muzakki->telepon) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('telepon') border-red-500 @enderror"
                            placeholder="Contoh: 081234567890" required>
                        @error('telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                        <input type="text" name="nik" id="nik"
                            value="{{ old('nik', $muzakki->nik) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('nik') border-red-500 @enderror"
                            placeholder="16 digit NIK (opsional)" maxlength="16">
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 resize-none @error('alamat') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $muzakki->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Action Buttons ── --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('muzakki.profil.show') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('foto-preview');
            const initial = document.getElementById('foto-initial');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (initial) initial.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush