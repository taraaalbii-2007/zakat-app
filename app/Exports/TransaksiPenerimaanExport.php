<?php

namespace App\Exports;

use App\Models\JenisZakat;
use App\Models\TransaksiPenerimaan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Export Excel Laporan Transaksi Penerimaan Zakat
 *
 * Struktur kolom (16 kolom, A–P) mengikuti template PDF landscape:
 *   A  : No
 *   B  : No. Transaksi
 *   C  : Tanggal
 *   D  : Muzakki
 *   E  : Telepon
 *   F  : Metode Penerimaan
 *   G  : Jenis Zakat
 *   H  : Tipe Zakat
 *   I  : Program
 *   J  : Nominal (Rp)
 *   K  : Jiwa / Fidyah / Beras
 *   L  : Metode Pembayaran
 *   M  : Infaq (Rp)
 *   N  : Total Dibayar
 *   O  : Status
 *   P  : Amil
 *
 * Layout sheet:
 *   Baris 1   : Nama lembaga (judul)
 *   Baris 2   : Sub-judul
 *   Baris 3   : Alamat (border bawah medium)
 *   Baris 4   : (kosong)
 *   Baris 5–9 : Info ekspor (tanggal, filter, total, nominal verified, petugas)
 *   Baris 10  : (kosong)
 *   Baris 11  : Blok ringkasan statistik (label)
 *   Baris 12  : Blok ringkasan statistik (nilai)
 *   Baris 13  : (kosong)
 *   Baris 14  : Header tabel (hijau #1a7a4a)
 *   Baris 15+ : Data transaksi
 *   Baris N   : Total
 */
class TransaksiPenerimaanExport implements WithEvents, ShouldAutoSize
{
    protected array $filters;
    protected       $user;
    protected       $lembaga;

    private const LAST_COL   = 'P';
    private const HEADER_ROW = 14;
    private const MERGE_SPAN = 'A1:P1'; // rentang merge judul

    // Warna tema — selaras dengan PDF (#1a7a4a)
    private const COLOR_HEADER_BG   = '1a7a4a';
    private const COLOR_HEADER_FG   = 'FFFFFF';
    private const COLOR_TOTAL_BG    = 'e8f5e9';
    private const COLOR_STATS_BG    = 'f0faf5';
    private const COLOR_STRIPE      = 'f8fffe'; // zebra genap (sama seperti PDF)
    private const COLOR_BORDER      = 'c8d6cb'; // border data (sama seperti PDF)

    // Warna status (selaras dengan badge PDF)
    private const STATUS_COLORS = [
        'verified' => 'd4edda', // hijau muda
        'pending'  => 'fff3cd', // kuning
        'rejected' => 'f8d7da', // merah muda
    ];

    public function __construct(array $filters, $user, $lembaga)
    {
        $this->filters = $filters;
        $this->user    = $user;
        $this->lembaga = $lembaga;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Query — sama persis dengan filter yang ada di PDF controller
    // ─────────────────────────────────────────────────────────────────────────

    private function getData()
    {
        $query = TransaksiPenerimaan::with([
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
        ])->byLembaga($this->lembaga->id);

        if (!empty($this->filters['q'])) {
            $query->search($this->filters['q']);
        }
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->byPeriode($this->filters['start_date'], $this->filters['end_date']);
        }
        if (!empty($this->filters['jenis_zakat_id'])) {
            $query->byJenisZakat($this->filters['jenis_zakat_id']);
        }
        if (!empty($this->filters['metode_pembayaran'])) {
            $query->byMetodePembayaran($this->filters['metode_pembayaran']);
        }
        if (!empty($this->filters['status'])) {
            $query->byStatus($this->filters['status']);
        }
        if (!empty($this->filters['metode_penerimaan'])) {
            $query->byMetodePenerimaan($this->filters['metode_penerimaan']);
        }
        // Filter fidyah_tipe (ada di controller tapi belum dipakai di export lama)
        if (!empty($this->filters['fidyah_tipe'])) {
            $query->where('fidyah_tipe', $this->filters['fidyah_tipe']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: label filter aktif (selaras dengan logika @php di PDF)
    // ─────────────────────────────────────────────────────────────────────────

    private function buildFilterString(): string
    {
        $parts = [];

        if (!empty($this->filters['q'])) {
            $parts[] = "Pencarian: '{$this->filters['q']}'";
        }
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $parts[] = 'Periode: '
                . Carbon::parse($this->filters['start_date'])->format('d/m/Y')
                . ' - '
                . Carbon::parse($this->filters['end_date'])->format('d/m/Y');
        }
        if (!empty($this->filters['jenis_zakat_id'])) {
            $jenis   = JenisZakat::find($this->filters['jenis_zakat_id']);
            $parts[] = 'Jenis: ' . ($jenis->nama ?? '-');
        }
        if (!empty($this->filters['metode_pembayaran'])) {
            $label   = match ($this->filters['metode_pembayaran']) {
                'bahan_mentah'   => 'Bahan Mentah',
                'makanan_matang' => 'Makanan Matang',
                default          => ucfirst($this->filters['metode_pembayaran']),
            };
            $parts[] = 'Metode Bayar: ' . $label;
        }
        if (!empty($this->filters['status'])) {
            $label   = match ($this->filters['status']) {
                'verified' => 'Terverifikasi',
                'pending'  => 'Menunggu',
                'rejected' => 'Ditolak',
                default    => $this->filters['status'],
            };
            $parts[] = 'Status: ' . $label;
        }
        if (!empty($this->filters['metode_penerimaan'])) {
            $label   = match ($this->filters['metode_penerimaan']) {
                'datang_langsung' => 'Datang Langsung',
                'dijemput'        => 'Dijemput',
                'daring'          => 'Daring',
                default           => $this->filters['metode_penerimaan'],
            };
            $parts[] = 'Penerimaan: ' . $label;
        }
        if (!empty($this->filters['fidyah_tipe'])) {
            $label   = match ($this->filters['fidyah_tipe']) {
                'mentah' => 'Fidyah Bahan Mentah',
                'matang' => 'Fidyah Makanan Matang',
                'tunai'  => 'Fidyah Tunai',
                default  => 'Fidyah',
            };
            $parts[] = $label;
        }

        return count($parts) ? implode(' | ', $parts) : 'Semua Data';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: kolom "Jiwa / Fidyah / Beras" — logika persis sama dengan PDF
    // ─────────────────────────────────────────────────────────────────────────

    private function buildDetailJiwa($transaksi): string
    {
        $isFidyah = !empty($transaksi->fidyah_tipe)
            && $transaksi->fidyah_jumlah_hari > 0;

        if ($isFidyah) {
            $tipe       = $transaksi->fidyah_tipe;
            $hari       = $transaksi->fidyah_jumlah_hari;
            $tipeLabel  = match ($tipe) {
                'mentah' => 'Bahan Mentah',
                'matang' => 'Makanan Matang',
                'tunai'  => 'Tunai',
                default  => ucfirst($tipe),
            };
            $base = "Fidyah {$tipeLabel}: {$hari} hari";

            if ($tipe === 'mentah') {
                $berat = $transaksi->fidyah_total_berat_kg ?? 0;
                $bahan = $transaksi->fidyah_nama_bahan ?? 'Bahan Pokok';
                return "{$base} | {$bahan}: {$berat} kg";
            }
            if ($tipe === 'matang') {
                $box  = $transaksi->fidyah_jumlah_box ?? $hari;
                $menu = $transaksi->fidyah_menu_makanan ?: 'Makanan';
                $cara = $transaksi->fidyah_cara_serah
                    ? ' (' . match ($transaksi->fidyah_cara_serah) {
                        'dibagikan'   => 'Dibagikan',
                        'dijamu'      => 'Dijamu',
                        'via_lembaga' => 'Via Lembaga',
                        default       => $transaksi->fidyah_cara_serah,
                    } . ')'
                    : '';
                return "{$base} | {$menu}: {$box} box{$cara}";
            }
            if ($tipe === 'tunai') {
                return "{$base} | Rp " . number_format($transaksi->jumlah ?? 0, 0, ',', '.');
            }
            return $base;
        }

        // Zakat fitrah beras
        if ($transaksi->jumlah_beras_kg > 0) {
            $namaJiwa = $transaksi->nama_jiwa_json;
            $namaArr  = is_array($namaJiwa) ? $namaJiwa : [];

            // Prioritas: count array > jumlah_jiwa field > ceil(kg÷2.5) — sama dengan PDF
            if (count($namaArr) > 0) {
                $jmlJiwa = count($namaArr);
            } elseif ($transaksi->jumlah_jiwa > 0) {
                $jmlJiwa = $transaksi->jumlah_jiwa;
            } else {
                $jmlJiwa = (int) ceil($transaksi->jumlah_beras_kg / 2.5);
            }

            $detail = "{$jmlJiwa} jiwa | {$transaksi->jumlah_beras_kg} kg beras";
            if ($transaksi->harga_beras_per_kg > 0) {
                $detail .= ' @ Rp' . number_format($transaksi->harga_beras_per_kg, 0, ',', '.');
            }
            if (count($namaArr) > 0) {
                $preview = array_slice($namaArr, 0, 4);
                $sisa    = count($namaArr) - count($preview);
                $detail .= ' | ' . implode(', ', array_map(
                    fn ($n, $i) => ($i + 1) . '. ' . $n,
                    $preview,
                    array_keys($preview)
                ));
                if ($sisa > 0) {
                    $detail .= " +{$sisa} lainnya";
                }
            }
            return $detail;
        }

        // Zakat fitrah tunai (jumlah jiwa)
        if ($transaksi->jumlah_jiwa > 0) {
            $namaJiwa = $transaksi->nama_jiwa_json;
            $namaArr  = is_array($namaJiwa) ? $namaJiwa : [];
            $jmlJiwa  = count($namaArr) > 0 ? count($namaArr) : $transaksi->jumlah_jiwa;

            $detail = "{$jmlJiwa} jiwa";
            if ($transaksi->nominal_per_jiwa > 0) {
                $detail .= ' @ Rp ' . number_format($transaksi->nominal_per_jiwa, 0, ',', '.');
            }
            if (count($namaArr) > 0) {
                $preview = array_slice($namaArr, 0, 4);
                $sisa    = count($namaArr) - count($preview);
                $detail .= ' | ' . implode(', ', array_map(
                    fn ($n, $i) => ($i + 1) . '. ' . $n,
                    $preview,
                    array_keys($preview)
                ));
                if ($sisa > 0) {
                    $detail .= " +{$sisa} lainnya";
                }
            }
            return $detail;
        }

        return '-';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: kolom "Total Dibayar" — logika persis sama dengan PDF
    // ─────────────────────────────────────────────────────────────────────────

    private function buildTotalDibayar($transaksi): string
    {
        if ($transaksi->jumlah_dibayar > 0) {
            return 'Rp ' . number_format($transaksi->jumlah_dibayar, 0, ',', '.');
        }

        if ($transaksi->jumlah_beras_kg > 0) {
            $namaJiwa = $transaksi->nama_jiwa_json;
            $namaArr  = is_array($namaJiwa) ? $namaJiwa : [];
            if (count($namaArr) > 0) {
                $jml = count($namaArr);
            } elseif ($transaksi->jumlah_jiwa > 0) {
                $jml = $transaksi->jumlah_jiwa;
            } else {
                $jml = (int) ceil($transaksi->jumlah_beras_kg / 2.5);
            }
            return "{$transaksi->jumlah_beras_kg} kg ({$jml} jiwa)";
        }

        $isFidyah = !empty($transaksi->fidyah_tipe) && $transaksi->fidyah_jumlah_hari > 0;
        if ($isFidyah) {
            return match ($transaksi->fidyah_tipe) {
                'mentah' => ($transaksi->fidyah_total_berat_kg ?? 0) . ' kg',
                'matang' => ($transaksi->fidyah_jumlah_box ?? 0) . ' box',
                default  => '-',
            };
        }

        return '-';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: warna status selaras dengan badge PDF
    // ─────────────────────────────────────────────────────────────────────────

    private function statusColor(string $status): string
    {
        return self::STATUS_COLORS[$status] ?? 'f8f9fa';
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'verified' => 'Terverifikasi',
            'pending'  => 'Menunggu',
            'rejected' => 'Ditolak',
            default    => ucfirst($status),
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AfterSheet — bangun seluruh sheet
    // ─────────────────────────────────────────────────────────────────────────

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $transaksis = $this->getData();
                $lembaga    = $this->lembaga;
                $user       = $this->user;

                // ── Lebar kolom (sesuai proporsi PDF) ────────────────────────
                $colWidths = [
                    'A' => 5,   // No
                    'B' => 24,  // No. Transaksi
                    'C' => 14,  // Tanggal
                    'D' => 28,  // Muzakki
                    'E' => 16,  // Telepon
                    'F' => 18,  // Metode Penerimaan
                    'G' => 18,  // Jenis Zakat
                    'H' => 18,  // Tipe Zakat
                    'I' => 22,  // Program
                    'J' => 20,  // Nominal (Rp)
                    'K' => 42,  // Jiwa / Fidyah / Beras
                    'L' => 16,  // Metode Pembayaran
                    'M' => 18,  // Infaq (Rp)
                    'N' => 24,  // Total Dibayar
                    'O' => 14,  // Status
                    'P' => 22,  // Amil
                ];
                foreach ($colWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // ── Baris 1: Nama lembaga ─────────────────────────────────────
                $sheet->mergeCells('A1:P1');
                $sheet->setCellValue('A1', strtoupper($lembaga->nama ?? 'LAPORAN TRANSAKSI PENERIMAAN ZAKAT'));
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Baris 2: Sub-judul ────────────────────────────────────────
                $sheet->mergeCells('A2:P2');
                $sheet->setCellValue('A2', 'Laporan Detail Transaksi Penerimaan Zakat');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 11, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Baris 3: Alamat (border bawah medium) ─────────────────────
                $alamat = implode('', array_filter([
                    $lembaga->alamat         ?? '',
                    $lembaga->kelurahan_nama  ? ', Kel. ' . $lembaga->kelurahan_nama : '',
                    $lembaga->kecamatan_nama  ? ', Kec. ' . $lembaga->kecamatan_nama : '',
                    $lembaga->kota_nama       ? ', ' . $lembaga->kota_nama            : '',
                ]));
                $sheet->mergeCells('A3:P3');
                $sheet->setCellValue('A3', $alamat);
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A3:P3')->getBorders()
                    ->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                // ── Baris 5–9: Info ekspor ────────────────────────────────────
                $totalVerified  = $transaksis->where('status', 'verified')->count();
                $totalPending   = $transaksis->where('status', 'pending')->count();
                $totalNominal   = $transaksis->where('status', 'verified')->sum('jumlah');
                $totalInfaq     = $transaksis->where('status', 'verified')->sum('jumlah_infaq');
                $totalBerasKg   = $transaksis->where('metode_pembayaran', 'beras')
                                             ->where('status', 'verified')
                                             ->sum('jumlah_beras_kg');

                $nominalStr = 'Rp ' . number_format($totalNominal, 0, ',', '.');
                if ($totalInfaq > 0) {
                    $nominalStr .= '  (+Infaq: Rp ' . number_format($totalInfaq, 0, ',', '.') . ')';
                }
                if ($totalBerasKg > 0) {
                    $nominalStr .= '  (+Beras: ' . number_format($totalBerasKg, 1, ',', '.') . ' kg)';
                }

                $amilName = '';
                if (!empty($user->amil)) {
                    $amilName = '  (Amil: ' . ($user->amil->nama_lengkap ?? '-') . ')';
                }

                $infoRows = [
                    ['Hari / Tanggal',    Carbon::now()->locale('id')->translatedFormat('l, d F Y') . ', ' . Carbon::now()->format('H:i') . ' WIB'],
                    ['Filter',            $this->buildFilterString()],
                    ['Total Transaksi',   $transaksis->count() . ' transaksi  (Terverifikasi: ' . $totalVerified . '  |  Menunggu: ' . $totalPending . ')'],
                    ['Total Nominal',     $nominalStr],
                    ['Petugas Ekspor',    ($user->name ?? ($user->username ?? 'System')) . $amilName],
                ];

                $row = 5;
                foreach ($infoRows as [$label, $value]) {
                    $sheet->setCellValue("A{$row}", $label);
                    $sheet->setCellValue("B{$row}", ': ' . $value);
                    $sheet->mergeCells("B{$row}:P{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$row}:P{$row}")->getFont()->setSize(9);
                    $row++;
                }

                // ── Baris 11–12: Ringkasan statistik ─────────────────────────
                // Selaras dengan stats-grid di PDF
                $totalDL    = $transaksis->where('metode_penerimaan', 'datang_langsung')->count();
                $totalDjm   = $transaksis->where('metode_penerimaan', 'dijemput')->count();
                $totalDar   = $transaksis->where('metode_penerimaan', 'daring')->count();
                $totalTunai = $transaksis->where('metode_pembayaran', 'tunai')->count();
                $totalTrf   = $transaksis->where('metode_pembayaran', 'transfer')->count();
                $totalQris  = $transaksis->where('metode_pembayaran', 'qris')->count();
                $totalBeras = $transaksis->where('metode_pembayaran', 'beras')->count();
                $totalFidyah = $transaksis->whereNotNull('fidyah_tipe')
                                          ->where('fidyah_jumlah_hari', '>', 0)->count();

                $statsLabel = [
                    'A11' => 'Total Transaksi',
                    'E11' => 'Total Nominal (Verified)',
                    'I11' => 'Metode Penerimaan',
                    'M11' => 'Metode Pembayaran',
                ];
                $statsValue = [
                    'A12' => $transaksis->count(),
                    'E12' => 'Rp ' . number_format($totalNominal, 0, ',', '.'),
                    'I12' => 'Datang Langsung: ' . $totalDL . '  Dijemput: ' . $totalDjm . '  Daring: ' . $totalDar,
                    'M12' => 'Tunai: ' . $totalTunai . '  Transfer: ' . $totalTrf . '  QRIS: ' . $totalQris . '  Beras: ' . $totalBeras,
                ];

                foreach (['A11:D11', 'E11:H11', 'I11:L11', 'M11:P11'] as $merge) {
                    $sheet->mergeCells($merge);
                }
                foreach (['A12:D12', 'E12:H12', 'I12:L12', 'M12:P12'] as $merge) {
                    $sheet->mergeCells($merge);
                }

                foreach ($statsLabel as $cell => $text) {
                    $sheet->setCellValue($cell, $text);
                    $sheet->getStyle($cell)->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 8, 'color' => ['rgb' => '636e72']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_STATS_BG]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    ]);
                }
                foreach ($statsValue as $cell => $text) {
                    $sheet->setCellValue($cell, $text);
                    $sheet->getStyle($cell)->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => self::COLOR_HEADER_BG]],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_STATS_BG]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    ]);
                }

                // Border luar blok statistik
                $sheet->getStyle('A11:P12')->applyFromArray([
                    'borders' => [
                        'outline'    => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'dfe6e9']],
                        'horizontal' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => 'dfe6e9']],
                    ],
                ]);

                // Tambah baris fidyah/beras jika ada (baris 13 — opsional, sama dengan PDF)
                $extraStatsRow = 13;
                if ($totalBerasKg > 0 || $totalFidyah > 0) {
                    if ($totalBerasKg > 0) {
                        $jiwaEst = floor($totalBerasKg / 2.5);
                        $sheet->mergeCells("A{$extraStatsRow}:H{$extraStatsRow}");
                        $sheet->setCellValue(
                            "A{$extraStatsRow}",
                            'Total Beras: ' . number_format($totalBerasKg, 1, ',', '.') . ' kg'
                            . '  (≈ ' . number_format($jiwaEst) . ' jiwa @ 2,5 kg/jiwa BAZNAS)'
                        );
                        $sheet->getStyle("A{$extraStatsRow}")->applyFromArray([
                            'font' => ['size' => 8, 'color' => ['rgb' => 'b8621a']],
                        ]);
                    }
                    if ($totalFidyah > 0) {
                        $fidyahMentah = $transaksis->where('fidyah_tipe', 'mentah')->count();
                        $fidyahMatang = $transaksis->where('fidyah_tipe', 'matang')->count();
                        $fidyahTunai  = $transaksis->where('fidyah_tipe', 'tunai')->count();

                        $col = $totalBerasKg > 0 ? 'I' : 'A';
                        $sheet->mergeCells("{$col}{$extraStatsRow}:P{$extraStatsRow}");
                        $sheet->setCellValue(
                            "{$col}{$extraStatsRow}",
                            'Transaksi Fidyah: ' . $totalFidyah
                            . '  (Mentah: ' . $fidyahMentah
                            . '  Matang: ' . $fidyahMatang
                            . '  Tunai: ' . $fidyahTunai . ')'
                        );
                        $sheet->getStyle("{$col}{$extraStatsRow}")->applyFromArray([
                            'font' => ['size' => 8, 'color' => ['rgb' => 'e67e22']],
                        ]);
                    }
                }

                // ── Baris 14: Header tabel ────────────────────────────────────
                $headerRow = self::HEADER_ROW;
                $headers   = [
                    'A' => 'No',
                    'B' => 'No. Transaksi',
                    'C' => 'Tanggal',
                    'D' => 'Muzakki',
                    'E' => 'Telepon',
                    'F' => 'Metode Penerimaan',
                    'G' => 'Jenis Zakat',
                    'H' => 'Tipe Zakat',
                    'I' => 'Program',
                    'J' => 'Nominal (Rp)',
                    'K' => 'Jiwa / Fidyah / Beras',
                    'L' => 'Metode Pembayaran',
                    'M' => 'Infaq (Rp)',
                    'N' => 'Total Dibayar',
                    'O' => 'Status',
                    'P' => 'Amil',
                ];

                foreach ($headers as $col => $label) {
                    $sheet->setCellValue("{$col}{$headerRow}", $label);
                }

                $sheet->getStyle("A{$headerRow}:P{$headerRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => self::COLOR_HEADER_FG], 'size' => 9],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER_BG]],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders'   => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '155d38']],
                    ],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(22);

                // ── Data rows ─────────────────────────────────────────────────
                $dataRow = $headerRow + 1;
                $no      = 1;

                // Akumulator grand total (sama dengan @php di PDF)
                $grandJumlah  = 0;
                $grandInfaq   = 0;
                $grandDibayar = 0;
                $grandBeras   = 0;

                foreach ($transaksis as $transaksi) {
                    $isOnline = $transaksi->diinput_muzakki ?? false;

                    $muzakkiCell = $transaksi->muzakki_nama ?? '-';
                    if ($isOnline) {
                        $muzakkiCell .= ' [Online]';
                    }

                    $metodePenerimaan = match ($transaksi->metode_penerimaan) {
                        'datang_langsung' => 'Datang Langsung',
                        'dijemput'        => 'Dijemput',
                        'daring'          => 'Daring',
                        default           => ucfirst($transaksi->metode_penerimaan ?? '-'),
                    };

                    $metodePembayaran = match ($transaksi->metode_pembayaran) {
                        'bahan_mentah'   => 'Bahan Mentah',
                        'makanan_matang' => 'Makanan Matang',
                        default          => $transaksi->metode_pembayaran
                            ? ucfirst($transaksi->metode_pembayaran)
                            : '-',
                    };

                    $sheet->setCellValue("A{$dataRow}", $no);
                    $sheet->setCellValue("B{$dataRow}", $transaksi->no_transaksi);
                    $sheet->setCellValue("C{$dataRow}", $transaksi->tanggal_transaksi->format('d/m/Y'));
                    $sheet->setCellValue("D{$dataRow}", $muzakkiCell);
                    $sheet->setCellValue("E{$dataRow}", $transaksi->muzakki_telepon ?? '-');
                    $sheet->setCellValue("F{$dataRow}", $metodePenerimaan);
                    $sheet->setCellValue("G{$dataRow}", $transaksi->jenisZakat->nama ?? '-');
                    $sheet->setCellValue("H{$dataRow}", $transaksi->tipeZakat->nama ?? '-');
                    $sheet->setCellValue("I{$dataRow}", $transaksi->programZakat->nama_program ?? '-');
                    $sheet->setCellValue("J{$dataRow}", $transaksi->jumlah > 0 ? (float) $transaksi->jumlah : 0);
                    $sheet->setCellValue("K{$dataRow}", $this->buildDetailJiwa($transaksi));
                    $sheet->setCellValue("L{$dataRow}", $metodePembayaran);
                    $sheet->setCellValue("M{$dataRow}", $transaksi->jumlah_infaq > 0 ? (float) $transaksi->jumlah_infaq : 0);
                    $sheet->setCellValue("N{$dataRow}", $this->buildTotalDibayar($transaksi));
                    $sheet->setCellValue("O{$dataRow}", $this->statusLabel($transaksi->status));
                    $sheet->setCellValue("P{$dataRow}", $transaksi->amil->pengguna->name
                        ?? $transaksi->amil->nama_lengkap
                        ?? '-');

                    // Format angka
                    $sheet->getStyle("J{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("M{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');

                    // Alignment
                    $sheet->getStyle("A{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("L{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("O{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("J{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("M{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Wrap text untuk kolom detail panjang
                    $sheet->getStyle("K{$dataRow}")->getAlignment()->setWrapText(true);

                    // Warna status (selaras dengan badge PDF)
                    $sheet->getStyle("O{$dataRow}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($this->statusColor($transaksi->status));

                    // Border semua sel
                    $sheet->getStyle("A{$dataRow}:P{$dataRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::COLOR_BORDER]],
                        ],
                    ]);

                    // Zebra stripe — baris genap (selaras dengan nth-child even di PDF)
                    if ($no % 2 === 0) {
                        foreach (['A','B','C','D','E','F','G','H','I','J','K','L','M','N','P'] as $col) {
                            $sheet->getStyle("{$col}{$dataRow}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB(self::COLOR_STRIPE);
                        }
                    }

                    // Akumulasi grand total
                    $grandJumlah  += (float) ($transaksi->jumlah ?? 0);
                    $grandInfaq   += (float) ($transaksi->jumlah_infaq ?? 0);
                    $grandBeras   += (float) ($transaksi->jumlah_beras_kg ?? 0);
                    // jumlah_dibayar hanya dijumlah jika numerik
                    if ($transaksi->jumlah_dibayar > 0) {
                        $grandDibayar += (float) $transaksi->jumlah_dibayar;
                    }

                    $no++;
                    $dataRow++;
                }

                // ── Baris total (selaras dengan <tfoot> PDF) ──────────────────
                $totalCount    = $transaksis->count();
                $totalVerifStr = 'Terverifikasi: ' . $totalVerified . '  |  Menunggu: ' . $totalPending;

                $sheet->mergeCells("A{$dataRow}:I{$dataRow}");
                $sheet->setCellValue(
                    "A{$dataRow}",
                    "TOTAL ({$totalCount} transaksi  |  {$totalVerifStr})"
                );
                $sheet->setCellValue("J{$dataRow}", $grandJumlah > 0 ? $grandJumlah : '-');
                $sheet->setCellValue("K{$dataRow}", $grandBeras > 0 ? number_format($grandBeras, 1, ',', '.') . ' kg' : '-');
                $sheet->setCellValue("L{$dataRow}", '-');
                $sheet->setCellValue("M{$dataRow}", $grandInfaq > 0 ? $grandInfaq : '-');
                $sheet->setCellValue("N{$dataRow}", $grandDibayar > 0 ? 'Rp ' . number_format($grandDibayar, 0, ',', '.') : '-');
                $sheet->setCellValue("O{$dataRow}", '-');
                $sheet->setCellValue("P{$dataRow}", '-');

                if ($grandJumlah > 0) {
                    $sheet->getStyle("J{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');
                }
                if ($grandInfaq > 0) {
                    $sheet->getStyle("M{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');
                }

                $sheet->getStyle("A{$dataRow}:P{$dataRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_TOTAL_BG]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                    'borders'   => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::COLOR_BORDER]],
                        'top'        => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => self::COLOR_HEADER_BG]],
                        'bottom'     => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => self::COLOR_HEADER_BG]],
                    ],
                ]);
                $sheet->getStyle("A{$dataRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // ── Catatan kaki (selaras dengan keterangan PDF) ──────────────
                $noteRow = $dataRow + 2;
                if ($grandBeras > 0 || $totalFidyah > 0) {
                    $noteText = 'Keterangan: ';
                    if ($grandBeras > 0) {
                        $noteText .= 'Estimasi jiwa beras berdasarkan standar BAZNAS 2,5 kg/jiwa. ';
                    }
                    if ($totalFidyah > 0) {
                        $noteText .= 'Fidyah: 1 mud = 675 gram bahan pokok per hari (standar umum). ';
                    }
                    $noteText .= 'Nominal total hanya mencakup transaksi uang (tunai/transfer/QRIS); beras dan fidyah non-tunai dihitung terpisah.';

                    $sheet->mergeCells("A{$noteRow}:P{$noteRow}");
                    $sheet->setCellValue("A{$noteRow}", $noteText);
                    $sheet->getStyle("A{$noteRow}")->applyFromArray([
                        'font'      => ['size' => 8, 'italic' => true, 'color' => ['rgb' => '636e72']],
                        'alignment' => ['wrapText' => true],
                        'borders'   => [
                            'outline' => ['borderStyle' => Border::BORDER_DASHED, 'color' => ['rgb' => 'dfe6e9']],
                        ],
                    ]);
                    $sheet->getRowDimension($noteRow)->setRowHeight(28);
                }

                // ── Footer timestamp (pojok kiri bawah) ───────────────────────
                $footerRow = $noteRow + 1;
                $sheet->mergeCells("A{$footerRow}:P{$footerRow}");
                $sheet->setCellValue(
                    "A{$footerRow}",
                    'Dokumen ini dicetak otomatis oleh sistem pada '
                    . Carbon::now()->locale('id')->translatedFormat('l, d F Y')
                    . ' pukul ' . Carbon::now()->format('H:i') . ' WIB'
                    . '  —  ' . ($lembaga->nama ?? '')
                );
                $sheet->getStyle("A{$footerRow}")->applyFromArray([
                    'font'      => ['size' => 8, 'color' => ['rgb' => 'b2bec3']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Freeze pane & Auto-filter ─────────────────────────────────
                $sheet->freezePane('A15'); // beku di bawah header

                $lastDataRow = $dataRow - 1;
                if ($lastDataRow >= self::HEADER_ROW + 1) {
                    $sheet->setAutoFilter("A" . self::HEADER_ROW . ":P{$lastDataRow}");
                }

                // ── Nama sheet ────────────────────────────────────────────────
                $sheet->setTitle('Laporan Penerimaan');
            },
        ];
    }
}