@extends('layouts.auth')

@section('title', 'Register')

@section('card-title', 'Daftar Akun Baru')

@section('card-subtitle', 'Masukkan email Anda untuk memulai')

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
</style>
@endif
@endpush

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-6" id="register-form">
    @csrf

    <!-- reCAPTCHA hidden input -->
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
                value="{{ old('email') }}"
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
        type="submit" 
        id="register-submit-btn"
        class="w-full bg-gradient-primary text-white font-semibold py-3.5 px-6 rounded-xl shadow-nz-lg hover:shadow-nz-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300"
    >
        <span class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Daftar dengan Email
        </span>
    </button>
    
    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-neutral-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-neutral-500">Atau daftar dengan</span>
        </div>
    </div>
    
    <!-- Google Register Button -->
    <a 
        href="{{ route('auth.google', ['action' => 'register']) }}"
        class="w-full flex items-center justify-center px-4 py-3.5 border-2 border-neutral-200 rounded-xl text-neutral-700 font-semibold hover:bg-neutral-50 hover:border-neutral-300 hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-300"
    >
        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Daftar dengan Google
    </a>
    
    <!-- Info Text -->
    <div class="mt-6 text-center text-sm text-neutral-600">
        <svg class="w-4 h-4 inline mr-1 text-primary" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        Anda akan menerima kode OTP untuk verifikasi
    </div>
    
</form>
@endsection

@section('footer-links')
<p class="text-white text-sm">
    Sudah punya akun? 
    <a href="{{ route('login') }}" class="font-semibold text-primary-100 hover:text-white transition-colors underline underline-offset-2">
        Masuk di sini
    </a>
</p>
@endsection

@push('scripts')
<!-- reCAPTCHA v3 Script -->
@if ($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
    // reCAPTCHA v3 — execute on form submit
    @if ($recaptchaSiteKey)
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('register-submit-btn');
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';

        grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'register' }).then(function(token) {
            document.getElementById('recaptcha_token').value = token;
            document.getElementById('register-form').submit();
        }).catch(function(error) {
            console.error('reCAPTCHA error:', error);
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'default';
            
            // Fallback submit jika reCAPTCHA gagal
            document.getElementById('register-form').submit();
        });
    });
    @endif
    
    // Auto lowercase email input
    document.getElementById('email').addEventListener('input', function(e) {
        this.value = this.value.toLowerCase();
    });
</script>
@endpush