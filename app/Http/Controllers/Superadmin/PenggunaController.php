<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Amil;
use App\Models\Masjid;
use App\Models\MailConfig;
use App\Models\Pengguna;
use App\Mail\PenggunaRegistrationMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class PenggunaController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Pengguna::with('masjid')->latest();

        if ($search = $request->get('q')) {
            $query->search($search);
        }
        if ($peran = $request->get('peran')) {
            $query->byPeran($peran);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'aktif');
        }
        if ($masjidId = $request->get('masjid_id')) {
            $query->where('masjid_id', $masjidId);
        }

        $pengguna   = $query->paginate(10);
        $masjidList = Masjid::orderBy('nama')->get(['id', 'nama']);

        return view('superadmin.pengguna.index', compact('pengguna', 'masjidList'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        // Hanya dibutuhkan untuk peran Amil (pilih masjid yang sudah ada)
        $masjidList = Masjid::orderBy('nama')->get(['id', 'uuid', 'nama', 'kode_masjid']);

        // Untuk form Admin Masjid (wilayah Indonesia)
        $provinces = Province::orderBy('name')->get(['code', 'name']);

        return view('superadmin.pengguna.create', compact('masjidList', 'provinces'));
    }

    public function store(Request $request): RedirectResponse
    {
        // ── Validasi Dasar ────────────────────────────────────────────────────
        $rules = [
            'peran'     => ['required', Rule::in(['superadmin', 'admin_masjid', 'amil'])],
            'username'  => ['nullable', 'string', 'max:255', 'unique:pengguna,username'],
            'email'     => ['required', 'email', 'max:255', 'unique:pengguna,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ];

        // ── Validasi Tambahan: Admin Masjid → Buat Masjid Baru ────────────────
        if ($request->peran === 'admin_masjid') {
            $rules = array_merge($rules, [
                'admin_nama'       => ['required', 'string', 'max:255'],
                'admin_telepon'    => ['required', 'string', 'max:20'],
                'admin_email'      => ['required', 'email', 'max:255'],
                'admin_foto'       => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'nama_masjid'      => ['required', 'string', 'max:255'],
                'alamat'           => ['required', 'string'],
                'provinsi_kode'    => ['required', 'string', 'exists:indonesia_provinces,code'],
                'kota_kode'        => ['required', 'string', 'exists:indonesia_cities,code'],
                'kecamatan_kode'   => ['required', 'string', 'exists:indonesia_districts,code'],
                'kelurahan_kode'   => ['required', 'string', 'exists:indonesia_villages,code'],
                'kode_pos'         => ['nullable', 'string', 'max:5'],
                'telepon_masjid'   => ['required', 'string', 'max:20'],
                'email_masjid'     => ['required', 'email', 'max:255'],
                'deskripsi_masjid' => ['nullable', 'string'],
                'sejarah'          => ['nullable', 'string'],
                'tahun_berdiri'    => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
                'pendiri'          => ['nullable', 'string', 'max:255'],
                'kapasitas_jamaah' => ['nullable', 'integer', 'min:1'],
                'foto_masjid'      => ['nullable', 'array'],
                'foto_masjid.*'    => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            ]);
        }

        // ── Validasi Tambahan: Amil → Pilih Masjid yang Ada ───────────────────
        if ($request->peran === 'amil') {
            $rules = array_merge($rules, [
                'masjid_id'                  => ['required', 'exists:masjid,id'],
                'amil_nama_lengkap'          => ['required', 'string', 'max:255'],
                'amil_jenis_kelamin'         => ['required', Rule::in(['L', 'P'])],
                'amil_tempat_lahir'          => ['required', 'string', 'max:100'],
                'amil_tanggal_lahir'         => ['required', 'date', 'before_or_equal:today'],
                'amil_alamat'                => ['required', 'string'],
                'amil_telepon'               => ['required', 'string', 'max:20'],
                'amil_tanggal_mulai_tugas'   => ['required', 'date'],
                'amil_tanggal_selesai_tugas' => ['nullable', 'date', 'after_or_equal:amil_tanggal_mulai_tugas'],
                'amil_status'                => ['required', Rule::in(['aktif', 'nonaktif', 'cuti'])],
                'amil_wilayah_tugas'         => ['nullable', 'string', 'max:255'],
                'amil_keterangan'            => ['nullable', 'string'],
                'amil_foto'                  => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            ]);
        }

        $request->validate($rules, [
            'masjid_id.required'                => 'Masjid wajib dipilih untuk peran Amil.',
            'admin_nama.required'               => 'Nama admin wajib diisi.',
            'admin_telepon.required'            => 'Telepon admin wajib diisi.',
            'admin_email.required'              => 'Email admin wajib diisi.',
            'nama_masjid.required'              => 'Nama masjid wajib diisi.',
            'alamat.required'                   => 'Alamat masjid wajib diisi.',
            'provinsi_kode.required'            => 'Provinsi wajib dipilih.',
            'kota_kode.required'                => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan_kode.required'           => 'Kecamatan wajib dipilih.',
            'kelurahan_kode.required'           => 'Kelurahan wajib dipilih.',
            'telepon_masjid.required'           => 'Telepon masjid wajib diisi.',
            'email_masjid.required'             => 'Email masjid wajib diisi.',
            'amil_nama_lengkap.required'        => 'Nama lengkap amil wajib diisi.',
            'amil_jenis_kelamin.required'       => 'Jenis kelamin amil wajib dipilih.',
            'amil_tempat_lahir.required'        => 'Tempat lahir amil wajib diisi.',
            'amil_tanggal_lahir.required'       => 'Tanggal lahir amil wajib diisi.',
            'amil_alamat.required'              => 'Alamat amil wajib diisi.',
            'amil_telepon.required'             => 'Telepon amil wajib diisi.',
            'amil_tanggal_mulai_tugas.required' => 'Tanggal mulai tugas wajib diisi.',
            'amil_status.required'              => 'Status amil wajib dipilih.',
        ]);

        $plainPassword = $request->password;

        DB::beginTransaction();

        try {
            $masjid   = null;
            $namaUser = $request->username ?? $request->email;

            // ══════════════════════════════════════════════════════
            // ADMIN MASJID: Buat Masjid Baru
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'admin_masjid') {
                $namaUser = $request->admin_nama;

                $adminFotoPath = null;
                if ($request->hasFile('admin_foto')) {
                    $adminFotoPath = $request->file('admin_foto')->store('admin-fotos', 'public');
                }

                $fotoMasjidArray = [];
                if ($request->hasFile('foto_masjid')) {
                    $files = $request->file('foto_masjid');
                    if (count($files) > Masjid::MAX_FOTO) {
                        DB::rollBack();
                        return back()->withInput()
                            ->with('error', 'Maksimal ' . Masjid::MAX_FOTO . ' foto masjid yang diperbolehkan.');
                    }
                    foreach ($files as $foto) {
                        $fotoMasjidArray[] = $foto->store('masjid-fotos', 'public');
                    }
                }

                $provinsi  = Province::where('code', $request->provinsi_kode)->first();
                $kota      = City::where('code', $request->kota_kode)->first();
                $kecamatan = District::where('code', $request->kecamatan_kode)->first();
                $kelurahan = Village::where('code', $request->kelurahan_kode)->first();

                if (!$provinsi || !$kota || !$kecamatan || !$kelurahan) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Data wilayah tidak valid. Silakan pilih ulang.');
                }

                $kodePos = $request->kode_pos;
                if (!$kodePos && $kelurahan->meta && is_array($kelurahan->meta)) {
                    $kodePos = $kelurahan->meta['postal_code'] ?? null;
                }

                if (Masjid::where('nama', $request->nama_masjid)->where('kelurahan_kode', $request->kelurahan_kode)->exists()) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Masjid dengan nama "' . $request->nama_masjid . '" sudah terdaftar di kelurahan ' . $kelurahan->name . '.');
                }

                $masjid = Masjid::create([
                    'uuid'             => (string) Str::uuid(),
                    'kode_masjid'      => $this->generateKodeMasjid(),
                    'admin_nama'       => $request->admin_nama,
                    'admin_telepon'    => $request->admin_telepon,
                    'admin_email'      => $request->admin_email,
                    'admin_foto'       => $adminFotoPath,
                    'nama'             => $request->nama_masjid,
                    'alamat'           => $request->alamat,
                    'telepon'          => $request->telepon_masjid,
                    'email'            => $request->email_masjid,
                    'deskripsi'        => $request->deskripsi_masjid,
                    'provinsi_kode'    => $request->provinsi_kode,
                    'provinsi_nama'    => $provinsi->name,
                    'kota_kode'        => $request->kota_kode,
                    'kota_nama'        => $kota->name,
                    'kecamatan_kode'   => $request->kecamatan_kode,
                    'kecamatan_nama'   => $kecamatan->name,
                    'kelurahan_kode'   => $request->kelurahan_kode,
                    'kelurahan_nama'   => $kelurahan->name,
                    'kode_pos'         => $kodePos,
                    'sejarah'          => $request->sejarah,
                    'tahun_berdiri'    => $request->tahun_berdiri ?: null,
                    'pendiri'          => $request->pendiri,
                    'kapasitas_jamaah' => $request->kapasitas_jamaah ?: null,
                    'foto'             => !empty($fotoMasjidArray) ? $fotoMasjidArray : null,
                    'is_active'        => true,
                ]);
            }

            // ══════════════════════════════════════════════════════
            // Buat Pengguna
            // ══════════════════════════════════════════════════════
            $pengguna = Pengguna::create([
                'peran'             => $request->peran,
                'masjid_id'         => match ($request->peran) {
                    'admin_masjid' => $masjid->id,
                    'amil'         => $request->masjid_id,
                    default        => null,
                },
                'username'          => $request->username ?: null,
                'email'             => $request->email,
                'password'          => Hash::make($plainPassword),
                'is_active'         => $request->boolean('is_active', true),
                'email_verified_at' => now(),
            ]);

            // ══════════════════════════════════════════════════════
            // AMIL: Buat record Amil
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'amil') {
                $masjid   = Masjid::findOrFail($request->masjid_id);
                $namaUser = $request->amil_nama_lengkap;

                $fotoPath = null;
                if ($request->hasFile('amil_foto')) {
                    $fotoPath = $request->file('amil_foto')->store('amil/foto', 'public');
                }

                Amil::create([
                    'pengguna_id'           => $pengguna->id,
                    'masjid_id'             => $request->masjid_id,
                    'kode_amil'             => $this->generateKodeAmil($request->masjid_id),
                    'nama_lengkap'          => $request->amil_nama_lengkap,
                    'jenis_kelamin'         => $request->amil_jenis_kelamin,
                    'tempat_lahir'          => $request->amil_tempat_lahir,
                    'tanggal_lahir'         => $request->amil_tanggal_lahir,
                    'alamat'                => $request->amil_alamat,
                    'telepon'               => $request->amil_telepon,
                    'email'                 => $request->email,
                    'foto'                  => $fotoPath,
                    'tanggal_mulai_tugas'   => $request->amil_tanggal_mulai_tugas,
                    'tanggal_selesai_tugas' => $request->amil_tanggal_selesai_tugas ?: null,
                    'status'                => $request->amil_status,
                    'wilayah_tugas'         => $request->amil_wilayah_tugas ?: null,
                    'keterangan'            => $request->amil_keterangan ?: null,
                ]);
            }

            DB::commit();

            // ── Kirim Email Notifikasi (di luar transaksi DB) ─────────────────────
            $emailSuccess = false;
            try {
                $mailConfig = MailConfig::first();

                \Log::info('Mail config check', [
                    'exists'   => (bool) $mailConfig,
                    'host'     => $mailConfig?->MAIL_HOST,
                    'port'     => $mailConfig?->MAIL_PORT,
                    'username' => $mailConfig?->MAIL_USERNAME,
                    'from'     => $mailConfig?->MAIL_FROM_ADDRESS,
                    'complete' => $mailConfig?->isComplete(),
                ]);

                if (!$mailConfig || !$mailConfig->isComplete()) {
                    throw new \Exception('Konfigurasi mail tidak lengkap atau belum diisi di database.');
                }

                // Override config runtime
                config($mailConfig->toConfigArray());

                // WAJIB: purge agar Laravel tidak pakai instance mailer lama yang ter-cache
                Mail::purge('smtp');

                Mail::mailer('smtp')
                    ->to($pengguna->email)
                    ->send(new PenggunaRegistrationMail(
                        namaLengkap: $namaUser,
                        email: $pengguna->email,
                        username: $pengguna->username ?? $pengguna->email,
                        password: $plainPassword,
                        peran: $pengguna->peran,
                        namaMasjid: $masjid?->nama,
                    ));

                $emailSuccess = true;
            } catch (\Exception $mailEx) {
                \Log::error('Gagal kirim email notifikasi pengguna', [
                    'pengguna_id' => $pengguna->id,
                    'email'       => $pengguna->email,
                    'error'       => $mailEx->getMessage(),
                    'file'        => $mailEx->getFile(),
                    'line'        => $mailEx->getLine(),
                ]);
            }

            $suffix = $emailSuccess
                ? ' dan notifikasi telah dikirim ke email.'
                : ' (notifikasi email gagal dikirim, cek log untuk detail).';

            return redirect()
                ->route('pengguna.index')
                ->with('success', 'Pengguna berhasil ditambahkan' . $suffix);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error store pengguna', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return back()->withInput()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(string $uuid): View
    {
        $pengguna = Pengguna::with('masjid')->where('uuid', $uuid)->firstOrFail();

        return view('superadmin.pengguna.show', compact('pengguna'));
    }

    public function edit(string $uuid): View
    {
        $pengguna   = Pengguna::where('uuid', $uuid)->firstOrFail();
        $masjidList = Masjid::orderBy('nama')->get(['id', 'uuid', 'nama', 'kode_masjid']);
        $provinces  = Province::orderBy('name')->get(['code', 'name']);  // ← tambah ini

        return view('superadmin.pengguna.edit', compact('pengguna', 'masjidList', 'provinces'));  // ← tambah 'provinces'
    }
    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'peran'     => ['required', Rule::in(['superadmin', 'admin_masjid', 'amil'])],
            'masjid_id' => [
                'nullable',
                Rule::requiredIf(fn() => in_array($request->peran, ['admin_masjid', 'amil'])),
                'exists:masjid,id',
            ],
            'username'  => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pengguna', 'username')->ignore($pengguna->id),
            ],
            'email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('pengguna', 'email')->ignore($pengguna->id),
            ],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ], [
            'masjid_id.required' => 'Masjid wajib dipilih untuk peran Admin Masjid atau Amil.',
        ]);

        if ($validated['peran'] === 'superadmin') {
            $validated['masjid_id'] = null;
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $pengguna->update($validated);

        return redirect()
            ->route('pengguna.show', $pengguna->uuid)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // ── Toggle Status ─────────────────────────────────────────────────────────

    public function toggleStatus(string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $pengguna->update(['is_active' => ! $pengguna->is_active]);
        $status = $pengguna->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengguna berhasil {$status}.");
    }

    // ── Reset Password ────────────────────────────────────────────────────────

    public function resetPassword(Request $request, string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $pengguna->update([
            'password'                        => Hash::make($request->password),
            'password_reset_token'            => null,
            'password_reset_token_expires_at' => null,
        ]);

        return back()->with('success', 'Password pengguna berhasil direset.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function generateKodeMasjid(): string
    {
        $prefix = 'MSJ';
        $year   = date('Y');

        return DB::transaction(function () use ($prefix, $year) {
            $lastMasjid = Masjid::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $number     = $lastMasjid ? (int) substr($lastMasjid->kode_masjid, -4) + 1 : 1;
            $kodeMasjid = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $attempts = 0;
            while (Masjid::where('kode_masjid', $kodeMasjid)->exists() && $attempts < 10) {
                $number++;
                $kodeMasjid = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            return $kodeMasjid;
        });
    }

    private function generateKodeAmil(int $masjidId): string
    {
        $masjid = Masjid::findOrFail($masjidId);
        $prefix = 'AMIL-' . $masjid->kode_masjid . '-';

        $lastAmil = Amil::where('kode_amil', 'like', $prefix . '%')
            ->orderBy('kode_amil', 'desc')
            ->first();

        $newNumber = $lastAmil
            ? str_pad((int) substr($lastAmil->kode_amil, strlen($prefix)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        return $prefix . $newNumber;
    }
}