{{-- resources/views/amil/kunjungan/finish.blade.php --}}
@extends('layouts.app')
@section('title', 'Isi Hasil Kunjungan')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Hasil Kunjungan</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Dokumentasikan hasil kunjungan ke mustahik</p>
        </div>

        {{-- Info Kunjungan (summary) --}}
        <div class="px-4 sm:px-6 py-3 bg-blue-50 border-b border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-9 h-9 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">{{ $kunjungan->mustahik->nama_lengkap }}</p>
                        <p class="text-xs text-blue-600">
                            {{ $kunjungan->tujuan_label }} ·
                            {{ $kunjungan->tanggal_kunjungan->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
                {!! $kunjungan->status_badge !!}
            </div>
        </div>

        <form action="{{ route('amil.kunjungan.complete', $kunjungan->uuid) }}" method="POST"
            enctype="multipart/form-data" class="p-4 sm:p-6 space-y-6">
            @csrf

            {{-- SECTION 1: Waktu Selesai --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">1</span>
                    Waktu Selesai
                </h3>
                <div class="flex items-end gap-3">
                    <div class="flex-1 max-w-xs">
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Waktu Selesai
                        </label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai"
                            value="{{ old('waktu_selesai', $kunjungan->waktu_selesai ? substr($kunjungan->waktu_selesai, 0, 5) : date('H:i')) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="button" onclick="setWaktuNow()"
                        class="px-3 py-2 text-xs font-medium border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 rounded-xl transition-colors">
                        Sekarang
                    </button>
                </div>
            </div>

            {{-- SECTION 2: Hasil Kunjungan --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">2</span>
                    Hasil Kunjungan
                </h3>
                <div>
                    <label for="hasil_kunjungan" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Deskripsi Hasil <span class="text-red-500">*</span>
                    </label>
                    <textarea name="hasil_kunjungan" id="hasil_kunjungan" rows="6"
                        placeholder="Tuliskan temuan survey, kondisi mustahik saat ini, dan hal-hal penting yang perlu dicatat..."
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-y @error('hasil_kunjungan') border-red-500 @enderror">{{ old('hasil_kunjungan', $kunjungan->hasil_kunjungan) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Minimal 10 karakter</p>
                    @error('hasil_kunjungan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- SECTION 3: Foto Dokumentasi --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">3</span>
                    Foto Dokumentasi
                </h3>

                {{-- Foto existing --}}
                @if($kunjungan->foto_dokumentasi_urls)
                <div class="mb-4">
                    <p class="text-xs font-medium text-gray-600 mb-2">Foto Tersimpan:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kunjungan->foto_dokumentasi_urls as $i => $url)
                        <div class="relative group w-20 h-20">
                            <img src="{{ $url }}" class="w-full h-full object-cover rounded-lg border border-gray-200">
                            <form method="POST" action="{{ route('amil.kunjungan.hapus-foto', $kunjungan->uuid) }}"
                                class="absolute -top-1.5 -right-1.5 hidden group-hover:block"
                                onsubmit="return confirm('Hapus foto ini?')">
                                @csrf @method('DELETE')
                                <input type="hidden" name="index" value="{{ $i }}">
                                <button class="w-5 h-5 bg-red-600 text-white rounded-full text-xs flex items-center justify-center">×</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Upload baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Baru</label>

                    {{-- Drop Zone --}}
                    <div id="drop-zone"
                        class="relative border-2 border-dashed border-gray-300 hover:border-primary/50 rounded-xl p-6 text-center transition-colors cursor-pointer bg-gray-50">
                        <input type="file" name="foto_dokumentasi[]" id="foto-input"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                            multiple
                            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                            onchange="previewFotos(this)">
                        <svg class="mx-auto w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Klik atau seret foto ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP · Maks 5MB per foto · Maks 8 foto</p>
                    </div>

                    {{-- Preview grid --}}
                    <div id="foto-preview-grid" class="mt-3 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 hidden"></div>

                    @error('foto_dokumentasi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('foto_dokumentasi.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- SECTION 4: Status Mustahik --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">4</span>
                    Update Status Mustahik
                </h3>
                <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 hover:border-red-300 cursor-pointer transition-colors has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                    <input type="checkbox" name="nonaktifkan_mustahik" value="1"
                        class="mt-0.5 w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                        {{ old('nonaktifkan_mustahik') ? 'checked' : '' }}
                        id="chk-nonaktif">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Nonaktifkan mustahik ini</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Centang jika berdasarkan hasil kunjungan mustahik dinilai tidak lagi memenuhi syarat
                            penerima zakat. Status akan diubah menjadi nonaktif dan tidak akan muncul dalam daftar
                            penyaluran selanjutnya.
                        </p>
                    </div>
                </label>

                {{-- Warning --}}
                <div id="nonaktif-warning" class="mt-3 hidden bg-red-50 border border-red-200 rounded-xl p-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-xs text-red-700">
                            <strong>Perhatian:</strong> Tindakan ini akan langsung mengubah status
                            <strong>{{ $kunjungan->mustahik->nama_lengkap }}</strong> menjadi nonaktif.
                            Admin masjid dapat mengaktifkan kembali jika diperlukan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('amil.kunjungan.show', $kunjungan->uuid) }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Hasil Kunjungan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Waktu Sekarang ────────────────────────────────────────────────────────
function setWaktuNow() {
    const now = new Date();
    const hh  = String(now.getHours()).padStart(2, '0');
    const mm  = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('waktu_selesai').value = `${hh}:${mm}`;
}

// ── Preview Foto ──────────────────────────────────────────────────────────
let selectedFiles = [];

function previewFotos(input) {
    const grid  = document.getElementById('foto-preview-grid');
    const files = Array.from(input.files);
    const maxFiles = 8;

    files.forEach(file => {
        if (selectedFiles.length >= maxFiles) {
            alert(`Maksimal ${maxFiles} foto.`); return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert(`File "${file.name}" terlalu besar (maks 5MB).`); return;
        }
        selectedFiles.push(file);

        const reader = new FileReader();
        const idx    = selectedFiles.length - 1;
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.id        = `preview-${idx}`;
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full aspect-square object-cover rounded-lg border border-gray-200">
                <button type="button" onclick="removeFoto(${idx})"
                    class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-600 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">×</button>
                <p class="text-xs text-gray-400 truncate mt-0.5">${file.name}</p>`;
            grid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    updateFileInput();
    grid.classList.toggle('hidden', selectedFiles.length === 0);
}

function removeFoto(idx) {
    selectedFiles.splice(idx, 1);
    const el = document.getElementById(`preview-${idx}`);
    if (el) el.remove();

    // Re-index remaining previews
    const grid = document.getElementById('foto-preview-grid');
    Array.from(grid.children).forEach((child, i) => { child.id = `preview-${i}`; });

    updateFileInput();
    document.getElementById('foto-preview-grid').classList.toggle('hidden', selectedFiles.length === 0);
}

function updateFileInput() {
    const input   = document.getElementById('foto-input');
    const dt      = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    input.files   = dt.files;
}

// ── Drag & Drop ───────────────────────────────────────────────────────────
const dropZone = document.getElementById('drop-zone');
['dragenter','dragover'].forEach(evt => dropZone.addEventListener(evt, e => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-blue-50');
}));
['dragleave','drop'].forEach(evt => dropZone.addEventListener(evt, e => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-blue-50');
}));
dropZone.addEventListener('drop', e => {
    const fakeInput = { files: e.dataTransfer.files };
    previewFotos(fakeInput);
});

// ── Checkbox Nonaktif Warning ─────────────────────────────────────────────
document.getElementById('chk-nonaktif').addEventListener('change', function () {
    document.getElementById('nonaktif-warning').classList.toggle('hidden', !this.checked);
});
@if(old('nonaktifkan_mustahik'))
document.getElementById('nonaktif-warning').classList.remove('hidden');
@endif
</script>
@endpush