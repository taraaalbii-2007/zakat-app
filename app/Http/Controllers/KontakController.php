<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use App\Models\KonfigurasiAplikasi;
use App\Models\RecaptchaConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KontakController extends Controller
{
    public function index()
    {
        $config    = KonfigurasiAplikasi::getConfig();
        // Query recaptcha config
        $recaptcha = RecaptchaConfig::first();

        return view('pages.kontak', compact('config', 'recaptcha'));
    }

    public function store(Request $request)
    {
        $recaptcha = RecaptchaConfig::first();

        // ── Verifikasi reCAPTCHA v3 jika aktif ────────────────────
        if ($recaptcha && $recaptcha->isEnabled()) {
            $token = $request->input('recaptcha_token');

            if (!$token) {
                Log::warning('reCAPTCHA token tidak ditemukan', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                
                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => 'Token reCAPTCHA tidak ditemukan. Silakan muat ulang halaman.']);
            }

            try {
                $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $recaptcha->RECAPTCHA_SECRET_KEY,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

                $result = $verify->json();

                Log::info('reCAPTCHA verification result', [
                    'success' => $result['success'] ?? false,
                    'score' => $result['score'] ?? null,
                    'action' => $result['action'] ?? null,
                    'hostname' => $result['hostname'] ?? null,
                ]);

                // v3: cek success + score (threshold 0.5) + action harus 'kontak'
                if (!($result['success'] ?? false)) {
                    return back()
                        ->withInput()
                        ->withErrors(['recaptcha' => 'Verifikasi keamanan gagal. Silakan coba lagi.']);
                }

                if (($result['score'] ?? 0) < 0.5) {
                    return back()
                        ->withInput()
                        ->withErrors(['recaptcha' => 'Skor keamanan terlalu rendah. Silakan coba lagi.']);
                }

                if (($result['action'] ?? '') !== 'kontak') {
                    return back()
                        ->withInput()
                        ->withErrors(['recaptcha' => 'Aksi tidak valid. Silakan coba lagi.']);
                }

            } catch (\Exception $e) {
                Log::error('reCAPTCHA verification exception', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => 'Terjadi kesalahan saat verifikasi keamanan. Silakan coba lagi.']);
            }
        }

        // Validasi form
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'subjek' => 'required|string|max:255|min:15',
            'pesan'  => 'required|string|max:5000|min:20',
        ], [
            'nama.required'   => 'Nama lengkap wajib diisi.',
            'email.required'  => 'Alamat email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'subjek.required' => 'Subjek pesan wajib diisi.',
            'subjek.min'      => 'Subjek minimal 15 karakter.',
            'pesan.required'  => 'Pesan wajib diisi.',
            'pesan.min'       => 'Pesan minimal 20 karakter.',
            'pesan.max'       => 'Pesan maksimal 5000 karakter.',
        ]);

        // Simpan ke database
        Kontak::create([
            'nama'    => $validated['nama'],
            'email'   => $validated['email'],
            'subjek'  => $validated['subjek'],
            'pesan'   => $validated['pesan'],
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Kirim notifikasi email ke admin (opsional)
        // Mail::to($config->email_admin)->send(new PesanBaru($validated));

        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan membalas ke email Anda segera.');
    }
}