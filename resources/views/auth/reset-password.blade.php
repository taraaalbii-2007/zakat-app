@extends('layouts.auth')

@section('title', 'Reset Password')
@section('auth-title', 'Reset Password')
@section('auth-subtitle', 'Masukkan password baru untuk akun Anda')

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
    .lg-input.has-toggle { padding-right: 3rem; }

    .lg-wrap:focus-within .lg-icon { color: #16a34a; }
    .lg-wrap.err .lg-icon { color: #f43f5e; }

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

    /* ══════════════════════════════════
       INFO CARD — email target
    ══════════════════════════════════ */
    .rp-email-card {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .75rem .875rem;
        background: #f0fdf4;
        border: 1.5px solid #bbf7d0;
        border-radius: 10px;
        margin-bottom: 1.1rem;
    }
    .rp-email-icon {
        width: 36px; height: 36px;
        background: #dcfce7;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rp-email-icon svg { width: 16px; height: 16px; color: #16a34a; }
    .rp-email-label  { font-size: .7rem; color: #9ca3af; margin-bottom: .1rem; }
    .rp-email-value  {
        font-size: .82rem; font-weight: 700; color: #111827;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .rp-email-note {
        font-size: .68rem; color: #9ca3af; margin-top: .15rem;
        display: flex; align-items: center; gap: .25rem;
    }
    .rp-email-note svg { width: 11px; height: 11px; flex-shrink: 0; }

    /* ══════════════════════════════════
       PASSWORD STRENGTH BAR
    ══════════════════════════════════ */
    .pw-strength-row {
        display: flex; align-items: center; gap: .5rem;
        margin-top: .5rem; margin-bottom: .3rem;
    }
    .pw-strength-track {
        flex: 1; height: 4px; background: #f3f4f6;
        border-radius: 9999px; overflow: hidden;
    }
    .pw-strength-fill {
        height: 100%; width: 0%;
        border-radius: 9999px;
        transition: width .3s, background .3s;
    }
    .pw-strength-label {
        font-size: .7rem; font-weight: 600;
        color: #9ca3af; white-space: nowrap; min-width: 72px; text-align: right;
    }

    .pw-checklist {
        list-style: none; padding: 0; margin: .35rem 0 0;
        display: grid; grid-template-columns: 1fr 1fr; gap: .1rem 0;
    }
    .pw-checklist li {
        display: flex; align-items: center; gap: .35rem;
        font-size: .7rem; color: #9ca3af; margin-bottom: .15rem;
        transition: color .2s;
    }
    .pw-checklist li svg { width: 12px; height: 12px; flex-shrink: 0; }
    .pw-checklist li.ok { color: #16a34a; }

    /* ══════════════════════════════════
       PASSWORD MATCH HINT
    ══════════════════════════════════ */
    .lg-hint {
        display: flex; align-items: center; gap: .3rem;
        font-size: .7rem; margin-top: .4rem;
        color: #9ca3af;
    }
    .lg-hint svg { width: 12px; height: 12px; flex-shrink: 0; }
    .lg-hint.success { color: #16a34a; }
    .lg-hint.error   { color: #f43f5e; }

    /* ══════════════════════════════════
       FLASH ERROR BOX
    ══════════════════════════════════ */
    .flash-box {
        display: flex; align-items: flex-start; gap: .5rem;
        border-radius: 10px; padding: .75rem 1rem;
        font-size: .78rem; margin-bottom: 1rem;
        border-width: 1.5px; border-style: solid;
    }
    .flash-box svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }
    .flash-box.error { background: #fff1f2; border-color: #fecdd3; color: #e11d48; }

    /* ══════════════════════════════════
       BUTTON HIJAU
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
        margin-top: 1.25rem;
    }
    .btn-masuk svg { width: 18px; height: 18px; flex-shrink: 0; display: block; }
    .btn-masuk:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(22,163,74,.42);
    }
    .btn-masuk:active  { transform: translateY(0); }
    .btn-masuk:disabled {
        opacity: .5; cursor: not-allowed; transform: none;
        box-shadow: 0 4px 14px rgba(22,163,74,.15);
    }

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
       BUTTON OUTLINE — kembali ke login
    ══════════════════════════════════ */
    .btn-outline {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; height: 46px; padding: 0 1.25rem;
        background: #fff;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: .85rem; font-weight: 700;
        color: #374151;
        cursor: pointer;
        white-space: nowrap;
        text-decoration: none;
        transition: border-color .2s, background .2s, color .2s, transform .18s, box-shadow .2s;
        -webkit-appearance: none;
        margin-top: .75rem;
        box-sizing: border-box;
    }
    .btn-outline svg { width: 16px; height: 16px; flex-shrink: 0; display: block; }
    .btn-outline:hover {
        border-color: #16a34a;
        background: #f0fdf4;
        color: #15803d;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(22,163,74,.12);
    }
    .btn-outline:active { transform: translateY(0); box-shadow: none; }
</style>
@endpush

@section('auth-content')

{{-- ─── FLASH ERRORS ─── --}}
@if($errors->any() || session('error'))
    <div class="flash-box error" role="alert">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            @if(session('error'))
                {{ session('error') }}
            @else
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            @endif
        </div>
    </div>
@endif


{{-- ─── FORM ─── --}}
<form method="POST" action="{{ route('password.update', $uuid) }}" id="resetForm" novalidate>
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    @if($recaptchaSiteKey ?? false)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    {{-- ── Password Baru ── --}}
    <div class="lg-group">
        <label for="password" class="lg-label">Password Baru</label>
        <div class="lg-wrap {{ $errors->has('password') ? 'err' : '' }}">
            <span class="lg-icon" aria-hidden="true">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <input type="password" id="password" name="password"
                class="lg-input has-toggle {{ $errors->has('password') ? 'err' : '' }}"
                placeholder="Masukkan password baru"
                autocomplete="new-password"
                autofocus
                required
                aria-describedby="pwStrengthDesc">
            <button type="button" class="pw-btn" aria-label="Tampilkan/sembunyikan password"
                onclick="togglePw('password', 'pw-eye-1')">
                <svg id="pw-eye-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>

        @error('password')
            <div class="lg-err" role="alert">
                <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror

        {{-- Strength Bar --}}
        <div class="pw-strength-row" id="pwStrengthDesc" aria-live="polite">
            <div class="pw-strength-track">
                <div class="pw-strength-fill" id="pwStrengthFill"></div>
            </div>
            <span class="pw-strength-label" id="pwStrengthLabel">—</span>
        </div>

        {{-- Checklist --}}
        <ul class="pw-checklist" aria-label="Syarat password">
            <li id="chk-length">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Min. 8 karakter
            </li>
            <li id="chk-upper">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Huruf besar (A–Z)
            </li>
            <li id="chk-lower">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Huruf kecil (a–z)
            </li>
            <li id="chk-number">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Angka (0–9)
            </li>
        </ul>
    </div>{{-- /lg-group password --}}

    {{-- ── Konfirmasi Password ── --}}
    <div class="lg-group">
        <label for="password_confirmation" class="lg-label">Konfirmasi Password Baru</label>
        <div class="lg-wrap {{ $errors->has('password_confirmation') ? 'err' : '' }}">
            <span class="lg-icon" aria-hidden="true">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </span>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="lg-input has-toggle {{ $errors->has('password_confirmation') ? 'err' : '' }}"
                placeholder="Ulangi password baru"
                autocomplete="new-password"
                required
                aria-describedby="pwMatchHint">
            <button type="button" class="pw-btn" aria-label="Tampilkan/sembunyikan konfirmasi password"
                onclick="togglePw('password_confirmation', 'pw-eye-2')">
                <svg id="pw-eye-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>

        @error('password_confirmation')
            <div class="lg-err" role="alert">
                <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror

        {{-- Match hint --}}
        <div class="lg-hint" id="pwMatchHint" style="display:none" aria-live="polite">
            <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"/>
            </svg>
            <span id="pwMatchText">Password cocok</span>
        </div>
    </div>{{-- /lg-group konfirmasi --}}

    {{-- ── Submit ── --}}
    <button type="button" class="btn-masuk" id="submitBtn"
            onclick="handleSubmit()" disabled aria-disabled="true">
        <div class="btn-spinner" aria-hidden="true"></div>
        <span class="btn-label">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Reset Password
        </span>
    </button>

</form>

{{-- ─── BUTTON KEMBALI KE LOGIN ─── --}}
<a href="{{ route('login') }}" class="btn-outline">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
    </svg>
    Kembali ke Halaman Login
</a>

@endsection

@section('auth-footer')
    Sudah ingat password? <a href="{{ route('login') }}">Masuk sekarang</a>
@endsection

@push('scripts')

{{-- ═══════════════════════════════════════════════════════
     reCAPTCHA — dimuat SETELAH splash screen selesai
     ═══════════════════════════════════════════════════════ --}}
@if($recaptchaSiteKey ?? false)
<script>
(function () {
    var SITE_KEY = '{{ $recaptchaSiteKey }}';

    function loadRecaptcha() {
        if (window.__recaptchaLoaded) return;
        window.__recaptchaLoaded = true;
        var s   = document.createElement('script');
        s.src   = 'https://www.google.com/recaptcha/api.js?render=' + SITE_KEY;
        s.async = true;
        s.defer = true;
        document.head.appendChild(s);
    }

    var splashEl = document.getElementById('splash-zakat');
    if (!splashEl || splashEl.classList.contains('sp-hidden')) {
        loadRecaptcha();
        return;
    }

    window.__onSplashHidden = window.__onSplashHidden || [];
    window.__onSplashHidden.push(loadRecaptcha);
    document.addEventListener('splashHidden', loadRecaptcha, { once: true });
})();
</script>
@endif

<script>
/* ════════════════════════════════════════════════════════
   TOGGLE PASSWORD VISIBILITY
════════════════════════════════════════════════════════ */
function togglePw(inputId, iconId) {
    const input    = document.getElementById(inputId);
    const icon     = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                  -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                  a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                  M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59
                  m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                  m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7
                  a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
}

/* ════════════════════════════════════════════════════════
   MAIN LOGIC
════════════════════════════════════════════════════════ */
(function () {
    // ── DOM refs ──────────────────────────────────────────
    const pwInput   = document.getElementById('password');
    const confInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    const fill      = document.getElementById('pwStrengthFill');
    const label     = document.getElementById('pwStrengthLabel');
    const matchHint = document.getElementById('pwMatchHint');
    const matchText = document.getElementById('pwMatchText');
    const resetForm = document.getElementById('resetForm');

    if (!pwInput || !confInput || !submitBtn || !resetForm) return;

    // ── Strength levels ───────────────────────────────────
    const LEVELS = [
        { max: 20,  color: '#f43f5e', text: 'Sangat Lemah' },
        { max: 40,  color: '#f97316', text: 'Lemah'        },
        { max: 60,  color: '#f59e0b', text: 'Cukup'        },
        { max: 80,  color: '#84cc16', text: 'Baik'         },
        { max: 101, color: '#16a34a', text: 'Sangat Baik'  },
    ];

    function calcStrength(pw) {
        let score = 0;
        if (pw.length >= 8)              score += 25;
        if (pw.length >= 12)             score += 10;
        if (/[A-Z]/.test(pw))            score += 20;
        if (/[a-z]/.test(pw))            score += 15;
        if (/\d/.test(pw))               score += 20;
        if (/[^A-Za-z0-9]/.test(pw))     score += 10;
        return Math.min(score, 100);
    }

    function updateStrength(pw) {
        const score = calcStrength(pw);
        const lvl   = LEVELS.find(l => score < l.max);
        if (fill) {
            fill.style.width      = score + '%';
            fill.style.background = lvl.color;
        }
        if (label) {
            label.textContent  = pw.length === 0 ? '—' : lvl.text;
            label.style.color  = pw.length === 0 ? '#9ca3af' : lvl.color;
        }
        setChk('chk-length', pw.length >= 8);
        setChk('chk-upper',  /[A-Z]/.test(pw));
        setChk('chk-lower',  /[a-z]/.test(pw));
        setChk('chk-number', /\d/.test(pw));
        return score;
    }

    function setChk(id, ok) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('ok', ok);
    }

    // ── Match hint ────────────────────────────────────────
    function updateMatch() {
        const pw   = pwInput.value;
        const conf = confInput.value;
        if (!conf) {
            matchHint.style.display = 'none';
            confInput.setCustomValidity('');
            return;
        }
        const ok = pw === conf;
        matchHint.style.display = 'flex';
        matchHint.className     = 'lg-hint ' + (ok ? 'success' : 'error');
        matchHint.querySelector('svg').innerHTML = ok
            ? `<path fill-rule="evenodd" fill="currentColor"
                   d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0
                      011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                   clip-rule="evenodd"/>`
            : `<path fill-rule="evenodd" fill="currentColor"
                   d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414
                      1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293
                      4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                   clip-rule="evenodd"/>`;
        matchText.textContent = ok ? 'Password cocok' : 'Password tidak cocok';
        confInput.setCustomValidity(ok ? '' : 'Password tidak cocok');
    }

    // ── Validate — enable/disable submit ─────────────────
    function validate() {
        const pw    = pwInput.value;
        const conf  = confInput.value;
        const score = calcStrength(pw);
        const valid = pw.length >= 8
            && /[A-Z]/.test(pw)
            && /[a-z]/.test(pw)
            && /\d/.test(pw)
            && pw === conf
            && score >= 40;
        submitBtn.disabled = !valid;
        submitBtn.setAttribute('aria-disabled', String(!valid));
    }

    // ── Event listeners ───────────────────────────────────
    pwInput.addEventListener('input', function () {
        updateStrength(this.value);
        updateMatch();
        validate();
    });
    confInput.addEventListener('input', function () {
        updateMatch();
        validate();
    });

    // Enter key
    [pwInput, confInput].forEach(el => {
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); handleSubmit(); }
        });
    });

    // ── Submit handler ────────────────────────────────────
    window.handleSubmit = async function () {
        if (submitBtn.disabled) return;
        submitBtn.disabled = true;
        submitBtn.setAttribute('aria-disabled', 'true');
        submitBtn.classList.add('loading');

        try {
            @if($recaptchaSiteKey ?? false)
            // Tunggu grecaptcha siap (bisa baru di-load setelah splash)
            await new Promise(function (resolve, reject) {
                let attempts = 0;
                const check = setInterval(function () {
                    if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                        clearInterval(check); resolve();
                    }
                    if (++attempts > 50) {
                        clearInterval(check);
                        reject(new Error('reCAPTCHA tidak siap.'));
                    }
                }, 100);
            });
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'reset_password' });
            document.getElementById('recaptcha_token').value = token;
            @endif

            resetForm.submit();

        } catch (err) {
            console.error('[ResetPassword]', err);
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.setAttribute('aria-disabled', 'false');
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    };

    // ── Init ──────────────────────────────────────────────
    updateStrength('');
    validate();
})();
</script>
@endpush