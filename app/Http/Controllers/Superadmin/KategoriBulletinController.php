<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBulletin;
use Illuminate\Http\Request;

class KategoriBulletinController extends Controller
{
    // ============================================
    // INDEX
    // ============================================
    public function index(Request $request)
    {
        $query = KategoriBulletin::withCount('bulletins');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $kategoriList = $query->orderBy('nama_kategori')->paginate(10);

        $breadcrumbs = [
            'Kategori Bulletin' => route('superadmin.kategori-bulletin.index'),
        ];

        return view('superadmin.kategori_bulletin.index', compact('kategoriList', 'breadcrumbs'));
    }

    // ============================================
    // CREATE
    // ============================================
    public function create()
    {
        $breadcrumbs = [
            'Kategori Bulletin' => route('superadmin.kategori-bulletin.index'),
            'Tambah Kategori Bulletin' => route('superadmin.kategori-bulletin.create'),
        ];
        return view('superadmin.kategori_bulletin.create', compact('breadcrumbs'));
    }

    // ============================================
    // STORE (inline, tanpa halaman create terpisah)
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_bulletin,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        KategoriBulletin::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('superadmin.kategori-bulletin.index')
            ->with('success', 'Kategori "' . $request->nama_kategori . '" berhasil ditambahkan.');
    }

    // ============================================
    // EDIT
    // ============================================
    public function edit($uuid)
    {
        $kategoriBulletin = KategoriBulletin::where('uuid', $uuid)->firstOrFail();

        $breadcrumbs = [
            'Kategori Bulletin' => route('superadmin.kategori-bulletin.index'),
            'Edit Kategori Bulletin' => route('superadmin.kategori-bulletin.edit', $uuid),
        ];

        return view('superadmin.kategori_bulletin.edit', compact('kategoriBulletin', 'breadcrumbs'));
    }

    // ============================================
    // UPDATE
    // ============================================
    public function update(Request $request, KategoriBulletin $kategoriBulletin)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_bulletin,nama_kategori,' . $kategoriBulletin->id,
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        $kategoriBulletin->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('superadmin.kategori-bulletin.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // ============================================
    // DESTROY
    // ============================================
    public function destroy(KategoriBulletin $kategoriBulletin)
    {
        // Cek termasuk yang sudah soft delete
        $totalBulletin = $kategoriBulletin->bulletins()->withTrashed()->count();

        if ($totalBulletin > 0) {
            return redirect()
                ->route('superadmin.kategori-bulletin.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $totalBulletin . ' bulletin (termasuk yang sudah dihapus sementara).');
        }

        $nama = $kategoriBulletin->nama_kategori;
        $kategoriBulletin->delete();

        return redirect()
            ->route('superadmin.kategori-bulletin.index')
            ->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}
