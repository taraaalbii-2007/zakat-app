<?php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use App\Models\SetorKas;
use App\Models\KasHarianAmil;
use App\Models\Amil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SetorKasController extends Controller
{
    /**
     * Pastikan amil yang login memiliki data amil terkait.
     */
    private function getAmil()
    {
        return Auth::user()->amil;
    }

    // ============================================
    // INDEX — Riwayat setoran milik amil ini
    // ============================================
    public function index(Request $request)
    {
        $amil = $this->getAmil();
        if (!$amil) abort(403);

        $query = SetorKas::byAmil($amil->id)
            ->with(['masjid', 'penerimaSetoran'])
            ->latest();

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'diterima', 'ditolak'])) {
            $query->byStatus($request->status);
        }

        // Filter tanggal dari
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_setor', '>=', $request->dari);
        }

        // Filter tanggal sampai
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_setor', '<=', $request->sampai);
        }

        $setorans = $query->paginate(10);

        // Summary counts
        $summary = SetorKas::byAmil($amil->id)
            ->selectRaw('status, COUNT(*) as total, SUM(jumlah_disetor) as jumlah')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('amil.setor-kas.index', compact('setorans', 'summary', 'amil'));
    }

    // ============================================
    // CREATE — Form buat setoran baru
    // ============================================
    public function create(Request $request)
    {
        $amil = $this->getAmil();
        if (!$amil) abort(403);

        // Ambil rekap kas harian jika ada periode yang diminta
        $rekapKas = null;
        $periodeDari  = $request->periode_dari;
        $periodeSampai = $request->periode_sampai;

        if ($periodeDari && $periodeSampai) {
            $rekapKas = $this->hitungRekapKas($amil->id, $amil->masjid_id, $periodeDari, $periodeSampai);
        }

        return view('amil.setor-kas.create', compact('amil', 'rekapKas', 'periodeDari', 'periodeSampai'));
    }

    // ============================================
    // STORE — Simpan setoran baru
    // ============================================
    public function store(Request $request)
    {
        $amil = $this->getAmil();
        if (!$amil) abort(403);

        $validated = $request->validate([
            'tanggal_setor'   => 'required|date',
            'periode_dari'    => 'required|date',
            'periode_sampai'  => 'required|date|after_or_equal:periode_dari',
            'jumlah_disetor'  => 'required|numeric|min:0',
            'keterangan'      => 'nullable|string|max:1000',
            'bukti_foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'tanda_tangan_amil' => 'nullable|string', // base64 dari signature pad
        ]);

        DB::beginTransaction();
        try {
            // Hitung rekap dari kas harian
            $rekap = $this->hitungRekapKas(
                $amil->id,
                $amil->masjid_id,
                $validated['periode_dari'],
                $validated['periode_sampai']
            );

            // Upload bukti foto
            $buktiFotoPath = null;
            if ($request->hasFile('bukti_foto')) {
                $buktiFotoPath = $request->file('bukti_foto')->store(
                    'setor-kas/bukti/' . date('Y/m'),
                    'public'
                );
            }

            // Simpan tanda tangan amil (base64 → file)
            $ttAmilPath = null;
            if ($request->filled('tanda_tangan_amil')) {
                $ttAmilPath = $this->saveSignature(
                    $request->tanda_tangan_amil,
                    'setor-kas/tanda-tangan/amil/' . date('Y/m')
                );
            }

            $setor = SetorKas::create([
                'tanggal_setor'               => $validated['tanggal_setor'],
                'periode_dari'                => $validated['periode_dari'],
                'periode_sampai'              => $validated['periode_sampai'],
                'amil_id'                     => $amil->id,
                'masjid_id'                   => $amil->masjid_id,
                'jumlah_disetor'              => $validated['jumlah_disetor'],
                'jumlah_dari_datang_langsung' => $rekap['datang_langsung'] ?? 0,
                'jumlah_dari_dijemput'        => $rekap['dijemput'] ?? 0,
                'bukti_foto'                  => $buktiFotoPath,
                'tanda_tangan_amil'           => $ttAmilPath,
                'keterangan'                  => $validated['keterangan'],
                'status'                      => 'pending',
            ]);

            DB::commit();

            return redirect()
                ->route('amil.setor-kas.show', $setor->uuid)
                ->with('success', 'Setoran kas berhasil diajukan. Menunggu konfirmasi admin masjid.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengajukan setoran: ' . $e->getMessage());
        }
    }

    // ============================================
    // SHOW — Detail setoran
    // ============================================
    public function show(SetorKas $setorKas)
    {
        $amil = $this->getAmil();
        if (!$amil || $setorKas->amil_id !== $amil->id) abort(403);

        $setorKas->load(['amil.pengguna', 'masjid', 'penerimaSetoran']);

        // Timeline events
        $timeline = $this->buildTimeline($setorKas);

        return view('amil.setor-kas.show', compact('setorKas', 'timeline'));
    }

    // ============================================
    // EDIT
    // ============================================
    public function edit(SetorKas $setorKas)
    {
        $amil = $this->getAmil();
        if (!$amil || $setorKas->amil_id !== $amil->id) abort(403);
        if (!$setorKas->bisa_diedit) abort(403, 'Setoran ini tidak dapat diedit.');

        $rekapKas = $this->hitungRekapKas(
            $amil->id,
            $amil->masjid_id,
            $setorKas->periode_dari->format('Y-m-d'),
            $setorKas->periode_sampai->format('Y-m-d')
        );

        return view('amil.setor-kas.edit', compact('setorKas', 'amil', 'rekapKas'));
    }

    // ============================================
    // UPDATE
    // ============================================
    public function update(Request $request, SetorKas $setorKas)
    {
        $amil = $this->getAmil();
        if (!$amil || $setorKas->amil_id !== $amil->id) abort(403);
        if (!$setorKas->bisa_diedit) abort(403);

        $validated = $request->validate([
            'tanggal_setor'   => 'required|date',
            'periode_dari'    => 'required|date',
            'periode_sampai'  => 'required|date|after_or_equal:periode_dari',
            'jumlah_disetor'  => 'required|numeric|min:0',
            'keterangan'      => 'nullable|string|max:1000',
            'bukti_foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'tanda_tangan_amil' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $rekap = $this->hitungRekapKas(
                $amil->id,
                $amil->masjid_id,
                $validated['periode_dari'],
                $validated['periode_sampai']
            );

            $data = [
                'tanggal_setor'               => $validated['tanggal_setor'],
                'periode_dari'                => $validated['periode_dari'],
                'periode_sampai'              => $validated['periode_sampai'],
                'jumlah_disetor'              => $validated['jumlah_disetor'],
                'jumlah_dari_datang_langsung' => $rekap['datang_langsung'] ?? 0,
                'jumlah_dari_dijemput'        => $rekap['dijemput'] ?? 0,
                'keterangan'                  => $validated['keterangan'],
            ];

            if ($request->hasFile('bukti_foto')) {
                if ($setorKas->bukti_foto) {
                    Storage::disk('public')->delete($setorKas->bukti_foto);
                }
                $data['bukti_foto'] = $request->file('bukti_foto')->store(
                    'setor-kas/bukti/' . date('Y/m'), 'public'
                );
            }

            if ($request->filled('tanda_tangan_amil')) {
                if ($setorKas->tanda_tangan_amil) {
                    Storage::disk('public')->delete($setorKas->tanda_tangan_amil);
                }
                $data['tanda_tangan_amil'] = $this->saveSignature(
                    $request->tanda_tangan_amil,
                    'setor-kas/tanda-tangan/amil/' . date('Y/m')
                );
            }

            $setorKas->update($data);
            DB::commit();

            return redirect()
                ->route('amil.setor-kas.show', $setorKas->uuid)
                ->with('success', 'Setoran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    // ============================================
    // DESTROY
    // ============================================
    public function destroy(SetorKas $setorKas)
    {
        $amil = $this->getAmil();
        if (!$amil || $setorKas->amil_id !== $amil->id) abort(403);
        if (!$setorKas->bisa_dihapus) abort(403);

        $setorKas->delete();

        return redirect()
            ->route('amil.setor-kas.index')
            ->with('success', 'Setoran berhasil dihapus.');
    }

    // ============================================
    // API — Hitung rekap kas periode
    // ============================================
    public function hitungRekapApi(Request $request)
    {
        $amil = $this->getAmil();
        if (!$amil) return response()->json(['error' => 'Unauthorized'], 403);

        $request->validate([
            'periode_dari'  => 'required|date',
            'periode_sampai'=> 'required|date|after_or_equal:periode_dari',
        ]);

        $rekap = $this->hitungRekapKas(
            $amil->id,
            $amil->masjid_id,
            $request->periode_dari,
            $request->periode_sampai
        );

        return response()->json($rekap);
    }

    // ============================================
    // PRIVATE HELPERS
    // ============================================
    private function hitungRekapKas(int $amilId, int $masjidId, string $dari, string $sampai): array
    {
        $kasHarians = KasHarianAmil::byAmil($amilId)
            ->byMasjid($masjidId)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->get();

        $totalPenerimaan    = $kasHarians->sum('total_penerimaan');
        $datangLangsung     = $kasHarians->sum(function ($kas) {
            // estimasi dari jumlah_datang_langsung * rata-rata (tidak ada kolom nominal per jenis)
            // Ambil langsung dari transaksi penerimaan
            return 0;
        });

        // Ambil dari transaksi penerimaan langsung untuk akurasi
        $transaksiPenerimaan = \App\Models\TransaksiPenerimaan::where('amil_id', $amilId)
            ->where('masjid_id', $masjidId)
            ->whereBetween('tanggal_transaksi', [$dari, $sampai])
            ->where('status', 'verified')
            ->get();

        $jumlahDatangLangsung = $transaksiPenerimaan
            ->where('metode_penerimaan', 'datang_langsung')
            ->sum('jumlah');

        $jumlahDijemput = $transaksiPenerimaan
            ->where('metode_penerimaan', 'dijemput')
            ->sum('jumlah');

        $total = $jumlahDatangLangsung + $jumlahDijemput;

        return [
            'datang_langsung'      => $jumlahDatangLangsung,
            'dijemput'             => $jumlahDijemput,
            'total'                => $total,
            'jumlah_hari'          => $kasHarians->count(),
            'periode_dari'         => $dari,
            'periode_sampai'       => $sampai,
            'datang_langsung_fmt'  => 'Rp ' . number_format($jumlahDatangLangsung, 0, ',', '.'),
            'dijemput_fmt'         => 'Rp ' . number_format($jumlahDijemput, 0, ',', '.'),
            'total_fmt'            => 'Rp ' . number_format($total, 0, ',', '.'),
        ];
    }

    private function saveSignature(string $base64, string $folder): string
    {
        // Hapus header data:image/png;base64,
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($imageData);

        $filename = $folder . '/' . uniqid('ttd_', true) . '.png';
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    private function buildTimeline(SetorKas $setor): array
    {
        $timeline = [];

        $timeline[] = [
            'label'  => 'Setoran Diajukan',
            'date'   => $setor->created_at->format('d M Y, H:i'),
            'icon'   => 'submit',
            'color'  => 'blue',
            'active' => true,
        ];

        if ($setor->status === 'diterima') {
            $timeline[] = [
                'label'  => 'Diterima oleh ' . ($setor->penerimaSetoran->username ?? '-'),
                'date'   => $setor->diterima_at?->format('d M Y, H:i') ?? '-',
                'icon'   => 'check',
                'color'  => 'green',
                'active' => true,
            ];
        } elseif ($setor->status === 'ditolak') {
            $timeline[] = [
                'label'  => 'Ditolak',
                'date'   => $setor->ditolak_at?->format('d M Y, H:i') ?? '-',
                'icon'   => 'x',
                'color'  => 'red',
                'active' => true,
            ];
        } else {
            $timeline[] = [
                'label'  => 'Menunggu Konfirmasi Admin',
                'date'   => null,
                'icon'   => 'clock',
                'color'  => 'yellow',
                'active' => false,
            ];
        }

        return $timeline;
    }
}