<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Telah Dibuat - {{ config('app.name') }}</title>
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
            position: relative;
            overflow: hidden;
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
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .email-subtitle {
            font-size: 15px;
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
            font-size: 26px;
            font-weight: 700;
            color: #2d6936;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .success-message {
            font-size: 15px;
            color: #4a4a4a;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* ==================== PERAN BADGE ==================== */
        .peran-badge-wrap {
            text-align: center;
            margin-bottom: 28px;
        }

        .peran-badge {
            display: inline-block;
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 4px 8px;
        }

        .masjid-badge {
            display: inline-block;
            background: linear-gradient(135deg, #3b5bdb, #4c6ef5);
            color: white;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin: 0 4px 8px;
        }

        /* ==================== DATA SECTION ==================== */
        .account-section {
            margin: 32px 0;
        }

        .section-label {
            font-size: 13px;
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
            flex: 0 0 130px;
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .data-value {
            flex: 1;
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }

        .highlight-value {
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
            padding: 6px 14px;
            border-radius: 8px;
            border: 2px solid #c5e1a5;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #2d6936;
            display: inline-block;
            font-size: 14px;
        }

        .highlight-value.pulse {
            animation: pulse 2s infinite;
        }

        .role-badge {
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* ==================== MASJID SECTION ==================== */
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
            font-size: 17px;
            font-weight: 700;
            color: #2d6936;
            margin-bottom: 8px;
        }

        /* ==================== SECURITY WARNING ==================== */
        .security-warning {
            background: linear-gradient(135deg, #fff3e0, #ffecb3);
            border-radius: 12px;
            padding: 20px 24px;
            border-left: 5px solid #ff9800;
            margin: 24px 0;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.1);
        }

        .warning-title {
            font-size: 14px;
            font-weight: 700;
            color: #e65100;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .warning-text {
            font-size: 13px;
            color: #bf360c;
            line-height: 1.6;
        }

        /* ==================== BUTTON ==================== */
        .button-section {
            text-align: center;
            margin: 36px 0 20px;
        }

        .login-button {
            background: linear-gradient(135deg, #2d6936, #7cb342);
            color: white !important;
            border: none;
            padding: 16px 48px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 6px 20px rgba(45, 105, 54, 0.25);
            text-decoration: none;
            display: inline-block;
        }

        /* ==================== NEXT STEPS ==================== */
        .next-steps {
            margin: 32px 0;
        }

        .steps-title {
            font-size: 14px;
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

        .step-content .step-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .step-content .step-description {
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
            display: inline-block;
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
            line-height: 1.7;
        }

        .support-link {
            color: #2d6936;
            text-decoration: none;
            font-weight: 500;
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes checkmark {
            0%   { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.04); }
        }

        .slide-in { animation: slideIn 0.5s ease-out forwards; }
        .fade-in  { animation: fadeIn 0.8s ease-out forwards; }

        .checkmark-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.5s ease-in-out 0.2s forwards;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 640px) {
            body { padding: 12px; }
            .email-header { padding: 36px 24px 24px; }
            .email-content { padding: 28px 24px; }
            .email-header h1 { font-size: 22px; }
            .data-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .data-label { flex: 0 0 auto; }
            .login-button { padding: 14px 32px; font-size: 15px; width: 100%; }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-card">

            {{-- ==================== HEADER ==================== --}}
            <div class="email-header">
                <div class="header-decoration"></div>

                @php $appConfig = \App\Models\KonfigurasiAplikasi::first(); @endphp

                @if($appConfig && $appConfig->logo_aplikasi)
                <div class="logo-container">
                    <img src="{{ asset('storage/' . $appConfig->logo_aplikasi) }}"
                         alt="Logo {{ $appConfig->nama_aplikasi ?? config('app.name') }}"
                         onerror="this.parentElement.style.display='none'">
                </div>
                @endif

                <h1>Akun Anda Berhasil Dibuat!</h1>
                <p class="email-subtitle">
                    Selamat datang di {{ $appConfig->nama_aplikasi ?? config('app.name') }} — akun Anda sudah aktif dan siap digunakan
                </p>
            </div>

            {{-- ==================== CONTENT ==================== --}}
            <div class="email-content">

                {{-- Badge Peran & Masjid --}}
                <div class="peran-badge-wrap slide-in">
                    <span class="peran-badge">{{ $peranLabel }}</span>
                    @if($namaMasjid)
                        <span class="masjid-badge">{{ $namaMasjid }}</span>
                    @endif
                </div>

                {{-- Greeting --}}
                <div class="greeting-section slide-in">
                    <p class="greeting">Assalamu'alaikum, <strong>{{ $namaLengkap }}</strong>!</p>
                    <p class="instruction">
                        Akun <strong>{{ $peranLabel }}</strong> Anda di sistem
                        <strong>{{ $appConfig->nama_aplikasi ?? config('app.name') }}</strong>
                        telah berhasil dibuat oleh administrator. Berikut adalah detail dan kredensial login Anda:
                    </p>
                </div>

                {{-- SUCCESS BADGE --}}
                <div class="success-section fade-in">
                    <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" opacity="0.2"/>
                        <path class="checkmark-path" d="M7 13l3 3 7-7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="success-title">Akun Sudah Aktif!</div>
                    <div class="success-message">
                        Anda dapat langsung login menggunakan kredensial di bawah ini.
                        Segera ubah password setelah login pertama untuk keamanan yang lebih baik.
                    </div>
                </div>

                {{-- DATA AKUN --}}
                <div class="account-section slide-in">
                    <div class="section-label">
                        <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        🔑 Informasi Login
                    </div>

                    <div class="data-card">
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
                        <div class="data-row">
                            <div class="data-label">Password</div>
                            <div class="data-value">
                                <span class="highlight-value pulse">{{ $password }}</span>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-label">Peran</div>
                            <div class="data-value">
                                <span class="role-badge">{{ $peranLabel }}</span>
                            </div>
                        </div>
                        @if($namaMasjid)
                        <div class="data-row">
                            <div class="data-label">Masjid</div>
                            <div class="data-value">{{ $namaMasjid }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- SECURITY WARNING --}}
                <div class="security-warning slide-in">
                    <div class="warning-title">
                        <svg style="width:18px;height:18px;color:#ff9800;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        Perhatian Keamanan
                    </div>
                    <div class="warning-text">
                        Email ini berisi password akun Anda. Segera ubah password setelah login pertama kali
                        dan <strong>jangan bagikan email ini kepada siapapun</strong>.
                    </div>
                </div>

                {{-- BUTTON LOGIN --}}
                <div class="button-section slide-in">
                    <a href="{{ $loginUrl }}" class="login-button">Login ke Dashboard →</a>
                    <p style="margin-top:12px; font-size:13px; color:#666;">
                        Klik tombol di atas untuk langsung login ke dashboard
                    </p>
                </div>

                {{-- NEXT STEPS --}}
                <div class="next-steps slide-in">
                    <div class="steps-title">Langkah Selanjutnya</div>

                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-title">Login ke Dashboard</div>
                            <div class="step-description">Gunakan email/username dan password di atas untuk masuk ke sistem.</div>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-title">Ubah Password</div>
                            <div class="step-description">Segera ubah password di pengaturan akun untuk keamanan yang lebih baik.</div>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-title">Mulai Kelola Sistem</div>
                            <div class="step-description">
                                @if($peranLabel === 'Admin Masjid')
                                    Mulai kelola data zakat masjid — pengumpulan, pencatatan, pelaporan, dan distribusi.
                                @elseif($peranLabel === 'Amil')
                                    Mulai jalankan tugas sebagai amil — verifikasi muzakki, penerimaan zakat, dan distribusi.
                                @else
                                    Kelola seluruh sistem sesuai dengan peran dan tanggung jawab Anda.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <p style="font-size:13px; color:#999; text-align:center; margin-top:8px;">
                    Jika Anda tidak merasa mendaftar akun ini, silakan hubungi administrator segera.
                </p>

            </div>{{-- end email-content --}}
        </div>{{-- end email-card --}}

        {{-- ==================== FOOTER ==================== --}}
        <div class="email-footer">
            <div class="footer-logo-text">{{ $appConfig->nama_aplikasi ?? config('app.name') }}</div>
            <div class="footer-divider"></div>
            <p class="copyright">© {{ date('Y') }} {{ $appConfig->nama_aplikasi ?? config('app.name') }}. Hak Cipta Dilindungi.</p>
            <p class="auto-email">
                Email ini dikirim secara otomatis oleh sistem.<br>
                Jika Anda tidak merasa mendaftar, silakan abaikan email ini.<br>
                Butuh bantuan? Hubungi
                @if($appConfig && $appConfig->email_admin)
                    <a href="mailto:{{ $appConfig->email_admin }}" class="support-link">{{ $appConfig->email_admin }}</a>
                @else
                    administrator sistem
                @endif
            </p>
        </div>

    </div>{{-- end email-wrapper --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animate checkmark
            const checkmark = document.querySelector('.checkmark-path');
            if (checkmark) {
                checkmark.style.animation = 'checkmark 0.5s ease-in-out 0.2s forwards';
            }
        });
    </script>
</body>
</html>