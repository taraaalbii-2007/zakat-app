<?php

namespace App\Http\Controllers\Admin_masjid;

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
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class ProfilAdminMasjidController extends Controller
{
    protected $user;
    protected $masjid;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user   = Auth::user();
            $this->masjid = $this->user?->masjid;

            if (!$this->user) abort(403, 'Unauthorized');
            if (!$this->user->isAdminMasjid()) abort(403, 'Hanya Admin Masjid yang dapat mengakses halaman ini');
            if (!$this->masjid) abort(404, 'Data masjid tidak ditemukan');

            return $next($request);
        });
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------
    public function show()
    {
        $user   = $this->user;
        $masjid = $this->masjid;
        return view('admin-masjid.profil.show', compact('user', 'masjid'));
    }

    // ---------------------------------------------------------------
    // EDIT — form edit profil (hanya data admin masjid, masjid, sejarah)
    // ---------------------------------------------------------------
    public function edit()
    {
        $user   = $this->user;
        $masjid = $this->masjid;

        // Ambil semua provinsi
        $provinces = Province::orderBy('name')->get();
        
        // Ambil semua kota (untuk keperluan JavaScript)
        $cities = City::orderBy('name')->get();
        
        // Ambil semua kecamatan (untuk keperluan JavaScript)
        $districts = District::orderBy('name')->get();
        
        // Ambil semua kelurahan (untuk keperluan JavaScript)
        $villages = Village::orderBy('name')->get();

        return view('admin-masjid.profil.edit', compact(
            'user', 'masjid', 'provinces', 'cities', 'districts', 'villages'
        ));
    }

    // ---------------------------------------------------------------
    // EDIT EMAIL — form ubah email (halaman tersendiri)
    // ---------------------------------------------------------------
    public function editEmail()
    {
        $user   = $this->user;
        $masjid = $this->masjid;
        return view('admin-masjid.profil.ubah-email', compact('user', 'masjid'));
    }

    // ---------------------------------------------------------------
    // EDIT PASSWORD — form ubah password
    // ---------------------------------------------------------------
    public function editPassword()
    {
        $user   = $this->user;
        $masjid = $this->masjid;
        return view('admin-masjid.profil.ubah-password', compact('user', 'masjid'));
    }

    // ---------------------------------------------------------------
    // UPDATE — simpan perubahan profil (TANPA username dan email)
    // ---------------------------------------------------------------
    public function update(Request $request)
    {
        $user   = $this->user;
        $masjid = $this->masjid;

        $validator = Validator::make($request->all(), [
            'admin_nama'       => 'nullable|string|max:255',
            'admin_telepon'    => 'nullable|string|max:20',
            'admin_email'      => 'nullable|email|max:255',
            'admin_foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hapus_admin_foto' => 'nullable|boolean',
            'nama'             => 'required|string|max:255',
            'alamat'           => 'required|string',
            'provinsi_kode'    => 'required|string|max:5',
            'kota_kode'        => 'required|string|max:10',
            'kecamatan_kode'   => 'required|string|max:15',
            'kelurahan_kode'   => 'required|string|max:20',
            'kode_pos'         => 'nullable|string|max:10',
            'telepon'          => 'nullable|string|max:20',
            'email_masjid'     => 'nullable|email|max:255',
            'deskripsi'        => 'nullable|string',
            'sejarah'          => 'nullable|string',
            'tahun_berdiri'    => 'nullable|digits:4|integer|min:1000|max:' . (date('Y') + 1),
            'pendiri'          => 'nullable|string|max:255',
            'kapasitas_jamaah' => 'nullable|integer|min:0',
            'fotos'            => 'nullable|array|max:' . $masjid->getRemainingFotoSlots(),
            'fotos.*'          => 'image|mimes:jpg,jpeg,png|max:2048',
            'hapus_foto_index'    => 'nullable|array',
            'hapus_foto_index.*'  => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Handle foto admin
            $adminFotoPath = $masjid->admin_foto;
            if ($request->boolean('hapus_admin_foto', false)) {
                if ($adminFotoPath && Storage::disk('public')->exists($adminFotoPath)) {
                    Storage::disk('public')->delete($adminFotoPath);
                }
                $adminFotoPath = null;
            } elseif ($request->hasFile('admin_foto')) {
                if ($adminFotoPath && Storage::disk('public')->exists($adminFotoPath)) {
                    Storage::disk('public')->delete($adminFotoPath);
                }
                $file          = $request->file('admin_foto');
                $filename      = time() . '_admin_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $adminFotoPath = $file->storeAs('fotos/admin/' . $masjid->id, $filename, 'public');
            }

            // Hapus foto masjid
            $hapusFotoIndex = array_filter(
                $request->input('hapus_foto_index', []),
                fn($v) => $v !== '' && $v !== null
            );
            if (!empty($hapusFotoIndex)) {
                foreach (array_unique(array_map('intval', $hapusFotoIndex)) as $idx) {
                    $masjid->removeFotoByIndex($idx);
                }
                $masjid->refresh();
            }

            // Tambah foto masjid baru
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    if ($masjid->canAddMoreFotos()) {
                        $fn   = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                        $path = $foto->storeAs('fotos/masjid/' . $masjid->id, $fn, 'public');
                        $masjid->addFoto($path);
                    } else {
                        break;
                    }
                }
                $masjid->refresh();
            }

            // Update masjid
            $masjid->update([
                'admin_nama'       => $request->admin_nama,
                'admin_telepon'    => $request->admin_telepon,
                'admin_email'      => $request->admin_email,
                'admin_foto'       => $adminFotoPath,
                'nama'             => $request->nama,
                'alamat'           => $request->alamat,
                'provinsi_kode'    => $request->provinsi_kode,
                'kota_kode'        => $request->kota_kode,
                'kecamatan_kode'   => $request->kecamatan_kode,
                'kelurahan_kode'   => $request->kelurahan_kode,
                'kode_pos'         => $request->kode_pos,
                'telepon'          => $request->telepon,
                'email'            => $request->email_masjid,
                'deskripsi'        => $request->deskripsi,
                'sejarah'          => $request->sejarah,
                'tahun_berdiri'    => $request->tahun_berdiri ?: null,
                'pendiri'          => $request->pendiri,
                'kapasitas_jamaah' => $request->kapasitas_jamaah ?: null,
            ]);

            DB::commit();

            return redirect()->route('admin-masjid.profil.show')
                ->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update profil admin masjid: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
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
            Log::error('Gagal update email admin masjid: ' . $e->getMessage());
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
            return redirect()->back()->withErrors($validator)->withInput();
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
            Log::error('Update password admin masjid error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah password: ' . $e->getMessage())->withInput();
        }
    }
}