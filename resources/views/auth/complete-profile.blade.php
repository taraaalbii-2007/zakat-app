@extends('layouts.auth')

@section('title', 'Lengkapi Profil Admin Lembaga Zakat')
@section('auth-title', 'Lengkapi Profil')
@section('auth-subtitle', 'Isi data diri dan lembaga zakat untuk menyelesaikan pendaftaran')

@push('styles')
<style>
    .right-brand, .right-eyebrow { display: none !important; }
    .auth-right {
        padding: 2rem 2.5rem !important;
        overflow-y: auto !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        max-width: 780px !important;
    }
    .right-heading { margin-bottom: 1.25rem !important; }
    .right-heading h1 {
        font-size: 1.45rem !important; font-weight: 800 !important;
        color: #111827 !important; letter-spacing: -.03em !important;
        margin-bottom: .3rem !important; line-height: 1.2 !important;
    }
    .right-heading p { font-size: .8rem !important; color: #9ca3af !important; }
    .right-footer { margin-top: 1rem !important; font-size: .78rem !important; }

    /* STEP */
    .cp-steps { display: flex; align-items: center; margin-bottom: 1.5rem; }
    .cp-step { display: flex; align-items: center; gap: .4rem; font-size: .7rem; font-weight: 600; color: #d1d5db; }
    .cp-step.done  { color: #16a34a; }
    .cp-step.active{ color: #111827; }
    .cp-step-dot {
        width: 22px; height: 22px; border-radius: 50%;
        background: #f3f4f6; border: 2px solid #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 700; flex-shrink: 0;
    }
    .cp-step.done  .cp-step-dot { background: #f0fdf4; border-color: #86efac; color: #16a34a; }
    .cp-step.active .cp-step-dot { background: #16a34a; border-color: #16a34a; color: #fff; }
    .cp-step-line  { flex: 1; height: 2px; background: #e5e7eb; margin: 0 .4rem; }
    .cp-step-line.done { background: #86efac; }

    /* SECTION */
    .cp-section {
        display: flex; align-items: center; gap: .45rem;
        font-size: .75rem; font-weight: 700; color: #6b7280;
        letter-spacing: .06em; text-transform: uppercase;
        margin: 1.5rem 0 .85rem; padding-bottom: .6rem;
        border-bottom: 1.5px solid #f3f4f6;
    }
    .cp-section:first-of-type { margin-top: 0; }
    .cp-section svg { width: 14px; height: 14px; color: #16a34a; flex-shrink: 0; }
    .cp-section .opt-tag { font-size: .68rem; font-weight: 400; color: #9ca3af; text-transform: none; letter-spacing: 0; margin-left: .2rem; }

    /* FORM GROUP */
    .lg-group { margin-bottom: .9rem; }
    .lg-label { display: block; font-size: .78rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
    .lg-label .req { color: #f43f5e; margin-left: .1rem; }
    .lg-label .opt { color: #9ca3af; font-weight: 400; font-size: .7rem; margin-left: .25rem; }
    .lg-wrap { position: relative; }
    .lg-icon {
        position: absolute; left: .875rem; top: 50%; transform: translateY(-50%);
        color: #9ca3af; pointer-events: none; display: flex; align-items: center; transition: color .2s;
    }
    .lg-icon svg { width: 15px; height: 15px; display: block; }
    .lg-wrap:focus-within .lg-icon { color: #16a34a; }
    .lg-wrap.textarea-wrap .lg-icon { top: .75rem; transform: none; }

    .lg-input {
        display: block; width: 100%; height: 44px;
        padding: 0 .875rem 0 2.6rem;
        background: #fff; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-family: 'Inter', sans-serif; font-size: .82rem; font-weight: 400;
        color: #111827; outline: none;
        transition: border-color .2s, box-shadow .2s;
        -webkit-appearance: none; appearance: none; box-sizing: border-box;
    }
    .lg-input.no-icon { padding-left: .875rem; }
    .lg-input::placeholder { color: #c4cad4; }
    .lg-input:hover { border-color: #d1d5db; }
    .lg-input:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
    .lg-input:read-only { background: #f9fafb; color: #9ca3af; cursor: not-allowed; border-color: #f3f4f6; }
    select.lg-input:disabled { background: #fff; color: #c4cad4; cursor: not-allowed; border-color: #e5e7eb; opacity: 1; }
    select.lg-input:not(:disabled) { background: #fff; color: #111827; }
    .lg-input.err { border-color: #f43f5e; }
    .lg-input.err:focus { box-shadow: 0 0 0 3px rgba(244,63,94,.1); }
    .lg-input:-webkit-autofill,
    .lg-input:-webkit-autofill:hover,
    .lg-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 999px #fff inset !important;
        -webkit-text-fill-color: #111827 !important;
    }

    textarea.lg-input {
        height: auto; padding: .65rem .875rem .65rem 2.6rem;
        resize: vertical; min-height: 78px; line-height: 1.5;
    }
    select.lg-input {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right .875rem center;
        padding-right: 2.25rem; padding-left: .875rem; cursor: pointer;
    }
    select.lg-input:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
    select.lg-input:disabled { background-color: #fff; color: #111827; cursor: default; }

    .lg-input.has-toggle { padding-right: 3rem; }
    .pw-btn {
        position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; padding: .3rem; color: #9ca3af;
        cursor: pointer; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        transition: color .2s; line-height: 1;
    }
    .pw-btn:hover { color: #16a34a; }
    .pw-btn svg { width: 15px; height: 15px; display: block; }

    /* HELPER */
    .lg-err { display: flex; align-items: center; gap: .3rem; font-size: .71rem; color: #f43f5e; margin-top: .35rem; }
    .lg-err svg { width: 12px; height: 12px; flex-shrink: 0; }
    .lg-hint { font-size: .7rem; color: #9ca3af; margin-top: .3rem; }
    .lg-hint.success { color: #16a34a; font-weight: 600; }
    .lg-hint.error   { color: #f43f5e; font-weight: 600; }
    .lg-hint.checking{ color: #f59e0b; }

    /* PASSWORD STRENGTH */
    .pw-strength { margin-top: .4rem; }
    .pw-strength-bar { height: 4px; border-radius: 2px; background: #f3f4f6; margin-bottom: .25rem; overflow: hidden; }
    .pw-strength-fill { height: 100%; width: 0%; border-radius: 2px; transition: width .3s, background .3s; }

    /* GRID */
    .cp-row   { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; margin-bottom: .9rem; }
    .cp-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .85rem; margin-bottom: .9rem; }
    @media (max-width: 580px) { .cp-row, .cp-row-3 { grid-template-columns: 1fr; } }

    /* GENDER TOGGLE */
    .gender-toggle { display: flex; gap: .6rem; }
    .gender-btn {
        flex: 1; display: flex; align-items: center; justify-content: center; gap: .4rem;
        height: 44px; border: 1.5px solid #e5e7eb; border-radius: 10px;
        background: #fff; font-family: 'Inter', sans-serif;
        font-size: .82rem; font-weight: 600; color: #6b7280;
        cursor: pointer; transition: all .2s; user-select: none;
    }
    .gender-btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .gender-btn:hover { border-color: #d1d5db; background: #f9fafb; }
    .gender-btn.active-laki { background: #eff6ff; border-color: #3b82f6; color: #1d4ed8; }
    .gender-btn.active-perempuan { background: #fdf2f8; border-color: #ec4899; color: #be185d; }

    /* FILE UPLOAD */
    .cp-file-label {
        display: flex; align-items: center; gap: .75rem;
        padding: .65rem .875rem; background: #fff;
        border: 1.5px dashed #e5e7eb; border-radius: 10px;
        cursor: pointer; transition: border-color .2s, background .2s;
        font-size: .78rem; color: #6b7280; font-weight: 500;
    }
    .cp-file-label:hover { border-color: #16a34a; background: #f0fdf4; color: #15803d; }
    .cp-file-label:hover svg { color: #16a34a; }
    .cp-file-label svg { width: 16px; height: 16px; flex-shrink: 0; color: #9ca3af; }
    .cp-file-label input[type="file"] { display: none; }

    /* FOTO PROFIL */
    .cp-foto-wrap { display: flex; align-items: center; gap: 1rem; margin-top: .6rem; }
    .cp-foto-thumb {
        width: 60px; height: 60px; border-radius: 50%;
        border: 2px dashed #e5e7eb; background: #f9fafb;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; flex-shrink: 0; transition: border-color .2s;
    }
    .cp-foto-thumb.has-image { border-color: #86efac; border-style: solid; }
    .cp-foto-thumb svg { width: 22px; height: 22px; color: #d1d5db; }
    .cp-foto-thumb img { width: 100%; height: 100%; object-fit: cover; }

    /* GALLERY */
    .cp-gallery { display: grid; grid-template-columns: repeat(5,1fr); gap: .6rem; margin-top: .6rem; }
    @media (max-width: 580px) { .cp-gallery { grid-template-columns: repeat(3,1fr); } }
    @media (max-width: 380px) { .cp-gallery { grid-template-columns: repeat(2,1fr); } }
    .cp-gallery-slot {
        aspect-ratio: 1; border: 1.5px dashed #e5e7eb; border-radius: 10px;
        overflow: hidden; background: #f9fafb;
        display: flex; align-items: center; justify-content: center;
        position: relative; transition: border-color .2s;
    }
    .cp-gallery-slot.filled { border-color: #86efac; border-style: solid; }
    .cp-gallery-slot img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .cp-gallery-slot .sph { text-align: center; color: #d1d5db; }
    .cp-gallery-slot .sph svg { width: 18px; height: 18px; margin-bottom: .15rem; }
    .cp-gallery-slot .sph p { font-size: .62rem; margin: 0; color: #d1d5db; }
    .cp-gallery-slot .slot-remove {
        position: absolute; top: 4px; right: 4px; width: 18px; height: 18px;
        border-radius: 50%; background: rgba(0,0,0,.55); color: #fff;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; line-height: 1; z-index: 2; transition: background .15s;
    }
    .cp-gallery-slot .slot-remove:hover { background: #ef4444; }

    /* COMPRESS BADGE */
    .compress-badge { font-size: .68rem; color: #16a34a; font-weight: 600; margin-top: .3rem; display: none; }
    .compress-badge.visible { display: block; }

    /* ALERT */
    .cp-alert {
        display: flex; align-items: flex-start; gap: .5rem;
        border-radius: 10px; padding: .75rem .875rem;
        font-size: .75rem; margin-bottom: 1.1rem; border-width: 1.5px; border-style: solid;
    }
    .cp-alert svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: .1rem; }
    .cp-alert strong { display: block; font-weight: 700; margin-bottom: .3rem; }
    .cp-alert ul { margin: 0; padding-left: 1rem; }
    .cp-alert li { margin-bottom: .15rem; }
    .cp-alert.danger  { background: #fff1f2; border-color: #fecdd3; color: #e11d48; }
    .cp-alert.warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }

    /* DIVIDER */
    .cp-divider { height: 1px; background: #f3f4f6; margin: 1.25rem 0; }

    /* SUBMIT */
    .btn-masuk {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; height: 48px; padding: 0 1.25rem;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 60%, #15803d 100%);
        color: #fff; font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 700;
        border: none; border-radius: 10px; cursor: pointer;
        box-shadow: 0 4px 14px rgba(22,163,74,.35);
        transition: transform .18s, box-shadow .18s; margin-top: 1.25rem;
    }
    .btn-masuk svg { width: 18px; height: 18px; flex-shrink: 0; }
    .btn-masuk:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(22,163,74,.42); }
    .btn-masuk:active { transform: translateY(0); }
    .btn-masuk:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .btn-spinner {
        width: 17px; height: 17px; border: 2.5px solid rgba(255,255,255,.35);
        border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite;
        flex-shrink: 0; display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-masuk.loading .btn-spinner { display: block; }
    .btn-masuk.loading .btn-label  { display: none; }
    .btn-label { display: flex; align-items: center; gap: .5rem; line-height: 1; }

    /* OVERLAY */
    .cp-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,.4);
        display: none; align-items: center; justify-content: center;
        z-index: 9999; backdrop-filter: blur(2px);
    }
    .cp-overlay.active { display: flex; }
    .cp-overlay-box {
        background: #fff; border-radius: 16px; padding: 1.75rem 2rem;
        text-align: center; box-shadow: 0 8px 30px rgba(0,0,0,.12); min-width: 180px;
    }
    .cp-overlay-spinner {
        width: 36px; height: 36px; border: 3px solid #dcfce7;
        border-top-color: #16a34a; border-radius: 50%;
        animation: spin .6s linear infinite; margin: 0 auto .75rem;
    }
    .cp-overlay-box p { font-size: .78rem; color: #6b7280; margin: 0; font-family: 'Inter', sans-serif; }
</style>
@endpush

@section('auth-content')

{{-- STEP INDICATOR --}}
<div class="cp-steps">
    <div class="cp-step done">
        <div class="cp-step-dot">✓</div>
        <span>Email</span>
    </div>
    <div class="cp-step-line done"></div>
    <div class="cp-step active">
        <div class="cp-step-dot">2</div>
        <span>Profil &amp; Lembaga</span>
    </div>
    <div class="cp-step-line"></div>
    <div class="cp-step">
        <div class="cp-step-dot">3</div>
        <span>Selesai</span>
    </div>
</div>

@if(session('warning'))
<div class="cp-alert warning">
    <svg fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
    <div>{{ session('warning') }}</div>
</div>
@endif

@if($errors->any())
<div class="cp-alert danger">
    <svg fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
    <div>
        <strong>Terdapat kesalahan:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('complete-profile.store', $token) }}"
      enctype="multipart/form-data" id="completeProfileForm" novalidate>
    @csrf
    <input type="hidden" name="pengguna_id" value="{{ $pengguna->id }}">
    @if($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    {{-- ════════════════════════════════
         SECTION 1 — DATA AKUN
    ════════════════════════════════ --}}
    <div class="cp-section">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 7a2 2 0 012 2m4-2a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
        Data Akun
        <span class="opt-tag">(Wajib diisi)</span>
    </div>

    <div class="cp-row">
        <div class="lg-group">
            <label class="lg-label">Email Terdaftar</label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="email" class="lg-input" value="{{ $pengguna->email }}" readonly>
            </div>
            <span class="lg-hint success">Email sudah diverifikasi</span>
        </div>

        <div class="lg-group">
            <label for="username" class="lg-label">Username <span class="req">*</span></label>
            @if($isGoogleUser)
                <div class="lg-wrap">
                    <span class="lg-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input type="text" class="lg-input" value="(Dibuat otomatis)" readonly>
                </div>
                <span class="lg-hint">Dibuat otomatis dari email Anda</span>
            @else
                <div class="lg-wrap">
                    <span class="lg-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input type="text" name="username" id="username"
                           class="lg-input {{ $errors->has('username') ? 'err' : '' }}"
                           value="{{ old('username') }}" required minlength="6" maxlength="50"
                           pattern="[a-zA-Z0-9_]+" autocomplete="username" placeholder="contoh: ahmad123">
                </div>
                @error('username')
                    <div class="lg-err">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <span class="lg-hint" id="usernameHelp">Min. 6 karakter, huruf/angka/underscore</span>
            @endif
        </div>
    </div>

    @if(!$isGoogleUser)
    <div class="cp-row">
        <div class="lg-group">
            <label for="password" class="lg-label">Password <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input type="password" name="password" id="password"
                       class="lg-input has-toggle {{ $errors->has('password') ? 'err' : '' }}"
                       required minlength="8" autocomplete="new-password" placeholder="Min. 8 karakter">
                <button type="button" class="pw-btn" onclick="togglePw('password','eye1')" aria-label="Tampilkan password">
                    <svg id="eye1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
            <div class="pw-strength">
                <div class="pw-strength-bar"><div class="pw-strength-fill" id="strengthFill"></div></div>
                <span class="lg-hint" id="strengthText">Kekuatan password</span>
            </div>
        </div>

        <div class="lg-group">
            <label for="password_confirmation" class="lg-label">Konfirmasi Password <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </span>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="lg-input has-toggle {{ $errors->has('password_confirmation') ? 'err' : '' }}"
                       required minlength="8" autocomplete="new-password" placeholder="Ulangi password">
                <button type="button" class="pw-btn" onclick="togglePw('password_confirmation','eye2')" aria-label="Tampilkan password">
                    <svg id="eye2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint" id="passwordMatchText"></span>
        </div>
    </div>
    @endif

    <div class="cp-divider"></div>

    {{-- ════════════════════════════════
         SECTION 2 — DATA ADMIN LEMBAGA
    ════════════════════════════════ --}}
    <div class="cp-section">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Data Admin Lembaga Zakat
        <span class="opt-tag">(Wajib diisi)</span>
    </div>

    <div class="cp-row">
        {{-- Nama Admin --}}
        <div class="lg-group">
            <label for="admin_nama" class="lg-label">Nama Lengkap Admin <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <input type="text" name="admin_nama" id="admin_nama"
                       class="lg-input {{ $errors->has('admin_nama') ? 'err' : '' }}"
                       value="{{ old('admin_nama') }}" required maxlength="255" placeholder="Ahmad Hidayat">
            </div>
            @error('admin_nama')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Telepon Admin --}}
        <div class="lg-group">
            <label for="admin_telepon" class="lg-label">Nomor Telepon Admin <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </span>
                <input type="tel" name="admin_telepon" id="admin_telepon"
                       class="lg-input {{ $errors->has('admin_telepon') ? 'err' : '' }}"
                       value="{{ old('admin_telepon') }}" required maxlength="20" placeholder="08xxxxxxxxxx">
            </div>
            @error('admin_telepon')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    {{-- ← JENIS KELAMIN ADMIN (full width) --}}
    <div class="lg-group">
        <label class="lg-label">Jenis Kelamin Admin <span class="req">*</span></label>
        <input type="hidden" name="admin_jenis_kelamin" id="admin_jenis_kelamin_input"
               value="{{ old('admin_jenis_kelamin', '') }}">
        <div class="gender-toggle">
            <button type="button" class="gender-btn" id="btnAdminLaki" onclick="setAdminGender('laki-laki')">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="10" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10 11c-5 0-7 2-7 4v1h14v-1c0-2-2-4-7-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 3l4 4m0 0l-4 4m4-4H14"/>
                </svg>
                Laki-laki
            </button>
            <button type="button" class="gender-btn" id="btnAdminPerempuan" onclick="setAdminGender('perempuan')">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 12c-5 0-7 2-7 4v1h14v-1c0-2-2-4-7-4z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v5m-2-2h4"/>
                </svg>
                Perempuan
            </button>
        </div>
        @error('admin_jenis_kelamin')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
        <span class="lg-hint" id="adminGenderHint">Pilih jenis kelamin admin</span>
    </div>

    <div class="cp-row">
        {{-- Email Admin --}}
        <div class="lg-group">
            <label for="admin_email" class="lg-label">Email Admin <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="email" name="admin_email" id="admin_email"
                       class="lg-input {{ $errors->has('admin_email') ? 'err' : '' }}"
                       value="{{ old('admin_email', $pengguna->email) }}" required maxlength="255">
            </div>
            @error('admin_email')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint">Default: {{ $maskedEmail }}</span>
        </div>

        {{-- Foto Admin --}}
        <div class="lg-group">
            <label class="lg-label">Foto Admin <span class="req">*</span></label>
            <label class="cp-file-label" for="admin_foto">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span id="adminFileName">Klik untuk pilih foto admin…</span>
                <input type="file" name="admin_foto" id="admin_foto"
                       accept="image/jpeg,image/jpg,image/png,image/webp">
            </label>
            @error('admin_foto')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <div class="cp-foto-wrap">
                <div class="cp-foto-thumb" id="adminFotoThumb">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <span class="lg-hint">JPG, JPEG, PNG · Maks. 2MB</span>
                    <span class="compress-badge" id="adminCompressBadge">Gambar dikompresi otomatis</span>
                </div>
            </div>
        </div>
    </div>

    <div class="cp-divider"></div>

    {{-- ════════════════════════════════
         SECTION 3 — DATA LEMBAGA ZAKAT
    ════════════════════════════════ --}}
    <div class="cp-section">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Data Lembaga Zakat
        <span class="opt-tag">(Wajib diisi)</span>
    </div>

    <div class="lg-group">
        <label for="nama_lembaga" class="lg-label">Nama Lembaga Zakat <span class="req">*</span></label>
        <div class="lg-wrap">
            <span class="lg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </span>
            <input type="text" name="nama_lembaga" id="nama_lembaga"
                   class="lg-input {{ $errors->has('nama_lembaga') ? 'err' : '' }}"
                   value="{{ old('nama_lembaga') }}" required maxlength="255"
                   placeholder="Lembaga Al-Ikhlas / Yayasan Zakat Sejahtera">
        </div>
        @error('nama_lembaga')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="lg-group">
        <label for="alamat" class="lg-label">Alamat Lengkap <span class="req">*</span></label>
        <div class="lg-wrap textarea-wrap">
            <span class="lg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </span>
            <textarea name="alamat" id="alamat"
                      class="lg-input {{ $errors->has('alamat') ? 'err' : '' }}"
                      required rows="3" placeholder="Jalan, RT/RW, No. Bangunan, dll">{{ old('alamat') }}</textarea>
        </div>
        @error('alamat')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="cp-row">
        <div class="lg-group">
            <label for="provinsi_kode" class="lg-label">Provinsi <span class="req">*</span></label>
            <select name="provinsi_kode" id="provinsi_kode"
                    class="lg-input no-icon {{ $errors->has('provinsi_kode') ? 'err' : '' }}" required>
                <option value="">Pilih Provinsi</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->code }}"
                            {{ old('provinsi_kode') == $province->code ? 'selected' : '' }}>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
            @error('provinsi_kode')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="lg-group">
            <label for="kota_kode" class="lg-label">Kota / Kabupaten <span class="req">*</span></label>
            <select name="kota_kode" id="kota_kode"
                    class="lg-input no-icon {{ $errors->has('kota_kode') ? 'err' : '' }}" required disabled>
                <option value="">Pilih Kota / Kabupaten</option>
            </select>
            @error('kota_kode')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="cp-row">
        <div class="lg-group">
            <label for="kecamatan_kode" class="lg-label">Kecamatan <span class="req">*</span></label>
            <select name="kecamatan_kode" id="kecamatan_kode"
                    class="lg-input no-icon {{ $errors->has('kecamatan_kode') ? 'err' : '' }}" required disabled>
                <option value="">Pilih Kecamatan</option>
            </select>
            @error('kecamatan_kode')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="lg-group">
            <label for="kelurahan_kode" class="lg-label">Kelurahan / Desa <span class="req">*</span></label>
            <select name="kelurahan_kode" id="kelurahan_kode"
                    class="lg-input no-icon {{ $errors->has('kelurahan_kode') ? 'err' : '' }}" required disabled>
                <option value="">Pilih Kelurahan / Desa</option>
            </select>
            @error('kelurahan_kode')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="cp-row">
        <div class="lg-group">
            <label for="kode_pos" class="lg-label">Kode Pos <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </span>
                <input type="text" name="kode_pos" id="kode_pos"
                       class="lg-input {{ $errors->has('kode_pos') ? 'err' : '' }}"
                       value="{{ old('kode_pos') }}" required maxlength="5" pattern="[0-9]{5}" placeholder="00000">
            </div>
            @error('kode_pos')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint">Terisi otomatis saat memilih kelurahan</span>
        </div>
        <div class="lg-group">
            <label for="telepon" class="lg-label">Telepon Lembaga <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </span>
                <input type="tel" name="telepon" id="telepon"
                       class="lg-input {{ $errors->has('telepon') ? 'err' : '' }}"
                       value="{{ old('telepon') }}" required maxlength="20" placeholder="(021) xxxxxxxx">
            </div>
            @error('telepon')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="lg-group">
        <label for="email_lembaga" class="lg-label">Email Lembaga <span class="req">*</span></label>
        <div class="lg-wrap">
            <span class="lg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </span>
            <input type="email" name="email_lembaga" id="email_lembaga"
                   class="lg-input {{ $errors->has('email_lembaga') ? 'err' : '' }}"
                   value="{{ old('email_lembaga') }}" required maxlength="255"
                   placeholder="lembagazazkat@example.com">
        </div>
        @error('email_lembaga')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
        <span class="lg-hint">Bisa sama dengan email admin</span>
    </div>

    <div class="cp-divider"></div>

    {{-- ════════════════════════════════
         SECTION 4 — SEJARAH LEMBAGA
    ════════════════════════════════ --}}
    <div class="cp-section">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Sejarah Lembaga Zakat
        <span class="opt-tag">(Wajib diisi)</span>
    </div>

    <div class="lg-group">
        <label for="sejarah" class="lg-label">Sejarah Singkat <span class="req">*</span></label>
        <div class="lg-wrap textarea-wrap">
            <span class="lg-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
            <textarea name="sejarah" id="sejarah"
                      class="lg-input {{ $errors->has('sejarah') ? 'err' : '' }}"
                      rows="4" required
                      placeholder="Ceritakan sejarah singkat lembaga zakat...">{{ old('sejarah') }}</textarea>
        </div>
        @error('sejarah')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="cp-row-3">
        <div class="lg-group">
            <label for="tahun_berdiri" class="lg-label">Tahun Berdiri <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input type="number" name="tahun_berdiri" id="tahun_berdiri"
                       class="lg-input {{ $errors->has('tahun_berdiri') ? 'err' : '' }}"
                       value="{{ old('tahun_berdiri') }}" required min="1900" max="{{ date('Y') }}"
                       placeholder="{{ date('Y') }}">
            </div>
            @error('tahun_berdiri')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="lg-group">
            <label for="pendiri" class="lg-label">Nama Pendiri <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <input type="text" name="pendiri" id="pendiri"
                       class="lg-input {{ $errors->has('pendiri') ? 'err' : '' }}"
                       value="{{ old('pendiri') }}" required maxlength="255" placeholder="H. Maulana">
            </div>
            @error('pendiri')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="lg-group">
            <label for="kapasitas_jamaah" class="lg-label">Kapasitas <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <input type="number" name="kapasitas_jamaah" id="kapasitas_jamaah"
                       class="lg-input {{ $errors->has('kapasitas_jamaah') ? 'err' : '' }}"
                       value="{{ old('kapasitas_jamaah') }}" required min="1" placeholder="500">
            </div>
            @error('kapasitas_jamaah')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint">Estimasi jumlah penerima zakat</span>
        </div>
    </div>

    <div class="cp-divider"></div>

    {{-- ════════════════════════════════
         SECTION 5 — FOTO LEMBAGA
    ════════════════════════════════ --}}
    <div class="cp-section">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Foto Lembaga Zakat
        <span class="opt-tag">(Wajib min. 1 foto)</span>
    </div>

    <div class="lg-group">
        <label class="lg-label">Upload Foto Lembaga <span class="req">*</span></label>
        <label class="cp-file-label" for="foto_lembaga_trigger">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <span id="lembagaFileName">Klik untuk pilih foto lembaga zakat (maks. 5 foto)…</span>
            <input type="file" id="foto_lembaga_trigger"
                   accept="image/jpeg,image/jpg,image/png,image/webp" multiple>
        </label>
        <div id="hiddenFileInputs"></div>
        @error('foto_lembaga.*')
            <div class="lg-err">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </div>
        @enderror
        <div style="display:flex;align-items:center;gap:.75rem;margin-top:.25rem;flex-wrap:wrap;">
            <span class="lg-hint">Maks. 5 foto · JPG, JPEG, PNG · Maks. 2MB per foto</span>
            <span class="compress-badge" id="lembagaCompressBadge">Gambar dikompresi otomatis</span>
        </div>
        <div class="cp-gallery" id="galleryPreview">
            @for($i = 1; $i <= 5; $i++)
                <div class="cp-gallery-slot" id="slot{{ $i }}">
                    <div class="sph">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>Foto {{ $i }}</p>
                    </div>
                </div>
            @endfor
        </div>
        <span class="lg-hint" id="galleryCountText" style="margin-top:.4rem;"></span>
    </div>

    <button type="submit" class="btn-masuk" id="submitBtn">
        <div class="btn-spinner"></div>
        <span class="btn-label">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan &amp; Selesaikan Registrasi
        </span>
    </button>
</form>

<div class="cp-overlay" id="loadingOverlay">
    <div class="cp-overlay-box">
        <div class="cp-overlay-spinner"></div>
        <p id="overlayText">Mengompresi gambar…</p>
    </div>
</div>

@endsection

@section('auth-footer')
    Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
@endsection

@push('scripts')
@if($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isGoogleUser = {{ $isGoogleUser ? 'true' : 'false' }};
    const MAX_FOTO = 5;

    /* ── GENDER ADMIN TOGGLE ── */
    const adminGenderInput  = document.getElementById('admin_jenis_kelamin_input');
    const btnAdminLaki      = document.getElementById('btnAdminLaki');
    const btnAdminPerempuan = document.getElementById('btnAdminPerempuan');
    const adminGenderHint   = document.getElementById('adminGenderHint');

    window.setAdminGender = function (val) {
        adminGenderInput.value = val;
        btnAdminLaki.classList.remove('active-laki', 'active-perempuan');
        btnAdminPerempuan.classList.remove('active-laki', 'active-perempuan');
        if (val === 'laki-laki') {
            btnAdminLaki.classList.add('active-laki');
            adminGenderHint.className   = 'lg-hint success';
            adminGenderHint.textContent = '✓ Laki-laki dipilih';
        } else {
            btnAdminPerempuan.classList.add('active-perempuan');
            adminGenderHint.className   = 'lg-hint success';
            adminGenderHint.textContent = '✓ Perempuan dipilih';
        }
    };

    // Restore on validation error
    const oldAdminGender = '{{ old('admin_jenis_kelamin', '') }}';
    if (oldAdminGender) setAdminGender(oldAdminGender);

    /* ── COMPRESS HELPER ── */
    async function compressImage(file, maxSizeMB, maxDimension) {
        const options = { maxSizeMB, maxWidthOrHeight: maxDimension, useWebWorker: true, fileType: 'image/webp', initialQuality: 0.82 };
        try {
            const compressed = await imageCompression(file, options);
            const newName = file.name.replace(/\.[^.]+$/, '') + '.webp';
            return new File([compressed], newName, { type: 'image/webp' });
        } catch { return file; }
    }

    function injectFileToInput(name, file) {
        const dt = new DataTransfer(); dt.items.add(file);
        const input = document.createElement('input');
        input.type = 'file'; input.name = name; input.style.display = 'none';
        Object.defineProperty(input, 'files', { value: dt.files });
        return input;
    }

    /* ── PASSWORD TOGGLE ── */
    window.togglePw = function (inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.innerHTML = show
            ? `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
            : `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    };

    /* ── USERNAME CHECK ── */
    const usernameInput = document.getElementById('username');
    if (usernameInput && !isGoogleUser) {
        let timer;
        usernameInput.addEventListener('input', function () {
            clearTimeout(timer);
            const val  = this.value.trim();
            const help = document.getElementById('usernameHelp');
            if (val.length < 6) { help.className = 'lg-hint error'; help.textContent = 'Minimal 6 karakter'; return; }
            if (!/^[a-zA-Z0-9_]+$/.test(val)) { help.className = 'lg-hint error'; help.textContent = 'Hanya huruf, angka, dan underscore'; return; }
            help.className = 'lg-hint checking'; help.textContent = 'Memeriksa ketersediaan…';
            timer = setTimeout(async () => {
                try {
                    const r = await (await fetch(`/api/check-username?username=${encodeURIComponent(val)}&pengguna_id={{ $pengguna->id }}`)).json();
                    help.className = r.available ? 'lg-hint success' : 'lg-hint error';
                    help.textContent = r.message;
                } catch { help.className = 'lg-hint error'; help.textContent = 'Gagal memeriksa username'; }
            }, 500);
        });
    }

    /* ── PASSWORD STRENGTH ── */
    const passwordInput = document.getElementById('password');
    if (passwordInput && !isGoogleUser) {
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        passwordInput.addEventListener('input', function () {
            const pw = this.value; let s = 0;
            if (pw.length > 0) s++; if (pw.length >= 8) s++;
            if (/[A-Z]/.test(pw)) s++; if (/[0-9]/.test(pw)) s++; if (/[^A-Za-z0-9]/.test(pw)) s++;
            const labels = ['','Sangat lemah','Lemah','Cukup','Kuat','Sangat kuat'];
            const colors = ['','#f43f5e','#f97316','#f59e0b','#84cc16','#16a34a'];
            fill.style.width = (s*20)+'%'; fill.style.backgroundColor = colors[s]||'#f3f4f6';
            text.className = pw.length ? (s>=4?'lg-hint success':s>=2?'lg-hint checking':'lg-hint error') : 'lg-hint';
            text.textContent = pw.length ? `Kekuatan: ${labels[s]}` : 'Kekuatan password';
        });
        const confirmInput = document.getElementById('password_confirmation');
        const matchText    = document.getElementById('passwordMatchText');
        function checkMatch() {
            if (!confirmInput.value) { matchText.textContent = ''; confirmInput.setCustomValidity(''); return; }
            const ok = confirmInput.value === passwordInput.value;
            matchText.className = ok ? 'lg-hint success' : 'lg-hint error';
            matchText.textContent = ok ? 'Password cocok' : 'Password tidak cocok';
            confirmInput.setCustomValidity(ok ? '' : 'tidak cocok');
        }
        confirmInput.addEventListener('input', checkMatch);
        passwordInput.addEventListener('input', () => { if (confirmInput.value) checkMatch(); });
    }

    /* ── FOTO ADMIN COMPRESS + PREVIEW ── */
    const adminFotoInput = document.getElementById('admin_foto');
    const adminFotoThumb = document.getElementById('adminFotoThumb');
    const adminFileName  = document.getElementById('adminFileName');
    const adminBadge     = document.getElementById('adminCompressBadge');

    if (adminFotoInput) {
        adminFotoInput.addEventListener('change', async function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) { alert('Format harus berupa gambar'); this.value = ''; return; }
            if (file.size > 2*1024*1024) { alert('Ukuran maksimal 2MB'); this.value = ''; return; }
            adminFileName.textContent = 'Mengompresi…';
            adminBadge.classList.remove('visible');
            const compressed = await compressImage(file, 0.4, 800);
            const dt = new DataTransfer(); dt.items.add(compressed);
            Object.defineProperty(adminFotoInput, 'files', { value: dt.files, configurable: true });
            const reader = new FileReader();
            reader.onload = ev => {
                adminFotoThumb.innerHTML = `<img src="${ev.target.result}" alt="Foto Admin">`;
                adminFotoThumb.classList.add('has-image');
                adminFileName.textContent = compressed.name;
                adminBadge.textContent = `Dikompresi: ${(file.size/1024).toFixed(0)}KB ke ${(compressed.size/1024).toFixed(0)}KB`;
                adminBadge.classList.add('visible');
            };
            reader.readAsDataURL(compressed);
        });
    }

    /* ── FOTO LEMBAGA GALLERY ── */
    const fotoTrigger    = document.getElementById('foto_lembaga_trigger');
    const hiddenInputs   = document.getElementById('hiddenFileInputs');
    const lembagaFileName= document.getElementById('lembagaFileName');
    const lembagaBadge   = document.getElementById('lembagaCompressBadge');
    const galleryCount   = document.getElementById('galleryCountText');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const overlayText    = document.getElementById('overlayText');
    let storedFiles      = [];

    function slotPlaceholder(i) {
        return `<div class="sph"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p>Foto ${i}</p></div>`;
    }

    function renderGallery() {
        for (let i = 1; i <= MAX_FOTO; i++) {
            const slot = document.getElementById(`slot${i}`);
            const file = storedFiles[i-1];
            if (file) {
                const reader = new FileReader();
                reader.onload = ev => {
                    slot.innerHTML = `<img src="${ev.target.result}" alt="Foto ${i}"><button type="button" class="slot-remove" onclick="removeFoto(${i-1})" title="Hapus foto">×</button>`;
                    slot.classList.add('filled');
                };
                reader.readAsDataURL(file);
            } else {
                slot.innerHTML = slotPlaceholder(i);
                slot.classList.remove('filled');
            }
        }
        const count = storedFiles.length;
        lembagaFileName.textContent = count > 0 ? `${count} foto dipilih — klik untuk menambah` : 'Klik untuk pilih foto lembaga zakat (maks. 5 foto)…';
        galleryCount.textContent = count > 0 ? `${count} dari ${MAX_FOTO} slot terisi` : '';
        if (count > 0) {
            const totalOrig = storedFiles.reduce((s,f) => s+(f._origSize||f.size), 0);
            const totalComp = storedFiles.reduce((s,f) => s+f.size, 0);
            lembagaBadge.textContent = `Dikompresi: ${(totalOrig/1024).toFixed(0)}KB ke ${(totalComp/1024).toFixed(0)}KB`;
            lembagaBadge.classList.add('visible');
        } else { lembagaBadge.classList.remove('visible'); }
    }

    window.removeFoto = function (index) { storedFiles.splice(index, 1); renderGallery(); };

    if (fotoTrigger) {
        fotoTrigger.addEventListener('change', async function (e) {
            const newFiles = Array.from(e.target.files);
            if (!newFiles.length) return;
            const available = MAX_FOTO - storedFiles.length;
            if (available <= 0) { alert(`Maksimal ${MAX_FOTO} foto. Hapus foto yang ada.`); this.value = ''; return; }
            const filesToProcess = newFiles.slice(0, available);
            if (newFiles.length > available) alert(`Hanya ${available} slot tersisa. ${newFiles.length - available} foto diabaikan.`);
            const valid = filesToProcess.filter(f => f.type.startsWith('image/') && f.size <= 2*1024*1024);
            if (!valid.length) { this.value = ''; return; }
            loadingOverlay.classList.add('active');
            overlayText.textContent = `Mengompresi ${valid.length} gambar…`;
            for (const f of valid) {
                const compressed = await compressImage(f, 0.5, 1280);
                compressed._origSize = f.size;
                storedFiles.push(compressed);
            }
            loadingOverlay.classList.remove('active');
            this.value = '';
            renderGallery();
        });
    }

    function buildHiddenInputs() {
        hiddenInputs.innerHTML = '';
        storedFiles.forEach(file => hiddenInputs.appendChild(injectFileToInput('foto_lembaga[]', file)));
    }

    /* ── WILAYAH CASCADE ── */
    const provinsiSelect  = document.getElementById('provinsi_kode');
    const kotaSelect      = document.getElementById('kota_kode');
    const kecamatanSelect = document.getElementById('kecamatan_kode');
    const kelurahanSelect = document.getElementById('kelurahan_kode');
    const kodePosInput    = document.getElementById('kode_pos');

    function fillSelect(el, data, placeholder) {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(item => el.add(new Option(item.name, item.code)));
    }
    function resetSelect(el, placeholder) { el.innerHTML = `<option value="">${placeholder}</option>`; el.disabled = true; }
    function setLoading(el, msg)           { el.innerHTML = `<option value="">${msg}</option>`; el.disabled = true; }

    provinsiSelect.addEventListener('change', async function () {
        resetSelect(kotaSelect,'Pilih Kota / Kabupaten'); resetSelect(kecamatanSelect,'Pilih Kecamatan'); resetSelect(kelurahanSelect,'Pilih Kelurahan / Desa'); kodePosInput.value = '';
        if (!this.value) return; setLoading(kotaSelect,'Memuat…');
        try { const r = await (await fetch(`/api/wilayah/cities/${this.value}`)).json(); if (r.success&&r.data) { fillSelect(kotaSelect,r.data,'Pilih Kota / Kabupaten'); kotaSelect.disabled=false; } else resetSelect(kotaSelect,'Pilih Kota / Kabupaten'); } catch { resetSelect(kotaSelect,'Gagal memuat'); }
    });
    kotaSelect.addEventListener('change', async function () {
        resetSelect(kecamatanSelect,'Pilih Kecamatan'); resetSelect(kelurahanSelect,'Pilih Kelurahan / Desa'); kodePosInput.value='';
        if (!this.value) return; setLoading(kecamatanSelect,'Memuat…');
        try { const r = await (await fetch(`/api/wilayah/districts/${this.value}`)).json(); if (r.success&&r.data) { fillSelect(kecamatanSelect,r.data,'Pilih Kecamatan'); kecamatanSelect.disabled=false; } else resetSelect(kecamatanSelect,'Pilih Kecamatan'); } catch { resetSelect(kecamatanSelect,'Gagal memuat'); }
    });
    kecamatanSelect.addEventListener('change', async function () {
        resetSelect(kelurahanSelect,'Pilih Kelurahan / Desa'); kodePosInput.value='';
        if (!this.value) return; setLoading(kelurahanSelect,'Memuat…');
        try { const r = await (await fetch(`/api/wilayah/villages/${this.value}`)).json(); if (r.success&&r.data) { fillSelect(kelurahanSelect,r.data,'Pilih Kelurahan / Desa'); kelurahanSelect.disabled=false; } else resetSelect(kelurahanSelect,'Pilih Kelurahan / Desa'); } catch { resetSelect(kelurahanSelect,'Gagal memuat'); }
    });
    kelurahanSelect.addEventListener('change', async function () {
        kodePosInput.value='';
        if (!this.value) return;
        try { const r = await (await fetch(`/api/wilayah/postal-code/${this.value}`)).json(); if (r.success&&r.data?.postal_code) kodePosInput.value=r.data.postal_code; } catch {}
    });

    /* ── FORM SUBMIT ── */
    const form      = document.getElementById('completeProfileForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Validasi jenis kelamin admin
        if (!adminGenderInput.value) {
            adminGenderHint.className   = 'lg-hint error';
            adminGenderHint.textContent = '✗ Pilih jenis kelamin admin terlebih dahulu';
            document.querySelector('.gender-toggle').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (storedFiles.length === 0) {
            alert('Harap upload minimal 1 foto lembaga zakat.');
            return;
        }

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        loadingOverlay.classList.add('active');
        overlayText.textContent = 'Menyimpan data…';

        buildHiddenInputs();

        try {
            @if($recaptchaSiteKey)
            const rcToken = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'complete_profile' });
            document.getElementById('recaptcha_token').value = rcToken;
            @endif
            form.submit();
        } catch {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            loadingOverlay.classList.remove('active');
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });

    window.addEventListener('load', () => loadingOverlay.classList.remove('active'));
});
</script>
@endpush