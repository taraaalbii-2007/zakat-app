<?php

namespace App\Http\Controllers\Muzakki;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Amil\TransaksiPenerimaanController as AmilController;
use App\Models\TransaksiPenerimaan;
use App\Models\JenisZakat;
use App\Models\TipeZakat;
use App\Models\ProgramZakat;
use App\Models\Amil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Transaksi Zakat untuk Muzakki
 *
 * Muzakki hanya bisa:
 *   - Pilih mode: DARING (online) atau DIJEMPUT
 *   - DARING  → step 1 (data diri) → step 2 (detail zakat + pembayaran)
 *   - DIJEMPUT → step 1 saja (data diri + alamat penjemputan) → langsung simpan
 */
class TransaksiZakatController extends Controller
{
    protected $user;
    protected $muzakki;
    protected $masjid;

    // Konstanta zakat fitrah (sama dengan AmilController)
    const NOMINAL_ZAKAT_FITRAH_PER_JIWA = 50000;
    const BERAS_KG_PER_JIWA             = 2.5;
    const BERAS_LITER_PER_JIWA          = 3.5;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user || !$this->user->isMuzakki()) {
                abort(403, 'Hanya muzakki yang dapat mengakses halaman ini.');
            }

            $this->muzakki = $this->user->muzakki;

            if (!$this->muzakki) {
                return redirect()->route('dashboard')
                    ->with('error', 'Profil muzakki belum dilengkapi.');
            }

            $this->masjid = $this->muzakki->masjid;

            if (!$this->masjid) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda belum memilih masjid. Silakan lengkapi profil terlebih dahulu.');
            }

            view()->share('masjid', $this->masjid);
            view()->share('muzakki', $this->muzakki);
            view()->share('zakatFitrahInfo', [
                'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
                'beras_kg'         => self::BERAS_KG_PER_JIWA,
                'beras_liter'      => self::BERAS_LITER_PER_JIWA,
            ]);

            return $next($request);
        });
    }

    // ================================================================
    // INDEX — riwayat transaksi muzakki ini
    // ================================================================

    public function index(Request $request)
    {
        $query = TransaksiPenerimaan::with(['jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna'])
            ->where('muzakki_id', $this->muzakki->id);

        if ($request->filled('q'))               $query->search($request->q);
        if ($request->filled('status'))          $query->byStatus($request->status);
        if ($request->filled('jenis_zakat_id'))  $query->byJenisZakat($request->jenis_zakat_id);
        if ($request->filled('start_date') && $request->filled('end_date'))
                                                  $query->byPeriode($request->start_date, $request->end_date);

        $query->orderBy('created_at', 'desc');

        $transaksis     = $query->paginate(10);
        $jenisZakatList = JenisZakat::orderBy('nama')->get();

        $stats = [
            'total'          => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->count(),
            'total_verified' => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->verified()->count(),
            'total_pending'  => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->pending()->count(),
            'total_nominal'  => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->verified()->sum('jumlah'),
            'total_infaq'    => TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)->verified()->sum('jumlah_infaq'),
        ];

        return view('muzakki.transaksi.index', compact('transaksis', 'jenisZakatList', 'stats'));
    }

    // ================================================================
    // CREATE — step 1: pilih mode (daring/dijemput) + data diri
    // ================================================================

    public function create(Request $request)
    {
        // Mode default: daring
        $mode = $request->get('mode', 'daring');
        if (!in_array($mode, ['daring', 'dijemput'])) $mode = 'daring';

        $rekeningList = \App\Models\RekeningMasjid::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)->get();

        $jenisZakatList   = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::byMasjid($this->masjid->id)
            ->where('status', 'aktif')->orderBy('nama_program')->get();
        $amilList = Amil::byMasjid($this->masjid->id)->with('pengguna')->where('status', 'aktif')->get();

        $tipeZakatList = [];
        foreach ($jenisZakatList as $jenis) {
            $tipeZakatList[$jenis->id] = TipeZakat::where('jenis_zakat_id', $jenis->id)
                ->orderBy('nama')->get()->makeVisible(['id']);
        }

        $noTransaksiPreview = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
        $tanggalHariIni     = now()->format('Y-m-d');

        $zakatFitrahInfo = [
            'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
            'beras_kg'         => self::BERAS_KG_PER_JIWA,
            'beras_liter'      => self::BERAS_LITER_PER_JIWA,
        ];

        // Prefill dari data muzakki
        $muzakkiData = [
            'nama'    => $this->muzakki->nama,
            'telepon' => $this->muzakki->telepon,
            'email'   => $this->muzakki->email,
            'alamat'  => $this->muzakki->alamat,
            'nik'     => $this->muzakki->nik,
        ];

        return view('muzakki.transaksi.create', compact(
            'mode', 'jenisZakatList', 'programZakatList', 'amilList',
            'tipeZakatList', 'rekeningList', 'noTransaksiPreview',
            'tanggalHariIni', 'zakatFitrahInfo', 'muzakkiData'
        ));
    }

    // ================================================================
    // STORE — simpan transaksi muzakki
    // ================================================================

    public function store(Request $request)
    {
        Log::info('Muzakki TransaksiZakat Store', [
            'muzakki_id'        => $this->muzakki->id,
            'masjid_id'         => $this->masjid->id,
            'metode_penerimaan' => $request->metode_penerimaan,
        ]);

        $metode            = $request->metode_penerimaan;
        $isDijemput        = $metode === 'dijemput';
        $isDaring          = $metode === 'daring';
        $isPembayaranBeras = $request->is_pembayaran_beras == '1';

        // Validasi: muzakki hanya boleh daring atau dijemput
        if (!in_array($metode, ['daring', 'dijemput'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Metode penerimaan tidak valid untuk muzakki.');
        }

        // ── Rules validasi ─────────────────────────────────────────
        $rules = [
            'tanggal_transaksi' => 'required|date',
            'muzakki_nama'      => 'required|string|max:255',
            'muzakki_telepon'   => 'nullable|string|max:20',
            'muzakki_email'     => 'nullable|email|max:255',
            'muzakki_alamat'    => 'nullable|string',
            'muzakki_nik'       => 'nullable|string|size:16',
            'metode_penerimaan' => 'required|in:daring,dijemput',
            'keterangan'        => 'nullable|string',
        ];

        if ($isDijemput) {
            // Dijemput: hanya step 1, wajib koordinat
            $rules['latitude']  = 'required|numeric';
            $rules['longitude'] = 'required|numeric';
            // amil_id optional (sistem assign amil)
            $rules['amil_id']   = 'nullable|exists:amil,id';
        } else {
            // Daring: step 2, wajib detail zakat + pembayaran
            $rules['jenis_zakat_id']     = 'required|exists:jenis_zakat,id';
            $rules['tipe_zakat_id']      = 'required|exists:tipe_zakat,uuid';
            $rules['program_zakat_id']   = 'nullable|exists:program_zakat,id';
            $rules['is_pembayaran_beras'] = 'nullable|boolean';
            $rules['jumlah_jiwa']        = 'nullable|integer|min:1';
            $rules['nominal_per_jiwa']   = 'nullable|numeric|min:0';
            $rules['jumlah_beras_kg']    = 'nullable|numeric|min:0';
            $rules['harga_beras_per_kg'] = 'nullable|numeric|min:0';
            $rules['nilai_harta']        = 'nullable|numeric|min:0';
            $rules['nisab_saat_ini']     = 'nullable|numeric|min:0';
            $rules['sudah_haul']         = 'nullable|boolean';
            $rules['tanggal_mulai_haul'] = 'nullable|date';
            $rules['jumlah_dibayar']     = 'nullable|numeric|min:0';

            if (!$isPembayaranBeras) {
                $rules['metode_pembayaran'] = 'required|in:transfer,qris'; // daring tidak ada tunai
                $rules['bukti_transfer']    = 'nullable|image|max:2048';
                $rules['no_referensi_transfer'] = 'nullable|string|max:100';
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        try {
            DB::beginTransaction();

            $transaksi = new TransaksiPenerimaan();
            $transaksi->masjid_id         = $this->masjid->id;
            $transaksi->muzakki_id        = $this->muzakki->id;
            $transaksi->diinput_muzakki   = true;
            $transaksi->no_transaksi      = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->waktu_transaksi   = now();
            $transaksi->muzakki_nama      = $request->muzakki_nama;
            $transaksi->muzakki_telepon   = $request->muzakki_telepon;
            $transaksi->muzakki_email     = $request->muzakki_email;
            $transaksi->muzakki_alamat    = $request->muzakki_alamat;
            $transaksi->muzakki_nik       = $request->muzakki_nik;
            $transaksi->metode_penerimaan = $metode;
            $transaksi->keterangan        = $request->keterangan;

            if ($isDijemput) {
                // Dijemput — simpan sebagai pending request penjemputan
                $transaksi->latitude           = $request->latitude;
                $transaksi->longitude          = $request->longitude;
                $transaksi->amil_id            = $request->amil_id;
                $transaksi->status             = 'pending';
                $transaksi->status_penjemputan = 'menunggu';
                $transaksi->waktu_request      = now();
                $transaksi->jumlah             = 0;
                $transaksi->jumlah_dibayar     = 0;
                $transaksi->jumlah_infaq       = 0;
            } else {
                // Daring — isi detail zakat + pembayaran
                $this->isiDetailZakatMuzakki($transaksi, $request, $isPembayaranBeras);
                $this->isiMetodePembayaranMuzakki($transaksi, $request, $isPembayaranBeras);
            }

            $transaksi->save();
            DB::commit();

            Log::info('Muzakki transaksi saved', ['no' => $transaksi->no_transaksi]);

            if ($isDijemput) {
                $message = 'Request penjemputan berhasil. Amil akan segera menghubungi Anda di ' . $transaksi->muzakki_telepon . '.';
            } else {
                $infaqMsg = ($transaksi->jumlah_infaq ?? 0) > 0
                    ? ' (Termasuk infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ')'
                    : '';
                $message = 'Transaksi zakat berhasil dikirim: ' . $transaksi->no_transaksi . $infaqMsg
                    . '. Menunggu konfirmasi dari amil.';
            }

            return redirect()->route('muzakki.transaksi.show', $transaksi->uuid)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Muzakki store error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ================================================================
    // HELPER: Detail zakat untuk muzakki
    // ================================================================

    protected function isiDetailZakatMuzakki(TransaksiPenerimaan $transaksi, Request $request, bool $isPembayaranBeras): void
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
                if (!$request->filled('jumlah_beras_kg'))
                    throw new \Exception('Jumlah beras (kg) harus diisi.');
                $jumlah = 0;
            } else {
                if (!$request->filled('jumlah_jiwa') || !$request->filled('nominal_per_jiwa'))
                    throw new \Exception('Jumlah jiwa dan nominal per jiwa harus diisi.');
                $jumlah = (int) $request->jumlah_jiwa * (float) $request->nominal_per_jiwa;
            }
        } elseif ($isMal) {
            if (!$request->filled('nilai_harta'))
                throw new \Exception('Nilai harta harus diisi untuk zakat mal.');
            $jumlah = ((float) $request->nilai_harta * ((float) ($tipeZakat->persentase_zakat ?? 2.5))) / 100;
        } else {
            $jumlah = (float) ($request->jumlah ?? 0);
        }

        if (!$isPembayaranBeras && $jumlah <= 0) {
            throw new \Exception('Jumlah zakat tidak valid.');
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
    // HELPER: Metode pembayaran untuk muzakki (daring = transfer/qris)
    // ================================================================

    protected function isiMetodePembayaranMuzakki(TransaksiPenerimaan $transaksi, Request $request, bool $isPembayaranBeras): void
    {
        if ($isPembayaranBeras) {
            // Beras: tidak ada pembayaran uang
            $transaksi->metode_pembayaran = 'tunai';
            $transaksi->jumlah_dibayar   = 0;
            $transaksi->jumlah_infaq     = 0;
            $transaksi->has_infaq        = false;
            // Daring beras tetap pending, amil yang verifikasi
            $transaksi->status           = 'pending';
            $transaksi->konfirmasi_status = 'menunggu_konfirmasi';
            return;
        }

        $metodePembayaran = $request->metode_pembayaran; // transfer atau qris
        $transaksi->metode_pembayaran = $metodePembayaran;

        // Hitung infaq dari kelebihan bayar
        $jumlahZakat   = (float) $transaksi->jumlah;
        $jumlahDibayar = $request->filled('jumlah_dibayar')
            ? (float) $request->jumlah_dibayar
            : $jumlahZakat;

        if ($jumlahDibayar <= 0) $jumlahDibayar = $jumlahZakat;

        $infaq = max(0, $jumlahDibayar - $jumlahZakat);

        $transaksi->jumlah_dibayar = $jumlahDibayar;
        $transaksi->jumlah_infaq   = $infaq;
        $transaksi->has_infaq      = $infaq > 0;

        // Muzakki daring selalu pending, menunggu konfirmasi amil
        $transaksi->status            = 'pending';
        $transaksi->konfirmasi_status = 'menunggu_konfirmasi';
        $transaksi->no_referensi_transfer = $request->no_referensi_transfer;

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
            $transaksi->bukti_transfer = $path;
        }
    }

    // ================================================================
    // SHOW — detail transaksi muzakki
    // ================================================================

    public function show($uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid', 'jenisZakat', 'tipeZakat', 'programZakat',
            'amil.pengguna', 'verifiedBy'
        ])
            ->where('uuid', $uuid)
            ->where('muzakki_id', $this->muzakki->id)
            ->firstOrFail();

        return view('muzakki.transaksi.show', compact('transaksi'));
    }
}