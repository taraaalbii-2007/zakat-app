<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Penyaluran Zakat</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #2d3436;
            margin: 12px 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2.5px solid #2d3436;
            padding-bottom: 8px;
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

        /* Info section */
        .info-section {
            margin-bottom: 10px;
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row   { display: table-row; }
        .info-label {
            display: table-cell;
            width: 140px;
            padding: 2px 0;
            font-weight: bold;
            color: #2d3436;
        }
        .info-value {
            display: table-cell;
            padding: 2px 0;
            border-bottom: 1px solid #f1f2f6;
        }

        /* Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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
            border: 1px solid #dee2e6;
            padding: 6px 4px;
            word-wrap: break-word;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8f9fa;
        }
        table.data-table tr { page-break-inside: avoid; }

        .text-left   { text-align: left; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        /* Status — no color, plain text */

        /* Summary row */
        .summary-row td {
            background-color: #e8f5e9 !important;
            font-weight: bold;
            border-top: 2px solid #1a7a4a;
        }

        /* Rincian row */
        .rincian-row td {
            background-color: #fafafa !important;
            font-size: 8px;
            color: #636e72;
            border-top: none;
        }

        /* Footer */
        .footer-container {
            margin-top: 10px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table { width: 100%; border: none; }
        .signature-table td { border: none !important; padding: 0; vertical-align: top; }
        .signature-wrapper { width: 200px; text-align: center; }
        .signature-space   { height: 35px; }
        .signature-name    { font-weight: bold; text-decoration: underline; }
        .footer-note {
            clear: both;
            padding-top: 6px;
            text-align: center;
            font-size: 8px;
            color: #b2bec3;
            border-top: 1px dashed #dfe6e9;
        }
    </style>
</head>
<body>

    {{-- ── Header ── --}}
    <div class="header">
        <h1>{{ strtoupper($masjid->nama ?? 'LAPORAN TRANSAKSI PENYALURAN ZAKAT') }}</h1>
        <h2>Laporan Transaksi Penyaluran</h2>
        <div class="subtitle">
            {{ $masjid->alamat ?? '' }}
            {{ $masjid->kelurahan_nama ? ', Kel. ' . $masjid->kelurahan_nama : '' }}
            {{ $masjid->kecamatan_nama ? ', Kec. ' . $masjid->kecamatan_nama : '' }}
            {{ $masjid->kota_nama      ? ', ' . $masjid->kota_nama            : '' }}
        </div>
    </div>

    {{-- ── Info ── --}}
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
                    if (!empty($filters['q'])) {
                        $appliedFilters[] = "Pencarian: '" . $filters['q'] . "'";
                    }
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $appliedFilters[] = "Periode: " . \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y')
                            . " - " . \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y');
                    } elseif (!empty($filters['start_date'])) {
                        $appliedFilters[] = "Dari: " . \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y');
                    } elseif (!empty($filters['end_date'])) {
                        $appliedFilters[] = "Sampai: " . \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y');
                    }
                    if (!empty($filters['status'])) {
                        $statusLabel = match($filters['status']) {
                            'draft'      => 'Draft',
                            'disetujui'  => 'Disetujui',
                            'disalurkan' => 'Disalurkan',
                            'dibatalkan' => 'Dibatalkan',
                            default      => $filters['status'],
                        };
                        $appliedFilters[] = "Status: " . $statusLabel;
                    }
                    if (!empty($filters['metode_penyaluran'])) {
                        $appliedFilters[] = "Metode: " . ucfirst($filters['metode_penyaluran']);
                    }
                    if (!empty($filters['jenis_zakat_id'])) {
                        $jenis = $jenisZakatList->firstWhere('id', $filters['jenis_zakat_id']);
                        $appliedFilters[] = "Jenis Zakat: " . ($jenis->nama ?? '-');
                    }
                    if (!empty($filters['periode'])) {
                        $appliedFilters[] = "Periode: " . $filters['periode'];
                    }
                @endphp
                {{ count($appliedFilters) ? implode(' | ', $appliedFilters) : 'Semua Data' }}
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Ringkasan Data</div>
            <div class="info-value">:
                <strong>{{ number_format($totalTransaksi, 0, ',', '.') }}</strong> Total |
                <span>{{ $totalDisalurkan }} Disalurkan</span> |
                <span>{{ $totalDiSetujui }} Disetujui</span> |
                <span>{{ $totalDraft }} Draft</span> |
                <span>{{ $totalDibatalkan }} Dibatalkan</span> |
                <strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $user->name ?? $user->username ?? 'System' }}</div>
        </div>
    </div>

    {{-- ── Tabel Data ── --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 22px;">No</th>
                <th style="width: 80px;">No. Transaksi</th>
                <th style="width: 55px;">Tanggal</th>
                <th style="width: 110px;">Mustahik</th>
                <th style="width: 75px;">Kategori</th>
                <th style="width: 70px;">Jenis Zakat</th>
                <th style="width: 65px;">Program</th>
                <th style="width: 75px;">Jumlah (Rp)</th>
                <th style="width: 48px;">Metode</th>
                <th style="width: 55px;">Status</th>
                <th>Amil</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($transaksis as $index => $transaksi)
                @php
                    $nominal = $transaksi->metode_penyaluran === 'barang'
                        ? ($transaksi->nilai_barang ?? 0)
                        : ($transaksi->jumlah ?? 0);
                    $grandTotal += in_array($transaksi->status, ['disetujui','disalurkan']) ? $nominal : 0;

                    $statusText = match($transaksi->status) {
                        'draft'      => 'Draft',
                        'disetujui'  => 'Disetujui',
                        'disalurkan' => 'Disalurkan',
                        'dibatalkan' => 'Dibatalkan',
                        default      => $transaksi->status,
                    };
                    $statusClass = '';

                    $metodeText = match($transaksi->metode_penyaluran) {
                        'tunai'    => 'Tunai',
                        'transfer' => 'Transfer',
                        'barang'   => 'Barang',
                        default    => '-',
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 8px;">{{ $transaksi->no_transaksi }}</td>
                    <td class="text-center">{{ $transaksi->tanggal_penyaluran->format('d/m/Y') }}</td>
                    <td class="text-left"><strong>{{ $transaksi->mustahik->nama_lengkap ?? '-' }}</strong></td>
                    <td class="text-left">{{ $transaksi->kategoriMustahik->nama ?? '-' }}</td>
                    <td class="text-left">{{ $transaksi->jenisZakat->nama ?? '-' }}</td>
                    <td class="text-left" style="font-size: 8px;">
                        {{ \Illuminate\Support\Str::limit($transaksi->programZakat->nama_program ?? '-', 18) }}
                    </td>
                    <td class="text-right">
                        @if($transaksi->metode_penyaluran === 'barang')
                            @if($transaksi->nilai_barang)
                                {{ number_format($transaksi->nilai_barang, 0, ',', '.') }}
                                <br><span style="font-size:7px;color:#636e72;">(Nilai Barang)</span>
                            @else
                                <em style="color:#636e72; font-size:8px;">Barang</em>
                            @endif
                        @else
                            {{ $nominal > 0 ? number_format($nominal, 0, ',', '.') : '-' }}
                        @endif
                    </td>
                    <td class="text-center">{{ $metodeText }}</td>
                    <td class="text-center">{{ $statusText }}</td>
                    <td class="text-left">
                        {{ $transaksi->amil->pengguna->name ?? $transaksi->amil->nama_lengkap ?? '-' }}
                    </td>
                </tr>

                {{-- Rincian tambahan bila ada detail barang / keterangan --}}
                @php
                    $adaRincian = $transaksi->detail_barang || $transaksi->keterangan;
                    $rincianParts = [];
                    if ($transaksi->detail_barang) $rincianParts[] = 'Barang: ' . $transaksi->detail_barang;
                    if ($transaksi->keterangan)    $rincianParts[] = 'Ket: ' . $transaksi->keterangan;
                    $rincianText = implode(' | ', $rincianParts);
                @endphp
                @if($adaRincian)
                <tr class="rincian-row">
                    <td colspan="11" style="padding: 3px 8px; border-top: none;">
                        <strong style="color:#2d3436;">Rincian:</strong>
                        {{ $rincianText }}
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 30px; color: #b2bec3;">
                        <em>Data tidak ditemukan untuk kriteria filter ini.</em>
                    </td>
                </tr>
            @endforelse

            {{-- Total --}}
            @if($transaksis->count() > 0)
            <tr class="summary-row">
                <td colspan="7" class="text-right" style="padding: 6px 4px;">
                    Total Nominal (Disetujui &amp; Disalurkan):
                </td>
                <td class="text-right">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- ── Tanda Tangan ── --}}
    <div class="footer-container">
        <table class="signature-table">
            <tr>
                <td style="width: 70%;"></td>
                <td style="width: 30%;">
                    <div class="signature-wrapper">
                        <div style="margin-bottom:5px;">
                            {{ $masjid->kota_nama ?? 'Kota' }},
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                        </div>
                        <div>Mengetahui,</div>
                        <div style="margin-bottom:10px;"><strong>Admin Masjid</strong></div>
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