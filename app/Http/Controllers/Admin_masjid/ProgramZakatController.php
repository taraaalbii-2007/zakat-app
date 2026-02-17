<?php
// app/Http/Controllers/Admin_masjid/ProgramZakatController.php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\ProgramZakat;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProgramZakatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        $query = ProgramZakat::byMasjid($masjidId)
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('q') && $request->q) {
            $query->search($request->q);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by periode
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        $programs = $query->paginate(10);

        // Get available years for filter
        $tahunList = ProgramZakat::byMasjid($masjidId)
            ->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin-masjid.program.index', compact('programs', 'tahunList'));
    }

    public function create()
    {
        $user = auth()->user();
        $masjid = $user->masjid;
        
        // Generate kode program
        $kodeProgram = ProgramZakat::generateKodeProgram($user->masjid_id);

        return view('admin-masjid.program.create', compact('masjid', 'kodeProgram'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'target_dana' => 'nullable|numeric|min:0',
            'target_mustahik' => 'nullable|integer|min:0',
            'foto_poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,aktif',
        ], [
            'nama_program.required' => 'Nama program harus diisi',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'foto_poster.image' => 'File harus berupa gambar',
            'foto_poster.max' => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $validated['masjid_id'] = $user->masjid_id;
            
            // Generate kode program unik
            $kodeBase = ProgramZakat::generateKodeProgram($user->masjid_id);
            $counter = 1;
            $kodeProgram = $kodeBase;
            
            while (ProgramZakat::where('kode_program', $kodeProgram)->exists()) {
                $kodeProgram = $kodeBase . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                $counter++;
            }
            
            $validated['kode_program'] = $kodeProgram;

            // Upload foto poster jika ada
            $fotoKegiatan = [];
            if ($request->hasFile('foto_poster')) {
                $file = $request->file('foto_poster');
                $filename = time() . '_poster_' . $file->getClientOriginalName();
                $path = $file->storeAs('program-zakat/poster', $filename, 'public');
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

    public function show($uuid)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        // TODO: Get related transactions when transaksi table is ready
        // $penerimaanTerkait = Penerimaan::where('program_id', $program->id)->get();
        // $penyaluranTerkait = Penyaluran::where('program_id', $program->id)->get();

        return view('admin-masjid.program.show', compact('program'));
    }

    public function edit($uuid)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        // Tidak bisa edit program yang sudah selesai atau dibatalkan
        if (in_array($program->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Program dengan status ' . $program->status . ' tidak dapat diedit');
        }

        return view('admin-masjid.program.edit', compact('program'));
    }

    public function update(Request $request, $uuid)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        // Tidak bisa edit program yang sudah selesai atau dibatalkan
        if (in_array($program->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Program dengan status ' . $program->status . ' tidak dapat diedit');
        }

        $validated = $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'target_dana' => 'nullable|numeric|min:0',
            'target_mustahik' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
            'status' => 'required|in:draft,aktif,selesai,dibatalkan',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_program.required' => 'Nama program harus diisi',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'foto_kegiatan.*.image' => 'File harus berupa gambar',
            'foto_kegiatan.*.max' => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto kegiatan tambahan jika ada
            $fotoKegiatan = $program->foto_kegiatan ?? [];
            
            if ($request->hasFile('foto_kegiatan')) {
                foreach ($request->file('foto_kegiatan') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('program-zakat/kegiatan', $filename, 'public');
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

    public function destroy($uuid)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        // Hanya bisa hapus program dengan status draft
        if ($program->status !== 'draft') {
            return back()->with('error', 'Hanya program dengan status draft yang dapat dihapus');
        }

        DB::beginTransaction();
        try {
            // Hapus foto-foto
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

    public function uploadFoto(Request $request, $uuid)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto.required' => 'Foto harus dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            $fotoKegiatan = $program->foto_kegiatan ?? [];
            
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('program-zakat/kegiatan', $filename, 'public');
            $fotoKegiatan[] = $path;
            
            $program->update(['foto_kegiatan' => $fotoKegiatan]);

            DB::commit();

            return back()->with('success', 'Foto berhasil diunggah');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengunggah foto: ' . $e->getMessage());
        }
    }

    public function deleteFoto($uuid, $index)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $fotoKegiatan = $program->foto_kegiatan ?? [];
            
            if (isset($fotoKegiatan[$index])) {
                Storage::disk('public')->delete($fotoKegiatan[$index]);
                unset($fotoKegiatan[$index]);
                $fotoKegiatan = array_values($fotoKegiatan); // Re-index array
                
                $program->update(['foto_kegiatan' => $fotoKegiatan]);
            }

            DB::commit();

            // Return JSON response for AJAX request
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto berhasil dihapus'
                ]);
            }

            return back()->with('success', 'Foto berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON response for AJAX request
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus foto: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menghapus foto: ' . $e->getMessage());
        }
    }

    public function changeStatus(Request $request, $uuid)
    {
        $user = auth()->user();
        $program = ProgramZakat::where('uuid', $uuid)
            ->byMasjid($user->masjid_id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:draft,aktif,selesai,dibatalkan',
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

            $statusLabels = [
                'draft' => 'draft',
                'aktif' => 'aktif',
                'selesai' => 'selesai',
                'dibatalkan' => 'dibatalkan',
            ];

            return back()->with('success', 'Status program berhasil diubah menjadi ' . $statusLabels[$request->status]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());
        }
    }
}