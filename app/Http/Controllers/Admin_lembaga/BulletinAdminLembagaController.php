<?php

namespace App\Http\Controllers\Admin_lembaga;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\KategoriBulletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class BulletinAdminLembagaController extends Controller
{
    /**
     * Ambil lembaga_id dari user yang sedang login.
     * User admin-lembaga menyimpan lembaga_id langsung di tabel pengguna.
     */
    private function getLembagaId(): int
    {
        $lembagaId = Auth::user()->lembaga_id;

        if (!$lembagaId) {
            abort(403, 'Akun Anda tidak terhubung ke lembaga manapun.');
        }

        return $lembagaId;
    }

    // ============================================
    // INDEX
    // ============================================
    public function index(Request $request)
    {
        $lembagaId = $this->getLembagaId();

        $query = Bulletin::with(['kategoriBulletin'])
            ->where('lembaga_id', $lembagaId)
            ->latest('created_at');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        $bulletins    = $query->paginate(10);
        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();

         $breadcrumbs = [
            'Kelola Bulletin' => route('admin-lembaga.bulletin.index'),
        ];

        // Hitung per-status untuk badge
        $counts = [
            'all'      => Bulletin::where('lembaga_id', $lembagaId)->count(),
            'draft'    => Bulletin::where('lembaga_id', $lembagaId)->where('status', 'draft')->count(),
            'pending'  => Bulletin::where('lembaga_id', $lembagaId)->where('status', 'pending')->count(),
            'approved' => Bulletin::where('lembaga_id', $lembagaId)->where('status', 'approved')->count(),
            'rejected' => Bulletin::where('lembaga_id', $lembagaId)->where('status', 'rejected')->count(),
        ];

        return view('admin-lembaga.bulletin.index', compact('bulletins', 'kategoriList', 'counts', 'breadcrumbs'));
    }

    // ============================================
    // CREATE
    // ============================================
    public function create()
    {
        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();
         $breadcrumbs = [
            'Kelola Bulletin' => route('admin-lembaga.bulletin.index'),
            'Tambah Bulletin' => route('admin-lembaga.bulletin.create')
        ];

        return view('admin-lembaga.bulletin.create', compact('kategoriList', 'breadcrumbs'));
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
            return back()->withInput()
                ->withErrors(['kategori_bulletin_id' => 'Pilih atau buat kategori terlebih dahulu.']);
        }

        // Upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->uploadAndCompress($request->file('thumbnail'));
        }

        // Tentukan action: simpan sebagai draft atau langsung submit
        $status = $request->input('action') === 'submit' ? 'pending' : 'draft';

        Bulletin::create([
            'created_by'           => Auth::id(),
            'lembaga_id'           => $this->getLembagaId(),
            'kategori_bulletin_id' => $kategoriId,
            'judul'                => $request->judul,
            'slug'                 => Bulletin::generateSlug($request->judul),
            'konten'               => $request->konten,
            'lokasi'               => $request->lokasi,
            'thumbnail'            => $thumbnailPath,
            'image_caption'        => $request->image_caption,
            'published_at'         => $request->published_at ?? now(),
            'status'               => $status,
        ]);

        $message = $status === 'pending'
            ? 'Bulletin berhasil dikirim untuk persetujuan superadmin.'
            : 'Bulletin berhasil disimpan sebagai draft.';

        return redirect()->route('admin-lembaga.bulletin.index')->with('success', $message);
    }

    // ============================================
    // SHOW
    // ============================================
    public function show(Bulletin $bulletin)
    {
        $this->authorizeOwnership($bulletin);
        $bulletin->load(['kategoriBulletin']);

         $breadcrumbs = [
            'Kelola Bulletin' => route('admin-lembaga.bulletin.index'),
            'Detail Bulletin' => route('admin-lembaga.bulletin.show', $bulletin)
        ];

        return view('admin-lembaga.bulletin.show', compact('bulletin', 'breadcrumbs'));
    }

    // ============================================
    // EDIT
    // ============================================
    public function edit(Bulletin $bulletin)
    {
        $this->authorizeOwnership($bulletin);

        if (!$bulletin->isEditable()) {
            return redirect()->route('admin-lembaga.bulletin.show', $bulletin->uuid)
                ->with('error', 'Bulletin yang sedang menunggu persetujuan atau sudah disetujui tidak dapat diedit.');
        }

        $kategoriList = KategoriBulletin::orderBy('nama_kategori')->get();
         $breadcrumbs = [
            'Kelola Bulletin' => route('admin-lembaga.bulletin.index'),
            'Edit Bulletin' => route('admin-lembaga.bulletin.edit', $bulletin)
        ];

        return view('admin-lembaga.bulletin.edit', compact('bulletin', 'kategoriList', 'breadcrumbs'));
    }

    // ============================================
    // UPDATE
    // ============================================
    public function update(Request $request, Bulletin $bulletin)
    {
        $this->authorizeOwnership($bulletin);

        if (!$bulletin->isEditable()) {
            return redirect()->route('admin-lembaga.bulletin.show', $bulletin->uuid)
                ->with('error', 'Bulletin ini tidak dapat diedit.');
        }

        $request->validate([
            'kategori_bulletin_id' => 'nullable|exists:kategori_bulletin,id',
            'kategori_baru'        => 'nullable|string|max:100',
            'judul'                => 'required|string|max:255',
            'lokasi'               => 'nullable|string|max:255',
            'thumbnail'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_caption'        => 'nullable|string|max:255',
            'konten'               => 'required|string|min:50',
            'published_at'         => 'nullable|date',
        ]);

        $kategoriId = $request->kategori_bulletin_id;
        if ($request->filled('kategori_baru') && empty($kategoriId)) {
            $kategori   = KategoriBulletin::create(['nama_kategori' => $request->kategori_baru]);
            $kategoriId = $kategori->id;
        }

        if (empty($kategoriId)) {
            return back()->withInput()
                ->withErrors(['kategori_bulletin_id' => 'Pilih atau buat kategori terlebih dahulu.']);
        }

        $thumbnailPath = $bulletin->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $thumbnailPath = $this->uploadAndCompress($request->file('thumbnail'));
        }

        $slug = $bulletin->slug;
        if ($bulletin->judul !== $request->judul) {
            $slug = Bulletin::generateSlug($request->judul);
        }

        // Tentukan action
        $status = $request->input('action') === 'submit' ? 'pending' : 'draft';

        $bulletin->update([
            'kategori_bulletin_id' => $kategoriId,
            'judul'                => $request->judul,
            'slug'                 => $slug,
            'konten'               => $request->konten,
            'lokasi'               => $request->lokasi,
            'thumbnail'            => $thumbnailPath,
            'image_caption'        => $request->image_caption,
            'published_at'         => $request->published_at ?? $bulletin->published_at,
            'status'               => $status,
            'rejection_reason'     => null, // reset jika diedit ulang
        ]);

        $message = $status === 'pending'
            ? 'Bulletin berhasil dikirim untuk persetujuan.'
            : 'Bulletin berhasil diperbarui sebagai draft.';

        return redirect()->route('admin-lembaga.bulletin.index')->with('success', $message);
    }

    // ============================================
    // SUBMIT (Draft → Pending)
    // ============================================
    public function submit(Bulletin $bulletin)
    {
        $this->authorizeOwnership($bulletin);

        if (!$bulletin->isDraft() && !$bulletin->isRejected()) {
            return back()->with('error', 'Hanya bulletin draft atau yang ditolak yang bisa diajukan ulang.');
        }

        $bulletin->update([
            'status'           => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin-lembaga.bulletin.show', $bulletin->uuid)
            ->with('success', 'Bulletin berhasil dikirim untuk persetujuan superadmin.');
    }

    // ============================================
    // DESTROY
    // ============================================
    public function destroy(Bulletin $bulletin)
    {
        $this->authorizeOwnership($bulletin);

        if ($bulletin->isApproved()) {
            return back()->with('error', 'Bulletin yang sudah disetujui tidak dapat dihapus. Hubungi superadmin.');
        }

        if ($bulletin->thumbnail) {
            Storage::disk('public')->delete($bulletin->thumbnail);
        }

        $bulletin->delete();

        return redirect()->route('admin-lembaga.bulletin.index')
            ->with('success', 'Bulletin berhasil dihapus.');
    }

    // ============================================
    // DELETE THUMBNAIL
    // ============================================
    public function deleteThumbnail(Bulletin $bulletin)
    {
        $this->authorizeOwnership($bulletin);

        if ($bulletin->thumbnail) {
            Storage::disk('public')->delete($bulletin->thumbnail);
            $bulletin->update(['thumbnail' => null, 'image_caption' => null]);
        }

        return redirect()->route('admin-lembaga.bulletin.edit', $bulletin->uuid)
            ->with('success', 'Thumbnail berhasil dihapus.');
    }

    // ============================================
    // PRIVATE HELPERS
    // ============================================
    private function authorizeOwnership(Bulletin $bulletin): void
    {
        if ($bulletin->lembaga_id !== $this->getLembagaId()) {
            abort(403, 'Anda tidak memiliki akses ke bulletin ini.');
        }
    }

    private function uploadAndCompress($file): string
    {
        $directory = 'bulletins/thumbnails';
        $filename  = 'thumb_' . Str::uuid() . '.webp';
        $savePath  = storage_path('app/public/' . $directory . '/' . $filename);

        if (!file_exists(dirname($savePath))) {
            mkdir(dirname($savePath), 0755, true);
        }

        Image::read($file)
            ->scaleDown(width: 1280, height: 1280)
            ->toWebp(quality: 80)
            ->save($savePath);

        return $directory . '/' . $filename;
    }
}