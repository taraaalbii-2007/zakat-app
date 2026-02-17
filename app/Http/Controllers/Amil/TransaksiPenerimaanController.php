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

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user) abort(403, 'Unauthorized');

            if ($this->user->isAdminMasjid()) {
                $this->masjid = $this->user->masjid;
                $this->amil   = null;
            } elseif ($this->user->isAmil()) {
                $this->amil   = $this->user->amil;
                $this->masjid = $this->amil ? $this->amil->masjid : null;
            } else {
                abort(403, 'Hanya Admin Masjid dan Amil yang dapat mengakses');
            }

            if (!$this->masjid) abort(404, 'Data masjid tidak ditemukan.');

            view()->share('masjid', $this->masjid);

            return $next($request);
        });
    }

    // ===============================================================
    // INDEX
    // ===============================================================

    public function index(Request $request)
    {
        $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil'])
            ->byMasjid($this->masjid->id);

        if ($request->filled('q'))                $query->search($request->q);
        if ($request->filled('tanggal'))          $query->byTanggal($request->tanggal);
        if ($request->filled('start_date') && $request->filled('end_date'))
                                                   $query->byPeriode($request->start_date, $request->end_date);
        if ($request->filled('jenis_zakat_id'))   $query->byJenisZakat($request->jenis_zakat_id);
        if ($request->filled('metode_pembayaran'))$query->byMetodePembayaran($request->metode_pembayaran);
        if ($request->filled('status'))           $query->byStatus($request->status);
        if ($request->filled('konfirmasi_status'))$query->byKonfirmasiStatus($request->konfirmasi_status);
        if ($request->filled('metode_penerimaan'))$query->byMetodePenerimaan($request->metode_penerimaan);

        $query->orderBy('created_at', 'desc');

        $transaksis     = $query->paginate(10)->withQueryString();
        $jenisZakatList = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::byMasjid($this->masjid->id)
            ->whereIn('status', ['aktif', 'draft'])->orderBy('nama_program')->get();
        $amilList = Amil::byMasjid($this->masjid->id)->with('pengguna')->where('status', 'aktif')->get();

        $stats = [
            'total'          => TransaksiPenerimaan::byMasjid($this->masjid->id)->count(),
            'total_verified' => TransaksiPenerimaan::byMasjid($this->masjid->id)->verified()->count(),
            'total_pending'  => TransaksiPenerimaan::byMasjid($this->masjid->id)->pending()->count(),
            'menunggu_konfirmasi' => TransaksiPenerimaan::byMasjid($this->masjid->id)->menungguKonfirmasi()->count(),
            'total_nominal'  => TransaksiPenerimaan::byMasjid($this->masjid->id)->verified()->sum('jumlah'),
            'total_hari_ini' => TransaksiPenerimaan::byMasjid($this->masjid->id)->byTanggal(now())->verified()->sum('jumlah'),
        ];

        return view('amil.transaksi-penerimaan.index', compact(
            'transaksis', 'jenisZakatList', 'programZakatList', 'amilList', 'stats'
        ));
    }

    // ===============================================================
    // CREATE
    // ===============================================================

    public function create()
    {
        // Ambil rekening masjid untuk ditampilkan di form transfer
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

        return view('amil.transaksi-penerimaan.create', compact(
            'jenisZakatList', 'programZakatList', 'amilList',
            'noTransaksiPreview', 'tanggalHariIni', 'tipeZakatList', 'rekeningList'
        ));
    }

    // ===============================================================
    // AJAX: GET TIPE ZAKAT
    // ===============================================================

    public function getTipeZakat(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_zakat_id' => 'required|exists:jenis_zakat,id'
            ]);
            if ($validator->fails()) return response()->json(['error' => $validator->errors()->first()], 422);

            $list = TipeZakat::where('jenis_zakat_id', $request->jenis_zakat_id)
                ->orderBy('nama')->get(['id', 'nama', 'persentase_zakat'])->makeVisible(['id']);

            return response()->json($list);
        } catch (\Exception $e) {
            Log::error('Error getting tipe zakat: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data tipe zakat'], 500);
        }
    }

    public function getNisabInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipe_zakat_id' => 'required|exists:tipe_zakat,id'
            ]);
            if ($validator->fails()) return response()->json(['error' => $validator->errors()->first()], 422);

            $tipeZakat      = TipeZakat::with('jenisZakat')->find($request->tipe_zakat_id)->makeVisible(['id']);
            $hargaEmasPerGram = 1000000;
            $nisabRupiah    = $tipeZakat->nisab_emas_gram
                ? number_format($tipeZakat->nisab_emas_gram * $hargaEmasPerGram, 0, ',', '.') : null;

            return response()->json([
                'tipe_zakat'          => $tipeZakat,
                'jenis_zakat'         => $tipeZakat->jenisZakat,
                'persentase'          => $tipeZakat->persentase_zakat,
                'requires_haul'       => $tipeZakat->requires_haul,
                'nisab_rupiah'        => $nisabRupiah,
                'harga_emas_per_gram' => $hargaEmasPerGram,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting nisab info: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data nisab'], 500);
        }
    }

    // ===============================================================
    // STORE
    // ===============================================================

    public function store(Request $request)
    {
        Log::info('Transaction Store Request', [
            'user_id'           => $this->user->id,
            'masjid_id'         => $this->masjid->id,
            'metode_penerimaan' => $request->metode_penerimaan,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        try {
            $isDijemput        = $request->metode_penerimaan === 'dijemput';
            $isPembayaranBeras = $request->is_pembayaran_beras == '1';

            $rules = [
                'tanggal_transaksi' => 'required|date',
                'muzakki_nama'      => 'required|string|max:255',
                'muzakki_telepon'   => 'nullable|string|max:20',
                'muzakki_email'     => 'nullable|email|max:255',
                'muzakki_alamat'    => 'nullable|string',
                'muzakki_nik'       => 'nullable|string|size:16',
                'metode_penerimaan' => 'required|in:datang_langsung,dijemput',
                'keterangan'        => 'nullable|string',
            ];

            if ($isDijemput) {
                $rules['amil_id']   = 'required|exists:amil,id';
                $rules['latitude']  = 'required|numeric';
                $rules['longitude'] = 'required|numeric';
            } else {
                $rules['jenis_zakat_id']     = 'required|exists:jenis_zakat,id';
                $rules['tipe_zakat_id']      = 'required|exists:tipe_zakat,uuid';
                $rules['program_zakat_id']   = 'nullable|exists:program_zakat,id';
                $rules['is_pembayaran_beras']= 'nullable|boolean';

                if (!$isPembayaranBeras) {
                    $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';

                    // Untuk transfer/qris: wajib upload bukti
                    if ($request->metode_pembayaran && in_array($request->metode_pembayaran, ['transfer', 'qris'])) {
                        $rules['bukti_transfer']        = 'nullable|image|max:2048';
                        $rules['no_referensi_transfer'] = 'nullable|string|max:100';
                    }
                }

                $rules['jumlah_jiwa']        = 'nullable|integer|min:1';
                $rules['nominal_per_jiwa']   = 'nullable|numeric|min:0';
                $rules['jumlah_beras_kg']    = 'nullable|numeric|min:0';
                $rules['harga_beras_per_kg'] = 'nullable|numeric|min:0';
                $rules['nilai_harta']        = 'nullable|numeric|min:0';
                $rules['nisab_saat_ini']     = 'nullable|numeric|min:0';
                $rules['sudah_haul']         = 'nullable|boolean';
                $rules['tanggal_mulai_haul'] = 'nullable|date';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();

            try {
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
                $transaksi->metode_penerimaan = $request->metode_penerimaan;
                $transaksi->keterangan        = $request->keterangan;

                if ($isDijemput) {
                    $transaksi->amil_id            = $request->amil_id;
                    $transaksi->latitude           = $request->latitude;
                    $transaksi->longitude          = $request->longitude;
                    $transaksi->status             = 'pending';
                    $transaksi->status_penjemputan = 'menunggu';
                    $transaksi->waktu_request      = now();
                    $transaksi->jumlah             = 0;
                } else {
                    $this->isiDetailZakat($transaksi, $request, $isPembayaranBeras);
                    $this->isiMetodePembayaran($transaksi, $request, $isPembayaranBeras);
                }

                $transaksi->save();
                DB::commit();

                Log::info('Transaction saved', [
                    'no_transaksi' => $transaksi->no_transaksi,
                    'status'       => $transaksi->status,
                ]);

                $message = $isDijemput
                    ? 'Request penjemputan berhasil disimpan. Amil akan segera menghubungi.'
                    : ($isPembayaranBeras
                        ? 'Transaksi zakat fitrah beras berhasil: ' . $transaksi->no_transaksi
                        : 'Transaksi berhasil: ' . $transaksi->no_transaksi);

                return redirect()->route('transaksi-penerimaan.index')->with('success', $message);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving transaction: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Isi detail zakat (fitrah/mal/beras)
     */
    protected function isiDetailZakat(TransaksiPenerimaan $transaksi, Request $request, bool $isPembayaranBeras)
    {
        $jenisZakat = JenisZakat::findOrFail($request->jenis_zakat_id);
        $tipeZakat  = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();

        if ($tipeZakat->jenis_zakat_id != $request->jenis_zakat_id) {
            throw new \Exception('Tipe zakat tidak sesuai jenis zakat');
        }

        $isFitrah = stripos($jenisZakat->nama, 'fitrah') !== false;
        $isMal    = stripos($jenisZakat->nama, 'mal') !== false;
        $isBeras  = stripos($tipeZakat->nama, 'beras') !== false;

        $jumlah = 0;

        if ($isFitrah) {
            if ($isBeras) {
                if (!$request->filled('jumlah_beras_kg')) throw new \Exception('Jumlah beras harus diisi');
                $jumlah = 0;
            } else {
                if (!$request->filled('jumlah_jiwa') || !$request->filled('nominal_per_jiwa'))
                    throw new \Exception('Jumlah jiwa dan nominal per jiwa harus diisi');
                $jumlah = $request->jumlah_jiwa * $request->nominal_per_jiwa;
            }
        } elseif ($isMal) {
            if (!$request->filled('nilai_harta')) throw new \Exception('Nilai harta harus diisi');
            $jumlah = ($request->nilai_harta * ($tipeZakat->persentase_zakat ?? 2.5)) / 100;
        } else {
            $jumlah = $request->jumlah ?? 0;
        }

        if (!$isPembayaranBeras && $jumlah <= 0) throw new \Exception('Jumlah pembayaran tidak valid');

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

        if ($this->user->isAmil() && $this->amil) {
            $transaksi->amil_id = $this->amil->id;
        }
    }

    /**
     * Set metode pembayaran + status (konfirmasi manual untuk transfer/qris)
     */
    protected function isiMetodePembayaran(TransaksiPenerimaan $transaksi, Request $request, bool $isPembayaranBeras)
    {
        if ($isPembayaranBeras) {
            $transaksi->metode_pembayaran = 'tunai';
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
            return;
        }

        $transaksi->metode_pembayaran = $request->metode_pembayaran;

        if ($request->metode_pembayaran === 'tunai') {
            // Tunai → langsung verified
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
        } else {
            // Transfer / QRIS → menunggu konfirmasi manual amil
            $transaksi->status            = 'pending';
            $transaksi->konfirmasi_status = 'menunggu_konfirmasi';
            $transaksi->no_referensi_transfer = $request->no_referensi_transfer;

            // Simpan bukti transfer jika ada
            if ($request->hasFile('bukti_transfer')) {
                $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                $transaksi->bukti_transfer = $path;
            }
        }
    }

    // ===============================================================
    // SHOW
    // ===============================================================

    public function show($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid', 'jenisZakat', 'tipeZakat', 'programZakat',
            'amil.pengguna', 'verifiedBy', 'dikonfirmasiOleh'
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.show', compact('transaksi'));
    }

    // ===============================================================
    // EDIT
    // ===============================================================

    public function edit($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->status !== 'pending') {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi dengan status ' . $transaksi->status . ' tidak dapat diedit');
        }

        $isDijemput    = $transaksi->metode_penerimaan === 'dijemput';
        $needsZakatData= $isDijemput && !$transaksi->jenis_zakat_id;

        $rekeningList  = \App\Models\RekeningMasjid::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)->get();
        $jenisZakatList  = JenisZakat::orderBy('nama')->get();
        $programZakatList= ProgramZakat::byMasjid($this->masjid->id)
            ->whereIn('status', ['aktif', 'draft'])->orderBy('nama_program')->get();
        $amilList = Amil::byMasjid($this->masjid->id)->with('pengguna')->where('status', 'aktif')->get();

        $tipeZakatList = [];
        foreach ($jenisZakatList as $jenis) {
            $tipeZakatList[$jenis->id] = TipeZakat::where('jenis_zakat_id', $jenis->id)
                ->orderBy('nama')->get()->makeVisible(['id']);
        }

        return view('amil.transaksi-penerimaan.edit', compact(
            'transaksi', 'jenisZakatList', 'programZakatList', 'amilList',
            'tipeZakatList', 'isDijemput', 'needsZakatData', 'rekeningList'
        ));
    }

    // ===============================================================
    // UPDATE
    // ===============================================================

    public function update(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->status !== 'pending') {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi tidak dapat diupdate');
        }

        try {
            $isDijemput         = $transaksi->metode_penerimaan === 'dijemput';
            $isCompletingDijemput= $isDijemput && $request->filled('jenis_zakat_id');

            if ($isCompletingDijemput) {
                return $this->completePickupTransaction($request, $transaksi);
            }

            // Edit biasa: data muzakki & program
            $rules = [
                'muzakki_nama'    => 'required|string|max:255',
                'muzakki_telepon' => 'nullable|string|max:20',
                'muzakki_email'   => 'nullable|email|max:255',
                'muzakki_alamat'  => 'nullable|string',
                'program_zakat_id'=> 'nullable|exists:program_zakat,id',
                'keterangan'      => 'nullable|string',
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

            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating transaction: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    protected function completePickupTransaction(Request $request, TransaksiPenerimaan $transaksi)
    {
        $isPembayaranBeras = $request->is_pembayaran_beras == '1';

        $rules = [
            'jenis_zakat_id'   => 'required|exists:jenis_zakat,id',
            'tipe_zakat_id'    => 'required|exists:tipe_zakat,uuid',
            'program_zakat_id' => 'nullable|exists:program_zakat,id',
            'jumlah_jiwa'      => 'nullable|integer|min:1',
            'nominal_per_jiwa' => 'nullable|numeric|min:0',
            'jumlah_beras_kg'  => 'nullable|numeric|min:0',
            'nilai_harta'      => 'nullable|numeric|min:0',
            'sudah_haul'       => 'nullable|boolean',
            'tanggal_mulai_haul'=> 'nullable|date',
        ];

        if (!$isPembayaranBeras) {
            $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
            if (in_array($request->metode_pembayaran, ['transfer', 'qris'])) {
                $rules['bukti_transfer']        = 'nullable|image|max:2048';
                $rules['no_referensi_transfer'] = 'nullable|string|max:100';
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator->errors());

        DB::beginTransaction();

        try {
            $this->isiDetailZakat($transaksi, $request, $isPembayaranBeras);
            $this->isiMetodePembayaran($transaksi, $request, $isPembayaranBeras);

            $transaksi->status_penjemputan = 'selesai';
            $transaksi->waktu_selesai      = now();
            $transaksi->save();

            DB::commit();

            $message = $isPembayaranBeras
                ? 'Transaksi penjemputan beras selesai: ' . $transaksi->no_transaksi
                : 'Transaksi penjemputan selesai: ' . $transaksi->no_transaksi;

            return redirect()->route('transaksi-penerimaan.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ===============================================================
    // KONFIRMASI MANUAL (Transfer / QRIS)
    // ===============================================================

    /**
     * Amil mengkonfirmasi bahwa pembayaran transfer/QRIS sudah masuk ke rekening masjid.
     * Ini menggantikan callback Midtrans.
     */
    public function konfirmasiPembayaran(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDikonfirmasi) {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi ini tidak bisa dikonfirmasi');
        }

        $request->validate([
            'catatan_konfirmasi' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $transaksi->konfirmasi_status = 'dikonfirmasi';
            $transaksi->dikonfirmasi_oleh = $this->user->id;
            $transaksi->konfirmasi_at     = now();
            $transaksi->catatan_konfirmasi= $request->catatan_konfirmasi;

            // Setelah dikonfirmasi → verified
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();

            $transaksi->save();
            DB::commit();

            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('success', 'Pembayaran berhasil dikonfirmasi. Transaksi terverifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error konfirmasi: ' . $e->getMessage());
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Gagal konfirmasi pembayaran');
        }
    }

    /**
     * Amil menolak bukti transfer/QRIS (misal nominal tidak sesuai, bukti palsu).
     */
    public function tolakPembayaran(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if ($transaksi->konfirmasi_status !== 'menunggu_konfirmasi') {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Status pembayaran tidak bisa ditolak');
        }

        $request->validate([
            'catatan_konfirmasi' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $transaksi->konfirmasi_status = 'ditolak';
            $transaksi->dikonfirmasi_oleh = $this->user->id;
            $transaksi->konfirmasi_at     = now();
            $transaksi->catatan_konfirmasi= $request->catatan_konfirmasi;

            $transaksi->status           = 'rejected';
            $transaksi->alasan_penolakan = $request->catatan_konfirmasi;
            $transaksi->verified_by      = $this->user->id;
            $transaksi->verified_at      = now();

            $transaksi->save();
            DB::commit();

            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('success', 'Pembayaran ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error tolak pembayaran: ' . $e->getMessage());
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Gagal menolak pembayaran');
        }
    }

    // ===============================================================
    // VERIFY / REJECT (status transaksi)
    // ===============================================================

    public function verify($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDiverifikasi) {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi tidak dapat diverifikasi');
        }

        DB::beginTransaction();
        try {
            $transaksi->status      = 'verified';
            $transaksi->verified_by = $this->user->id;
            $transaksi->verified_at = now();
            $transaksi->save();
            DB::commit();

            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('success', 'Transaksi berhasil diverifikasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('error', 'Gagal verifikasi');
        }
    }

    public function reject(Request $request, $uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDitolak) {
            return redirect()->route('transaksi-penerimaan.show', $uuid)
                ->with('error', 'Transaksi tidak dapat ditolak');
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

            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('success', 'Transaksi ditolak');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi-penerimaan.show', $uuid)->with('error', 'Gagal menolak');
        }
    }

    // ===============================================================
    // UPDATE STATUS PENJEMPUTAN
    // ===============================================================

    public function updateStatusPenjemputan(Request $request, $uuid)
    {
        if (!$this->user->isAmil()) {
            return response()->json(['error' => 'Hanya amil yang dapat update status penjemputan'], 403);
        }

        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!$transaksi->bisaDiupdatePenjemputan) {
            return response()->json(['error' => 'Status penjemputan tidak dapat diupdate'], 400);
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
                case 'diterima':          $transaksi->waktu_diterima_amil = now(); break;
                case 'dalam_perjalanan':  $transaksi->waktu_berangkat = now();     break;
                case 'sampai_lokasi':     $transaksi->waktu_sampai = now();        break;
                case 'selesai':           $transaksi->waktu_selesai = now();       break;
            }

            $transaksi->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status penjemputan berhasil diupdate',
                'status'  => $status,
                'waktu'   => now()->format('H:i:s'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal mengupdate'], 500);
        }
    }

    // ===============================================================
    // PRINT KWITANSI
    // ===============================================================

    public function printKwitansi($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid', 'jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna', 'verifiedBy'
        ])
            ->where('uuid', $uuid)
            ->byMasjid($this->masjid->id)
            ->firstOrFail();

        return view('amil.transaksi-penerimaan.print', compact('transaksi'));
    }

    // ===============================================================
    // DESTROY
    // ===============================================================

    public function destroy($uuid)
    {
        $transaksi = TransaksiPenerimaan::where('uuid', $uuid)
            ->byMasjid($this->masjid->id)->firstOrFail();

        if (!in_array($transaksi->status, ['pending', 'rejected'])) {
            return redirect()->route('transaksi-penerimaan.index')
                ->with('error', 'Transaksi dengan status ' . $transaksi->status . ' tidak dapat dihapus');
        }

        DB::beginTransaction();
        try {
            $transaksi->delete();
            DB::commit();
            return redirect()->route('transaksi-penerimaan.index')->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi-penerimaan.index')->with('error', 'Gagal menghapus');
        }
    }

    // ===============================================================
    // EXPORT
    // ===============================================================

    public function exportPdf(Request $request)
    {
        try {
            $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna'])
                ->byMasjid($this->masjid->id);

            if ($request->filled('q'))                $query->search($request->q);
            if ($request->filled('start_date') && $request->filled('end_date'))
                                                       $query->byPeriode($request->start_date, $request->end_date);
            if ($request->filled('jenis_zakat_id'))   $query->byJenisZakat($request->jenis_zakat_id);
            if ($request->filled('metode_pembayaran'))$query->byMetodePembayaran($request->metode_pembayaran);
            if ($request->filled('status'))           $query->byStatus($request->status);
            if ($request->filled('metode_penerimaan'))$query->byMetodePenerimaan($request->metode_penerimaan);

            $transaksis   = $query->orderBy('created_at', 'desc')->get();
            $totalNominal = $transaksis->where('status', 'verified')->sum('jumlah');

            $pdf = PDF::loadView('amil.transaksi-penerimaan.exports.pdf', [
                'transaksis'    => $transaksis,
                'masjid'        => $this->masjid,
                'user'          => $this->user,
                'filters'       => $request->all(),
                'jenisZakatList'=> \App\Models\JenisZakat::all(),
                'totalNominal'  => $totalNominal,
                'totalVerified' => $transaksis->where('status', 'verified')->count(),
                'totalPending'  => $transaksis->where('status', 'pending')->count(),
                'totalTransaksi'=> $transaksis->count(),
                'tanggalExport' => now()->format('d/m/Y H:i:s'),
            ]);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('transaksi-penerimaan-' . date('Y-m-d-His') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error export PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $filters  = $request->only(['q', 'status', 'jenis_zakat_id', 'metode_pembayaran', 'start_date', 'end_date', 'metode_penerimaan']);
            $filename = 'transaksi-penerimaan-' . date('Y-m-d-His') . '.xlsx';
            return Excel::download(new TransaksiPenerimaanExport($filters, $this->user, $this->masjid), $filename);
        } catch (\Exception $e) {
            Log::error('Error export Excel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel');
        }
    }
}