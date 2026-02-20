{{-- resources/views/superadmin/pengguna/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Edit Pengguna</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi akun pengguna</p>
        </div>

        <form action="{{ route('pengguna.update', $pengguna->uuid) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            {{-- SECTION 1 – INFORMASI AKUN --}}
            <div class="mb-6 sm:mb-8">
                <h3 class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">1</span>
                    Informasi Akun
                </h3>
                <div class="space-y-4">
                    <div>
                        <label for="peran" class="block text-sm font-medium text-gray-700 mb-2">
                            Peran <span class="text-red-500">*</span>
                        </label>
                        <select name="peran" id="peran"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('peran') border-red-500 @enderror"
                            onchange="handlePeranChange(this.value)">
                            <option value="">-- Pilih Peran --</option>
                            <option value="superadmin"   {{ old('peran', $pengguna->peran) === 'superadmin'   ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin_masjid" {{ old('peran', $pengguna->peran) === 'admin_masjid' ? 'selected' : '' }}>Admin Masjid</option>
                            <option value="amil"         {{ old('peran', $pengguna->peran) === 'amil'         ? 'selected' : '' }}>Amil</option>
                        </select>
                        @error('peran') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <div id="info-superadmin" class="hidden mt-2 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <p class="text-xs text-purple-700">Super Admin hanya memerlukan informasi akun dan password.</p>
                        </div>
                        <div id="info-admin-masjid" class="hidden mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-700">Perubahan di sini akan memperbarui akun dan seluruh data masjid terkait.</p>
                        </div>
                        <div id="info-amil" class="hidden mt-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                            <p class="text-xs text-emerald-700">Perubahan di sini akan memperbarui akun dan data profil amil.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" id="username"
                                value="{{ old('username', $pengguna->username) }}"
                                placeholder="Opsional"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('username') border-red-500 @enderror">
                            @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-xs text-gray-400">Jika kosong, gunakan email untuk login.</p>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $pengguna->email) }}"
                                placeholder="pengguna@example.com"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror">
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2 – PASSWORD --}}
            <div class="mb-6 sm:mb-8">
                <h3 class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">2</span>
                    Password
                </h3>
                <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-xs text-amber-700">ⓘ Kosongkan field password jika tidak ingin mengubah password pengguna.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru <span class="text-gray-400 text-xs font-normal">(opsional)</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                class="block w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password') border-red-500 @enderror">
                            <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-gray-400 text-xs font-normal">(opsional)</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password baru"
                                class="block w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════ --}}
            {{-- SECTION 3A – DATA ADMIN MASJID                          --}}
            {{-- ════════════════════════════════════════════════════════ --}}
            @php $masjid = $pengguna->masjid ?? null; @endphp
            <div id="section-admin-masjid" class="{{ old('peran', $pengguna->peran) === 'admin_masjid' ? '' : 'hidden' }} mb-6 sm:mb-8">

                {{-- SUB: Data Admin --}}
                <h3 class="flex items-center gap-2 text-sm sm:text-base font-semibold text-blue-900 mb-4 pb-2 border-b border-blue-200">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">3</span>
                    Data Admin Masjid
                </h3>

                {{-- Pilih Masjid --}}
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <label for="masjid_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Masjid <span class="text-red-500">*</span>
                    </label>
                    <select name="masjid_id" id="masjid_id"
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('masjid_id') border-red-500 @enderror"
                        onchange="onMasjidChange(this.value)">
                        <option value="">-- Pilih Masjid --</option>
                        @foreach($masjidList as $m)
                            <option value="{{ $m->id }}" {{ old('masjid_id', $pengguna->masjid_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('masjid_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-blue-600">Pilih masjid yang akan dikelola admin ini. Data masjid akan terisi otomatis.</p>
                </div>

                <div class="space-y-4 mb-6">
                    <div>
                        <label for="admin_nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Admin <span class="text-red-500">*</span></label>
                        <input type="text" name="admin_nama" id="admin_nama"
                            value="{{ old('admin_nama', $masjid?->admin_nama) }}"
                            placeholder="Nama lengkap admin masjid"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('admin_nama') border-red-500 @enderror">
                        @error('admin_nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="admin_telepon" class="block text-sm font-medium text-gray-700 mb-2">Telepon Admin <span class="text-red-500">*</span></label>
                            <input type="text" name="admin_telepon" id="admin_telepon"
                                value="{{ old('admin_telepon', $masjid?->admin_telepon) }}"
                                placeholder="08xxxxxxxxxx"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('admin_telepon') border-red-500 @enderror">
                            @error('admin_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">Email Admin <span class="text-red-500">*</span></label>
                            <input type="email" name="admin_email" id="admin_email"
                                value="{{ old('admin_email', $masjid?->admin_email) }}"
                                placeholder="admin@masjid.com"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('admin_email') border-red-500 @enderror">
                            @error('admin_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Admin <span class="text-gray-400 text-xs font-normal">(opsional, maks 2MB)</span>
                        </label>
                        @if($masjid?->admin_foto)
                            <div class="mb-2 flex items-center gap-3">
                                <img src="{{ Storage::url($masjid->admin_foto) }}" alt="Foto Admin"
                                    class="w-16 h-16 object-cover rounded-xl border border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-500">Foto saat ini</p>
                                    <label class="flex items-center gap-2 mt-1 cursor-pointer">
                                        <input type="checkbox" name="hapus_admin_foto" value="1" class="rounded">
                                        <span class="text-xs text-red-600">Hapus foto</span>
                                    </label>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="admin_foto" id="admin_foto" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('admin_foto') border border-red-500 rounded-xl p-1 @enderror">
                        @error('admin_foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- SUB: Identitas Masjid --}}
                <h4 class="text-sm font-semibold text-blue-800 mb-3 pb-1 border-b border-blue-100">Identitas Masjid</h4>
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="nama_masjid" class="block text-sm font-medium text-gray-700 mb-2">Nama Masjid <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_masjid" id="nama_masjid"
                            value="{{ old('nama_masjid', $masjid?->nama) }}"
                            placeholder="Masjid Al-Ikhlas"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('nama_masjid') border-red-500 @enderror">
                        @error('nama_masjid') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="telepon_masjid" class="block text-sm font-medium text-gray-700 mb-2">Telepon Masjid <span class="text-red-500">*</span></label>
                            <input type="text" name="telepon_masjid" id="telepon_masjid"
                                value="{{ old('telepon_masjid', $masjid?->telepon) }}"
                                placeholder="021xxxxxxxx"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('telepon_masjid') border-red-500 @enderror">
                            @error('telepon_masjid') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email_masjid" class="block text-sm font-medium text-gray-700 mb-2">Email Masjid <span class="text-red-500">*</span></label>
                            <input type="email" name="email_masjid" id="email_masjid"
                                value="{{ old('email_masjid', $masjid?->email) }}"
                                placeholder="masjid@example.com"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('email_masjid') border-red-500 @enderror">
                            @error('email_masjid') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="deskripsi_masjid" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-gray-400 text-xs font-normal">(opsional)</span></label>
                        <textarea name="deskripsi_masjid" id="deskripsi_masjid" rows="2"
                            placeholder="Deskripsi singkat tentang masjid"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 resize-none transition-all @error('deskripsi_masjid') border-red-500 @enderror">{{ old('deskripsi_masjid', $masjid?->deskripsi) }}</textarea>
                    </div>
                </div>

                {{-- SUB: Alamat & Wilayah --}}
                <h4 class="text-sm font-semibold text-blue-800 mb-3 pb-1 border-b border-blue-100">Alamat & Wilayah</h4>
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" id="alamat" rows="2"
                            placeholder="Jl. Contoh No. 123, RT 01/RW 02"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 resize-none transition-all @error('alamat') border-red-500 @enderror">{{ old('alamat', $masjid?->alamat) }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Info wilayah saat ini --}}
                    @if($masjid?->provinsi_nama)
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-xs text-gray-500 font-medium mb-1">Wilayah saat ini:</p>
                        <p class="text-xs text-gray-700">
                            {{ $masjid->kelurahan_nama }}, {{ $masjid->kecamatan_nama }}, {{ $masjid->kota_nama }}, {{ $masjid->provinsi_nama }}
                            @if($masjid->kode_pos) — {{ $masjid->kode_pos }} @endif
                        </p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Provinsi --}}
                        <div>
                            <label for="provinsi_kode" class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select name="provinsi_kode" id="provinsi_kode"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('provinsi_kode') border-red-500 @enderror"
                                onchange="loadKota(this.value)">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->code }}"
                                        {{ old('provinsi_kode', $masjid?->provinsi_kode) === $prov->code ? 'selected' : '' }}>
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provinsi_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        {{-- Kota --}}
                        <div>
                            <label for="kota_kode" class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                            <select name="kota_kode" id="kota_kode"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('kota_kode') border-red-500 @enderror"
                                onchange="loadKecamatan(this.value)">
                                <option value="">-- Pilih Kota/Kabupaten --</option>
                            </select>
                            @error('kota_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        {{-- Kecamatan --}}
                        <div>
                            <label for="kecamatan_kode" class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="kecamatan_kode" id="kecamatan_kode"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('kecamatan_kode') border-red-500 @enderror"
                                onchange="loadKelurahan(this.value)">
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            @error('kecamatan_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        {{-- Kelurahan --}}
                        <div>
                            <label for="kelurahan_kode" class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select name="kelurahan_kode" id="kelurahan_kode"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('kelurahan_kode') border-red-500 @enderror"
                                onchange="fillKodePos(this)">
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                            </select>
                            @error('kelurahan_kode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="sm:w-1/4">
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos"
                            value="{{ old('kode_pos', $masjid?->kode_pos) }}"
                            placeholder="12345" maxlength="5"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all @error('kode_pos') border-red-500 @enderror">
                    </div>
                </div>

                {{-- SUB: Sejarah & Info Tambahan --}}
                <h4 class="text-sm font-semibold text-blue-800 mb-3 pb-1 border-b border-blue-100">Sejarah & Info Tambahan <span class="text-gray-400 text-xs font-normal">(opsional)</span></h4>
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="sejarah" class="block text-sm font-medium text-gray-700 mb-2">Sejarah Masjid</label>
                        <textarea name="sejarah" id="sejarah" rows="3"
                            placeholder="Ceritakan sejarah singkat masjid ini..."
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 resize-none transition-all">{{ old('sejarah', $masjid?->sejarah) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="tahun_berdiri" class="block text-sm font-medium text-gray-700 mb-2">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" id="tahun_berdiri"
                                value="{{ old('tahun_berdiri', $masjid?->tahun_berdiri) }}"
                                placeholder="{{ date('Y') }}" min="1900" max="{{ date('Y') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label for="pendiri" class="block text-sm font-medium text-gray-700 mb-2">Pendiri</label>
                            <input type="text" name="pendiri" id="pendiri"
                                value="{{ old('pendiri', $masjid?->pendiri) }}"
                                placeholder="Nama pendiri masjid"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label for="kapasitas_jamaah" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas Jamaah</label>
                            <input type="number" name="kapasitas_jamaah" id="kapasitas_jamaah"
                                value="{{ old('kapasitas_jamaah', $masjid?->kapasitas_jamaah) }}"
                                placeholder="500" min="1"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                {{-- SUB: Foto Masjid --}}
                <h4 class="text-sm font-semibold text-blue-800 mb-3 pb-1 border-b border-blue-100">Foto Masjid <span class="text-gray-400 text-xs font-normal">(opsional, maks 5 foto)</span></h4>
                <div>
                    {{-- Foto existing --}}
                    @if($masjid && !empty($masjid->foto))
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 mb-2">Foto saat ini:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach((array) $masjid->foto as $i => $fotoPath)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($fotoPath) }}" alt="Foto Masjid {{ $i+1 }}"
                                            class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                                        <label class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition rounded-xl flex items-center justify-center cursor-pointer">
                                            <input type="checkbox" name="hapus_foto_masjid[]" value="{{ $i }}" class="hidden">
                                            <span class="text-white text-xs font-medium">Hapus</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Hover pada foto untuk opsi hapus.</p>
                        </div>
                    @endif
                    <input type="file" name="foto_masjid[]" id="foto_masjid" multiple accept="image/jpeg,image/png,image/jpg"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('foto_masjid') border border-red-500 rounded-xl p-1 @enderror">
                    <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG. Maks 2MB per foto, maks 5 foto total.</p>
                    @error('foto_masjid') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('foto_masjid.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════ --}}
            {{-- SECTION 3B – DATA AMIL                                  --}}
            {{-- ════════════════════════════════════════════════════════ --}}
            @php $amil = $pengguna->amil ?? null; @endphp
            <div id="section-amil" class="{{ old('peran', $pengguna->peran) === 'amil' ? '' : 'hidden' }} mb-6 sm:mb-8">
                <h3 class="flex items-center gap-2 text-sm sm:text-base font-semibold text-emerald-900 mb-4 pb-2 border-b border-emerald-200">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs">3</span>
                    Data Amil
                </h3>

                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <label for="masjid_id_amil" class="block text-sm font-medium text-gray-700 mb-2">
                        Masjid <span class="text-red-500">*</span>
                    </label>
                    <select name="masjid_id" id="masjid_id_amil"
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('masjid_id') border-red-500 @enderror">
                        <option value="">-- Pilih Masjid --</option>
                        @foreach($masjidList as $m)
                            <option value="{{ $m->id }}" {{ old('masjid_id', $pengguna->masjid_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('masjid_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-emerald-600">Amil akan ditautkan ke masjid ini.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="amil_nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="amil_nama_lengkap" id="amil_nama_lengkap"
                            value="{{ old('amil_nama_lengkap', $amil?->nama_lengkap) }}"
                            placeholder="Nama lengkap amil"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_nama_lengkap') border-red-500 @enderror">
                        @error('amil_nama_lengkap') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="amil_jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="amil_jenis_kelamin" id="amil_jenis_kelamin"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_jenis_kelamin') border-red-500 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('amil_jenis_kelamin', $amil?->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('amil_jenis_kelamin', $amil?->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('amil_jenis_kelamin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="amil_tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="amil_tempat_lahir" id="amil_tempat_lahir"
                                value="{{ old('amil_tempat_lahir', $amil?->tempat_lahir) }}"
                                placeholder="Jakarta"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_tempat_lahir') border-red-500 @enderror">
                            @error('amil_tempat_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="amil_tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="amil_tanggal_lahir" id="amil_tanggal_lahir"
                                value="{{ old('amil_tanggal_lahir', $amil?->tanggal_lahir?->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_tanggal_lahir') border-red-500 @enderror">
                            @error('amil_tanggal_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="amil_telepon" class="block text-sm font-medium text-gray-700 mb-2">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="amil_telepon" id="amil_telepon"
                                value="{{ old('amil_telepon', $amil?->telepon) }}"
                                placeholder="08xxxxxxxxxx"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_telepon') border-red-500 @enderror">
                            @error('amil_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="amil_status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="amil_status" id="amil_status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_status') border-red-500 @enderror">
                                <option value="aktif"    {{ old('amil_status', $amil?->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('amil_status', $amil?->status) === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="cuti"     {{ old('amil_status', $amil?->status) === 'cuti'     ? 'selected' : '' }}>Cuti</option>
                            </select>
                            @error('amil_status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="amil_alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="amil_alamat" id="amil_alamat" rows="2"
                            placeholder="Alamat lengkap amil"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 resize-none transition-all @error('amil_alamat') border-red-500 @enderror">{{ old('amil_alamat', $amil?->alamat) }}</textarea>
                        @error('amil_alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="amil_tanggal_mulai_tugas" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Tugas <span class="text-red-500">*</span></label>
                            <input type="date" name="amil_tanggal_mulai_tugas" id="amil_tanggal_mulai_tugas"
                                value="{{ old('amil_tanggal_mulai_tugas', $amil?->tanggal_mulai_tugas?->format('Y-m-d')) }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_tanggal_mulai_tugas') border-red-500 @enderror">
                            @error('amil_tanggal_mulai_tugas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="amil_tanggal_selesai_tugas" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai Tugas <span class="text-gray-400 text-xs font-normal">(opsional)</span></label>
                            <input type="date" name="amil_tanggal_selesai_tugas" id="amil_tanggal_selesai_tugas"
                                value="{{ old('amil_tanggal_selesai_tugas', $amil?->tanggal_selesai_tugas?->format('Y-m-d')) }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all @error('amil_tanggal_selesai_tugas') border-red-500 @enderror">
                            @error('amil_tanggal_selesai_tugas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="amil_wilayah_tugas" class="block text-sm font-medium text-gray-700 mb-2">Wilayah Tugas <span class="text-gray-400 text-xs font-normal">(opsional)</span></label>
                        <input type="text" name="amil_wilayah_tugas" id="amil_wilayah_tugas"
                            value="{{ old('amil_wilayah_tugas', $amil?->wilayah_tugas) }}"
                            placeholder="Contoh: Kelurahan Cempaka Putih"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Amil <span class="text-gray-400 text-xs font-normal">(opsional, maks 2MB)</span>
                        </label>
                        @if($amil?->foto)
                            <div class="mb-2 flex items-center gap-3">
                                <img src="{{ Storage::url($amil->foto) }}" alt="Foto Amil"
                                    class="w-16 h-16 object-cover rounded-xl border border-gray-200">
                                <div>
                                    <p class="text-xs text-gray-500">Foto saat ini</p>
                                    <label class="flex items-center gap-2 mt-1 cursor-pointer">
                                        <input type="checkbox" name="hapus_amil_foto" value="1" class="rounded">
                                        <span class="text-xs text-red-600">Hapus foto</span>
                                    </label>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="amil_foto" id="amil_foto" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 @error('amil_foto') border border-red-500 rounded-xl p-1 @enderror">
                        @error('amil_foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="amil_keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan <span class="text-gray-400 text-xs font-normal">(opsional)</span></label>
                        <textarea name="amil_keterangan" id="amil_keterangan" rows="2"
                            placeholder="Catatan tambahan"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 resize-none transition-all">{{ old('amil_keterangan', $amil?->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION 4 – STATUS AKUN --}}
            <div class="mb-6 sm:mb-8">
                <h3 class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    <span id="status-step-num" class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">
                        {{ in_array(old('peran', $pengguna->peran), ['admin_masjid','amil']) ? '4' : '3' }}
                    </span>
                    Status Akun
                </h3>
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $pengguna->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                    <div>
                        <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Akun Aktif</label>
                        <p class="text-xs text-gray-500">Pengguna dapat login jika akun aktif</p>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs text-gray-500">
                        <span class="font-medium text-gray-700">Info Akun:</span>
                        Dibuat {{ $pengguna->created_at->diffForHumans() }}
                        @if($pengguna->email_verified_at)
                            · Email terverifikasi {{ $pengguna->email_verified_at->diffForHumans() }}
                        @else
                            · <span class="text-amber-600">Email belum terverifikasi</span>
                        @endif
                        @if($pengguna->google_id)
                            · <span class="text-blue-600">Akun Google</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-200">
                <a href="{{ route('pengguna.show', $pengguna->uuid) }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
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
    const currentPeran = '{{ old('peran', $pengguna->peran) }}';
    handlePeranChange(currentPeran);

    // Load wilayah jika admin_masjid dan ada provinsi lama
    const oldProvinsi = '{{ old('provinsi_kode', $pengguna->masjid?->provinsi_kode ?? '') }}';
    if (oldProvinsi && currentPeran === 'admin_masjid') {
        loadKota(
            oldProvinsi,
            '{{ old('kota_kode', $pengguna->masjid?->kota_kode ?? '') }}',
            '{{ old('kecamatan_kode', $pengguna->masjid?->kecamatan_kode ?? '') }}',
            '{{ old('kelurahan_kode', $pengguna->masjid?->kelurahan_kode ?? '') }}'
        );
    }
});

function handlePeranChange(value) {
    const sectionAdmin = document.getElementById('section-admin-masjid');
    const sectionAmil  = document.getElementById('section-amil');
    const statusStep   = document.getElementById('status-step-num');
    const infoSuper    = document.getElementById('info-superadmin');
    const infoAdmin    = document.getElementById('info-admin-masjid');
    const infoAmil     = document.getElementById('info-amil');

    sectionAdmin.classList.add('hidden');
    sectionAmil.classList.add('hidden');
    infoSuper.classList.add('hidden');
    infoAdmin.classList.add('hidden');
    infoAmil.classList.add('hidden');

    if (value === 'admin_masjid') {
        sectionAdmin.classList.remove('hidden');
        infoAdmin.classList.remove('hidden');
        statusStep.textContent = '4';
    } else if (value === 'amil') {
        sectionAmil.classList.remove('hidden');
        infoAmil.classList.remove('hidden');
        statusStep.textContent = '4';
    } else if (value === 'superadmin') {
        infoSuper.classList.remove('hidden');
        statusStep.textContent = '3';
    } else {
        statusStep.textContent = '3';
    }
}

function onMasjidChange(masjidId) {
    // Kosongkan field masjid jika masjid diganti
    // (opsional: bisa fetch data masjid via AJAX untuk auto-fill)
}

function togglePassword(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

// ── Wilayah AJAX ──────────────────────────────────────────────────────────────
function setLoading(selectId, msg = 'Memuat...') {
    const el = document.getElementById(selectId);
    el.innerHTML = `<option value="">${msg}</option>`;
    el.disabled = true;
}

function resetSelect(selectId, placeholder) {
    const el = document.getElementById(selectId);
    el.innerHTML = `<option value="">${placeholder}</option>`;
    el.disabled = false;
}

function loadKota(provinsiKode, selectedKota = '', selectedKecamatan = '', selectedKelurahan = '') {
    if (!provinsiKode) {
        resetSelect('kota_kode', '-- Pilih Kota/Kabupaten --');
        resetSelect('kecamatan_kode', '-- Pilih Kecamatan --');
        resetSelect('kelurahan_kode', '-- Pilih Kelurahan/Desa --');
        return;
    }
    setLoading('kota_kode');
    resetSelect('kecamatan_kode', '-- Pilih Kecamatan --');
    resetSelect('kelurahan_kode', '-- Pilih Kelurahan/Desa --');

    fetch(`{{ url('/registration-api/cities') }}?province_code=${provinsiKode}`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('kota_kode');
            sel.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
            (data.cities || []).forEach(c => {
                const opt = new Option(c.name, c.code, false, c.code === selectedKota);
                sel.add(opt);
            });
            sel.disabled = false;
            if (selectedKota) loadKecamatan(selectedKota, selectedKecamatan, selectedKelurahan);
        })
        .catch(() => resetSelect('kota_kode', '-- Gagal memuat --'));
}

function loadKecamatan(kotaKode, selectedKec = '', selectedKel = '') {
    if (!kotaKode) {
        resetSelect('kecamatan_kode', '-- Pilih Kecamatan --');
        resetSelect('kelurahan_kode', '-- Pilih Kelurahan/Desa --');
        return;
    }
    setLoading('kecamatan_kode');
    resetSelect('kelurahan_kode', '-- Pilih Kelurahan/Desa --');

    fetch(`{{ url('/registration-api/districts') }}?city_code=${kotaKode}`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('kecamatan_kode');
            sel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            (data.districts || []).forEach(d => {
                const opt = new Option(d.name, d.code, false, d.code === selectedKec);
                sel.add(opt);
            });
            sel.disabled = false;
            if (selectedKec) loadKelurahan(selectedKec, selectedKel);
        })
        .catch(() => resetSelect('kecamatan_kode', '-- Gagal memuat --'));
}

function loadKelurahan(kecamatanKode, selectedKel = '') {
    if (!kecamatanKode) {
        resetSelect('kelurahan_kode', '-- Pilih Kelurahan/Desa --');
        return;
    }
    setLoading('kelurahan_kode');

    fetch(`{{ url('/registration-api/villages') }}?district_code=${kecamatanKode}`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('kelurahan_kode');
            sel.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
            (data.villages || []).forEach(v => {
                const opt = new Option(v.name, v.code, false, v.code === selectedKel);
                opt.dataset.postalCode = (v.meta && v.meta.postal_code) ? v.meta.postal_code : '';
                sel.add(opt);
            });
            sel.disabled = false;
            if (selectedKel) fillKodePos(sel);
        })
        .catch(() => resetSelect('kelurahan_kode', '-- Gagal memuat --'));
}

function fillKodePos(selectEl) {
    const selected = selectEl.options[selectEl.selectedIndex];
    const postalCode = selected?.dataset?.postalCode;
    if (postalCode) {
        document.getElementById('kode_pos').value = postalCode;
    }
}
</script>
@endpush