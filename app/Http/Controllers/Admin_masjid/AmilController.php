<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\Amil;
use App\Models\Masjid;
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


class AmilController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Inisialisasi query
        $query = Amil::with(['masjid']);

        // Jika admin masjid, hanya tampilkan amil dari masjid yang sama
        if ($user->peran === 'admin_masjid') {
            $masjidId = $user->masjid_id;
            if ($masjidId) {
                $query->where('masjid_id', $masjidId);
            }
        }

        // Jika pengguna, hanya tampilkan amil dari masjid yang sama
        if ($user->peran === 'pengguna') {
            $masjidId = $user->masjid_id;
            if ($masjidId) {
                $query->where('masjid_id', $masjidId);
            }
        }

        // Filter berdasarkan status
        if ($request->has('status') && in_array($request->status, ['aktif', 'nonaktif', 'cuti'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('kode_amil', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan masjid (untuk superadmin)
        if ($user->peran === 'superadmin' && $request->has('masjid_id')) {
            $query->where('masjid_id', $request->masjid_id);
        }

        $amils = $query->latest()->paginate(10);

        // Data untuk filter
        $masjids = $user->peran === 'superadmin'
            ? Masjid::where('is_active', true)->get()
            : collect();

        return view('admin-masjid.amil.index', compact('amils', 'masjids'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Jika admin masjid atau pengguna, hanya bisa buat amil untuk masjidnya sendiri
        if (in_array($user->peran, ['admin_masjid', 'pengguna'])) {
            $masjid = Masjid::find($user->masjid_id);
            $masjids = $masjid ? collect([$masjid]) : collect();
        } else {
            // Untuk superadmin
            $masjids = Masjid::where('is_active', true)->get();
        }

        return view('admin-masjid.amil.create', compact('masjids'));
    }

    /**
     * Generate kode amil otomatis
     */
    private function generateKodeAmil($masjidId)
    {
        $masjid = Masjid::find($masjidId);
        if (!$masjid) {
            throw new \Exception('Masjid tidak ditemukan');
        }

        // Format: AMIL-KODE_MASJID-001
        $prefix = "AMIL-{$masjid->kode_masjid}-";

        // Cari nomor terakhir
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

        // Tentukan masjid_id
        $masjidId = $request->masjid_id;
        if (in_array($user->peran, ['admin_masjid', 'pengguna'])) {
            $masjidId = $user->masjid_id;

            if (!$masjidId) {
                return back()->withInput()
                    ->with('error', 'Anda tidak terdaftar di masjid manapun. Hubungi administrator.');
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
                'masjid_id' => ['required', 'exists:masjid,id']
            ]);
            $masjidId = $request->masjid_id;
        }

        DB::beginTransaction();

        try {
            // Generate kode amil
            $kodeAmil = $this->generateKodeAmil($masjidId);

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
                'masjid_id' => $masjidId,
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
                'masjid_id' => $masjidId,
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

                // Load relasi masjid
                $amil->load('masjid');

                // Kirim email dengan password default (plain text untuk ditampilkan)
                Mail::to($amil->email)->send(new \App\Mail\AmilRegistrationMail($amil, $username, $defaultPassword));

                \Log::info('Email registrasi amil berhasil dikirim ke: ' . $amil->email);
                $emailSuccess = true;
            } catch (\Exception $mailException) {
                \Log::error('Gagal mengirim email ke amil: ' . $mailException->getMessage());
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
            \Log::error('Error store amil: ' . $e->getMessage());

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

        // Admin masjid hanya bisa melihat amil dari masjidnya sendiri
        if ($user->peran === 'admin_masjid') {
            $masjidId = $user->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses ke data amil ini.');
            }
        }

        // Pengguna hanya bisa melihat amil dari masjidnya sendiri
        if ($user->peran === 'pengguna') {
            $masjidId = $user->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses ke data amil ini.');
            }
        }

        $amil->load(['masjid', 'pengguna']);

        return view('admin-masjid.amil.show', compact('amil'));
    }

    public function edit(Amil $amil)
    {
        $user = Auth::user();

        // Admin masjid hanya bisa edit amil dari masjidnya sendiri
        if ($user->peran === 'admin_masjid') {
            $masjidId = $user->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Pengguna hanya bisa edit amil dari masjidnya sendiri
        if ($user->peran === 'pengguna') {
            $masjidId = $user->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Jika admin masjid atau pengguna, hanya bisa edit amil dari masjidnya sendiri
        if (in_array($user->peran, ['admin_masjid', 'pengguna'])) {
            $masjid = Masjid::find($user->masjid_id);
            $masjids = $masjid ? collect([$masjid]) : collect();
        } else {
            $masjids = Masjid::where('is_active', true)->get();
        }

        return view('admin-masjid.amil.edit', compact('amil', 'masjids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Amil $amil)
    {
        // Authorization check
        $user = Auth::user();

        // Admin masjid hanya bisa update amil dari masjidnya sendiri
        if ($user->role === 'admin_masjid') {
            $masjidId = $user->pengguna?->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Pengguna hanya bisa update amil dari masjidnya sendiri
        if ($user->role === 'pengguna') {
            $masjidId = $user->pengguna?->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data amil ini.');
            }
        }

        // Validasi
        $request->validate([
            'masjid_id' => ['required', 'exists:masjid,id'],
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

        // Jika admin masjid atau pengguna, pastikan hanya bisa update untuk masjidnya sendiri
        if (in_array($user->role, ['admin_masjid', 'pengguna'])) {
            $request->merge(['masjid_id' => $user->pengguna?->masjid_id]);
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
                'masjid_id' => $request->masjid_id,
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

        // Admin masjid hanya bisa hapus amil dari masjidnya sendiri
        if ($user->peran === 'admin_masjid') {
            $masjidId = $user->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus data amil ini.');
            }
        }

        // Pengguna hanya bisa hapus amil dari masjidnya sendiri
        if ($user->peran === 'pengguna') {
            $masjidId = $user->masjid_id;
            if ($amil->masjid_id !== $masjidId) {
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
                    \Log::info("Pengguna terkait amil berhasil dihapus: {$amilEmail}");
                }
            }

            // Hapus data amil
            $amil->delete();

            DB::commit();

            \Log::info("Amil berhasil dihapus: {$amilName} ({$amilEmail})");

            return redirect()->route('amil.index')
                ->with('success', "Data amil {$amilName} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error delete amil: ' . $e->getMessage());

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
     * Get amil by masjid (API)
     */
    public function getByMasjid($masjidId)
    {
        $amils = Amil::where('masjid_id', $masjidId)
            ->where('status', 'aktif')
            ->select('id', 'kode_amil', 'nama_lengkap')
            ->get();

        return response()->json($amils);
    }
}