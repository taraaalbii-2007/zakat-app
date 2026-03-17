<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi - Niat Zakat</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    @php
        $config = \App\Models\KonfigurasiAplikasi::first();
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f5f7; padding:40px 16px;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.08);">

                    <!-- ===== HEADER (putih, bersih) ===== -->
                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px; text-align:center; border-bottom:1px solid #f0f0f0;">

                            <h1 style="margin:0 0 8px; color:#1a1a1a; font-size:22px; font-weight:700;">
                                Verifikasi Email Anda
                            </h1>
                            <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.6;">
                                Gunakan kode OTP berikut untuk memverifikasi email Anda di <strong style="color:#2d6936;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- ===== CONTENT ===== -->
                    <tr>
                        <td style="padding:40px 48px;">

                            <p style="margin:0 0 6px; color:#1a1a1a; font-size:16px; font-weight:600;">
                                Assalamu'alaikum,
                            </p>
                            <p style="margin:0 0 32px; color:#6b7280; font-size:14px; line-height:1.7;">
                                Terima kasih telah bergabung dengan <strong style="color:#2d6936;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>. Berikut adalah kode OTP untuk memverifikasi email Anda:
                            </p>

                            <!-- ===== OTP ===== -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                                <tr>
                                    <td style="text-align:center; padding:8px 0;">
                                        <p style="margin:0 0 12px; color:#6b7280; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                            Kode OTP Anda
                                        </p>
                                        <span style="font-family:'Courier New', Courier, monospace; font-size:36px; font-weight:700; color:#2d6936; letter-spacing:10px; display:block; line-height:1.2;">
                                            {{ $otp ?? '123456' }}
                                        </span>
                                        <p style="margin:12px 0 0; color:#6b7280; font-size:13px;">
                                            Berlaku selama <strong style="color:#2d6936;">{{ $expiresInMinutes ?? 10 }} menit</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== WARNING (tanpa border, tanpa emoticon) ===== -->
                            <p style="margin:0 0 10px; color:#374151; font-size:13px; font-weight:600;">
                                Harap diperhatikan:
                            </p>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - <strong style="color:#374151;">Jangan bagikan</strong> kode OTP ini kepada siapapun, termasuk petugas {{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Kode akan <strong style="color:#374151;">kedaluwarsa dalam {{ $expiresInMinutes ?? 10 }} menit</strong>.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Jika Anda <strong style="color:#374151;">tidak melakukan pendaftaran</strong>, abaikan email ini.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Segera ubah password jika Anda mencurigai adanya akses tidak sah.
                                    </td>
                                </tr>
                            </table>

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