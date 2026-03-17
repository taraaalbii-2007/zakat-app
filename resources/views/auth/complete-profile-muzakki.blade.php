@extends('layouts.auth')

@section('title', 'Lengkapi Profil Muzakki')
@section('auth-title', 'Lengkapi Profil')
@section('auth-subtitle', 'Isi data diri untuk menyelesaikan pendaftaran')

@push('styles')
    <style>
        /* ══════════════════════════════════
           HIDE / OVERRIDE LAYOUT ELEMENTS
        ══════════════════════════════════ */
        .right-brand,
        .right-eyebrow {
            display: none !important;
        }

        .auth-right {
            padding: 2rem 2.5rem !important;
            overflow-y: auto !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
        }

        .right-heading {
            margin-bottom: 1.25rem !important;
        }

        .right-heading h1 {
            font-size: 1.45rem !important;
            font-weight: 800 !important;
            color: #111827 !important;
            letter-spacing: -.03em !important;
            margin-bottom: .3rem !important;
            line-height: 1.2 !important;
        }

        .right-heading p {
            font-size: .8rem !important;
            color: #9ca3af !important;
        }

        .right-footer {
            margin-top: 1rem !important;
            font-size: .78rem !important;
        }

        /* ══════════════════════════════════
           STEP INDICATOR
        ══════════════════════════════════ */
        .cp-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 1.5rem;
        }

        .cp-step {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .7rem;
            font-weight: 600;
            color: #d1d5db;
        }

        .cp-step.done {
            color: #16a34a;
        }

        .cp-step.active {
            color: #111827;
        }

        .cp-step-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .cp-step.done .cp-step-dot {
            background: #f0fdf4;
            border-color: #86efac;
            color: #16a34a;
        }

        .cp-step.active .cp-step-dot {
            background: #16a34a;
            border-color: #16a34a;
            color: #fff;
        }

        .cp-step-line {
            flex: 1;
            height: 2px;
            background: #e5e7eb;
            margin: 0 .4rem;
        }

        .cp-step-line.done {
            background: #86efac;
        }

        /* ══════════════════════════════════
           SECTION TITLE
        ══════════════════════════════════ */
        .cp-section {
            display: flex;
            align-items: center;
            gap: .45rem;
            font-size: .75rem;
            font-weight: 700;
            color: #6b7280;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin: 1.5rem 0 .85rem;
            padding-bottom: .6rem;
            border-bottom: 1.5px solid #f3f4f6;
        }

        .cp-section:first-of-type {
            margin-top: 0;
        }

        .cp-section svg {
            width: 14px;
            height: 14px;
            color: #16a34a;
            flex-shrink: 0;
        }

        /* ══════════════════════════════════
           FORM GROUP
        ══════════════════════════════════ */
        .lg-group {
            margin-bottom: .9rem;
        }

        .lg-label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .4rem;
        }

        .lg-label .req {
            color: #f43f5e;
            margin-left: .1rem;
        }

        .lg-label .opt {
            color: #9ca3af;
            font-weight: 400;
            font-size: .7rem;
            margin-left: .25rem;
        }

        .lg-wrap {
            position: relative;
        }

        .lg-icon {
            position: absolute;
            left: .875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            display: flex;
            align-items: center;
            transition: color .2s;
            z-index: 1;
        }

        .lg-icon svg {
            width: 15px;
            height: 15px;
            display: block;
        }

        .lg-wrap:focus-within .lg-icon {
            color: #16a34a;
        }

        .lg-input {
            display: block;
            width: 100%;
            height: 44px;
            padding: 0 .875rem 0 2.6rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: .82rem;
            font-weight: 400;
            color: #111827;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
            box-sizing: border-box;
        }

        .lg-input.no-icon {
            padding-left: .875rem;
        }

        .lg-input::placeholder {
            color: #c4cad4;
        }

        .lg-input:hover {
            border-color: #d1d5db;
        }

        .lg-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        .lg-input:read-only,
        .lg-input:disabled {
            background: #f9fafb;
            color: #9ca3af;
            cursor: not-allowed;
            border-color: #f3f4f6;
        }

        .lg-input.err {
            border-color: #f43f5e;
        }

        .lg-input.err:focus {
            box-shadow: 0 0 0 3px rgba(244, 63, 94, .1);
        }

        .lg-input:-webkit-autofill,
        .lg-input:-webkit-autofill:hover,
        .lg-input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 999px #ffffff inset !important;
            -webkit-text-fill-color: #111827 !important;
        }

        /* SELECT */
        select.lg-input {
            background-color: #ffffff !important;
            color: #111827 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .875rem center;
            padding-right: 2.25rem;
            padding-left: 2.6rem;
            cursor: pointer;
        }

        select.lg-input:hover {
            border-color: #d1d5db;
        }

        select.lg-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        select.lg-input option[value=""] {
            color: #c4cad4;
        }

        select.lg-input option {
            color: #111827;
            background: #ffffff;
        }

        /* TEXTAREA */
        textarea.lg-input {
            height: auto;
            min-height: 80px;
            padding-top: .65rem;
            padding-bottom: .65rem;
            resize: vertical;
            line-height: 1.5;
        }

        /* GENDER TOGGLE */
        .gender-toggle {
            display: flex;
            gap: .6rem;
        }

        .gender-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            height: 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all .2s;
            user-select: none;
        }

        .gender-btn svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .gender-btn:hover {
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .gender-btn.active-laki {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #1d4ed8;
        }

        .gender-btn.active-perempuan {
            background: #fdf2f8;
            border-color: #ec4899;
            color: #be185d;
        }

        /* PASSWORD TOGGLE */
        .lg-input.has-toggle {
            padding-right: 3rem;
        }

        .pw-btn {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: .3rem;
            color: #9ca3af;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color .2s;
            line-height: 1;
        }

        .pw-btn:hover {
            color: #16a34a;
        }

        .pw-btn svg {
            width: 15px;
            height: 15px;
            display: block;
        }

        /* ══════════════════════════════════
           HELPER TEXTS
        ══════════════════════════════════ */
        .lg-err {
            display: flex;
            align-items: center;
            gap: .3rem;
            font-size: .71rem;
            color: #f43f5e;
            margin-top: .35rem;
        }

        .lg-err svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        .lg-hint {
            font-size: .7rem;
            color: #9ca3af;
            margin-top: .3rem;
        }

        .lg-hint.success {
            color: #16a34a;
            font-weight: 600;
        }

        .lg-hint.error {
            color: #f43f5e;
            font-weight: 600;
        }

        .lg-hint.checking {
            color: #f59e0b;
        }

        /* PASSWORD STRENGTH */
        .pw-strength {
            margin-top: .4rem;
        }

        .pw-strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #f3f4f6;
            margin-bottom: .25rem;
            overflow: hidden;
        }

        .pw-strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width .3s, background .3s;
        }

        /* ══════════════════════════════════
           2-COLUMN GRID
        ══════════════════════════════════ */
        .cp-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
            margin-bottom: .9rem;
        }

        @media (max-width: 520px) {
            .cp-row {
                grid-template-columns: 1fr;
            }
        }

        /* ══════════════════════════════════
           FILE UPLOAD
        ══════════════════════════════════ */
        .cp-file-label {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem .875rem;
            background: #fff;
            border: 1.5px dashed #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            font-size: .78rem;
            color: #6b7280;
            font-weight: 500;
        }

        .cp-file-label:hover {
            border-color: #16a34a;
            background: #f0fdf4;
            color: #15803d;
        }

        .cp-file-label svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            color: #9ca3af;
        }

        .cp-file-label:hover svg {
            color: #16a34a;
        }

        .cp-file-label input[type="file"] {
            display: none;
        }

        /* FOTO PREVIEW */
        .cp-foto-wrap {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: .6rem;
        }

        .cp-foto-thumb {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px dashed #e5e7eb;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            transition: border-color .2s;
        }

        .cp-foto-thumb.has-image {
            border-color: #86efac;
            border-style: solid;
        }

        .cp-foto-thumb svg {
            width: 22px;
            height: 22px;
            color: #d1d5db;
        }

        .cp-foto-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* COMPRESS INFO */
        .cp-compress-info {
            display: none;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            font-weight: 700;
            color: #16a34a;
            margin-top: .4rem;
        }

        .cp-compress-info.show {
            display: flex;
        }

        .cp-compress-info svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }

        /* LEMBAGA INFO */
        .cp-lembaga-info {
            display: none;
            align-items: center;
            gap: .4rem;
            margin-top: .4rem;
            padding: .5rem .75rem;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 8px;
            font-size: .72rem;
            color: #15803d;
            font-weight: 500;
        }

        .cp-lembaga-info.show {
            display: flex;
        }

        .cp-lembaga-info svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }

        /* ALERT */
        .cp-alert {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            background: #fff1f2;
            border: 1.5px solid #fecdd3;
            border-radius: 10px;
            padding: .75rem .875rem;
            font-size: .75rem;
            color: #e11d48;
            margin-bottom: 1.1rem;
        }

        .cp-alert svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            margin-top: .1rem;
        }

        .cp-alert strong {
            display: block;
            font-weight: 700;
            margin-bottom: .3rem;
        }

        .cp-alert ul {
            margin: 0;
            padding-left: 1rem;
        }

        .cp-alert li {
            margin-bottom: .15rem;
        }

        /* DIVIDER */
        .cp-divider {
            height: 1px;
            background: #f3f4f6;
            margin: 1.25rem 0;
        }

        /* SUBMIT BUTTON */
        .btn-masuk {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            width: 100%;
            height: 48px;
            padding: 0 1.25rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 60%, #15803d 100%);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(22, 163, 74, .35);
            transition: transform .18s, box-shadow .18s;
            margin-top: 1.25rem;
        }

        .btn-masuk svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .btn-masuk:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(22, 163, 74, .42);
        }

        .btn-masuk:active {
            transform: translateY(0);
        }

        .btn-masuk:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-spinner {
            width: 17px;
            height: 17px;
            border: 2.5px solid rgba(255, 255, 255, .35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            flex-shrink: 0;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-masuk.loading .btn-spinner {
            display: block;
        }

        .btn-masuk.loading .btn-label {
            display: none;
        }

        .btn-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            line-height: 1;
        }

        /* LOADING OVERLAY */
        .cp-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(2px);
        }

        .cp-overlay.active {
            display: flex;
        }

        .cp-overlay-box {
            background: #fff;
            border-radius: 16px;
            padding: 1.75rem 2rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12);
            min-width: 180px;
        }

        .cp-overlay-spinner {
            width: 36px;
            height: 36px;
            border: 3px solid #dcfce7;
            border-top-color: #16a34a;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            margin: 0 auto .75rem;
        }

        .cp-overlay-box p {
            font-size: .78rem;
            color: #6b7280;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        /* NIK INPUT — monospace */
        #nik {
            font-family: 'Courier New', monospace;
            letter-spacing: .05em;
        }
    </style>
