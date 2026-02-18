<?php
// app/Http/Controllers/Admin_masjid/ProgramZakatController.php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\ProgramZakat;
use App\Models\TransaksiPenerimaan;
use App\Models\TransaksiPenyaluran;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProgramZakatController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================

    public function index(Request $request)
    {
        $user     = auth()->user();
        $masjidId = $user->masjid_id;

        $query = ProgramZakat::byMasjid($masjidId)
            ->orderBy('created_at', 'desc');

        if ($request->has('q') && $request->q) {
            $query->search($request->q);
        }
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        // Eager-load relasi transaksi agar progress_dana & progress_mustahik
        // dihitung via collection (tanpa N+1 query di index).
        // PENTING: sertakan kolom 'id' agar eager load bekerja dengan benar,
        // dan semua kolom yang dipakai accessor.
        $programs = $query->with([
            'transaksiPenerimaan' => fn ($q) => $q->select('id', 'program_zakat_id', 'jumlah', 'status'),
            'transaksiPenyaluran' => fn ($q) => $q->select('id', 'program_zakat_id', 'mustahik_id', 'status'),
        ])->paginate(10);

        $tahunList = ProgramZakat::byMasjid($masjidId)
            ->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin-masjid.program.index', compact('programs', 'tahunList'));
    }

    // ============================================================
    // CREATE
    // ============================================================

    public function create()
    {
        $user        = auth()->user();
        $masjid      = $user->masjid;
        $kodeProgram = ProgramZakat::generateKodeProgram($user->masjid_id);

        return view('admin-masjid.program.create', compact('masjid', 'kodeProgram'));
    }

    // ============================================================
    // STORE
    // ============================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program'    => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'target_dana'     => 'nullable|numeric|min:0',
            'target_mustahik' => 'nullable|integer|min:0',
            'foto_poster'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'          => 'required|in:draft,aktif',
        ], [
            'nama_program.required'             => 'Nama program harus diisi',
            'tanggal_mulai.required'            => 'Tanggal mulai harus diisi',
            'tanggal_selesai.after_or_equal'    => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'foto_poster.image'                 => 'File harus berupa gambar',
            'foto_poster.max'                   => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            $user                    = auth()->user();
            $validated['masjid_id'] = $user->masjid_id;

            // Generate kode program unik
            $kodeBase    = ProgramZakat::generateKodeProgram($user->masjid_id);
            $counter     = 1;
            $kodeProgram = $kodeBase;
            while (ProgramZakat::where('kode_program', $kodeProgram)->exists()) {
                $kodeProgram = $kodeBase . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                $counter++;
            }
            $validated['kode_program'] = $kodeProgram;

            // Upload foto poster jika ada
            $fotoKegiatan = [];
            if ($request->hasFile('foto_poster')) {
                $file     = $request->file('foto_poster');
                $filename = time() . '_poster_' . $file->getClientOriginalName();
                $path     = $file->storeAs('program-zakat/poster', $filename, 'public');
                $fotoKegiatan[] = $path;
            }
            $validated['foto_kegiatan'] = $fotoKegiatan;

            $program = ProgramZakat::create($validated);

            DB::commit();

            $message = $validated['status'] === 'draft'
                ? 'Program berhasil disimpan sebagai draft'
                : 'Program berhasil dibuat dan diaktifkan';

            return redirect()->route('program-zakat.index', $program->uuid)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan program: ' . $e->getMessage());
        }
    }

    // ============================================================
    // SHOW
    // ============================================================

    public function show($uuid)
    {
        $user = auth()->user();

        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        // ── Penerimaan Dana ──────────────────────────────────────────────────
        // Semua transaksi penerimaan yang memilih program ini, diurutkan terbaru.
        $penerimaanList = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'amil.pengguna'])
            ->where('program_zakat_id', $program->id)
            ->orderByDesc('tanggal_transaksi')
            ->orderByDesc('created_at')
            ->get();

        // Ringkasan penerimaan
        $totalPenerimaanVerified = $penerimaanList->where('status', 'verified')->sum('jumlah');
        $totalPenerimaanPending  = $penerimaanList->where('status', 'pending')->count();
        $totalPenerimaanTrx      = $penerimaanList->count();

        // ── Penyaluran ───────────────────────────────────────────────────────
        // Semua transaksi penyaluran yang terhubung program ini.
        $penyaluranList = TransaksiPenyaluran::with(['mustahik', 'kategoriMustahik', 'amil.pengguna'])
            ->where('program_zakat_id', $program->id)
            ->orderByDesc('tanggal_penyaluran')
            ->orderByDesc('created_at')
            ->get();

        // Ringkasan penyaluran
        $totalPenyaluranNominal = $penyaluranList
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->sum(fn ($t) => $t->metode_penyaluran === 'barang' ? ($t->nilai_barang ?? 0) : ($t->jumlah ?? 0));
        $totalMustahikUnik      = $penyaluranList
            ->whereIn('status', ['disetujui', 'disalurkan'])
            ->pluck('mustahik_id')
            ->unique()
            ->count();
        $totalPenyaluranTrx     = $penyaluranList->count();

        return view('admin-masjid.program.show', compact(
            'program',
            'penerimaanList',
            'totalPenerimaanVerified',
            'totalPenerimaanPending',
            'totalPenerimaanTrx',
            'penyaluranList',
            'totalPenyaluranNominal',
            'totalMustahikUnik',
            'totalPenyaluranTrx',
        ));
    }

    // ============================================================
    // EDIT
    // ============================================================

    public function edit($uuid)
    {
        $user    = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        if (in_array($program->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Program dengan status ' . $program->status . ' tidak dapat diedit');
        }

        return view('admin-masjid.program.edit', compact('program'));
    }

    // ============================================================
    // UPDATE
    // ============================================================

    public function update(Request $request, $uuid)
    {
        $user    = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        if (in_array($program->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Program dengan status ' . $program->status . ' tidak dapat diedit');
        }

        $validated = $request->validate([
            'nama_program'       => 'required|string|max:255',
            'deskripsi'          => 'nullable|string',
            'tanggal_mulai'      => 'required|date',
            'tanggal_selesai'    => 'nullable|date|after_or_equal:tanggal_mulai',
            'target_dana'        => 'nullable|numeric|min:0',
            'target_mustahik'    => 'nullable|integer|min:0',
            'catatan'            => 'nullable|string',
            'status'             => 'required|in:draft,aktif,selesai,dibatalkan',
            'foto_kegiatan.*'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_program.required'          => 'Nama program harus diisi',
            'tanggal_mulai.required'         => 'Tanggal mulai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'foto_kegiatan.*.image'          => 'File harus berupa gambar',
            'foto_kegiatan.*.max'            => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            $fotoKegiatan = $program->foto_kegiatan ?? [];
            if ($request->hasFile('foto_kegiatan')) {
                foreach ($request->file('foto_kegiatan') as $file) {
                    $filename       = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $path           = $file->storeAs('program-zakat/kegiatan', $filename, 'public');
                    $fotoKegiatan[] = $path;
                }
            }
            $validated['foto_kegiatan'] = $fotoKegiatan;

            $program->update($validated);
            DB::commit();

            return redirect()->route('program-zakat.show', $program->uuid)
                ->with('success', 'Program berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui program: ' . $e->getMessage());
        }
    }

    // ============================================================
    // DESTROY
    // ============================================================

    public function destroy($uuid)
    {
        $user    = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        if ($program->status !== 'draft') {
            return back()->with('error', 'Hanya program dengan status draft yang dapat dihapus');
        }

        DB::beginTransaction();
        try {
            if ($program->foto_kegiatan) {
                foreach ($program->foto_kegiatan as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }
            $program->delete();
            DB::commit();

            return redirect()->route('program-zakat.index')
                ->with('success', 'Program berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus program: ' . $e->getMessage());
        }
    }

    // ============================================================
    // UPLOAD FOTO
    // ============================================================

    public function uploadFoto(Request $request, $uuid)
    {
        $user    = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto.required' => 'Foto harus dipilih',
            'foto.image'    => 'File harus berupa gambar',
            'foto.max'      => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            $fotoKegiatan   = $program->foto_kegiatan ?? [];
            $file           = $request->file('foto');
            $filename       = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $path           = $file->storeAs('program-zakat/kegiatan', $filename, 'public');
            $fotoKegiatan[] = $path;

            $program->update(['foto_kegiatan' => $fotoKegiatan]);
            DB::commit();

            return back()->with('success', 'Foto berhasil diunggah');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengunggah foto: ' . $e->getMessage());
        }
    }

    // ============================================================
    // DELETE FOTO
    // ============================================================

    public function deleteFoto($uuid, $index)
    {
        $user    = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $fotoKegiatan = $program->foto_kegiatan ?? [];
            if (isset($fotoKegiatan[$index])) {
                Storage::disk('public')->delete($fotoKegiatan[$index]);
                unset($fotoKegiatan[$index]);
                $fotoKegiatan = array_values($fotoKegiatan);
                $program->update(['foto_kegiatan' => $fotoKegiatan]);
            }
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus']);
            }
            return back()->with('success', 'Foto berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus foto: ' . $e->getMessage());
        }
    }

    // ============================================================
    // CHANGE STATUS
    // ============================================================

    public function changeStatus(Request $request, $uuid)
    {
        $user    = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        $request->validate([
            'status'  => 'required|in:draft,aktif,selesai,dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $updateData = ['status' => $request->status];
            if ($request->catatan) {
                $updateData['catatan'] = $request->catatan;
            }
            $program->update($updateData);
            DB::commit();

            $label = ['draft' => 'draft', 'aktif' => 'aktif', 'selesai' => 'selesai', 'dibatalkan' => 'dibatalkan'];
            return back()->with('success', 'Status program berhasil diubah menjadi ' . ($label[$request->status] ?? $request->status));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());
        }
    }
}