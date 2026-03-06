<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimoniSuperadminController extends Controller
{
    /**
     * Daftar semua testimoni (pending & approved) dengan filter.
     */
    public function index(Request $request)
    {
        $query = Testimoni::with(['muzakki', 'approvedBy'])->latest();

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_pengirim', 'like', "%{$q}%")
                    ->orWhere('isi_testimoni', 'like', "%{$q}%");
            });
        }

        $testimonis   = $query->paginate(10);
        $totalPending  = Testimoni::where('is_approved', false)->count();
        $totalApproved = Testimoni::where('is_approved', true)->count();

        return view('superadmin.testimoni.index', compact(
            'testimonis',
            'totalPending',
            'totalApproved'
        ));
    }

    /**
     * Detail satu testimoni.
     */
    public function show(Testimoni $testimoni)
    {
        $testimoni->load(['muzakki', 'transaksi', 'approvedBy']);
        return view('superadmin.testimoni.show', compact('testimoni'));
    }

    /**
     * Approve testimoni — tampil di landing page.
     */
    public function approve(Testimoni $testimoni)
    {
        $testimoni->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Testimoni berhasil disetujui dan akan tampil di landing page.');
    }

    /**
     * Reject / batalkan approval testimoni — sembunyikan dari landing page.
     */
    public function reject(Testimoni $testimoni)
    {
        $testimoni->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()->back()->with('success', 'Testimoni berhasil ditolak dan disembunyikan dari landing page.');
    }

    /**
     * Hapus permanen.
     */
    public function destroy(Testimoni $testimoni)
    {
        $testimoni->delete();
        return redirect()->route('superadmin.testimoni.index')
            ->with('success', 'Testimoni berhasil dihapus.');
    }
}