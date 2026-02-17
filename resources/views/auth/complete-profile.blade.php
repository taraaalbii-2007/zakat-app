@extends('layouts.auth')

@section('title', 'Lengkapi Profil')

@push('styles')
<style>
    /* =============================================
       OVERRIDE LAYOUT AUTH — lebarkan container
       ============================================= */
    .w-full.max-w-md.relative.z-10 {
        max-width: 960px !important;
    }

    /* Sembunyikan card header dari layout auth */
    .glass-effect.rounded-3xl > .mb-8 {
        display: none !important;
    }

    /* Glass card — padding nol, overflow hidden untuk header */
    .glass-effect.rounded-3xl {
        padding: 0 !important;
        border-radius: 1.5rem !important;
        overflow: hidden !important;
    }

    /* =============================================
       HEADER PROFIL (gradient hijau sesuai tema)
       ============================================= */
    .profile-header {
        background: linear-gradient(135deg, #2d6936 0%, #1e5223 50%, #7cb342 100%);
        background-size: 200% 200%;
        animation: headerShift 8s ease infinite;
        padding: 2.5rem 2.5rem 2.75rem;
        position: relative;
        overflow: hidden;
    }

    @keyframes headerShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30px; left: -20px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .profile-header .hdr-content {
        position: relative; z-index: 1;
        display: flex; align-items: center; gap: 1.25rem;
    }

    .hdr-icon-wrap {
        width: 56px; height: 56px;
        background: rgba(255,255,255,0.15);
        border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }

    .hdr-icon-wrap svg { width: 1.75rem; height: 1.75rem; color: #fff; }

    .profile-header h2 {
        color: #fff; font-size: 1.5rem; font-weight: 700;
        margin: 0 0 0.2rem; line-height: 1.3;
    }

    .profile-header > .hdr-content + p,
    .profile-header .hdr-sub {
        color: rgba(255,255,255,0.7); font-size: 0.8rem; margin: 0;
    }

    /* Step badges */
    .step-badges {
        position: relative; z-index: 1;
        display: flex; gap: 0.5rem; margin-top: 1.25rem;
    }

    .step-badge {
        font-size: 0.68rem; font-weight: 600;
        padding: 0.22rem 0.6rem; border-radius: 999px;
        background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.55);
        border: 1px solid rgba(255,255,255,0.15);
    }

    .step-badge.active {
        background: rgba(255,255,255,0.22); color: #fff;
        border-color: rgba(255,255,255,0.3);
    }

    /* =============================================
       FORM BODY
       ============================================= */
    .profile-form-body {
        padding: 2rem 2.5rem 2.5rem;
    }

    /* =============================================
       SECTION TITLE — hijau tema
       ============================================= */
    .section-title {
        font-size: 0.95rem; font-weight: 600;
        color: #2d6936;
        margin-bottom: 1.125rem; margin-top: 2rem;
        padding-bottom: 0.55rem;
        border-bottom: 2px solid #dcedc8;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .section-title:first-of-type { margin-top: 0; }

    .section-title .s-icon {
        width: 1.1rem; height: 1.1rem;
        color: #7cb342; flex-shrink: 0;
    }

    .section-title .opt-tag {
        font-weight: 400; color: #9e9e9e; font-size: 0.7rem; margin-left: 0.2rem;
    }

    /* =============================================
       GRID
       ============================================= */
    .form-row {
        display: grid; grid-template-columns: 1fr;
        gap: 1.125rem; margin-bottom: 1.125rem;
    }

    .form-row.col-2 { grid-template-columns: repeat(2, 1fr); }
    .form-row.col-3 { grid-template-columns: repeat(3, 1fr); }

    /* =============================================
       LABEL / INPUT / SELECT / TEXTAREA
       ============================================= */
    .form-label {
        display: block; font-weight: 500;
        color: #424242; margin-bottom: 0.35rem; font-size: 0.8rem;
    }

    .form-label .req { color: #f44336; margin-left: 0.15rem; }

    .form-control {
        width: 100%; padding: 0.6rem 0.875rem;
        border: 1.5px solid #e0e0e0; border-radius: 0.6rem;
        font-size: 0.8rem; font-family: 'Poppins', sans-serif;
        color: #212121; background: #fafafa;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #2d6936;
        box-shadow: 0 0 0 3px rgba(45, 105, 54, 0.13);
        background: #fff;
    }

    .form-control:disabled {
        background: #f5f5f5; color: #9e9e9e; cursor: not-allowed;
    }

    .form-control.is-invalid { border-color: #f44336; }
    .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(244,67,54,0.12); }

    textarea.form-control { resize: vertical; min-height: 78px; }

    select.form-control {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239e9e9e' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
    }

    .invalid-feedback {
        display: block; color: #f44336;
        font-size: 0.72rem; margin-top: 0.22rem;
    }

    .form-text {
        display: block; color: #9e9e9e;
        font-size: 0.7rem; margin-top: 0.22rem;
    }

    .form-text.success { color: #4caf50; }
    .form-text.error { color: #f44336; }

    /* =============================================
       PASSWORD STRENGTH INDICATOR
       ============================================= */
    .password-strength {
        margin-top: 0.3rem;
    }

    .strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #e0e0e0;
        margin-bottom: 0.25rem;
        overflow: hidden;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        transition: width 0.3s, background-color 0.3s;
    }

    .strength-text {
        font-size: 0.7rem;
    }

    /* =============================================
       FILE INPUT
       ============================================= */
    .file-wrap input[type="file"] {
        width: 100%; padding: 0.6rem 0.875rem;
        border: 1.5px dashed #bdbdbd; border-radius: 0.6rem;
        font-size: 0.75rem; font-family: 'Poppins', sans-serif;
        color: #616161; background: #fafafa; cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        box-sizing: border-box;
    }

    .file-wrap input[type="file"]:hover {
        border-color: #7cb342; background: #f1f8e9;
    }

    .file-wrap input[type="file"].is-invalid { border-color: #f44336; }

    /* =============================================
       FOTO PREVIEW — single
       ============================================= */
    .foto-single {
        margin-top: 0.7rem;
        width: 96px; height: 96px;
        border: 2px dashed #bdbdbd; border-radius: 0.7rem;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; background: #f5f5f5;
        transition: border-color 0.2s;
    }

    .foto-single:hover { border-color: #7cb342; }
    .foto-single img { width: 100%; height: 100%; object-fit: cover; }

    .foto-single .ph { text-align: center; color: #bdbdbd; }
    .foto-single .ph svg { width: 1.4rem; height: 1.4rem; margin-bottom: 0.12rem; }
    .foto-single .ph p { font-size: 0.62rem; margin: 0; }

    /* =============================================
       FOTO GALLERY — masjid
       ============================================= */
    .foto-gallery {
        display: grid; grid-template-columns: repeat(5, 1fr);
        gap: 0.6rem; margin-top: 0.7rem;
    }

    .foto-gallery-item {
        aspect-ratio: 1;
        border: 2px dashed #bdbdbd; border-radius: 0.6rem;
        overflow: hidden; background: #f5f5f5;
        display: flex; align-items: center; justify-content: center;
        position: relative; transition: border-color 0.2s;
    }

    .foto-gallery-item:hover { border-color: #7cb342; }
    .foto-gallery-item img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }

    .foto-gallery-item .sph { text-align: center; color: #bdbdbd; }
    .foto-gallery-item .sph svg { width: 1.2rem; height: 1.2rem; margin-bottom: 0.1rem; }
    .foto-gallery-item .sph p { font-size: 0.62rem; margin: 0; }

    /* =============================================
       ALERT
       ============================================= */
    .alert-box {
        padding: 0.7rem 0.9rem; border-radius: 0.6rem;
        margin-bottom: 1.25rem;
        display: flex; align-items: flex-start; gap: 0.6rem;
        font-size: 0.78rem;
    }

    .alert-box .ai { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: 0.08rem; }
    .alert-box .at { font-weight: 600; display: block; margin-bottom: 0.12rem; }
    .alert-box ul { margin: 0.25rem 0 0; padding-left: 1.1rem; }

    .alert-box.success { background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; }
    .alert-box.danger  { background: #ffebee; border: 1px solid #ef9a9a; color: #b71c1c; }
    .alert-box.warning { background: #fff3e0; border: 1px solid #ffcc80; color: #e65100; }

    /* =============================================
       DIVIDER
       ============================================= */
    .section-divider { height: 1px; background: #eeeeee; margin: 1.75rem 0; }

    /* =============================================
       SUBMIT — hijau tema
       ============================================= */
    .btn-submit {
        width: 100%; margin-top: 2rem;
        padding: 0.78rem 1.5rem; border: none; border-radius: 0.75rem;
        font-size: 0.88rem; font-weight: 600; font-family: 'Poppins', sans-serif;
        color: #fff;
        background: linear-gradient(135deg, #2d6936 0%, #1e5223 100%);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
        box-shadow: 0 4px 12px rgba(45,105,54,0.3);
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(45,105,54,0.4);
    }

    .btn-submit:active:not(:disabled) { transform: translateY(0); }
    .btn-submit:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }
    .btn-submit svg { width: 1.1rem; height: 1.1rem; }

    /* =============================================
       LOADING OVERLAY
       ============================================= */
    .loading-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        display: none; align-items: center; justify-content: center;
        z-index: 9999;
    }

    .loading-overlay.active { display: flex; }

    .loading-box {
        background: #fff; border-radius: 1rem;
        padding: 2rem 2.5rem; text-align: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .spinner {
        width: 38px; height: 38px;
        border: 3.5px solid #dcedc8; border-top-color: #2d6936;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin: 0 auto 0.65rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .loading-box p { font-size: 0.78rem; color: #616161; margin: 0; font-family: 'Poppins', sans-serif; }

    /* =============================================
       RECAPTCHA STYLE
       ============================================= */
    @if ($recaptchaSiteKey)
    .g-recaptcha-badge {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
        border-radius: 4px !important;
    }
    @endif

    /* =============================================
       RESPONSIVE
       ============================================= */
    @media (max-width: 768px) {
        .w-full.max-w-md.relative.z-10 { max-width: 100% !important; }
        .profile-header { padding: 1.75rem 1.25rem 2rem; }
        .profile-form-body { padding: 1.5rem 1.25rem 1.75rem; }
        .form-row.col-2, .form-row.col-3 { grid-template-columns: 1fr; }
        .foto-gallery { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 480px) {
        .profile-header { padding: 1.25rem 1rem 1.5rem; }
        .profile-header h2 { font-size: 1.125rem; }
        .profile-form-body { padding: 1.125rem 0.875rem 1.25rem; }
        .foto-gallery { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('card-title', '')
@section('card-subtitle', '')

@section('content')

<!-- =============================================
     HEADER
     ============================================= -->
<div class="profile-header">
    <div class="hdr-content">
        <div class="hdr-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h2>Lengkapi Profil Anda</h2>
            <p class="hdr-sub">Isi semua informasi di bawah untuk menyelesaikan pendaftaran</p>
        </div>
    </div>
    <div class="step-badges">
        <span class="step-badge">✓ Email Verified</span>
        <span class="step-badge active">● Profil & Masjid</span>
        <span class="step-badge">○ Selesai</span>
    </div>
</div>

<!-- =============================================
     FORM BODY
     ============================================= -->
<div class="profile-form-body">

    @if(session('warning'))
    <div class="alert-box warning">
        <svg class="ai" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div>{{ session('warning') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-box danger">
        <svg class="ai" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div>
            <span class="at">Terdapat kesalahan:</span>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    <form method="POST"
          action="{{ route('complete-profile.store', $token) }}"
          enctype="multipart/form-data"
          id="completeProfileForm">
        @csrf
        <input type="hidden" name="pengguna_id" value="{{ $pengguna->id }}">
        
        <!-- reCAPTCHA hidden input -->
        @if ($recaptchaSiteKey)
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
        @endif

        <!-- =====================
             SECTION 1: Data Akun (WAJIB untuk semua user)
             ==================== -->
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2 2 2 0 01-2 2m0-6a2 2 0 00-2 2m0 0a2 2 0 002 2M10 5a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h4z"/></svg>
            Data Akun <small class="opt-tag">(Wajib diisi)</small>
        </div>

        <div class="form-row col-2">
            <div>
                <label class="form-label">Email Terdaftar</label>
                <input type="email" class="form-control" value="{{ $pengguna->email }}" readonly>
                <span class="form-text success">✓ Email sudah diverifikasi</span>
            </div>
            <div>
                <label for="username" class="form-label">Username <span class="req">*</span></label>
                @if($isGoogleUser)
                <input type="text" class="form-control" value="(Akan dibuat otomatis)" readonly>
                <span class="form-text">Username akan dibuat otomatis dari email Anda</span>
                @else
                <input type="text" name="username" id="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" required minlength="6" maxlength="50"
                       pattern="[a-zA-Z0-9_]+" autocomplete="username"
                       placeholder="contoh: ahmad123">
                @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text" id="usernameHelp">Minimal 6 karakter, hanya huruf, angka, dan underscore</span>
                @endif
            </div>
        </div>

        @if(!$isGoogleUser)
        <div class="form-row col-2">
            <div>
                <label for="password" class="form-label">Password <span class="req">*</span></label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required minlength="8" autocomplete="new-password"
                       placeholder="Minimal 8 karakter">
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                
                <!-- Password Strength Indicator -->
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="passwordStrengthFill"></div>
                    </div>
                    <div class="strength-text" id="passwordStrengthText">Kekuatan password: lemah</div>
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="req">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       required minlength="8" autocomplete="new-password"
                       placeholder="Ulangi password yang sama">
                @error('password_confirmation')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text" id="passwordMatchText"></span>
            </div>
        </div>
        @endif

        <div class="section-divider"></div>

        <!-- ========================
             SECTION 2: Admin Masjid (WAJIB)
             ======================== -->
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Data Admin Masjid <small class="opt-tag">(Wajib diisi)</small>
        </div>

        <div class="form-row col-2">
            <div>
                <label for="admin_nama" class="form-label">Nama Lengkap Admin <span class="req">*</span></label>
                <input type="text" name="admin_nama" id="admin_nama"
                       class="form-control @error('admin_nama') is-invalid @enderror"
                       value="{{ old('admin_nama') }}" required maxlength="255"
                       placeholder="Contoh: Ahmad Hidayat">
                @error('admin_nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="admin_telepon" class="form-label">Nomor Telepon Admin <span class="req">*</span></label>
                <input type="tel" name="admin_telepon" id="admin_telepon"
                       class="form-control @error('admin_telepon') is-invalid @enderror"
                       value="{{ old('admin_telepon') }}" required maxlength="20"
                       placeholder="08xxxxxxxxxx">
                @error('admin_telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row col-2">
            <div>
                <label for="admin_email" class="form-label">Email Admin <span class="req">*</span></label>
                <input type="email" name="admin_email" id="admin_email"
                       class="form-control @error('admin_email') is-invalid @enderror"
                       value="{{ old('admin_email', $pengguna->email) }}" required maxlength="255">
                @error('admin_email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text" id="adminEmailHelp">Default: {{ $maskedEmail }}</span>
            </div>
            <div>
                <label for="admin_foto" class="form-label">Foto Admin <span class="req">*</span></label>
                <div class="file-wrap">
                    <input type="file" name="admin_foto" id="admin_foto"
                           class="@error('admin_foto') is-invalid @enderror"
                           accept="image/jpeg,image/jpg,image/png" required>
                </div>
                @error('admin_foto')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text">JPG, JPEG, PNG · Max 2MB</span>
            </div>
        </div>

        <div class="foto-single" id="adminFotoPreview">
            <div class="ph">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <p>Preview</p>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- =====================
             SECTION 3: Data Masjid (WAJIB)
             ===================== -->
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>
            Data Masjid <small class="opt-tag">(Wajib diisi)</small>
        </div>

        <div class="form-row">
            <div>
                <label for="nama_masjid" class="form-label">Nama Masjid <span class="req">*</span></label>
                <input type="text" name="nama_masjid" id="nama_masjid"
                       class="form-control @error('nama_masjid') is-invalid @enderror"
                       value="{{ old('nama_masjid') }}" required maxlength="255"
                       placeholder="Contoh: Masjid Al-Ikhlas">
                @error('nama_masjid')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="alamat" class="form-label">Alamat Lengkap <span class="req">*</span></label>
                <textarea name="alamat" id="alamat"
                          class="form-control @error('alamat') is-invalid @enderror"
                          required rows="3"
                          placeholder="Jalan, RT/RW, No. Rumah, dll">{{ old('alamat') }}</textarea>
                @error('alamat')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row col-2">
            <div>
                <label for="provinsi_kode" class="form-label">Provinsi <span class="req">*</span></label>
                <select name="provinsi_kode" id="provinsi_kode"
                        class="form-control @error('provinsi_kode') is-invalid @enderror" required>
                    <option value="">Pilih Provinsi</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->code }}" {{ old('provinsi_kode') == $province->code ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
                @error('provinsi_kode')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="kota_kode" class="form-label">Kota / Kabupaten <span class="req">*</span></label>
                <select name="kota_kode" id="kota_kode"
                        class="form-control @error('kota_kode') is-invalid @enderror" required disabled>
                    <option value="">Pilih Kota / Kabupaten</option>
                </select>
                @error('kota_kode')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row col-2">
            <div>
                <label for="kecamatan_kode" class="form-label">Kecamatan <span class="req">*</span></label>
                <select name="kecamatan_kode" id="kecamatan_kode"
                        class="form-control @error('kecamatan_kode') is-invalid @enderror" required disabled>
                    <option value="">Pilih Kecamatan</option>
                </select>
                @error('kecamatan_kode')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="kelurahan_kode" class="form-label">Kelurahan / Desa <span class="req">*</span></label>
                <select name="kelurahan_kode" id="kelurahan_kode"
                        class="form-control @error('kelurahan_kode') is-invalid @enderror" required disabled>
                    <option value="">Pilih Kelurahan / Desa</option>
                </select>
                @error('kelurahan_kode')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row col-2">
            <div>
                <label for="kode_pos" class="form-label">Kode Pos <span class="req">*</span></label>
                <input type="text" name="kode_pos" id="kode_pos"
                       class="form-control @error('kode_pos') is-invalid @enderror"
                       value="{{ old('kode_pos') }}" required maxlength="5"
                       pattern="[0-9]{5}" placeholder="00000">
                @error('kode_pos')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text">Terisi otomatis saat memilih kelurahan</span>
            </div>
            <div>
                <label for="telepon" class="form-label">Telepon Masjid <span class="req">*</span></label>
                <input type="tel" name="telepon" id="telepon"
                       class="form-control @error('telepon') is-invalid @enderror"
                       value="{{ old('telepon') }}" required maxlength="20"
                       placeholder="(021) xxxxxxxx">
                @error('telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="email_masjid" class="form-label">Email Masjid <span class="req">*</span></label>
                <input type="email" name="email_masjid" id="email_masjid"
                       class="form-control @error('email_masjid') is-invalid @enderror"
                       value="{{ old('email_masjid') }}" required maxlength="255"
                       placeholder="masjid@example.com">
                @error('email_masjid')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text" id="emailMasjidHelp">Bisa sama dengan email admin</span>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- ========================
             SECTION 4: Sejarah (WAJIB sesuai controller)
             ======================== -->
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Sejarah Masjid <small class="opt-tag">(Wajib diisi)</small>
        </div>

        <div class="form-row">
            <div>
                <label for="sejarah" class="form-label">Sejarah Singkat <span class="req">*</span></label>
                <textarea name="sejarah" id="sejarah"
                          class="form-control @error('sejarah') is-invalid @enderror"
                          rows="4" required placeholder="Ceritakan sejarah singkat masjid...">{{ old('sejarah') }}</textarea>
                @error('sejarah')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row col-3">
            <div>
                <label for="tahun_berdiri" class="form-label">Tahun Berdiri <span class="req">*</span></label>
                <input type="number" name="tahun_berdiri" id="tahun_berdiri"
                       class="form-control @error('tahun_berdiri') is-invalid @enderror"
                       value="{{ old('tahun_berdiri') }}" required min="1900" max="{{ date('Y') }}"
                       placeholder="{{ date('Y') }}">
                @error('tahun_berdiri')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="pendiri" class="form-label">Nama Pendiri <span class="req">*</span></label>
                <input type="text" name="pendiri" id="pendiri"
                       class="form-control @error('pendiri') is-invalid @enderror"
                       value="{{ old('pendiri') }}" required maxlength="255"
                       placeholder="Contoh: H. Maulana">
                @error('pendiri')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="kapasitas_jamaah" class="form-label">Kapasitas Jamaah <span class="req">*</span></label>
                <input type="number" name="kapasitas_jamaah" id="kapasitas_jamaah"
                       class="form-control @error('kapasitas_jamaah') is-invalid @enderror"
                       value="{{ old('kapasitas_jamaah') }}" required min="1" placeholder="500">
                @error('kapasitas_jamaah')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text">Jumlah jamaah maksimal</span>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- ========================
             SECTION 5: Foto Masjid (WAJIB sesuai controller)
             ======================== -->
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Foto Masjid <small class="opt-tag">(Wajib diisi - minimal 1 foto)</small>
        </div>

        <div class="form-row">
            <div>
                <label for="foto_masjid" class="form-label">Upload Foto Masjid <span class="req">*</span></label>
                <div class="file-wrap">
                    <input type="file" name="foto_masjid[]" id="foto_masjid"
                           class="@error('foto_masjid.*') is-invalid @enderror"
                           accept="image/jpeg,image/jpg,image/png" multiple required>
                </div>
                @error('foto_masjid.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text">Maksimal 5 foto · JPG, JPEG, PNG · Max 2MB per foto</span>
            </div>
        </div>

        <div class="foto-gallery" id="fotoMasjidPreview">
            <!-- Preview akan muncul di sini -->
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit" id="submitBtn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan dan Selesaikan Registrasi
        </button>
    </form>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
        <div class="spinner"></div>
        <p>Menyimpan data...</p>
    </div>
</div>

@endsection

@section('footer-links')
@endsection

@push('scripts')
<!-- reCAPTCHA v3 Script -->
@if ($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form            = document.getElementById('completeProfileForm');
    const submitBtn       = document.getElementById('submitBtn');
    const loadingOverlay  = document.getElementById('loadingOverlay');
    const usernameInput   = document.getElementById('username');
    const passwordInput   = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    const adminFotoInput  = document.getElementById('admin_foto');
    const adminFotoPreview= document.getElementById('adminFotoPreview');
    const fotoMasjidInput = document.getElementById('foto_masjid');
    const fotoMasjidPreview = document.getElementById('fotoMasjidPreview');
    const provinsiSelect  = document.getElementById('provinsi_kode');
    const kotaSelect      = document.getElementById('kota_kode');
    const kecamatanSelect = document.getElementById('kecamatan_kode');
    const kelurahanSelect = document.getElementById('kelurahan_kode');
    const kodePosInput    = document.getElementById('kode_pos');
    const adminEmailInput = document.getElementById('admin_email');
    const adminEmailHelp  = document.getElementById('adminEmailHelp');
    const emailMasjidInput = document.getElementById('email_masjid');
    const emailMasjidHelp = document.getElementById('emailMasjidHelp');

    // MASKED EMAIL DISPLAY
    const originalEmail = "{{ $pengguna->email }}";
    const maskedEmail = "{{ $maskedEmail }}";
    
    // Show masked email in help text
    if (adminEmailHelp) {
        adminEmailHelp.textContent = `Default: ${maskedEmail}`;
    }

    // USERNAME CHECK (hanya untuk non-Google user)
    if (usernameInput && !{{ $isGoogleUser ? 'true' : 'false' }}) {
        let usernameTimeout;
        usernameInput.addEventListener('input', function () {
            clearTimeout(usernameTimeout);
            const val  = this.value.trim();
            const help = document.getElementById('usernameHelp');
            
            if (val.length < 6) {
                help.className = 'form-text error';
                help.textContent = 'Username minimal 6 karakter';
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(val)) {
                help.className = 'form-text error';
                help.textContent = 'Hanya huruf, angka, dan underscore';
                return;
            }
            
            help.className = 'form-text';
            help.textContent = 'Memeriksa ketersediaan...';
            
            usernameTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/check-username?username=${encodeURIComponent(val)}&pengguna_id={{ $pengguna->id }}`);
                    const r   = await res.json();
                    help.className = r.available ? 'form-text success' : 'form-text error';
                    help.textContent = (r.available ? '✓ ' : '✗ ') + r.message;
                } catch(e) {
                    console.error('Username check error:', e);
                    help.className = 'form-text error';
                    help.textContent = 'Gagal memeriksa username';
                }
            }, 500);
        });
    }

    // PASSWORD STRENGTH CHECK (hanya untuk non-Google user)
    if (passwordInput && !{{ $isGoogleUser ? 'true' : 'false' }}) {
        const strengthFill = document.getElementById('passwordStrengthFill');
        const strengthText = document.getElementById('passwordStrengthText');
        
        passwordInput.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;
            let text = 'Kosong';
            let color = '#e0e0e0';
            
            if (password.length > 0) {
                strength++;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                // Set strength level
                if (strength <= 1) {
                    text = 'Sangat lemah';
                    color = '#f44336';
                } else if (strength === 2) {
                    text = 'Lemah';
                    color = '#ff9800';
                } else if (strength === 3) {
                    text = 'Cukup';
                    color = '#ffeb3b';
                } else if (strength === 4) {
                    text = 'Kuat';
                    color = '#8bc34a';
                } else {
                    text = 'Sangat kuat';
                    color = '#4caf50';
                }
            }
            
            // Update UI
            strengthFill.style.width = (strength * 20) + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = `Kekuatan password: ${text}`;
            strengthText.style.color = color;
        });
    }

    // PASSWORD CONFIRMATION CHECK (hanya untuk non-Google user)
    if (passwordConfirm && passwordInput && !{{ $isGoogleUser ? 'true' : 'false' }}) {
        const passwordMatchText = document.getElementById('passwordMatchText');
        
        passwordConfirm.addEventListener('input', function () {
            if (this.value === '') {
                passwordMatchText.textContent = '';
                passwordMatchText.className = 'form-text';
                this.setCustomValidity('');
                return;
            }
            
            if (this.value !== passwordInput.value) {
                passwordMatchText.textContent = '✗ Password tidak cocok';
                passwordMatchText.className = 'form-text error';
                this.setCustomValidity('Password tidak cocok');
            } else {
                passwordMatchText.textContent = '✓ Password cocok';
                passwordMatchText.className = 'form-text success';
                this.setCustomValidity('');
            }
        });
        
        // Also check when password input changes
        passwordInput.addEventListener('input', function () {
            if (passwordConfirm.value !== '') {
                passwordConfirm.dispatchEvent(new Event('input'));
            }
        });
    }

    // ADMIN FOTO PREVIEW
    if (adminFotoInput) {
        adminFotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Validation
            if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) {
                alert('Format harus JPG/JPEG/PNG');
                this.value = '';
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran maksimal 2MB');
                this.value = '';
                return;
            }
            
            // Preview
            const reader = new FileReader();
            reader.onload = function(ev) {
                adminFotoPreview.innerHTML = `<img src="${ev.target.result}" alt="Foto Admin">`;
            };
            reader.readAsDataURL(file);
        });
    }

    // FOTO MASJID PREVIEW
    if (fotoMasjidInput) {
        fotoMasjidInput.addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            
            // Validation
            if (files.length > 5) {
                alert('Maksimal 5 foto');
                this.value = '';
                return;
            }
            
            for (const f of files) {
                if (!['image/jpeg','image/jpg','image/png'].includes(f.type)) {
                    alert('Format harus JPG/JPEG/PNG');
                    this.value = '';
                    return;
                }
                if (f.size > 2 * 1024 * 1024) {
                    alert('Max 2MB per foto');
                    this.value = '';
                    return;
                }
            }
            
            // Clear previous preview
            fotoMasjidPreview.innerHTML = '';
            
            // Create preview for each file
            files.forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const div = document.createElement('div');
                    div.className = 'foto-gallery-item';
                    div.innerHTML = `<img src="${ev.target.result}" alt="Foto Masjid ${i+1}">`;
                    fotoMasjidPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            
            // Add empty slots
            for (let i = files.length; i < 5; i++) {
                const div = document.createElement('div');
                div.className = 'foto-gallery-item';
                div.innerHTML = `
                    <div class="sph">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>Slot ${i+1}</p>
                    </div>
                `;
                fotoMasjidPreview.appendChild(div);
            }
        });
    }

    // WILAYAH CASCADE
    provinsiSelect.addEventListener('change', async function () {
        resetSelect(kotaSelect, 'Pilih Kota / Kabupaten');
        resetSelect(kecamatanSelect, 'Pilih Kecamatan');
        resetSelect(kelurahanSelect, 'Pilih Kelurahan / Desa');
        kodePosInput.value = '';
        
        if (!this.value) return;
        
        kotaSelect.disabled = true;
        kotaSelect.innerHTML = '<option value="">Loading...</option>';
        
        try {
            const res = await fetch(`/api/wilayah/cities/${this.value}`);
            const r = await res.json();
            
            if (r.success && r.data) {
                fillSelect(kotaSelect, r.data, 'Pilih Kota / Kabupaten');
                kotaSelect.disabled = false;
            } else {
                alert('Gagal memuat data kota/kabupaten');
                resetSelect(kotaSelect, 'Pilih Kota / Kabupaten');
            }
        } catch(e) {
            console.error('Cities fetch error:', e);
            alert('Gagal memuat kota/kabupaten');
            resetSelect(kotaSelect, 'Pilih Kota / Kabupaten');
        }
    });

    kotaSelect.addEventListener('change', async function () {
        resetSelect(kecamatanSelect, 'Pilih Kecamatan');
        resetSelect(kelurahanSelect, 'Pilih Kelurahan / Desa');
        kodePosInput.value = '';
        
        if (!this.value) return;
        
        kecamatanSelect.disabled = true;
        kecamatanSelect.innerHTML = '<option value="">Loading...</option>';
        
        try {
            const res = await fetch(`/api/wilayah/districts/${this.value}`);
            const r = await res.json();
            
            if (r.success && r.data) {
                fillSelect(kecamatanSelect, r.data, 'Pilih Kecamatan');
                kecamatanSelect.disabled = false;
            } else {
                alert('Gagal memuat data kecamatan');
                resetSelect(kecamatanSelect, 'Pilih Kecamatan');
            }
        } catch(e) {
            console.error('Districts fetch error:', e);
            alert('Gagal memuat kecamatan');
            resetSelect(kecamatanSelect, 'Pilih Kecamatan');
        }
    });

    kecamatanSelect.addEventListener('change', async function () {
        resetSelect(kelurahanSelect, 'Pilih Kelurahan / Desa');
        kodePosInput.value = '';
        
        if (!this.value) return;
        
        kelurahanSelect.disabled = true;
        kelurahanSelect.innerHTML = '<option value="">Loading...</option>';
        
        try {
            const res = await fetch(`/api/wilayah/villages/${this.value}`);
            const r = await res.json();
            
            if (r.success && r.data) {
                fillSelect(kelurahanSelect, r.data, 'Pilih Kelurahan / Desa');
                kelurahanSelect.disabled = false;
            } else {
                alert('Gagal memuat data kelurahan');
                resetSelect(kelurahanSelect, 'Pilih Kelurahan / Desa');
            }
        } catch(e) {
            console.error('Villages fetch error:', e);
            alert('Gagal memuat kelurahan');
            resetSelect(kelurahanSelect, 'Pilih Kelurahan / Desa');
        }
    });

    kelurahanSelect.addEventListener('change', async function () {
        kodePosInput.value = '';
        
        if (!this.value) return;
        
        try {
            const res = await fetch(`/api/wilayah/postal-code/${this.value}`);
            const r = await res.json();
            
            if (r.success && r.data && r.data.postal_code) {
                kodePosInput.value = r.data.postal_code;
            }
        } catch(e) {
            console.error('Postal code fetch error:', e);
        }
    });

    function fillSelect(selectElement, data, placeholder) {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(item => {
            const option = new Option(item.name, item.code);
            selectElement.add(option);
        });
    }

    function resetSelect(selectElement, placeholder) {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        selectElement.disabled = true;
    }

    // FORM SUBMIT WITH reCAPTCHA
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        
        // Validate required fields
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Harap isi semua field yang wajib diisi');
            return;
        }
        
        // Show loading
        loadingOverlay.classList.add('active');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin" style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Menyimpan...
        `;
        
        try {
            @if ($recaptchaSiteKey)
            // Execute reCAPTCHA
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { 
                action: 'complete_profile' 
            });
            document.getElementById('recaptcha_token').value = token;
            @endif
            
            // Submit form
            form.submit();
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            
            // Hide loading
            loadingOverlay.classList.remove('active');
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.1rem;height:1.1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan dan Selesaikan Registrasi
            `;
            
            alert('Terjadi kesalahan saat verifikasi. Silakan coba lagi.');
        }
    });

    // Handle page load errors
    window.addEventListener('load', function () {
        loadingOverlay.classList.remove('active');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.1rem;height:1.1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan dan Selesaikan Registrasi
            `;
        }
    });
});
</script>
@endpush