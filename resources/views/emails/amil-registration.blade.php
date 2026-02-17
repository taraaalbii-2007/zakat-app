<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Amil Berhasil - Niat Zakat</title>
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
            padding: 10px;
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

        .success-section {
            background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 40px 32px;
            margin: 32px 0;
            border: 3px solid #7cb342;
            text-align: center;
            box-shadow: 0 8px 24px rgba(45, 105, 54, 0.08);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            color: #2d6936;
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d6936;
            margin-bottom: 12px;
        }

        .success-message {
            font-size: 16px;
            color: #4a4a4a;
            line-height: 1.6;
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
        }

        .warning-text {
            font-size: 13px;
            color: #e65100;
            line-height: 1.6;
        }

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
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(45, 105, 54, 0.35);
        }

        .email-footer {
            text-align: center;
            padding: 40px 0 20px;
        }

        .footer-logo-text {
            color: #2d6936;
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 12px;
        }

        .copyright {
            font-size: 12px;
            color: #999;
            margin-bottom: 6px;
        }

        .auto-email {
            font-size: 12px;
            color: #aaa;
        }

        @media (max-width: 640px) {
            .email-header {
                padding: 36px 24px 24px;
            }
            .email-content {
                padding: 28px 24px;
            }
            .data-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            <div class="email-header">
                <div class="header-decoration"></div>
                
                @php
                    $config = \App\Models\KonfigurasiAplikasi::first();
                @endphp
                
                @if($config && $config->logo_aplikasi)
                <div class="logo-container">
                    <img src="{{ asset('storage/' . $config->logo_aplikasi) }}" alt="Logo">
                </div>
                @endif

                <h1>Selamat Datang, Amil!</h1>
                <p class="email-subtitle">Anda telah didaftarkan sebagai Amil di {{ $amil->masjid->nama ?? 'Masjid' }}</p>
            </div>

            <div class="email-content">
                <div class="greeting-section">
                    <p class="greeting">Assalamu'alaikum, {{ $amil->nama_lengkap }}!</p>
                    <p class="instruction">Akun Anda sebagai Amil telah berhasil dibuat. Berikut adalah informasi akun Anda:</p>
                </div>

                <div class="success-section">
                    <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" opacity="0.2"/>
                        <path d="M7 13l3 3 7-7" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    
                    <div class="success-title">Registrasi Berhasil!</div>
                    <div class="success-message">
                        Anda sekarang adalah bagian dari tim amil {{ $amil->masjid->nama ?? 'masjid' }}
                    </div>
                </div>

                <div class="section-label">
                    📋 Informasi Akun
                </div>
                
                <div class="data-card">
                    <div class="data-row">
                        <div class="data-label">Nama Lengkap</div>
                        <div class="data-value">{{ $amil->nama_lengkap }}</div>
                    </div>
                    
                    <div class="data-row">
                        <div class="data-label">Kode Amil</div>
                        <div class="data-value">
                            <span class="highlight-value">{{ $amil->kode_amil }}</span>
                        </div>
                    </div>
                    
                    <div class="data-row">
                        <div class="data-label">Email</div>
                        <div class="data-value">{{ $amil->email }}</div>
                    </div>
                    
                    <div class="data-row">
                        <div class="data-label">Username</div>
                        <div class="data-value">
                            <span class="highlight-value">{{ $username }}</span>
                        </div>
                    </div>
                    
                    @if($password)
                    <div class="data-row">
                        <div class="data-label">Password</div>
                        <div class="data-value">
                            <span class="highlight-value">{{ $password }}</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="data-row">
                        <div class="data-label">Peran</div>
                        <div class="data-value">
                            <span class="role-badge">Amil</span>
                        </div>
                    </div>
                    
                    <div class="data-row">
                        <div class="data-label">Status</div>
                        <div class="data-value">{{ ucfirst($amil->status) }}</div>
                    </div>
                </div>

                <div class="section-label">
                    🕌 Informasi Masjid
                </div>
                
                <div class="mosque-card">
                    <div class="mosque-name">{{ $amil->masjid->nama ?? 'Masjid' }}</div>
                    <div class="data-row">
                        <div class="data-label">Kode Masjid</div>
                        <div class="data-value">{{ $amil->masjid->kode_masjid ?? '-' }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Alamat</div>
                        <div class="data-value">{{ $amil->masjid->alamat ?? '-' }}</div>
                    </div>
                    @if($amil->wilayah_tugas)
                    <div class="data-row">
                        <div class="data-label">Wilayah Tugas</div>
                        <div class="data-value">{{ $amil->wilayah_tugas }}</div>
                    </div>
                    @endif
                </div>

                @if($password)
                <div class="security-warning">
                    <div class="warning-title">⚠️ Perhatian Keamanan</div>
                    <div class="warning-text">
                        Email ini berisi password akun Anda. Segera ubah password setelah login pertama kali dan jangan bagikan email ini kepada siapapun.
                    </div>
                </div>
                @endif

                <div class="button-section">
                    <a href="{{ url('/login') }}" class="login-button">
                        Login Sekarang
                    </a>
                    <p style="margin-top: 12px; font-size: 13px; color: #666;">
                        Klik tombol di atas untuk login ke sistem
                    </p>
                </div>
            </div>
        </div>

        <div class="email-footer">
            <div class="footer-logo-text">{{ $config->nama_aplikasi ?? 'Niat Zakat' }}</div>
            <p class="copyright">© {{ date('Y') }} {{ $config->nama_aplikasi ?? 'Niat Zakat' }}. Hak Cipta Dilindungi.</p>
            <p class="auto-email">
                Email ini dikirim secara otomatis oleh sistem.<br>
                Jangan balas email ini.
            </p>
        </div>
    </div>
</body>
</html>