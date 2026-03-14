<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TipeZakat;
use App\Models\JenisZakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TipeZakatController extends Controller
{
    /**
     * Daftar tipe Zakat Mal beserta konfigurasi nisab & haul-nya.
     * Dijadikan konstanta agar mudah dikelola dan konsisten.
     */
    const ZAKAT_MAL_TYPES = [
        'emas_perak'     => ['label' => 'Emas, Perak & Logam Mulia', 'requires_haul' => true,  'nisab' => ['emas', 'perak']],
        'perniagaan'     => ['label' => 'Perniagaan / Perdagangan',  'requires_haul' => true,  'nisab' => ['emas']],
        'pertanian'      => ['label' => 'Pertanian, Perkebunan & Kehutanan', 'requires_haul' => false, 'nisab' => ['pertanian_kg']],
        'peternakan'     => ['label' => 'Peternakan & Perikanan',    'requires_haul' => true,  'nisab' => ['kambing', 'sapi', 'unta']],
        'penghasilan'    => ['label' => 'Penghasilan / Profesi',     'requires_haul' => true,  'nisab' => ['emas']],
        'uang_surat'     => ['label' => 'Uang & Surat Berharga',     'requires_haul' => true,  'nisab' => ['emas']],
        'investasi'      => ['label' => 'Investasi',                 'requires_haul' => true,  'nisab' => ['emas']],
        'pertambangan'   => ['label' => 'Pertambangan (Ma\'din)',    'requires_haul' => false, 'nisab' => ['emas']],
        'rikaz'          => ['label' => 'Rikaz (Harta Temuan)',      'requires_haul' => false, 'nisab' => []],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TipeZakat::with('jenisZakat');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('ketentuan_khusus', 'like', "%{$search}%")
                    ->orWhereHas('jenisZakat', function ($subQ) use ($search) {
                        $subQ->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('jenis_zakat_id')) {
            $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        }

        if ($request->filled('requires_haul')) {
            $query->where('requires_haul', $request->requires_haul === 'true');
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = ['nama', 'persentase_zakat', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tipeZakat = $query->paginate(10);
        $jenisZakatList = JenisZakat::orderBy('nama')->get(['id', 'nama']);

        $breadcrumbs = ['Tipe Zakat' => route('tipe-zakat.index')];

        return view('superadmin.tipe-zakat.index', compact('tipeZakat', 'jenisZakatList', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisZakatList = JenisZakat::orderBy('nama')->get(['id', 'nama']);
        $zakatMalId     = $this->getZakatMalId();
        $zakatMalTypes  = self::ZAKAT_MAL_TYPES;

        $breadcrumbs = [
            'Tipe Zakat' => route('tipe-zakat.index'),
            'Edit Tipe Zakat' => route('tipe-zakat.create'),
        ];

        return view('superadmin.tipe-zakat.create', compact('jenisZakatList', 'zakatMalId', 'zakatMalTypes', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $isZakatMal  = $this->isZakatMal($request->jenis_zakat_id);
            $zakatMalType = $request->zakat_mal_type; // sub-tipe yang dipilih

            if ($isZakatMal) {
                $validationError = $this->validateZakatMalInput($request, $zakatMalType);
                if ($validationError) {
                    return redirect()->back()->with('error', $validationError)->withInput();
                }
            } else {
                $this->clearZakatMalFields($request);
            }

            TipeZakat::create($this->buildData($request, $isZakatMal, $zakatMalType));

            DB::commit();

            Log::info('Tipe Zakat berhasil ditambahkan', [
                'nama'           => $request->nama,
                'jenis_zakat_id' => $request->jenis_zakat_id,
                'zakat_mal_type' => $zakatMalType,
                'is_zakat_mal'   => $isZakatMal,
                'user_id'        => auth()->id(),
            ]);

            return redirect()->route('tipe-zakat.index')->with('success', 'Tipe zakat berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan tipe zakat: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipeZakat $tipeZakat)
    {
        $jenisZakatList = JenisZakat::orderBy('nama')->get(['id', 'nama']);
        $zakatMalId     = $this->getZakatMalId();
        $zakatMalTypes  = self::ZAKAT_MAL_TYPES;

        $breadcrumbs = [
            'Tipe Zakat' => route('tipe-zakat.index'),
            'Edit Tipe Zakat' => route('tipe-zakat.edit', $zakatMalId),
        ];

        return view('superadmin.tipe-zakat.edit', compact('tipeZakat', 'jenisZakatList', 'zakatMalId', 'zakatMalTypes', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipeZakat $tipeZakat)
    {
        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $isZakatMal   = $this->isZakatMal($request->jenis_zakat_id);
            $zakatMalType = $request->zakat_mal_type;

            if ($isZakatMal) {
                $validationError = $this->validateZakatMalInput($request, $zakatMalType);
                if ($validationError) {
                    return redirect()->back()->with('error', $validationError)->withInput();
                }
            } else {
                $this->clearZakatMalFields($request);
            }

            $tipeZakat->update($this->buildData($request, $isZakatMal, $zakatMalType));

            DB::commit();

            Log::info('Tipe Zakat berhasil diupdate', [
                'uuid'           => $tipeZakat->uuid,
                'nama'           => $tipeZakat->nama,
                'jenis_zakat_id' => $request->jenis_zakat_id,
                'zakat_mal_type' => $zakatMalType,
                'is_zakat_mal'   => $isZakatMal,
                'user_id'        => auth()->id(),
            ]);

            return redirect()->route('tipe-zakat.index')->with('success', 'Tipe zakat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengupdate tipe zakat: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipeZakat $tipeZakat)
    {
        try {
            DB::beginTransaction();

            $nama = $tipeZakat->nama;
            $tipeZakat->delete();

            DB::commit();

            Log::info('Tipe Zakat berhasil dihapus', ['nama' => $nama, 'user_id' => auth()->id()]);

            return redirect()->route('tipe-zakat.index')
                ->with('success', 'Tipe zakat "' . $nama . '" berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus tipe zakat: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API untuk mendapatkan daftar tipe zakat berdasarkan jenis
     */
    public function getByJenisZakat(Request $request)
    {
        $jenisZakatId = $request->jenis_zakat_id;

        if (!$jenisZakatId) {
            return response()->json([]);
        }

        $tipeZakat = TipeZakat::where('jenis_zakat_id', $jenisZakatId)
            ->orderBy('nama')
            ->get(['uuid', 'nama', 'persentase_zakat', 'zakat_mal_type']);

        return response()->json($tipeZakat);
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Ambil ID jenis zakat "Zakat Mal" dari database.
     */
    private function getZakatMalId(): ?int
    {
        $zakatMal = JenisZakat::where('nama', 'like', '%Zakat Mal%')
            ->orWhere('nama', 'like', '%Zakat Maal%')
            ->first();

        return $zakatMal?->id;
    }

    /**
     * Cek apakah jenis zakat yang dipilih adalah Zakat Mal.
     */
    private function isZakatMal($jenisZakatId): bool
    {
        $zakatMalId = $this->getZakatMalId();
        return $zakatMalId && $zakatMalId == $jenisZakatId;
    }

    /**
     * Validasi input khusus Zakat Mal sesuai sub-tipe.
     * Mengembalikan pesan error (string) atau null jika valid.
     */
    private function validateZakatMalInput(Request $request, ?string $zakatMalType): ?string
    {
        if (!$zakatMalType || !array_key_exists($zakatMalType, self::ZAKAT_MAL_TYPES)) {
            return 'Sub-tipe Zakat Mal wajib dipilih.';
        }

        // Rikaz tidak memerlukan nisab
        if ($zakatMalType === 'rikaz') {
            if (!$request->persentase_zakat) {
                return 'Persentase zakat wajib diisi. Untuk Rikaz standarnya 20%.';
            }
            return null;
        }

        // Peternakan: minimal satu nisab hewan
        if ($zakatMalType === 'peternakan') {
            $hasNisab = $request->nisab_kambing_min || $request->nisab_sapi_min || $request->nisab_unta_min;
            if (!$hasNisab) {
                return 'Minimal satu nisab peternakan (kambing/sapi/unta) harus diisi.';
            }
        }
        // Pertanian: wajib nisab kg
        elseif ($zakatMalType === 'pertanian') {
            if (!$request->nisab_pertanian_kg) {
                return 'Nisab pertanian (kg) wajib diisi untuk tipe Pertanian.';
            }
        }
        // Semua tipe lainnya: minimal nisab emas
        else {
            if (!$request->nisab_emas_gram) {
                return 'Nisab emas (gram) wajib diisi. Standar: 85 gram.';
            }
        }

        if (!$request->persentase_zakat) {
            return 'Persentase zakat wajib diisi untuk Zakat Mal.';
        }

        return null;
    }

    /**
     * Kosongkan field Zakat Mal ketika jenis zakat bukan Zakat Mal.
     */
    private function clearZakatMalFields(Request $request): void
    {
        $request->merge([
            'zakat_mal_type'        => null,
            'nisab_emas_gram'       => null,
            'nisab_perak_gram'      => null,
            'nisab_pertanian_kg'    => null,
            'nisab_kambing_min'     => null,
            'nisab_sapi_min'        => null,
            'nisab_unta_min'        => null,
            'persentase_zakat'      => null,
            'persentase_alternatif' => null,
            'keterangan_persentase' => null,
            'requires_haul'         => false,
        ]);
    }

    /**
     * Tentukan nilai requires_haul otomatis berdasarkan sub-tipe.
     */
    private function resolveRequiresHaul(Request $request, bool $isZakatMal, ?string $zakatMalType): bool
    {
        if (!$isZakatMal) {
            return false;
        }

        // Jika sub-tipe diketahui, gunakan konfigurasi default-nya
        if ($zakatMalType && isset(self::ZAKAT_MAL_TYPES[$zakatMalType])) {
            // Tetap hormati override dari user jika haul-nya di-uncheck manual
            return $request->boolean('requires_haul', self::ZAKAT_MAL_TYPES[$zakatMalType]['requires_haul']);
        }

        return $request->boolean('requires_haul', true);
    }

    /**
     * Bangun array data untuk create/update.
     */
    private function buildData(Request $request, bool $isZakatMal, ?string $zakatMalType): array
    {
        return [
            'jenis_zakat_id'        => $request->jenis_zakat_id,
            'nama'                  => $request->nama,
            'zakat_mal_type'        => $isZakatMal ? $zakatMalType : null,
            'nisab_emas_gram'       => $request->nisab_emas_gram,
            'nisab_perak_gram'      => $request->nisab_perak_gram,
            'nisab_pertanian_kg'    => $request->nisab_pertanian_kg,
            'nisab_kambing_min'     => $request->nisab_kambing_min,
            'nisab_sapi_min'        => $request->nisab_sapi_min,
            'nisab_unta_min'        => $request->nisab_unta_min,
            'persentase_zakat'      => $request->persentase_zakat,
            'persentase_alternatif' => $request->persentase_alternatif,
            'keterangan_persentase' => $request->keterangan_persentase,
            'requires_haul'         => $this->resolveRequiresHaul($request, $isZakatMal, $zakatMalType),
            'ketentuan_khusus'      => $request->ketentuan_khusus,
        ];
    }

    /**
     * Aturan validasi bersama untuk store & update.
     */
    private function validationRules(): array
    {
        return [
            'jenis_zakat_id'        => 'required|exists:jenis_zakat,id',
            'nama'                  => 'required|string|max:255',
            'zakat_mal_type'        => 'nullable|string|in:' . implode(',', array_keys(self::ZAKAT_MAL_TYPES)),
            'nisab_emas_gram'       => 'nullable|numeric|min:0|max:999999.99',
            'nisab_perak_gram'      => 'nullable|numeric|min:0|max:999999.99',
            'nisab_pertanian_kg'    => 'nullable|numeric|min:0|max:999999.99',
            'nisab_kambing_min'     => 'nullable|integer|min:0|max:9999',
            'nisab_sapi_min'        => 'nullable|integer|min:0|max:9999',
            'nisab_unta_min'        => 'nullable|integer|min:0|max:9999',
            'persentase_zakat'      => 'nullable|numeric|min:0|max:100',
            'persentase_alternatif' => 'nullable|numeric|min:0|max:100',
            'keterangan_persentase' => 'nullable|string|max:255',
            'requires_haul'         => 'boolean',
            'ketentuan_khusus'      => 'nullable|string|max:1000',
        ];
    }

    /**
     * Pesan validasi custom.
     */
    private function validationMessages(): array
    {
        return [
            'jenis_zakat_id.required' => 'Jenis zakat wajib dipilih',
            'jenis_zakat_id.exists'   => 'Jenis zakat tidak valid',
            'nama.required'           => 'Nama tipe zakat wajib diisi',
            'nama.max'                => 'Nama tipe zakat maksimal 255 karakter',
            'zakat_mal_type.in'       => 'Sub-tipe Zakat Mal tidak valid',
            'persentase_zakat.max'    => 'Persentase tidak boleh lebih dari 100%',
            'persentase_alternatif.max' => 'Persentase alternatif tidak boleh lebih dari 100%',
            'ketentuan_khusus.max'    => 'Ketentuan khusus maksimal 1000 karakter',
        ];
    }
}