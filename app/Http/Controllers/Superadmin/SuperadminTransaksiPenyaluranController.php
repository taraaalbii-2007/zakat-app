<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperadminTransaksiPenyaluranController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk lembaga dengan filter
        $query = Lembaga::with(['transaksiPenyaluran' => function ($q) use ($request) {
            $q->with('mustahik')->orderBy('tanggal_penyaluran', 'desc');
            
            // Filter status
            if ($request->has('status') && $request->status) {
                $q->where('status', $request->status);
            }
            
            // Filter metode penyaluran
            if ($request->has('metode_penyaluran') && $request->metode_penyaluran) {
                $q->where('metode_penyaluran', $request->metode_penyaluran);
            }
            
            // Filter tanggal
            if ($request->has('start_date') && $request->start_date) {
                $q->whereDate('tanggal_penyaluran', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $q->whereDate('tanggal_penyaluran', '<=', $request->end_date);
            }
            
            // Filter pencarian
            if ($request->has('q') && $request->q) {
                $q->where(function($subq) use ($request) {
                    $subq->whereHas('mustahik', function($mustahikQuery) use ($request) {
                        $mustahikQuery->where('nama_lengkap', 'like', '%' . $request->q . '%');
                    })->orWhere('no_transaksi', 'like', '%' . $request->q . '%');
                });
            }
        }])
        ->withCount(['transaksiPenyaluran' => function ($q) use ($request) {
            // Filter status untuk count
            if ($request->has('status') && $request->status) {
                $q->where('status', $request->status);
            }
            
            // Filter metode penyaluran untuk count
            if ($request->has('metode_penyaluran') && $request->metode_penyaluran) {
                $q->where('metode_penyaluran', $request->metode_penyaluran);
            }
            
            // Filter tanggal untuk count
            if ($request->has('start_date') && $request->start_date) {
                $q->whereDate('tanggal_penyaluran', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $q->whereDate('tanggal_penyaluran', '<=', $request->end_date);
            }
            
            // Filter pencarian untuk count
            if ($request->has('q') && $request->q) {
                $q->where(function($subq) use ($request) {
                    $subq->whereHas('mustahik', function($mustahikQuery) use ($request) {
                        $mustahikQuery->where('nama_lengkap', 'like', '%' . $request->q . '%');
                    })->orWhere('no_transaksi', 'like', '%' . $request->q . '%');
                });
            }
        }]);
        
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
        
        $lembagas = $query->get();
        
        // Hitung statistik dengan filter
        $totalTransaksi = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenyaluran->count();
        });
        
        $totalDraft = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenyaluran->where('status', 'draft')->count();
        });
        
        $totalDisalurkan = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenyaluran->where('status', 'disalurkan')->count();
        });
        
        $totalNominal = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenyaluran->sum('jumlah');
        });
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Siapkan data penyaluran untuk JavaScript
                $penyaluranData = [];
                foreach ($lembagas as $lembaga) {
                    $penyaluranData[$lembaga->id] = $lembaga->transaksiPenyaluran->map(function ($trx) {
                        return [
                            'mustahik'      => optional($trx->mustahik)->nama_lengkap ?? '-',
                            'no_transaksi'  => $trx->no_transaksi ?? '-',
                            'tanggal'       => optional($trx->tanggal_penyaluran)->format('d/m/Y') ?? '-',
                            'metode'        => $trx->metode_penyaluran ?? '-',
                            'jumlah'        => (float) ($trx->jumlah ?? 0),
                            'status'        => $trx->status ?? '-',
                        ];
                    })->toArray();
                }
                
                // Render HTML untuk tabel
                $html = view('superadmin.transaksi-penyaluran.partials.table', compact('lembagas'))->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalTransaksi' => $totalTransaksi,
                    'totalDraft' => $totalDraft,
                    'totalDisalurkan' => $totalDisalurkan,
                    'totalNominal' => $totalNominal,
                    'totalLembaga' => $lembagas->count(),
                    'penyaluranData' => $penyaluranData
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        
        $breadcrumbs = [
            'Kelola Penyaluran' => null,
        ];
        
        return view('superadmin.transaksi-penyaluran.index',
            compact('lembagas', 'totalTransaksi', 'totalDraft', 'totalDisalurkan', 'totalNominal', 'breadcrumbs'));
    }
}