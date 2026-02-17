{{-- resources/views/amil/profil/edit.blade.php --}}
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
                <a href="{{ route('profil.show') }}"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
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
                 FORM EDIT PROFIL + FOTO + TANDA TANGAN
            ══════════════════════════════════════════════════ --}}
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Upload Foto ── --}}
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Foto Profil
                    </h2>
                    <div class="flex items-start space-x-6">
                        <div class="shrink-0">
                            @if($amil->foto)
                                <img id="foto-preview"
                                    src="{{ $amil->foto_url }}"
                                    alt="Foto Profil"
                                    class="h-24 w-24 object-cover rounded-full border-2 border-gray-200">
                            @else
                                <div class="h-24 w-24 rounded-full bg-primary/20 border-2 border-gray-200 flex items-center justify-center relative">
                                    <img id="foto-preview" src="" alt="" class="h-24 w-24 object-cover rounded-full border-2 border-gray-200 hidden absolute inset-0">
                                    <span id="foto-initial" class="text-2xl font-semibold text-primary">
                                        {{ strtoupper(substr($amil->nama_lengkap, 0, 1)) }}
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
                            @if($amil->foto)
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

                {{-- ── Tanda Tangan ── --}}
                <div id="section-ttd" class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Tanda Tangan
                        <span class="text-sm font-normal text-gray-500 ml-2">— muncul otomatis di kwitansi zakat</span>
                    </h2>
                    <div class="flex flex-col sm:flex-row items-start gap-6">

                        {{-- Preview box: before & after --}}
                        <div class="shrink-0 flex flex-col gap-2">
                            {{-- Preview AFTER (transparent background) --}}
                            <div id="ttd-box"
                                class="w-48 h-24 rounded-lg border-2 @if($amil->tanda_tangan_url) border-gray-200 @else border-dashed border-gray-300 @endif bg-gray-50 flex items-center justify-center overflow-hidden relative">
                                {{-- Checkerboard background (menunjukkan transparansi) --}}
                                <div id="ttd-checker"
                                    class="absolute inset-0 hidden"
                                    style="background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%),
                                           linear-gradient(-45deg, #e5e7eb 25%, transparent 25%),
                                           linear-gradient(45deg, transparent 75%, #e5e7eb 75%),
                                           linear-gradient(-45deg, transparent 75%, #e5e7eb 75%);
                                           background-size: 12px 12px;
                                           background-position: 0 0, 0 6px, 6px -6px, -6px 0px;">
                                </div>
                                @if($amil->tanda_tangan_url)
                                    <img id="ttd-preview" src="{{ $amil->tanda_tangan_url }}" alt="TTD"
                                        class="relative z-10 h-full max-w-full object-contain p-2">
                                @else
                                    <img id="ttd-preview" src="" alt="" class="relative z-10 h-full max-w-full object-contain p-2 hidden">
                                    <div id="ttd-placeholder" class="flex flex-col items-center gap-1 text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span class="text-xs">Belum ada TTD</span>
                                    </div>
                                @endif

                                {{-- Processing overlay --}}
                                <div id="ttd-processing"
                                    class="absolute inset-0 z-20 hidden bg-white/80 flex items-center justify-center rounded-lg">
                                    <svg class="w-6 h-6 text-primary animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-center text-gray-400">Preview hasil</p>
                        </div>

                        {{-- Controls --}}
                        <div class="flex-1 space-y-3">
                            <div>
                                <label for="tanda_tangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Tanda Tangan
                                </label>
                                <input type="file" name="tanda_tangan" id="tanda_tangan" accept="image/jpeg,image/png,image/jpg"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tanda_tangan') border-red-500 @enderror">

                                {{-- Hidden input yang akan menampung hasil PNG transparan --}}
                                <input type="hidden" name="tanda_tangan_processed" id="tanda_tangan_processed">

                                @error('tanda_tangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status badge auto remove bg --}}
                            <div id="ttd-status" class="hidden">
                                <span id="ttd-status-badge"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Background berhasil dihapus
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-blue-700 leading-relaxed">
                                    Background putih akan <strong>otomatis dihapus</strong> saat Anda memilih gambar.
                                    Gunakan foto tanda tangan di atas kertas putih bersih untuk hasil terbaik.
                                    Format JPG, PNG. Maks. 1MB.
                                </p>
                            </div>

                            @if($amil->tanda_tangan)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="remove_ttd" value="1"
                                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-600">Hapus tanda tangan saat ini</span>
                                </label>
                            @endif
                        </div>
                    </div>

                    {{-- Canvas tersembunyi untuk proses remove background --}}
                    <canvas id="ttd-canvas" class="hidden"></canvas>
                </div>

                {{-- ── Data Pribadi ── --}}
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                        Data Pribadi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama Lengkap --}}
                        <div class="md:col-span-2">
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                value="{{ old('nama_lengkap', $amil->nama_lengkap) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('nama_lengkap') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama_lengkap')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('jenis_kelamin') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $amil->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $amil->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir"
                                value="{{ old('tempat_lahir', $amil->tempat_lahir) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tempat_lahir') border-red-500 @enderror"
                                placeholder="Kota tempat lahir">
                            @error('tempat_lahir')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                value="{{ old('tanggal_lahir', optional($amil->tanggal_lahir)->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="telepon" id="telepon"
                                value="{{ old('telepon', $amil->telepon) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('telepon') border-red-500 @enderror"
                                placeholder="Contoh: 081234567890">
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $amil->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('email') border-red-500 @enderror"
                                placeholder="email@contoh.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Wilayah Tugas --}}
                        <div>
                            <label for="wilayah_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                                Wilayah Tugas
                            </label>
                            <input type="text" name="wilayah_tugas" id="wilayah_tugas"
                                value="{{ old('wilayah_tugas', $amil->wilayah_tugas) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                                placeholder="Kelurahan / RW / Area">
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <input type="text" name="keterangan" id="keterangan"
                                value="{{ old('keterangan', $amil->keterangan) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                                placeholder="Catatan tambahan (opsional)">
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 resize-none @error('alamat') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat', $amil->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Action Buttons ── --}}
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('profil.show') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>

            {{-- ══════════════════════════════════════════════════
                 FORM UBAH PASSWORD
            ══════════════════════════════════════════════════ --}}
            <form action="{{ route('profil.password') }}" method="POST" id="section-password" class="mt-10">
                @csrf
                @method('PUT')

                <div class="mb-6 pb-3 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Ubah Password</h2>
                    <p class="text-sm text-gray-500 mt-1">Perbarui kata sandi akun Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    @foreach([
                        ['current_password',      'Password Saat Ini'],
                        ['password',              'Password Baru'],
                        ['password_confirmation', 'Konfirmasi Password'],
                    ] as [$fieldId, $fieldLabel])
                    <div>
                        <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $fieldLabel }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="{{ $fieldId }}" id="{{ $fieldId }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-12 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error($fieldId) border-red-500 @enderror"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('{{ $fieldId }}')"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eye-{{ $fieldId }}" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-slash-{{ $fieldId }}" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error($fieldId)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach

                </div>

                <div class="flex justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Ubah Password
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ── Preview foto profil ──────────────────────────
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

    // ── Tanda Tangan: Auto Remove Background ────────
    document.getElementById('tanda_tangan').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (evt) {
            const img = new Image();
            img.onload = function () {
                // Tampilkan loading overlay
                const processing  = document.getElementById('ttd-processing');
                const status      = document.getElementById('ttd-status');
                const statusBadge = document.getElementById('ttd-status-badge');
                if (processing) processing.classList.remove('hidden');
                if (status)     status.classList.add('hidden');

                // Beri jeda kecil agar browser sempat render spinner
                setTimeout(function () {
                    try {
                        const canvas  = document.getElementById('ttd-canvas');
                        const ctx     = canvas.getContext('2d');

                        canvas.width  = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);

                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const data      = imageData.data;

                        // ── Algoritma remove background ──────────────
                        // 1. Ambil warna background dari 4 sudut gambar
                        function getPixel(x, y) {
                            const i = (y * canvas.width + x) * 4;
                            return { r: data[i], g: data[i+1], b: data[i+2], a: data[i+3] };
                        }

                        const corners = [
                            getPixel(0, 0),
                            getPixel(canvas.width - 1, 0),
                            getPixel(0, canvas.height - 1),
                            getPixel(canvas.width - 1, canvas.height - 1),
                        ];
                        const bgR = Math.round(corners.reduce((s, c) => s + c.r, 0) / 4);
                        const bgG = Math.round(corners.reduce((s, c) => s + c.g, 0) / 4);
                        const bgB = Math.round(corners.reduce((s, c) => s + c.b, 0) / 4);

                        // Threshold: toleransi warna terhadap background
                        // Makin tinggi = makin agresif menghapus warna terang
                        const THRESHOLD = 30;

                        // 2. Untuk setiap piksel: hitung jarak warna ke bg
                        //    Piksel dekat bg → transparan, piksel jauh → opak
                        for (let i = 0; i < data.length; i += 4) {
                            const r = data[i];
                            const g = data[i+1];
                            const b = data[i+2];

                            const dist = Math.sqrt(
                                Math.pow(r - bgR, 2) +
                                Math.pow(g - bgG, 2) +
                                Math.pow(b - bgB, 2)
                            );

                            if (dist <= THRESHOLD) {
                                // Piksel background → transparan penuh
                                data[i+3] = 0;
                            } else if (dist <= THRESHOLD * 2.5) {
                                // Piksel transisi → semi transparan (feathering)
                                const alpha = Math.round(((dist - THRESHOLD) / (THRESHOLD * 1.5)) * 255);
                                data[i+3]   = alpha;
                            }
                            // Piksel foreground (tinta) → biarkan opak
                        }

                        ctx.putImageData(imageData, 0, 0);

                        // Konversi ke PNG (wajib PNG agar transparansi tersimpan)
                        const processedDataUrl = canvas.toDataURL('image/png');

                        // Simpan ke hidden input → dikirim ke server
                        document.getElementById('tanda_tangan_processed').value = processedDataUrl;

                        // Tampilkan preview hasil
                        const preview     = document.getElementById('ttd-preview');
                        const placeholder = document.getElementById('ttd-placeholder');
                        const box         = document.getElementById('ttd-box');
                        const checker     = document.getElementById('ttd-checker');

                        preview.src = processedDataUrl;
                        preview.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                        if (checker)     checker.classList.remove('hidden');
                        box.classList.remove('border-dashed', 'border-gray-300');
                        box.classList.add('border-gray-200');

                        // Tampilkan badge sukses
                        if (status) {
                            status.classList.remove('hidden');
                            statusBadge.innerHTML = `
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Background berhasil dihapus`;
                            statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200';
                        }

                    } catch (err) {
                        console.error('Remove background error:', err);
                        // Fallback: gunakan gambar asli tanpa pemrosesan
                        const preview = document.getElementById('ttd-preview');
                        const placeholder = document.getElementById('ttd-placeholder');
                        const box = document.getElementById('ttd-box');
                        preview.src = evt.target.result;
                        preview.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                        box.classList.remove('border-dashed', 'border-gray-300');
                        box.classList.add('border-gray-200');

                        const status      = document.getElementById('ttd-status');
                        const statusBadge = document.getElementById('ttd-status-badge');
                        if (status) {
                            status.classList.remove('hidden');
                            statusBadge.innerHTML = `
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Gambar asli digunakan`;
                            statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200';
                        }
                    } finally {
                        if (processing) processing.classList.add('hidden');
                    }
                }, 80); // delay kecil untuk render spinner
            };
            img.src = evt.target.result;
        };
        reader.readAsDataURL(file);
    });

    // ── Toggle password visibility ───────────────────
    function togglePassword(fieldId) {
        const field       = document.getElementById(fieldId);
        const eyeIcon     = document.getElementById('eye-' + fieldId);
        const eyeSlashIcon = document.getElementById('eye-slash-' + fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            field.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }
</script>
@endpush