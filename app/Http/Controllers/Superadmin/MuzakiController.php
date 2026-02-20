<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuzakiController extends Controller
{
    /**
     * Display muzakki grouped by masjid (accordion view).
     */
    public function index(Request $request)
    {
        // Ambil semua masjid aktif
        $masjids = Masjid::where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama', 'kode_masjid', 'alamat']);

        // Untuk setiap masjid, ambil daftar muzaki (aggregated)
        $masjids->each(function ($masjid) {
            $muzakkis = DB::table('transaksi_penerimaan as tp')
                ->where('tp.masjid_id', $masjid->id)
                ->whereNotNull('tp.muzakki_nama')
                ->select([
                    'tp.muzakki_nama',
                    'tp.muzakki_telepon',
                    'tp.muzakki_email',
                    'tp.muzakki_nik',
                    'tp.masjid_id',
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
                ])
                ->orderBy('tp.muzakki_nama')
                ->get();

            $masjid->muzakkis      = $muzakkis;
            $masjid->muzakkiCount  = $muzakkis->count();
            $masjid->totalNominal  = $muzakkis->sum('total_nominal');
        });

        // Summary stats global
        $stats = [
            'total_muzakki_unik' => DB::table('transaksi_penerimaan')
                ->whereNotNull('muzakki_nama')
                ->selectRaw('COUNT(DISTINCT CONCAT(muzakki_nama, "-", masjid_id)) as cnt')
                ->value('cnt'),

            'total_transaksi' => DB::table('transaksi_penerimaan')->count(),

            'total_nominal' => DB::table('transaksi_penerimaan')
                ->where('status', 'verified')
                ->sum('jumlah'),

            'nominal_bulan_ini' => DB::table('transaksi_penerimaan')
                ->where('status', 'verified')
                ->whereMonth('tanggal_transaksi', now()->month)
                ->whereYear('tanggal_transaksi', now()->year)
                ->sum('jumlah'),

            'transaksi_bulan_ini' => DB::table('transaksi_penerimaan')
                ->where('status', 'verified')
                ->whereMonth('tanggal_transaksi', now()->month)
                ->count(),
        ];

        return view('superadmin.muzaki.index', compact('masjids', 'stats'));
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