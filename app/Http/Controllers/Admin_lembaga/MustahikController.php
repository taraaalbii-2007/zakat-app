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

        $mustahiks = $query->latest()->paginate(15);

        $kategoris = KategoriMustahik::orderBy('nama')->get();

        $userRole = $user->peran;
        $permissions = [
            'canCreate' => in_array($userRole, ['admin_lembaga', 'amil']),
            'userRole'  => $userRole,
        ];

        return view('admin-lembaga.mustahik.index', compact('mustahiks', 'kategoris', 'permissions'));
    }
    public function create()
    {
        // Amil & Admin Lembaga Both can create
        if (!in_array(auth()->user()->peran, ['admin_lembaga', 'amil'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah mustahik.');
        }

        $kategoris = KategoriMustahik::all();
        $provinces = Province::orderBy('name')->get();

        return view('admin-lembaga.mustahik.create', compact('kategoris', 'provinces'));
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
            'provinsi_kode' => 'nullable|string|max:2',
            'kota_kode' => 'nullable|string|max:4',
            'kecamatan_kode' => 'nullable|string|max:10',
            'kelurahan_kode' => 'nullable|string|max:13',
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

    return view('admin-lembaga.mustahik.show', compact(
        'mustahik',
        'permissions',
        'riwayatPenyaluran',
        'riwayatKunjungan',
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

        return view('admin-lembaga.mustahik.edit', compact('mustahik', 'kategoris', 'provinces', 'cities', 'districts', 'villages'));
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
            'provinsi_kode' => 'nullable|string|max:2',
            'kota_kode' => 'nullable|string|max:4',
            'kecamatan_kode' => 'nullable|string|max:10',
            'kelurahan_kode' => 'nullable|string|max:13',
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

    // API Wilayah
    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($cities);
    }

    public function getDistricts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($districts);
    }

    public function getVillages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($villages);
    }

    private function generateNoRegistrasi()
    {
        $lembaga = Lembaga::find(auth()->user()->lembaga_id);

        // Get last mustahik number for this lembaga
        $lastMustahik = Mustahik::where('lembaga_id', auth()->user()->lembaga_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMustahik && $lastMustahik->no_registrasi) {
            // Extract the last number from format MUST-MSJ001-001
            $lastNumber = intval(substr($lastMustahik->no_registrasi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: MUST-{kode_lembaga}-{increment 3 digit}
        return sprintf('MUST-%s-%03d', $lembaga->kode_lembaga, $newNumber);
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
}