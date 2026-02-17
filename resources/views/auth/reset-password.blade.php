@extends('layouts.auth')

@section('title', 'Reset Password')

@section('card-title', 'Reset Password')

@section('card-subtitle', 'Masukkan password baru Anda')

@push('styles')
@if ($recaptchaSiteKey)
<style>
    .g-recaptcha-badge {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
        border-radius: 4px !important;
    }
    
    .btn-loading {
        cursor: not-allowed !important;
        opacity: 0.7 !important;
    }
</style>
@endif
@endpush

@section('content')
@if($errors->any())
    <div class="mb-6 p-4 bg-danger-50 border-l-4 border-danger rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-danger mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-danger-800">
                <p class="font-semibold mb-1">Perhatian:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-danger-50 border-l-4 border-danger rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-danger mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-danger-800">
                <p class="font-semibold mb-1">Error:</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@error('recaptcha_token')
    <div class="mb-6 p-4 bg-danger-50 border-l-4 border-danger rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-danger mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-danger-800">
                <p class="font-semibold mb-1">Error:</p>
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
@enderror

<form method="POST" action="{{ route('password.update', $uuid) }}" id="resetPasswordForm" class="space-y-6">
    @csrf
    {{-- Token dikirim sebagai hidden field --}}
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    
    <!-- Hidden field untuk reCAPTCHA token -->
    @if ($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif
    
    <!-- Info Box -->
    <div class="p-4 bg-primary-50 border-l-4 border-primary rounded-lg mb-6">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-primary mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-primary-800">
                <p class="font-semibold mb-1">Reset password untuk:</p>
                <p class="text-primary-700">
                    @if(isset($maskedEmail))
                        {{ $maskedEmail }}
                    @else
                        {{ $email }}
                    @endif
                </p>
                <p class="text-primary-600 text-xs mt-1">
                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    Untuk keamanan, email Anda telah disembunyikan sebagian
                </p>
            </div>
        </div>
    </div>

    <!-- Password Field -->
    <div class="space-y-2">
        <label for="password" class="block text-sm font-semibold text-neutral-700">
            Password Baru
        </label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                autocomplete="new-password"
                autofocus
                class="w-full pl-12 pr-10 py-3.5 bg-neutral-50 border-2 border-neutral-200 rounded-xl text-neutral-800 placeholder-neutral-400 focus:outline-none focus:border-primary focus:bg-white transition-all duration-200 @error('password') border-danger @enderror"
                placeholder="Masukkan password baru"
            >
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <button type="button" class="toggle-password-btn text-neutral-400 hover:text-primary transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>
        @error('password')
            <p class="text-danger text-xs mt-1.5 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
        <div class="mt-2">
            <div class="flex items-center space-x-2 mb-1">
                <div id="password-strength-bar" class="flex-1 h-2 bg-neutral-200 rounded-full overflow-hidden">
                    <div id="password-strength-fill" class="h-full w-0 rounded-full transition-all duration-300"></div>
                </div>
                <span id="password-strength-text" class="text-xs font-medium text-neutral-500">Lemah</span>
            </div>
            <ul class="text-xs text-neutral-500 space-y-1">
                <li id="length-check" class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                    Minimal 8 karakter
                </li>
                <li id="complexity-check" class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kombinasi huruf besar, kecil, dan angka
                </li>
            </ul>
        </div>
    </div>

    <!-- Confirm Password Field -->
    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-semibold text-neutral-700">
            Konfirmasi Password Baru
        </label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                class="w-full pl-12 pr-10 py-3.5 bg-neutral-50 border-2 border-neutral-200 rounded-xl text-neutral-800 placeholder-neutral-400 focus:outline-none focus:border-primary focus:bg-white transition-all duration-200 @error('password_confirmation') border-danger @enderror"
                placeholder="Konfirmasi password baru"
            >
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <button type="button" class="toggle-password-btn text-neutral-400 hover:text-primary transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>
        @error('password_confirmation')
            <p class="text-danger text-xs mt-1.5 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
        <div id="password-match" class="hidden mt-2 text-xs flex items-center">
            <svg class="w-3 h-3 mr-1 text-success" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-success">Password cocok</span>
        </div>
        <div id="password-mismatch" class="hidden mt-2 text-xs flex items-center">
            <svg class="w-3 h-3 mr-1 text-danger" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
            <span class="text-danger">Password tidak cocok</span>
        </div>
    </div>

    <!-- Password Requirements Info -->
    <div class="p-4 bg-neutral-50 border border-neutral-200 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-neutral-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-neutral-600">
                <p class="font-semibold mb-1">Syarat Password:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Minimal 8 karakter</li>
                    <li>Kombinasi huruf besar dan kecil</li>
                    <li>Sebaiknya mengandung angka</li>
                    <li>Bisa mengandung simbol (!@#$%^&*)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button 
        type="button" 
        id="submitButton"
        class="w-full bg-gradient-primary text-white font-semibold py-3.5 px-6 rounded-xl shadow-nz-lg hover:shadow-nz-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300 flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:shadow-nz-lg disabled:hover:transform-none"
        onclick="handleResetPassword()"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span id="buttonText">Reset Password</span>
    </button>
    
</form>
@endsection

@section('footer-links')
<div class="space-y-2">
    <p class="text-white text-sm">
        Ingat password Anda? 
        <a href="{{ route('login') }}" class="font-semibold text-primary-100 hover:text-white transition-colors underline underline-offset-2">
            Kembali ke Login
        </a>
    </p>
</div>
@endsection

@push('scripts')
<!-- reCAPTCHA v3 Script -->
@if ($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
    // Global variables
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const submitButton = document.getElementById('submitButton');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const toggleButtons = document.querySelectorAll('.toggle-password-btn');
    const recaptchaTokenInput = document.getElementById('recaptcha_token');
    
    // Password strength indicators
    const strengthBar = document.getElementById('password-strength-fill');
    const strengthText = document.getElementById('password-strength-text');
    const lengthCheck = document.getElementById('length-check');
    const complexityCheck = document.getElementById('complexity-check');
    const matchCheck = document.getElementById('password-match');
    const mismatchCheck = document.getElementById('password-mismatch');
    
    // Handle reset password submission with reCAPTCHA
    async function handleResetPassword() {
        // Validate passwords
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        
        if (!password) {
            alert('Harap masukkan password baru');
            passwordInput.focus();
            return;
        }
        
        if (!confirmPassword) {
            alert('Harap konfirmasi password baru');
            confirmInput.focus();
            return;
        }
        
        if (password !== confirmPassword) {
            alert('Password tidak cocok');
            passwordInput.focus();
            return;
        }
        
        // Check password strength
        const strength = checkPasswordStrength(password);
        if (strength < 40) {
            alert('Password terlalu lemah. Gunakan kombinasi huruf besar, kecil, dan angka.');
            passwordInput.focus();
            return;
        }
        
        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.classList.add('btn-loading');
        const originalHTML = submitButton.innerHTML;
        submitButton.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        try {
            @if ($recaptchaSiteKey)
            // Execute reCAPTCHA for reset password
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { 
                action: 'reset_password' 
            });
            
            // Set token ke hidden input
            if (recaptchaTokenInput) {
                recaptchaTokenInput.value = token;
            }
            @endif
            
            // Submit form
            resetPasswordForm.submit();
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            // Restore button state
            submitButton.innerHTML = originalHTML;
            submitButton.disabled = false;
            submitButton.classList.remove('btn-loading');
            
            alert('Terjadi kesalahan saat verifikasi reCAPTCHA. Silakan coba lagi.');
        }
    }
    
    // Check password strength
    function checkPasswordStrength(password) {
        let strength = 0;
        const checks = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            numbers: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        };
        
        // Update length check
        updateCheckIcon(lengthCheck, checks.length);
        
        // Update complexity check (at least one uppercase, one lowercase, one number)
        const hasComplexity = checks.lowercase && checks.uppercase && checks.numbers;
        updateCheckIcon(complexityCheck, hasComplexity);
        
        // Calculate strength
        if (checks.length) strength += 25;
        if (checks.lowercase) strength += 15;
        if (checks.uppercase) strength += 15;
        if (checks.numbers) strength += 20;
        if (checks.special) strength += 25;
        
        // Cap at 100
        strength = Math.min(strength, 100);
        
        // Update strength bar and text
        strengthBar.style.width = strength + '%';
        
        if (strength < 40) {
            strengthBar.className = 'h-full rounded-full transition-all duration-300 bg-danger';
            strengthText.textContent = 'Lemah';
            strengthText.className = 'text-xs font-medium text-danger';
        } else if (strength < 70) {
            strengthBar.className = 'h-full rounded-full transition-all duration-300 bg-warning';
            strengthText.textContent = 'Cukup';
            strengthText.className = 'text-xs font-medium text-warning';
        } else if (strength < 90) {
            strengthBar.className = 'h-full rounded-full transition-all duration-300 bg-primary';
            strengthText.textContent = 'Baik';
            strengthText.className = 'text-xs font-medium text-primary';
        } else {
            strengthBar.className = 'h-full rounded-full transition-all duration-300 bg-success';
            strengthText.textContent = 'Sangat Baik';
            strengthText.className = 'text-xs font-medium text-success';
        }
        
        return strength;
    }
    
    // Update check icon
    function updateCheckIcon(element, isValid) {
        const svg = element.querySelector('svg');
        if (isValid) {
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" stroke="currentColor"/>';
            element.classList.add('text-success');
            element.classList.remove('text-neutral-500');
        } else {
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor"/>';
            element.classList.remove('text-success');
            element.classList.add('text-neutral-500');
        }
    }
    
    // Check password match
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        
        if (password === '' || confirmPassword === '') {
            matchCheck.classList.add('hidden');
            mismatchCheck.classList.add('hidden');
            return;
        }
        
        if (password === confirmPassword) {
            matchCheck.classList.remove('hidden');
            mismatchCheck.classList.add('hidden');
        } else {
            matchCheck.classList.add('hidden');
            mismatchCheck.classList.remove('hidden');
        }
    }
    
    // Validate form before enabling submit
    function validateForm() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        const strength = checkPasswordStrength(password);
        
        const isPasswordValid = password.length >= 8 && 
                               /[a-z]/.test(password) && 
                               /[A-Z]/.test(password) && 
                               /\d/.test(password);
        
        const isPasswordMatch = password === confirmPassword;
        
        // Enable button only if password is strong enough and matches
        const isFormValid = isPasswordValid && isPasswordMatch && strength >= 40;
        
        if (isFormValid) {
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            submitButton.disabled = false;
        } else {
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.disabled = true;
        }
    }
    
    // Toggle password visibility
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.relative').querySelector('input');
            const icon = this.querySelector('svg');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            }
        });
    });
    
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Event listeners for password validation
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
            validateForm();
        });
        
        confirmInput.addEventListener('input', function() {
            checkPasswordMatch();
            validateForm();
        });
        
        // Focus password input
        passwordInput.focus();
        
        // Allow Enter key to submit form
        passwordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleResetPassword();
            }
        });
        
        confirmInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleResetPassword();
            }
        });
        
        // Initial validation
        validateForm();
    });
</script>
@endpush