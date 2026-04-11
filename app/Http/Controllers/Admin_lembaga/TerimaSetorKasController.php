<?php

namespace App\Http\Controllers\Admin_lembaga;

use App\Http\Controllers\Controller;
use App\Models\SetorKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TerimaSetorKasController extends Controller
{
    private function getLembagaId(): int
    {
        return Auth::user()->lembaga_id;
    }

    // ============================================
    // INDEX — Daftar setoran pending
    // ============================================
    public function pending(Request $request)
    {
        $lembagaId = $this->getLembagaId();

        $query = SetorKas::byLembaga($lembagaId)
            ->pending()
            ->with(['amil.pengguna', 'lembaga'])
            ->latest();

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $setorans = $query->paginate(10);

        // Summary
        $summary = SetorKas::byLembaga($lembagaId)
            ->selectRaw('status, COUNT(*) as total, SUM(jumlah_disetor) as jumlah')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $breadcrumbs = [
            'Kelola Setor Kas' => route('admin-lembaga.setor-kas.pending'),
        ];

        return view('admin-lembaga.setor-kas.pending', compact('setorans', 'summary', 'breadcrumbs'));
    }

    // ============================================
    // TERIMA — Proses terima atau tolak
    // ============================================
    public function proses(Request $request, SetorKas $setorKas)
    {
        if ($setorKas->lembaga_id !== $this->getLembagaId()) abort(403);
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
                ->route('admin-lembaga.setor-kas.pending')
                ->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    // ============================================
    // SHOW — Detail setoran (admin lembaga)
    // ============================================
    public function show(SetorKas $setorKas)
    {
        if ($setorKas->lembaga_id !== $this->getLembagaId()) abort(403);
        $setorKas->load(['amil.pengguna', 'lembaga', 'penerimaSetoran']);

        return view('admin-lembaga.setor-kas.show', compact('setorKas'));
    }

    public function riwayat(Request $request)
    {
        $lembagaId = $this->getLembagaId();

        $query = SetorKas::byLembaga($lembagaId)
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

        $setorans = $query->paginate(10);

        $summary = SetorKas::byLembaga($lembagaId)
            ->selectRaw('status, COUNT(*) as total, SUM(jumlah_disetor) as jumlah')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Hitung jumlah pending untuk badge di tombol
        $pendingCount = SetorKas::byLembaga($lembagaId)->pending()->count();

        return view('admin-lembaga.setor-kas.riwayat', compact('setorans', 'summary', 'pendingCount'));
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