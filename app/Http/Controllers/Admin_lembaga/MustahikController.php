<?php
// app/Http\Controllers\Admin_lembaga\MustahikController.php

namespace App\Http\Controllers\Admin_lembaga;

use App\Http\Controllers\Controller;
use App\Models\Mustahik;
use App\Models\Lembaga;
use App\Models\KategoriMustahik;
use App\Models\Amil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPenerimaan;
use App\Models\TransaksiPenyaluran;
use App\Models\KunjunganMustahik;

class MustahikController extends Controller
{
    public function index(Request $request)
    {
        $query = Mustahik::with(['lembaga', 'kategoriMustahik', 'creator']);
        $user = auth()->user();

        if ($user->peran === 'superadmin') {
            // Superadmin: lihat semua
        } elseif ($user->peran === 'amil') {
            // Amil: hanya lihat mustahik yang dia input sendiri
            $query->where('lembaga_id', $user->lembaga_id)
                ->where('created_by', $user->id);
        } else {
            // Admin lembaga: lihat semua mustahik di lembaganya
            $query->where('lembaga_id', $user->lembaga_id);
        }

        // Search
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->byKategori($request->kategori_id);
        }

        // Filter status verifikasi
        if ($request->filled('status_verifikasi')) {
            $query->byStatus($request->status_verifikasi);
        }

        // Filter is_active
        if ($request->filled('is_active')) {
            if ($request->is_active == '1') {
                $query->active();
            } elseif ($request->is_active == '0') {
                $query->inactive();
            }
        }

        // Filter jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $mustahiks = $query->latest()->paginate(10);

        $kategoris = KategoriMustahik::orderBy('nama')->get();

        $userRole = $user->peran;
        $permissions = [
            'canCreate' => in_array($userRole, ['admin_lembaga', 'amil']),
            'userRole'  => $userRole,
        ];

        $breadcrumbs = [
            'Kelola Mustahik' => route('mustahik.index'),
        ];

