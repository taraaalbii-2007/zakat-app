<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - Niat Zakat</title>
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
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            padding: 8px 16px;
            border-radius: 8px;
            border: 2px solid #c5e1a5;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #2d6936;
            display: inline-block;
        }

        .role-badge {
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .google-badge {
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* ==================== MOSQUE DATA SECTION ==================== */
        .mosque-section {
            margin: 32px 0;
        }

        .mosque-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #4caf50;
            box-shadow: 0 4px 12px rgba(45, 105, 54, 0.08);
        }

        .mosque-name {
            font-size: 18px;
            font-weight: 700;
            color: #2d6936;
            margin-bottom: 8px;
        }

        .mosque-code {
            font-size: 24px;
            font-weight: 800;
            color: #7cb342;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            text-align: center;
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            padding: 16px;
            border-radius: 12px;
            border: 3px dashed #a5d6a7;
            margin: 16px 0;
        }

        /* ==================== SECURITY WARNING ==================== */
        .security-warning {
            background: linear-gradient(135deg, #fff3e0, #ffecb3);
            border-radius: 12px;
            padding: 24px 28px;
            border-left: 5px solid #ff9800;
            margin: 24px 0;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.1);
        }

        .warning-title {
            font-size: 15px;
            font-weight: 700;
            color: #e65100;
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

        .warning-text {
            font-size: 13px;
            color: #e65100;
            line-height: 1.6;
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

        /* ==================== NEXT STEPS ==================== */
        .next-steps {
            margin: 32px 0;
        }

        .steps-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d6936;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(45, 105, 54, 0.1);
        }

        .step-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .step-description {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
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

        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
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

            .data-card, .mosque-card {
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

            .mosque-code {
                font-size: 20px;
                padding: 12px;
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

            .success-title {
                font-size: 20px;
            }

            .mosque-code {
                font-size: 18px;
                letter-spacing: 1px;
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

                <h1>Registrasi Berhasil!</h1>
                <p class="email-subtitle">Akun admin masjid Anda telah berhasil dibuat dan siap digunakan</p>
            </div>

            <!-- ==================== CONTENT ==================== -->
            <div class="email-content">
                <!-- Greeting -->
                <div class="greeting-section slide-in">
                    <p class="greeting">Assalamu'alaikum, {{ $nama }}!</p>
                    <p class="instruction">Selamat datang di <strong>{{ $config->nama_aplikasi ?? 'Niat Zakat' }}</strong>. Akun admin masjid Anda telah berhasil terdaftar. Berikut adalah detail akun Anda:</p>
                </div>

                <!-- ==================== SUCCESS SECTION ==================== -->
                <div class="success-section fade-in">
                    <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" opacity="0.2"/>
                        <path class="checkmark-path" d="M7 13l3 3 7-7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <div class="success-title">Akun Anda Sudah Aktif!</div>
                    <div class="success-message">
                        Anda sekarang dapat login menggunakan kredensial di bawah ini. Segera ubah password setelah login pertama untuk keamanan akun yang lebih baik.
                    </div>
                </div>

                <!-- ==================== ACCOUNT DATA SECTION ==================== -->
                <div class="account-section slide-in">
                    <div class="section-label">
                        <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Data Akun Anda
                    </div>
                    
                    <div class="data-card">
                        <div class="data-row">
                            <div class="data-label">Nama</div>
                            <div class="data-value">{{ $nama }}</div>
                        </div>
                        
                        <div class="data-row">
                            <div class="data-label">Email</div>
                            <div class="data-value">{{ $email }}</div>
                        </div>
                        
                        <div class="data-row">
                            <div class="data-label">Username</div>
                            <div class="data-value">
                                <span class="highlight-value">{{ $username }}</span>
                            </div>
                        </div>
                        
                        @if(!$isGoogleUser && $password)
                        <div class="data-row">
                            <div class="data-label">Password</div>
                            <div class="data-value">
                                <span class="highlight-value pulse">{{ $password }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="data-row">
                            <div class="data-label">Peran</div>
                            <div class="data-value">
                                <span class="role-badge">Admin Masjid</span>
                            </div>
                        </div>
                        
                        @if($isGoogleUser)
                        <div class="data-row">
                            <div class="data-label">Metode Login</div>
                            <div class="data-value">
                                <span class="google-badge">Google OAuth</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- ==================== MOSQUE DATA SECTION ==================== -->
                <div class="mosque-section slide-in">
                    <div class="section-label">
                        <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8c0-3.31-2.69-6-6-6S6 4.69 6 8c0 4.5 6 11 6 11s6-6.5 6-11zm-8 0c0-1.1.9-2 2-2s2 .9 2 2-.89 2-2 2c-1.1 0-2-.9-2-2zM5 20v2h14v-2H5z"/>
                        </svg>
                        Data Masjid
                    </div>
                    
                    <div class="mosque-card">
                        <div class="mosque-name">{{ $nama_masjid }}</div>
                        <div class="mosque-code">{{ $kode_masjid }}</div>
                        <p style="text-align: center; color: #666; font-size: 13px;">
                            Gunakan kode ini untuk mengelola data zakat masjid Anda
                        </p>
                    </div>
                </div>

                <!-- ==================== SECURITY WARNING ==================== -->
                @if(!$isGoogleUser && $password)
                <div class="security-warning slide-in">
                    <div class="warning-title">
                        <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        Perhatian Keamanan
                    </div>
                    <div class="warning-text">
                        Email ini berisi password akun Anda. Segera ubah password setelah login pertama kali dan jangan bagikan email ini kepada siapapun. Password akan berkedip sebagai pengingat untuk segera diubah.
                    </div>
                </div>
                @endif

                <!-- ==================== BUTTON SECTION ==================== -->
                <div class="button-section slide-in">
                    <a href="{{ url('/login') }}" class="login-button">
                        Login ke Dashboard
                    </a>
                    <p style="margin-top: 12px; font-size: 13px; color: #666;">
                        Klik tombol di atas untuk langsung login ke dashboard admin
                    </p>
                </div>

                <!-- ==================== NEXT STEPS ==================== -->
                <div class="next-steps slide-in">
                    <div class="steps-title">Langkah Selanjutnya</div>
                    
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-title">Login ke Dashboard</div>
                            <div class="step-description">
                                Gunakan username dan password di atas untuk login ke dashboard admin.
                                @if($isGoogleUser) Login dengan akun Google Anda. @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-title">Ubah Password</div>
                            <div class="step-description">
                                @if(!$isGoogleUser && $password)
                                Segera ubah password di pengaturan akun untuk keamanan yang lebih baik.
                                @else
                                Untuk keamanan ekstra, Anda dapat mengatur password khusus di pengaturan akun.
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-title">Kelola Zakat Masjid</div>
                            <div class="step-description">
                                Mulai kelola zakat masjid Anda — mulai dari pengumpulan, pencatatan, hingga pelaporan dan distribusi.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== FOOTER ==================== -->
        <div class="email-footer">
            <div class="footer-logo-text">{{ $config->nama_aplikasi ?? 'Niat Zakat' }}</div>
            <div class="footer-divider"></div>
            <p class="copyright">© {{ date('Y') }} {{ $config->nama_aplikasi ?? 'Niat Zakat' }}. Hak Cipta Dilindungi.</p>
            <p class="auto-email">
                Email ini dikirim secara otomatis oleh sistem {{ $config->nama_aplikasi ?? 'Niat Zakat' }}.<br>
                Jika Anda tidak merasa mendaftar, silakan abaikan email ini.<br>
                Jangan balas email ini — untuk bantuan hubungi 
                <a href="mailto:support@niat-zakat.com" class="support-link">
                    support@niat-zakat.com
                </a>
            </p>
        </div>
    </div>

    <script>
        // ==================== LOGGING ====================
        console.log('%c{{ $config->nama_aplikasi ?? "Niat Zakat" }} - Registration Success', 'color: #2d6936; font-size: 24px; font-weight: bold; font-family: Poppins, sans-serif;');
        console.log('%cNew admin registration completed successfully', 'color: #7cb342; font-size: 16px; font-family: Poppins, sans-serif;');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #c5e1a5;');
        console.log('%cUser Details:', 'color: #2d6936; font-weight: bold;');
        console.log('  Name:', 'color: #666;', '{{ $nama }}');
        console.log('  Email:', 'color: #666;', '{{ $email }}');
        console.log('  Username:', 'color: #666;', '{{ $username }}');
        @if(!$isGoogleUser && $password)
        console.log('  Password:', 'color: #ff9800; font-weight: bold;', '{{ $password }}');
        @endif
        console.log('  Mosque:', 'color: #666;', '{{ $nama_masjid }}');
        console.log('  Mosque Code:', 'color: #2d6936; font-weight: bold;', '{{ $kode_masjid }}');
        console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #c5e1a5;');
        console.log('%cRegistration time:', 'color: #7cb342; font-weight: bold;', new Date().toLocaleString('id-ID'));
        console.log('%cSelamat bergabung! 🎉 Masjid {{ $nama_masjid }} siap dikelola', 'color: #2d6936; font-size: 14px; font-weight: bold;');
        @if(!$isGoogleUser && $password)
        console.log('%c⚠️  Peringatan: Password terlihat di email! Pastikan segera diubah', 'color: #ff9800; font-weight: bold;');
        @endif

        // ==================== ANIMATE CHECKMARK ====================
        document.addEventListener('DOMContentLoaded', function() {
            const checkmark = document.querySelector('.checkmark-path');
            if (checkmark) {
                checkmark.style.animation = 'checkmark 0.5s ease-in-out 0.2s forwards';
            }
            
            // Add pulse animation to password if exists
            const passwordElement = document.querySelector('.pulse');
            if (passwordElement) {
                setInterval(() => {
                    passwordElement.classList.toggle('pulse');
                    setTimeout(() => {
                        passwordElement.classList.add('pulse');
                    }, 100);
                }, 2000);
            }
        });

        // ==================== EASTER EGG: DOUBLE CLICK LOGO ====================
        let logoClickCount = 0;
        let logoClickTimer = null;

        document.querySelector('.logo-container')?.addEventListener('click', function() {
            logoClickCount++;
            
            if (logoClickTimer) clearTimeout(logoClickTimer);
            
            if (logoClickCount === 2) {
                console.log('%c🎉 Selamat! Anda menemukan Easter Egg!', 'color: #ff9800; font-size: 16px; font-weight: bold;');
                console.log('%cTips: Password yang berkedip menandakan harus segera diubah!', 'color: #2d6936; font-size: 12px;');
                
                // Create confetti effect
                createConfetti();
                logoClickCount = 0;
            }
            
            logoClickTimer = setTimeout(() => {
                logoClickCount = 0;
            }, 500);
        });

        // ==================== CONFETTI HELPER ====================
        function createConfetti() {
            const colors = ['#2d6936', '#7cb342', '#4caf50', '#81c784', '#aed581'];
            
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.top = '0';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.width = (Math.random() * 10 + 5) + 'px';
                    confetti.style.height = (Math.random() * 10 + 5) + 'px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.borderRadius = '50%';
                    confetti.style.zIndex = '9999';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.animation = `confettiFall ${Math.random() * 2 + 1}s linear forwards`;
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 3000);
                }, i * 30);
            }
            
            // Add CSS for animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes confettiFall {
                    0% {
                        transform: translateY(-100px) rotate(0deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>
</html>