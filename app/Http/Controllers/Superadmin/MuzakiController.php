<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuzakiController extends Controller
{
    /**
     * Display muzakki grouped by lembaga (accordion view).
     */
    public function index(Request $request)
    {
        // Query untuk lembaga aktif dengan filter
        $query = Lembaga::where('is_active', true);
        
        // Filter lembaga berdasarkan ID
        if ($request->has('lembaga_id') && $request->lembaga_id) {
            $query->where('id', $request->lembaga_id);
        }
        
        // Filter pencarian nama lembaga
        if ($request->has('q') && $request->q) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }
        
        // Urutkan lembaga berdasarkan nama
        $query->orderBy('nama');
        
        $lembagas = $query->get(['id', 'nama', 'kode_lembaga', 'alamat']);

        // Untuk setiap lembaga, ambil daftar muzaki (aggregated)
        foreach ($lembagas as $lembaga) {
            // Query untuk muzaki dengan filter pencarian
            $muzakiQuery = DB::table('transaksi_penerimaan as tp')
                ->leftJoin('jenis_zakat as jz', 'tp.jenis_zakat_id', '=', 'jz.id')
                ->where('tp.lembaga_id', $lembaga->id)
                ->whereNotNull('tp.muzakki_nama');
            
            // Filter pencarian nama muzaki
            if ($request->has('q') && $request->q) {
                $muzakiQuery->where('tp.muzakki_nama', 'like', '%' . $request->q . '%');
            }
            
            $muzakkis = $muzakiQuery->select([
                    'tp.muzakki_nama',
                    'tp.muzakki_telepon',
                    'tp.muzakki_email',
                    'tp.muzakki_nik',
                    'tp.lembaga_id',
                    DB::raw('COUNT(tp.id) as total_transaksi'),
                    DB::raw('SUM(CASE WHEN tp.status = "verified" THEN tp.jumlah ELSE 0 END) as total_nominal'),
                    DB::raw('MAX(tp.tanggal_transaksi) as transaksi_terakhir'),
                    DB::raw('MIN(tp.tanggal_transaksi) as transaksi_pertama'),
                    DB::raw('COUNT(CASE WHEN tp.status = "verified" THEN 1 END) as total_verified'),
                    DB::raw('COUNT(CASE WHEN tp.status = "pending" THEN 1 END) as total_pending'),
                    DB::raw('GROUP_CONCAT(DISTINCT jz.nama ORDER BY jz.nama SEPARATOR ",") as jenis_zakat_list'),
                ])
                ->groupBy([
                    'tp.muzakki_nama',
                    'tp.muzakki_telepon',
                    'tp.muzakki_email',
                    'tp.muzakki_nik',
                    'tp.lembaga_id',
                ])
                ->orderBy('tp.muzakki_nama')
                ->get();

            $lembaga->muzakkis      = $muzakkis;
            $lembaga->muzakkiCount  = $muzakkis->count();
            $lembaga->totalNominal  = $muzakkis->sum('total_nominal');
        }

        // Summary stats global
        $stats = [
            'total_muzakki_unik' => DB::table('transaksi_penerimaan')
                ->whereNotNull('muzakki_nama')
                ->selectRaw('COUNT(DISTINCT CONCAT(muzakki_nama, "-", lembaga_id)) as cnt')
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
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Siapkan data muzaki untuk JavaScript
                $muzakiData = [];
                foreach ($lembagas as $lembaga) {
                    $muzakiData[$lembaga->id] = $lembaga->muzakkis->map(function ($m) use ($lembaga) {
                        $jenisZakat = collect(
                            array_filter(explode(',', $m->jenis_zakat_list ?? ''))
                        )->unique()->values();
                        
                        return [
                            'nama'              => $m->muzakki_nama,
                            'initial'           => strtoupper(substr($m->muzakki_nama, 0, 1)),
                            'nik'               => $m->muzakki_nik ?? null,
                            'telepon'           => $m->muzakki_telepon ?? null,
                            'email'             => $m->muzakki_email ?? null,
                            'jenis_zakat'       => $jenisZakat->toArray(),
                            'total_transaksi'   => $m->total_transaksi,
                            'total_nominal'     => (int) $m->total_nominal,
                            'transaksi_terakhir'=> $m->transaksi_terakhir
                                ? \Carbon\Carbon::parse($m->transaksi_terakhir)->translatedFormat('d M Y')
                                : '-',
                            'detail_url'        => route('muzaki.show', [
                                'nama'       => $m->muzakki_nama,
                                'lembaga_id' => $lembaga->id,
                            ]),
                        ];
                    })->toArray();
                }
                
                // Render HTML untuk tabel
                $html = view('superadmin.muzaki.partials.table', compact('lembagas'))->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalMuzaki' => $stats['total_muzakki_unik'],
                    'totalLembaga' => $lembagas->count(),
                    'muzakiData' => $muzakiData
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return view('superadmin.muzaki.index', compact('lembagas', 'stats'));
    }

    /**
     * Show riwayat transaksi lengkap seorang muzakki.
     */
    public function show(Request $request)
    {
        $muzakkiNama = $request->query('nama');
        $lembagaId   = $request->query('lembaga_id');

        abort_if(!$muzakkiNama || !$lembagaId, 404);

        $lembaga = Lembaga::findOrFail($lembagaId);

        // Biodata ringkas dari transaksi terbaru
        $biodata = DB::table('transaksi_penerimaan')
            ->where('muzakki_nama', $muzakkiNama)
            ->where('lembaga_id', $lembagaId)
            ->orderByDesc('tanggal_transaksi')
            ->first(['muzakki_nama', 'muzakki_telepon', 'muzakki_email', 'muzakki_alamat', 'muzakki_nik']);

        abort_if(!$biodata, 404);

        // Riwayat transaksi dengan detail
        $transaksi = DB::table('transaksi_penerimaan as tp')
            ->leftJoin('jenis_zakat as jz', 'tp.jenis_zakat_id', '=', 'jz.id')
            ->leftJoin('tipe_zakat as tz', 'tp.tipe_zakat_id', '=', 'tz.id')
            ->where('tp.muzakki_nama', $muzakkiNama)
            ->where('tp.lembaga_id', $lembagaId)
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
            ->where('lembaga_id', $lembagaId)
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
            ->where('tp.lembaga_id', $lembagaId)
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
            'lembaga',
            'transaksi',
            'summary',
            'breakdownJenis'
        ));
    }
}