        return view('admin-lembaga.mustahik.index', compact('mustahiks', 'kategoris', 'permissions', 'breadcrumbs'));
    }
    public function create()
    {
        // Amil & Admin Lembaga Both can create
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah mustahik.');
        }

        $kategoris = KategoriMustahik::all();
        $provinces = Province::orderBy('name')->get();

        $breadcrumbs = [
            'Kelola Mustahik' => route('mustahik.index'),
            'Tambah Mustahik' => route('mustahik.create')
        ];

        return view('admin-lembaga.mustahik.create', compact('kategoris', 'provinces', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        // Amil & Admin Lembaga Both can create
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah mustahik.');
        }

        $validated = $request->validate([
            'kategori_mustahik_id' => 'required|exists:kategori_mustahik,id',
            'nik' => 'nullable|string|size:16|unique:mustahik,nik',
            'kk' => 'nullable|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date|before:today',
            'tempat_lahir' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:10',
            'kode_pos' => 'nullable|string|max:10',
            'pekerjaan' => 'nullable|string|max:255',
            'penghasilan_perbulan' => 'nullable|numeric|min:0',
            'jumlah_tanggungan' => 'nullable|integer|min:0',
            'status_rumah' => 'nullable|in:milik_sendiri,kontrak,menumpang,lainnya',
            'kondisi_kesehatan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_rumah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumen_lainnya' => 'nullable|array|max:5',
            'dokumen_lainnya.*' => 'image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('foto_ktp')) {
            $filename = time() . '_ktp_' . uniqid() . '.' . $request->file('foto_ktp')->getClientOriginalExtension();
            $validated['foto_ktp'] = $request->file('foto_ktp')->storeAs('dokumen/mustahik/ktp', $filename, 'public');
        }

        if ($request->hasFile('foto_kk')) {
            $filename = time() . '_kk_' . uniqid() . '.' . $request->file('foto_kk')->getClientOriginalExtension();
            $validated['foto_kk'] = $request->file('foto_kk')->storeAs('dokumen/mustahik/kk', $filename, 'public');
        }

        if ($request->hasFile('foto_rumah')) {
            $filename = time() . '_rumah_' . uniqid() . '.' . $request->file('foto_rumah')->getClientOriginalExtension();
            $validated['foto_rumah'] = $request->file('foto_rumah')->storeAs('dokumen/mustahik/rumah', $filename, 'public');
        }

        $dokumenPaths = [];
        if ($request->hasFile('dokumen_lainnya')) {
            foreach ($request->file('dokumen_lainnya') as $dokumen) {
                $filename = time() . '_doc_' . uniqid() . '.' . $dokumen->getClientOriginalExtension();
                $path = $dokumen->storeAs('dokumen/mustahik/lainnya', $filename, 'public');
                $dokumenPaths[] = $path;
            }
        }

        if (!empty($dokumenPaths)) {
            $validated['dokumen_lainnya'] = $dokumenPaths;
        }

        // Set additional fields
        $validated['lembaga_id'] = auth()->user()->lembaga_id;
        $validated['no_registrasi'] = $this->generateNoRegistrasi();
        $validated['created_by'] = auth()->id();
        $validated['tanggal_registrasi'] = now();
        $validated['is_active'] = true;

        // Status default berdasarkan role
        if (auth()->user()->peran === 'admin_lembaga') {
            // Admin langsung verifikasi
            $validated['status_verifikasi'] = 'verified';
            $validated['verified_by'] = auth()->id();
            $validated['verified_at'] = now();
        } else {
            // Amil: status pending, perlu diverifikasi oleh admin
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
        }

        Mustahik::create($validated);

        $message = auth()->user()->peran === 'admin_lembaga'
            ? 'Data mustahik berhasil ditambahkan dan langsung terverifikasi.'
            : 'Data mustahik berhasil ditambahkan. Menunggu verifikasi admin lembaga.';

        return redirect()->route('mustahik.index')->with('success', $message);
    }

    public function show(Mustahik $mustahik)
    {
        // Load relasi yang dibutuhkan
        $mustahik->load(['kategoriMustahik', 'verifiedBy']);

        $user = auth()->user();

        // ── Permissions ───────────────────────────────────────────────────────
        // SALIN blok $permissions dari show() lama jika berbeda.
        // Ini contoh yang sesuai dengan role sistem Anda:
        $permissions = [
            'canEdit'          => in_array($user->peran, ['admin_lembaga', 'amil']),
            'canDelete'        => $user->peran === 'admin_lembaga',
            'canVerify'        => $user->peran === 'admin_lembaga',
            'canDistribute'    => in_array($user->peran, ['admin_lembaga', 'amil']),
            'canScheduleVisit' => in_array($user->peran, ['admin_lembaga', 'amil']),
        ];

        // ── Riwayat Penyaluran ────────────────────────────────────────────────
        // Gunakan where() langsung — JANGAN pakai scope byLembaga() karena
        // scope itu membaca Auth user, bukan parameter yang kita berikan.
        $riwayatPenyaluran = TransaksiPenyaluran::with(['jenisZakat', 'programZakat'])
            ->where('mustahik_id', $mustahik->id)
            ->where('lembaga_id', $mustahik->lembaga_id)
            ->orderByDesc('tanggal_penyaluran')
            ->get();

        // ── Riwayat Kunjungan ─────────────────────────────────────────────────
        $riwayatKunjungan = KunjunganMustahik::with(['amil.pengguna'])
            ->where('mustahik_id', $mustahik->id)
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        $breadcrumbs = [
            'Kelola Mustahik' => route('mustahik.index'),
            'Detail Mustahik' => route('mustahik.show', $mustahik)
        ];

        return view('admin-lembaga.mustahik.show', compact(
            'mustahik',
            'permissions',
            'riwayatPenyaluran',
            'riwayatKunjungan',
            'breadcrumbs'
        ));
    }

    public function edit(Mustahik $mustahik)
    {
        // Amil & Admin Lembaga Both can edit
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit mustahik.');
        }

        if (auth()->user()->peran !== 'superadmin' && $mustahik->lembaga_id !== auth()->user()->lembaga_id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Check permission untuk edit
        if (!$mustahik->canBeEditedBy(auth()->id(), auth()->user()->peran)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $kategoris = KategoriMustahik::all();
        $provinces = Province::orderBy('name')->get();

        $cities = $mustahik->provinsi_kode
            ? City::where('province_code', $mustahik->provinsi_kode)->orderBy('name')->get()
            : collect();

        $districts = $mustahik->kota_kode
            ? District::where('city_code', $mustahik->kota_kode)->orderBy('name')->get()
            : collect();

        $villages = $mustahik->kecamatan_kode
            ? Village::where('district_code', $mustahik->kecamatan_kode)->orderBy('name')->get()
            : collect();

        $breadcrumbs = [
            'Kelola Mustahik' => route('mustahik.index'),
            'Edit Mustahik' => route('mustahik.show', $mustahik)
        ];

        return view('admin-lembaga.mustahik.edit', compact('mustahik', 'kategoris', 'provinces', 'cities', 'districts', 'villages', 'breadcrumbs'));
    }

    public function update(Request $request, Mustahik $mustahik)
    {
        // Amil & Admin Lembaga Both can update
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit mustahik.');
        }

        if (auth()->user()->peran !== 'superadmin' && $mustahik->lembaga_id !== auth()->user()->lembaga_id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Check permission untuk update
        if (!$mustahik->canBeEditedBy(auth()->id(), auth()->user()->peran)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $validated = $request->validate([
            'kategori_mustahik_id' => 'required|exists:kategori_mustahik,id',
            'nik' => [
                'nullable',
                'string',
                'size:16',
                Rule::unique('mustahik', 'nik')->ignore($mustahik->id)
            ],
            'kk' => 'nullable|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date|before:today',
            'tempat_lahir' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:10',
            'kode_pos' => 'nullable|string|max:10',
            'pekerjaan' => 'nullable|string|max:255',
            'penghasilan_perbulan' => 'nullable|numeric|min:0',
            'jumlah_tanggungan' => 'nullable|integer|min:0',
            'status_rumah' => 'nullable|in:milik_sendiri,kontrak,menumpang,lainnya',
            'kondisi_kesehatan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_rumah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumen_lainnya' => 'nullable|array|max:5',
            'dokumen_lainnya.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'existing_dokumen' => 'nullable|array',
            'existing_dokumen.*' => 'string',
            'remove_foto_ktp' => 'nullable|string',
            'remove_foto_kk' => 'nullable|string',
            'remove_foto_rumah' => 'nullable|string',
            'remove_dokumen_lainnya' => 'nullable|string', // Changed to string
        ]);

        // Jika amil mengedit, reset status ke pending (karena ada perubahan)
        if (auth()->user()->peran === 'amil') {
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
            $validated['alasan_penolakan'] = null;
        }

        // Handle file removals untuk foto tunggal
        if ($request->filled('remove_foto_ktp') && $request->remove_foto_ktp == '1') {
            if ($mustahik->foto_ktp) {
                Storage::disk('public')->delete($mustahik->foto_ktp);
            }
            $validated['foto_ktp'] = null;
        }

        if ($request->filled('remove_foto_kk') && $request->remove_foto_kk == '1') {
            if ($mustahik->foto_kk) {
                Storage::disk('public')->delete($mustahik->foto_kk);
            }
            $validated['foto_kk'] = null;
        }

        if ($request->filled('remove_foto_rumah') && $request->remove_foto_rumah == '1') {
            if ($mustahik->foto_rumah) {
                Storage::disk('public')->delete($mustahik->foto_rumah);
            }
            $validated['foto_rumah'] = null;
        }

        // Handle file uploads untuk foto tunggal
        if ($request->hasFile('foto_ktp')) {
            // Remove old file if exists
            if ($mustahik->foto_ktp) {
                Storage::disk('public')->delete($mustahik->foto_ktp);
            }

            $filename = time() . '_ktp_' . uniqid() . '.' . $request->file('foto_ktp')->getClientOriginalExtension();
            $validated['foto_ktp'] = $request->file('foto_ktp')->storeAs('dokumen/mustahik/ktp', $filename, 'public');
        }

        if ($request->hasFile('foto_kk')) {
            // Remove old file if exists
            if ($mustahik->foto_kk) {
                Storage::disk('public')->delete($mustahik->foto_kk);
            }

            $filename = time() . '_kk_' . uniqid() . '.' . $request->file('foto_kk')->getClientOriginalExtension();
            $validated['foto_kk'] = $request->file('foto_kk')->storeAs('dokumen/mustahik/kk', $filename, 'public');
        }

        if ($request->hasFile('foto_rumah')) {
            // Remove old file if exists
            if ($mustahik->foto_rumah) {
                Storage::disk('public')->delete($mustahik->foto_rumah);
            }

            $filename = time() . '_rumah_' . uniqid() . '.' . $request->file('foto_rumah')->getClientOriginalExtension();
            $validated['foto_rumah'] = $request->file('foto_rumah')->storeAs('dokumen/mustahik/rumah', $filename, 'public');
        }

        // Handle dokumen_lainnya
        $currentDokumen = $mustahik->dokumen_lainnya ?? [];

        // Process removed documents
        if ($request->filled('remove_dokumen_lainnya')) {
            $removedIndices = json_decode($request->remove_dokumen_lainnya, true) ?? [];

            foreach ($removedIndices as $index) {
                if (isset($currentDokumen[$index])) {
                    // Delete file from storage
                    Storage::disk('public')->delete($currentDokumen[$index]);
                    unset($currentDokumen[$index]);
                }
            }

            // Re-index array
            $currentDokumen = array_values($currentDokumen);
        }

        // Process existing documents that weren't removed
        if ($request->has('existing_dokumen')) {
            $existingDokumen = $request->existing_dokumen;

            // Only keep documents that are in the existing_dokumen array
            $currentDokumen = array_values(array_intersect($currentDokumen, $existingDokumen));
        }

        // Handle new dokumen uploads
        if ($request->hasFile('dokumen_lainnya')) {
            foreach ($request->file('dokumen_lainnya') as $dokumen) {
                // Check if we haven't exceeded the limit
                if (count($currentDokumen) >= 5) {
                    break;
                }

                $filename = time() . '_doc_' . uniqid() . '.' . $dokumen->getClientOriginalExtension();
                $path = $dokumen->storeAs('dokumen/mustahik/lainnya', $filename, 'public');
                $currentDokumen[] = $path;
            }
        }

        // Update validated data with processed documents
        $validated['dokumen_lainnya'] = !empty($currentDokumen) ? $currentDokumen : null;

        // Update data
        $mustahik->update($validated);

        $message = auth()->user()->peran === 'amil'
            ? 'Data mustahik berhasil diperbarui. Status kembali menjadi pending dan menunggu verifikasi admin.'
            : 'Data mustahik berhasil diperbarui.';

        return redirect()->route('mustahik.index', $mustahik->uuid)->with('success', $message);
    }

    public function destroy(Mustahik $mustahik)
    {
        // Amil & Admin Lembaga Both can delete
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus mustahik.');
        }

        if (auth()->user()->peran !== 'superadmin' && $mustahik->lembaga_id !== auth()->user()->lembaga_id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Check permission untuk delete
        if (!$mustahik->canBeDeletedBy(auth()->id(), auth()->user()->peran)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        $mustahik->clearAllDocuments();
        $mustahik->delete();

        return redirect()->route('mustahik.index')
            ->with('success', 'Data mustahik berhasil dihapus.');
    }

    public function verify(Mustahik $mustahik)
    {
        // Only Admin Lembaga can verify
        if (auth()->user()->peran !== 'admin_lembaga') {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses'], 403);
        }

        if (auth()->user()->peran !== 'superadmin' && $mustahik->lembaga_id !== auth()->user()->lembaga_id) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses ke data ini'], 403);
        }

        // Check permission untuk verify
        if (!$mustahik->canBeVerifiedBy(auth()->user()->peran)) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat memverifikasi data ini'], 403);
        }

        $mustahik->verify(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Mustahik berhasil diverifikasi',
            'status_badge_html' => $mustahik->status_badge
        ]);
    }

    public function reject(Request $request, Mustahik $mustahik)
    {
        // Only Admin Lembaga can reject
        if (auth()->user()->peran !== 'admin_lembaga') {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses'], 403);
        }

        if (auth()->user()->peran !== 'superadmin' && $mustahik->lembaga_id !== auth()->user()->lembaga_id) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses ke data ini'], 403);
        }

        // Check permission untuk reject
        if (!$mustahik->canBeRejectedBy(auth()->user()->peran)) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menolak data ini'], 403);
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        $mustahik->reject($request->alasan_penolakan, auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi mustahik ditolak',
            'status_badge_html' => $mustahik->status_badge
        ]);
    }

    public function toggleActive(Mustahik $mustahik)
    {
        // Only Admin Lembaga can toggle active
        if (auth()->user()->peran !== 'admin_lembaga') {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses'], 403);
        }

        if (auth()->user()->peran !== 'superadmin' && $mustahik->lembaga_id !== auth()->user()->lembaga_id) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses ke data ini'], 403);
        }

        // Check permission untuk toggle active
        if (!$mustahik->canBeToggledActiveBy(auth()->user()->peran)) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat mengubah status aktif data ini'], 403);
        }

        if ($mustahik->is_active) {
            $mustahik->deactivate();
            $message = 'Mustahik berhasil dinonaktifkan';
        } else {
            $mustahik->activate();
            $message = 'Mustahik berhasil diaktifkan';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => $mustahik->is_active,
            'active_badge_html' => $mustahik->active_badge
        ]);
    }

    private function generateNoRegistrasi()
    {
        $lembaga = Lembaga::find(auth()->user()->lembaga_id);

        $lastMustahik = Mustahik::where('lembaga_id', auth()->user()->lembaga_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMustahik && $lastMustahik->no_registrasi) {
            $lastNumber = intval(substr($lastMustahik->no_registrasi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // LAMA: sprintf('MUST-%s-%03d', $lembaga->kode_lembaga, $newNumber)
        // → MUST-MSJ20260003-001

        // BARU: ambil hanya bagian nomor dari kode_lembaga (LMBG-2026-0001 → 0001)
        $parts = explode('-', $lembaga->kode_lembaga); // ['LMBG', '2026', '0001']
        $nomorLembaga = end($parts); // '0001'

        return sprintf('MUST-LMBG%s-%03d', $nomorLembaga, $newNumber);
        // → MUST-LMBG0001-001
    }

    public function getMuzakiByAmil(Request $request, $amilId)
    {
        $user     = Auth::user();
        $lembagaId = $user->lembaga_id;
        $isAmil   = $user->peran === 'amil';

        // Pastikan amil milik lembaga ini
        $amil = Amil::where('id', $amilId)
            ->where('lembaga_id', $lembagaId)
            ->firstOrFail();

        // Jika role amil, hanya boleh lihat data miliknya sendiri
        if ($isAmil) {
            $amilSaya = Amil::where('pengguna_id', $user->id)
                ->where('lembaga_id', $lembagaId)
                ->first();

            if (!$amilSaya || $amilSaya->id != $amilId) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
        }

        $query = TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('amil_id', $amilId)
            ->select(
                'muzakki_nama',
                'muzakki_telepon',
                'muzakki_email',
                'muzakki_alamat',
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(CASE WHEN status = "verified" THEN jumlah ELSE 0 END) as total_nominal'),
                DB::raw('MAX(tanggal_transaksi) as transaksi_terakhir')
            )
            ->groupBy('muzakki_nama', 'muzakki_telepon', 'muzakki_email', 'muzakki_alamat');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('muzakki_nama', 'like', "%{$search}%")
                    ->orWhere('muzakki_telepon', 'like', "%{$search}%")
                    ->orWhere('muzakki_email', 'like', "%{$search}%");
            });
        }

        $muzakkis = $query->orderBy('muzakki_nama')->paginate(10);

        return response()->json([
            'success'  => true,
            'amil'     => $amil->only(['id', 'nama_lengkap', 'kode_amil', 'status']),
            'muzakkis' => $muzakkis,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // STEP 0 — Download template Excel
    // ─────────────────────────────────────────────────────────────
public function downloadTemplate()
{
    if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
        abort(403);
    }

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Template Mustahik');

    // ── Header kolom ─────────────────────────────────────────
    $columns = [
        'A' => ['label' => 'Nama Lengkap *',                                         'width' => 28],
        'B' => ['label' => 'NIK (16 digit)',                                          'width' => 20],
        'C' => ['label' => 'No. KK (16 digit)',                                       'width' => 20],
        'D' => ['label' => 'Jenis Kelamin * (L/P)',                                   'width' => 20],
        'E' => ['label' => 'Tanggal Lahir (YYYY-MM-DD)',                              'width' => 24],
        'F' => ['label' => 'Tempat Lahir',                                            'width' => 20],
        'G' => ['label' => 'No. Telepon',                                             'width' => 18],
        'H' => ['label' => 'Alamat *',                                                'width' => 35],
        'I' => ['label' => 'RT/RW (contoh: 01/02)',                                  'width' => 20],
        'J' => ['label' => 'Kode Pos',                                                'width' => 12],
        'K' => ['label' => 'Nama Kategori Mustahik *',                                'width' => 26],
        'L' => ['label' => 'Pekerjaan',                                               'width' => 20],
        'M' => ['label' => 'Penghasilan/Bulan (angka)',                               'width' => 24],
        'N' => ['label' => 'Jumlah Tanggungan (angka)',                               'width' => 24],
        'O' => ['label' => 'Status Rumah (milik_sendiri/kontrak/menumpang/lainnya)',  'width' => 46],
        'P' => ['label' => 'Kondisi Kesehatan',                                       'width' => 24],
        'Q' => ['label' => 'Catatan',                                                 'width' => 30],
    ];

    foreach ($columns as $col => $info) {
        $sheet->getCell($col . '1')->setValue($info['label']);
        $sheet->getStyle($col . '1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1B6CA8']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'AAAAAA']]],
        ]);
        $sheet->getColumnDimension($col)->setWidth($info['width']);
    }

    $sheet->getRowDimension(1)->setRowHeight(38);

    // ── Format kolom NIK, KK, No. Telepon, Tanggal Lahir sebagai TEXT ─────
    // Wajib dilakukan SEBELUM mengisi nilai contoh agar tidak di-convert Excel
    $textFormat = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT;

    // NIK (B), No. KK (C), No. Telepon (G), Tanggal Lahir (E) → semua TEXT
    foreach (['B', 'C', 'E', 'G'] as $col) {
        $sheet->getStyle($col . '1:' . $col . '1000')
              ->getNumberFormat()
              ->setFormatCode($textFormat);
    }

    // ── Baris contoh ─────────────────────────────────────────
    // Gunakan TYPE_STRING agar nilai tidak diinterpretasi ulang oleh PhpSpreadsheet
    $examples = [
        'A' => ['Siti Rahmawati',                              \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'B' => ['3201234567890001',                            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'C' => ['3201234567890002',                            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'D' => ['P',                                           \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'E' => ['1980-05-15',                                  \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'F' => ['Bandung',                                     \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'G' => ['081234567890',                                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'H' => ['Jl. Merdeka No. 10 RT 01/02 Kel. Cibeunying', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'I' => ['01/02',                                       \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'J' => ['40132',                                       \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'K' => ['Fakir Miskin',                                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'L' => ['Buruh Harian',                                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'M' => ['500000',                                      \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC],
        'N' => ['3',                                           \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC],
        'O' => ['kontrak',                                     \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'P' => ['Sehat',                                       \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
        'Q' => ['Perlu bantuan sembako',                       \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING],
    ];

    foreach ($examples as $col => [$val, $type]) {
        $sheet->getCell($col . '2')->setValueExplicit($val, $type);
        $sheet->getStyle($col . '2')->applyFromArray([
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F4FD']],
            'alignment' => ['vertical' => 'center'],
            'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CCCCCC']]],
        ]);
    }

    $sheet->getRowDimension(2)->setRowHeight(20);

    // ── Freeze header ─────────────────────────────────────────
    $sheet->freezePane('A2');

    // ── Sheet Instruksi ───────────────────────────────────────
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Instruksi');

    $instruksi = [
        ['INSTRUKSI PENGISIAN TEMPLATE IMPORT MUSTAHIK',                                    true,  13],
        ['',                                                                                 false, 11],
        ['KOLOM WAJIB DIISI (bertanda *):',                                                  true,  11],
        ['1. Nama Lengkap — Nama lengkap mustahik',                                          false, 10],
        ['2. Jenis Kelamin — Isi dengan L (Laki-laki) atau P (Perempuan)',                   false, 10],
        ['3. Alamat — Alamat lengkap mustahik',                                              false, 10],
        ['4. Nama Kategori Mustahik — Harus sesuai dengan nama kategori yang ada di sistem', false, 10],
        ['',                                                                                 false, 10],
        ['KOLOM OPSIONAL:',                                                                  true,  11],
        ['- NIK: 16 digit angka (tanpa spasi/titik) — kolom otomatis format TEXT',           false, 10],
        ['- No. KK: 16 digit angka — kolom otomatis format TEXT',                            false, 10],
        ['- No. Telepon: Tulis lengkap dengan 0 di depan, contoh: 081234567890',             false, 10],
        ['- Tanggal Lahir: Format YYYY-MM-DD (contoh: 1990-01-25) — kolom otomatis TEXT',    false, 10],
        ['- RT/RW: Format XX/XX (contoh: 01/02)',                                            false, 10],
        ['- Penghasilan/Bulan: Angka saja tanpa titik/koma (contoh: 500000)',                false, 10],
        ['- Jumlah Tanggungan: Angka saja (contoh: 3)',                                      false, 10],
        ['- Status Rumah: milik_sendiri / kontrak / menumpang / lainnya',                    false, 10],
        ['',                                                                                 false, 10],
        ['CATATAN PENTING:',                                                                 true,  11],
        ['- Jangan mengubah urutan atau nama kolom header (baris ke-1)',                     false, 10],
        ['- Data dimulai dari baris ke-2 (baris contoh boleh dihapus)',                     false, 10],
        ['- Maksimal 500 baris per sekali import',                                           false, 10],
        ['- Format file harus .xlsx atau .xls',                                              false, 10],
        ['- Nama kategori mustahik harus PERSIS sama dengan yang ada di sistem',             false, 10],
    ];

    foreach ($instruksi as $i => [$text, $bold, $size]) {
        $row = $i + 1;
        $sheet2->getCell('A' . $row)->setValue($text);
        $sheet2->getStyle('A' . $row)->applyFromArray([
            'font'      => ['bold' => $bold, 'size' => $size],
            'alignment' => ['vertical' => 'center'],
        ]);
        $sheet2->getRowDimension($row)->setRowHeight(18);
    }

    $sheet2->getColumnDimension('A')->setWidth(80);

    // ── Aktifkan sheet pertama ────────────────────────────────
    $spreadsheet->setActiveSheetIndex(0);

    // ── Stream response ───────────────────────────────────────
    $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'template_import_mustahik.xlsx';

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
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403);
        }

        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls|max:512000',
        ], [
            'file_import.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_import.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_import.max'      => 'Ukuran file maksimal 500 MB.',
        ]);

        $file = $request->file('file_import');

        // Simpan file sementara di storage/app/imports/
        $tmpFilename = 'import_mustahik_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $tmpPath     = $file->storeAs('imports', $tmpFilename);          // storage/app/imports/...

        // Baca header & 5 baris pertama untuk pemetaan kolom
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

            // Ambil header baris 1
            $excelHeaders = [];
            foreach ($sheet->getRowIterator(1, 1) as $row) {
                foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                    $val = trim((string) $cell->getValue());
                    if ($val !== '') {
                        $excelHeaders[] = $val;
                    }
                }
            }

            if (empty($excelHeaders)) {
                \Illuminate\Support\Facades\Storage::delete($tmpPath);
                return back()->with('error', 'Baris header tidak ditemukan di file Excel.');
            }

            // Ambil maks 10 baris pertama sebagai preview (baris 2–11)
            $previewRows = [];
            $maxPreview  = min($highestRow, 101); // baris 2 s.d. 101 (100 baris preview)
            foreach ($sheet->getRowIterator(2, $maxPreview) as $row) {
                $rowData = [];
                foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                    $rowData[] = $cell->getValue();
                }
                // Hanya simpan baris yang tidak seluruhnya kosong
                if (count(array_filter($rowData, fn($v) => $v !== null && $v !== '')) > 0) {
                    $previewRows[] = $rowData;
                }
            }

            $totalRows = $highestRow - 1; // minus header

            if ($totalRows > 5000) {
                \Illuminate\Support\Facades\Storage::delete($tmpPath);
                return back()->with('error', 'Jumlah data melebihi batas maksimal 500 baris per sekali import.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Storage::delete($tmpPath);
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        // Simpan info ke session
        session([
            'import_mustahik' => [
                'tmp_path'      => $tmpPath,
                'excel_headers' => $excelHeaders,
                'preview_rows'  => $previewRows,
                'total_rows'    => $totalRows,
                'lembaga_id'    => auth()->user()->lembaga_id,
                'uploaded_by'   => auth()->id(),
                'uploaded_at'   => now()->toDateTimeString(),
            ],
        ]);

        return redirect()->route('mustahik.import.pemetaan');
    }


    // ─────────────────────────────────────────────────────────────
    // STEP 2 — Tampilkan halaman pemetaan kolom + preview data
    // ─────────────────────────────────────────────────────────────
    public function pemetaanImport(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403);
        }

        $importSession = session('import_mustahik');
        if (!$importSession || !isset($importSession['tmp_path'])) {
            return redirect()->route('mustahik.index')
                ->with('error', 'Sesi import tidak ditemukan. Silakan upload ulang file.');
        }

        // Definisi kolom sistem yang bisa dipetakan
        $systemColumns = [
            // field_key => [label, required]
            'nama_lengkap'         => ['label' => 'Nama Lengkap',              'required' => true],
            'nik'                  => ['label' => 'NIK (16 digit)',             'required' => false],
            'kk'                   => ['label' => 'No. KK (16 digit)',          'required' => false],
            'jenis_kelamin'        => ['label' => 'Jenis Kelamin (L/P)',        'required' => true],
            'tanggal_lahir'        => ['label' => 'Tanggal Lahir',              'required' => false],
            'tempat_lahir'         => ['label' => 'Tempat Lahir',               'required' => false],
            'telepon'              => ['label' => 'No. Telepon',                'required' => false],
            'alamat'               => ['label' => 'Alamat',                     'required' => true],
            'rt_rw'                => ['label' => 'RT/RW',                      'required' => false],
            'kode_pos'             => ['label' => 'Kode Pos',                   'required' => false],
            'kategori_mustahik'    => ['label' => 'Nama Kategori Mustahik',     'required' => true],
            'pekerjaan'            => ['label' => 'Pekerjaan',                  'required' => false],
            'penghasilan_perbulan' => ['label' => 'Penghasilan/Bulan',          'required' => false],
            'jumlah_tanggungan'    => ['label' => 'Jumlah Tanggungan',          'required' => false],
            'status_rumah'         => ['label' => 'Status Rumah',               'required' => false],
            'kondisi_kesehatan'    => ['label' => 'Kondisi Kesehatan',          'required' => false],
            'catatan'              => ['label' => 'Catatan',                    'required' => false],
        ];

        // Auto-mapping: cocokkan header Excel dengan field sistem
        $autoMapping = [];
        foreach ($importSession['excel_headers'] as $idx => $excelHeader) {
            $normalized = strtolower(trim(preg_replace('/[^a-z0-9]/i', '_', $excelHeader)));
            foreach (array_keys($systemColumns) as $fieldKey) {
                if ($normalized === $fieldKey || str_contains($normalized, $fieldKey) || str_contains($fieldKey, explode('_', $normalized)[0])) {
                    if (!in_array($fieldKey, $autoMapping)) {
                        $autoMapping[$idx] = $fieldKey;
                        break;
                    }
                }
            }
        }

        $kategoris = \App\Models\KategoriMustahik::orderBy('nama')->get();

        $breadcrumbs = [
            'Kelola Mustahik'   => route('mustahik.index'),
            'Pemetaan Kolom Import' => route('mustahik.import.pemetaan'),
        ];

        return view('admin-lembaga.mustahik.import-pemetaan', compact(
            'importSession',
            'systemColumns',
            'autoMapping',
            'kategoris',
            'breadcrumbs'
        ));
    }


  // ─────────────────────────────────────────────────────────────
    // STEP 3 — Proses import sesungguhnya
    // ─────────────────────────────────────────────────────────────
    public function prosesImport(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403);
        }

        $importSession = session('import_mustahik');
        if (!$importSession || !isset($importSession['tmp_path'])) {
            return redirect()->route('mustahik.index')
                ->with('error', 'Sesi import tidak ditemukan. Silakan upload ulang file.');
        }

        $request->validate([
            'mapping'   => 'required|array',
            'mapping.*' => 'nullable|string',
        ]);

        $mapping = $request->input('mapping');

        // Pastikan kolom wajib terpetakan
        $requiredFields = ['nama_lengkap', 'jenis_kelamin', 'alamat', 'kategori_mustahik'];
        $mappedFields   = array_values(array_filter($mapping));
        foreach ($requiredFields as $rf) {
            if (!in_array($rf, $mappedFields)) {
                return back()
                    ->with('error', "Kolom wajib \"{$rf}\" belum dipetakan.")
                    ->withInput();
            }
        }

        $fullPath = storage_path('app/' . $importSession['tmp_path']);
        if (!file_exists($fullPath)) {
            session()->forget('import_mustahik');
            return redirect()->route('mustahik.index')
                ->with('error', 'File import sudah tidak tersedia. Silakan upload ulang.');
        }

        // Naikkan limit PHP untuk file besar
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $lembagaId = auth()->user()->lembaga_id;
        $userId    = auth()->id();
        $peran     = auth()->user()->peran;

        // ── Preload lookup kategori (lowercase → id) ─────────────
        $kategoris = \App\Models\KategoriMustahik::all()
            ->mapWithKeys(fn($k) => [
                strtolower(trim(preg_replace('/\s+/', ' ', $k->nama))) => $k->id,
            ])->toArray();

        // ── Preload semua NIK yang sudah ada di DB lembaga ini ───
        // Disimpan sebagai flip array agar lookup O(1)
        $existingNiks = \App\Models\Mustahik::where('lembaga_id', $lembagaId)
            ->whereNotNull('nik')
            ->pluck('nik')
            ->map(fn($n) => preg_replace('/\D/', '', $n))
            ->filter(fn($n) => strlen($n) === 16)
            ->flip()
            ->toArray();

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        // ── Baca tinggi baris file tanpa muat konten ─────────────
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

        // ── Proses per chunk 100 baris ───────────────────────────
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
                    // Kumpulkan nilai sel per kolom
                    $cells    = [];
                    $colIndex = 0;
                    foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                        $cells[$colIndex] = $cell->getValue();
                        $colIndex++;
                    }

                    // Lewati baris kosong sepenuhnya
                    if (empty(array_filter($cells, fn($v) => $v !== null && $v !== ''))) {
                        continue;
                    }

                    // Petakan kolom Excel → field sistem
                    $rowData = [];
                    foreach ($mapping as $excelColIdx => $systemField) {
                        if (!$systemField) continue;
                        $rowData[$systemField] = isset($cells[$excelColIdx])
                            ? trim((string) $cells[$excelColIdx])
                            : null;
                    }

                    // ── Validasi format & kelengkapan baris ──────
                    $rowErrors = $this->validateImportRow($rowData, $rowIndex, $kategoris);
                    if (!empty($rowErrors)) {
                        $errors[] = "Baris {$rowIndex}: " . implode(', ', $rowErrors);
                        $skipped++;
                        continue;
                    }

                    // ── Cek duplikat NIK ke DB (dan dalam file) ──
                    $nikBersih = $this->cleanNik($rowData['nik'] ?? null);
                    if ($nikBersih !== null) {
                        if (isset($existingNiks[$nikBersih])) {
                            // NIK sudah ada di DB atau sudah diproses di baris sebelumnya
                            $errors[] = "Baris {$rowIndex}: NIK {$nikBersih} sudah terdaftar, baris dilewati.";
                            $skipped++;
                            continue;
                        }
                        // Tandai NIK ini sudah dipakai agar duplikat dalam file juga tertangkap
                        $existingNiks[$nikBersih] = true;
                    }

                    // ── Siapkan data insert ──────────────────────
                    $insertData = [
                        'uuid'                 => \Illuminate\Support\Str::uuid(),
                        'lembaga_id'           => $lembagaId,
                        'no_registrasi'        => $this->generateNoRegistrasi(),
                        'nama_lengkap'         => $rowData['nama_lengkap'],
                        'jenis_kelamin'        => strtoupper($rowData['jenis_kelamin']),
                        'alamat'               => $rowData['alamat'],
                        'kategori_mustahik_id' => $kategoris[strtolower(trim(preg_replace('/\s+/', ' ', $rowData['kategori_mustahik'])))],
                        'nik'                  => $nikBersih,
                        'kk'                   => $this->cleanNik($rowData['kk'] ?? null),
                        'tanggal_lahir'        => $this->parseDate($rowData['tanggal_lahir'] ?? null),
                        'tempat_lahir'         => $rowData['tempat_lahir'] ?? null,
                        'telepon'              => $rowData['telepon'] ?? null,
                        'rt_rw'                => $rowData['rt_rw'] ?? null,
                        'kode_pos'             => $rowData['kode_pos'] ?? null,
                        'pekerjaan'            => $rowData['pekerjaan'] ?? null,
                        'penghasilan_perbulan' => is_numeric($rowData['penghasilan_perbulan'] ?? null)
                            ? (float) $rowData['penghasilan_perbulan']
                            : null,
                        'jumlah_tanggungan'    => is_numeric($rowData['jumlah_tanggungan'] ?? null)
                            ? (int) $rowData['jumlah_tanggungan']
                            : null,
                        'status_rumah'         => $this->normalizeStatusRumah($rowData['status_rumah'] ?? null),
                        'kondisi_kesehatan'    => $rowData['kondisi_kesehatan'] ?? null,
                        'catatan'              => $rowData['catatan'] ?? null,
                        'is_active'            => true,
                        'tanggal_registrasi'   => now()->toDateString(),
                        'created_by'           => $userId,
                    ];

                    // Status verifikasi berdasarkan role
                    if ($peran === 'admin_lembaga') {
                        $insertData['status_verifikasi'] = 'verified';
                        $insertData['verified_by']       = $userId;
                        $insertData['verified_at']       = now();
                    } else {
                        $insertData['status_verifikasi'] = 'pending';
                    }

                    \App\Models\Mustahik::create($insertData);
                    $imported++;
                }

                // Bebaskan memori setelah tiap chunk
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet, $reader);
                gc_collect_cycles();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }

        // Bersihkan file sementara & session
        \Illuminate\Support\Facades\Storage::delete($importSession['tmp_path']);
        session()->forget('import_mustahik');

        $message = "Import selesai. {$imported} data berhasil diimport.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('mustahik.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }
    // ─────────────────────────────────────────────────────────────
    // Cancel import — hapus file sementara & session
    // ─────────────────────────────────────────────────────────────
    public function batalImport()
    {
        $importSession = session('import_mustahik');
        if ($importSession && isset($importSession['tmp_path'])) {
            \Illuminate\Support\Facades\Storage::delete($importSession['tmp_path']);
        }
        session()->forget('import_mustahik');

        return redirect()->route('mustahik.index')->with('info', 'Import dibatalkan.');
    }
 
 
