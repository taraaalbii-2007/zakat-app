<?php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use App\Mail\DataAkunDiubahNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Amil;

class ProfilAmilController extends Controller
{
    protected $user;
    protected $amil;
    protected $lembaga;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user   = Auth::user();
            if (!$this->user)           abort(403, 'Unauthorized');
            if (!$this->user->isAmil()) abort(403, 'Hanya Amil yang dapat mengakses halaman ini');

            $this->amil   = $this->user->amil;
            $this->lembaga = $this->amil ? $this->amil->lembaga : null;

            if (!$this->amil)   abort(404, 'Data amil tidak ditemukan.');
            if (!$this->lembaga) abort(404, 'Data lembaga tidak ditemukan.');

            view()->share('lembaga', $this->lembaga);
            return $next($request);
        });
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------
    public function show()
    {
        $amil = $this->amil->load(['pengguna', 'lembaga']);

        $stats = [
            'total_transaksi'     => $amil->transaksiPenerimaan()->count(),
            'transaksi_bulan_ini' => $amil->transaksiPenerimaan()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_verified' => $amil->transaksiPenerimaan()->where('status', 'verified')->count(),
            'total_nominal'  => $amil->transaksiPenerimaan()->where('status', 'verified')->sum('jumlah'),
        ];

        $breadcrumbs = [
            'Data Profil' => route('profil.show'),
        ];

        return view('amil.profil.show', compact('amil', 'stats', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // EDIT
    // ---------------------------------------------------------------
    public function edit()
    {
        $amil = $this->amil->load(['pengguna', 'lembaga']);
        $breadcrumbs = [
            'Data Profil' => route('profil.show'),
            'Edit Profil' => route('profil.edit')
        ];
        return view('amil.profil.edit', compact('amil', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // EDIT EMAIL — form ubah email (halaman tersendiri)
    // ---------------------------------------------------------------
    public function editEmail()
    {
        $amil = $this->amil->load(['pengguna', 'lembaga']);
        $breadcrumbs = [
            'Data Profil' => route('profil.show'),
            'Ubah Email' => route('profil.email.edit')
        ];
        return view('amil.profil.ubah-email', compact('amil', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // EDIT PASSWORD — form ubah password
    // ---------------------------------------------------------------
    public function editPassword()
    {
        $amil = $this->amil->load(['pengguna', 'lembaga']);
        $breadcrumbs = [
            'Data Profil' => route('profil.show'),
            'Ubah Password' => route('profil.password.edit')
        ];
        return view('amil.profil.ubah-password', compact('amil', 'breadcrumbs'));
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

            'tanda_tangan'  => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $amil = $this->amil;

        if (!$amil->lembaga_id) {
            $amil->lembaga_id = $this->lembaga->id;
        }

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

        foreach ($dataToUpdate as $key => $value) {
            if ($request->has($key)) {
                $amil->$key = $value;
            }
        }

        // Hapus foto
        if ($request->boolean('remove_foto') && $amil->foto) {
            Storage::disk('public')->delete($amil->foto);
            $amil->foto = null;
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {
            if ($amil->foto && Storage::disk('public')->exists($amil->foto)) {
                Storage::disk('public')->delete($amil->foto);
            }
            
            $path = $request->file('foto')->store('amil/foto', 'public');
            $amil->foto = $path;
        }

        // Hapus tanda tangan
        if ($request->boolean('remove_ttd') && $amil->tanda_tangan) {
            if (Storage::disk('public')->exists($amil->tanda_tangan)) {
                Storage::disk('public')->delete($amil->tanda_tangan);
            }
            $amil->tanda_tangan = null;
        }

        // Simpan tanda tangan (prioritas: processed base64)
        if (!$request->boolean('remove_ttd')) {
            $processedBase64 = $request->input('tanda_tangan_processed');

            if ($processedBase64 && str_starts_with($processedBase64, 'data:image/png;base64,')) {
                $base64Data = substr($processedBase64, strpos($processedBase64, ',') + 1);
                $pngData    = base64_decode($base64Data);

                if ($pngData !== false) {
                    if ($amil->tanda_tangan && Storage::disk('public')->exists($amil->tanda_tangan)) {
                        Storage::disk('public')->delete($amil->tanda_tangan);
                    }

                    $filename = 'amil/tanda_tangan/' . $amil->id . '_' . time() . '.png';
                    Storage::disk('public')->put($filename, $pngData);
                    $amil->tanda_tangan = $filename;
                }
            } elseif ($request->hasFile('tanda_tangan')) {
                if ($amil->tanda_tangan && Storage::disk('public')->exists($amil->tanda_tangan)) {
                    Storage::disk('public')->delete($amil->tanda_tangan);
                }
                
                $path = $request->file('tanda_tangan')->store('amil/tanda_tangan', 'public');
                $amil->tanda_tangan = $path;
            }
        }

        try {
            $amil->save();
            
            return redirect()->route('profil.show')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update profil amil: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    // ---------------------------------------------------------------
    // UPDATE EMAIL — simpan email baru + notifikasi + auto logout
    // ---------------------------------------------------------------
    public function updateEmail(Request $request)
    {
        $user = $this->user;

        $validator = Validator::make($request->all(), [
            'email'            => 'required|email|max:255|unique:pengguna,email,' . $user->id,
            'current_password' => 'required|string',
        ], [
            'email.unique'              => 'Email tersebut sudah digunakan oleh akun lain.',
            'email.required'            => 'Email baru wajib diisi.',
            'current_password.required' => 'Password wajib diisi untuk konfirmasi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password tidak sesuai. Perubahan email dibatalkan.'])
                ->withInput();
        }

        if ($user->email === $request->email) {
            return redirect()->back()
                ->with('info', 'Email tidak berubah.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user->email = $request->email;
            $user->save();

            // Update email di tabel amil juga jika ada
            if ($this->amil) {
                $this->amil->email = $request->email;
                $this->amil->save();
            }

            // Kirim notifikasi ke email baru
            try {
                $mailConfig = \App\Models\MailConfig::first();
                if ($mailConfig && $mailConfig->isComplete()) {
                    Mail::to($user->email)->send(new DataAkunDiubahNotification($user, 'email'));
                    Log::info('Notifikasi perubahan email terkirim ke: ' . $user->email);
                } else {
                    Log::warning('Konfigurasi mail tidak lengkap, notifikasi email tidak dikirim.');
                }
            } catch (\Exception $e) {
                Log::error('Gagal kirim notifikasi perubahan email: ' . $e->getMessage());
            }

            DB::commit();

            // Auto logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('warning', 'Email Anda berhasil diubah. Silakan login ulang menggunakan email baru Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update email amil: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui email. Silakan coba lagi.');
        }
    }

    // ---------------------------------------------------------------
    // UPDATE PASSWORD — simpan password baru + notifikasi + auto logout
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

            // Kirim notifikasi ke email
            try {
                $mailConfig = \App\Models\MailConfig::first();
                if ($mailConfig && $mailConfig->isComplete()) {
                    Mail::to($this->user->email)->send(new DataAkunDiubahNotification($this->user, 'password'));
                    Log::info('Notifikasi perubahan password terkirim ke: ' . $this->user->email);
                } else {
                    Log::warning('Konfigurasi mail tidak lengkap, notifikasi password tidak dikirim.');
                }
            } catch (\Exception $e) {
                Log::error('Gagal kirim notifikasi perubahan password: ' . $e->getMessage());
            }

            DB::commit();

            // Auto logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah. Silakan login ulang dengan password baru Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update password amil error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password.')->withInput();
        }
    }
}