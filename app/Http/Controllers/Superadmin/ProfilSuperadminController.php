<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Mail\DataAkunDiubahNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProfilSuperadminController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user)                 abort(403, 'Unauthorized');
            if (!$this->user->isSuperadmin()) abort(403, 'Hanya Superadmin yang dapat mengakses halaman ini');

            return $next($request);
        });
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------
    public function show()
    {
        $user = $this->user;
        return view('superadmin.profil.show', compact('user'));
    }

    // ---------------------------------------------------------------
    // EDIT
    // ---------------------------------------------------------------
    public function edit()
    {
        $user = $this->user;
        return view('superadmin.profil.edit', compact('user'));
    }

    // ---------------------------------------------------------------
    // FORM UBAH PASSWORD
    // ---------------------------------------------------------------
    public function editPassword()
    {
        $user = $this->user;
        return view('superadmin.profil.ubah-password', compact('user'));
    }

    // ---------------------------------------------------------------
    // UPDATE — username + email (FOTO DIHAPUS)
    // ---------------------------------------------------------------
    public function update(Request $request)
    {
        $user = $this->user;
        $emailChanged = $user->email !== $request->email;

        $request->validate([
            'username' => 'required|string|max:255|unique:pengguna,username,' . $user->id,
            'email'    => 'required|email|max:255|unique:pengguna,email,' . $user->id,
        ]);

        DB::beginTransaction();
        try {
            // ── Update username & email ──────────────────
            $user->username = $request->input('username');
            $user->email    = $request->input('email');

            // ── Simpan ───────────────────────────────────
            $user->save();

            // ── Kirim email notifikasi jika email berubah ──
            if ($emailChanged) {
                try {
                    Mail::to($user->email)->send(new DataAkunDiubahNotification($user, 'email'));
                    Log::info('Email notifikasi perubahan email terkirim ke: ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email notifikasi perubahan email: ' . $e->getMessage());
                    // Tetap lanjutkan proses meskipun email gagal terkirim
                }
            }

            DB::commit();

            // ── Logout user jika email berubah ──
            if ($emailChanged) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('warning', 'Email Anda telah diubah. Silakan login ulang dengan email baru Anda.');
            }

            return redirect()->route('superadmin.profil.show')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update profil superadmin: ' . $e->getMessage());

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
                ->withErrors($validator)
                ->withInput();
        }

        if (!Hash::check($request->current_password, $this->user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $this->user->password = Hash::make($request->password);
            $this->user->save();

            // Kirim email notifikasi perubahan password
            try {
                // Cek konfigurasi mail sebelum mengirim
                $mailConfig = \App\Models\MailConfig::first();

                if (!$mailConfig || !$mailConfig->isComplete()) {
                    Log::error('Mail configuration incomplete', [
                        'config_exists' => !is_null($mailConfig),
                        'is_complete' => $mailConfig ? $mailConfig->isComplete() : false
                    ]);
                    throw new \Exception('Konfigurasi email tidak lengkap');
                }

                // Log config yang akan digunakan
                Log::info('Attempting to send email with config:', [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'from' => config('mail.from.address'),
                    'to' => $this->user->email
                ]);

                Mail::to($this->user->email)->send(new \App\Mail\DataAkunDiubahNotification($this->user, 'password'));

                Log::info('Email notifikasi perubahan password terkirim ke: ' . $this->user->email);
            } catch (\Exception $e) {
                Log::error('Gagal kirim email notifikasi perubahan password: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());

                // Tetap lanjutkan proses meskipun email gagal terkirim
                // Tapi kita bisa kasih warning ke user
                session()->flash('warning', 'Password berhasil diubah, tetapi gagal mengirim email notifikasi. Error: ' . $e->getMessage());
            }

            DB::commit();

            // Logout user setelah password berubah
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah. Silakan login ulang dengan password baru Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update password superadmin error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage())
                ->withInput();
        }
    }
}