{{-- resources/views/amil/transaksi-penerimaan/print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi - {{ $transaksi->no_transaksi }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0fdf4;
            min-height: 100vh;
            padding: 32px 20px 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Action Bar ── */
        .action-bar {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 999;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 999px;
            padding: 7px 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.18s ease;
            font-family: 'Inter', sans-serif;
        }
        .btn-back { background: #f3f4f6; color: #374151; }
        .btn-back:hover { background: #e5e7eb; }
        .btn-download {
            background: #17a34a;
            color: #fff;
            box-shadow: 0 4px 14px rgba(23,163,74,0.35);
        }
        .btn-download:hover { background: #15803d; transform: translateY(-1px); }
        .btn-download:disabled { background: #9ca3af; box-shadow: none; cursor: not-allowed; transform: none; }
        .spinner {
            display: none;
            width: 13px; height: 13px;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Kwitansi ── */
        .kwitansi {
            width: 100%;
            max-width: 720px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 16px 48px rgba(0,0,0,0.1);
        }

        /* ── Header ── */
        .header {
            background: #17a34a;
            padding: 22px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: #22c55e;
        }
        .header::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .header-left { display: flex; align-items: center; gap: 14px; z-index: 1; }

        .header-logo-img {
            width: 52px; height: 52px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.35);
            background: #fff;
            flex-shrink: 0;
        }
        .header-logo-fallback {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .header-logo-fallback svg { width: 26px; height: 26px; color: #fff; }

        .header-lembaga h1 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 3px;
        }
        .header-lembaga p {
            font-size: 11px;
            color: rgba(255,255,255,0.80);
            line-height: 1.5;
        }
        .header-badge {
            z-index: 1;
            background: rgba(255,255,255,0.18);
            border: 1.5px solid rgba(255,255,255,0.40);
            border-radius: 8px;
            padding: 9px 14px;
            text-align: right;
            flex-shrink: 0;
        }
        .header-badge .badge-label {
            font-size: 7.5px;
            color: rgba(255,255,255,0.75);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
        }
        .header-badge .badge-no {
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.3px;
        }

        /* ── Title Strip ── */
        .title-strip {
            background: #f0fdf4;
            border-top: 3px solid #22c55e;
            border-bottom: 1px solid #bbf7d0;
            padding: 10px 32px;
            text-align: center;
        }
        .title-strip h2 {
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-weight: bold;
            color: #15803d;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        /* ── Content ── */
        .content { padding: 14px 28px; }

        /* ── Info Grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1.5px solid #bbf7d0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .info-grid-full { grid-column: 1 / -1; border-top: 1px solid #bbf7d0; }
        .info-cell {
            padding: 7px 12px;
            border-right: 1px solid #bbf7d0;
            background: #fff;
        }
        .info-cell:nth-child(even), .info-cell:last-child { border-right: none; }
        .cell-label {
            font-size: 8.5px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 2px;
        }
        .cell-value { font-size: 13px; font-weight: 500; color: #111827; }
        .cell-value.bold { font-weight: 700; color: #15803d; }

        /* ── Amount Box ── */
        .amount-box {
            background: linear-gradient(135deg, #17a34a 0%, #15803d 100%);
            border-radius: 8px;
            padding: 16px 22px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            overflow: hidden;
            border: 1.5px solid #22c55e;
        }
        .amount-box::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 4px;
            background: #22c55e;
        }
        .amount-box::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 130px; height: 130px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .amount-left .label {
            font-size: 8.5px; font-weight: 700;
            color: rgba(255,255,255,0.70);
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 5px;
        }
        .amount-left .value {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 700;
            color: #fff; letter-spacing: -0.5px; line-height: 1;
        }
        .amount-left .terbilang {
            font-size: 9px; color: rgba(255,255,255,0.65);
            margin-top: 5px; font-style: italic;
        }
        .amount-right { flex-shrink: 0; z-index: 1; }
        .status-pill {
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 9.5px; font-weight: 700;
            color: rgba(255,255,255,0.75); text-transform: uppercase;
        }
        .status-pill.verified {
            background: rgba(255,255,255,0.20);
            border: 1.5px solid rgba(255,255,255,0.50);
            color: #ffffff;
        }

        /* ── Section Header ── */
        .section-header {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 8px; margin-top: 2px;
        }
        .section-header .line { flex: 1; height: 1px; background: #bbf7d0; }
        .section-header span {
            font-size: 8px; font-weight: 700; color: #17a34a;
            text-transform: uppercase; letter-spacing: 2px; white-space: nowrap;
            padding: 4px 18px;
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 999px;
        }

        /* ── Detail Table ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1.5px solid #bbf7d0;
            border-radius: 8px;
            overflow: hidden;
        }
        .detail-table tr:not(:last-child) td { border-bottom: 1px solid #f0fdf4; }
        .detail-table tr:nth-child(odd) td  { background: #ffffff; }
        .detail-table tr:nth-child(even) td { background: #fafffe; }
        .detail-table td { padding: 8px 14px; font-size: 12px; vertical-align: top; }
        .detail-table td:first-child {
            width: 34%; font-weight: 700; color: #6b7280;
            font-size: 8.5px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .detail-table td:nth-child(2) { width: 4%; color: #d1d5db; text-align: center; font-size: 11px; }
        .detail-table td:last-child { color: #1f2937; font-weight: 500; font-size: 11px; }
        .detail-table td:last-child.accent { font-weight: 700; color: #15803d; }

        /* ── Badge ── */
        .badge {
            display: inline-block; padding: 2px 10px;
            border-radius: 999px; font-size: 8.5px; font-weight: 700;
            letter-spacing: 0.5px; text-transform: uppercase;
        }
        .badge-verified { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .badge-pending  { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
        .badge-confirmed { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

        /* ── Tanda Tangan ── */
        .signature-section {
            margin-top: 6px;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            overflow: hidden;
        }
        .signature-section-header {
            background: linear-gradient(90deg, #17a34a 0%, #22c55e 100%);
            padding: 6px 14px;
            font-size: 8px; font-weight: 700; color: #ffffff;
            text-transform: uppercase; letter-spacing: 2px;
        }
        .signature-amil-wrap {
            display: flex;
            justify-content: center;
            padding: 16px 16px 14px;
        }
        .sig-cell {
            text-align: center;
            min-width: 200px;
            max-width: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sig-date { font-size: 9.5px; color: #9ca3af; margin-bottom: 2px; }
        .sig-role { font-size: 10.5px; font-weight: 700; color: #17a34a; margin-bottom: 14px; }
        .sig-space {
            width: 100%; min-height: 55px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 6px;
        }
        .sig-space img { max-width: 115px; max-height: 55px; object-fit: contain; }
        .sig-line { width: 190px; border-top: 1.5px solid #374151; margin: 0 auto 6px; }
        .sig-name { font-size: 12px; font-weight: 700; color: #111827; }
        .sig-sub { font-size: 9px; color: #9ca3af; margin-top: 2px; }

        /* ── Footer ── */
        .footer {
            margin-top: 16px;
            background: #17a34a;
        }
        .footer-accent { height: 3px; background: #22c55e; }
        .footer-inner {
            padding: 10px 30px;
            text-align: center;
        }
        .footer-inner p { font-size: 11px; color: rgba(255,255,255,0.60); line-height: 1.7; }
        .footer-inner p:first-child { font-size: 10px; font-weight: 700; color: rgba(255,255,255,0.90); margin-bottom: 3px; }

        /* ── Print ── */
        @media print {
            body { background: #fff; padding: 0; }
            .kwitansi { box-shadow: none; border-radius: 0; max-width: 100%; }
            .no-print, .action-bar { display: none !important; }
            .header, .amount-box, .footer {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            @page { size: A4 portrait; margin: 8mm; }
        }
    </style>
</head>
<body>

    {{-- Action Bar --}}
    <div class="action-bar no-print">
        <a href="{{ route('transaksi-datang-langsung.show', $transaksi->uuid) }}" class="btn btn-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <button onclick="downloadPdf()" id="btn-download" class="btn btn-download">
            <span class="spinner" id="spinner"></span>
            <svg id="icon-download" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span id="btn-text">Unduh PDF</span>
        </button>
    </div>

    @php
        $config = \App\Models\KonfigurasiAplikasi::first();

        $logoBase64 = null;
        $logoPath = base_path('public/images/logo.png');
        if (file_exists($logoPath)) {
            $logoMime   = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="kwitansi" id="kwitansi-content">

        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}"
                         alt="{{ optional($config)->nama_aplikasi ?? 'Logo' }}"
                         class="header-logo-img">
                @else
                    <div class="header-logo-fallback">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
                <div class="header-lembaga">
                    <h1>{{ $transaksi->lembaga->nama ?? optional($config)->nama_aplikasi ?? 'Lembaga' }}</h1>
                    <p>{{ $transaksi->lembaga->alamat_lengkap ?? $transaksi->lembaga->alamat ?? '-' }}</p>
                    @if($transaksi->lembaga->telepon || $transaksi->lembaga->email)
                    <p>
                        @if($transaksi->lembaga->telepon) Telp: {{ $transaksi->lembaga->telepon }} @endif
                        @if($transaksi->lembaga->telepon && $transaksi->lembaga->email) &nbsp;&bull;&nbsp; @endif
                        @if($transaksi->lembaga->email) {{ $transaksi->lembaga->email }} @endif
                    </p>
                    @endif
                </div>
            </div>
            <div class="header-badge">
                <div class="badge-label">No. Kwitansi</div>
                <div class="badge-no">{{ $transaksi->no_kwitansi ?? $transaksi->no_transaksi }}</div>
            </div>
        </div>

        {{-- Title Strip --}}
        <div class="title-strip">
            <h2>Kwitansi Penerimaan Zakat</h2>
        </div>

        <div class="content">

            {{-- Info Grid --}}
            <div class="info-grid" style="margin-top:14px;">
                <div class="info-cell">
                    <div class="cell-label">No. Transaksi</div>
                    <div class="cell-value" style="font-size:12px;font-weight:600;">{{ $transaksi->no_transaksi }}</div>
                </div>
                <div class="info-cell" style="border-right:none;">
                    <div class="cell-label">Tanggal</div>
                    <div class="cell-value">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</div>
                </div>
                <div class="info-cell info-grid-full">
                    <div class="cell-label">Terima Dari</div>
                    <div class="cell-value bold" style="font-size:14px;">{{ $transaksi->muzakki_nama }}</div>
                </div>
                @if($transaksi->muzakki_nik)
                <div class="info-cell info-grid-full">
                    <div class="cell-label">NIK</div>
                    <div class="cell-value" style="font-family:monospace;">{{ $transaksi->muzakki_nik }}</div>
                </div>
                @endif
                @if($transaksi->muzakki_alamat)
                <div class="info-cell info-grid-full">
                    <div class="cell-label">Alamat</div>
                    <div class="cell-value">{{ $transaksi->muzakki_alamat }}</div>
                </div>
                @endif
            </div>

            {{-- Amount Box --}}
            @if($transaksi->jumlah > 0)
            <div class="amount-box">
                <div class="amount-left">
                    <div class="label">Jumlah Diterima</div>
                    <div class="value">{{ $transaksi->jumlah_formatted }}</div>
                    @if(function_exists('terbilang'))
                    <div class="terbilang">&#x2605; {{ ucfirst(terbilang($transaksi->jumlah)) }} Rupiah</div>
                    @endif
                </div>
                <div class="amount-right">
                    @if($transaksi->status === 'verified')
                        <span class="status-pill verified">&#10003; Terverifikasi</span>
                    @elseif($transaksi->status === 'pending')
                        <span class="status-pill">Menunggu Verifikasi</span>
                    @else
                        <span class="status-pill">{{ ucfirst($transaksi->status) }}</span>
                    @endif
                </div>
            </div>
            @elseif(isset($transaksi->isBayarBeras) && $transaksi->isBayarBeras && $transaksi->jumlah_beras_kg > 0)
            <div class="amount-box">
                <div class="amount-left">
                    <div class="label">Zakat Fitrah — Beras</div>
                    <div class="value" style="font-size:24px;">{{ number_format($transaksi->jumlah_beras_kg, 2, ',', '.') }} kg</div>
                    @if($transaksi->jumlah_jiwa)
                    <div class="terbilang">
                        {{ $transaksi->jumlah_jiwa }} jiwa
                        @if($transaksi->harga_beras_per_kg) &bull; Setara &plusmn; Rp {{ number_format($transaksi->jumlah_beras_kg * $transaksi->harga_beras_per_kg, 0, ',', '.') }} @endif
                    </div>
                    @endif
                </div>
                <div class="amount-right">
                    @if($transaksi->status === 'verified')
                        <span class="status-pill verified">&#10003; Terverifikasi</span>
                    @else
                        <span class="status-pill">{{ ucfirst($transaksi->status) }}</span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Detail Zakat --}}
            <div class="section-header">
                <div class="line"></div>
                <span>Detail Pembayaran Zakat</span>
                <div class="line"></div>
            </div>

            <table class="detail-table">
                <tr>
                    <td>Untuk Pembayaran</td>
                    <td>:</td>
                    <td class="accent">
                        {{ optional($transaksi->jenisZakat)->nama ?? '-' }}
                        @if($transaksi->tipeZakat) &ndash; {{ $transaksi->tipeZakat->nama }} @endif
                        @if($transaksi->programZakat)
                            <br><span style="font-size:9.5px;font-weight:400;color:#6b7280;">Program: {{ $transaksi->programZakat->nama_program }}</span>
                        @endif
                    </td>
                </tr>
                @if($transaksi->jumlah > 0 && function_exists('terbilang'))
                <tr>
                    <td>Terbilang</td>
                    <td>:</td>
                    <td style="font-style:italic; color:#374151;">{{ ucfirst(terbilang($transaksi->jumlah)) }} Rupiah</td>
                </tr>
                @elseif(isset($transaksi->isBayarBeras) && $transaksi->isBayarBeras)
                <tr>
                    <td>Terbilang</td>
                    <td>:</td>
                    <td style="font-style:italic;">{{ number_format($transaksi->jumlah_beras_kg, 2, ',', '.') }} kilogram beras</td>
                </tr>
                @endif
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>:</td>
                    <td>
                        @php
                            $metodeLabel = match($transaksi->metode_pembayaran) {
                                'tunai'          => 'Tunai (Cash)',
                                'transfer'       => 'Transfer Bank',
                                'qris'           => 'QRIS',
                                'beras'          => 'Beras',
                                'bahan_mentah'   => 'Bahan Mentah',
                                'makanan_matang' => 'Makanan Matang/Siap Santap',
                                default          => ucfirst($transaksi->metode_pembayaran ?? '-'),
                            };
                        @endphp
                        {{ $metodeLabel }}
                        &nbsp;
                        @if($transaksi->status === 'verified')
                            <span class="badge badge-verified">Terverifikasi</span>
                        @elseif($transaksi->status === 'pending')
                            <span class="badge badge-pending">Menunggu</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Metode Penerimaan</td>
                    <td>:</td>
                    <td>{{ match($transaksi->metode_penerimaan) {
                        'dijemput' => 'Dijemput Amil',
                        'daring'   => 'Daring / Online',
                        default    => 'Datang Langsung',
                    } }}</td>
                </tr>
                @if($transaksi->no_referensi_transfer)
                <tr>
                    <td>No. Referensi</td>
                    <td>:</td>
                    <td style="font-family:monospace;">{{ $transaksi->no_referensi_transfer }}</td>
                </tr>
                @endif
                @if(in_array($transaksi->metode_pembayaran, ['transfer','qris']) && $transaksi->konfirmasi_status)
                <tr>
                    <td>Status Transfer</td>
                    <td>:</td>
                    <td>
                        @if($transaksi->konfirmasi_status === 'dikonfirmasi')
                            <span class="badge badge-confirmed">Dikonfirmasi Amil</span>
                        @elseif($transaksi->konfirmasi_status === 'menunggu_konfirmasi')
                            <span class="badge badge-pending">Menunggu Konfirmasi</span>
                        @else
                            {{ ucfirst($transaksi->konfirmasi_status) }}
                        @endif
                    </td>
                </tr>
                @endif
                @if(isset($transaksi->isZakatFitrah) && $transaksi->isZakatFitrah && !(isset($transaksi->isBayarBeras) && $transaksi->isBayarBeras))
                    @if($transaksi->jumlah_jiwa)
                    <tr><td>Jumlah Jiwa</td><td>:</td><td>{{ $transaksi->jumlah_jiwa }} jiwa</td></tr>
                    @endif
                    @if($transaksi->nominal_per_jiwa)
                    <tr><td>Nominal / Jiwa</td><td>:</td><td>Rp {{ number_format($transaksi->nominal_per_jiwa, 0, ',', '.') }}</td></tr>
                    @endif
                @endif
                @if(isset($transaksi->isBayarBeras) && $transaksi->isBayarBeras)
                    @if($transaksi->jumlah_beras_kg)
                    <tr><td>Jumlah Beras</td><td>:</td><td>{{ $transaksi->jumlah_beras_kg }} kg</td></tr>
                    @endif
                    @if($transaksi->jumlah_jiwa)
                    <tr><td>Jumlah Jiwa</td><td>:</td><td>{{ $transaksi->jumlah_jiwa }} jiwa</td></tr>
                    @endif
                    @if($transaksi->harga_beras_per_kg !== null && $transaksi->harga_beras_per_kg > 0)
                    <tr><td>Harga / kg</td><td>:</td><td>Rp {{ number_format($transaksi->harga_beras_per_kg, 0, ',', '.') }}</td></tr>
                    @endif
                @endif
                @if(isset($transaksi->isZakatMal) && $transaksi->isZakatMal)
                    @if($transaksi->nilai_harta)
                    <tr><td>Nilai Harta</td><td>:</td><td>Rp {{ number_format($transaksi->nilai_harta, 0, ',', '.') }}</td></tr>
                    @endif
                    @if($transaksi->nisab_saat_ini)
                    <tr><td>Nisab Saat Ini</td><td>:</td><td>Rp {{ number_format($transaksi->nisab_saat_ini, 0, ',', '.') }}</td></tr>
                    @endif
                    <tr><td>Sudah Haul</td><td>:</td><td>{{ $transaksi->sudah_haul ? 'Sudah' : 'Belum' }}</td></tr>
                @endif
                @if($transaksi->keterangan)
                <tr><td>Keterangan</td><td>:</td><td style="font-style:italic; color:#6b7280;">{{ $transaksi->keterangan }}</td></tr>
                @endif
            </table>

            {{-- Tanda Tangan --}}
            <div class="signature-section">
                <div class="signature-section-header">Pengesahan &amp; Tanda Tangan</div>
                <div class="signature-amil-wrap">
                    <div class="sig-cell">
                        <div class="sig-date">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</div>
                        <div class="sig-role">Tanda Tangan Penerimaan</div>
                        <div class="sig-space">
                            @if($transaksi->amil && $transaksi->amil->tanda_tangan_url)
                                <img src="{{ $transaksi->amil->tanda_tangan_url }}" alt="TTD Amil">
                            @endif
                        </div>
                        <div class="sig-line"></div>
                        <div class="sig-name">
                            {{ optional($transaksi->amil)->nama_lengkap
                                ?? optional(optional($transaksi->amil)->pengguna)->name
                                ?? '_____________________' }}
                        </div>
                        <div class="sig-sub">Amil / Petugas Penerimaan Zakat</div>
                    </div>
                </div>
            </div>

        </div>{{-- end .content --}}

        {{-- Footer --}}
        <div class="footer">
            <div class="footer-accent"></div>
            <div class="footer-inner">
                <p>Terima kasih telah menunaikan zakat melalui {{ $transaksi->lembaga->nama ?? optional($config)->nama_aplikasi ?? 'Lembaga' }}</p>
                <p>Kwitansi ini dicetak secara otomatis oleh sistem &bull; {{ optional($config)->nama_aplikasi ?? 'Niat Zakat' }} &bull; Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
            </div>
        </div>

    </div>{{-- end .kwitansi --}}

<script>
    async function downloadPdf() {
        const btn     = document.getElementById('btn-download');
        const spinner = document.getElementById('spinner');
        const icon    = document.getElementById('icon-download');
        const text    = document.getElementById('btn-text');

        btn.disabled          = true;
        spinner.style.display = 'block';
        icon.style.display    = 'none';
        text.textContent      = 'Memproses...';

        const actionBar = document.querySelector('.action-bar');
        actionBar.style.visibility = 'hidden';
        actionBar.style.opacity    = '0';

        await new Promise(r => setTimeout(r, 100));

        try {
            const element = document.getElementById('kwitansi-content');

            const canvas = await html2canvas(element, {
                scale          : 2,
                useCORS        : true,
                allowTaint     : true,
                logging        : false,
                backgroundColor: '#ffffff',
                imageTimeout   : 15000,
                width          : element.scrollWidth,
                height         : element.scrollHeight,
                windowWidth    : element.scrollWidth,
                windowHeight   : element.scrollHeight,
                x              : 0,
                y              : 0,
                scrollX        : -window.scrollX,
                scrollY        : -window.scrollY,
                onclone: (doc) => {
                    const bar = doc.querySelector('.action-bar');
                    if (bar) bar.remove();

                    doc.body.style.cssText = [
                        'margin:0',
                        'padding:0',
                        'background:#fff',
                        'display:block',
                        'min-height:unset',
                        'height:auto',
                        'overflow:visible',
                        'align-items:unset',
                        'justify-content:unset',
                    ].join(';');

                    const kw = doc.getElementById('kwitansi-content');
                    if (kw) {
                        kw.style.cssText = [
                            'border-radius:0',
                            'box-shadow:none',
                            'max-width:720px',
                            'width:720px',
                            'margin:0',
                            'padding:0',
                            'position:static',
                            'left:0',
                            'top:0',
                        ].join(';');
                    }
                }
            });

            const { jsPDF } = window.jspdf;

            const PDF_W  = 210;
            const PDF_H  = 297;
            const MARGIN = 8;

            const usableW = PDF_W - MARGIN * 2;
            const usableH = PDF_H - MARGIN * 2;

            let imgW = usableW;
            let imgH = (canvas.height / canvas.width) * imgW;

            if (imgH > usableH) {
                imgH = usableH;
                imgW = (canvas.width / canvas.height) * imgH;
            }

            const offsetX = MARGIN + (usableW - imgW) / 2;
            const offsetY = MARGIN;

            const pdf = new jsPDF({
                orientation: 'portrait',
                unit       : 'mm',
                format     : 'a4',
                compress   : true
            });

            pdf.addImage(
                canvas.toDataURL('image/jpeg', 0.97),
                'JPEG',
                offsetX, offsetY,
                imgW, imgH
            );

            pdf.save('kwitansi-{{ $transaksi->no_transaksi }}.pdf');

        } catch (err) {
            console.error(err);
            alert('Gagal mengunduh PDF: ' + err.message);
        } finally {
            actionBar.style.visibility = '';
            actionBar.style.opacity    = '';
            btn.disabled            = false;
            spinner.style.display   = 'none';
            icon.style.display      = 'block';
            text.textContent        = 'Unduh PDF';
        }
    }
</script>
</body>
</html>