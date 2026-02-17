@extends('layouts.app')
@section('title', 'Edit Rekening Masjid')
@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white from-primary/5 to-primary/10">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Edit Rekening Masjid</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui field yang diperlukan untuk mengedit data rekening masjid</p>
            </div>
            
            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mx-4 sm:mx-6 mt-4 animate-slide-down">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam pengisian form:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('rekening-masjid.update', $rekeningMasjid->uuid) }}" method="POST" class="p-4 sm:p-6">
                @csrf
                @method('PUT')
                
                {{-- Info Masjid --}}
                @if($masjid)
                <div class="mb-6 sm:mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-blue-900 mb-1">Masjid Terdaftar</p>
                                <p class="text-sm font-semibold text-blue-700">{{ $masjid->nama }}</p>
                                @if($masjid->alamat)
                                <p class="text-xs text-blue-600 mt-1">{{ $masjid->alamat }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Section 1 - Data Rekening --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Data Rekening
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Nama Bank --}}
                        <div>
                            <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Bank <span class="text-red-500">*</span>
                            </label>
                            <select name="nama_bank" id="nama_bank"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nama_bank') border-red-500 @enderror" required>
                                <option value="">-- Pilih Bank --</option>
                                <option value="BCA" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'BCA' ? 'selected' : '' }}>BCA (Bank Central Asia)</option>
                                <option value="Mandiri" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                <option value="BNI" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'BNI' ? 'selected' : '' }}>BNI (Bank Negara Indonesia)</option>
                                <option value="BRI" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'BRI' ? 'selected' : '' }}>BRI (Bank Rakyat Indonesia)</option>
                                <option value="BSI" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia)</option>
                                <option value="Muamalat" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                <option value="Bank Jateng" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Bank Jateng' ? 'selected' : '' }}>Bank Jateng</option>
                                <option value="Bank Jatim" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Bank Jatim' ? 'selected' : '' }}>Bank Jatim</option>
                                <option value="Bank DKI" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Bank DKI' ? 'selected' : '' }}>Bank DKI</option>
                                <option value="CIMB Niaga" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                <option value="Maybank" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                <option value="OCBC NISP" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'OCBC NISP' ? 'selected' : '' }}>OCBC NISP</option>
                                <option value="Panin Bank" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Panin Bank' ? 'selected' : '' }}>Panin Bank</option>
                                <option value="Permata Bank" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Permata Bank' ? 'selected' : '' }}>Permata Bank</option>
                                <option value="Danamon" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                <option value="Lainnya" {{ old('nama_bank', $rekeningMasjid->nama_bank) == 'Lainnya' ? 'selected' : '' }}>Bank Lainnya</option>
                            </select>
                            @error('nama_bank')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Rekening --}}
                        <div>
                            <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Rekening <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $rekeningMasjid->nomor_rekening) }}"
                                placeholder="Masukkan nomor rekening"
                                maxlength="50"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nomor_rekening') border-red-500 @enderror" required>
                            @error('nomor_rekening')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Pemilik --}}
                        <div>
                            <label for="nama_pemilik" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pemilik Rekening <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik', $rekeningMasjid->nama_pemilik) }}"
                                placeholder="Nama pemilik rekening"
                                maxlength="150"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nama_pemilik') border-red-500 @enderror" required>
                            @error('nama_pemilik')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2 - Status & Keterangan --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Status & Keterangan
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Status Rekening Utama --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <label for="is_primary" class="text-sm font-medium text-gray-700">
                                    Jadikan sebagai rekening utama?
                                </label>
                                <div class="relative inline-block w-11 h-6">
                                    <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary', $rekeningMasjid->is_primary) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Rekening utama akan ditampilkan sebagai pilihan default untuk transaksi.
                            </p>
                            @error('is_primary')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Aktif --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <label for="is_active" class="text-sm font-medium text-gray-700">
                                    Status Aktif
                                </label>
                                <div class="relative inline-block w-11 h-6">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $rekeningMasjid->is_active) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Rekening nonaktif tidak akan tersedia untuk transaksi.
                            </p>
                            @error('is_active')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan (Opsional)
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                placeholder="Tambahkan keterangan tentang rekening ini"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $rekeningMasjid->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('rekening-masjid.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40">
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
            if (window.innerWidth >= 768) {
                document.getElementById('nama_bank')?.focus();
            }
        });
    </script>
@endpush