<?php

namespace App\Http\Controllers\Admin_masjid;

use App\Http\Controllers\Controller;
use App\Models\RekeningMasjid;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RekeningMasjidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        if (!$masjidId) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan masjid.');
        }

        // Start query
        $query = RekeningMasjid::byMasjid($masjidId)->with('masjid');

        // Search filter
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_bank', 'like', "%{$search}%")
                  ->orWhere('nomor_rekening', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Primary filter
        if ($request->filled('is_primary')) {
            $query->where('is_primary', $request->is_primary);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $rekeningMasjids = $query->paginate(10);

        // Get permissions
        $permissions = $this->getPermissions();

        return view('admin-masjid.rekening-masjid.index', compact('rekeningMasjids', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        if (!$masjidId) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan masjid.');
        }

        $masjid = Masjid::find($masjidId);
        
        return view('admin-masjid.rekening-masjid.create', compact('masjid'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $masjidId = $user->masjid_id;

        if (!$masjidId) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan masjid.');
        }

        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_masjid,nomor_rekening',
            'nama_pemilik' => 'required|string|max:150',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'nomor_rekening.unique' => 'Nomor rekening ini sudah terdaftar.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // If setting as primary, reset all primary first
            if ($request->boolean('is_primary')) {
                RekeningMasjid::byMasjid($masjidId)->update(['is_primary' => false]);
            }

            // Create new account
            $rekening = RekeningMasjid::create([
                'masjid_id' => $masjidId,
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_pemilik' => $request->nama_pemilik,
                'is_primary' => $request->boolean('is_primary'),
                'is_active' => $request->boolean('is_active', true),
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('rekening-masjid.index')
                ->with('success', 'Rekening masjid berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RekeningMasjid $rekeningMasjid)
    {
        $this->authorize('view', $rekeningMasjid);

        return view('admin-masjid.rekening-masjid.show', compact('rekeningMasjid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekeningMasjid $rekeningMasjid)
    {
        $this->authorize('update', $rekeningMasjid);

        $user = auth()->user();
        $masjidId = $user->masjid_id;
        $masjid = Masjid::find($masjidId);

        return view('admin-masjid.rekening-masjid.edit', compact('rekeningMasjid', 'masjid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekeningMasjid $rekeningMasjid)
    {
        $this->authorize('update', $rekeningMasjid);

        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_masjid,nomor_rekening,' . $rekeningMasjid->id,
            'nama_pemilik' => 'required|string|max:150',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'nomor_rekening.unique' => 'Nomor rekening ini sudah terdaftar.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // If setting as primary, reset all primary first
            if ($request->boolean('is_primary') && !$rekeningMasjid->is_primary) {
                RekeningMasjid::byMasjid($rekeningMasjid->masjid_id)
                    ->where('id', '!=', $rekeningMasjid->id)
                    ->update(['is_primary' => false]);
            }

            // Update account
            $rekeningMasjid->update([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_pemilik' => $request->nama_pemilik,
                'is_primary' => $request->boolean('is_primary'),
                'is_active' => $request->boolean('is_active'),
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('rekening-masjid.index')
                ->with('success', 'Rekening masjid berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekeningMasjid $rekeningMasjid)
    {
        $this->authorize('delete', $rekeningMasjid);

        DB::beginTransaction();
        try {
            // Check if this is primary account
            $isPrimary = $rekeningMasjid->is_primary;
            
            $rekeningMasjid->delete();

            // If deleted account was primary, set another active account as primary
            if ($isPrimary) {
                $newPrimary = RekeningMasjid::byMasjid($rekeningMasjid->masjid_id)
                    ->active()
                    ->first();
                
                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                }
            }

            DB::commit();

            return redirect()->route('rekening-masjid.index')
                ->with('success', 'Rekening masjid berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(RekeningMasjid $rekeningMasjid)
    {
        $this->authorize('update', $rekeningMasjid);

        $rekeningMasjid->update([
            'is_active' => !$rekeningMasjid->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status rekening berhasil diubah.',
            'is_active' => $rekeningMasjid->is_active
        ]);
    }

    /**
     * Set as primary account
     */
    public function setPrimary(RekeningMasjid $rekeningMasjid)
    {
        $this->authorize('update', $rekeningMasjid);

        DB::beginTransaction();
        try {
            // Reset all primary first
            RekeningMasjid::byMasjid($rekeningMasjid->masjid_id)
                ->update(['is_primary' => false]);

            // Set new primary
            $rekeningMasjid->update(['is_primary' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rekening berhasil diatur sebagai utama.',
                'is_primary' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user permissions
     */
    private function getPermissions(): array
    {
        $user = auth()->user();
        
        return [
            'canCreate' => true, // Admin masjid can always create
            'canEdit' => true,
            'canDelete' => true,
            'canToggleActive' => true,
            'canSetPrimary' => true,
            'userRole' => $user->peran ?? 'admin_masjid'
        ];
    }
}