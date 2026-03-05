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

                {{-- ============================================================ --}}
                {{-- SECTION: INFORMASI DASAR                                     --}}
                {{-- ============================================================ --}}
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
                                    <option value="{{ $jenis->id }}"
                                        {{ old('jenis_zakat_id', $tipeZakat->jenis_zakat_id) == $jenis->id ? 'selected' : '' }}>
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
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $tipeZakat->nama) }}"
                                placeholder="Contoh: Zakat Emas, Zakat Perdagangan"
                                maxlength="255" required
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nama') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Nama spesifik untuk tipe zakat ini</p>
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- SECTION ZAKAT MAL — muncul hanya jika jenis = Zakat Mal     --}}
                {{-- ============================================================ --}}
                <div id="zakat-mal-section"
                    class="{{ old('jenis_zakat_id', $tipeZakat->jenis_zakat_id) == $zakatMalId ? '' : 'hidden' }} space-y-8">

                    {{-- SUB-TIPE ZAKAT MAL --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Sub-Tipe Zakat Mal
                        </h3>
                        <div>
                            <label for="zakat_mal_type" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Pilih Sub-Tipe <span class="text-red-500">*</span>
                            </label>
                            <select name="zakat_mal_type" id="zakat_mal_type"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('zakat_mal_type') border-red-500 @enderror">
                                <option value="">-- Pilih Sub-Tipe --</option>
                                @foreach($zakatMalTypes as $key => $config)
                                    <option value="{{ $key }}"
                                        data-requires-haul="{{ $config['requires_haul'] ? '1' : '0' }}"
                                        {{ old('zakat_mal_type', $tipeZakat->zakat_mal_type) === $key ? 'selected' : '' }}>
                                        {{ $config['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Sub-tipe menentukan field nisab dan ketentuan haul yang relevan.
                            </p>
                            @error('zakat_mal_type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- PANDUAN PER SUB-TIPE --}}
                    <div id="subtipe-info" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700 space-y-1">
                        {{-- Diisi oleh JS --}}
                    </div>

                    {{-- NISAB EMAS & PERAK --}}
                    <div id="nisab-emas-perak-section" class="hidden">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Nisab Berbasis Emas &amp; Perak
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nisab_emas_gram" class="block text-xs font-medium text-gray-600 mb-1">
                                    Nisab Emas (gram)
                                </label>
                                <input type="number" name="nisab_emas_gram" id="nisab_emas_gram"
                                    value="{{ old('nisab_emas_gram', $tipeZakat->nisab_emas_gram) }}"
                                    placeholder="85" min="0" max="999999.99" step="0.01"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_emas_gram') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Standar: 85 gram emas murni</p>
                                @error('nisab_emas_gram')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="nisab-perak-wrap">
                                <label for="nisab_perak_gram" class="block text-xs font-medium text-gray-600 mb-1">
                                    Nisab Perak (gram)
                                </label>
                                <input type="number" name="nisab_perak_gram" id="nisab_perak_gram"
                                    value="{{ old('nisab_perak_gram', $tipeZakat->nisab_perak_gram) }}"
                                    placeholder="595" min="0" max="999999.99" step="0.01"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_perak_gram') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Standar: 595 gram perak murni</p>
                                @error('nisab_perak_gram')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- NISAB PERTANIAN --}}
                    <div id="nisab-pertanian-section" class="hidden">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Nisab Pertanian
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nisab_pertanian_kg" class="block text-xs font-medium text-gray-600 mb-1">
                                    Nisab Pertanian (kg)
                                </label>
                                <input type="number" name="nisab_pertanian_kg" id="nisab_pertanian_kg"
                                    value="{{ old('nisab_pertanian_kg', $tipeZakat->nisab_pertanian_kg) }}"
                                    placeholder="653" min="0" max="999999.99" step="0.01"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_pertanian_kg') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Standar: 653 kg gabah / 520 kg beras (≈ 5 wasaq)</p>
                                @error('nisab_pertanian_kg')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- NISAB PETERNAKAN --}}
                    <div id="nisab-peternakan-section" class="hidden">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Nisab Peternakan
                            <span class="text-xs font-normal text-gray-500 ml-2">(Minimal satu wajib diisi)</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="nisab_kambing_min" class="block text-xs font-medium text-gray-600 mb-1">
                                    Kambing / Domba (min ekor)
                                </label>
                                <input type="number" name="nisab_kambing_min" id="nisab_kambing_min"
                                    value="{{ old('nisab_kambing_min', $tipeZakat->nisab_kambing_min) }}"
                                    placeholder="40" min="0" max="9999"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_kambing_min') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Standar: 40 ekor</p>
                                @error('nisab_kambing_min')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nisab_sapi_min" class="block text-xs font-medium text-gray-600 mb-1">
                                    Sapi / Kerbau (min ekor)
                                </label>
                                <input type="number" name="nisab_sapi_min" id="nisab_sapi_min"
                                    value="{{ old('nisab_sapi_min', $tipeZakat->nisab_sapi_min) }}"
                                    placeholder="30" min="0" max="9999"
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
                                    placeholder="5" min="0" max="9999"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('nisab_unta_min') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Standar: 5 ekor</p>
                                @error('nisab_unta_min')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- PERSENTASE ZAKAT --}}
                    <div id="persentase-section" class="hidden">
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
                                        placeholder="2.5" min="0" max="100" step="0.01"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('persentase_zakat') border-red-500 @enderror">
                                    <span class="absolute right-3 top-2 text-gray-500 text-sm">%</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500" id="persentase-hint">Standar: 2.5% untuk zakat mal</p>
                                @error('persentase_zakat')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="persentase-alternatif-wrap">
                                <label for="persentase_alternatif" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Persentase Alternatif (%)
                                </label>
                                <div class="relative">
                                    <input type="number" name="persentase_alternatif" id="persentase_alternatif"
                                        value="{{ old('persentase_alternatif', $tipeZakat->persentase_alternatif) }}"
                                        placeholder="5 atau 10" min="0" max="100" step="0.01"
                                        class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('persentase_alternatif') border-red-500 @enderror">
                                    <span class="absolute right-3 top-2 text-gray-500 text-sm">%</span>
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

                    {{-- KETENTUAN HAUL --}}
                    <div id="haul-section" class="hidden">
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
                        <p class="text-xs text-gray-500" id="haul-desc">
                            Jika dicentang, zakat ini hanya diwajibkan setelah harta dimiliki selama 1 tahun hijriyah.
                        </p>
                    </div>

                </div>{{-- END ZAKAT MAL SECTION --}}

                {{-- ============================================================ --}}
                {{-- KETENTUAN KHUSUS — tampil untuk semua jenis zakat            --}}
                {{-- ============================================================ --}}
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
                        <p class="mt-1 text-xs text-gray-500">
                            Maksimal 1000 karakter. Karakter tersisa:
                            <span id="ketentuan-counter">{{ 1000 - strlen($tipeZakat->ketentuan_khusus ?? '') }}</span>
                        </p>
                        @error('ketentuan_khusus')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.</p>
                        <ul class="mt-2 text-xs text-red-600 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="ml-2">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>{{-- END space-y-8 --}}

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
document.addEventListener('DOMContentLoaded', function () {
    const zakatMalId = {{ $zakatMalId ?? 'null' }};

    const subtypeConfig = {
        emas_perak: {
            nisab: ['emas-perak'],
            perak: true,
            haul: true,
            persentaseHint: 'Standar: 2.5%',
            altPersentase: false,
            info: 'Nisab emas 85 gram atau perak 595 gram. Termasuk perhiasan yang disimpan & logam mulia.'
        },
        perniagaan: {
            nisab: ['emas-perak'],
            perak: false,
            haul: true,
            persentaseHint: 'Standar: 2.5%',
            altPersentase: false,
            info: 'Nisab setara 85 gram emas. Berlaku untuk aset usaha/dagangan yang diperjualbelikan dengan niat mencari keuntungan.'
        },
        pertanian: {
            nisab: ['pertanian'],
            perak: false,
            haul: false,
            persentaseHint: 'Standar: 10% (air hujan) atau 5% (irigasi buatan)',
            altPersentase: true,
            info: 'Tidak memerlukan haul — dikeluarkan saat panen. Nisab: 653 kg gabah / 520 kg beras.'
        },
        peternakan: {
            nisab: ['peternakan'],
            perak: false,
            haul: true,
            persentaseHint: 'Disesuaikan dengan jenis dan jumlah hewan',
            altPersentase: false,
            info: 'Nisab kambing 40 ekor, sapi/kerbau 30 ekor, unta 5 ekor. Hewan tidak boleh cacat atau hamil.'
        },
        penghasilan: {
            nisab: ['emas-perak'],
            perak: false,
            haul: true,
            persentaseHint: 'Standar: 2.5%',
            altPersentase: false,
            info: 'Dari gaji, honorarium, royalti, atau dividen. Nisab setara 85 gram emas per tahun.'
        },
        uang_surat: {
            nisab: ['emas-perak'],
            perak: false,
            haul: true,
            persentaseHint: 'Standar: 2.5%',
            altPersentase: false,
            info: 'Uang tunai dan surat berharga (saham, obligasi). Nisab setara nilai 85 gram emas.'
        },
        investasi: {
            nisab: ['emas-perak'],
            perak: false,
            haul: true,
            persentaseHint: 'Standar: 2.5% (hasil bersih) atau 5% (hasil kotor)',
            altPersentase: true,
            info: 'Nisab setara 85 gram emas. Beberapa ulama: 5% dari hasil kotor, 10% dari hasil bersih.'
        },
        pertambangan: {
            nisab: ['emas-perak'],
            perak: false,
            haul: false,
            persentaseHint: 'Standar: 2.5% (dianalogikan zakat penghasilan)',
            altPersentase: false,
            info: 'Tidak memerlukan haul. Dikeluarkan saat hasil tambang diperoleh dan sudah mencapai nisab.'
        },
        rikaz: {
            nisab: [],
            perak: false,
            haul: false,
            persentaseHint: 'Standar: 20% (seperlima dari nilai temuan)',
            altPersentase: false,
            info: 'Harta temuan tidak memerlukan nisab maupun haul. Langsung dikeluarkan 20% saat ditemukan.'
        },
    };

    initForm();

    function initForm() {
        if (window.innerWidth >= 768) document.getElementById('jenis_zakat_id')?.focus();
        setupKetentuanCounter();
        setupJenisZakatToggle();
        setupSubtypeToggle();
        setupFormValidation();

        // Restore state dari data existing
        const currentSubtype = '{{ old('zakat_mal_type', $tipeZakat->zakat_mal_type) }}';
        const currentJenis   = '{{ old('jenis_zakat_id', $tipeZakat->jenis_zakat_id) }}';

        if (currentJenis == zakatMalId && currentSubtype) {
            applySubtypeConfig(currentSubtype);
        }
    }

    function setupJenisZakatToggle() {
        const jenisSelect     = document.getElementById('jenis_zakat_id');
        const zakatMalSection = document.getElementById('zakat-mal-section');

        jenisSelect?.addEventListener('change', function () {
            if (this.value == zakatMalId) {
                zakatMalSection.classList.remove('hidden');
            } else {
                zakatMalSection.classList.add('hidden');
                resetAllNisabFields();
                hideAllNisabSections();
            }
        });
    }

    function setupSubtypeToggle() {
        document.getElementById('zakat_mal_type')?.addEventListener('change', function () {
            applySubtypeConfig(this.value);
        });
    }

    function applySubtypeConfig(subtype) {
        const cfg = subtypeConfig[subtype];

        hideAllNisabSections();
        resetAllNisabFields();

        if (!cfg) return;

        const infoBox = document.getElementById('subtipe-info');
        if (cfg.info) {
            infoBox.innerHTML = cfg.info;
            infoBox.classList.remove('hidden');
        } else {
            infoBox.classList.add('hidden');
        }

        cfg.nisab.forEach(function (n) {
            const el = document.getElementById('nisab-' + n + '-section');
            if (el) el.classList.remove('hidden');
        });

        const perakWrap = document.getElementById('nisab-perak-wrap');
        if (perakWrap) {
            perakWrap.style.display = cfg.perak ? '' : 'none';
            if (!cfg.perak) document.getElementById('nisab_perak_gram').value = '';
        }

        document.getElementById('persentase-section').classList.remove('hidden');
        document.getElementById('haul-section').classList.remove('hidden');

        document.getElementById('persentase-hint').textContent = cfg.persentaseHint;

        const altWrap = document.getElementById('persentase-alternatif-wrap');
        if (altWrap) altWrap.style.display = cfg.altPersentase ? '' : 'none';

        const haulCheck = document.getElementById('requires_haul');
        // Pada edit, hormati nilai yang tersimpan di DB — jangan override
        // kecuali sub-tipe baru dipilih (berbeda dari data existing)
        const savedSubtype = '{{ $tipeZakat->zakat_mal_type }}';
        if (subtype !== savedSubtype) {
            if (haulCheck) haulCheck.checked = cfg.haul;
        }

        const haulDesc = document.getElementById('haul-desc');
        if (haulDesc) {
            haulDesc.textContent = cfg.haul
                ? 'Tipe ini umumnya memerlukan haul (kepemilikan 1 tahun). Dapat diubah sesuai kondisi.'
                : 'Tipe ini umumnya TIDAK memerlukan haul — kewajiban muncul saat harta diperoleh/dipanen.';
        }
    }

    function hideAllNisabSections() {
        ['nisab-emas-perak-section', 'nisab-pertanian-section', 'nisab-peternakan-section',
         'persentase-section', 'haul-section', 'subtipe-info']
        .forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });
    }

    function resetAllNisabFields() {
        ['nisab_emas_gram','nisab_perak_gram','nisab_pertanian_kg',
         'nisab_kambing_min','nisab_sapi_min','nisab_unta_min',
         'persentase_zakat','persentase_alternatif','keterangan_persentase']
        .forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
    }

    function setupKetentuanCounter() {
        const textarea    = document.getElementById('ketentuan_khusus');
        const counterSpan = document.getElementById('ketentuan-counter');
        if (!textarea || !counterSpan) return;

        updateCounter(textarea.value.length);
        textarea.addEventListener('input', function () {
            updateCounter(this.value.length);
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    function updateCounter(length) {
        const counterSpan = document.getElementById('ketentuan-counter');
        const remaining   = 1000 - length;
        counterSpan.textContent = remaining;
        counterSpan.className   = remaining < 0 ? 'text-red-500' : remaining < 50 ? 'text-yellow-500' : 'text-gray-500';
    }

    function setupFormValidation() {
        document.getElementById('tipe-zakat-form')?.addEventListener('submit', function (e) {
            const jenisId = document.getElementById('jenis_zakat_id').value;
            const subtype = document.getElementById('zakat_mal_type').value;

            if (jenisId != zakatMalId) return true;

            if (!subtype) {
                alert('Sub-tipe Zakat Mal wajib dipilih!');
                document.getElementById('zakat_mal_type').focus();
                e.preventDefault();
                return false;
            }

            if (subtype === 'rikaz') {
                if (!document.getElementById('persentase_zakat').value) {
                    alert('Persentase zakat wajib diisi. Untuk Rikaz standarnya 20%.');
                    document.getElementById('persentase_zakat').focus();
                    e.preventDefault();
                    return false;
                }
                return true;
            }

            if (subtype === 'peternakan') {
                const hasNisab = document.getElementById('nisab_kambing_min').value
                              || document.getElementById('nisab_sapi_min').value
                              || document.getElementById('nisab_unta_min').value;
                if (!hasNisab) {
                    alert('Minimal satu nisab peternakan (kambing/sapi/unta) harus diisi!');
                    e.preventDefault();
                    return false;
                }
            } else if (subtype === 'pertanian') {
                if (!document.getElementById('nisab_pertanian_kg').value) {
                    alert('Nisab pertanian (kg) wajib diisi!');
                    document.getElementById('nisab_pertanian_kg').focus();
                    e.preventDefault();
                    return false;
                }
            } else {
                if (!document.getElementById('nisab_emas_gram').value) {
                    alert('Nisab emas (gram) wajib diisi! Standar: 85 gram.');
                    document.getElementById('nisab_emas_gram').focus();
                    e.preventDefault();
                    return false;
                }
            }

            if (!document.getElementById('persentase_zakat').value) {
                alert('Persentase zakat wajib diisi!');
                document.getElementById('persentase_zakat').focus();
                e.preventDefault();
                return false;
            }

            return true;
        });
    }
});
</script>
@endpush