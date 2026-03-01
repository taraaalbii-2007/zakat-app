<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\KategoriBulletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BulletinController extends Controller
{
    // ============================================
    // INDEX
    // ============================================
    public function index(Request $request)
    {
        $query = Bulletin::with(['author', 'kategoriBulletin'])
            ->latest('published_at');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        $bulletins    = $query->paginate(10);
        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();

        return view('superadmin.bulletin.index', compact('bulletins', 'kategoriList'));
    }

    // ============================================
    // CREATE
    // ============================================
    public function create()
    {
        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();

        return view('superadmin.bulletin.create', compact('kategoriList'));
    }

    // ============================================
    // STORE
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'kategori_bulletin_id' => 'nullable|exists:kategori_bulletin,id',
            'kategori_baru'        => 'nullable|string|max:100',
            'judul'                => 'required|string|max:255',
            'lokasi'               => 'nullable|string|max:255',
            'thumbnail'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_caption'        => 'nullable|string|max:255',
            'konten'               => 'required|string|min:50',
            'published_at'         => 'nullable|date',
        ], [
            'judul.required'  => 'Judul bulletin wajib diisi.',
            'konten.required' => 'Konten bulletin wajib diisi.',
            'konten.min'      => 'Konten bulletin minimal 50 karakter.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max'   => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Buat kategori baru jika dipilih
        $kategoriId = $request->kategori_bulletin_id;

        if ($request->filled('kategori_baru') && empty($kategoriId)) {
            $kategori   = KategoriBulletin::create(['nama_kategori' => $request->kategori_baru]);
            $kategoriId = $kategori->id;
        }

        if (empty($kategoriId)) {
            return back()
                ->withInput()
                ->withErrors(['kategori_bulletin_id' => 'Pilih atau buat kategori terlebih dahulu.']);
        }

        // Upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('bulletins/thumbnails', 'public');
        }

        Bulletin::create([
            'created_by'           => Auth::id(),
            'kategori_bulletin_id' => $kategoriId,
            'judul'                => $request->judul,
            'slug'                 => Bulletin::generateSlug($request->judul),
            'konten'               => $request->konten,
            'lokasi'               => $request->lokasi,
            'thumbnail'            => $thumbnailPath,
            'image_caption'        => $request->image_caption,
            'published_at'         => $request->published_at ?? now(),
        ]);

        return redirect()
            ->route('superadmin.bulletin.index')
            ->with('success', 'Bulletin berhasil dibuat.');
    }

    // ============================================
    // SHOW
    // ============================================
    public function show(Bulletin $bulletin)
    {
        $bulletin->load(['author', 'kategoriBulletin']);
        $bulletin->incrementViewCount();

        return view('superadmin.bulletin.show', compact('bulletin'));
    }

    // ============================================
    // EDIT
    // ============================================
    public function edit(Bulletin $bulletin)
    {
        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();

        return view('superadmin.bulletin.edit', compact('bulletin', 'kategoriList'));
    }

    // ============================================
    // UPDATE
    // ============================================
    public function update(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'kategori_bulletin_id' => 'nullable|exists:kategori_bulletin,id',
            'kategori_baru'        => 'nullable|string|max:100',
            'judul'                => 'required|string|max:255',
            'lokasi'               => 'nullable|string|max:255',
            'thumbnail'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_caption'        => 'nullable|string|max:255',
            'konten'               => 'required|string|min:50',
            'published_at'         => 'nullable|date',
        ], [
            'judul.required'  => 'Judul bulletin wajib diisi.',
            'konten.required' => 'Konten bulletin wajib diisi.',
            'konten.min'      => 'Konten bulletin minimal 50 karakter.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max'   => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Buat kategori baru jika dipilih
        $kategoriId = $request->kategori_bulletin_id;

        if ($request->filled('kategori_baru') && empty($kategoriId)) {
            $kategori   = KategoriBulletin::create(['nama_kategori' => $request->kategori_baru]);
            $kategoriId = $kategori->id;
        }

        if (empty($kategoriId)) {
            return back()
                ->withInput()
                ->withErrors(['kategori_bulletin_id' => 'Pilih atau buat kategori terlebih dahulu.']);
        }

        // Upload thumbnail baru (hapus yang lama)
        $thumbnailPath = $bulletin->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $thumbnailPath = $request->file('thumbnail')->store('bulletins/thumbnails', 'public');
        }

        // Regenerate slug hanya jika judul berubah
        $slug = $bulletin->slug;
        if ($bulletin->judul !== $request->judul) {
            $slug = Bulletin::generateSlug($request->judul);
        }

        $bulletin->update([
            'kategori_bulletin_id' => $kategoriId,
            'judul'                => $request->judul,
            'slug'                 => $slug,
            'konten'               => $request->konten,
            'lokasi'               => $request->lokasi,
            'thumbnail'            => $thumbnailPath,
            'image_caption'        => $request->image_caption,
            'published_at'         => $request->published_at ?? $bulletin->published_at,
        ]);

        return redirect()
            ->route('superadmin.bulletin.show', $bulletin->uuid)
            ->with('success', 'Bulletin berhasil diperbarui.');
    }

    // ============================================
    // DESTROY
    // ============================================
    public function destroy(Bulletin $bulletin)
    {
        // Hapus thumbnail dari storage
        if ($bulletin->thumbnail) {
            Storage::disk('public')->delete($bulletin->thumbnail);
        }

        $bulletin->delete();

        return redirect()
            ->route('superadmin.bulletin.index')
            ->with('success', 'Bulletin berhasil dihapus.');
    }

    // ============================================
    // DELETE THUMBNAIL (AJAX / ROUTE KHUSUS)
    // ============================================
    public function deleteThumbnail(Bulletin $bulletin)
    {
        if ($bulletin->thumbnail) {
            Storage::disk('public')->delete($bulletin->thumbnail);
            $bulletin->update([
                'thumbnail'     => null,
                'image_caption' => null,
            ]);
        }

        return redirect()
            ->route('superadmin.bulletin.edit', $bulletin->uuid)
            ->with('success', 'Thumbnail berhasil dihapus.');
    }

    // ============================================
    // API - GET KATEGORI LIST
    // ============================================
    public function apiKategoriList()
    {
        $list = KategoriBulletin::orderBy('nama_kategori')
            ->get(['id', 'nama_kategori']);

        return response()->json($list);
    }
}