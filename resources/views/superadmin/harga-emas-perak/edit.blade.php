@extends('layouts.app')

@section('title', 'Edit Harga Emas & Perak')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Harga Emas & Perak</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Tanggal: {{ $harga->tanggal->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('harga-emas-perak.update', $harga->uuid) }}" method="POST" class="p-4 sm:p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- SECTION: DATA DASAR -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Data Dasar
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $harga->tanggal->format('Y-m-d')) }}"
                                    required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('tanggal') border-red-500 @enderror">
                                @error('tanggal')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Sumber --}}
                            <div>
                                <label for="sumber" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sumber Data
                                </label>
                                <input type="text" name="sumber" id="sumber" value="{{ old('sumber', $harga->sumber) }}"
                                    placeholder="Contoh: Antam, Pegadaian, dll"
                                    maxlength="100"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('sumber') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Sumber data harga (opsional)</p>
                                @error('sumber')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: HARGA -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Detail Harga
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Harga Emas per Gram --}}
                            <div>
                                <label for="harga_emas_pergram" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Emas per Gram (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="harga_emas_pergram" id="harga_emas_pergram" 
                                    value="{{ old('harga_emas_pergram', $harga->harga_emas_pergram) }}"
                                    placeholder="1,000,000"
                                    min="0"
                                    max="999999999999.99"
                                    step="0.01"
                                    required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('harga_emas_pergram') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Harga emas murni (24 karat) per gram</p>
                                @error('harga_emas_pergram')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Harga Perak per Gram --}}
                            <div>
                                <label for="harga_perak_pergram" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Perak per Gram (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="harga_perak_pergram" id="harga_perak_pergram" 
                                    value="{{ old('harga_perak_pergram', $harga->harga_perak_pergram) }}"
                                    placeholder="15,000"
                                    min="0"
                                    max="999999999999.99"
                                    step="0.01"
                                    required
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('harga_perak_pergram') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Harga perak murni per gram</p>
                                @error('harga_perak_pergram')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Current Status --}}
                        <div class="mt-6">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-700 mr-3">Status Saat Ini:</div>
                                @if($harga->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: LAIN-LAIN -->
                    <div class="pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            <svg class="w-5 h-5 inline mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lain-lain
                        </h3>
                        
                        {{-- Keterangan --}}
                        <div class="mb-6">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                placeholder="Tambahkan keterangan tambahan jika diperlukan..."
                                maxlength="500"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $harga->keterangan) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter. Karakter tersisa: <span id="keterangan-counter">{{ 500 - strlen($harga->keterangan) }}</span></p>
                            @error('keterangan')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Aktif --}}
                        <div>
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                    {{ old('is_active', $harga->is_active) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Aktif</label>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">
                                Jika diaktifkan, harga ini akan menjadi referensi untuk perhitungan nisab zakat.
                                Harga aktif sebelumnya untuk tanggal yang sama akan otomatis dinonaktifkan.
                            </p>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-600 font-medium">
                                Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.
                            </p>
                            <ul class="mt-2 text-xs text-red-600 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="ml-2">• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('harga-emas-perak.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
    document.addEventListener('DOMContentLoaded', function() {
        initForm();
    });

    function initForm() {
        if (window.innerWidth >= 768) {
            document.getElementById('tanggal')?.focus();
        }

        setupKeteranganCounter();
        setupHargaValidation();
    }

    function setupKeteranganCounter() {
        const keteranganTextarea = document.getElementById('keterangan');
        const counterSpan = document.getElementById('keterangan-counter');

        if (keteranganTextarea && counterSpan) {
            updateKeteranganCounter(keteranganTextarea.value.length);

            keteranganTextarea.addEventListener('input', function() {
                updateKeteranganCounter(this.value.length);
                
                // Auto-resize textarea
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Initial resize
            keteranganTextarea.style.height = 'auto';
            keteranganTextarea.style.height = (keteranganTextarea.scrollHeight) + 'px';
        }
    }

    function updateKeteranganCounter(length) {
        const counterSpan = document.getElementById('keterangan-counter');
        const remaining = 500 - length;
        
        if (counterSpan) {
            counterSpan.textContent = remaining;
            
            // Change color based on remaining characters
            if (remaining < 0) {
                counterSpan.classList.remove('text-gray-500');
                counterSpan.classList.add('text-red-500');
            } else if (remaining < 50) {
                counterSpan.classList.remove('text-gray-500', 'text-red-500');
                counterSpan.classList.add('text-yellow-500');
            } else {
                counterSpan.classList.remove('text-yellow-500', 'text-red-500');
                counterSpan.classList.add('text-gray-500');
            }
        }
    }

    function setupHargaValidation() {
        const hargaEmasInput = document.getElementById('harga_emas_pergram');
        const hargaPerakInput = document.getElementById('harga_perak_pergram');

        if (hargaEmasInput && hargaPerakInput) {
            // Format input saat blur
            hargaEmasInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });

            hargaPerakInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });

            // Validasi saat submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const emasValue = parseFloat(hargaEmasInput.value);
                const perakValue = parseFloat(hargaPerakInput.value);

                if (emasValue <= 0) {
                    alert('Harga emas harus lebih dari 0');
                    hargaEmasInput.focus();
                    e.preventDefault();
                    return false;
                }

                if (perakValue <= 0) {
                    alert('Harga perak harus lebih dari 0');
                    hargaPerakInput.focus();
                    e.preventDefault();
                    return false;
                }

                // Harga emas harus lebih mahal dari perak (validasi logis)
                if (emasValue <= perakValue) {
                    alert('Harga emas harus lebih tinggi dari harga perak');
                    hargaEmasInput.focus();
                    e.preventDefault();
                    return false;
                }
            });
        }
    }
</script>
@endpush