<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KonfigurasiAplikasiController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Panggil method static dengan benar
        $konfigurasi = KonfigurasiAplikasi::getKonfigurasi();
        
        return view('superadmin.konfigurasi.show', compact('konfigurasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $konfigurasi = KonfigurasiAplikasi::getKonfigurasi();
        
        return view('superadmin.konfigurasi.edit', compact('konfigurasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:200',
            'deskripsi_aplikasi' => 'nullable|string',
            'logo_aplikasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'email_support' => 'nullable|email|max:100',
            'telepon_support' => 'nullable|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_support' => 'nullable|string|max:20',
        ]);

        $konfigurasi = KonfigurasiAplikasi::getKonfigurasi();

        // Handle logo upload
        if ($request->hasFile('logo_aplikasi')) {
            // Delete old logo if exists
            if ($konfigurasi->logo_aplikasi && Storage::disk('public')->exists($konfigurasi->logo_aplikasi)) {
                Storage::disk('public')->delete($konfigurasi->logo_aplikasi);
            }
            
            $path = $request->file('logo_aplikasi')->store('konfigurasi', 'public');
            $validated['logo_aplikasi'] = $path;
        } else {
            unset($validated['logo_aplikasi']);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($konfigurasi->favicon && Storage::disk('public')->exists($konfigurasi->favicon)) {
                Storage::disk('public')->delete($konfigurasi->favicon);
            }
            
            $path = $request->file('favicon')->store('konfigurasi', 'public');
            $validated['favicon'] = $path;
        } else {
            unset($validated['favicon']);
        }

        // Update or create konfigurasi
        KonfigurasiAplikasi::saveKonfigurasi($validated);

        return redirect()->route('superadmin.konfigurasi.show')
            ->with('success', 'Konfigurasi aplikasi berhasil diperbarui!');
    }
}