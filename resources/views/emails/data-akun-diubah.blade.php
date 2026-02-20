<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Akun Diubah - {{ $config->nama_aplikasi ?? 'Aplikasi Zakat' }}</title>
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

        /* ==================== WARNING SECTION ==================== */
        .warning-section {
            background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 40px 32px;
            margin: 32px 0;
            border: 3px solid #ff9800;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(255, 152, 0, 0.08);
        }

        .warning-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 152, 0, 0.03) 0%, transparent 70%);
        }

        .warning-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            color: #ff9800;
            filter: drop-shadow(0 4px 8px rgba(255, 152, 0, 0.2));
        }

        .warning-title {
            font-size: 28px;
            font-weight: 700;
            color: #e65100;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .warning-message {
            font-size: 16px;
            color: #4a4a4a;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* ==================== ACCOUNT DATA SECTION ==================== */
        .account-section {
            margin: 32px 0;
        }

        .section-label {
            font-size: 14px;
            color: #2d6936;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            width: 20px;
            height: 20px;
            color: #2d6936;
        }

        .data-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #2d6936;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(45, 105, 54, 0.08);
        }

        .data-row {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(45, 105, 54, 0.1);
        }

        .data-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .data-label {
            flex: 0 0 140px;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .data-value {
            flex: 1;
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }

        .highlight-value {
            background: linear-gradient(135deg, #fff3e0, #ffffff);
            padding: 8px 16px;
            border-radius: 8px;
            border: 2px solid #ffb74d;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #e65100;
            display: inline-block;
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

        /* ==================== SECURITY TIPS ==================== */
        .security-tips {
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #4caf50;
            margin: 24px 0;
            box-shadow: 0 4px 12px rgba(45, 105, 54, 0.1);
        }

        .tips-title {
            font-size: 15px;
            font-weight: 700;
            color: #2d6936;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tips-icon {
            width: 20px;
            height: 20px;
            color: #4caf50;
        }

        .tips-text {
            font-size: 13px;
            color: #2d6936;
            line-height: 1.6;
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

        .support-link {
            color: #2d6936;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .support-link:hover {
            color: #7cb342;
            text-decoration: underline;
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

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .pulse {
            animation: pulse 2s infinite;
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

            .warning-section {
                padding: 32px 24px;
            }

            .warning-title {
                font-size: 24px;
            }

            .data-card {
                padding: 20px;
            }

            .data-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .data-label {
                flex: 0 0 auto;
                font-size: 13px;
            }

            .data-value {
                font-size: 14px;
            }

            .login-button {
                padding: 14px 32px;
                font-size: 15px;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .email-header h1 {
                font-size: 22px;
            }

            .warning-title {
                font-size: 20px;
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
                
                @if($config && $config->logo_aplikasi)
                <div class="logo-container">
                    <img src="{{ asset('storage/' . $config->logo_aplikasi) }}" alt="Logo {{ $config->nama_aplikasi ?? 'Aplikasi' }}">
                </div>
                @endif

                <h1>Peringatan Keamanan!</h1>
                <p class="email-subtitle">Data akun Anda telah diubah</p>
            </div>

            <!-- ==================== CONTENT ==================== -->
            <div class="email-content">
                <!-- Greeting -->
                <div class="greeting-section slide-in">
                    <p class="greeting">Assalamu'alaikum, {{ $user->username }}!</p>
                    <p class="instruction">
                        @if($perubahan === 'email')
                            <strong>Email akun Anda</strong> baru saja diubah di sistem <strong>{{ $config->nama_aplikasi ?? 'Aplikasi Zakat' }}</strong>.
                        @else
                            <strong>Password akun Anda</strong> baru saja diubah di sistem <strong>{{ $config->nama_aplikasi ?? 'Aplikasi Zakat' }}</strong>.
                        @endif
                    </p>
                </div>

                <!-- ==================== WARNING SECTION ==================== -->
                <div class="warning-section fade-in">
                    <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" opacity="0.2"/>
                        <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    
                    <div class="warning-title">Login Ulang Diperlukan!</div>
                    <div class="warning-message">
                        Demi keamanan akun Anda, Anda harus <strong>login ulang</strong> menggunakan 
                        @if($perubahan === 'email')
                            <strong>email baru</strong>
                        @else
                            <strong>password baru</strong>
                        @endif
                        Anda. Anda tidak dapat mengakses dashboard sampai berhasil login ulang.
                    </div>
                </div>

                <!-- ==================== ACCOUNT DATA SECTION ==================== -->
                <div class="account-section slide-in">
                    <div class="section-label">
                        <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Data Akun Terkini
                    </div>
                    
                    <div class="data-card">
                        <div class="data-row">
                            <div class="data-label">Username</div>
                            <div class="data-value">{{ $user->username }}</div>
                        </div>
                        
                        <div class="data-row">
                            <div class="data-label">Email</div>
                            <div class="data-value">
                                <span class="highlight-value">{{ $user->email }}</span>
                            </div>
                        </div>
                        
                        <div class="data-row">
                            <div class="data-label">Perubahan</div>
                            <div class="data-value">
                                @if($perubahan === 'email')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Email Diubah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Password Diubah
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="data-row">
                            <div class="data-label">Waktu Perubahan</div>
                            <div class="data-value">{{ now()->format('d F Y H:i:s') }} WIB</div>
                        </div>
                    </div>
                </div>

                <!-- ==================== BUTTON SECTION ==================== -->
                <div class="button-section slide-in">
                    <a href="{{ url('/login') }}" class="login-button">
                        Login Sekarang
                    </a>
                    <p style="margin-top: 12px; font-size: 13px; color: #666;">
                        Klik tombol di atas untuk login ulang ke dashboard
                    </p>
                </div>

                <!-- ==================== SECURITY TIPS ==================== -->
                <div class="security-tips slide-in">
                    <div class="tips-title">
                        <svg class="tips-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                        </svg>
                        Tips Keamanan
                    </div>
                    <div class="tips-text">
                        • Jika Anda tidak merasa melakukan perubahan ini, segera hubungi administrator.<br>
                        • Selalu gunakan password yang kuat dan berbeda untuk setiap akun.<br>
                        • Jangan bagikan informasi login Anda kepada siapapun.<br>
                        • Aktifkan notifikasi keamanan untuk mendapatkan update aktivitas akun.
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== FOOTER ==================== -->
        <div class="email-footer">
            <div class="footer-logo-text">{{ $config->nama_aplikasi ?? 'Aplikasi Zakat' }}</div>
            <div class="footer-divider"></div>
            <p class="copyright">© {{ date('Y') }} {{ $config->nama_aplikasi ?? 'Aplikasi Zakat' }}. Hak Cipta Dilindungi.</p>
            <p class="auto-email">
                Email ini dikirim secara otomatis oleh sistem {{ $config->nama_aplikasi ?? 'Aplikasi Zakat' }}.<br>
                Jika Anda tidak merasa melakukan perubahan ini, segera hubungi administrator.<br>
                Jangan balas email ini — untuk bantuan hubungi 
                <a href="mailto:{{ $config->email_admin ?? 'support@aplikasi-zakat.com' }}" class="support-link">
                    {{ $config->email_admin ?? 'support@aplikasi-zakat.com' }}
                </a>
            </p>
        </div>
    </div>
</body>
</html>