<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class LembagaController extends Controller
{
    public function index(Request $request)
    {
        $query = Lembaga::query();

        // Search menggunakan scope dari Model
        if ($request->has('q') && $request->q) {
            $query->search($request->q);
        }

        // Filter status aktif menggunakan scope
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter wilayah menggunakan scope
        if ($request->has('provinsi_kode')) {
            $query->byProvinsi($request->provinsi_kode);
        }

        if ($request->has('kota_kode')) {
            $query->byKota($request->kota_kode);
        }

        $lembagas = $query->latest()->paginate(10);
        $provinces = Province::orderBy('name')->get();

        $breadcrumbs = [
            'Kelola Lembaga' => null,
        ];

        return view('lembaga.index', compact('lembagas', 'provinces', 'breadcrumbs'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('lembaga.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // DATA ADMIN LEMBAGA
            'admin_nama' => 'nullable|string|max:255',
            'admin_telepon' => 'nullable|string|max:20',
            'admin_email' => 'nullable|email|max:255',
            'admin_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // DATA SEJARAH
            'sejarah' => 'nullable|string',
            'tahun_berdiri' => 'nullable|digits:4|integer|min:1000|max:' . (date('Y') + 1),
            'pendiri' => 'nullable|string|max:255',
            'kapasitas_jamaah' => 'nullable|integer|min:0',

            // DATA LEMBAGA
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'provinsi_kode' => 'required|string|max:5',
            'kota_kode' => 'required|string|max:10',
            'kecamatan_kode' => 'nullable|string|max:15',
            'kelurahan_kode' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',

            // FOTO LEMBAGA
            'fotos' => 'nullable|array|max:' . Lembaga::MAX_FOTO,
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean'
        ]);

        // Handle upload foto admin
        if ($request->hasFile('admin_foto')) {
            $adminFoto = $request->file('admin_foto');
            $adminFilename = time() . '_admin_' . uniqid() . '.' . $adminFoto->getClientOriginalExtension();
            $adminPath = $adminFoto->storeAs('fotos/admin/lembaga', $adminFilename, 'public');
            $validated['admin_foto'] = $adminPath;
        }

        // Handle multiple foto lembaga upload
        $fotoPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('fotos/lembaga', $filename, 'public');
                $fotoPaths[] = $path;
            }
        }

        // Set is_active
        $validated['is_active'] = $request->boolean('is_active', true);

        // Simpan foto paths sebagai JSON array
        if (!empty($fotoPaths)) {
            $validated['foto'] = $fotoPaths;
        }

        // Buat lembaga baru
        Lembaga::create($validated);

        return redirect()->route('lembaga.index')
            ->with('success', 'Data lembaga berhasil ditambahkan.');
    }

    public function show(Lembaga $lembaga)
    {
        return view('lembaga.show', compact('lembaga'));
    }

    public function edit(Lembaga $lembaga)
    {
        $provinces = Province::orderBy('name')->get();

        // Ambil data kota, kecamatan, kelurahan berdasarkan pilihan sebelumnya
        $cities = $lembaga->provinsi_kode
            ? City::where('province_code', $lembaga->provinsi_kode)->orderBy('name')->get()
            : collect();

        $districts = $lembaga->kota_kode
            ? District::where('city_code', $lembaga->kota_kode)->orderBy('name')->get()
            : collect();

        $villages = $lembaga->kecamatan_kode
            ? Village::where('district_code', $lembaga->kecamatan_kode)->orderBy('name')->get()
            : collect();

        return view('lembaga.edit', compact('lembaga', 'provinces', 'cities', 'districts', 'villages'));
    }

    public function update(Request $request, Lembaga $lembaga)
    {
        // Validasi utama
        $validated = $request->validate([
            // DATA ADMIN LEMBAGA
            'admin_nama' => 'nullable|string|max:255',
            'admin_telepon' => 'nullable|string|max:20',
            'admin_email' => 'nullable|email|max:255',
            'admin_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'hapus_admin_foto' => 'nullable|boolean',

            // DATA SEJARAH
            'sejarah' => 'nullable|string',
            'tahun_berdiri' => 'nullable|digits:4|integer|min:1000|max:' . (date('Y') + 1),
            'pendiri' => 'nullable|string|max:255',
            'kapasitas_jamaah' => 'nullable|integer|min:0',

            // DATA LEMBAGA
            'nama' => 'required|string|max:255',
            'kode_lembaga' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lembaga', 'kode_lembaga')->ignore($lembaga->id) 
            ],
            'alamat' => 'required|string',
            'provinsi_kode' => 'required|string|max:5',
            'kota_kode' => 'required|string|max:10',
            'kecamatan_kode' => 'nullable|string|max:15',
            'kelurahan_kode' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',

            // FOTO LEMBAGA
            'fotos' => 'nullable|array|max:' . $lembaga->getRemainingFotoSlots(),
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean'
        ]);

        // Validasi khusus untuk hapus_foto_index dengan filter
        $request->validate([
            'hapus_foto_index' => 'nullable|array',
            'hapus_foto_index.*' => 'nullable|integer|min:0',
        ]);

        // Handle penghapusan foto admin
        if ($request->boolean('hapus_admin_foto', false)) {
            // Hapus file foto admin dari storage
            if ($lembaga->admin_foto && Storage::disk('public')->exists($lembaga->admin_foto)) {
                Storage::disk('public')->delete($lembaga->admin_foto);
            }
            $validated['admin_foto'] = null;
        }
        // Handle upload foto admin baru
        elseif ($request->hasFile('admin_foto')) {
            // Hapus foto admin lama jika ada
            if ($lembaga->admin_foto && Storage::disk('public')->exists($lembaga->admin_foto)) {
                Storage::disk('public')->delete($lembaga->admin_foto);
            }

            $adminFoto = $request->file('admin_foto');
            $adminFilename = time() . '_admin_' . uniqid() . '.' . $adminFoto->getClientOriginalExtension();
            $adminPath = $adminFoto->storeAs('fotos/admin/lembaga/' . $lembaga->id, $adminFilename, 'public');
            $validated['admin_foto'] = $adminPath;
        }

        // Handle penghapusan foto lembaga berdasarkan index
        $hapusFotoIndex = $request->input('hapus_foto_index', []);
        // Filter hanya nilai yang valid (bukan null atau string kosong)
        $hapusFotoIndex = array_filter($hapusFotoIndex, function($value) {
            return $value !== '' && $value !== null;
        });
        
        if (!empty($hapusFotoIndex)) {
            // Konversi ke integer dan hapus duplikat
            $hapusFotoIndex = array_unique(array_map('intval', $hapusFotoIndex));
            
            foreach ($hapusFotoIndex as $index) {
                $lembaga->removeFotoByIndex($index);
            }
        }

        // Handle penambahan foto lembaga baru
        $newFotoPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                if ($lembaga->canAddMoreFotos()) {
                    $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('fotos/lembaga/' . $lembaga->id, $filename, 'public');
                    $newFotoPaths[] = $path;
                } else {
                    break; // Stop jika sudah mencapai batas maksimal
                }
            }
        }

        // Tambahkan foto baru ke lembaga
        foreach ($newFotoPaths as $path) {
            $lembaga->addFoto($path);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Update data lembaga (kecuali foto yang sudah dihandle terpisah)
        unset($validated['fotos']);
        $lembaga->update($validated);

        return redirect()->route('lembaga.index')
            ->with('success', 'Data lembaga berhasil diperbarui.');
    }

    public function destroy(Lembaga $lembaga)
    {
        // Hapus foto admin jika ada
        if ($lembaga->admin_foto && Storage::disk('public')->exists($lembaga->admin_foto)) {
            Storage::disk('public')->delete($lembaga->admin_foto);
        }

        // Hapus semua foto lembaga menggunakan method dari model
        $lembaga->clearAllFotos();

        $lembaga->delete();

        return redirect()->route('lembaga.index')
            ->with('success', 'Data lembaga berhasil dihapus.');
    }

    /**
     * API untuk mendapatkan kota berdasarkan provinsi (untuk dropdown AJAX)
     */
    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($cities);
    }

    /**
     * API untuk mendapatkan kecamatan berdasarkan kota
     */
    public function getDistricts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($districts);
    }

    /**
     * API untuk mendapatkan kelurahan berdasarkan kecamatan
     */
    public function getVillages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($villages);
    }

    /**
     * API untuk mendapatkan kode pos berdasarkan kelurahan
     */
    public function getPostalCode($villageCode)
    {
        $village = Village::where('code', $villageCode)->first();

        if ($village && isset($village->meta) && is_array($village->meta)) {
            $meta = $village->meta;
            if (isset($meta['postal_code'])) {
                return response()->json(['kode_pos' => $meta['postal_code']]);
            }
        }

        return response()->json(['kode_pos' => '']);
    }

    /**
     * API untuk menghapus foto lembaga tertentu berdasarkan index
     */
    public function deleteFoto(Lembaga $lembaga, $index)
    {
        if ($lembaga->removeFotoByIndex($index)) {
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus',
                'remaining_slots' => $lembaga->getRemainingFotoSlots(),
                'foto_count' => $lembaga->foto_count
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Foto tidak ditemukan'
        ], 404);
    }

    /**
     * API untuk menghapus foto admin
     */
    public function deleteAdminFoto(Lembaga $lembaga)
    {
        if ($lembaga->admin_foto) {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($lembaga->admin_foto)) {
                Storage::disk('public')->delete($lembaga->admin_foto);
            }

            // Update database
            $lembaga->update(['admin_foto' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Foto admin berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Foto admin tidak ditemukan'
        ], 404);
    }

    /**
     * API untuk upload foto tambahan lembaga
     */
    public function uploadFoto(Request $request, Lembaga $lembaga)
    {
        $request->validate([
            'fotos' => 'required|array|max:' . $lembaga->getRemainingFotoSlots(),
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $uploadedPaths = [];

        foreach ($request->file('fotos') as $foto) {
            if ($lembaga->canAddMoreFotos()) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('fotos/lembaga/' . $lembaga->id, $filename, 'public');

                // Tambah foto ke lembaga
                $lembaga->addFoto($path);
                $uploadedPaths[] = $path;
            } else {
                break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload',
            'uploaded_count' => count($uploadedPaths),
            'remaining_slots' => $lembaga->getRemainingFotoSlots(),
            'foto_count' => $lembaga->foto_count,
            'fotos' => $uploadedPaths
        ]);
    }
}