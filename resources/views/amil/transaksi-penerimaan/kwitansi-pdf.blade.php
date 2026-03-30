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
            background: #ffffff;
            width: 100%;
        }

        /* ══ HEADER ══ */
        .header-outer {
            background: #17a34a;
            padding: 0;
        }
        .header-top-stripe {
            background: #22c55e;
            height: 4px;
            width: 100%;
        }
        .header-inner {
            padding: 18px 30px 16px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo-cell {
            width: 58px;
            vertical-align: middle;
        }
        .header-logo {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            border: 2px solid rgba(255,255,255,0.35);
            background: #fff;
        }
        .header-text-cell {
            padding-left: 14px;
            vertical-align: middle;
        }
        .header-org {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }
        .header-address {
            font-size: 9px;
            color: rgba(255,255,255,0.80);
            line-height: 1.6;
        }
        .header-badge-cell {
            vertical-align: middle;
            text-align: right;
            width: 175px;
        }
        .kwt-tag {
            background: rgba(255,255,255,0.18);
            border: 1.5px solid rgba(255,255,255,0.40);
            border-radius: 8px;
            padding: 9px 14px;
            text-align: right;
        }
        .kwt-tag-label {
            font-size: 7.5px;
            color: rgba(255,255,255,0.75);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .kwt-tag-no {
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 0.3px;
            margin-top: 3px;
        }

        /* ══ TITLE BAND ══ */
        .title-band {
            background: #f0fdf4;
            border-top: 3px solid #22c55e;
            border-bottom: 1px solid #bbf7d0;
            padding: 10px 30px;
            text-align: center;
        }
        .title-band h2 {
            font-size: 13px;
            font-weight: bold;
            color: #15803d;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        /* ══ BODY ══ */
        .body-content {
            padding: 16px 28px 0;
        }

        /* ── Card ── */
        .card {
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .card-header {
            background: linear-gradient(90deg, #17a34a 0%, #22c55e 100%);
            padding: 6px 14px;
        }
        .card-header span {
            font-size: 8px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* ── Muzakki Table ── */
        .mz-table {
            width: 100%;
            border-collapse: collapse;
        }
        .mz-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #f0fdf4;
            vertical-align: top;
        }
        .mz-table tr:last-child td {
            border-bottom: none;
        }
        .mz-lbl {
            font-size: 8.5px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            width: 28%;
            background: #fafffe;
        }
        .mz-sep {
            width: 3%;
            color: #d1d5db;
            text-align: center;
            background: #fafffe;
        }
        .mz-val {
            font-size: 11.5px;
            color: #111827;
            font-weight: 500;
        }
        .mz-name {
            font-size: 14px;
            font-weight: bold;
            color: #15803d;
        }

        /* ── Amount Hero ── */
        .amount-hero {
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            border: 1.5px solid #22c55e;
        }
        .amount-inner {
            background: linear-gradient(135deg, #17a34a 0%, #15803d 100%);
            padding: 16px 22px;
        }
        .amount-table {
            width: 100%;
            border-collapse: collapse;
        }
        .amount-lbl {
            font-size: 8.5px;
            font-weight: bold;
            color: rgba(255,255,255,0.70);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 5px;
        }
        .amount-val {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .amount-terbilang {
            font-size: 9px;
            color: rgba(255,255,255,0.65);
            font-style: italic;
            margin-top: 5px;
        }
        .amount-right {
            text-align: right;
            vertical-align: middle;
            width: 130px;
        }
        .pill-verified {
            background: rgba(255,255,255,0.20);
            border: 1.5px solid rgba(255,255,255,0.50);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 9.5px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .pill-other {
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 9.5px;
            font-weight: bold;
            color: rgba(255,255,255,0.75);
            text-transform: uppercase;
        }
        .amount-strip {
            height: 4px;
            background: #22c55e;
        }

        /* ── Amount untuk beras/fidyah non-uang ── */
        .amount-alt-inner {
            background: linear-gradient(135deg, #17a34a 0%, #15803d 100%);
            padding: 14px 22px;
        }

        /* ── Section Divider ── */
        .sect-divider {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }
        .sect-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #bbf7d0;
        }
        .sect-pill {
            position: relative;
            font-size: 8px;
            font-weight: bold;
            color: #17a34a;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 4px 18px;
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 999px;
        }

        /* ── Detail Table ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #f0fdf4;
            vertical-align: top;
        }
        .detail-table tr:last-child td {
            border-bottom: none;
        }
        .detail-table tr:nth-child(odd) td {
            background: #ffffff;
        }
        .detail-table tr:nth-child(even) td {
            background: #fafffe;
        }
        .dt-k {
            font-size: 8.5px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 34%;
        }
        .dt-s {
            width: 4%;
            color: #d1d5db;
            text-align: center;
        }
        .dt-v {
            font-size: 11px;
            color: #1f2937;
            font-weight: 500;
        }
        .dt-v-acc {
            font-size: 11px;
            font-weight: bold;
            color: #15803d;
        }

        /* ── Badges ── */
        .bdg {
            border-radius: 999px;
            padding: 2px 10px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .bdg-g {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .bdg-y {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde68a;
        }
        .bdg-b {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        /* ── Infaq Note ── */
        .infaq-note {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 12px;
        }
        .infaq-note-label {
            font-size: 8px;
            font-weight: bold;
            color: #17a34a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .infaq-note-val {
            font-size: 12px;
            font-weight: bold;
            color: #15803d;
        }

        /* ── Signature ── */
        .sig-body {
            padding: 16px 20px;
            text-align: center;
        }
        .sig-date {
            font-size: 9.5px;
            color: #9ca3af;
            margin-bottom: 2px;
        }
        .sig-role {
            font-size: 10.5px;
            font-weight: bold;
            color: #17a34a;
            margin-bottom: 14px;
        }
        .sig-space {
            height: 55px;
            display: block;
        }
        .sig-img {
            max-width: 115px;
            max-height: 55px;
        }
        .sig-line {
            width: 190px;
            border-top: 1.5px solid #374151;
            margin: 0 auto 6px;
        }
        .sig-name {
            font-size: 12px;
            font-weight: bold;
            color: #111827;
        }
        .sig-sub {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Footer ── */
        .footer-outer {
            margin-top: 16px;
            background: #17a34a;
        }
        .footer-accent {
            height: 3px;
            background: #22c55e;
        }
        .footer-inner {
            padding: 10px 30px;
            text-align: center;
        }
        .footer-main {
            font-size: 10px;
            font-weight: bold;
            color: rgba(255,255,255,0.90);
            margin-bottom: 3px;
        }
        .footer-sub {
            font-size: 8.5px;
            color: rgba(255,255,255,0.60);
            line-height: 1.6;
        }

        /* ── Divider Line ── */
        .divider-line {
            border: none;
            border-top: 1px solid #bbf7d0;
            margin: 0;
        }

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

    // Tanda tangan — embed as base64
    $ttdBase64 = null;
    $amil      = $transaksi->amil;
    if ($amil && !empty($amil->tanda_tangan)) {
        $ttdPath = storage_path('app/public/' . $amil->tanda_tangan);
        if (file_exists($ttdPath)) {
            $ttdMime   = mime_content_type($ttdPath);
            $ttdBase64 = 'data:' . $ttdMime . ';base64,' . base64_encode(file_get_contents($ttdPath));
        }
    }

    $t = $transaksi;
@endphp

{{-- ══ HEADER ══ --}}
<div class="header-outer">
    <div class="header-top-stripe"></div>
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

{{-- ══ TITLE BAND ══ --}}
<div class="title-band">
    <h2>Kwitansi Penerimaan Zakat</h2>
</div>

<div class="body-content">

    {{-- ── Identitas Muzakki ── --}}
    <div class="card" style="margin-top:14px;">
        <div class="card-header"><span>Identitas Muzakki</span></div>
        <table class="mz-table">
            <tr>
                <td class="mz-lbl" style="width:22%;">No. Transaksi</td>
                <td class="mz-sep">:</td>
                <td class="mz-val" style="width:28%; font-family:monospace; font-size:10.5px; font-weight:600;">
                    {{ $t->no_transaksi }}
                </td>
                <td class="mz-lbl" style="width:16%; border-left:1px solid #f0fdf4;">Tanggal</td>
                <td class="mz-sep">:</td>
                <td class="mz-val">{{ $t->tanggal_transaksi->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="mz-lbl">Terima Dari</td>
                <td class="mz-sep">:</td>
                <td class="mz-val mz-name" colspan="4">{{ $t->muzakki_nama }}</td>
            </tr>
            @if($t->muzakki_nik)
            <tr>
                <td class="mz-lbl">NIK</td>
                <td class="mz-sep">:</td>
                <td class="mz-val" style="font-family:monospace;" colspan="4">{{ $t->muzakki_nik }}</td>
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

    {{-- ── Amount Hero (Uang) ── --}}
    @if($t->jumlah > 0)
    <div class="amount-hero">
        <div class="amount-inner">
            <table class="amount-table">
                <tr>
                    <td>
                        <div class="amount-lbl">Jumlah Diterima</div>
                        <div class="amount-val">{{ $t->jumlah_formatted }}</div>
                        @if(function_exists('terbilang'))
                        <div class="amount-terbilang">
                            &#x2605; {{ ucfirst(terbilang($t->jumlah)) }} Rupiah
                        </div>
                        @endif
                    </td>
                    <td class="amount-right">
                        @if($t->status === 'verified')
                            <span class="pill-verified">&#10003; Terverifikasi</span>
                        @else
                            <span class="pill-other">{{ ucfirst($t->status) }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="amount-strip"></div>
    </div>

    {{-- Infaq Note jika ada --}}
    @if($t->has_infaq && $t->jumlah_infaq > 0)
    <div class="infaq-note">
        <div class="infaq-note-label">&#9829; Infaq Sukarela Tercatat</div>
        <div class="infaq-note-val">
            Dibayar: {{ $t->jumlah_dibayar_formatted }}
            &nbsp;&bull;&nbsp; Zakat: {{ $t->jumlah_formatted }}
            &nbsp;&bull;&nbsp; Infaq: {{ $t->jumlah_infaq_formatted }}
        </div>
    </div>
    @endif

    {{-- ── Amount Hero (Beras Fitrah) ── --}}
    @elseif(isset($t->isBayarBeras) && $t->isBayarBeras && $t->jumlah_beras_kg > 0)
    <div class="amount-hero">
        <div class="amount-inner">
            <table class="amount-table">
                <tr>
                    <td>
                        <div class="amount-lbl">Zakat Fitrah — Beras</div>
                        <div class="amount-val" style="font-size:24px;">
                            {{ number_format($t->jumlah_beras_kg, 2, ',', '.') }} kg
                        </div>
                        @if($t->jumlah_jiwa)
                        <div class="amount-terbilang">
                            {{ $t->jumlah_jiwa }} jiwa
                            @if($t->harga_beras_per_kg)
                                &bull; Setara &plusmn; Rp {{ number_format($t->jumlah_beras_kg * $t->harga_beras_per_kg, 0, ',', '.') }}
                            @endif
                        </div>
                        @endif
                    </td>
                    <td class="amount-right">
                        @if($t->status === 'verified')
                            <span class="pill-verified">&#10003; Terverifikasi</span>
                        @else
                            <span class="pill-other">{{ ucfirst($t->status) }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="amount-strip"></div>
    </div>

    {{-- ── Amount Hero (Fidyah non-uang) ── --}}
    @elseif(isset($t->isFidyah) && $t->isFidyah && in_array($t->fidyah_tipe, ['mentah','matang']))
    <div class="amount-hero">
        <div class="amount-inner">
            <table class="amount-table">
                <tr>
                    <td>
                        @if($t->fidyah_tipe === 'mentah')
                        <div class="amount-lbl">Fidyah — Bahan Pokok Mentah</div>
                        <div class="amount-val" style="font-size:24px;">
                            {{ number_format($t->fidyah_total_berat_kg ?? 0, 2, ',', '.') }} kg
                        </div>
                        <div class="amount-terbilang">
                            {{ $t->fidyah_jumlah_hari }} hari
                            &bull; {{ $t->fidyah_nama_bahan ?? 'Bahan Pokok' }}
                            &bull; {{ $t->fidyah_berat_per_hari_gram ?? 675 }} gram/hari
                        </div>
                        @else
                        <div class="amount-lbl">Fidyah — Makanan Siap Santap</div>
                        <div class="amount-val" style="font-size:24px;">
                            {{ $t->fidyah_jumlah_box ?? $t->fidyah_jumlah_hari }} Box
                        </div>
                        <div class="amount-terbilang">
                            {{ $t->fidyah_jumlah_hari }} hari
                            @if($t->fidyah_menu_makanan) &bull; {{ $t->fidyah_menu_makanan }} @endif
                        </div>
                        @endif
                    </td>
                    <td class="amount-right">
                        @if($t->status === 'verified')
                            <span class="pill-verified">&#10003; Terverifikasi</span>
                        @else
                            <span class="pill-other">{{ ucfirst($t->status) }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="amount-strip"></div>
    </div>
    @endif

    {{-- ── Section Divider ── --}}
    <div class="sect-divider">
        <span class="sect-pill">Detail Pembayaran Zakat</span>
    </div>

    {{-- ── Detail Pembayaran ── --}}
    <div class="card" style="margin-bottom:12px;">
        <table class="detail-table">
            <tr>
                <td class="dt-k">Untuk Pembayaran</td>
                <td class="dt-s">:</td>
                <td class="dt-v dt-v-acc">
                    {{ optional($t->jenisZakat)->nama ?? '-' }}
                    @if($t->tipeZakat) &ndash; {{ $t->tipeZakat->nama }} @endif
                    @if($t->programZakat)
                        <br><span style="font-size:9.5px; font-weight:400; color:#6b7280;">
                            Program: {{ $t->programZakat->nama_program }}
                        </span>
                    @endif
                </td>
            </tr>

            {{-- Terbilang --}}
            @if($t->jumlah > 0 && function_exists('terbilang'))
            <tr>
                <td class="dt-k">Terbilang</td>
                <td class="dt-s">:</td>
                <td class="dt-v" style="font-style:italic; color:#374151;">
                    {{ ucfirst(terbilang($t->jumlah)) }} Rupiah
                </td>
            </tr>
            @elseif(isset($t->isBayarBeras) && $t->isBayarBeras)
            <tr>
                <td class="dt-k">Terbilang</td>
                <td class="dt-s">:</td>
                <td class="dt-v" style="font-style:italic;">
                    {{ number_format($t->jumlah_beras_kg, 2, ',', '.') }} kilogram beras
                </td>
            </tr>
            @endif

            {{-- Metode Pembayaran --}}
            <tr>
                <td class="dt-k">Metode Pembayaran</td>
                <td class="dt-s">:</td>
                <td class="dt-v">
                    @php
                        $metodeLabel = match($t->metode_pembayaran) {
                            'tunai'        => 'Tunai (Cash)',
                            'transfer'     => 'Transfer Bank',
                            'qris'         => 'QRIS',
                            'beras'        => 'Beras',
                            'bahan_mentah' => 'Bahan Mentah',
                            'makanan_matang' => 'Makanan Matang/Siap Santap',
                            default        => ucfirst($t->metode_pembayaran ?? '-'),
                        };
                    @endphp
                    {{ $metodeLabel }}
                    &nbsp;
                    @if($t->status === 'verified')
                        <span class="bdg bdg-g">Terverifikasi</span>
                    @elseif($t->status === 'pending')
                        <span class="bdg bdg-y">Menunggu</span>
                    @endif
                </td>
            </tr>

            {{-- Metode Penerimaan --}}
            <tr>
                <td class="dt-k">Metode Penerimaan</td>
                <td class="dt-s">:</td>
                <td class="dt-v">
                    {{ match($t->metode_penerimaan) {
                        'dijemput' => 'Dijemput Amil',
                        'daring'   => 'Daring / Online',
                        default    => 'Datang Langsung',
                    } }}
                </td>
            </tr>

            {{-- No Referensi --}}
            @if($t->no_referensi_transfer)
            <tr>
                <td class="dt-k">No. Referensi</td>
                <td class="dt-s">:</td>
                <td class="dt-v" style="font-family:monospace;">{{ $t->no_referensi_transfer }}</td>
            </tr>
            @endif

            {{-- Status Konfirmasi Transfer --}}
            @if(in_array($t->metode_pembayaran, ['transfer','qris']) && $t->konfirmasi_status)
            <tr>
                <td class="dt-k">Status Transfer</td>
                <td class="dt-s">:</td>
                <td class="dt-v">
                    @if($t->konfirmasi_status === 'dikonfirmasi')
                        <span class="bdg bdg-b">Dikonfirmasi Amil</span>
                    @elseif($t->konfirmasi_status === 'menunggu_konfirmasi')
                        <span class="bdg bdg-y">Menunggu Konfirmasi</span>
                    @else
                        {{ ucfirst($t->konfirmasi_status) }}
                    @endif
                </td>
            </tr>
            @endif

            {{-- Detail Fitrah (Uang) --}}
            @if(isset($t->isZakatFitrah) && $t->isZakatFitrah && !(isset($t->isBayarBeras) && $t->isBayarBeras))
                @if($t->jumlah_jiwa)
                <tr>
                    <td class="dt-k">Jumlah Jiwa</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->jumlah_jiwa }} jiwa</td>
                </tr>
                @endif
                @if($t->nominal_per_jiwa)
                <tr>
                    <td class="dt-k">Nominal / Jiwa</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">Rp {{ number_format($t->nominal_per_jiwa, 0, ',', '.') }}</td>
                </tr>
                @endif
            @endif

            {{-- Detail Beras --}}
            @if(isset($t->isBayarBeras) && $t->isBayarBeras)
                @if($t->jumlah_beras_kg)
                <tr>
                    <td class="dt-k">Jumlah Beras</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->jumlah_beras_kg }} kg</td>
                </tr>
                @endif
                @if($t->jumlah_jiwa)
                <tr>
                    <td class="dt-k">Jumlah Jiwa</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->jumlah_jiwa }} jiwa</td>
                </tr>
                @endif
                @if($t->harga_beras_per_kg !== null && $t->harga_beras_per_kg > 0)
                <tr>
                    <td class="dt-k">Harga / kg</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">Rp {{ number_format($t->harga_beras_per_kg, 0, ',', '.') }}</td>
                </tr>
                @endif
            @endif

            {{-- Detail Zakat Mal --}}
            @if(isset($t->isZakatMal) && $t->isZakatMal)
                @if($t->nilai_harta)
                <tr>
                    <td class="dt-k">Nilai Harta</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">Rp {{ number_format($t->nilai_harta, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($t->nisab_saat_ini)
                <tr>
                    <td class="dt-k">Nisab Saat Ini</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">Rp {{ number_format($t->nisab_saat_ini, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td class="dt-k">Sudah Haul</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->sudah_haul ? 'Sudah' : 'Belum' }}</td>
                </tr>
            @endif

            {{-- Detail Fidyah --}}
            @if(isset($t->isFidyah) && $t->isFidyah)
                <tr>
                    <td class="dt-k">Jumlah Hari</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->fidyah_jumlah_hari }} hari</td>
                </tr>
                <tr>
                    <td class="dt-k">Tipe Fidyah</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->fidyah_tipe_label ?? ucfirst($t->fidyah_tipe) }}</td>
                </tr>
                @if($t->fidyah_tipe === 'mentah' && $t->fidyah_nama_bahan)
                <tr>
                    <td class="dt-k">Jenis Bahan</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->fidyah_nama_bahan }}</td>
                </tr>
                @endif
                @if($t->fidyah_tipe === 'matang' && $t->fidyah_cara_serah)
                <tr>
                    <td class="dt-k">Cara Serah</td>
                    <td class="dt-s">:</td>
                    <td class="dt-v">{{ $t->fidyah_cara_serah_label ?? ucfirst($t->fidyah_cara_serah) }}</td>
                </tr>
                @endif
            @endif

            {{-- Keterangan --}}
            @if($t->keterangan)
            <tr>
                <td class="dt-k">Keterangan</td>
                <td class="dt-s">:</td>
                <td class="dt-v" style="font-style:italic; color:#6b7280;">{{ $t->keterangan }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- ── Tanda Tangan ── --}}
    <div class="card">
        <div class="card-header"><span>Pengesahan &amp; Tanda Tangan</span></div>
        <div class="sig-body">
            <div class="sig-date">{{ $t->tanggal_transaksi->format('d F Y') }}</div>
            <div class="sig-role">Tanda Tangan Penerimaan</div>
            <div class="sig-space">
                @if($ttdBase64)
                    <img src="{{ $ttdBase64 }}" class="sig-img" alt="Tanda Tangan">
                @endif
            </div>
            <div class="sig-line"></div>
            <div class="sig-name">
                {{ optional($t->amil)->nama_lengkap
                    ?? optional(optional($t->amil)->pengguna)->name
                    ?? '_____________________' }}
            </div>
            <div class="sig-sub">Amil / Petugas Penerimaan Zakat</div>
        </div>
    </div>

</div>

{{-- ══ FOOTER ══ --}}
<div class="footer-outer">
    <div class="footer-accent"></div>
    <div class="footer-inner">
        <div class="footer-main">
            Terima kasih telah menunaikan zakat melalui {{ $t->lembaga->nama ?? $appName }}
        </div>
        <div class="footer-sub">
            Kwitansi ini dicetak secara otomatis oleh sistem &bull; {{ $appName }} &bull;
            Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
        </div>
    </div>
</div>

</body>
</html>