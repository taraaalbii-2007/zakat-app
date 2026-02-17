<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diubah - Niat Zakat</title>
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

        /* ==================== SUCCESS SECTION ==================== */
        .success-section {
            background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 40px 32px;
            margin: 32px 0;
            border: 3px solid #7cb342;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(45, 105, 54, 0.08);
        }

        .success-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 179, 66, 0.03) 0%, transparent 70%);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            color: #2d6936;
            filter: drop-shadow(0 4px 8px rgba(45, 105, 54, 0.2));
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d6936;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .success-message {
            font-size: 16px;
            color: #4a4a4a;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* ==================== DETAILS SECTION ==================== */
        .details-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 28px 32px;
            margin: 32px 0;
            border-left: 5px solid #2d6936;
            box-shadow: 0 4px 12px rgba(45, 105, 54, 0.08);
        }

        .details-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d6936;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .details-icon {
            width: 20px;
            height: 20px;
            color: #2d6936;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(45, 105, 54, 0.1);
        }

        .detail-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-label {
            flex: 0 0 140px;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            flex: 1;
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }

        /* ==================== BUTTON SECTION ==================== */
        .button-section {
            text-align: center;
            margin: 40px 0 20px;
        }

        .login-button {
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            border: none;
            padding: 16px 48px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 6px 20px rgba(45, 105, 54, 0.25);
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(45, 105, 54, 0.35);
            background: linear-gradient(135deg, #1e5223, #558b2f);
        }

        .login-button:active {
            transform: translateY(-1px);
        }

        .login-button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .login-button:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .checkmark-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.5s ease-in-out 0.2s forwards;
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

            .success-section {
                padding: 32px 24px;
            }

            .success-title {
                font-size: 24px;
            }

            .details-section {
                padding: 20px;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .detail-label {
                flex: 0 0 auto;
                font-size: 13px;
            }

            .detail-value {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .email-header h1 {
                font-size: 22px;
            }

            .success-title {
                font-size: 20px;
            }

            .login-button {
                padding: 14px 32px;
                font-size: 15px;
                width: 100%;
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
                
                @php
                    $config = \App\Models\KonfigurasiAplikasi::first();
                @endphp
                
                @if($config && $config->logo_aplikasi)
                <div class="logo-container">
                    <img src="{{ asset('storage/' . $config->logo_aplikasi) }}" alt="Logo {{ $config->nama_aplikasi ?? 'Aplikasi' }}" onerror="this.parentElement.style.display='none'">
                </div>
                @endif

                <h1>Password Berhasil Diubah</h1>
                <p class="email-subtitle">Konfirmasi perubahan password akun Anda di {{ $config->nama_aplikasi ?? 'Niat Zakat' }}</p>
            </div>

            <!-- ==================== CONTENT ==================== -->
            <div class="email-content">
                <!-- Greeting -->
                <div class="greeting-section slide-in">
                    <p class="greeting">Assalamu'alaikum, {{ $nama }}!</p>
                    <p class="instruction">Password akun Anda telah berhasil diubah. Berikut adalah detail perubahan password:</p>
                </div>

                <!-- ==================== SUCCESS SECTION ==================== -->
                <div class="success-section fade-in">
                    <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" opacity="0.2"/>
                        <path class="checkmark-path" d="M7 13l3 3 7-7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <div class="success-title">Password Anda Telah Diubah</div>
                    <div class="success-message">
                        Password akun Anda telah berhasil diperbarui. Anda sekarang dapat login dengan password baru Anda.
                    </div>
                </div>

                <!-- ==================== DETAILS SECTION ==================== -->
                <div class="details-section slide-in">
                    <div class="details-title">
                        <svg class="details-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                        Detail Perubahan Password
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Nama Pengguna</div>
                        <div class="detail-value">{{ $nama }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Waktu Perubahan</div>
                        <div class="detail-value">{{ $tanggal }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">Berhasil Diubah</div>
                    </div>
                </div>

                <!-- ==================== BUTTON SECTION ==================== -->
                <div class="button-section slide-in">
                    <a href="{{ route('login') }}" class="login-button">
                        Login ke Akun Saya
                    </a>
                </div>

                <!-- ==================== WARNING SECTION ==================== -->
                <div class="warning-section slide-in">
                    <div class="warning-title">
                        <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        Keamanan Akun Anda
                    </div>
                    <ul class="warning-list">
                        <li>Jika Anda <strong>tidak melakukan perubahan password</strong>, segera hubungi administrator</li>
                        <li>Jaga kerahasiaan password baru Anda</li>
                        <li>Gunakan password yang kuat dan unik</li>
                        <li>Hindari menggunakan password yang sama untuk banyak akun</li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- ==================== FOOTER ==================== -->
        <div class="email-footer">
            <div class="footer-logo-text">{{ $config->nama_aplikasi ?? 'Niat Zakat' }}</div>
            <div class="footer-divider"></div>
            <p class="copyright">© {{ date('Y') }} {{ $config->nama_aplikasi ?? 'Niat Zakat' }}. Hak Cipta Dilindungi.</p>
            <p class="auto-email">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>

    <script>
        // ==================== LOGGING ====================
        console.log('%c{{ $config->nama_aplikasi ?? "Niat Zakat" }} - Reset Password Notification', 'color: #2d6936; font-size: 24px; font-weight: bold; font-family: Poppins, sans-serif;');
        console.log('%cPassword reset email sent successfully', 'color: #7cb342; font-size: 16px; font-family: Poppins, sans-serif;');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #c5e1a5;');
        console.log('%cUser:', 'color: #2d6936; font-weight: bold;', '{{ $nama }}');
        console.log('%cReset Time:', 'color: #2d6936; font-weight: bold;', '{{ $tanggal }}');
        console.log('%cStatus:', 'color: #4caf50; font-weight: bold;', 'Password successfully changed');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #c5e1a5;');
        console.log('%cEmail sent at:', 'color: #7cb342; font-weight: bold;', new Date().toLocaleString('id-ID'));
        console.log('%cSelamat! Password Anda telah aman diperbarui 🔒', 'color: #2d6936; font-size: 14px; font-weight: bold;');

        // ==================== ANIMATE CHECKMARK ====================
        document.addEventListener('DOMContentLoaded', function() {
            const checkmark = document.querySelector('.checkmark-path');
            if (checkmark) {
                checkmark.style.animation = 'checkmark 0.5s ease-in-out 0.2s forwards';
            }
        });
    </script>
</body>
</html>