<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class LaporanKonsolidasiExport implements FromArray, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnWidths
{
    protected $data;
    protected $type;
    protected $sheetIndex = 0;
    
    public function __construct($data)
    {
        $this->data = $data;
        $this->type = $data['type'] ?? 'konsolidasi';
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        $titles = [
            'konsolidasi' => 'Konsolidasi',
            'penerimaan' => 'Penerimaan',
            'penyaluran' => 'Penyaluran'
        ];
        
        return $titles[$this->type] ?? 'Laporan';
    }
    
    /**
     * @return array
     */
    public function array(): array
    {
        $rows = [];
        
        if ($this->type == 'konsolidasi') {
            // Ringkasan Bulanan
            foreach ($this->data['ringkasan_bulanan'] ?? [] as $item) {
                $rows[] = [
                    'periode' => $item['periode'],
                    'penerimaan' => $item['total_penerimaan'],
                    'trans_penerimaan' => $item['jumlah_transaksi_penerimaan'],
                    'muzakki' => $item['jumlah_muzakki'],
                    'penyaluran' => $item['total_penyaluran'],
                    'trans_penyaluran' => $item['jumlah_transaksi_penyaluran'],
                    'mustahik' => $item['jumlah_mustahik'],
                    'saldo' => $item['total_penerimaan'] - $item['total_penyaluran']
                ];
            }
            
            // Tambahkan baris kosong
            $rows[] = [];
            $rows[] = ['BREAKDOWN PER JENIS ZAKAT'];
            
            // Breakdown Jenis Zakat
            foreach ($this->data['breakdown_jenis_zakat'] ?? [] as $item) {
                $rows[] = [
                    'jenis_zakat' => $item->nama_zakat,
                    'jml_transaksi' => $item->jumlah_transaksi,
                    'jml_muzakki' => $item->jumlah_muzakki,
                    'total' => $item->total_nominal,
                    'persentase' => round(($item->total_nominal / ($this->data['total_penerimaan'] ?: 1)) * 100, 2) . '%'
                ];
            }
            
            // Tambahkan baris kosong
            $rows[] = [];
            $rows[] = ['BREAKDOWN PER KATEGORI MUSTAHIK'];
            
            // Breakdown Kategori Mustahik
            foreach ($this->data['breakdown_kategori_mustahik'] ?? [] as $item) {
                $rows[] = [
                    'kategori' => $item->nama_kategori,
                    'jml_transaksi' => $item->jumlah_transaksi,
                    'jml_mustahik' => $item->jumlah_mustahik,
                    'total' => $item->total_nominal,
                    'persentase' => round(($item->total_nominal / ($this->data['total_penyaluran'] ?: 1)) * 100, 2) . '%'
                ];
            }
        }
        
        if ($this->type == 'penerimaan' && !empty($this->data['penerimaan_detail'])) {
            foreach ($this->data['penerimaan_detail'] as $trans) {
                $rows[] = [
                    'no_transaksi' => $trans->no_transaksi,
                    'tanggal' => Carbon::parse($trans->tanggal_transaksi)->format('d/m/Y'),
                    'muzakki' => $trans->muzakki_nama,
                    'jenis_zakat' => $trans->jenis_zakat_nama,
                    'program' => $trans->program_zakat_nama,
                    'jumlah' => $trans->jumlah,
                    'metode' => $trans->metode_pembayaran,
                    'no_referensi' => $trans->no_referensi_transfer,
                    'amil' => $trans->amil_nama,
                    'status' => $trans->status
                ];
            }
        }
        
        if ($this->type == 'penyaluran' && !empty($this->data['penyaluran_detail'])) {
            foreach ($this->data['penyaluran_detail'] as $trans) {
                $nominal = $trans->metode_penyaluran == 'barang' ? 
                    ($trans->nilai_barang ?? 0) : ($trans->jumlah ?? 0);
                    
                $rows[] = [
                    'no_transaksi' => $trans->no_transaksi_penyaluran ?? $trans->no_transaksi,
                    'tanggal' => Carbon::parse($trans->tanggal_penyaluran)->format('d/m/Y'),
                    'mustahik' => $trans->nama_mustahik ?? $trans->mustahik_nama,
                    'kategori' => $trans->kategori_mustahik_nama,
                    'program' => $trans->program_penyaluran_nama,
                    'jumlah' => $nominal,
                    'metode' => $trans->metode_penyaluran,
                    'amil' => $trans->amil_nama,
                    'keterangan' => $trans->keterangan
                ];
            }
        }
        
        return $rows;
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        if ($this->type == 'konsolidasi') {
            return [
                'Periode',
                'Penerimaan (Rp)',
                'Trans Penerimaan',
                'Jml Muzakki',
                'Penyaluran (Rp)',
                'Trans Penyaluran',
                'Jml Mustahik',
                'Saldo (Rp)'
            ];
        }
        
        if ($this->type == 'penerimaan') {
            return [
                'No. Transaksi',
                'Tanggal',
                'Muzakki',
                'Jenis Zakat',
                'Program',
                'Jumlah (Rp)',
                'Metode',
                'No. Referensi',
                'Amil',
                'Status'
            ];
        }
        
        if ($this->type == 'penyaluran') {
            return [
                'No. Transaksi',
                'Tanggal',
                'Mustahik',
                'Kategori',
                'Program',
                'Jumlah (Rp)',
                'Metode',
                'Amil',
                'Keterangan'
            ];
        }
        
        return [];
    }
    
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        if (!is_array($row)) {
            return [];
        }
        
        return array_values($row);
    }
    
    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A7A4A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Border untuk semua sel
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Alignment untuk kolom tertentu
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        return [];
    }
    
    /**
     * @return array
     */
    public function columnWidths(): array
    {
        if ($this->type == 'konsolidasi') {
            return [
                'A' => 20, // Periode
                'B' => 18, // Penerimaan
                'C' => 15, // Trans Penerimaan
                'D' => 12, // Muzakki
                'E' => 18, // Penyaluran
                'F' => 15, // Trans Penyaluran
                'G' => 12, // Mustahik
                'H' => 18, // Saldo
            ];
        }
        
        if ($this->type == 'penerimaan') {
            return [
                'A' => 18, // No Transaksi
                'B' => 12, // Tanggal
                'C' => 25, // Muzakki
                'D' => 15, // Jenis Zakat
                'E' => 25, // Program
                'F' => 18, // Jumlah
                'G' => 12, // Metode
                'H' => 18, // No Referensi
                'I' => 20, // Amil
                'J' => 12, // Status
            ];
        }
        
        if ($this->type == 'penyaluran') {
            return [
                'A' => 18, // No Transaksi
                'B' => 12, // Tanggal
                'C' => 25, // Mustahik
                'D' => 18, // Kategori
                'E' => 25, // Program
                'F' => 18, // Jumlah
                'G' => 12, // Metode
                'H' => 20, // Amil
                'I' => 25, // Keterangan
            ];
        }
        
        return [];
    }
}