{{-- resources/views/admin-masjid/program/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Buat Program Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Buat Program Zakat</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi informasi program zakat yang akan dibuat</p>
            </div>

            <form action="{{ route('program-zakat.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf

                {{-- Section 1 - Informasi Program --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Informasi Program
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Info Masjid (Read-only) --}}
                        @if($masjid)
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-blue-900 mb-1">Masjid</p>
                                    <p class="text-sm font-semibold text-blue-700">{{ $masjid->nama }}</p>
                                    @if($masjid->alamat)
                                    <p class="text-xs text-blue-600 mt-1">{{ $masjid->alamat }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Kode Program (Auto-generated, Read-only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Program
                            </label>
                            <input type="text" value="{{ $kodeProgram }}" disabled
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl bg-gray-50 cursor-not-allowed shadow-inner">
                            <p class="mt-1 text-xs text-gray-500">Kode program akan digenerate otomatis saat menyimpan</p>
                        </div>

                        {{-- Nama Program --}}
                        <div>
                            <label for="nama_program" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Program <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_program" id="nama_program" value="{{ old('nama_program') }}"
                                placeholder="Contoh: Program Zakat Fitrah Ramadhan 1446 H"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nama_program') border-red-500 @enderror">
                            @error('nama_program')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Program
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                placeholder="Jelaskan tujuan dan detail program zakat ini..."
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Periode Program --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_mulai') border-red-500 @enderror">
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai (Opsional)
                                </label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_selesai') border-red-500 @enderror">
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika program tidak memiliki batas waktu</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2 - Target Program --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Target Program
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            {{-- Target Dana --}}
                            <div>
                                <label for="target_dana" class="block text-sm font-medium text-gray-700 mb-2">
                                    Target Dana (Rp)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                    <input type="number" name="target_dana" id="target_dana" value="{{ old('target_dana') }}"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        class="block w-full pl-10 pr-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('target_dana') border-red-500 @enderror">
                                </div>
                                @error('target_dana')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada target dana</p>
                            </div>

                            {{-- Target Mustahik --}}
                            <div>
                                <label for="target_mustahik" class="block text-sm font-medium text-gray-700 mb-2">
                                    Target Jumlah Mustahik
                                </label>
                                <input type="number" name="target_mustahik" id="target_mustahik" value="{{ old('target_mustahik') }}"
                                    placeholder="0"
                                    min="0"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('target_mustahik') border-red-500 @enderror">
                                @error('target_mustahik')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Jumlah penerima yang ditargetkan</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3 - Upload Foto/Poster --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                        Foto/Poster Program
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Foto Poster Program
                            </div>
                        </label>
                        <div class="space-y-3">
                            <div id="poster-preview" class="h-48 w-full rounded-lg bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 hover:border-primary/50 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Belum ada foto dipilih</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input type="file" name="foto_poster" id="foto_poster" accept="image/jpeg,image/png,image/jpg"
                                    class="hidden" onchange="previewImage(this, 'poster-preview', 'remove-poster')">
                                <label for="foto_poster"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Pilih Foto
                                </label>
                                <button type="button" id="remove-poster" onclick="removeImage('foto_poster', 'poster-preview', 'remove-poster')" 
                                    class="hidden px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                            @error('foto_poster')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('program-zakat.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" name="status" value="draft"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan Draft
                    </button>
                    <button type="submit" name="status" value="aktif"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Aktifkan Program
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth >= 768) {
                document.getElementById('nama_program')?.focus();
            }

            // Set min date for tanggal_selesai based on tanggal_mulai
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            tanggalMulai.addEventListener('change', function() {
                tanggalSelesai.min = this.value;
                if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                    tanggalSelesai.value = this.value;
                }
            });
        });

        function previewImage(input, previewId, removeBtnId) {
            const preview = document.getElementById(previewId);
            const removeBtn = document.getElementById(removeBtnId);
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    input.value = '';
                    return;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPG, JPEG, atau PNG');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-contain" alt="Preview">`;
                    preview.classList.remove('border-dashed');
                    preview.classList.add('border-solid');
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage(inputId, previewId, removeBtnId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const removeBtn = document.getElementById(removeBtnId);
            
            input.value = '';
            preview.innerHTML = `
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">Belum ada foto dipilih</p>
                </div>
            `;
            preview.classList.add('border-dashed');
            preview.classList.remove('border-solid');
            removeBtn.classList.add('hidden');
        }
    </script>
@endpush