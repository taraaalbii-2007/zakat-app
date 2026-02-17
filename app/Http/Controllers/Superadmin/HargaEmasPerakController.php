<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\HargaEmasPerak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HargaEmasPerakController extends Controller
{
    public function index(Request $request)
    {
        $query = HargaEmasPerak::query();

        // Filter pencarian
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('sumber', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        // Filter tanggal
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter sumber
        if ($request->has('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validasi field sorting
        $allowedSortColumns = ['tanggal', 'harga_emas_pergram', 'harga_perak_pergram', 'created_at', 'sumber'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'tanggal';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        $hargaEmasPerak = $query->paginate(10)->withQueryString();

        // Get unique sumber for filter
        $sumberList = HargaEmasPerak::distinct('sumber')->pluck('sumber')->filter()->values();

        return view('superadmin.harga-emas-perak.index', compact('hargaEmasPerak', 'sumberList'));
    }

    public function create()
    {
        return view('superadmin.harga-emas-perak.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'harga_emas_pergram' => 'required|numeric|min:0',
            'harga_perak_pergram' => 'required|numeric|min:0',
            'sumber' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        // Check for existing active record for the same date
        if ($request->is_active) {
            $existingActive = HargaEmasPerak::where('tanggal', $request->tanggal)
                ->where('is_active', true)
                ->exists();
            
            if ($existingActive) {
                return back()
                    ->withInput()
                    ->with('warning', 'Sudah ada harga aktif untuk tanggal ini. Harga lama akan dinonaktifkan.');
            }
        }

        try {
            $harga = HargaEmasPerak::create(array_merge($validated, [
                'uuid' => (string) Str::uuid()
            ]));

            return redirect()->route('harga-emas-perak.index')
                ->with('success', 'Data harga emas perak berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($uuid)
    {
        $harga = HargaEmasPerak::where('uuid', $uuid)->firstOrFail();
        return view('superadmin.harga-emas-perak.edit', compact('harga'));
    }

    public function update(Request $request, $uuid)
    {
        $harga = HargaEmasPerak::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'harga_emas_pergram' => 'required|numeric|min:0',
            'harga_perak_pergram' => 'required|numeric|min:0',
            'sumber' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        try {
            // Jika mengaktifkan harga untuk tanggal yang sudah ada harga aktif
            if ($request->is_active && $harga->tanggal != $request->tanggal) {
                $existingActive = HargaEmasPerak::where('tanggal', $request->tanggal)
                    ->where('is_active', true)
                    ->where('uuid', '!=', $uuid)
                    ->exists();
                
                if ($existingActive) {
                    return back()
                        ->withInput()
                        ->with('warning', 'Sudah ada harga aktif untuk tanggal ini. Harga lama akan dinonaktifkan.');
                }
            }

            $harga->update($validated);

            return redirect()->route('harga-emas-perak.index')
                ->with('success', 'Data harga emas perak berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($uuid)
    {
        $harga = HargaEmasPerak::where('uuid', $uuid)->firstOrFail();

        try {
            $harga->delete();

            return redirect()->route('harga-emas-perak.index')
                ->with('success', 'Data harga emas perak berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function toggleStatus($uuid)
    {
        $harga = HargaEmasPerak::where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();
        try {
            // Jika akan diaktifkan, nonaktifkan semua harga untuk tanggal yang sama
            if (!$harga->is_active) {
                HargaEmasPerak::where('tanggal', $harga->tanggal)
                    ->where('uuid', '!=', $uuid)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $harga->update(['is_active' => !$harga->is_active]);

            DB::commit();

            return redirect()->route('harga-emas-perak.index')
                ->with('success', 'Status harga emas perak berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());
        }
    }

    public function getSumberList()
    {
        $sumberList = HargaEmasPerak::distinct('sumber')
            ->whereNotNull('sumber')
            ->pluck('sumber')
            ->sort()
            ->values();

        return response()->json($sumberList);
    }
}