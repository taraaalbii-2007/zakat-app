<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Pengguna;
use App\Models\Masjid;
use App\Models\GoogleConfig;
use App\Models\MailConfig;
use App\Models\RecaptchaConfig;
use Carbon\Carbon;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class AuthController extends Controller
{
    const OTP_EXPIRY_MINUTES = 15;
    const RESEND_COOLDOWN_SECONDS = 60;
    const PASSWORD_RESET_EXPIRY_MINUTES = 15;

    /**
     * Load konfigurasi email dari database
     */
    private function loadMailConfig()
    {
        try {
            $mailConfig = MailConfig::first();

            if (!$mailConfig) {
                return false;
            }

            config([
                'mail.mailers.smtp.host' => $mailConfig->MAIL_HOST,
                'mail.mailers.smtp.port' => $mailConfig->MAIL_PORT ?? 587,
                'mail.mailers.smtp.encryption' => $mailConfig->MAIL_ENCRYPTION ?? 'tls',
                'mail.mailers.smtp.username' => $mailConfig->MAIL_USERNAME,
                'mail.mailers.smtp.password' => $mailConfig->MAIL_PASSWORD,
                'mail.from.address' => $mailConfig->MAIL_FROM_ADDRESS ?? $mailConfig->MAIL_USERNAME,
                'mail.from.name' => $mailConfig->MAIL_FROM_NAME ?? 'Niat Zakat',
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate masked email (t***@gmail.com)
     */
    private function maskEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $parts = explode('@', $email);
        $username = $parts[0];
        $domain = $parts[1];

        if (strlen($username) > 1) {
            $maskedUsername = $username[0] . str_repeat('*', strlen($username) - 1);
        } else {
            $maskedUsername = $username;
        }

        return $maskedUsername . '@' . $domain;
    }

    /**
     * Verifikasi reCAPTCHA v3
     */
    private function verifyRecaptcha(?string $token, string $expectedAction = 'login'): bool
    {
        // PRIORITY 1: Get dari config/services.php (environment based)
        $secretKey = config('services.recaptcha.secret_key');

        // PRIORITY 2: Fallback ke database jika config tidak ada
        if (!$secretKey) {
            try {
                $recaptchaConfig = RecaptchaConfig::first();
                $secretKey = $recaptchaConfig?->RECAPTCHA_SECRET_KEY ?? null;
            } catch (\Exception $e) {
                \Log::warning('RecaptchaConfig Error: ' . $e->getMessage());
            }
        }

        // Jika masih tidak ada, allow (development mode)
        if (!$secretKey) {
            \Log::warning('reCAPTCHA Secret Key not configured - allowing request');
            return true;
        }

        // Token MUST exist
        if (!$token) {
            \Log::warning('reCAPTCHA token is missing');
            return false;
        }

        try {
            \Log::info('Verifying reCAPTCHA token', ['action' => $expectedAction]);

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            \Log::info('reCAPTCHA Response', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? 0,
                'action' => $result['action'] ?? null,
                'expected_action' => $expectedAction,
                'error_codes' => $result['error-codes'] ?? [],
            ]);

            // Check 1: Must be successful
            if (!($result['success'] ?? false)) {
                \Log::warning('reCAPTCHA failed: success=false, errors=' . json_encode($result['error-codes'] ?? []));
                return false;
            }

            // Check 2: Score >= 0.5 (0 = bot, 1 = human)
            $score = $result['score'] ?? 0;
            if ($score < 0.5) {
                \Log::warning('reCAPTCHA score too low: ' . $score);
                return false;
            }

            \Log::info('reCAPTCHA verification SUCCESS', ['score' => $score]);
            return true;
        } catch (\Throwable $e) {
            \Log::error('reCAPTCHA Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * LOGIN
     */
    public function showLogin()
    {
        $recaptchaConfig = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.login', compact('recaptchaSiteKey'));
    }

    public function login(Request $request)
    {
        // Validasi utama
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
            'recaptcha_token' => 'nullable|string',
        ], [
            'login.required' => 'Email atau username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('login', 'remember'));
        }

        // Verifikasi reCAPTCHA (bisa disable dulu untuk testing)
        if (!$this->verifyRecaptcha($request->recaptcha_token, 'login')) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        // Tentukan login type (email atau username)
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Cari pengguna berdasarkan email atau username
        $pengguna = Pengguna::where($loginType, $request->login)->first();

        // Cek 1: Apakah pengguna ada?
        if (!$pengguna) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Akun tidak terdaftar dalam sistem.']);
        }

        // Cek 2: Apakah email sudah diverifikasi?
        if (!$pengguna->email_verified_at) {
            return redirect()->route('verify-otp')
                ->with('email', $pengguna->email)
                ->with('warning', 'Email Anda belum diverifikasi. Silakan cek email Anda untuk kode OTP.');
        }

        // Cek 3: Apakah akun aktif?
        if (!$pengguna->is_active) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        // Cek 4: Apakah password benar? (untuk non-Google users)
        if ($pengguna->password) {
            if (!Hash::check($request->password, $pengguna->password)) {
                return redirect()->back()
                    ->withInput($request->only('login', 'remember'))
                    ->withErrors(['password' => 'Password yang Anda masukkan salah.']);
            }
        } else {
            // User Google tanpa password
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Akun ini menggunakan Google Login. Silakan login dengan Google.']);
        }

        // ✅ SEMUA CEK PASSED - Login user
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . ($pengguna->username ?? $this->maskEmail($pengguna->email)) . '!');
        }

        // Jika Auth::attempt() gagal (seharusnya tidak sampai sini jika Hash::check() sudah pass)
        return redirect()->back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Terjadi kesalahan saat login. Silakan coba lagi.']);
    }

    /**
     * REGISTER
     */
    public function showRegister()
    {
        $recaptchaConfig = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.register', compact('recaptchaSiteKey'));
    }

    public function register(Request $request)
    {
        // Auto check email
        $email = strtolower(trim($request->email));

        $existingEmail = Pengguna::where('email', $email)->first();
        if ($existingEmail) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Email sudah terdaftar. Silakan gunakan email lain.']);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:pengguna,email',
            'recaptcha_token' => 'nullable|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'register')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        try {
            $normalizedEmail = strtolower(trim($request->email));

            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

            $cacheKey = 'otp_registration_' . $normalizedEmail;
            Cache::put($cacheKey, [
                'email' => $normalizedEmail,
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'created_at' => now()->toDateTimeString(),
            ], self::OTP_EXPIRY_MINUTES * 60);

            $cooldownKey = 'otp_cooldown_' . $normalizedEmail;
            Cache::put($cooldownKey, Carbon::now()->addSeconds(self::RESEND_COOLDOWN_SECONDS), self::RESEND_COOLDOWN_SECONDS);

            $request->session()->put('otp_email', $normalizedEmail);
            $request->session()->put('email', $normalizedEmail);
            $request->session()->put('otp_session_start', now()->toDateTimeString());

            $mailConfigLoaded = $this->loadMailConfig();

            if (!$mailConfigLoaded) {
                if (app()->environment('local')) {
                    return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                        ->with('email', $normalizedEmail)
                        ->with('warning', 'Mode development: Konfigurasi email belum lengkap. OTP Anda adalah: ' . $otp);
                }

                return back()
                    ->with('error', 'Konfigurasi email belum lengkap. Silakan hubungi administrator.')
                    ->withInput();
            }

            try {
                Mail::send('emails.otp-verification', [
                    'otp' => $otp,
                    'email' => $normalizedEmail,
                    'expiresInMinutes' => self::OTP_EXPIRY_MINUTES,
                ], function ($message) use ($normalizedEmail) {
                    $message->to($normalizedEmail)
                        ->subject('Kode OTP Verifikasi - Niat Zakat');
                });

                return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                    ->with('email', $normalizedEmail)
                    ->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            } catch (\Exception $mailException) {
                if (app()->environment('local')) {
                    return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                        ->with('email', $normalizedEmail)
                        ->with('warning', 'Email tidak terkirim (error SMTP). OTP Anda adalah: ' . $otp);
                }

                return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                    ->with('email', $normalizedEmail)
                    ->with('error', 'Gagal mengirim email. Silakan gunakan tombol "Kirim Ulang OTP".');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * VERIFY OTP
     */
    public function showVerifyOtp(Request $request)
    {
        $email = $request->query('email')
            ?? $request->session()->get('otp_email')
            ?? $request->session()->get('email');

        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        $normalizedEmail = strtolower(trim($email));

        $cacheKey = 'otp_registration_' . $normalizedEmail;
        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return redirect()->route('register')
                ->with('error', 'OTP telah kedaluwarsa. Silakan daftar ulang.');
        }

        $expiresAt = $otpData['expires_at'];

        $cooldownKey = 'otp_cooldown_' . $normalizedEmail;
        $canResendAt = Cache::get($cooldownKey);

        $request->session()->put('otp_email', $normalizedEmail);
        $request->session()->put('email', $normalizedEmail);
        $request->session()->put('otp_session_start', now()->toDateTimeString());

        // Mask email untuk tampilan
        $maskedEmail = $this->maskEmail($email);

        // Tambahkan reCAPTCHA site key
        $recaptchaConfig = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.verify-otp', compact(
            'email',
            'maskedEmail',
            'expiresAt',
            'canResendAt',
            'recaptchaSiteKey' // TAMBAHKAN INI
        ));
    }

    public function verifyOtp(Request $request)
    {
        // Validasi email dan OTP
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'recaptcha_token' => 'nullable|string', // TAMBAHKAN INI
        ], [
            'email.required' => 'Email tidak valid',
            'otp.required' => 'Kode OTP harus diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('email', $request->email);
        }

        // Verifikasi reCAPTCHA
        if (!$this->verifyRecaptcha($request->recaptcha_token, 'verify_otp')) {
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        $cacheKey = 'otp_registration_' . $request->email;
        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'OTP telah kedaluwarsa. Silakan minta kode baru.']);
        }

        if ($otpData['otp'] !== $request->otp) {
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        DB::beginTransaction();

        try {
            // Cek apakah pengguna sudah ada (untuk kasus resend)
            $pengguna = Pengguna::where('email', $request->email)->first();

            if (!$pengguna) {
                // Buat pengguna baru
                $pengguna = Pengguna::create([
                    'uuid' => (string) Str::uuid(),
                    'email' => $request->email,
                    'email_verified_at' => now(),
                    'peran' => 'admin_masjid',
                    'is_active' => false,
                ]);
            } else {
                // Update email_verified_at jika belum diverifikasi
                if (!$pengguna->email_verified_at) {
                    $pengguna->update(['email_verified_at' => now()]);
                }
            }

            // Hapus cache OTP
            Cache::forget($cacheKey);
            Cache::forget('otp_cooldown_' . $request->email);

            // Buat token untuk complete profile
            $profileToken = (string) Str::uuid();
            $cacheData = [
                'email' => $request->email,
                'pengguna_id' => $pengguna->id,
                'token' => $profileToken,
                'created_at' => now()->toDateTimeString(),
            ];

            Cache::put('complete_profile_' . $request->email, $cacheData, 3600);
            Cache::put('token_map_' . $profileToken, $request->email, 3600);

            DB::commit();

            return redirect()->route('complete-profile', ['token' => $profileToken])
                ->with('success', 'Email berhasil diverifikasi. Silakan lengkapi profil Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('OTP Verification Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function resendOtp(Request $request)
    {
        $email = $request->input('email');
        $recaptchaToken = $request->input('recaptcha_token'); // TAMBAHKAN INI

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.'
            ], 400);
        }

        // Verifikasi reCAPTCHA
        if (!$this->verifyRecaptcha($recaptchaToken, 'resend_otp')) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi reCAPTCHA gagal.'
            ], 400);
        }

        try {
            $cooldownKey = 'otp_cooldown_' . $email;
            $canResendAt = Cache::get($cooldownKey);

            if ($canResendAt && Carbon::now()->isBefore($canResendAt)) {
                $remainingSeconds = Carbon::now()->diffInSeconds($canResendAt);
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remainingSeconds} detik sebelum mengirim ulang OTP."
                ], 400);
            }

            // Generate OTP baru
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

            $cacheKey = 'otp_registration_' . $email;
            Cache::put($cacheKey, [
                'email' => $email,
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'created_at' => now()->toDateTimeString(),
            ], self::OTP_EXPIRY_MINUTES * 60);

            // Set cooldown
            Cache::put($cooldownKey, Carbon::now()->addSeconds(self::RESEND_COOLDOWN_SECONDS), self::RESEND_COOLDOWN_SECONDS);

            // Kirim email
            $mailConfigLoaded = $this->loadMailConfig();

            if ($mailConfigLoaded) {
                Mail::send('emails.otp-verification', [
                    'otp' => $otp,
                    'email' => $email,
                    'expiresInMinutes' => self::OTP_EXPIRY_MINUTES,
                ], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Kode OTP Baru - Niat Zakat');
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP baru telah dikirim ke email Anda.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * FORGOT PASSWORD
     */
    public function showForgotPasswordForm()
    {
        $recaptchaConfig = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.forgot-password', compact('recaptchaSiteKey'));
    }

    public function sendResetLink(Request $request)
    {
        // Auto check email
        $email = strtolower(trim($request->email));

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:pengguna,email',
            'recaptcha_token' => 'nullable|string',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'forgot_password')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        try {
            $email = $request->email;
            $pengguna = Pengguna::where('email', $email)->first();

            if (!$pengguna) {
                return redirect()->back()
                    ->with('error', 'Email tidak terdaftar.')
                    ->withInput();
            }

            $cooldownKey = 'password_reset_cooldown_' . $email;
            $cooldownExpiry = Cache::get($cooldownKey);

            if ($cooldownExpiry && Carbon::now()->isBefore($cooldownExpiry)) {
                $remainingSeconds = Carbon::now()->diffInSeconds($cooldownExpiry);
                return redirect()->back()
                    ->with('error', "Mohon tunggu {$remainingSeconds} detik sebelum meminta link reset password lagi")
                    ->withInput();
            }

            $cacheKey = 'password_reset_' . $pengguna->uuid;
            $existingData = Cache::get($cacheKey);

            if ($existingData) {
                $expiresAt = Carbon::parse($existingData['expires_at']);

                if (Carbon::now()->lt($expiresAt)) {
                    $token = $existingData['token'];
                } else {
                    $token = Str::random(64);
                }
            } else {
                $token = Str::random(64);
            }

            $expiresAt = Carbon::now()->addMinutes(self::PASSWORD_RESET_EXPIRY_MINUTES);

            Cache::put($cacheKey, [
                'email' => $email,
                'token' => $token,
                'pengguna_id' => $pengguna->id,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::PASSWORD_RESET_EXPIRY_MINUTES * 60);

            Cache::put($cooldownKey, Carbon::now()->addSeconds(60), 60);

            $mailConfigLoaded = $this->loadMailConfig();

            if ($mailConfigLoaded) {
                $resetUrl = route('password.reset', [
                    'uuid' => $pengguna->uuid,
                    'token' => $token
                ]);

                Mail::send('emails.password-reset', [
                    'token' => $token,
                    'email' => $email,
                    'nama' => $pengguna->username ?? $this->maskEmail($pengguna->email),
                    'expiresInMinutes' => self::PASSWORD_RESET_EXPIRY_MINUTES,
                    'resetUrl' => $resetUrl,
                ], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Reset Password - Niat Zakat');
                });
            }

            $request->session()->put('email', $email);
            $request->session()->flash('email', $email);

            return redirect()->route('password.reset-sent')
                ->with('email', $email)
                ->with('success', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput();
        }
    }

    // Dalam method showResetSent di AuthController
    public function showResetSent(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->query('email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi telah berakhir. Silakan minta reset password ulang.');
        }

        $pengguna = Pengguna::where('email', $email)->first();

        if (!$pengguna) {
            return redirect()->route('password.request')
                ->with('error', 'Pengguna tidak ditemukan.');
        }

        $cacheKey = 'password_reset_' . $pengguna->uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa. Silakan minta ulang.');
        }

        $expiresAt = Carbon::parse($resetData['expires_at']);

        $cooldownKey = 'password_reset_cooldown_' . $email;
        $canResendAt = Cache::get($cooldownKey);

        // ⭐⭐ PERBAIKAN: Hitung canResendIn dengan benar
        $canResendIn = 0;
        if ($canResendAt) {
            $canResendIn = Carbon::now()->diffInSeconds($canResendAt);
            if ($canResendIn < 0) {
                $canResendIn = 0;
            }
        }

        $request->session()->put('email', $email);
        $request->session()->flash('email', $email);

        // Mask email untuk tampilan seperti pada gambar
        $maskedEmail = $this->maskEmail($email);

        // Countdown timer (8 menit = 480 detik dari expires_at)
        $countdownSeconds = max(0, Carbon::now()->diffInSeconds($expiresAt));
        if ($countdownSeconds > 480) {
            $countdownSeconds = 480;
        }

        $recaptchaConfig = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.reset-password-sent', [
            'email' => $email,
            'maskedEmail' => $maskedEmail,
            'expiresAt' => $expiresAt,
            'canResendAt' => $canResendAt,
            'canResendIn' => $canResendIn, // ⭐⭐ Kirim ini ke view
            'countdownSeconds' => $countdownSeconds,
            'pengguna' => $pengguna,
            'recaptchaSiteKey' => $recaptchaSiteKey
        ]);
    }
    public function showResetPasswordForm(Request $request, $uuid, $token)
    {
        $cacheKey = 'password_reset_' . $uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password tidak valid atau telah kadaluarsa.');
        }

        if (!hash_equals($resetData['token'], $token)) {
            return redirect()->route('password.request')
                ->with('error', 'Token reset password tidak valid.');
        }

        $expiresAt = Carbon::parse($resetData['expires_at']);
        if (Carbon::now()->gt($expiresAt)) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa.');
        }

        // Definisikan variabel $email dari $resetData
        $email = $resetData['email']; // ✅ TAMBAHKAN INI

        // Mask email untuk tampilan
        $maskedEmail = $this->maskEmail($email);

        // Get reCAPTCHA site key
        $recaptchaConfig = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.reset-password', compact(
            'email',        // ✅ Sekarang $email sudah didefinisikan
            'maskedEmail',
            'token',
            'uuid',
            'recaptchaSiteKey'
        ));
    }

    /**
     * RESEND RESET LINK EMAIL
     */
    public function resendResetLink(Request $request)
    {
        $email = $request->input('email');
        $recaptchaToken = $request->input('recaptcha_token');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.'
            ], 400);
        }

        // Verifikasi reCAPTCHA
        if (!$this->verifyRecaptcha($recaptchaToken, 'resend_reset_link')) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi reCAPTCHA gagal.'
            ], 400);
        }

        try {
            $cooldownKey = 'password_reset_cooldown_' . $email;
            $canResendAt = Cache::get($cooldownKey);

            if ($canResendAt && Carbon::now()->isBefore($canResendAt)) {
                $remainingSeconds = Carbon::now()->diffInSeconds($canResendAt);
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remainingSeconds} detik sebelum mengirim ulang link reset password."
                ], 400);
            }

            $pengguna = Pengguna::where('email', $email)->first();

            if (!$pengguna) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.'
                ], 404);
            }

            $cacheKey = 'password_reset_' . $pengguna->uuid;
            $existingData = Cache::get($cacheKey);

            // Buat token baru setiap kali resend
            $token = Str::random(64);
            $expiresAt = Carbon::now()->addMinutes(self::PASSWORD_RESET_EXPIRY_MINUTES);

            // Simpan data reset baru
            Cache::put($cacheKey, [
                'email' => $email,
                'token' => $token,
                'pengguna_id' => $pengguna->id,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::PASSWORD_RESET_EXPIRY_MINUTES * 60);

            // Set cooldown 60 detik (1 menit)
            Cache::put($cooldownKey, Carbon::now()->addSeconds(60), 60);

            $mailConfigLoaded = $this->loadMailConfig();

            if ($mailConfigLoaded) {
                $resetUrl = route('password.reset', [
                    'uuid' => $pengguna->uuid,
                    'token' => $token
                ]);

                try {
                    Mail::send('emails.password-reset', [
                        'token' => $token,
                        'email' => $email,
                        'nama' => $pengguna->username ?? $this->maskEmail($pengguna->email),
                        'expiresInMinutes' => self::PASSWORD_RESET_EXPIRY_MINUTES,
                        'resetUrl' => $resetUrl,
                    ], function ($message) use ($email) {
                        $message->to($email)
                            ->subject('Reset Password - Niat Zakat');
                    });
                } catch (\Exception $mailEx) {
                    \Log::error('Gagal mengirim email reset: ' . $mailEx->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengirim email. Silakan coba lagi.'
                    ], 500);
                }
            } else {
                \Log::warning('Konfigurasi email tidak ditemukan');
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi email belum lengkap.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ulang ke email Anda.',
                'canResendIn' => 60 // 60 detik cooldown
            ]);
        } catch (\Exception $e) {
            \Log::error('Exception di resendResetLink: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function resetPassword(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cacheKey = 'password_reset_' . $uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa.');
        }

        if (!hash_equals($resetData['token'], $request->token)) {
            return redirect()->route('password.request')
                ->with('error', 'Token reset password tidak valid.');
        }

        if ($resetData['email'] !== $request->email) {
            return redirect()->route('password.request')
                ->with('error', 'Email tidak sesuai.');
        }

        $expiresAt = Carbon::parse($resetData['expires_at']);
        if (Carbon::now()->gt($expiresAt)) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa.');
        }

        DB::beginTransaction();

        try {
            $pengguna = Pengguna::find($resetData['pengguna_id']);

            if (!$pengguna) {
                throw new \Exception('Pengguna tidak ditemukan');
            }

            $pengguna->update([
                'password' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

            Cache::forget($cacheKey);
            Cache::forget('password_reset_cooldown_' . $request->email);

            try {
                $this->loadMailConfig();

                Mail::send('emails.password-reset-success', [
                    'nama' => $pengguna->username ?? $this->maskEmail($pengguna->email),
                    'email' => $pengguna->email,
                    'tanggal' => now()->format('d F Y H:i:s'),
                ], function ($message) use ($pengguna) {
                    $message->to($pengguna->email)
                        ->subject('Password Berhasil Diubah - Niat Zakat');
                });
            } catch (\Exception $mailEx) {
                // Silent fail untuk email
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * COMPLETE PROFILE
     */
    public function showCompleteProfile($token)
    {
        if (!$token) {
            return redirect()->route('register')
                ->with('error', 'Token tidak valid. Silakan daftar ulang.');
        }

        $tokenMapKey = 'token_map_' . $token;
        $email = Cache::get($tokenMapKey);

        if (!$email) {
            $inactiveUsers = Pengguna::where('is_active', false)
                ->whereNotNull('email_verified_at')
                ->get();

            foreach ($inactiveUsers as $user) {
                $possibleKeys = [
                    'complete_profile_' . $user->email,
                    'google_complete_' . $user->email,
                ];

                foreach ($possibleKeys as $cacheKey) {
                    $cacheData = Cache::get($cacheKey);

                    if ($cacheData && isset($cacheData['token']) && $cacheData['token'] === $token) {
                        $email = $user->email;
                        break 2;
                    }
                }
            }

            if (!$email) {
                return redirect()->route('register')
                    ->with('error', 'Token tidak valid atau sesi telah berakhir. Silakan daftar ulang.');
            }
        }

        $cacheKey = 'complete_profile_' . $email;
        $cacheData = Cache::get($cacheKey);

        if (!$cacheData) {
            $cacheKey = 'google_complete_' . $email;
            $cacheData = Cache::get($cacheKey);
        }

        if (!$cacheData || !isset($cacheData['pengguna_id'])) {
            return redirect()->route('register')
                ->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        $pengguna = Pengguna::find($cacheData['pengguna_id']);

        if (!$pengguna) {
            return redirect()->route('register')
                ->with('error', 'Pengguna tidak ditemukan. Silakan daftar ulang.');
        }

        try {
            $provinces = Province::orderBy('name', 'asc')->get();
            $isGoogleUser = !empty($pengguna->google_id);

            // Mask email untuk tampilan
            $maskedEmail = $this->maskEmail($pengguna->email);

            $recaptchaConfig = RecaptchaConfig::first();
            $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

            return view('auth.complete-profile', compact(
                'pengguna',
                'maskedEmail',
                'isGoogleUser',
                'provinces',
                'token',
                'recaptchaSiteKey'
            ));
        } catch (\Exception $e) {
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat memuat data wilayah. Silakan coba lagi.');
        }
    }

    public function storeCompleteProfile(Request $request, $token)
    {
        $pengguna = Pengguna::find($request->pengguna_id);

        if (!$pengguna) {
            return back()->with('error', 'Pengguna tidak ditemukan')->withInput();
        }

        $isGoogleUser = !empty($pengguna->google_id);

        // =====================================================
        // VALIDASI SEMUA FIELD WAJIB
        // =====================================================
        $rules = [
            // Data Akun
            'username' => $isGoogleUser ? 'nullable' : [
                'required',
                'string',
                'min:6',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:pengguna,username,' . $pengguna->id
            ],
            'password' => $isGoogleUser ? 'nullable' : 'required|string|min:8|confirmed',

            // Data Admin Masjid
            'admin_nama' => 'required|string|max:255',
            'admin_telepon' => 'required|string|max:20',
            'admin_email' => 'required|email|max:255',
            'admin_foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',

            // Data Masjid
            'nama_masjid' => 'required|string|max:255',
            'alamat' => 'required|string',
            'provinsi_kode' => 'required|string|exists:indonesia_provinces,code',
            'kota_kode' => 'required|string|exists:indonesia_cities,code',
            'kecamatan_kode' => 'required|string|exists:indonesia_districts,code',
            'kelurahan_kode' => 'required|string|exists:indonesia_villages,code',
            'kode_pos' => 'required|string|max:5',
            'telepon' => 'required|string|max:20',
            'email_masjid' => 'required|email|max:255',

            // Sejarah Masjid
            'sejarah' => 'required|string',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'pendiri' => 'required|string|max:255',
            'kapasitas_jamaah' => 'required|integer|min:1',

            // Foto Masjid
            'foto_masjid.*' => 'required|image|mimes:jpeg,jpg,png|max:2048',

            // reCAPTCHA
            'recaptcha_token' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'complete_profile')) {
            return back()
                ->with('error', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.')
                ->withInput();
        }

        // =====================================================
        // MULAI TRANSAKSI DATABASE
        // =====================================================
        DB::beginTransaction();

        try {
            // STEP 1: Update data pengguna (username dan password)
            $penggunaData = ['is_active' => true];

            if ($isGoogleUser) {
                $penggunaData['username'] = $this->generateUniqueUsername($pengguna->email, $pengguna->id);
            } else {
                $penggunaData['username'] = $request->username;
                $penggunaData['password'] = Hash::make($request->password);
            }

            $pengguna->update($penggunaData);

            // STEP 2: Upload foto admin
            $adminFotoPath = null;
            if ($request->hasFile('admin_foto')) {
                $adminFotoPath = $request->file('admin_foto')->store('admin-fotos', 'public');
            }

            // STEP 3: Upload foto masjid (multiple)
            $fotoMasjidArray = [];
            if ($request->hasFile('foto_masjid')) {
                $uploadedFiles = $request->file('foto_masjid');

                if (count($uploadedFiles) > Masjid::MAX_FOTO) {
                    DB::rollBack();
                    return back()
                        ->with('error', 'Maksimal ' . Masjid::MAX_FOTO . ' foto masjid yang diperbolehkan')
                        ->withInput();
                }

                foreach ($uploadedFiles as $index => $foto) {
                    $path = $foto->store('masjid-fotos', 'public');
                    $fotoMasjidArray[] = $path;
                }
            }

            // STEP 4: Ambil data wilayah dari database
            $provinsi = Province::where('code', $request->provinsi_kode)->first();
            $kota = City::where('code', $request->kota_kode)->first();
            $kecamatan = District::where('code', $request->kecamatan_kode)->first();
            $kelurahan = Village::where('code', $request->kelurahan_kode)->first();

            if (!$provinsi || !$kota || !$kecamatan || !$kelurahan) {
                DB::rollBack();
                return back()
                    ->with('error', 'Data wilayah tidak valid. Silakan pilih ulang.')
                    ->withInput();
            }

            // STEP 5: Generate kode pos (auto-fill dari wilayah jika kosong)
            $kodePos = $request->kode_pos;
            if (!$kodePos && $kelurahan->meta && is_array($kelurahan->meta)) {
                $kodePos = $kelurahan->meta['postal_code'] ?? null;
            }

            // STEP 6: Generate kode masjid otomatis
            $kodeMasjid = $this->generateKodeMasjid();

            // STEP 7: Cek duplikasi nama masjid di kelurahan yang sama
            $existingMasjid = Masjid::where('nama', $request->nama_masjid)
                ->where('kelurahan_kode', $request->kelurahan_kode)
                ->first();

            if ($existingMasjid) {
                DB::rollBack();
                return back()
                    ->with('error', 'Masjid dengan nama "' . $request->nama_masjid . '" sudah terdaftar di kelurahan ' . $kelurahan->name)
                    ->withInput();
            }

            // =====================================================
            // STEP 8: CREATE DATA MASJID
            // =====================================================
            $masjid = Masjid::create([
                'uuid' => (string) Str::uuid(),
                'kode_masjid' => $kodeMasjid,

                // Data Admin Masjid
                'admin_nama' => $request->admin_nama,
                'admin_telepon' => $request->admin_telepon,
                'admin_email' => $request->admin_email ?? $pengguna->email,
                'admin_foto' => $adminFotoPath,

                // Data Masjid
                'nama' => $request->nama_masjid,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email_masjid,

                // Data Wilayah
                'provinsi_kode' => $request->provinsi_kode,
                'provinsi_nama' => $provinsi->name,
                'kota_kode' => $request->kota_kode,
                'kota_nama' => $kota->name,
                'kecamatan_kode' => $request->kecamatan_kode,
                'kecamatan_nama' => $kecamatan->name,
                'kelurahan_kode' => $request->kelurahan_kode,
                'kelurahan_nama' => $kelurahan->name,
                'kode_pos' => $kodePos,

                // Data Sejarah
                'sejarah' => $request->sejarah,
                'tahun_berdiri' => $request->tahun_berdiri,
                'pendiri' => $request->pendiri,
                'kapasitas_jamaah' => $request->kapasitas_jamaah,

                // Foto Masjid
                'foto' => !empty($fotoMasjidArray) ? $fotoMasjidArray : null,

                'is_active' => true,
            ]);


            $pengguna->update([
                'masjid_id' => $masjid->id,
            ]);

            // STEP 10: Cleanup cache registrasi
            $this->cleanupRegistrationCache($pengguna->email, $token);

            // STEP 11: Kirim email sukses (optional, bisa gagal tanpa rollback)
            try {
                $this->loadMailConfig();

                Mail::send('emails.registration-success', [
                    'nama' => $request->admin_nama,
                    'username' => $penggunaData['username'],
                    'email' => $pengguna->email,
                    'nama_masjid' => $request->nama_masjid,
                    'kode_masjid' => $kodeMasjid,
                    'isGoogleUser' => $isGoogleUser,
                    'password' => $isGoogleUser ? null : $request->password,
                ], function ($message) use ($pengguna) {
                    $message->to($pengguna->email)
                        ->subject('Registrasi Berhasil - Niat Zakat');
                });
            } catch (\Exception $mailEx) {
                // Silent fail untuk email - tidak perlu rollback
                \Log::warning('Email registrasi gagal dikirim: ' . $mailEx->getMessage());
            }

            // =====================================================
            // COMMIT TRANSAKSI - SEMUA DATA TERSIMPAN
            // =====================================================
            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            // =====================================================
            // ROLLBACK JIKA ADA ERROR
            // =====================================================
            DB::rollBack();

            \Log::error('Complete Profile Error: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * HELPER METHODS
     */
    private function cleanupRegistrationCache(string $email, string $token)
    {
        $keysToForget = [
            'complete_profile_' . $email,
            'google_complete_' . $email,
            'otp_registration_' . $email,
            'otp_cooldown_' . $email,
            'token_map_' . $token,
        ];

        foreach ($keysToForget as $key) {
            Cache::forget($key);
        }
    }

    private function generateUniqueUsername(string $email, ?int $excludePenggunaId = null): string
    {
        $emailParts = explode('@', $email);
        $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $emailParts[0]);
        $baseUsername = strtolower($baseUsername);

        $username = $baseUsername;
        $counter = 1;

        while (Pengguna::where('username', $username)
            ->when($excludePenggunaId, fn($q) => $q->where('id', '!=', $excludePenggunaId))
            ->exists()
        ) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    private function generateKodeMasjid(): string
    {
        $prefix = 'MSJ';
        $year = date('Y');

        return DB::transaction(function () use ($prefix, $year) {
            $lastMasjid = Masjid::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $number = $lastMasjid ? (int) substr($lastMasjid->kode_masjid, -4) + 1 : 1;
            $kodeMasjid = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $attempts = 0;
            while (Masjid::where('kode_masjid', $kodeMasjid)->exists() && $attempts < 10) {
                $number++;
                $kodeMasjid = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            if ($attempts >= 10) {
                throw new \Exception('Failed to generate unique kode masjid after 10 attempts');
            }

            return $kodeMasjid;
        });
    }

    /**
     * AJAX METHODS - Auto Check Username dan Email
     */
    public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        $penggunaId = $request->query('pengguna_id');

        if (!$username) {
            return response()->json(['available' => false, 'message' => 'Username tidak boleh kosong']);
        }

        if (strlen($username) < 6) {
            return response()->json(['available' => false, 'message' => 'Username minimal 6 karakter']);
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return response()->json(['available' => false, 'message' => 'Username hanya boleh huruf, angka, dan underscore']);
        }

        $exists = Pengguna::where('username', $username)
            ->when($penggunaId, fn($q) => $q->where('id', '!=', $penggunaId))
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username sudah digunakan' : 'Username tersedia'
        ]);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        $penggunaId = $request->query('pengguna_id');

        if (!$email) {
            return response()->json(['available' => false, 'message' => 'Email tidak boleh kosong']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['available' => false, 'message' => 'Format email tidak valid']);
        }

        $exists = Pengguna::where('email', $email)
            ->when($penggunaId, fn($q) => $q->where('id', '!=', $penggunaId))
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * GOOGLE OAUTH
     */
    public function redirectToGoogle(Request $request)
    {
        $action = $request->query('action', 'login');
        session(['oauth_action' => $action]);

        $googleConfig = GoogleConfig::first();

        if (!$googleConfig || !$googleConfig->GOOGLE_CLIENT_ID || !$googleConfig->GOOGLE_CLIENT_SECRET) {
            return redirect()->route($action === 'register' ? 'register' : 'login')
                ->with('error', 'Google OAuth belum dikonfigurasi. Silakan hubungi administrator.');
        }

        config([
            'services.google.client_id' => $googleConfig->GOOGLE_CLIENT_ID,
            'services.google.client_secret' => $googleConfig->GOOGLE_CLIENT_SECRET,
            'services.google.redirect' => $googleConfig->GOOGLE_REDIRECT_URI ?? url('/auth/google/callback'),
        ]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleConfig = GoogleConfig::first();

            if ($googleConfig) {
                config([
                    'services.google.client_id' => $googleConfig->GOOGLE_CLIENT_ID,
                    'services.google.client_secret' => $googleConfig->GOOGLE_CLIENT_SECRET,
                    'services.google.redirect' => $googleConfig->GOOGLE_REDIRECT_URI ?? url('/auth/google/callback'),
                ]);
            }

            $googleUser = Socialite::driver('google')->user();
            $action = session('oauth_action', 'login');
            session()->forget('oauth_action');

            if ($action === 'register') {
                return $this->handleGoogleRegister($googleUser);
            }

            return $this->handleGoogleLogin($googleUser, $request);
        } catch (\Exception $e) {
            $redirectRoute = session('oauth_action', 'login') === 'register' ? 'register' : 'login';
            session()->forget('oauth_action');

            return redirect()->route($redirectRoute)
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }

    private function handleGoogleRegister($googleUser)
    {
        $existingGoogleUser = Pengguna::where('google_id', $googleUser->id)->first();
        if ($existingGoogleUser) {
            return redirect()->route('register')
                ->with('error', 'Akun Google sudah terdaftar. Silakan login.');
        }

        $existingEmail = Pengguna::where('email', $googleUser->email)->first();
        if ($existingEmail) {
            return redirect()->route('register')
                ->with('error', 'Email sudah terdaftar dengan metode lain. Silakan login.');
        }

        DB::beginTransaction();
        try {
            $pengguna = Pengguna::create([
                'uuid' => (string) Str::uuid(),
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
                'email_verified_at' => now(),
                'peran' => 'admin_masjid',
                'is_active' => false,
            ]);

            $profileToken = (string) Str::uuid();

            $cacheData = [
                'email' => $googleUser->email,
                'pengguna_id' => $pengguna->id,
                'token' => $profileToken,
                'created_at' => now()->toDateTimeString(),
                'source' => 'google_oauth'
            ];

            Cache::put('complete_profile_' . $googleUser->email, $cacheData, 3600);
            Cache::put('google_complete_' . $googleUser->email, $cacheData, 3600);
            Cache::put('token_map_' . $profileToken, $googleUser->email, 3600);

            DB::commit();

            return redirect()->route('complete-profile', ['token' => $profileToken])
                ->with('success', 'Akun berhasil dibuat dengan Google. Silakan lengkapi profil Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat registrasi dengan Google. Silakan coba lagi.');
        }
    }

    private function handleGoogleLogin($googleUser, Request $request)
    {
        $pengguna = Pengguna::where('google_id', $googleUser->id)->first();

        if (!$pengguna) {
            $pengguna = Pengguna::where('email', $googleUser->email)->first();
        }

        if (!$pengguna) {
            return redirect()->route('login')
                ->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        }

        if (!$pengguna->email_verified_at) {
            return redirect()->route('login')
                ->with('error', 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.');
        }

        if (!$pengguna->is_active) {
            $profileToken = (string) Str::uuid();

            $cacheData = [
                'email' => $pengguna->email,
                'pengguna_id' => $pengguna->id,
                'token' => $profileToken,
                'created_at' => now()->toDateTimeString(),
                'source' => 'google_login_reactivate'
            ];

            Cache::put('complete_profile_' . $pengguna->email, $cacheData, 3600);
            Cache::put('google_complete_' . $pengguna->email, $cacheData, 3600);
            Cache::put('token_map_' . $profileToken, $pengguna->email, 3600);

            return redirect()->route('complete-profile', ['token' => $profileToken])
                ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if (!$pengguna->google_id) {
            $pengguna->update([
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]);
        }

        Auth::login($pengguna);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang, ' . ($pengguna->username ?? $this->maskEmail($pengguna->email)) . '!');
    }

    /**
     * AJAX WILAYAH METHODS (untuk complete profile)
     */
    public function getCities(Request $request)
    {
        try {
            $provinceCode = $request->input('province_code');

            if (!$provinceCode) {
                return response()->json(['error' => 'Province code required'], 400);
            }

            $cities = City::where('province_code', $provinceCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name']);

            return response()->json(['cities' => $cities]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load cities'], 500);
        }
    }

    public function getDistricts(Request $request)
    {
        try {
            $cityCode = $request->input('city_code');

            if (!$cityCode) {
                return response()->json(['error' => 'City code required'], 400);
            }

            $districts = District::where('city_code', $cityCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name']);

            return response()->json(['districts' => $districts]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }

    public function getVillages(Request $request)
    {
        try {
            $districtCode = $request->input('district_code');

            if (!$districtCode) {
                return response()->json(['error' => 'District code required'], 400);
            }

            $villages = Village::where('district_code', $districtCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name', 'meta']);

            return response()->json(['villages' => $villages]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load villages'], 500);
        }
    }

    public function getPostalCode(Request $request)
    {
        try {
            $villageCode = $request->input('village_code');

            if (!$villageCode) {
                return response()->json(['error' => 'Village code required'], 400);
            }

            $village = Village::where('code', $villageCode)->first();

            if (!$village) {
                return response()->json(['error' => 'Village not found'], 404);
            }

            $postalCode = null;
            if ($village->meta && is_array($village->meta)) {
                $postalCode = $village->meta['postal_code'] ?? null;
            }

            return response()->json(['postal_code' => $postalCode]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load postal code'], 500);
        }
    }
}