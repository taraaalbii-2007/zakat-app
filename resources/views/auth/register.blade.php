@extends('layouts.auth')

@section('title', 'Register')
@section('auth-title', 'Buat Akun Baru')
@section('auth-subtitle', 'Masukkan email Anda untuk memulai pendaftaran')

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

    .right-heading { margin-bottom: 1.25rem !important; }
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
       ROLE SELECTOR
    ══════════════════════════════════ */
    .role-label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .6rem;
    }

    .role-required {
        color: #f43f5e;
        margin-left: 2px;
    }

    .role-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem;
        margin-bottom: .5rem;
    }

    .role-card {
        position: relative;
        cursor: pointer;
    }
    .role-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0; height: 0;
    }

    .role-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        padding: .9rem .75rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        transition: border-color .2s, background .2s, box-shadow .2s;
        text-align: center;
        cursor: pointer;
    }
    .role-inner:hover {
        border-color: #86efac;
        background: #f9fefb;
    }

    /* checked state */
    .role-card input:checked + .role-inner {
        border-color: #16a34a;
        background: #f0fdf4;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    }

    .role-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
        flex-shrink: 0;
    }
    .role-icon svg { width: 18px; height: 18px; color: #9ca3af; transition: color .2s; }

    .role-card input:checked + .role-inner .role-icon {
        background: #dcfce7;
    }
    .role-card input:checked + .role-inner .role-icon svg {
        color: #16a34a;
    }

    .role-title {
        font-size: .75rem;
        font-weight: 700;
        color: #374151;
        line-height: 1.2;
    }
    .role-desc {
        font-size: .67rem;
        color: #9ca3af;
        line-height: 1.3;
    }
    .role-card input:checked + .role-inner .role-title { color: #15803d; }

    /* role error state */
    .role-grid.role-err .role-inner {
        border-color: #fca5a5;
    }

    /* ══════════════════════════════════
       FORM GROUP
    ══════════════════════════════════ */
    .rg-group { margin-bottom: .9rem; }

    .rg-label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .5rem;
    }

    .rg-wrap { position: relative; }

    .rg-icon {
        position: absolute;
        left: .875rem; top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
        display: flex; align-items: center;
        transition: color .2s;
    }
    .rg-icon svg { width: 16px; height: 16px; display: block; }

    /* INPUT — putih bersih */
    .rg-input {
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
    .rg-input::placeholder { color: #c4cad4; }
    .rg-input:hover  { border-color: #d1d5db; }
    .rg-input:focus  {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    }
    .rg-input.err { border-color: #f43f5e; }
    .rg-input.err:focus { box-shadow: 0 0 0 3px rgba(244,63,94,.1); }

    .rg-input:-webkit-autofill,
    .rg-input:-webkit-autofill:hover,
    .rg-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 999px #ffffff inset !important;
        -webkit-text-fill-color: #111827 !important;
        transition: background-color 9999s ease 0s;
    }

    .rg-wrap:focus-within .rg-icon { color: #16a34a; }
    .rg-wrap.err .rg-icon          { color: #f43f5e; }

    .rg-err {
        display: flex; align-items: center; gap: .3rem;
        font-size: .71rem; color: #f43f5e; margin-top: .4rem;
    }
    .rg-err svg { width: 13px; height: 13px; flex-shrink: 0; }

    /* OTP info box */
    .otp-info {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem .75rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        margin-bottom: .9rem;
    }
    .otp-info svg { width: 14px; height: 14px; color: #16a34a; flex-shrink: 0; }
    .otp-info span { font-size: .72rem; color: #15803d; line-height: 1.4; }

    /* ══════════════════════════════════
       SUBMIT BUTTON
    ══════════════════════════════════ */
    .btn-daftar {
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
    .btn-daftar svg { width: 18px; height: 18px; flex-shrink: 0; display: block; }
    .btn-daftar:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(22,163,74,.42);
    }
    .btn-daftar:active  { transform: translateY(0); }
    .btn-daftar:disabled {
        opacity: .45;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-spinner {
        width: 17px; height: 17px;
        border: 2.5px solid rgba(255,255,255,.35);
        border-top-color: #fff; border-radius: 50%;
        animation: spin .6s linear infinite;
        flex-shrink: 0; display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-daftar.loading .btn-spinner { display: block; }
    .btn-daftar.loading .btn-label   { display: none; }
    .btn-label { display: flex; align-items: center; gap: .5rem; line-height: 1; }

    /* ══════════════════════════════════
       DIVIDER
    ══════════════════════════════════ */
    .or-row {
        display: flex; align-items: center; gap: .75rem;
        margin: .9rem 0;
    }
    .or-line { flex: 1; height: 1px; background: #f3f4f6; }
    .or-row span {
        font-size: .72rem; font-weight: 600;
        color: #9ca3af; white-space: nowrap; letter-spacing: .02em;
    }

    /* ══════════════════════════════════
       GOOGLE BUTTON
    ══════════════════════════════════ */
    .btn-google {
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        width: 100%; height: 46px; padding: 0 1.25rem;
        background: #fff; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: .82rem; font-weight: 700;
        color: #374151; text-decoration: none; cursor: pointer;
        transition: border-color .2s, background .2s, transform .18s, box-shadow .2s;
        white-space: nowrap;
    }
    .btn-google svg { width: 17px; height: 17px; flex-shrink: 0; display: block; }
    .btn-google:hover:not(.btn-google-disabled) {
        border-color: #16a34a; background: #f0fdf4;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(22,163,74,.1);
    }

    /* Google disabled state */
    .btn-google-disabled {
        opacity: .45;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
@endpush

@section('auth-content')
<form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
    @csrf

    @if($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    {{-- Role Selector --}}
    <div class="rg-group">
        <span class="role-label">
            Daftar sebagai
            <span class="role-required">*</span>
        </span>
        <div class="role-grid {{ $errors->has('role') ? 'role-err' : '' }}" id="role-grid">

            {{-- Admin Lembaga --}}
            <label class="role-card">
                <input
                    type="radio"
                    name="role"
                    value="admin_lembaga"
                    id="role-admin"
                    {{ old('role') === 'admin_lembaga' ? 'checked' : '' }}
                >
                <div class="role-inner">
                    <div class="role-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="role-title">Admin Lembaga</div>
                        <div class="role-desc">Kelola zakat lembaga</div>
                    </div>
                </div>
            </label>

            {{-- Muzakki --}}
            <label class="role-card">
                <input
                    type="radio"
                    name="role"
                    value="muzakki"
                    id="role-muzakki"
                    {{ old('role') === 'muzakki' ? 'checked' : '' }}
                >
                <div class="role-inner">
                    <div class="role-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="role-title">Muzakki</div>
                        <div class="role-desc">Bayar & pantau zakat</div>
                    </div>
                </div>
            </label>

        </div>

        {{-- Error role --}}
        @error('role')
            <div class="rg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="rg-group">
        <label class="rg-label" for="email">Email</label>
        <div class="rg-wrap {{ $errors->has('email') ? 'err' : '' }}">
            <span class="rg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </span>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                class="rg-input {{ $errors->has('email') ? 'err' : '' }}"
                placeholder="nama@email.com"
            >
        </div>
        @error('email')
            <div class="rg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Submit --}}
    <button type="submit" id="register-btn" class="btn-daftar" disabled>
        <div class="btn-spinner"></div>
        <span class="btn-label">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Daftar dengan Email
        </span>
    </button>

    {{-- Divider --}}
    <div class="or-row">
        <div class="or-line"></div>
        <span>atau daftar dengan</span>
        <div class="or-line"></div>
    </div>

    {{-- Google — disabled by default, enabled setelah role dipilih --}}
    <a href="#"
       id="btn-google-register"
       class="btn-google btn-google-disabled"
       aria-disabled="true">
        <svg viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Daftar dengan Google
    </a>

</form>
@endsection

@section('auth-footer')
    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
@endsection

@push('scripts')
@if($recaptchaSiteKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
(function () {
    const submitBtn  = document.getElementById('register-btn');
    const googleBtn  = document.getElementById('btn-google-register');
    const radios     = document.querySelectorAll('input[name="role"]');
    const googleBase = '{{ route("auth.google", ["action" => "register"]) }}';

    // ── Cek apakah ada old('role') dari Laravel (validasi gagal / redirect back)
    const hasOldRole = {{ old('role') ? 'true' : 'false' }};
    if (hasOldRole) {
        enableActions('{{ old('role') }}');
    }

    // ── Saat user memilih role
    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            enableActions(this.value);
        });
    });

    function enableActions(role) {
        // Enable submit
        submitBtn.disabled = false;

        // Enable + set href Google button
        const url = new URL(googleBase);
        url.searchParams.set('role', role);
        googleBtn.href = url.toString();
        googleBtn.classList.remove('btn-google-disabled');
        googleBtn.removeAttribute('aria-disabled');
    }

    // ── Auto lowercase email
    document.getElementById('email').addEventListener('input', function () {
        this.value = this.value.toLowerCase();
    });

    @if($recaptchaSiteKey)
    // ── Submit dengan reCAPTCHA
    document.getElementById('register-form').addEventListener('submit', function (e) {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.classList.add('loading');

        grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'register' })
            .then(function (token) {
                document.getElementById('recaptcha_token').value = token;
                document.getElementById('register-form').submit();
            })
            .catch(function () {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                document.getElementById('register-form').submit();
            });
    });
    @endif
})();
</script>
@endpush