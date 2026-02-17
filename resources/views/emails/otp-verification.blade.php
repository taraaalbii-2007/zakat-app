<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi - Niat Zakat</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #fafbfc 0%, #f3f4f6 100%);
            color: #1a1a1a;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding: 20px;
            min-height: 100vh;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        .email-card {
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 50px rgba(45, 105, 54, 0.12);
            margin-bottom: 30px;
        }

        /* ==================== HEADER ==================== */
        .email-header {
            padding: 48px 40px 32px;
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, #2d6936 0%, #7cb342 100%);
            color: white;
        }

        .header-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2d6936, #7cb342, #4caf50);
        }

        /* Logo Container */
        .logo-container {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            padding: 10px;
        }

        .logo-container:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
        }

        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .email-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .email-subtitle {
            font-size: 16px;
            opacity: 0.95;
            max-width: 400px;
            margin: 0 auto;
            font-weight: 400;
        }

        /* ==================== CONTENT ==================== */
        .email-content {
            padding: 40px;
        }

        .greeting-section {
            margin-bottom: 32px;
            text-align: center;
        }

        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .instruction {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
            margin-top: 12px;
        }

        /* ==================== OTP SECTION ==================== */
        .otp-section {
            background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 40px 32px;
            margin: 32px 0;
            border: 3px dashed #7cb342;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(45, 105, 54, 0.08);
        }

        .otp-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 179, 66, 0.03) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .otp-label {
            font-size: 14px;
            color: #2d6936;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .otp-code-wrapper {
            position: relative;
            z-index: 1;
        }

        .otp-code {
            font-size: 56px;
            font-weight: 800;
            color: #2d6936;
            letter-spacing: 16px;
            margin: 16px 0;
            font-family: 'Courier New', monospace;
            text-shadow: 2px 2px 4px rgba(45, 105, 54, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
        }

        .otp-code:hover {
            transform: scale(1.05);
            color: #7cb342;
            text-shadow: 3px 3px 6px rgba(124, 179, 66, 0.2);
        }

        .otp-timer {
            font-size: 14px;
            color: #558b2f;
            font-weight: 600;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .timer-icon {
            width: 18px;
            height: 18px;
            color: #7cb342;
        }

        .copy-otp-btn {
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 12px rgba(45, 105, 54, 0.25);
            position: relative;
            z-index: 1;
        }

        .copy-otp-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 105, 54, 0.35);
            background: linear-gradient(135deg, #1e5223, #558b2f);
        }

        .copy-otp-btn:active {
            transform: translateY(0);
        }

        .copy-otp-btn.copied {
            background: linear-gradient(135deg, #4caf50, #81c784);
        }

        /* ==================== WARNING SECTION ==================== */
        .warning-section {
            background: linear-gradient(135deg, #fff3cd, #ffeeba);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #ff9800;
            margin: 28px 0;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.1);
        }

        .warning-title {
            font-size: 15px;
            font-weight: 700;
            color: #856404;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .warning-icon {
            width: 20px;
            height: 20px;
            color: #ff9800;
        }

        .warning-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .warning-list li {
            font-size: 13px;
            color: #856404;
            line-height: 1.7;
            padding-left: 24px;
            position: relative;
            margin-bottom: 6px;
        }

        .warning-list li::before {
            content: '⚠️';
            position: absolute;
            left: 0;
            font-size: 14px;
        }

        /* ==================== FOOTER ==================== */
        .email-footer {
            text-align: center;
            padding: 40px 0 20px;
        }

        .footer-logo-text {
            color: #2d6936;
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-logo-text:hover {
            color: #7cb342;
            transform: scale(1.05);
        }

        .copyright {
            font-size: 12px;
            color: #999;
            margin-bottom: 6px;
        }

        .footer-divider {
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #c5e1a5, transparent);
            margin: 16px auto 12px;
        }

        .auto-email {
            font-size: 12px;
            color: #aaa;
        }

        /* ==================== CONFETTI ANIMATION ==================== */
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #7cb342;
            pointer-events: none;
            z-index: 9999;
            animation: confetti-fall 3s ease-in-out forwards;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .otp-section {
            animation: slideIn 0.6s ease-out 0.2s forwards;
            opacity: 0;
        }

        .warning-section {
            animation: slideIn 0.6s ease-out 0.3s forwards;
            opacity: 0;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .email-wrapper {
                max-width: 100%;
            }

            .email-header {
                padding: 36px 24px 24px;
            }

            .email-content {
                padding: 28px 24px;
            }

            .email-header h1 {
                font-size: 26px;
            }

            .logo-container {
                width: 70px;
                height: 70px;
                padding: 8px;
            }

            .otp-section {
                padding: 32px 24px;
            }

            .otp-code {
                font-size: 42px;
                letter-spacing: 12px;
                padding: 10px 16px;
            }

            .warning-section {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .email-header h1 {
                font-size: 22px;
            }

            .otp-code {
                font-size: 36px;
                letter-spacing: 8px;
            }
        }

        /* ==================== EASTER EGG STYLES ==================== */
        .konami-activated {
            animation: rainbow 2s linear infinite;
        }

        @keyframes rainbow {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }

        .secret-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            padding: 40px 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            text-align: center;
            animation: popIn 0.5s ease-out forwards;
            font-size: 24px;
            font-weight: 700;
        }

        @keyframes popIn {
            0% {
                transform: translate(-50%, -50%) scale(0);
            }
            50% {
                transform: translate(-50%, -50%) scale(1.1);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-card">
            <!-- ==================== HEADER ==================== -->
            <div class="email-header">
                <div class="header-decoration"></div>
                
                <!-- Logo Container (dengan easter egg!) -->
                @php
                    $config = \App\Models\KonfigurasiAplikasi::first();
                @endphp
                
                @if($config && $config->logo_aplikasi)
                <div class="logo-container" id="logoContainer" onclick="handleLogoClick()">
                    <img src="{{ asset('storage/' . $config->logo_aplikasi) }}" alt="Logo {{ $config->nama_aplikasi ?? 'Aplikasi' }}" onerror="this.parentElement.style.display='none'">
                </div>
                @endif

                <h1>Verifikasi Email Anda</h1>
                <p class="email-subtitle">Gunakan kode OTP berikut untuk memverifikasi email Anda di {{ $config->nama_aplikasi ?? 'Niat Zakat' }}</p>
            </div>

            <!-- ==================== CONTENT ==================== -->
            <div class="email-content">
                <!-- Greeting -->
                <div class="greeting-section slide-in">
                    <p class="greeting">Assalamu'alaikum,</p>
                    <p class="instruction">Terima kasih telah bergabung dengan <strong>Niat Zakat</strong>. Berikut adalah kode OTP untuk memverifikasi email Anda:</p>
                </div>

                <!-- ==================== OTP SECTION ==================== -->
                <div class="otp-section">
                    <div class="otp-label">KODE OTP ANDA</div>
                    <div class="otp-code-wrapper">
                        <div class="otp-code" id="otpCode" onclick="copyOtpCode()">{{ $otp ?? '123456' }}</div>
                    </div>
                    <div class="otp-timer">
                        <svg class="timer-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        <span>Berlaku selama <strong>{{ $expiresInMinutes ?? 10 }} menit</strong></span>
                    </div>
                    <button class="copy-otp-btn" id="copyBtn" onclick="copyOtpCode()">
                        Salin Kode OTP
                    </button>
                </div>

                <!-- ==================== WARNING SECTION ==================== -->
                <div class="warning-section">
                    <div class="warning-title">
                        <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        PENTING - Perhatikan Hal Berikut
                    </div>
                    <ul class="warning-list">
                        <li><strong>Jangan bagikan</strong> kode OTP ini kepada siapapun, termasuk petugas Niat Zakat</li>
                        <li>Kode akan <strong>kedaluwarsa dalam {{ $expiresInMinutes ?? 10 }} menit</strong></li>
                        <li>Jika Anda <strong>tidak melakukan pendaftaran</strong>, abaikan email ini</li>
                        <li>Segera ubah password jika Anda mencurigai adanya akses tidak sah</li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- ==================== FOOTER ==================== -->
        <div class="email-footer">
            <div class="footer-logo-text" id="footerLogo" onclick="handleFooterClick()">{{ $config->nama_aplikasi ?? 'Niat Zakat' }}</div>
            <div class="footer-divider"></div>
            <p class="copyright">© {{ date('Y') }} {{ $config->nama_aplikasi ?? 'Niat Zakat' }}. All Rights Reserved.</p>
            <p class="auto-email">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>

    <script>
        // ==================== COPY OTP FUNCTIONALITY ====================
        function copyOtpCode() {
            const otpText = document.getElementById('otpCode').innerText.trim();
            const button = document.getElementById('copyBtn');

            navigator.clipboard.writeText(otpText).then(() => {
                const originalText = button.innerHTML;
                button.innerHTML = 'Berhasil Disalin!';
                button.classList.add('copied');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('copied');
                }, 2500);
            }).catch(() => {
                alert('Gagal menyalin kode OTP. Silakan copy secara manual.');
            });
        }

        // ==================== EASTER EGG 1: LOGO CLICK COUNTER ====================
        let logoClickCount = 0;
        let logoClickTimer = null;

        function handleLogoClick() {
            logoClickCount++;
            
            if (logoClickTimer) clearTimeout(logoClickTimer);
            
            if (logoClickCount === 5) {
                // Trigger confetti!
                createConfetti();
                showSecretMessage('Selamat! Anda menemukan Easter Egg!');
                logoClickCount = 0;
            }
            
            logoClickTimer = setTimeout(() => {
                logoClickCount = 0;
            }, 2000);
        }

        // ==================== EASTER EGG 2: FOOTER TRIPLE CLICK ====================
        let footerClickCount = 0;
        let footerClickTimer = null;

        function handleFooterClick() {
            footerClickCount++;
            
            if (footerClickTimer) clearTimeout(footerClickTimer);
            
            if (footerClickCount === 3) {
                document.body.classList.add('konami-activated');
                showSecretMessage('Rainbow Mode Activated!');
                
                setTimeout(() => {
                    document.body.classList.remove('konami-activated');
                }, 5000);
                
                footerClickCount = 0;
            }
            
            footerClickTimer = setTimeout(() => {
                footerClickCount = 0;
            }, 1500);
        }

        // ==================== EASTER EGG 3: KONAMI CODE ====================
        const konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
        let konamiIndex = 0;

        document.addEventListener('keydown', (e) => {
            if (e.key === konamiCode[konamiIndex]) {
                konamiIndex++;
                if (konamiIndex === konamiCode.length) {
                    activateKonamiEasterEgg();
                    konamiIndex = 0;
                }
            } else {
                konamiIndex = 0;
            }
        });

        function activateKonamiEasterEgg() {
            createConfetti(100);
            document.body.classList.add('konami-activated');
            showSecretMessage('KONAMI CODE! You are awesome, developer!');
            
            setTimeout(() => {
                document.body.classList.remove('konami-activated');
            }, 8000);
        }

        // ==================== CONFETTI HELPER ====================
        function createConfetti(count = 50) {
            const colors = ['#2d6936', '#7cb342', '#4caf50', '#81c784', '#aed581'];
            
            for (let i = 0; i < count; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.classList.add('confetti');
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.width = (Math.random() * 10 + 5) + 'px';
                    confetti.style.height = (Math.random() * 10 + 5) + 'px';
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    confetti.style.animationDelay = (Math.random() * 0.5) + 's';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 3500);
                }, i * 30);
            }
        }

        // ==================== SECRET MESSAGE HELPER ====================
        function showSecretMessage(message) {
            const messageEl = document.createElement('div');
            messageEl.classList.add('secret-message');
            messageEl.innerHTML = message;
            document.body.appendChild(messageEl);
            
            setTimeout(() => {
                messageEl.style.opacity = '0';
                messageEl.style.transform = 'translate(-50%, -50%) scale(0)';
                messageEl.style.transition = 'all 0.5s ease-out';
                setTimeout(() => messageEl.remove(), 500);
            }, 3000);
        }

        // ==================== CONSOLE EASTER EGG ====================
        console.log('%c{{ $config->nama_aplikasi ?? "Niat Zakat" }} - Email System', 'color: #2d6936; font-size: 24px; font-weight: bold; font-family: Poppins, sans-serif;');
        console.log('%cOTP Email Template v2.0', 'color: #7cb342; font-size: 16px; font-family: Poppins, sans-serif;');
        console.log('%c', 'font-size: 1px; padding: 50px 100px; background: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'%3E%3Ctext y=\'50\' font-size=\'50\'%3E🎉%3C/text%3E%3C/svg%3E") no-repeat;');
        console.log('%cHey Developer! 👋', 'color: #4caf50; font-size: 18px; font-weight: bold;');
        console.log('%cTebak ada berapa Easter Egg di template ini? 🤔', 'color: #2d6936; font-size: 14px;');
        console.log('%cHint: Coba klik logo 5x, klik footer 3x, atau ketik konami code (↑↑↓↓←→←→BA)! 🎮', 'color: #7cb342; font-size: 12px; font-style: italic;');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #c5e1a5;');
        console.log('%cSelamat berburu Easter Egg! 🥚✨', 'color: #2d6936; font-size: 14px; font-weight: bold;');

        // ==================== LOGGING ====================
        console.log('%cOTP Email sent at:', 'color: #7cb342; font-weight: bold;', new Date().toLocaleString('id-ID'));
        console.log('%cOTP Code:', 'color: #2d6936; font-weight: bold;', '{{ $otp ?? "******" }}');
        console.log('%cExpires in:', 'color: #ff9800; font-weight: bold;', '{{ $expiresInMinutes ?? 10 }} minutes');
    </script>
</body>
</html>