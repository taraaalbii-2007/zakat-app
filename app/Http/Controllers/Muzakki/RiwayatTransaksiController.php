<?php

namespace App\Http\Controllers\Muzakki;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenerimaan;
use App\Models\JenisZakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatTransaksiController extends Controller
{
    protected $user;
    protected $muzakki;
    protected $masjid;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user || !$this->user->isMuzakki()) {
                abort(403, 'Hanya muzakki yang dapat mengakses halaman ini.');
            }

            $this->muzakki = $this->user->muzakki;
            if (!$this->muzakki) {
                return redirect()->route('dashboard')
                    ->with('error', 'Profil muzakki belum dilengkapi.');
            }

            $this->masjid = $this->muzakki->masjid;
            if (!$this->masjid) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda belum memilih masjid.');
            }

            view()->share([
                'masjid'  => $this->masjid,
                'muzakki' => $this->muzakki,
            ]);

            return $next($request);
        });
    }

    // ================================================================
    // INDEX — Riwayat transaksi (view only, no create/edit/show)
    // ================================================================
    public function index(Request $request)
    {
        $query = TransaksiPenerimaan::with([
            'jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna',
        ])->where('muzakki_id', $this->muzakki->id);

        // Pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_transaksi', 'like', "%{$q}%")
                    ->orWhere('muzakki_nama', 'like', "%{$q}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis zakat
        if ($request->filled('jenis_zakat_id')) {
            $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        }

        // Filter metode penerimaan
        if ($request->filled('metode_penerimaan')) {
            $query->where('metode_penerimaan', $request->metode_penerimaan);
        }

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        $transaksis     = $query->orderBy('tanggal_transaksi', 'desc')->paginate(15);
        $jenisZakatList = JenisZakat::orderBy('nama')->get();

        // Stats aggregate
        $statsRaw = TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)
            ->selectRaw("
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) AS total_verified,
                SUM(CASE WHEN status = 'pending'  THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS total_rejected,
                SUM(CASE WHEN status = 'verified' THEN jumlah       ELSE 0 END) AS total_nominal,
                SUM(CASE WHEN status = 'verified' THEN jumlah_infaq ELSE 0 END) AS total_infaq
            ")
            ->first();

        $stats = [
            'total'          => (int)   $statsRaw->total,
            'total_verified' => (int)   $statsRaw->total_verified,
            'total_pending'  => (int)   $statsRaw->total_pending,
            'total_rejected' => (int)   $statsRaw->total_rejected,
            'total_nominal'  => (float) $statsRaw->total_nominal,
            'total_infaq'    => (float) $statsRaw->total_infaq,
        ];

        return view('muzakki.riwayat-transaksi.index', compact(
            'transaksis',
            'jenisZakatList',
            'stats'
        ));
    }
}