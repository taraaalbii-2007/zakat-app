<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\JenisZakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JenisZakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JenisZakat::query();
        
        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%");
        }
        
        // Sorting - Data terbaru paling atas
        $query->orderBy('created_at', 'desc');
        
        $jenisZakat = $query->paginate(10);
        
        return view('superadmin.jenis-zakat.index', compact('jenisZakat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.jenis-zakat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:jenis_zakat,nama',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data Anda.');
        }

        try {
            JenisZakat::create([
                'nama' => $request->nama
            ]);
            
            return redirect()->route('jenis-zakat.index')
                ->with('success', 'Jenis zakat berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jenis zakat. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $jenisZakat = JenisZakat::where('uuid', $uuid)->firstOrFail();
        
        return view('superadmin.jenis-zakat.edit', compact('jenisZakat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $jenisZakat = JenisZakat::where('uuid', $uuid)->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_zakat', 'nama')->ignore($jenisZakat->id)
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data Anda.');
        }

        try {
            $jenisZakat->update([
                'nama' => $request->nama
            ]);
            
            return redirect()->route('jenis-zakat.index')
                ->with('success', 'Jenis zakat berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jenis zakat. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $jenisZakat = JenisZakat::where('uuid', $uuid)->firstOrFail();
        
        try {
            $jenisZakat->delete();
            
            return redirect()->route('jenis-zakat.index')
                ->with('success', 'Jenis zakat berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('jenis-zakat.index')
                ->with('error', 'Gagal menghapus jenis zakat. Error: ' . $e->getMessage());
        }
    }
}