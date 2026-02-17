@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('card-title', 'Lupa Password?')

@section('card-subtitle', 'Masukkan email Anda untuk reset password')

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

@if(session('error') && str_contains(session('error'), 'Mohon tunggu'))
    <div class="mb-4 p-4 bg-warning-50 border-l-4 border-warning rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-warning mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-warning-800">
                <p class="font-semibold mb-1">Perhatian:</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('success'))
    <div class="mb-4 p-4 bg-success-50 border-l-4 border-success rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-success mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-success-800">
                <p class="font-semibold mb-1">Berhasil:</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@error('recaptcha_token')
    <div class="mb-4 p-4 bg-danger-50 border-l-4 border-danger rounded-lg">
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

<form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" class="space-y-6">
    @csrf
    
    <!-- Hanya SATU hidden field untuk recaptcha_token -->
    @if ($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif
    
    <!-- Email Field -->
    <div class="space-y-2">
        <label for="email" class="block text-sm font-semibold text-neutral-700">
            Email
        </label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </div>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', session('email')) }}"
                required 
                autocomplete="email" 
                autofocus
                class="w-full pl-12 pr-4 py-3.5 bg-neutral-50 border-2 border-neutral-200 rounded-xl text-neutral-800 placeholder-neutral-400 focus:outline-none focus:border-primary focus:bg-white transition-all duration-200 @error('email') border-danger @enderror"
                placeholder="nama@email.com"
            >
        </div>
        @error('email')
            <p class="text-danger text-xs mt-1.5 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    
    <!-- Submit Button -->
    <button 
        type="button" 
        id="submitButton"
        class="w-full bg-gradient-primary text-white font-semibold py-3.5 px-6 rounded-xl shadow-nz-lg hover:shadow-nz-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300 flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:shadow-nz-lg disabled:hover:transform-none"
        onclick="handleForgotPassword()"
        @if(session('error') && str_contains(session('error'), 'Mohon tunggu')) disabled @endif
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <span id="buttonText">
            @if(session('error') && str_contains(session('error'), 'Mohon tunggu'))
                Tunggu...
            @else
                Kirim Link Reset Password
            @endif
        </span>
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
    <p class="text-white text-sm">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-semibold text-primary-100 hover:text-white transition-colors underline underline-offset-2">
            Daftar sekarang
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
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const emailInput = document.getElementById('email');
    const recaptchaTokenInput = document.getElementById('recaptcha_token');
    
    // Handle forgot password submission with reCAPTCHA
    async function handleForgotPassword() {
        // Validate email
        const email = emailInput.value.trim();
        if (!email) {
            alert('Harap masukkan email Anda');
            emailInput.focus();
            return;
        }
        
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Format email tidak valid');
            emailInput.focus();
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
            // Execute reCAPTCHA for forgot password
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { 
                action: 'forgot_password' 
            });
            
            // Set token ke hidden input
            if (recaptchaTokenInput) {
                recaptchaTokenInput.value = token;
            }
            @endif
            
            // Submit form
            forgotPasswordForm.submit();
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            // Restore button state
            submitButton.innerHTML = originalHTML;
            submitButton.disabled = false;
            submitButton.classList.remove('btn-loading');
            
            alert('Terjadi kesalahan saat verifikasi reCAPTCHA. Silakan coba lagi.');
        }
    }
    
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Ekstrak waktu tunggu dari pesan error jika ada
        const errorMessage = @json(session('error', ''));
        if (errorMessage && errorMessage.includes('Mohon tunggu')) {
            const match = errorMessage.match(/(\d+)/);
            if (match) {
                startCooldownTimer(parseInt(match[0]));
            }
        }
        
        // Focus email input
        emailInput.focus();
        
        // Allow Enter key to submit form
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleForgotPassword();
            }
        });
        
        // Reset button jika email diubah
        emailInput.addEventListener('input', function() {
            if (submitButton.disabled && !submitButton.classList.contains('btn-loading')) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed', 'btn-loading');
                buttonText.textContent = 'Kirim Link Reset Password';
            }
        });
    });
    
    function startCooldownTimer(seconds) {
        let remaining = seconds;
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Update button text dengan countdown
        const updateButtonText = () => {
            if (remaining <= 0) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span id="buttonText">Kirim Link Reset Password</span>
                `;
                return;
            }
            
            const minutes = Math.floor(remaining / 60);
            const secs = remaining % 60;
            
            if (minutes > 0) {
                buttonText.innerHTML = `Tunggu ${minutes}m ${secs.toString().padStart(2, '0')}s`;
            } else {
                buttonText.innerHTML = `Tunggu ${secs}s`;
            }
            
            remaining--;
            setTimeout(updateButtonText, 1000);
        };
        
        updateButtonText();
    }
</script>
@endpush