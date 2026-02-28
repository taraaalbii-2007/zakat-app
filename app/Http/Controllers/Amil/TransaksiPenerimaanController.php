<?php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenerimaan;
use App\Models\JenisZakat;
use App\Models\TipeZakat;
use App\Models\ProgramZakat;
use App\Models\Amil;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Exports\TransaksiPenerimaanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KonfigurasiQris;

class TransaksiPenerimaanController extends Controller
{
    protected $user;
    protected $masjid;
    protected $amil;

    // Konstanta zakat fitrah dari BAZNAS
    const NOMINAL_ZAKAT_FITRAH_PER_JIWA = 50000;  // Rp 50.000 per jiwa (BAZNAS 2024)
    const BERAS_KG_PER_JIWA             = 2.5;     // 2,5 kg per jiwa
    const BERAS_LITER_PER_JIWA          = 3.5;     // 3,5 liter per jiwa

    // Konstanta fidyah
    const FIDYAH_BERAT_PER_HARI_GRAM = 675; // 1 mud = 675 gram

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user) {
                abort(403, 'Unauthorized');
            }

            // Inisialisasi awal
            $this->amil = null;
            $this->masjid = null;

            // Dapatkan masjid_id dari user
            $masjidId = $this->user->masjid_id;

            if ($this->user->isAdminMasjid()) {
                // Admin masjid: langsung ambil masjid dari ID user
                if ($masjidId) {
                    $this->masjid = Masjid::find($masjidId);
                }
                $this->amil = null;
            } elseif ($this->user->isAmil()) {
                // Amil: coba ambil dari relasi amil
                $this->amil = $this->user->amil;

                // Coba ambil masjid dari amil
                if ($this->amil && $this->amil->masjid_id) {
                    $this->masjid = Masjid::find($this->amil->masjid_id);
                }

                // Fallback ke masjid user jika masih null
                if (!$this->masjid && $masjidId) {
                    $this->masjid = Masjid::find($masjidId);
                }
            } elseif ($this->user->isMuzakki()) {
                // Muzakki: coba ambil dari relasi muzakki
                $muzakki = $this->user->muzakki;

                if ($muzakki && $muzakki->masjid_id) {
                    $this->masjid = Masjid::find($muzakki->masjid_id);
                }

                // Fallback ke masjid user
                if (!$this->masjid && $masjidId) {
                    $this->masjid = Masjid::find($masjidId);
                }

                $this->amil = null;
            } else {
                abort(403, 'Akses ditolak');
            }

            // Final check: pastikan masjid ditemukan
            if (!$this->masjid) {
                Log::error('Masjid tidak ditemukan untuk user', [
                    'user_id' => $this->user->id,
                    'peran' => $this->user->peran,
                    'masjid_id_user' => $masjidId,
                    'amil_data' => $this->user->amil ? 'ada' : 'tidak ada',
                    'muzakki_data' => $this->user->muzakki ? 'ada' : 'tidak ada',
                ]);
                abort(404, 'Data masjid tidak ditemukan. Silakan hubungi administrator.');
            }

            // Share data ke view
            view()->share('masjid', $this->masjid);
            view()->share('zakatFitrahInfo', [
                'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
                'beras_kg'         => self::BERAS_KG_PER_JIWA,
                'beras_liter'      => self::BERAS_LITER_PER_JIWA,
            ]);

            // Share konstanta fidyah ke view
            view()->share('fidyahInfo', [
                'berat_per_hari_gram' => self::FIDYAH_BERAT_PER_HARI_GRAM,
            ]);

            return $next($request);
        });
    }

    // ================================================================
    // HELPER: Tentukan default metode_penerimaan berdasarkan context
    // ================================================================
    protected function getMode(Request $request, string $default = 'datang_langsung'): string
    {
        $mode = $request->get('mode', $default);
        $allowed = ['datang_langsung', 'dijemput', 'daring'];
        return in_array($mode, $allowed) ? $mode : $default;
    }

    // ================================================================
    // INDEX — semua transaksi (atau filter per mode)
    // ================================================================
    public function index(Request $request)
    {
        $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil'])
            ->byMasjid($this->masjid->id);

        if ($request->filled('q'))                 $query->search($request->q);
        if ($request->filled('tanggal'))           $query->byTanggal($request->tanggal);
        if ($request->filled('start_date') && $request->filled('end_date'))
            $query->byPeriode($request->start_date, $request->end_date);
        if ($request->filled('jenis_zakat_id'))    $query->byJenisZakat($request->jenis_zakat_id);
        if ($request->filled('metode_pembayaran')) $query->byMetodePembayaran($request->metode_pembayaran);
        if ($request->filled('status'))            $query->byStatus($request->status);
        if ($request->filled('konfirmasi_status')) $query->byKonfirmasiStatus($request->konfirmasi_status);
        if ($request->filled('tahun')) $query->whereYear('tanggal_transaksi', $request->tahun);
        if ($request->filled('metode_penerimaan')) $query->byMetodePenerimaan($request->metode_penerimaan);

        // Filter fidyah
        if ($request->filled('fidyah_tipe')) {
            $query->where('fidyah_tipe', $request->fidyah_tipe);
        }

        $query->orderBy('created_at', 'desc');

        $transaksis       = $query->paginate(10)->withQueryString();
        $jenisZakatList   = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::byMasjid($this->masjid->id)
            ->whereIn('status', ['aktif', 'draft'])->orderBy('nama_program')->get();
        $amilList         = Amil::byMasjid($this->masjid->id)->with('pengguna')->where('status', 'aktif')->get();

        $stats = [
            'total'               => TransaksiPenerimaan::byMasjid($this->masjid->id)->count(),
            'total_verified'      => TransaksiPenerimaan::byMasjid($this->masjid->id)->verified()->count(),
            'total_pending'       => TransaksiPenerimaan::byMasjid($this->masjid->id)->pending()->count(),
            'menunggu_konfirmasi' => TransaksiPenerimaan::byMasjid($this->masjid->id)->menungguKonfirmasi()->count(),
            'total_nominal'       => TransaksiPenerimaan::byMasjid($this->masjid->id)->verified()->sum('jumlah'),
            'total_hari_ini'      => TransaksiPenerimaan::byMasjid($this->masjid->id)->byTanggal(now())->verified()->sum('jumlah'),
            'total_infaq'         => TransaksiPenerimaan::byMasjid($this->masjid->id)->verified()->sum('jumlah_infaq'),
            // Stats fidyah
            'total_fidyah'        => TransaksiPenerimaan::byMasjid($this->masjid->id)->fidyah()->count(),
        ];

        return view('amil.transaksi-penerimaan.index', compact(
            'transaksis',
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'stats'
        ));
    }

    // ================================================================
    // INDEX DATANG LANGSUNG — hanya mode datang_langsung
    // ================================================================
    public function indexDatangLangsung(Request $request)
    {
        if (!$this->masjid) {
            abort(404, 'Data masjid tidak ditemukan.');
        }

        $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna'])
            ->byMasjid($this->masjid->id)
            ->byMetodePenerimaan('datang_langsung');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('jenis_zakat_id')) {
            $query->byJenisZakat($request->jenis_zakat_id);
        }

        if ($request->filled('metode_pembayaran')) {
            $query->byMetodePembayaran($request->metode_pembayaran);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byPeriode($request->start_date, $request->end_date);
        }

        // Filter fidyah
        if ($request->filled('fidyah_tipe')) {
            $query->where('fidyah_tipe', $request->fidyah_tipe);
        }

        $query->orderBy('created_at', 'desc');

        $transaksis = $query->paginate(10)->withQueryString();

        $jenisZakatList = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::byMasjid($this->masjid->id)
            ->whereIn('status', ['aktif', 'draft'])->orderBy('nama_program')->get();

        $baseQuery = TransaksiPenerimaan::byMasjid($this->masjid->id)
            ->byMetodePenerimaan('datang_langsung');

        $stats = [
            'total' => $baseQuery->count(),
            'tunai' => $baseQuery->where('metode_pembayaran', 'tunai')->count(),
            'non_tunai' => $baseQuery->whereIn('metode_pembayaran', ['transfer', 'qris'])->count(),
            'total_nominal' => $baseQuery->where('status', 'verified')->sum('jumlah'),
            'total_fidyah' => $baseQuery->fidyah()->count(),
        ];

        return view('amil.transaksi-penerimaan.index-datang-langsung', compact(
            'transaksis',
            'jenisZakatList',
            'programZakatList',
            'stats'
        ));
    }

    // ================================================================
    // INDEX DARING — hanya mode daring
    // ================================================================
    public function indexDaring(Request $request)
    {
        $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil'])
            ->byMasjid($this->masjid->id)
            ->byMetodePenerimaan('daring');

        if ($request->filled('q'))                 $query->search($request->q);
        if ($request->filled('start_date') && $request->filled('end_date'))
            $query->byPeriode($request->start_date, $request->end_date);
        if ($request->filled('jenis_zakat_id'))    $query->byJenisZakat($request->jenis_zakat_id);
        if ($request->filled('status'))            $query->byStatus($request->status);
        if ($request->filled('konfirmasi_status')) $query->byKonfirmasiStatus($request->konfirmasi_status);

        $query->orderBy('created_at', 'desc');

        $transaksis       = $query->paginate(10)->withQueryString();
        $jenisZakatList   = JenisZakat::orderBy('nama')->get();

        $stats = [
            'total'               => TransaksiPenerimaan::byMasjid($this->masjid->id)->byMetodePenerimaan('daring')->count(),
            'total_verified'      => TransaksiPenerimaan::byMasjid($this->masjid->id)->byMetodePenerimaan('daring')->verified()->count(),
            'total_pending'       => TransaksiPenerimaan::byMasjid($this->masjid->id)->byMetodePenerimaan('daring')->pending()->count(),
            'menunggu_konfirmasi' => TransaksiPenerimaan::byMasjid($this->masjid->id)->byMetodePenerimaan('daring')->menungguKonfirmasi()->count(),
            'total_nominal'       => TransaksiPenerimaan::byMasjid($this->masjid->id)->byMetodePenerimaan('daring')->verified()->sum('jumlah'),
        ];

        return view('amil.transaksi-penerimaan.index-daring', compact(
            'transaksis',
            'jenisZakatList',
            'stats'
        ));
    }

    // ================================================================
    // INDEX DIJEMPUT — hanya mode dijemput
    // ================================================================
    public function indexDijemput(Request $request)
    {
        if (!$this->masjid) {
            abort(404, 'Data masjid tidak ditemukan.');
        }

        $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna'])
            ->byMasjid($this->masjid->id)
            ->byMetodePenerimaan('dijemput');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('status_penjemputan')) {
            $query->where('status_penjemputan', $request->status_penjemputan);
        }

        if ($request->filled('amil_id')) {
            $query->where('amil_id', $request->amil_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byPeriode($request->start_date, $request->end_date);
        }

        $query->orderBy('created_at', 'desc');

        $transaksis = $query->paginate(10)->withQueryString();

        $amilList = Amil::byMasjid($this->masjid->id)
            ->with('pengguna')
            ->where('status', 'aktif')
            ->get();

        $stats = [
            'total' => TransaksiPenerimaan::byMasjid($this->masjid->id)
                ->byMetodePenerimaan('dijemput')->count(),
            'menunggu' => TransaksiPenerimaan::byMasjid($this->masjid->id)
                ->byMetodePenerimaan('dijemput')
                ->where('status_penjemputan', 'menunggu')->count(),
            'dalam_proses' => TransaksiPenerimaan::byMasjid($this->masjid->id)
                ->byMetodePenerimaan('dijemput')
                ->whereIn('status_penjemputan', ['diterima', 'dalam_perjalanan', 'sampai_lokasi'])->count(),
            'perlu_dilengkapi' => TransaksiPenerimaan::byMasjid($this->masjid->id)
                ->byMetodePenerimaan('dijemput')
                ->whereIn('status_penjemputan', ['sampai_lokasi', 'selesai'])
                ->whereNull('jenis_zakat_id')->count(),
            'selesai' => TransaksiPenerimaan::byMasjid($this->masjid->id)
                ->byMetodePenerimaan('dijemput')
                ->where('status_penjemputan', 'selesai')
                ->whereNotNull('jenis_zakat_id')->count(),
        ];

        return view('amil.transaksi-penerimaan.index-dijemput', compact(
            'transaksis',
            'amilList',
            'stats'
        ));
    }

    // ================================================================
    // CREATE — form multistep
    // ================================================================
    public function create(Request $request)
    {
        // Tentukan default mode berdasarkan role & context
        if ($this->user->isMuzakki()) {
            $defaultMode = 'daring';
        } elseif ($request->is('*/transaksi-dijemput/create')) {
            $defaultMode = 'dijemput';
        } elseif ($request->is('*/transaksi-daring/create')) {
            $defaultMode = 'daring';
        } else {
            $defaultMode = 'datang_langsung';
        }

        $mode = $this->getMode($request, $defaultMode);

        $rekeningList = \App\Models\RekeningMasjid::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)->get();

        $jenisZakatList   = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::byMasjid($this->masjid->id)
            ->whereIn('status', ['aktif'])->orderBy('nama_program')->get();
        $amilList = Amil::byMasjid($this->masjid->id)->with('pengguna')->where('status', 'aktif')->get();

        $noTransaksiPreview = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
        $tanggalHariIni     = now()->format('Y-m-d');

        $tipeZakatList = [];
        foreach ($jenisZakatList as $jenis) {
            $tipeZakatList[$jenis->id] = TipeZakat::where('jenis_zakat_id', $jenis->id)
                ->orderBy('nama')->get()->makeVisible(['id']);
        }

        $zakatFitrahInfo = [
            'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
            'beras_kg'         => self::BERAS_KG_PER_JIWA,
            'beras_liter'      => self::BERAS_LITER_PER_JIWA,
        ];

        $fidyahInfo = [
            'berat_per_hari_gram' => self::FIDYAH_BERAT_PER_HARI_GRAM,
        ];

        $qrisConfig = KonfigurasiQris::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)
            ->first();

        $muzakkiData = null;
        if ($this->user->isMuzakki() && $this->user->muzakki) {
            $m           = $this->user->muzakki;
            $muzakkiData = [
                'nama'    => $m->nama,
                'telepon' => $m->telepon,
                'email'   => $m->email,
                'alamat'  => $m->alamat,
                'nik'     => $m->nik,
            ];
        }

        return view('amil.transaksi-penerimaan.create', compact(
            'mode',
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'noTransaksiPreview',
            'tanggalHariIni',
            'tipeZakatList',
            'rekeningList',
            'zakatFitrahInfo',
            'fidyahInfo',
            'muzakkiData',
            'qrisConfig'
        ));
    }

    // ================================================================
    // CREATE DATANG LANGSUNG — shortcut
    // ================================================================
    public function createDatangLangsung(Request $request)
    {
        $request->merge(['mode' => 'datang_langsung']);
        return $this->create($request);
    }

    // ================================================================
    // CREATE DIJEMPUT — shortcut
    // ================================================================
    public function createDijemput(Request $request)
    {
        $request->merge(['mode' => 'dijemput']);
        return $this->create($request);
    }

    // ================================================================
    // HELPER: Isi data fidyah berdasarkan tipe
    // ================================================================
    protected function isiDataFidyah(TransaksiPenerimaan $transaksi, Request $request): void
    {
        // Reset semua kolom fidyah dulu
        $transaksi->fidyah_jumlah_hari = null;
        $transaksi->fidyah_tipe = null;
        $transaksi->fidyah_nama_bahan = null;
        $transaksi->fidyah_berat_per_hari_gram = self::FIDYAH_BERAT_PER_HARI_GRAM;
        $transaksi->fidyah_total_berat_kg = null;
        $transaksi->fidyah_jumlah_box = null;
        $transaksi->fidyah_menu_makanan = null;
        $transaksi->fidyah_harga_per_box = null;
        $transaksi->fidyah_cara_serah = null;

        // Jika bukan fidyah, stop
        if (!$request->filled('fidyah_jumlah_hari') || $request->fidyah_jumlah_hari <= 0) {
            return;
        }

        // Validasi: Pastikan jenis zakat adalah fidyah
        $jenisZakat = JenisZakat::find($request->jenis_zakat_id);
        if (!$jenisZakat || stripos($jenisZakat->nama, 'fidyah') === false) {
            throw new \Exception('Untuk membayar fidyah, pilih jenis zakat Fidyah');
        }

        // Set jumlah hari
        $transaksi->fidyah_jumlah_hari = $request->fidyah_jumlah_hari;

        // Set tipe fidyah
        $tipe = $request->fidyah_tipe; // 'mentah', 'matang', atau 'tunai'
        $transaksi->fidyah_tipe = $tipe;

        switch ($tipe) {
            case 'mentah': // Bahan Pokok Mentah
                $transaksi->fidyah_nama_bahan = $request->fidyah_nama_bahan;

                // Hitung total berat
                $beratPerHari = $request->fidyah_berat_per_hari_gram ?? self::FIDYAH_BERAT_PER_HARI_GRAM;
                $transaksi->fidyah_berat_per_hari_gram = $beratPerHari;

                $totalGram = $beratPerHari * $request->fidyah_jumlah_hari;
                $transaksi->fidyah_total_berat_kg = $totalGram / 1000;

                // Untuk fidyah bahan mentah, tidak perlu set jumlah (uang)
                $transaksi->jumlah = 0;
                $transaksi->jumlah_dibayar = 0;
                break;

            case 'matang': // Makanan Siap Santap
                $transaksi->fidyah_jumlah_box = $request->fidyah_jumlah_box;
                $transaksi->fidyah_menu_makanan = $request->fidyah_menu_makanan;
                $transaksi->fidyah_harga_per_box = $request->fidyah_harga_per_box;
                $transaksi->fidyah_cara_serah = $request->fidyah_cara_serah;

                // Untuk fidyah makanan matang, tidak perlu set jumlah (uang)
                $transaksi->jumlah = 0;
                $transaksi->jumlah_dibayar = 0;
                break;

            case 'tunai': // Tunai/Uang
                // Untuk tunai, jumlah sudah diisi dari form biasa
                // Pastikan jumlah sudah benar (harga x jumlah hari)
                // atau bisa juga dihitung otomatis di frontend
                break;
        }

        Log::info('Data fidyah diisi', [
            'tipe' => $tipe,
            'jumlah_hari' => $request->fidyah_jumlah_hari,
            'transaksi_no' => $transaksi->no_transaksi
        ]);
    }
    // ================================================================
    // HELPER: Isi metode pembayaran untuk beras
    // ================================================================
    protected function isiMetodePembayaranBeras(TransaksiPenerimaan $transaksi, Request $request): void
    {
        $transaksi->metode_pembayaran = 'beras'; // <-- ubah dari 'tunai' ke 'beras'
        $transaksi->jumlah_dibayar   = 0;
        $transaksi->jumlah_infaq     = 0;
        $transaksi->has_infaq        = false;
        $transaksi->status           = 'verified';
        $transaksi->verified_by      = $this->user->id;
        $transaksi->verified_at      = now();

        Log::info('Transaksi beras disimpan', [
            'no_transaksi' => $transaksi->no_transaksi,
            'jumlah_beras' => $request->jumlah_beras_kg . ' kg',
            'jumlah_jiwa'  => $request->jumlah_jiwa,
        ]);
    }

    // ================================================================
    // SHOW DATANG LANGSUNG - Display details of a datang langsung transaction
    // ================================================================
    public function showDatangLangsung($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
            'verifiedBy',
            'dikonfirmasiOleh'
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->byMetodePenerimaan('datang_langsung')
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.show-datang-langsung', compact('transaksi'));
    }

    // ================================================================
    // STORE DIJEMPUT
    // ================================================================
    public function storeDijemput(Request $request)
    {
        Log::info('Transaksi Dijemput Store', [
            'user_id'   => $this->user->id,
            'masjid_id' => $this->masjid->id,
            'request_data' => $request->except(['_token'])
        ]);

        try {
            // Deteksi apakah ini pembayaran beras
            $isBeras = $request->is_pembayaran_beras == '1';

            // Deteksi fidyah — harus jenis zakat fidyah DAN ada isian fidyah
            $jenisZakatCheck = JenisZakat::find($request->jenis_zakat_id);
            $isFidyah = $request->filled('fidyah_jumlah_hari')
                && $request->fidyah_jumlah_hari > 0
                && $jenisZakatCheck
                && stripos($jenisZakatCheck->nama, 'fidyah') !== false;

            $rules = [
                'tanggal_transaksi'   => 'required|date',
                'muzakki_nama'        => 'required|string|max:255',
                'muzakki_telepon'     => 'nullable|string|max:20',
                'muzakki_email'       => 'nullable|email|max:255',
                'muzakki_alamat'      => 'required|string',
                'muzakki_nik'         => 'nullable|string|size:16',
                'amil_id'             => 'required|exists:amil,id',
                'latitude'            => 'required|numeric',
                'longitude'           => 'required|numeric',
                'tanggal_penjemputan' => 'nullable|date',
                'metode_pembayaran'   => 'nullable|in:tunai,transfer,qris,beras,makanan_matang,bahan_mentah',
                'jumlah_dibayar'      => 'nullable|numeric|min:0',
                'keterangan'          => 'nullable|string',
            ];

            // Jika jenis zakat & tipe sudah diisi dari form (opsional untuk dijemput)
            if ($request->filled('jenis_zakat_id')) {
                $rules['jenis_zakat_id']  = 'nullable|exists:jenis_zakat,id';
                $rules['tipe_zakat_id']   = 'nullable|exists:tipe_zakat,uuid';
                $rules['program_zakat_id'] = 'nullable|exists:program_zakat,id';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Log::warning('Validasi gagal', ['errors' => $validator->errors()->toArray()]);
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();

            $noTransaksi = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);

            Log::info('Menyimpan transaksi dijemput', ['no_transaksi' => $noTransaksi]);

            $transaksi = new TransaksiPenerimaan();
            $transaksi->masjid_id          = $this->masjid->id;
            $transaksi->no_transaksi       = $noTransaksi;
            $transaksi->tanggal_transaksi  = $request->tanggal_transaksi ?? now();
            $transaksi->waktu_transaksi    = now();
            $transaksi->muzakki_nama       = $request->muzakki_nama;
            $transaksi->muzakki_telepon    = $request->muzakki_telepon;
            $transaksi->muzakki_email      = $request->muzakki_email;
            $transaksi->muzakki_alamat     = $request->muzakki_alamat;
            $transaksi->muzakki_nik        = $request->muzakki_nik;
            $transaksi->metode_penerimaan  = 'dijemput';
            $transaksi->amil_id            = $request->amil_id;
            $transaksi->latitude           = $request->latitude;
            $transaksi->longitude          = $request->longitude;
            $transaksi->status_penjemputan = 'menunggu';
            $transaksi->waktu_request      = now();
            $transaksi->jumlah             = 0;
            $transaksi->keterangan         = $request->keterangan;

            if ($request->filled('tanggal_penjemputan')) {
                $transaksi->tanggal_penjemputan = $request->tanggal_penjemputan;
            }

            // ── Tentukan metode pembayaran ────────────────────────────
            if ($request->filled('metode_pembayaran')) {

                if ($isBeras) {
                    // Zakat fitrah beras
                    $transaksi->metode_pembayaran = 'beras';
                    $transaksi->jumlah_dibayar    = 0;
                    $transaksi->jumlah_infaq      = 0;
                    $transaksi->has_infaq         = false;
                } elseif ($isFidyah) {
                    // Fidyah
                    switch ($request->fidyah_tipe) {
                        case 'matang':
                            $transaksi->metode_pembayaran = 'makanan_matang';
                            $transaksi->jumlah_dibayar    = 0;
                            $transaksi->jumlah_infaq      = 0;
                            $transaksi->has_infaq         = false;
                            break;
                        case 'mentah':
                            $transaksi->metode_pembayaran = 'bahan_mentah';
                            $transaksi->jumlah_dibayar    = 0;
                            $transaksi->jumlah_infaq      = 0;
                            $transaksi->has_infaq         = false;
                            break;
                        case 'tunai':
                        default:
                            $transaksi->metode_pembayaran = $request->metode_pembayaran;
                            if ($request->filled('jumlah_dibayar')) {
                                $transaksi->jumlah_dibayar = $request->jumlah_dibayar;
                                $transaksi->jumlah         = $request->jumlah_dibayar;
                            } else {
                                $transaksi->jumlah_dibayar = 0;
                            }
                            break;
                    }
                } else {
                    // Zakat biasa (tunai / transfer / qris)
                    $transaksi->metode_pembayaran = $request->metode_pembayaran;

                    if ($request->filled('jumlah_dibayar')) {
                        $transaksi->jumlah_dibayar = $request->jumlah_dibayar;
                        $transaksi->jumlah         = $request->jumlah_dibayar;
                    } else {
                        $transaksi->jumlah_dibayar = 0;
                    }
                }

                // Auto verified untuk semua metode
                $transaksi->status      = 'verified';
                $transaksi->verified_by = $this->user->id;
                $transaksi->verified_at = now();

                // konfirmasi_status untuk transfer/qris
                if (in_array($transaksi->metode_pembayaran, ['transfer', 'qris'])) {
                    $transaksi->konfirmasi_status = 'dikonfirmasi';
                } else {
                    $transaksi->konfirmasi_status = null;
                }

                Log::info('Transaksi dijemput auto verified', [
                    'no_transaksi' => $noTransaksi,
                    'metode'       => $transaksi->metode_pembayaran,
                    'is_beras'     => $isBeras,
                    'is_fidyah'    => $isFidyah,
                ]);
            } else {
                // Tidak ada metode pembayaran — status pending, akan dilengkapi amil nanti
                $transaksi->status            = 'pending';
                $transaksi->konfirmasi_status = null;

                Log::warning('Transaksi dijemput tanpa metode pembayaran', [
                    'no_transaksi' => $noTransaksi,
                ]);
            }

            // Jika diinput oleh muzakki
            if ($this->user->isMuzakki() && $this->user->muzakki) {
                $transaksi->diinput_muzakki = true;
                $transaksi->muzakki_id      = $this->user->muzakki->id;
            }

            $transaksi->save();
            DB::commit();

            Log::info('Request penjemputan berhasil disimpan', [
                'no_transaksi' => $transaksi->no_transaksi,
                'id'           => $transaksi->id,
                'status'       => $transaksi->status,
                'metode'       => $transaksi->metode_pembayaran,
                'verified_at'  => $transaksi->verified_at,
            ]);

            // ── Pesan sukses ─────────────────────────────────────────
            $message = 'Request penjemputan berhasil disimpan. ';

            if ($transaksi->status == 'verified') {
                $labelMetode = [
                    'tunai'          => 'TUNAI',
                    'transfer'       => 'TRANSFER',
                    'qris'           => 'QRIS',
                    'beras'          => 'BERAS',
                    'makanan_matang' => 'MAKANAN SIAP SANTAP',
                    'bahan_mentah'   => 'BAHAN MAKANAN MENTAH',
                ];
                $namaMetode = $labelMetode[$transaksi->metode_pembayaran ?? ''] ?? strtoupper($transaksi->metode_pembayaran);
                $message .= 'Pembayaran ' . $namaMetode . ' telah terverifikasi otomatis. ';
            }

            $message .= 'Amil akan segera menghubungi Anda. No. Transaksi: ' . $transaksi->no_transaksi;

            if ($this->user->isMuzakki()) {
                return redirect()->route('muzakki.transaksi.index')->with('success', $message);
            }

            return redirect()->route('transaksi-dijemput.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store dijemput error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan request: ' . $e->getMessage());
        }
    }

    // ================================================================
    // STORE DATANG LANGSUNG
    // ================================================================
    public function storeDatangLangsung(Request $request)
    {
        Log::info('Transaksi Datang Langsung Store', [
            'user_id'   => $this->user->id,
            'masjid_id' => $this->masjid->id,
        ]);

        try {
            $isBeras = $request->is_pembayaran_beras == '1';

            $jenisZakatCheck = JenisZakat::find($request->jenis_zakat_id);
            $isFidyah = $request->filled('fidyah_jumlah_hari')
                && $request->fidyah_jumlah_hari > 0
                && $jenisZakatCheck
                && stripos($jenisZakatCheck->nama, 'fidyah') !== false;

            $rules = [
                'tanggal_transaksi'   => 'required|date',
                'muzakki_nama'        => 'required|string|max:255',
                'muzakki_telepon'     => 'nullable|string|max:20',
                'muzakki_email'       => 'nullable|email|max:255',
                'muzakki_alamat'      => 'nullable|string',
                'muzakki_nik'         => 'nullable|string|size:16',
                'jenis_zakat_id'      => 'required|exists:jenis_zakat,id',
                'tipe_zakat_id'       => 'required|exists:tipe_zakat,uuid',
                'program_zakat_id'    => 'nullable|exists:program_zakat,id',
                'is_pembayaran_beras' => 'nullable|boolean',
                'keterangan'          => 'nullable|string',
            ];

            if ($isFidyah) {
                $rules['fidyah_jumlah_hari'] = 'required|integer|min:1';
                $rules['fidyah_tipe']        = 'required|in:mentah,matang,tunai';

                switch ($request->fidyah_tipe) {
                    case 'mentah':
                        $rules['fidyah_nama_bahan']          = 'required|string|max:100';
                        $rules['fidyah_berat_per_hari_gram']  = 'nullable|integer|min:100|max:2000';
                        break;
                    case 'matang':
                        $rules['fidyah_jumlah_box']    = 'required|integer|min:1';
                        $rules['fidyah_menu_makanan']  = 'nullable|string|max:200';
                        $rules['fidyah_harga_per_box'] = 'nullable|numeric|min:0';
                        $rules['fidyah_cara_serah']    = 'required|in:dibagikan,dijamu,via_lembaga';
                        break;
                    case 'tunai':
                        $rules['jumlah']            = 'required|numeric|min:1000';
                        $rules['jumlah_dibayar']    = 'nullable|numeric|min:0';
                        $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
                        break;
                }

                if ($request->fidyah_tipe == 'tunai' && in_array($request->metode_pembayaran, ['transfer', 'qris'])) {
                    $rules['bukti_transfer'] = 'nullable|image|max:2048';
                }
            } else {
                if (!$isBeras) {
                    $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
                }

                $rules['jumlah_jiwa']        = 'nullable|integer|min:1';
                $rules['nominal_per_jiwa']   = 'nullable|numeric|min:0';
                $rules['jumlah_beras_kg']    = 'nullable|numeric|min:0';
                $rules['harga_beras_per_kg'] = 'nullable|numeric|min:0';
                $rules['nama_jiwa_json']     = 'nullable|array';
                $rules['nama_jiwa_json.*']   = 'nullable|string|max:100';
                $rules['nilai_harta']        = 'nullable|numeric|min:0';
                $rules['nisab_saat_ini']     = 'nullable|numeric|min:0';
                $rules['sudah_haul']         = 'nullable|boolean';
                $rules['tanggal_mulai_haul'] = 'nullable|date';

                if (!$isBeras && $request->metode_pembayaran === 'transfer') {
                    $rules['bukti_transfer'] = 'nullable|image|max:2048';
                }
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();

            $transaksi = new TransaksiPenerimaan();
            $transaksi->masjid_id         = $this->masjid->id;
            $transaksi->no_transaksi      = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->waktu_transaksi   = now();
            $transaksi->muzakki_nama      = $request->muzakki_nama;
            $transaksi->muzakki_telepon   = $request->muzakki_telepon;
            $transaksi->muzakki_email     = $request->muzakki_email;
            $transaksi->muzakki_alamat    = $request->muzakki_alamat;
            $transaksi->muzakki_nik       = $request->muzakki_nik;
            $transaksi->metode_penerimaan = 'datang_langsung';
            $transaksi->keterangan        = $request->keterangan;

            if ($isFidyah) {
                $tipeZakat                   = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();
                $transaksi->jenis_zakat_id   = $request->jenis_zakat_id;
                $transaksi->tipe_zakat_id    = $tipeZakat->id;
                $transaksi->program_zakat_id = $request->program_zakat_id;

                $this->isiDataFidyah($transaksi, $request);

                switch ($request->fidyah_tipe) {
                    case 'tunai':
                        $transaksi->jumlah = (float) $request->jumlah;
                        $this->isiMetodePembayaranDatangLangsung($transaksi, $request);
                        break;
                    case 'matang':
                        $transaksi->metode_pembayaran = 'makanan_matang';
                        $transaksi->jumlah            = 0;
                        $transaksi->jumlah_dibayar    = 0;
                        $transaksi->jumlah_infaq      = 0;
                        $transaksi->has_infaq         = false;
                        $transaksi->status            = 'verified';
                        $transaksi->verified_by       = $this->user->id;
                        $transaksi->verified_at       = now();
                        break;
                    case 'mentah':
                        $transaksi->metode_pembayaran = 'bahan_mentah';
                        $transaksi->jumlah            = 0;
                        $transaksi->jumlah_dibayar    = 0;
                        $transaksi->jumlah_infaq      = 0;
                        $transaksi->has_infaq         = false;
                        $transaksi->status            = 'verified';
                        $transaksi->verified_by       = $this->user->id;
                        $transaksi->verified_at       = now();
                        break;
                }
            } else {
                $this->isiDetailZakat($transaksi, $request, $isBeras);

                if ($isBeras) {
                    $this->isiMetodePembayaranBeras($transaksi, $request);
                } else {
                    $this->isiMetodePembayaranDatangLangsung($transaksi, $request);
                }
            }

            if ($this->user->isAmil() && $this->amil) {
                $transaksi->amil_id = $this->amil->id;
            }

            $transaksi->save();
            DB::commit();

            Log::info('Transaksi datang langsung saved', [
                'no'          => $transaksi->no_transaksi,
                'is_fidyah'   => $isFidyah,
                'is_beras'    => $isBeras,
                'fidyah_tipe' => $transaksi->fidyah_tipe,
                'metode'      => $transaksi->metode_pembayaran,
            ]);

            $infaqMsg = $transaksi->jumlah_infaq > 0
                ? ' (Termasuk infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ')'
                : '';

            $fidyahLabel = match ($transaksi->fidyah_tipe ?? '') {
                'mentah' => ' Fidyah Bahan Makanan Mentah — ' . $transaksi->fidyah_jumlah_hari . ' hari.',
                'matang' => ' Fidyah Makanan Siap Santap — ' . $transaksi->fidyah_jumlah_hari . ' hari.',
                'tunai'  => ' Fidyah Tunai — ' . $transaksi->fidyah_jumlah_hari . ' hari'
                    . ' (Rp ' . number_format($transaksi->jumlah_dibayar, 0, ',', '.') . ').',
                default  => '',
            };

            $message = 'Transaksi datang langsung berhasil: '
                . $transaksi->no_transaksi
                . $fidyahLabel
                . $infaqMsg;

            if ($this->user->isMuzakki()) {
                return redirect()->route('muzakki.transaksi.index')->with('success', $message);
            }

            return redirect()->route('transaksi-datang-langsung.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store datang langsung error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ================================================================
    // STORE DARING
    // ================================================================
    public function storeDaring(Request $request)
    {
        Log::info('Transaksi Daring Store', [
            'user_id'   => $this->user->id,
            'masjid_id' => $this->masjid->id,
        ]);

        try {
            $rules = [
                'tanggal_transaksi'  => 'required|date',
                'muzakki_nama'       => 'required|string|max:255',
                'muzakki_telepon'    => 'required|string|max:20',
                'muzakki_email'      => 'required|email|max:255',
                'muzakki_alamat'     => 'required|string',
                'muzakki_nik'        => 'nullable|string|size:16',
                'jenis_zakat_id'     => 'required|exists:jenis_zakat,id',
                'tipe_zakat_id'      => 'required|exists:tipe_zakat,uuid',
                'program_zakat_id'   => 'nullable|exists:program_zakat,id',
                'is_pembayaran_beras' => 'nullable|boolean',
                'jumlah_jiwa'        => 'nullable|integer|min:1',
                'nominal_per_jiwa'   => 'nullable|numeric|min:0',
                'jumlah_beras_kg'    => 'nullable|numeric|min:0',
                'harga_beras_per_kg' => 'nullable|numeric|min:0',
                'nama_jiwa_json'     => 'nullable|array',
                'nama_jiwa_json.*'   => 'nullable|string|max:100',
                'nilai_harta'        => 'nullable|numeric|min:0',
                'nisab_saat_ini'     => 'nullable|numeric|min:0',
                'sudah_haul'         => 'nullable|boolean',
                'tanggal_mulai_haul' => 'nullable|date',
                'jumlah_dibayar'     => 'required|numeric|min:0',
                'metode_pembayaran'  => 'required|in:transfer,qris',
                'bukti_transfer'     => 'required|image|max:2048',
                'keterangan'         => 'nullable|string',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();

            $transaksi = new TransaksiPenerimaan();
            $transaksi->masjid_id         = $this->masjid->id;
            $transaksi->no_transaksi      = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->waktu_transaksi   = now();
            $transaksi->muzakki_nama      = $request->muzakki_nama;
            $transaksi->muzakki_telepon   = $request->muzakki_telepon;
            $transaksi->muzakki_email     = $request->muzakki_email;
            $transaksi->muzakki_alamat    = $request->muzakki_alamat;
            $transaksi->muzakki_nik       = $request->muzakki_nik;
            $transaksi->metode_penerimaan = 'daring';
            $transaksi->keterangan        = $request->keterangan;

            $this->isiDetailZakat($transaksi, $request, $request->is_pembayaran_beras == '1');
            $this->isiMetodePembayaranDaring($transaksi, $request);

            if ($this->user->isMuzakki() && $this->user->muzakki) {
                $transaksi->diinput_muzakki = true;
                $transaksi->muzakki_id = $this->user->muzakki->id;
            }

            $transaksi->save();
            DB::commit();

            Log::info('Transaksi daring saved', ['no' => $transaksi->no_transaksi]);

            $message = 'Transaksi daring berhasil dikirim. Menunggu konfirmasi pembayaran dari amil. No. Transaksi: ' . $transaksi->no_transaksi;

            if ($this->user->isMuzakki()) {
                return redirect()->route('muzakki.transaksi.index')->with('success', $message);
            }
            return redirect()->route('transaksi-penerimaan.index-daring')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store daring error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi daring: ' . $e->getMessage());
        }
    }

    // ================================================================
    // COMPLETE PICKUP — amil melengkapi detail zakat setelah penjemputan
    // ================================================================
    public function completePickup(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->metode_penerimaan !== 'dijemput' || $transaksi->status_penjemputan !== 'selesai') {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi ini tidak dapat dilengkapi.');
        }

        $isPembayaranBeras = $request->is_pembayaran_beras == '1';
        // PERBAIKAN: Deteksi fidyah
        $isFidyah = $request->filled('fidyah_jumlah_hari') && $request->fidyah_jumlah_hari > 0;

        $rules = [
            'jenis_zakat_id'    => 'required|exists:jenis_zakat,id',
            'tipe_zakat_id'     => 'required|exists:tipe_zakat,uuid',
            'program_zakat_id'  => 'nullable|exists:program_zakat,id',
            'jumlah_jiwa'       => 'nullable|integer|min:1',
            'nominal_per_jiwa'  => 'nullable|numeric|min:0',
            'jumlah_beras_kg'   => 'nullable|numeric|min:0',
            'nama_jiwa_json'    => 'nullable|array',
            'nama_jiwa_json.*'  => 'nullable|string|max:100',
            'nilai_harta'       => 'nullable|numeric|min:0',
            'sudah_haul'        => 'nullable|boolean',
            'tanggal_mulai_haul' => 'nullable|date',
            'jumlah_dibayar'    => 'nullable|numeric|min:0',
        ];

        // PERBAIKAN: Rules khusus untuk fidyah
        if ($isFidyah) {
            $rules['fidyah_jumlah_hari'] = 'required|integer|min:1';
            $rules['fidyah_tipe'] = 'required|in:mentah,matang,tunai';
            
            // Untuk fidyah tunai, metode pembayaran required
            if ($request->fidyah_tipe == 'tunai' && !$isPembayaranBeras) {
                $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
            }
        } elseif (!$isPembayaranBeras) {
            // Untuk non-fidyah non-beras, metode pembayaran required
            $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        DB::beginTransaction();
        try {
            $this->isiDetailZakat($transaksi, $request, $isPembayaranBeras);
            
            // PERBAIKAN: Handle fidyah secara spesifik
            if ($isFidyah) {
                // Set tipe zakat untuk fidyah
                $tipeZakat = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();
                $transaksi->jenis_zakat_id = $request->jenis_zakat_id;
                $transaksi->tipe_zakat_id = $tipeZakat->id;
                $transaksi->program_zakat_id = $request->program_zakat_id;
                
                // Isi data fidyah
                $this->isiDataFidyah($transaksi, $request);
                
                if ($request->fidyah_tipe == 'tunai') {
                    // Untuk fidyah tunai, gunakan metode pembayaran biasa
                    $this->isiMetodePembayaranPickup($transaksi, $request, $isPembayaranBeras);
                } else {
                    // Untuk fidyah mentah/matang
                    $transaksi->metode_pembayaran = $request->fidyah_tipe === 'mentah' 
                        ? 'bahan_mentah' 
                        : 'makanan_matang';
                    $transaksi->jumlah_dibayar = 0;
                    $transaksi->jumlah_infaq = 0;
                    $transaksi->has_infaq = false;
                    $transaksi->status = 'verified';
                    $transaksi->verified_by = $this->user->id;
                    $transaksi->verified_at = now();
                }
            } else {
                // Untuk non-fidyah, gunakan metode biasa
                $this->isiMetodePembayaranPickup($transaksi, $request, $isPembayaranBeras);
            }
            
            $transaksi->save();

            DB::commit();

            $infaqMsg = $transaksi->jumlah_infaq > 0
                ? ' (Infaq: Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ')'
                : '';

            $message = 'Data penjemputan berhasil dilengkapi: ' . $transaksi->no_transaksi . $infaqMsg;

            return redirect()->route('transaksi-penerimaan.index-dijemput')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complete pickup error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal melengkapi data: ' . $e->getMessage());
        }
    }

    // ================================================================
    // AJAX: GET TIPE ZAKAT
    // ================================================================
    public function getTipeZakat(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_zakat_id' => 'required|exists:jenis_zakat,id'
            ]);
            if ($validator->fails()) return response()->json(['error' => $validator->errors()->first()], 422);

            $list = TipeZakat::where('jenis_zakat_id', $request->jenis_zakat_id)
                ->orderBy('nama')->get(['id', 'nama', 'persentase_zakat', 'uuid'])->makeVisible(['id']);

            return response()->json($list);
        } catch (\Exception $e) {
            Log::error('getTipeZakat error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data tipe zakat'], 500);
        }
    }

    // ================================================================
    // AJAX: GET NISAB INFO + INFO ZAKAT FITRAH
    // ================================================================
    public function getNisabInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipe_zakat_id' => 'required'
            ]);
            if ($validator->fails()) return response()->json(['error' => $validator->errors()->first()], 422);

            $tipeZakat = TipeZakat::with('jenisZakat')
                ->where('uuid', $request->tipe_zakat_id)
                ->orWhere('id', $request->tipe_zakat_id)
                ->first();

            if (!$tipeZakat) return response()->json(['error' => 'Tipe zakat tidak ditemukan'], 404);
            $tipeZakat->makeVisible(['id']);

            $hargaEmasPerGram = 1000000;
            $nisabRupiah = $tipeZakat->nisab_emas_gram
                ? number_format($tipeZakat->nisab_emas_gram * $hargaEmasPerGram, 0, ',', '.') : null;

            $isFitrah = $tipeZakat->jenisZakat && stripos($tipeZakat->jenisZakat->nama, 'fitrah') !== false;
            $isBeras  = stripos($tipeZakat->nama, 'beras') !== false;
            $isFidyah = $tipeZakat->jenisZakat && stripos($tipeZakat->jenisZakat->nama, 'fidyah') !== false;

            return response()->json([
                'tipe_zakat'          => $tipeZakat,
                'jenis_zakat'         => $tipeZakat->jenisZakat,
                'persentase'          => $tipeZakat->persentase_zakat,
                'requires_haul'       => $tipeZakat->requires_haul ?? false,
                'nisab_rupiah'        => $nisabRupiah,
                'harga_emas_per_gram' => $hargaEmasPerGram,
                'is_fitrah'           => $isFitrah,
                'is_beras'            => $isBeras,
                'is_fidyah'           => $isFidyah,
                'zakat_fitrah_info'   => $isFitrah ? [
                    'nominal_per_jiwa'   => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
                    'beras_kg_per_jiwa'  => self::BERAS_KG_PER_JIWA,
                    'beras_liter_per_jiwa' => self::BERAS_LITER_PER_JIWA,
                    'keterangan'         => 'Berdasarkan ketetapan BAZNAS, zakat fitrah per jiwa = 2,5 kg atau 3,5 liter beras ≈ Rp ' . number_format(self::NOMINAL_ZAKAT_FITRAH_PER_JIWA, 0, ',', '.'),
                ] : null,
                'fidyah_info'         => $isFidyah ? [
                    'berat_per_hari_gram' => self::FIDYAH_BERAT_PER_HARI_GRAM,
                    'keterangan'          => 'Fidyah: 1 mud (675 gram) bahan pokok per hari, atau makanan siap santap sekali makan, atau uang senilai makanan',
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('getNisabInfo error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data nisab'], 500);
        }
    }

    // ================================================================
    // AJAX: HITUNG INFO PEMBAYARAN
    // ================================================================
    public function hitungInfoPembayaran(Request $request)
    {
        $jumlahZakat   = (float) $request->jumlah_zakat;
        $jumlahDibayar = (float) $request->jumlah_dibayar;

        if ($jumlahDibayar <= 0 || $jumlahZakat <= 0) {
            return response()->json(['infaq' => 0, 'kelebihan' => false]);
        }

        $infaq    = max(0, $jumlahDibayar - $jumlahZakat);
        $kurang   = max(0, $jumlahZakat - $jumlahDibayar);

        return response()->json([
            'jumlah_zakat'      => $jumlahZakat,
            'jumlah_dibayar'    => $jumlahDibayar,
            'infaq'             => $infaq,
            'kekurangan'        => $kurang,
            'kelebihan'         => $infaq > 0,
            'kurang'            => $kurang > 0,
            'pesan'             => $infaq > 0
                ? 'Kelebihan Rp ' . number_format($infaq, 0, ',', '.') . ' akan dicatat sebagai infaq sukarela.'
                : ($kurang > 0 ? 'Pembayaran kurang Rp ' . number_format($kurang, 0, ',', '.') . '.' : 'Pembayaran pas.'),
        ]);
    }

    // ================================================================
    // AJAX: HITUNG FIDYAH OTOMATIS
    // ================================================================
    public function hitungFidyah(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jumlah_hari' => 'required|integer|min:1',
                'tipe' => 'required|in:mentah,matang,tunai',
                'harga_per_hari' => 'nullable|numeric|min:0', // untuk tunai
                'berat_per_hari' => 'nullable|integer|min:100|max:2000', // untuk mentah
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $jumlahHari = $request->jumlah_hari;
            $tipe = $request->tipe;

            $result = [
                'jumlah_hari' => $jumlahHari,
                'tipe' => $tipe,
                'total' => null,
                'detail' => '',
            ];

            switch ($tipe) {
                case 'mentah':
                    $beratPerHari = $request->berat_per_hari ?? self::FIDYAH_BERAT_PER_HARI_GRAM;
                    $totalGram = $beratPerHari * $jumlahHari;
                    $totalKg = $totalGram / 1000;

                    $result['total'] = $totalKg;
                    $result['detail'] = "Total: {$totalKg} kg ({$jumlahHari} hari × {$beratPerHari} gram)";
                    break;

                case 'matang':
                    $result['total'] = $jumlahHari; // jumlah box = jumlah hari
                    $result['detail'] = "Total: {$jumlahHari} box makanan siap santap";
                    break;

                case 'tunai':
                    $hargaPerHari = $request->harga_per_hari ?? 0;
                    $total = $hargaPerHari * $jumlahHari;

                    $result['total'] = $total;
                    $result['detail'] = "Total: Rp " . number_format($total, 0, ',', '.') . " ({$jumlahHari} hari × Rp " . number_format($hargaPerHari, 0, ',', '.') . ")";
                    break;
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('hitungFidyah error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghitung fidyah'], 500);
        }
    }

    // ================================================================
    // SHOW DIJEMPUT - Display details of a pickup transaction
    // ================================================================
    public function showDijemput($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
            'verifiedBy',
            'dikonfirmasiOleh'
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->byMetodePenerimaan('dijemput')
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.show-dijemput', compact('transaksi'));
    }

    // ================================================================
    // SHOW DARING - Display details of an online transaction
    // ================================================================
    public function showDaring($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
            'verifiedBy',
            'dikonfirmasiOleh'
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->byMetodePenerimaan('daring')
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.show-daring', compact('transaksi'));
    }

    // ================================================================
    // SHOW MUZAKKI
    // ================================================================
    public function showMuzakki($uuid)
    {
        $query = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
            'verifiedBy'
        ])->where('uuid', $uuid);

        if ($this->user->isMuzakki() && $this->user->muzakki) {
            $query->where('muzakki_id', $this->user->muzakki->id);
        } else {
            $query->byMasjid($this->masjid->id);
        }

        $transaksi = $query->firstOrFail();

        return view('muzakki.transaksi.show', compact('transaksi'));
    }

    public function showPemantauan($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
            'verifiedBy',
            'dikonfirmasiOleh'
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            // tidak ada filter metode — semua metode bisa dilihat
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.show-pemantauan', compact('transaksi'));
    }

    public function showKas($uuid)
    {
        $query = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil.pengguna',
            'verifiedBy'
        ])->where('uuid', $uuid);

        if ($this->user->isMuzakki() && $this->user->muzakki) {
            $query->where('muzakki_id', $this->user->muzakki->id);
        } else {
            $query->byMasjid($this->masjid->id);
        }

        $transaksi = $query->firstOrFail();

        return view('amil.kas-harian.show', compact('transaksi'));
    }


    // ================================================================
    // EDIT
    // ================================================================
    public function edit($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->status !== 'pending') {
            return redirect()->route('transaksi-dijemput.show', $uuid)
                ->with('error', 'Transaksi dengan status ' . $transaksi->status . ' tidak dapat diedit.');
        }

        $isDijemput    = $transaksi->metode_penerimaan === 'dijemput';
        $needsZakatData = $isDijemput && !$transaksi->jenis_zakat_id;

        $rekeningList    = \App\Models\RekeningMasjid::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)->get();
        $jenisZakatList  = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::byMasjid($this->masjid->id)
            ->whereIn('status', ['aktif', 'draft'])->orderBy('nama_program')->get();
        $amilList = Amil::byMasjid($this->masjid->id)->with('pengguna')->where('status', 'aktif')->get();

        $tipeZakatList = [];
        foreach ($jenisZakatList as $jenis) {
            $tipeZakatList[$jenis->id] = TipeZakat::where('jenis_zakat_id', $jenis->id)
                ->orderBy('nama')->get()->makeVisible(['id']);
        }

        $zakatFitrahInfo = [
            'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
            'beras_kg'         => self::BERAS_KG_PER_JIWA,
            'beras_liter'      => self::BERAS_LITER_PER_JIWA,
        ];

        $fidyahInfo = [
            'berat_per_hari_gram' => self::FIDYAH_BERAT_PER_HARI_GRAM,
        ];

        // Ambil QRIS config
        $qrisConfig = KonfigurasiQris::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)
            ->first();

        // Gunakan no_transaksi dari transaksi yang sudah ada
        $noTransaksiPreview = $transaksi->no_transaksi;

        // Siapkan data muzakki jika user adalah muzakki
        $muzakkiData = null;
        if ($this->user->isMuzakki() && $this->user->muzakki) {
            $m = $this->user->muzakki;
            $muzakkiData = [
                'nama'    => $m->nama,
                'telepon' => $m->telepon,
                'email'   => $m->email,
                'alamat'  => $m->alamat,
                'nik'     => $m->nik,
            ];
        }

        // Tambahkan tanggalHariIni untuk input hidden
        $tanggalHariIni = now()->format('Y-m-d');

        return view('amil.transaksi-penerimaan.edit', compact(
            'transaksi',
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'tipeZakatList',
            'isDijemput',
            'needsZakatData',
            'rekeningList',
            'zakatFitrahInfo',
            'fidyahInfo',
            'qrisConfig',
            'noTransaksiPreview',
            'muzakkiData',
            'tanggalHariIni'
        ));
    }

    // ================================================================
    // UPDATE
    // ================================================================
    public function update(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->status !== 'pending') {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi tidak dapat diupdate.');
        }

        try {
            // Cek apakah ini mode "lengkapi zakat" (dijemput dan belum ada jenis_zakat_id)
            $isLengkapiZakat = ($transaksi->metode_penerimaan === 'dijemput' && !$transaksi->jenis_zakat_id);

            // Deteksi apakah ini pembayaran beras
            $isBeras = $request->is_pembayaran_beras == '1';

            // Deteksi apakah ini fidyah
            $isFidyah = $request->filled('fidyah_jumlah_hari') && $request->fidyah_jumlah_hari > 0;

            if ($isLengkapiZakat) {
                // MODE LENGKAPI ZAKAT - validasi untuk detail zakat dan pembayaran

                // Rules dasar untuk semua tipe zakat
                $rules = [
                    'jenis_zakat_id'     => 'required|exists:jenis_zakat,id',
                    'tipe_zakat_id'      => 'required|exists:tipe_zakat,uuid',
                    'program_zakat_id'   => 'nullable|exists:program_zakat,id',
                    'nama_jiwa_json'     => 'nullable|array',
                    'nama_jiwa_json.*'   => 'nullable|string|max:100',
                    'keterangan'         => 'nullable|string',
                ];

                // Rules khusus berdasarkan tipe zakat
                if ($isFidyah) {
                    // PERBAIKAN: Tambahkan rules untuk fidyah
                    $rules['fidyah_jumlah_hari'] = 'required|integer|min:1';
                    $rules['fidyah_tipe'] = 'required|in:mentah,matang,tunai';
                    
                    // Untuk fidyah tunai, metode pembayaran required
                    if ($request->fidyah_tipe == 'tunai') {
                        $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
                        $rules['jumlah'] = 'required|numeric|min:1000';
                    }

                    // Set jenis_zakat_id dan tipe_zakat_id ke transaksi
                    $tipeZakat = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();
                    $transaksi->jenis_zakat_id = $request->jenis_zakat_id;
                    $transaksi->tipe_zakat_id  = $tipeZakat->id;
                    $transaksi->program_zakat_id = $request->program_zakat_id;

                    // Isi semua field fidyah
                    $this->isiDataFidyah($transaksi, $request);

                    // Untuk fidyah tunai, isi metode pembayaran
                    if ($request->fidyah_tipe == 'tunai') {
                        $transaksi->metode_pembayaran = $request->metode_pembayaran;
                        $transaksi->jumlah = $request->jumlah;

                        $jumlahDibayar = $request->filled('jumlah_dibayar') ? $request->jumlah_dibayar : $request->jumlah;
                        $infaq = max(0, $jumlahDibayar - $request->jumlah);

                        $transaksi->jumlah_dibayar = $jumlahDibayar;
                        $transaksi->jumlah_infaq = $infaq;
                        $transaksi->has_infaq = $infaq > 0;

                        if ($request->metode_pembayaran === 'tunai') {
                            $transaksi->status = 'verified';
                            $transaksi->verified_by = $this->user->id;
                            $transaksi->verified_at = now();
                            $transaksi->status_penjemputan = 'selesai';
                            $transaksi->waktu_selesai = now();
                        } else {
                            $transaksi->status = 'pending';
                            $transaksi->konfirmasi_status = 'menunggu_konfirmasi';

                            if ($request->hasFile('bukti_transfer')) {
                                $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                                $transaksi->bukti_transfer = $path;
                            }
                        }
                    } else {
                        // PERBAIKAN: Untuk fidyah mentah/matang, set metode yang benar
                        $transaksi->metode_pembayaran = $request->fidyah_tipe === 'mentah'
                            ? 'bahan_mentah'
                            : 'makanan_matang';

                        $transaksi->status = 'verified';
                        $transaksi->verified_by = $this->user->id;
                        $transaksi->verified_at = now();
                        $transaksi->status_penjemputan = 'selesai';
                        $transaksi->waktu_selesai = now();
                    }
                } elseif ($isBeras) {
                    // ZAKAT BERAS - tidak perlu metode pembayaran
                    $rules = array_merge($rules, [
                        'is_pembayaran_beras' => 'required|in:1',
                        'jumlah_jiwa'         => 'required|integer|min:1',
                        'jumlah_beras_kg'     => 'required|numeric|min:0.1',
                        'harga_beras_per_kg'  => 'nullable|numeric|min:0',
                    ]);

                    Log::info('Mode lengkapi zakat - BERAS', [
                        'transaksi' => $transaksi->no_transaksi
                    ]);
                } else {
                    // ZAKAT UANG - perlu metode pembayaran
                    $rules = array_merge($rules, [
                        'is_pembayaran_beras' => 'in:0',
                        'metode_pembayaran'    => 'required|in:tunai,transfer,qris',
                        'jumlah_dibayar'        => 'nullable|numeric|min:0',
                        'bukti_transfer'        => 'nullable|image|max:2048',
                    ]);

                    // Rules untuk fitrah tunai
                    if ($request->filled('jumlah_jiwa') && $request->filled('nominal_per_jiwa')) {
                        $rules['jumlah_jiwa'] = 'required|integer|min:1';
                        $rules['nominal_per_jiwa'] = 'required|numeric|min:1000';
                    }

                    // Rules untuk zakat mal
                    if ($request->filled('nilai_harta')) {
                        $rules['nilai_harta'] = 'required|numeric|min:0';
                        $rules['sudah_haul'] = 'nullable|boolean';
                        $rules['tanggal_mulai_haul'] = 'nullable|date';
                        $rules['nisab_saat_ini'] = 'nullable|numeric|min:0';
                    }

                    Log::info('Mode lengkapi zakat - UANG', [
                        'transaksi' => $transaksi->no_transaksi,
                        'metode' => $request->metode_pembayaran
                    ]);
                }
            } else {
                // MODE EDIT BIASA - hanya update data muzakki
                $rules = [
                    'muzakki_nama'     => 'required|string|max:255',
                    'muzakki_telepon'  => 'nullable|string|max:20',
                    'muzakki_email'    => 'nullable|email|max:255',
                    'muzakki_alamat'   => 'nullable|string',
                    'program_zakat_id' => 'nullable|exists:program_zakat,id',
                    'keterangan'       => 'nullable|string',
                ];

                Log::info('Mode edit biasa', ['transaksi' => $transaksi->no_transaksi]);
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Log::warning('Validasi update gagal', [
                    'errors' => $validator->errors()->toArray(),
                    'mode' => $isLengkapiZakat ? 'lengkapi' : 'edit',
                    'is_beras' => $isBeras,
                    'is_fidyah' => $isFidyah
                ]);
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();

            if ($isLengkapiZakat) {
                if ($isFidyah) {
                    // Isi data fidyah
                    $this->isiDataFidyah($transaksi, $request);

                    // Untuk fidyah tunai, isi metode pembayaran
                    if ($request->fidyah_tipe == 'tunai') {
                        $transaksi->metode_pembayaran = $request->metode_pembayaran;
                        $transaksi->jumlah = $request->jumlah;

                        $jumlahDibayar = $request->filled('jumlah_dibayar') ? $request->jumlah_dibayar : $request->jumlah;
                        $infaq = max(0, $jumlahDibayar - $request->jumlah);

                        $transaksi->jumlah_dibayar = $jumlahDibayar;
                        $transaksi->jumlah_infaq = $infaq;
                        $transaksi->has_infaq = $infaq > 0;

                        if ($request->metode_pembayaran === 'tunai') {
                            $transaksi->status = 'verified';
                            $transaksi->verified_by = $this->user->id;
                            $transaksi->verified_at = now();
                            $transaksi->status_penjemputan = 'selesai';
                            $transaksi->waktu_selesai = now();
                        } else {
                            $transaksi->status = 'pending';
                            $transaksi->konfirmasi_status = 'menunggu_konfirmasi';

                            if ($request->hasFile('bukti_transfer')) {
                                $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                                $transaksi->bukti_transfer = $path;
                            }
                        }
                    } else {
                        // PERBAIKAN: Untuk fidyah non-tunai (mentah/matang)
                        $transaksi->metode_pembayaran = $request->fidyah_tipe === 'mentah'
                            ? 'bahan_mentah'
                            : 'makanan_matang';

                        $transaksi->status = 'verified';
                        $transaksi->verified_by = $this->user->id;
                        $transaksi->verified_at = now();
                        $transaksi->status_penjemputan = 'selesai';
                        $transaksi->waktu_selesai = now();
                    }
                } elseif ($isBeras) {
                    // Isi detail zakat untuk beras
                    $this->isiDetailZakat($transaksi, $request, true);

                    $transaksi->metode_pembayaran = 'tunai';
                    $transaksi->jumlah_dibayar = 0;
                    $transaksi->jumlah_infaq = 0;
                    $transaksi->has_infaq = false;
                    $transaksi->status = 'verified';
                    $transaksi->verified_by = $this->user->id;
                    $transaksi->verified_at = now();
                    $transaksi->status_penjemputan = 'selesai';
                    $transaksi->waktu_selesai = now();
                } else {
                    // Isi detail zakat untuk uang
                    $this->isiDetailZakat($transaksi, $request, false);

                    $transaksi->metode_pembayaran = $request->metode_pembayaran;

                    $jumlahZakat = (float) $transaksi->jumlah;
                    $jumlahDibayar = $request->filled('jumlah_dibayar')
                        ? (float) $request->jumlah_dibayar
                        : $jumlahZakat;

                    $infaq = max(0, $jumlahDibayar - $jumlahZakat);

                    $transaksi->jumlah_dibayar = $jumlahDibayar;
                    $transaksi->jumlah_infaq = $infaq;
                    $transaksi->has_infaq = $infaq > 0;

                    if ($request->metode_pembayaran === 'tunai') {
                        $transaksi->status = 'verified';
                        $transaksi->verified_by = $this->user->id;
                        $transaksi->verified_at = now();
                        $transaksi->status_penjemputan = 'selesai';
                        $transaksi->waktu_selesai = now();
                    } else {
                        $transaksi->status = 'pending';
                        $transaksi->konfirmasi_status = 'menunggu_konfirmasi';

                        if ($request->hasFile('bukti_transfer')) {
                            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                            $transaksi->bukti_transfer = $path;
                        }
                    }
                }

                $transaksi->keterangan = $request->keterangan ?? $transaksi->keterangan;
            } else {
                // Mode edit biasa - update data muzakki
                $transaksi->muzakki_nama     = $request->muzakki_nama;
                $transaksi->muzakki_telepon  = $request->muzakki_telepon;
                $transaksi->muzakki_email    = $request->muzakki_email;
                $transaksi->muzakki_alamat   = $request->muzakki_alamat;
                $transaksi->program_zakat_id = $request->program_zakat_id;
                $transaksi->keterangan       = $request->keterangan;
            }

            $transaksi->save();
            DB::commit();

            $message = $isLengkapiZakat
                ? ($isFidyah
                    ? 'Data fidyah berhasil disimpan.'
                    : ($isBeras
                        ? 'Data zakat beras berhasil disimpan. Transaksi selesai.'
                        : 'Data zakat berhasil dilengkapi. ' . ($request->metode_pembayaran === 'tunai' ? 'Transaksi selesai.' : 'Menunggu konfirmasi pembayaran.')))
                : 'Data muzakki berhasil diupdate.';

            // Redirect ke halaman yang sesuai
            if ($transaksi->metode_penerimaan === 'dijemput') {
                return redirect()->route('transaksi-dijemput.index')->with('success', $message);
            }

            return redirect()->route('transaksi-penerimaan.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'uuid' => $uuid
            ]);
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // ================================================================
    // KONFIRMASI PEMBAYARAN
    // ================================================================
    public function konfirmasiPembayaran(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDikonfirmasi) {
            return redirect()->back()->with('error', 'Transaksi ini tidak bisa dikonfirmasi.');
        }

        $request->validate(['catatan_konfirmasi' => 'nullable|string|max:500']);

        DB::beginTransaction();
        try {
            $transaksi->konfirmasi_status = 'dikonfirmasi';
            $transaksi->dikonfirmasi_oleh = $this->user->id;
            $transaksi->konfirmasi_at = now();
            $transaksi->catatan_konfirmasi = $request->catatan_konfirmasi;
            $transaksi->status = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
            $transaksi->save();

            DB::commit();

            $infaqMsg = $transaksi->jumlah_infaq > 0
                ? ' Infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ' dicatat.'
                : '';

            // Redirect berdasarkan metode penerimaan
            if ($transaksi->metode_penerimaan === 'daring') {
                return redirect()->route('transaksi-daring.index')
                    ->with('success', 'Pembayaran berhasil dikonfirmasi. Transaksi terverifikasi.' . $infaqMsg);
            } else {
                return redirect()->route('transaksi-dijemput.index')
                    ->with('success', 'Pembayaran berhasil dikonfirmasi. Transaksi terverifikasi.' . $infaqMsg);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Konfirmasi error: ' . $e->getMessage());

            if ($transaksi->metode_penerimaan === 'daring') {
                return redirect()->route('transaksi-daring.index')
                    ->with('error', 'Gagal konfirmasi pembayaran.');
            } else {
                return redirect()->route('transaksi-dijemput.index')
                    ->with('error', 'Gagal konfirmasi pembayaran.');
            }
        }
    }

    // ================================================================
    // TOLAK PEMBAYARAN
    // ================================================================
    public function tolakPembayaran(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->konfirmasi_status !== 'menunggu_konfirmasi') {
            return redirect()->back()->with('error', 'Status pembayaran tidak bisa ditolak.');
        }

        $request->validate(['catatan_konfirmasi' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            $transaksi->konfirmasi_status = 'ditolak';
            $transaksi->dikonfirmasi_oleh = $this->user->id;
            $transaksi->konfirmasi_at = now();
            $transaksi->catatan_konfirmasi = $request->catatan_konfirmasi;
            $transaksi->status = 'rejected';
            $transaksi->alasan_penolakan = $request->catatan_konfirmasi;
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
            $transaksi->save();
            DB::commit();

            if ($transaksi->metode_penerimaan === 'daring') {
                return redirect()->route('transaksi-daring.index')
                    ->with('success', 'Pembayaran ditolak.');
            } else {
                return redirect()->route('transaksi-dijemput.index')
                    ->with('success', 'Pembayaran ditolak.');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($transaksi->metode_penerimaan === 'daring') {
                return redirect()->route('transaksi-daring.index')
                    ->with('error', 'Gagal menolak pembayaran.');
            } else {
                return redirect()->route('transaksi-dijemput.index')
                    ->with('error', 'Gagal menolak pembayaran.');
            }
        }
    }

    // ================================================================
    // VERIFY
    // ================================================================
    public function verify($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDiverifikasi) {
            return redirect()->back()->with('error', 'Transaksi tidak dapat diverifikasi.');
        }

        DB::beginTransaction();
        try {
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
            $transaksi->save();
            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil diverifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal verifikasi.');
        }
    }

    // ================================================================
    // REJECT
    // ================================================================
    public function reject(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDitolak) {
            return redirect()->back()->with('error', 'Transaksi tidak dapat ditolak.');
        }

        $request->validate(['alasan_penolakan' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            $transaksi->status           = 'rejected';
            $transaksi->alasan_penolakan = $request->alasan_penolakan;
            $transaksi->verified_by      = $this->user->id;
            $transaksi->verified_at      = now();
            $transaksi->save();
            DB::commit();

            return redirect()->back()->with('success', 'Transaksi ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak.');
        }
    }

    public function updateStatusPenjemputan(Request $request, $uuid)
    {
        if (!$this->user->isAmil() && !$this->user->isAdminMasjid()) {
            return response()->json(['error' => 'Hanya amil yang dapat update status penjemputan.'], 403);
        }

        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->firstOrFail();

        if (!$transaksi->bisaDiupdatePenjemputan) {
            return response()->json(['error' => 'Status penjemputan tidak dapat diupdate.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:diterima,dalam_perjalanan,sampai_lokasi,selesai'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $status = $request->status;
            $transaksi->status_penjemputan = $status;

            switch ($status) {
                case 'diterima':
                    $transaksi->waktu_diterima_amil = now();
                    break;
                case 'dalam_perjalanan':
                    $transaksi->waktu_berangkat = now();
                    break;
                case 'sampai_lokasi':
                    $transaksi->waktu_sampai = now();
                    break;
                case 'selesai':
                    $transaksi->waktu_selesai = now();

                    // Auto verified saat penjemputan selesai (jika belum verified)
                    if ($transaksi->status != 'verified') {
                        $transaksi->status = 'verified';
                        $transaksi->verified_by = $this->user->id;
                        $transaksi->verified_at = now();

                        // Untuk transfer/qris, pastikan konfirmasi_status
                        if (in_array($transaksi->metode_pembayaran, ['transfer', 'qris'])) {
                            $transaksi->konfirmasi_status = 'dikonfirmasi';
                        }

                        Log::info('Transaksi dijemput verified saat selesai', [
                            'no_transaksi' => $transaksi->no_transaksi,
                            'metode' => $transaksi->metode_pembayaran
                        ]);
                    }
                    break;
            }

            $transaksi->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status penjemputan berhasil diupdate.',
                'status'  => $status,
                'waktu'   => now()->format('H:i:s'),
                'transaksi_status' => $transaksi->status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update status penjemputan error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengupdate status.'], 500);
        }
    }

    // ================================================================
    // PRINT KWITANSI
    // ================================================================
    public function printKwitansi($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid',
            'jenisZakat',
            'tipeZakat',
            'programZakat',
            'amil' => fn($q) => $q->with('pengguna'),
            'verifiedBy',
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.print', compact('transaksi'));
    }

    // ================================================================
    // DESTROY
    // ================================================================
    public function destroy($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!in_array($transaksi->status, ['pending', 'rejected'])) {
            return redirect()->back()->with('error', 'Transaksi dengan status ' . $transaksi->status . ' tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $transaksi->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi.');
        }
    }

    // ================================================================
    // EXPORT PDF
    // ================================================================
    public function exportPdf(Request $request)
    {
        try {
            $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna'])
                ->byMasjid($this->masjid->id);

            if ($request->filled('q'))                 $query->search($request->q);
            if ($request->filled('start_date') && $request->filled('end_date'))
                $query->byPeriode($request->start_date, $request->end_date);
            if ($request->filled('jenis_zakat_id'))    $query->byJenisZakat($request->jenis_zakat_id);
            if ($request->filled('metode_pembayaran')) $query->byMetodePembayaran($request->metode_pembayaran);
            if ($request->filled('status'))            $query->byStatus($request->status);
            if ($request->filled('metode_penerimaan')) $query->byMetodePenerimaan($request->metode_penerimaan);

            // Filter fidyah
            if ($request->filled('fidyah_tipe')) {
                $query->where('fidyah_tipe', $request->fidyah_tipe);
            }

            $transaksis   = $query->orderBy('created_at', 'desc')->get();
            $totalNominal = $transaksis->where('status', 'verified')->sum('jumlah');
            $totalInfaq   = $transaksis->where('status', 'verified')->sum('jumlah_infaq');

            $pdf = PDF::loadView('amil.transaksi-penerimaan.exports.pdf', [
                'transaksis'    => $transaksis,
                'masjid'        => $this->masjid,
                'user'          => $this->user,
                'filters'       => $request->all(),
                'jenisZakatList' => JenisZakat::all(),
                'totalNominal'  => $totalNominal,
                'totalInfaq'    => $totalInfaq,
                'totalVerified' => $transaksis->where('status', 'verified')->count(),
                'totalPending'  => $transaksis->where('status', 'pending')->count(),
                'totalTransaksi' => $transaksis->count(),
                'tanggalExport' => now()->format('d/m/Y H:i:s'),
            ]);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('transaksi-penerimaan-' . date('Y-m-d-His') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export PDF error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF.');
        }
    }

    // ================================================================
    // EXPORT EXCEL
    // ================================================================
    public function exportExcel(Request $request)
    {
        try {
            $filters  = $request->only([
                'q',
                'status',
                'jenis_zakat_id',
                'metode_pembayaran',
                'start_date',
                'end_date',
                'metode_penerimaan',
                'fidyah_tipe'
            ]);
            $filename = 'transaksi-penerimaan-' . date('Y-m-d-His') . '.xlsx';
            return Excel::download(
                new TransaksiPenerimaanExport($filters, $this->user, $this->masjid),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('Export Excel error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel.');
        }
    }

    // ================================================================
    // HELPER: Isi detail zakat
    // ================================================================
    protected function isiDetailZakat(TransaksiPenerimaan $transaksi, Request $request, bool $isPembayaranBeras): void
    {
        $jenisZakat = JenisZakat::findOrFail($request->jenis_zakat_id);
        $tipeZakat  = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();

        if ($tipeZakat->jenis_zakat_id != $request->jenis_zakat_id) {
            throw new \Exception('Tipe zakat tidak sesuai jenis zakat yang dipilih.');
        }

        $isFitrah = stripos($jenisZakat->nama, 'fitrah') !== false;
        $isMal    = stripos($jenisZakat->nama, 'mal') !== false;
        $isBeras  = stripos($tipeZakat->nama, 'beras') !== false;

        $jumlah = 0;

        if ($isFitrah) {
            if ($isBeras) {
                if (!$request->filled('jumlah_beras_kg')) {
                    throw new \Exception('Jumlah beras (kg) harus diisi.');
                }
                $jumlah = 0;
            } else {
                if (!$request->filled('jumlah_jiwa') || !$request->filled('nominal_per_jiwa')) {
                    throw new \Exception('Jumlah jiwa dan nominal per jiwa harus diisi.');
                }
                $jumlah = (int) $request->jumlah_jiwa * (float) $request->nominal_per_jiwa;
            }
        } elseif ($isMal) {
            if (!$request->filled('nilai_harta')) {
                throw new \Exception('Nilai harta harus diisi untuk zakat mal.');
            }
            $jumlah = ((float) $request->nilai_harta * ((float) ($tipeZakat->persentase_zakat ?? 2.5))) / 100;
        } else {
            $jumlah = (float) ($request->jumlah ?? 0);
        }

        if (!$isPembayaranBeras && $jumlah <= 0) {
            throw new \Exception('Jumlah zakat tidak valid. Pastikan data yang diisi sudah benar.');
        }

        $transaksi->jenis_zakat_id   = $request->jenis_zakat_id;
        $transaksi->tipe_zakat_id    = $tipeZakat->id;
        $transaksi->program_zakat_id = $request->program_zakat_id;
        $transaksi->jumlah           = round($jumlah);

        // Tambahkan nama jiwa jika ada
        if ($request->has('nama_jiwa_json') && is_array($request->nama_jiwa_json)) {
            $namaJiwa = array_filter($request->nama_jiwa_json, function ($value) {
                return !empty(trim($value));
            });

            if (!empty($namaJiwa)) {
                $transaksi->nama_jiwa_json = array_values($namaJiwa);
            }
        }

        if ($isFitrah) {
            $transaksi->jumlah_jiwa        = $request->jumlah_jiwa;
            $transaksi->nominal_per_jiwa   = $request->nominal_per_jiwa;
            $transaksi->jumlah_beras_kg    = $request->jumlah_beras_kg;
            $transaksi->harga_beras_per_kg = $request->harga_beras_per_kg ?? 0;
        }

        if ($isMal) {
            $transaksi->nilai_harta        = $request->nilai_harta;
            $transaksi->nisab_saat_ini     = $request->nisab_saat_ini;
            $transaksi->sudah_haul         = $request->sudah_haul ? true : false;
            $transaksi->tanggal_mulai_haul = $request->tanggal_mulai_haul;
        }
    }

    // ================================================================
    // HELPER: Isi metode pembayaran datang langsung - AUTO VERIFIED UNTUK SEMUA METODE
    // ================================================================
    protected function isiMetodePembayaranDatangLangsung(TransaksiPenerimaan $transaksi, Request $request): void
    {
        if ($request->is_pembayaran_beras == '1') {
            $transaksi->metode_pembayaran = 'tunai';
            $transaksi->jumlah_dibayar   = 0;
            $transaksi->jumlah_infaq     = 0;
            $transaksi->has_infaq        = false;
            $transaksi->status           = 'verified';
            $transaksi->verified_by      = $this->user->id;
            $transaksi->verified_at      = now();
            return;
        }

        $metodePembayaran = $request->metode_pembayaran;
        $transaksi->metode_pembayaran = $metodePembayaran;

        $jumlahZakat   = (float) $transaksi->jumlah;
        $jumlahDibayar = $request->filled('jumlah_dibayar')
            ? (float) $request->jumlah_dibayar
            : $jumlahZakat;

        if ($jumlahDibayar <= 0) {
            $jumlahDibayar = $jumlahZakat;
        }

        $infaq = max(0, $jumlahDibayar - $jumlahZakat);

        $transaksi->jumlah_dibayar = $jumlahDibayar;
        $transaksi->jumlah_infaq   = $infaq;
        $transaksi->has_infaq      = $infaq > 0;

        // Semua metode pembayaran langsung verified
        $transaksi->status      = 'verified';
        $transaksi->verified_by = $this->user->id;
        $transaksi->verified_at = now();

        // Untuk transfer/qris, tetap simpan bukti transfer jika ada
        if (in_array($metodePembayaran, ['transfer', 'qris'])) {
            $transaksi->konfirmasi_status = 'dikonfirmasi';

            if ($request->hasFile('bukti_transfer')) {
                $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                $transaksi->bukti_transfer = $path;
            }
        } else {
            // Untuk tunai, tidak perlu konfirmasi status
            $transaksi->konfirmasi_status = null;
        }

        Log::info('Transaksi datang langsung auto verified', [
            'no_transaksi' => $transaksi->no_transaksi,
            'metode' => $metodePembayaran,
            'jumlah' => $jumlahDibayar,
            'infaq' => $infaq
        ]);
    }

    // ================================================================
    // HELPER: Isi metode pembayaran daring
    // ================================================================
    protected function isiMetodePembayaranDaring(TransaksiPenerimaan $transaksi, Request $request): void
    {
        $transaksi->metode_pembayaran = $request->metode_pembayaran;

        $jumlahZakat   = (float) $transaksi->jumlah;
        $jumlahDibayar = (float) $request->jumlah_dibayar;

        $infaq = max(0, $jumlahDibayar - $jumlahZakat);

        $transaksi->jumlah_dibayar = $jumlahDibayar;
        $transaksi->jumlah_infaq   = $infaq;
        $transaksi->has_infaq      = $infaq > 0;

        $transaksi->status             = 'pending';
        $transaksi->konfirmasi_status  = 'menunggu_konfirmasi';

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
            $transaksi->bukti_transfer = $path;
        }
    }

    // ================================================================
    // HELPER: Isi metode pembayaran untuk pickup
    // ================================================================
    protected function isiMetodePembayaranPickup(TransaksiPenerimaan $transaksi, Request $request, bool $isPembayaranBeras): void
    {
        if ($isPembayaranBeras) {
            $transaksi->metode_pembayaran = 'tunai';
            $transaksi->jumlah_dibayar   = 0;
            $transaksi->jumlah_infaq     = 0;
            $transaksi->has_infaq        = false;
        } else {
            $metodePembayaran = $request->metode_pembayaran;
            $transaksi->metode_pembayaran = $metodePembayaran;

            $jumlahZakat   = (float) $transaksi->jumlah;
            $jumlahDibayar = $request->filled('jumlah_dibayar')
                ? (float) $request->jumlah_dibayar
                : $jumlahZakat;

            $infaq = max(0, $jumlahDibayar - $jumlahZakat);

            $transaksi->jumlah_dibayar = $jumlahDibayar;
            $transaksi->jumlah_infaq   = $infaq;
            $transaksi->has_infaq      = $infaq > 0;

            if (in_array($metodePembayaran, ['transfer', 'qris'])) {
                $transaksi->konfirmasi_status = 'menunggu_konfirmasi';
            }
        }

        $transaksi->status      = 'verified';
        $transaksi->verified_by = $this->user->id;
        $transaksi->verified_at = now();
    }
}