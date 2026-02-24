<?php

namespace App\Http\Controllers\Muzakki;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenerimaan;
use App\Models\JenisZakat;
use App\Models\TipeZakat;
use App\Models\ProgramZakat;
use App\Models\Amil;
use App\Models\RekeningMasjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/**
 * TransaksiZakatController untuk Muzakki
 * 
 * VERSI: FIXED (Without Notification - Comment out notifikasi call)
 * 
 * Controller untuk mengelola transaksi zakat dari perspektif muzakki.
 * Muzakki dapat memilih 2 metode:
 * - DARING: Transfer/QRIS ke rekening masjid, tunggu konfirmasi amil
 * - DIJEMPUT: Amil datang ke lokasi, ambil zakat tunai/beras
 * 
 * VALIDASI PENTING:
 * - Metode DARING hanya untuk transfer/QRIS, TIDAK boleh beras tunai
 * - Untuk beras, HARUS gunakan metode DIJEMPUT
 */
class TransaksiZakatController extends Controller
{
    protected $user;
    protected $muzakki;
    protected $masjid;

    const NOMINAL_ZAKAT_FITRAH_PER_JIWA = 50000;
    const BERAS_KG_PER_JIWA = 2.5;
    const BERAS_LITER_PER_JIWA = 3.5;

    /**
     * Constructor dengan middleware
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            // Pastikan user adalah muzakki
            if (!$this->user || !$this->user->isMuzakki()) {
                abort(403, 'Hanya muzakki yang dapat mengakses halaman ini.');
            }

            // Pastikan profil muzakki lengkap
            $this->muzakki = $this->user->muzakki;
            if (!$this->muzakki) {
                return redirect()->route('dashboard')
                    ->with('error', 'Profil muzakki belum dilengkapi. Silakan lengkapi profil terlebih dahulu.');
            }

            // Pastikan muzakki sudah pilih masjid
            $this->masjid = $this->muzakki->masjid;
            if (!$this->masjid) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda belum memilih masjid. Silakan lengkapi profil terlebih dahulu.');
            }

            // Share dengan view
            view()->share([
                'masjid' => $this->masjid,
                'muzakki' => $this->muzakki,
                'zakatFitrahInfo' => [
                    'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
                    'beras_kg' => self::BERAS_KG_PER_JIWA,
                    'beras_liter' => self::BERAS_LITER_PER_JIWA,
                ],
            ]);

            return $next($request);
        });
    }

    /**
     * INDEX — Riwayat transaksi muzakki
     */
    public function index(Request $request)
    {
        $query = TransaksiPenerimaan::with([
            'jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna'
        ])->where('muzakki_id', $this->muzakki->id);

        // Filter
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_transaksi', 'like', '%' . $request->q . '%')
                    ->orWhere('muzakki_nama', 'like', '%' . $request->q . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_zakat_id')) {
            $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $query->orderBy('created_at', 'desc');
        $transaksis = $query->paginate(15);
        $jenisZakatList = JenisZakat::orderBy('nama')->get();

        // Stats
        $stats = [
            'total' => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->count(),
            'total_verified' => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->where('status', 'verified')->count(),
            'total_pending' => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->where('status', 'pending')->count(),
            'total_nominal' => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->where('status', 'verified')->sum('jumlah'),
            'total_infaq' => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->where('status', 'verified')->sum('jumlah_infaq'),
        ];

        return view('muzakki.transaksi-daring-muzakki.index', compact('transaksis', 'jenisZakatList', 'stats'));
    }

    /**
     * CREATE — Tampilkan form create dengan pilihan metode
     */
    public function create()
    {
        // Load data untuk form
        $rekeningList = RekeningMasjid::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)->get();

        $jenisZakatList = JenisZakat::all();

        $programZakatList = ProgramZakat::where('masjid_id', $this->masjid->id)
            ->where('status', 'aktif')->orderBy('nama_program')->get();

        $amilList = Amil::where('masjid_id', $this->masjid->id)
            ->where('status', 'aktif')->with('pengguna')->get();

        // Build tipe zakat array grouped by jenis
        $tipeZakatList = [];
        foreach ($jenisZakatList as $jenis) {
            $tipeZakatList[$jenis->id] = TipeZakat::all()
                ->map(function ($tipe) {
                    return [
                        'uuid' => $tipe->uuid,
                        'id' => $tipe->id,
                        'nama' => $tipe->nama,
                        'persentase_zakat' => $tipe->persentase_zakat ?? 2.5,
                    ];
                })
                ->toArray();
        }

        // Prefill dari data muzakki
        $muzakkiData = [
            'nama' => $this->muzakki->nama,
            'telepon' => $this->muzakki->telepon,
            'email' => $this->muzakki->email,
            'alamat' => $this->muzakki->alamat,
            'nik' => $this->muzakki->nik,
        ];

        $zakatFitrahInfo = [
            'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
            'beras_kg' => self::BERAS_KG_PER_JIWA,
            'beras_liter' => self::BERAS_LITER_PER_JIWA,
        ];

        return view('muzakki.transaksi-daring-muzakki.create', compact(
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'tipeZakatList',
            'rekeningList',
            'zakatFitrahInfo',
            'muzakkiData'
        ));
    }

