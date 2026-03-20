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

        /* ── Ringkasan Stats Box ── */
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .stats-grid td {
            border: 1px solid #dfe6e9;
            padding: 6px 10px;
            vertical-align: top;
            width: 25%;
        }
        .stats-label {
            font-size: 8.5px;
            color: #636e72;
            margin-bottom: 2px;
        }
        .stats-value {
            font-size: 13px;
            font-weight: bold;
            color: #2d3436;
        }
        .stats-value.green  { color: #1a7a4a; }
        .stats-value.amber  { color: #b8621a; }
        .stats-value.blue   { color: #01579b; }
        .stats-value.purple { color: #6c3483; }
        .stats-value.orange { color: #e67e22; }
        .stats-sub {
            font-size: 8px;
            color: #636e72;
            margin-top: 2px;
        }

        /* ── Main Data Table ── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
            table-layout: fixed;
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
            border: 1px solid #c8d6cb;
            padding: 5px 4px;
            word-wrap: break-word;
        }
        table.data-table tr {
            page-break-inside: avoid;
        }
        /* Zebra stripe */
        table.data-table tbody tr:nth-child(even) {
            background-color: #f8fffe;
        }
        /* Baris total footer tabel */
        table.data-table tfoot tr {
            background-color: #e8f5e9;
            font-weight: bold;
        }
        table.data-table tfoot td {
            border-top: 2px solid #1a7a4a;
            border-bottom: 2px solid #1a7a4a;
        }

        .text-left   { text-align: left; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        /* ── Badge-style pill ── */
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            line-height: 1.4;
        }
        .badge-green   { background: #d4edda; color: #155724; }
        .badge-yellow  { background: #fff3cd; color: #856404; }
        .badge-red     { background: #f8d7da; color: #721c24; }
        .badge-blue    { background: #d1ecf1; color: #0c5460; }
        .badge-purple  { background: #e2d9f3; color: #4a235a; }
        .badge-amber   { background: #fff3e0; color: #7c4a00; }
        .badge-orange  { background: #ffe0b2; color: #6d3400; }
        .badge-gray    { background: #e9ecef; color: #383d41; }

        /* ── Metode penerimaan color strip ── */
        .metode-dl  { border-left: 3px solid #0288d1; padding-left: 3px; }
        .metode-djm { border-left: 3px solid #f9a825; padding-left: 3px; }
        .metode-dar { border-left: 3px solid #7b1fa2; padding-left: 3px; }

        /* ── Fidyah Styles ── */
        .fidyah-info {
            background-color: #fff3e0;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 8px;
            margin-top: 2px;
        }

        /* ── Beras info ── */
        .beras-info {
            background-color: #fffde7;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 8px;
            margin-top: 2px;
        }

        /* ── Section divider ── */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1a7a4a;
            border-bottom: 1px solid #1a7a4a;
            padding-bottom: 3px;
            margin-bottom: 10px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Footer & Signature ── */
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
        .paren-left  { text-align: left;  width: 10px; }
        .paren-mid   { text-align: center; }
        .paren-right { text-align: right; width: 10px; }

        /* ── Footer note ── */
        .footer-note {
            clear: both;
            padding-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #b2bec3;
            border-top: 1px dashed #dfe6e9;
        }

        /* ── Page numbers (DomPDF) ── */
        .page-number:before {
            content: "Halaman " counter(page) " dari " counter(pages);
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }
    </style>
</head>

<body>

    {{-- ══════════ HEADER ══════════ --}}
    <div class="header">
        <h1>{{ strtoupper($lembaga->nama ?? 'LAPORAN TRANSAKSI PENERIMAAN ZAKAT') }}</h1>
        <h2>Laporan Detail Transaksi Penerimaan Zakat</h2>
        <div class="subtitle">
            {{ $lembaga->alamat ?? '' }}
            {{ $lembaga->kelurahan_nama ? ', Kel. ' . $lembaga->kelurahan_nama : '' }}
            {{ $lembaga->kecamatan_nama ? ', Kec. ' . $lembaga->kecamatan_nama : '' }}
            {{ $lembaga->kota_nama ? ', ' . $lembaga->kota_nama : '' }}
        </div>
    </div>

    {{-- ══════════ INFO LAPORAN ══════════ --}}
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
                        $metBayarText = match($filters['metode_pembayaran']) {
                            'bahan_mentah'   => 'Bahan Mentah',
                            'makanan_matang' => 'Makanan Matang',
                            default          => ucfirst($filters['metode_pembayaran']),
                        };
                        $appliedFilters[] = 'Metode Bayar: ' . $metBayarText;
                    }
                    if (!empty($filters['status'])) {
                        $statusText = match ($filters['status']) {
                            'verified' => 'Terverifikasi',
                            'pending'  => 'Menunggu',
                            'rejected' => 'Ditolak',
                            default    => $filters['status'],
                        };
                        $appliedFilters[] = 'Status: ' . $statusText;
                    }
                    if (!empty($filters['metode_penerimaan'])) {
                        $penerimaanText = match($filters['metode_penerimaan']) {
                            'datang_langsung' => 'Datang Langsung',
                            'dijemput'        => 'Dijemput',
                            'daring'          => 'Daring',
                            default           => $filters['metode_penerimaan'],
                        };
                        $appliedFilters[] = 'Penerimaan: ' . $penerimaanText;
                    }
                    if (!empty($filters['fidyah_tipe'])) {
                        $fidyahText = match($filters['fidyah_tipe']) {
                            'mentah' => 'Fidyah Bahan Mentah',
                            'matang' => 'Fidyah Makanan Matang',
                            'tunai'  => 'Fidyah Tunai',
                            default  => 'Fidyah',
                        };
                        $appliedFilters[] = $fidyahText;
                    }

                    // ── Hitung agregat untuk stats ──────────────────────────
                    $totalBerasKg       = $transaksis->where('metode_pembayaran', 'beras')->where('status', 'verified')->sum('jumlah_beras_kg');
                    $totalTransaksiBeras = $transaksis->where('metode_pembayaran', 'beras')->where('status', 'verified')->count();
                    $totalFidyahMentah  = $transaksis->where('fidyah_tipe', 'mentah')->count();
                    $totalFidyahMatang  = $transaksis->where('fidyah_tipe', 'matang')->count();
                    $totalFidyahTunai   = $transaksis->where('fidyah_tipe', 'tunai')->count();
                    $totalFidyah        = $totalFidyahMentah + $totalFidyahMatang + $totalFidyahTunai;

                    $totalDatangLangsung = $transaksis->where('metode_penerimaan', 'datang_langsung')->count();
                    $totalDijemput       = $transaksis->where('metode_penerimaan', 'dijemput')->count();
                    $totalDaring         = $transaksis->where('metode_penerimaan', 'daring')->count();

                    $totalInfaq          = $transaksis->where('status', 'verified')->sum('jumlah_infaq');
                    $totalNominalVerif   = $transaksis->where('status', 'verified')->sum('jumlah');
                    $menungguKonfirmasi  = $transaksis->where('konfirmasi_status', 'menunggu_konfirmasi')->count();
                @endphp
                @if (count($appliedFilters) > 0)
                    {{ implode(' | ', $appliedFilters) }}
                @else
                    Semua Data
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $user->name ?? ($user->username ?? 'System') }}
                @if (!empty($amil))
                    &nbsp;<span style="color:#636e72;">(Amil: {{ $amil->nama_lengkap ?? '-' }})</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════ STATS CARDS ══════════ --}}
    <div class="section-title">Ringkasan Data</div>

    {{-- Baris 1: Statistik Utama --}}
    <table class="stats-grid">
        <tr>
            <td>
                <div class="stats-label">Total Transaksi</div>
                <div class="stats-value">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
                <div class="stats-sub">
                    Verified: {{ $totalVerified }} &nbsp;|&nbsp;
                    Pending: {{ $totalPending }}
                    @if ($menungguKonfirmasi > 0)
                        &nbsp;|&nbsp; Konfirmasi: {{ $menungguKonfirmasi }}
                    @endif
                </div>
            </td>
            <td>
                <div class="stats-label">Total Nominal (Verified)</div>
                <div class="stats-value green">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
                @if ($totalInfaq > 0)
                    <div class="stats-sub">+ Infaq: Rp {{ number_format($totalInfaq, 0, ',', '.') }}</div>
                @endif
            </td>
            <td>
                <div class="stats-label">Metode Penerimaan</div>
                <div class="stats-sub" style="font-size:9px; margin-top:3px;">
                    @if ($totalDatangLangsung > 0)
                        <span class="badge badge-blue">Datang Langsung: {{ $totalDatangLangsung }}</span>&nbsp;
                    @endif
                    @if ($totalDijemput > 0)
                        <span class="badge badge-amber">Dijemput: {{ $totalDijemput }}</span>&nbsp;
                    @endif
                    @if ($totalDaring > 0)
                        <span class="badge badge-purple">Daring: {{ $totalDaring }}</span>
                    @endif
                </div>
            </td>
            <td>
                <div class="stats-label">Pembayaran Non-Tunai</div>
                @php
                    $totalTransfer = $transaksis->where('metode_pembayaran', 'transfer')->count();
                    $totalQris     = $transaksis->where('metode_pembayaran', 'qris')->count();
                    $totalTunai    = $transaksis->where('metode_pembayaran', 'tunai')->count();
                @endphp
                <div class="stats-sub" style="font-size:9px; margin-top:3px;">
                    @if ($totalTunai > 0)
                        Tunai: <strong>{{ $totalTunai }}</strong>&nbsp;
                    @endif
                    @if ($totalTransfer > 0)
                        Transfer: <strong>{{ $totalTransfer }}</strong>&nbsp;
                    @endif
                    @if ($totalQris > 0)
                        QRIS: <strong>{{ $totalQris }}</strong>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Baris 2: Beras & Fidyah (hanya tampil jika ada data) --}}
    @if ($totalBerasKg > 0 || $totalFidyah > 0)
        <table class="stats-grid" style="margin-bottom: 18px;">
            <tr>
                @if ($totalBerasKg > 0)
                    <td>
                        <div class="stats-label">Total Beras Diterima</div>
                        <div class="stats-value amber">{{ number_format($totalBerasKg, 1, ',', '.') }} kg</div>
                        <div class="stats-sub">
                            dari {{ $totalTransaksiBeras }} transaksi &nbsp;|&nbsp;
                            ≈ {{ number_format($totalBerasKg > 0 ? floor($totalBerasKg / 2.5) : 0) }} jiwa (@ 2,5 kg/jiwa BAZNAS)
                        </div>
                    </td>
                @endif
                @if ($totalFidyah > 0)
                    <td colspan="{{ $totalBerasKg > 0 ? 1 : 2 }}">
                        <div class="stats-label">Transaksi Fidyah</div>
                        <div class="stats-value orange">{{ $totalFidyah }}</div>
                        <div class="stats-sub">
                            @if ($totalFidyahMentah > 0)
                                Bahan Mentah: <strong>{{ $totalFidyahMentah }}</strong>&nbsp;
                            @endif
                            @if ($totalFidyahMatang > 0)
                                Makanan Matang: <strong>{{ $totalFidyahMatang }}</strong>&nbsp;
                            @endif
                            @if ($totalFidyahTunai > 0)
                                Tunai: <strong>{{ $totalFidyahTunai }}</strong>
                            @endif
                        </div>
                    </td>
                @endif
                {{-- Kolom kosong untuk padding --}}
                @php
                    $filledCols = ($totalBerasKg > 0 ? 1 : 0) + ($totalFidyah > 0 ? 1 : 0);
                    $emptyCols  = 4 - $filledCols;
                @endphp
                @for ($i = 0; $i < $emptyCols; $i++)
                    <td style="background: #fafafa;"></td>
                @endfor
            </tr>
        </table>
    @endif

    {{-- ══════════ TABEL DATA UTAMA ══════════ --}}
    <div class="section-title">Detail Transaksi</div>

    {{--
        Distribusi 13 kolom — landscape A4 (267mm area cetak):
        No(8) | No.Trx(68) | Tgl(40) | Muzakki(85) | Penerimaan(46)
        | Jenis(52) | Tipe(48) | Program(52) | Nominal(62)
        | Jiwa/Fidyah/Beras(72) | Metode(42) | Infaq(54) | TotalDibayar(62) | Amil(52)
        ≈ 743px total, muat nyaman di landscape
    --}}
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width:8px;">No</th>
                <th rowspan="2" style="width:68px;">No. Transaksi</th>
                <th rowspan="2" style="width:40px;">Tanggal</th>
                <th rowspan="2" style="width:85px;">Muzakki</th>
                <th rowspan="2" style="width:46px;">Penerimaan</th>
                <th colspan="4">Detail Zakat</th>
                <th rowspan="2" style="width:72px;">Jiwa / Fidyah / Beras</th>
                <th colspan="3">Pembayaran</th>
                <th rowspan="2" style="width:52px;">Amil</th>
            </tr>
            <tr>
                <th style="width:52px;">Jenis</th>
                <th style="width:48px;">Tipe</th>
                <th style="width:52px;">Program</th>
                <th style="width:62px;">Nominal (Rp)</th>
                <th style="width:42px;">Metode</th>
                <th style="width:54px;">Infaq (Rp)</th>
                <th style="width:62px;">Total Dibayar</th>
            </tr>
        </thead>

        <tbody>
            @php
                // Akumulator untuk baris total tabel
                $grandJumlah       = 0;
                $grandInfaq        = 0;
                $grandDibayar      = 0;
                $grandBerasKg      = 0;
            @endphp

            @forelse($transaksis as $index => $transaksi)
                @php
                    // ── Metode penerimaan ──────────────────────────────────
                    $metodePenerimaanText = match ($transaksi->metode_penerimaan) {
                        'datang_langsung' => 'Datang Langsung',
                        'dijemput'        => 'Dijemput',
                        'daring'          => 'Daring',
                        default           => ucfirst($transaksi->metode_penerimaan ?? '-'),
                    };
                    $metodeClass = match ($transaksi->metode_penerimaan) {
                        'datang_langsung' => 'badge-blue',
                        'dijemput'        => 'badge-amber',
                        'daring'          => 'badge-purple',
                        default           => 'badge-gray',
                    };

                    // ── Deteksi fidyah ─────────────────────────────────────
                    $isFidyah = !empty($transaksi->fidyah_tipe)
                        && $transaksi->fidyah_jumlah_hari > 0;

                    // ── Kolom "Jiwa / Fidyah / Beras" ─────────────────────
                    $detailText  = '-';
                    $detailExtra = '';

                    if ($isFidyah) {
                        $fidyahTipe  = $transaksi->fidyah_tipe;
                        $jumlahHari  = $transaksi->fidyah_jumlah_hari;
                        $detailText  = $jumlahHari . ' hari';

                        if ($fidyahTipe === 'mentah') {
                            $beratKg     = $transaksi->fidyah_total_berat_kg ?? 0;
                            $bahan       = $transaksi->fidyah_nama_bahan ?? 'Bahan Pokok';
                            $detailExtra = "{$bahan}: {$beratKg} kg";
                        } elseif ($fidyahTipe === 'matang') {
                            $box         = $transaksi->fidyah_jumlah_box ?? $jumlahHari;
                            $menu        = $transaksi->fidyah_menu_makanan ?: 'Makanan';
                            $detailExtra = "{$menu}: {$box} box";
                            if ($transaksi->fidyah_cara_serah) {
                                $serahLabel  = match ($transaksi->fidyah_cara_serah) {
                                    'dibagikan'   => 'Dibagikan',
                                    'dijamu'      => 'Dijamu',
                                    'via_lembaga' => 'Via Lembaga',
                                    default       => $transaksi->fidyah_cara_serah,
                                };
                                $detailExtra .= ' (' . $serahLabel . ')';
                            }
                        } elseif ($fidyahTipe === 'tunai') {
                            $detailExtra = 'Rp ' . number_format($transaksi->jumlah ?? 0, 0, ',', '.');
                        }

                        $fidyahBadge = match ($fidyahTipe) {
                            'mentah' => '<span class="badge badge-amber">Mentah</span>',
                            'matang' => '<span class="badge badge-orange">Matang</span>',
                            'tunai'  => '<span class="badge badge-green">Tunai</span>',
                            default  => '',
                        };
                    } elseif ($transaksi->jumlah_beras_kg > 0) {
                        // Zakat fitrah BERAS — tampilkan jiwa sebagai info utama, kg sebagai info tambahan
                        $namaJiwa   = $transaksi->nama_jiwa_json;
                        $jmlJiwa    = $transaksi->jumlah_jiwa > 0
                                        ? $transaksi->jumlah_jiwa
                                        : ($transaksi->jumlah_beras_kg > 0
                                            ? (int) ceil($transaksi->jumlah_beras_kg / 2.5)
                                            : 0);

                        // Tampilkan jumlah jiwa sebagai teks utama
                        $detailText  = $jmlJiwa . ' jiwa';
                        // Tambahkan berat beras sebagai info tambahan
                        $detailExtra = $transaksi->jumlah_beras_kg . ' kg beras';
                        if ($transaksi->harga_beras_per_kg > 0) {
                            $detailExtra .= ' @ Rp' . number_format($transaksi->harga_beras_per_kg, 0, ',', '.');
                        }

                        // Tampilkan nama jiwa jika ada
                        if (!empty($namaJiwa) && is_array($namaJiwa)) {
                            $limit       = min(count($namaJiwa), 3);
                            $namaPreview = [];
                            for ($i = 0; $i < $limit; $i++) {
                                $namaPreview[] = ($i + 1) . '. ' . $namaJiwa[$i];
                            }
                            if (count($namaJiwa) > 3) {
                                $namaPreview[] = '+' . (count($namaJiwa) - 3) . ' lainnya';
                            }
                            $detailExtra .= ' | ' . implode(', ', $namaPreview);
                        }
                    } elseif ($transaksi->jumlah_jiwa > 0) {
                        // Zakat fitrah TUNAI
                        $namaJiwa    = $transaksi->nama_jiwa_json;
                        $detailText  = $transaksi->jumlah_jiwa . ' jiwa';
                        if ($transaksi->nominal_per_jiwa > 0) {
                            $detailExtra = '@ Rp ' . number_format($transaksi->nominal_per_jiwa, 0, ',', '.');
                        }
                        if (!empty($namaJiwa) && is_array($namaJiwa)) {
                            $limit       = min(count($namaJiwa), 3);
                            $namaPreview = [];
                            for ($i = 0; $i < $limit; $i++) {
                                $namaPreview[] = ($i + 1) . '. ' . $namaJiwa[$i];
                            }
                            if (count($namaJiwa) > 3) {
                                $namaPreview[] = '+' . (count($namaJiwa) - 3) . ' lainnya';
                            }
                            $detailExtra = implode(', ', $namaPreview);
                        }
                    }

                    // ── Kolom Total Dibayar ────────────────────────────────
                    $totalDibayarText = '-';
                    if ($transaksi->jumlah_dibayar > 0) {
                        $totalDibayarText = number_format($transaksi->jumlah_dibayar, 0, ',', '.');
                    } elseif ($transaksi->jumlah_beras_kg > 0) {
                        // Tampilkan kg + jiwa untuk beras
                        $jmlJiwaBayar = $transaksi->jumlah_jiwa > 0
                            ? $transaksi->jumlah_jiwa
                            : (int) ceil($transaksi->jumlah_beras_kg / 2.5);
                        $totalDibayarText = $transaksi->jumlah_beras_kg . ' kg (' . $jmlJiwaBayar . ' jiwa)';
                    } elseif ($isFidyah && $transaksi->fidyah_tipe !== 'tunai') {
                        if ($transaksi->fidyah_tipe === 'mentah') {
                            $totalDibayarText = ($transaksi->fidyah_total_berat_kg ?? 0) . ' kg';
                        } elseif ($transaksi->fidyah_tipe === 'matang') {
                            $totalDibayarText = ($transaksi->fidyah_jumlah_box ?? 0) . ' box';
                        }
                    }

                    // ── Metode pembayaran label ────────────────────────────
                    $metBayar = $transaksi->metode_pembayaran;
                    $metBayarText = match($metBayar) {
                        'bahan_mentah'   => 'Bahan Mentah',
                        'makanan_matang' => 'Makanan Matang',
                        default          => $metBayar ? ucfirst($metBayar) : '-',
                    };
                    $metBayarClass = match($metBayar) {
                        'tunai'          => 'badge-gray',
                        'transfer'       => 'badge-blue',
                        'qris'           => 'badge-green',
                        'beras'          => 'badge-amber',
                        'bahan_mentah'   => 'badge-amber',
                        'makanan_matang' => 'badge-orange',
                        default          => 'badge-gray',
                    };

                    // ── Akumulator ─────────────────────────────────────────
                    $grandJumlah  += (float) ($transaksi->jumlah ?? 0);
                    $grandInfaq   += (float) ($transaksi->jumlah_infaq ?? 0);
                    $grandDibayar += (float) ($transaksi->jumlah_dibayar ?? 0);
                    $grandBerasKg += (float) ($transaksi->jumlah_beras_kg ?? 0);

                    // ── Diinput online oleh muzakki ────────────────────────
                    $isOnline = $transaksi->diinput_muzakki ?? false;
                @endphp

                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left" style="font-size: 7.5px; font-family: monospace;">
                        {{ $transaksi->no_transaksi }}
                    </td>
                    <td class="text-center">
                        {{ $transaksi->tanggal_transaksi->format('d/m/Y') }}
                        @if ($transaksi->waktu_transaksi)
                            <br><span style="color:#636e72; font-size:7.5px;">
                                {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('H:i') }}
                            </span>
                        @endif
                    </td>
                    <td class="text-left">
                        <strong>{{ $transaksi->muzakki_nama }}</strong>
                        @if ($isOnline)
                            <br><span style="color:#27ae60; font-size:7px;">&#9679; Online</span>
                        @endif
                        @if ($transaksi->muzakki_telepon)
                            <br><span style="color:#636e72; font-size:7.5px;">{{ $transaksi->muzakki_telepon }}</span>
                        @endif
                    </td>

                    {{-- BARU: Kolom Metode Penerimaan --}}
                    <td class="text-center">
                        <span class="badge {{ $metodeClass }}">{{ $metodePenerimaanText }}</span>
                    </td>

                    <td class="text-left">{{ $transaksi->jenisZakat->nama ?? '-' }}</td>
                    <td class="text-left">{{ $transaksi->tipeZakat->nama ?? '-' }}</td>
                    <td class="text-left" style="font-size: 8px;">
                        {{ \Illuminate\Support\Str::limit($transaksi->programZakat->nama_program ?? '-', 20) }}
                    </td>
                    <td class="text-right">
                        {{ $transaksi->jumlah > 0 ? number_format($transaksi->jumlah, 0, ',', '.') : '-' }}
                    </td>

                    {{-- Jiwa / Fidyah / Beras --}}
                    <td class="text-left" style="font-size: 8px;">
                        @if ($isFidyah)
                            {{-- Fidyah: badge + jumlah hari --}}
                            {!! $fidyahBadge !!} <strong>{{ $detailText }}</strong>
                        @elseif ($transaksi->jumlah_beras_kg > 0)
                            {{-- Beras: jiwa sebagai info utama --}}
                            <strong style="color:#1a7a4a;">{{ $detailText }}</strong>
                        @else
                            <strong>{{ $detailText }}</strong>
                        @endif
                        @if ($detailExtra)
                            <br><span style="color:#636e72;">{{ $detailExtra }}</span>
                        @endif
                        @if ($transaksi->jumlah_beras_kg > 0 && !$isFidyah)
                            <br><span class="badge badge-amber">Beras</span>
                        @endif
                    </td>

                    {{-- Metode Bayar --}}
                    <td class="text-center">
                        <span class="badge {{ $metBayarClass }}">{{ $metBayarText }}</span>
                    </td>

                    <td class="text-right">
                        {{ $transaksi->jumlah_infaq > 0 ? number_format($transaksi->jumlah_infaq, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right">{{ $totalDibayarText }}</td>

                    <td class="text-left" style="font-size: 8px;">
                        {{ $transaksi->amil->pengguna->name ?? ($transaksi->amil->nama_lengkap ?? '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 30px; color: #b2bec3;">
                        <em>Data tidak ditemukan untuk kriteria filter ini.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>

        {{-- ── Baris Total ── --}}
        <tfoot>
            <tr>
                <td colspan="8" class="text-right" style="font-size: 9px;">
                    <strong>TOTAL ({{ $totalTransaksi }} transaksi &nbsp;|&nbsp; Verified: {{ $totalVerified }} &nbsp;|&nbsp; Pending: {{ $totalPending }})</strong>
                </td>
                <td class="text-right" style="font-size: 9px; font-weight:bold;">
                    {{ $grandJumlah > 0 ? number_format($grandJumlah, 0, ',', '.') : '-' }}
                </td>
                <td class="text-center" style="font-size: 8px;">
                    @if ($grandBerasKg > 0)
                        <strong>{{ number_format($grandBerasKg, 1, ',', '.') }} kg</strong>
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">-</td>
                <td class="text-right" style="font-size: 9px; font-weight:bold;">
                    {{ $grandInfaq > 0 ? number_format($grandInfaq, 0, ',', '.') : '-' }}
                </td>
                <td class="text-right" style="font-size: 9px; font-weight:bold;">
                    {{ $grandDibayar > 0 ? number_format($grandDibayar, 0, ',', '.') : '-' }}
                </td>
                <td class="text-center" style="font-size: 8px;">-</td>
            </tr>
        </tfoot>
    </table>

    {{-- ══════════ CATATAN KAKI ══════════ --}}
    @if ($totalBerasKg > 0 || $totalFidyah > 0)
        <div style="font-size: 8px; color: #636e72; margin-bottom: 10px; border: 1px dashed #dfe6e9; padding: 6px 10px; border-radius: 3px;">
            <strong>Keterangan:</strong>
            @if ($totalBerasKg > 0)
                Estimasi jiwa beras berdasarkan standar BAZNAS 2,5 kg/jiwa.
            @endif
            @if ($totalFidyah > 0)
                Fidyah: 1 mud = 675 gram bahan pokok per hari (standar umum).
            @endif
            Nominal total hanya mencakup transaksi uang (tunai/transfer/QRIS); beras dan fidyah non-tunai dihitung terpisah.
        </div>
    @endif

    {{-- ══════════ TANDA TANGAN ══════════ --}}
    <div class="footer-container">
        <div class="signature-wrapper">
            <div>{{ $lembaga->kota_nama ?? 'Bandung' }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 15px;">Yang Membuat Laporan,</div>
            <div class="signature-title">Admin Lembaga</div>
            <div class="signature-space"></div>
            <table class="signature-line-table">
                <tr>
                    <td colspan="3" style="border: none; text-align: center; font-weight: bold; padding: 0; line-height: 1.3;">
                        {{ $namaAdmin ?? ($lembaga->admin_nama ?? '') }}
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

    {{-- ══════════ FOOTER NOTE ══════════ --}}
    <div class="footer-note">
        Dokumen ini dicetak otomatis oleh sistem pada
        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }} pukul {{ \Carbon\Carbon::now()->format('H:i') }} WIB
        &nbsp;&mdash;&nbsp;
        {{ $lembaga->nama ?? '' }}
        &nbsp;&mdash;&nbsp;
        <span class="page-number"></span>
    </div>

</body>
</html>