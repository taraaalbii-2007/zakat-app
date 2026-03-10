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
 * Tampilan mengikuti standar exportExcel (penyaluran):
 *   Baris 1    : Nama lembaga
 *   Baris 2    : Sub-judul
 *   Baris 3    : Alamat (garis bawah medium)
 *   Baris 5-8  : Info ekspor (tanggal, filter, total, petugas)
 *   Baris 10   : Header tabel (hijau #1a7a4a)
 *   Baris 11+  : Data (baris selang-seling, warna status)
 *   Baris akhir: Total nominal
 */
class TransaksiPenerimaanExport implements WithEvents, ShouldAutoSize
{
    protected array  $filters;
    protected        $user;
    protected        $lembaga;

    // Kolom tabel: A–M (13 kolom)
    private const LAST_COL  = 'M';
    private const HEADER_ROW = 10;

    public function __construct(array $filters, $user, $lembaga)
    {
        $this->filters = $filters;
        $this->user    = $user;
        $this->lembaga = $lembaga;
    }

    // ── Query ────────────────────────────────────────────────────────────────

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

        return $query->orderBy('created_at', 'desc')->get();
    }

    // ── Event: AfterSheet ────────────────────────────────────────────────────

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $transaksis = $this->getData();
                $lembaga    = $this->lembaga;
                $user       = $this->user;

                // ── Lebar kolom ───────────────────────────────────────────────
                $colWidths = [
                    'A' => 5,  'B' => 22, 'C' => 18, 'D' => 28,
                    'E' => 16, 'F' => 18, 'G' => 24, 'H' => 20,
                    'I' => 20, 'J' => 16, 'K' => 13, 'L' => 22,
                    'M' => 35,
                ];
                foreach ($colWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // ── Baris 1: Judul ────────────────────────────────────────────
                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', strtoupper($lembaga->nama ?? 'LAPORAN TRANSAKSI PENERIMAAN ZAKAT'));
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Baris 2: Sub-judul ────────────────────────────────────────
                $sheet->mergeCells('A2:M2');
                $sheet->setCellValue('A2', 'Laporan Transaksi Penerimaan Zakat');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 11, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Baris 3: Alamat ───────────────────────────────────────────
                $alamat = implode('', array_filter([
                    $lembaga->alamat          ?? '',
                    $lembaga->kelurahan_nama   ? ', Kel. ' . $lembaga->kelurahan_nama : '',
                    $lembaga->kecamatan_nama   ? ', Kec. ' . $lembaga->kecamatan_nama : '',
                    $lembaga->kota_nama        ? ', ' . $lembaga->kota_nama            : '',
                ]));
                $sheet->mergeCells('A3:M3');
                $sheet->setCellValue('A3', $alamat);
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A3:M3')->getBorders()
                    ->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

                // ── Baris 5-8: Info ekspor ────────────────────────────────────
                $filterStr = [];
                if (!empty($this->filters['status'])) {
                    $filterStr[] = 'Status: ' . ucfirst($this->filters['status']);
                }
                if (!empty($this->filters['metode_penerimaan'])) {
                    $filterStr[] = 'Metode Penerimaan: ' . ucfirst($this->filters['metode_penerimaan']);
                }
                if (!empty($this->filters['metode_pembayaran'])) {
                    $filterStr[] = 'Metode Pembayaran: ' . ucfirst($this->filters['metode_pembayaran']);
                }
                if (!empty($this->filters['jenis_zakat_id'])) {
                    $jenis       = JenisZakat::find($this->filters['jenis_zakat_id']);
                    $filterStr[] = 'Jenis Zakat: ' . ($jenis->nama ?? '-');
                }
                if (!empty($this->filters['start_date'])) {
                    $filterStr[] = 'Dari: ' . Carbon::parse($this->filters['start_date'])->format('d/m/Y');
                }
                if (!empty($this->filters['end_date'])) {
                    $filterStr[] = 'Sampai: ' . Carbon::parse($this->filters['end_date'])->format('d/m/Y');
                }
                if (!empty($this->filters['q'])) {
                    $filterStr[] = "Pencarian: '{$this->filters['q']}'";
                }

                $infoRows = [
                    ['Tanggal Ekspor', Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB'],
                    ['Filter',         count($filterStr) ? implode(' | ', $filterStr) : 'Semua Data'],
                    ['Total Data',     $transaksis->count() . ' transaksi'],
                    ['Petugas',        $user->name ?? $user->username ?? 'System'],
                ];

                $row = 5;
                foreach ($infoRows as [$label, $value]) {
                    $sheet->setCellValue("A{$row}", $label);
                    $sheet->setCellValue("B{$row}", ': ' . $value);
                    $sheet->mergeCells("B{$row}:M{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $row++;
                }

                // ── Baris 10: Header tabel ────────────────────────────────────
                $headerRow = self::HEADER_ROW;
                $headers   = [
                    'A' => 'No',
                    'B' => 'No. Transaksi',
                    'C' => 'Tanggal',
                    'D' => 'Muzakki',
                    'E' => 'Telepon',
                    'F' => 'Jenis Zakat',
                    'G' => 'Program',
                    'H' => 'Metode Penerimaan',
                    'I' => 'Metode Pembayaran',
                    'J' => 'Jumlah (Rp)',
                    'K' => 'Status',
                    'L' => 'Amil',
                    'M' => 'Keterangan',
                ];

                foreach ($headers as $col => $label) {
                    $sheet->setCellValue("{$col}{$headerRow}", $label);
                }

                $sheet->getStyle("A{$headerRow}:M{$headerRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a7a4a']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders'   => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    ],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(20);

                // ── Data rows ─────────────────────────────────────────────────
                $dataRow = $headerRow + 1;
                $no      = 1;

                foreach ($transaksis as $transaksi) {
                    $statusText = match ($transaksi->status) {
                        'draft'      => 'Draft',
                        'disetujui'  => 'Disetujui',
                        'diterima'   => 'Diterima',
                        'dibatalkan' => 'Dibatalkan',
                        default      => $transaksi->status_label ?? $transaksi->status,
                    };

                    $sheet->setCellValue("A{$dataRow}", $no);
                    $sheet->setCellValue("B{$dataRow}", $transaksi->no_transaksi);
                    $sheet->setCellValue("C{$dataRow}", $transaksi->tanggal_transaksi->format('d/m/Y H:i'));
                    $sheet->setCellValue("D{$dataRow}", $transaksi->muzakki_nama ?? '-');
                    $sheet->setCellValue("E{$dataRow}", $transaksi->muzakki_telepon ?? '-');
                    $sheet->setCellValue("F{$dataRow}", $transaksi->jenisZakat->nama ?? '-');
                    $sheet->setCellValue("G{$dataRow}", $transaksi->programZakat->nama_program ?? '-');
                    $sheet->setCellValue("H{$dataRow}", $transaksi->metode_penerimaan_label ?? '-');
                    $sheet->setCellValue("I{$dataRow}", $transaksi->metode_pembayaran_label ?? '-');
                    $sheet->setCellValue("J{$dataRow}", $transaksi->jumlah > 0 ? $transaksi->jumlah : 0);
                    $sheet->setCellValue("K{$dataRow}", $statusText);
                    $sheet->setCellValue("L{$dataRow}", $transaksi->amil->pengguna->name ?? $transaksi->amil->nama_lengkap ?? '-');
                    $sheet->setCellValue("M{$dataRow}", $transaksi->keterangan ?? '-');

                    // Format angka
                    $sheet->getStyle("J{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');

                    // Alignment
                    $sheet->getStyle("A{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("H{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("I{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("K{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("J{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Warna status
                    $statusColor = match ($transaksi->status) {
                        'diterima'   => 'd4edda', // hijau muda
                        'disetujui'  => 'cce5ff', // biru muda
                        'dibatalkan' => 'f8d7da', // merah muda
                        default      => 'fff3cd', // kuning (draft)
                    };
                    $sheet->getStyle("K{$dataRow}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($statusColor);

                    // Border semua sel
                    $sheet->getStyle("A{$dataRow}:M{$dataRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
                        ],
                    ]);

                    // Baris selang-seling (cek SEBELUM increment)
                    if ($no % 2 === 0) {
                        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'L', 'M'] as $col) {
                            $sheet->getStyle("{$col}{$dataRow}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('f8f9fa');
                        }
                    }

                    $no++;
                    $dataRow++;
                }

                // ── Baris total ───────────────────────────────────────────────
                $totalNominal = $transaksis
                    ->whereIn('status', ['disetujui', 'diterima'])
                    ->sum('jumlah');

                $sheet->mergeCells("A{$dataRow}:I{$dataRow}");
                $sheet->setCellValue("A{$dataRow}", 'TOTAL NOMINAL (Diterima & Disetujui)');
                $sheet->setCellValue("J{$dataRow}", $totalNominal);
                $sheet->getStyle("J{$dataRow}")->getNumberFormat()->setFormatCode('#,##0');

                $sheet->getStyle("A{$dataRow}:M{$dataRow}")->applyFromArray([
                    'font'      => ['bold' => true],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e8f5e9']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                    'borders'   => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        'top'        => ['borderStyle' => Border::BORDER_MEDIUM],
                    ],
                ]);
                $sheet->getStyle("A{$dataRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // ── Freeze pane & Auto-filter ─────────────────────────────────
                $sheet->freezePane('A11');

                $lastDataRow = $dataRow - 1;
                if ($lastDataRow >= $headerRow + 1) {
                    $sheet->setAutoFilter("A{$headerRow}:M{$lastDataRow}");
                }

                // Nama sheet
                $sheet->setTitle('Laporan Penerimaan');
            },
        ];
    }
}