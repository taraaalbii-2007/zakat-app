<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use App\Mail\KontakBalasanMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KontakSuperadminController extends Controller
{
    public function index(Request $request)
    {
        $query = Kontak::latest();

        if ($request->filled('status')) {
            match ($request->status) {
                'baru'    => $query->whereNull('dibaca_at'),
                'dibaca'  => $query->whereNotNull('dibaca_at')->whereNull('dibalas_at'),
                'dibalas' => $query->whereNotNull('dibalas_at'),
                default   => null,
            };
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('subjek', 'like', "%{$q}%");
            });
        }

        $kontaks      = $query->paginate(15)->withQueryString();
        $totalBaru    = Kontak::whereNull('dibaca_at')->count();
        $totalDibaca  = Kontak::whereNotNull('dibaca_at')->whereNull('dibalas_at')->count();
        $totalDibalas = Kontak::whereNotNull('dibalas_at')->count();

        return view('superadmin.kontak.index', compact(
            'kontaks',
            'totalBaru',
            'totalDibaca',
            'totalDibalas'
        ));
    }

    public function show(Kontak $kontak)
    {
        $kontak->tandaiDibaca();

        return view('superadmin.kontak.show', compact('kontak'));
    }

    public function balas(Request $request, Kontak $kontak)
    {
        $request->validate([
            'balasan' => 'required|string|max:10000',
        ], [
            'balasan.required' => 'Isi balasan wajib diisi.',
        ]);

        try {
            Mail::to($kontak->email, $kontak->nama)
                ->send(new KontakBalasanMail($kontak, $request->balasan));

            $kontak->update([
                'balasan'    => $request->balasan,
                'dibalas_at' => now(),
            ]);

            return redirect()
                ->route('superadmin.kontak.show', $kontak)
                ->with('success', 'Balasan berhasil dikirim ke ' . $kontak->email);

        } catch (\Throwable $e) {
            \Log::error('Gagal kirim email kontak: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function destroy(Kontak $kontak)
    {
        $kontak->delete();

        return redirect()
            ->route('superadmin.kontak.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }

    public function tandaiBelumDibaca(Kontak $kontak)
    {
        $kontak->update(['dibaca_at' => null]);

        return back()->with('success', 'Pesan ditandai sebagai belum dibaca.');
    }
}