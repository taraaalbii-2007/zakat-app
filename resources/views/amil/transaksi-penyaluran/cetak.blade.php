{{-- resources/views/amil/transaksi-penyaluran/cetak.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Penyaluran - {{ $transaksi->no_transaksi }}</title>

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
            background: #17a34a; /* hijau utama kwitansi penerimaan */
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
            position: absolute; top: -40px; right: -40px;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .header::after {
            content: '';
            position: absolute; bottom: -60px; right: 60px;
            width: 160px; height: 160px; border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .header-icon {
            flex-shrink: 0;
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .header-icon svg { width: 30px; height: 30px; }
        .header-text h1 {
            font-family: 'Lora', serif;
            font-size: 20px; font-weight: 700;
            margin-bottom: 4px; letter-spacing: 0.2px;
        }
        .header-text p { font-size: 12.5px; opacity: 0.72; line-height: 1.55; }

        /* ── Content ── */
        .content { padding: 20px 32px; }

        /* ── Title ── */
        .title {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #bbf7d0; /* hijau muda kwitansi penerimaan */
        }
        .title h2 {
            font-family: 'Lora', serif;
            font-size: 17px; font-weight: 700;
            color: #15803d; /* hijau tua kwitansi penerimaan */
            letter-spacing: 3px;
            text-transform: uppercase; margin-bottom: 12px;
        }
        .no-kwitansi {
            display: inline-block;
            font-size: 13.5px; font-weight: 700; color: #15803d;
            background: #f0fdf4; /* bg hijau lembut */
            border: 1.5px solid #86efac;
            padding: 5px 22px; border-radius: 3px; letter-spacing: 0.5px;
        }

        /* ── Info rows ── */
        .info-section { margin-bottom: 12px; }
        .info-row {
            display: flex; align-items: flex-start;
            padding: 5px 0; border-bottom: 1px solid #f0fdf4;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            width: 165px; flex-shrink: 0;
            font-size: 12.5px; font-weight: 600; color: #6b7280;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .info-sep { width: 16px; flex-shrink: 0; font-size: 12.5px; color: #9ca3af; }
        .info-value { flex: 1; font-size: 13.5px; color: #111827; font-weight: 500; }

        .section-label {
            font-size: 10px; font-weight: 700; color: #9ca3af;
            text-transform: uppercase; letter-spacing: 2px;
            margin-bottom: 10px; padding-bottom: 6px;
            border-bottom: 1px solid #bbf7d0; /* hijau muda */
        }

        /* ── Amount box ── */
        .amount-box {
            background: linear-gradient(135deg, #17a34a 0%, #15803d 100%); /* gradasi hijau kwitansi penerimaan */
            border-radius: 6px;
            padding: 16px 24px; margin: 14px 0; text-align: center;
            position: relative; overflow: hidden;
            border: 1.5px solid #22c55e;
        }
        .amount-box::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 120px; height: 120px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .amount-box .label {
            font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.6);
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 6px;
        }
        .amount-box .value {
            font-family: 'Lora', serif; font-size: 28px;
            font-weight: 700; color: #ffffff; letter-spacing: -0.5px;
        }
        .amount-box .terbilang {
            font-size: 11px; color: rgba(255,255,255,0.55);
            margin-top: 6px; font-style: italic;
        }

        .detail-penyaluran {
            background: #f0fdf4; /* bg hijau lembut kwitansi penerimaan */
            border: 1px solid #bbf7d0;
            border-radius: 6px; padding: 12px 18px; margin-bottom: 12px;
        }

        .status-badge {
            display: inline-block; padding: 2px 10px; border-radius: 3px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;
        }
        .status-disalurkan { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

        /* ── Signature area: 2 kolom ── */
        .signature-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #bbf7d0; /* hijau muda */
            gap: 16px;
        }

        .signature-box,
        .recipient-box {
            flex: 1;
            text-align: center;
            min-width: 0;
        }

        .sig-city-date {
            font-size: 12px; color: #4b5563; margin-bottom: 2px;
        }
        .sig-position {
            font-size: 11px; color: #6b7280; margin-bottom: 4px;
        }

        .sig-image-wrap {
            height: 90px;
            margin: 4px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .sig-image-wrap img.tt-mustahik {
            max-width: 180px;
            max-height: 85px;
            object-fit: contain;
            display: block;
        }

        .sig-image-wrap img.tt-amil {
            max-width: 180px;
            max-height: 85px;
            object-fit: contain;
            display: block;
        }

        .stamp-circle {
            width: 72px; height: 72px;
            border: 1.5px dashed #86efac; /* hijau muda */
            border-radius: 50%;
            margin: 0 auto;
            display: flex; align-items: center; justify-content: center;
        }
        .stamp-circle span { font-size: 9px; color: #17a34a; text-align: center; line-height: 1.4; }

        .sig-name {
            font-size: 12.5px; font-weight: 700; color: #111827;
            margin-top: 6px;
            border-top: 1px solid #374151;
            padding-top: 4px;
            display: inline-block;
            min-width: 160px;
        }
        .sig-sub {
            font-size: 10px; color: #6b7280; margin-top: 2px;
        }

        /* ── Footer ── */
        .footer {
            background: #17a34a; /* hijau utama kwitansi penerimaan */
            padding: 12px 32px; text-align: center;
        }
        .footer p { font-size: 11.5px; color: rgba(255,255,255,0.5); line-height: 1.9; }
        .footer p:first-child { color: rgba(255,255,255,0.75); font-weight: 600; font-size: 12px; }

        /* ── Action buttons ── */
        .action-bar {
            position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 12px; z-index: 999;
        }
        .btn {
            display: flex; align-items: center; gap: 8px;
            padding: 13px 24px; border-radius: 999px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            border: none; text-decoration: none; transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-primary { background: #17a34a; color: white; } /* hijau utama */
        .btn-primary:hover { background: #15803d; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(23,163,74,0.35); }
        .btn-primary:disabled { background: #9ca3af; cursor: not-allowed; transform: none; }
        .btn-secondary { background: white; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #f9fafb; transform: translateY(-1px); }
        .spinner {
            display: none; width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3); border-top-color: white;
            border-radius: 50%; animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Print ── */
        @media print {
            body { background: white; padding: 0; }
            .kwitansi { box-shadow: none; border-radius: 0; }
            .header, .footer, .amount-box {
                -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
            .no-print, .action-bar { display: none !important; }
            .sig-image-wrap img {
                max-width: 180px; max-height: 85px;
                -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

    {{-- Action Buttons --}}
    <div class="action-bar no-print">
        <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}" class="btn btn-secondary">
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

        {{-- Header Lembaga --}}
        <div class="header">
            <div class="header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="header-text">
                <h1>{{ $transaksi->lembaga->nama ?? 'Lembaga' }}</h1>
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

        <div class="content">

            {{-- Judul --}}
            <div class="title">
                <h2>Kwitansi Penyaluran Zakat</h2>
                <div class="no-kwitansi">No. {{ $transaksi->no_kwitansi ?? $transaksi->no_transaksi }}</div>
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
                    <span class="info-label">Tanggal Penyaluran</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->tanggal_penyaluran->translatedFormat('d F Y') }}</span>
                </div>
                @if($transaksi->waktu_penyaluran)
                <div class="info-row">
                    <span class="info-label">Waktu</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->waktu_penyaluran }}</span>
                </div>
                @endif
                @if($transaksi->periode)
                <div class="info-row">
                    <span class="info-label">Periode</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ \Carbon\Carbon::createFromFormat('Y-m', $transaksi->periode)->translatedFormat('F Y') }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">
                        <span class="status-badge status-disalurkan">Disalurkan</span>
                    </span>
                </div>
                @if($transaksi->disalurkan_at)
                <div class="info-row">
                    <span class="info-label">Tanggal Konfirmasi</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->disalurkan_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                @endif
            </div>

            {{-- Info Mustahik --}}
            <div class="info-section">
                <div class="section-label">Data Mustahik (Penerima)</div>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-sep">:</span>
                    <span class="info-value" style="font-weight:700; color:#15803d;">
                        {{ optional($transaksi->mustahik)->nama_lengkap ?? '-' }}
                    </span>
                </div>
                @if(optional($transaksi->mustahik)->nik)
                <div class="info-row">
                    <span class="info-label">NIK</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->mustahik->nik }}</span>
                </div>
                @endif
                @if(optional($transaksi->mustahik)->telepon)
                <div class="info-row">
                    <span class="info-label">Telepon</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->mustahik->telepon }}</span>
                </div>
                @endif
                @if(optional($transaksi->mustahik)->alamat)
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->mustahik->alamat }}</span>
                </div>
                @endif
                @if($transaksi->kategoriMustahik)
                <div class="info-row">
                    <span class="info-label">Kategori</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->kategoriMustahik->nama }}</span>
                </div>
                @endif
            </div>

            {{-- Jumlah --}}
            <div class="amount-box">
                @if($transaksi->metode_penyaluran === 'barang')
                    <div class="label">Penyaluran Berupa Barang</div>
                    <div class="value" style="font-size:20px;">{{ $transaksi->detail_barang ?? 'Barang' }}</div>
                    @if($transaksi->nilai_barang)
                    <div class="terbilang">Nilai Setara: Rp {{ number_format($transaksi->nilai_barang, 0, ',', '.') }}</div>
                    @endif
                @else
                    <div class="label">Jumlah Disalurkan</div>
                    <div class="value">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</div>
                @endif
            </div>

            {{-- Detail Penyaluran --}}
            <div class="detail-penyaluran">
                <div class="section-label" style="margin-bottom:12px;">Detail Penyaluran</div>
                @if($transaksi->jenisZakat)
                <div class="info-row">
                    <span class="info-label">Jenis Zakat</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->jenisZakat->nama }}</span>
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
                    <span class="info-label">Metode Penyaluran</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">
                        @if($transaksi->metode_penyaluran === 'tunai') Tunai
                        @elseif($transaksi->metode_penyaluran === 'transfer') Transfer
                        @elseif($transaksi->metode_penyaluran === 'barang') Barang
                        @else {{ ucfirst($transaksi->metode_penyaluran) }}
                        @endif
                    </span>
                </div>
                @if($transaksi->metode_penyaluran === 'barang' && $transaksi->detail_barang)
                <div class="info-row">
                    <span class="info-label">Detail Barang</span>
                    <span class="info-sep">:</span>
                    <span class="info-value">{{ $transaksi->detail_barang }}</span>
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

                {{-- Penerima (Mustahik) — KIRI --}}
                <div class="recipient-box">
                    <div class="sig-city-date">
                        {{ optional($transaksi->lembaga)->kota_nama ?? '' }},
                        {{ $transaksi->tanggal_penyaluran->translatedFormat('d F Y') }}
                    </div>
                    <div class="sig-position">Yang Menerima,</div>

                    <div class="sig-image-wrap">
                        @php
                            $ttMustahikUrl = null;
                            if (!empty($transaksi->path_tanda_tangan)) {
                                $ttMustahikUrl = asset('storage/' . $transaksi->path_tanda_tangan);
                            }
                        @endphp

                        @if($ttMustahikUrl)
                            <img src="{{ $ttMustahikUrl }}"
                                 alt="Tanda Tangan {{ optional($transaksi->mustahik)->nama_lengkap }}"
                                 class="tt-mustahik"
                                 onerror="this.style.display='none'; document.getElementById('tt-mustahik-fallback').style.display='flex';">
                            <div id="tt-mustahik-fallback" style="display:none; position:absolute; inset:0; align-items:center; justify-content:center;">
                                <div class="stamp-circle">
                                    <span>Tanda<br>Tangan</span>
                                </div>
                            </div>
                        @else
                            <div class="stamp-circle">
                                <span>Tanda<br>Tangan</span>
                            </div>
                        @endif
                    </div>

                    <div class="sig-name">
                        {{ optional($transaksi->mustahik)->nama_lengkap ?? '_____________________' }}
                    </div>
                    @if(optional($transaksi->mustahik)->nik)
                    <div class="sig-sub">NIK: {{ $transaksi->mustahik->nik }}</div>
                    @endif
                </div>

                {{-- Amil Penyalur — KANAN --}}
                <div class="signature-box">
                    <div class="sig-city-date">
                        {{ optional($transaksi->lembaga)->kota_nama ?? '' }},
                        {{ $transaksi->tanggal_penyaluran->translatedFormat('d F Y') }}
                    </div>
                    <div class="sig-position">Amil Penyalur,</div>

                    <div class="sig-image-wrap">
                        @if($transaksi->amil && !empty($transaksi->amil->tanda_tangan_url))
                            <img src="{{ $transaksi->amil->tanda_tangan_url }}"
                                 alt="Tanda Tangan Amil"
                                 class="tt-amil">
                        @elseif($transaksi->amil && !empty($transaksi->amil->path_tanda_tangan))
                            <img src="{{ asset('storage/' . $transaksi->amil->path_tanda_tangan) }}"
                                 alt="Tanda Tangan Amil"
                                 class="tt-amil">
                        @else
                            <div class="stamp-circle">
                                <span>Stempel &<br>Tanda Tangan</span>
                            </div>
                        @endif
                    </div>

                    <div class="sig-name">
                        {{ optional($transaksi->amil)->nama_lengkap
                            ?? optional(optional($transaksi->amil)->pengguna)->name
                            ?? '_____________________' }}
                    </div>
                    @if($transaksi->amil && $transaksi->amil->kode_amil)
                    <div class="sig-sub">Kode Amil: {{ $transaksi->amil->kode_amil }}</div>
                    @endif
                </div>

            </div>

            {{-- Catatan --}}
            <div style="margin-top:14px; padding:10px 14px; background:#f0fdf4; border-radius:6px; border:1px solid #bbf7d0;">
                <p style="font-size:11px; color:#6b7280; line-height:1.7;">
                    Kwitansi ini merupakan bukti sah penyaluran zakat kepada mustahik yang berhak.<br>
                    Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB &bull; Dokumen ini dicetak secara digital.
                </p>
            </div>

        </div>{{-- end .content --}}

        {{-- Footer --}}
        <div class="footer">
            <p>{{ $transaksi->lembaga->nama ?? 'Lembaga' }} &mdash; Pengelolaan Zakat Transparan & Amanah</p>
            <p>Semoga Allah SWT melipatgandakan kebaikan dan memudahkan urusan mustahik &bull; Dokumen ini sah tanpa tanda tangan basah</p>
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

            try {
                const element = document.getElementById('kwitansi-content');

                const canvas = await html2canvas(element, {
                    scale          : 2,
                    useCORS        : true,
                    allowTaint     : false,
                    logging        : false,
                    backgroundColor: '#ffffff',
                    windowWidth    : element.scrollWidth,
                    windowHeight   : element.scrollHeight,
                    foreignObjectRendering: false,
                    onclone: function(clonedDoc) {
                        const imgs = clonedDoc.querySelectorAll('img.tt-mustahik, img.tt-amil');
                        imgs.forEach(img => {
                            img.style.maxWidth  = '180px';
                            img.style.maxHeight = '85px';
                        });
                    }
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4', compress: true });

                const pageW  = pdf.internal.pageSize.getWidth();
                const pageH  = pdf.internal.pageSize.getHeight();
                const margin = 8;
                const maxW   = pageW - margin * 2;
                const maxH   = pageH - margin * 2;

                let imgW = maxW;
                let imgH = (canvas.height * imgW) / canvas.width;
                if (imgH > maxH) { imgH = maxH; imgW = (canvas.width * imgH) / canvas.height; }

                const offsetX = margin + (maxW - imgW) / 2;
                pdf.addImage(imgData, 'JPEG', offsetX, margin, imgW, imgH);
                pdf.save('kwitansi-penyaluran-{{ $transaksi->no_transaksi }}.pdf');

            } catch (err) {
                console.error('Gagal generate PDF:', err);
                alert('Gagal mengunduh PDF. Silakan coba lagi.\nError: ' + err.message);
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