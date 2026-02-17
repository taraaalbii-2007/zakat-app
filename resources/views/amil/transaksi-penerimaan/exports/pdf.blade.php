<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Penerimaan Zakat</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2d3436;
            margin: 20px;
        }
        
        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2.5px solid #2d3436;
            padding-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #000;
        }
        .header h2 {
            margin: 4px 0;
            font-size: 14px;
            font-weight: normal;
            color: #636e72;
        }
        .header .subtitle {
            margin: 2px 0;
            font-size: 11px;
            font-style: italic;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 20px;
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 140px;
            padding: 4px 0;
            font-weight: bold;
            color: #2d3436;
        }
        .info-value {
            display: table-cell;
            padding: 4px 0;
            border-bottom: 1px solid #f1f2f6;
        }
        
        /* Table Styles */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5px;
            table-layout: fixed;
        }
        table.data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
            border: 1px solid #2d3436;
            padding: 8px 4px;
            text-transform: uppercase;
        }
        table.data-table td {
            border: 1px solid #2d3436;
            padding: 6px 4px;
            word-wrap: break-word;
        }

        table.data-table tr {
            page-break-inside: avoid;
        }

        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
        }
        .badge-success { background-color: #e1f5fe; color: #01579b; border: 0.5px solid #01579b; }
        .badge-warning { background-color: #fffde7; color: #f57f17; border: 0.5px solid #f57f17; }
        .badge-danger { background-color: #ffebee; color: #b71c1c; border: 0.5px solid #b71c1c; }
        .badge-secondary { background-color: #f5f5f5; color: #616161; border: 0.5px solid #616161; }
        
        /* Footer & Signature */
        .footer-container {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        
        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none !important;
            padding: 0;
            vertical-align: top;
        }

        .signature-wrapper {
            width: 200px;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        .footer-note {
            clear: both;
            padding-top: 40px;
            text-align: center;
            font-size: 8px;
            color: #b2bec3;
            border-top: 1px dashed #dfe6e9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ strtoupper($masjid->nama ?? 'LAPORAN TRANSAKSI PENERIMAAN ZAKAT') }}</h1>
        <h2>Laporan Detail Transaksi</h2>
        <div class="subtitle">
            {{ $masjid->alamat ?? '' }}
            {{ $masjid->kelurahan_nama ? ', Kel. ' . $masjid->kelurahan_nama : '' }}
            {{ $masjid->kecamatan_nama ? ', Kec. ' . $masjid->kecamatan_nama : '' }}
            {{ $masjid->kota_nama ? ', ' . $masjid->kota_nama : '' }}
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Hari / Tanggal</div>
            <div class="info-value">: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}, {{ \Carbon\Carbon::now()->format('H:i') }} WIB</div>
        </div>

        <div class="info-row">
            <div class="info-label">Filter Berdasarkan</div>
            <div class="info-value">: 
                @php
                    $appliedFilters = [];
                    
                    // Filter Keyword (q)
                    if(!empty($filters['q'])) {
                        $appliedFilters[] = "Pencarian: '" . $filters['q'] . "'";
                    }

                    // Filter Periode (start_date & end_date)
                    if(!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $appliedFilters[] = "Periode: " . \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') . " - " . \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y');
                    }

                    // Filter Jenis Zakat (jenis_zakat_id)
                    if(!empty($filters['jenis_zakat_id'])) {
                        $jenis = $jenisZakatList->firstWhere('id', $filters['jenis_zakat_id']);
                        $appliedFilters[] = "Jenis: " . ($jenis->nama ?? 'Zakat');
                    }

                    // Filter Metode Pembayaran
                    if(!empty($filters['metode_pembayaran'])) {
                        $appliedFilters[] = "Metode Bayar: " . ucfirst($filters['metode_pembayaran']);
                    }

                    // Filter Status
                    if(!empty($filters['status'])) {
                        $statusText = match($filters['status']) {
                            'verified' => 'Terverifikasi',
                            'pending' => 'Menunggu',
                            'rejected' => 'Ditolak',
                            default => $filters['status']
                        };
                        $appliedFilters[] = "Status: " . $statusText;
                    }

                    // Filter Metode Penerimaan
                    if(!empty($filters['metode_penerimaan'])) {
                        $penerimaanText = $filters['metode_penerimaan'] == 'datang_langsung' ? 'Datang Langsung' : 'Dijemput';
                        $appliedFilters[] = "Penerimaan: " . $penerimaanText;
                    }
                @endphp

                @if(count($appliedFilters) > 0)
                    {{ implode(' | ', $appliedFilters) }}
                @else
                    Semua Data
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Ringkasan Data</div>
            <div class="info-value">: 
                <strong>{{ number_format($totalTransaksi, 0, ',', '.') }}</strong> Total | 
                <span style="color: #01579b;">{{ $totalVerified }} Terverifikasi</span> | 
                <span style="color: #f57f17;">{{ $totalPending }} Pending</span> | 
                <strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $user->name ?? $user->username ?? 'System' }}</div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">No</th>
                <th rowspan="2" style="width: 80px;">No. Transaksi</th>
                <th rowspan="2" style="width: 60px;">Tanggal</th>
                <th rowspan="2">Muzakki</th>
                <th colspan="4">Detail Zakat</th>
                <th colspan="2">Pembayaran</th>
                <th colspan="2">Status</th>
                <th rowspan="2">Amil</th>
            </tr>
            <tr>
                <th>Jenis</th>
                <th>Tipe</th>
                <th>Program</th>
                <th style="width: 70px;">Jumlah (Rp)</th>
                <th>Metode</th>
                <th>Tipe Pay</th>
                <th>Verif</th>
                <th>Pay</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $transaksi)
                @php
                    $statusClass = match($transaksi->status) {
                        'verified' => 'badge-success',
                        'pending' => 'badge-warning',
                        'rejected' => 'badge-danger',
                        default => 'badge-secondary'
                    };
                    $paymentStatusClass = match($transaksi->payment_status) {
                        'berhasil' => 'badge-success',
                        'menunggu_pembayaran' => 'badge-warning',
                        'kadaluarsa', 'dibatalkan' => 'badge-danger',
                        default => 'badge-secondary'
                    };
                    $paymentStatusText = match($transaksi->payment_status) {
                        'berhasil' => 'SUKSES',
                        'menunggu_pembayaran' => 'PENDING',
                        'kadaluarsa' => 'EXPIRED',
                        'dibatalkan' => 'BATAL',
                        default => '-'
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 8px;">{{ $transaksi->no_transaksi }}</td>
                    <td class="text-center">{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td class="text-left"><strong>{{ $transaksi->muzakki_nama }}</strong></td>
                    <td class="text-left">{{ $transaksi->jenisZakat->nama ?? '-' }}</td>
                    <td class="text-left">{{ $transaksi->tipeZakat->nama ?? '-' }}</td>
                    <td class="text-left" style="font-size: 8px;">{{ \Illuminate\Support\Str::limit($transaksi->programZakat->nama_program ?? '-', 20) }}</td>
                    <td class="text-right">{{ $transaksi->jumlah > 0 ? number_format($transaksi->jumlah, 0, ',', '.') : '-' }}</td>
                    <td class="text-center">{{ $transaksi->metode_pembayaran ? ucfirst($transaksi->metode_pembayaran) : '-' }}</td>
                    <td class="text-center">{{ $transaksi->payment_type ?? '-' }}</td>
                    <td class="text-center"><span class="badge {{ $statusClass }}">{{ strtoupper($transaksi->status) }}</span></td>
                    <td class="text-center"><span class="badge {{ $paymentStatusClass }}">{{ $paymentStatusText }}</span></td>
                    <td class="text-left">{{ $transaksi->amil->pengguna->name ?? $transaksi->amil->nama_lengkap ?? '-' }}</td>
                </tr>

                @if($transaksi->jumlah_beras_kg || $transaksi->jumlah_jiwa || $transaksi->nilai_harta || $transaksi->keterangan)
                <tr style="background-color: #fafafa;">
                    <td colspan="13" style="padding: 4px 8px; font-size: 8px; color: #636e72; border-top: none;">
                        <span style="font-weight: bold; color: #2d3436;">Rincian:</span> 
                        @if($transaksi->jumlah_beras_kg) Beras: {{ $transaksi->jumlah_beras_kg }} kg (@ Rp {{ number_format($transaksi->harga_beras_per_kg ?? 0, 0, ',', '.') }}) | @endif
                        @if($transaksi->jumlah_jiwa) Jiwa: {{ $transaksi->jumlah_jiwa }} orang | @endif
                        @if($transaksi->nilai_harta) Nilai Harta: Rp {{ number_format($transaksi->nilai_harta, 0, ',', '.') }} | @endif
                        @if($transaksi->keterangan) Ket: {{ $transaksi->keterangan }} @endif
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 30px; color: #b2bec3;">
                        <em>Data tidak ditemukan untuk kriteria filter ini.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-container">
        <table class="signature-table">
            <tr>
                <td style="width: 70%;"></td> 
                <td style="width: 30%;">
                    <div class="signature-wrapper">
                        <div style="margin-bottom: 5px;">{{ $masjid->kota_nama ?? 'Bandung' }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
                        <div>Mengetahui,</div>
                        <div style="margin-bottom: 10px;"><strong>Admin Masjid</strong></div>
                        <div class="signature-space"></div>
                        <div class="signature-name">{{ $masjid->admin_nama ?? '_____________________' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        <p>Laporan ini diterbitkan secara resmi melalui Sistem Manajemen Zakat {{ $masjid->nama ?? 'Masjid' }}.</p>
        <p>Dicetak pada: {{ $tanggalExport }}</p>
    </div>
</body>
</html>