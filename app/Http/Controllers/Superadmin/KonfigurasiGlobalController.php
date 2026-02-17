<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiAplikasi;
use App\Models\RecaptchaConfig;
use App\Models\GoogleConfig;
use App\Models\MailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KonfigurasiGlobalController extends Controller
{
    public function show()
    {
        $config = KonfigurasiAplikasi::first();
        $recaptcha = RecaptchaConfig::first();
        $google = GoogleConfig::first();
        $mail = MailConfig::first();

        return view('superadmin.konfigurasi-global.show', compact('config', 'recaptcha', 'google', 'mail'));
    }

    public function edit()
    {
        $config = KonfigurasiAplikasi::first();
        $recaptcha = RecaptchaConfig::first();
        $google = GoogleConfig::first();
        $mail = MailConfig::first();

        return view('superadmin.konfigurasi-global.edit', compact('config', 'recaptcha', 'google', 'mail'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // INFO APLIKASI
            'nama_aplikasi' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'deskripsi_aplikasi' => 'nullable|string',
            'versi' => 'nullable|string|max:20',

            // KONTAK & SUPPORT
            'email_admin' => 'nullable|email|max:255',
            'telepon_admin' => 'nullable|string|max:20',
            'alamat_kantor' => 'nullable|string',
            'whatsapp_support' => 'nullable|string|max:20',

            // SOCIAL MEDIA
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',

            // LOGO & FAVICON - DIUBAH DARI 2MB/1MB MENJADI 15MB
            'logo_aplikasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:15360', // 15MB = 15360KB
            'favicon' => 'nullable|image|mimes:ico,png,svg,jpeg,jpg,webp|max:15360', // 15MB = 15360KB

            // reCAPTCHA
            'RECAPTCHA_SITE_KEY' => 'nullable|string',
            'RECAPTCHA_SECRET_KEY' => 'nullable|string',

            // Google OAuth
            'GOOGLE_CLIENT_ID' => 'nullable|string',
            'GOOGLE_CLIENT_SECRET' => 'nullable|string',
            'GOOGLE_REDIRECT_URI' => 'nullable|url',

            // Mail Configuration
            'MAIL_MAILER' => 'nullable|string|max:50',
            'MAIL_HOST' => 'nullable|string|max:255',
            'MAIL_PORT' => 'nullable|string|max:10',
            'MAIL_USERNAME' => 'nullable|string|max:255',
            'MAIL_PASSWORD' => 'nullable|string|max:255',
            'MAIL_ENCRYPTION' => 'nullable|string|max:20',
            'MAIL_FROM_ADDRESS' => 'nullable|email|max:255',
            'MAIL_FROM_NAME' => 'nullable|string|max:255',
        ], [
            'logo_aplikasi.max' => 'Ukuran logo tidak boleh lebih dari 15MB',
            'favicon.max' => 'Ukuran favicon tidak boleh lebih dari 15MB',
            'logo_aplikasi.mimes' => 'Format logo harus: jpeg, png, jpg, gif, svg, atau webp',
            'favicon.mimes' => 'Format favicon harus: ico, png, svg, jpeg, jpg, atau webp',
        ]);

        if ($validator->fails()) {
            return redirect()->route('konfigurasi-global.edit')
                ->withErrors($validator)
                ->withInput();
        }

        // Update Konfigurasi Aplikasi
        $config = KonfigurasiAplikasi::firstOrNew();
        $config->nama_aplikasi = $request->nama_aplikasi;
        $config->tagline = $request->tagline;
        $config->deskripsi_aplikasi = $request->deskripsi_aplikasi;
        $config->versi = $request->versi ?? '1.0.0';
        $config->email_admin = $request->email_admin;
        $config->telepon_admin = $request->telepon_admin;
        $config->alamat_kantor = $request->alamat_kantor;
        $config->facebook_url = $request->facebook_url;
        $config->instagram_url = $request->instagram_url;
        $config->twitter_url = $request->twitter_url;
        $config->youtube_url = $request->youtube_url;
        $config->whatsapp_support = $request->whatsapp_support;

        // Handle logo_aplikasi upload
        if ($request->hasFile('logo_aplikasi')) {
            if ($config->logo_aplikasi && Storage::exists('public/' . $config->logo_aplikasi)) {
                Storage::delete('public/' . $config->logo_aplikasi);
            }
            $config->logo_aplikasi = $request->file('logo_aplikasi')->store('konfigurasi', 'public');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($config->favicon && Storage::exists('public/' . $config->favicon)) {
                Storage::delete('public/' . $config->favicon);
            }
            $config->favicon = $request->file('favicon')->store('konfigurasi', 'public');
        }

        // Handle hapus logo jika ada parameter
        if ($request->has('hapus_logo') && $request->hapus_logo == '1') {
            if ($config->logo_aplikasi && Storage::exists('public/' . $config->logo_aplikasi)) {
                Storage::delete('public/' . $config->logo_aplikasi);
            }
            $config->logo_aplikasi = null;
        }

        // Handle hapus favicon jika ada parameter
        if ($request->has('hapus_favicon') && $request->hapus_favicon == '1') {
            if ($config->favicon && Storage::exists('public/' . $config->favicon)) {
                Storage::delete('public/' . $config->favicon);
            }
            $config->favicon = null;
        }

        // Generate UUID jika baru
        if (empty($config->uuid)) {
            $config->uuid = \Illuminate\Support\Str::uuid();
        }

        $config->is_default = true; // Hanya satu konfigurasi default
        $config->save();

        // Update ReCAPTCHA Configuration
        $recaptcha = RecaptchaConfig::firstOrNew();
        $recaptcha->RECAPTCHA_SITE_KEY = $request->RECAPTCHA_SITE_KEY;
        $recaptcha->RECAPTCHA_SECRET_KEY = $request->RECAPTCHA_SECRET_KEY;
        $recaptcha->save();

        // Update Google OAuth Configuration
        $google = GoogleConfig::firstOrNew();
        $google->GOOGLE_CLIENT_ID = $request->GOOGLE_CLIENT_ID;
        $google->GOOGLE_CLIENT_SECRET = $request->GOOGLE_CLIENT_SECRET;
        $google->GOOGLE_REDIRECT_URI = $request->GOOGLE_REDIRECT_URI;
        $google->save();

        // Update Mail Configuration
        $mail = MailConfig::firstOrNew();
        $mail->MAIL_MAILER = $request->MAIL_MAILER ?? 'smtp';
        $mail->MAIL_HOST = $request->MAIL_HOST;
        $mail->MAIL_PORT = $request->MAIL_PORT ?? '587';
        $mail->MAIL_USERNAME = $request->MAIL_USERNAME;
        if ($request->filled('MAIL_PASSWORD')) {
            $mail->MAIL_PASSWORD = $request->MAIL_PASSWORD;
        }
        $mail->MAIL_ENCRYPTION = $request->MAIL_ENCRYPTION;
        $mail->MAIL_FROM_ADDRESS = $request->MAIL_FROM_ADDRESS;
        $mail->MAIL_FROM_NAME = $request->MAIL_FROM_NAME;
        $mail->save();

        return redirect()->route('konfigurasi-global.show')
            ->with('success', 'Konfigurasi aplikasi berhasil diperbarui!');
    }

    /**
     * Hapus logo aplikasi (AJAX)
     */
    public function hapusLogo()
    {
        $config = KonfigurasiAplikasi::first();

        if ($config && $config->logo_aplikasi && Storage::exists('public/' . $config->logo_aplikasi)) {
            Storage::delete('public/' . $config->logo_aplikasi);
            $config->logo_aplikasi = null;
            $config->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo berhasil dihapus'
        ]);
    }

    /**
     * Hapus favicon (AJAX)
     */
    public function hapusFavicon()
    {
        $config = KonfigurasiAplikasi::first();

        if ($config && $config->favicon && Storage::exists('public/' . $config->favicon)) {
            Storage::delete('public/' . $config->favicon);
            $config->favicon = null;
            $config->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Favicon berhasil dihapus'
        ]);
    }

    /**
     * Reset konfigurasi ke default
     */
    public function reset()
    {
        $config = KonfigurasiAplikasi::first();

        if ($config) {
            // Hapus file logo jika ada
            if ($config->logo_aplikasi && Storage::exists('public/' . $config->logo_aplikasi)) {
                Storage::delete('public/' . $config->logo_aplikasi);
            }

            // Hapus file favicon jika ada
            if ($config->favicon && Storage::exists('public/' . $config->favicon)) {
                Storage::delete('public/' . $config->favicon);
            }

            // Reset ke nilai default
            $config->update([
                'nama_aplikasi' => 'Sistem Zakat Digital',
                'tagline' => 'Membantu Pengelolaan Zakat Digital',
                'deskripsi_aplikasi' => 'Sistem manajemen zakat digital untuk kemudahan pembayaran dan pengelolaan zakat',
                'versi' => '1.0.0',
                'logo_aplikasi' => null,
                'favicon' => null,
                'email_admin' => 'admin@zakatdigital.com',
                'telepon_admin' => '081234567890',
                'alamat_kantor' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'twitter_url' => null,
                'youtube_url' => null,
                'whatsapp_support' => null,
                'is_default' => true,
            ]);
        }

        return redirect()->route('konfigurasi-global.show')
            ->with('success', 'Konfigurasi berhasil direset ke default!');
    }
}
