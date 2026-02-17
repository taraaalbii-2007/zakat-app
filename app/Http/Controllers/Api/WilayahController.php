<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

/**
 * API Controller untuk data wilayah Indonesia
 * Digunakan untuk cascading dropdown di seluruh aplikasi
 */
class WilayahController extends Controller
{
    /**
     * Get all provinces
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function provinces()
    {
        try {
            $provinces = Province::orderBy('name', 'asc')
                ->get(['code', 'name']);
            
            return response()->json([
                'success' => true,
                'data' => $provinces,
                'count' => $provinces->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get Provinces Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data provinsi',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get cities by province code
     * 
     * @param string $provinceCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities($provinceCode)
    {
        try {
            // Validasi province exists
            $province = Province::where('code', $provinceCode)->first();
            
            if (!$province) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provinsi tidak ditemukan'
                ], 404);
            }

            $cities = City::where('province_code', $provinceCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name', 'province_code']);
            
            return response()->json([
                'success' => true,
                'data' => $cities,
                'count' => $cities->count(),
                'province' => [
                    'code' => $province->code,
                    'name' => $province->name
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get Cities Error: ' . $e->getMessage(), [
                'province_code' => $provinceCode
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kota/kabupaten',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get districts by city code
     * 
     * @param string $cityCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function districts($cityCode)
    {
        try {
            // Validasi city exists
            $city = City::where('code', $cityCode)->first();
            
            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kota/Kabupaten tidak ditemukan'
                ], 404);
            }

            $districts = District::where('city_code', $cityCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name', 'city_code']);
            
            return response()->json([
                'success' => true,
                'data' => $districts,
                'count' => $districts->count(),
                'city' => [
                    'code' => $city->code,
                    'name' => $city->name
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get Districts Error: ' . $e->getMessage(), [
                'city_code' => $cityCode
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kecamatan',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get villages by district code
     * 
     * @param string $districtCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function villages($districtCode)
    {
        try {
            // Validasi district exists
            $district = District::where('code', $districtCode)->first();
            
            if (!$district) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kecamatan tidak ditemukan'
                ], 404);
            }

            $villages = Village::where('district_code', $districtCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name', 'district_code', 'meta']);
            
            return response()->json([
                'success' => true,
                'data' => $villages,
                'count' => $villages->count(),
                'district' => [
                    'code' => $district->code,
                    'name' => $district->name
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get Villages Error: ' . $e->getMessage(), [
                'district_code' => $districtCode
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kelurahan/desa',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get postal code by village code
     * 
     * @param string $villageCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function postalCode($villageCode)
    {
        try {
            $village = Village::where('code', $villageCode)->first();
            
            if (!$village) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelurahan/Desa tidak ditemukan'
                ], 404);
            }

            $postalCode = null;
            if ($village->meta && is_array($village->meta)) {
                $postalCode = $village->meta['postal_code'] ?? null;
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'postal_code' => $postalCode,
                    'village' => [
                        'code' => $village->code,
                        'name' => $village->name
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get Postal Code Error: ' . $e->getMessage(), [
                'village_code' => $villageCode
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat kode pos',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Search wilayah by keyword (optional feature)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('q');
            $type = $request->input('type', 'all'); // all, province, city, district, village
            
            if (!$keyword || strlen($keyword) < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keyword minimal 3 karakter'
                ], 400);
            }

            $results = [];

            if (in_array($type, ['all', 'province'])) {
                $provinces = Province::where('name', 'like', "%{$keyword}%")
                    ->limit(10)
                    ->get(['code', 'name']);
                
                $results['provinces'] = $provinces;
            }

            if (in_array($type, ['all', 'city'])) {
                $cities = City::where('name', 'like', "%{$keyword}%")
                    ->limit(10)
                    ->get(['code', 'name', 'province_code']);
                
                $results['cities'] = $cities;
            }

            if (in_array($type, ['all', 'district'])) {
                $districts = District::where('name', 'like', "%{$keyword}%")
                    ->limit(10)
                    ->get(['code', 'name', 'city_code']);
                
                $results['districts'] = $districts;
            }

            if (in_array($type, ['all', 'village'])) {
                $villages = Village::where('name', 'like', "%{$keyword}%")
                    ->limit(10)
                    ->get(['code', 'name', 'district_code']);
                
                $results['villages'] = $villages;
            }

            return response()->json([
                'success' => true,
                'keyword' => $keyword,
                'type' => $type,
                'data' => $results
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Search Wilayah Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}