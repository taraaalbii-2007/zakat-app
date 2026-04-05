@extends('layouts.app')

@section('title', 'Tambah Kategori Mustahik')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tambah Kategori Mustahik</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Tambahkan kategori penerima zakat (8 asnaf)</p>
            </div>

            <form action="{{ route('kategori-mustahik.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf

                <div class="space-y-5 sm:space-y-6">

                    {{-- ── SECTION: DATA DASAR ──────────────────────────────── --}}
                    <div class="border-b border-gray-200 pb-5 sm:pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Data Dasar
                        </h3>

                        <div class="space-y-4">

                            {{-- Nama Kategori --}}
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Kategori <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama') }}"
                                    placeholder="Contoh: Fakir, Miskin, Amil, dll"
                                    maxlength="255"
                                    required
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl
                                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                           @error('nama') border-red-500 bg-red-50 @enderror">
                                <p class="mt-1 text-xs text-gray-500">
                                    Nama asnaf sesuai QS. At-Taubah: 60 (unik, maks. 255 karakter)
                                </p>
                                @error('nama')
                                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Persentase Default --}}
                            <div>
                                <label for="persentase_default" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Persentase Default (%)
                                </label>
                                <div class="relative">
                                    <input type="number" name="persentase_default" id="persentase_default"
                                        value="{{ old('persentase_default') }}"
                                        placeholder="0.00"
                                        min="0" max="100" step="0.01"
                                        class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-300 rounded-xl
                                               focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                               @error('persentase_default') border-red-500 bg-red-50 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm font-medium">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Opsional. Total 8 asnaf idealnya = 100%
                                </p>
                                @error('persentase_default')
                                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kriteria Penerima --}}
                            <div>
                                <label for="kriteria" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kriteria Penerima
                                </label>
                                <textarea name="kriteria" id="kriteria" rows="4"
                                    placeholder="Sebutkan syarat/kriteria penerima untuk kategori ini..."
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl resize-none
                                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                           @error('kriteria') border-red-500 bg-red-50 @enderror">{{ old('kriteria') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Opsional. Deskripsikan syarat penerima zakat untuk kategori ini.</p>
                                @error('kriteria')
                                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SECTION: PANDUAN 8 ASNAF ────────────────────────── --}}
                    <div class="border-b border-gray-200 pb-5 sm:pb-6">
                        {{-- Collapsible header on mobile --}}
                        <button type="button" id="asnaf-toggle"
                            class="w-full text-left text-sm font-semibold text-gray-900 mb-0 pb-2 border-b border-gray-100 flex items-center justify-between gap-2 sm:pointer-events-none"
                            onclick="toggleAsnaf()">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Panduan 8 Asnaf
                            </span>
                            {{-- Chevron — only visible on mobile --}}
                            <svg id="asnaf-chevron"
                                class="w-4 h-4 text-gray-400 transform transition-transform duration-200 sm:hidden"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="asnaf-content" class="hidden sm:block mt-3">
                            <div class="p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <p class="text-xs text-blue-700 font-medium mb-2">Berdasarkan QS. At-Taubah ayat 60:</p>
                                <ol class="text-xs text-blue-600 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">1.</span>
                                        <span><strong>Fakir (الفقراء)</strong> — Tidak memiliki harta &amp; tidak mampu bekerja</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">2.</span>
                                        <span><strong>Miskin (المساكين)</strong> — Berpenghasilan namun tidak mencukupi kebutuhan</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">3.</span>
                                        <span><strong>Amil (العاملين عليها)</strong> — Pengurus/pengelola zakat (maks. 12.5%)</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">4.</span>
                                        <span><strong>Muallaf (المؤلفة قلوبهم)</strong> — Baru masuk Islam atau yang dilunakkan hatinya</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">5.</span>
                                        <span><strong>Riqab (في الرقاب)</strong> — Hamba sahaya yang ingin memerdekakan diri</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">6.</span>
                                        <span><strong>Gharim (الغارمين)</strong> — Terlilit hutang untuk kebaikan</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">7.</span>
                                        <span><strong>Fisabilillah (في سبيل الله)</strong> — Pejuang di jalan Allah (dakwah, pendidikan)</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-semibold text-blue-700 flex-shrink-0">8.</span>
                                        <span><strong>Ibnu Sabil (ابن السبيل)</strong> — Musafir yang kehabisan bekal</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- Error summary --}}
                    @if ($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-600 font-medium mb-1.5">Terdapat kesalahan dalam pengisian form:</p>
                            <ul class="text-xs text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end
                            gap-2 sm:gap-3 mt-5 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('kategori-mustahik.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5
                               border border-gray-300 text-sm font-medium rounded-xl
                               text-gray-700 bg-white hover:bg-gray-50 active:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2.5
                               bg-primary hover:bg-primary-600 active:bg-primary-700 text-white text-sm font-medium
                               rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Kategori
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

        // Auto-resize textarea
        const textarea = document.getElementById('kriteria');
        if (textarea) {
            autoResize(textarea);
            textarea.addEventListener('input', function () { autoResize(this); });
        }

        // On desktop always show asnaf panel
        if (window.innerWidth >= 640) {
            document.getElementById('asnaf-content')?.classList.remove('hidden');
        }
    });

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 240) + 'px';
    }

    function toggleAsnaf() {
        // Only toggle on mobile
        if (window.innerWidth >= 640) return;
        const content = document.getElementById('asnaf-content');
        const chevron = document.getElementById('asnaf-chevron');
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
</script>
@endpush