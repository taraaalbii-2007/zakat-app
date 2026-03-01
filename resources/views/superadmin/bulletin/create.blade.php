@extends('layouts.app')

@section('title', 'Tambah Bulletin')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 300px; font-size: 15px; }
    .ql-container { font-family: inherit; }
    .drag-zone { transition: all 0.3s ease; }
    .drag-zone.drag-over { border-color: #4F46E5; background-color: #EEF2FF; }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <a href="{{ route('superadmin.bulletin.index') }}"
                   class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Tambah Bulletin</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi field yang diperlukan untuk membuat bulletin baru</p>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.bulletin.store') }}" method="POST"
              enctype="multipart/form-data" id="bulletinForm" class="p-4 sm:p-6">
            @csrf

            <div class="space-y-6">

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori Bulletin <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <div id="select-container">
                            <select id="kategori-select" name="kategori_bulletin_id"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('kategori_bulletin_id') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_bulletin_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                                <option value="buat-baru" class="font-semibold">+ Buat Kategori Baru</option>
                            </select>
                        </div>
                        <div id="new-kategori-container" class="hidden">
                            <div class="flex gap-2">
                                <input type="text" id="kategori-baru" name="kategori_baru"
                                       placeholder="Nama kategori baru..."
                                       value="{{ old('kategori_baru') }}"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                <button type="button" onclick="batalBuatKategori()"
                                        class="px-4 py-2 text-sm border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Kategori baru akan tersimpan otomatis saat bulletin dibuat</p>
                        </div>
                    </div>
                    @error('kategori_bulletin_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('kategori_baru')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul & Lokasi --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Bulletin <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                               placeholder="Masukkan judul bulletin"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('judul') border-red-500 @enderror">
                        @error('judul')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                                   placeholder="Contoh: Jakarta, Indonesia"
                                   class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('lokasi') border-red-500 @enderror">
                        </div>
                        @error('lokasi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama / Thumbnail</label>
                    <div id="thumbnail-zone"
                         class="drag-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
                        <input type="file" name="thumbnail" id="thumbnail"
                               accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                        <div id="thumbnail-placeholder">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 text-sm font-medium mb-1">Klik untuk upload atau drag & drop</p>
                            <p class="text-xs text-gray-400">PNG, JPG, WEBP hingga 2MB</p>
                        </div>
                        <div id="thumbnail-preview" class="hidden">
                            <img id="preview-img" src="" alt="" class="max-h-64 mx-auto rounded-lg mb-3 shadow-md">
                            <button type="button" onclick="removeThumbnail()"
                                    class="text-sm text-red-600 hover:text-red-700 font-medium transition-colors">
                                Hapus gambar
                            </button>
                        </div>
                    </div>
                    {{-- Caption --}}
                    <div id="caption-container" class="hidden mt-3">
                        <input type="text" name="image_caption" id="image_caption"
                               value="{{ old('image_caption') }}"
                               placeholder="Caption gambar (opsional)"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        @error('image_caption')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @error('thumbnail')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Publish --}}
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Publish <span class="text-gray-400 font-normal text-xs">(opsional, kosongkan = sekarang)</span>
                    </label>
                    <input type="datetime-local" name="published_at" id="published_at"
                           value="{{ old('published_at') }}"
                           class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('published_at')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konten --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konten Bulletin <span class="text-red-500">*</span>
                    </label>
                    <div id="quill-editor"
                         class="bg-white border border-gray-300 rounded-xl overflow-hidden @error('konten') border-red-500 @enderror">
                    </div>
                    <textarea name="konten" id="konten" class="hidden">{{ old('konten') }}</textarea>
                    @error('konten')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('superadmin.bulletin.index') }}"
                       class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Bulletin
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Quill ----
    let quill = null;
    try {
        quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tulis konten bulletin di sini...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    [{ font: [] }],
                    [{ size: ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    ['link', 'image', 'video'],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });

        const oldKonten = {!! json_encode(old('konten', '')) !!};
        if (oldKonten) quill.root.innerHTML = oldKonten;
    } catch (e) { console.error(e); }

    // ---- Form Submit ----
    const form      = document.getElementById('bulletinForm');
    const kontenTA  = document.getElementById('konten');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';

        if (quill) {
            kontenTA.value = quill.root.innerHTML;
            const text = quill.getText().trim();
            if (text.length < 50) {
                alert('Konten bulletin minimal 50 karakter!');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Bulletin';
                return;
            }
        }
        form.submit();
    });

    // ---- Thumbnail ----
    const zone        = document.getElementById('thumbnail-zone');
    const input       = document.getElementById('thumbnail');
    const placeholder = document.getElementById('thumbnail-placeholder');
    const preview     = document.getElementById('thumbnail-preview');
    const previewImg  = document.getElementById('preview-img');
    const captionBox  = document.getElementById('caption-container');

    zone.addEventListener('click', function (e) { if (!e.target.closest('button')) input.click(); });
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; showPreview(e.dataTransfer.files[0]); }
    });
    input.addEventListener('change', function () { if (this.files.length) showPreview(this.files[0]); });

    function showPreview(file) {
        if (file.size > 2 * 1024 * 1024) { alert('Ukuran gambar maksimal 2MB!'); input.value = ''; return; }
        if (!file.type.startsWith('image/')) { alert('File harus berupa gambar!'); input.value = ''; return; }
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
            captionBox.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    window.removeThumbnail = function () {
        input.value = ''; previewImg.src = '';
        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');
        captionBox.classList.add('hidden');
    };

    // ---- Kategori Toggle ----
    const sel          = document.getElementById('kategori-select');
    const selCont      = document.getElementById('select-container');
    const newKatCont   = document.getElementById('new-kategori-container');
    const newKatInput  = document.getElementById('kategori-baru');

    sel.addEventListener('change', function () {
        if (this.value === 'buat-baru') {
            selCont.classList.add('hidden');
            newKatCont.classList.remove('hidden');
            newKatInput.focus();
            this.value = '';
        }
    });

    window.batalBuatKategori = function () {
        selCont.classList.remove('hidden');
        newKatCont.classList.add('hidden');
        newKatInput.value = '';
        sel.value = '';
    };

    // Auto-focus
    if (window.innerWidth >= 768) {
        setTimeout(() => document.getElementById('judul')?.focus(), 100);
    }
});
</script>
@endpush