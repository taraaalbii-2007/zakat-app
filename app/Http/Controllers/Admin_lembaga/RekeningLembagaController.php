<?php

namespace App\Http\Controllers\Admin_lembaga;

use App\Http\Controllers\Controller;
use App\Models\RekeningLembaga;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RekeningLembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $lembagaId = $user->lembaga_id;

        if (!$lembagaId) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan lembaga.');
        }

        // Start query
        $query = RekeningLembaga::byLembaga($lembagaId)->with('lembaga');

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

        $rekeningLembagas = $query->paginate(10);

        // Get permissions
        $permissions = $this->getPermissions();

        $breadcrumbs = [
            'Kelola Rekening Lembaga' => route('rekening-lembaga.index'),
        ];

        return view('admin-lembaga.rekening-lembaga.index', compact('rekeningLembagas', 'permissions', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $lembagaId = $user->lembaga_id;

        if (!$lembagaId) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan lembaga.');
        }

        $lembaga = Lembaga::find($lembagaId);

        $breadcrumbs = [
            'Kelola Rekening Lembaga' => route('rekening-lembaga.index'),
            'Tambah Rekening Lembaga' => route('rekening-lembaga.create')
        ];
        
        return view('admin-lembaga.rekening-lembaga.create', compact('lembaga', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $lembagaId = $user->lembaga_id;

        if (!$lembagaId) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terhubung dengan lembaga.');
        }

        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_lembaga,nomor_rekening',
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
                RekeningLembaga::byLembaga($lembagaId)->update(['is_primary' => false]);
            }

            // Create new account
            $rekening = RekeningLembaga::create([
                'lembaga_id' => $lembagaId,
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_pemilik' => $request->nama_pemilik,
                'is_primary' => $request->boolean('is_primary'),
                'is_active' => $request->boolean('is_active', true),
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('rekening-lembaga.index')
                ->with('success', 'Rekening lembaga berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekeningLembaga $rekeningLembaga)
    {
        $this->authorize('update', $rekeningLembaga);

        $user = auth()->user();
        $lembagaId = $user->lembaga_id;
        $lembaga = Lembaga::find($lembagaId);

        $breadcrumbs = [
            'Kelola Rekening Lembaga' => route('rekening-lembaga.index'),
            'Edit Rekening Lembaga' => route('rekening-lembaga.edit', $rekeningLembaga)
        ];

        return view('admin-lembaga.rekening-lembaga.edit', compact('rekeningLembaga', 'lembaga', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekeningLembaga $rekeningLembaga)
    {
        $this->authorize('update', $rekeningLembaga);

        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50|unique:rekening_lembaga,nomor_rekening,' . $rekeningLembaga->id,
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
            if ($request->boolean('is_primary') && !$rekeningLembaga->is_primary) {
                RekeningLembaga::byLembaga($rekeningLembaga->lembaga_id)
                    ->where('id', '!=', $rekeningLembaga->id)
                    ->update(['is_primary' => false]);
            }

            // Update account
            $rekeningLembaga->update([
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_pemilik' => $request->nama_pemilik,
                'is_primary' => $request->boolean('is_primary'),
                'is_active' => $request->boolean('is_active'),
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('rekening-lembaga.index')
                ->with('success', 'Rekening lembaga berhasil diperbarui.');

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
    public function destroy(RekeningLembaga $rekeningLembaga)
    {
        $this->authorize('delete', $rekeningLembaga);

        DB::beginTransaction();
        try {
            // Check if this is primary account
            $isPrimary = $rekeningLembaga->is_primary;
            
            $rekeningLembaga->delete();

            // If deleted account was primary, set another active account as primary
            if ($isPrimary) {
                $newPrimary = RekeningLembaga::byLembaga($rekeningLembaga->lembaga_id)
                    ->active()
                    ->first();
                
                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                }
            }

            DB::commit();

            return redirect()->route('rekening-lembaga.index')
                ->with('success', 'Rekening lembaga berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(RekeningLembaga $rekeningLembaga)
    {
        $this->authorize('update', $rekeningLembaga);

        $rekeningLembaga->update([
            'is_active' => !$rekeningLembaga->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status rekening berhasil diubah.',
            'is_active' => $rekeningLembaga->is_active
        ]);
    }

    /**
     * Set as primary account
     */
    public function setPrimary(RekeningLembaga $rekeningLembaga)
    {
        $this->authorize('update', $rekeningLembaga);

        DB::beginTransaction();
        try {
            // Reset all primary first
            RekeningLembaga::byLembaga($rekeningLembaga->lembaga_id)
                ->update(['is_primary' => false]);

            // Set new primary
            $rekeningLembaga->update(['is_primary' => true]);

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
            'canCreate' => true, // Admin lembaga can always create
            'canEdit' => true,
            'canDelete' => true,
            'canToggleActive' => true,
            'canSetPrimary' => true,
            'userRole' => $user->peran ?? 'admin_lembaga'
        ];
    }
}