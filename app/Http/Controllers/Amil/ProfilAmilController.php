<?php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Amil;

class ProfilAmilController extends Controller
{
    protected $user;
    protected $amil;
    protected $masjid;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user   = Auth::user();
            if (!$this->user)           abort(403, 'Unauthorized');
            if (!$this->user->isAmil()) abort(403, 'Hanya Amil yang dapat mengakses halaman ini');

            $this->amil   = $this->user->amil;
            $this->masjid = $this->amil ? $this->amil->masjid : null;

            if (!$this->amil)   abort(404, 'Data amil tidak ditemukan.');
            if (!$this->masjid) abort(404, 'Data masjid tidak ditemukan.');

            view()->share('masjid', $this->masjid);
            return $next($request);
        });
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------
    public function show()
    {
        $amil = $this->amil->load(['pengguna', 'masjid']);

        $stats = [
            'total_transaksi'     => $amil->transaksiPenerimaan()->count(),
            'transaksi_bulan_ini' => $amil->transaksiPenerimaan()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_verified' => $amil->transaksiPenerimaan()->where('status', 'verified')->count(),
            'total_nominal'  => $amil->transaksiPenerimaan()->where('status', 'verified')->sum('jumlah'),
        ];

        return view('amil.profil.show', compact('amil', 'stats'));
    }

    // ---------------------------------------------------------------
    // EDIT
    // ---------------------------------------------------------------
    public function edit()
    {
        $amil = $this->amil->load(['pengguna', 'masjid']);
        return view('amil.profil.edit', compact('amil'));
    }

    // ---------------------------------------------------------------
// UPDATE — data pribadi + foto + tanda tangan
// ---------------------------------------------------------------
public function update(Request $request)
{
    $request->validate([
        'nama_lengkap'  => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'tempat_lahir'  => 'required|string|max:100',
        'tanggal_lahir' => 'required|date',
        'telepon'       => 'required|string|max:20',
        'email'         => 'required|email|max:255',
        'alamat'        => 'required|string',
        'wilayah_tugas' => 'nullable|string|max:255',
        'keterangan'    => 'nullable|string|max:500',

        // File asli hanya divalidasi jika processed tidak ada
        'tanda_tangan'  => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $amil = $this->amil; // Ambil dari constructor

    // ── Pastikan masjid_id selalu ada ─────────────────────
    if (!$amil->masjid_id) {
        $amil->masjid_id = $this->masjid->id;
    }

    // ── Simpan data pribadi ──────────────────────────
    $dataToUpdate = $request->only([
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'telepon',
        'email',
        'alamat',
        'wilayah_tugas',
        'keterangan',
    ]);

    // Hanya update field yang ada di request
    foreach ($dataToUpdate as $key => $value) {
        if ($request->has($key)) {
            $amil->$key = $value;
        }
    }

    // ── Hapus foto ───────────────────────────────────
    if ($request->boolean('remove_foto') && $amil->foto) {
        Storage::disk('public')->delete($amil->foto);
        $amil->foto = null;
    }

    // ── Upload foto baru ─────────────────────────────
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($amil->foto && Storage::disk('public')->exists($amil->foto)) {
            Storage::disk('public')->delete($amil->foto);
        }
        
        // Simpan foto baru
        $path = $request->file('foto')->store('amil/foto', 'public');
        $amil->foto = $path;
    }

    // ── Hapus tanda tangan ───────────────────────────
    if ($request->boolean('remove_ttd') && $amil->tanda_tangan) {
        if (Storage::disk('public')->exists($amil->tanda_tangan)) {
            Storage::disk('public')->delete($amil->tanda_tangan);
        }
        $amil->tanda_tangan = null;
    }

    // ── Simpan tanda tangan (prioritas: processed base64) ──
    if (!$request->boolean('remove_ttd')) {
        $processedBase64 = $request->input('tanda_tangan_processed');

        if ($processedBase64 && str_starts_with($processedBase64, 'data:image/png;base64,')) {
            // Hasil remove background dari browser (base64 PNG transparan)
            $base64Data = substr($processedBase64, strpos($processedBase64, ',') + 1);
            $pngData    = base64_decode($base64Data);

            if ($pngData !== false) {
                // Hapus TTD lama
                if ($amil->tanda_tangan && Storage::disk('public')->exists($amil->tanda_tangan)) {
                    Storage::disk('public')->delete($amil->tanda_tangan);
                }

                $filename = 'amil/tanda_tangan/' . $amil->id . '_' . time() . '.png';
                Storage::disk('public')->put($filename, $pngData);
                $amil->tanda_tangan = $filename;
            }
        } elseif ($request->hasFile('tanda_tangan')) {
            // Fallback: file asli tanpa pemrosesan (jika JS gagal)
            if ($amil->tanda_tangan && Storage::disk('public')->exists($amil->tanda_tangan)) {
                Storage::disk('public')->delete($amil->tanda_tangan);
            }
            
            $path = $request->file('tanda_tangan')->store('amil/tanda_tangan', 'public');
            $amil->tanda_tangan = $path;
        }
    }

    // ── Simpan perubahan ─────────────────────────────
    try {
        $amil->save();
        
        return redirect()->route('profil.show')
            ->with('success', 'Profil berhasil diperbarui.');
    } catch (\Exception $e) {
        // Log error untuk debugging
        Log::error('Gagal update profil amil: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
    }
}

    // ---------------------------------------------------------------
    // UPDATE PASSWORD
    // ---------------------------------------------------------------
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withFragment('section-password');
        }

        if (!Hash::check($request->current_password, $this->user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withFragment('section-password');
        }

        DB::beginTransaction();
        try {
            $this->user->password = Hash::make($request->password);
            $this->user->save();
            DB::commit();
            return redirect()->route('amil.profil.show')->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update password amil error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password.');
        }
    }
}