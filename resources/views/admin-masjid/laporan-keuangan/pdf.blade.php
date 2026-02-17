<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan Masjid</title>
    <style>
        /* Reset dan base styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }
        
        .container {
            width: 100%;
            padding: 20px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        
        .header-logo {
            height: 80px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 18px;
            color: #6b7280;
            font-weight: normal;
        }
        
        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
        }
        
        .saldo-awal {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .penerimaan {
            background: #d1fae5;
            color: #065f46;
        }
        
        .penyaluran {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .saldo-akhir {
            background: #ede9fe;
            color: #5b21b6;
        }
        
        /* Tables */
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border: 1px solid #e5e7eb;
        }
        
        td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-green {
            color: #065f46;
        }
        
        .text-red {
            color: #991b1b;
        }
        
        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-draft {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        .status-published {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            width: 100%;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
            font-weight: bold;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Print styles */
        @media print {
            body {
                font-size: 10pt;
            }
            
            .no-print {
                display: none;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    {{-- Watermark --}}
    @if($laporan->status === 'published')
        <div class="watermark">OFFICIAL REPORT</div>
    @endif

    <div class="container">
        {{-- Header --}}
        <div class="header">
            @if($logo)
                <img src="{{ $logo }}" alt="Logo Masjid" class="header-logo">
            @endif
            <h1>LAPORAN KEUANGAN MASJID</h1>
            <h2>{{ $laporan->masjid->nama }}</h2>
            <p>Periode: {{ $laporan->periode_mulai->format('d F Y') }} - {{ $laporan->periode_selesai->format('d F Y') }}</p>
            <p>Status: <span class="status-badge status-{{ $laporan->status }}">{{ strtoupper($laporan->status) }}</span></p>
        </div>

        {{-- Summary Cards --}}
        <div class="summary-grid">
            <div class="summary-card saldo-awal">
                <div class="summary-label">SALDO AWAL</div>
                <div class="summary-value">Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</div>
            </div>
            
            <div class="summary-card penerimaan">
                <div class="summary-label">TOTAL PENERIMAAN</div>
                <div class="summary-value">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</div>
                <div style="font-size: 10px; margin-top: 5px;">
                    {{ $laporan->jumlah_muzakki }} Muzakki • {{ $laporan->jumlah_transaksi_masuk }} Transaksi
                </div>
            </div>
            
            <div class="summary-card penyaluran">
                <div class="summary-label">TOTAL PENYALURAN</div>
                <div class="summary-value">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</div>
                <div style="font-size: 10px; margin-top: 5px;">
                    {{ $laporan->jumlah_mustahik }} Mustahik • {{ $laporan->jumlah_transaksi_keluar }} Transaksi
                </div>
            </div>
            
            <div class="summary-card saldo-akhir">
                <div class="summary-label">SALDO AKHIR</div>
                <div class="summary-value">Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Detail Penerimaan --}}
        <div class="section-title">DETAIL PENERIMAAN PER JENIS ZAKAT</div>
        <table>
            <thead>
                <tr>
                    <th>Jenis Zakat</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPenerimaan = $laporan->total_penerimaan;
                @endphp
                @foreach($detailPenerimaan as $item)
                    <tr>
                        <td>{{ $item['jenis_zakat'] }}</td>
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
                @endforeach
                @if(count($detailPenerimaan) > 0)
                    <tr style="font-weight: bold; background-color: #f9fafb;">
                        <td>TOTAL</td>
                        <td class="text-center">{{ $laporan->jumlah_transaksi_masuk }}</td>
                        <td class="text-right">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                        <td class="text-center">100%</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data penerimaan</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- Detail Penyaluran --}}
        <div class="section-title">DETAIL PENYALURAN PER KATEGORI MUSTAHIK</div>
        <table>
            <thead>
                <tr>
                    <th>Kategori Mustahik</th>
                    <th class="text-center">Jumlah Mustahik</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPenyaluran = $laporan->total_penyaluran;
                @endphp
                @foreach($detailPenyaluran as $item)
                    <tr>
                        <td>{{ $item['kategori'] }}</td>
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
                @endforeach
                @if(count($detailPenyaluran) > 0)
                    <tr style="font-weight: bold; background-color: #f9fafb;">
                        <td>TOTAL</td>
                        <td class="text-center">{{ $laporan->jumlah_mustahik }}</td>
                        <td class="text-right">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</td>
                        <td class="text-center">100%</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data penyaluran</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- Informasi Tambahan --}}
        <div class="section-title">INFORMASI LAINNYA</div>
        <table>
            <tbody>
                <tr>
                    <td width="30%"><strong>Dibuat Oleh</strong></td>
                    <td>: {{ $laporan->creator->nama_lengkap ?? 'System' }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Generate</strong></td>
                    <td>: {{ $laporan->created_at->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Publikasi</strong></td>
                    <td>: {{ $laporan->published_at ? $laporan->published_at->format('d F Y, H:i') : 'Belum dipublikasi' }}</td>
                </tr>
                <tr>
                    <td><strong>Nomor Laporan</strong></td>
                    <td>: {{ $laporan->uuid }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature">
                <p>Mengetahui,</p>
                <p><strong>BENDAHARA</strong></p>
                <div class="signature-line"></div>
                <p style="margin-top: 5px;">(................................)</p>
            </div>
            
            <div class="signature">
                <p>Yang Membuat Laporan,</p>
                <p><strong>ADMIN MASJID</strong></p>
                <div class="signature-line"></div>
                <p style="margin-top: 5px;">({{ $laporan->creator->nama_lengkap ?? '................................' }})</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>Dokumen ini dicetak pada: {{ $tanggalCetak }}</p>
            <p>Laporan ini {{ $laporan->status === 'published' ? 'telah dipublikasi secara resmi' : 'masih dalam status draft' }}</p>
            <p>© {{ date('Y') }} Sistem Manajemen Zakat Masjid</p>
        </div>
    </div>
</body>
</html>