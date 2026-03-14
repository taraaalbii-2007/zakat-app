<?php

namespace App\Http\Controllers\Muzakki;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Models\TransaksiPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimoniMuzakkiController extends Controller
{
    protected $user;
    protected $muzakki;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user    = Auth::user();
            $this->muzakki = $this->user?->muzakki;

            if (!$this->user || !$this->user->isMuzakki() || !$this->muzakki) {
                abort(403);
            }

            return $next($request);
        });
    }

    /**
     * Halaman daftar testimoni milik muzakki ini.
     */
    public function index()
    {
        $testimonis = Testimoni::where('muzakki_id', $this->muzakki->id)
            ->latest()
            ->paginate(10);
        
        $breadcrumbs = [
            'Rating Saya' => route('muzakki.testimoni.index'),
        ];

        return view('muzakki.testimoni.index', compact('testimonis', 'breadcrumbs'));
    }

    /**
     * Form tulis testimoni baru.
     */
    public function create()
    {
        // Ambil transaksi terakhir yang sudah verified (untuk referensi)
        $transaksiTerakhir = TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)
            ->where('status', 'verified')
            ->latest()
            ->first();
        
        $breadcrumbs = [
            'Rating Saya' => route('muzakki.testimoni.index'),
            'Buat Rating' => route('muzakki.testimoni.create')
        ];

        return view('muzakki.testimoni.create', compact('transaksiTerakhir', 'breadcrumbs'));
    }

    /**
     * Simpan testimoni baru.
     * Muzakki boleh submit berkali-kali (tidak ada batasan).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'pekerjaan'     => 'nullable|string|max:100',
            'isi_testimoni' => 'required|string|min:10|max:500',
            'rating'        => 'required|integer|min:1|max:5',
        ], [
            'isi_testimoni.min'  => 'Testimoni minimal 10 karakter.',
            'isi_testimoni.max'  => 'Testimoni maksimal 500 karakter.',
            'rating.required'    => 'Rating wajib dipilih.',
        ]);

        // Ambil transaksi terakhir verified untuk referensi
        $transaksiTerakhir = TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)
            ->where('status', 'verified')
            ->latest()
            ->first();

        Testimoni::create([
            'muzakki_id'    => $this->muzakki->id,
            'transaksi_id'  => $transaksiTerakhir?->id,
            'nama_pengirim' => $request->nama_pengirim,
            'pekerjaan'     => $request->pekerjaan,
            'isi_testimoni' => $request->isi_testimoni,
            'rating'        => $request->rating,
            'is_approved'   => false, // menunggu persetujuan superadmin
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Testimoni berhasil dikirim! Menunggu persetujuan admin.']);
        }

        return redirect()->route('muzakki.testimoni.index')
            ->with('success', 'Testimoni berhasil dikirim! Menunggu persetujuan admin.');
    }

    /**
     * Cek apakah muzakki sudah pernah bertransaksi dan belum pernah isi testimoni sama sekali.
     * Dipakai oleh pop-up setelah transaksi pertama.
     */
    public function checkPopup()
    {
        $sudahTransaksi = TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)
            ->where('status', 'verified')
            ->exists();

        $sudahIsiTestimoni = Testimoni::where('muzakki_id', $this->muzakki->id)->exists();

        return response()->json([
            'show_popup' => $sudahTransaksi && !$sudahIsiTestimoni,
        ]);
    }
}