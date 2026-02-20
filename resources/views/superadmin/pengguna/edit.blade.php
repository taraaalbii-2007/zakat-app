{{-- resources/views/superadmin/pengguna/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Pengguna</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi akun pengguna</p>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    {!! $pengguna->peran_badge !!}
                    {!! $pengguna->status_badge !!}
                </div>
            </div>

            <form action="{{ route('pengguna.update', $pengguna->uuid) }}" method="POST" class="p-4 sm:p-6">
                @csrf
                @method('PUT')

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
                                <option value="superadmin"   {{ old('peran', $pengguna->peran) === 'superadmin'   ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin_masjid" {{ old('peran', $pengguna->peran) === 'admin_masjid' ? 'selected' : '' }}>Admin Masjid</option>
                                <option value="amil"         {{ old('peran', $pengguna->peran) === 'amil'         ? 'selected' : '' }}>Amil</option>
                            </select>
                            @error('peran')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Masjid (conditional) --}}
                        <div id="masjid-field"
                            class="{{ in_array(old('peran', $pengguna->peran), ['admin_masjid','amil']) ? '' : 'hidden' }}">
                            <label for="masjid_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Masjid <span class="text-red-500">*</span>
                            </label>
                            <select name="masjid_id" id="masjid_id"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('masjid_id') border-red-500 @enderror">
                                <option value="">-- Pilih Masjid --</option>
                                @foreach($masjidList as $masjid)
                                    <option value="{{ $masjid->id }}"
                                        {{ old('masjid_id', $pengguna->masjid_id) == $masjid->id ? 'selected' : '' }}>
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
                            <input type="text" name="username" id="username"
                                value="{{ old('username', $pengguna->username) }}"
                                placeholder="Contoh: admin_masjid_al_ikhlas"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $pengguna->email) }}"
                                placeholder="pengguna@example.com"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Google Info (read-only) --}}
                        @if($pengguna->is_google_user)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Akun terhubung dengan Google</p>
                                        <p class="text-xs text-blue-700 mt-0.5">Google ID: {{ $pengguna->google_id }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Section 2 - Reset Password --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Password
                    </h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                        <p class="text-xs text-yellow-800">
                            <svg class="w-4 h-4 inline mr-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kosongkan kolom password jika tidak ingin mengubah password.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
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
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Ulangi password baru"
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
                                {{ old('is_active', $pengguna->is_active) ? 'checked' : '' }}
                                {{ $pengguna->id === auth()->id() ? 'disabled' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></div>
                        </label>
                        <div>
                            <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                                Akun Aktif
                            </label>
                            @if($pengguna->id === auth()->id())
                                <p class="text-xs text-red-500">Anda tidak dapat menonaktifkan akun sendiri.</p>
                            @else
                                <p class="text-xs text-gray-500">Pengguna dapat login jika akun aktif</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('pengguna.show', $pengguna->uuid) }}"
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
    });

    function handlePeranChange(value) {
        const masjidField  = document.getElementById('masjid-field');
        const masjidSelect = document.getElementById('masjid_id');

        if (value === 'admin_masjid' || value === 'amil') {
            masjidField.classList.remove('hidden');
            masjidSelect.required = true;
        } else {
            masjidField.classList.add('hidden');
            masjidSelect.required = false;
        }
    }

    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush