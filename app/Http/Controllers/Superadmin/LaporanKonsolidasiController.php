<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use App\Models\TransaksiPenerimaan;
use App\Models\TransaksiPenyaluran;
use App\Models\JenisZakat;
use App\Models\KategoriMustahik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKonsolidasiExport;

class LaporanKonsolidasiController extends Controller
{
    // ================================================================
    // INDEX — Daftar semua masjid dengan ringkasan per periode
    // ================================================================

    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan');
        $search = $request->get('search');
        $masjidId = $request->get('masjid_id');

        // Ambil semua masjid (dengan filter search/masjid_id)
        $masjidQuery = Masjid::query()->orderBy('nama');

        if ($search) {
            $masjidQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_masjid', 'like', "%{$search}%")
                    ->orWhere('kota_nama', 'like', "%{$search}%");
            });
        }

        if ($masjidId) {
            $masjidQuery->where('id', $masjidId);
        }

        $masjids = $masjidQuery->get();
        $masjidIds = $masjids->pluck('id')->toArray();

        // ── Penerimaan (status=verified) per masjid + periode ──────────────
        $penerimaanQuery = DB::table('transaksi_penerimaan')
            ->select(
                'masjid_id',
                DB::raw('YEAR(tanggal_transaksi) as tahun'),
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(jumlah) as total_penerimaan'),
                DB::raw('COUNT(DISTINCT muzakki_nama) as jumlah_muzakki')
            )
            ->where('status', 'verified')
            ->whereIn('masjid_id', $masjidIds)
            ->where(DB::raw('YEAR(tanggal_transaksi)'), $tahun)
            ->groupBy('masjid_id', DB::raw('YEAR(tanggal_transaksi)'), DB::raw('MONTH(tanggal_transaksi)'));

        if ($bulan) {
            $penerimaanQuery->where(DB::raw('MONTH(tanggal_transaksi)'), $bulan);
        }

        $penerimaanData = $penerimaanQuery->get()->groupBy(function ($row) {
            return $row->masjid_id . '-' . $row->tahun . '-' . $row->bulan;
        });

        // ── Penyaluran (status=disalurkan) per masjid + periode ────────────
        $penyaluranQuery = DB::table('transaksi_penyaluran')
            ->select(
                'masjid_id',
                DB::raw('YEAR(tanggal_penyaluran) as tahun'),
                DB::raw('MONTH(tanggal_penyaluran) as bulan'),
                DB::raw('SUM(CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END) as total_penyaluran'),
                DB::raw('COUNT(DISTINCT mustahik_id) as jumlah_mustahik')
            )
            ->where('status', 'disalurkan')
            ->whereIn('masjid_id', $masjidIds)
            ->where(DB::raw('YEAR(tanggal_penyaluran)'), $tahun)
            ->whereNull('deleted_at')
            ->groupBy('masjid_id', DB::raw('YEAR(tanggal_penyaluran)'), DB::raw('MONTH(tanggal_penyaluran)'));

        if ($bulan) {
            $penyaluranQuery->where(DB::raw('MONTH(tanggal_penyaluran)'), $bulan);
        }

        $penyaluranData = $penyaluranQuery->get()->groupBy(function ($row) {
            return $row->masjid_id . '-' . $row->tahun . '-' . $row->bulan;
        });

        // ── Setor Kas (status=diterima) per masjid + periode ───────────────
        $setorKasQuery = DB::table('setor_kas')
            ->select(
                'masjid_id',
                DB::raw('YEAR(tanggal_setor) as tahun'),
                DB::raw('MONTH(tanggal_setor) as bulan'),
                DB::raw('SUM(jumlah_disetor) as total_setor_kas')
            )
            ->where('status', 'diterima')
            ->whereIn('masjid_id', $masjidIds)
            ->where(DB::raw('YEAR(tanggal_setor)'), $tahun)
            ->groupBy('masjid_id', DB::raw('YEAR(tanggal_setor)'), DB::raw('MONTH(tanggal_setor)'));

        if ($bulan) {
            $setorKasQuery->where(DB::raw('MONTH(tanggal_setor)'), $bulan);
        }

        $setorKasData = $setorKasQuery->get()->groupBy(function ($row) {
            return $row->masjid_id . '-' . $row->tahun . '-' . $row->bulan;
        });

        // ── Susun data per masjid ───────────────────────────────────────────
        $bulanList = $bulan ? [$bulan] : range(1, 12);

        $laporanPerMasjid = [];

        foreach ($masjids as $m) {
            $periodes = [];
            $masjidTotalPenerimaan = 0;
            $masjidTotalPenyaluran = 0;
            $masjidTotalSetorKas   = 0;
            $masjidTotalMuzakki    = 0;
            $masjidTotalMustahik   = 0;

            foreach ($bulanList as $bl) {
                $key = $m->id . '-' . $tahun . '-' . $bl;

                $pen  = $penerimaanData->get($key);
                $peny = $penyaluranData->get($key);
                $setor = $setorKasData->get($key);

                $totalPen   = $pen  ? $pen->first()->total_penerimaan  : 0;
                $totalPeny  = $peny ? $peny->first()->total_penyaluran : 0;
                $totalSetor = $setor ? $setor->first()->total_setor_kas  : 0;
                $jmlMuzakki = $pen  ? $pen->first()->jumlah_muzakki    : 0;
                $jmlMustahik = $peny ? $peny->first()->jumlah_mustahik  : 0;

                // Hanya tampilkan bulan yang punya data
                if ($totalPen == 0 && $totalPeny == 0 && $totalSetor == 0) {
                    continue;
                }

                $masjidTotalPenerimaan += $totalPen;
                $masjidTotalPenyaluran += $totalPeny;
                $masjidTotalSetorKas   += $totalSetor;
                $masjidTotalMuzakki    += $jmlMuzakki;
                $masjidTotalMustahik   += $jmlMustahik;

                $periodes[] = [
                    'tahun'            => $tahun,
                    'bulan'            => $bl,
                    'bulan_nama'       => $this->namaBulan($bl),
                    'total_penerimaan' => $totalPen,
                    'total_penyaluran' => $totalPeny,
                    'total_setor_kas'  => $totalSetor,
                    'jumlah_muzakki'   => $jmlMuzakki,
                    'jumlah_mustahik'  => $jmlMustahik,
                    'saldo_akhir'      => $totalPen + $totalSetor - $totalPeny,
                ];
            }

            // Skip masjid tanpa data sama sekali
            if (empty($periodes) && !$search && !$masjidId) {
                continue;
            }

            $laporanPerMasjid[] = [
                'masjid'             => $m,
                'periodes'           => $periodes,
                'total_penerimaan'   => $masjidTotalPenerimaan,
                'total_penyaluran'   => $masjidTotalPenyaluran,
                'total_setor_kas'    => $masjidTotalSetorKas,
                'jumlah_muzakki'     => $masjidTotalMuzakki,
                'jumlah_mustahik'    => $masjidTotalMustahik,
                'saldo_akhir'        => $masjidTotalPenerimaan + $masjidTotalSetorKas - $masjidTotalPenyaluran,
            ];
        }

        // ── Grand total ─────────────────────────────────────────────────────
        $grandTotal = [
            'penerimaan'  => collect($laporanPerMasjid)->sum('total_penerimaan'),
            'penyaluran'  => collect($laporanPerMasjid)->sum('total_penyaluran'),
            'setor_kas'   => collect($laporanPerMasjid)->sum('total_setor_kas'),
            'saldo_akhir' => collect($laporanPerMasjid)->sum('saldo_akhir'),
            'muzakki'     => collect($laporanPerMasjid)->sum('jumlah_muzakki'),
            'mustahik'    => collect($laporanPerMasjid)->sum('jumlah_mustahik'),
            'masjid'      => count($laporanPerMasjid),
        ];

        $allMasjids    = Masjid::orderBy('nama')->get(['id', 'nama']);
        $availableYears = $this->getAvailableYears();

        return view('superadmin.laporan-konsolidasi.index', compact(
            'laporanPerMasjid',
            'grandTotal',
            'allMasjids',
            'availableYears',
            'tahun',
            'bulan',
            'search',
            'masjidId'
        ));
    }

    // ================================================================
    // SHOW — Detail 12 bulan per masjid
    // ================================================================

    public function show($masjidId)
    {
        $masjid = Masjid::findOrFail($masjidId);

        // 12 bulan terakhir
        $end   = Carbon::now();
        $start = Carbon::now()->subMonths(11)->startOfMonth();

        // Penerimaan per bulan
        $penerimaanBulanan = DB::table('transaksi_penerimaan')
            ->select(
                DB::raw('YEAR(tanggal_transaksi) as tahun'),
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(jumlah) as total_penerimaan'),
                DB::raw('COUNT(DISTINCT muzakki_nama) as jumlah_muzakki')
            )
            ->where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$start, $end])
            ->groupBy(DB::raw('YEAR(tanggal_transaksi)'), DB::raw('MONTH(tanggal_transaksi)'))
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . $r->bulan);

        // Penyaluran per bulan
        $penyaluranBulanan = DB::table('transaksi_penyaluran')
            ->select(
                DB::raw('YEAR(tanggal_penyaluran) as tahun'),
                DB::raw('MONTH(tanggal_penyaluran) as bulan'),
                DB::raw('SUM(CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END) as total_penyaluran'),
                DB::raw('COUNT(DISTINCT mustahik_id) as jumlah_mustahik')
            )
            ->where('masjid_id', $masjidId)
            ->where('status', 'disalurkan')
            ->whereNull('deleted_at')
            ->whereBetween('tanggal_penyaluran', [$start, $end])
            ->groupBy(DB::raw('YEAR(tanggal_penyaluran)'), DB::raw('MONTH(tanggal_penyaluran)'))
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . $r->bulan);

        // Setor kas per bulan
        $setorKasBulanan = DB::table('setor_kas')
            ->select(
                DB::raw('YEAR(tanggal_setor) as tahun'),
                DB::raw('MONTH(tanggal_setor) as bulan'),
                DB::raw('SUM(jumlah_disetor) as total_setor_kas')
            )
            ->where('masjid_id', $masjidId)
            ->where('status', 'diterima')
            ->whereBetween('tanggal_setor', [$start, $end])
            ->groupBy(DB::raw('YEAR(tanggal_setor)'), DB::raw('MONTH(tanggal_setor)'))
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . $r->bulan);

        // Susun 12 bulan
        $laporanBulanan = collect();
        $current = $start->copy();
        $saldoBerjalan = 0;

        while ($current <= $end) {
            $key = $current->year . '-' . $current->month;

            $pen  = $penerimaanBulanan->get($key);
            $peny = $penyaluranBulanan->get($key);
            $setor = $setorKasBulanan->get($key);

            $totalPen   = $pen   ? (float) $pen->total_penerimaan  : 0;
            $totalPeny  = $peny  ? (float) $peny->total_penyaluran : 0;
            $totalSetor = $setor ? (float) $setor->total_setor_kas  : 0;

            $saldoBerjalan += $totalPen + $totalSetor - $totalPeny;

            $laporanBulanan->push((object) [
                'tahun'            => $current->year,
                'bulan'            => $current->month,
                'bulan_nama'       => $this->namaBulan($current->month),
                'periode'          => $this->namaBulan($current->month) . ' ' . $current->year,
                'total_penerimaan' => $totalPen,
                'total_penyaluran' => $totalPeny,
                'total_setor_kas'  => $totalSetor,
                'saldo_akhir'      => $saldoBerjalan,
                'jumlah_muzakki'   => $pen  ? (int) $pen->jumlah_muzakki   : 0,
                'jumlah_mustahik'  => $peny ? (int) $peny->jumlah_mustahik : 0,
            ]);

            $current->addMonth();
        }

        // Summary
        $totalPenerimaan = $laporanBulanan->sum('total_penerimaan');
        $totalPenyaluran = $laporanBulanan->sum('total_penyaluran');
        $totalSetorKas   = $laporanBulanan->sum('total_setor_kas');
        $saldoTerakhir   = $laporanBulanan->last()->saldo_akhir ?? 0;
        $totalMuzakki    = $laporanBulanan->sum('jumlah_muzakki');
        $totalMustahik   = $laporanBulanan->sum('jumlah_mustahik');

        // Chart data
        $chartLabels    = $laporanBulanan->pluck('periode');
        $chartPenerimaan = $laporanBulanan->pluck('total_penerimaan');
        $chartPenyaluran = $laporanBulanan->pluck('total_penyaluran');
        $chartSaldo     = $laporanBulanan->pluck('saldo_akhir');

        // Breakdown per jenis zakat (12 bln)
        $breakdownJenisZakat = DB::table('transaksi_penerimaan')
            ->join('jenis_zakat', 'transaksi_penerimaan.jenis_zakat_id', '=', 'jenis_zakat.id')
            ->select(
                'jenis_zakat.nama as nama_zakat',
                DB::raw('SUM(transaksi_penerimaan.jumlah) as total'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->where('transaksi_penerimaan.masjid_id', $masjidId)
            ->where('transaksi_penerimaan.status', 'verified')
            ->whereBetween('transaksi_penerimaan.tanggal_transaksi', [$start, $end])
            ->groupBy('jenis_zakat.id', 'jenis_zakat.nama')
            ->orderByDesc('total')
            ->get();

        // Breakdown per kategori mustahik (12 bln)
        $breakdownMustahik = DB::table('transaksi_penyaluran')
            ->join('kategori_mustahik', 'transaksi_penyaluran.kategori_mustahik_id', '=', 'kategori_mustahik.id')
            ->select(
                'kategori_mustahik.nama as nama_kategori',
                DB::raw('COUNT(DISTINCT transaksi_penyaluran.mustahik_id) as jumlah_penerima'),
                DB::raw('SUM(CASE WHEN transaksi_penyaluran.metode_penyaluran = "barang" THEN COALESCE(transaksi_penyaluran.nilai_barang, 0) ELSE COALESCE(transaksi_penyaluran.jumlah, 0) END) as total')
            )
            ->where('transaksi_penyaluran.masjid_id', $masjidId)
            ->where('transaksi_penyaluran.status', 'disalurkan')
            ->whereNull('transaksi_penyaluran.deleted_at')
            ->whereBetween('transaksi_penyaluran.tanggal_penyaluran', [$start, $end])
            ->groupBy('kategori_mustahik.id', 'kategori_mustahik.nama')
            ->orderByDesc('total')
            ->get();

        return view('superadmin.laporan-konsolidasi.show', compact(
            'masjid',
            'laporanBulanan',
            'totalPenerimaan',
            'totalPenyaluran',
            'totalSetorKas',
            'saldoTerakhir',
            'totalMuzakki',
            'totalMustahik',
            'chartLabels',
            'chartPenerimaan',
            'chartPenyaluran',
            'chartSaldo',
            'breakdownJenisZakat',
            'breakdownMustahik'
        ));
    }

    // ================================================================
    // HELPERS
    // ================================================================

    private function namaBulan(int $bulan): string
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3  => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6  => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ][$bulan] ?? '';
    }

    private function getAvailableYears(): array
    {
        $yearsPenerimaan = DB::table('transaksi_penerimaan')
            ->where('status', 'verified')
            ->selectRaw('DISTINCT YEAR(tanggal_transaksi) as tahun')
            ->pluck('tahun');

        $yearsPenyaluran = DB::table('transaksi_penyaluran')
            ->where('status', 'disalurkan')
            ->selectRaw('DISTINCT YEAR(tanggal_penyaluran) as tahun')
            ->pluck('tahun');

        $years = $yearsPenerimaan->merge($yearsPenyaluran)->unique()->sort()->values()->toArray();

        if (!in_array(date('Y'), $years)) {
            $years[] = (int) date('Y');
            sort($years);
        }

        return $years;
    }

    /**
     * Export laporan konsolidasi ke PDF/Excel
     */
    public function export(Request $request, $masjidId)
    {
        $masjid = Masjid::findOrFail($masjidId);
        $format = $request->get('format', 'pdf'); // pdf atau excel
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan');
        $type = $request->get('type', 'konsolidasi'); // konsolidasi, penerimaan, penyaluran

        // Ambil data laporan
        $data = $this->getLaporanData($masjidId, $tahun, $bulan, $type);

        // Data tambahan untuk view
        $data['masjid'] = $masjid;
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
        $data['bulan_nama'] = $bulan ? $this->namaBulan((int)$bulan) : 'Semua Bulan';
        $data['tanggalExport'] = Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i:s');
        $data['user'] = Auth::user();
        $data['filters'] = $request->all();

        // Export berdasarkan format
        if ($format === 'excel') {
            return Excel::download(
                new LaporanKonsolidasiExport($data),
                $this->generateFilename($masjid, $tahun, $bulan, 'xlsx')
            );
        }

        // Export PDF
        $pdf = Pdf::loadView('superadmin.laporan-konsolidasi.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'Helvetica',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->download($this->generateFilename($masjid, $tahun, $bulan, 'pdf'));
    }

    /**
     * Generate filename untuk export
     */
    private function generateFilename($masjid, $tahun, $bulan, $extension)
    {
        $filename = 'laporan-konsolidasi-' . str_replace(' ', '-', $masjid->nama);
        $filename .= $bulan ? '-' . $this->namaBulan((int)$bulan) . '-' . $tahun : '-12-bulan-terakhir';
        $filename .= '.' . $extension;

        // Sanitasi filename
        $filename = preg_replace('/[^A-Za-z0-9\-\.]/', '', $filename);

        return $filename;
    }

    /**
     * Ambil data laporan untuk export
     * DIPERBAIKI: Mengganti program_penyaluran menjadi program_zakat
     */
    private function getLaporanData($masjidId, $tahun, $bulan = null, $type = 'konsolidasi')
    {
        if ($bulan) {
            // Export per bulan tertentu
            $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $end = Carbon::create($tahun, $bulan, 1)->endOfMonth();
        } else {
            // Export 12 bulan terakhir
            $end = Carbon::now();
            $start = Carbon::now()->subMonths(11)->startOfMonth();
        }

        $data = [
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'periode_text' => $this->getPeriodeText($start, $end, $bulan, $tahun),
        ];

        if ($type == 'penerimaan' || $type == 'konsolidasi') {
            // Data Penerimaan Detail
            $data['penerimaan_detail'] = DB::table('transaksi_penerimaan')
                ->join('jenis_zakat', 'transaksi_penerimaan.jenis_zakat_id', '=', 'jenis_zakat.id')
                ->leftJoin('program_zakat', 'transaksi_penerimaan.program_zakat_id', '=', 'program_zakat.id')
                ->leftJoin('amil', 'transaksi_penerimaan.amil_id', '=', 'amil.id')
                ->leftJoin('pengguna', 'amil.pengguna_id', '=', 'pengguna.id')
                ->select(
                    'transaksi_penerimaan.*',
                    'jenis_zakat.nama as jenis_zakat_nama',
                    'program_zakat.nama_program as program_zakat_nama',
                    DB::raw('COALESCE(pengguna.username, amil.nama_lengkap) as amil_nama')
                )
                ->where('transaksi_penerimaan.masjid_id', $masjidId)
                ->where('transaksi_penerimaan.status', 'verified')
                ->whereBetween('transaksi_penerimaan.tanggal_transaksi', [$start, $end])
                ->orderBy('transaksi_penerimaan.tanggal_transaksi', 'desc')
                ->get();

            $data['total_penerimaan'] = $data['penerimaan_detail']->sum('jumlah');
            $data['total_penerimaan_transaksi'] = $data['penerimaan_detail']->count();
        }

        if ($type == 'penyaluran' || $type == 'konsolidasi') {
            // Data Penyaluran Detail
            // DIPERBAIKI: Mengganti program_penyaluran menjadi program_zakat
            // Data Penyaluran Detail
            $data['penyaluran_detail'] = DB::table('transaksi_penyaluran')
                ->join('kategori_mustahik', 'transaksi_penyaluran.kategori_mustahik_id', '=', 'kategori_mustahik.id')
                ->leftJoin('program_zakat', 'transaksi_penyaluran.program_zakat_id', '=', 'program_zakat.id')
                ->leftJoin('amil', 'transaksi_penyaluran.amil_id', '=', 'amil.id')
                ->leftJoin('pengguna', 'amil.pengguna_id', '=', 'pengguna.id')
                ->leftJoin('mustahik', 'transaksi_penyaluran.mustahik_id', '=', 'mustahik.id') // JOIN tabel mustahik
                ->select(
                    'transaksi_penyaluran.*',
                    'kategori_mustahik.nama as kategori_mustahik_nama',
                    'program_zakat.nama_program as program_penyaluran_nama',
                    DB::raw('COALESCE(pengguna.username, amil.nama_lengkap) as amil_nama'),
                    'mustahik.nama_lengkap as nama_mustahik' // Ambil nama dari tabel mustahik
                )
                ->where('transaksi_penyaluran.masjid_id', $masjidId)
                ->where('transaksi_penyaluran.status', 'disalurkan')
                ->whereNull('transaksi_penyaluran.deleted_at')
                ->whereBetween('transaksi_penyaluran.tanggal_penyaluran', [$start, $end])
                ->orderBy('transaksi_penyaluran.tanggal_penyaluran', 'desc')
                ->get();

            $data['total_penyaluran'] = $data['penyaluran_detail']->sum(function ($item) {
                return $item->metode_penyaluran == 'barang' ?
                    ($item->nilai_barang ?? 0) : ($item->jumlah ?? 0);
            });
            $data['total_penyaluran_transaksi'] = $data['penyaluran_detail']->count();
        }

        if ($type == 'konsolidasi') {
            // Data Ringkasan Bulanan
            $data['ringkasan_bulanan'] = $this->getRingkasanBulanan($masjidId, $start, $end);

            // Breakdown per jenis zakat
            $data['breakdown_jenis_zakat'] = DB::table('transaksi_penerimaan')
                ->join('jenis_zakat', 'transaksi_penerimaan.jenis_zakat_id', '=', 'jenis_zakat.id')
                ->select(
                    'jenis_zakat.nama as nama_zakat',
                    DB::raw('COUNT(*) as jumlah_transaksi'),
                    DB::raw('SUM(jumlah) as total_nominal'),
                    DB::raw('COUNT(DISTINCT muzakki_nama) as jumlah_muzakki')
                )
                ->where('transaksi_penerimaan.masjid_id', $masjidId)
                ->where('transaksi_penerimaan.status', 'verified')
                ->whereBetween('transaksi_penerimaan.tanggal_transaksi', [$start, $end])
                ->groupBy('jenis_zakat.id', 'jenis_zakat.nama')
                ->orderByDesc('total_nominal')
                ->get();

            // Breakdown per kategori mustahik
            $data['breakdown_kategori_mustahik'] = DB::table('transaksi_penyaluran')
                ->join('kategori_mustahik', 'transaksi_penyaluran.kategori_mustahik_id', '=', 'kategori_mustahik.id')
                ->select(
                    'kategori_mustahik.nama as nama_kategori',
                    DB::raw('COUNT(*) as jumlah_transaksi'),
                    DB::raw('COUNT(DISTINCT mustahik_id) as jumlah_mustahik'),
                    DB::raw('SUM(CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END) as total_nominal')
                )
                ->where('transaksi_penyaluran.masjid_id', $masjidId)
                ->where('transaksi_penyaluran.status', 'disalurkan')
                ->whereNull('transaksi_penyaluran.deleted_at')
                ->whereBetween('transaksi_penyaluran.tanggal_penyaluran', [$start, $end])
                ->groupBy('kategori_mustahik.id', 'kategori_mustahik.nama')
                ->orderByDesc('total_nominal')
                ->get();
        }

        $data['type'] = $type;
        $data['jenisZakatList'] = JenisZakat::all();

        return $data;
    }

    /**
     * Get ringkasan bulanan
     */
    private function getRingkasanBulanan($masjidId, $start, $end)
    {
        $penerimaanBulanan = DB::table('transaksi_penerimaan')
            ->select(
                DB::raw('YEAR(tanggal_transaksi) as tahun'),
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(jumlah) as total_penerimaan'),
                DB::raw('COUNT(*) as jumlah_transaksi_penerimaan'),
                DB::raw('COUNT(DISTINCT muzakki_nama) as jumlah_muzakki')
            )
            ->where('masjid_id', $masjidId)
            ->where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [$start, $end])
            ->groupBy(DB::raw('YEAR(tanggal_transaksi)'), DB::raw('MONTH(tanggal_transaksi)'))
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . $r->bulan);

        $penyaluranBulanan = DB::table('transaksi_penyaluran')
            ->select(
                DB::raw('YEAR(tanggal_penyaluran) as tahun'),
                DB::raw('MONTH(tanggal_penyaluran) as bulan'),
                DB::raw('SUM(CASE WHEN metode_penyaluran = "barang" THEN COALESCE(nilai_barang, 0) ELSE COALESCE(jumlah, 0) END) as total_penyaluran'),
                DB::raw('COUNT(*) as jumlah_transaksi_penyaluran'),
                DB::raw('COUNT(DISTINCT mustahik_id) as jumlah_mustahik')
            )
            ->where('masjid_id', $masjidId)
            ->where('status', 'disalurkan')
            ->whereNull('deleted_at')
            ->whereBetween('tanggal_penyaluran', [$start, $end])
            ->groupBy(DB::raw('YEAR(tanggal_penyaluran)'), DB::raw('MONTH(tanggal_penyaluran)'))
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . $r->bulan);

        $ringkasan = [];
        $current = $start->copy();

        while ($current <= $end) {
            $key = $current->year . '-' . $current->month;

            $pen = $penerimaanBulanan->get($key);
            $peny = $penyaluranBulanan->get($key);

            $ringkasan[] = [
                'tahun' => $current->year,
                'bulan' => $current->month,
                'bulan_nama' => $this->namaBulan($current->month),
                'periode' => $this->namaBulan($current->month) . ' ' . $current->year,
                'total_penerimaan' => $pen ? (float) $pen->total_penerimaan : 0,
                'total_penyaluran' => $peny ? (float) $peny->total_penyaluran : 0,
                'jumlah_transaksi_penerimaan' => $pen ? (int) $pen->jumlah_transaksi_penerimaan : 0,
                'jumlah_transaksi_penyaluran' => $peny ? (int) $peny->jumlah_transaksi_penyaluran : 0,
                'jumlah_muzakki' => $pen ? (int) $pen->jumlah_muzakki : 0,
                'jumlah_mustahik' => $peny ? (int) $peny->jumlah_mustahik : 0,
            ];

            $current->addMonth();
        }

        return $ringkasan;
    }

    /**
     * Get teks periode
     */
    private function getPeriodeText($start, $end, $bulan, $tahun)
    {
        if ($bulan) {
            return $this->namaBulan((int)$bulan) . ' ' . $tahun;
        }

        return $start->locale('id')->translatedFormat('d F Y') . ' - ' .
            $end->locale('id')->translatedFormat('d F Y');
    }
}