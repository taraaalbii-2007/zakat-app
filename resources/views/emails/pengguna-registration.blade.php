<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Telah Dibuat - Niat Zakat</title>
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

                    <!-- ===== HEADER ===== -->
                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px; text-align:center; border-bottom:1px solid #f0f0f0;">
                            <h1 style="margin:0 0 8px; color:#1a1a1a; font-size:22px; font-weight:700;">
                                Akun Anda Berhasil Dibuat
                            </h1>
                            <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.6;">
                                Selamat datang di <strong style="color:#2d6936;">{{ optional($appConfig)->nama_aplikasi ?? 'Niat Zakat' }}</strong> — akun Anda sudah aktif dan siap digunakan
                            </p>
                        </td>
                    </tr>

                    <!-- ===== CONTENT ===== -->
                    <tr>
                        <td style="padding:40px 48px;">

                            <!-- Greeting -->
                            <p style="margin:0 0 6px; color:#1a1a1a; font-size:16px; font-weight:600;">
                                Assalamu'alaikum, {{ $namaLengkap }}!
                            </p>
                            <p style="margin:0 0 32px; color:#6b7280; font-size:14px; line-height:1.7;">
                                Akun <strong style="color:#2d6936;">{{ $peranLabel }}</strong> Anda di sistem <strong style="color:#2d6936;">{{ optional($appConfig)->nama_aplikasi ?? 'Niat Zakat' }}</strong> telah berhasil dibuat oleh administrator. Berikut adalah detail dan kredensial login Anda:
                            </p>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr><td height="1" style="background-color:#f0f0f0;"></td></tr>
                            </table>

                            <!-- ===== INFORMASI LOGIN ===== -->
                            <p style="margin:0 0 16px; color:#374151; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                Informasi Login
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
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Password</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $password }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Peran</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $peranLabel }}</td>
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

                            <!-- ===== TOMBOL LOGIN ===== -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                                <tr>
                                    <td style="text-align:center; padding:8px 0;">
                                        <a href="{{ $loginUrl }}"
                                           style="display:inline-block; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; padding:14px 40px; border-radius:50px; letter-spacing:0.5px;">
                                            Login ke Dashboard &rarr;
                                        </a>
                                        <p style="margin:10px 0 0; color:#9ca3af; font-size:12px;">Klik tombol di atas untuk langsung masuk ke dashboard</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- ===== LANGKAH SELANJUTNYA ===== -->
                            <p style="margin:0 0 16px; color:#374151; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Langkah Selanjutnya</p>

                            <!-- Step 1 -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                                <tr>
                                    <td style="width:36px; vertical-align:top; padding-top:1px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="width:28px; height:28px; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); border-radius:50%; text-align:center; vertical-align:middle; color:#ffffff; font-size:13px; font-weight:700; line-height:28px;">1</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding-left:14px; padding-bottom:12px; border-bottom:1px solid #f0f0f0;">
                                        <p style="margin:0 0 3px; color:#1a1a1a; font-size:14px; font-weight:600;">Login ke Dashboard</p>
                                        <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.6;">
                                            Gunakan email/username dan password di atas untuk masuk ke sistem.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Step 2 -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                                <tr>
                                    <td style="width:36px; vertical-align:top; padding-top:1px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="width:28px; height:28px; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); border-radius:50%; text-align:center; vertical-align:middle; color:#ffffff; font-size:13px; font-weight:700; line-height:28px;">2</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding-left:14px; padding-bottom:12px; border-bottom:1px solid #f0f0f0;">
                                        <p style="margin:0 0 3px; color:#1a1a1a; font-size:14px; font-weight:600;">Ubah Password</p>
                                        <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.6;">
                                            Segera ubah password di pengaturan akun untuk keamanan yang lebih baik.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Step 3 -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:0;">
                                <tr>
                                    <td style="width:36px; vertical-align:top; padding-top:1px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="width:28px; height:28px; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); border-radius:50%; text-align:center; vertical-align:middle; color:#ffffff; font-size:13px; font-weight:700; line-height:28px;">3</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding-left:14px;">
                                        <p style="margin:0 0 3px; color:#1a1a1a; font-size:14px; font-weight:600;">Mulai Kelola Sistem</p>
                                        <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.6;">
                                            @if($peranLabel === 'Admin Lembaga')
                                                Mulai kelola data zakat lembaga — pengumpulan, pencatatan, pelaporan, dan distribusi.
                                            @elseif($peranLabel === 'Amil')
                                                Mulai jalankan tugas sebagai amil — verifikasi muzakki, penerimaan zakat, dan distribusi.
                                            @else
                                                Kelola seluruh sistem sesuai dengan peran dan tanggung jawab Anda.
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- ===== FOOTER ===== -->
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