<?php

namespace App\Http\Controllers\Admin_lembaga;

use App\Http\Controllers\Controller;
use App\Models\Amil;
use App\Models\Lembaga;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\AmilRegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AmilController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Inisialisasi query
        $query = Amil::with(['lembaga']);

        // Jika admin lembaga, hanya tampilkan amil dari lembaga yang sama
        if ($user->peran === 'admin_lembaga') {
            $lembagaId = $user->lembaga_id;
            if ($lembagaId) {
                $query->where('lembaga_id', $lembagaId);
            }
        }

        // Jika pengguna, hanya tampilkan amil dari lembaga yang sama
        if ($user->peran === 'pengguna') {
            $lembagaId = $user->lembaga_id;
            if ($lembagaId) {
                $query->where('lembaga_id', $lembagaId);
            }
        }

        // Filter berdasarkan status
        if ($request->has('status') && in_array($request->status, ['aktif', 'nonaktif', 'cuti'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->has('jenis_kelamin') && in_array($request->jenis_kelamin, ['L', 'P'])) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan pencarian
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('kode_amil', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan lembaga (untuk superadmin)
        if ($user->peran === 'superadmin' && $request->has('lembaga_id') && $request->lembaga_id) {
            $query->where('lembaga_id', $request->lembaga_id);
        }

        $amils = $query->latest()->paginate(10);

        // Data untuk filter
        $lembagas = $user->peran === 'superadmin'
            ? Lembaga::where('is_active', true)->get()
            : collect();

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Siapkan data untuk JavaScript (untuk expandable content)
                $amilData = [];
                foreach ($amils as $amil) {
                    $colors = [
                        'bg-blue-500',
                        'bg-green-500',
                        'bg-yellow-500',
                        'bg-red-500',
                        'bg-purple-500',
                        'bg-pink-500',
                        'bg-indigo-500',
                        'bg-orange-500'
                    ];
                    $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                    $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];

                    $amilData[$amil->uuid] = [
                        'nama_lengkap' => $amil->nama_lengkap,
                        'kode_amil' => $amil->kode_amil,
                        'jenis_kelamin' => $amil->jenis_kelamin,
                        'tempat_lahir' => $amil->tempat_lahir,
                        'tanggal_lahir' => $amil->tanggal_lahir ? $amil->tanggal_lahir->format('d F Y') : '-',
                        'telepon' => $amil->telepon,
                        'email' => $amil->email,
                        'alamat' => $amil->alamat,
                        'wilayah_tugas' => $amil->wilayah_tugas,
                        'status' => $amil->status,
                        'tanggal_mulai_tugas' => $amil->tanggal_mulai_tugas ? \Carbon\Carbon::parse($amil->tanggal_mulai_tugas)->format('d/m/Y') : '-',
                        'tanggal_selesai_tugas' => $amil->tanggal_selesai_tugas ? \Carbon\Carbon::parse($amil->tanggal_selesai_tugas)->format('d/m/Y') : '-',
                        'created_at' => $amil->created_at->format('d/m/Y'),
                        'foto' => $amil->foto ? Storage::url($amil->foto) : null,
                        'initial' => $initial,
                        'bg_color' => $bgColor,
                    ];
                }

                // Render HTML untuk tabel
                $html = view('admin-lembaga.amil.partials.table', compact('amils'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'amilData' => $amilData,
                    'pagination' => (string) $amils->links(),
                    'total' => $amils->total()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        $breadcrumbs = [
            'Kelola Amil' => route('amil.index'),
        ];

        return view('admin-lembaga.amil.index', compact('amils', 'lembagas', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Jika admin lembaga atau pengguna, hanya bisa buat amil untuk lembaganya sendiri
        if (in_array($user->peran, ['admin_lembaga', 'pengguna'])) {
            $lembaga = Lembaga::find($user->lembaga_id);
            $lembagas = $lembaga ? collect([$lembaga]) : collect();
        } else {
            // Untuk superadmin
            $lembagas = Lembaga::where('is_active', true)->get();
        }

        $breadcrumbs = [
            'Kelola Amil' => route('amil.index'),
            'Tambah Amil' => route('amil.create')
        ];

        return view('admin-lembaga.amil.create', compact('lembagas', 'breadcrumbs'));
    }

    private function generateKodeAmil($lembagaId)
    {
        $lembaga = Lembaga::find($lembagaId);
        if (!$lembaga) {
            throw new \Exception('Lembaga tidak ditemukan');
        }

        // Format: AMIL-LMBG20260001-001
        $prefix = "AMIL-{$lembaga->kode_lembaga}-";

        $lastAmil = Amil::where('kode_amil', 'like', $prefix . '%')
            ->orderBy('kode_amil', 'desc')
            ->first();

        if ($lastAmil) {
            $lastNumber = intval(substr($lastAmil->kode_amil, strlen($prefix)));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Tentukan lembaga_id
        $lembagaId = $request->lembaga_id;
        if (in_array($user->peran, ['admin_lembaga', 'pengguna'])) {
            $lembagaId = $user->lembaga_id;

            if (!$lembagaId) {
                return back()->withInput()
                    ->with('error', 'Anda tidak terdaftar di lembaga manapun. Hubungi administrator.');
            }
        }

        // Validasi
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'alamat' => ['required', 'string'],
            'telepon' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:amil,email', 'unique:pengguna,email'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'tanggal_mulai_tugas' => ['required', 'date'],
            'tanggal_selesai_tugas' => ['nullable', 'date', 'after_or_equal:tanggal_mulai_tugas'],
            'status' => ['required', 'in:aktif,nonaktif,cuti'],
            'wilayah_tugas' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($user->peran === 'superadmin') {
            $request->validate([
                'lembaga_id' => ['required', 'exists:lembaga,id']
            ]);
            $lembagaId = $request->lembaga_id;
        }

        DB::beginTransaction();

        try {
            // Generate kode amil
            $kodeAmil = $this->generateKodeAmil($lembagaId);

            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('amil/foto', 'public');
            }

            // Generate username dan password untuk amil
            $username = $this->generateUsernameFromEmail($request->email);
            $defaultPassword = '12345678'; // Password default untuk amil baru

            // Buat akun pengguna untuk amil
            $pengguna = Pengguna::create([
                'uuid' => Str::uuid(),
                'lembaga_id' => $lembagaId,
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword), // Hash password default
                'email_verified_at' => now(), // Auto-verify
                'peran' => 'amil',
                'is_active' => true,
            ]);

            // Buat data amil
            $amil = Amil::create([
                'uuid' => Str::uuid(),
                'lembaga_id' => $lembagaId,
                'pengguna_id' => $pengguna->id, // Link ke pengguna
                'kode_amil' => $kodeAmil,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'foto' => $fotoPath,
                'tanggal_mulai_tugas' => $request->tanggal_mulai_tugas,
                'tanggal_selesai_tugas' => $request->tanggal_selesai_tugas,
                'status' => $request->status,
                'wilayah_tugas' => $request->wilayah_tugas,
                'keterangan' => $request->keterangan,
            ]);

            // Kirim email notifikasi
            try {
                // Load mail config
                $mailConfig = \App\Models\MailConfig::first();
                if ($mailConfig) {
                    config([
                        'mail.mailers.smtp.host' => $mailConfig->MAIL_HOST,
                        'mail.mailers.smtp.port' => $mailConfig->MAIL_PORT ?? 587,
                        'mail.mailers.smtp.username' => $mailConfig->MAIL_USERNAME,
                        'mail.mailers.smtp.password' => $mailConfig->MAIL_PASSWORD,
                        'mail.mailers.smtp.encryption' => $mailConfig->MAIL_ENCRYPTION ?? 'tls',
                        'mail.from.address' => $mailConfig->MAIL_FROM_ADDRESS ?? $mailConfig->MAIL_USERNAME,
                        'mail.from.name' => $mailConfig->MAIL_FROM_NAME ?? config('app.name'),
                    ]);
                }

                // Load relasi lembaga
                $amil->load('lembaga');

                // Kirim email dengan password default (plain text untuk ditampilkan)
                Mail::to($amil->email)->send(new \App\Mail\AmilRegistrationMail($amil, $username, $defaultPassword));

                Log::info('Email registrasi amil berhasil dikirim ke: ' . $amil->email);
                $emailSuccess = true;
            } catch (\Exception $mailException) {
                Log::error('Gagal mengirim email ke amil: ' . $mailException->getMessage());
                $emailSuccess = false;
            }

            DB::commit();

            $message = 'Data amil berhasil ditambahkan';
            if ($emailSuccess) {
                $message .= ' dan notifikasi telah dikirim ke email.';
            } else {
                $message .= ' tetapi email notifikasi gagal dikirim.';
            }

            return redirect()->route('amil.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store amil: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Gagal menambahkan data amil: ' . $e->getMessage());
        }
    }

    /**
     * Generate username dari email
     */
    private function generateUsernameFromEmail(string $email): string
    {
        $emailParts = explode('@', $email);
        $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $emailParts[0]);
        $baseUsername = strtolower($baseUsername);

        $username = $baseUsername;
        $counter = 1;

        while (Pengguna::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function show(Amil $amil)
    {
        $user = Auth::user();

        // Admin lembaga hanya bisa melihat amil dari lembaganya sendiri
        if ($user->peran === 'admin_lembaga') {
            $lembagaId = $user->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses ke data amil ini.');
            }
        }

        // Pengguna hanya bisa melihat amil dari lembaganya sendiri
        if ($user->peran === 'pengguna') {
            $lembagaId = $user->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses ke data amil ini.');
            }
        }

        $amil->load(['lembaga', 'pengguna']);

        $breadcrumbs = [
            'Kelola Amil' => route('amil.index'),
            'Detail Amil' => route('amil.show', $amil)
        ];

        return view('admin-lembaga.amil.show', compact('amil', 'breadcrumbs'));
    }

    public function edit(Amil $amil)
    {
        $user = Auth::user();

        // Admin lembaga hanya bisa edit amil dari lembaganya sendiri
        if ($user->peran === 'admin_lembaga') {
            $lembagaId = $user->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Pengguna hanya bisa edit amil dari lembaganya sendiri
        if ($user->peran === 'pengguna') {
            $lembagaId = $user->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Jika admin lembaga atau pengguna, hanya bisa edit amil dari lembaganya sendiri
        if (in_array($user->peran, ['admin_lembaga', 'pengguna'])) {
            $lembaga = Lembaga::find($user->lembaga_id);
            $lembagas = $lembaga ? collect([$lembaga]) : collect();
        } else {
            $lembagas = Lembaga::where('is_active', true)->get();
        }

        $breadcrumbs = [
            'Kelola Amil' => route('amil.index'),
            'Edit Amil' => route('amil.edit', $amil)
        ];

        return view('admin-lembaga.amil.edit', compact('amil', 'lembagas', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Amil $amil)
    {
        // Authorization check
        $user = Auth::user();

        // Admin lembaga hanya bisa update amil dari lembaganya sendiri
        if ($user->role === 'admin_lembaga') {
            $lembagaId = $user->pengguna?->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Pengguna hanya bisa update amil dari lembaganya sendiri
        if ($user->role === 'pengguna') {
            $lembagaId = $user->pengguna?->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Validasi
        $request->validate([
            'lembaga_id' => ['required', 'exists:lembaga,id'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'alamat' => ['required', 'string'],
            'telepon' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('amil', 'email')->ignore($amil->id)
            ],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'tanggal_mulai_tugas' => ['required', 'date'],
            'tanggal_selesai_tugas' => ['nullable', 'date', 'after_or_equal:tanggal_mulai_tugas'],
            'status' => ['required', 'in:aktif,nonaktif,cuti'],
            'wilayah_tugas' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Jika admin lembaga atau pengguna, pastikan hanya bisa update untuk lembaganya sendiri
        if (in_array($user->role, ['admin_lembaga', 'pengguna'])) {
            $request->merge(['lembaga_id' => $user->pengguna?->lembaga_id]);
        }

        try {
            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($amil->foto) {
                    Storage::disk('public')->delete($amil->foto);
                }

                // Upload foto baru
                $fotoPath = $request->file('foto')->store('amil/foto', 'public');
                $amil->foto = $fotoPath;
            }

            // Update data amil
            $amil->update([
                'lembaga_id' => $request->lembaga_id,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'tanggal_mulai_tugas' => $request->tanggal_mulai_tugas,
                'tanggal_selesai_tugas' => $request->tanggal_selesai_tugas,
                'status' => $request->status,
                'wilayah_tugas' => $request->wilayah_tugas,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('amil.index')
                ->with('success', 'Data amil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui data amil: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amil $amil)
    {
        // Authorization check
        $user = Auth::user();

        // Admin lembaga hanya bisa hapus amil dari lembaganya sendiri
        if ($user->peran === 'admin_lembaga') {
            $lembagaId = $user->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus data amil ini.');
            }
        }

        // Pengguna hanya bisa hapus amil dari lembaganya sendiri
        if ($user->peran === 'pengguna') {
            $lembagaId = $user->lembaga_id;
            if ($amil->lembaga_id !== $lembagaId) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus data amil ini.');
            }
        }

        DB::beginTransaction();

        try {
            // Simpan data untuk log
            $amilName = $amil->nama_lengkap;
            $amilEmail = $amil->email;

            // Hapus foto jika ada
            if ($amil->foto) {
                Storage::disk('public')->delete($amil->foto);
            }

            // Hapus pengguna terkait jika ada
            if ($amil->pengguna_id) {
                $pengguna = Pengguna::find($amil->pengguna_id);
                if ($pengguna) {
                    $pengguna->delete();
                    Log::info("Pengguna terkait amil berhasil dihapus: {$amilEmail}");
                }
            }

            // Hapus data amil
            $amil->delete();

            DB::commit();

            Log::info("Amil berhasil dihapus: {$amilName} ({$amilEmail})");

            return redirect()->route('amil.index')
                ->with('success', "Data amil {$amilName} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete amil: ' . $e->getMessage());

            return back()
                ->with('error', 'Gagal menghapus data amil: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, Amil $amil)
    {
        try {
            // Toggle status logic
            if ($amil->status === 'aktif') {
                $amil->status = 'nonaktif';
                $amil->tanggal_selesai_tugas = now();
            } else {
                $amil->status = 'aktif';
                $amil->tanggal_mulai_tugas = now();
                $amil->tanggal_selesai_tugas = null;
            }

            $amil->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diubah',
                    'new_status' => $amil->status,
                    'status_badge_html' => view('amil.partials.status-badge', ['status' => $amil->status])->render()
                ]);
            }

            return redirect()->route('amil.show', $amil->uuid)
                ->with('success', 'Status berhasil diubah');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('amil.show', $amil->uuid)
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Get amil by lembaga (API)
     */
    public function getByLembaga($lembagaId)
    {
        $amils = Amil::where('lembaga_id', $lembagaId)
            ->where('status', 'aktif')
            ->select('id', 'kode_amil', 'nama_lengkap')
            ->get();

        return response()->json($amils);
    }

    // ═══════════════════════════════════════════════════════════════
    // TAMBAHKAN METHOD-METHOD INI KE DALAM AmilController.php
    // Letakkan sebelum closing brace } terakhir dari class
    // ═══════════════════════════════════════════════════════════════

    // ─────────────────────────────────────────────────────────────
    // EXPORT EXCEL
    // ─────────────────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'superadmin'])) {
            abort(403);
        }

        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $user      = auth()->user();
        $query     = Amil::with(['lembaga'])->orderBy('nama_lengkap');

        if ($user->peran === 'admin_lembaga') {
            $query->where('lembaga_id', $user->lembaga_id);
        }

        // Filter opsional (ikuti filter index)
        if ($request->filled('status'))        $query->where('status', $request->status);
        if ($request->filled('jenis_kelamin')) $query->where('jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('kode_amil',  'like', "%{$q}%")
                    ->orWhere('email',      'like', "%{$q}%")
                    ->orWhere('telepon',    'like', "%{$q}%");
            });
        }

        $amils       = $query->get();
        $totalData   = $amils->count();
        $lembagaNama = $user->lembaga?->nama ?? 'Semua Lembaga';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Amil');

        $lastCol   = 'P';
        $headerRow = 4;
        $dataStart = 5;

        // ── Paksa TEXT dulu sebelum isi data ─────────────────────
        $textFormat = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT;
        foreach (['D', 'E', 'F', 'G'] as $col) {       // D=Tgl Lahir, E=Telepon, F=Email, G=KTP (jika ada)
            $sheet->getStyle($col . $dataStart . ':' . $col . ($dataStart + $totalData + 5))
                ->getNumberFormat()->setFormatCode($textFormat);
        }

        // ── Baris 1: Judul ───────────────────────────────────────
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->setCellValue('A1', 'DATA AMIL — ' . strtoupper($lembagaNama));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Baris 2: Info ────────────────────────────────────────
        $sheet->mergeCells('A2:' . $lastCol . '2');
        $sheet->setCellValue('A2', 'Diekspor: ' . now()->format('d F Y, H:i') . ' WIB  |  Total: ' . $totalData . ' amil');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 9, 'color' => ['rgb' => '374151']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
            'alignment' => ['horizontal' => 'center'],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── Baris 4: Header ──────────────────────────────────────
        $headers = [
            'A' => ['No.',                  5],
            'B' => ['Kode Amil',           18],
            'C' => ['Nama Lengkap',        28],
            'D' => ['Tanggal Lahir',       16],
            'E' => ['No. Telepon',         16],
            'F' => ['Email',               28],
            'G' => ['Jenis Kelamin',       14],
            'H' => ['Tempat Lahir',        18],
            'I' => ['Alamat',              36],
            'J' => ['Wilayah Tugas',       20],
            'K' => ['Status',              14],
            'L' => ['Tgl. Mulai Tugas',    18],
            'M' => ['Tgl. Selesai Tugas',  18],
            'N' => ['Keterangan',          26],
            'O' => ['Status Aktif',        14],
            'P' => ['Tgl. Bergabung',      18],
        ];

        foreach ($headers as $col => [$label, $width]) {
            $sheet->setCellValue($col . $headerRow, $label);
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->getStyle('A' . $headerRow . ':' . $lastCol . $headerRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E40AF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '3B5998']]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // ── Isi data ─────────────────────────────────────────────
        $row = $dataStart;
        $no  = 1;

        foreach ($amils as $amil) {
            $bg         = ($no % 2 === 0) ? 'EFF6FF' : 'FFFFFF';
            $statusAmil = match ($amil->status) {
                'aktif'    => 'Aktif',
                'nonaktif' => 'Nonaktif',
                'cuti'     => 'Cuti',
                default    => $amil->status ?? '-',
            };

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $amil->kode_amil ?? '-');
            $sheet->setCellValue('C' . $row, $amil->nama_lengkap);

            // Tanggal lahir → TYPE_STRING YYYY-MM-DD
            $sheet->setCellValueExplicit(
                'D' . $row,
                $amil->tanggal_lahir ? \Carbon\Carbon::parse($amil->tanggal_lahir)->format('Y-m-d') : '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // Telepon → TYPE_STRING agar 0 di depan tidak hilang
            $sheet->setCellValueExplicit(
                'E' . $row,
                $amil->telepon ?? '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // Email → TYPE_STRING
            $sheet->setCellValueExplicit(
                'F' . $row,
                $amil->email ?? '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            $sheet->setCellValue('G' . $row, $amil->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('H' . $row, $amil->tempat_lahir ?? '-');
            $sheet->setCellValue('I' . $row, $amil->alamat ?? '-');
            $sheet->setCellValue('J' . $row, $amil->wilayah_tugas ?? '-');
            $sheet->setCellValue('K' . $row, $statusAmil);
            $sheet->setCellValue('L' . $row, $amil->tanggal_mulai_tugas
                ? \Carbon\Carbon::parse($amil->tanggal_mulai_tugas)->format('d/m/Y') : '-');
            $sheet->setCellValue('M' . $row, $amil->tanggal_selesai_tugas
                ? \Carbon\Carbon::parse($amil->tanggal_selesai_tugas)->format('d/m/Y') : '-');
            $sheet->setCellValue('N' . $row, $amil->keterangan ?? '-');
            $sheet->setCellValue('O' . $row, ($amil->pengguna?->is_active ?? false) ? 'Aktif' : 'Nonaktif');
            $sheet->setCellValue('P' . $row, $amil->created_at
                ? $amil->created_at->format('d/m/Y') : '-');

            // Warna baris
            $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray([
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'E5E7EB']]],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);

            // Warna badge status
            $statusStyle = match ($statusAmil) {
                'Aktif'    => ['bg' => 'DCFCE7', 'font' => '166534'],
                'Cuti'     => ['bg' => 'FEF9C3', 'font' => '713F12'],
                'Nonaktif' => ['bg' => 'FEE2E2', 'font' => '991B1B'],
                default    => null,
            };
            if ($statusStyle) {
                $sheet->getStyle('K' . $row)->applyFromArray([
                    'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $statusStyle['bg']]],
                    'font'      => ['bold' => true, 'color' => ['rgb' => $statusStyle['font']]],
                    'alignment' => ['horizontal' => 'center'],
                ]);
            }

            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;
            $no++;
        }

        // ── Baris total ──────────────────────────────────────────
        $sheet->mergeCells('A' . $row . ':N' . $row);
        $sheet->setCellValue('A' . $row, 'Total: ' . $totalData . ' amil');
        $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray([
            'font'    => ['bold' => true, 'color' => ['rgb' => '1E3A8A']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '93C5FD']]],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(20);

        // ── Freeze & auto filter ─────────────────────────────────
        $sheet->freezePane('A' . $dataStart);
        $sheet->setAutoFilter('A' . $headerRow . ':' . $lastCol . $headerRow);

        // ── Stream ───────────────────────────────────────────────
        $filename = 'amil_' . now()->format('Ymd_His') . '.xlsx';
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // DOWNLOAD TEMPLATE IMPORT AMIL
    // ─────────────────────────────────────────────────────────────
    public function downloadTemplate()
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'superadmin'])) {
            abort(403);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Amil');

        $columns = [
            'A' => ['label' => 'Nama Lengkap *',                          'width' => 28],
            'B' => ['label' => 'Jenis Kelamin * (L/P)',                   'width' => 20],
            'C' => ['label' => 'Tempat Lahir *',                          'width' => 20],
            'D' => ['label' => 'Tanggal Lahir (YYYY-MM-DD) *',            'width' => 24],
            'E' => ['label' => 'Alamat *',                                 'width' => 36],
            'F' => ['label' => 'No. Telepon *',                            'width' => 18],
            'G' => ['label' => 'Email *',                                  'width' => 28],
            'H' => ['label' => 'Wilayah Tugas',                           'width' => 22],
            'I' => ['label' => 'Status * (aktif/nonaktif/cuti)',           'width' => 26],
            'J' => ['label' => 'Tgl. Mulai Tugas (YYYY-MM-DD) *',         'width' => 26],
            'K' => ['label' => 'Tgl. Selesai Tugas (YYYY-MM-DD)',         'width' => 26],
            'L' => ['label' => 'Keterangan',                              'width' => 30],
        ];

        foreach ($columns as $col => $info) {
            $sheet->getCell($col . '1')->setValue($info['label']);
            $sheet->getStyle($col . '1')->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
                'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '3B5998']]],
            ]);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }
        $sheet->getRowDimension(1)->setRowHeight(38);

        // Format TEXT untuk kolom Tanggal & Telepon sebelum isi contoh
        $textFormat = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT;
        foreach (['D', 'F', 'J', 'K'] as $col) {
            $sheet->getStyle($col . '1:' . $col . '1000')
                ->getNumberFormat()->setFormatCode($textFormat);
        }

        // Baris contoh
        $examples = [
            'A' => ['Ahmad Fauzi',                   \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'B' => ['L',                             \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'C' => ['Jakarta',                       \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'D' => ['1990-05-20',                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'E' => ['Jl. Mawar No. 10 Jakarta',      \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'F' => ['081234567890',                  \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'G' => ['ahmad.fauzi@email.com',         \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'H' => ['Jakarta Pusat',                 \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'I' => ['aktif',                         \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'J' => ['2024-01-01',                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'K' => ['',                              \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
            'L' => ['Amil untuk wilayah pusat',     \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        ];

        foreach ($examples as $col => [$val, $type]) {
            $sheet->getCell($col . '2')->setValueExplicit($val, $type);
            $sheet->getStyle($col . '2')->applyFromArray([
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
                'alignment' => ['vertical' => 'center'],
                'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CCCCCC']]],
            ]);
        }
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->freezePane('A2');

        // Sheet instruksi
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Instruksi');
        $instruksi = [
            ['INSTRUKSI PENGISIAN TEMPLATE IMPORT AMIL',                                      true,  13],
            ['',                                                                               false, 11],
            ['KOLOM WAJIB DIISI (bertanda *):',                                               true,  11],
            ['1. Nama Lengkap — Nama lengkap amil',                                           false, 10],
            ['2. Jenis Kelamin — Isi L (Laki-laki) atau P (Perempuan)',                       false, 10],
            ['3. Tempat Lahir — Kota/kabupaten tempat lahir',                                 false, 10],
            ['4. Tanggal Lahir — Format YYYY-MM-DD (contoh: 1990-05-20)',                     false, 10],
            ['5. Alamat — Alamat lengkap amil',                                               false, 10],
            ['6. No. Telepon — Tulis dengan 0 di depan (contoh: 081234567890)',               false, 10],
            ['7. Email — Alamat email unik, akan dipakai untuk login',                        false, 10],
            ['8. Status — Isi: aktif / nonaktif / cuti',                                      false, 10],
            ['9. Tgl. Mulai Tugas — Format YYYY-MM-DD',                                       false, 10],
            ['',                                                                               false, 10],
            ['KOLOM OPSIONAL:',                                                               true,  11],
            ['- Wilayah Tugas — Area tugas amil (opsional)',                                  false, 10],
            ['- Tgl. Selesai Tugas — Kosongkan jika masih aktif',                             false, 10],
            ['- Keterangan — Catatan tambahan',                                               false, 10],
            ['',                                                                               false, 10],
            ['CATATAN PENTING:',                                                              true,  11],
            ['- Email harus unik dan belum terdaftar di sistem',                              false, 10],
            ['- Akun login akan dibuat otomatis dengan password default: 12345678',           false, 10],
            ['- Notifikasi login akan dikirim ke email masing-masing amil',                   false, 10],
            ['- Jangan mengubah urutan atau nama kolom header (baris ke-1)',                   false, 10],
            ['- Data dimulai dari baris ke-2 (baris contoh boleh dihapus)',                   false, 10],
            ['- Maksimal 1.000 baris per sekali import',                                      false, 10],
            ['- Format file harus .xlsx atau .xls',                                           false, 10],
        ];
        foreach ($instruksi as $i => [$text, $bold, $size]) {
            $rowNum = $i + 1;
            $sheet2->getCell('A' . $rowNum)->setValue($text);
            $sheet2->getStyle('A' . $rowNum)->applyFromArray([
                'font'      => ['bold' => $bold, 'size' => $size],
                'alignment' => ['vertical' => 'center'],
            ]);
            $sheet2->getRowDimension($rowNum)->setRowHeight(18);
        }
        $sheet2->getColumnDimension('A')->setWidth(80);

        $spreadsheet->setActiveSheetIndex(0);

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_import_amil.xlsx';

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // STEP 1 — Upload file Excel, simpan sementara, baca kolom
    // ─────────────────────────────────────────────────────────────
    public function uploadImport(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'superadmin'])) {
            abort(403);
        }

        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls|max:512000',
        ], [
            'file_import.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_import.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_import.max'      => 'Ukuran file maksimal 500 MB.',
        ]);

        $file        = $request->file('file_import');
        $tmpFilename = 'import_amil_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $tmpPath     = $file->storeAs('imports', $tmpFilename);

        try {
            $fullPath    = storage_path('app/' . $tmpPath);
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
            $sheet       = $spreadsheet->getActiveSheet();
            $highestCol  = $sheet->getHighestColumn();
            $highestRow  = $sheet->getHighestRow();

            if ($highestRow < 2) {
                \Illuminate\Support\Facades\Storage::delete($tmpPath);
                return back()->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            $excelHeaders = [];
            foreach ($sheet->getRowIterator(1, 1) as $row) {
                foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                    $val = trim((string) $cell->getValue());
                    if ($val !== '') $excelHeaders[] = $val;
                }
            }

            if (empty($excelHeaders)) {
                \Illuminate\Support\Facades\Storage::delete($tmpPath);
                return back()->with('error', 'Baris header tidak ditemukan di file Excel.');
            }

            $previewRows = [];
            $maxPreview  = min($highestRow, 101);
            foreach ($sheet->getRowIterator(2, $maxPreview) as $row) {
                $rowData = [];
                foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                    $rowData[] = $cell->getValue();
                }
                if (count(array_filter($rowData, fn($v) => $v !== null && $v !== '')) > 0) {
                    $previewRows[] = $rowData;
                }
            }

            $totalRows = $highestRow - 1;

            if ($totalRows > 1000) {
                \Illuminate\Support\Facades\Storage::delete($tmpPath);
                return back()->with('error', 'Jumlah data melebihi batas maksimal 1.000 baris per sekali import.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Storage::delete($tmpPath);
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        session([
            'import_amil' => [
                'tmp_path'      => $tmpPath,
                'excel_headers' => $excelHeaders,
                'preview_rows'  => $previewRows,
                'total_rows'    => $totalRows,
                'lembaga_id'    => auth()->user()->lembaga_id,
                'uploaded_by'   => auth()->id(),
                'uploaded_at'   => now()->toDateTimeString(),
            ],
        ]);

        return redirect()->route('import.pemetaan');
    }

    // ─────────────────────────────────────────────────────────────
    // STEP 2 — Tampilkan halaman pemetaan kolom
    // ─────────────────────────────────────────────────────────────
    public function pemetaanImport(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'superadmin'])) {
            abort(403);
        }

        $importSession = session('import_amil');
        if (!$importSession || !isset($importSession['tmp_path'])) {
            return redirect()->route('amil.index')
                ->with('error', 'Sesi import tidak ditemukan. Silakan upload ulang file.');
        }

        $systemColumns = [
            'nama_lengkap'          => ['label' => 'Nama Lengkap',                 'required' => true],
            'jenis_kelamin'         => ['label' => 'Jenis Kelamin (L/P)',           'required' => true],
            'tempat_lahir'          => ['label' => 'Tempat Lahir',                  'required' => true],
            'tanggal_lahir'         => ['label' => 'Tanggal Lahir',                 'required' => true],
            'alamat'                => ['label' => 'Alamat',                        'required' => true],
            'telepon'               => ['label' => 'No. Telepon',                   'required' => true],
            'email'                 => ['label' => 'Email',                         'required' => true],
            'wilayah_tugas'         => ['label' => 'Wilayah Tugas',                 'required' => false],
            'status'                => ['label' => 'Status (aktif/nonaktif/cuti)',  'required' => true],
            'tanggal_mulai_tugas'   => ['label' => 'Tgl. Mulai Tugas',              'required' => true],
            'tanggal_selesai_tugas' => ['label' => 'Tgl. Selesai Tugas',            'required' => false],
            'keterangan'            => ['label' => 'Keterangan',                    'required' => false],
        ];

        // Auto-mapping
        $autoMapping = [];
        foreach ($importSession['excel_headers'] as $idx => $excelHeader) {
            $normalized = strtolower(trim(preg_replace('/[^a-z0-9]/i', '_', $excelHeader)));
            foreach (array_keys($systemColumns) as $fieldKey) {
                if (
                    $normalized === $fieldKey || str_contains($normalized, $fieldKey)
                    || str_contains($fieldKey, explode('_', $normalized)[0])
                ) {
                    if (!in_array($fieldKey, $autoMapping)) {
                        $autoMapping[$idx] = $fieldKey;
                        break;
                    }
                }
            }
        }

        $breadcrumbs = [
            'Kelola Amil'           => route('amil.index'),
            'Pemetaan Kolom Import' => route('import.pemetaan'),
        ];

        return view('admin-lembaga.amil.import-pemetaan', compact(
            'importSession',
            'systemColumns',
            'autoMapping',
            'breadcrumbs'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // STEP 3 — Proses import sesungguhnya
    // ─────────────────────────────────────────────────────────────
    public function prosesImport(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'superadmin'])) {
            abort(403);
        }

        $importSession = session('import_amil');
        if (!$importSession || !isset($importSession['tmp_path'])) {
            return redirect()->route('amil.index')
                ->with('error', 'Sesi import tidak ditemukan. Silakan upload ulang file.');
        }

        $request->validate([
            'mapping'   => 'required|array',
            'mapping.*' => 'nullable|string',
        ]);

        $mapping        = $request->input('mapping');
        $requiredFields = [
            'nama_lengkap',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'telepon',
            'email',
            'status',
            'tanggal_mulai_tugas'
        ];
        $mappedFields   = array_values(array_filter($mapping));

        foreach ($requiredFields as $rf) {
            if (!in_array($rf, $mappedFields)) {
                return back()->with('error', "Kolom wajib \"{$rf}\" belum dipetakan.")->withInput();
            }
        }

        $fullPath = storage_path('app/' . $importSession['tmp_path']);
        if (!file_exists($fullPath)) {
            session()->forget('import_amil');
            return redirect()->route('amil.index')
                ->with('error', 'File import sudah tidak tersedia. Silakan upload ulang.');
        }

        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $lembagaId = auth()->user()->lembaga_id;
        $userId    = auth()->id();
        $imported  = 0;
        $skipped   = 0;
        $errors    = [];

        // Preload email yang sudah ada agar cek duplikat O(1)
        $existingEmails = \App\Models\Pengguna::pluck('email')
            ->merge(Amil::pluck('email'))
            ->map(fn($e) => strtolower(trim($e)))
            ->flip()->toArray();

        try {
            $readerCount = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fullPath);
            $readerCount->setReadDataOnly(true);
            $readerCount->setReadEmptyCells(false);
            $spreadsheetCount = $readerCount->load($fullPath);
            $highestRow       = $spreadsheetCount->getActiveSheet()->getHighestRow();
            $highestCol       = $spreadsheetCount->getActiveSheet()->getHighestColumn();
            $spreadsheetCount->disconnectWorksheets();
            unset($spreadsheetCount, $readerCount);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $chunkSize   = 100;
        $chunkFilter = new \App\Imports\ChunkReadFilter();

        DB::beginTransaction();
        try {
            for ($startRow = 2; $startRow <= $highestRow; $startRow += $chunkSize) {
                $endRow = min($startRow + $chunkSize - 1, $highestRow);
                $chunkFilter->setRows($startRow, $endRow);

                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fullPath);
                $reader->setReadDataOnly(true);
                $reader->setReadEmptyCells(false);
                $reader->setReadFilter($chunkFilter);
                $spreadsheet = $reader->load($fullPath);
                $sheet       = $spreadsheet->getActiveSheet();

                foreach ($sheet->getRowIterator($startRow, $endRow) as $rowIndex => $row) {
                    $cells    = [];
                    $colIndex = 0;
                    foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                        $cells[$colIndex] = $cell->getValue();
                        $colIndex++;
                    }

                    if (empty(array_filter($cells, fn($v) => $v !== null && $v !== ''))) continue;

                    $rowData = [];
                    foreach ($mapping as $excelColIdx => $systemField) {
                        if (!$systemField) continue;
                        $rowData[$systemField] = isset($cells[$excelColIdx])
                            ? trim((string) $cells[$excelColIdx]) : null;
                    }

                    // Validasi per baris
                    $rowErrors = $this->validateImportAmilRow($rowData, $rowIndex);
                    if (!empty($rowErrors)) {
                        $errors[] = "Baris {$rowIndex}: " . implode(', ', $rowErrors);
                        $skipped++;
                        continue;
                    }

                    // Cek duplikat email
                    $emailBersih = strtolower(trim($rowData['email'] ?? ''));
                    if (isset($existingEmails[$emailBersih])) {
                        $errors[] = "Baris {$rowIndex}: Email {$emailBersih} sudah terdaftar, baris dilewati.";
                        $skipped++;
                        continue;
                    }
                    $existingEmails[$emailBersih] = true;

                    // Parse tanggal
                    $tanggalLahir      = $this->parseDateAmil($rowData['tanggal_lahir'] ?? null);
                    $tanggalMulai      = $this->parseDateAmil($rowData['tanggal_mulai_tugas'] ?? null);
                    $tanggalSelesai    = $this->parseDateAmil($rowData['tanggal_selesai_tugas'] ?? null);

                    // Buat akun Pengguna
                    $username        = $this->generateUsernameFromEmail($rowData['email']);
                    $defaultPassword = '12345678';

                    $pengguna = Pengguna::create([
                        'uuid'              => \Illuminate\Support\Str::uuid(),
                        'lembaga_id'        => $lembagaId,
                        'username'          => $username,
                        'email'             => $rowData['email'],
                        'password'          => Hash::make($defaultPassword),
                        'email_verified_at' => now(),
                        'peran'             => 'amil',
                        'is_active'         => true,
                    ]);

                    // Buat data Amil
                    $amil = Amil::create([
                        'uuid'                  => \Illuminate\Support\Str::uuid(),
                        'lembaga_id'            => $lembagaId,
                        'pengguna_id'           => $pengguna->id,
                        'kode_amil'             => $this->generateKodeAmil($lembagaId),
                        'nama_lengkap'          => $rowData['nama_lengkap'],
                        'jenis_kelamin'         => strtoupper($rowData['jenis_kelamin']),
                        'tempat_lahir'          => $rowData['tempat_lahir'],
                        'tanggal_lahir'         => $tanggalLahir,
                        'alamat'                => $rowData['alamat'],
                        'telepon'               => $rowData['telepon'],
                        'email'                 => $rowData['email'],
                        'wilayah_tugas'         => $rowData['wilayah_tugas'] ?? null,
                        'status'                => strtolower($rowData['status']),
                        'tanggal_mulai_tugas'   => $tanggalMulai,
                        'tanggal_selesai_tugas' => $tanggalSelesai,
                        'keterangan'            => $rowData['keterangan'] ?? null,
                    ]);

                    // Email sengaja tidak dikirim saat import massal
                    Log::info("Import amil: akun dibuat untuk {$amil->email}, notifikasi email dilewati.");

                    $imported++;
                }

                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet, $reader);
                gc_collect_cycles();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }

        \Illuminate\Support\Facades\Storage::delete($importSession['tmp_path']);
        session()->forget('import_amil');

        $message = "Import selesai. {$imported} amil berhasil diimport.";
        if ($skipped > 0) $message .= " {$skipped} baris dilewati.";

        return redirect()->route('amil.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    // ─────────────────────────────────────────────────────────────
    // BATAL IMPORT
    // ─────────────────────────────────────────────────────────────
    public function batalImport()
    {
        $importSession = session('import_amil');
        if ($importSession && isset($importSession['tmp_path'])) {
            \Illuminate\Support\Facades\Storage::delete($importSession['tmp_path']);
        }
        session()->forget('import_amil');
        return redirect()->route('amil.index')->with('info', 'Import dibatalkan.');
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS IMPORT AMIL
    // ─────────────────────────────────────────────────────────────
    private function validateImportAmilRow(array $row, int $rowIndex): array
    {
        $errors = [];

        if (empty($row['nama_lengkap']))    $errors[] = 'nama_lengkap kosong';
        if (empty($row['jenis_kelamin']))   $errors[] = 'jenis_kelamin kosong';
        elseif (!in_array(strtoupper($row['jenis_kelamin']), ['L', 'P']))
            $errors[] = 'jenis_kelamin harus L atau P';
        if (empty($row['tempat_lahir']))    $errors[] = 'tempat_lahir kosong';
        if (empty($row['tanggal_lahir']))   $errors[] = 'tanggal_lahir kosong';
        elseif (!$this->parseDateAmil($row['tanggal_lahir']))
            $errors[] = 'format tanggal_lahir tidak valid (YYYY-MM-DD)';
        if (empty($row['alamat']))          $errors[] = 'alamat kosong';
        if (empty($row['telepon']))         $errors[] = 'telepon kosong';
        if (empty($row['email']))           $errors[] = 'email kosong';
        elseif (!filter_var($row['email'], FILTER_VALIDATE_EMAIL))
            $errors[] = 'format email tidak valid';
        if (empty($row['status']))          $errors[] = 'status kosong';
        elseif (!in_array(strtolower($row['status']), ['aktif', 'nonaktif', 'cuti']))
            $errors[] = 'status harus: aktif / nonaktif / cuti';
        if (empty($row['tanggal_mulai_tugas']))
            $errors[] = 'tanggal_mulai_tugas kosong';
        elseif (!$this->parseDateAmil($row['tanggal_mulai_tugas']))
            $errors[] = 'format tanggal_mulai_tugas tidak valid (YYYY-MM-DD)';

        return $errors;
    }

    private function parseDateAmil(?string $value): ?string
    {
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $formats = ['Y-m-d', 'Y/m/d', 'd/m/Y', 'd-m-Y', 'm/d/Y'];
        foreach ($formats as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, trim($value));
            if ($dt && $dt->format($fmt) === trim($value)) return $dt->format('Y-m-d');
        }

        $ts = strtotime($value);
        return $ts !== false ? date('Y-m-d', $ts) : null;
    }
}