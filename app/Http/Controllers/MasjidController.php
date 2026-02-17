<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class MasjidController extends Controller
{
    public function index(Request $request)
    {
        $query = Masjid::query();

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

        $masjids = $query->latest()->paginate(10);
        $provinces = Province::orderBy('name')->get();

        return view('masjid.index', compact('masjids', 'provinces'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('masjid.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // DATA ADMIN MASJID
            'admin_nama' => 'nullable|string|max:255',
            'admin_telepon' => 'nullable|string|max:20',
            'admin_email' => 'nullable|email|max:255',
            'admin_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // DATA SEJARAH
            'sejarah' => 'nullable|string',
            'tahun_berdiri' => 'nullable|digits:4|integer|min:1000|max:' . (date('Y') + 1),
            'pendiri' => 'nullable|string|max:255',
            'kapasitas_jamaah' => 'nullable|integer|min:0',

            // DATA MASJID
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

            // FOTO MASJID
            'fotos' => 'nullable|array|max:' . Masjid::MAX_FOTO,
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean'
        ]);

        // Handle upload foto admin
        if ($request->hasFile('admin_foto')) {
            $adminFoto = $request->file('admin_foto');
            $adminFilename = time() . '_admin_' . uniqid() . '.' . $adminFoto->getClientOriginalExtension();
            $adminPath = $adminFoto->storeAs('fotos/admin', $adminFilename, 'public');
            $validated['admin_foto'] = $adminPath;
        }

        // Handle multiple foto masjid upload
        $fotoPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('fotos/masjid', $filename, 'public');
                $fotoPaths[] = $path;
            }
        }

        // Set is_active
        $validated['is_active'] = $request->boolean('is_active', true);

        // Simpan foto paths sebagai JSON array
        if (!empty($fotoPaths)) {
            $validated['foto'] = $fotoPaths;
        }

        // Buat masjid baru
        Masjid::create($validated);

        return redirect()->route('masjid.index')
            ->with('success', 'Data masjid berhasil ditambahkan.');
    }

    public function show(Masjid $masjid)
    {
        return view('masjid.show', compact('masjid'));
    }

    public function edit(Masjid $masjid)
    {
        $provinces = Province::orderBy('name')->get();

        // Ambil data kota, kecamatan, kelurahan berdasarkan pilihan sebelumnya
        $cities = $masjid->provinsi_kode
            ? City::where('province_code', $masjid->provinsi_kode)->orderBy('name')->get()
            : collect();

        $districts = $masjid->kota_kode
            ? District::where('city_code', $masjid->kota_kode)->orderBy('name')->get()
            : collect();

        $villages = $masjid->kecamatan_kode
            ? Village::where('district_code', $masjid->kecamatan_kode)->orderBy('name')->get()
            : collect();

        return view('masjid.edit', compact('masjid', 'provinces', 'cities', 'districts', 'villages'));
    }

    public function update(Request $request, Masjid $masjid)
    {
        // Validasi utama
        $validated = $request->validate([
            // DATA ADMIN MASJID
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

            // DATA MASJID
            'nama' => 'required|string|max:255',
            'kode_masjid' => [
                'required',
                'string',
                'max:50',
                Rule::unique('masjid', 'kode_masjid')->ignore($masjid->id) // Perbaiki nama tabel ke 'masjids'
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

            // FOTO MASJID
            'fotos' => 'nullable|array|max:' . $masjid->getRemainingFotoSlots(),
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
            if ($masjid->admin_foto && Storage::disk('public')->exists($masjid->admin_foto)) {
                Storage::disk('public')->delete($masjid->admin_foto);
            }
            $validated['admin_foto'] = null;
        }
        // Handle upload foto admin baru
        elseif ($request->hasFile('admin_foto')) {
            // Hapus foto admin lama jika ada
            if ($masjid->admin_foto && Storage::disk('public')->exists($masjid->admin_foto)) {
                Storage::disk('public')->delete($masjid->admin_foto);
            }

            $adminFoto = $request->file('admin_foto');
            $adminFilename = time() . '_admin_' . uniqid() . '.' . $adminFoto->getClientOriginalExtension();
            $adminPath = $adminFoto->storeAs('fotos/admin/' . $masjid->id, $adminFilename, 'public');
            $validated['admin_foto'] = $adminPath;
        }

        // Handle penghapusan foto masjid berdasarkan index
        $hapusFotoIndex = $request->input('hapus_foto_index', []);
        // Filter hanya nilai yang valid (bukan null atau string kosong)
        $hapusFotoIndex = array_filter($hapusFotoIndex, function($value) {
            return $value !== '' && $value !== null;
        });
        
        if (!empty($hapusFotoIndex)) {
            // Konversi ke integer dan hapus duplikat
            $hapusFotoIndex = array_unique(array_map('intval', $hapusFotoIndex));
            
            foreach ($hapusFotoIndex as $index) {
                $masjid->removeFotoByIndex($index);
            }
        }

        // Handle penambahan foto masjid baru
        $newFotoPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                if ($masjid->canAddMoreFotos()) {
                    $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('fotos/masjid/' . $masjid->id, $filename, 'public');
                    $newFotoPaths[] = $path;
                } else {
                    break; // Stop jika sudah mencapai batas maksimal
                }
            }
        }

        // Tambahkan foto baru ke masjid
        foreach ($newFotoPaths as $path) {
            $masjid->addFoto($path);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Update data masjid (kecuali foto yang sudah dihandle terpisah)
        unset($validated['fotos']);
        $masjid->update($validated);

        return redirect()->route('masjid.index')
            ->with('success', 'Data masjid berhasil diperbarui.');
    }

    public function destroy(Masjid $masjid)
    {
        // Hapus foto admin jika ada
        if ($masjid->admin_foto && Storage::disk('public')->exists($masjid->admin_foto)) {
            Storage::disk('public')->delete($masjid->admin_foto);
        }

        // Hapus semua foto masjid menggunakan method dari model
        $masjid->clearAllFotos();

        $masjid->delete();

        return redirect()->route('masjid.index')
            ->with('success', 'Data masjid berhasil dihapus.');
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
     * API untuk menghapus foto masjid tertentu berdasarkan index
     */
    public function deleteFoto(Masjid $masjid, $index)
    {
        if ($masjid->removeFotoByIndex($index)) {
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus',
                'remaining_slots' => $masjid->getRemainingFotoSlots(),
                'foto_count' => $masjid->foto_count
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
    public function deleteAdminFoto(Masjid $masjid)
    {
        if ($masjid->admin_foto) {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($masjid->admin_foto)) {
                Storage::disk('public')->delete($masjid->admin_foto);
            }

            // Update database
            $masjid->update(['admin_foto' => null]);

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
     * API untuk upload foto tambahan masjid
     */
    public function uploadFoto(Request $request, Masjid $masjid)
    {
        $request->validate([
            'fotos' => 'required|array|max:' . $masjid->getRemainingFotoSlots(),
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $uploadedPaths = [];

        foreach ($request->file('fotos') as $foto) {
            if ($masjid->canAddMoreFotos()) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('fotos/masjid/' . $masjid->id, $filename, 'public');

                // Tambah foto ke masjid
                $masjid->addFoto($path);
                $uploadedPaths[] = $path;
            } else {
                break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload',
            'uploaded_count' => count($uploadedPaths),
            'remaining_slots' => $masjid->getRemainingFotoSlots(),
            'foto_count' => $masjid->foto_count,
            'fotos' => $uploadedPaths
        ]);
    }
}