<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiWhatsapp;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KonfigurasiIntegrasiController extends Controller
{
    /**
     * Display konfigurasi integrasi (WhatsApp)
     */
    public function show()
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

            return view('admin-masjid.konfigurasi-integrasi.show', compact('masjid', 'whatsapp'));
        } catch (\Exception $e) {
            Log::error('Error showing konfigurasi integrasi: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat konfigurasi');
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

            return view('admin-masjid.konfigurasi-integrasi.edit', compact('masjid', 'whatsapp'));
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
            'whatsapp_api_key'             => 'nullable|string|max:255',
            'whatsapp_nomor_pengirim'       => 'nullable|string|max:20',
            'whatsapp_api_url'              => 'nullable|url|max:255',
            'whatsapp_nomor_tujuan_default' => 'nullable|string|max:20',
            'whatsapp_is_active'            => 'nullable|boolean',
        ], [
            'whatsapp_api_url.url' => 'Format URL API WhatsApp tidak valid',
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

            // Update WhatsApp Configuration
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
}