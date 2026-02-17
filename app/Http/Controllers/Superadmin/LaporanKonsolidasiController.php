<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ViewLaporanKonsolidasi;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKonsolidasiController extends Controller
{
    public function index(Request $request)
    {
        $query = ViewLaporanKonsolidasi::with('masjid');

        // Filter by search (masjid name)
        if ($request->filled('search')) {
            $query->whereHas('masjid', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by masjid
        if ($request->filled('masjid_id')) {
            $query->where('masjid_id', $request->masjid_id);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        $laporan = $query->orderByPeriode('desc')->paginate(10);

        return view('superadmin.laporan-konsolidasi.index', compact('laporan'));
    }

    /**
     * Display the specified resource (detail per masjid).
     */
    public function show($masjidId)
    {
        // Get masjid
        $masjid = Masjid::where('id', $masjidId)->firstOrFail();

        // Get 12 bulan terakhir
        $laporanBulanan = ViewLaporanKonsolidasi::byMasjid($masjidId)
            ->orderByPeriode('desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        // Summary statistics
        $totalPenerimaan = $laporanBulanan->sum('total_penerimaan');
        $totalPenyaluran = $laporanBulanan->sum('total_penyaluran');
        $saldoTerakhir = $laporanBulanan->last()->saldo_akhir ?? 0;
        $totalMuzakki = $laporanBulanan->sum('jumlah_muzakki');
        $totalMustahik = $laporanBulanan->sum('jumlah_mustahik');

        // Data untuk chart
        $chartLabels = $laporanBulanan->map(function ($item) {
            return $item->bulan_nama . ' ' . $item->tahun;
        });

        $chartPenerimaan = $laporanBulanan->pluck('total_penerimaan');
        $chartPenyaluran = $laporanBulanan->pluck('total_penyaluran');

        // Breakdown per jenis zakat (contoh query - sesuaikan dengan tabel transaksi Anda)
        $breakdownJenisZakat = DB::table('transaksi_zakat')
            ->join('jenis_zakat', 'transaksi_zakat.jenis_zakat_id', '=', 'jenis_zakat.id')
            ->where('transaksi_zakat.masjid_id', $masjidId)
            ->whereIn(
                DB::raw('CONCAT(YEAR(transaksi_zakat.tanggal), "-", LPAD(MONTH(transaksi_zakat.tanggal), 2, "0"))'),
                $laporanBulanan->map(function ($item) {
                    return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
                })->toArray()
            )
            ->select(
                'jenis_zakat.nama_zakat',
                DB::raw('SUM(transaksi_zakat.jumlah) as total')
            )
            ->groupBy('jenis_zakat.id', 'jenis_zakat.nama_zakat')
            ->orderBy('total', 'desc')
            ->get();

        // Breakdown per kategori mustahik (contoh query - sesuaikan dengan tabel penyaluran Anda)
        $breakdownMustahik = DB::table('penyaluran_zakat')
            ->join('kategori_mustahik', 'penyaluran_zakat.kategori_mustahik_id', '=', 'kategori_mustahik.id')
            ->where('penyaluran_zakat.masjid_id', $masjidId)
            ->whereIn(
                DB::raw('CONCAT(YEAR(penyaluran_zakat.tanggal), "-", LPAD(MONTH(penyaluran_zakat.tanggal), 2, "0"))'),
                $laporanBulanan->map(function ($item) {
                    return $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
                })->toArray()
            )
            ->select(
                'kategori_mustahik.nama_kategori',
                DB::raw('COUNT(DISTINCT penyaluran_zakat.mustahik_id) as jumlah_penerima'),
                DB::raw('SUM(penyaluran_zakat.jumlah) as total')
            )
            ->groupBy('kategori_mustahik.id', 'kategori_mustahik.nama_kategori')
            ->orderBy('total', 'desc')
            ->get();

        return view('superadmin.laporan-konsolidasi.show', compact(
            'masjid',
            'laporanBulanan',
            'totalPenerimaan',
            'totalPenyaluran',
            'saldoTerakhir',
            'totalMuzakki',
            'totalMustahik',
            'chartLabels',
            'chartPenerimaan',
            'chartPenyaluran',
            'breakdownJenisZakat',
            'breakdownMustahik'
        ));
    }

    /**
     * Export laporan (opsional)
     */
    public function export($masjidId, Request $request)
    {
        // Implementasi export Excel/PDF
        // Menggunakan library seperti Maatwebsite\Excel atau DomPDF
    }
}