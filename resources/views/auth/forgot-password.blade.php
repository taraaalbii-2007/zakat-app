@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('auth-title', 'Lupa Password?')
@section('auth-subtitle', 'Masukkan email Anda untuk menerima link reset password')

@push('styles')
<style>
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
       ALERT BOXES
    ══════════════════════════════════ */
    .fp-alert {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        padding: .75rem .875rem;
        border-radius: 10px;
        margin-bottom: 1.1rem;
        font-size: .78rem;
        line-height: 1.5;
    }
    .fp-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: .05rem; }
    .fp-alert-title { font-weight: 600; margin-bottom: .1rem; }
    .fp-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .fp-alert.warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    .fp-alert.danger  { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; }

    /* ══════════════════════════════════
       FORM GROUP
    ══════════════════════════════════ */
    .fp-group { margin-bottom: 1rem; }

    .fp-label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .5rem;
    }

    .fp-wrap { position: relative; }

    .fp-icon {
        position: absolute;
        left: .875rem; top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
        display: flex; align-items: center;
        transition: color .2s;
    }
    .fp-icon svg { width: 16px; height: 16px; display: block; }

    .fp-input {
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
    .fp-input::placeholder { color: #c4cad4; }
    .fp-input:hover  { border-color: #d1d5db; }
    .fp-input:focus  {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    }
    .fp-input.err { border-color: #f43f5e; }
    .fp-input.err:focus { box-shadow: 0 0 0 3px rgba(244,63,94,.1); }

    .fp-input:-webkit-autofill,
    .fp-input:-webkit-autofill:hover,
    .fp-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 999px #ffffff inset !important;
        -webkit-text-fill-color: #111827 !important;
        transition: background-color 9999s ease 0s;
    }

    .fp-wrap:focus-within .fp-icon { color: #16a34a; }
    .fp-wrap.err .fp-icon          { color: #f43f5e; }

    .fp-err {
        display: flex; align-items: center; gap: .3rem;
        font-size: .71rem; color: #f43f5e; margin-top: .4rem;
    }
    .fp-err svg { width: 13px; height: 13px; flex-shrink: 0; }

    /* ══════════════════════════════════
       SUBMIT BUTTON
    ══════════════════════════════════ */
    .btn-kirim {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; height: 48px; padding: 0 1.25rem;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 60%, #15803d 100%);
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: .88rem; font-weight: 700;
        border: none; border-radius: 10px; cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(22,163,74,.35);
        transition: transform .18s, box-shadow .18s, opacity .2s;
        -webkit-appearance: none;
        margin-bottom: .75rem;
    }
    .btn-kirim svg { width: 18px; height: 18px; flex-shrink: 0; display: block; }
    .btn-kirim:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(22,163,74,.42);
    }
    .btn-kirim:active  { transform: translateY(0); }
    .btn-kirim:disabled {
        opacity: .6; cursor: not-allowed; transform: none;
        box-shadow: 0 4px 14px rgba(22,163,74,.2);
    }

    .btn-spinner {
        width: 17px; height: 17px;
        border: 2.5px solid rgba(255,255,255,.35);
        border-top-color: #fff; border-radius: 50%;
        animation: spin .6s linear infinite;
        flex-shrink: 0; display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-kirim.loading .btn-spinner { display: block; }
    .btn-kirim.loading .btn-lbl     { display: none; }
    .btn-lbl { display: flex; align-items: center; gap: .5rem; line-height: 1; }

    /* ══════════════════════════════════
       BACK BUTTON
    ══════════════════════════════════ */
    .btn-back {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; height: 48px; padding: 0 1.25rem;
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: .84rem; font-weight: 600;
        color: #374151;
        text-decoration: none; cursor: pointer;
        white-space: nowrap;
        transition: border-color .2s, background .2s, color .2s, transform .18s, box-shadow .2s;
    }
    .btn-back svg { width: 17px; height: 17px; flex-shrink: 0; display: block; }
    .btn-back:hover {
        border-color: #16a34a;
        background: #f0fdf4;
        color: #15803d;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(22,163,74,.1);
    }
</style>
@endpush

@section('auth-content')
<form method="POST" action="{{ route('password.email') }}" id="forgot-form" novalidate>
    @csrf

    @if($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    {{-- Alert: Rate limit --}}
    @if(session('error') && str_contains(session('error'), 'Mohon tunggu'))
        <div class="fp-alert warning">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <div class="fp-alert-title">Perhatian</div>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Alert: Sukses --}}
    @if(session('success'))
        <div class="fp-alert success">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <div class="fp-alert-title">Berhasil</div>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Alert: Error recaptcha --}}
    @error('recaptcha_token')
        <div class="fp-alert danger">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <div class="fp-alert-title">Error</div>
                {{ $message }}
            </div>
        </div>
    @enderror

    {{-- Email --}}
    <div class="fp-group">
        <label class="fp-label" for="email">Email</label>
        <div class="fp-wrap {{ $errors->has('email') ? 'err' : '' }}">
            <span class="fp-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </span>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', session('email')) }}"
                required
                autocomplete="email"
                autofocus
                class="fp-input {{ $errors->has('email') ? 'err' : '' }}"
                placeholder="nama@email.com"
            >
        </div>
        @error('email')
            <div class="fp-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Submit --}}
    <button
        type="button"
        id="submit-btn"
        class="btn-kirim"
        onclick="handleSubmit()"
        @if(session('error') && str_contains(session('error'), 'Mohon tunggu')) disabled @endif
    >
        <div class="btn-spinner"></div>
        <span class="btn-lbl" id="btn-lbl">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            @if(session('error') && str_contains(session('error'), 'Mohon tunggu'))
                Tunggu...
            @else
                Kirim Link Reset Password
            @endif
        </span>
    </button>

    {{-- Back button --}}
    <a href="{{ route('login') }}" class="btn-back">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Halaman Login
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
    const emailInput = document.getElementById('email');
    const submitBtn  = document.getElementById('submit-btn');
    const btnLbl     = document.getElementById('btn-lbl');

    async function handleSubmit() {
        const email = emailInput.value.trim();
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            emailInput.focus();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.classList.add('loading');

        try {
            @if($recaptchaSiteKey)
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'forgot_password' });
            document.getElementById('recaptcha_token').value = token;
            @endif
            document.getElementById('forgot-form').submit();
        } catch (err) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
            document.getElementById('forgot-form').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        emailInput.focus();

        emailInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); handleSubmit(); }
        });

        emailInput.addEventListener('input', function () {
            this.value = this.value.toLowerCase();
        });

        const errorMsg = @json(session('error', ''));
        if (errorMsg && errorMsg.includes('Mohon tunggu')) {
            const match = errorMsg.match(/(\d+)/);
            if (match) startCooldown(parseInt(match[0]));
        }
    });

    function startCooldown(seconds) {
        let left = seconds;
        submitBtn.disabled = true;

        const tick = () => {
            if (left <= 0) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                btnLbl.innerHTML = `
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"
                        style="width:18px;height:18px;flex-shrink:0;display:block">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Kirim Link Reset Password
                `;
                return;
            }
            const m = Math.floor(left / 60);
            const s = left % 60;
            btnLbl.textContent = m > 0
                ? `Tunggu ${m}m ${String(s).padStart(2, '0')}s`
                : `Tunggu ${s}s`;
            left--;
            setTimeout(tick, 1000);
        };
        tick();
    }
</script>
@endpush