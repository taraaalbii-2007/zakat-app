<?php

namespace App\Http\Controllers\Admin_lembaga;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiQris;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class KonfigurasiIntegrasiController extends Controller
{
    /**
     * Display konfigurasi integrasi (QRIS)
     */
    public function show()
    {
        try {
            $user = Auth::user();
            $lembaga = $user->lembaga;

            if (!$lembaga) {
                return redirect()->route('dashboard')->with('error', 'Data lembaga tidak ditemukan');
            }

            $qris = KonfigurasiQris::firstOrCreate(
                ['lembaga_id' => $lembaga->id],
                ['is_active' => false]
            );

             $breadcrumbs = [
            'Kelola Pengaturan Lembaga' => route('konfigurasi-integrasi.show'),
        ];

            return view('admin-lembaga.konfigurasi-integrasi.show', compact('lembaga', 'qris', 'breadcrumbs'));
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
            $lembaga = $user->lembaga;

            if (!$lembaga) {
                return redirect()->route('dashboard')
                    ->with('error', 'Data lembaga tidak ditemukan');
            }

            $qris = KonfigurasiQris::firstOrCreate(
                ['lembaga_id' => $lembaga->id],
                ['is_active' => false]
            );

             $breadcrumbs = [
            'Kelola Pengaturan Lembaga' => route('konfigurasi-integrasi.show'),
            'Edit Pengaturan Lembaga' => route('konfigurasi-integrasi.edit')
        ];

            return view('admin-lembaga.konfigurasi-integrasi.edit', compact('lembaga', 'qris', 'breadcrumbs'));
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
            'qris_image'    => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
            'qris_is_active' => 'nullable|boolean',
        ], [
            'qris_image.image' => 'File QRIS harus berupa gambar',
            'qris_image.mimes' => 'Format gambar QRIS hanya boleh JPG, JPEG, PNG, atau GIF',
            'qris_image.max'   => 'Ukuran gambar QRIS maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $lembaga = $user->lembaga;

            if (!$lembaga) {
                return redirect()->route('dashboard')
                    ->with('error', 'Data lembaga tidak ditemukan');
            }

            $qrisData = [
                'is_active' => $request->has('qris_is_active') ? true : false,
            ];

            // Handle upload gambar QRIS
            if ($request->hasFile('qris_image')) {
                // Hapus gambar lama jika ada
                $qrisOld = KonfigurasiQris::where('lembaga_id', $lembaga->id)->first();
                if ($qrisOld && $qrisOld->qris_image_path && !filter_var($qrisOld->qris_image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($qrisOld->qris_image_path);
                }

                $file = $request->file('qris_image');

                // Simpan sebagai .jpg (konversi otomatis)
                $fileName = $lembaga->id . '_' . time() . '.jpg';

                $folder = storage_path('app/public/qris');
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                // Konversi ke JPG menggunakan GD (built-in PHP)
                $imageContent = file_get_contents($file->getRealPath());
                $image = imagecreatefromstring($imageContent);
                imagejpeg($image, $folder . '/' . $fileName, 90);
                imagedestroy($image);

                $qrisData['qris_image_path'] = 'qris/' . $fileName;
            }

            // Hapus gambar jika checkbox di-check
            if ($request->has('hapus_qris_image')) {
                $qrisOld = KonfigurasiQris::where('lembaga_id', $lembaga->id)->first();
                if ($qrisOld && $qrisOld->qris_image_path && !filter_var($qrisOld->qris_image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($qrisOld->qris_image_path);
                }
                $qrisData['qris_image_path'] = null;
            }

            KonfigurasiQris::updateOrCreate(
                ['lembaga_id' => $lembaga->id],
                $qrisData
            );

            DB::commit();

            return redirect()->route('konfigurasi-integrasi.show')
                ->with('success', 'Konfigurasi QRIS berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating konfigurasi integrasi: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan konfigurasi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle QRIS status
     */
    public function toggleQrisStatus()
    {
        try {
            $user   = Auth::user();
            $lembaga = $user->lembaga;

            $qris = KonfigurasiQris::where('lembaga_id', $lembaga->id)->first();

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