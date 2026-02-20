@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- ── Header ── --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Ubah Password</h1>
                <p class="text-gray-600 mt-1">Perbarui kata sandi akun Anda</p>
            </div>
            <a href="{{ route('superadmin.profil.show') }}"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Profil
            </a>
        </div>

        {{-- ══════════════════════════════════════════════════
             FORM UBAH PASSWORD
        ══════════════════════════════════════════════════ --}}
        @if(!$user->is_google_user)
        <form action="{{ route('superadmin.profil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Password Saat Ini --}}
            <div class="mb-6 w-full">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Saat Ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('current_password') border-red-500 @enderror"
                        placeholder="Masukkan password saat ini" required>
                    <button type="button" onclick="togglePassword('current_password')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg id="eye-current_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-slash-current_password" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div class="mb-6 w-full">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter" required>
                    <button type="button" onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-slash-password" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-6 w-full">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Ketik ulang password baru" required>
                    <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg id="eye-password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-slash-password_confirmation" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Password strength indicator --}}
            <div class="mt-4 w-full">
                <div class="flex items-center gap-2 mb-2">
                    <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                        <div id="password-strength-bar" class="h-full w-0 transition-all duration-300"></div>
                    </div>
                    <span id="password-strength-text" class="text-xs font-medium whitespace-nowrap">Kekuatan Password</span>
                </div>
                <ul class="text-xs text-gray-500 space-y-1 list-disc list-inside">
                    <li id="rule-length" class="text-gray-400">Minimal 8 karakter</li>
                    <li id="rule-uppercase" class="text-gray-400">Mengandung huruf besar (A-Z)</li>
                    <li id="rule-lowercase" class="text-gray-400">Mengandung huruf kecil (a-z)</li>
                    <li id="rule-number" class="text-gray-400">Mengandung angka (0-9)</li>
                </ul>
            </div>

            {{-- Peringatan perubahan password --}}
            <div class="mt-6 w-full p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h4 class="font-medium text-amber-800">Perhatian: Perubahan Password</h4>
                        <p class="text-sm text-amber-700 mt-1">
                            Jika Anda mengubah password, Anda akan <strong>otomatis logout</strong> dan harus login ulang 
                            menggunakan password baru. Email notifikasi akan dikirim ke alamat email Anda.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons - Full Width --}}
            <div class="flex justify-end space-x-4 pt-6 mt-6 border-t border-gray-200 w-full">
                <a href="{{ route('superadmin.profil.show') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ubah Password
                </button>
            </div>

        </form>
        @else
        {{-- Info jika login via Google --}}
        <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-lg w-full">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-blue-700">
                Akun Anda terdaftar melalui <strong>Google OAuth</strong>. 
                Ubah password melalui pengaturan akun Google Anda.
            </p>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    // ── Toggle password visibility ───────────────────
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

    // ── Password strength checker ───────────────────
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const rules = {
        length: document.getElementById('rule-length'),
        uppercase: document.getElementById('rule-uppercase'),
        lowercase: document.getElementById('rule-lowercase'),
        number: document.getElementById('rule-number')
    };

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Check rules
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            
            // Update rule colors
            rules.length.className = hasLength ? 'text-green-600' : 'text-gray-400';
            rules.uppercase.className = hasUppercase ? 'text-green-600' : 'text-gray-400';
            rules.lowercase.className = hasLowercase ? 'text-green-600' : 'text-gray-400';
            rules.number.className = hasNumber ? 'text-green-600' : 'text-gray-400';
            
            // Calculate strength
            if (hasLength) strength += 25;
            if (hasUppercase) strength += 25;
            if (hasLowercase) strength += 25;
            if (hasNumber) strength += 25;
            
            // Update bar
            strengthBar.style.width = strength + '%';
            
            // Update color and text
            if (strength <= 25) {
                strengthBar.className = 'h-full bg-red-500 transition-all duration-300';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-medium text-red-500 whitespace-nowrap';
            } else if (strength <= 50) {
                strengthBar.className = 'h-full bg-orange-500 transition-all duration-300';
                strengthText.textContent = 'Cukup';
                strengthText.className = 'text-xs font-medium text-orange-500 whitespace-nowrap';
            } else if (strength <= 75) {
                strengthBar.className = 'h-full bg-yellow-500 transition-all duration-300';
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-xs font-medium text-yellow-500 whitespace-nowrap';
            } else {
                strengthBar.className = 'h-full bg-green-500 transition-all duration-300';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-xs font-medium text-green-500 whitespace-nowrap';
            }
        });
    }
</script>
@endpush