<?php

namespace App\Exports;

use App\Models\TransaksiPenerimaan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransaksiPenerimaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;
    protected $user;
    protected $masjid;

    public function __construct($filters, $user, $masjid)
    {
        $this->filters = $filters;
        $this->user = $user;
        $this->masjid = $masjid;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = TransaksiPenerimaan::with([
            'jenisZakat', 
            'tipeZakat', 
            'programZakat', 
            'amil'
        ])->byMasjid($this->masjid->id);

        // Apply filters
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

    public function headings(): array
    {
        return [
            'NO. TRANSAKSI',
            'TANGGAL',
            'MUZAKKI',
            'TELEPON',
            'JENIS ZAKAT',
            'TIPE ZAKAT',
            'PROGRAM',
            'METODE PENERIMAAN',
            'METODE PEMBAYARAN',
            'JUMLAH (Rp)',
            'STATUS',
            'AMIL',
            'KETERANGAN'
        ];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->no_transaksi,
            $transaksi->tanggal_transaksi->format('d/m/Y H:i'),
            $transaksi->muzakki_nama,
            $transaksi->muzakki_telepon ?? '-',
            $transaksi->jenisZakat->nama ?? '-',
            $transaksi->tipeZakat->nama ?? '-',
            $transaksi->programZakat->nama_program ?? '-',
            $transaksi->metode_penerimaan_label ?? '-',
            $transaksi->metode_pembayaran_label ?? '-',
            $transaksi->jumlah > 0 ? number_format($transaksi->jumlah, 0, ',', '.') : '0',
            $transaksi->status_label,
            $transaksi->amil->pengguna->name ?? '-',
            $transaksi->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style untuk seluruh data
        $sheet->getStyle('A2:M' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border untuk seluruh data
        $sheet->getStyle('A1:M' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        return [];
    }
}