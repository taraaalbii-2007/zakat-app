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
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            background-color: #fafafa;
            color: #212121;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            padding: 24px 16px;
            min-height: 100vh;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        .email-card {
            background-color: #ffffff;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 14px 0 rgba(23, 163, 74, 0.08);
        }

        /* Header - Menggunakan warna secondary yang lebih tenang */
        .email-header {
            padding: 40px 32px 28px;
            text-align: center;
            background: linear-gradient(135deg, #2d6936 0%, #1e5223 100%);
            color: white;
        }

        .logo-container {
            width: 72px;
            height: 72px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 8px;
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
            font-weight: 600;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .email-subtitle {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Content */
        .email-content {
            padding: 32px;
        }

        .greeting-section {
            margin-bottom: 24px;
        }

        .greeting {
            font-size: 16px;
            color: #212121;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .username-highlight {
            color: #17a34a;
            font-weight: 600;
        }

        .instruction {
            font-size: 14px;
            color: #616161;
            line-height: 1.6;
        }

        /* Reset Button Section - Tanpa container terpisah */
        .reset-button-wrapper {
            margin: 28px 0 20px;
            text-align: center;
        }

        .reset-button {
            display: inline-block;
            background-color: #17a34a;
            color: white;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(23, 163, 74, 0.2);
        }

        .reset-button:hover {
            background-color: #15803d;
            transform: translateY(-1px);
            box-shadow: 0 8px 12px -1px rgba(23, 163, 74, 0.25);
        }

        .reset-button:active {
            transform: translateY(0);
        }

        /* Link box - Minimalis */
        .link-box {
            background-color: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 0.75rem;
            padding: 16px;
            margin: 16px 0 28px;
            word-break: break-all;
            font-size: 12px;
            color: #424242;
            font-family: 'Courier New', monospace;
        }

        /* Info sections - Tanpa background container, hanya teks */
        .info-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #212121;
        }

        .info-text {
            font-size: 14px;
            color: #616161;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .info-text strong {
            color: #17a34a;
            font-weight: 600;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0 0 24px 0;
        }

        .tips-list li {
            font-size: 13px;
            color: #616161;
            line-height: 1.7;
            padding-left: 20px;
            position: relative;
            margin-bottom: 6px;
        }

        .tips-list li::before {
            content: '•';
            position: absolute;
            left: 6px;
            color: #17a34a;
            font-weight: 700;
            font-size: 16px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
            margin: 24px 0;
        }

        .signature {
            font-size: 14px;
            color: #424242;
            margin-top: 24px;
        }

        .signature strong {
            color: #17a34a;
            font-weight: 600;
        }

        /* Footer */
        .email-footer {
            text-align: center;
            padding: 32px 0 16px;
        }

        .footer-logo-text {
            color: #2d6936;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        .copyright {
            font-size: 11px;
            color: #9e9e9e;
            margin-bottom: 4px;
        }

        .auto-email {
            font-size: 11px;
            color: #bdbdbd;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-header {
                padding: 32px 24px 20px;
            }

            .email-content {
                padding: 24px;
            }

            .reset-button {
                padding: 12px 28px;
                font-size: 13px;
                width: 100%;
            }

            .logo-container {
                width: 64px;
                height: 64px;
                padding: 6px;
            }

            .email-header h1 {
                font-size: 24px;
            }
        }

        /* Animasi minimal */
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="email-wrapper fade-in">
        <div class="email-card">
            <!-- Header dengan warna solid dari secondary -->
            <div class="email-header">
                <div class="logo-container">
                    @php
                        $config = \App\Models\KonfigurasiAplikasi::first();
                    @endphp
                    
                    @if($config && $config->logo_aplikasi)
                        <img src="{{ asset('storage/' . $config->logo_aplikasi) }}" 
                             alt="Logo {{ $config->nama_aplikasi ?? 'Aplikasi' }}"
                             onerror="this.parentElement.style.display='none'">
                    @endif
                </div>

                <h1>Reset Password</h1>
                <p class="email-subtitle">Atur ulang password akun Anda di {{ $config->nama_aplikasi ?? 'Niat Zakat' }}</p>
            </div>

            <!-- Content Area -->
            <div class="email-content">
                <!-- Greeting -->
                <div class="greeting-section">
                    <p class="greeting">Halo, <span class="username-highlight">{{ $nama ?? 'Pengguna' }}</span></p>
                    <p class="instruction">Kami menerima permintaan untuk mengatur ulang password akun Anda. Klik tombol di bawah untuk melanjutkan proses reset password:</p>
                </div>

                <!-- Reset Button - Tanpa container background -->
                <div class="reset-button-wrapper">
                    <a href="{{ $resetUrl }}" class="reset-button">
                        Reset Password Sekarang
                    </a>
                </div>

                <!-- Link Alternative -->
                <p style="font-size: 13px; color: #616161; margin-bottom: 6px;">Atau salin dan tempel link berikut:</p>
                <div class="link-box">{{ $resetUrl }}</div>

                <!-- Expiry Notice - Tanpa container, hanya teks biasa -->
                <div class="info-title">PENTING – Perhatikan Waktu</div>
                <div class="info-text">
                    Link ini akan <strong>kedaluwarsa dalam {{ $expiresInMinutes ?? 15 }} menit</strong>. 
                    Jika Anda tidak meminta reset password, abaikan email ini dan akun Anda akan tetap aman.
                </div>

                <!-- Security Tips - Tanpa container, hanya teks -->
                <div class="info-title">Tips Keamanan Password</div>
                <ul class="tips-list">
                    <li>Jangan berikan link ini kepada siapapun</li>
                    <li>Buat password yang kuat minimal 8 karakter</li>
                    <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                    <li>Hindari menggunakan password yang sama di beberapa akun</li>
                    <li>Segera hubungi admin jika Anda tidak meminta reset password</li>
                </ul>

                <!-- Support & Signature -->
                <p style="font-size: 13px; color: #616161; margin: 16px 0 12px;">
                    Jika Anda mengalami masalah, silakan hubungi administrator sistem.
                </p>

                <div class="signature">
                    Salam,<br>
                    <strong>Tim {{ $config->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-logo-text">{{ $config->nama_aplikasi ?? 'Niat Zakat' }}</div>
            <p class="copyright">© {{ date('Y') }} {{ $config->nama_aplikasi ?? 'Niat Zakat' }}. All Rights Reserved.</p>
            <p class="auto-email">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>

    <script>
        // Menghapus semua console.log yang mengandung emoji
        console.log('Reset Password Email - Niat Zakat');
        console.log('Template version 3.0');
        console.log('Reset Password Email sent at:', new Date().toLocaleString('id-ID'));
        console.log('Expires in:', '{{ $expiresInMinutes ?? 15 }} minutes');
    </script>
</body>
</html>