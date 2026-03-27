<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\JenisZakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperadminTransaksiPenerimaanController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk lembaga dengan filter
        $query = Lembaga::with(['transaksiPenerimaan' => function ($q) use ($request) {
            $q->with('jenisZakat')->orderBy('tanggal_transaksi', 'desc');
            
            // Filter status
            if ($request->has('status') && $request->status) {
                $q->where('status', $request->status);
            }
            
            // Filter jenis zakat
            if ($request->has('jenis_zakat_id') && $request->jenis_zakat_id) {
                $q->where('jenis_zakat_id', $request->jenis_zakat_id);
            }
            
            // Filter tanggal
            if ($request->has('start_date') && $request->start_date) {
                $q->whereDate('tanggal_transaksi', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $q->whereDate('tanggal_transaksi', '<=', $request->end_date);
            }
            
            // Filter pencarian
            if ($request->has('q') && $request->q) {
                $q->where(function($subq) use ($request) {
                    $subq->where('muzakki_nama', 'like', '%' . $request->q . '%')
                         ->orWhere('no_transaksi', 'like', '%' . $request->q . '%');
                });
            }
        }])
        ->withCount(['transaksiPenerimaan' => function ($q) use ($request) {
            // Filter status untuk count
            if ($request->has('status') && $request->status) {
                $q->where('status', $request->status);
            }
            
            // Filter jenis zakat untuk count
            if ($request->has('jenis_zakat_id') && $request->jenis_zakat_id) {
                $q->where('jenis_zakat_id', $request->jenis_zakat_id);
            }
            
            // Filter tanggal untuk count
            if ($request->has('start_date') && $request->start_date) {
                $q->whereDate('tanggal_transaksi', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $q->whereDate('tanggal_transaksi', '<=', $request->end_date);
            }
            
            // Filter pencarian untuk count
            if ($request->has('q') && $request->q) {
                $q->where(function($subq) use ($request) {
                    $subq->where('muzakki_nama', 'like', '%' . $request->q . '%')
                         ->orWhere('no_transaksi', 'like', '%' . $request->q . '%');
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
            return $lembaga->transaksiPenerimaan->count();
        });
        
        $totalVerified = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenerimaan->where('status', 'verified')->count();
        });
        
        $totalPending = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenerimaan->where('status', 'pending')->count();
        });
        
        $totalNominal = $lembagas->sum(function($lembaga) {
            return $lembaga->transaksiPenerimaan->sum('jumlah');
        });
        
        // Ambil daftar jenis zakat untuk filter
        $jenisZakatList = JenisZakat::orderBy('nama')
            ->get();
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Siapkan data transaksi untuk JavaScript
                $trxData = [];
                foreach ($lembagas as $lembaga) {
                    $trxData[$lembaga->id] = $lembaga->transaksiPenerimaan->map(function ($trx) {
                        return [
                            'muzakki_nama'    => $trx->muzakki_nama ?? '-',
                            'no_transaksi'    => $trx->no_transaksi ?? '-',
                            'tanggal'         => optional($trx->tanggal_transaksi)->format('d/m/Y') ?? '-',
                            'waktu'           => optional($trx->waktu_transaksi)->format('H:i') ?? '',
                            'jenis_zakat'     => $trx->jenisZakat->nama ?? '-',
                            'jumlah'          => (float) ($trx->jumlah ?? 0),
                            'status'          => $trx->status ?? '-',
                        ];
                    })->toArray();
                }
                
                // Render HTML untuk tabel
                $html = view('superadmin.transaksi-penerimaan.partials.table', compact('lembagas'))->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalTransaksi' => $totalTransaksi,
                    'totalVerified' => $totalVerified,
                    'totalPending' => $totalPending,
                    'totalNominal' => $totalNominal,
                    'totalLembaga' => $lembagas->count(),
                    'trxData' => $trxData
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        
        $breadcrumbs = [
            'Kelola Transaksi' => null,
        ];
        
        return view('superadmin.transaksi-penerimaan.index',
            compact('lembagas', 'totalTransaksi', 'totalVerified', 'totalPending', 'totalNominal', 'jenisZakatList', 'breadcrumbs'));
    }
}