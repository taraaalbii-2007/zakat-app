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

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

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

        /* Fidyah Styles */
        .fidyah-info {
            background-color: #fff3e0;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 8px;
            margin-top: 2px;
        }

        .fidyah-badge {
            background-color: #ff9800;
            color: white;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ strtoupper($masjid->nama ?? 'LAPORAN TRANSAKSI PENERIMAAN ZAKAT') }}</h1>
        <h2>Laporan Detail Transaksi Penerimaan Zakat</h2>
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
            <div class="info-value">: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }},
                {{ \Carbon\Carbon::now()->format('H:i') }} WIB</div>
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
                        $appliedFilters[] =
                            'Periode: ' .
                            \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') .
                            ' - ' .
                            \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y');
                    }

                    if (!empty($filters['jenis_zakat_id'])) {
                        $jenis = $jenisZakatList->firstWhere('id', $filters['jenis_zakat_id']);
                        $appliedFilters[] = 'Jenis: ' . ($jenis->nama ?? 'Zakat');
                    }

                    if (!empty($filters['metode_pembayaran'])) {
                        $appliedFilters[] = 'Metode Bayar: ' . ucfirst($filters['metode_pembayaran']);
                    }

                    if (!empty($filters['status'])) {
                        $statusText = match ($filters['status']) {
                            'verified' => 'Terverifikasi',
                            'pending' => 'Menunggu',
                            'rejected' => 'Ditolak',
                            default => $filters['status'],
                        };
                        $appliedFilters[] = 'Status: ' . $statusText;
                    }

                    if (!empty($filters['metode_penerimaan'])) {
                        $penerimaanText = match($filters['metode_penerimaan']) {
                            'datang_langsung' => 'Datang Langsung',
                            'dijemput' => 'Dijemput',
                            'daring' => 'Daring',
                            default => $filters['metode_penerimaan'],
                        };
                        $appliedFilters[] = 'Penerimaan: ' . $penerimaanText;
                    }

                    if (!empty($filters['fidyah_tipe'])) {
                        $fidyahText = match($filters['fidyah_tipe']) {
                            'mentah' => 'Fidyah Bahan Mentah',
                            'matang' => 'Fidyah Makanan Matang',
                            'tunai' => 'Fidyah Tunai',
                            default => 'Fidyah',
                        };
                        $appliedFilters[] = $fidyahText;
                    }
                @endphp

                @if (count($appliedFilters) > 0)
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
                <span style="color: #f57f17;">{{ $totalPending }} Menunggu</span> |
                <strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong> |
                <span style="color: #e67e22;">Infaq: Rp {{ number_format($totalInfaq, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $user->name ?? ($user->username ?? 'System') }}</div>
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
                <th rowspan="2" style="width: 70px;">Jumlah Jiwa / Fidyah</th>
                <th colspan="3">Pembayaran</th>
                <th colspan="2">Status</th>
                <th rowspan="2">Amil</th>
            </tr>
            <tr>
                <th>Jenis</th>
                <th>Tipe</th>
                <th>Program</th>
                <th style="width: 70px;">Jumlah (Rp)</th>
                <th>Metode</th>
                <th style="width: 60px;">Infaq (Rp)</th>
                <th style="width: 60px;">Total Dibayar</th>
                <th>Verif</th>
                <th>Konfirmasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $transaksi)
                @php
                    $statusText = match ($transaksi->status) {
                        'verified' => 'Verified',
                        'pending' => 'Pending',
                        'rejected' => 'Rejected',
                        default => strtoupper($transaksi->status),
                    };

                    $konfirmasiStatusText = match ($transaksi->konfirmasi_status) {
                        'dikonfirmasi' => 'Dikonfirmasi',
                        'menunggu_konfirmasi' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                        default => '-',
                    };

                    // Deteksi apakah ini transaksi fidyah
                    $isFidyah = $transaksi->fidyah_jumlah_hari > 0 && $transaksi->jenisZakat && stripos($transaksi->jenisZakat->nama, 'fidyah') !== false;

                    // Bangun teks untuk kolom Jumlah Jiwa / Fidyah
                    $detailText = '-';

                    if ($isFidyah) {
                        $fidyahTipe = $transaksi->fidyah_tipe ?? '-';
                        $jumlahHari = $transaksi->fidyah_jumlah_hari ?? 0;

                        $detailText = "FIDYAH: {$jumlahHari} hari";

                        if ($fidyahTipe == 'mentah') {
                            $beratKg = $transaksi->fidyah_total_berat_kg ?? 0;
                            $bahan = $transaksi->fidyah_nama_bahan ?? 'Bahan Pokok';
                            $detailText .= "\n{$bahan}: {$beratKg} kg";
                        } elseif ($fidyahTipe == 'matang') {
                            $box = $transaksi->fidyah_jumlah_box ?? $jumlahHari;
                            $menu = $transaksi->fidyah_menu_makanan ?: 'Makanan';
                            $detailText .= "\n{$menu}: {$box} box";
                        } elseif ($fidyahTipe == 'tunai') {
                            $total = $transaksi->jumlah ?? 0;
                            $detailText .= "\nRp " . number_format($total, 0, ',', '.');
                        }
                    } elseif ($transaksi->jumlah_beras_kg > 0) {
                        // Zakat fitrah beras
                        $detailText = $transaksi->jumlah_beras_kg . ' kg';
                        if ($transaksi->jumlah_jiwa > 0) {
                            $detailText .= ' (' . $transaksi->jumlah_jiwa . ' jiwa)';
                        }
                    } elseif ($transaksi->jumlah_jiwa > 0) {
                        // Zakat fitrah uang — tampilkan jumlah jiwa + nama-nama
                        $namaJiwa = $transaksi->nama_jiwa_json; // sudah di-cast array otomatis

                        if (!empty($namaJiwa) && is_array($namaJiwa)) {
                            // Ada nama jiwa tersimpan
                            $detailText = $transaksi->jumlah_jiwa . ' jiwa:' . "\n";
                            $limit = min(count($namaJiwa), 3); // Tampilkan maksimal 3 nama
                            for ($i = 0; $i < $limit; $i++) {
                                $detailText .= ($i + 1) . '. ' . $namaJiwa[$i] . "\n";
                            }
                            if (count($namaJiwa) > 3) {
                                $detailText .= '...dan ' . (count($namaJiwa) - 3) . ' lainnya';
                            }
                        } else {
                            // Tidak ada nama jiwa, tampilkan jumlah saja
                            $detailText = $transaksi->jumlah_jiwa . ' jiwa';
                        }
                    }

                    // Format total dibayar
                    $totalDibayarText = '-';
                    if ($transaksi->jumlah_dibayar > 0) {
                        $totalDibayarText = number_format($transaksi->jumlah_dibayar, 0, ',', '.');
                    } elseif ($transaksi->jumlah_beras_kg > 0) {
                        $totalDibayarText = $transaksi->jumlah_beras_kg . ' kg';
                    } elseif ($isFidyah && $transaksi->fidyah_tipe != 'tunai') {
                        if ($transaksi->fidyah_tipe == 'mentah') {
                            $totalDibayarText = ($transaksi->fidyah_total_berat_kg ?? 0) . ' kg';
                        } elseif ($transaksi->fidyah_tipe == 'matang') {
                            $totalDibayarText = ($transaksi->fidyah_jumlah_box ?? 0) . ' box';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 8px;">{{ $transaksi->no_transaksi }}</td>
                    <td class="text-center">{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td class="text-left">
                        <strong>{{ $transaksi->muzakki_nama }}</strong>
                        @if($transaksi->diinput_muzakki)
                            <span style="color: #27ae60; font-size: 7px;">(Online)</span>
                        @endif
                    </td>
                    <td class="text-left">
                        {{ $transaksi->jenisZakat->nama ?? '-' }}
                    </td>
                    <td class="text-left">{{ $transaksi->tipeZakat->nama ?? '-' }}</td>
                    <td class="text-left" style="font-size: 8px;">
                        {{ \Illuminate\Support\Str::limit($transaksi->programZakat->nama_program ?? '-', 20) }}
                    </td>
                    <td class="text-right">
                        {{ $transaksi->jumlah > 0 ? number_format($transaksi->jumlah, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-left" style="font-size: 8px; white-space: pre-wrap;">{{ $detailText }}</td>
                    <td class="text-center">
                        @php
                            $metode = $transaksi->metode_pembayaran;
                            $metodeText = match($metode) {
                                'bahan_mentah' => 'Bahan Mentah',
                                'makanan_matang' => 'Makanan Matang',
                                default => $metode ? ucfirst($metode) : '-',
                            };
                        @endphp
                        {{ $metodeText }}
                    </td>
                    <td class="text-right">
                        {{ $transaksi->jumlah_infaq > 0 ? number_format($transaksi->jumlah_infaq, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right">{{ $totalDibayarText }}</td>
                    <td class="text-center">{{ $statusText }}</td>
                    <td class="text-center">{{ $konfirmasiStatusText }}</td>
                    <td class="text-left">
                        {{ $transaksi->amil->pengguna->name ?? ($transaksi->amil->nama_lengkap ?? '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center" style="padding: 30px; color: #b2bec3;">
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
                        <div style="margin-bottom: 5px;">{{ $masjid->kota_nama ?? 'Bandung' }},
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
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
        <p>*Zakat Fitrah: Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'] ?? 50000, 0, ',', '.') }}/jiwa atau {{ ($zakatFitrahInfo['beras_kg'] ?? 2.5) }} kg ({{ ($zakatFitrahInfo['beras_liter'] ?? 3.5) }} liter) beras.</p>
        <p>*Fidyah: 1 mud = {{ ($fidyahInfo['berat_per_hari_gram'] ?? 675) }} gram bahan pokok per hari, atau makanan siap santap, atau uang senilai makanan.</p>
        <p>Pembayaran melalui transfer atau QRIS dilakukan langsung ke rekening resmi masjid. Muzzaki mengunggah bukti transfer untuk dikonfirmasi oleh amil. Tidak ada potongan biaya admin/pajak dari sistem.</p>
        <p>Dicetak pada: {{ $tanggalExport }}</p>
    </div>
</body>

</html>