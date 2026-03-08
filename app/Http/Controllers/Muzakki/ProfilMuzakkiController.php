<?php

namespace App\Http\Controllers\Muzakki;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Mail\DataAkunDiubahNotification;

class ProfilMuzakkiController extends Controller
{
    protected $user;
    protected $muzakki;
    protected $lembaga;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!$this->user)               abort(403, 'Unauthorized');
            if (!$this->user->isMuzakki())  abort(403, 'Hanya Muzakki yang dapat mengakses halaman ini');

            $this->muzakki = $this->user->muzakki;
            $this->lembaga  = $this->muzakki ? $this->muzakki->lembaga : null;

            if (!$this->muzakki) abort(404, 'Data muzakki tidak ditemukan.');
            if (!$this->lembaga)  abort(404, 'Data lembaga tidak ditemukan.');

            view()->share('lembaga', $this->lembaga);
            return $next($request);
        });
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------
    public function show()
    {
        $muzakki = $this->muzakki->load(['pengguna', 'lembaga']);
        $user    = $this->user;

        $stats = [
            'total_transaksi'     => $muzakki->transaksiPenerimaan()->count(),
            'transaksi_bulan_ini' => $muzakki->transaksiPenerimaan()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_verified' => $muzakki->transaksiPenerimaan()
                ->where('status', 'verified')->count(),
            'total_nominal'  => $muzakki->transaksiPenerimaan()
                ->where('status', 'verified')->sum('jumlah'),
        ];

        return view('muzakki.profil.show', compact('muzakki', 'stats', 'user'));
    }

    // ---------------------------------------------------------------
    // EDIT
    // ---------------------------------------------------------------
    public function edit()
    {
        $muzakki = $this->muzakki->load(['pengguna', 'lembaga']);
        $user    = $this->user;
        return view('muzakki.profil.edit', compact('muzakki', 'user'));
    }

    // ---------------------------------------------------------------
    // UPDATE — data pribadi + foto
    // ---------------------------------------------------------------
    public function update(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat'  => 'required|string',
            'nik'     => 'nullable|string|max:16',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $muzakki = $this->muzakki;

        foreach (['nama', 'telepon', 'alamat', 'nik'] as $field) {
            if ($request->has($field)) {
                $muzakki->$field = $request->input($field);
            }
        }

        // ── Hapus foto ───────────────────────────────────
        if ($request->boolean('remove_foto') && $muzakki->foto) {
            if (Storage::disk('public')->exists($muzakki->foto)) {
                Storage::disk('public')->delete($muzakki->foto);
            }
            $muzakki->foto = null;
        }

        // ── Upload foto baru ─────────────────────────────
        if ($request->hasFile('foto') && !$request->boolean('remove_foto')) {
            if ($muzakki->foto && Storage::disk('public')->exists($muzakki->foto)) {
                Storage::disk('public')->delete($muzakki->foto);
            }
            $muzakki->foto = $request->file('foto')->store('muzakki/foto', 'public');
        }

        try {
            $muzakki->save();
            return redirect()->route('muzakki.profil.show')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update profil muzakki: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    // ---------------------------------------------------------------
    // EDIT PASSWORD — halaman tersendiri
    // ---------------------------------------------------------------
    public function editPassword()
    {
        $user    = $this->user;
        $muzakki = $this->muzakki;
        return view('muzakki.profil.edit-password', compact('user', 'muzakki'));
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
            return redirect()->back()->withErrors($validator->errors());
        }

        if (!Hash::check($request->current_password, $this->user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        DB::beginTransaction();
        try {
            $this->user->password = Hash::make($request->password);
            $this->user->save();
            DB::commit();

            // Kirim notifikasi email
            try {
                Mail::to($this->user->email)
                    ->send(new DataAkunDiubahNotification($this->user, 'password'));
            } catch (\Exception $e) {
                Log::warning('Gagal kirim notifikasi perubahan password: ' . $e->getMessage());
            }

            // Logout setelah ganti password
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah. Silakan login ulang.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update password muzakki error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password.');
        }
    }

    // ---------------------------------------------------------------
    // EDIT EMAIL — halaman tersendiri
    // ---------------------------------------------------------------
    public function editEmail()
    {
        $user    = $this->user;
        $muzakki = $this->muzakki;
        return view('muzakki.profil.edit-email', compact('user', 'muzakki'));
    }

    // ---------------------------------------------------------------
    // UPDATE EMAIL
    // ---------------------------------------------------------------
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'            => 'required|email|max:255|unique:pengguna,email,' . $this->user->id,
            'current_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        if (!Hash::check($request->current_password, $this->user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password tidak sesuai.'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $this->user->email  = $request->email;
            $this->user->save();

            // Sinkronisasi email di tabel muzakki
            $this->muzakki->email = $request->email;
            $this->muzakki->save();

            DB::commit();

            // Kirim notifikasi email
            try {
                Mail::to($request->email)
                    ->send(new DataAkunDiubahNotification($this->user, 'email'));
            } catch (\Exception $e) {
                Log::warning('Gagal kirim notifikasi perubahan email: ' . $e->getMessage());
            }

            // Logout setelah ganti email
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Email berhasil diubah. Silakan login ulang dengan email baru.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update email muzakki error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengubah email.');
        }
    }
}