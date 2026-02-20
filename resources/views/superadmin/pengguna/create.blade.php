{{-- resources/views/superadmin/pengguna/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Tambah Pengguna</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Isi informasi akun pengguna baru</p>
            </div>

            <form action="{{ route('pengguna.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf

                {{-- Section 1 - Informasi Akun --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Informasi Akun
                    </h3>
                    <div class="space-y-4 sm:space-y-6">

                        {{-- Peran --}}
                        <div>
                            <label for="peran" class="block text-sm font-medium text-gray-700 mb-2">
                                Peran <span class="text-red-500">*</span>
                            </label>
                            <select name="peran" id="peran"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('peran') border-red-500 @enderror"
                                onchange="handlePeranChange(this.value)">
                                <option value="">-- Pilih Peran --</option>
                                <option value="superadmin"   {{ old('peran') === 'superadmin'   ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin_masjid" {{ old('peran') === 'admin_masjid' ? 'selected' : '' }}>Admin Masjid</option>
                                <option value="amil"         {{ old('peran') === 'amil'         ? 'selected' : '' }}>Amil</option>
                            </select>
                            @error('peran')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Masjid (conditional) --}}
                        <div id="masjid-field" class="{{ in_array(old('peran'), ['admin_masjid','amil']) ? '' : 'hidden' }}">
                            <label for="masjid_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Masjid <span class="text-red-500">*</span>
                            </label>
                            <select name="masjid_id" id="masjid_id"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('masjid_id') border-red-500 @enderror">
                                <option value="">-- Pilih Masjid --</option>
                                @foreach($masjidList as $masjid)
                                    <option value="{{ $masjid->id }}" {{ old('masjid_id') == $masjid->id ? 'selected' : '' }}>
                                        {{ $masjid->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('masjid_id')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                placeholder="Contoh: admin_masjid_al_ikhlas"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Opsional. Hanya huruf, angka, dan underscore.</p>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="pengguna@example.com"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2 - Password --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Password
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    placeholder="Minimal 8 karakter"
                                    class="block w-full px-3 py-2 pr-10 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password') border-red-500 @enderror">
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Ulangi password"
                                    class="block w-full px-3 py-2 pr-10 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3 - Status --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                        Status Akun
                    </h3>
                    <div class="flex items-center space-x-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                        <div>
                            <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                                Akun Aktif
                            </label>
                            <p class="text-xs text-gray-500">Pengguna dapat login jika akun aktif</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('pengguna.index') }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.innerWidth >= 768) {
            document.getElementById('peran')?.focus();
        }
    });

    function handlePeranChange(value) {
        const masjidField = document.getElementById('masjid-field');
        const masjidSelect = document.getElementById('masjid_id');

        if (value === 'admin_masjid' || value === 'amil') {
            masjidField.classList.remove('hidden');
            masjidSelect.required = true;
        } else {
            masjidField.classList.add('hidden');
            masjidSelect.required = false;
            masjidSelect.value = '';
        }
    }

    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush