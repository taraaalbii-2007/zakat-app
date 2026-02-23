@extends('layouts.auth')

@section('title', 'Lengkapi Profil Muzakki')

@push('styles')
<style>
    .w-full.max-w-md.relative.z-10 { max-width: 640px !important; }
    .glass-effect.rounded-3xl > .mb-8 { display: none !important; }
    .glass-effect.rounded-3xl {
        padding: 0 !important;
        border-radius: 1.5rem !important;
        overflow: hidden !important;
    }

    .profile-header {
        background: linear-gradient(135deg, #1565c0 0%, #0d47a1 50%, #42a5f5 100%);
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

    .profile-header .hdr-content {
        position: relative; z-index: 1;
        display: flex; align-items: center; gap: 1.25rem;
    }

    .hdr-icon-wrap {
        width: 56px; height: 56px;
        background: rgba(255,255,255,0.15);
        border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; backdrop-filter: blur(4px);
    }

    .hdr-icon-wrap svg { width: 1.75rem; height: 1.75rem; color: #fff; }
    .profile-header h2 { color: #fff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.2rem; }
    .profile-header .hdr-sub { color: rgba(255,255,255,0.7); font-size: 0.8rem; margin: 0; }

    .step-badges { position: relative; z-index: 1; display: flex; gap: 0.5rem; margin-top: 1.25rem; }
    .step-badge {
        font-size: 0.68rem; font-weight: 600;
        padding: 0.22rem 0.6rem; border-radius: 999px;
        background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.55);
        border: 1px solid rgba(255,255,255,0.15);
    }
    .step-badge.active { background: rgba(255,255,255,0.22); color: #fff; border-color: rgba(255,255,255,0.3); }

    .profile-form-body { padding: 2rem 2.5rem 2.5rem; }

    .section-title {
        font-size: 0.95rem; font-weight: 600; color: #1565c0;
        margin-bottom: 1.125rem; margin-top: 2rem;
        padding-bottom: 0.55rem; border-bottom: 2px solid #bbdefb;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .section-title:first-of-type { margin-top: 0; }
    .section-title .s-icon { width: 1.1rem; height: 1.1rem; color: #42a5f5; flex-shrink: 0; }

    .form-row { display: grid; grid-template-columns: 1fr; gap: 1.125rem; margin-bottom: 1.125rem; }
    .form-row.col-2 { grid-template-columns: repeat(2, 1fr); }

    .form-label { display: block; font-weight: 500; color: #424242; margin-bottom: 0.35rem; font-size: 0.8rem; }
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
        outline: none; border-color: #1565c0;
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.13);
        background: #fff;
    }
    .form-control:disabled { background: #f5f5f5; color: #9e9e9e; cursor: not-allowed; }
    .form-control.is-invalid { border-color: #f44336; }

    select.form-control {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239e9e9e' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2rem;
    }

    .invalid-feedback { display: block; color: #f44336; font-size: 0.72rem; margin-top: 0.22rem; }
    .form-text { display: block; color: #9e9e9e; font-size: 0.7rem; margin-top: 0.22rem; }
    .form-text.success { color: #4caf50; }
    .form-text.error { color: #f44336; }

    .password-strength { margin-top: 0.3rem; }
    .strength-bar { height: 4px; border-radius: 2px; background: #e0e0e0; margin-bottom: 0.25rem; overflow: hidden; }
    .strength-fill { height: 100%; width: 0%; transition: width 0.3s, background-color 0.3s; }
    .strength-text { font-size: 0.7rem; }

    .file-wrap input[type="file"] {
        width: 100%; padding: 0.6rem 0.875rem;
        border: 1.5px dashed #bdbdbd; border-radius: 0.6rem;
        font-size: 0.75rem; font-family: 'Poppins', sans-serif;
        color: #616161; background: #fafafa; cursor: pointer;
        transition: border-color 0.2s, background 0.2s; box-sizing: border-box;
    }
    .file-wrap input[type="file"]:hover { border-color: #42a5f5; background: #e3f2fd; }

    .foto-single {
        margin-top: 0.7rem; width: 96px; height: 96px;
        border: 2px dashed #bdbdbd; border-radius: 0.7rem;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; background: #f5f5f5;
    }
    .foto-single img { width: 100%; height: 100%; object-fit: cover; }
    .foto-single .ph { text-align: center; color: #bdbdbd; }
    .foto-single .ph svg { width: 1.4rem; height: 1.4rem; margin-bottom: 0.12rem; }
    .foto-single .ph p { font-size: 0.62rem; margin: 0; }

    .alert-box {
        padding: 0.7rem 0.9rem; border-radius: 0.6rem;
        margin-bottom: 1.25rem;
        display: flex; align-items: flex-start; gap: 0.6rem; font-size: 0.78rem;
    }
    .alert-box .ai { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: 0.08rem; }
    .alert-box .at { font-weight: 600; display: block; margin-bottom: 0.12rem; }
    .alert-box ul { margin: 0.25rem 0 0; padding-left: 1.1rem; }
    .alert-box.danger  { background: #ffebee; border: 1px solid #ef9a9a; color: #b71c1c; }

    .section-divider { height: 1px; background: #eeeeee; margin: 1.75rem 0; }

    /* Masjid card info */
    .masjid-info {
        display: none;
        margin-top: 0.6rem; padding: 0.6rem 0.875rem;
        background: #e3f2fd; border: 1px solid #90caf9;
        border-radius: 0.5rem; font-size: 0.75rem; color: #1565c0;
    }
    .masjid-info.show { display: block; }

    .btn-submit {
        width: 100%; margin-top: 2rem;
        padding: 0.78rem 1.5rem; border: none; border-radius: 0.75rem;
        font-size: 0.88rem; font-weight: 600; font-family: 'Poppins', sans-serif;
        color: #fff;
        background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
        box-shadow: 0 4px 12px rgba(21,101,192,0.3);
    }
    .btn-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(21,101,192,0.4); }
    .btn-submit:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }

    .loading-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45);
        display: none; align-items: center; justify-content: center; z-index: 9999;
    }
    .loading-overlay.active { display: flex; }
    .loading-box { background: #fff; border-radius: 1rem; padding: 2rem 2.5rem; text-align: center; box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
    .spinner { width: 38px; height: 38px; border: 3.5px solid #bbdefb; border-top-color: #1565c0; border-radius: 50%; animation: spin 0.7s linear infinite; margin: 0 auto 0.65rem; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 0.78rem; color: #616161; margin: 0; font-family: 'Poppins', sans-serif; }

    @media (max-width: 768px) {
        .w-full.max-w-md.relative.z-10 { max-width: 100% !important; }
        .profile-header { padding: 1.75rem 1.25rem 2rem; }
        .profile-form-body { padding: 1.5rem 1.25rem 1.75rem; }
        .form-row.col-2 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('card-title', '')
@section('card-subtitle', '')

@section('content')

<div class="profile-header">
    <div class="hdr-content">
        <div class="hdr-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h2>Lengkapi Profil Muzakki</h2>
            <p class="hdr-sub">Isi informasi di bawah untuk menyelesaikan pendaftaran</p>
        </div>
    </div>
    <div class="step-badges">
        <span class="step-badge">✓ Email Verified</span>
        <span class="step-badge active">● Data Diri</span>
        <span class="step-badge">○ Selesai</span>
    </div>
</div>

<div class="profile-form-body">

    @if($errors->any())
    <div class="alert-box danger">
        <svg class="ai" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div>
            <span class="at">Terdapat kesalahan:</span>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    <form method="POST"
          action="{{ route('complete-profile-muzakki.store', $token) }}"
          enctype="multipart/form-data"
          id="muzakkiProfileForm">
        @csrf
        <input type="hidden" name="pengguna_id" value="{{ $pengguna->id }}">
        @if($recaptchaSiteKey)
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
        @endif

        {{-- SECTION 1: Data Akun --}}
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4-2a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            Data Akun
        </div>

        <div class="form-row col-2">
            <div>
                <label class="form-label">Email Terdaftar</label>
                <input type="email" class="form-control" value="{{ $pengguna->email }}" readonly>
                <span class="form-text success">✓ Email sudah diverifikasi</span>
            </div>
            <div>
                <label for="username" class="form-label">
                    Username <span class="req">*</span>
                </label>
                @if($isGoogleUser)
                    <input type="text" class="form-control" value="(Akan dibuat otomatis)" readonly>
                    <span class="form-text">Username dibuat otomatis dari email Anda</span>
                @else
                    <input type="text" name="username" id="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}" required minlength="6" maxlength="50"
                           pattern="[a-zA-Z0-9_]+" autocomplete="username"
                           placeholder="contoh: ahmad123">
                    @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    <span class="form-text" id="usernameHelp">Minimal 6 karakter, hanya huruf, angka, underscore</span>
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
                <div class="password-strength">
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                    <div class="strength-text" id="strengthText">Kekuatan password</div>
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="req">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       required minlength="8" autocomplete="new-password"
                       placeholder="Ulangi password">
                @error('password_confirmation')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text" id="passwordMatchText"></span>
            </div>
        </div>
        @endif

        <div class="section-divider"></div>

        {{-- SECTION 2: Data Diri --}}
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Data Diri
        </div>

        <div class="form-row col-2">
            <div>
                <label for="nama" class="form-label">Nama Lengkap <span class="req">*</span></label>
                <input type="text" name="nama" id="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}" required maxlength="255"
                       placeholder="Contoh: Ahmad Hidayat">
                @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="telepon" class="form-label">Nomor Telepon <span class="req">*</span></label>
                <input type="tel" name="telepon" id="telepon"
                       class="form-control @error('telepon') is-invalid @enderror"
                       value="{{ old('telepon') }}" required maxlength="20"
                       placeholder="08xxxxxxxxxx">
                @error('telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="foto" class="form-label">Foto Profil <span style="color:#9e9e9e;font-size:0.7rem;">(Opsional)</span></label>
                <div class="file-wrap">
                    <input type="file" name="foto" id="foto"
                           class="@error('foto') is-invalid @enderror"
                           accept="image/jpeg,image/jpg,image/png">
                </div>
                @error('foto')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text">JPG, JPEG, PNG · Max 2MB</span>
            </div>
        </div>

        <div class="foto-single" id="fotoPreview">
            <div class="ph">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p>Preview</p>
            </div>
        </div>

        <div class="section-divider"></div>

        {{-- SECTION 3: Pilih Masjid --}}
        <div class="section-title">
            <svg class="s-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
            </svg>
            Pilih Masjid
        </div>

        <div class="form-row">
            <div>
                <label for="masjid_id" class="form-label">Masjid Tempat Berzakat <span class="req">*</span></label>
                <select name="masjid_id" id="masjid_id"
                        class="form-control @error('masjid_id') is-invalid @enderror" required>
                    <option value="">— Pilih Masjid —</option>
                    @foreach($masjidList as $masjid)
                        <option value="{{ $masjid->id }}"
                                data-kota="{{ $masjid->kota_nama }}"
                                data-provinsi="{{ $masjid->provinsi_nama }}"
                                {{ old('masjid_id') == $masjid->id ? 'selected' : '' }}>
                            {{ $masjid->nama }}
                        </option>
                    @endforeach
                </select>
                @error('masjid_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <span class="form-text">Pilih masjid di mana Anda ingin membayar zakat</span>

                {{-- Info masjid yang dipilih --}}
                <div class="masjid-info" id="masjidInfo">
                    <svg style="width:0.9rem;height:0.9rem;display:inline;vertical-align:middle;margin-right:4px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span id="masjidInfoText"></span>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" id="submitBtn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan dan Selesaikan Pendaftaran
        </button>

    </form>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
        <div class="spinner"></div>
        <p>Menyimpan data...</p>
    </div>
</div>

@endsection

@section('footer-links')@endsection

@push('scripts')
@if($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form          = document.getElementById('muzakkiProfileForm');
    const submitBtn     = document.getElementById('submitBtn');
    const loadingOverlay= document.getElementById('loadingOverlay');
    const isGoogleUser  = {{ $isGoogleUser ? 'true' : 'false' }};

    // ── USERNAME CHECK ──
    const usernameInput = document.getElementById('username');
    if (usernameInput && !isGoogleUser) {
        let timer;
        usernameInput.addEventListener('input', function () {
            clearTimeout(timer);
            const val  = this.value.trim();
            const help = document.getElementById('usernameHelp');
            if (val.length < 6) { help.className = 'form-text error'; help.textContent = 'Minimal 6 karakter'; return; }
            if (!/^[a-zA-Z0-9_]+$/.test(val)) { help.className = 'form-text error'; help.textContent = 'Hanya huruf, angka, dan underscore'; return; }
            help.className = 'form-text'; help.textContent = 'Memeriksa...';
            timer = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/check-username?username=${encodeURIComponent(val)}&pengguna_id={{ $pengguna->id }}`);
                    const r   = await res.json();
                    help.className   = r.available ? 'form-text success' : 'form-text error';
                    help.textContent = (r.available ? '✓ ' : '✗ ') + r.message;
                } catch(e) { help.className = 'form-text error'; help.textContent = 'Gagal memeriksa'; }
            }, 500);
        });
    }

    // ── PASSWORD STRENGTH ──
    const passwordInput = document.getElementById('password');
    if (passwordInput && !isGoogleUser) {
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        passwordInput.addEventListener('input', function () {
            const pw = this.value;
            let s = 0;
            if (pw.length > 0) s++;
            if (pw.length >= 8) s++;
            if (/[A-Z]/.test(pw)) s++;
            if (/[0-9]/.test(pw)) s++;
            if (/[^A-Za-z0-9]/.test(pw)) s++;
            const levels = ['','Sangat lemah','Lemah','Cukup','Kuat','Sangat kuat'];
            const colors = ['','#f44336','#ff9800','#ffeb3b','#8bc34a','#4caf50'];
            fill.style.width = (s * 20) + '%';
            fill.style.backgroundColor = colors[s] || '#e0e0e0';
            text.textContent = pw.length ? `Kekuatan: ${levels[s]}` : 'Kekuatan password';
            text.style.color = colors[s] || '#9e9e9e';
        });

        // Password match
        const confirmInput = document.getElementById('password_confirmation');
        const matchText    = document.getElementById('passwordMatchText');
        confirmInput.addEventListener('input', function () {
            if (!this.value) { matchText.textContent = ''; this.setCustomValidity(''); return; }
            if (this.value !== passwordInput.value) {
                matchText.textContent = '✗ Password tidak cocok'; matchText.className = 'form-text error';
                this.setCustomValidity('tidak cocok');
            } else {
                matchText.textContent = '✓ Password cocok'; matchText.className = 'form-text success';
                this.setCustomValidity('');
            }
        });
        passwordInput.addEventListener('input', () => {
            if (confirmInput.value) confirmInput.dispatchEvent(new Event('input'));
        });
    }

    // ── FOTO PREVIEW ──
    const fotoInput   = document.getElementById('foto');
    const fotoPreview = document.getElementById('fotoPreview');
    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) { alert('Format harus JPG/JPEG/PNG'); this.value = ''; return; }
            if (file.size > 2 * 1024 * 1024) { alert('Ukuran maksimal 2MB'); this.value = ''; return; }
            const reader = new FileReader();
            reader.onload = ev => fotoPreview.innerHTML = `<img src="${ev.target.result}" alt="Foto Profil">`;
            reader.readAsDataURL(file);
        });
    }

    // ── MASJID INFO ──
    const masjidSelect = document.getElementById('masjid_id');
    const masjidInfo   = document.getElementById('masjidInfo');
    const masjidInfoTxt= document.getElementById('masjidInfoText');
    masjidSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (this.value && opt.dataset.kota) {
            masjidInfoTxt.textContent = `${opt.dataset.kota}, ${opt.dataset.provinsi}`;
            masjidInfo.classList.add('show');
        } else {
            masjidInfo.classList.remove('show');
        }
    });

    // Trigger jika ada old value
    if (masjidSelect.value) masjidSelect.dispatchEvent(new Event('change'));

    // ── FORM SUBMIT ──
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        loadingOverlay.classList.add('active');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<svg style="width:1.1rem;height:1.1rem;animation:spin 0.7s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...`;

        try {
            @if($recaptchaSiteKey)
            const rcToken = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'complete_profile_muzakki' });
            document.getElementById('recaptcha_token').value = rcToken;
            @endif
            form.submit();
        } catch (err) {
            loadingOverlay.classList.remove('active');
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.1rem;height:1.1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan dan Selesaikan Pendaftaran`;
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });

    window.addEventListener('load', function () {
        loadingOverlay.classList.remove('active');
    });
});
</script>
@endpush