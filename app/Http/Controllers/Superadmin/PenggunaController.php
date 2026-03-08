<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Amil;
use App\Models\Lembaga;
use App\Models\MailConfig;
use App\Models\Muzakki;
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
use Illuminate\Support\Facades\Log;

class PenggunaController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Pengguna::with('lembaga')->latest();

        if ($search = $request->get('q')) {
            $query->search($search);
        }
        if ($peran = $request->get('peran')) {
            $query->byPeran($peran);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'aktif');
        }
        if ($dId = $request->get('d_id')) {
            $query->where('d_id', $dId);
        }

        $pengguna   = $query->paginate(10);
        $lembagaList = Lembaga::orderBy('nama')->get(['id', 'nama']);

        $breadcrumbs = [
            'Data Pengguna' => null,
        ];

        return view('superadmin.pengguna.index', compact('pengguna', 'lembagaList', 'breadcrumbs'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        $lembagaList = Lembaga::orderBy('nama')->get(['id', 'uuid', 'nama', 'kode_lembaga']);
        $provinces  = Province::orderBy('name')->get(['code', 'name']);

        return view('superadmin.pengguna.create', compact('lembagaList', 'provinces'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        // ── Validasi Dasar ────────────────────────────────────────────────────
        $rules = [
            'peran'     => ['required', Rule::in(['superadmin', 'admin_d', 'amil', 'muzakki'])],
            'username'  => ['nullable', 'string', 'max:255', 'unique:pengguna,username'],
            'email'     => ['required', 'email', 'max:255', 'unique:pengguna,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ];

        // ── Validasi Tambahan: Admin Lembaga → Buat Lembaga Baru ────────────────
        if ($request->peran === 'admin_d') {
            $rules = array_merge($rules, [
                'admin_nama'       => ['required', 'string', 'max:255'],
                'admin_telepon'    => ['required', 'string', 'max:20'],
                'admin_email'      => ['required', 'email', 'max:255'],
                'admin_foto'       => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'nama_d'      => ['required', 'string', 'max:255'],
                'alamat'           => ['required', 'string'],
                'provinsi_kode'    => ['required', 'string', 'exists:indonesia_provinces,code'],
                'kota_kode'        => ['required', 'string', 'exists:indonesia_cities,code'],
                'kecamatan_kode'   => ['required', 'string', 'exists:indonesia_districts,code'],
                'kelurahan_kode'   => ['required', 'string', 'exists:indonesia_villages,code'],
                'kode_pos'         => ['nullable', 'string', 'max:5'],
                'telepon_d'   => ['required', 'string', 'max:20'],
                'email_d'     => ['required', 'email', 'max:255'],
                'deskripsi_d' => ['nullable', 'string'],
                'sejarah'          => ['nullable', 'string'],
                'tahun_berdiri'    => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
                'pendiri'          => ['nullable', 'string', 'max:255'],
                'kapasitas_jamaah' => ['nullable', 'integer', 'min:1'],
                'foto_d'      => ['nullable', 'array'],
                'foto_d.*'    => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            ]);
        }

        // ── Validasi Tambahan: Amil → Pilih Lembaga yang Ada ───────────────────
        if ($request->peran === 'amil') {
            $rules = array_merge($rules, [
                'd_id'                  => ['required', 'exists:d,id'],
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

        // ── Validasi Tambahan: Muzakki ─────────────────────────────────────────
        if ($request->peran === 'muzakki') {
            $rules = array_merge($rules, [
                'muzakki_nama'      => ['required', 'string', 'max:255'],
                'muzakki_nik'       => ['nullable', 'string', 'size:16', 'unique:muzakki,nik'],
                'muzakki_telepon'   => ['nullable', 'string', 'max:20'],
                'muzakki_alamat'    => ['nullable', 'string'],
                'muzakki_d_id' => ['nullable', 'exists:d,id'],
                'muzakki_foto'      => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            ]);
        }

        $request->validate($rules, [
            'd_id.required'                => 'Lembaga wajib dipilih untuk peran Amil.',
            'admin_nama.required'               => 'Nama admin wajib diisi.',
            'admin_telepon.required'            => 'Telepon admin wajib diisi.',
            'admin_email.required'              => 'Email admin wajib diisi.',
            'nama_d.required'              => 'Nama d wajib diisi.',
            'alamat.required'                   => 'Alamat d wajib diisi.',
            'provinsi_kode.required'            => 'Provinsi wajib dipilih.',
            'kota_kode.required'                => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan_kode.required'           => 'Kecamatan wajib dipilih.',
            'kelurahan_kode.required'           => 'Kelurahan wajib dipilih.',
            'telepon_d.required'           => 'Telepon d wajib diisi.',
            'email_d.required'             => 'Email d wajib diisi.',
            'amil_nama_lengkap.required'        => 'Nama lengkap amil wajib diisi.',
            'amil_jenis_kelamin.required'       => 'Jenis kelamin amil wajib dipilih.',
            'amil_tempat_lahir.required'        => 'Tempat lahir amil wajib diisi.',
            'amil_tanggal_lahir.required'       => 'Tanggal lahir amil wajib diisi.',
            'amil_alamat.required'              => 'Alamat amil wajib diisi.',
            'amil_telepon.required'             => 'Telepon amil wajib diisi.',
            'amil_tanggal_mulai_tugas.required' => 'Tanggal mulai tugas wajib diisi.',
            'amil_status.required'              => 'Status amil wajib dipilih.',
            'muzakki_nama.required'             => 'Nama lengkap muzakki wajib diisi.',
            'muzakki_nik.size'                  => 'NIK harus 16 digit.',
            'muzakki_nik.unique'                => 'NIK sudah terdaftar.',
        ]);

        $plainPassword = $request->password;

        DB::beginTransaction();

        try {
            $d   = null;
            $namaUser = $request->username ?? $request->email;

            // ══════════════════════════════════════════════════════
            // ADMIN MASJID: Buat Lembaga Baru
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'admin_d') {
                $namaUser = $request->admin_nama;

                $adminFotoPath = null;
                if ($request->hasFile('admin_foto')) {
                    $adminFotoPath = $request->file('admin_foto')->store('admin-fotos', 'public');
                }

                $fotoLembagaArray = [];
                if ($request->hasFile('foto_d')) {
                    $files = $request->file('foto_d');
                    if (count($files) > Lembaga::MAX_FOTO) {
                        DB::rollBack();
                        return back()->withInput()
                            ->with('error', 'Maksimal ' . Lembaga::MAX_FOTO . ' foto d yang diperbolehkan.');
                    }
                    foreach ($files as $foto) {
                        $fotoLembagaArray[] = $foto->store('d-fotos', 'public');
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

                if (Lembaga::where('nama', $request->nama_d)->where('kelurahan_kode', $request->kelurahan_kode)->exists()) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Lembaga dengan nama "' . $request->nama_d . '" sudah terdaftar di kelurahan ' . $kelurahan->name . '.');
                }

                $d = Lembaga::create([
                    'uuid'             => (string) Str::uuid(),
                    'kode_lembaga'      => $this->generateKodeLembaga(),
                    'admin_nama'       => $request->admin_nama,
                    'admin_telepon'    => $request->admin_telepon,
                    'admin_email'      => $request->admin_email,
                    'admin_foto'       => $adminFotoPath,
                    'nama'             => $request->nama_d,
                    'alamat'           => $request->alamat,
                    'telepon'          => $request->telepon_d,
                    'email'            => $request->email_d,
                    'deskripsi'        => $request->deskripsi_d,
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
                    'foto'             => !empty($fotoLembagaArray) ? $fotoLembagaArray : null,
                    'is_active'        => true,
                ]);
            }

            // ══════════════════════════════════════════════════════
            // Buat Pengguna
            // ══════════════════════════════════════════════════════
            $pengguna = Pengguna::create([
                'peran'             => $request->peran,
                'd_id'         => match ($request->peran) {
                    'admin_d' => $d->id,
                    'amil'         => $request->d_id,
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
                $d   = Lembaga::findOrFail($request->d_id);
                $namaUser = $request->amil_nama_lengkap;

                $fotoPath = null;
                if ($request->hasFile('amil_foto')) {
                    $fotoPath = $request->file('amil_foto')->store('amil/foto', 'public');
                }

                Amil::create([
                    'pengguna_id'           => $pengguna->id,
                    'd_id'             => $request->d_id,
                    'kode_amil'             => $this->generateKodeAmil($request->d_id),
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

            // ══════════════════════════════════════════════════════
            // MUZAKKI: Buat record Muzakki
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'muzakki') {
                $namaUser = $request->muzakki_nama;

                $fotoPath = null;
                if ($request->hasFile('muzakki_foto')) {
                    $fotoPath = $request->file('muzakki_foto')->store('muzakki/foto', 'public');
                }

                Muzakki::create([
                    'pengguna_id' => $pengguna->id,
                    'd_id'   => $request->muzakki_d_id ?: null,
                    'nama'        => $request->muzakki_nama,
                    'nik'         => $request->muzakki_nik ?: null,
                    'telepon'     => $request->muzakki_telepon ?: null,
                    'email'       => $request->email,
                    'alamat'      => $request->muzakki_alamat ?: null,
                    'foto'        => $fotoPath,
                    'is_active'   => $request->boolean('is_active', true),
                ]);
            }

            DB::commit();

            // ── Kirim Email Notifikasi (di luar transaksi DB) ─────────────────────
            $emailSuccess = false;
            try {
                $mailConfig = MailConfig::first();

                if (!$mailConfig || !$mailConfig->isComplete()) {
                    throw new \Exception('Konfigurasi mail tidak lengkap atau belum diisi di database.');
                }

                config($mailConfig->toConfigArray());
                Mail::purge('smtp');

                Mail::mailer('smtp')
                    ->to($pengguna->email)
                    ->send(new PenggunaRegistrationMail(
                        namaLengkap: $namaUser,
                        email: $pengguna->email,
                        username: $pengguna->username ?? $pengguna->email,
                        password: $plainPassword,
                        peran: $pengguna->peran,
                        namaLembaga: $d?->nama,
                    ));

                $emailSuccess = true;
            } catch (\Exception $mailEx) {
                Log::error('Gagal kirim email notifikasi pengguna', [
                    'pengguna_id' => $pengguna->id,
                    'email'       => $pengguna->email,
                    'error'       => $mailEx->getMessage(),
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
            Log::error('Error store pengguna', [
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
        $pengguna = Pengguna::with(['lembaga', 'amil', 'muzakki'])->where('uuid', $uuid)->firstOrFail();

        return view('superadmin.pengguna.show', compact('pengguna'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(string $uuid): View
    {
        $pengguna   = Pengguna::with(['lembaga', 'amil', 'muzakki'])->where('uuid', $uuid)->firstOrFail();
        $lembagaList = Lembaga::orderBy('nama')->get(['id', 'uuid', 'nama', 'kode_lembaga']);
        $provinces  = Province::orderBy('name')->get(['code', 'name']);

        return view('superadmin.pengguna.edit', compact('pengguna', 'lembagaList', 'provinces'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::with(['lembaga', 'amil', 'muzakki'])->where('uuid', $uuid)->firstOrFail();

        // ── Validasi Dasar ────────────────────────────────────────────────────
        $rules = [
            'peran'     => ['required', Rule::in(['superadmin', 'admin_d', 'amil', 'muzakki'])],
            'username'  => [
                'nullable', 'string', 'max:255',
                Rule::unique('pengguna', 'username')->ignore($pengguna->id),
            ],
            'email'     => [
                'required', 'email', 'max:255',
                Rule::unique('pengguna', 'email')->ignore($pengguna->id),
            ],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ];

        // ── Validasi Tambahan: Admin Lembaga ───────────────────────────────────
        if ($request->peran === 'admin_d') {
            $rules = array_merge($rules, [
                'd_id'        => ['required', 'exists:d,id'],
                'admin_nama'       => ['required', 'string', 'max:255'],
                'admin_telepon'    => ['required', 'string', 'max:20'],
                'admin_email'      => ['required', 'email', 'max:255'],
                'admin_foto'       => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'nama_d'      => ['required', 'string', 'max:255'],
                'alamat'           => ['required', 'string'],
                'provinsi_kode'    => ['required', 'string', 'exists:indonesia_provinces,code'],
                'kota_kode'        => ['required', 'string', 'exists:indonesia_cities,code'],
                'kecamatan_kode'   => ['required', 'string', 'exists:indonesia_districts,code'],
                'kelurahan_kode'   => ['required', 'string', 'exists:indonesia_villages,code'],
                'kode_pos'         => ['nullable', 'string', 'max:5'],
                'telepon_d'   => ['required', 'string', 'max:20'],
                'email_d'     => ['required', 'email', 'max:255'],
                'deskripsi_d' => ['nullable', 'string'],
                'sejarah'          => ['nullable', 'string'],
                'tahun_berdiri'    => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
                'pendiri'          => ['nullable', 'string', 'max:255'],
                'kapasitas_jamaah' => ['nullable', 'integer', 'min:1'],
                'foto_d'      => ['nullable', 'array'],
                'foto_d.*'    => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'hapus_foto_d' => ['nullable', 'array'],
                'hapus_admin_foto' => ['nullable', 'boolean'],
            ]);
        }

        // ── Validasi Tambahan: Amil ───────────────────────────────────────────
        if ($request->peran === 'amil') {
            $rules = array_merge($rules, [
                'd_id'                  => ['required', 'exists:d,id'],
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
                'hapus_amil_foto'            => ['nullable', 'boolean'],
            ]);
        }

        // ── Validasi Tambahan: Muzakki ─────────────────────────────────────────
        if ($request->peran === 'muzakki') {
            $existingMuzakkiId = $pengguna->muzakki?->id;
            $rules = array_merge($rules, [
                'muzakki_nama'      => ['required', 'string', 'max:255'],
                'muzakki_nik'       => [
                    'nullable', 'string', 'size:16',
                    Rule::unique('muzakki', 'nik')->ignore($existingMuzakkiId),
                ],
                'muzakki_telepon'   => ['nullable', 'string', 'max:20'],
                'muzakki_alamat'    => ['nullable', 'string'],
                'muzakki_d_id' => ['nullable', 'exists:d,id'],
                'muzakki_foto'      => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'hapus_muzakki_foto' => ['nullable', 'boolean'],
            ]);
        }

        $request->validate($rules, [
            'd_id.required'                => 'Lembaga wajib dipilih.',
            'admin_nama.required'               => 'Nama admin wajib diisi.',
            'admin_telepon.required'            => 'Telepon admin wajib diisi.',
            'admin_email.required'              => 'Email admin wajib diisi.',
            'nama_d.required'              => 'Nama d wajib diisi.',
            'alamat.required'                   => 'Alamat d wajib diisi.',
            'provinsi_kode.required'            => 'Provinsi wajib dipilih.',
            'kota_kode.required'                => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan_kode.required'           => 'Kecamatan wajib dipilih.',
            'kelurahan_kode.required'           => 'Kelurahan wajib dipilih.',
            'telepon_d.required'           => 'Telepon d wajib diisi.',
            'email_d.required'             => 'Email d wajib diisi.',
            'amil_nama_lengkap.required'        => 'Nama lengkap amil wajib diisi.',
            'amil_jenis_kelamin.required'       => 'Jenis kelamin amil wajib dipilih.',
            'amil_tempat_lahir.required'        => 'Tempat lahir amil wajib diisi.',
            'amil_tanggal_lahir.required'       => 'Tanggal lahir amil wajib diisi.',
            'amil_alamat.required'              => 'Alamat amil wajib diisi.',
            'amil_telepon.required'             => 'Telepon amil wajib diisi.',
            'amil_tanggal_mulai_tugas.required' => 'Tanggal mulai tugas wajib diisi.',
            'amil_status.required'              => 'Status amil wajib dipilih.',
            'muzakki_nama.required'             => 'Nama lengkap muzakki wajib diisi.',
            'muzakki_nik.size'                  => 'NIK harus 16 digit.',
            'muzakki_nik.unique'                => 'NIK sudah terdaftar.',
        ]);

        DB::beginTransaction();

        try {
            // ── Update data pengguna (akun) ───────────────────────────────────
            $penggunaData = [
                'peran'     => $request->peran,
                'username'  => $request->username ?: null,
                'email'     => $request->email,
                'is_active' => $request->boolean('is_active', true),
                'd_id' => match ($request->peran) {
                    'admin_d' => $request->d_id,
                    'amil'         => $request->d_id,
                    default        => null,
                },
            ];

            if (!empty($request->password)) {
                $penggunaData['password'] = Hash::make($request->password);
            }

            $pengguna->update($penggunaData);

            // ══════════════════════════════════════════════════════
            // ADMIN MASJID: Update data Lembaga
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'admin_d') {
                $d = Lembaga::findOrFail($request->d_id);

                // Handle hapus foto admin
                $adminFotoPath = $d->admin_foto;
                if ($request->boolean('hapus_admin_foto') && $adminFotoPath) {
                    Storage::disk('public')->delete($adminFotoPath);
                    $adminFotoPath = null;
                }
                if ($request->hasFile('admin_foto')) {
                    if ($adminFotoPath) {
                        Storage::disk('public')->delete($adminFotoPath);
                    }
                    $adminFotoPath = $request->file('admin_foto')->store('admin-fotos', 'public');
                }

                // Handle foto d
                $currentFotos = (array) ($d->foto ?? []);

                // Hapus foto yang dicentang untuk dihapus
                if ($request->filled('hapus_foto_d')) {
                    $hapusIndeks = $request->input('hapus_foto_d', []);
                    foreach ($hapusIndeks as $idx) {
                        if (isset($currentFotos[$idx])) {
                            Storage::disk('public')->delete($currentFotos[$idx]);
                            unset($currentFotos[$idx]);
                        }
                    }
                    $currentFotos = array_values($currentFotos);
                }

                // Upload foto baru
                if ($request->hasFile('foto_d')) {
                    $newFotos = $request->file('foto_d');
                    $totalFotos = count($currentFotos) + count($newFotos);
                    if ($totalFotos > Lembaga::MAX_FOTO) {
                        DB::rollBack();
                        return back()->withInput()
                            ->with('error', 'Maksimal ' . Lembaga::MAX_FOTO . ' foto d yang diperbolehkan.');
                    }
                    foreach ($newFotos as $foto) {
                        $currentFotos[] = $foto->store('d-fotos', 'public');
                    }
                }

                // Resolve nama wilayah
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

                $d->update([
                    'admin_nama'       => $request->admin_nama,
                    'admin_telepon'    => $request->admin_telepon,
                    'admin_email'      => $request->admin_email,
                    'admin_foto'       => $adminFotoPath,
                    'nama'             => $request->nama_d,
                    'alamat'           => $request->alamat,
                    'telepon'          => $request->telepon_d,
                    'email'            => $request->email_d,
                    'deskripsi'        => $request->deskripsi_d,
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
                    'foto'             => !empty($currentFotos) ? $currentFotos : null,
                ]);
            }

            // ══════════════════════════════════════════════════════
            // AMIL: Update atau Buat record Amil
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'amil') {
                // Handle foto amil
                $amil = $pengguna->amil;
                $fotoPath = $amil?->foto;

                if ($request->boolean('hapus_amil_foto') && $fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                    $fotoPath = null;
                }
                if ($request->hasFile('amil_foto')) {
                    if ($fotoPath) {
                        Storage::disk('public')->delete($fotoPath);
                    }
                    $fotoPath = $request->file('amil_foto')->store('amil/foto', 'public');
                }

                $amilData = [
                    'pengguna_id'           => $pengguna->id,
                    'd_id'             => $request->d_id,
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
                ];

                if ($amil) {
                    $amil->update($amilData);
                } else {
                    $amilData['kode_amil'] = $this->generateKodeAmil($request->d_id);
                    Amil::create($amilData);
                }
            }

            // ══════════════════════════════════════════════════════
            // MUZAKKI: Update atau Buat record Muzakki
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'muzakki') {
                $muzakki  = $pengguna->muzakki;
                $fotoPath = $muzakki?->foto;

                if ($request->boolean('hapus_muzakki_foto') && $fotoPath) {
                    Storage::disk('public')->delete($fotoPath);
                    $fotoPath = null;
                }
                if ($request->hasFile('muzakki_foto')) {
                    if ($fotoPath) {
                        Storage::disk('public')->delete($fotoPath);
                    }
                    $fotoPath = $request->file('muzakki_foto')->store('muzakki/foto', 'public');
                }

                $muzakkiData = [
                    'pengguna_id' => $pengguna->id,
                    'd_id'   => $request->muzakki_d_id ?: null,
                    'nama'        => $request->muzakki_nama,
                    'nik'         => $request->muzakki_nik ?: null,
                    'telepon'     => $request->muzakki_telepon ?: null,
                    'email'       => $request->email,
                    'alamat'      => $request->muzakki_alamat ?: null,
                    'foto'        => $fotoPath,
                    'is_active'   => $request->boolean('is_active', true),
                ];

                if ($muzakki) {
                    $muzakki->update($muzakkiData);
                } else {
                    Muzakki::create($muzakkiData);
                }
            }

            DB::commit();

            return redirect()
                ->route('pengguna.show', $pengguna->uuid)
                ->with('success', 'Data pengguna berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update pengguna', [
                'uuid'  => $uuid,
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return back()->withInput()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
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

    private function generateKodeLembaga(): string
    {
        $prefix = 'MSJ';
        $year   = date('Y');

        return DB::transaction(function () use ($prefix, $year) {
            $lastLembaga = Lembaga::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $number     = $lastLembaga ? (int) substr($lastLembaga->kode_lembaga, -4) + 1 : 1;
            $kodeLembaga = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $attempts = 0;
            while (Lembaga::where('kode_lembaga', $kodeLembaga)->exists() && $attempts < 10) {
                $number++;
                $kodeLembaga = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            return $kodeLembaga;
        });
    }

    private function generateKodeAmil(int $dId): string
    {
        $d = Lembaga::findOrFail($dId);
        $prefix = 'AMIL-' . $d->kode_lembaga . '-';

        $lastAmil = Amil::where('kode_amil', 'like', $prefix . '%')
            ->orderBy('kode_amil', 'desc')
            ->first();

        $newNumber = $lastAmil
            ? str_pad((int) substr($lastAmil->kode_amil, strlen($prefix)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        return $prefix . $newNumber;
    }
}