<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konsolidasi - {{ $lembaga->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #2d3436;
            margin: 15px;
        }

        /* Header */
        .header {
            margin-bottom: 20px;
            border-bottom: 2.5px solid #2d3436;
            padding-bottom: 10px;
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

        /* Table Styles */
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
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
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

        .text-left   { text-align: left; }
        .text-right  { text-align: right; }
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

        .compact-table th, .compact-table td {
            padding: 3px 2px;
        }
    </style>
</head>
<body>

    {{-- ══════════════════ HEADER ══════════════════ --}}
    @php
        // Logo Lembaga
        $fotoLembaga    = $lembaga->foto;
        $fotoLembagaArr = is_array($fotoLembaga) ? $fotoLembaga : (is_string($fotoLembaga) ? json_decode($fotoLembaga, true) : []);
        $logoLembagaB64 = null;
        if (!empty($fotoLembagaArr)) {
            $lp = storage_path('app/public/' . $fotoLembagaArr[0]);
            if (file_exists($lp)) {
                $ext  = strtolower(pathinfo($lp, PATHINFO_EXTENSION));
                $mime = in_array($ext, ['jpg','jpeg']) ? 'image/jpeg' : 'image/' . $ext;
                $logoLembagaB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($lp));
            }
        }
        // Logo Aplikasi
        $logoAplikasiB64 = null;
        try {
            $konfigApp = \App\Models\KonfigurasiAplikasi::getConfig();
            if ($konfigApp && $konfigApp->logo_aplikasi) {
                foreach ([
                    storage_path('app/public/' . $konfigApp->logo_aplikasi),
                    public_path('storage/' . $konfigApp->logo_aplikasi),
                    public_path($konfigApp->logo_aplikasi),
                    base_path($konfigApp->logo_aplikasi),
                ] as $cp) {
                    if (file_exists($cp)) {
                        $ext  = strtolower(pathinfo($cp, PATHINFO_EXTENSION));
                        $mime = in_array($ext, ['jpg','jpeg']) ? 'image/jpeg' : 'image/' . $ext;
                        $logoAplikasiB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($cp));
                        break;
                    }
                }
            }
        } catch (\Exception $e) {}
    @endphp

    <div class="header">
        <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
            <tr>
                {{-- Logo Lembaga --}}
                <td style="width:85px; text-align:center; vertical-align:middle;">
                    @if($logoLembagaB64)
                        <img src="{{ $logoLembagaB64 }}" style="width:72px;height:72px;object-fit:contain;border-radius:50%;border:2px solid #d1d5db;padding:4px;background:#ffffff;" alt="Logo Lembaga">
                    @else
                        <div style="width:72px;height:72px;display:inline-block;"></div>
                    @endif
                </td>

                {{-- Teks Tengah --}}
                <td style="text-align:center; vertical-align:middle; padding:0 10px;">
                    <h1 style="margin:0; font-size:18px; font-weight:bold; letter-spacing:1px; color:#000; text-transform:uppercase;">{{ strtoupper($lembaga->nama ?? 'LAPORAN KONSOLIDASI ZAKAT') }}</h1>
                    <h2 style="margin:4px 0; font-size:14px; font-weight:normal; color:#636e72;">Laporan Konsolidasi {{ $type == 'penerimaan' ? 'Penerimaan' : ($type == 'penyaluran' ? 'Penyaluran' : 'Penerimaan & Penyaluran') }}</h2>
                    <div style="margin:2px 0; font-size:10px; font-style:italic; color:#2d3436;">
                        {{ $lembaga->alamat ?? '' }}
                        {{ $lembaga->kelurahan_nama ? ', Kel. ' . $lembaga->kelurahan_nama : '' }}
                        {{ $lembaga->kecamatan_nama ? ', Kec. ' . $lembaga->kecamatan_nama : '' }}
                        {{ $lembaga->kota_nama ? ', ' . $lembaga->kota_nama : '' }}
                    </div>
                </td>

                {{-- Logo Aplikasi --}}
                <td style="width:85px; text-align:center; vertical-align:middle;">
                    @if($logoAplikasiB64)
                        <img src="{{ $logoAplikasiB64 }}" style="width:72px;height:72px;object-fit:contain;border-radius:50%;border:2px solid #d1d5db;padding:4px;background:#ffffff;" alt="Logo Aplikasi">
                    @else
                        <div style="width:72px;height:72px;display:inline-block;"></div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ══════════════════ INFO SECTION ══════════════════ --}}
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
                {{ count($filterText) > 0 ? implode(' | ', $filterText) : 'Semua Data' }}
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Petugas Ekspor</div>
            <div class="info-value">: {{ $user->name ?? $user->username ?? 'System' }}</div>
        </div>
    </div>

    {{-- ══════════════════ KONSOLIDASI ══════════════════ --}}
    @if($type == 'konsolidasi')
        @php $ringkasanCollection = collect($ringkasan_bulanan ?? []); @endphp

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
                <tr><td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data untuk periode ini</td></tr>
                @endforelse
            </tbody>
        </table>

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
                <tr><td colspan="6" class="text-center">Tidak ada data penerimaan</td></tr>
                @endforelse
            </tbody>
        </table>

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
                <tr><td colspan="6" class="text-center">Tidak ada data penyaluran</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- ══════════════════ DETAIL PENERIMAAN / PENYALURAN ══════════════════ --}}
    @if(in_array($type, ['penerimaan', 'penyaluran']))
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
                        <td class="text-center"><span class="badge badge-success">Verified</span></td>
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
                        $nominal = $transaksi->metode_penyaluran == 'barang'
                            ? ($transaksi->nilai_barang ?? 0)
                            : ($transaksi->jumlah ?? 0);
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

    {{-- ══════════════════ TANDA TANGAN ══════════════════ --}}
    <div style="margin-top:30px; width:100%; page-break-inside:avoid;">
        <div style="float:right; width:250px; text-align:center;">
            <div>{{ $lembaga->kota_nama ?? 'Bandung' }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
            <div style="margin-top:12px;">Yang Membuat Laporan,</div>
            <div style="font-weight:bold; margin-bottom:5px;">Admin Lembaga</div>

            <div style="height:70px;"></div>

            <table style="width:210px; margin:0 auto; border-collapse:collapse; font-weight:bold; font-size:11px;">
                <tr>
                    <td colspan="3" style="border:none; text-align:center; font-weight:bold; padding:0; line-height:1.3;">
                        {{ $lembaga->admin_nama ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left; width:10px; border:none; padding:0; border-bottom:1px solid #2d3436; line-height:1.4;">(</td>
                    <td style="text-align:center; border:none; padding:0; border-bottom:1px solid #2d3436; line-height:1.4;">&nbsp;</td>
                    <td style="text-align:right; width:10px; border:none; padding:0; border-bottom:1px solid #2d3436; line-height:1.4;">)</td>
                </tr>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>