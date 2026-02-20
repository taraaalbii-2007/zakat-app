<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuzakiController extends Controller
{
    /**
     * Display a listing of all muzakki across all masjid.
     * Data diambil dari tabel transaksi_penerimaan (aggregated per muzakki_nama + masjid).
     */
    public function index(Request $request)
    {
        $query = DB::table('transaksi_penerimaan as tp')
            ->join('masjid as m', 'tp.masjid_id', '=', 'm.id')
            ->select([
                'tp.muzakki_nama',
                'tp.muzakki_telepon',
                'tp.muzakki_email',
                'tp.muzakki_nik',
                'tp.masjid_id',
                'm.nama as nama_masjid',
                'm.kode_masjid',
                DB::raw('COUNT(tp.id) as total_transaksi'),
                DB::raw('SUM(CASE WHEN tp.status = "verified" THEN tp.jumlah ELSE 0 END) as total_nominal'),
                DB::raw('MAX(tp.tanggal_transaksi) as transaksi_terakhir'),
                DB::raw('MIN(tp.tanggal_transaksi) as transaksi_pertama'),
                DB::raw('COUNT(CASE WHEN tp.status = "verified" THEN 1 END) as total_verified'),
                DB::raw('COUNT(CASE WHEN tp.status = "pending" THEN 1 END) as total_pending'),
            ])
            ->groupBy([
                'tp.muzakki_nama',
                'tp.muzakki_telepon',
                'tp.muzakki_email',
                'tp.muzakki_nik',
                'tp.masjid_id',
                'm.nama',
                'm.kode_masjid',
            ]);

        // Filter pencarian (nama, email, telepon, NIK)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('tp.muzakki_nama', 'like', "%{$search}%")
                  ->orWhere('tp.muzakki_email', 'like', "%{$search}%")
                  ->orWhere('tp.muzakki_telepon', 'like', "%{$search}%")
                  ->orWhere('tp.muzakki_nik', 'like', "%{$search}%");
            });
        }

        // Filter by masjid
        if ($request->filled('masjid_id')) {
            $query->where('tp.masjid_id', $request->masjid_id);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tp.tanggal_transaksi', $request->tahun);
        }

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tp.tanggal_transaksi', $request->bulan);
        }

        // Sorting
        $allowedSort = ['muzakki_nama', 'total_transaksi', 'total_nominal', 'transaksi_terakhir', 'nama_masjid'];
        $sortBy    = in_array($request->get('sort_by'), $allowedSort) ? $request->get('sort_by') : 'transaksi_terakhir';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $muzakkiList = $query->paginate(10);

        // Summary stats (global, semua masjid)
        $stats = [
            'total_muzakki_unik' => DB::table('transaksi_penerimaan')
                ->whereNotNull('muzakki_nama')
                ->selectRaw('COUNT(DISTINCT CONCAT(muzakki_nama, "-", masjid_id)) as cnt')
                ->value('cnt'),

            'total_transaksi'    => DB::table('transaksi_penerimaan')->count(),

            'total_nominal'      => DB::table('transaksi_penerimaan')
                ->where('status', 'verified')
                ->sum('jumlah'),

            'nominal_bulan_ini'  => DB::table('transaksi_penerimaan')
                ->where('status', 'verified')
                ->whereMonth('tanggal_transaksi', now()->month)
                ->whereYear('tanggal_transaksi', now()->year)
                ->sum('jumlah'),

            'transaksi_bulan_ini' => DB::table('transaksi_penerimaan')
                ->where('status', 'verified')
                ->whereMonth('tanggal_transaksi', now()->month)
                ->whereYear('tanggal_transaksi', now()->year)
                ->count(),
        ];

        // Daftar masjid untuk filter dropdown
        $masjidList = Masjid::where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama', 'kode_masjid']);

        // Daftar tahun untuk filter dropdown
        $tahunList = DB::table('transaksi_penerimaan')
            ->selectRaw('YEAR(tanggal_transaksi) as tahun')
            ->whereNotNull('tanggal_transaksi')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('superadmin.muzaki.index', compact(
            'muzakkiList',
            'stats',
            'masjidList',
            'tahunList'
        ));
    }

    /**
     * Show riwayat transaksi lengkap seorang muzakki.
     * Diidentifikasi berdasarkan muzakki_nama + masjid_id dari query string.
     */
    public function show(Request $request)
    {
        $muzakkiNama = $request->query('nama');
        $masjidId    = $request->query('masjid_id');

        abort_if(!$muzakkiNama || !$masjidId, 404);

        $masjid = Masjid::findOrFail($masjidId);

        // Biodata ringkas dari transaksi terbaru
        $biodata = DB::table('transaksi_penerimaan')
            ->where('muzakki_nama', $muzakkiNama)
            ->where('masjid_id', $masjidId)
            ->orderByDesc('tanggal_transaksi')
            ->first(['muzakki_nama', 'muzakki_telepon', 'muzakki_email', 'muzakki_alamat', 'muzakki_nik']);

        abort_if(!$biodata, 404);

        // Riwayat transaksi dengan detail
        $transaksi = DB::table('transaksi_penerimaan as tp')
            ->leftJoin('jenis_zakat as jz', 'tp.jenis_zakat_id', '=', 'jz.id')
            ->leftJoin('tipe_zakat as tz', 'tp.tipe_zakat_id', '=', 'tz.id')
            ->where('tp.muzakki_nama', $muzakkiNama)
            ->where('tp.masjid_id', $masjidId)
            ->select([
                'tp.uuid',
                'tp.no_transaksi',
                'tp.tanggal_transaksi',
                'tp.jumlah',
                'tp.status',
                'tp.metode_pembayaran',
                'tp.metode_penerimaan',
                'jz.nama as jenis_zakat',
                'tz.nama as tipe_zakat',
            ])
            ->orderByDesc('tp.tanggal_transaksi')
            ->paginate(10);

        // Summary muzakki ini
        $summary = DB::table('transaksi_penerimaan')
            ->where('muzakki_nama', $muzakkiNama)
            ->where('masjid_id', $masjidId)
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(CASE WHEN status = "verified" THEN jumlah ELSE 0 END) as total_nominal,
                MAX(tanggal_transaksi) as transaksi_terakhir,
                MIN(tanggal_transaksi) as transaksi_pertama,
                COUNT(CASE WHEN status = "verified" THEN 1 END) as total_verified,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as total_pending,
                COUNT(CASE WHEN status = "rejected" THEN 1 END) as total_rejected
            ')
            ->first();

        // Breakdown per jenis zakat
        $breakdownJenis = DB::table('transaksi_penerimaan as tp')
            ->join('jenis_zakat as jz', 'tp.jenis_zakat_id', '=', 'jz.id')
            ->where('tp.muzakki_nama', $muzakkiNama)
            ->where('tp.masjid_id', $masjidId)
            ->where('tp.status', 'verified')
            ->groupBy('jz.id', 'jz.nama')
            ->select([
                'jz.nama',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(tp.jumlah) as total_nominal'),
            ])
            ->orderByDesc('total_nominal')
            ->get();

        return view('superadmin.muzaki.show', compact(
            'biodata',
            'masjid',
            'transaksi',
            'summary',
            'breakdownJenis'
        ));
    }
}