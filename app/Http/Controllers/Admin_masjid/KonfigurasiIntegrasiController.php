<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiWhatsapp;
use App\Models\KonfigurasiQris;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class KonfigurasiIntegrasiController extends Controller
{
    /**
     * Display konfigurasi integrasi (WhatsApp & QRIS)
     */
    public function show()
    {
        try {
            $user = Auth::user();
            $masjid = $user->masjid;

            if (!$masjid) {
                return redirect()->route('dashboard')->with('error', 'Data masjid tidak ditemukan');
            }

            // WhatsApp
            $whatsapp = KonfigurasiWhatsapp::firstOrCreate(
                ['masjid_id' => $masjid->id],
                ['api_url' => 'https://api.fonnte.com/send', 'is_active' => false]
            );

            // QRIS
            $qris = KonfigurasiQris::firstOrCreate(
                ['masjid_id' => $masjid->id],
                ['is_active' => false]
            );

            return view('admin-masjid.konfigurasi-integrasi.show', compact('masjid', 'whatsapp', 'qris'));
        } catch (\Exception $e) {
            Log::error('Error showing konfigurasi integrasi: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat memuat konfigurasi');
        }
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        try {
            $user = Auth::user();
            $masjid = $user->masjid;

            if (!$masjid) {
                return redirect()->route('dashboard')
                    ->with('error', 'Data masjid tidak ditemukan');
            }

            // Get atau create konfigurasi WhatsApp
            $whatsapp = KonfigurasiWhatsapp::firstOrCreate(
                ['masjid_id' => $masjid->id],
                [
                    'api_url' => 'https://api.fonnte.com/send',
                    'is_active' => false
                ]
            );

            // Get atau create konfigurasi QRIS
            $qris = KonfigurasiQris::firstOrCreate(
                ['masjid_id' => $masjid->id],
                ['is_active' => false]
            );

            return view('admin-masjid.konfigurasi-integrasi.edit', compact('masjid', 'whatsapp', 'qris'));
        } catch (\Exception $e) {
            Log::error('Error showing edit konfigurasi integrasi: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat form edit');
        }
    }

    /**
     * Update konfigurasi integrasi
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // WhatsApp validation
            'whatsapp_api_key'             => 'nullable|string|max:255',
            'whatsapp_nomor_pengirim'       => 'nullable|string|max:20',
            'whatsapp_api_url'              => 'nullable|url|max:255',
            'whatsapp_nomor_tujuan_default' => 'nullable|string|max:20',
            'whatsapp_is_active'            => 'nullable|boolean',
            
            // QRIS validation - Upload foto saja
            'qris_image'                    => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
            'qris_is_active'                => 'nullable|boolean',
        ], [
            'whatsapp_api_url.url' => 'Format URL API WhatsApp tidak valid',
            'qris_image.image' => 'File QRIS harus berupa gambar',
            'qris_image.mimes' => 'Format gambar QRIS hanya boleh JPG, JPEG, PNG, atau GIF',
            'qris_image.max' => 'Ukuran gambar QRIS maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $masjid = $user->masjid;

            if (!$masjid) {
                return redirect()->route('dashboard')
                    ->with('error', 'Data masjid tidak ditemukan');
            }

            // ═══════════════════════════════════════════════════════════
            // UPDATE WHATSAPP CONFIGURATION
            // ═══════════════════════════════════════════════════════════
            $whatsappData = [
                'api_key'               => $request->whatsapp_api_key,
                'nomor_pengirim'        => $request->whatsapp_nomor_pengirim,
                'api_url'               => $request->whatsapp_api_url ?? 'https://api.fonnte.com/send',
                'nomor_tujuan_default'  => $request->whatsapp_nomor_tujuan_default,
                'is_active'             => $request->has('whatsapp_is_active') ? true : false,
            ];

            KonfigurasiWhatsapp::updateOrCreate(
                ['masjid_id' => $masjid->id],
                $whatsappData
            );

            // ═══════════════════════════════════════════════════════════
            // UPDATE QRIS CONFIGURATION
            // ═══════════════════════════════════════════════════════════
            $qrisData = [
                'is_active' => $request->has('qris_is_active') ? true : false,
            ];

            // Handle upload gambar QRIS
            if ($request->hasFile('qris_image')) {
                // Hapus gambar lama jika ada
                $qrisOld = KonfigurasiQris::where('masjid_id', $masjid->id)->first();
                if ($qrisOld && $qrisOld->qris_image_path && !filter_var($qrisOld->qris_image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($qrisOld->qris_image_path);
                }

                // ✅ PERBAIKAN: Simpan gambar ke folder public/qris/
                $file = $request->file('qris_image');
                $fileName = $masjid->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Gunakan store() untuk menyimpan ke public/qris/
                $path = $file->storeAs('qris', $fileName, 'public');
                $qrisData['qris_image_path'] = $path;
            }

            // Hapus gambar jika checkbox di-check
            if ($request->has('hapus_qris_image')) {
                $qrisOld = KonfigurasiQris::where('masjid_id', $masjid->id)->first();
                if ($qrisOld && $qrisOld->qris_image_path && !filter_var($qrisOld->qris_image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($qrisOld->qris_image_path);
                }
                $qrisData['qris_image_path'] = null;
            }

            KonfigurasiQris::updateOrCreate(
                ['masjid_id' => $masjid->id],
                $qrisData
            );

            DB::commit();

            return redirect()->route('konfigurasi-integrasi.show')
                ->with('success', 'Konfigurasi integrasi berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating konfigurasi integrasi: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan konfigurasi: ' . $e->getMessage());
        }
    }

    /**
     * Test WhatsApp Connection
     */
    public function testWhatsapp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key'      => 'required|string',
            'nomor_tujuan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $user   = Auth::user();
            $masjid = $user->masjid;

            // Test send message via Fonnte
            $response = \Http::withHeaders([
                'Authorization' => $request->api_key,
            ])->post('https://api.fonnte.com/send', [
                'target'      => $request->nomor_tujuan,
                'message'     => "Test koneksi WhatsApp dari {$masjid->nama}\n\nPesan ini dikirim untuk menguji konfigurasi WhatsApp API.",
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Koneksi WhatsApp berhasil! Pesan test telah dikirim.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi gagal: ' . $response->body()
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error testing WhatsApp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle WhatsApp status
     */
    public function toggleWhatsappStatus()
    {
        try {
            $user   = Auth::user();
            $masjid = $user->masjid;

            $whatsapp = KonfigurasiWhatsapp::where('masjid_id', $masjid->id)->first();

            if (!$whatsapp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi WhatsApp tidak ditemukan'
                ], 404);
            }

            $whatsapp->is_active = !$whatsapp->is_active;
            $whatsapp->save();

            return response()->json([
                'success'   => true,
                'message'   => 'Status WhatsApp berhasil diubah',
                'is_active' => $whatsapp->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling WhatsApp status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    /**
     * Toggle QRIS status
     */
    public function toggleQrisStatus()
    {
        try {
            $user   = Auth::user();
            $masjid = $user->masjid;

            $qris = KonfigurasiQris::where('masjid_id', $masjid->id)->first();

            if (!$qris) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi QRIS tidak ditemukan'
                ], 404);
            }

            $qris->is_active = !$qris->is_active;
            $qris->save();

            return response()->json([
                'success'   => true,
                'message'   => 'Status QRIS berhasil diubah',
                'is_active' => $qris->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling QRIS status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }
}