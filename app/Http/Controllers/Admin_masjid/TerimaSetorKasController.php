<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\SetorKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TerimaSetorKasController extends Controller
{
    private function getMasjidId(): int
    {
        return Auth::user()->masjid_id;
    }

    // ============================================
    // INDEX — Daftar setoran pending
    // ============================================
    public function pending(Request $request)
    {
        $masjidId = $this->getMasjidId();

        $query = SetorKas::byMasjid($masjidId)
            ->pending()
            ->with(['amil.pengguna', 'masjid'])
            ->latest();

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $setorans = $query->paginate(15)->withQueryString();

        // Summary
        $summary = SetorKas::byMasjid($masjidId)
            ->selectRaw('status, COUNT(*) as total, SUM(jumlah_disetor) as jumlah')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('admin-masjid.setor-kas.pending', compact('setorans', 'summary'));
    }

    // ============================================
    // TERIMA — Proses terima atau tolak
    // ============================================
    public function proses(Request $request, SetorKas $setorKas)
    {
        if ($setorKas->masjid_id !== $this->getMasjidId()) abort(403);
        if ($setorKas->status !== 'pending') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'aksi'                  => 'required|in:diterima,ditolak',
            'jumlah_dihitung_fisik' => 'nullable|numeric|min:0',
            'tanda_tangan_penerima' => 'nullable|string', // base64
            'alasan_penolakan'      => 'required_if:aksi,ditolak|nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'status'                => $validated['aksi'],
                'diterima_oleh'         => Auth::id(),
                'jumlah_dihitung_fisik' => $validated['jumlah_dihitung_fisik'] ?? null,
            ];

            if ($validated['aksi'] === 'diterima') {
                $data['diterima_at'] = now();

                // Simpan tanda tangan penerima
                if ($request->filled('tanda_tangan_penerima')) {
                    $data['tanda_tangan_penerima'] = $this->saveSignature(
                        $request->tanda_tangan_penerima,
                        'setor-kas/tanda-tangan/penerima/' . date('Y/m')
                    );
                }
            } else {
                $data['ditolak_at']       = now();
                $data['alasan_penolakan'] = $validated['alasan_penolakan'];
            }

            $setorKas->update($data);
            DB::commit();

            $msg = $validated['aksi'] === 'diterima'
                ? 'Setoran berhasil diterima dan dikonfirmasi.'
                : 'Setoran ditolak. Amil akan diberitahu.';

            return redirect()
                ->route('admin-masjid.setor-kas.pending')
                ->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    // ============================================
    // SHOW — Detail setoran (admin masjid)
    // ============================================
    public function show(SetorKas $setorKas)
    {
        if ($setorKas->masjid_id !== $this->getMasjidId()) abort(403);
        $setorKas->load(['amil.pengguna', 'masjid', 'penerimaSetoran']);

        return view('admin-masjid.setor-kas.show', compact('setorKas'));
    }

    // ============================================
    // RIWAYAT — Semua setoran (semua status)
    // ============================================
    public function riwayat(Request $request)
    {
        $masjidId = $this->getMasjidId();

        $query = SetorKas::byMasjid($masjidId)
            ->with(['amil.pengguna', 'penerimaSetoran'])
            ->latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_setor', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_setor', '<=', $request->sampai);
        }
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $setorans = $query->paginate(15)->withQueryString();

        $summary = SetorKas::byMasjid($masjidId)
            ->selectRaw('status, COUNT(*) as total, SUM(jumlah_disetor) as jumlah')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('admin-masjid.setor-kas.riwayat', compact('setorans', 'summary'));
    }

    private function saveSignature(string $base64, string $folder): string
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($imageData);
        $filename  = $folder . '/' . uniqid('ttd_penerima_', true) . '.png';
        Storage::disk('public')->put($filename, $imageData);
        return $filename;
    }
}