    /**
     * STORE — Simpan transaksi muzakki
     */
    public function store(Request $request)
    {
        $metode = $request->metode_penerimaan;
        $isDijemput = $metode === 'dijemput';
        $isDaring = $metode === 'daring';

        Log::info('Muzakki Transaksi Store', [
            'muzakki_id' => $this->muzakki->id,
            'masjid_id' => $this->masjid->id,
            'metode' => $metode,
        ]);

        // ════════════════════════════════════════════════════════════
        // VALIDASI
        // ════════════════════════════════════════════════════════════

        if (!in_array($metode, ['daring', 'dijemput'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Metode penerimaan tidak valid.');
        }

        $rules = $this->getValidationRules($request, $isDaring, $isDijemput);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        // ════════════════════════════════════════════════════════════
        // SIMPAN KE DATABASE
        // ════════════════════════════════════════════════════════════

        try {
            DB::beginTransaction();

            $transaksi = new TransaksiPenerimaan();
            $transaksi->masjid_id = $this->masjid->id;
            $transaksi->muzakki_id = $this->muzakki->id;
            $transaksi->diinput_muzakki = true;
            $transaksi->no_transaksi = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi ?? now()->format('Y-m-d');
            $transaksi->waktu_transaksi = now();
            $transaksi->muzakki_nama = $request->muzakki_nama;
            $transaksi->muzakki_telepon = $request->muzakki_telepon;
            $transaksi->muzakki_email = $request->muzakki_email;
            $transaksi->muzakki_alamat = $request->muzakki_alamat;
            $transaksi->muzakki_nik = $request->muzakki_nik;
            $transaksi->metode_penerimaan = $metode;
            $transaksi->keterangan = $request->keterangan;

            if ($isDijemput) {
                // DIJEMPUT: minimal data untuk penjemputan
                $transaksi->latitude = $request->latitude;
                $transaksi->longitude = $request->longitude;
                $transaksi->amil_id = $request->amil_id;
                $transaksi->status = 'pending';
                $transaksi->status_penjemputan = 'menunggu';
                $transaksi->waktu_request = now();
                $transaksi->jumlah = 0;
                $transaksi->jumlah_dibayar = 0;
                $transaksi->jumlah_infaq = 0;
            } else {
                // DARING: detail zakat + pembayaran
                $this->isiDetailZakatDaring($transaksi, $request);
                $this->isiMetodePembayaranDaring($transaksi, $request);
            }

            $transaksi->save();

            // ════════════════════════════════════════════════════════════
            // NOTIFIKASI (Commented out - implement later with notification class)
            // ════════════════════════════════════════════════════════════
            // TODO: Create NotifikasiTransaksiZakatAmil & NotifikasiTransaksiZakatMuzakki
            // $this->kirimNotifikasi($transaksi, $request);

            DB::commit();

            Log::info('Muzakki transaksi saved', ['no' => $transaksi->no_transaksi]);

            // ════════════════════════════════════════════════════════════
            // REDIRECT
            // ════════════════════════════════════════════════════════════

            if ($isDijemput) {
                $message = 'Request penjemputan berhasil dikirim. Amil akan menghubungi Anda di ' . $transaksi->muzakki_telepon . '.';
            } else {
                $infaqMsg = ($transaksi->jumlah_infaq ?? 0) > 0
                    ? ' (Termasuk infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ')'
                    : '';
                $message = 'Transaksi zakat berhasil dikirim: ' . $transaksi->no_transaksi . $infaqMsg . '. Menunggu konfirmasi dari amil.';
            }

            return redirect()->route('transaksi-daring-muzakki.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Muzakki store error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * SHOW — Detail transaksi muzakki
     */
    public function show($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid', 'jenisZakat', 'tipeZakat', 'programZakat',
            'amil.pengguna', 'verifiedBy'
        ])
            ->where('uuid', $uuid)
            ->where('muzakki_id', $this->muzakki->id)
            ->firstOrFail();

        return view('muzakki.transaksi-daring-muzakki.show', compact('transaksi'));
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ════════════════════════════════════════════════════════════════════════════════════

    /**
     * GET VALIDATION RULES
     */
    private function getValidationRules(Request $request, $isDaring, $isDijemput)
    {
        $rules = [
            'tanggal_transaksi' => 'nullable|date',
            'muzakki_nama' => 'required|string|max:255',
            'muzakki_telepon' => 'nullable|string|max:20',
            'muzakki_email' => 'nullable|email|max:255',
            'muzakki_alamat' => 'nullable|string',
            'muzakki_nik' => 'nullable|string|size:16',
            'metode_penerimaan' => 'required|in:daring,dijemput',
            'keterangan' => 'nullable|string',
        ];

        if ($isDijemput) {
            $rules['latitude'] = 'required|numeric';
            $rules['longitude'] = 'required|numeric';
            $rules['amil_id'] = 'nullable|exists:amil,id';
        } elseif ($isDaring) {
            $rules['jenis_zakat_id'] = 'required|exists:jenis_zakat,id';
            $rules['tipe_zakat_id'] = 'required|exists:tipe_zakat,uuid';
            $rules['program_zakat_id'] = 'nullable|exists:program_zakat,id';
            $rules['is_pembayaran_beras'] = 'nullable|boolean';
            $rules['jumlah_jiwa'] = 'nullable|integer|min:1';
            $rules['nominal_per_jiwa'] = 'nullable|numeric|min:0';
            $rules['jumlah_beras_kg'] = 'nullable|numeric|min:0';
            $rules['harga_beras_per_kg'] = 'nullable|numeric|min:0';
            $rules['nilai_harta'] = 'nullable|numeric|min:0';
            $rules['nisab_saat_ini'] = 'nullable|numeric|min:0';
            $rules['sudah_haul'] = 'nullable|boolean';
            $rules['tanggal_mulai_haul'] = 'nullable|date';
            $rules['jumlah_dibayar'] = 'nullable|numeric|min:0';
            $rules['metode_pembayaran'] = 'required|in:transfer,qris';
            $rules['bukti_transfer'] = 'nullable|image|max:2048';
            $rules['no_referensi_transfer'] = 'nullable|string|max:100';
        }

        return $rules;
    }

    /**
     * ISI DETAIL ZAKAT DARING
     */
    private function isiDetailZakatDaring(TransaksiPenerimaan $transaksi, Request $request)
    {
        $jenisZakat = JenisZakat::findOrFail($request->jenis_zakat_id);
        $tipeZakat = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();

        // Validasi tipe sesuai jenis
        if ($tipeZakat->jenis_zakat_id != $request->jenis_zakat_id) {
            throw new \Exception('Tipe zakat tidak sesuai dengan jenis zakat yang dipilih.');
        }

        $isFitrah = stripos($jenisZakat->nama, 'fitrah') !== false;
        $isMal = stripos($jenisZakat->nama, 'mal') !== false;
        $isBeras = stripos($tipeZakat->nama, 'beras') !== false;

        // VALIDASI KRITIS: Daring + Fitrah + Beras = REJECT
        if ($isFitrah && $isBeras) {
            throw new \Exception(
                'Metode daring hanya tersedia untuk pembayaran transfer/QRIS. ' .
                'Untuk pembayaran zakat fitrah dengan beras, silakan gunakan metode dijemput.'
            );
        }

        $jumlah = 0;

        if ($isFitrah) {
            // Fitrah numerik (transfer/QRIS)
            if (!$request->filled('jumlah_jiwa') || !$request->filled('nominal_per_jiwa')) {
                throw new \Exception('Jumlah jiwa dan nominal per jiwa harus diisi untuk zakat fitrah.');
            }
            $jumlah = (int) $request->jumlah_jiwa * (float) $request->nominal_per_jiwa;
            $transaksi->jumlah_jiwa = $request->jumlah_jiwa;
            $transaksi->nominal_per_jiwa = $request->nominal_per_jiwa;
        } elseif ($isMal) {
            // Mal berdasarkan nilai harta
            if (!$request->filled('nilai_harta')) {
                throw new \Exception('Nilai harta harus diisi untuk zakat mal.');
            }
            $nilaiHarta = (float) $request->nilai_harta;
            $persentase = (float) ($tipeZakat->persentase_zakat ?? 2.5);
            $jumlah = $nilaiHarta * ($persentase / 100);

            $transaksi->nilai_harta = $nilaiHarta;
            $transaksi->nisab_saat_ini = $request->nisab_saat_ini;
            $transaksi->sudah_haul = $request->boolean('sudah_haul', false);
            $transaksi->tanggal_mulai_haul = $request->tanggal_mulai_haul;
        }

        if ($jumlah <= 0) {
            throw new \Exception('Jumlah zakat tidak valid. Periksa kembali data yang diisi.');
        }

        $transaksi->jenis_zakat_id = $request->jenis_zakat_id;
        $transaksi->tipe_zakat_id = $tipeZakat->id;
        $transaksi->program_zakat_id = $request->program_zakat_id;
        $transaksi->jumlah = round($jumlah);
    }

    /**
     * ISI METODE PEMBAYARAN DARING
     */
    private function isiMetodePembayaranDaring(TransaksiPenerimaan $transaksi, Request $request)
    {
        $metode = $request->metode_pembayaran ?? 'transfer';
        $transaksi->metode_pembayaran = $metode;

        // Hitung infaq
        $jumlahZakat = (float) $transaksi->jumlah;
        $jumlahDibayar = $request->filled('jumlah_dibayar')
            ? (float) $request->jumlah_dibayar
            : $jumlahZakat;

        if ($jumlahDibayar <= 0) {
            $jumlahDibayar = $jumlahZakat;
        }

        $infaq = max(0, $jumlahDibayar - $jumlahZakat);

        $transaksi->jumlah_dibayar = $jumlahDibayar;
        $transaksi->jumlah_infaq = $infaq;
        $transaksi->has_infaq = $infaq > 0;

        // Status pending, tunggu konfirmasi amil
        $transaksi->status = 'pending';
        $transaksi->konfirmasi_status = 'menunggu_konfirmasi';
        $transaksi->no_referensi_transfer = $request->no_referensi_transfer;

        // Upload bukti jika ada
        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')
                ->store('bukti-transfer', 'public');
            $transaksi->bukti_transfer = $path;
        }
    }
}