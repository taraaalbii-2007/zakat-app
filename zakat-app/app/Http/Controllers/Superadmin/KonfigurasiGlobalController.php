<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KonfigurasiGlobalController extends Controller
{
    /**
     * Menampilkan halaman konfigurasi
     */
    public function show()
    {
        $config = KonfigurasiAplikasi::getConfig();
        
        return view('superadmin.konfigurasi-global.show', compact('config'));
    }

    /**
     * Menampilkan form edit konfigurasi
     */
    public function edit()
    {
        $config = KonfigurasiAplikasi::getConfig();
        
        return view('superadmin.konfigurasi-global.edit', compact('config'));
    }

    /**
     * Update konfigurasi aplikasi
     */
    public function update(Request $request)
    {
        $config = KonfigurasiAplikasi::getConfig();
        
        $validated = $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'deskripsi_aplikasi' => 'nullable|string',
            'logo_aplikasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'email_support' => 'nullable|email|max:255',
            'telepon_support' => 'nullable|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_support' => 'nullable|string|max:20',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_aplikasi')) {
            // Hapus logo lama jika ada
            if ($config->logo_aplikasi && Storage::exists('public/' . $config->logo_aplikasi)) {
                Storage::delete('public/' . $config->logo_aplikasi);
            }
            
            $logo = $request->file('logo_aplikasi');
            $logoName = 'logo-' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('konfigurasi', $logoName, 'public');
            $validated['logo_aplikasi'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Hapus favicon lama jika ada
            if ($config->favicon && Storage::exists('public/' . $config->favicon)) {
                Storage::delete('public/' . $config->favicon);
            }
            
            $favicon = $request->file('favicon');
            $faviconName = 'favicon-' . time() . '.' . $favicon->getClientOriginalExtension();
            $faviconPath = $favicon->storeAs('konfigurasi', $faviconName, 'public');
            $validated['favicon'] = $faviconPath;
        }

        $config->update($validated);

        return redirect()->route('konfigurasi-global.show')
            ->with('success', 'Konfigurasi aplikasi berhasil diperbarui!');
    }

    /**
     * Hapus logo aplikasi
     */
    public function hapusLogo()
    {
        $config = KonfigurasiAplikasi::getConfig();
        
        if ($config->logo_aplikasi && Storage::exists('public/' . $config->logo_aplikasi)) {
            Storage::delete('public/' . $config->logo_aplikasi);
        }
        
        $config->update(['logo_aplikasi' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Logo berhasil dihapus'
        ]);
    }

    /**
     * Hapus favicon
     */
    public function hapusFavicon()
    {
        $config = KonfigurasiAplikasi::getConfig();
        
        if ($config->favicon && Storage::exists('public/' . $config->favicon)) {
            Storage::delete('public/' . $config->favicon);
        }
        
        $config->update(['favicon' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Favicon berhasil dihapus'
        ]);
    }
}