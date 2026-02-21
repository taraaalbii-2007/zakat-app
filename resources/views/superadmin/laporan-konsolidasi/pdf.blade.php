<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konsolidasi - {{ $masjid->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #2d3436;
            margin: 15px;
        }
        
        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2.5px solid #2d3436;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #000;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 4px 0;
            font-size: 14px;
            font-weight: normal;
            color: #636e72;
        }
        .header .subtitle {
            margin: 2px 0;
            font-size: 10px;
            font-style: italic;
            color: #2d3436;
        }
        
        /* Info Section */
        .info-section {
            margin-bottom: 20px;
            width: 100%;
            border: 1px solid #dfe6e9;
            border-radius: 5px;
            padding: 10px;
            background-color: #f8f9fa;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }
        .info-label {
            display: table-cell;
            width: 130px;
            padding: 3px 0;
            font-weight: bold;
            color: #2d3436;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
        }
        
        /* Summary Cards */
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .card {
            display: table-cell;
            width: 25%;
            border: 1px solid #1a7a4a;
            padding: 8px;
            text-align: center;
            background-color: #f0f9f4;
        }
        .card-title {
            font-size: 9px;
            color: #1a7a4a;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .card-value {
            font-size: 14px;
            font-weight: bold;
            color: #155d38;
        }
        .card-sub {
            font-size: 8px;
            color: #636e72;
        }
        
        /* Table Styles - Diperbaiki untuk menghindari pemotongan */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8.5px;
            table-layout: fixed;
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        tfoot {
            display: table-footer-group;
        }
        
        table.data-table th {
            background-color: #1a7a4a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            border: 1px solid #155d38;
            padding: 6px 3px;
            text-transform: uppercase;
            font-size: 8px;
        }
        table.data-table td {
            border: 1px solid #2d3436;
            padding: 4px 3px;
            word-wrap: break-word;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        /* Section Titles */
        .section-title {
            background-color: #e9ecef;
            padding: 8px 10px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            font-size: 11px;
            color: #2d3436;
            border-left: 4px solid #1a7a4a;
            text-transform: uppercase;
            page-break-after: avoid;
        }
        
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Footer */
        .footer-note {
            clear: both;
            padding-top: 30px;
            text-align: center;
            font-size: 7px;
            color: #b2bec3;
            border-top: 1px dashed #dfe6e9;
            margin-top: 20px;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        /* Compact table for better fit */
        .compact-table th, .compact-table td {
            padding: 3px 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ strtoupper($masjid->nama ?? 'LAPORAN KONSOLIDASI ZAKAT') }}</h1>
        <h2>Laporan Konsolidasi {{ $type == 'penerimaan' ? 'Penerimaan' : ($type == 'penyaluran' ? 'Penyaluran' : 'Penerimaan & Penyaluran') }}</h2>
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
            <div class="info-label">Periode Laporan</div>
            <div class="info-value">: {{ $periode_text ?? ($bulan_nama . ' ' . $tahun) }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Filter</div>
            <div class="info-value">: 
                @php
                    $filterText = [];
                    if(!empty($filters['type'])) {
                        $typeText = match($filters['type']) {
                            'penerimaan' => 'Penerimaan',
                            'penyaluran' => 'Penyaluran',
                            default => 'Konsolidasi'
                        };
                        $filterText[] = "Tipe: " . $typeText;
                    }
                @endphp
                
                @if(count($filterText) > 0)
                    {{ implode(' | ', $filterText) }}
                @else
                    Semua Data
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $user->name ?? $user->username ?? 'System' }}</div>
        </div>
    </div>

    @if($type == 'konsolidasi')
        @php
            // Convert array to collection untuk menghindari error sum() on array
            $ringkasanCollection = collect($ringkasan_bulanan ?? []);
        @endphp
        
        <!-- Summary Cards -->
        <table class="summary-cards">
            <tr>
                <td class="card">
                    <div class="card-title">Total Penerimaan</div>
                    <div class="card-value">Rp {{ number_format($total_penerimaan ?? 0, 0, ',', '.') }}</div>
                    <div class="card-sub">{{ number_format($total_penerimaan_transaksi ?? 0, 0, ',', '.') }} Transaksi</div>
                </td>
                <td class="card">
                    <div class="card-title">Total Penyaluran</div>
                    <div class="card-value">Rp {{ number_format($total_penyaluran ?? 0, 0, ',', '.') }}</div>
                    <div class="card-sub">{{ number_format($total_penyaluran_transaksi ?? 0, 0, ',', '.') }} Transaksi</div>
                </td>
                <td class="card">
                    <div class="card-title">Saldo Akhir</div>
                    <div class="card-value">Rp {{ number_format(($total_penerimaan ?? 0) - ($total_penyaluran ?? 0), 0, ',', '.') }}</div>
                    <div class="card-sub">Surplus/Defisit</div>
                </td>
                <td class="card">
                    <div class="card-title">Jumlah Muzakki/Mustahik</div>
                    <div class="card-value">{{ number_format($ringkasanCollection->sum('jumlah_muzakki'), 0, ',', '.') }} / {{ number_format($ringkasanCollection->sum('jumlah_mustahik'), 0, ',', '.') }}</div>
                    <div class="card-sub">Muzakki / Mustahik</div>
                </td>
            </tr>
        </table>

        <!-- Ringkasan Bulanan -->
        <div class="section-title">RINGKASAN BULANAN</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Periode</th>
                    <th>Penerimaan (Rp)</th>
                    <th style="width: 40px;">Trans</th>
                    <th>Muzakki</th>
                    <th>Penyaluran (Rp)</th>
                    <th style="width: 40px;">Trans</th>
                    <th>Mustahik</th>
                    <th>Surplus/Defisit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ringkasan_bulanan ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['periode'] }}</td>
                    <td class="text-right">{{ number_format($item['total_penerimaan'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['jumlah_transaksi_penerimaan'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['jumlah_muzakki'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total_penyaluran'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['jumlah_transaksi_penyaluran'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['jumlah_mustahik'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total_penerimaan'] - $item['total_penyaluran'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data untuk periode ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Breakdown Jenis Zakat -->
        <div class="section-title">BREAKDOWN PER JENIS ZAKAT</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Jenis Zakat</th>
                    <th>Jumlah Transaksi</th>
                    <th>Jumlah Muzakki</th>
                    <th>Total Nominal (Rp)</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown_jenis_zakat ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_zakat }}</td>
                    <td class="text-center">{{ number_format($item->jumlah_transaksi, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item->jumlah_muzakki, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
                    <td class="text-center">{{ round(($item->total_nominal / ($total_penerimaan ?: 1)) * 100, 2) }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data penerimaan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Breakdown Kategori Mustahik -->
        <div class="section-title">BREAKDOWN PER KATEGORI MUSTAHIK</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Kategori Mustahik</th>
                    <th>Jumlah Transaksi</th>
                    <th>Jumlah Mustahik</th>
                    <th>Total Penyaluran (Rp)</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown_kategori_mustahik ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kategori }}</td>
                    <td class="text-center">{{ number_format($item->jumlah_transaksi, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item->jumlah_mustahik, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
                    <td class="text-center">{{ round(($item->total_nominal / ($total_penyaluran ?: 1)) * 100, 2) }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data penyaluran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    @if(in_array($type, ['penerimaan', 'penyaluran']))
        {{-- HAPUS TAG PAGE-BREAK INI --}}
        {{-- <div class="page-break"></div> --}}
        
        <div class="section-title">DETAIL TRANSAKSI {{ strtoupper($type) }}</div>
        
        @if($type == 'penerimaan' && !empty($penerimaan_detail))
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 70px;">No. Transaksi</th>
                        <th style="width: 50px;">Tanggal</th>
                        <th>Muzakki</th>
                        <th>Jenis Zakat</th>
                        <th>Program</th>
                        <th style="width: 65px;">Jumlah (Rp)</th>
                        <th>Metode</th>
                        <th>Amil</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penerimaan_detail as $index => $transaksi)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td style="font-size: 7px;">{{ $transaksi->no_transaksi }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td><strong>{{ $transaksi->muzakki_nama }}</strong></td>
                        <td>{{ $transaksi->jenis_zakat_nama ?? '-' }}</td>
                        <td style="font-size: 7px;">{{ Str::limit($transaksi->program_zakat_nama ?? '-', 15) }}</td>
                        <td class="text-right">{{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                        <td class="text-center">{{ ucfirst($transaksi->metode_pembayaran ?? '-') }}</td>
                        <td style="font-size: 7px;">{{ $transaksi->amil_nama ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">Verified</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right"><strong>TOTAL</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($total_penerimaan, 0, ',', '.') }}</strong></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        @endif
        
        @if($type == 'penyaluran' && !empty($penyaluran_detail))
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 70px;">No. Transaksi</th>
                        <th style="width: 50px;">Tanggal</th>
                        <th>Mustahik</th>
                        <th>Kategori</th>
                        <th>Program</th>
                        <th style="width: 65px;">Jumlah (Rp)</th>
                        <th>Metode</th>
                        <th>Amil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyaluran_detail as $index => $transaksi)
                    @php
                        $nominal = $transaksi->metode_penyaluran == 'barang' ? 
                            ($transaksi->nilai_barang ?? 0) : ($transaksi->jumlah ?? 0);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td style="font-size: 7px;">{{ $transaksi->no_transaksi_penyaluran ?? $transaksi->no_transaksi ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal_penyaluran)->format('d/m/Y') }}</td>
                        <td><strong>{{ $transaksi->nama_mustahik ?? $transaksi->mustahik_nama ?? '-' }}</strong></td>
                        <td>{{ $transaksi->kategori_mustahik_nama ?? '-' }}</td>
                        <td style="font-size: 7px;">{{ Str::limit($transaksi->program_penyaluran_nama ?? '-', 15) }}</td>
                        <td class="text-right">{{ number_format($nominal, 0, ',', '.') }}</td>
                        <td class="text-center">{{ ucfirst($transaksi->metode_penyaluran ?? '-') }}</td>
                        <td style="font-size: 7px;">{{ $transaksi->amil_nama ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right"><strong>TOTAL</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($total_penyaluran, 0, ',', '.') }}</strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    @endif

    <div class="footer-note">
        <p><strong>Catatan:</strong> Laporan ini dibuat berdasarkan transaksi yang telah terverifikasi. Untuk penerimaan via transfer, nominal sesuai dengan bukti transfer yang diunggah. Penyaluran barang dinilai berdasarkan harga pasar wajar.</p>
        <p>Laporan ini diterbitkan secara resmi melalui Sistem Manajemen Zakat {{ $masjid->nama ?? 'Masjid' }}.</p>
        <p>Dicetak pada: {{ $tanggalExport }}</p>
    </div>
</body>
</html>