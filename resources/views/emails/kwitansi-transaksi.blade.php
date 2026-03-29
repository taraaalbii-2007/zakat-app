{{-- resources/views/emails/kwitansi-transaksi.blade.php --}}
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

        // Helpers
        $isBeras   = $t->metode_pembayaran === 'beras';
        $isFidyah  = !empty($t->fidyah_tipe);
        $isMentah  = $t->fidyah_tipe === 'mentah';
        $isMatang  = $t->fidyah_tipe === 'matang';
        $isFidTunai= $t->fidyah_tipe === 'tunai';
        $isDijemput= $t->metode_penerimaan === 'dijemput';

        $metodePembayaranLabel = match($t->metode_pembayaran ?? '') {
            'tunai'         => 'Tunai',
            'transfer'      => 'Transfer Bank',
            'qris'          => 'QRIS',
            'beras'         => 'Beras',
            'bahan_mentah'  => 'Bahan Makanan Mentah',
            'makanan_matang'=> 'Makanan Siap Santap',
            default         => ucfirst($t->metode_pembayaran ?? '-'),
        };

        $fidyahTipeLabel = match($t->fidyah_tipe ?? '') {
            'mentah' => 'Bahan Makanan Mentah',
            'matang' => 'Makanan Siap Santap',
            'tunai'  => 'Tunai / Uang',
            default  => '-',
        };

        $caraSerahLabel = match($t->fidyah_cara_serah ?? '') {
            'dibagikan'  => 'Dibagikan kepada mustahik',
            'dijamu'     => 'Dijamu (makan bersama)',
            'via_lembaga'=> 'Diserahkan via lembaga',
            default      => $t->fidyah_cara_serah ?? '-',
        };
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

                    <!-- ===== BADGE METODE PENERIMAAN ===== -->
                    @if($isDijemput)
                    <tr>
                        <td style="padding:16px 40px 0; text-align:center;">
                            <span style="display:inline-block; background-color:#fef3c7; color:#92400e; font-size:11px; font-weight:700; padding:5px 14px; border-radius:999px; letter-spacing:0.5px; font-family:'Segoe UI',Arial,sans-serif;">
                                🚗 Zakat Via Penjemputan
                            </span>
                        </td>
                    </tr>
                    @endif

                    <!-- ===== SALAM ===== -->
                    <tr>
                        <td class="content-padding" style="padding:24px 40px 0;">
                            <p style="margin:0 0 4px; color:#111827; font-size:16px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif;">
                                Assalamu'alaikum, {{ $t->muzakki_nama }}!
                            </p>
                            <p style="margin:0; color:#6b7280; font-size:14px; line-height:1.7; font-family:'Segoe UI',Arial,sans-serif;">
                                Terima kasih telah menunaikan zakat. Berikut ringkasan transaksi Anda yang telah
                                @if($isDijemput) dijemput dan @endif diverifikasi oleh amil:
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td height="1" style="background-color:#e5e7eb; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ===== DETAIL TRANSAKSI ===== -->
                    <tr>
                        <td class="content-padding" style="padding:20px 40px 0;">
                            <p style="margin:0 0 14px; color:#374151; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:2px; font-family:'Segoe UI',Arial,sans-serif;">
                                Informasi Transaksi
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
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Tanggal Transaksi</td>
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

                                <!-- NIK (jika ada) -->
                                @if($t->muzakki_nik)
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">NIK</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Courier New',monospace; border-bottom:1px solid #f0f0f0;">{{ $t->muzakki_nik }}</td>
                                </tr>
                                @endif

                                <!-- Lembaga -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Lembaga</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ optional($t->lembaga)->nama ?? '-' }}</td>
                                </tr>

                                <!-- Amil (jika ada) -->
                                @if($t->amil)
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Amil Penerima</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ $t->amil->nama_lengkap ?? optional($t->amil->pengguna)->name ?? '-' }}
                                    </td>
                                </tr>
                                @endif

                                <!-- Program Zakat (jika ada) -->
                                @if($t->programZakat)
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Program Zakat</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->programZakat->nama_program }}</td>
                                </tr>
                                @endif

                                <!-- Status -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Status</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; border-bottom:1px solid #f0f0f0;">
                                        <span style="display:inline-block; background-color:#dcfce7; color:#166534; font-size:11px; font-weight:700; padding:4px 12px; border-radius:999px; letter-spacing:0.5px; text-transform:uppercase; font-family:'Segoe UI',Arial,sans-serif;">
                                            Terverifikasi
                                        </span>
                                    </td>
                                </tr>

                                <!-- Metode Penerimaan -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Metode Penerimaan</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ match($t->metode_penerimaan) {
                                            'datang_langsung' => 'Datang Langsung',
                                            'dijemput'        => 'Dijemput Amil',
                                            'daring'          => 'Daring / Online',
                                            default           => ucfirst($t->metode_penerimaan ?? '-'),
                                        } }}
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- ===== DETAIL ZAKAT ===== -->
                    <tr>
                        <td class="content-padding" style="padding:20px 40px 0;">
                            <p style="margin:0 0 14px; color:#374151; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:2px; font-family:'Segoe UI',Arial,sans-serif;">
                                Detail Zakat
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                <!-- Jenis Zakat -->
                                <tr>
                                    <td width="44%" style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jenis Zakat</td>
                                    <td width="4%" style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ optional($t->jenisZakat)->nama ?? '-' }}
                                    </td>
                                </tr>

                                <!-- Tipe Zakat -->
                                @if($t->tipeZakat)
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Tipe Zakat</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->tipeZakat->nama }}</td>
                                </tr>
                                @endif

                                @if($isFidyah)
                                {{-- ══ FIDYAH ══ --}}
                                <!-- Tipe Fidyah -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Tipe Fidyah</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $fidyahTipeLabel }}</td>
                                </tr>

                                <!-- Jumlah Hari -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Hari</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->fidyah_jumlah_hari }} hari</td>
                                </tr>

                                @if($isMentah)
                                <!-- Nama Bahan -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Nama Bahan Pokok</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->fidyah_nama_bahan ?? '-' }}</td>
                                </tr>
                                <!-- Berat per Hari -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Berat per Hari</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->fidyah_berat_per_hari_gram ?? 675 }} gram</td>
                                </tr>
                                <!-- Total Berat -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Total Berat</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#2d6936; font-size:14px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ number_format($t->fidyah_total_berat_kg ?? 0, 2) }} kg
                                    </td>
                                </tr>
                                @endif

                                @if($isMatang)
                                <!-- Jumlah Box -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Box</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#2d6936; font-size:14px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->fidyah_jumlah_box }} box</td>
                                </tr>
                                @if($t->fidyah_menu_makanan)
                                <!-- Menu -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Menu Makanan</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->fidyah_menu_makanan }}</td>
                                </tr>
                                @endif
                                @if($t->fidyah_harga_per_box)
                                <!-- Harga per Box -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Harga per Box</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Rp {{ number_format($t->fidyah_harga_per_box, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <!-- Cara Serah -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Cara Penyerahan</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $caraSerahLabel }}</td>
                                </tr>
                                @endif

                                @if($isFidTunai)
                                <!-- Jumlah Fidyah Tunai -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Total Fidyah</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#2d6936; font-size:15px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif

                                @else
                                {{-- ══ NON-FIDYAH ══ --}}

                                @if($t->jumlah_jiwa)
                                <!-- Jumlah Jiwa -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Jiwa</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $t->jumlah_jiwa }} jiwa</td>
                                </tr>
                                @endif

                                @if($t->nominal_per_jiwa)
                                <!-- Nominal per Jiwa -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Nominal per Jiwa</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Rp {{ number_format($t->nominal_per_jiwa, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                @if($isBeras)
                                <!-- Jumlah Beras -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Beras</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#2d6936; font-size:15px; font-weight:700; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        {{ $t->jumlah_beras_kg }} kg
                                    </td>
                                </tr>
                                @if($t->harga_beras_per_kg)
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Harga Beras / kg</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Rp {{ number_format($t->harga_beras_per_kg, 0, ',', '.') }}</td>
                                </tr>
                                @endif

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

                                @if($t->nilai_harta)
                                <!-- Nilai Harta (Mal) -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Nilai Harta</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Rp {{ number_format($t->nilai_harta, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                @endif {{-- end non-fidyah --}}

                                <!-- Nama Jiwa (jika ada) -->
                                @if(!empty($t->nama_jiwa_json) && count($t->nama_jiwa_json) > 0)
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;" valign="top">Nama Jiwa</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;" valign="top">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:500; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        @foreach($t->nama_jiwa_json as $i => $nama)
                                            @if(!empty($nama))
                                                {{ ($i + 1) }}. {{ $nama }}<br>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                @endif

                            </table>
                        </td>
                    </tr>

                    <!-- ===== DETAIL PEMBAYARAN ===== -->
                    @if(!$isMentah && !$isMatang)
                    <tr>
                        <td class="content-padding" style="padding:20px 40px 0;">
                            <p style="margin:0 0 14px; color:#374151; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:2px; font-family:'Segoe UI',Arial,sans-serif;">
                                Detail Pembayaran
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                <!-- Metode Pembayaran -->
                                <tr>
                                    <td width="44%" style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Metode Pembayaran</td>
                                    <td width="4%" style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">{{ $metodePembayaranLabel }}</td>
                                </tr>

                                @if($t->jumlah_dibayar > 0)
                                <!-- Jumlah Dibayar -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Jumlah Dibayar</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#111827; font-size:13px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Rp {{ number_format($t->jumlah_dibayar, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                @if($t->has_infaq && $t->jumlah_infaq > 0)
                                <!-- Infaq -->
                                <tr>
                                    <td style="padding:9px 0; font-size:13px; color:#6b7280; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">Infaq Sukarela</td>
                                    <td style="padding:9px 4px; color:#9ca3af; font-size:13px; border-bottom:1px solid #f0f0f0;">:</td>
                                    <td style="padding:9px 0; color:#d97706; font-size:13px; font-weight:600; font-family:'Segoe UI',Arial,sans-serif; border-bottom:1px solid #f0f0f0;">
                                        + Rp {{ number_format($t->jumlah_infaq, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif

                            </table>
                        </td>
                    </tr>
                    @endif

                    <!-- ===== KETERANGAN (jika ada) ===== -->
                    @if($t->keterangan)
                    <tr>
                        <td class="content-padding" style="padding:16px 40px 0;">
                            <p style="margin:0 0 6px; color:#374151; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:2px; font-family:'Segoe UI',Arial,sans-serif;">
                                Catatan
                            </p>
                            <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.6; font-style:italic; font-family:'Segoe UI',Arial,sans-serif;">
                                "{{ $t->keterangan }}"
                            </p>
                        </td>
                    </tr>
                    @endif

                    <!-- ===== TOTAL BOX ===== -->
                    @if(!$isMentah && !$isMatang && !$isBeras)
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border:1px solid #bbf7d0; border-radius:10px; padding:16px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-size:13px; color:#15803d; font-family:'Segoe UI',Arial,sans-serif; font-weight:500;">
                                                    Total Zakat Diterima
                                                </td>
                                                <td style="text-align:right; font-size:18px; font-weight:800; color:#15803d; font-family:'Segoe UI',Arial,sans-serif;">
                                                    Rp {{ number_format($t->jumlah_dibayar ?: $t->jumlah, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @if($t->has_infaq && $t->jumlah_infaq > 0)
                                            <tr>
                                                <td colspan="2" style="padding-top:4px;">
                                                    <p style="margin:0; font-size:11px; color:#16a34a; font-family:'Segoe UI',Arial,sans-serif;">
                                                        Termasuk infaq sukarela Rp {{ number_format($t->jumlah_infaq, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @elseif($isBeras)
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border:1px solid #bbf7d0; border-radius:10px; padding:16px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-size:13px; color:#15803d; font-family:'Segoe UI',Arial,sans-serif; font-weight:500;">
                                                    Total Beras Diterima
                                                </td>
                                                <td style="text-align:right; font-size:18px; font-weight:800; color:#15803d; font-family:'Segoe UI',Arial,sans-serif;">
                                                    {{ $t->jumlah_beras_kg }} kg
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @elseif($isMentah)
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border:1px solid #bbf7d0; border-radius:10px; padding:16px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-size:13px; color:#15803d; font-family:'Segoe UI',Arial,sans-serif; font-weight:500;">
                                                    Total Fidyah Bahan Mentah
                                                </td>
                                                <td style="text-align:right; font-size:18px; font-weight:800; color:#15803d; font-family:'Segoe UI',Arial,sans-serif;">
                                                    {{ number_format($t->fidyah_total_berat_kg ?? 0, 2) }} kg
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @elseif($isMatang)
                    <tr>
                        <td style="padding:16px 40px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border:1px solid #bbf7d0; border-radius:10px; padding:16px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="font-size:13px; color:#15803d; font-family:'Segoe UI',Arial,sans-serif; font-weight:500;">
                                                    Total Fidyah Makanan Matang
                                                </td>
                                                <td style="text-align:right; font-size:18px; font-weight:800; color:#15803d; font-family:'Segoe UI',Arial,sans-serif;">
                                                    {{ $t->fidyah_jumlah_box }} box
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif

                    <!-- ===== TOMBOL DOWNLOAD ===== -->
                    <tr>
                        <td class="content-padding" style="padding:24px 40px 0; text-align:center;">
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

                    <!-- ===== PESAN PENUTUP ===== -->
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