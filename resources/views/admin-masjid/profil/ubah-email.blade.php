@extends('layouts.app')

@section('title', 'Ubah Email')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">

        {{-- ── Card Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Ubah Email</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui alamat email akun Anda</p>
                </div>
                <a href="{{ route('admin-masjid.profil.show') }}"
                   class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Profil
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-6">

            @if(!$user->is_google_user)

            {{-- Info email saat ini --}}
            <div class="w-full mb-6 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">EMAIL SAAT INI</p>
                <p class="text-sm font-semibold text-gray-900">{{ $user->email }}</p>
            </div>

            <form action="{{ route('admin-masjid.profil.email.update') }}" method="POST" class="w-full">
                @csrf
                @method('PUT')

                {{-- Email Baru --}}
                <div class="w-full mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email"
                            value="{{ old('email') }}"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 @enderror"
                            placeholder="emailbaru@contoh.com"
                            required>
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="w-full mb-5">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Masukkan password Anda saat ini untuk mengkonfirmasi perubahan email.</p>
                    <div class="relative w-full">
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2.5 pr-11 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('current_password') border-red-500 @enderror"
                            placeholder="Masukkan password Anda" required>
                        <button type="button" onclick="togglePassword('current_password')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg id="eye-current_password" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-slash-current_password" class="w-4.5 h-4.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Peringatan --}}
                <div class="w-full mb-6 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs sm:text-sm text-blue-700 space-y-1">
                            <p><strong>Perhatian:</strong> Setelah email diubah:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-blue-600">
                                <li>Anda akan <strong>otomatis logout</strong> dari sistem</li>
                                <li>Email notifikasi akan dikirim ke alamat email baru</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="w-full flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin-masjid.profil.show') }}"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Simpan Email Baru
                    </button>
                </div>

            </form>

            @else
            {{-- Login via Google --}}
            <div class="w-full flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-700">
                    Akun Anda terdaftar melalui <strong>Google OAuth</strong>.
                    Ubah email melalui pengaturan akun Google Anda.
                </p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field        = document.getElementById(fieldId);
    const eyeIcon      = document.getElementById('eye-' + fieldId);
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