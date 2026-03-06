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

class TransaksiZakatController extends Controller
{
    protected $user;
    protected $muzakki;
    protected $masjid;

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
                    ->with('error', 'Profil muzakki belum dilengkapi. Silakan lengkapi profil terlebih dahulu.');
            }

            $this->masjid = $this->muzakki->masjid;
            if (!$this->masjid) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda belum memilih masjid. Silakan lengkapi profil terlebih dahulu.');
            }

            view()->share([
                'masjid'          => $this->masjid,
                'muzakki'         => $this->muzakki,
                'zakatFitrahInfo' => [
                    'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
                    'beras_kg'         => self::BERAS_KG_PER_JIWA,
                    'beras_liter'      => self::BERAS_LITER_PER_JIWA,
                ],
            ]);

            return $next($request);
        });
    }

    // ================================================================
    // INDEX
    // ================================================================
    public function index(Request $request)
    {
        $query = TransaksiPenerimaan::with([
            'jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna',
        ])->where('muzakki_id', $this->muzakki->id);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_transaksi', 'like', "%{$q}%")
                    ->orWhere('muzakki_nama', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status'))             $query->where('status', $request->status);
        if ($request->filled('jenis_zakat_id'))     $query->where('jenis_zakat_id', $request->jenis_zakat_id);
        if ($request->filled('metode_penerimaan'))  $query->where('metode_penerimaan', $request->metode_penerimaan);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        $transaksis     = $query->orderBy('created_at', 'desc')->paginate(15);
        $jenisZakatList = JenisZakat::orderBy('nama')->get();

        $statsRaw = TransaksiPenerimaan::where('muzakki_id', $this->muzakki->id)
            ->selectRaw("
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) AS total_verified,
                SUM(CASE WHEN status = 'pending'  THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN status = 'verified' THEN jumlah       ELSE 0 END) AS total_nominal,
                SUM(CASE WHEN status = 'verified' THEN jumlah_infaq ELSE 0 END) AS total_infaq
            ")
            ->first();

        $stats = [
            'total'          => (int)   $statsRaw->total,
            'total_verified' => (int)   $statsRaw->total_verified,
            'total_pending'  => (int)   $statsRaw->total_pending,
            'total_nominal'  => (float) $statsRaw->total_nominal,
            'total_infaq'    => (float) $statsRaw->total_infaq,
        ];

        return view('muzakki.transaksi-daring-muzakki.index', compact(
            'transaksis',
            'jenisZakatList',
            'stats'
        ));
    }

    // ================================================================
    // CREATE
    // ================================================================
    public function create()
    {
        $jenisZakatList   = JenisZakat::orderBy('nama')->get();
        $programZakatList = ProgramZakat::where('masjid_id', $this->masjid->id)
            ->where('status', 'aktif')
            ->orderBy('nama_program')
            ->get();
        $amilList = Amil::where('masjid_id', $this->masjid->id)
            ->where('status', 'aktif')
            ->with('pengguna')
            ->get();
        $rekeningMasjidList = RekeningMasjid::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)
            ->get();

        $tipeZakatList = [];
        foreach ($jenisZakatList as $jenis) {
            $tipeZakatList[$jenis->id] = TipeZakat::where('jenis_zakat_id', $jenis->id)
                ->orderBy('nama')
                ->get()
                ->map(fn($t) => [
                    'uuid'             => $t->uuid,
                    'id'               => $t->id,
                    'nama'             => $t->nama,
                    'persentase_zakat' => $t->persentase_zakat ?? 2.5,
                ])
                ->toArray();
        }

        $muzakkiData = [
            'nama'    => $this->muzakki->nama ?? $this->user->username ?? 'Muzakki',
            'email'   => $this->muzakki->email ?? $this->user->email,
            'telepon' => $this->muzakki->telepon ?? '',
            'nik'     => $this->muzakki->nik ?? '',
            'alamat'  => $this->muzakki->alamat ?? '',
        ];

        $zakatFitrahInfo = [
            'nominal_per_jiwa' => self::NOMINAL_ZAKAT_FITRAH_PER_JIWA,
            'beras_kg'         => self::BERAS_KG_PER_JIWA,
            'beras_liter'      => self::BERAS_LITER_PER_JIWA,
        ];

        $konfigurasiQris = \App\Models\KonfigurasiQris::where('masjid_id', $this->masjid->id)
            ->where('is_active', true)
            ->first();

        return view('muzakki.transaksi-daring-muzakki.create', compact(
            'jenisZakatList',
            'programZakatList',
            'amilList',
            'tipeZakatList',
            'rekeningMasjidList',
            'zakatFitrahInfo',
            'muzakkiData',
            'konfigurasiQris'
        ));
    }

    // ================================================================
    // STORE
    // ================================================================
    public function store(Request $request)
    {
        $metode     = $request->metode_penerimaan;
        $isDijemput = $metode === 'dijemput';
        $isDaring   = $metode === 'daring';

        if (!in_array($metode, ['daring', 'dijemput'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Metode penerimaan tidak valid.');
        }

        $validator = Validator::make(
            $request->all(),
            $this->getValidationRules($isDaring, $isDijemput),
            $this->getValidationMessages()
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        // Validasi tambahan: jumlah_dibayar tidak boleh kurang dari jumlah zakat (hanya daring)
        if ($isDaring) {
            $jumlahZakat   = $this->hitungJumlahZakat($request);
            $jumlahDibayar = (float) $request->jumlah_dibayar;

            if ($jumlahZakat > 0 && $jumlahDibayar < $jumlahZakat) {
                return redirect()->back()->withInput()->withErrors([
                    'jumlah_dibayar' => 'Jumlah dibayar (Rp ' . number_format($jumlahDibayar, 0, ',', '.') .
                                        ') tidak boleh kurang dari jumlah zakat (Rp ' . number_format($jumlahZakat, 0, ',', '.') . ').',
                ]);
            }
        }

        try {
            DB::beginTransaction();

            $transaksi = new TransaksiPenerimaan();
            $transaksi->masjid_id         = $this->masjid->id;
            $transaksi->muzakki_id        = $this->muzakki->id;
            $transaksi->diinput_muzakki   = true;
            $transaksi->no_transaksi      = TransaksiPenerimaan::generateNoTransaksi($this->masjid->id);
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi ?? now()->format('Y-m-d');
            $transaksi->waktu_transaksi   = now();

            $transaksi->muzakki_nama    = $request->muzakki_nama;
            $transaksi->muzakki_email   = $request->muzakki_email;
            $transaksi->muzakki_telepon = $request->muzakki_telepon;
            $transaksi->muzakki_nik     = $request->muzakki_nik;
            $transaksi->muzakki_alamat  = $request->muzakki_alamat;

            $transaksi->metode_penerimaan = $metode;
            $transaksi->keterangan        = $request->keterangan;

            if ($isDijemput) {
                $this->isiDataDijemput($transaksi, $request);
            } else {
                $this->isiDetailZakatDaring($transaksi, $request);
                $this->isiMetodePembayaranDaring($transaksi, $request);
                $this->simpanNamaJiwa($transaksi, $request);
            }

            $transaksi->save();

            DB::commit();

            Log::info('Muzakki transaksi saved', [
                'no_transaksi'  => $transaksi->no_transaksi,
                'muzakki_id'    => $this->muzakki->id,
                'metode'        => $metode,
                'jumlah'        => $transaksi->jumlah,
                'jumlah_dibayar'=> $transaksi->jumlah_dibayar,
                'jumlah_infaq'  => $transaksi->jumlah_infaq,
            ]);

            $infaqNote = $transaksi->jumlah_infaq > 0
                ? ' (Termasuk infaq Rp ' . number_format($transaksi->jumlah_infaq, 0, ',', '.') . ')'
                : '';

            $message = $isDijemput
                ? 'Request penjemputan berhasil dikirim. Amil akan menghubungi Anda segera.'
                : 'Transaksi zakat berhasil dikirim: ' . $transaksi->no_transaksi . $infaqNote . '. Menunggu konfirmasi dari amil.';

            return redirect()->route('transaksi-daring-muzakki.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Muzakki store error', [
                'muzakki_id' => $this->muzakki->id,
                'message'    => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ================================================================
    // SHOW
    // ================================================================
    public function show(string $uuid)
    {
        $transaksi = TransaksiPenerimaan::with([
            'masjid', 'jenisZakat', 'tipeZakat', 'programZakat', 'amil.pengguna', 'verifiedBy',
        ])
            ->where('uuid', $uuid)
            ->where('muzakki_id', $this->muzakki->id)
            ->firstOrFail();

        return view('muzakki.transaksi-daring-muzakki.show', compact('transaksi'));
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    /**
     * Validation rules dinamis berdasarkan metode penerimaan.
     */
    private function getValidationRules(bool $isDaring, bool $isDijemput): array
    {
        $rules = [
            'tanggal_transaksi' => 'nullable|date',
            'muzakki_nama'      => 'required|string|max:255',
            'muzakki_email'     => 'nullable|email|max:255',
            'muzakki_telepon'   => 'required|string|max:20',
            'muzakki_nik'       => 'nullable|string|max:16',
            'muzakki_alamat'    => 'required|string|max:500',
            'metode_penerimaan' => 'required|in:daring,dijemput',
            'keterangan'        => 'nullable|string|max:1000',
        ];

        if ($isDijemput) {
            $rules['latitude']  = 'required|numeric|between:-90,90';
            $rules['longitude'] = 'required|numeric|between:-180,180';
            $rules['amil_id']   = 'nullable|exists:amil,id';
        }

        if ($isDaring) {
            $rules['jenis_zakat_id']   = 'required|exists:jenis_zakat,id';
            $rules['tipe_zakat_id']    = 'required|exists:tipe_zakat,uuid';
            $rules['program_zakat_id'] = 'nullable|exists:program_zakat,id';
            $rules['jumlah_jiwa']      = 'nullable|integer|min:1|max:100';
            $rules['nominal_per_jiwa'] = 'nullable|numeric|min:0';
            $rules['nilai_harta']      = 'nullable|numeric|min:0';
            $rules['nisab_saat_ini']   = 'nullable|numeric|min:0';
            $rules['sudah_haul']       = 'nullable|boolean';
            $rules['tanggal_mulai_haul'] = 'nullable|date';

            // PERUBAHAN: jumlah_dibayar sekarang WAJIB diisi
            $rules['jumlah_dibayar']   = 'required|numeric|min:1';

            $rules['metode_pembayaran'] = 'required|in:transfer,qris';

            // PERUBAHAN: bukti WAJIB sesuai metode yang dipilih
            // Validasi kondisional: required_if tidak cukup karena salah satu dari dua field
            // Ditangani di isiMetodePembayaranDaring() dengan pengecekan manual
            $rules['bukti_transfer'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
            $rules['bukti_qris']     = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';

            $rules['nama_jiwa']   = 'nullable|array|max:100';
            $rules['nama_jiwa.*'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Pesan validasi custom yang lebih informatif.
     */
    private function getValidationMessages(): array
    {
        return [
            'muzakki_telepon.required' => 'Nomor telepon wajib diisi.',
            'muzakki_alamat.required'  => 'Alamat lengkap wajib diisi.',
            'jenis_zakat_id.required'  => 'Jenis zakat wajib dipilih.',
            'tipe_zakat_id.required'   => 'Tipe zakat wajib dipilih.',
            'jumlah_dibayar.required'  => 'Jumlah dibayar wajib diisi.',
            'jumlah_dibayar.min'       => 'Jumlah dibayar harus lebih dari 0.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'latitude.required'        => 'Lokasi GPS wajib dideteksi. Klik tombol "Dapatkan Lokasi GPS Saya".',
            'longitude.required'       => 'Lokasi GPS wajib dideteksi. Klik tombol "Dapatkan Lokasi GPS Saya".',
        ];
    }

    /**
     * Hitung jumlah zakat dari request (untuk validasi pre-save).
     */
    private function hitungJumlahZakat(Request $request): float
    {
        $jenisZakat = JenisZakat::find($request->jenis_zakat_id);
        if (!$jenisZakat) return 0;

        $isFitrah = stripos($jenisZakat->nama, 'fitrah') !== false;
        $isMal    = stripos($jenisZakat->nama, 'mal') !== false;

        if ($isFitrah && $request->filled('jumlah_jiwa') && $request->filled('nominal_per_jiwa')) {
            return (int) $request->jumlah_jiwa * (float) $request->nominal_per_jiwa;
        }

        if ($isMal && $request->filled('nilai_harta')) {
            $tipeZakat  = TipeZakat::where('uuid', $request->tipe_zakat_id)->first();
            $persentase = $tipeZakat ? (float) ($tipeZakat->persentase_zakat ?? 2.5) : 2.5;
            return (float) $request->nilai_harta * ($persentase / 100);
        }

        return 0;
    }

    /**
     * Isi data khusus metode dijemput.
     */
    private function isiDataDijemput(TransaksiPenerimaan $transaksi, Request $request): void
    {
        $transaksi->latitude           = $request->latitude;
        $transaksi->longitude          = $request->longitude;
        $transaksi->amil_id            = $request->amil_id ?: null;
        $transaksi->status             = 'pending';
        $transaksi->status_penjemputan = 'menunggu';
        $transaksi->waktu_request      = now();
        $transaksi->jumlah             = 0;
        $transaksi->jumlah_dibayar     = 0;
        $transaksi->jumlah_infaq       = 0;
    }

    /**
     * Isi detail zakat untuk metode daring.
     */
    private function isiDetailZakatDaring(TransaksiPenerimaan $transaksi, Request $request): void
    {
        $jenisZakat = JenisZakat::findOrFail($request->jenis_zakat_id);
        $tipeZakat  = TipeZakat::where('uuid', $request->tipe_zakat_id)->firstOrFail();

        if ((int) $tipeZakat->jenis_zakat_id !== (int) $request->jenis_zakat_id) {
            throw new \Exception('Tipe zakat tidak sesuai dengan jenis zakat yang dipilih.');
        }

        $isFitrah = stripos($jenisZakat->nama, 'fitrah') !== false;
        $isMal    = stripos($jenisZakat->nama, 'mal') !== false;
        $isBeras  = stripos($tipeZakat->nama, 'beras') !== false;

        if ($isFitrah && $isBeras) {
            throw new \Exception(
                'Pembayaran beras tidak tersedia untuk metode daring. Silakan gunakan metode dijemput.'
            );
        }

        $jumlah = 0;

        if ($isFitrah) {
            if (!$request->filled('jumlah_jiwa') || !$request->filled('nominal_per_jiwa')) {
                throw new \Exception('Jumlah jiwa dan nominal per jiwa wajib diisi untuk zakat fitrah.');
            }
            $jumlah = (int) $request->jumlah_jiwa * (float) $request->nominal_per_jiwa;
            $transaksi->jumlah_jiwa      = (int) $request->jumlah_jiwa;
            $transaksi->nominal_per_jiwa = (float) $request->nominal_per_jiwa;

        } elseif ($isMal) {
            if (!$request->filled('nilai_harta')) {
                throw new \Exception('Nilai harta wajib diisi untuk zakat mal.');
            }
            $nilaiHarta = (float) $request->nilai_harta;
            $persentase = (float) ($tipeZakat->persentase_zakat ?? 2.5);
            $jumlah     = $nilaiHarta * ($persentase / 100);

            $transaksi->nilai_harta        = $nilaiHarta;
            $transaksi->nisab_saat_ini     = $request->filled('nisab_saat_ini') ? (float) $request->nisab_saat_ini : null;
            $transaksi->sudah_haul         = $request->boolean('sudah_haul', false);
            $transaksi->tanggal_mulai_haul = $request->tanggal_mulai_haul ?: null;

        } else {
            // Jenis lain — gunakan jumlah_dibayar sebagai acuan
            $jumlah = (float) $request->jumlah_dibayar;
        }

        if ($jumlah <= 0) {
            throw new \Exception('Jumlah zakat tidak valid. Periksa kembali data yang diisi.');
        }

        $transaksi->jenis_zakat_id   = (int) $request->jenis_zakat_id;
        $transaksi->tipe_zakat_id    = $tipeZakat->id;
        $transaksi->program_zakat_id = $request->program_zakat_id ?: null;
        $transaksi->jumlah           = (int) round($jumlah);
    }

    /**
     * Isi metode pembayaran, bukti (WAJIB), dan kalkulasi infaq.
     *
     * PERUBAHAN:
     * - jumlah_dibayar sekarang wajib diisi dari form
     * - bukti bayar (transfer atau qris) wajib ada
     * - infaq = jumlah_dibayar - jumlah_zakat (jika lebih)
     * - jika kurang dari zakat → throw exception (sudah dicek di store() juga)
     */
    private function isiMetodePembayaranDaring(TransaksiPenerimaan $transaksi, Request $request): void
    {
        $metodePembayaran = $request->metode_pembayaran; // transfer | qris
        $transaksi->metode_pembayaran = $metodePembayaran;

        // ── Validasi bukti wajib ──
        if ($metodePembayaran === 'transfer' && !$request->hasFile('bukti_transfer')) {
            throw new \Exception('Bukti transfer wajib diupload untuk metode Transfer Bank.');
        }
        if ($metodePembayaran === 'qris' && !$request->hasFile('bukti_qris')) {
            throw new \Exception('Bukti pembayaran QRIS wajib diupload untuk metode QRIS.');
        }

        // ── Jumlah zakat & dibayar ──
        $jumlahZakat   = (float) $transaksi->jumlah;
        $jumlahDibayar = (float) $request->jumlah_dibayar;

        // Guard: tidak boleh bayar kurang dari zakat (double-check setelah validasi awal)
        if ($jumlahDibayar < $jumlahZakat) {
            throw new \Exception(
                'Jumlah dibayar (Rp ' . number_format($jumlahDibayar, 0, ',', '.') .
                ') tidak boleh kurang dari jumlah zakat (Rp ' . number_format($jumlahZakat, 0, ',', '.') . ').'
            );
        }

        // ── Hitung infaq dari kelebihan bayar ──
        $infaq = max(0.0, $jumlahDibayar - $jumlahZakat);

        $transaksi->jumlah_dibayar    = (int) round($jumlahDibayar);
        $transaksi->jumlah_infaq      = (int) round($infaq);
        $transaksi->has_infaq         = $infaq > 0;
        $transaksi->status            = 'pending';
        $transaksi->konfirmasi_status = 'menunggu_konfirmasi';

        // ── Simpan bukti ──
        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
            $transaksi->bukti_transfer = $path;
        }

        if ($request->hasFile('bukti_qris')) {
            $path = $request->file('bukti_qris')->store('bukti-qris', 'public');
            // Simpan ke kolom yang sama (bukti_transfer) atau buat kolom bukti_qris tersendiri
            $transaksi->bukti_transfer = $path;
        }
    }

    /**
     * Simpan daftar nama jiwa ke kolom nama_jiwa_json.
     * Hanya berlaku untuk zakat fitrah.
     */
    private function simpanNamaJiwa(TransaksiPenerimaan $transaksi, Request $request): void
    {
        $jenisZakat = JenisZakat::find($request->jenis_zakat_id);
        if (!$jenisZakat || stripos($jenisZakat->nama, 'fitrah') === false) {
            return;
        }

        $namaJiwaBersih = array_values(
            array_filter(
                array_map('trim', $request->input('nama_jiwa', [])),
                fn($n) => $n !== ''
            )
        );

        if (empty($namaJiwaBersih)) return;

        $transaksi->nama_jiwa_json = $namaJiwaBersih;

        Log::info('Nama jiwa disimpan', [
            'no_transaksi' => $transaksi->no_transaksi,
            'jumlah_jiwa'  => count($namaJiwaBersih),
        ]);
    }
}