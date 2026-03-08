<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tahunan Keuangan Masjid - {{ $masjid->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2d3436;
            margin: 20px;
            background: #fff;
        }

        /* ── Header ── */
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

        /* ── Info Section ── */
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
            width: 180px;
            padding: 4px 0;
            font-weight: bold;
            color: #2d3436;
        }
        .info-value {
            padding: 4px 0;
            padding-left: 190px;
            border-bottom: 1px solid #f1f2f6;
        }

        /* ── Summary Cards ── */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 20px;
        }
        .summary-table td {
            width: 25%;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            font-size: 10px;
            border: none;
        }
        .summary-value {
            font-size: 13px;
            font-weight: bold;
            margin: 4px 0;
        }

        /* ── Section Titles ── */
        .section-title {
            margin: 20px 0 10px 0;
            font-size: 12px;
            border-bottom: 1px solid #2d3436;
            padding-bottom: 5px;
        }

        /* ── Data Tables ── */
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

        /* ── Analysis Table ── */
        table.analysis-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5px;
        }
        table.analysis-table td {
            border: 1px solid #2d3436;
            padding: 6px 8px;
        }
        table.analysis-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* ── Utility ── */
        .text-left   { text-align: left; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-green  { color: #065f46; }
        .text-red    { color: #991b1b; }
        .text-blue   { color: #1e40af; }
        .text-bold   { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }

        .status-published { color: #065f46; font-weight: bold; }
        .status-draft     { color: #6b7280; font-weight: bold; }
        .status-final     { color: #1d4ed8; font-weight: bold; }

        .row-total {
            font-weight: bold;
            background-color: #f1f2f6;
        }

        /* ── Chart Placeholder ── */
        .chart-placeholder {
            text-align: center;
            padding: 18px;
            border: 1px dashed #b2bec3;
            border-radius: 4px;
            color: #636e72;
            font-style: italic;
            font-size: 10px;
            margin-bottom: 20px;
        }

        /* ── Signature ── */
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
        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
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
            border-bottom: 1px solid #2d3436;
            line-height: 1.4;
        }
        .paren-left  { text-align: left;   width: 10px; }
        .paren-mid   { text-align: center; }
        .paren-right { text-align: right;  width: 10px; }

        /* ── Footer Note ── */
        .footer-note {
            clear: both;
            padding-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #b2bec3;
            border-top: 1px dashed #dfe6e9;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }
    </style>
</head>
<body>

    {{-- ══════════════════ HEADER ══════════════════ --}}
    <div class="header">
        <h1>{{ strtoupper($masjid->nama ?? 'LAPORAN TAHUNAN KEUANGAN MASJID') }}</h1>
        <h2>Laporan Keuangan Tahunan – Tahun {{ $tahun }}</h2>
        <div class="subtitle">
            {{ $masjid->alamat ?? '' }}
            @if(isset($masjid->kelurahan_nama) && $masjid->kelurahan_nama)
                , Kel. {{ $masjid->kelurahan_nama }}
            @endif
            @if(isset($masjid->kecamatan_nama) && $masjid->kecamatan_nama)
                , Kec. {{ $masjid->kecamatan_nama }}
            @endif
            @if(isset($masjid->kota_nama) && $masjid->kota_nama)
                , {{ $masjid->kota_nama }}
            @endif
        </div>
    </div>

    {{-- ══════════════════ INFO SECTION ══════════════════ --}}
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Tahun Laporan</div>
            <div class="info-value">: {{ $tahun }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Ringkasan Keuangan</div>
            <div class="info-value">:
                <span class="text-blue"><strong>Rp {{ number_format($laporanTahunan->first()->saldo_awal ?? 0, 0, ',', '.') }}</strong> Saldo Awal</span> |
                <span class="text-green"><strong>Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</strong> Total Penerimaan</span> |
                <span class="text-red"><strong>Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</strong> Total Penyaluran</span> |
                <strong>Rp {{ number_format($laporanTahunan->last()->saldo_akhir ?? 0, 0, ',', '.') }}</strong> Saldo Akhir
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Statistik Keseluruhan</div>
            <div class="info-value">:
                <strong>{{ $totalMuzakki }}</strong> Muzakki |
                <strong>{{ $totalMustahik }}</strong> Mustahik |
                <strong>{{ $laporanTahunan->count() }}</strong> Bulan Terlaporkan
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Cetak</div>
            <div class="info-value">: {{ \Carbon\Carbon::parse($tanggalCetak)->locale('id')->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- ══════════════════ SUMMARY CARDS ══════════════════ --}}
    <table class="summary-table">
        <tr>
            <td style="background-color: #d1fae5;">
                <div>Total Penerimaan</div>
                <div class="summary-value text-green">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</div>
                <div>{{ $totalMuzakki }} Muzakki</div>
            </td>
            <td style="background-color: #fee2e2;">
                <div>Total Penyaluran</div>
                <div class="summary-value text-red">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</div>
                <div>{{ $totalMustahik }} Mustahik</div>
            </td>
            <td style="background-color: #dbeafe;">
                <div>Saldo Awal Tahun</div>
                <div class="summary-value text-blue">Rp {{ number_format($laporanTahunan->first()->saldo_awal ?? 0, 0, ',', '.') }}</div>
                <div>&nbsp;</div>
            </td>
            <td style="background-color: #ede9fe;">
                <div>Saldo Akhir Tahun</div>
                <div class="summary-value" style="color: #5b21b6;">Rp {{ number_format($laporanTahunan->last()->saldo_akhir ?? 0, 0, ',', '.') }}</div>
                <div>&nbsp;</div>
            </td>
        </tr>
    </table>

    {{-- ══════════════════ TABEL UTAMA ══════════════════ --}}
    <h3 class="section-title">REKAPITULASI KEUANGAN PER BULAN</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 10%;">Bulan</th>
                <th colspan="3" style="width: 33%;">Saldo</th>
                <th colspan="2" style="width: 22%;">Penerimaan</th>
                <th colspan="2" style="width: 22%;">Penyaluran</th>
                <th rowspan="2" style="width: 9%;">Status</th>
            </tr>
            <tr>
                <th style="width: 11%;">Awal (Rp)</th>
                <th style="width: 11%;">Akhir (Rp)</th>
                <th style="width: 11%;">Bersih (Rp)</th>
                <th style="width: 11%;">Jumlah (Rp)</th>
                <th style="width: 6%;">Muzakki</th>
                <th style="width: 11%;">Jumlah (Rp)</th>
                <th style="width: 6%;">Mustahik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanTahunan as $laporan)
            @php $bersih = $laporan->saldo_akhir - $laporan->saldo_awal; @endphp
            <tr>
                <td class="text-left text-bold">{{ $laporan->nama_bulan }}</td>
                <td class="text-right">Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</td>
                <td class="text-right {{ $bersih >= 0 ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($bersih, 0, ',', '.') }}
                </td>
                <td class="text-right text-green">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</td>
                <td class="text-center">{{ $laporan->jumlah_muzakki }}</td>
                <td class="text-right text-red">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</td>
                <td class="text-center">{{ $laporan->jumlah_mustahik }}</td>
                <td class="text-center">
                    @if($laporan->status === 'published')
                        <span class="status-published">PUBLISHED</span>
                    @elseif($laporan->status === 'draft')
                        <span class="status-draft">DRAFT</span>
                    @else
                        <span class="status-final">FINAL</span>
                    @endif
                </td>
            </tr>
            @endforeach

            {{-- Total Row --}}
            <tr class="row-total">
                <td class="text-left">TOTAL</td>
                <td class="text-center">–</td>
                <td class="text-center">–</td>
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

    {{-- ══════════════════ ANALISIS TAHUNAN ══════════════════ --}}
    <h3 class="section-title">ANALISIS TAHUNAN</h3>

    @php
        $maxPenerimaan        = $laporanTahunan->max('total_penerimaan');
        $bulanMaxPenerimaan   = $laporanTahunan->where('total_penerimaan', $maxPenerimaan)->first();
        $maxPenyaluran        = $laporanTahunan->max('total_penyaluran');
        $bulanMaxPenyaluran   = $laporanTahunan->where('total_penyaluran', $maxPenyaluran)->first();
        $jumlahBulan          = $laporanTahunan->count() ?: 12;
    @endphp

    <table class="analysis-table">
        <tbody>
            <tr>
                <td style="width: 40%; background-color: #f1f2f6;"><strong>Rata-rata Penerimaan per Bulan</strong></td>
                <td>: Rp {{ number_format($totalPenerimaan / $jumlahBulan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="background-color: #f1f2f6;"><strong>Rata-rata Penyaluran per Bulan</strong></td>
                <td>: Rp {{ number_format($totalPenyaluran / $jumlahBulan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="background-color: #f1f2f6;"><strong>Bulan dengan Penerimaan Tertinggi</strong></td>
                <td>: {{ $bulanMaxPenerimaan->nama_bulan ?? '-' }} (Rp {{ number_format($maxPenerimaan, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td style="background-color: #f1f2f6;"><strong>Bulan dengan Penyaluran Tertinggi</strong></td>
                <td>: {{ $bulanMaxPenyaluran->nama_bulan ?? '-' }} (Rp {{ number_format($maxPenyaluran, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td style="background-color: #f1f2f6;"><strong>Persentase Penyaluran dari Penerimaan</strong></td>
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

    {{-- ══════════════════ TANDA TANGAN ══════════════════ --}}
    <div class="footer-container">
        <div class="signature-wrapper">
            <div>{{ $masjid->kota_nama ?? 'Bandung' }}, {{ \Carbon\Carbon::parse($tanggalCetak)->locale('id')->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 15px;">Yang Membuat Laporan,</div>
            <div class="signature-title">ADMIN MASJID</div>
            <div class="signature-space"></div>
            <table class="signature-line-table">
                <tr>
                    <td colspan="3" style="border: none; text-align: center; font-weight: bold; padding: 0; line-height: 1.3;">
                        {{ $namaAdmin ?? '' }}
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