@extends('layouts.auth')

@section('title', 'Login')
@section('auth-title', 'Hai, Selamat Datang')
@section('auth-subtitle', 'Masukkan akun Anda untuk mengakses dashboard')

@push('styles')
<style>
    /* ══════════════════════════════════
       HIDE UNWANTED ELEMENTS
    ══════════════════════════════════ */
    .right-brand,
    .right-eyebrow { display: none !important; }

    .auth-right {
        padding: 2rem 2.5rem !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }

    .right-heading { margin-bottom: 1.5rem !important; }
    .right-heading h1 {
        font-size: 1.6rem !important;
        font-weight: 800 !important;
        color: #111827 !important;
        letter-spacing: -.03em !important;
        margin-bottom: .3rem !important;
        line-height: 1.2 !important;
    }
    .right-heading p { font-size: .8rem !important; color: #9ca3af !important; }
    .right-footer { margin-top: 1rem !important; font-size: .78rem !important; }

    /* ══════════════════════════════════
       FORM GROUP
    ══════════════════════════════════ */
    .lg-group { margin-bottom: 1rem; }

    .lg-label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .5rem;
    }

    .lg-wrap { position: relative; }

    .lg-icon {
        position: absolute;
        left: .875rem; top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
        display: flex; align-items: center;
        transition: color .2s;
    }
    .lg-icon svg { width: 16px; height: 16px; display: block; }

    .lg-input {
        display: block;
        width: 100%;
        height: 48px;
        padding: 0 .875rem 0 2.75rem;
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: .83rem;
        font-weight: 400;
        color: #111827;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        -webkit-appearance: none;
        appearance: none;
        box-sizing: border-box;
    }
    .lg-input::placeholder { color: #c4cad4; }
    .lg-input:hover  { border-color: #d1d5db; }
    .lg-input:focus  {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    }
    .lg-input.err { border-color: #f43f5e; }
    .lg-input.err:focus { box-shadow: 0 0 0 3px rgba(244,63,94,.1); }

    .lg-input:-webkit-autofill,
    .lg-input:-webkit-autofill:hover,
    .lg-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 999px #ffffff inset !important;
        -webkit-text-fill-color: #111827 !important;
        transition: background-color 9999s ease 0s;
    }

    .lg-wrap:focus-within .lg-icon { color: #16a34a; }
    .lg-wrap.err .lg-icon          { color: #f43f5e; }
    .lg-input.has-toggle { padding-right: 3rem; }

    .pw-btn {
        position: absolute;
        right: .75rem; top: 50%;
        transform: translateY(-50%);
        background: none; border: none;
        padding: .3rem; color: #9ca3af;
        cursor: pointer; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        transition: color .2s; line-height: 1;
    }
    .pw-btn:hover { color: #16a34a; }
    .pw-btn svg { width: 16px; height: 16px; display: block; }

    .lg-err {
        display: flex; align-items: center; gap: .3rem;
        font-size: .71rem; color: #f43f5e; margin-top: .4rem;
    }
    .lg-err svg { width: 13px; height: 13px; flex-shrink: 0; }

    /* ══════════════════════════════════════════
       REMEMBER + LUPA PASSWORD — SEJAJAR 1 BARIS
    ══════════════════════════════════════════ */
    .remember-forgot-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.1rem;
        margin-top: .5rem;
    }
    .remember-left {
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .remember-left input[type="checkbox"] {
        width: 15px; height: 15px;
        accent-color: #16a34a;
        cursor: pointer; flex-shrink: 0; margin: 0;
    }
    .remember-left label {
        font-size: .76rem; color: #6b7280;
        cursor: pointer; user-select: none; line-height: 1;
    }
    .forgot-link {
        font-size: .74rem; font-weight: 600;
        color: #16a34a; text-decoration: none;
        transition: color .2s; white-space: nowrap;
    }
    .forgot-link:hover { color: #15803d; }

    /* ══════════════════════════════════
       BUTTON MASUK
    ══════════════════════════════════ */
    .btn-masuk {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; height: 48px; padding: 0 1.25rem;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 60%, #15803d 100%);
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: .88rem; font-weight: 700;
        border: none; border-radius: 10px; cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(22,163,74,.35);
        transition: transform .18s, box-shadow .18s;
        -webkit-appearance: none;
    }
    .btn-masuk svg { width: 18px; height: 18px; flex-shrink: 0; display: block; }
    .btn-masuk:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(22,163,74,.42);
    }
    .btn-masuk:active  { transform: translateY(0); }
    .btn-masuk:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    .btn-spinner {
        width: 17px; height: 17px;
        border: 2.5px solid rgba(255,255,255,.35);
        border-top-color: #fff; border-radius: 50%;
        animation: spin .6s linear infinite;
        flex-shrink: 0; display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-masuk.loading .btn-spinner { display: block; }
    .btn-masuk.loading .btn-label   { display: none; }
    .btn-label { display: flex; align-items: center; gap: .5rem; line-height: 1; }

    /* ══════════════════════════════════
       DIVIDER — font lebih tebal
    ══════════════════════════════════ */
    .or-row {
        display: flex; align-items: center; gap: .75rem;
        margin: .9rem 0;
    }
    .or-line { flex: 1; height: 1px; background: #f3f4f6; }
    .or-row span {
        font-size: .72rem;
        font-weight: 600;        /* ← lebih tebal dari sebelumnya */
        color: #9ca3af;          /* ← sedikit lebih gelap agar terbaca */
        white-space: nowrap;
        letter-spacing: .02em;
    }

    /* ══════════════════════════════════
       GOOGLE BUTTON — font lebih tebal
    ══════════════════════════════════ */
    .btn-google {
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        width: 100%; height: 46px; padding: 0 1.25rem;
        background: #fff; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: .82rem;
        font-weight: 700;        /* ← lebih tebal dari sebelumnya */
        color: #374151; text-decoration: none; cursor: pointer;
        transition: border-color .2s, background .2s, transform .18s, box-shadow .2s;
        white-space: nowrap;
    }
    .btn-google svg { width: 17px; height: 17px; flex-shrink: 0; display: block; }
    .btn-google:hover {
        border-color: #16a34a; background: #f0fdf4;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(22,163,74,.1);
    }
</style>
@endpush

@section('auth-content')
<form method="POST" action="{{ route('login') }}" id="login-form" novalidate>
    @csrf

    @if($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    {{-- Email / Username --}}
    <div class="lg-group">
        <label class="lg-label" for="login">Email atau Username</label>
        <div class="lg-wrap {{ $errors->has('login') ? 'err' : '' }}">
            <span class="lg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </span>
            <input
                type="text" id="login" name="login"
                value="{{ old('login') }}"
                required autocomplete="off" autofocus
                class="lg-input {{ $errors->has('login') ? 'err' : '' }}"
                placeholder="nama@email.com atau username"
            >
        </div>
        @error('login')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="lg-group">
        <label class="lg-label" for="password">Password</label>
        <div class="lg-wrap {{ $errors->has('password') ? 'err' : '' }}">
            <span class="lg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <input
                type="password" id="password" name="password"
                required autocomplete="current-password"
                class="lg-input has-toggle {{ $errors->has('password') ? 'err' : '' }}"
                placeholder="Masukkan password Anda"
            >
            <button type="button" class="pw-btn" onclick="togglePassword()" aria-label="Tampilkan password">
                <svg id="eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        @error('password')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- ✅ Remember + Lupa Password — 1 baris sejajar --}}
    <div class="remember-forgot-row">
        <div class="remember-left">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Ingat saya</label>
        </div>
        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
        @endif
    </div>

    {{-- Submit --}}
    <button type="submit" id="login-btn" class="btn-masuk">
        <div class="btn-spinner"></div>
        <span class="btn-label">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk ke Akun
        </span>
    </button>

    {{-- Divider --}}
    <div class="or-row">
        <div class="or-line"></div>
        <span>atau lanjutkan dengan</span>
        <div class="or-line"></div>
    </div>

    {{-- Google --}}
    <a href="{{ route('auth.google', ['action' => 'login']) }}" class="btn-google">
        <svg viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Masuk dengan Google
    </a>

</form>
@endsection

@section('auth-footer')
    Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
@endsection

@push('scripts')
@if($recaptchaSiteKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif
<script>
    @if($recaptchaSiteKey)
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('login-btn');
        btn.disabled = true;
        btn.classList.add('loading');
        grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'login' })
            .then(function(token) {
                document.getElementById('recaptcha_token').value = token;
                document.getElementById('login-form').submit();
            })
            .catch(function() {
                btn.disabled = false;
                btn.classList.remove('loading');
            });
    });
    @endif
</script>
@endpush