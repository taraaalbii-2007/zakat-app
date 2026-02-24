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

class TransaksiPenerimaanController extends Controller
{
    protected $user;
    protected $masjid;
    protected $amil;

    // Konstanta zakat fitrah dari BAZNAS
    const NOMINAL_ZAKAT_FITRAH_PER_JIWA = 50000;  // Rp 50.000 per jiwa (BAZNAS 2024)
    const BERAS_KG_PER_JIWA             = 2.5;     // 2,5 kg per jiwa
    const BERAS_LITER_PER_JIWA          = 3.5;     // 3,5 liter per jiwa

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
        if ($request->filled('metode_penerimaan')) $query->byMetodePenerimaan($request->metode_penerimaan);

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
            'muzakkiData'
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
    // STORE DATANG LANGSUNG
    // ================================================================
    public function storeDatangLangsung(Request $request)
    {
        Log::info('Transaksi Datang Langsung Store', [
            'user_id'   => $this->user->id,
            'masjid_id' => $this->masjid->id,
        ]);

        try {
            $rules = [
                'tanggal_transaksi'  => 'required|date',
                'muzakki_nama'       => 'required|string|max:255',
                'muzakki_telepon'    => 'nullable|string|max:20',
                'muzakki_email'      => 'nullable|email|max:255',
                'muzakki_alamat'     => 'nullable|string',
                'muzakki_nik'        => 'nullable|string|size:16',
                'jenis_zakat_id'     => 'required|exists:jenis_zakat,id',
                'tipe_zakat_id'      => 'required|exists:tipe_zakat,uuid',
                'program_zakat_id'   => 'nullable|exists:program_zakat,id',
                'is_pembayaran_beras' => 'nullable|boolean',
                'jumlah_jiwa'        => 'nullable|integer|min:1',
                'nominal_per_jiwa'   => 'nullable|numeric|min:0',
                'jumlah_beras_kg'    => 'nullable|numeric|min:0',
                'harga_beras_per_kg' => 'nullable|numeric|min:0',
                'nilai_harta'        => 'nullable|numeric|min:0',
                'nisab_saat_ini'     => 'nullable|numeric|min:0',
                'sudah_haul'         => 'nullable|boolean',
                'tanggal_mulai_haul' => 'nullable|date',
                'jumlah_dibayar'     => 'nullable|numeric|min:0',
                'metode_pembayaran'  => 'required|in:tunai,transfer,qris',
                'no_referensi_transfer' => 'nullable|string|max:100',
                'bukti_transfer'     => 'nullable|image|max:2048',
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
            $transaksi->metode_penerimaan = 'datang_langsung';
            $transaksi->keterangan        = $request->keterangan;

            $this->isiDetailZakat($transaksi, $request, $request->is_pembayaran_beras == '1');
            $this->isiMetodePembayaranDatangLangsung($transaksi, $request);

            if ($this->user->isAmil() && $this->amil) {
                $transaksi->amil_id = $this->amil->id;
            }

            $transaksi->save();
            DB::commit();

            Log::info('Transaksi datang langsung saved', ['no' => $transaksi->no_transaksi]);

            $infaqMsg = $transaksi->jumlah_infaq > 0
                ? ' (Termasuk infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ')'
                : '';

            $message = 'Transaksi datang langsung berhasil: ' . $transaksi->no_transaksi . $infaqMsg;

            if ($this->user->isMuzakki()) {
                return redirect()->route('muzakki.transaksi.index')->with('success', $message);
            }
            return redirect()->route('transaksi-datang-langsung.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store datang langsung error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ================================================================
    // STORE DIJEMPUT
    // ================================================================
    public function storeDijemput(Request $request)
    {
        Log::info('Transaksi Dijemput Store', [
            'user_id'   => $this->user->id,
            'masjid_id' => $this->masjid->id,
        ]);

        try {
            $rules = [
                'tanggal_transaksi' => 'required|date',
                'muzakki_nama'      => 'required|string|max:255',
                'muzakki_telepon'   => 'required|string|max:20',
                'muzakki_email'     => 'nullable|email|max:255',
                'muzakki_alamat'    => 'required|string',
                'muzakki_nik'       => 'nullable|string|size:16',
                'amil_id'           => 'required|exists:amil,id',
                'latitude'          => 'required|numeric',
                'longitude'         => 'required|numeric',
                'keterangan'        => 'nullable|string',
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
            $transaksi->metode_penerimaan = 'dijemput';
            $transaksi->amil_id           = $request->amil_id;
            $transaksi->latitude          = $request->latitude;
            $transaksi->longitude         = $request->longitude;
            $transaksi->status            = 'pending';
            $transaksi->status_penjemputan = 'menunggu';
            $transaksi->waktu_request     = now();
            $transaksi->jumlah            = 0;
            $transaksi->keterangan        = $request->keterangan;

            if ($this->user->isMuzakki() && $this->user->muzakki) {
                $transaksi->diinput_muzakki = true;
                $transaksi->muzakki_id = $this->user->muzakki->id;
            }

            $transaksi->save();
            DB::commit();

            Log::info('Request penjemputan saved', ['no' => $transaksi->no_transaksi]);

            $message = 'Request penjemputan berhasil disimpan. Amil akan segera menghubungi Anda.';

            if ($this->user->isMuzakki()) {
                return redirect()->route('muzakki.transaksi.index')->with('success', $message);
            }
            return redirect()->route('transaksi-penerimaan.index-dijemput')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store dijemput error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan request: ' . $e->getMessage());
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
                'nilai_harta'        => 'nullable|numeric|min:0',
                'nisab_saat_ini'     => 'nullable|numeric|min:0',
                'sudah_haul'         => 'nullable|boolean',
                'tanggal_mulai_haul' => 'nullable|date',
                'jumlah_dibayar'     => 'required|numeric|min:0',
                'metode_pembayaran'  => 'required|in:transfer,qris',
                'no_referensi_transfer' => 'required|string|max:100',
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

        $rules = [
            'jenis_zakat_id'    => 'required|exists:jenis_zakat,id',
            'tipe_zakat_id'     => 'required|exists:tipe_zakat,uuid',
            'program_zakat_id'  => 'nullable|exists:program_zakat,id',
            'jumlah_jiwa'       => 'nullable|integer|min:1',
            'nominal_per_jiwa'  => 'nullable|numeric|min:0',
            'jumlah_beras_kg'   => 'nullable|numeric|min:0',
            'nilai_harta'       => 'nullable|numeric|min:0',
            'sudah_haul'        => 'nullable|boolean',
            'tanggal_mulai_haul' => 'nullable|date',
            'jumlah_dibayar'    => 'nullable|numeric|min:0',
        ];

        if (!$isPembayaranBeras) {
            $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        DB::beginTransaction();
        try {
            $this->isiDetailZakat($transaksi, $request, $isPembayaranBeras);
            $this->isiMetodePembayaranPickup($transaksi, $request, $isPembayaranBeras);
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

            return response()->json([
                'tipe_zakat'          => $tipeZakat,
                'jenis_zakat'         => $tipeZakat->jenisZakat,
                'persentase'          => $tipeZakat->persentase_zakat,
                'requires_haul'       => $tipeZakat->requires_haul ?? false,
                'nisab_rupiah'        => $nisabRupiah,
                'harga_emas_per_gram' => $hargaEmasPerGram,
                'is_fitrah'           => $isFitrah,
                'is_beras'            => $isBeras,
                'zakat_fitrah_info'   => $isFitrah ? [
                    'nominal_per_jiwa'   => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
                    'beras_kg_per_jiwa'  => self::BERAS_KG_PER_JIWA,
                    'beras_liter_per_jiwa' => self::BERAS_LITER_PER_JIWA,
                    'keterangan'         => 'Berdasarkan ketetapan BAZNAS, zakat fitrah per jiwa = 2,5 kg atau 3,5 liter beras ≈ Rp ' . number_format(self::NOMINAL_ZAKAT_FITRAH_PER_JIWA, 0, ',', '.'),
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
    // SHOW
    // ================================================================
    public function show($uuid)
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
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.show', compact('transaksi'));
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

    // ================================================================
    // EDIT
    // ================================================================
    public function edit($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->status !== 'pending') {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
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

        return view('amil.transaksi-penerimaan.edit', compact(
            'transaksi',
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'tipeZakatList',
            'isDijemput',
            'needsZakatData',
            'rekeningList',
            'zakatFitrahInfo'
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
            $rules = [
                'muzakki_nama'     => 'required|string|max:255',
                'muzakki_telepon'  => 'nullable|string|max:20',
                'muzakki_email'    => 'nullable|email|max:255',
                'muzakki_alamat'   => 'nullable|string',
                'program_zakat_id' => 'nullable|exists:program_zakat,id',
                'keterangan'       => 'nullable|string',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator->errors());

            DB::beginTransaction();
            $transaksi->muzakki_nama     = $request->muzakki_nama;
            $transaksi->muzakki_telepon  = $request->muzakki_telepon;
            $transaksi->muzakki_email    = $request->muzakki_email;
            $transaksi->muzakki_alamat   = $request->muzakki_alamat;
            $transaksi->program_zakat_id = $request->program_zakat_id;
            $transaksi->keterangan       = $request->keterangan;
            $transaksi->save();
            DB::commit();

            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('success', 'Data berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update error: ' . $e->getMessage());
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
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi ini tidak bisa dikonfirmasi.');
        }

        $request->validate(['catatan_konfirmasi' => 'nullable|string|max:500']);

        DB::beginTransaction();
        try {
            $transaksi->konfirmasi_status  = 'dikonfirmasi';
            $transaksi->dikonfirmasi_oleh  = $this->user->id;
            $transaksi->konfirmasi_at      = now();
            $transaksi->catatan_konfirmasi = $request->catatan_konfirmasi;
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
            $transaksi->save();
            DB::commit();

            $infaqMsg = $transaksi->jumlah_infaq > 0
                ? ' Infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ' dicatat.'
                : '';

            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('success', 'Pembayaran berhasil dikonfirmasi. Transaksi terverifikasi.' . $infaqMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Konfirmasi error: ' . $e->getMessage());
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Gagal konfirmasi pembayaran.');
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
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Status pembayaran tidak bisa ditolak.');
        }

        $request->validate(['catatan_konfirmasi' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            $transaksi->konfirmasi_status  = 'ditolak';
            $transaksi->dikonfirmasi_oleh  = $this->user->id;
            $transaksi->konfirmasi_at      = now();
            $transaksi->catatan_konfirmasi = $request->catatan_konfirmasi;
            $transaksi->status             = 'rejected';
            $transaksi->alasan_penolakan   = $request->catatan_konfirmasi;
            $transaksi->verified_by        = $this->user->id;
            $transaksi->verified_at        = now();
            $transaksi->save();
            DB::commit();

            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('success', 'Pembayaran ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('error', 'Gagal menolak pembayaran.');
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

    // ================================================================
    // UPDATE STATUS PENJEMPUTAN
    // ================================================================
    public function updateStatusPenjemputan(Request $request, $uuid)
    {
        if (!$this->user->isAmil()) {
            return response()->json(['error' => 'Hanya amil yang dapat update status penjemputan.'], 403);
        }

        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDiupdatePenjemputan) {
            return response()->json(['error' => 'Status penjemputan tidak dapat diupdate.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:diterima,dalam_perjalanan,sampai_lokasi,selesai'
        ]);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

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
                    break;
            }

            $transaksi->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status penjemputan berhasil diupdate.',
                'status'  => $status,
                'waktu'   => now()->format('H:i:s'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
        
        // Redirect back ke halaman sebelumnya
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
                'metode_penerimaan'
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
    // HELPER: Isi metode pembayaran datang langsung
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

        if ($metodePembayaran === 'tunai') {
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
        } else {
            $transaksi->status             = 'pending';
            $transaksi->konfirmasi_status  = 'menunggu_konfirmasi';
            $transaksi->no_referensi_transfer = $request->no_referensi_transfer;

            if ($request->hasFile('bukti_transfer')) {
                $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                $transaksi->bukti_transfer = $path;
            }
        }
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
        $transaksi->no_referensi_transfer = $request->no_referensi_transfer;

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
                $transaksi->no_referensi_transfer = $request->no_referensi_transfer;
            }
        }

        $transaksi->status      = 'verified';
        $transaksi->verified_by = $this->user->id;
        $transaksi->verified_at = now();
    }
}