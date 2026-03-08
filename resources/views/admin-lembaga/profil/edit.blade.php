@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">

        {{-- ── Card Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Edit Profil</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui data admin masjid, data masjid, dan sejarah</p>
                </div>
                <a href="{{ route('admin-masjid.profil.show') }}"
                   class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('admin-masjid.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-4 sm:p-6 space-y-8">

                {{-- ══════════════════════════════════
                     BAGIAN 1 — DATA ADMIN MASJID
                ══════════════════════════════════ --}}
                <div>
                    <h2 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                        Data Admin Masjid
                    </h2>

                    {{-- Foto Admin --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Admin</label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <div class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-emerald-100 bg-gray-100 flex-shrink-0" id="admin-foto-preview-wrapper">
                                <img id="admin-foto-preview"
                                     src="{{ $masjid->admin_foto_url }}"
                                     alt="Foto Admin"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=059669&color=fff&size=96'">
                            </div>
                            <div class="flex flex-col gap-2 w-full sm:w-auto">
                                <input type="file" name="admin_foto" id="admin_foto" accept="image/*" class="hidden"
                                       onchange="previewAdminFoto(this)">
                                <button type="button" onclick="document.getElementById('admin_foto').click()"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Pilih Foto
                                </button>
                                @if($masjid->admin_foto)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="hapus_admin_foto" value="1"
                                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    <span class="text-xs text-red-600">Hapus foto</span>
                                </label>
                                @endif
                                <p class="text-xs text-gray-400">JPG, JPEG, PNG · Maks. 2MB</p>
                            </div>
                        </div>
                        @error('admin_foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        <div>
                            <label for="admin_nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Admin</label>
                            <input type="text" name="admin_nama" id="admin_nama"
                                value="{{ old('admin_nama', $masjid->admin_nama) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="Nama lengkap admin">
                            @error('admin_nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="admin_telepon" class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon Admin</label>
                            <input type="text" name="admin_telepon" id="admin_telepon"
                                value="{{ old('admin_telepon', $masjid->admin_telepon) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="08xxxxxxxxxx">
                            @error('admin_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Admin</label>
                            <input type="email" name="admin_email" id="admin_email"
                                value="{{ old('admin_email', $masjid->admin_email) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="email@masjid.com">
                            @error('admin_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════
                     BAGIAN 2 — DATA MASJID
                ══════════════════════════════════ --}}
                <div>
                    <h2 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                        Data Masjid
                    </h2>

                    {{-- Foto Masjid --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Masjid
                            <span class="text-xs text-gray-400 font-normal ml-1">({{ $masjid->foto_count }}/{{ \App\Models\Masjid::MAX_FOTO }} foto)</span>
                        </label>

                        {{-- Foto existing --}}
                        @if($masjid->foto_count > 0)
                        <div class="flex flex-wrap gap-3 mb-4" id="existing-fotos">
                            @foreach(($masjid->foto ?? []) as $index => $fotoPath)
                            <div class="relative group" id="foto-item-{{ $index }}">
                                <img src="{{ asset('storage/' . $fotoPath) }}"
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-24 h-24 object-cover rounded-xl border border-gray-200 shadow-sm">
                                @if($index === 0)
                                    <span class="absolute top-1 left-1 bg-emerald-600 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded-md">Utama</span>
                                @endif
                                {{-- Tombol hapus --}}
                                <label class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center cursor-pointer shadow-md hover:bg-red-600 transition"
                                       title="Hapus foto ini">
                                    <input type="checkbox" name="hapus_foto_index[]" value="{{ $index }}" class="hidden foto-hapus-cb">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </label>
                                {{-- Overlay saat dicentang --}}
                                <div class="foto-hapus-overlay absolute inset-0 bg-red-500 bg-opacity-50 rounded-xl items-center justify-center hidden">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Upload foto baru --}}
                        @if($masjid->canAddMoreFotos())
                        <div>
                            <input type="file" name="fotos[]" id="fotos" accept="image/*" multiple class="hidden"
                                   onchange="previewFotoMasjid(this)" data-max="{{ $masjid->getRemainingFotoSlots() }}">
                            <button type="button" onclick="document.getElementById('fotos').click()"
                                class="inline-flex items-center px-3 py-2 border border-dashed border-emerald-400 text-emerald-700 text-xs font-medium rounded-lg hover:bg-emerald-50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Foto (maks. {{ $masjid->getRemainingFotoSlots() }} lagi)
                            </button>
                            <div id="new-fotos-preview" class="flex flex-wrap gap-3 mt-3"></div>
                        </div>
                        @else
                        <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 inline-block">
                            Batas maksimal {{ \App\Models\Masjid::MAX_FOTO }} foto telah tercapai. Hapus foto yang ada untuk menambah yang baru.
                        </p>
                        @endif
                        @error('fotos') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('fotos.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Masjid <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $masjid->nama) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('nama') border-red-500 @enderror"
                                placeholder="Nama masjid" required>
                            @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1.5">Telepon Masjid</label>
                            <input type="text" name="telepon" id="telepon"
                                value="{{ old('telepon', $masjid->telepon) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="021xxxxxxxx">
                        </div>
                        <div>
                            <label for="email_masjid" class="block text-sm font-medium text-gray-700 mb-1.5">Email Masjid</label>
                            <input type="email" name="email_masjid" id="email_masjid"
                                value="{{ old('email_masjid', $masjid->email) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="info@masjid.com">
                            @error('email_masjid') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1.5">Kode Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos"
                                value="{{ old('kode_pos', $masjid->kode_pos) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="12345">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mt-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" id="alamat" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none @error('alamat') border-red-500 @enderror"
                            placeholder="Jl. ..." required>{{ old('alamat', $masjid->alamat) }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Wilayah --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mt-4">
                        <div>
                            <label for="provinsi_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <select name="provinsi_kode" id="provinsi_kode"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('provinsi_kode') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->code }}" {{ old('provinsi_kode', $masjid->provinsi_kode) == $prov->code ? 'selected' : '' }}>
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provinsi_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kota_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kabupaten/Kota <span class="text-red-500">*</span>
                            </label>
                            <select name="kota_kode" id="kota_kode"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('kota_kode') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                            </select>
                            @error('kota_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kecamatan_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <select name="kecamatan_kode" id="kecamatan_kode"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('kecamatan_kode') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            @error('kecamatan_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kelurahan_kode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kelurahan/Desa <span class="text-red-500">*</span>
                            </label>
                            <select name="kelurahan_kode" id="kelurahan_kode"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('kelurahan_kode') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                            @error('kelurahan_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mt-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Masjid</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none"
                            placeholder="Deskripsi singkat tentang masjid...">{{ old('deskripsi', $masjid->deskripsi) }}</textarea>
                    </div>
                </div>

                {{-- ══════════════════════════════════
                     BAGIAN 3 — SEJARAH MASJID
                ══════════════════════════════════ --}}
                <div>
                    <h2 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                        Sejarah Masjid
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        <div>
                            <label for="tahun_berdiri" class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" id="tahun_berdiri"
                                value="{{ old('tahun_berdiri', $masjid->tahun_berdiri) }}"
                                min="1000" max="{{ date('Y') + 1 }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="{{ date('Y') }}">
                            @error('tahun_berdiri') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pendiri" class="block text-sm font-medium text-gray-700 mb-1.5">Pendiri</label>
                            <input type="text" name="pendiri" id="pendiri"
                                value="{{ old('pendiri', $masjid->pendiri) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="Nama pendiri masjid">
                        </div>
                        <div>
                            <label for="kapasitas_jamaah" class="block text-sm font-medium text-gray-700 mb-1.5">Kapasitas Jamaah</label>
                            <input type="number" name="kapasitas_jamaah" id="kapasitas_jamaah"
                                value="{{ old('kapasitas_jamaah', $masjid->kapasitas_jamaah) }}"
                                min="0"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="Contoh: 1000">
                            @error('kapasitas_jamaah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="sejarah" class="block text-sm font-medium text-gray-700 mb-1.5">Sejarah Masjid</label>
                        <textarea name="sejarah" id="sejarah" rows="5"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none"
                            placeholder="Ceritakan sejarah berdirinya masjid...">{{ old('sejarah', $masjid->sejarah) }}</textarea>
                    </div>
                </div>

                {{-- ── Action Buttons ── --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin-masjid.profil.show') }}"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Preview foto admin ────────────────────────────────────────
function previewAdminFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('admin-foto-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Preview foto masjid baru ──────────────────────────────────
function previewFotoMasjid(input) {
    const container = document.getElementById('new-fotos-preview');
    const max       = parseInt(input.dataset.max || 5);
    container.innerHTML = '';

    Array.from(input.files).slice(0, max).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative w-24 h-24';
            div.innerHTML = `<img src="${e.target.result}" class="w-24 h-24 object-cover rounded-xl border border-emerald-200 shadow-sm">
                <span class="absolute bottom-1 right-1 bg-emerald-600 text-white text-[10px] px-1.5 py-0.5 rounded-md">Baru</span>`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// ── Toggle overlay pada foto yang akan dihapus ──────────────
document.querySelectorAll('.foto-hapus-cb').forEach(cb => {
    cb.addEventListener('change', function () {
        const item    = this.closest('[id^="foto-item-"]');
        const overlay = item.querySelector('.foto-hapus-overlay');
        if (this.checked) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        } else {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
    });
});

// ── Data wilayah dari controller ──────────────────────────────
const provinces = @json($provinces);
const cities = @json($cities);
const districts = @json($districts);
const villages = @json($villages);

// ── Inisialisasi dropdown ────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const provinsiSelect = document.getElementById('provinsi_kode');
    const kotaSelect = document.getElementById('kota_kode');
    const kecamatanSelect = document.getElementById('kecamatan_kode');
    const kelurahanSelect = document.getElementById('kelurahan_kode');

    // Load kota berdasarkan provinsi yang dipilih
    function loadCities(provinceCode, selectedCityCode = null) {
        kotaSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

        if (!provinceCode) return;

        // Filter cities berdasarkan province_code
        const filteredCities = cities.filter(city => city.province_code === provinceCode);
        
        filteredCities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.code;
            option.textContent = city.name;
            if (selectedCityCode && city.code === selectedCityCode) {
                option.selected = true;
            }
            kotaSelect.appendChild(option);
        });

        // Jika ada kota yang dipilih, load kecamatannya
        if (selectedCityCode) {
            loadDistricts(selectedCityCode);
        }
    }

    // Load kecamatan berdasarkan kota yang dipilih
    function loadDistricts(cityCode, selectedDistrictCode = null) {
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

        if (!cityCode) return;

        // Filter districts berdasarkan city_code
        const filteredDistricts = districts.filter(dist => dist.city_code === cityCode);
        
        filteredDistricts.forEach(dist => {
            const option = document.createElement('option');
            option.value = dist.code;
            option.textContent = dist.name;
            if (selectedDistrictCode && dist.code === selectedDistrictCode) {
                option.selected = true;
            }
            kecamatanSelect.appendChild(option);
        });

        // Jika ada kecamatan yang dipilih, load kelurahannya
        if (selectedDistrictCode) {
            loadVillages(selectedDistrictCode);
        }
    }

    // Load kelurahan berdasarkan kecamatan yang dipilih
    function loadVillages(districtCode, selectedVillageCode = null) {
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

        if (!districtCode) return;

        // Filter villages berdasarkan district_code
        const filteredVillages = villages.filter(vil => vil.district_code === districtCode);
        
        filteredVillages.forEach(vil => {
            const option = document.createElement('option');
            option.value = vil.code;
            option.textContent = vil.name;
            if (selectedVillageCode && vil.code === selectedVillageCode) {
                option.selected = true;
            }
            kelurahanSelect.appendChild(option);
        });
    }

    // Event listener untuk perubahan provinsi
    provinsiSelect.addEventListener('change', function() {
        loadCities(this.value);
    });

    // Event listener untuk perubahan kota
    kotaSelect.addEventListener('change', function() {
        loadDistricts(this.value);
    });

    // Event listener untuk perubahan kecamatan
    kecamatanSelect.addEventListener('change', function() {
        loadVillages(this.value);
    });

    // Inisialisasi awal dengan nilai yang sudah dipilih
    const selectedProvince = provinsiSelect.value;
    const selectedCity = '{{ $masjid->kota_kode }}';
    const selectedDistrict = '{{ $masjid->kecamatan_kode }}';
    const selectedVillage = '{{ $masjid->kelurahan_kode }}';

    if (selectedProvince) {
        loadCities(selectedProvince, selectedCity);
    }
    
    if (selectedCity) {
        loadDistricts(selectedCity, selectedDistrict);
    }
    
    if (selectedDistrict) {
        loadVillages(selectedDistrict, selectedVillage);
    }
});
</script>
@endpush