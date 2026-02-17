@extends('layouts.app')

@section('title', 'Edit Kategori Mustahik')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Kategori Mustahik</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Perbarui data kategori: <span class="font-medium text-gray-700">{{ $kategoriMustahik->nama }}</span>
                </p>
            </div>

            <form action="{{ route('kategori-mustahik.update', $kategoriMustahik->uuid) }}"
                  method="POST" class="p-4 sm:p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- ── SECTION: DATA DASAR ──────────────────────────────── --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Data Dasar
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Nama Kategori --}}
                            <div class="md:col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Kategori <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $kategoriMustahik->nama) }}"
                                    placeholder="Contoh: Fakir, Miskin, Amil, dll"
                                    maxlength="255"
                                    required
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl
                                           focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary
                                           @error('nama') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">
                                    Nama asnaf sesuai QS. At-Taubah: 60 (unik, maks. 255 karakter)
                                </p>
                                @error('nama')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Persentase Default --}}
                            <div>
                                <label for="persentase_default" class="block text-sm font-medium text-gray-700 mb-2">
                                    Persentase Default (%)
                                </label>
                                <input type="number" name="persentase_default" id="persentase_default"
                                    value="{{ old('persentase_default', $kategoriMustahik->persentase_default) }}"
                                    placeholder="0.00 – 100.00"
                                    min="0" max="100" step="0.01"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl
                                           focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary
                                           @error('persentase_default') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">
                                    Opsional. Total 8 asnaf idealnya = 100%
                                </p>
                                @error('persentase_default')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kriteria Penerima --}}
                            <div class="md:col-span-2">
                                <label for="kriteria" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kriteria Penerima
                                </label>
                                <textarea name="kriteria" id="kriteria" rows="5"
                                    placeholder="Sebutkan syarat/kriteria penerima untuk kategori ini..."
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl
                                           focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary
                                           @error('kriteria') border-red-500 @enderror">{{ old('kriteria', $kategoriMustahik->kriteria) }}</textarea>
                                @error('kriteria')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECTION: PANDUAN 8 ASNAF ────────────────────────── --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Panduan 8 Asnaf
                        </h3>
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-xs text-blue-700 font-medium mb-2">Berdasarkan QS. At-Taubah ayat 60:</p>
                            <ol class="text-xs text-blue-600 space-y-1.5">
                                <li><strong>1. Fakir (الفقراء)</strong> — Tidak memiliki harta &amp; tidak mampu bekerja</li>
                                <li><strong>2. Miskin (المساكين)</strong> — Berpenghasilan namun tidak mencukupi kebutuhan</li>
                                <li><strong>3. Amil (العاملين عليها)</strong> — Pengurus/pengelola zakat (maks. 12.5%)</li>
                                <li><strong>4. Muallaf (المؤلفة قلوبهم)</strong> — Baru masuk Islam atau yang dilunakkan hatinya</li>
                                <li><strong>5. Riqab (في الرقاب)</strong> — Hamba sahaya yang ingin memerdekakan diri</li>
                                <li><strong>6. Gharim (الغارمين)</strong> — Terlilit hutang untuk kebaikan</li>
                                <li><strong>7. Fisabilillah (في سبيل الله)</strong> — Pejuang di jalan Allah (dakwah, pendidikan)</li>
                                <li><strong>8. Ibnu Sabil (ابن السبيل)</strong> — Musafir yang kehabisan bekal</li>
                            </ol>
                        </div>
                    </div>

                    {{-- Error summary --}}
                    @if($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-600 font-medium mb-2">
                                Terdapat kesalahan dalam pengisian form:
                            </p>
                            <ul class="text-xs text-red-600 space-y-1 ml-2">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end
                            gap-3 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('kategori-mustahik.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2
                               border border-gray-300 text-sm font-medium rounded-xl
                               text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2
                               bg-primary hover:bg-primary-600 text-white text-sm font-medium
                               rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7"/>
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
        // Auto-focus pada desktop
        if (window.innerWidth >= 768) {
            document.getElementById('nama')?.focus();
        }

        // Auto-resize textarea saat load (isi data existing)
        autoResizeOnLoad('kriteria');
        document.getElementById('kriteria')?.addEventListener('input', function () {
            autoResize(this);
        });
    });

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    function autoResizeOnLoad(id) {
        const el = document.getElementById(id);
        if (el) autoResize(el);
    }
</script>
@endpush