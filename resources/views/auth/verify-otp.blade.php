@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('card-title', 'Masukkan Kode OTP')

@section('card-subtitle', 'Kami telah mengirim kode 6 digit ke email Anda')

@push('styles')
@if ($recaptchaSiteKey)
<style>
    .g-recaptcha-badge {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
        border-radius: 4px !important;
    }
</style>
@endif
@endpush

@section('content')
<div class="mb-6 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-50 rounded-full mb-4">
        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <p class="text-sm text-neutral-600">
        Kode dikirim ke: <span class="font-semibold text-neutral-800">{{ $maskedEmail ?? $email }}</span>
    </p>
</div>

<!-- Form untuk verifikasi OTP -->
<form method="POST" action="{{ route('verify-otp.submit') }}" class="space-y-6" id="otpForm">
    @csrf
    
    @if ($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    @endif
    
    <input type="hidden" name="email" value="{{ $email }}">
    
    <!-- OTP Input Fields -->
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-neutral-700 text-center">
            Kode OTP
        </label>
        <div class="flex justify-center gap-3">
            @for ($i = 1; $i <= 6; $i++)
            <input 
                type="text" 
                maxlength="1" 
                class="otp-input w-12 h-14 text-center text-2xl font-bold bg-neutral-50 border-2 border-neutral-200 rounded-xl text-neutral-800 focus:outline-none focus:border-primary focus:bg-white transition-all duration-200 @error('otp') border-danger @enderror"
                data-index="{{ $i - 1 }}"
                inputmode="numeric"
                pattern="[0-9]"
                autocomplete="off"
            >
            @endfor
        </div>
        <input type="hidden" name="otp" id="otpValue">
        
        @error('otp')
            <p class="text-danger text-xs mt-2 text-center flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    
    <!-- Single Multi-function Button -->
    <div class="space-y-4">
        <!-- Button Multifungsi (Verifikasi/Kirim Ulang) -->
        <button 
            type="button" 
            id="actionButton"
            class="w-full bg-gradient-primary text-white font-semibold py-3.5 px-6 rounded-xl shadow-nz-lg hover:shadow-nz-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:hover:shadow-nz-lg"
            onclick="handleAction()"
            disabled
        >
            <span class="flex items-center justify-center gap-2">
                <!-- Loading Spinner -->
                <svg class="w-5 h-5 animate-spin hidden" id="loadingSpinner" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                <!-- Action Icon -->
                <svg class="w-5 h-5" id="actionIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                
                <!-- Button Text -->
                <span id="actionText">
                    Verifikasi OTP (<span id="timerDisplay">15:00</span>)
                </span>
            </span>
        </button>
        
        <!-- Progress Bar -->
        <div class="h-1.5 bg-neutral-200 rounded-full overflow-hidden">
            <div id="progressBar" class="h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full transition-all duration-1000" style="width: 100%"></div>
        </div>
    </div>

    <!-- Expired Message (hidden by default) -->
    <div id="expiredMessage" class="hidden bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-left">
                <p class="text-sm text-red-600 font-medium">Kode OTP telah expired!</p>
                <p class="text-sm text-red-500 mt-1">Kirim ulang OTP akan tersedia dalam:</p>
                
                <!-- Resend Countdown Timer -->
                <div class="flex items-center justify-center gap-3 mt-3">
                    <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-2 min-w-[70px]">
                        <span id="resendMinutes" class="text-xl font-bold text-red-600">01</span>
                        <p class="text-xs text-red-500">Menit</p>
                    </div>
                    <span class="text-xl font-bold text-red-400">:</span>
                    <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-2 min-w-[70px]">
                        <span id="resendSeconds" class="text-xl font-bold text-red-600">00</span>
                        <p class="text-xs text-red-500">Detik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-green-600">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error') || $errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-600">{{ session('error') ?? $errors->first() }}</p>
            </div>
        </div>
    @endif
</form>

<!-- Form tersembunyi untuk resend OTP -->
<form method="POST" action="{{ route('resend-otp') }}" id="resendForm" class="hidden">
    @csrf
    
    @if ($recaptchaSiteKey)
        <input type="hidden" name="recaptcha_token" id="resend_recaptcha_token">
    @endif
    
    <input type="hidden" name="email" value="{{ $email }}">
</form>
@endsection

@section('footer-links')
<p class="text-white text-sm">
    Salah email? 
    <a href="{{ route('register') }}" class="font-semibold text-primary-100 hover:text-white transition-colors underline underline-offset-2">
        Daftar ulang
    </a>
</p>
@endsection

@push('scripts')
<!-- reCAPTCHA v3 Script -->
@if ($recaptchaSiteKey)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
(function() {
    const TIMER_KEY = 'otp_timer_{{ $email }}';
    const TOTAL_SECONDS = 15 * 60; // 15 menit untuk timer utama
    const RESEND_COOLDOWN = 1 * 60; // 1 menit untuk cooldown kirim ulang
    
    // Elements
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpValue = document.getElementById('otpValue');
    const otpForm = document.getElementById('otpForm');
    const resendForm = document.getElementById('resendForm');
    
    const actionButton = document.getElementById('actionButton');
    const actionText = document.getElementById('actionText');
    const actionIcon = document.getElementById('actionIcon');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const timerDisplay = document.getElementById('timerDisplay');
    const progressBar = document.getElementById('progressBar');
    
    const expiredMessage = document.getElementById('expiredMessage');
    const resendMinutesEl = document.getElementById('resendMinutes');
    const resendSecondsEl = document.getElementById('resendSeconds');
    
    // Timer state
    let mainTimerEndTime = localStorage.getItem(TIMER_KEY);
    let resendTimerEndTime = localStorage.getItem(TIMER_KEY + '_resend');
    let isMainTimerActive = true;
    let buttonMode = 'verify'; // 'verify' atau 'resend'
    
    @if(session('success'))
        // Reset semua timer jika baru saja berhasil kirim ulang
        mainTimerEndTime = null;
        resendTimerEndTime = null;
        localStorage.removeItem(TIMER_KEY);
        localStorage.removeItem(TIMER_KEY + '_resend');
    @endif
    
    // Setup main timer (OTP verification)
    if (!mainTimerEndTime) {
        mainTimerEndTime = Date.now() + (TOTAL_SECONDS * 1000);
        localStorage.setItem(TIMER_KEY, mainTimerEndTime);
    } else {
        mainTimerEndTime = parseInt(mainTimerEndTime);
    }
    
    // Setup resend cooldown timer jika ada
    if (resendTimerEndTime) {
        resendTimerEndTime = parseInt(resendTimerEndTime);
        isMainTimerActive = false;
        
        // Cek apakah cooldown sudah selesai
        if (Date.now() >= resendTimerEndTime) {
            // Cooldown selesai, set mode resend
            buttonMode = 'resend';
            setButtonMode('resend');
            localStorage.removeItem(TIMER_KEY + '_resend');
        } else {
            // Masih dalam cooldown
            buttonMode = 'cooldown';
            actionButton.disabled = true;
            actionButton.classList.add('hidden');
            expiredMessage.classList.remove('hidden');
        }
    } else if (Date.now() >= mainTimerEndTime) {
        // Main timer sudah expired tanpa cooldown aktif
        isMainTimerActive = false;
        buttonMode = 'resend';
        setButtonMode('resend');
    } else {
        // Timer masih berjalan, mode verify
        buttonMode = 'verify';
        setButtonMode('verify');
    }
    
    function setButtonMode(mode) {
        if (mode === 'verify') {
            actionButton.disabled = false;
            actionIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            actionText.innerHTML = 'Verifikasi OTP (<span id="timerDisplay">15:00</span>)';
            progressBar.parentElement.classList.remove('hidden');
            expiredMessage.classList.add('hidden');
        } else if (mode === 'resend') {
            actionButton.disabled = false;
            actionIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>';
            actionText.textContent = 'Kirim Ulang Kode OTP';
            progressBar.style.width = '0%';
            progressBar.parentElement.classList.add('hidden');
            expiredMessage.classList.add('hidden');
        }
    }
    
    function updateMainTimer() {
        const now = Date.now();
        const remaining = Math.max(0, mainTimerEndTime - now);
        const remainingSeconds = Math.floor(remaining / 1000);
        
        if (remainingSeconds <= 0) {
            // Main timer expired
            isMainTimerActive = false;
            buttonMode = 'resend';
            setButtonMode('resend');
            localStorage.removeItem(TIMER_KEY);
            return;
        }
        
        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        
        // Update timer display in button
        const timerEl = document.getElementById('timerDisplay');
        if (timerEl) {
            timerEl.textContent = mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
        }
        
        // Update progress bar
        const progress = (remainingSeconds / TOTAL_SECONDS) * 100;
        progressBar.style.width = progress + '%';
        
        // Update warna progress bar
        if (remainingSeconds <= 60) {
            progressBar.className = 'h-full bg-gradient-to-r from-red-500 to-red-400 rounded-full transition-all duration-1000';
        } else if (remainingSeconds <= 300) {
            progressBar.className = 'h-full bg-gradient-to-r from-yellow-500 to-yellow-400 rounded-full transition-all duration-1000';
        } else {
            progressBar.className = 'h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full transition-all duration-1000';
        }
        
        setTimeout(updateMainTimer, 1000);
    }
    
    function updateResendTimer() {
        const now = Date.now();
        const remaining = Math.max(0, resendTimerEndTime - now);
        const remainingSeconds = Math.floor(remaining / 1000);
        
        if (remainingSeconds <= 0) {
            // Cooldown selesai
            expiredMessage.classList.add('hidden');
            actionButton.classList.remove('hidden');
            buttonMode = 'resend';
            setButtonMode('resend');
            localStorage.removeItem(TIMER_KEY + '_resend');
            return;
        }
        
        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        
        resendMinutesEl.textContent = mins.toString().padStart(2, '0');
        resendSecondsEl.textContent = secs.toString().padStart(2, '0');
        
        setTimeout(updateResendTimer, 1000);
    }
    
    // Start appropriate timer
    if (isMainTimerActive) {
        updateMainTimer();
    } else if (resendTimerEndTime) {
        updateResendTimer();
    }
    
    // Initialize OTP input handling
    function initializeOTPInputs() {
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                if (!/^\d$/.test(value)) {
                    e.target.value = '';
                    return;
                }
                
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                updateOTPValue();
                
                // Auto verifikasi jika semua field terisi DAN dalam mode verify
                checkAndAutoVerify();
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    updateOTPValue();
                }
                
                if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                const digits = pastedData.replace(/\D/g, '').slice(0, 6);
                
                digits.split('').forEach((digit, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = digit;
                    }
                });
                
                updateOTPValue();
                
                const lastIndex = Math.min(digits.length - 1, otpInputs.length - 1);
                otpInputs[lastIndex].focus();
                
                // Auto verifikasi jika paste lengkap DAN mode verify
                if (digits.length === 6 && buttonMode === 'verify') {
                    setTimeout(() => {
                        checkAndAutoVerify();
                    }, 100);
                }
            });
        });
    }
    
    // Fungsi untuk cek dan auto verifikasi
    function checkAndAutoVerify() {
        const otp = updateOTPValue();
        
        // Cek jika OTP sudah lengkap (6 digit) DAN dalam mode verify
        if (otp.length === 6 && buttonMode === 'verify') {
            // Delay kecil untuk memberi feedback visual
            setTimeout(() => {
                handleAction();
            }, 300); // 300ms delay untuk UX yang lebih baik
        }
    }
    
    function updateOTPValue() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpValue.value = otp;
        return otp;
    }
    
    // Handle button action
    window.handleAction = async function() {
        if (actionButton.disabled) return;
        
        if (buttonMode === 'verify') {
            await handleVerifyOTP();
        } else if (buttonMode === 'resend') {
            await handleResendOTP();
        }
    };
    
    // Handle OTP verification
    async function handleVerifyOTP() {
        const otp = updateOTPValue();
        if (otp.length !== 6) {
            showNotification('error', 'Harap masukkan 6 digit kode OTP');
            // Focus ke input pertama yang kosong
            for (let i = 0; i < otpInputs.length; i++) {
                if (!otpInputs[i].value) {
                    otpInputs[i].focus();
                    break;
                }
            }
            return;
        }
        
        // Show loading
        actionButton.disabled = true;
        loadingSpinner.classList.remove('hidden');
        actionIcon.classList.add('hidden');
        actionText.textContent = 'Memverifikasi...';
        
        try {
            @if ($recaptchaSiteKey)
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { 
                action: 'verify_otp' 
            });
            document.getElementById('recaptcha_token').value = token;
            @endif
            
            otpForm.submit();
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            
            actionButton.disabled = false;
            loadingSpinner.classList.add('hidden');
            actionIcon.classList.remove('hidden');
            setButtonMode('verify');
            
            showNotification('error', 'Terjadi kesalahan saat verifikasi reCAPTCHA. Silakan coba lagi.');
        }
    }
    
    // Handle resend OTP
    async function handleResendOTP() {
        // Show loading
        actionButton.disabled = true;
        loadingSpinner.classList.remove('hidden');
        actionIcon.classList.add('hidden');
        actionText.textContent = 'Mengirim ulang...';
        
        try {
            @if ($recaptchaSiteKey)
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { 
                action: 'resend_otp' 
            });
            document.getElementById('resend_recaptcha_token').value = token;
            @endif
            
            const formData = new FormData(resendForm);
            const response = await fetch("{{ route('resend-otp') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Setup cooldown timer
                resendTimerEndTime = Date.now() + (RESEND_COOLDOWN * 1000);
                localStorage.setItem(TIMER_KEY + '_resend', resendTimerEndTime);
                
                // Update UI
                actionButton.classList.add('hidden');
                loadingSpinner.classList.add('hidden');
                actionIcon.classList.remove('hidden');
                expiredMessage.classList.remove('hidden');
                
                // Reset main timer untuk OTP baru
                mainTimerEndTime = Date.now() + (TOTAL_SECONDS * 1000);
                localStorage.setItem(TIMER_KEY, mainTimerEndTime);
                isMainTimerActive = false;
                buttonMode = 'cooldown';
                
                // Reset OTP inputs
                otpInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('border-danger');
                });
                otpInputs[0].focus();
                updateOTPValue();
                
                // Start cooldown timer
                updateResendTimer();
                
                showNotification('success', data.message || 'OTP baru telah dikirim ke email Anda!');
            } else {
                // Show error
                showNotification('error', data.message || 'Gagal mengirim ulang OTP');
                
                actionButton.disabled = false;
                loadingSpinner.classList.add('hidden');
                actionIcon.classList.remove('hidden');
                setButtonMode('resend');
            }
        } catch (error) {
            console.error('Resend OTP error:', error);
            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
            
            actionButton.disabled = false;
            loadingSpinner.classList.add('hidden');
            actionIcon.classList.remove('hidden');
            setButtonMode('resend');
        }
    }
    
    // Helper function untuk show notification
    function showNotification(type, message) {
        // Cek jika sudah ada notification yang sama
        const existingNotification = document.querySelector('.notification-' + type);
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `notification-${type} fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg animate-slide-down ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' 
                        ? '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>'
                        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                    }
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('animate-slide-up');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeOTPInputs();
        otpInputs[0].focus();
        
        @error('otp')
            otpInputs.forEach(input => {
                input.classList.add('border-danger');
            });
        @enderror
    });
})();
</script>

<style>
@keyframes slide-down {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slide-up {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(-100%);
        opacity: 0;
    }
}

.animate-slide-down {
    animation: slide-down 0.3s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.3s ease-in;
}

/* Tambahan style untuk OTP input yang sedang aktif */
.otp-input:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.otp-input.filled {
    background-color: #f8fafc;
    border-color: #94a3b8;
}
</style>
@endpush