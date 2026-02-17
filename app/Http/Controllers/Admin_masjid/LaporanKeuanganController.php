<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\LaporanKeuanganMasjid;
use App\Models\Transaksi;
use App\Models\JenisZakat;
use App\Models\KategoriMustahik;
use App\Models\Mustahik;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $masjidId = session('masjid_id');

        // Ambil laporan untuk tahun tertentu
        $laporanTahunan = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();

        // Jika belum ada laporan untuk bulan tertentu, buat draft
        $bulanSekarang = date('n');
        $existingMonths = $laporanTahunan->pluck('bulan')->toArray();
        
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            if (!in_array($bulan, $existingMonths)) {
                $laporanTahunan->push(new LaporanKeuanganMasjid([
                    'masjid_id' => $masjidId,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'status' => 'draft',
                    'nama_bulan' => $this->getNamaBulan($bulan)
                ]));
            }
        }

        // Data untuk chart
        $chartData = $this->prepareChartData($laporanTahunan);

        // Data untuk filter tahun
        $availableYears = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->select(DB::raw('DISTINCT tahun'))
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (!in_array(date('Y'), $availableYears)) {
            $availableYears[] = date('Y');
        }

        sort($availableYears);

        return view('admin-masjid.laporan-keuangan.index', compact(
            'laporanTahunan',
            'tahun',
            'availableYears',
            'chartData'
        ));
    }

    public function show($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->with(['masjid', 'creator'])
            ->firstOrFail();

        // Pastikan hanya bisa akses laporan masjid sendiri
        if ($laporan->masjid_id != session('masjid_id')) {
            abort(403);
        }

        // Parse detail penerimaan dan penyaluran
        $detailPenerimaan = $this->parseDetailPenerimaan($laporan->detail_penerimaan);
        $detailPenyaluran = $this->parseDetailPenyaluran($laporan->detail_penyaluran);

        // Data untuk chart
        $chartPenerimaan = $this->preparePieChartData($detailPenerimaan, 'penerimaan');
        $chartPenyaluran = $this->preparePieChartData($detailPenyaluran, 'penyaluran');

        return view('admin-masjid.laporan-keuangan.show', compact(
            'laporan',
            'detailPenerimaan',
            'detailPenyaluran',
            'chartPenerimaan',
            'chartPenyaluran'
        ));
    }

    public function generate(Request $request, $tahun, $bulan)
    {
        $masjidId = session('masjid_id');
        
        // Cek apakah sudah ada laporan
        $existingLaporan = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if ($existingLaporan && $existingLaporan->status !== 'draft') {
            return redirect()->back()->with('error', 'Laporan sudah difinalisasi atau dipublikasi.');
        }

        // Hitung periode
        $periodeMulai = now()->setYear($tahun)->setMonth($bulan)->startOfMonth();
        $periodeSelesai = now()->setYear($tahun)->setMonth($bulan)->endOfMonth();

        // Ambil data transaksi untuk periode tersebut
        $transaksiMasuk = Transaksi::where('masjid_id', $masjidId)
            ->where('jenis_transaksi', 'masuk')
            ->whereBetween('tanggal_transaksi', [$periodeMulai, $periodeSelesai])
            ->where('status', 'completed')
            ->get();

        $transaksiKeluar = Transaksi::where('masjid_id', $masjidId)
            ->where('jenis_transaksi', 'keluar')
            ->whereBetween('tanggal_transaksi', [$periodeMulai, $periodeSelesai])
            ->where('status', 'completed')
            ->get();

        // Hitung saldo awal (saldo akhir bulan sebelumnya)
        $saldoAwal = $this->calculateSaldoAwal($masjidId, $tahun, $bulan);

        // Hitung total penerimaan dan penyaluran
        $totalPenerimaan = $transaksiMasuk->sum('jumlah');
        $totalPenyaluran = $transaksiKeluar->sum('jumlah');
        $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPenyaluran;

        // Hitung detail per jenis zakat
        $detailPenerimaan = $this->calculateDetailPenerimaan($transaksiMasuk);
        $detailPenyaluran = $this->calculateDetailPenyaluran($transaksiKeluar);

        // Hitung statistik
        $jumlahMuzakki = $transaksiMasuk->groupBy('muzakki_id')->count();
        $jumlahMustahik = $transaksiKeluar->groupBy('mustahik_id')->count();
        $jumlahTransaksiMasuk = $transaksiMasuk->count();
        $jumlahTransaksiKeluar = $transaksiKeluar->count();

        // Buat atau update laporan
        $laporanData = [
            'uuid' => $existingLaporan ? $existingLaporan->uuid : (string) Str::uuid(),
            'masjid_id' => $masjidId,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'periode_mulai' => $periodeMulai,
            'periode_selesai' => $periodeSelesai,
            'saldo_awal' => $saldoAwal,
            'total_penerimaan' => $totalPenerimaan,
            'total_penyaluran' => $totalPenyaluran,
            'saldo_akhir' => $saldoAkhir,
            'detail_penerimaan' => $detailPenerimaan,
            'detail_penyaluran' => $detailPenyaluran,
            'jumlah_muzakki' => $jumlahMuzakki,
            'jumlah_mustahik' => $jumlahMustahik,
            'jumlah_transaksi_masuk' => $jumlahTransaksiMasuk,
            'jumlah_transaksi_keluar' => $jumlahTransaksiKeluar,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ];

        if ($existingLaporan) {
            $existingLaporan->update($laporanData);
            $laporan = $existingLaporan;
        } else {
            $laporan = LaporanKeuanganMasjid::create($laporanData);
        }

        return redirect()->route('admin-masjid.laporan-keuangan.show', $laporan->uuid)
            ->with('success', 'Laporan berhasil digenerate.');
    }

    public function publish($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->where('masjid_id', session('masjid_id'))
            ->firstOrFail();

        if ($laporan->status === 'draft') {
            $laporan->status = 'published';
            $laporan->published_at = now();
            $laporan->save();

            return redirect()->back()->with('success', 'Laporan berhasil dipublikasi.');
        }

        return redirect()->back()->with('error', 'Laporan tidak dapat dipublikasi.');
    }

    // Public page untuk transparansi
    public function publicIndex(Request $request)
    {
        $masjidId = session('masjid_id');
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', null);

        $query = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('status', 'published');

        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        $query->where('tahun', $tahun);

        $laporan = $query->orderBy('bulan')->get();

        // Data untuk chart trend 6 bulan terakhir
        $trendData = $this->getTrendData($masjidId);

        // Data untuk filter
        $availableYears = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('status', 'published')
            ->select(DB::raw('DISTINCT tahun'))
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        return view('public.laporan-keuangan.index', compact(
            'laporan',
            'tahun',
            'bulan',
            'availableYears',
            'trendData'
        ));
    }

    public function publicShow($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->with(['masjid', 'creator'])
            ->where('status', 'published')
            ->firstOrFail();

        // Parse detail
        $detailPenerimaan = $this->parseDetailPenerimaan($laporan->detail_penerimaan);
        $detailPenyaluran = $this->parseDetailPenyaluran($laporan->detail_penyaluran);

        // Data untuk chart
        $chartPenerimaan = $this->preparePieChartData($detailPenerimaan, 'penerimaan');
        $chartPenyaluran = $this->preparePieChartData($detailPenyaluran, 'penyaluran');

        return view('public.laporan-keuangan.show', compact(
            'laporan',
            'detailPenerimaan',
            'detailPenyaluran',
            'chartPenerimaan',
            'chartPenyaluran'
        ));
    }

    // Helper methods
    private function calculateSaldoAwal($masjidId, $tahun, $bulan)
    {
        // Cari saldo akhir bulan sebelumnya
        if ($bulan == 1) {
            // Jika Januari, cari saldo akhir tahun sebelumnya
            $bulanSebelumnya = 12;
            $tahunSebelumnya = $tahun - 1;
        } else {
            $bulanSebelumnya = $bulan - 1;
            $tahunSebelumnya = $tahun;
        }

        $laporanSebelumnya = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('tahun', $tahunSebelumnya)
            ->where('bulan', $bulanSebelumnya)
            ->first();

        return $laporanSebelumnya ? $laporanSebelumnya->saldo_akhir : 0;
    }

    private function calculateDetailPenerimaan($transaksiMasuk)
    {
        $detail = [];
        
        // Group by jenis zakat
        foreach ($transaksiMasuk->groupBy('jenis_zakat_id') as $jenisZakatId => $transaksis) {
            $jenisZakat = JenisZakat::find($jenisZakatId);
            if ($jenisZakat) {
                $detail[] = [
                    'jenis_zakat' => $jenisZakat->nama,
                    'jumlah' => $transaksis->sum('jumlah'),
                    'count' => $transaksis->count()
                ];
            }
        }

        return $detail;
    }

    private function calculateDetailPenyaluran($transaksiKeluar)
    {
        $detail = [];
        
        // Group by kategori mustahik
        foreach ($transaksiKeluar as $transaksi) {
            if ($transaksi->mustahik_id) {
                $mustahik = Mustahik::find($transaksi->mustahik_id);
                if ($mustahik && $mustahik->kategori_mustahik_id) {
                    $kategori = KategoriMustahik::find($mustahik->kategori_mustahik_id);
                    if ($kategori) {
                        $kategoriKey = $kategori->nama;
                        if (!isset($detail[$kategoriKey])) {
                            $detail[$kategoriKey] = [
                                'jumlah' => 0,
                                'count' => 0
                            ];
                        }
                        $detail[$kategoriKey]['jumlah'] += $transaksi->jumlah;
                        $detail[$kategoriKey]['count']++;
                    }
                }
            }
        }

        // Convert to array format
        $result = [];
        foreach ($detail as $kategori => $data) {
            $result[] = [
                'kategori' => $kategori,
                'jumlah' => $data['jumlah'],
                'count' => $data['count']
            ];
        }

        return $result;
    }

    private function parseDetailPenerimaan($detail)
    {
        if (is_string($detail)) {
            $detail = json_decode($detail, true);
        }
        
        return is_array($detail) ? $detail : [];
    }

    private function parseDetailPenyaluran($detail)
    {
        if (is_string($detail)) {
            $detail = json_decode($detail, true);
        }
        
        return is_array($detail) ? $detail : [];
    }

    private function prepareChartData($laporanTahunan)
    {
        $bulanLabels = [];
        $penerimaanData = [];
        $penyaluranData = [];

        foreach ($laporanTahunan->sortBy('bulan') as $laporan) {
            $bulanLabels[] = substr($laporan->nama_bulan, 0, 3);
            $penerimaanData[] = (float) $laporan->total_penerimaan;
            $penyaluranData[] = (float) $laporan->total_penyaluran;
        }

        return [
            'labels' => $bulanLabels,
            'penerimaan' => $penerimaanData,
            'penyaluran' => $penyaluranData
        ];
    }

    private function preparePieChartData($detail, $type)
    {
        $labels = [];
        $data = [];
        $backgroundColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
            '#9966FF', '#FF9F40', '#8AC926', '#1982C4',
            '#6A4C93', '#FF595E', '#8AC926', '#1982C4'
        ];

        $i = 0;
        foreach ($detail as $item) {
            $labels[] = $item[$type === 'penerimaan' ? 'jenis_zakat' : 'kategori'];
            $data[] = (float) $item['jumlah'];
            $i++;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'backgroundColor' => array_slice($backgroundColors, 0, count($labels))
        ];
    }

    private function getTrendData($masjidId)
    {
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        $trendData = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('status', 'published')
            ->where('periode_mulai', '>=', $sixMonthsAgo)
            ->orderBy('periode_mulai')
            ->get();

        $labels = [];
        $penerimaan = [];
        $penyaluran = [];

        foreach ($trendData as $data) {
            $labels[] = $data->nama_bulan . ' ' . $data->tahun;
            $penerimaan[] = (float) $data->total_penerimaan;
            $penyaluran[] = (float) $data->total_penyaluran;
        }

        return [
            'labels' => $labels,
            'penerimaan' => $penerimaan,
            'penyaluran' => $penyaluran
        ];
    }

    private function getNamaBulan($bulan)
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $bulanList[$bulan] ?? '';
    }

     public function downloadPDF($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->with(['masjid', 'creator'])
            ->firstOrFail();

        // Pastikan hanya bisa akses laporan masjid sendiri
        if ($laporan->masjid_id != session('masjid_id')) {
            abort(403);
        }

        $detailPenerimaan = $this->parseDetailPenerimaan($laporan->detail_penerimaan);
        $detailPenyaluran = $this->parseDetailPenyaluran($laporan->detail_penyaluran);

        // Data untuk PDF
        $data = [
            'laporan' => $laporan,
            'detailPenerimaan' => $detailPenerimaan,
            'detailPenyaluran' => $detailPenyaluran,
            'logo' => $this->getLogoBase64(),
            'tanggalCetak' => now()->format('d F Y H:i:s'),
        ];

        // Load view PDF
        $pdf = Pdf::loadView('admin-masjid.laporan-keuangan.pdf', $data);

        // Konfigurasi PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('chroot', public_path());

        // Download PDF
        return $pdf->download("Laporan-Keuangan-{$laporan->masjid->nama}-{$laporan->tahun}-{$laporan->bulan}.pdf");
    }

    /**
     * Download PDF untuk laporan tahunan
     */
    public function downloadTahunanPDF(Request $request, $tahun)
    {
        $masjidId = session('masjid_id');
        
        $laporanTahunan = LaporanKeuanganMasjid::where('masjid_id', $masjidId)
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();

        if ($laporanTahunan->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data laporan untuk tahun ini.');
        }

        $masjid = $laporanTahunan->first()->masjid;
        $chartData = $this->prepareChartData($laporanTahunan);

        // Hitung total
        $totalPenerimaan = $laporanTahunan->sum('total_penerimaan');
        $totalPenyaluran = $laporanTahunan->sum('total_penyaluran');
        $totalMuzakki = $laporanTahunan->sum('jumlah_muzakki');
        $totalMustahik = $laporanTahunan->sum('jumlah_mustahik');

        $data = [
            'laporanTahunan' => $laporanTahunan,
            'masjid' => $masjid,
            'tahun' => $tahun,
            'chartData' => $chartData,
            'totalPenerimaan' => $totalPenerimaan,
            'totalPenyaluran' => $totalPenyaluran,
            'totalMuzakki' => $totalMuzakki,
            'totalMustahik' => $totalMustahik,
            'logo' => $this->getLogoBase64(),
            'tanggalCetak' => now()->format('d F Y H:i:s'),
        ];

        $pdf = Pdf::loadView('admin-masjid.laporan-keuangan.pdf-tahunan', $data);
        
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        
        return $pdf->download("Laporan-Tahunan-{$masjid->nama}-{$tahun}.pdf");
    }

    /**
     * Public PDF untuk transparansi
     */
    public function downloadPublicPDF($uuid)
    {
        $laporan = LaporanKeuanganMasjid::where('uuid', $uuid)
            ->with(['masjid', 'creator'])
            ->where('status', 'published')
            ->firstOrFail();

        $detailPenerimaan = $this->parseDetailPenerimaan($laporan->detail_penerimaan);
        $detailPenyaluran = $this->parseDetailPenyaluran($laporan->detail_penyaluran);

        $data = [
            'laporan' => $laporan,
            'detailPenerimaan' => $detailPenerimaan,
            'detailPenyaluran' => $detailPenyaluran,
            'logo' => $this->getLogoBase64(),
            'tanggalCetak' => now()->format('d F Y H:i:s'),
            'isPublic' => true,
        ];

        $pdf = Pdf::loadView('public.laporan-keuangan.pdf', $data);
        
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        
        return $pdf->download("Laporan-Publik-{$laporan->masjid->nama}-{$laporan->tahun}-{$laporan->bulan}.pdf");
    }

    /**
     * Helper method untuk mendapatkan logo base64
     */
    private function getLogoBase64()
    {
        try {
            $logoPath = public_path('images/logo-masjid.png');
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        } catch (\Exception $e) {
            // Log error jika diperlukan
        }
        
        return null;
    }
}