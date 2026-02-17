<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::with('pengguna')
            ->latest('created_at');

        // Filter berdasarkan pencarian
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // Filter berdasarkan aktivitas
        if ($request->filled('aktivitas')) {
            $query->byAktivitas($request->aktivitas);
        }

        // Filter berdasarkan modul
        if ($request->filled('modul')) {
            $query->byModul($request->modul);
        }

        // Filter berdasarkan peran
        if ($request->filled('peran')) {
            $query->byPeran($request->peran);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->byTanggal($request->tanggal);
        }

        // Pagination
        $logs = $query->paginate(10);

        // Data untuk filter dropdown
        $aktivitasList = LogAktivitas::select('aktivitas')
            ->distinct()
            ->orderBy('aktivitas')
            ->pluck('aktivitas');

        $modulList = LogAktivitas::select('modul')
            ->distinct()
            ->orderBy('modul')
            ->pluck('modul');

        $peranList = LogAktivitas::select('peran')
            ->distinct()
            ->orderBy('peran')
            ->pluck('peran');

        return view('superadmin.log-aktivitas.index', compact(
            'logs',
            'aktivitasList',
            'modulList',
            'peranList'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $log = LogAktivitas::with('pengguna')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('superadmin.log-aktivitas.show', compact('log'));
    }

    /**
     * Export log aktivitas
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality (Excel/PDF)
        // Untuk sementara redirect back
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan');
    }

    /**
     * Delete old logs (cleanup)
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'older_than' => 'required|integer|min:30',
        ]);

        $olderThan = $request->older_than;
        $date = now()->subDays($olderThan);

        $deleted = LogAktivitas::where('created_at', '<', $date)->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$deleted} log aktivitas yang lebih lama dari {$olderThan} hari");
    }
}