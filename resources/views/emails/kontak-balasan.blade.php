{{-- resources/views/emails/kontak-balasan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Pesan - Niat Zakat</title>
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
                                Balasan Pesan Anda
                            </h1>
                            <p style="margin:0; color:rgba(255,255,255,0.85); font-size:14px; line-height:1.6;">
                                <strong style="color:#ffffff;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- ===== CONTENT ===== -->
                    <tr>
                        <td style="padding:40px 48px;">

                            <!-- Greeting -->
                            <p style="margin:0 0 6px; color:#1a1a1a; font-size:16px; font-weight:600;">
                                Halo, {{ $kontak->nama }}!
                            </p>
                            <p style="margin:0 0 32px; color:#6b7280; font-size:14px; line-height:1.7;">
                                Terima kasih telah menghubungi kami. Kami telah membaca pesan Anda dan berikut adalah balasan dari tim <strong style="color:#2d6936;">{{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }}</strong>:
                            </p>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== DETAIL PESAN ===== -->
                            <p style="margin:0 0 16px; color:#374151; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                Detail Pesan
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">

                                <!-- Nama -->
                                <tr>
                                    <td style="padding:7px 0; width:130px; color:#6b7280; font-size:14px; vertical-align:top;">Nama</td>
                                    <td style="padding:7px 4px; width:10px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $kontak->nama }}</td>
                                </tr>

                                <!-- Subjek -->
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Subjek</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ $kontak->subjek }}</td>
                                </tr>

                                <!-- Dikirim pada -->
                                <tr>
                                    <td style="padding:7px 0; color:#6b7280; font-size:14px; vertical-align:top;">Dikirim Pada</td>
                                    <td style="padding:7px 4px; color:#6b7280; font-size:14px; vertical-align:top;">:</td>
                                    <td style="padding:7px 0; color:#1a1a1a; font-size:14px; font-weight:600; vertical-align:top;">{{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
                                </tr>

                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== PESAN ANDA ===== -->
                            <p style="margin:0 0 12px; color:#374151; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                Pesan Anda
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#f8faf8; border-left:4px solid #d1d5db; border-radius:0 6px 6px 0; padding:14px 18px; color:#4b5563; font-size:13px; line-height:1.7;">
                                        {{ $kontak->pesan }}
                                    </td>
                                </tr>
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- ===== BALASAN TIM ===== -->
                            <p style="margin:0 0 12px; color:#374151; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:2px;">
                                Balasan dari Tim Kami
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#f0faf2; border-left:4px solid #2d6936; border-radius:0 6px 6px 0; padding:14px 18px; color:#1a3d22; font-size:14px; line-height:1.7; white-space:pre-wrap;">{{ $balasan }}</td>
                                </tr>
                            </table>

                            <!-- DIVIDER -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
                                <tr>
                                    <td height="1" style="background-color:#f0f0f0;"></td>
                                </tr>
                            </table>

                            <!-- Penutup -->
                            <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.7;">
                                Jika Anda memiliki pertanyaan lanjutan, silakan kunjungi halaman kontak kami. Kami senang dapat membantu Anda.
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