<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Niat Zakat</title>
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

        .username-highlight {
            color: #2d6936;
            font-weight: 700;
        }

        .instruction {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
            margin-top: 12px;
        }

        /* ==================== RESET BUTTON SECTION ==================== */
        .reset-section {
            background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
            border-radius: 16px;
            padding: 32px;
            margin: 28px 0;
            text-align: center;
            border: 2px solid #c5e1a5;
            box-shadow: 0 4px 15px rgba(45, 105, 54, 0.08);
        }

        .reset-title {
            font-size: 16px;
            color: #2d6936;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            text-decoration: none;
            padding: 16px 44px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(45, 105, 54, 0.25);
            position: relative;
            overflow: hidden;
        }

        .reset-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.6s;
        }

        .reset-button:hover::before {
            left: 100%;
        }

        .reset-button:hover {
            background: linear-gradient(135deg, #388E3C, #4CAF50);
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(45, 105, 54, 0.35);
        }

        .reset-button:active {
            transform: translateY(-1px);
        }

        /* ==================== LINK BOX ==================== */
        .link-box {
            background: #f5f5f5;
            border: 1px dashed #c5e1a5;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 13px;
            color: #555;
            font-family: 'Courier New', monospace;
        }

        /* ==================== EXPIRY NOTICE ==================== */
        .expiry-notice {
            background: linear-gradient(135deg, #fff3cd, #ffeeba);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #ff9800;
            margin: 28px 0;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.1);
        }

        .expiry-title {
            font-size: 15px;
            font-weight: 700;
            color: #856404;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .expiry-icon {
            width: 20px;
            height: 20px;
            color: #ff9800;
        }

        .expiry-text {
            font-size: 14px;
            color: #856404;
            line-height: 1.6;
        }

        /* ==================== SECURITY TIPS ==================== */
        .tips-section {
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #2196f3;
            margin: 28px 0;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.08);
        }

        .tips-title {
            font-size: 15px;
            font-weight: 700;
            color: #1565c0;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tips-icon {
            width: 20px;
            height: 20px;
            color: #2196f3;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tips-list li {
            font-size: 13px;
            color: #0d47a1;
            line-height: 1.7;
            padding-left: 24px;
            position: relative;
            margin-bottom: 6px;
        }

        .tips-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #2196f3;
            font-weight: 700;
            font-size: 16px;
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

        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .reset-section {
            animation: slideIn 0.6s ease-out 0.2s forwards;
            opacity: 0;
        }

        .expiry-notice {
            animation: slideIn 0.6s ease-out 0.3s forwards;
            opacity: 0;
        }

        .tips-section {
            animation: slideIn 0.6s ease-out 0.4s forwards;
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

            .reset-section,
            .expiry-notice,
            .tips-section {
                padding: 20px;
            }

            .reset-button {
                padding: 14px 32px;
                font-size: 14px;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .email-header h1 {
                font-size: 22px;
            }

            .greeting {
                font-size: 16px;
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

                <h1>Reset Password</h1>
                <p class="email-subtitle">Atur ulang password akun Anda di {{ $config->nama_aplikasi ?? 'Niat Zakat' }}</p>
            </div>

            <!-- ==================== CONTENT ==================== -->
            <div class="email-content">
                <!-- Greeting -->
                <div class="greeting-section slide-in">
                    <p class="greeting">Halo, <span class="username-highlight">{{ $nama ?? 'Pengguna' }}</span></p>
                    <p class="instruction">Kami menerima permintaan untuk mengatur ulang password akun Anda. Klik tombol di bawah ini untuk melanjutkan proses reset password:</p>
                </div>

                <!-- ==================== RESET BUTTON SECTION ==================== -->
                <div class="reset-section">
                    <p class="reset-title">Klik tombol di bawah untuk mengatur ulang password:</p>
                    <a href="{{ $resetUrl }}" class="reset-button">
                        Reset Password Sekarang
                    </a>
                </div>

                <!-- Link Alternative -->
                <p style="font-size: 14px; color: #666; margin-bottom: 8px;">Atau salin dan tempel link berikut di browser Anda:</p>
                <div class="link-box">{{ $resetUrl }}</div>

                <!-- ==================== EXPIRY NOTICE ==================== -->
                <div class="expiry-notice">
                    <div class="expiry-title">
                        <svg class="expiry-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        PENTING - Perhatikan Waktu
                    </div>
                    <div class="expiry-text">
                        Link ini akan <strong>kedaluwarsa dalam {{ $expiresInMinutes ?? 60 }} menit</strong>. Jika Anda tidak meminta reset password, abaikan email ini dan akun Anda akan tetap aman.
                    </div>
                </div>

                <!-- ==================== SECURITY TIPS ==================== -->
                <div class="tips-section">
                    <div class="tips-title">
                        <svg class="tips-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                        </svg>
                        Tips Keamanan Password
                    </div>
                    <ul class="tips-list">
                        <li>Jangan berikan link ini kepada siapapun</li>
                        <li>Buat password yang kuat minimal 8 karakter</li>
                        <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                        <li>Hindari menggunakan password yang sama di beberapa akun</li>
                        <li>Segera hubungi admin jika Anda tidak meminta reset password</li>
                    </ul>
                </div>

                <p style="font-size: 14px; color: #666; margin-top: 24px;">
                    Jika Anda mengalami masalah, silakan hubungi administrator sistem.
                </p>

                <p style="font-size: 14px; color: #666; margin-top: 20px;">
                    Salam,<br>
                    <strong style="color: #2d6936;">Tim {{ $config->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                </p>
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
        console.log('%cReset Password Email Template v2.0', 'color: #7cb342; font-size: 16px; font-family: Poppins, sans-serif;');
        console.log('%c', 'font-size: 1px; padding: 50px 100px; background: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'%3E%3Ctext y=\'50\' font-size=\'50\'%3E🔐%3C/text%3E%3C/svg%3E") no-repeat;');
        console.log('%cHey Developer! 👋', 'color: #4caf50; font-size: 18px; font-weight: bold;');
        console.log('%cJangan lupa password baru yang kuat ya! 💪', 'color: #2d6936; font-size: 14px;');
        console.log('%cHint: Coba klik logo 5x, klik footer 3x, atau ketik konami code (↑↑↓↓←→←→BA)! 🎮', 'color: #7cb342; font-size: 12px; font-style: italic;');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #c5e1a5;');
        console.log('%cSelamat berburu Easter Egg! 🥚✨', 'color: #2d6936; font-size: 14px; font-weight: bold;');

        // ==================== LOGGING ====================
        console.log('%cReset Password Email sent at:', 'color: #7cb342; font-weight: bold;', new Date().toLocaleString('id-ID'));
        console.log('%cExpires in:', 'color: #ff9800; font-weight: bold;', '{{ $expiresInMinutes ?? 60 }} minutes');
    </script>
</body>
</html>