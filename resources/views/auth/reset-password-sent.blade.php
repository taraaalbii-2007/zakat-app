@extends('layouts.auth')

@section('title', 'Reset Password Terkirim')
@section('auth-title', 'Link Reset Terkirim')
@section('auth-subtitle', 'Kami telah mengirim link reset password ke email Anda')

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
       EMAIL CARD
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
    .rp-email-card .rp-email-icon {
        width: 36px; height: 36px;
        background: #dcfce7;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rp-email-card .rp-email-icon svg { width: 16px; height: 16px; color: #16a34a; }
    .rp-email-card .rp-email-label {
        font-size: .7rem; color: #9ca3af; margin-bottom: .1rem;
    }
    .rp-email-card .rp-email-value {
        font-size: .82rem; font-weight: 700; color: #111827;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* ══════════════════════════════════
       BUTTON HIJAU — identik btn-masuk login
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
       BUTTON OUTLINE — kembali ke login  ← BARU
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

    /* ══════════════════════════════════
       PROGRESS BAR
    ══════════════════════════════════ */
    .rp-progress-wrap {
        height: 5px; background: #f3f4f6;
        border-radius: 9999px; overflow: hidden; margin-top: .75rem;
    }
    .rp-progress-bar {
        height: 100%; border-radius: 9999px;
        background: linear-gradient(90deg, #22c55e, #16a34a);
        transition: width 1s linear, background .5s;
    }

    /* ══════════════════════════════════
       EXPIRED / COOLDOWN BOX
    ══════════════════════════════════ */
    .rp-expired-box {
        display: none;
        background: #fff1f2;
        border: 1.5px solid #fecdd3;
        border-radius: 10px;
        padding: .9rem 1rem;
        margin-top: 1rem;
    }
    .rp-expired-box.show { display: block; }
    .rp-expired-header {
        display: flex; align-items: center; gap: .4rem; margin-bottom: .4rem;
    }
    .rp-expired-header svg { width: 15px; height: 15px; color: #f43f5e; flex-shrink: 0; }
    .rp-expired-header span { font-size: .78rem; font-weight: 700; color: #e11d48; }
    .rp-expired-sub { font-size: .73rem; color: #f43f5e; margin-bottom: .6rem; }
    .rp-cooldown-row {
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .rp-cooldown-box {
        background: #fff; border: 1.5px solid #fecdd3;
        border-radius: 8px; padding: .3rem .75rem;
        text-align: center; min-width: 54px;
    }
    .rp-cooldown-box span { display: block; font-size: 1.1rem; font-weight: 800; color: #e11d48; line-height: 1.2; }
    .rp-cooldown-box p   { font-size: .65rem; color: #f43f5e; margin: 0; }
    .rp-cooldown-sep { font-size: 1.1rem; font-weight: 800; color: #fca5a5; }

    /* ══════════════════════════════════
       FLASH ERROR
    ══════════════════════════════════ */
    .flash-box {
        display: flex; align-items: flex-start; gap: .5rem;
        border-radius: 10px; padding: .75rem 1rem;
        font-size: .78rem; margin-top: .75rem;
        border-width: 1.5px; border-style: solid;
    }
    .flash-box svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }
    .flash-box.error { background: #fff1f2; border-color: #fecdd3; color: #e11d48; }

    /* ══════════════════════════════════
       DIVIDER
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
       LINK COBA EMAIL LAIN
    ══════════════════════════════════ */
    .rp-alt-link {
        display: block; text-align: center;
        font-size: .75rem; color: #9ca3af;
        margin-top: .75rem;
    }
    .rp-alt-link a {
        font-weight: 600; color: #16a34a;
        text-decoration: none; margin-left: .2rem;
        transition: color .2s;
    }
    .rp-alt-link a:hover { color: #15803d; }

    /* ══════════════════════════════════
       TOAST NOTIFICATION
    ══════════════════════════════════ */
    @keyframes slide-down {
        from { transform: translateY(-100%); opacity: 0; }
        to   { transform: translateY(0);     opacity: 1; }
    }
    @keyframes slide-up {
        from { transform: translateY(0);     opacity: 1; }
        to   { transform: translateY(-100%); opacity: 0; }
    }
    .toast-anim-in  { animation: slide-down .3s ease-out; }
    .toast-anim-out { animation: slide-up  .3s ease-in;  }
</style>
@endpush

@section('auth-content')

{{-- ─── EMAIL CARD ─── --}}
<div class="rp-email-card">
    <div class="rp-email-icon">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
        </svg>
    </div>
    <div style="min-width:0;">
        <div class="rp-email-label">Email Tujuan</div>
        <div class="rp-email-value">{{ $maskedEmail ?? session('email') }}</div>
    </div>
</div>

{{-- ─── FORM KIRIM ULANG ─── --}}
<form action="{{ route('password.resend') }}" method="POST" id="resendForm">
    @csrf
    <input type="hidden" name="email" value="{{ session('email') ?? $email }}">
    @if($recaptchaSiteKey ?? false)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif

    <button type="button" class="btn-masuk" id="resendBtn"
            onclick="handleResend()" disabled>
        <div class="btn-spinner"></div>
        <span class="btn-label" id="btnLabel">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                    clip-rule="evenodd"/>
            </svg>
            Kirim Ulang dalam: <span id="timerDisplay">15:00</span>
        </span>
    </button>

    {{-- Progress Bar --}}
    <div class="rp-progress-wrap">
        <div class="rp-progress-bar" id="progressBar" style="width:100%"></div>
    </div>
</form>

{{-- ─── BUTTON KEMBALI KE LOGIN (outline) ─── --}}
<a href="{{ route('login') }}" class="btn-outline">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
    </svg>
    Kembali ke Halaman Login
</a>

{{-- ─── EXPIRED / COOLDOWN BOX ─── --}}
<div class="rp-expired-box" id="expiredBox">
    <div class="rp-expired-header">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Link reset password telah expired!</span>
    </div>
    <p class="rp-expired-sub">Kirim ulang tersedia dalam:</p>
    <div class="rp-cooldown-row">
        <div class="rp-cooldown-box">
            <span id="resendMinutes">03</span>
            <p>Menit</p>
        </div>
        <span class="rp-cooldown-sep">:</span>
        <div class="rp-cooldown-box">
            <span id="resendSeconds">00</span>
            <p>Detik</p>
        </div>
    </div>
</div>

{{-- ─── FLASH ERROR ─── --}}
@if(session('error') || $errors->any())
    <div class="flash-box error">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif

{{-- ─── DIVIDER + LINK EMAIL LAIN ─── --}}
<div class="or-row">
    <div class="or-line"></div>
    <span>atau</span>
    <div class="or-line"></div>
</div>

<span class="rp-alt-link">
    Tidak menerima email?
    <a href="{{ route('password.request') }}">Coba dengan email lain</a>
</span>

@endsection

@section('auth-footer')
    Sudah ingat password? <a href="{{ route('login') }}">Masuk sekarang</a>
@endsection

@push('scripts')
@if($recaptchaSiteKey ?? false)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
(function () {
    /* ── Konstanta ── */
    const TIMER_KEY   = 'reset_password_timer_{{ session('email') ?? $email }}';
    const TOTAL_SEC   = 15 * 60;
    const RESEND_CD   = 3  * 60;

    /* ── Elemen ── */
    const resendBtn    = document.getElementById('resendBtn');
    const btnLabel     = document.getElementById('btnLabel');
    const spinner      = document.querySelector('.btn-spinner');
    const timerDisplay = document.getElementById('timerDisplay');
    const progressBar  = document.getElementById('progressBar');
    const expiredBox   = document.getElementById('expiredBox');
    const resendMinEl  = document.getElementById('resendMinutes');
    const resendSecEl  = document.getElementById('resendSeconds');
    const resendForm   = document.getElementById('resendForm');

    /* ── State ── */
    let mainEnd   = localStorage.getItem(TIMER_KEY);
    let resendEnd = localStorage.getItem(TIMER_KEY + '_resend');

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
        setMode('counting');
        updateMainTimer();
    }

    /* ── Mode UI ── */
    function setMode(mode) {
        if (mode === 'counting') {
            resendBtn.disabled = true;
            resendBtn.style.display = '';
            expiredBox.classList.remove('show');
            progressBar.parentElement.style.display = '';
        } else if (mode === 'resend') {
            resendBtn.disabled = false;
            resendBtn.style.display = '';
            expiredBox.classList.remove('show');
            progressBar.parentElement.style.display = 'none';
            btnLabel.innerHTML = `
                <svg fill="currentColor" viewBox="0 0 20 20" style="width:18px;height:18px;flex-shrink:0;">
                    <path fill-rule="evenodd"
                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                        clip-rule="evenodd"/>
                </svg>
                Kirim Ulang Link Reset Password`;
        } else if (mode === 'cooldown') {
            resendBtn.style.display = 'none';
            expiredBox.classList.add('show');
            progressBar.parentElement.style.display = 'none';
            updateResendTimer();
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

    /* ── Timer cooldown ── */
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

    /* ── Handle Kirim Ulang ── */
    window.handleResend = async function () {
        if (resendBtn.disabled) return;

        resendBtn.disabled = true;
        resendBtn.classList.add('loading');

        try {
            @if($recaptchaSiteKey ?? false)
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'resend_reset_link' });
            document.getElementById('recaptcha_token').value = token;
            @endif

            const fd  = new FormData(resendForm);
            const res = await fetch('{{ route('password.resend') }}', {
                method: 'POST', body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
            });
            const data = await res.json();

            if (data.success) {
                resendEnd = Date.now() + RESEND_CD * 1000;
                localStorage.setItem(TIMER_KEY + '_resend', resendEnd);
                mainEnd = Date.now() + TOTAL_SEC * 1000;
                localStorage.setItem(TIMER_KEY, mainEnd);
                resendBtn.classList.remove('loading');
                setMode('cooldown');
                toast('success', data.message || 'Link reset password berhasil dikirim ulang!');
            } else {
                resendBtn.classList.remove('loading');
                resendBtn.disabled = false;
                setMode('resend');
                toast('error', data.message || 'Gagal mengirim ulang email');
            }
        } catch {
            resendBtn.classList.remove('loading');
            resendBtn.disabled = false;
            setMode('resend');
            toast('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    };

    /* ── Toast ── */
    function toast(type, msg) {
        document.querySelectorAll('.rp-toast').forEach(el => el.remove());
        const el = document.createElement('div');
        el.className = 'rp-toast toast-anim-in';
        el.style.cssText = `position:fixed;top:1rem;right:1rem;z-index:9999;padding:.75rem 1rem;border-radius:10px;color:#fff;display:flex;align-items:center;gap:.5rem;font-size:.82rem;font-weight:600;font-family:Inter,sans-serif;box-shadow:0 4px 14px rgba(0,0,0,.18);max-width:300px;background:${type === 'success' ? '#16a34a' : '#f43f5e'};`;
        el.innerHTML = `
            <svg style="width:16px;height:16px;flex-shrink:0" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success'
                    ? '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                }
            </svg>
            <span>${msg}</span>`;
        document.body.appendChild(el);
        setTimeout(() => {
            el.classList.replace('toast-anim-in', 'toast-anim-out');
            setTimeout(() => el.remove(), 300);
        }, 5000);
    }
})();
</script>
@endpush