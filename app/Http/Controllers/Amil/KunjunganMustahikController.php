<?php
// app/Http/Controllers/Amil/KunjunganMustahikController.php

namespace App\Http\Controllers\Amil;

use App\Http\Controllers\Controller;
use App\Models\KunjunganMustahik;
use App\Models\Mustahik;
use App\Models\Amil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KunjunganMustahikController extends Controller
{
    /**
     * Ambil data amil yang sedang login
     */
    private function getAmil(): Amil
    {
        return Amil::where('pengguna_id', Auth::id())->firstOrFail();
    }

    // ── 17.1  Kalender ───────────────────────────────────────────────────
    public function index(Request $request)
    {
        $amil = $this->getAmil();

        // Data untuk filter di view
        $tujuanOptions = [
            'verifikasi' => 'Verifikasi',
            'penyaluran' => 'Penyaluran',
            'monitoring' => 'Monitoring',
            'lainnya'    => 'Lainnya',
        ];
        $statusOptions = [
            'direncanakan' => 'Direncanakan',
            'selesai'      => 'Selesai',
            'dibatalkan'   => 'Dibatalkan',
        ];

        // Statistik ringkasan bulan ini
        $bulanIni = now()->format('Y-m');
        $stats = [
            'total'        => KunjunganMustahik::byAmil($amil->id)->whereRaw("DATE_FORMAT(tanggal_kunjungan,'%Y-%m') = ?", [$bulanIni])->count(),
            'direncanakan' => KunjunganMustahik::byAmil($amil->id)->direncanakan()->whereRaw("DATE_FORMAT(tanggal_kunjungan,'%Y-%m') = ?", [$bulanIni])->count(),
            'selesai'      => KunjunganMustahik::byAmil($amil->id)->selesai()->whereRaw("DATE_FORMAT(tanggal_kunjungan,'%Y-%m') = ?", [$bulanIni])->count(),
        ];

        return view('amil.kunjungan.calendar', compact('amil', 'tujuanOptions', 'statusOptions', 'stats'));
    }

    /**
     * API: events untuk FullCalendar (JSON)
     */
    public function events(Request $request)
    {
        $amil = $this->getAmil();

        $query = KunjunganMustahik::byAmil($amil->id)
            ->with('mustahik:id,nama_lengkap')
            ->whereBetween('tanggal_kunjungan', [
                $request->input('start', now()->startOfMonth()->toDateString()),
                $request->input('end',   now()->endOfMonth()->toDateString()),
            ]);

        if ($request->filled('tujuan')) {
            $query->byTujuan($request->tujuan);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $events = $query->get()->map(fn($k) => [
            'id'              => $k->uuid,
            'title'           => ($k->mustahik->nama_lengkap ?? 'Mustahik') . ' — ' . $k->tujuan_label,
            'start'           => $k->tanggal_kunjungan->toDateString() . ($k->waktu_mulai ? 'T' . $k->waktu_mulai : ''),
            'end'             => $k->waktu_selesai ? $k->tanggal_kunjungan->toDateString() . 'T' . $k->waktu_selesai : null,
            'backgroundColor' => $k->status_color,
            'borderColor'     => $k->status_color,
            'textColor'       => '#ffffff',
            'extendedProps'   => [
                'status'  => $k->status,
                'tujuan'  => $k->tujuan,
                'url'     => route('amil.kunjungan.show', $k->uuid),
            ],
        ]);

        return response()->json($events);
    }

    /**
     * API: list kunjungan untuk toggle list view
     */
    public function listData(Request $request)
    {
        $amil  = $this->getAmil();
        $query = KunjunganMustahik::byAmil($amil->id)
            ->with('mustahik:id,nama_lengkap,alamat')
            ->orderBy('tanggal_kunjungan', 'desc');

        if ($request->filled('tujuan')) $query->byTujuan($request->tujuan);
        if ($request->filled('status')) $query->byStatus($request->status);
        if ($request->filled('bulan'))  $query->whereRaw("DATE_FORMAT(tanggal_kunjungan,'%Y-%m') = ?", [$request->bulan]);

        $kunjungan = $query->paginate(15);
        return response()->json($kunjungan);
    }

    // ── 17.2  Create ─────────────────────────────────────────────────────
    public function create()
    {
        $amil      = $this->getAmil();
        $mustahiks = Mustahik::byMasjid($amil->masjid_id)
            ->active()
            ->byStatus('verified')
            ->orderBy('nama_lengkap')
            ->select('id', 'nama_lengkap', 'alamat', 'no_registrasi', 'telepon')
            ->get();

        return view('amil.kunjungan.create', compact('amil', 'mustahiks'));
    }

    public function store(Request $request)
    {
        $amil = $this->getAmil();

        $validated = $request->validate([
            'mustahik_id'      => ['required', 'exists:mustahik,id'],
            'tanggal_kunjungan'=> ['required', 'date'],
            'waktu_mulai'      => ['nullable', 'date_format:H:i'],
            'waktu_selesai'    => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'tujuan'           => ['required', Rule::in(['verifikasi', 'penyaluran', 'monitoring', 'lainnya'])],
            'catatan'          => ['nullable', 'string', 'max:2000'],
            'langsung_selesai' => ['nullable', 'boolean'],
        ]);

        $kunjungan = KunjunganMustahik::create([
            'amil_id'          => $amil->id,
            'mustahik_id'      => $validated['mustahik_id'],
            'tanggal_kunjungan'=> $validated['tanggal_kunjungan'],
            'waktu_mulai'      => $validated['waktu_mulai'] ?? null,
            'waktu_selesai'    => $validated['waktu_selesai'] ?? null,
            'tujuan'           => $validated['tujuan'],
            'catatan'          => $validated['catatan'] ?? null,
            'status'           => 'direncanakan',
        ]);

        if ($request->boolean('langsung_selesai')) {
            return redirect()->route('amil.kunjungan.finish', $kunjungan->uuid)
                ->with('success', 'Jadwal dibuat. Isi hasil kunjungan di bawah.');
        }

        return redirect()->route('amil.kunjungan.show', $kunjungan->uuid)
            ->with('success', 'Jadwal kunjungan berhasil disimpan.');
    }

    // ── 17.3  Show ───────────────────────────────────────────────────────
    public function show(string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->with('mustahik')
            ->firstOrFail();

        return view('amil.kunjungan.show', compact('kunjungan'));
    }

    // ── Edit ─────────────────────────────────────────────────────────────
    public function edit(string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->where('status', 'direncanakan')
            ->with('mustahik')
            ->firstOrFail();

        $mustahiks = Mustahik::byMasjid($amil->masjid_id)
            ->active()
            ->byStatus('verified')
            ->orderBy('nama_lengkap')
            ->select('id', 'nama_lengkap', 'alamat', 'no_registrasi', 'telepon')
            ->get();

        return view('amil.kunjungan.edit', compact('kunjungan', 'mustahiks'));
    }

    public function update(Request $request, string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->where('status', 'direncanakan')
            ->firstOrFail();

        $validated = $request->validate([
            'mustahik_id'       => ['required', 'exists:mustahik,id'],
            'tanggal_kunjungan' => ['required', 'date'],
            'waktu_mulai'       => ['nullable', 'date_format:H:i'],
            'waktu_selesai'     => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'tujuan'            => ['required', Rule::in(['verifikasi', 'penyaluran', 'monitoring', 'lainnya'])],
            'catatan'           => ['nullable', 'string', 'max:2000'],
        ]);

        $kunjungan->update($validated);

        return redirect()->route('amil.kunjungan.show', $kunjungan->uuid)
            ->with('success', 'Jadwal kunjungan berhasil diperbarui.');
    }

    // ── Cancel ───────────────────────────────────────────────────────────
    public function cancel(string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->where('status', 'direncanakan')
            ->firstOrFail();

        $kunjungan->batalkan();

        return back()->with('success', 'Kunjungan berhasil dibatalkan.');
    }

    // ── 17.4  Finish (show form) ─────────────────────────────────────────
    public function finish(string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->whereIn('status', ['direncanakan', 'selesai']) // boleh edit hasil jika sudah selesai
            ->with('mustahik')
            ->firstOrFail();

        return view('amil.kunjungan.finish', compact('kunjungan'));
    }

    /**
     * Proses simpan hasil kunjungan
     */
    public function complete(Request $request, string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->whereIn('status', ['direncanakan', 'selesai'])
            ->with('mustahik')
            ->firstOrFail();

        $validated = $request->validate([
            'waktu_selesai'       => ['nullable', 'date_format:H:i'],
            'hasil_kunjungan'     => ['required', 'string', 'min:10'],
            'foto_dokumentasi'    => ['nullable', 'array', 'max:8'],
            'foto_dokumentasi.*'  => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'nonaktifkan_mustahik'=> ['nullable', 'boolean'],
        ]);

        // Upload foto
        $existingFotos = $kunjungan->foto_dokumentasi ?? [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $path = $foto->store("kunjungan/{$kunjungan->id}", 'public');
                $existingFotos[] = $path;
            }
        }

        $kunjungan->tandaiSelesai([
            'hasil_kunjungan'  => $validated['hasil_kunjungan'],
            'foto_dokumentasi' => $existingFotos ?: null,
            'waktu_selesai'    => $validated['waktu_selesai'] ?? now()->format('H:i:s'),
        ]);

        // Nonaktifkan mustahik jika diminta
        if ($request->boolean('nonaktifkan_mustahik')) {
            $kunjungan->mustahik->deactivate();
        }

        return redirect()->route('amil.kunjungan.show', $kunjungan->uuid)
            ->with('success', 'Hasil kunjungan berhasil disimpan.');
    }

    // ── Hapus foto dokumentasi satu per satu ────────────────────────────
    public function hapusFoto(Request $request, string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $index = $request->input('index');
        $kunjungan->removeFotoDokumentasiByIndex((int) $index);

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    // ── Destroy ──────────────────────────────────────────────────────────
    public function destroy(string $uuid)
    {
        $amil      = $this->getAmil();
        $kunjungan = KunjunganMustahik::byAmil($amil->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $kunjungan->clearFotoDokumentasi();
        $kunjungan->delete();

        return redirect()->route('amil.kunjungan.index')
            ->with('success', 'Kunjungan berhasil dihapus.');
    }

    // ── API Autocomplete mustahik ────────────────────────────────────────
    public function searchMustahik(Request $request)
    {
        $amil = $this->getAmil();
        $mustahiks = Mustahik::byMasjid($amil->masjid_id)
            ->active()
            ->byStatus('verified')
            ->search($request->input('q', ''))
            ->select('id', 'nama_lengkap', 'alamat', 'no_registrasi', 'telepon')
            ->limit(10)
            ->get();

        return response()->json($mustahiks);
    }
}