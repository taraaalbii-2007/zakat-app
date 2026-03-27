<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuperadminAmilController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk lembaga dengan filter
        $query = Lembaga::with(['amils' => function ($q) use ($request) {
            $q->orderBy('nama_lengkap');
            
            // Filter status amil
            if ($request->has('status') && $request->status) {
                $q->where('status', $request->status);
            }
        }])
        ->withCount(['amils' => function ($q) use ($request) {
            // Filter status amil untuk count
            if ($request->has('status') && $request->status) {
                $q->where('status', $request->status);
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
        
        // Hitung total amil dengan filter
        $totalAmil = $lembagas->sum(function($lembaga) {
            return $lembaga->amils->count();
        });
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Siapkan data amil untuk JavaScript
                $amilData = [];
                foreach ($lembagas as $lembaga) {
                    $amilData[$lembaga->id] = $lembaga->amils->map(function ($amil) {
                        $hasFoto = $amil->foto && Storage::disk('public')->exists($amil->foto);
                        $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                        $avatarColors = [
                            'ABCD' => 'bg-primary-500',
                            'EFGH' => 'bg-green-500',
                            'IJKL' => 'bg-purple-500',
                            'MNOP' => 'bg-orange-500',
                            'QRST' => 'bg-red-500',
                            'UVWX' => 'bg-teal-500',
                        ];
                        $avatarBg = 'bg-gray-500';
                        foreach ($avatarColors as $letters => $color) {
                            if (in_array($initial, str_split($letters))) {
                                $avatarBg = $color;
                                break;
                            }
                        }
                        return [
                            'nama'          => $amil->nama_lengkap,
                            'kode'          => $amil->kode_amil,
                            'jenis_kelamin' => $amil->jenis_kelamin,
                            'telepon'       => $amil->telepon ?? '-',
                            'email'         => $amil->email ?? '-',
                            'status'        => $amil->status,
                            'foto_url'      => $hasFoto ? Storage::url($amil->foto) : null,
                            'initial'       => $initial,
                            'avatar_bg'     => $avatarBg,
                        ];
                    })->toArray();
                }
                
                // Render HTML untuk tabel
                $html = view('superadmin.amil.partials.table', compact('lembagas'))->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalAmil' => $totalAmil,
                    'totalLembaga' => $lembagas->count(),
                    'amilData' => $amilData
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        
        $breadcrumbs = [
            'Kelola Amil' => null,
        ];
        
        return view('superadmin.amil.index', compact('lembagas', 'totalAmil', 'breadcrumbs'));
    }
}