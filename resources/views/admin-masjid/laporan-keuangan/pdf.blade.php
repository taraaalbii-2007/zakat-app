<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Masjid - {{ $laporan->masjid->nama }}</title>
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
            width: 100%;
        }
        .info-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .info-row:after {
            content: "";
            display: table;
            clear: both;
        }
        .info-label {
            float: left;
            width: 140px;
            padding: 4px 0;
            font-weight: bold;
            color: #2d3436;
        }
        .info-value {
            padding: 4px 0;
            padding-left: 150px;
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
            background-color: #1a7a4a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            border: 1px solid #155d38;
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
        .text-green { color: #065f46; }
        .text-red { color: #991b1b; }
        .text-blue { color: #1e40af; }
        .text-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        
        /* Footer & Signature */
        .footer-container {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }

        .footer-container:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .signature-wrapper {
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-space {
            height: 70px;
        }
        
        .signature-space {
            height: 70px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 0px;
            margin-bottom: 1px;
        }

        .signature-line-table {
            width: 210px;
            margin: 0 auto;
            border-collapse: collapse;
            font-weight: bold;
            font-size: 11px;
        }

        .signature-line-table td {
            border: none;
            padding: 0;
            padding-top: 0;
            border-bottom: 1px solid #2d3436;
            line-height: 1.4;
        }

        .paren-left {
            text-align: left;
            width: 10px;
        }

        .paren-mid {
            text-align: center;
        }

        .paren-right {
            text-align: right;
            width: 10px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .footer-note {
            clear: both;
            padding-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #b2bec3;
            border-top: 1px dashed #dfe6e9;
        }
        
        .page-break {
            page-break-before: always;
        }

        .ringkasan-value {
            font-weight: bold;
        }

        .status-text {
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-draft-text {
            color: #6b7280;
        }

        .status-published-text {
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ strtoupper($laporan->masjid->nama ?? 'LAPORAN KEUANGAN MASJID') }}</h1>
        <h2>Laporan Keuangan Bulanan</h2>
        <div class="subtitle">
            {{ $laporan->masjid->alamat ?? '' }}
            @if(isset($laporan->masjid->kelurahan_nama) && $laporan->masjid->kelurahan_nama)
                , Kel. {{ $laporan->masjid->kelurahan_nama }}
            @endif
            @if(isset($laporan->masjid->kecamatan_nama) && $laporan->masjid->kecamatan_nama)
                , Kec. {{ $laporan->masjid->kecamatan_nama }}
            @endif
            @if(isset($laporan->masjid->kota_nama) && $laporan->masjid->kota_nama)
                , {{ $laporan->masjid->kota_nama }}
            @endif
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Periode Laporan</div>
            <div class="info-value">: {{ \Carbon\Carbon::parse($laporan->periode_mulai)->locale('id')->translatedFormat('l, d F Y') }} - {{ \Carbon\Carbon::parse($laporan->periode_selesai)->locale('id')->translatedFormat('l, d F Y') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Bulan / Tahun</div>
            <div class="info-value">: {{ \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->locale('id')->translatedFormat('F Y') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Status Laporan</div>
            <div class="info-value">: 
                <span class="status-text status-{{ $laporan->status }}-text">{{ strtoupper($laporan->status) }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Ringkasan Data</div>
            <div class="info-value">: 
                <span class="text-blue"><strong>Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</strong> Saldo Awal</span> | 
                <span class="text-green"><strong>Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</strong> Penerimaan</span> | 
                <span class="text-red"><strong>Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</strong> Penyaluran</span> | 
                <strong>Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</strong> Saldo Akhir
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Statistik Transaksi</div>
            <div class="info-value">: 
                <strong>{{ $laporan->jumlah_muzakki }}</strong> Muzakki | 
                <strong>{{ $laporan->jumlah_transaksi_masuk }}</strong> Transaksi Masuk | 
                <strong>{{ $laporan->jumlah_mustahik }}</strong> Mustahik | 
                <strong>{{ $laporan->jumlah_transaksi_keluar }}</strong> Transaksi Keluar
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $laporan->creator->nama_lengkap ?? $laporan->creator->username ?? 'System' }}</div>
        </div>
    </div>

    {{-- Detail Penerimaan --}}
    <h3 style="margin: 20px 0 10px 0; font-size: 12px; border-bottom: 1px solid #2d3436; padding-bottom: 5px;">DETAIL PENERIMAAN PER JENIS ZAKAT</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Jenis Zakat</th>
                <th style="width: 20%;">Jumlah Transaksi</th>
                <th style="width: 25%;">Total (Rp)</th>
                <th style="width: 15%;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPenerimaan = $laporan->total_penerimaan;
            @endphp
            @forelse($detailPenerimaan as $item)
                <tr>
                    <td class="text-left">{{ $item['jenis_zakat'] }}</td>
                    <td class="text-center">{{ $item['count'] }}</td>
                    <td class="text-right text-green">Rp {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($totalPenerimaan > 0)
                            {{ number_format(($item['jumlah'] / $totalPenerimaan) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px;">Tidak ada data penerimaan</td>
                </tr>
            @endforelse
            @if(count($detailPenerimaan) > 0)
                <tr style="font-weight: bold; background-color: #f1f2f6;">
                    <td class="text-left">TOTAL</td>
                    <td class="text-center">{{ $laporan->jumlah_transaksi_masuk }}</td>
                    <td class="text-right">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                    <td class="text-center">100%</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Detail Penyaluran --}}
    <h3 style="margin: 20px 0 10px 0; font-size: 12px; border-bottom: 1px solid #2d3436; padding-bottom: 5px;">DETAIL PENYALURAN PER KATEGORI MUSTAHIK</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Kategori Mustahik</th>
                <th style="width: 20%;">Jumlah Mustahik</th>
                <th style="width: 25%;">Total (Rp)</th>
                <th style="width: 15%;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPenyaluran = $laporan->total_penyaluran;
            @endphp
            @forelse($detailPenyaluran as $item)
                <tr>
                    <td class="text-left">{{ $item['kategori'] }}</td>
                    <td class="text-center">{{ $item['count'] }}</td>
                    <td class="text-right text-red">Rp {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($totalPenyaluran > 0)
                            {{ number_format(($item['jumlah'] / $totalPenyaluran) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px;">Tidak ada data penyaluran</td>
                </tr>
            @endforelse
            @if(count($detailPenyaluran) > 0)
                <tr style="font-weight: bold; background-color: #f1f2f6;">
                    <td class="text-left">TOTAL</td>
                    <td class="text-center">{{ $laporan->jumlah_mustahik }}</td>
                    <td class="text-right">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</td>
                    <td class="text-center">100%</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Informasi Tambahan --}}
    <h3 style="margin: 20px 0 10px 0; font-size: 12px; border-bottom: 1px solid #2d3436; padding-bottom: 5px;">INFORMASI LAPORAN</h3>
    
    <table class="data-table">
        <tbody>
            <tr>
                <td style="background-color: #f1f2f6; width: 35%;"><strong>Dibuat Oleh</strong></td>
                <td>{{ $laporan->creator->nama_lengkap ?? $laporan->creator->username ?? 'System' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f1f2f6;"><strong>Tanggal Generate</strong></td>
                <td>{{ \Carbon\Carbon::parse($laporan->created_at)->locale('id')->translatedFormat('l, d F Y H:i') }} WIB</td>
            </tr>
            @if($laporan->published_at)
            <tr>
                <td style="background-color: #f1f2f6;"><strong>Tanggal Publikasi</strong></td>
                <td>{{ \Carbon\Carbon::parse($laporan->published_at)->locale('id')->translatedFormat('l, d F Y H:i') }} WIB</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Signature Section - Rata Kanan --}}
    <div class="footer-container">
        <div class="signature-wrapper">
            <div>{{ $laporan->masjid->kota_nama ?? 'Bandung' }}, {{ \Carbon\Carbon::parse($tanggalCetak)->locale('id')->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 15px;">Yang Membuat Laporan,</div>
            <div class="signature-title">ADMIN MASJID</div>
            <div class="signature-space"></div>
            <table class="signature-line-table" style="margin-top: 0;">
                <tr>
                    <td colspan="3" style="border: none; text-align: center; font-weight: bold; padding: 0; line-height: 1.3;">
                        {{ $laporan->creator->nama_lengkap ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="paren-left">(</td>
                    <td class="paren-mid">&nbsp;</td>
                    <td class="paren-right">)</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>