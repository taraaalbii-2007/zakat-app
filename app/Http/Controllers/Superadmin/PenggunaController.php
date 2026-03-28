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
use App\Mail\PenggunaUpdatedMail;

class PenggunaController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Pengguna::with('lembaga')
            ->orderByRaw("FIELD(peran, 'superadmin') DESC")
            ->latest();

        if ($search = $request->get('q')) {
            $query->search($search);
        }
        if ($peran = $request->get('peran')) {
            $query->byPeran($peran);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'aktif');
        }
        if ($lembagaId = $request->get('lembaga_id')) {
            $query->where('lembaga_id', $lembagaId);
        }

        $pengguna   = $query->paginate(10);
        $lembagaList = Lembaga::orderBy('nama')->get(['id', 'nama']);

        $breadcrumbs = [
            'Data Pengguna' => route('pengguna.index'),
        ];

        return view('superadmin.pengguna.index', compact('pengguna', 'lembagaList', 'breadcrumbs'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        $lembagaList = Lembaga::orderBy('nama')->get(['id', 'uuid', 'nama', 'kode_lembaga']);
        $provinces  = Province::orderBy('name')->get(['code', 'name']);
        $breadcrumbs = [
            'Pengguna' => route('pengguna.index'),
            'Tambah Pengguna' => route('pengguna.create'),
        ];

        return view('superadmin.pengguna.create', compact('lembagaList', 'provinces', 'breadcrumbs'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        // ── Validasi Dasar ────────────────────────────────────────────────────
        $rules = [
            'peran'     => ['required', Rule::in(['superadmin', 'admin_lembaga', 'amil', 'muzakki'])],
            'username'  => ['nullable', 'string', 'max:255', 'unique:pengguna,username'],
            'email'     => ['required', 'email', 'max:255', 'unique:pengguna,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ];

        // ── Validasi Tambahan: Admin Lembaga → Buat Lembaga Baru ────────────────
        if ($request->peran === 'admin_lembaga') {
            $rules = array_merge($rules, [
                'admin_nama'       => ['required', 'string', 'max:255'],
                'admin_telepon'    => ['required', 'string', 'max:20'],
                'admin_email'      => ['required', 'email', 'max:255'],
                'admin_foto'       => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'nama_lembaga'     => ['required', 'string', 'max:255'],
                'alamat'           => ['required', 'string'],
                'provinsi_kode'    => ['required', 'string', 'exists:indonesia_provinces,code'],
                'kota_kode'        => ['required', 'string', 'exists:indonesia_cities,code'],
                'kecamatan_kode'   => ['required', 'string', 'exists:indonesia_districts,code'],
                'kelurahan_kode'   => ['required', 'string', 'exists:indonesia_villages,code'],
                'kode_pos'         => ['nullable', 'string', 'max:5'],
                'telepon_lembaga'  => ['required', 'string', 'max:20'],
                'email_lembaga'    => ['required', 'email', 'max:255'],
                'deskripsi_lembaga' => ['nullable', 'string'],
                'sejarah'          => ['nullable', 'string'],
                'tahun_berdiri'    => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
                'pendiri'          => ['nullable', 'string', 'max:255'],
                'kapasitas_jamaah' => ['nullable', 'integer', 'min:1'],
                'foto_lembaga'     => ['nullable', 'array'],
                'foto_lembaga.*'   => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            ]);
        }

        // ── Validasi Tambahan: Amil → Pilih Lembaga yang Ada ───────────────────
        if ($request->peran === 'amil') {
            $rules = array_merge($rules, [
                'lembaga_id'                  => ['required', 'exists:lembaga,id'],
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
                'muzakki_lembaga_id' => ['nullable', 'exists:lembaga,id'],
                'muzakki_foto'      => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            ]);
        }

        $request->validate($rules, [
            'lembaga_id.required'                => 'Lembaga wajib dipilih untuk peran Amil.',
            'admin_nama.required'               => 'Nama admin wajib diisi.',
            'admin_telepon.required'            => 'Telepon admin wajib diisi.',
            'admin_email.required'              => 'Email admin wajib diisi.',
            'nama_lembaga.required'             => 'Nama lembaga wajib diisi.',
            'alamat.required'                   => 'Alamat lembaga wajib diisi.',
            'provinsi_kode.required'            => 'Provinsi wajib dipilih.',
            'kota_kode.required'                => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan_kode.required'           => 'Kecamatan wajib dipilih.',
            'kelurahan_kode.required'           => 'Kelurahan wajib dipilih.',
            'telepon_lembaga.required'          => 'Telepon lembaga wajib diisi.',
            'email_lembaga.required'            => 'Email lembaga wajib diisi.',
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
            $lembaga = null;
            $namaUser = $request->username ?? $request->email;

            // ══════════════════════════════════════════════════════
            // ADMIN LEMBAGA: Buat Lembaga Baru
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'admin_lembaga') {
                $namaUser = $request->admin_nama;

                $adminFotoPath = null;
                if ($request->hasFile('admin_foto')) {
                    $adminFotoPath = $request->file('admin_foto')->store('admin-fotos', 'public');
                }

                $fotoLembagaArray = [];
                if ($request->hasFile('foto_lembaga')) {
                    $files = $request->file('foto_lembaga');
                    if (count($files) > Lembaga::MAX_FOTO) {
                        DB::rollBack();
                        return back()->withInput()
                            ->with('error', 'Maksimal ' . Lembaga::MAX_FOTO . ' foto lembaga yang diperbolehkan.');
                    }
                    foreach ($files as $foto) {
                        $fotoLembagaArray[] = $foto->store('lembaga-fotos', 'public');
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

                if (Lembaga::where('nama', $request->nama_lembaga)->where('kelurahan_kode', $request->kelurahan_kode)->exists()) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Lembaga dengan nama "' . $request->nama_lembaga . '" sudah terdaftar di kelurahan ' . $kelurahan->name . '.');
                }

                $lembaga = Lembaga::create([
                    'uuid'             => (string) Str::uuid(),
                    'kode_lembaga'     => $this->generateKodeLembaga(),
                    'admin_nama'       => $request->admin_nama,
                    'admin_telepon'    => $request->admin_telepon,
                    'admin_email'      => $request->admin_email,
                    'admin_foto'       => $adminFotoPath,
                    'nama'             => $request->nama_lembaga,
                    'alamat'           => $request->alamat,
                    'telepon'          => $request->telepon_lembaga,
                    'email'            => $request->email_lembaga,
                    'deskripsi'        => $request->deskripsi_lembaga,
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
                'lembaga_id'        => match ($request->peran) {
                    'admin_lembaga' => $lembaga?->id,
                    'amil'          => $request->lembaga_id,
                    default         => null,
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
                $lembaga = Lembaga::findOrFail($request->lembaga_id);
                $namaUser = $request->amil_nama_lengkap;

                $fotoPath = null;
                if ($request->hasFile('amil_foto')) {
                    $fotoPath = $request->file('amil_foto')->store('amil/foto', 'public');
                }

                Amil::create([
                    'pengguna_id'           => $pengguna->id,
                    'lembaga_id'            => $request->lembaga_id,
                    'kode_amil'             => $this->generateKodeAmil($request->lembaga_id),
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
                    'lembaga_id'  => $request->muzakki_lembaga_id ?: null,
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
                        namaLembaga: $lembaga?->nama,
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
        $breadcrumbs = [
            'Pengguna' => route('pengguna.index'),
            'Detail Pengguna' => route('pengguna.show', $uuid),
        ];

        return view('superadmin.pengguna.show', compact('pengguna', 'breadcrumbs'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(string $uuid): View
    {
        $pengguna   = Pengguna::with(['lembaga', 'amil', 'muzakki'])->where('uuid', $uuid)->firstOrFail();
        $lembagaList = Lembaga::orderBy('nama')->get(['id', 'uuid', 'nama', 'kode_lembaga']);
        $provinces  = Province::orderBy('name')->get(['code', 'name']);

        $breadcrumbs = [
            'Pengguna' => route('pengguna.index'),
            'Edit Pengguna' => route('pengguna.edit', $uuid),
        ];

        return view('superadmin.pengguna.edit', compact('pengguna', 'lembagaList', 'provinces', 'breadcrumbs'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::with(['lembaga', 'amil', 'muzakki'])->where('uuid', $uuid)->firstOrFail();

        // ── Validasi Dasar ────────────────────────────────────────────────────
        $rules = [
            'peran'     => ['required', Rule::in(['superadmin', 'admin_lembaga', 'amil', 'muzakki'])],
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
        ];

        // ── Validasi Tambahan: Admin Lembaga ───────────────────────────────────
        if ($request->peran === 'admin_lembaga') {
            $rules = array_merge($rules, [
                'lembaga_id'        => ['required', 'exists:lembaga,id'],
                'admin_nama'        => ['required', 'string', 'max:255'],
                'admin_telepon'     => ['required', 'string', 'max:20'],
                'admin_email'       => ['required', 'email', 'max:255'],
                'admin_foto'        => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'nama_lembaga'      => ['required', 'string', 'max:255'],
                'alamat'            => ['required', 'string'],
                'provinsi_kode'     => ['required', 'string', 'exists:indonesia_provinces,code'],
                'kota_kode'         => ['required', 'string', 'exists:indonesia_cities,code'],
                'kecamatan_kode'    => ['required', 'string', 'exists:indonesia_districts,code'],
                'kelurahan_kode'    => ['required', 'string', 'exists:indonesia_villages,code'],
                'kode_pos'          => ['nullable', 'string', 'max:5'],
                'telepon_lembaga'   => ['required', 'string', 'max:20'],
                'email_lembaga'     => ['required', 'email', 'max:255'],
                'deskripsi_lembaga' => ['nullable', 'string'],
                'sejarah'           => ['nullable', 'string'],
                'tahun_berdiri'     => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
                'pendiri'           => ['nullable', 'string', 'max:255'],
                'kapasitas_jamaah'  => ['nullable', 'integer', 'min:1'],
                'foto_lembaga'      => ['nullable', 'array'],
                'foto_lembaga.*'    => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'hapus_foto_lembaga' => ['nullable', 'array'],
                'hapus_admin_foto'  => ['nullable', 'boolean'],
            ]);
        }

        // ── Validasi Tambahan: Amil ───────────────────────────────────────────
        if ($request->peran === 'amil') {
            $rules = array_merge($rules, [
                'lembaga_id'                  => ['required', 'exists:lembaga,id'],
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
                    'nullable',
                    'string',
                    'size:16',
                    Rule::unique('muzakki', 'nik')->ignore($existingMuzakkiId),
                ],
                'muzakki_telepon'   => ['nullable', 'string', 'max:20'],
                'muzakki_alamat'    => ['nullable', 'string'],
                'muzakki_lembaga_id' => ['nullable', 'exists:lembaga,id'],
                'muzakki_foto'      => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'hapus_muzakki_foto' => ['nullable', 'boolean'],
            ]);
        }

        $request->validate($rules, [
            'lembaga_id.required'                => 'Lembaga wajib dipilih.',
            'admin_nama.required'               => 'Nama admin wajib diisi.',
            'admin_telepon.required'            => 'Telepon admin wajib diisi.',
            'admin_email.required'              => 'Email admin wajib diisi.',
            'nama_lembaga.required'             => 'Nama lembaga wajib diisi.',
            'alamat.required'                   => 'Alamat lembaga wajib diisi.',
            'provinsi_kode.required'            => 'Provinsi wajib dipilih.',
            'kota_kode.required'                => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan_kode.required'           => 'Kecamatan wajib dipilih.',
            'kelurahan_kode.required'           => 'Kelurahan wajib dipilih.',
            'telepon_lembaga.required'          => 'Telepon lembaga wajib diisi.',
            'email_lembaga.required'            => 'Email lembaga wajib diisi.',
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
                'lembaga_id' => match ($request->peran) {
                    'admin_lembaga' => $request->lembaga_id,
                    'amil'          => $request->lembaga_id,
                    default         => null,
                },
            ];

            if (!empty($request->password)) {
                $penggunaData['password'] = Hash::make($request->password);
            }

            $pengguna->update($penggunaData);

            // ══════════════════════════════════════════════════════
            // ADMIN LEMBAGA: Update data Lembaga
            // ══════════════════════════════════════════════════════
            if ($request->peran === 'admin_lembaga') {
                $lembaga = Lembaga::findOrFail($request->lembaga_id);

                // Handle hapus foto admin
                $adminFotoPath = $lembaga->admin_foto;
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

                // Handle foto lembaga
                $currentFotos = (array) ($lembaga->foto ?? []);

                // Hapus foto yang dicentang untuk dihapus
                if ($request->filled('hapus_foto_lembaga')) {
                    $hapusIndeks = $request->input('hapus_foto_lembaga', []);
                    foreach ($hapusIndeks as $idx) {
                        if (isset($currentFotos[$idx])) {
                            Storage::disk('public')->delete($currentFotos[$idx]);
                            unset($currentFotos[$idx]);
                        }
                    }
                    $currentFotos = array_values($currentFotos);
                }

                // Upload foto baru
                if ($request->hasFile('foto_lembaga')) {
                    $newFotos = $request->file('foto_lembaga');
                    $totalFotos = count($currentFotos) + count($newFotos);
                    if ($totalFotos > Lembaga::MAX_FOTO) {
                        DB::rollBack();
                        return back()->withInput()
                            ->with('error', 'Maksimal ' . Lembaga::MAX_FOTO . ' foto lembaga yang diperbolehkan.');
                    }
                    foreach ($newFotos as $foto) {
                        $currentFotos[] = $foto->store('lembaga-fotos', 'public');
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

                $lembaga->update([
                    'admin_nama'       => $request->admin_nama,
                    'admin_telepon'    => $request->admin_telepon,
                    'admin_email'      => $request->admin_email,
                    'admin_foto'       => $adminFotoPath,
                    'nama'             => $request->nama_lembaga,
                    'alamat'           => $request->alamat,
                    'telepon'          => $request->telepon_lembaga,
                    'email'            => $request->email_lembaga,
                    'deskripsi'        => $request->deskripsi_lembaga,
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
                    'lembaga_id'            => $request->lembaga_id,
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
                    $amilData['kode_amil'] = $this->generateKodeAmil($request->lembaga_id);
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
                    'lembaga_id'  => $request->muzakki_lembaga_id ?: null,
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

            // ── Kirim Email Notifikasi Update ─────────────────────────────────────
            $namaUser = match ($pengguna->peran) {
                'admin_lembaga' => $request->admin_nama,
                'amil'          => $request->amil_nama_lengkap,
                'muzakki'       => $request->muzakki_nama,
                default         => $pengguna->username ?? $pengguna->email,
            };

            $lembagaNama = null;
            if (in_array($pengguna->peran, ['admin_lembaga', 'amil'])) {
                $lembagaNama = Lembaga::find($request->lembaga_id)?->nama;
            }

            $passwordBerubah = !empty($request->password);

            try {
                $mailConfig = MailConfig::first();
                if ($mailConfig && $mailConfig->isComplete()) {
                    config($mailConfig->toConfigArray());
                    Mail::purge('smtp');

                    Mail::mailer('smtp')
                        ->to($pengguna->email)
                        ->send(new PenggunaUpdatedMail(
                            namaLengkap: $namaUser,
                            email: $pengguna->email,
                            username: $pengguna->username ?? $pengguna->email,
                            peran: $pengguna->peran,
                            namaLembaga: $lembagaNama,
                            passwordChanged: $passwordBerubah,
                            newPassword: $passwordBerubah ? $request->password : null,
                        ));
                }
            } catch (\Exception $mailEx) {
                Log::error('Gagal kirim email notifikasi update pengguna', [
                    'pengguna_id' => $pengguna->id,
                    'error'       => $mailEx->getMessage(),
                ]);
            }

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
        $pengguna = Pengguna::with(['lembaga', 'amil', 'muzakki'])->where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $plainPassword = $request->password;

        $pengguna->update([
            'password'                        => Hash::make($plainPassword),
            'password_reset_token'            => null,
            'password_reset_token_expires_at' => null,
        ]);

        // ── Kirim Email Notifikasi Reset Password ─────────────────────────────
        $namaUser = match ($pengguna->peran) {
            'amil'    => $pengguna->amil?->nama_lengkap,
            'muzakki' => $pengguna->muzakki?->nama,
            default   => $pengguna->username ?? $pengguna->email,
        } ?? ($pengguna->username ?? $pengguna->email);

        try {
            $mailConfig = MailConfig::first();
            if ($mailConfig && $mailConfig->isComplete()) {
                config($mailConfig->toConfigArray());
                Mail::purge('smtp');

                Mail::mailer('smtp')
                    ->to($pengguna->email)
                    ->send(new PenggunaUpdatedMail(
                        namaLengkap: $namaUser,
                        email: $pengguna->email,
                        username: $pengguna->username ?? $pengguna->email,
                        peran: $pengguna->peran,
                        namaLembaga: $pengguna->lembaga?->nama,
                        passwordChanged: true,
                        newPassword: $plainPassword,
                    ));
            }
        } catch (\Exception $mailEx) {
            Log::error('Gagal kirim email notifikasi reset password', [
                'pengguna_id' => $pengguna->id,
                'error'       => $mailEx->getMessage(),
            ]);
        }

        return back()->with('success', 'Password pengguna berhasil direset dan notifikasi telah dikirim ke email.');
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

    private function generateKodeAmil(int $lembagaId): string
    {
        $lembaga = Lembaga::findOrFail($lembagaId);
        $prefix = 'AMIL-' . $lembaga->kode_lembaga . '-';

        $lastAmil = Amil::where('kode_amil', 'like', $prefix . '%')
            ->orderBy('kode_amil', 'desc')
            ->first();

        $newNumber = $lastAmil
            ? str_pad((int) substr($lastAmil->kode_amil, strlen($prefix)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        return $prefix . $newNumber;
    }
}