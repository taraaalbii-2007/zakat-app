{{-- resources/views/amil/transaksi-penerimaan/print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi - {{ $transaksi->no_transaksi }}</title>

    {{-- jsPDF + html2canvas untuk download PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Source Sans 3', sans-serif;
            background: #e8ede9;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 30px 20px 100px;
        }

        /* ── Wrapper ── */
        .kwitansi {
            max-width: 720px;
            width: 100%;
            background: white;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07), 0 20px 50px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* ── Header ── */
        .header {
            background: #1a3a2a;
            color: white;
            padding: 20px 32px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -60px; right: 60px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .header-icon {
            flex-shrink: 0;
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-icon svg { width: 30px; height: 30px; }
        .header-text h1 {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: 0.2px;
        }
        .header-text p {
            font-size: 12.5px;
            opacity: 0.72;
            line-height: 1.55;
        }

        /* ── Content ── */
        .content { padding: 20px 32px; }

        /* ── Title ── */
        .title {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #d1d5db;
        }
        .title h2 {
            font-family: 'Lora', serif;
            font-size: 17px;
            font-weight: 700;
            color: #1a3a2a;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .no-kwitansi {
            display: inline-block;
            font-size: 13.5px;
            font-weight: 700;
            color: #1a3a2a;
            background: #f0f7f2;
            border: 1.5px solid #a7c4b0;
            padding: 5px 22px;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }

        /* ── Info rows ── */
        .info-section { margin-bottom: 12px; }
        .info-row {
            display: flex;
            align-items: flex-start;
            padding: 5px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            width: 165px;
            flex-shrink: 0;
            font-size: 12.5px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-sep {
            width: 16px;
            flex-shrink: 0;
            font-size: 12.5px;
            color: #9ca3af;
        }
        .info-value {
            flex: 1;
            font-size: 13.5px;
            color: #111827;
            font-weight: 500;
        }

        /* ── Section divider ── */
        .section-label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        /* ── Amount box ── */
        .amount-box {
            background: #1a3a2a;
            border-radius: 6px;
            padding: 16px 24px;
            margin: 14px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .amount-box::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .amount-box .label {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
        .amount-box .value {
            font-family: 'Lora', serif;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .amount-box .terbilang {
            font-size: 11px;
            color: rgba(255,255,255,0.55);
            margin-top: 6px;
            font-style: italic;
        }

        /* ── Detail zakat box ── */
        .detail-zakat {
            background: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px 18px;
            margin-bottom: 12px;
        }

        /* ── Signature ── */
        .signature-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .signature-note {
            font-size: 11px;
            color: #9ca3af;
            max-width: 280px;
            line-height: 1.6;
        }
        .signature-box {
            text-align: center;
            min-width: 180px;
        }
        .signature-box .city-date {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 2px;
        }
        .signature-box .position {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
        }
        
        /* Container untuk tanda tangan */
        .signature-image-container {
            min-height: 70px;
            margin: 5px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .signature-image-container img {
            max-width: 150px;
            max-height: 60px;
            object-fit: contain;
            border: none;
        }
        
        .stamp-circle {
            width: 72px; height: 72px;
            border: 1.5px dashed #cbd5e1;
            border-radius: 50%;
            margin: 6px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stamp-circle span {
            font-size: 9px;
            color: #b0bbc7;
            text-align: center;
            line-height: 1.4;
        }
        .signature-box .name {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            margin-top: 4px;
            border-top: 1px solid #374151;
            padding-top: 4px;
            min-width: 140px;
            display: inline-block;
        }
        .signature-box .nik {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── Status badges ── */
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .status-verified { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
        .status-pending  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        /* ── Footer ── */
        .footer {
            background: #1a3a2a;
            padding: 12px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 11.5px;
            color: rgba(255,255,255,0.5);
            line-height: 1.9;
        }
        .footer p:first-child {
            color: rgba(255,255,255,0.75);
            font-weight: 600;
            font-size: 12px;
        }

        /* ── Action buttons (no-print) ── */
        .action-bar {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 999;
        }
        .btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-primary {
            background: #1a3a2a;
            color: white;
        }
        .btn-primary:hover {
            background: #0f2418;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,58,42,0.35);
        }
        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        .btn-secondary {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover {
            background: #f9fafb;
            transform: translateY(-1px);
        }
        .spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Print media ── */
        @media print {
            body { background: white; padding: 0; }
            .kwitansi { box-shadow: none; border-radius: 0; }
            .header, .footer, .amount-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print, .action-bar { display: none !important; }
            .signature-image-container img {
                max-width: 150px;
                max-height: 60px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

    {{-- Action Buttons --}}
    <div class="action-bar no-print">
        <a href="{{ route('transaksi-datang-langsung.show', $transaksi->uuid) }}" class="btn btn-secondary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <button onclick="downloadPdf()" id="btn-download" class="btn btn-primary">
            <span class="spinner" id="spinner"></span>
            <svg id="icon-download" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            <span id="btn-text">Unduh PDF</span>
        </button>
    </div>

    <div class="kwitansi" id="kwitansi-content">

        {{-- Header Masjid --}}
        <div class="header">
            <div class="header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="header-text">
                <h1>{{ $transaksi->masjid->nama ?? 'Masjid' }}</h1>
                <p>{{ $transaksi->masjid->alamat_lengkap ?? $transaksi->masjid->alamat ?? '-' }}</p>
                @if($transaksi->masjid->telepon || $transaksi->masjid->email)
                <p>
                    @if($transaksi->masjid->telepon) Telp: {{ $transaksi->masjid->telepon }} @endif
                    @if($transaksi->masjid->telepon && $transaksi->masjid->email) &nbsp;&bull;&nbsp; @endif
                    @if($transaksi->masjid->email) {{ $transaksi->masjid->email }} @endif
                </p>
                @endif
            </div>
        </div>

        <div class="content">

            {{-- Judul --}}
            <div class="title">
                <h2>Kwitansi Penerimaan Zakat</h2>
                <div class="no-kwitansi">
                    No. {{ $transaksi->no_kwitansi ?? $transaksi->no_transaksi }}
                </div>
            </div>

            {{-- Info Transaksi --}}
            <div class="info-section">
                <div class="section-label">Informasi Transaksi</div>
                <div class="info-row">
                    <span class="info-label">No. Transaksi</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->no_transaksi }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">
                        @if($transaksi->status === 'verified')
                            <span class="status-badge status-verified">Terverifikasi</span>
                        @elseif($transaksi->status === 'pending')
                            <span class="status-badge status-pending">Menunggu Verifikasi</span>
                        @else
                            <span class="status-badge">{{ ucfirst($transaksi->status) }}</span>
                        @endif
                    </span>
                </div>
            </div>

            {{-- Info Muzakki --}}
            <div class="info-section">
                <div class="section-label">Data Muzakki</div>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-sep">:</span>
                    <span class="info-value" style="font-weight:700; color:#1a3a2a;">{{ $transaksi->muzakki_nama }}</span>
                </div>
                @if($transaksi->muzakki_nik)
                <div class="info-row">
                    <span class="info-label">NIK</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->muzakki_nik }}</span>
                </div>
                @endif
                @if($transaksi->muzakki_telepon)
                <div class="info-row">
                    <span class="info-label">Telepon</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->muzakki_telepon }}</span>
                </div>
                @endif
                @if($transaksi->muzakki_alamat)
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->muzakki_alamat }}</span>
                </div>
                @endif
            </div>

            {{-- Jumlah --}}
            @if($transaksi->jumlah > 0)
            <div class="amount-box">
                <div class="label">Jumlah Diterima</div>
                <div class="value">{{ $transaksi->jumlah_formatted }}</div>
                @if(function_exists('terbilang'))
                <div class="terbilang">{{ ucfirst(terbilang($transaksi->jumlah)) }} Rupiah</div>
                @endif
            </div>
            @elseif($transaksi->isBayarBeras && $transaksi->jumlah_beras_kg > 0)
            {{-- Khusus beras: tidak ada nominal, tampilkan kg --}}
            <div class="amount-box">
                <div class="label">Zakat Fitrah Beras</div>
                <div class="value">{{ number_format($transaksi->jumlah_beras_kg, 2, ',', '.') }} kg</div>
                @if($transaksi->harga_beras_per_kg)
                <div class="terbilang">
                    Nilai setara ± Rp {{ number_format($transaksi->jumlah_beras_kg * $transaksi->harga_beras_per_kg, 0, ',', '.') }}
                </div>
                @endif
            </div>
            @endif

            {{-- Detail Zakat --}}
            <div class="detail-zakat">
                <div class="section-label" style="margin-bottom:12px;">Detail Zakat</div>

                @if($transaksi->jenisZakat)
                <div class="info-row">
                    <span class="info-label">Jenis Zakat</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->jenisZakat->nama }}</span>
                </div>
                @endif

                @if($transaksi->tipeZakat)
                <div class="info-row">
                    <span class="info-label">Tipe Zakat</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->tipeZakat->nama }}</span>
                </div>
                @endif

                @if($transaksi->programZakat)
                <div class="info-row">
                    <span class="info-label">Program</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->programZakat->nama_program }}</span>
                </div>
                @endif

                <div class="info-row">
                    <span class="info-label">Metode Penerimaan</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">
                        {{ $transaksi->metode_penerimaan === 'dijemput' ? 'Dijemput Amil' : 'Datang Langsung' }}
                    </span>
                </div>

                @if($transaksi->metode_pembayaran)
                <div class="info-row">
                    <span class="info-label">Metode Pembayaran</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ ucfirst($transaksi->metode_pembayaran) }}</span>
                </div>
                @endif

                {{-- no_referensi_transfer (bukan transaction_id / Midtrans) --}}
                @if($transaksi->no_referensi_transfer)
                <div class="info-row">
                    <span class="info-label">No. Referensi</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->no_referensi_transfer }}</span>
                </div>
                @endif

                {{-- Status Konfirmasi untuk transfer/qris --}}
                @if(in_array($transaksi->metode_pembayaran, ['transfer', 'qris']) && $transaksi->konfirmasi_status)
                <div class="info-row">
                    <span class="info-label">Status Transfer</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">
                        @if($transaksi->konfirmasi_status === 'dikonfirmasi')
                            <span class="status-badge status-verified">Dikonfirmasi Amil</span>
                        @elseif($transaksi->konfirmasi_status === 'menunggu_konfirmasi')
                            <span class="status-badge status-pending">Menunggu Konfirmasi</span>
                        @else
                            <span class="status-badge">{{ ucfirst($transaksi->konfirmasi_status) }}</span>
                        @endif
                    </span>
                </div>
                @endif

                {{-- Detail Fitrah: Uang --}}
                @if($transaksi->isZakatFitrah && !$transaksi->isBayarBeras)
                    @if($transaksi->jumlah_jiwa)
                    <div class="info-row">
                        <span class="info-label">Jumlah Jiwa</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">{{ $transaksi->jumlah_jiwa }} jiwa</span>
                    </div>
                    @endif
                    @if($transaksi->nominal_per_jiwa)
                    <div class="info-row">
                        <span class="info-label">Nominal / Jiwa</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">Rp {{ number_format($transaksi->nominal_per_jiwa, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endif

                {{-- Detail Fitrah: Beras --}}
                @if($transaksi->isBayarBeras)
                    @if($transaksi->jumlah_beras_kg)
                    <div class="info-row">
                        <span class="info-label">Jumlah Beras</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">{{ $transaksi->jumlah_beras_kg }} kg</span>
                    </div>
                    @endif
                    @if($transaksi->harga_beras_per_kg)
                    <div class="info-row">
                        <span class="info-label">Harga / kg</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">Rp {{ number_format($transaksi->harga_beras_per_kg, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endif

                {{-- Detail Mal --}}
                @if($transaksi->isZakatMal)
                    @if($transaksi->nilai_harta)
                    <div class="info-row">
                        <span class="info-label">Nilai Harta</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">Rp {{ number_format($transaksi->nilai_harta, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($transaksi->nisab_saat_ini)
                    <div class="info-row">
                        <span class="info-label">Nisab Saat Ini</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">Rp {{ number_format($transaksi->nisab_saat_ini, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Sudah Haul</span>
                        <span class="info-sep">:</span>
                        <span class="info-value">{{ $transaksi->sudah_haul ? 'Sudah' : 'Belum' }}</span>
                    </div>
                @endif
            </div>

            @if($transaksi->keterangan)
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Keterangan</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->keterangan }}</span>
                </div>
            </div>
            @endif

            {{-- Tanda Tangan --}}
            <div class="signature-area">
                <div class="signature-note">
                    Kwitansi ini merupakan bukti sah penerimaan zakat.<br>
                    Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
                </div>
                <div class="signature-box">
                    <div class="city-date">
                        {{ optional($transaksi->masjid)->kota_nama ?? optional($transaksi->masjid)->nama_kota ?? '' }},
                        {{ $transaksi->tanggal_transaksi->format('d F Y') }}
                    </div>
                    <div class="position">Amil Penerima,</div>
                    
                    {{-- Tanda Tangan Image (jika ada) --}}
                    <div class="signature-image-container">
                        @if($transaksi->amil && $transaksi->amil->tanda_tangan_url)
                            <img src="{{ $transaksi->amil->tanda_tangan_url }}" 
                                 alt="Tanda Tangan Amil"
                                 style="max-width: 150px; max-height: 60px; object-fit: contain;">
                        @else
                            <div class="stamp-circle">
                                <span>Stempel &<br>Tanda Tangan</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="name">
                        {{ optional($transaksi->amil)->nama_lengkap
                            ?? optional(optional($transaksi->amil)->pengguna)->name
                            ?? '_____________________' }}
                    </div>
                    @if($transaksi->amil && $transaksi->amil->kode_amil)
                    <div class="nik">Kode Amil: {{ $transaksi->amil->kode_amil }}</div>
                    @endif
                    @if($transaksi->amil && $transaksi->amil->nik)
                    <div class="nik">NIK: {{ $transaksi->amil->nik }}</div>
                    @endif
                </div>
            </div>

        </div>{{-- end .content --}}

        {{-- Footer --}}
        <div class="footer">
            <p>Terima kasih telah menunaikan zakat melalui {{ $transaksi->masjid->nama ?? 'Masjid' }}</p>
            <p>Semoga Allah SWT menerima dan melipatgandakan kebaikan Anda &bull; Dokumen ini dicetak secara digital</p>
        </div>

    </div>{{-- end .kwitansi --}}

    {{-- Script Download PDF — selalu 1 halaman A4 portrait --}}
    <script>
        async function downloadPdf() {
            const btn     = document.getElementById('btn-download');
            const spinner = document.getElementById('spinner');
            const icon    = document.getElementById('icon-download');
            const text    = document.getElementById('btn-text');

            btn.disabled             = true;
            spinner.style.display    = 'block';
            icon.style.display       = 'none';
            text.textContent         = 'Memproses...';

            try {
                const element = document.getElementById('kwitansi-content');

                // Capture resolusi tinggi
                const canvas = await html2canvas(element, {
                    scale          : 2,
                    useCORS        : true,
                    logging        : false,
                    backgroundColor: '#ffffff',
                    windowWidth    : element.scrollWidth,
                    windowHeight   : element.scrollHeight,
                    allowTaint      : false,
                    foreignObjectRendering: false
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);

                // A4 portrait dalam mm
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit       : 'mm',
                    format     : 'a4',
                    compress   : true,
                });

                const pageW  = pdf.internal.pageSize.getWidth();  // 210 mm
                const pageH  = pdf.internal.pageSize.getHeight(); // 297 mm
                const margin = 8;

                const maxW = pageW - margin * 2;  // 194 mm
                const maxH = pageH - margin * 2;  // 281 mm

                // Hitung dimensi gambar dengan mempertahankan aspek rasio
                let imgW = maxW;
                let imgH = (canvas.height * imgW) / canvas.width;

                // Jika masih terlalu tinggi → scale down supaya pas 1 halaman
                if (imgH > maxH) {
                    imgH = maxH;
                    imgW = (canvas.width * imgH) / canvas.height;
                }

                // Tengahkan secara horizontal
                const offsetX = margin + (maxW - imgW) / 2;

                // Selalu 1 halaman — addImage sekali saja
                pdf.addImage(imgData, 'JPEG', offsetX, margin, imgW, imgH);

                const noTransaksi = '{{ $transaksi->no_transaksi }}';
                pdf.save('kwitansi-' + noTransaksi + '.pdf');

            } catch (err) {
                console.error('Gagal generate PDF:', err);
                alert('Gagal mengunduh PDF. Silakan coba lagi. Error: ' + err.message);
            } finally {
                btn.disabled          = false;
                spinner.style.display = 'none';
                icon.style.display    = 'block';
                text.textContent      = 'Unduh PDF';
            }
        }
    </script>

</body>
</html>