<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenyaluran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalPenyaluranController extends Controller
{
    /**
     * Setujui transaksi penyaluran (draft → disetujui)
     */
    public function approve(Request $request, TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user   = Auth::user();
        $masjid = $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'draft', 403, 'Hanya transaksi draft yang dapat disetujui.');

        $transaksiPenyaluran->update([
            'status'      => 'disetujui',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Transaksi {$transaksiPenyaluran->no_transaksi} berhasil disetujui.");
    }

    /**
     * Tolak/batalkan transaksi penyaluran (draft → dibatalkan)
     */
    public function reject(Request $request, TransaksiPenyaluran $transaksiPenyaluran)
    {
        $user   = Auth::user();
        $masjid = $user->masjid;

        abort_if($transaksiPenyaluran->masjid_id !== $masjid->id, 403);
        abort_if($transaksiPenyaluran->status !== 'draft', 403, 'Hanya transaksi draft yang dapat ditolak.');

        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $transaksiPenyaluran->update([
            'status'             => 'dibatalkan',
            'alasan_pembatalan'  => $request->alasan_pembatalan,
            'dibatalkan_oleh'    => $user->id,
            'dibatalkan_at'      => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Transaksi {$transaksiPenyaluran->no_transaksi} telah ditolak.");
    }
}