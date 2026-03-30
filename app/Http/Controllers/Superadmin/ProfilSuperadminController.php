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

        $breadcrumbs = [
            'Kelola Profil' => route('superadmin.profil.show'),
        ];
        return view('superadmin.profil.show', compact('user', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // EDIT
    // ---------------------------------------------------------------
    public function edit()
    {
        $user = $this->user;
        $breadcrumbs = [
            'Kelola Profil' => route('superadmin.profil.show'),
            'Edit Profil' => route('superadmin.profil.edit')
        ];
        return view('superadmin.profil.edit', compact('user', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // FORM UBAH EMAIL
    // ---------------------------------------------------------------
    public function editEmail()
    {
        $user = $this->user;
        $breadcrumbs = [
            'Kelola Profil' => route('superadmin.profil.show'),
            'Ubah Email' => route('superadmin.profil.email.edit')
        ];
        return view('superadmin.profil.ubah-email', compact('user', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // FORM UBAH PASSWORD
    // ---------------------------------------------------------------
    public function editPassword()
    {
        $user = $this->user;
        $breadcrumbs = [
            'Kelola Profil' => route('superadmin.profil.show'),
            'Ubah Password' => route('superadmin.profil.password.edit')
        ];
        return view('superadmin.profil.ubah-password', compact('user', 'breadcrumbs'));
    }

    // ---------------------------------------------------------------
    // UPDATE — username (TANPA email)
    // ---------------------------------------------------------------
    public function update(Request $request)
    {
        $user = $this->user;

        $request->validate([
            'username' => 'required|string|max:255|unique:pengguna,username,' . $user->id,
        ]);

        DB::beginTransaction();
        try {
            $user->username = $request->input('username');
            $user->save();

            DB::commit();

            return redirect()->route('superadmin.profil.show')
                ->with('success', 'Username berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update username superadmin: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui username. Silakan coba lagi.');
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

        // Verifikasi password sebagai konfirmasi keamanan
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password tidak sesuai. Perubahan email dibatalkan.'])
                ->withInput();
        }

        // Cek apakah email benar-benar berubah
        if ($user->email === $request->email) {
            return redirect()->back()
                ->with('info', 'Email tidak berubah.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user->email = $request->email;
            $user->save();

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
                // Tetap lanjutkan meskipun notifikasi gagal
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
            Log::error('Gagal update email superadmin: ' . $e->getMessage());
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
                // Tetap lanjutkan meskipun notifikasi gagal
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
            Log::error('Update password superadmin error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage())
                ->withInput();
        }
    }
}