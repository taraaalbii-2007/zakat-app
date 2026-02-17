<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMustahik;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriMustahikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KategoriMustahik::query();

        // Search pada kolom yang ada di migration
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting — hanya kolom yang ada di migration
        $allowedSortBy = ['nama', 'persentase_default', 'created_at'];
        $sortBy        = in_array($request->get('sort_by'), $allowedSortBy)
            ? $request->get('sort_by')
            : 'nama';

        // PERBAIKAN: validasi sort_order agar tidak bisa inject nilai arbitrer
        $sortOrder = $request->get('sort_order') === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sortBy, $sortOrder);

        $kategoriMustahik = $query->paginate(10);

        return view('superadmin.kategori-mustahik.index', compact('kategoriMustahik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.kategori-mustahik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: hapus DB::beginTransaction — tidak diperlukan untuk single-model write
        // DB transaction berguna saat ada beberapa operasi DB yang harus atomic

        $validated = $request->validate([
            'nama'               => 'required|string|max:255|unique:kategori_mustahik,nama',
            'kriteria'           => 'nullable|string',
            'persentase_default' => 'nullable|numeric|min:0|max:100',
        ], [
            'nama.required'           => 'Nama kategori mustahik wajib diisi',
            'nama.max'                => 'Nama kategori mustahik maksimal 255 karakter',
            'nama.unique'             => 'Nama kategori mustahik sudah digunakan',
            'persentase_default.numeric' => 'Persentase harus berupa angka',
            'persentase_default.min'  => 'Persentase minimal 0',
            'persentase_default.max'  => 'Persentase maksimal 100',
        ]);

        KategoriMustahik::create($validated);

        return redirect()
            ->route('kategori-mustahik.index')
            ->with('success', 'Kategori mustahik berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriMustahik $kategoriMustahik)
    {
        return view('superadmin.kategori-mustahik.show', compact('kategoriMustahik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriMustahik $kategoriMustahik)
    {
        return view('superadmin.kategori-mustahik.edit', compact('kategoriMustahik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriMustahik $kategoriMustahik)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                // PERBAIKAN: ignore menggunakan ID model saat ini agar tidak konflik dengan dirinya sendiri
                Rule::unique('kategori_mustahik', 'nama')->ignore($kategoriMustahik->id),
            ],
            'kriteria'           => 'nullable|string',
            'persentase_default' => 'nullable|numeric|min:0|max:100',
        ], [
            'nama.required'           => 'Nama kategori mustahik wajib diisi',
            'nama.max'                => 'Nama kategori mustahik maksimal 255 karakter',
            'nama.unique'             => 'Nama kategori mustahik sudah digunakan',
            'persentase_default.numeric' => 'Persentase harus berupa angka',
            'persentase_default.min'  => 'Persentase minimal 0',
            'persentase_default.max'  => 'Persentase maksimal 100',
        ]);

        $kategoriMustahik->update($validated);

        return redirect()
            ->route('kategori-mustahik.index')
            ->with('success', 'Kategori mustahik berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriMustahik $kategoriMustahik)
    {
        $nama = $kategoriMustahik->nama;
        $kategoriMustahik->delete();

        return redirect()
            ->route('kategori-mustahik.index')
            ->with('success', "Kategori mustahik '{$nama}' berhasil dihapus");
    }
}