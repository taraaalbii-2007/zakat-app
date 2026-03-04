<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Niat Zakat</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    @php
        $config = \App\Models\KonfigurasiAplikasi::first();

        $logoBase64 = null;
        $logoPath = base_path('public/images/logo.png');
        if (file_exists($logoPath)) {
            $logoMime = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f5f7; padding:40px 16px;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.08);">

                    <!-- ===== HEADER ===== -->
                    <tr>
                        <td style="background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); padding:40px 40px 36px; text-align:center;">

                            @if($logoBase64)
                            <div style="margin-bottom:20px;">
                                <img src="{{ $logoBase64 }}"
                                     alt="Logo {{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}"
                                     width="72" height="72"
                                     style="width:72px; height:72px; border-radius:50%; object-fit:cover; background-color:#ffffff; display:inline-block;">
                            </div>
                            @endif

                            <h1 style="margin:0 0 8px; color:#ffffff; font-size:24px; font-weight:700;">
                                Reset Password
                            </h1>
                            <p style="margin:0; color:rgba(255,255,255,0.85); font-size:14px; line-height:1.6;">
                                Atur ulang password akun Anda di <strong style="color:#ffffff;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- ===== CONTENT ===== -->
                    <tr>
                        <td style="padding:40px 48px;">

                            <!-- Greeting -->
                            <p style="margin:0 0 6px; color:#1a1a1a; font-size:16px; font-weight:600;">
                                Halo, {{ $nama ?? 'Pengguna' }}!
                            </p>
                            <p style="margin:0 0 32px; color:#6b7280; font-size:14px; line-height:1.7;">
                                Kami menerima permintaan untuk mengatur ulang password akun Anda di <strong style="color:#2d6936;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>. Klik tombol di bawah untuk melanjutkan proses reset password:
                            </p>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== TOMBOL RESET ===== -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="text-align:center; padding:8px 0;">
                                        <a href="{{ $resetUrl }}"
                                           style="display:inline-block; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; padding:14px 40px; border-radius:50px; letter-spacing:0.5px;">
                                            Reset Password Sekarang &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- ===== LINK ALTERNATIF ===== -->
                            <p style="margin:0 0 8px; color:#6b7280; font-size:13px;">
                                Atau salin dan tempel link berikut ke browser Anda:
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#f8faf8; border:1px solid #e5e7eb; border-radius:6px; padding:12px 16px; word-break:break-all; font-family:'Courier New', Courier, monospace; font-size:12px; color:#374151; line-height:1.6;">
                                        {{ $resetUrl }}
                                    </td>
                                </tr>
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== PENTING - WAKTU KEDALUWARSA ===== -->
                            <p style="margin:0 0 8px; color:#374151; font-size:13px; font-weight:600;">
                                PENTING – Perhatikan Waktu
                            </p>
                            <p style="margin:0 0 28px; color:#6b7280; font-size:14px; line-height:1.7;">
                                Link ini akan <strong style="color:#2d6936;">kedaluwarsa dalam {{ $expiresInMinutes ?? 15 }} menit</strong>.
                                Jika Anda tidak meminta reset password, abaikan email ini dan akun Anda akan tetap aman.
                            </p>

                            <!-- ===== TIPS KEAMANAN ===== -->
                            <p style="margin:0 0 10px; color:#374151; font-size:13px; font-weight:600;">
                                Tips Keamanan Password
                            </p>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:28px;">
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Jangan berikan link ini kepada siapapun.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Buat password yang kuat <strong style="color:#374151;">minimal 8 karakter</strong>.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Kombinasikan huruf besar, kecil, angka, dan simbol.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Hindari menggunakan password yang sama di beberapa akun.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Segera hubungi admin jika Anda <strong style="color:#374151;">tidak meminta</strong> reset password.
                                    </td>
                                </tr>
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- Penutup -->
                            <p style="margin:0 0 4px; color:#6b7280; font-size:13px; line-height:1.6;">
                                Jika Anda mengalami masalah, silakan hubungi administrator sistem.
                            </p>
                            <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.6;">
                                Salam,<br>
                                <strong style="color:#2d6936;">Tim {{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- ===== FOOTER ===== -->
                    <tr>
                        <td style="background-color:#f8faf8; padding:24px 48px; text-align:center; border-top:1px solid #f0f0f0;">
                            <p style="margin:0 0 4px; color:#2d6936; font-size:15px; font-weight:700;">
                                {{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}
                            </p>
                            <p style="margin:0 0 2px; color:#9ca3af; font-size:11px;">
                                &copy; {{ date('Y') }} {{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}. All Rights Reserved.
                            </p>
                            <p style="margin:0; color:#c0c0c0; font-size:11px;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>