{{-- resources/views/emails/transaksi-zakat.blade.php --}}
<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kwitansi Transaksi Zakat</title>
    <!--[if mso]>
    <noscript>
        <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
            display: block;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f4f5f7;
            width: 100% !important;
        }
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .content-padding { padding: 28px 24px !important; }
            .header-padding { padding: 32px 24px !important; }
            .btn-download { padding: 14px 28px !important; font-size: 14px !important; }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI',Arial,sans-serif;">

    @php
        $config  = \App\Models\KonfigurasiAplikasi::first();
        $appName = optional($config)->nama_aplikasi ?? 'Niat Zakat';
        $t       = $transaksi;
    @endphp

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f4f5f7; padding:40px 16px;">
        <tr>
            <td align="center" valign="top">

                <!-- Card -->
                <table role="presentation" class="email-container" width="580" cellpadding="0" cellspacing="0"
                    border="0"
                    style="max-width:580px; width:100%; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    <!-- ===== HEADER ===== -->
                    <tr>
                        <td class="header-padding"
                            style="background-color:#ffffff; padding:40px 40px 32px; text-align:center; border-bottom:1px solid #f0f0f0;">
                            <h1 style="margin:0 0 8px; color:#1a1a1a; font-size:22px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif;">
                                Kwitansi Transaksi Zakat
                            </h1>
                            <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.5; font-family:'Segoe UI',Arial,sans-serif;">
                                Terima kasih telah menunaikan zakat di <strong style="color:#2d6936;">{{ $appName }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- ===== SALAM ===== -->
                    <tr>
                        <td class="content-padding" style="padding:32px 40px 0;">
                            <p style="margin:0 0 4px; color:#111827; font-size:16px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif;">
                                Assalamu'alaikum, {{ $t->muzakki_nama }}!
                            </p>
                            <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.7; font-family:'Segoe UI',Arial,sans-serif;">
                                Terima kasih telah menunaikan zakat. Berikut ringkasan transaksi Anda:
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:20px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td height="1" style="background-color:#e5e7eb; font-size:1px; line-height:1px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ===== DETAIL TRANSAKSI ===== -->
                    <tr>
                        <td class="content-padding" style="padding:24px 40px 0;">

                            <p style="margin:0 0 14px; color:#374151; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:2px; font-family:'Segoe UI',Arial,sans-serif;">
                                Detail Transaksi
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                <!-- No Transaksi -->
                                <tr>
                                    <td width="44%" style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">No. Transaksi</td>
                                    <td width="4%" style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:600; font-family:'Courier New',monospace; border-bottom:1px solid #f0f0f0;">{{ $t->no_transaksi }}</td>
                                </tr>

                                <!-- Tanggal -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Tanggal</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d F Y') }}
                                    </td>
                                </tr>

                                <!-- Nama Muzakki -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Nama Muzakki</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#1a1a1a; font-size:13px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->muzakki_nama }}</td>
                                </tr>

                                <!-- Jenis Zakat -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jenis Zakat</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ optional($t->jenisZakat)->nama ?? '-' }}
                                        @if ($t->tipeZakat)
                                            &ndash; {{ $t->tipeZakat->nama }}
                                        @endif
                                    </td>
                                </tr>

                                @if ($t->jumlah_jiwa)
                                <!-- Jumlah Jiwa -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Jiwa</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->jumlah_jiwa }} jiwa</td>
                                </tr>
                                @endif

                                @if ($t->jumlah_beras_kg)
                                <!-- Jumlah Beras -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Beras</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->jumlah_beras_kg }} kg</td>
                                </tr>
                                @else
                                <!-- Jumlah Zakat (uang) -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Zakat</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#2d6936; font-size:15px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif

                                <!-- Lembaga -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Lembaga</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ optional($t->lembaga)->nama ?? '-' }}</td>
                                </tr>

                                <!-- Status -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif;">Status</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px;">:</td>
                                    <td style="padding:9px 0; font-family:'Segoe UI',Arial,sans-serif;">
                                        <span style="display:inline-block; background-color:#dcfce7; color:#166534; font-size:11px; font-weight:700; padding:4px 12px; border-radius:999px; letter-spacing:0.5px; text-transform:uppercase;">
                                            Terverifikasi
                                        </span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- ===== TOMBOL DOWNLOAD ===== -->
                    <tr>
                        <td class="content-padding" style="padding:28px 40px 0; text-align:center;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $downloadUrl }}" class="btn-download"
                                            style="display:inline-block; background:linear-gradient(160deg, #2d6936 0%, #4a9040 100%); color:#ffffff; font-size:14px; font-weight:700; text-decoration:none; padding:14px 36px; border-radius:999px; letter-spacing:0.3px; font-family:'Segoe UI',Arial,sans-serif;">
                                            Unduh Kwitansi PDF
                                        </a>
                                        <p style="margin:10px 0 0; color:#9ca3af; font-size:12px; font-family:'Segoe UI',Arial,sans-serif;">
                                            Link ini akan kadaluarsa dalam <strong style="color:#6b7280;">3 hari</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ===== PESAN PENUTUP (tanpa border box) ===== -->
                    <tr>
                        <td class="content-padding" style="padding:20px 40px 32px;">
                            <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.7; font-family:'Segoe UI',Arial,sans-serif;">
                                Semoga zakat yang Anda tunaikan menjadi berkah dan membawa kebaikan bagi yang membutuhkan. <em>Jazakallahu Khairan.</em>
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:0 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td height="1" style="background-color:#e5e7eb; font-size:1px; line-height:1px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ===== FOOTER ===== -->
                    <tr>
                        <td style="background-color:#f8faf8; padding:20px 40px; text-align:center; border-top:1px solid #f0f0f0;">
                            <p style="margin:0 0 3px; color:#2d6936; font-size:14px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif;">
                                {{ $appName }}
                            </p>
                            <p style="margin:0 0 2px; color:#9ca3af; font-size:11px; font-family:'Segoe UI',Arial,sans-serif;">
                                &copy; {{ date('Y') }} {{ $appName }}. All Rights Reserved.
                            </p>
                            <p style="margin:0; color:#c0c0c0; font-size:11px; font-family:'Segoe UI',Arial,sans-serif;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- end Card -->

            </td>
        </tr>
    </table>
    <!-- end Wrapper -->

</body>

</html>