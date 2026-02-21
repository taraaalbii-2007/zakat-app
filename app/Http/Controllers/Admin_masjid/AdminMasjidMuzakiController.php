<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\Amil;
use App\Models\TransaksiPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminMasjidMuzakiController extends Controller
{
    public function index(Request $request)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;

        // Ambil semua amil aktif di masjid ini beserta jumlah muzakki uniknya
        $amils = Amil::where('masjid_id', $masjidId)
            ->withCount([
                'transaksiPenerimaan as jumlah_muzakki' => function ($q) {
                    $q->select(DB::raw('COUNT(DISTINCT muzakki_nama)'));
                },
                'transaksiPenerimaan as jumlah_transaksi',
                'transaksiPenerimaan as total_verified' => function ($q) {
                    $q->where('status', 'verified');
                },
            ])
            ->withSum([
                'transaksiPenerimaan as total_nominal' => function ($q) {
                    $q->where('status', 'verified');
                },
            ], 'jumlah')
            ->orderBy('nama_lengkap')
            ->get();

        // Filter pencarian amil
        if ($request->filled('q')) {
            $q     = strtolower($request->q);
            $amils = $amils->filter(fn($a) => str_contains(strtolower($a->nama_lengkap), $q)
                || str_contains(strtolower($a->kode_amil), $q));
        }

        // Summary keseluruhan masjid
        $summary = [
            'total_amil'      => Amil::where('masjid_id', $masjidId)->where('status', 'aktif')->count(),
            'total_muzakki'   => TransaksiPenerimaan::where('masjid_id', $masjidId)
                                    ->select('muzakki_nama')->distinct()->count(),
            'total_transaksi' => TransaksiPenerimaan::where('masjid_id', $masjidId)->count(),
            'total_nominal'   => TransaksiPenerimaan::where('masjid_id', $masjidId)
                                    ->where('status', 'verified')->sum('jumlah'),
        ];

        return view('admin-masjid.muzaki.index', compact('amils', 'summary'));
    }

    /**
     * AJAX: Ambil daftar muzaki per amil (untuk expandable row)
     */
    public function getMuzakiByAmil(Request $request, $amilId)
    {
        $user     = Auth::user();
        $masjidId = $user->masjid_id;

        // Pastikan amil milik masjid ini
        $amil = Amil::where('id', $amilId)
            ->where('masjid_id', $masjidId)
            ->firstOrFail();

        $query = TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('amil_id', $amilId)
            ->with(['jenisZakat', 'tipeZakat'])
            ->select(
                'muzakki_nama',
                'muzakki_telepon',
                'muzakki_email',
                'muzakki_alamat',
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(CASE WHEN status = "verified" THEN jumlah ELSE 0 END) as total_nominal'),
                DB::raw('MAX(tanggal_transaksi) as transaksi_terakhir'),
                DB::raw('GROUP_CONCAT(DISTINCT jenis_zakat_id) as jenis_zakat_ids')
            )
            ->groupBy('muzakki_nama', 'muzakki_telepon', 'muzakki_email', 'muzakki_alamat');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('muzakki_nama', 'like', "%{$search}%")
                    ->orWhere('muzakki_telepon', 'like', "%{$search}%")
                    ->orWhere('muzakki_email', 'like', "%{$search}%");
            });
        }

        $muzakkis = $query->orderBy('muzakki_nama')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'amil'     => $amil->only(['id', 'nama_lengkap', 'kode_amil', 'status']),
                'muzakkis' => $muzakkis,
            ]);
        }

        return response()->json(['success' => false], 400);
    }
}