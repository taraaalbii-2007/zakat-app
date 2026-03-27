<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\KategoriMustahik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuperadminMustahikController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk lembaga dengan filter mustahik
        $query = Lembaga::with(['mustahiks' => function ($q) use ($request) {
            $q->with('kategoriMustahik')->orderBy('nama_lengkap');
            
            // Filter status verifikasi
            if ($request->has('status_verifikasi') && $request->status_verifikasi) {
                $q->where('status_verifikasi', $request->status_verifikasi);
            }
            
            // Filter keaktifan
            if ($request->has('is_active') && $request->is_active !== '') {
                $q->where('is_active', $request->is_active);
            }
            
            // Filter kategori
            if ($request->has('kategori_id') && $request->kategori_id) {
                $q->where('kategori_mustahik_id', $request->kategori_id);
            }
        }])
        ->withCount(['mustahiks' => function ($q) use ($request) {
            // Filter status verifikasi untuk count
            if ($request->has('status_verifikasi') && $request->status_verifikasi) {
                $q->where('status_verifikasi', $request->status_verifikasi);
            }
            
            // Filter keaktifan untuk count
            if ($request->has('is_active') && $request->is_active !== '') {
                $q->where('is_active', $request->is_active);
            }
            
            // Filter kategori untuk count
            if ($request->has('kategori_id') && $request->kategori_id) {
                $q->where('kategori_mustahik_id', $request->kategori_id);
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
        
        // Hitung total mustahik dengan filter
        $totalMustahik = $lembagas->sum(function($lembaga) {
            return $lembaga->mustahiks->count();
        });
        
        // Ambil daftar kategori mustahik untuk filter
        // PERBAIKAN: Hapus filter is_active jika kolom tidak ada
        // Atau jika sudah menambahkan kolom is_active, gunakan kode di bawah:
        try {
            $kategoriList = KategoriMustahik::orderBy('nama')->get();
        } catch (\Exception $e) {
            // Fallback jika kolom is_active belum ada
            $kategoriList = KategoriMustahik::orderBy('nama')->get();
        }
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Siapkan data mustahik untuk JavaScript
                $mustahikData = [];
                foreach ($lembagas as $lembaga) {
                    $mustahikData[$lembaga->id] = $lembaga->mustahiks->map(function ($m) {
                        return [
                            'no_registrasi'     => $m->no_registrasi ?? '-',
                            'tanggal'           => $m->tanggal_registrasi ? $m->tanggal_registrasi->format('d M Y') : '-',
                            'nama'              => $m->nama_lengkap,
                            'initial'           => strtoupper(substr($m->nama_lengkap, 0, 1)),
                            'nik'               => $m->nik ?? null,
                            'kategori'          => $m->kategoriMustahik->nama ?? null,
                            'status_verifikasi' => $m->status_verifikasi,
                            'is_active'         => (bool) $m->is_active,
                        ];
                    })->toArray();
                }
                
                // Render HTML untuk tabel
                $html = view('superadmin.mustahik.partials.table', compact('lembagas'))->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalMustahik' => $totalMustahik,
                    'totalLembaga' => $lembagas->count(),
                    'mustahikData' => $mustahikData,
                    'kategoriList' => $kategoriList
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        
        $breadcrumbs = [
            'Kelola Mustahik' => null,
        ];
        
        return view('superadmin.mustahik.index', compact('lembagas', 'totalMustahik', 'kategoriList', 'breadcrumbs'));
    }
}