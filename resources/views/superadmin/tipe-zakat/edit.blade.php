@extends('layouts.app')

@section('title', 'Edit Tipe Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Tipe Zakat</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Mengubah data tipe zakat: {{ $tipeZakat->nama }}</p>
                </div>
                <div class="mt-2 sm:mt-0">
                    <span class="text-xs text-gray-400">UUID: {{ $tipeZakat->uuid }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('tipe-zakat.update', $tipeZakat->uuid) }}" method="POST" class="p-4 sm:p-6" id="tipe-zakat-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- SECTION: INFORMASI DASAR -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Jenis Zakat --}}
                        <div>
                            <label for="jenis_zakat_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Zakat <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_zakat_id" id="jenis_zakat_id" required
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('jenis_zakat_id') border-red-500 @enderror">
                                <option value="">Pilih Jenis Zakat</option>
                                @foreach($jenisZakatList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_zakat_id', $tipeZakat->jenis_zakat_id) == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_zakat_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Tipe Zakat --}}
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Tipe Zakat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $tipeZakat->nama) }}"
                                placeholder="Contoh: Zakat Emas, Zakat Perdagangan"
                                maxlength="255"
                                required
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nama') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Nama spesifik untuk tipe zakat ini</p>
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION UNTUK ZAKAT MAL (NISAB, PERSENTASE, HAUL) -->
                <div id="zakat-mal-section" class="{{ old('jenis_zakat_id', $tipeZakat->jenis_zakat_id) == $zakatMalId ? '' : 'hidden' }} space-y-8">
                    
                    <!-- SUBSECTION: NISAB ZAKAT -->
                    <div id="nisab-section">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Nisab Zakat
                            <span class="text-xs font-normal text-gray-500 ml-2">(Minimal satu jenis nisab wajib diisi)</span>
                        </h3>
                        
                        <!-- Nisab Berbasis Emas & Perak -->
                        <div class="mb-6">
                            <h4 class="text-xs font-medium text-gray-700 uppercase tracking-wider mb-3">
                                <span class="bg-gray-100 px-2 py-1 rounded">Nisab Berbasis Emas & Perak</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nisab_emas_gram" class="block text-xs font-medium text-gray-600 mb-1">
                                        Nisab Emas (gram)
                                    </label>
                                    <input type="number" name="nisab_emas_gram" id="nisab_emas_gram" 
                                        value="{{ old('nisab_emas_gram', $tipeZakat->nisab_emas_gram) }}"
                                        placeholder="85"
                                        min="0"
                                        max="999999.99"
                                        step="0.01"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_emas_gram') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Standar: 85 gram emas murni</p>
                                    @error('nisab_emas_gram')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nisab_perak_gram" class="block text-xs font-medium text-gray-600 mb-1">
                                        Nisab Perak (gram)
                                    </label>
                                    <input type="number" name="nisab_perak_gram" id="nisab_perak_gram" 
                                        value="{{ old('nisab_perak_gram', $tipeZakat->nisab_perak_gram) }}"
                                        placeholder="595"
                                        min="0"
                                        max="999999.99"
                                        step="0.01"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_perak_gram') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Standar: 595 gram perak murni</p>
                                    @error('nisab_perak_gram')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nisab Pertanian -->
                        <div class="mb-6">
                            <h4 class="text-xs font-medium text-gray-700 uppercase tracking-wider mb-3">
                                <span class="bg-gray-100 px-2 py-1 rounded">Nisab Pertanian</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nisab_pertanian_kg" class="block text-xs font-medium text-gray-600 mb-1">
                                        Nisab Pertanian (kg)
                                    </label>
                                    <input type="number" name="nisab_pertanian_kg" id="nisab_pertanian_kg" 
                                        value="{{ old('nisab_pertanian_kg', $tipeZakat->nisab_pertanian_kg) }}"
                                        placeholder="653"
                                        min="0"
                                        max="999999.99"
                                        step="0.01"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_pertanian_kg') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Standar: 653 kg gabah atau 520 kg beras</p>
                                    @error('nisab_pertanian_kg')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nisab Peternakan -->
                        <div>
                            <h4 class="text-xs font-medium text-gray-700 uppercase tracking-wider mb-3">
                                <span class="bg-gray-100 px-2 py-1 rounded">Nisab Peternakan</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="nisab_kambing_min" class="block text-xs font-medium text-gray-600 mb-1">
                                        Kambing/Domba (min ekor)
                                    </label>
                                    <input type="number" name="nisab_kambing_min" id="nisab_kambing_min" 
                                        value="{{ old('nisab_kambing_min', $tipeZakat->nisab_kambing_min) }}"
                                        placeholder="40"
                                        min="0"
                                        max="9999"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_kambing_min') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Standar: 40 ekor</p>
                                    @error('nisab_kambing_min')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nisab_sapi_min" class="block text-xs font-medium text-gray-600 mb-1">
                                        Sapi/Kerbau (min ekor)
                                    </label>
                                    <input type="number" name="nisab_sapi_min" id="nisab_sapi_min" 
                                        value="{{ old('nisab_sapi_min', $tipeZakat->nisab_sapi_min) }}"
                                        placeholder="30"
                                        min="0"
                                        max="9999"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_sapi_min') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Standar: 30 ekor</p>
                                    @error('nisab_sapi_min')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nisab_unta_min" class="block text-xs font-medium text-gray-600 mb-1">
                                        Unta (min ekor)
                                    </label>
                                    <input type="number" name="nisab_unta_min" id="nisab_unta_min" 
                                        value="{{ old('nisab_unta_min', $tipeZakat->nisab_unta_min) }}"
                                        placeholder="5"
                                        min="0"
                                        max="9999"
                                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_unta_min') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Standar: 5 ekor</p>
                                    @error('nisab_unta_min')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBSECTION: PERSENTASE ZAKAT -->
                    <div id="persentase-section">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Persentase Zakat
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="persentase_zakat" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Persentase Zakat (%) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="persentase_zakat" id="persentase_zakat" 
                                        value="{{ old('persentase_zakat', $tipeZakat->persentase_zakat) }}"
                                        placeholder="2.5"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('persentase_zakat') border-red-500 @enderror">
                                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Standar: 2.5% untuk zakat mal</p>
                                @error('persentase_zakat')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="persentase_alternatif" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Persentase Alternatif (%)
                                </label>
                                <div class="relative">
                                    <input type="number" name="persentase_alternatif" id="persentase_alternatif" 
                                        value="{{ old('persentase_alternatif', $tipeZakat->persentase_alternatif) }}"
                                        placeholder="5 atau 10"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('persentase_alternatif') border-red-500 @enderror">
                                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                                </div>
                                @error('persentase_alternatif')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="keterangan_persentase" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Keterangan Persentase
                                </label>
                                <input type="text" name="keterangan_persentase" id="keterangan_persentase" 
                                    value="{{ old('keterangan_persentase', $tipeZakat->keterangan_persentase) }}"
                                    placeholder="Contoh: 10% jika pengairan hujan, 5% jika irigasi"
                                    maxlength="255"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('keterangan_persentase') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Penjelasan kapan persentase alternatif digunakan</p>
                                @error('keterangan_persentase')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SUBSECTION: KETENTUAN HAUL -->
                    <div id="haul-section">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Ketentuan Haul
                        </h3>
                        
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="requires_haul" id="requires_haul" value="1" 
                                {{ old('requires_haul', $tipeZakat->requires_haul) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="requires_haul" class="ml-2 text-sm text-gray-700">
                                Memerlukan haul (kepemilikan 1 tahun)
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">
                            Jika dicentang, zakat ini hanya diwajibkan setelah harta dimiliki selama 1 tahun (haul).
                        </p>
                    </div>

                </div> <!-- END ZAKAT MAL SECTION -->

                <!-- SECTION: KETENTUAN KHUSUS (TETAP MUNCUL UNTUK SEMUA) -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Ketentuan Khusus
                    </h3>
                    
                    <div>
                        <label for="ketentuan_khusus" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Ketentuan Khusus
                        </label>
                        <textarea name="ketentuan_khusus" id="ketentuan_khusus" rows="4"
                            placeholder="Tambahkan ketentuan khusus untuk tipe zakat ini..."
                            maxlength="1000"
                            class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('ketentuan_khusus') border-red-500 @enderror">{{ old('ketentuan_khusus', $tipeZakat->ketentuan_khusus) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Maksimal 1000 karakter. Karakter tersisa: <span id="ketentuan-counter">{{ 1000 - strlen($tipeZakat->ketentuan_khusus ?? '') }}</span></p>
                        @error('ketentuan_khusus')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
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

            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('tipe-zakat.index') }}"
                    class="inline-flex items-center justify-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
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
            document.getElementById('jenis_zakat_id')?.focus();
        }

        setupKetentuanCounter();
        setupJenisZakatToggle();
        validateZakatMal();
    }

    function setupKetentuanCounter() {
        const textarea = document.getElementById('ketentuan_khusus');
        const counterSpan = document.getElementById('ketentuan-counter');

        if (textarea && counterSpan) {
            updateCounter(textarea.value.length);

            textarea.addEventListener('input', function() {
                updateCounter(this.value.length);
                
                // Auto-resize textarea
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Initial resize
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }
    }

    function updateCounter(length) {
        const counterSpan = document.getElementById('ketentuan-counter');
        const remaining = 1000 - length;
        
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

    function setupJenisZakatToggle() {
        const jenisZakatSelect = document.getElementById('jenis_zakat_id');
        const zakatMalSection = document.getElementById('zakat-mal-section');
        const zakatMalId = {{ $zakatMalId ?? 'null' }};
        
        if (jenisZakatSelect && zakatMalSection) {
            jenisZakatSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                
                if (selectedValue == zakatMalId) {
                    zakatMalSection.classList.remove('hidden');
                } else {
                    zakatMalSection.classList.add('hidden');
                    
                    // Kosongkan nilai nisab jika bukan zakat mal
                    document.getElementById('nisab_emas_gram').value = '';
                    document.getElementById('nisab_perak_gram').value = '';
                    document.getElementById('nisab_pertanian_kg').value = '';
                    document.getElementById('nisab_kambing_min').value = '';
                    document.getElementById('nisab_sapi_min').value = '';
                    document.getElementById('nisab_unta_min').value = '';
                    
                    // Kosongkan nilai persentase
                    document.getElementById('persentase_zakat').value = '';
                    document.getElementById('persentase_alternatif').value = '';
                    document.getElementById('keterangan_persentase').value = '';
                    
                    // Set haul ke default (true) tapi disembunyikan
                    document.getElementById('requires_haul').checked = true;
                }
            });
        }
    }

    function validateZakatMal() {
        const form = document.getElementById('tipe-zakat-form');
        const jenisZakatSelect = document.getElementById('jenis_zakat_id');
        const zakatMalId = {{ $zakatMalId ?? 'null' }};
        
        form.addEventListener('submit', function(e) {
            // Validasi hanya jika memilih Zakat Mal
            if (jenisZakatSelect.value == zakatMalId) {
                // Validasi Nisab
                const nisabEmas = document.getElementById('nisab_emas_gram').value;
                const nisabPerak = document.getElementById('nisab_perak_gram').value;
                const nisabPertanian = document.getElementById('nisab_pertanian_kg').value;
                const nisabKambing = document.getElementById('nisab_kambing_min').value;
                const nisabSapi = document.getElementById('nisab_sapi_min').value;
                const nisabUnta = document.getElementById('nisab_unta_min').value;
                
                const hasNisab = nisabEmas || nisabPerak || nisabPertanian || nisabKambing || nisabSapi || nisabUnta;
                
                if (!hasNisab) {
                    alert('Minimal satu jenis nisab harus diisi untuk Zakat Mal!');
                    e.preventDefault();
                    return false;
                }
                
                // Validasi Persentase Zakat
                const persentaseZakat = document.getElementById('persentase_zakat').value;
                
                if (!persentaseZakat) {
                    alert('Persentase zakat wajib diisi untuk Zakat Mal!');
                    document.getElementById('persentase_zakat').focus();
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
    }
</script>
@endpush