@endpush

@section('auth-content')

    {{-- ─── STEP INDICATOR ─── --}}
    <div class="cp-steps">
        <div class="cp-step done">
            <div class="cp-step-dot">✓</div>
            <span>Email</span>
        </div>
        <div class="cp-step-line done"></div>
        <div class="cp-step active">
            <div class="cp-step-dot">2</div>
            <span>Data Diri</span>
        </div>
        <div class="cp-step-line"></div>
        <div class="cp-step">
            <div class="cp-step-dot">3</div>
            <span>Selesai</span>
        </div>
    </div>

    {{-- ─── ERROR ALERT ─── --}}
    @if ($errors->any())
        <div class="cp-alert">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('complete-profile-muzakki.store', $token) }}" enctype="multipart/form-data"
        id="muzakkiProfileForm" novalidate>
        @csrf
        <input type="hidden" name="pengguna_id" value="{{ $pengguna->id }}">
        @if ($recaptchaSiteKey)
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
        @endif

        {{-- ════════════════════════════════════
         SECTION 1 — DATA AKUN
    ════════════════════════════════════ --}}
        <div class="cp-section">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 7a2 2 0 012 2m4-2a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Data Akun
        </div>

        <div class="cp-row">
            {{-- Email --}}
            <div class="lg-group">
                <label class="lg-label">Email Terdaftar</label>
                <div class="lg-wrap">
                    <span class="lg-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" class="lg-input" value="{{ $pengguna->email }}" readonly>
                </div>
                <span class="lg-hint success">✓ Email sudah diverifikasi</span>
            </div>

            {{-- Username --}}
            <div class="lg-group">
                <label for="username" class="lg-label">Username <span class="req">*</span></label>
                @if ($isGoogleUser)
                    <div class="lg-wrap">
                        <span class="lg-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
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
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="text" name="username" id="username"
                            class="lg-input {{ $errors->has('username') ? 'err' : '' }}" value="{{ old('username') }}"
                            required minlength="6" maxlength="50" pattern="[a-zA-Z0-9_]+" autocomplete="username"
                            placeholder="contoh: ahmad123">
                    </div>
                    @error('username')
                        <div class="lg-err">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <span class="lg-hint" id="usernameHelp">Min. 6 karakter, huruf/angka/underscore</span>
                @endif
            </div>
        </div>

        @if (!$isGoogleUser)
            <div class="cp-row">
                {{-- Password --}}
                <div class="lg-group">
                    <label for="password" class="lg-label">Password <span class="req">*</span></label>
                    <div class="lg-wrap">
                        <span class="lg-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password"
                            class="lg-input has-toggle {{ $errors->has('password') ? 'err' : '' }}" required
                            minlength="8" autocomplete="new-password" placeholder="Min. 8 karakter">
                        <button type="button" class="pw-btn" onclick="togglePw('password','eye1')"
                            aria-label="Tampilkan password">
                            <svg id="eye1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="lg-err">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="pw-strength">
                        <div class="pw-strength-bar">
                            <div class="pw-strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="lg-hint" id="strengthText">Kekuatan password</span>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="lg-group">
                    <label for="password_confirmation" class="lg-label">Konfirmasi Password <span
                            class="req">*</span></label>
                    <div class="lg-wrap">
                        <span class="lg-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="lg-input has-toggle {{ $errors->has('password_confirmation') ? 'err' : '' }}" required
                            minlength="8" autocomplete="new-password" placeholder="Ulangi password">
                        <button type="button" class="pw-btn" onclick="togglePw('password_confirmation','eye2')"
                            aria-label="Tampilkan password">
                            <svg id="eye2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="lg-err">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <span class="lg-hint" id="passwordMatchText"></span>
                </div>
            </div>
        @endif

        <div class="cp-divider"></div>

        {{-- ════════════════════════════════════
         SECTION 2 — DATA DIRI
    ════════════════════════════════════ --}}
        <div class="cp-section">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Data Diri
        </div>

        <div class="cp-row">
            {{-- Nama --}}
            <div class="lg-group">
                <label for="nama" class="lg-label">Nama Lengkap <span class="req">*</span></label>
                <div class="lg-wrap">
                    <span class="lg-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <input type="text" name="nama" id="nama"
                        class="lg-input {{ $errors->has('nama') ? 'err' : '' }}" value="{{ old('nama') }}" required
                        maxlength="255" placeholder="Ahmad Hidayat">
                </div>
                @error('nama')
                    <div class="lg-err">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Telepon --}}
            <div class="lg-group">
                <label for="telepon" class="lg-label">Nomor Telepon <span class="req">*</span></label>
                <div class="lg-wrap">
                    <span class="lg-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </span>
                    <input type="tel" name="telepon" id="telepon"
                        class="lg-input {{ $errors->has('telepon') ? 'err' : '' }}" value="{{ old('telepon') }}"
                        required maxlength="20" placeholder="08xxxxxxxxxx">
                </div>
                @error('telepon')
                    <div class="lg-err">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- Jenis Kelamin --}}
        <div class="lg-group">
            <label class="lg-label">Jenis Kelamin <span class="req">*</span></label>
            <input type="hidden" name="jenis_kelamin" id="jenis_kelamin_input" value="{{ old('jenis_kelamin', '') }}">
            <div class="gender-toggle">
                {{-- Laki-laki --}}
                <button type="button" class="gender-btn" id="btnLaki" onclick="setGender('laki-laki')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="10" cy="7" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 11c-5 0-7 2-7 4v1h14v-1c0-2-2-4-7-4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3l4 4m0 0l-4 4m4-4H14" />
                    </svg>
                    Laki-laki
                </button>
                {{-- Perempuan --}}
                <button type="button" class="gender-btn" id="btnPerempuan" onclick="setGender('perempuan')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 12c-5 0-7 2-7 4v1h14v-1c0-2-2-4-7-4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v5m-2-2h4" />
                    </svg>
                    Perempuan
                </button>
            </div>
            @error('jenis_kelamin')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint" id="genderHint">Pilih salah satu</span>
        </div>

        <div class="cp-row">
            {{-- NIK --}}
            <div class="lg-group">
                <label for="nik" class="lg-label">
                    NIK <span class="opt">(Opsional)</span>
                </label>
                <div class="lg-wrap">
                    <span class="lg-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </span>
                    <input type="text" name="nik" id="nik"
                        class="lg-input {{ $errors->has('nik') ? 'err' : '' }}" value="{{ old('nik') }}"
                        maxlength="16" pattern="[0-9]{16}" inputmode="numeric" placeholder="16 digit NIK KTP">
                </div>
                @error('nik')
                    <div class="lg-err">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <span class="lg-hint" id="nikHelp">16 digit angka sesuai KTP</span>
            </div>

            {{-- Foto Profil (pindah ke grid kanan) --}}
            <div class="lg-group">
                <label class="lg-label">
                    Foto Profil <span class="opt">(Opsional)</span>
                </label>
                <label class="cp-file-label" for="foto">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span id="fileName">Pilih foto…</span>
                    <input type="file" name="foto" id="foto" accept="image/jpeg,image/jpg,image/png">
                </label>
                @error('foto')
                    <div class="lg-err">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
                <span class="lg-hint">JPG/PNG · Maks. 2MB</span>
                <div class="cp-compress-info" id="compressInfo">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span id="compressText"></span>
                </div>
                <div class="cp-foto-wrap">
                    <div class="cp-foto-thumb" id="fotoThumb">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="lg-hint" id="fotoSlotText">Belum ada foto</span>
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="lg-group">
            <label for="alamat" class="lg-label">
                Alamat <span class="req">*</span>
            </label>
            <div class="lg-wrap">
                <span class="lg-icon" style="top: 1rem; transform: none;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                <textarea name="alamat" id="alamat" class="lg-input {{ $errors->has('alamat') ? 'err' : '' }}" required
                    maxlength="500" rows="3" placeholder="Jl. Contoh No. 1, RT 01/RW 02, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
            </div>
            @error('alamat')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint">Alamat lengkap tempat tinggal Anda wajib diisi</span>
        </div>

        <div class="cp-divider"></div>

        {{-- ════════════════════════════════════
         SECTION 3 — PILIH LEMBAGA ZAKAT
    ════════════════════════════════════ --}}
        <div class="cp-section">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Pilih Lembaga Zakat
        </div>

        <div class="lg-group">
            <label for="lembaga_id" class="lg-label">Tempat Lembaga Zakat <span class="req">*</span></label>
            <div class="lg-wrap">
                <span class="lg-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
                <select name="lembaga_id" id="lembaga_id" class="lg-input {{ $errors->has('lembaga_id') ? 'err' : '' }}"
                    required>
                    <option value="">— Pilih Lembaga Zakat —</option>
                    @foreach ($lembagaList as $lembaga)
                        <option value="{{ $lembaga->id }}" data-kota="{{ $lembaga->kota_nama }}"
                            data-provinsi="{{ $lembaga->provinsi_nama }}"
                            {{ old('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                            {{ $lembaga->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('lembaga_id')
                <div class="lg-err">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </div>
            @enderror
            <span class="lg-hint">Pilih lembaga zakat tempat Anda ingin membayar zakat</span>

            <div class="cp-lembaga-info" id="lembagaInfo">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                </svg>
                <span id="lembagaInfoText"></span>
            </div>
        </div>

        {{-- SUBMIT --}}
        <button type="submit" class="btn-masuk" id="submitBtn">
            <div class="btn-spinner"></div>
            <span class="btn-label">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Simpan &amp; Selesaikan Pendaftaran
            </span>
        </button>

    </form>

    {{-- Loading Overlay --}}
    <div class="cp-overlay" id="loadingOverlay">
        <div class="cp-overlay-box">
            <div class="cp-overlay-spinner"></div>
            <p>Menyimpan data…</p>
        </div>
    </div>

@endsection

@section('auth-footer')
    Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
@endsection

@push('scripts')
    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isGoogleUser = {{ $isGoogleUser ? 'true' : 'false' }};

            /* ─── GENDER TOGGLE ─── */
            const genderInput = document.getElementById('jenis_kelamin_input');
            const btnLaki = document.getElementById('btnLaki');
            const btnPerempuan = document.getElementById('btnPerempuan');
            const genderHint = document.getElementById('genderHint');

            window.setGender = function(val) {
                genderInput.value = val;

                btnLaki.classList.remove('active-laki', 'active-perempuan');
                btnPerempuan.classList.remove('active-laki', 'active-perempuan');

                if (val === 'laki-laki') {
                    btnLaki.classList.add('active-laki');
                    genderHint.className = 'lg-hint success';
                    genderHint.textContent = '✓ Laki-laki dipilih';
                } else {
                    btnPerempuan.classList.add('active-perempuan');
                    genderHint.className = 'lg-hint success';
                    genderHint.textContent = '✓ Perempuan dipilih';
                }
            };

            // Restore old value on validation error
            const oldGender = '{{ old('jenis_kelamin', '') }}';
            if (oldGender) setGender(oldGender);

            /* ─── NIK — only digits ─── */
            const nikInput = document.getElementById('nik');
            const nikHelp = document.getElementById('nikHelp');
            if (nikInput) {
                nikInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 16);
                    const len = this.value.length;
                    if (len === 0) {
                        nikHelp.className = 'lg-hint';
                        nikHelp.textContent = '16 digit angka sesuai KTP';
                    } else if (len < 16) {
                        nikHelp.className = 'lg-hint error';
                        nikHelp.textContent = `${len}/16 digit`;
                    } else {
                        nikHelp.className = 'lg-hint success';
                        nikHelp.textContent = '✓ NIK valid (16 digit)';
                    }
                });
            }

            /* ─── PASSWORD TOGGLE ─── */
            window.togglePw = function(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                icon.innerHTML = show ?
                    `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>` :
                    `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            };

            /* ─── USERNAME CHECK ─── */
            const usernameInput = document.getElementById('username');
            if (usernameInput && !isGoogleUser) {
                let timer;
                usernameInput.addEventListener('input', function() {
                    clearTimeout(timer);
                    const val = this.value.trim();
                    const help = document.getElementById('usernameHelp');
                    if (val.length < 6) {
                        help.className = 'lg-hint error';
                        help.textContent = 'Minimal 6 karakter';
                        return;
                    }
                    if (!/^[a-zA-Z0-9_]+$/.test(val)) {
                        help.className = 'lg-hint error';
                        help.textContent = 'Hanya huruf, angka, dan underscore';
                        return;
                    }
                    help.className = 'lg-hint checking';
                    help.textContent = 'Memeriksa ketersediaan…';
                    timer = setTimeout(async () => {
                        try {
                            const res = await fetch(
                                `/api/check-username?username=${encodeURIComponent(val)}&pengguna_id={{ $pengguna->id }}`
                                );
                            const r = await res.json();
                            help.className = r.available ? 'lg-hint success' : 'lg-hint error';
                            help.textContent = (r.available ? '✓ ' : '✗ ') + r.message;
                        } catch {
                            help.className = 'lg-hint error';
                            help.textContent = 'Gagal memeriksa username';
                        }
                    }, 500);
                });
            }

            /* ─── PASSWORD STRENGTH ─── */
            const passwordInput = document.getElementById('password');
            if (passwordInput && !isGoogleUser) {
                const fill = document.getElementById('strengthFill');
                const text = document.getElementById('strengthText');
                passwordInput.addEventListener('input', function() {
                    const pw = this.value;
                    let s = 0;
                    if (pw.length > 0) s++;
                    if (pw.length >= 8) s++;
                    if (/[A-Z]/.test(pw)) s++;
                    if (/[0-9]/.test(pw)) s++;
                    if (/[^A-Za-z0-9]/.test(pw)) s++;
                    const labels = ['', 'Sangat lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];
                    const colors = ['', '#f43f5e', '#f97316', '#f59e0b', '#84cc16', '#16a34a'];
                    fill.style.width = (s * 20) + '%';
                    fill.style.backgroundColor = colors[s] || '#f3f4f6';
                    text.className = pw.length ? (s >= 4 ? 'lg-hint success' : s >= 2 ? 'lg-hint checking' :
                        'lg-hint error') : 'lg-hint';
                    text.textContent = pw.length ? `Kekuatan: ${labels[s]}` : 'Kekuatan password';
                });

                const confirmInput = document.getElementById('password_confirmation');
                const matchText = document.getElementById('passwordMatchText');

                function checkMatch() {
                    if (!confirmInput.value) {
                        matchText.textContent = '';
                        confirmInput.setCustomValidity('');
                        return;
                    }
                    const match = confirmInput.value === passwordInput.value;
                    matchText.className = match ? 'lg-hint success' : 'lg-hint error';
                    matchText.textContent = match ? '✓ Password cocok' : '✗ Password tidak cocok';
                    confirmInput.setCustomValidity(match ? '' : 'tidak cocok');
                }
                confirmInput.addEventListener('input', checkMatch);
                passwordInput.addEventListener('input', () => {
                    if (confirmInput.value) checkMatch();
                });
            }

            /* ─── FOTO PREVIEW ─── */
            const FOTO_ICON_SVG = `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>`;

            function estimateWebpKb(file) {
                const kb = file.size / 1024;
                const ratio = file.type === 'image/png' ? 0.09 : 0.16;
                return Math.max(15, Math.round(kb * ratio));
            }

            const fotoInput = document.getElementById('foto');
            const fotoThumb = document.getElementById('fotoThumb');
            const fileName = document.getElementById('fileName');
            const compressInfo = document.getElementById('compressInfo');
            const compressText = document.getElementById('compressText');
            const fotoSlotText = document.getElementById('fotoSlotText');

            function resetFotoPreview() {
                fotoThumb.innerHTML = FOTO_ICON_SVG;
                fotoThumb.classList.remove('has-image');
                fileName.textContent = 'Pilih foto…';
                compressInfo.classList.remove('show');
                compressText.textContent = '';
                fotoSlotText.textContent = 'Belum ada foto';
            }

            if (fotoInput) {
                fotoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) {
                        resetFotoPreview();
                        return;
                    }
                    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                        alert('Format tidak didukung. Gunakan JPG atau PNG.');
                        this.value = '';
                        resetFotoPreview();
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file melebihi 2MB.');
                        this.value = '';
                        resetFotoPreview();
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = ev => {
                        fotoThumb.innerHTML = `<img src="${ev.target.result}" alt="Foto Profil">`;
                        fotoThumb.classList.add('has-image');
                        fileName.textContent = file.name;
                        const originalKb = Math.round(file.size / 1024);
                        const estimatedKb = estimateWebpKb(file);
                        compressText.textContent =
                            `Dikompresi: ${originalKb}KB → ±${estimatedKb}KB (WebP)`;
                        compressInfo.classList.add('show');
                        fotoSlotText.textContent = '1 foto dipilih';
                    };
                    reader.readAsDataURL(file);
                });
            }

            /* ─── LEMBAGA INFO ─── */
            const lembagaSelect = document.getElementById('lembaga_id');
            const lembagaInfo = document.getElementById('lembagaInfo');
            const lembagaInfoTxt = document.getElementById('lembagaInfoText');

            lembagaSelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (this.value && opt.dataset.kota) {
                    lembagaInfoTxt.textContent = `${opt.dataset.kota}, ${opt.dataset.provinsi}`;
                    lembagaInfo.classList.add('show');
                } else {
                    lembagaInfo.classList.remove('show');
                }
            });
            if (lembagaSelect.value) lembagaSelect.dispatchEvent(new Event('change'));

            /* ─── FORM SUBMIT ─── */
            const form = document.getElementById('muzakkiProfileForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validasi jenis kelamin wajib diisi
                if (!genderInput.value) {
                    genderHint.className = 'lg-hint error';
                    genderHint.textContent = '✗ Pilih jenis kelamin terlebih dahulu';
                    document.querySelector('.gender-toggle').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }

                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                loadingOverlay.classList.add('active');

                // Validasi alamat wajib diisi
                const alamatInput = document.getElementById('alamat');
                if (!alamatInput.value.trim()) {
                    alamatInput.classList.add('err');
                    alamatInput.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    alamatInput.focus();

                    // Tampilkan hint error
                    const alamatHint = document.querySelector('.lg-group:has(#alamat) .lg-hint');
                    if (alamatHint) {
                        alamatHint.className = 'lg-hint error';
                        alamatHint.textContent = '✗ Alamat wajib diisi';
                    }

                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    loadingOverlay.classList.remove('active');
                    return;
                }

                try {
                    @if ($recaptchaSiteKey)
                        const rcToken = await grecaptcha.execute('{{ $recaptchaSiteKey }}', {
                            action: 'complete_profile_muzakki'
                        });
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
