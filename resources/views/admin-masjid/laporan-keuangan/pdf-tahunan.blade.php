<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tahunan Keuangan Masjid</title>
    <style>
        /* Reset dan base styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .container {
            width: 100%;
            padding: 15px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
        }
        
        .header h1 {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 16px;
            color: #6b7280;
            font-weight: normal;
        }
        
        /* Summary Cards */}
        .summary-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            font-size: 9px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        
        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #d1d5db;
        }
        
        td {
            padding: 5px 8px;
            border: 1px solid #d1d5db;
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
        
        .bg-gray-50 {
            background-color: #f9fafb;
        }
        
        .font-semibold {
            font-weight: bold;
        }
        
        /* Chart container */}
        .chart-container {
            height: 200px;
            margin: 20px 0;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #6b7280;
            text-align: center;
        }
        
        /* Print styles */
        @media print {
            body {
                font-size: 8pt;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .no-print {
                display: none;
            }
        }
        
        /* Landscape specific */
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>LAPORAN TAHUNAN KEUANGAN MASJID {{ $tahun }}</h1>
            <h2>{{ $masjid->nama }}</h2>
            <p>Alamat: {{ $masjid->alamat }}</p>
        </div>

        {{-- Summary Section --}}
        <div class="summary-section">
            <div class="summary-card" style="background-color: #d1fae5;">
                <div>Total Penerimaan</div>
                <div class="summary-value">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</div>
                <div>{{ $totalMuzakki }} Muzakki</div>
            </div>
            
            <div class="summary-card" style="background-color: #fee2e2;">
                <div>Total Penyaluran</div>
                <div class="summary-value">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</div>
                <div>{{ $totalMustahik }} Mustahik</div>
            </div>
            
            <div class="summary-card" style="background-color: #dbeafe;">
                <div>Saldo Awal Tahun</div>
                <div class="summary-value">Rp {{ number_format($laporanTahunan->first()->saldo_awal ?? 0, 0, ',', '.') }}</div>
            </div>
            
            <div class="summary-card" style="background-color: #ede9fe;">
                <div>Saldo Akhir Tahun</div>
                <div class="summary-value">Rp {{ number_format($laporanTahunan->last()->saldo_akhir ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Main Table --}}
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Bulan</th>
                    <th colspan="3" class="text-center">Saldo</th>
                    <th colspan="2" class="text-center">Penerimaan</th>
                    <th colspan="2" class="text-center">Penyaluran</th>
                    <th rowspan="2">Status</th>
                </tr>
                <tr>
                    <th>Awal</th>
                    <th>Akhir</th>
                    <th>Bersih</th>
                    <th>Jumlah</th>
                    <th>Muzakki</th>
                    <th>Jumlah</th>
                    <th>Mustahik</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporanTahunan as $laporan)
                <tr>
                    <td>{{ $laporan->nama_bulan }}</td>
                    <td class="text-right">Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</td>
                    <td class="text-right {{ ($laporan->saldo_akhir - $laporan->saldo_awal) >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($laporan->saldo_akhir - $laporan->saldo_awal, 0, ',', '.') }}
                    </td>
                    <td class="text-right text-green">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $laporan->jumlah_muzakki }}</td>
                    <td class="text-right text-red">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $laporan->jumlah_mustahik }}</td>
                    <td class="text-center">
                        @if($laporan->status === 'published')
                            <span style="color: #065f46; font-weight: bold;">PUBLISHED</span>
                        @elseif($laporan->status === 'draft')
                            <span style="color: #6b7280;">DRAFT</span>
                        @else
                            <span style="color: #1d4ed8;">FINAL</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                
                {{-- Total Row --}}
                <tr class="bg-gray-50 font-semibold">
                    <td>TOTAL</td>
                    <td class="text-right">-</td>
                    <td class="text-right">-</td>
                    <td class="text-right {{ ($totalPenerimaan - $totalPenyaluran) >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($totalPenerimaan - $totalPenyaluran, 0, ',', '.') }}
                    </td>
                    <td class="text-right text-green">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $totalMuzakki }}</td>
                    <td class="text-right text-red">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $totalMustahik }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        {{-- Chart Section --}}
        <div style="margin: 20px 0;">
            <h3 style="font-size: 12px; margin-bottom: 10px; text-align: center;">GRAFIK PENERIMAAN VS PENYALURAN TAHUN {{ $tahun }}</h3>
            {{-- Chart akan di-generate secara manual atau bisa menggunakan library seperti Chart.js --}}
            <div style="text-align: center; padding: 20px; border: 1px dashed #d1d5db; border-radius: 6px;">
                <p style="color: #6b7280; font-style: italic;">
                    Grafik tersedia dalam versi digital. Untuk melihat grafik interaktif, 
                    kunjungi dashboard laporan keuangan.
                </p>
            </div>
        </div>

        {{-- Analysis Section --}}
        <div style="margin-top: 20px;">
            <h3 style="font-size: 12px; margin-bottom: 10px;">ANALISIS TAHUNAN</h3>
            <table>
                <tbody>
                    <tr>
                        <td width="30%">Rata-rata Penerimaan per Bulan</td>
                        <td>: Rp {{ number_format($totalPenerimaan / 12, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Rata-rata Penyaluran per Bulan</td>
                        <td>: Rp {{ number_format($totalPenyaluran / 12, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Bulan dengan Penerimaan Tertinggi</td>
                        <td>: 
                            @php
                                $maxPenerimaan = $laporanTahunan->max('total_penerimaan');
                                $bulanMax = $laporanTahunan->where('total_penerimaan', $maxPenerimaan)->first();
                            @endphp
                            {{ $bulanMax->nama_bulan ?? '-' }} (Rp {{ number_format($maxPenerimaan, 0, ',', '.') }})
                        </td>
                    </tr>
                    <tr>
                        <td>Bulan dengan Penyaluran Tertinggi</td>
                        <td>: 
                            @php
                                $maxPenyaluran = $laporanTahunan->max('total_penyaluran');
                                $bulanMaxPenyaluran = $laporanTahunan->where('total_penyaluran', $maxPenyaluran)->first();
                            @endphp
                            {{ $bulanMaxPenyaluran->nama_bulan ?? '-' }} (Rp {{ number_format($maxPenyaluran, 0, ',', '.') }})
                        </td>
                    </tr>
                    <tr>
                        <td>Persentase Penyaluran dari Penerimaan</td>
                        <td>: 
                            @if($totalPenerimaan > 0)
                                {{ number_format(($totalPenyaluran / $totalPenerimaan) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>Laporan ini dicetak pada: {{ $tanggalCetak }}</p>
            <p>© {{ date('Y') }} Sistem Manajemen Zakat Masjid • Halaman 1/1</p>
        </div>
    </div>
</body>
</html>