// ─────────────────────────────────────────────────────────────
// PRIVATE HELPERS
// ─────────────────────────────────────────────────────────────

    /** Validasi satu baris data import, return array of error strings */
    private function validateImportRow(array $row, int $rowIndex, array $kategoris): array
    {
        $errors = [];

        // Wajib: nama_lengkap
        if (empty($row['nama_lengkap'])) {
            $errors[] = 'nama_lengkap kosong';
        }

        // Wajib: jenis_kelamin
        if (empty($row['jenis_kelamin'])) {
            $errors[] = 'jenis_kelamin kosong';
        } elseif (!in_array(strtoupper($row['jenis_kelamin']), ['L', 'P'])) {
            $errors[] = 'jenis_kelamin harus L atau P';
        }

        // Wajib: alamat
        if (empty($row['alamat'])) {
            $errors[] = 'alamat kosong';
        }

        // Wajib: kategori_mustahik
        if (empty($row['kategori_mustahik'])) {
            $errors[] = 'kategori_mustahik kosong';
        } else {
            $keyKategori = strtolower(trim(preg_replace('/\s+/', ' ', $row['kategori_mustahik'])));
            if (!isset($kategoris[$keyKategori])) {
                $errors[] = 'kategori_mustahik "' . $row['kategori_mustahik'] . '" tidak ditemukan di sistem';
            }
        }

        // NIK: harus 16 digit jika diisi
        if (!empty($row['nik'])) {
            $nik = preg_replace('/\D/', '', $row['nik']);
            if (strlen($nik) !== 16) {
                $errors[] = 'NIK harus 16 digit angka';
            }
        }

        // Tanggal lahir
        if (!empty($row['tanggal_lahir'])) {
            if (!$this->parseDate($row['tanggal_lahir'])) {
                $errors[] = 'format tanggal_lahir tidak valid (gunakan YYYY-MM-DD)';
            }
        }

        // Penghasilan
        if (!empty($row['penghasilan_perbulan']) && !is_numeric($row['penghasilan_perbulan'])) {
            $errors[] = 'penghasilan_perbulan harus berupa angka';
        }

        // Jumlah tanggungan
        if (!empty($row['jumlah_tanggungan']) && !is_numeric($row['jumlah_tanggungan'])) {
            $errors[] = 'jumlah_tanggungan harus berupa angka';
        }

        return $errors;
    }

    /** Bersihkan NIK/KK: hanya angka, max 16 */
    private function cleanNik(?string $value): ?string
    {
        if ($value === null || $value === '') return null;
        $cleaned = preg_replace('/\D/', '', $value);
        return strlen($cleaned) >= 1 ? substr($cleaned, 0, 16) : null;
    }

    private function parseDate(?string $value): ?string
    {
        if ($value === null || $value === '') return null;

        // Excel serial date (angka seperti 44927)
        if (is_numeric($value)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$value);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Format yang didukung — urutan dari yang paling spesifik
        $formats = ['Y-m-d', 'Y/m/d', 'd/m/Y', 'd-m-Y', 'm/d/Y'];
        foreach ($formats as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, trim($value));
            if ($dt && $dt->format($fmt) === trim($value)) {
                return $dt->format('Y-m-d');
            }
        }

        // Fallback strtotime
        $ts = strtotime($value);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    /** Normalkan nilai status_rumah */
    private function normalizeStatusRumah(?string $value): ?string
    {
        if ($value === null || $value === '') return null;
        $allowed = ['milik_sendiri', 'kontrak', 'menumpang', 'lainnya'];
        $lower   = strtolower(trim($value));
        return in_array($lower, $allowed) ? $lower : 'lainnya';
    }

    public function cekKategori(Request $request)
{
    if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
        abort(403);
    }
 
    $request->validate([
        'values'   => 'required|array|max:100',
        'values.*' => 'string|max:255',
    ]);
 
    // Normalisasi nama kategori di DB (lowercase + trim + spasi ganda dihapus)
    $existingNormalized = \App\Models\KategoriMustahik::all()
        ->map(fn($k) => strtolower(trim(preg_replace('/\s+/', ' ', $k->nama))))
        ->toArray();
 
    $notFound = [];
 
    foreach ($request->values as $val) {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $val)));
        if (!in_array($normalized, $existingNormalized)) {
            $notFound[] = $val; // kembalikan nilai ASLI agar mudah dibaca user
        }
    }
 
    return response()->json([
        'not_found' => $notFound,
        'found'     => count($request->values) - count($notFound),
        'total'     => count($request->values),
    ]);
}
 
 
/**
 * AJAX — Cek apakah NIK dari file Excel sudah ada di database mustahik.
 *
 * POST body (JSON) : { "values": ["3201234567890001", ...] }
 * Response (JSON)  : { "duplicates": ["3201234567890001"], "total": 1 }
 */
