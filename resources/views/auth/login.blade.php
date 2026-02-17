@extends('layouts.auth')

@section('title', 'Login')

@section('card-title', 'Selamat Datang')

@section('card-subtitle', 'Masukkan kredensial Anda untuk melanjutkan')

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
<form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
    @csrf

    <!-- reCAPTCHA hidden input -->
    @if ($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif
    
    <!-- Login Field (Email atau Username) -->
    <div class="space-y-2">
        <label for="login" class="block text-sm font-semibold text-neutral-700">
            Email atau Username
        </label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </div>
            <input 
                type="text" 
                id="login" 
                name="login" 
                value="{{ old('login') }}"
                required 
                autocomplete="off" 
                autofocus
                class="w-full pl-12 pr-4 py-3.5 bg-neutral-50 border-2 border-neutral-200 rounded-xl text-neutral-800 placeholder-neutral-400 focus:outline-none focus:border-primary focus:bg-white transition-all duration-200 @error('login') border-danger @enderror"
                placeholder="nama@email.com atau username"
            >
        </div>
        @error('login')
            <p class="text-danger text-xs mt-1.5 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    
    <!-- Password Field -->
    <div class="space-y-2">
        <label for="password" class="block text-sm font-semibold text-neutral-700">
            Password
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
                autocomplete="current-password"
                class="w-full pl-12 pr-12 py-3.5 bg-neutral-50 border-2 border-neutral-200 rounded-xl text-neutral-800 placeholder-neutral-400 focus:outline-none focus:border-primary focus:bg-white transition-all duration-200 @error('password') border-danger @enderror"
                placeholder="Masukkan password"
            >
            <button 
                type="button" 
                onclick="togglePassword()"
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-primary transition-colors"
            >
                <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        @error('password')
            <p class="text-danger text-xs mt-1.5 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    
    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between">
        <label class="flex items-center group cursor-pointer">
            <input 
                type="checkbox" 
                name="remember" 
                id="remember"
                class="w-4 h-4 text-primary bg-neutral-100 border-neutral-300 rounded focus:ring-primary-500 focus:ring-2 transition-all cursor-pointer"
                {{ old('remember') ? 'checked' : '' }}
            >
            <span class="ml-2 text-sm text-neutral-700 group-hover:text-primary transition-colors">
                Ingat saya
            </span>
        </label>
        
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary hover:text-primary-700 transition-colors">
            Lupa password?
        </a>
        @endif
    </div>
    
    <!-- Submit Button -->
    <button 
        type="submit" 
        id="login-submit-btn"
        class="w-full bg-gradient-primary text-white font-semibold py-3.5 px-6 rounded-xl shadow-nz-lg hover:shadow-nz-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300"
    >
        <span class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk
        </span>
    </button>
    
    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-neutral-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-neutral-500">Atau masuk dengan</span>
        </div>
    </div>
    
    <!-- Google Login Button -->
    <a 
        href="{{ route('auth.google', ['action' => 'login']) }}"
        class="w-full flex items-center justify-center px-4 py-3.5 border-2 border-neutral-200 rounded-xl text-neutral-700 font-semibold hover:bg-neutral-50 hover:border-neutral-300 hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-300"
    >
        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Masuk dengan Google
    </a>
    
</form>
@endsection

@section('footer-links')
<p class="text-white text-sm">
    Belum punya akun? 
    <a href="{{ route('register') }}" class="font-semibold text-primary-100 hover:text-white transition-colors underline underline-offset-2">
        Daftar sekarang
    </a>
</p>
@endsection

@push('scripts')
<!-- reCAPTCHA v3 Script -->
@if ($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }

    // reCAPTCHA v3 — execute on form submit
    @if ($recaptchaSiteKey)
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('login-submit-btn');
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';

        grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'login' }).then(function(token) {
            document.getElementById('recaptcha_token').value = token;
            document.getElementById('login-form').submit();
        }).catch(function() {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'default';
        });
    });
    @endif
</script>
@endpush