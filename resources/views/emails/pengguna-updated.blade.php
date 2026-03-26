<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $passwordChanged ? 'Password Direset' : 'Data Akun Diperbarui' }} - Niat Zakat</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    @php
        $appConfig = \App\Models\KonfigurasiAplikasi::first();
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f5f7; padding:40px 16px;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:560px; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px; text-align:center; border-bottom:1px solid #f0f0f0;">
                            @if($passwordChanged)
                                <h1 style="margin:0 0 8px; color:#1a1a1a; font-size:22px; font-weight:700;">
                                    Password Akun Direset
                                </h1>
                                <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.6;">
                                    Password akun Anda di <strong style="color:#2d6936;">{{ optional($appConfig)->nama_aplikasi ?? 'Niat Zakat' }}</strong> telah diubah oleh administrator
                                </p>
                            @else
                                <h1 style="margin:0 0 8px; color:#1a1a1a; font-size:22px; font-weight:700;">
                                    Data Akun Diperbarui
                                </h1>
                                <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.6;">
                                    Informasi akun Anda di <strong style="color:#2d6936;">{{ optional($appConfig)->nama_aplikasi ?? 'Niat Zakat' }}</strong> telah diperbarui oleh administrator
                                </p>
                            @endif
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:40px 48px;">

                            <p style="margin:0 0 6px; color:#1a1a1a; font-size:16px; font-weight:600;">
                                Assalamu'alaikum, {{ $namaLengkap }}!
                            </p>
                            <p style="margin:0 0 32px; color:#6b7280; font-size:14px; line-height:1.7;">
                                @if($passwordChanged)
                                    Administrator telah mereset password akun <strong style="color:#2d6936;">{{ $peranLabel ?? $peran }}</strong> Anda. Berikut adalah kredensial login terbaru Anda:
                                @else
                                    Administrator telah memperbarui data akun <strong style="color:#2d6936;">{{ $peranLabel ?? $peran }}</strong> Anda. Berikut adalah informasi akun Anda saat ini:
                                @endif
                            </p>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr><td height="1" style="background-color:#f0f0f0;"></td></tr>
                            </table>

                            <!-- INFO AKUN -->
                            <p style="margin:0 0 16px; color:#374151; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                {{ $passwordChanged ? 'Kredensial Login' : 'Informasi Akun' }}
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="padding:7px 0; width:130px; color:#6b7280; font-size:14px; vertical-align:top;">Email</td>
                                    <td style="padding:7px 4px; width:10px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Username</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $username }}</td>
                                </tr>
                                @if($passwordChanged && $newPassword)
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Password Baru</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; vertical-align:top;">
                                        <span style="display:inline-block; background:#fff7ed; border:1px solid #fed7aa; color:#c2410c; font-size:14px; font-weight:700; padding:4px 10px; border-radius:6px; font-family:monospace;">
                                            {{ $newPassword }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Peran</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $peranLabel ?? $peran }}</td>
                                </tr>
                                @if($namaLembaga)
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Lembaga</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $namaLembaga }}</td>
                                </tr>
                                @endif
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr><td height="1" style="background-color:#f0f0f0;"></td></tr>
                            </table>

                            <!-- TOMBOL LOGIN -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                                <tr>
                                    <td style="text-align:center; padding:8px 0;">
                                        <a href="{{ url('/login') }}"
                                           style="display:inline-block; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; padding:14px 40px; border-radius:50px; letter-spacing:0.5px;">
                                            Login ke Dashboard &rarr;
                                        </a>
                                        <p style="margin:10px 0 0; color:#9ca3af; font-size:12px;">
                                            @if($passwordChanged)
                                                Gunakan password baru di atas untuk masuk
                                            @else
                                                Klik tombol di atas untuk masuk ke dashboard
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- PERINGATAN jika password direset -->
                            @if($passwordChanged)
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background:#fff7ed; border:1px solid #fed7aa; border-radius:8px; padding:14px 16px;">
                                        <p style="margin:0 0 4px; color:#92400e; font-size:13px; font-weight:600;">⚠️ Penting!</p>
                                        <p style="margin:0; color:#92400e; font-size:13px; line-height:1.6;">
                                            Segera ubah password Anda setelah login melalui menu <strong>Pengaturan Akun</strong> untuk menjaga keamanan.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color:#f8faf8; padding:24px 48px; text-align:center; border-top:1px solid #f0f0f0;">
                            <p style="margin:0 0 4px; color:#2d6936; font-size:15px; font-weight:700;">
                                {{ optional($appConfig)->nama_aplikasi ?? 'Niat Zakat' }}
                            </p>
                            <p style="margin:0 0 2px; color:#9ca3af; font-size:11px;">
                                &copy; {{ date('Y') }} {{ optional($appConfig)->nama_aplikasi ?? 'Niat Zakat' }}. All Rights Reserved.
                            </p>
                            <p style="margin:0 0 4px; color:#c0c0c0; font-size:11px;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                            <p style="margin:0; color:#c0c0c0; font-size:11px;">
                                Butuh bantuan? Hubungi
                                @if($appConfig && $appConfig->email_admin)
                                    <a href="mailto:{{ $appConfig->email_admin }}" style="color:#2d6936; text-decoration:none; font-weight:600;">{{ $appConfig->email_admin }}</a>
                                @else
                                    administrator sistem
                                @endif
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>