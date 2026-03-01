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

        $kategoriList = $query->orderBy('nama_kategori')->paginate(15)->withQueryString();

        return view('superadmin.kategori_bulletin.index', compact('kategoriList'));
    }

    // ============================================
    // CREATE
    // ============================================
    public function create()
    {
        return view('superadmin.kategori_bulletin.create');
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
    public function edit(KategoriBulletin $kategoriBulletin)
    {
        $kategoriBulletin->loadCount('bulletins');

        return view('superadmin.kategori_bulletin.edit', compact('kategoriBulletin'));
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
        // Cek apakah masih ada bulletin yang menggunakan kategori ini
        if ($kategoriBulletin->bulletins()->count() > 0) {
            return redirect()
                ->route('superadmin.kategori-bulletin.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $kategoriBulletin->bulletins()->count() . ' bulletin.');
        }

        $nama = $kategoriBulletin->nama_kategori;
        $kategoriBulletin->delete();

        return redirect()
            ->route('superadmin.kategori-bulletin.index')
            ->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}