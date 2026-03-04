<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Akun Diubah - Niat Zakat</title>
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
                                Peringatan Keamanan!
                            </h1>
                            <p style="margin:0; color:rgba(255,255,255,0.85); font-size:14px; line-height:1.6;">
                                Data akun Anda telah diubah di <strong style="color:#ffffff;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- ===== CONTENT ===== -->
                    <tr>
                        <td style="padding:40px 48px;">

                            <!-- Greeting -->
                            <p style="margin:0 0 6px; color:#1a1a1a; font-size:16px; font-weight:600;">
                                Assalamu'alaikum, {{ $user->username }}!
                            </p>
                            <p style="margin:0 0 32px; color:#6b7280; font-size:14px; line-height:1.7;">
                                @if($perubahan === 'email')
                                    <strong style="color:#2d6936;">Email akun Anda</strong> baru saja diubah di sistem <strong style="color:#2d6936;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>.
                                @else
                                    <strong style="color:#2d6936;">Password akun Anda</strong> baru saja diubah di sistem <strong style="color:#2d6936;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>.
                                @endif
                            </p>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== DATA AKUN TERKINI ===== -->
                            <p style="margin:0 0 16px; color:#374151; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                Data Akun Terkini
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">

                                <!-- Username -->
                                <tr>
                                    <td style="padding:7px 0; width:130px; color:#6b7280; font-size:14px; vertical-align:top;">Username</td>
                                    <td style="padding:7px 4px; width:10px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $user->username }}</td>
                                </tr>

                                <!-- Email -->
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Email</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $user->email }}</td>
                                </tr>

                                <!-- Perubahan -->
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Perubahan</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">
                                        @if($perubahan === 'email')
                                            Email Diubah
                                        @else
                                            Password Diubah
                                        @endif
                                    </td>
                                </tr>

                                <!-- Waktu Perubahan -->
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Waktu Perubahan</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ now()->format('d F Y H:i:s') }} WIB</td>
                                </tr>

                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== SECURITY WARNING ===== -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px; background-color:#fff8f0; border-left:4px solid #ff9800; border-radius:6px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="margin:0 0 8px; color:#e65100; font-size:13px; font-weight:700;">⚠️ Login Ulang Diperlukan!</p>
                                        <p style="margin:0; color:#92400e; font-size:13px; line-height:1.7;">
                                            Demi keamanan akun Anda, Anda harus <strong style="color:#78350f;">login ulang</strong> menggunakan
                                            @if($perubahan === 'email')
                                                <strong style="color:#78350f;">email baru</strong>
                                            @else
                                                <strong style="color:#78350f;">password baru</strong>
                                            @endif
                                            Anda. Anda tidak dapat mengakses dashboard sampai berhasil login ulang.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- ===== TOMBOL LOGIN ===== -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="text-align:center; padding:8px 0;">
                                        <a href="{{ url('/login') }}"
                                           style="display:inline-block; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; padding:14px 40px; border-radius:50px; letter-spacing:0.5px;">
                                            Login Sekarang &rarr;
                                        </a>
                                        <p style="margin:10px 0 0; color:#9ca3af; font-size:12px;">Klik tombol di atas untuk login ulang ke dashboard</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== TIPS KEAMANAN ===== -->
                            <p style="margin:0 0 10px; color:#374151; font-size:13px; font-weight:600;">
                                Tips Keamanan
                            </p>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Jika Anda <strong style="color:#374151;">tidak merasa melakukan</strong> perubahan ini, segera hubungi administrator.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Selalu gunakan password yang <strong style="color:#374151;">kuat dan berbeda</strong> untuk setiap akun.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - <strong style="color:#374151;">Jangan bagikan</strong> informasi login Anda kepada siapapun.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; font-size:13px; line-height:1.6;">
                                        - Aktifkan notifikasi keamanan untuk mendapatkan update aktivitas akun.
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
                            <p style="margin:0 0 4px; color:#c0c0c0; font-size:11px;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                            <p style="margin:0; color:#c0c0c0; font-size:11px;">
                                Jika tidak merasa melakukan perubahan ini, hubungi
                                <a href="mailto:{{ optional($config)->email_admin ?? 'support@niat-zakat.com' }}" style="color:#2d6936; text-decoration:none; font-weight:600;">{{ optional($config)->email_admin ?? 'support@niat-zakat.com' }}</a>
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>