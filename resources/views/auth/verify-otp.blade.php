@extends('layouts.auth')

@section('title', 'Verifikasi OTP')
@section('auth-title', 'Verifikasi Email Anda')
@section('auth-subtitle', 'Kami telah mengirim kode 6 digit ke email Anda')

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
       OTP HEADER
    ══════════════════════════════════ */
    .otp-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .otp-icon-wrap {
        width: 56px; height: 56px;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: .75rem;
    }
    .otp-icon-wrap svg { width: 26px; height: 26px; color: #16a34a; }
    .otp-email-label {
        font-size: .78rem;
        color: #9ca3af;
    }
    .otp-email-label span {
        font-weight: 700;
        color: #374151;
    }

    /* ══════════════════════════════════
       FORM GROUP (sama dengan login)
    ══════════════════════════════════ */
    .lg-group { margin-bottom: 1rem; }

    .lg-label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .5rem;
        text-align: center;
    }

    .lg-err {
        display: flex; align-items: center; justify-content: center; gap: .3rem;
        font-size: .71rem; color: #f43f5e; margin-top: .4rem;
    }
    .lg-err svg { width: 13px; height: 13px; flex-shrink: 0; }

    /* ══════════════════════════════════
       OTP INPUTS
    ══════════════════════════════════ */
    .otp-inputs-row {
        display: flex;
        justify-content: center;
        gap: .5rem;
    }
    .otp-input {
        width: 46px;
        height: 52px;
        text-align: center;
        font-family: 'Inter', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #111827;
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
        -webkit-appearance: none;
        appearance: none;
        box-sizing: border-box;
        caret-color: #16a34a;
    }
    .otp-input::placeholder { color: #e5e7eb; }
    .otp-input:hover  { border-color: #d1d5db; }
    .otp-input:focus  {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
        background: #fff;
    }
    .otp-input.filled {
        background: #f0fdf4;
        border-color: #86efac;
    }
    .otp-input.err {
        border-color: #f43f5e !important;
    }
    .otp-input.err:focus {
        box-shadow: 0 0 0 3px rgba(244,63,94,.1);
    }

    /* ══════════════════════════════════
       PROGRESS BAR
    ══════════════════════════════════ */
    .otp-progress-wrap {
        height: 5px;
        background: #f3f4f6;
        border-radius: 9999px;
        overflow: hidden;
        margin-top: .75rem;
    }
    .otp-progress-bar {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #22c55e, #16a34a);
        transition: width 1s linear, background .5s;
    }

    /* ══════════════════════════════════
       BUTTON AKSI (sama dengan btn-masuk)
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
        transition: transform .18s, box-shadow .18s, opacity .2s;
        -webkit-appearance: none;
        margin-top: 1rem;
    }
    .btn-masuk svg { width: 18px; height: 18px; flex-shrink: 0; display: block; }
    .btn-masuk:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(22,163,74,.42);
    }
    .btn-masuk:active { transform: translateY(0); }
    .btn-masuk:disabled { opacity: .5; cursor: not-allowed; transform: none; }

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
       EXPIRED / COOLDOWN BOX
    ══════════════════════════════════ */
    .otp-expired-box {
        display: none;
        background: #fff1f2;
        border: 1.5px solid #fecdd3;
        border-radius: 10px;
        padding: .9rem 1rem;
        margin-top: 1rem;
    }
    .otp-expired-box.show { display: block; }
    .otp-expired-header {
        display: flex; align-items: center; gap: .4rem;
        margin-bottom: .5rem;
    }
    .otp-expired-header svg { width: 15px; height: 15px; color: #f43f5e; flex-shrink: 0; }
    .otp-expired-header span {
        font-size: .78rem; font-weight: 700; color: #e11d48;
    }
    .otp-expired-sub {
        font-size: .73rem; color: #f43f5e; margin-bottom: .6rem;
    }
    .otp-cooldown-row {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .otp-cooldown-box {
        background: #fff; border: 1.5px solid #fecdd3;
        border-radius: 8px; padding: .3rem .75rem;
        text-align: center; min-width: 54px;
    }
    .otp-cooldown-box span {
        display: block; font-size: 1.1rem; font-weight: 800; color: #e11d48; line-height: 1.2;
    }
    .otp-cooldown-box p {
        font-size: .65rem; color: #f43f5e; margin: 0;
    }
    .otp-cooldown-sep {
        font-size: 1.1rem; font-weight: 800; color: #fca5a5;
    }

    /* ══════════════════════════════════
       FLASH MESSAGES
    ══════════════════════════════════ */
    .flash-box {
        display: flex; align-items: flex-start; gap: .5rem;
        border-radius: 10px; padding: .75rem 1rem;
        font-size: .78rem; margin-top: .75rem;
        border-width: 1.5px; border-style: solid;
    }
    .flash-box svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }
    .flash-box.success { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
    .flash-box.error   { background: #fff1f2; border-color: #fecdd3; color: #e11d48; }

    /* ══════════════════════════════════
       DIVIDER (sama dengan login)
    ══════════════════════════════════ */
    .or-row {
        display: flex; align-items: center; gap: .75rem;
        margin: .9rem 0 0;
    }
    .or-line { flex: 1; height: 1px; background: #f3f4f6; }
    .or-row span {
        font-size: .72rem; font-weight: 600; color: #9ca3af;
        white-space: nowrap; letter-spacing: .02em;
    }

    /* ══════════════════════════════════
       TOAST — sama persis dengan app.blade.php
    ══════════════════════════════════ */
    .otp-toast-container {
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        pointer-events: none;
    }

    .otp-toast {
        pointer-events: auto;
        min-width: 260px;
        max-width: 340px;
        padding: 0.75rem 0.875rem;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 4px 16px -2px rgba(0,0,0,0.08), 0 2px 6px -1px rgba(0,0,0,0.06);
        display: flex;
        align-items: flex-start;
        gap: 0.625rem;
        border: 1px solid #f3f4f6;
        animation: otpSlideInRight 0.25s ease-out;
    }

    .otp-toast.otp-toast-exit {
        animation: otpSlideOutRight 0.25s ease-in forwards;
    }

    @keyframes otpSlideInRight {
        from { transform: translateX(360px); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }
    @keyframes otpSlideOutRight {
        from { transform: translateX(0);     opacity: 1; }
        to   { transform: translateX(360px); opacity: 0; }
    }

    .otp-toast-icon {
        flex-shrink: 0;
        width: 1.125rem;
        height: 1.125rem;
        margin-top: 0.05rem;
    }

    .otp-toast-body {
        flex: 1;
        font-size: 0.8125rem;
        line-height: 1.45;
        color: #374151;
    }

    .otp-toast-close {
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
        cursor: pointer;
        opacity: 0.4;
        transition: opacity 0.15s;
        color: #6b7280;
    }
    .otp-toast-close:hover { opacity: 0.8; }

    /* Border kiri berwarna — sama seperti app.blade.php */
    .otp-toast.toast-success { border-left: 3px solid #10b981; }
    .otp-toast.toast-error   { border-left: 3px solid #ef4444; }

    .otp-toast.toast-success .otp-toast-icon { color: #10b981; }
    .otp-toast.toast-error   .otp-toast-icon { color: #ef4444; }

    @media (max-width: 768px) {
        .otp-toast-container { right: 0.75rem; left: 0.75rem; top: 0.75rem; }
        .otp-toast { min-width: auto; width: 100%; }
    }
</style>
@endpush

@section('auth-content')

{{-- Toast Container --}}
<div class="otp-toast-container" id="otpToastContainer"></div>

{{-- Header icon + email --}}
<div class="otp-header">
    <div class="otp-icon-wrap">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <p class="otp-email-label">
        Kode dikirim ke: <span>{{ $maskedEmail ?? $email }}</span>
    </p>
</div>

{{-- OTP Form --}}
<form method="POST" action="{{ route('verify-otp.submit') }}" id="otpForm" novalidate>
    @csrf

    @if($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    <input type="hidden" name="email"  value="{{ $email }}">
    <input type="hidden" name="otp"    id="otpValue">

    {{-- OTP Inputs --}}
    <div class="lg-group">
        <label class="lg-label">Masukkan Kode OTP</label>

        <div class="otp-inputs-row">
            @for ($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    maxlength="1"
                    inputmode="numeric"
                    pattern="[0-9]"
                    autocomplete="off"
                    data-index="{{ $i }}"
                    class="otp-input {{ $errors->has('otp') ? 'err' : '' }}"
                    placeholder="·"
                >
            @endfor
        </div>

        {{-- Progress Bar --}}
        <div class="otp-progress-wrap">
            <div class="otp-progress-bar" id="progressBar" style="width:100%"></div>
        </div>

    </div>

    {{-- Action Button --}}
    <button type="button" id="actionButton" class="btn-masuk" onclick="handleAction()" disabled>
        <div class="btn-spinner"></div>
        <span class="btn-label">
            <svg id="actionIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span id="actionText">
                Verifikasi OTP (<span id="timerDisplay">15:00</span>)
            </span>
        </span>
    </button>

    {{-- Expired / Cooldown Box --}}
    <div class="otp-expired-box" id="expiredBox">
        <div class="otp-expired-header">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Kode OTP telah expired!</span>
        </div>
        <p class="otp-expired-sub">Kirim ulang OTP tersedia dalam:</p>
        <div class="otp-cooldown-row">
            <div class="otp-cooldown-box">
                <span id="resendMinutes">01</span>
                <p>Menit</p>
            </div>
            <span class="otp-cooldown-sep">:</span>
            <div class="otp-cooldown-box">
                <span id="resendSeconds">00</span>
                <p>Detik</p>
            </div>
        </div>
    </div>

</form>

{{-- Divider --}}
<div class="or-row">
    <div class="or-line"></div>
    <span>salah email?</span>
    <div class="or-line"></div>
</div>

{{-- Resend hidden form --}}
<form method="POST" action="{{ route('resend-otp') }}" id="resendForm" style="display:none">
    @csrf
    @if($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="resend_recaptcha_token">
    @endif
    <input type="hidden" name="email" value="{{ $email }}">
</form>

@endsection

@section('auth-footer')
    Salah email? <a href="{{ route('register') }}">Daftar ulang</a>
@endsection

@push('scripts')
@if($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
(function () {
    /* ── Konstanta ── */
    const TIMER_KEY     = 'otp_timer_{{ $email }}';
    const TOTAL_SEC     = 15 * 60;
    const RESEND_CD     = 1  * 60;

    /* ── Elemen ── */
    const otpInputs     = document.querySelectorAll('.otp-input');
    const otpValue      = document.getElementById('otpValue');
    const otpForm       = document.getElementById('otpForm');
    const resendForm    = document.getElementById('resendForm');
    const actionButton  = document.getElementById('actionButton');
    const actionText    = document.getElementById('actionText');
    const actionIcon    = document.getElementById('actionIcon');
    const loadingSpinner= document.querySelector('.btn-spinner');
    const progressBar   = document.getElementById('progressBar');
    const expiredBox    = document.getElementById('expiredBox');
    const resendMinEl   = document.getElementById('resendMinutes');
    const resendSecEl   = document.getElementById('resendSeconds');
    const toastContainer= document.getElementById('otpToastContainer');

    /* ── State ── */
    let mainEnd   = localStorage.getItem(TIMER_KEY);
    let resendEnd = localStorage.getItem(TIMER_KEY + '_resend');
    let mode      = 'verify'; // 'verify' | 'resend' | 'cooldown'

    @if(session('success'))
        mainEnd = null; resendEnd = null;
        localStorage.removeItem(TIMER_KEY);
        localStorage.removeItem(TIMER_KEY + '_resend');
    @endif

    /* ── Init main timer ── */
    if (!mainEnd) {
        mainEnd = Date.now() + TOTAL_SEC * 1000;
        localStorage.setItem(TIMER_KEY, mainEnd);
    } else {
        mainEnd = parseInt(mainEnd);
    }

    /* ── Init mode ── */
    if (resendEnd) {
        resendEnd = parseInt(resendEnd);
        if (Date.now() >= resendEnd) {
            localStorage.removeItem(TIMER_KEY + '_resend');
            setMode('resend');
        } else {
            setMode('cooldown');
        }
    } else if (Date.now() >= mainEnd) {
        setMode('resend');
    } else {
        setMode('verify');
    }

    /* ── Set mode UI ── */
    function setMode(m) {
        mode = m;
        if (m === 'verify') {
            actionButton.disabled = false;
            actionButton.style.display = '';
            expiredBox.classList.remove('show');
            setActionIcon('check');
            actionText.innerHTML = 'Verifikasi OTP (<span id="timerDisplay">15:00</span>)';
            progressBar.parentElement.style.display = '';
            updateMainTimer();
        } else if (m === 'resend') {
            actionButton.disabled = false;
            actionButton.style.display = '';
            expiredBox.classList.remove('show');
            setActionIcon('refresh');
            actionText.textContent = 'Kirim Ulang Kode OTP';
            progressBar.style.width = '0%';
            progressBar.parentElement.style.display = 'none';
        } else if (m === 'cooldown') {
            actionButton.style.display = 'none';
            expiredBox.classList.add('show');
            progressBar.parentElement.style.display = 'none';
            updateResendTimer();
        }
    }

    function setActionIcon(type) {
        if (type === 'check') {
            actionIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
        } else {
            actionIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>';
        }
    }

    /* ── Timer utama ── */
    function updateMainTimer() {
        const remaining = Math.max(0, Math.floor((mainEnd - Date.now()) / 1000));

        if (remaining <= 0) {
            localStorage.removeItem(TIMER_KEY);
            setMode('resend');
            return;
        }

        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        const el = document.getElementById('timerDisplay');
        if (el) el.textContent = pad(m) + ':' + pad(s);

        const pct = (remaining / TOTAL_SEC) * 100;
        progressBar.style.width = pct + '%';

        if (remaining <= 60) {
            progressBar.style.background = 'linear-gradient(90deg,#f43f5e,#e11d48)';
        } else if (remaining <= 300) {
            progressBar.style.background = 'linear-gradient(90deg,#f59e0b,#d97706)';
        } else {
            progressBar.style.background = 'linear-gradient(90deg,#22c55e,#16a34a)';
        }

        setTimeout(updateMainTimer, 1000);
    }

    /* ── Timer cooldown resend ── */
    function updateResendTimer() {
        const remaining = Math.max(0, Math.floor((resendEnd - Date.now()) / 1000));

        if (remaining <= 0) {
            localStorage.removeItem(TIMER_KEY + '_resend');
            setMode('resend');
            return;
        }

        resendMinEl.textContent = pad(Math.floor(remaining / 60));
        resendSecEl.textContent = pad(remaining % 60);
        setTimeout(updateResendTimer, 1000);
    }

    function pad(n) { return n.toString().padStart(2, '0'); }

    /* ══════════════════════════════════
       OTP INPUT HANDLING
    ══════════════════════════════════ */
    function initOTPInputs() {
        otpInputs.forEach((input, i) => {
            input.addEventListener('input', e => {
                if (!/^\d$/.test(e.target.value)) { e.target.value = ''; return; }
                e.target.classList.add('filled');
                if (i < otpInputs.length - 1) otpInputs[i + 1].focus();
                syncOTP();
                if (getOTP().length === 6 && mode === 'verify') {
                    setTimeout(handleAction, 300);
                }
            });

            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace') {
                    if (!e.target.value && i > 0) {
                        otpInputs[i - 1].value = '';
                        otpInputs[i - 1].classList.remove('filled');
                        otpInputs[i - 1].focus();
                    } else {
                        e.target.classList.remove('filled');
                    }
                    syncOTP();
                }
                if (e.key === 'ArrowRight' && i < otpInputs.length - 1) otpInputs[i + 1].focus();
                if (e.key === 'ArrowLeft'  && i > 0)                    otpInputs[i - 1].focus();
            });

            input.addEventListener('paste', e => {
                e.preventDefault();
                const digits = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                digits.split('').forEach((d, idx) => {
                    if (otpInputs[idx]) {
                        otpInputs[idx].value = d;
                        otpInputs[idx].classList.add('filled');
                    }
                });
                syncOTP();
                otpInputs[Math.min(digits.length - 1, 5)].focus();
                if (digits.length === 6 && mode === 'verify') setTimeout(handleAction, 300);
            });
        });
    }

    function getOTP()  { return Array.from(otpInputs).map(i => i.value).join(''); }
    function syncOTP() { otpValue.value = getOTP(); return otpValue.value; }

    /* ══════════════════════════════════
       HANDLE ACTION BUTTON
    ══════════════════════════════════ */
    window.handleAction = async function () {
        if (actionButton.disabled) return;
        if (mode === 'verify') await doVerify();
        else if (mode === 'resend') await doResend();
    };

    async function doVerify() {
        const otp = syncOTP();
        if (otp.length !== 6) {
            toast('error', 'Harap masukkan 6 digit kode OTP');
            for (const inp of otpInputs) { if (!inp.value) { inp.focus(); break; } }
            return;
        }

        setLoading(true, 'Memverifikasi...');

        try {
            @if($recaptchaSiteKey)
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'verify_otp' });
            document.getElementById('recaptcha_token').value = token;
            @endif
            otpForm.submit();
        } catch {
            setLoading(false);
            toast('error', 'Gagal verifikasi reCAPTCHA. Silakan coba lagi.');
        }
    }

    async function doResend() {
        setLoading(true, 'Mengirim ulang...');

        try {
            @if($recaptchaSiteKey)
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'resend_otp' });
            document.getElementById('resend_recaptcha_token').value = token;
            @endif

            const fd = new FormData(resendForm);
            const res = await fetch("{{ route('resend-otp') }}", {
                method: 'POST', body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
            });
            const data = await res.json();

            if (data.success) {
                resendEnd = Date.now() + RESEND_CD * 1000;
                localStorage.setItem(TIMER_KEY + '_resend', resendEnd);
                mainEnd = Date.now() + TOTAL_SEC * 1000;
                localStorage.setItem(TIMER_KEY, mainEnd);

                otpInputs.forEach(inp => { inp.value = ''; inp.classList.remove('filled', 'err'); });
                syncOTP();
                otpInputs[0].focus();

                setLoading(false);
                setMode('cooldown');
                toast('success', data.message || 'OTP baru telah dikirim ke email Anda!');
            } else {
                setLoading(false);
                setMode('resend');
                toast('error', data.message || 'Gagal mengirim ulang OTP');
            }
        } catch {
            setLoading(false);
            setMode('resend');
            toast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /* ── Loading state ── */
    function setLoading(on, text) {
        actionButton.disabled = on;
        loadingSpinner.style.display = on ? 'block' : 'none';
        actionIcon.style.display     = on ? 'none'  : '';
        if (on) actionText.textContent = text;
        else setMode(mode);
    }

    /* ── Toast — sama persis dengan app.blade.php ── */
    function toast(type, msg) {
        const el = document.createElement('div');
        el.className = `otp-toast toast-${type}`;

        const icons = {
            success: `<svg class="otp-toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`,
            error:   `<svg class="otp-toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`,
        };

        el.innerHTML = `
            ${icons[type] || icons.error}
            <div class="otp-toast-body">${msg}</div>
            <svg class="otp-toast-close" fill="currentColor" viewBox="0 0 20 20" onclick="this.parentElement.remove()">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        `;

        toastContainer.appendChild(el);

        setTimeout(() => {
            el.classList.add('otp-toast-exit');
            setTimeout(() => { if (el.parentElement) el.remove(); }, 250);
        }, 3500);
    }

    /* ── Init ── */
    document.addEventListener('DOMContentLoaded', () => {
        initOTPInputs();
        otpInputs[0].focus();
        @error('otp') otpInputs.forEach(i => i.classList.add('err')); @enderror
    });

})();
</script>
@endpush