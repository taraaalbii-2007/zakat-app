@extends('layouts.auth')

@section('title', 'Reset Password Terkirim')

@section('card-title', 'Link Reset Password Terkirim')
@section('card-subtitle', 'Kami telah mengirim link reset password ke email Anda')

@section('content')
<div class="space-y-6">

    {{-- Email Display --}}
    <div class="bg-primary-50 border border-primary-100 rounded-xl p-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-neutral-500 mb-1">Email Tujuan:</p>
                <p class="text-sm font-medium text-neutral-800 truncate">{{ $maskedEmail ?? session('email') }}</p>
            </div>
        </div>
    </div>

    {{-- Tombol Kirim Ulang dengan Timer --}}
    <div class="space-y-4">
        <form action="{{ route('password.resend') }}" method="POST" id="resendForm">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') ?? $email }}">
            
            @if ($recaptchaSiteKey ?? false)
            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            @endif
            
            <button type="button" 
                    id="resendBtn"
                    class="w-full bg-gradient-primary text-white font-semibold py-3.5 px-6 rounded-xl shadow-nz-lg hover:shadow-nz-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:hover:shadow-nz-lg"
                    disabled
                    onclick="handleResendEmail()">
                <span class="flex items-center justify-center gap-2">
                    {{-- Loading Spinner --}}
                    <svg class="w-5 h-5 animate-spin hidden" id="loadingSpinner" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    {{-- Resend Icon --}}
                    <svg class="w-5 h-5" id="resendIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    
                    {{-- Button Text --}}
                    <span id="btnText">
                        Kirim Ulang dalam: <span id="timerDisplay">15:00</span>
                    </span>
                </span>
            </button>
        </form>
        
        {{-- Progress Bar --}}
        <div class="h-1.5 bg-neutral-200 rounded-full overflow-hidden">
            <div id="progressBar" class="h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full transition-all duration-1000" style="width: 100%"></div>
        </div>
    </div>

    {{-- Expired Message (hidden by default) --}}
    <div id="expiredMessage" class="hidden bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-left">
                <p class="text-sm text-red-600 font-medium">Link reset password telah expired!</p>
                <p class="text-sm text-red-500 mt-1">Kirim ulang akan tersedia dalam:</p>
                
                {{-- Resend Countdown Timer --}}
                <div class="flex items-center justify-center gap-3 mt-3">
                    <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-2 min-w-[70px]">
                        <span id="resendMinutes" class="text-xl font-bold text-red-600">03</span>
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

    {{-- Error Message --}}
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

    {{-- Footer Links --}}
    <div class="pt-6 border-t border-neutral-200">
        <div class="space-y-3 text-center">
            <p class="text-sm text-neutral-600">
                <a href="{{ route('login') }}" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali ke Halaman Login
                </a>
            </p>
            <p class="text-sm text-neutral-600">
                Tidak menerima email?
                <a href="{{ route('password.request') }}" class="text-primary hover:text-primary-dark font-medium ml-1">
                    Coba dengan email lain
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- reCAPTCHA v3 Script --}}
@if ($recaptchaSiteKey ?? false)
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const TIMER_KEY = 'reset_password_timer_{{ session('email') ?? $email }}';
    const TOTAL_SECONDS = 15 * 60; // 15 menit untuk timer utama
    const RESEND_COOLDOWN = 3 * 60; // 3 menit untuk cooldown kirim ulang
    
    // Elements - dengan null check
    const timerDisplay = document.getElementById('timerDisplay');
    const progressBar = document.getElementById('progressBar');
    const resendBtn = document.getElementById('resendBtn');
    const btnText = document.getElementById('btnText');
    const expiredMessage = document.getElementById('expiredMessage');
    const resendMinutesEl = document.getElementById('resendMinutes');
    const resendSecondsEl = document.getElementById('resendSeconds');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const resendIcon = document.getElementById('resendIcon');
    const resendForm = document.getElementById('resendForm');
    
    // Validasi semua elemen penting ada
    if (!resendBtn || !btnText || !progressBar) {
        console.error('Required elements not found!');
        return;
    }
    
    // Timer state
    let mainTimerEndTime = localStorage.getItem(TIMER_KEY);
    let resendTimerEndTime = localStorage.getItem(TIMER_KEY + '_resend');
    let isMainTimerActive = true;
    
    @if(session('success'))
        // Reset semua timer jika baru saja berhasil kirim ulang
        mainTimerEndTime = null;
        resendTimerEndTime = null;
        localStorage.removeItem(TIMER_KEY);
        localStorage.removeItem(TIMER_KEY + '_resend');
    @endif
    
    // Setup main timer (reset password link)
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
            // Cooldown selesai, tampilkan tombol aktif
            resendBtn.disabled = false;
            btnText.innerHTML = 'Kirim Ulang Link Reset Password';
            if (expiredMessage) expiredMessage.classList.add('hidden');
            localStorage.removeItem(TIMER_KEY + '_resend');
        } else {
            // Masih dalam cooldown, tampilkan expired message
            resendBtn.disabled = true;
            btnText.innerHTML = 'Kirim Ulang Link Reset Password';
            if (expiredMessage) {
                expiredMessage.classList.remove('hidden');
                resendBtn.classList.add('hidden');
            }
        }
    } else if (Date.now() >= mainTimerEndTime) {
        // Main timer sudah expired tanpa cooldown aktif
        isMainTimerActive = false;
        resendBtn.disabled = false;
        btnText.innerHTML = 'Kirim Ulang Link Reset Password';
        if (expiredMessage) expiredMessage.classList.add('hidden');
    }
    
    function updateMainTimer() {
        if (!timerDisplay || !progressBar) return;
        
        const now = Date.now();
        const remaining = Math.max(0, mainTimerEndTime - now);
        const remainingSeconds = Math.floor(remaining / 1000);
        
        if (remainingSeconds <= 0) {
            // Main timer expired
            isMainTimerActive = false;
            resendBtn.disabled = false;
            btnText.innerHTML = 'Kirim Ulang Link Reset Password';
            progressBar.style.width = '0%';
            localStorage.removeItem(TIMER_KEY);
            return;
        }
        
        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        
        // Update timer display in button
        timerDisplay.textContent = mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
        
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
        if (!resendMinutesEl || !resendSecondsEl) return;
        
        const now = Date.now();
        const remaining = Math.max(0, resendTimerEndTime - now);
        const remainingSeconds = Math.floor(remaining / 1000);
        
        if (remainingSeconds <= 0) {
            // Cooldown selesai
            if (expiredMessage) expiredMessage.classList.add('hidden');
            resendBtn.classList.remove('hidden');
            resendBtn.disabled = false;
            btnText.innerHTML = 'Kirim Ulang Link Reset Password';
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
    
    // Handle resend dengan reCAPTCHA
    window.handleResendEmail = async function() {
        if (resendBtn.disabled) return;
        
        try {
            // Validasi elemen ada
            if (!loadingSpinner || !resendIcon || !btnText) {
                console.error('UI elements not found');
                alert('Terjadi kesalahan UI. Silakan refresh halaman.');
                return;
            }
            
            // Execute reCAPTCHA jika ada
            @if ($recaptchaSiteKey ?? false)
            const recaptchaTokenInput = document.getElementById('recaptcha_token');
            if (!recaptchaTokenInput) {
                console.error('reCAPTCHA token input not found');
                alert('Terjadi kesalahan reCAPTCHA. Silakan refresh halaman.');
                return;
            }
            
            const token = await grecaptcha.execute('{{ $recaptchaSiteKey }}', { 
                action: 'resend_reset_link' 
            });
            recaptchaTokenInput.value = token;
            @endif
            
            // Show loading state
            resendBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            resendIcon.classList.add('hidden');
            btnText.innerHTML = 'Mengirim...';
            
            // Kirim AJAX request
            if (!resendForm) {
                console.error('Form not found');
                alert('Terjadi kesalahan. Silakan refresh halaman.');
                return;
            }
            
            const formData = new FormData(resendForm);
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            
            const response = await fetch('{{ route('password.resend') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Setup cooldown timer 3 menit
                resendTimerEndTime = Date.now() + (RESEND_COOLDOWN * 1000);
                localStorage.setItem(TIMER_KEY + '_resend', resendTimerEndTime);
                
                // Update UI
                resendBtn.classList.add('hidden');
                loadingSpinner.classList.add('hidden');
                resendIcon.classList.remove('hidden');
                if (expiredMessage) expiredMessage.classList.remove('hidden');
                
                // Reset main timer untuk link baru
                mainTimerEndTime = Date.now() + (TOTAL_SECONDS * 1000);
                localStorage.setItem(TIMER_KEY, mainTimerEndTime);
                isMainTimerActive = false;
                
                // Start cooldown timer
                updateResendTimer();
                
                // Show success notification
                showNotification('success', data.message || 'Link reset password telah dikirim ulang ke email Anda.');
            } else {
                // Show error message
                showNotification('error', data.message || 'Gagal mengirim ulang email');
                
                // Reset button
                resendBtn.disabled = false;
                loadingSpinner.classList.add('hidden');
                resendIcon.classList.remove('hidden');
                btnText.innerHTML = 'Kirim Ulang Link Reset Password';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
            
            // Reset button
            if (resendBtn && loadingSpinner && resendIcon && btnText) {
                resendBtn.disabled = false;
                loadingSpinner.classList.add('hidden');
                resendIcon.classList.remove('hidden');
                btnText.innerHTML = 'Kirim Ulang Link Reset Password';
            }
        }
    };
    
    // Helper function untuk show notification
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg animate-slide-down ${
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
        
        // Auto remove setelah 5 detik
        setTimeout(() => {
            notification.classList.add('animate-slide-up');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
});
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

#resendBtn:not(:disabled):hover {
    transform: translateY(-0.125rem);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush