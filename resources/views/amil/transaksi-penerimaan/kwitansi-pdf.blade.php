<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi - {{ $transaksi->no_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            width: 100%;
        }

        /* ══ HEADER ══ */
        .header-outer { background: #0d2b1f; }
        .header-accent { background: #16a34a; height: 3px; width: 100%; }
        .header-inner  { padding: 16px 28px 14px; }
        .header-table  { width: 100%; border-collapse: collapse; }

        .header-logo-cell { width: 52px; vertical-align: middle; }
        .header-logo {
            width: 48px; height: 48px;
            border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.2);
        }
        .header-text-cell { padding-left: 14px; vertical-align: middle; }
        .header-org {
            font-size: 15px; font-weight: bold;
            color: #ffffff; letter-spacing: 0.2px; margin-bottom: 3px;
        }
        .header-address {
            font-size: 9.5px; color: rgba(255,255,255,0.55); line-height: 1.55;
        }
        .header-badge-cell {
            vertical-align: middle; text-align: right; width: 165px;
        }
        .kwt-tag {
            background: rgba(22,163,74,0.18);
            border: 1px solid rgba(22,163,74,0.4);
            border-radius: 6px; padding: 7px 13px; text-align: right;
        }
        .kwt-tag-label {
            font-size: 7.5px; color: rgba(255,255,255,0.45);
            text-transform: uppercase; letter-spacing: 1.5px;
        }
        .kwt-tag-no {
            font-size: 11px; font-weight: bold;
            color: #4ade80; letter-spacing: 0.3px; margin-top: 3px;
        }

        /* ══ TITLE BAND ══ */
        .title-band {
            background: #f0fdf4;
            border-top: 1px solid #d1fae5;
            border-bottom: 2px solid #16a34a;
            padding: 9px 28px; text-align: center;
        }
        .title-band h2 {
            font-size: 12.5px; font-weight: bold;
            color: #14532d; letter-spacing: 4px; text-transform: uppercase;
        }

        /* ══ BODY ══ */
        .body-content { padding: 14px 28px 0; }

        /* Muzakki Card */
        .card {
            border: 1px solid #d1fae5;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .card-header {
            background: #f0fdf4;
            border-bottom: 1px solid #d1fae5;
            padding: 5px 14px;
        }
        .card-header span {
            font-size: 8px; font-weight: bold;
            color: #15803d; text-transform: uppercase; letter-spacing: 1.8px;
        }

        .mz-table { width: 100%; border-collapse: collapse; }
        .mz-table td {
            padding: 7px 14px;
            border-bottom: 1px solid #f0fdf4;
            vertical-align: top;
        }
        .mz-table tr:last-child td { border-bottom: none; }
        .mz-lbl {
            font-size: 8.5px; font-weight: bold;
            color: #9ca3af; text-transform: uppercase; letter-spacing: 0.7px;
            width: 28%; background: #fafcfa;
        }
        .mz-sep  { width: 3%; color: #e5e7eb; text-align: center; background: #fafcfa; }
        .mz-val  { font-size: 11.5px; color: #111827; font-weight: 500; }
        .mz-name { font-size: 14px; font-weight: bold; color: #14532d; }

        /* Amount Hero */
        .amount-hero { border-radius: 8px; margin-bottom: 12px; overflow: hidden; }
        .amount-inner { background: #0d2b1f; padding: 14px 20px; }
        .amount-table { width: 100%; border-collapse: collapse; }
        .amount-lbl {
            font-size: 8.5px; font-weight: bold;
            color: rgba(255,255,255,0.4); text-transform: uppercase;
            letter-spacing: 1.5px; margin-bottom: 4px;
        }
        .amount-val {
            font-size: 26px; font-weight: bold;
            color: #fff; letter-spacing: -0.5px; line-height: 1;
        }
        .amount-terbilang {
            font-size: 9px; color: rgba(255,255,255,0.38);
            font-style: italic; margin-top: 4px;
        }
        .amount-right { text-align: right; vertical-align: middle; width: 130px; }
        .pill-verified {
            background: rgba(74,222,128,0.15);
            border: 1px solid rgba(74,222,128,0.35);
            border-radius: 999px; padding: 5px 14px;
            font-size: 9.5px; font-weight: bold;
            color: #86efac; text-transform: uppercase; letter-spacing: 0.8px;
        }
        .pill-other {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px; padding: 5px 14px;
            font-size: 9.5px; font-weight: bold;
            color: rgba(255,255,255,0.6); text-transform: uppercase;
        }
        .amount-strip { height: 3px; background: #16a34a; }

        /* Section pill divider */
        .sect-divider { text-align: center; margin-bottom: 9px; }
        .sect-pill {
            font-size: 8px; font-weight: bold; color: #15803d;
            text-transform: uppercase; letter-spacing: 2px;
            padding: 3px 16px;
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 999px;
        }

        /* Detail Table */
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table td {
            padding: 7px 14px;
            border-bottom: 1px solid #f0fdf4;
            vertical-align: top;
        }
        .detail-table tr:last-child td { border-bottom: none; }
        .detail-table tr:nth-child(odd)  td { background: #fff; }
        .detail-table tr:nth-child(even) td { background: #fafcfa; }
        .dt-k {
            font-size: 8.5px; font-weight: bold;
            color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;
            width: 34%;
        }
        .dt-s { width: 4%; color: #e5e7eb; text-align: center; }
        .dt-v { font-size: 11px; color: #1f2937; font-weight: 500; }
        .dt-v-acc { font-size: 11px; font-weight: bold; color: #14532d; }

        /* Badges */
        .bdg { border-radius: 999px; padding: 2px 9px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .bdg-g { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .bdg-y { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
        .bdg-b { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

        /* Signature */
        .sig-body { padding: 14px; text-align: center; }
        .sig-date  { font-size: 10px; color: #9ca3af; margin-bottom: 2px; }
        .sig-role  { font-size: 10.5px; font-weight: bold; color: #14532d; margin-bottom: 12px; }
        .sig-space { height: 52px; }
        .sig-img   { max-width: 110px; max-height: 52px; }
        .sig-line  { width: 180px; border-top: 1.5px solid #374151; margin: 0 auto 6px; }
        .sig-name  { font-size: 12px; font-weight: bold; color: #111827; }
        .sig-sub   { font-size: 9.5px; color: #9ca3af; margin-top: 2px; }

        /* Footer */
        .footer-outer { margin-top: 16px; background: #0d2b1f; }
        .footer-accent { height: 2px; background: #16a34a; }
        .footer-inner  { padding: 10px 28px; text-align: center; }
        .footer-main   { font-size: 10.5px; font-weight: bold; color: rgba(255,255,255,0.75); margin-bottom: 3px; }
        .footer-sub    { font-size: 9px; color: rgba(255,255,255,0.35); line-height: 1.6; }

        @page { margin: 0; size: A4 portrait; }
    </style>
</head>
<body>

@php
    $config  = \App\Models\KonfigurasiAplikasi::first();
    $appName = optional($config)->nama_aplikasi ?? 'Niat Zakat';

    // Logo — embed as base64 for DomPDF
    $logoBase64 = null;
    $logoPath   = base_path('public/images/logo.png');
    if (file_exists($logoPath)) {
        $logoMime   = mime_content_type($logoPath);
        $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    // Tanda tangan — ambil dari storage path, embed as base64
    $ttdBase64 = null;
    $amil      = $transaksi->amil;
    if ($amil && !empty($amil->tanda_tangan)) {
        // $amil->tanda_tangan berisi path relatif seperti "amil/tanda_tangan/1_xxx.png"
        $ttdPath = storage_path('app/public/' . $amil->tanda_tangan);
        if (file_exists($ttdPath)) {
            $ttdMime   = mime_content_type($ttdPath);
            $ttdBase64 = 'data:' . $ttdMime . ';base64,' . base64_encode(file_get_contents($ttdPath));
        }
    }

    $t = $transaksi;
@endphp

{{-- HEADER --}}
<div class="header-outer">
    <div class="header-accent"></div>
    <div class="header-inner">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" class="header-logo" alt="Logo">
                    @endif
                </td>
                <td class="header-text-cell">
                    <div class="header-org">{{ $t->lembaga->nama ?? $appName }}</div>
                    <div class="header-address">
                        {{ $t->lembaga->alamat_lengkap ?? $t->lembaga->alamat ?? '' }}
                        @if($t->lembaga->telepon || $t->lembaga->email)
                        <br>
                        @if($t->lembaga->telepon) Telp: {{ $t->lembaga->telepon }} @endif
                        @if($t->lembaga->telepon && $t->lembaga->email) &nbsp;&bull;&nbsp; @endif
                        @if($t->lembaga->email) {{ $t->lembaga->email }} @endif
                        @endif
                    </div>
                </td>
                <td class="header-badge-cell">
                    <div class="kwt-tag">
                        <div class="kwt-tag-label">No. Kwitansi</div>
                        <div class="kwt-tag-no">{{ $t->no_kwitansi ?? $t->no_transaksi }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- TITLE BAND --}}
<div class="title-band">
    <h2>Kwitansi Penerimaan Zakat</h2>
</div>

<div class="body-content">

    {{-- Identitas --}}
    <div class="card">
        <div class="card-header"><span>Identitas Muzakki</span></div>
        <table class="mz-table">
            <tr>
                <td class="mz-lbl" style="width:22%;">No. Transaksi</td>
                <td class="mz-sep">:</td>
                <td class="mz-val" style="width:28%;font-family:monospace;font-size:11px;font-weight:600;">{{ $t->no_transaksi }}</td>
                <td class="mz-lbl" style="width:18%;border-left:1px solid #f0fdf4;">Tanggal</td>
                <td class="mz-sep">:</td>
                <td class="mz-val">{{ $t->tanggal_transaksi->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="mz-lbl">Terima Dari</td>
                <td class="mz-sep">:</td>
                <td class="mz-name mz-val mz-name" colspan="4">{{ $t->muzakki_nama }}</td>
            </tr>
            @if($t->muzakki_nik)
            <tr>
                <td class="mz-lbl">NIK</td>
                <td class="mz-sep">:</td>
                <td class="mz-val" colspan="4">{{ $t->muzakki_nik }}</td>
            </tr>
            @endif
            @if($t->muzakki_alamat)
            <tr>
                <td class="mz-lbl">Alamat</td>
                <td class="mz-sep">:</td>
                <td class="mz-val" colspan="4">{{ $t->muzakki_alamat }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Amount Hero --}}
    @if($t->jumlah > 0)
    <div class="amount-hero">
        <div class="amount-inner">
            <table class="amount-table">
                <tr>
                    <td>
                        <div class="amount-lbl">Jumlah Diterima</div>
                        <div class="amount-val">{{ $t->jumlah_formatted }}</div>
                        @if(function_exists('terbilang'))
                        <div class="amount-terbilang">{{ ucfirst(terbilang($t->jumlah)) }} Rupiah</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="amount-strip"></div>
    </div>
    @elseif(isset($t->isBayarBeras) && $t->isBayarBeras && $t->jumlah_beras_kg > 0)
    <div class="amount-hero">
        <div class="amount-inner">
            <table class="amount-table">
                <tr>
                    <td>
                        <div class="amount-lbl">Zakat Fitrah Beras</div>
                        <div class="amount-val">{{ number_format($t->jumlah_beras_kg, 2, ',', '.') }} kg</div>
                        @if($t->jumlah_jiwa)
                        <div class="amount-terbilang">
                            {{ $t->jumlah_jiwa }} jiwa
                            @if($t->harga_beras_per_kg)
                                &bull; Setara &plusmn; Rp {{ number_format($t->jumlah_beras_kg * $t->harga_beras_per_kg, 0, ',', '.') }}
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="amount-strip"></div>
    </div>
    @endif

    {{-- Section Divider --}}
    <div class="sect-divider">
        <span class="sect-pill">Detail Pembayaran Zakat</span>
    </div>

    {{-- Detail --}}
    <div class="card" style="margin-bottom:12px;">
        <table class="detail-table">
            <tr>
                <td class="dt-k">Untuk Pembayaran</td>
                <td class="dt-s">:</td>
                <td class="dt-v dt-v-acc">
                    {{ optional($t->jenisZakat)->nama ?? '-' }}
                    @if($t->tipeZakat) &ndash; {{ $t->tipeZakat->nama }} @endif
                    @if($t->programZakat)
                        <br><span style="font-size:10px;font-weight:400;color:#6b7280;">Program: {{ $t->programZakat->nama_program }}</span>
                    @endif
                </td>
            </tr>
            @if($t->jumlah > 0 && function_exists('terbilang'))
            <tr>
                <td class="dt-k">Terbilang</td>
                <td class="dt-s">:</td>
                <td class="dt-v" style="font-style:italic;">{{ ucfirst(terbilang($t->jumlah)) }} Rupiah</td>
            </tr>
            @elseif(isset($t->isBayarBeras) && $t->isBayarBeras)
            <tr>
                <td class="dt-k">Terbilang</td>
                <td class="dt-s">:</td>
                <td class="dt-v" style="font-style:italic;">{{ number_format($t->jumlah_beras_kg, 2, ',', '.') }} kilogram beras</td>
            </tr>
            @endif
            <tr>
                <td class="dt-k">Metode Pembayaran</td>
                <td class="dt-s">:</td>
                <td class="dt-v">
                    {{ ucfirst($t->metode_pembayaran ?? '-') }}
                    &nbsp;
                    @if($t->status === 'verified')
                        <span class="bdg bdg-g">Terverifikasi</span>
                    @elseif($t->status === 'pending')
                        <span class="bdg bdg-y">Menunggu</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="dt-k">Metode Penerimaan</td>
                <td class="dt-s">:</td>
                <td class="dt-v">{{ $t->metode_penerimaan === 'dijemput' ? 'Dijemput Amil' : 'Datang Langsung' }}</td>
            </tr>
            @if($t->no_referensi_transfer)
            <tr>
                <td class="dt-k">No. Referensi</td>
                <td class="dt-s">:</td>
                <td class="dt-v">{{ $t->no_referensi_transfer }}</td>
            </tr>
            @endif
            @if(in_array($t->metode_pembayaran, ['transfer','qris']) && $t->konfirmasi_status)
            <tr>
                <td class="dt-k">Status Transfer</td>
                <td class="dt-s">:</td>
                <td class="dt-v">
                    @if($t->konfirmasi_status === 'dikonfirmasi') <span class="bdg bdg-b">Dikonfirmasi Amil</span>
                    @elseif($t->konfirmasi_status === 'menunggu_konfirmasi') <span class="bdg bdg-y">Menunggu Konfirmasi</span>
                    @else {{ ucfirst($t->konfirmasi_status) }}
                    @endif
                </td>
            </tr>
            @endif
            @if(isset($t->isZakatFitrah) && $t->isZakatFitrah && !(isset($t->isBayarBeras) && $t->isBayarBeras))
                @if($t->jumlah_jiwa)
                <tr><td class="dt-k">Jumlah Jiwa</td><td class="dt-s">:</td><td class="dt-v">{{ $t->jumlah_jiwa }} jiwa</td></tr>
                @endif
                @if($t->nominal_per_jiwa)
                <tr><td class="dt-k">Nominal / Jiwa</td><td class="dt-s">:</td><td class="dt-v">Rp {{ number_format($t->nominal_per_jiwa, 0, ',', '.') }}</td></tr>
                @endif
            @endif
            @if(isset($t->isBayarBeras) && $t->isBayarBeras)
                @if($t->jumlah_beras_kg)
                <tr><td class="dt-k">Jumlah Beras</td><td class="dt-s">:</td><td class="dt-v">{{ $t->jumlah_beras_kg }} kg</td></tr>
                @endif
                @if($t->jumlah_jiwa)
                <tr><td class="dt-k">Jumlah Jiwa</td><td class="dt-s">:</td><td class="dt-v">{{ $t->jumlah_jiwa }} jiwa</td></tr>
                @endif
                @if($t->harga_beras_per_kg !== null)
                <tr><td class="dt-k">Harga / kg</td><td class="dt-s">:</td><td class="dt-v">Rp {{ number_format($t->harga_beras_per_kg, 0, ',', '.') }}</td></tr>
                @endif
            @endif
            @if(isset($t->isZakatMal) && $t->isZakatMal)
                @if($t->nilai_harta)
                <tr><td class="dt-k">Nilai Harta</td><td class="dt-s">:</td><td class="dt-v">Rp {{ number_format($t->nilai_harta, 0, ',', '.') }}</td></tr>
                @endif
                @if($t->nisab_saat_ini)
                <tr><td class="dt-k">Nisab Saat Ini</td><td class="dt-s">:</td><td class="dt-v">Rp {{ number_format($t->nisab_saat_ini, 0, ',', '.') }}</td></tr>
                @endif
                <tr><td class="dt-k">Sudah Haul</td><td class="dt-s">:</td><td class="dt-v">{{ $t->sudah_haul ? 'Sudah' : 'Belum' }}</td></tr>
            @endif
            @if($t->keterangan)
            <tr><td class="dt-k">Keterangan</td><td class="dt-s">:</td><td class="dt-v">{{ $t->keterangan }}</td></tr>
            @endif
        </table>
    </div>

    {{-- Tanda Tangan --}}
    <div class="card">
        <div class="card-header"><span>Pengesahan &amp; Tanda Tangan</span></div>
        <div class="sig-body">
            <div class="sig-date">{{ $t->tanggal_transaksi->format('d F Y') }}</div>
            <div class="sig-role">Tanda Tangan Penerimaan</div>
            <div class="sig-space">
                @if($ttdBase64)
                    <img src="{{ $ttdBase64 }}" class="sig-img" alt="TTD">
                @endif
            </div>
            <div class="sig-line"></div>
            <div class="sig-name">
                {{ optional($t->amil)->nama_lengkap ?? optional(optional($t->amil)->pengguna)->name ?? '_____________________' }}
            </div>
            <div class="sig-sub">Amil / Petugas</div>
        </div>
    </div>

</div>

{{-- FOOTER --}}
<div class="footer-outer">
    <div class="footer-accent"></div>
    <div class="footer-inner">
        <div class="footer-main">Terima kasih telah menunaikan zakat melalui {{ $t->lembaga->nama ?? $appName }}</div>
        <div class="footer-sub">
            Kwitansi ini dicetak secara otomatis oleh sistem &bull; {{ $appName }} &bull; Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
        </div>
    </div>
</div>

</body>
</html>