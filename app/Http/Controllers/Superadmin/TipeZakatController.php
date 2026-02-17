<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TipeZakat;
use App\Models\JenisZakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TipeZakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TipeZakat::with('jenisZakat');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('ketentuan_khusus', 'like', "%{$search}%")
                    ->orWhereHas('jenisZakat', function ($subQ) use ($search) {
                        $subQ->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan jenis zakat
        if ($request->filled('jenis_zakat_id')) {
            $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        }

        // Filter berdasarkan haul
        if ($request->filled('requires_haul')) {
            $query->where('requires_haul', $request->requires_haul === 'true');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['nama', 'persentase_zakat', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tipeZakat = $query->paginate(10);

        // Data untuk filter
        $jenisZakatList = JenisZakat::orderBy('nama')->get(['id', 'nama']);

        return view('superadmin.tipe-zakat.index', compact('tipeZakat', 'jenisZakatList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisZakatList = JenisZakat::orderBy('nama')->get(['id', 'nama']);

        // Cari ID dari jenis zakat "Zakat Mal"
        $zakatMal = JenisZakat::where('nama', 'like', '%Zakat Mal%')
            ->orWhere('nama', 'like', '%Zakat Maal%')
            ->first();

        $zakatMalId = $zakatMal ? $zakatMal->id : null;

        return view('superadmin.tipe-zakat.create', compact('jenisZakatList', 'zakatMalId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_zakat_id' => 'required|exists:jenis_zakat,id',
            'nama' => 'required|string|max:255',

            // Validasi nisab emas/perak
            'nisab_emas_gram' => 'nullable|numeric|min:0|max:999999.99',
            'nisab_perak_gram' => 'nullable|numeric|min:0|max:999999.99',

            // Validasi nisab pertanian
            'nisab_pertanian_kg' => 'nullable|numeric|min:0|max:999999.99',

            // Validasi nisab peternakan
            'nisab_kambing_min' => 'nullable|integer|min:0|max:9999',
            'nisab_sapi_min' => 'nullable|integer|min:0|max:9999',
            'nisab_unta_min' => 'nullable|integer|min:0|max:9999',

            // Validasi persentase
            'persentase_zakat' => 'nullable|numeric|min:0|max:100',
            'persentase_alternatif' => 'nullable|numeric|min:0|max:100',
            'keterangan_persentase' => 'nullable|string|max:255',

            'requires_haul' => 'boolean',
            'ketentuan_khusus' => 'nullable|string|max:1000',
        ], [
            'jenis_zakat_id.required' => 'Jenis zakat wajib dipilih',
            'jenis_zakat_id.exists' => 'Jenis zakat tidak valid',
            'nama.required' => 'Nama tipe zakat wajib diisi',
            'nama.max' => 'Nama tipe zakat maksimal 255 karakter',
            'persentase_zakat.max' => 'Persentase tidak boleh lebih dari 100%',
            'persentase_alternatif.max' => 'Persentase alternatif tidak boleh lebih dari 100%',
            'ketentuan_khusus.max' => 'Ketentuan khusus maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cek apakah jenis zakat yang dipilih adalah Zakat Mal
            $isZakatMal = $this->isZakatMal($request->jenis_zakat_id);

            // Validasi nisab hanya untuk Zakat Mal
            if ($isZakatMal) {
                $hasNisab = $request->nisab_emas_gram ||
                    $request->nisab_perak_gram ||
                    $request->nisab_pertanian_kg ||
                    $request->nisab_kambing_min ||
                    $request->nisab_sapi_min ||
                    $request->nisab_unta_min;

                if (!$hasNisab) {
                    return redirect()->back()
                        ->with('error', 'Minimal satu jenis nisab harus diisi untuk Zakat Mal')
                        ->withInput();
                }

                // Validasi persentase zakat wajib untuk Zakat Mal
                if (!$request->persentase_zakat) {
                    return redirect()->back()
                        ->with('error', 'Persentase zakat wajib diisi untuk Zakat Mal')
                        ->withInput();
                }
            } else {
                // Untuk selain Zakat Mal, kosongkan nilai nisab dan persentase
                $request->merge([
                    'nisab_emas_gram' => null,
                    'nisab_perak_gram' => null,
                    'nisab_pertanian_kg' => null,
                    'nisab_kambing_min' => null,
                    'nisab_sapi_min' => null,
                    'nisab_unta_min' => null,
                    'persentase_zakat' => null,
                    'persentase_alternatif' => null,
                    'keterangan_persentase' => null,
                    'requires_haul' => false, // Non-Zakat Mal tidak perlu haul
                ]);
            }

            TipeZakat::create([
                'jenis_zakat_id' => $request->jenis_zakat_id,
                'nama' => $request->nama,
                'nisab_emas_gram' => $request->nisab_emas_gram,
                'nisab_perak_gram' => $request->nisab_perak_gram,
                'nisab_pertanian_kg' => $request->nisab_pertanian_kg,
                'nisab_kambing_min' => $request->nisab_kambing_min,
                'nisab_sapi_min' => $request->nisab_sapi_min,
                'nisab_unta_min' => $request->nisab_unta_min,
                'persentase_zakat' => $request->persentase_zakat,
                'persentase_alternatif' => $request->persentase_alternatif,
                'keterangan_persentase' => $request->keterangan_persentase,
                'requires_haul' => $request->boolean('requires_haul', $isZakatMal),
                'ketentuan_khusus' => $request->ketentuan_khusus,
            ]);

            DB::commit();

            Log::info('Tipe Zakat berhasil ditambahkan', [
                'nama' => $request->nama,
                'jenis_zakat_id' => $request->jenis_zakat_id,
                'is_zakat_mal' => $isZakatMal,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('tipe-zakat.index')
                ->with('success', 'Tipe zakat berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan tipe zakat: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipeZakat $tipeZakat)
    {
        $jenisZakatList = JenisZakat::orderBy('nama')->get(['id', 'nama']);

        // Cari ID dari jenis zakat "Zakat Mal"
        $zakatMal = JenisZakat::where('nama', 'like', '%Zakat Mal%')
            ->orWhere('nama', 'like', '%Zakat Maal%')
            ->first();

        $zakatMalId = $zakatMal ? $zakatMal->id : null;

        return view('superadmin.tipe-zakat.edit', compact('tipeZakat', 'jenisZakatList', 'zakatMalId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipeZakat $tipeZakat)
    {
        $validator = Validator::make($request->all(), [
            'jenis_zakat_id' => 'required|exists:jenis_zakat,id',
            'nama' => 'required|string|max:255',
            'nisab_emas_gram' => 'nullable|numeric|min:0|max:999999.99',
            'nisab_perak_gram' => 'nullable|numeric|min:0|max:999999.99',
            'nisab_pertanian_kg' => 'nullable|numeric|min:0|max:999999.99',
            'nisab_kambing_min' => 'nullable|integer|min:0|max:9999',
            'nisab_sapi_min' => 'nullable|integer|min:0|max:9999',
            'nisab_unta_min' => 'nullable|integer|min:0|max:9999',
            'persentase_zakat' => 'nullable|numeric|min:0|max:100',
            'persentase_alternatif' => 'nullable|numeric|min:0|max:100',
            'keterangan_persentase' => 'nullable|string|max:255',
            'requires_haul' => 'boolean',
            'ketentuan_khusus' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cek apakah jenis zakat yang dipilih adalah Zakat Mal
            $isZakatMal = $this->isZakatMal($request->jenis_zakat_id);

            // Validasi nisab hanya untuk Zakat Mal
            if ($isZakatMal) {
                $hasNisab = $request->nisab_emas_gram ||
                    $request->nisab_perak_gram ||
                    $request->nisab_pertanian_kg ||
                    $request->nisab_kambing_min ||
                    $request->nisab_sapi_min ||
                    $request->nisab_unta_min;

                if (!$hasNisab) {
                    return redirect()->back()
                        ->with('error', 'Minimal satu jenis nisab harus diisi untuk Zakat Mal')
                        ->withInput();
                }

                // Validasi persentase zakat wajib untuk Zakat Mal
                if (!$request->persentase_zakat) {
                    return redirect()->back()
                        ->with('error', 'Persentase zakat wajib diisi untuk Zakat Mal')
                        ->withInput();
                }
            } else {
                // Untuk selain Zakat Mal, kosongkan nilai nisab dan persentase
                $request->merge([
                    'nisab_emas_gram' => null,
                    'nisab_perak_gram' => null,
                    'nisab_pertanian_kg' => null,
                    'nisab_kambing_min' => null,
                    'nisab_sapi_min' => null,
                    'nisab_unta_min' => null,
                    'persentase_zakat' => null,
                    'persentase_alternatif' => null,
                    'keterangan_persentase' => null,
                ]);
            }

            $tipeZakat->update([
                'jenis_zakat_id' => $request->jenis_zakat_id,
                'nama' => $request->nama,
                'nisab_emas_gram' => $request->nisab_emas_gram,
                'nisab_perak_gram' => $request->nisab_perak_gram,
                'nisab_pertanian_kg' => $request->nisab_pertanian_kg,
                'nisab_kambing_min' => $request->nisab_kambing_min,
                'nisab_sapi_min' => $request->nisab_sapi_min,
                'nisab_unta_min' => $request->nisab_unta_min,
                'persentase_zakat' => $request->persentase_zakat,
                'persentase_alternatif' => $request->persentase_alternatif,
                'keterangan_persentase' => $request->keterangan_persentase,
                'requires_haul' => $request->boolean('requires_haul', $isZakatMal),
                'ketentuan_khusus' => $request->ketentuan_khusus,
            ]);

            DB::commit();

            Log::info('Tipe Zakat berhasil diupdate', [
                'uuid' => $tipeZakat->uuid,
                'nama' => $tipeZakat->nama,
                'jenis_zakat_id' => $request->jenis_zakat_id,
                'is_zakat_mal' => $isZakatMal,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('tipe-zakat.index')
                ->with('success', 'Tipe zakat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengupdate tipe zakat: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipeZakat $tipeZakat)
    {
        try {
            DB::beginTransaction();

            $nama = $tipeZakat->nama;
            $tipeZakat->delete();

            DB::commit();

            Log::info('Tipe Zakat berhasil dihapus', [
                'nama' => $nama,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('tipe-zakat.index')
                ->with('success', 'Tipe zakat "' . $nama . '" berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus tipe zakat: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API untuk mendapatkan daftar tipe zakat berdasarkan jenis
     */
    public function getByJenisZakat(Request $request)
    {
        $jenisZakatId = $request->jenis_zakat_id;

        if (!$jenisZakatId) {
            return response()->json([]);
        }

        $tipeZakat = TipeZakat::where('jenis_zakat_id', $jenisZakatId)
            ->orderBy('nama')
            ->get(['uuid', 'nama', 'persentase_zakat']);

        return response()->json($tipeZakat);
    }

    /**
     * Helper function untuk mengecek apakah suatu ID adalah Zakat Mal
     */
    private function isZakatMal($jenisZakatId)
    {
        $zakatMal = JenisZakat::where(function($query) {
                $query->where('nama', 'like', '%Zakat Mal%')
                      ->orWhere('nama', 'like', '%Zakat Maal%');
            })
            ->first();

        return $zakatMal && $zakatMal->id == $jenisZakatId;
    }
}