public function cekNik(Request $request)
{
    if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
        abort(403);
    }
 
    $request->validate([
        'values'   => 'required|array|max:500',
        'values.*' => 'string|max:20',
    ]);
 
    // Bersihkan input: ambil digit saja
    $cleaned = collect($request->values)
        ->map(fn($v) => preg_replace('/\D/', '', $v))
        ->filter(fn($v) => strlen($v) >= 10)
        ->unique()
        ->values()
        ->toArray();
 
    if (empty($cleaned)) {
        return response()->json(['duplicates' => [], 'total' => 0]);
    }
 
    // Cek yang sudah ada di DB (scope per lembaga jika perlu)
    $existing = \App\Models\Mustahik::whereIn('nik', $cleaned)
        ->where('lembaga_id', auth()->user()->lembaga_id)
        ->pluck('nik')
        ->toArray();
 
    return response()->json([
        'duplicates' => $existing,
        'total'      => count($existing),
    ]);
}

// ─────────────────────────────────────────────────────────────
    // EXPORT — Query builder (tanpa paginate, ambil SEMUA data)
    // ─────────────────────────────────────────────────────────────
    private function buildExportQuery(Request $request)
    {
        $user  = auth()->user();
        $query = Mustahik::with(['kategoriMustahik'])->orderBy('nama_lengkap');

        if ($user->peran === 'superadmin') {
            // semua
        } elseif ($user->peran === 'amil') {
            $query->where('lembaga_id', $user->lembaga_id)
                  ->where('created_by', $user->id);
        } else {
            $query->where('lembaga_id', $user->lembaga_id);
        }

        if ($request->filled('q'))                 $query->search($request->q);
        if ($request->filled('kategori_id'))        $query->byKategori($request->kategori_id);
        if ($request->filled('status_verifikasi'))  $query->byStatus($request->status_verifikasi);
        if ($request->filled('is_active'))          $request->is_active == '1' ? $query->active() : $query->inactive();
        if ($request->filled('jenis_kelamin'))      $query->where('jenis_kelamin', $request->jenis_kelamin);

        return $query;
    }

    // ─────────────────────────────────────────────────────────────
    // EXPORT EXCEL
    // ─────────────────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil', 'superadmin'])) {
            abort(403);
        }

        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $mustahiks   = $this->buildExportQuery($request)->get();
        $lembagaNama = auth()->user()->lembaga?->nama ?? 'Semua Lembaga';
        $totalData   = $mustahiks->count();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Mustahik');

        $lastCol    = 'U';
        $headerRow  = 4;
        $dataRowStart = $headerRow + 1; // baris 5

        // ── PAKSA FORMAT TEXT dulu sebelum isi data ──────────────
        // Ini WAJIB dilakukan sebelum setCellValue agar Excel
        // tidak mengkonversi NIK/KK/Telepon/Tanggal ke number
        $textFormat = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT;

        // Kolom D = NIK, E = No. KK, I = No. Telepon, H = Tanggal Lahir
        // Range sampai baris yang cukup besar (totalData + buffer)
        $maxDataRow = $dataRowStart + $totalData + 5;

        foreach (['D', 'E', 'H', 'I'] as $col) {
            $sheet->getStyle($col . $dataRowStart . ':' . $col . $maxDataRow)
                ->getNumberFormat()
                ->setFormatCode($textFormat);
        }

        // ── Baris 1: Judul ───────────────────────────────────────
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->setCellValue('A1', 'DATA MUSTAHIK — ' . strtoupper($lembagaNama));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Baris 2: Info ekspor ─────────────────────────────────
        $sheet->mergeCells('A2:' . $lastCol . '2');
        $sheet->setCellValue('A2',
            'Diekspor: ' . now()->format('d F Y, H:i') . ' WIB  |  Total data: ' . $totalData . ' mustahik'
        );
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 9, 'color' => ['rgb' => '374151']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
            'alignment' => ['horizontal' => 'center'],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── Baris 4: Header kolom ────────────────────────────────
        $headers = [
            'A' => ['No.',                     5],
            'B' => ['No. Registrasi',         18],
            'C' => ['Nama Lengkap',           28],
            'D' => ['NIK',                    20],   // → text
            'E' => ['No. KK',                 20],   // → text
            'F' => ['Jenis Kelamin',          14],
            'G' => ['Tempat Lahir',           18],
            'H' => ['Tanggal Lahir',          16],   // → text YYYY-MM-DD
            'I' => ['No. Telepon',            16],   // → text (0 di depan)
            'J' => ['Alamat',                 40],
            'K' => ['RT/RW',                  10],
            'L' => ['Kode Pos',               10],
            'M' => ['Kategori Mustahik',      22],
            'N' => ['Pekerjaan',              20],
            'O' => ['Penghasilan/Bulan (Rp)', 22],
            'P' => ['Jml. Tanggungan',        16],
            'Q' => ['Status Rumah',           18],
            'R' => ['Kondisi Kesehatan',      22],
            'S' => ['Status Verifikasi',      18],
            'T' => ['Status Aktif',           14],
            'U' => ['Tgl. Registrasi',        18],
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
            'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '3B5998']]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // ── Baris 5+: Isi data ───────────────────────────────────
        $statusRumahMap = [
            'milik_sendiri' => 'Milik Sendiri',
            'kontrak'       => 'Kontrak',
            'menumpang'     => 'Menumpang',
            'lainnya'       => 'Lainnya',
        ];

        $row = $dataRowStart;
        $no  = 1;

        foreach ($mustahiks as $m) {
            $bg          = ($no % 2 === 0) ? 'EFF6FF' : 'FFFFFF';
            $statusVerif = match($m->status_verifikasi) {
                'verified' => 'Terverifikasi',
                'pending'  => 'Pending',
                'rejected' => 'Ditolak',
                default    => $m->status_verifikasi ?? '-',
            };

            // ── Set nilai pakai setValueExplicit TYPE_STRING ─────
            // untuk kolom yang rentan dikonversi Excel

            // Kolom biasa (angka & teks umum)
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $m->no_registrasi ?? '-');
            $sheet->setCellValue('C' . $row, $m->nama_lengkap);

            // NIK — TYPE_STRING agar 16 digit tidak terpotong
            $sheet->setCellValueExplicit(
                'D' . $row,
                $m->nik ?? '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // No. KK — TYPE_STRING
            $sheet->setCellValueExplicit(
                'E' . $row,
                $m->kk ?? '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            $sheet->setCellValue('F' . $row, $m->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('G' . $row, $m->tempat_lahir ?? '-');

            // Tanggal Lahir — TYPE_STRING format YYYY-MM-DD
            $sheet->setCellValueExplicit(
                'H' . $row,
                $m->tanggal_lahir ? $m->tanggal_lahir->format('Y-m-d') : '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // No. Telepon — TYPE_STRING agar 0 di depan tidak hilang
            $sheet->setCellValueExplicit(
                'I' . $row,
                $m->telepon ?? '',
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            $sheet->setCellValue('J' . $row, $m->alamat);
            $sheet->setCellValue('K' . $row, $m->rt_rw ?? '-');
            $sheet->setCellValue('L' . $row, $m->kode_pos ?? '-');
            $sheet->setCellValue('M' . $row, $m->kategoriMustahik?->nama ?? '-');
            $sheet->setCellValue('N' . $row, $m->pekerjaan ?? '-');
            $sheet->setCellValue('O' . $row, $m->penghasilan_perbulan ?? 0);
            $sheet->setCellValue('P' . $row, $m->jumlah_tanggungan ?? 0);
            $sheet->setCellValue('Q' . $row, $statusRumahMap[$m->status_rumah ?? ''] ?? ($m->status_rumah ?? '-'));
            $sheet->setCellValue('R' . $row, $m->kondisi_kesehatan ?? '-');
            $sheet->setCellValue('S' . $row, $statusVerif);
            $sheet->setCellValue('T' . $row, $m->is_active ? 'Aktif' : 'Nonaktif');
            $sheet->setCellValue('U' . $row, $m->tanggal_registrasi ? $m->tanggal_registrasi->format('d/m/Y') : '-');

            // Format angka penghasilan
            $sheet->getStyle('O' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            // Warna baris alternating
            $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray([
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'borders'   => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'E5E7EB']]],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);

            // Warna badge status verifikasi
            $verifStyle = match($statusVerif) {
                'Terverifikasi' => ['bg' => 'DCFCE7', 'font' => '166534'],
                'Pending'       => ['bg' => 'FEF9C3', 'font' => '713F12'],
                'Ditolak'       => ['bg' => 'FEE2E2', 'font' => '991B1B'],
                default         => null,
            };
            if ($verifStyle) {
                $sheet->getStyle('S' . $row)->applyFromArray([
                    'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $verifStyle['bg']]],
                    'font'      => ['bold' => true, 'color' => ['rgb' => $verifStyle['font']]],
                    'alignment' => ['horizontal' => 'center'],
                ]);
            }

            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;
            $no++;
        }

        // ── Baris total ──────────────────────────────────────────
        $sheet->mergeCells('A' . $row . ':R' . $row);
        $sheet->setCellValue('A' . $row, 'Total: ' . $totalData . ' mustahik');
        $sheet->getStyle('A' . $row . ':' . $lastCol . $row)->applyFromArray([
            'font'    => ['bold' => true, 'color' => ['rgb' => '1E3A8A']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '93C5FD']]],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(20);

        // ── Freeze header & auto filter ──────────────────────────
        $sheet->freezePane('A' . $dataRowStart);
        $sheet->setAutoFilter('A' . $headerRow . ':' . $lastCol . $headerRow);

        // ── Download ─────────────────────────────────────────────
        $filename = 'mustahik_' . now()->format('Ymd_His') . '.xlsx';
        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

}