{{-- resources/views/partials/navbar.blade.php --}}

@php
    $authUser = auth()->user();
    $isSuperadmin = $authUser?->isSuperadmin();
    $isAdminLembaga = $authUser?->isAdminLembaga();
    $isAmil = $authUser?->isAmil();
    $isMuzakki = $authUser?->isMuzakki();

    // ════════════════════════════════════════════════════════
    // SUPERADMIN — Jumlah pesan masuk belum dibaca
    // ════════════════════════════════════════════════════════
    $kontakBelumDibaca = 0;
    if ($isSuperadmin) {
        $kontakBelumDibaca = \App\Models\Kontak::whereNull('dibaca_at')->count();
    }

    // ════════════════════════════════════════════════════════
    // NOTIFIKASI DINAMIS BERDASARKAN PERAN
    // ════════════════════════════════════════════════════════
    $notifItems = collect();
    $notifUnread = 0;

    $notifSessionKey = 'notif_read_ids_' . $authUser->id;
    $readIds = session($notifSessionKey, []);

    if ($isAmil && isset($authUser->amil)) {
        $lembagaId = $authUser->amil->lembaga_id;

        $daringPending = \App\Models\TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('metode_penerimaan', 'daring')
            ->where('status', 'pending')
            ->where('konfirmasi_status', 'menunggu_konfirmasi')
            ->with('jenisZakat')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(
                fn($t) => [
                    'icon' => 'transfer',
                    'color' => 'emerald',
                    'title' => 'Transaksi Daring Baru',
                    'body' =>
                        $t->muzakki_nama .
                        ' — ' .
                        ($t->jenisZakat->nama ?? 'Zakat') .
                        ' Rp ' .
                        number_format($t->jumlah, 0, ',', '.'),
                    'time' => $t->created_at->diffForHumans(),
                    'timestamp' => $t->created_at->timestamp,
                    'url' => route('transaksi-daring.show', $t->uuid),
                    'id' => $t->uuid,
                ],
            );

        $amilId = $authUser->amil->id;

        $dijemputPending = \App\Models\TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('metode_penerimaan', 'dijemput')
            ->where('status', 'pending')
            ->where('status_penjemputan', 'menunggu')
            ->where(function ($q) use ($amilId) {
                $q->where('amil_id', $amilId)->orWhereNull('amil_id');
            })
            ->with('jenisZakat')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(
                fn($t) => [
                    'icon' => 'pickup',
                    'color' => 'amber',
                    'title' => $t->amil_id === $amilId ? 'Request Penjemputan (Anda Dipilih)' : 'Request Penjemputan',
                    'body' =>
                        $t->muzakki_nama .
                        ' — Menunggu dijemput di ' .
                        \Str::limit($t->muzakki_alamat ?? 'lokasi terdaftar', 35),
                    'time' => $t->created_at->diffForHumans(),
                    'timestamp' => $t->created_at->timestamp,
                    'url' => route('transaksi-dijemput.show', $t->uuid),
                    'id' => $t->uuid,
                ],
            );

        $notifItems = collect(array_merge($daringPending->values()->toArray(), $dijemputPending->values()->toArray()))
            ->filter(fn($n) => !in_array($n['id'], $readIds))
            ->sortByDesc('timestamp')
            ->values();
        $notifUnread = $notifItems->count();
    }

    if ($isMuzakki && isset($authUser->muzakki)) {
        $muzakkiId = $authUser->muzakki->id;

        $allMuzakkiNotif = \App\Models\TransaksiPenerimaan::where('muzakki_id', $muzakkiId)
            ->whereIn('status', ['verified', 'rejected', 'dijemput'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->with('jenisZakat')
            ->latest('updated_at')
            ->take(10)
            ->get()
            ->map(function ($t) {
                $statusMap = [
                    'verified' => [
                        'icon' => 'check',
                        'color' => 'emerald',
                        'title' => 'Transaksi Dikonfirmasi',
                        'body' => 'Transaksi ' . $t->no_transaksi . ' telah diverifikasi oleh amil.',
                    ],
                    'rejected' => [
                        'icon' => 'x',
                        'color' => 'red',
                        'title' => 'Transaksi Ditolak',
                        'body' => 'Transaksi ' . $t->no_transaksi . ' ditolak. Silakan hubungi amil.',
                    ],
                    'dijemput' => [
                        'icon' => 'truck',
                        'color' => 'blue',
                        'title' => 'Amil Dalam Perjalanan',
                        'body' => 'Amil sedang menuju lokasi untuk ' . $t->no_transaksi . '.',
                    ],
                ];
                $info = $statusMap[$t->status] ?? [
                    'icon' => 'info',
                    'color' => 'gray',
                    'title' => 'Update Transaksi',
                    'body' => 'Ada pembaruan pada transaksi ' . $t->no_transaksi,
                ];
                return array_merge($info, [
                    'time' => $t->updated_at->diffForHumans(),
                    'timestamp' => $t->updated_at->timestamp,
                    'url' => route('transaksi-daring-muzakki.show', $t->uuid),
                    'id' => $t->uuid,
                ]);
            });

        $notifItems = collect($allMuzakkiNotif->values()->toArray())
            ->filter(fn($n) => !in_array($n['id'], $readIds))
            ->sortByDesc('timestamp')
            ->values();
        $notifUnread = $notifItems->count();
    }

    $roleLabel = match (true) {
        $isSuperadmin => 'Super Admin',
        $isAdminLembaga => 'Admin Lembaga',
        $isAmil => 'Amil',
        $isMuzakki => 'Muzakki',
        default => 'Pengguna',
    };
    $badgeClass = match (true) {
        $isSuperadmin => 'bg-indigo-100 text-indigo-700',
        $isAdminLembaga => 'bg-emerald-100 text-emerald-700',
        $isAmil => 'bg-amber-100 text-amber-700',
        $isMuzakki => 'bg-sky-100 text-sky-700',
        default => 'bg-gray-100 text-gray-700',
    };

    $avatarUrl = null;
    if ($isAdminLembaga && $authUser?->lembaga?->admin_foto) {
        $avatarUrl = $authUser->lembaga->admin_foto_url;
    } elseif ($isAmil && isset($authUser->amil) && $authUser->amil->foto) {
        if (\Storage::disk('public')->exists($authUser->amil->foto)) {
            $avatarUrl = \Storage::url($authUser->amil->foto);
        }
    } elseif ($isMuzakki && isset($authUser->muzakki) && $authUser->muzakki->foto) {
        if (\Storage::disk('public')->exists($authUser->muzakki->foto)) {
            $avatarUrl = \Storage::url($authUser->muzakki->foto);
        }
    } elseif (isset($authUser->foto) && $authUser->foto && \Storage::disk('public')->exists($authUser->foto)) {
        $avatarUrl = \Storage::url($authUser->foto);
    }
@endphp

{{-- ═══════════════════════════════════════════════════════════════
     LOGOUT CONFIRMATION MODAL
     ═══════════════════════════════════════════════════════════════ --}}
<div id="logout-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden" aria-modal="true"
    role="dialog">
    <div id="logout-backdrop"
        class="absolute inset-0 bg-black/50 backdrop-blur-md opacity-0 transition-opacity duration-300"
        onclick="closeLogoutModal()"></div>

    <div id="logout-card"
        class="relative bg-white rounded-3xl w-full max-w-sm mx-4 overflow-hidden scale-90 opacity-0 transition-all duration-300"
        style="box-shadow: 0 8px 40px rgba(0,0,0,0.10), 0 2px 12px rgba(0,0,0,0.05);">

        <div class="px-8 pt-8 pb-7">
            {{-- Ikon --}}
            <div class="flex items-center justify-center mb-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: #fff0f0;">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
            </div>

            <h2 class="text-[18px] font-bold text-gray-900 text-center mb-1.5" style="letter-spacing: -0.025em;">
                Keluar dari Akun?
            </h2>
            <p class="text-[13px] text-gray-400 text-center leading-relaxed mb-5">
                Sesi Anda akan diakhiri. Pastikan semua<br>pekerjaan sudah tersimpan sebelum keluar.
            </p>

            {{-- User info card --}}
            <div class="flex items-center space-x-3 px-2 py-2 mb-6">
                <div class="w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center"
                    style="background: #2d6a2d;">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $authUser->username }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-white text-[13px] font-bold">
                            {{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-semibold text-gray-800 truncate leading-tight">
                        {{ $isAdminLembaga && $authUser?->lembaga?->admin_nama ? $authUser->lembaga->admin_nama : $authUser->username ?? 'Pengguna' }}
                    </p>
                    <p class="text-[11.5px] text-gray-400 truncate mt-0.5">{{ $authUser->email ?? '' }}</p>
                </div>
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold flex-shrink-0 {{ $badgeClass }}">
                    {{ $roleLabel }}
                </span>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-2.5">
                <button onclick="closeLogoutModal()"
                    class="flex-1 px-4 py-2.5 rounded-2xl text-[13px] font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 active:scale-95 transition-all duration-200">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center space-x-1.5 px-4 py-2.5 rounded-2xl text-[13px] font-semibold text-white active:scale-95 transition-all duration-200"
                        style="background: #ef4444; box-shadow: 0 4px 14px rgba(239,68,68,0.35);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Ya, Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     NAVBAR UTAMA
     ═══════════════════════════════════════════════════════════════ --}}
<header class="sticky top-0 z-40" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);">
    <div class="px-5 sm:px-7 lg:px-10">
        <div class="flex items-center justify-between" style="height: 64px;">

{{-- Left: Page Title --}}
@php
    $routePageMap = [
        // Dashboard
        'dashboard'                               => ['title' => 'Dashboard',                    'subtitle' => 'Ringkasan aktivitas'],

        // Superadmin - Data Master
        'jenis-zakat.index'                       => ['title' => 'Jenis Zakat',                  'subtitle' => 'Kelola jenis zakat'],
        'jenis-zakat.create'                      => ['title' => 'Tambah Jenis Zakat',           'subtitle' => 'Jenis Zakat'],
        'jenis-zakat.edit'                        => ['title' => 'Edit Jenis Zakat',             'subtitle' => 'Jenis Zakat'],
        'tipe-zakat.index'                        => ['title' => 'Tipe Zakat',                   'subtitle' => 'Kelola tipe zakat'],
        'tipe-zakat.create'                       => ['title' => 'Tambah Tipe Zakat',            'subtitle' => 'Tipe Zakat'],
        'tipe-zakat.edit'                         => ['title' => 'Edit Tipe Zakat',              'subtitle' => 'Tipe Zakat'],
        'kategori-mustahik.index'                 => ['title' => 'Kategori Mustahik',            'subtitle' => 'Kelola kategori mustahik'],
        'kategori-mustahik.create'                => ['title' => 'Tambah Kategori Mustahik',     'subtitle' => 'Kategori Mustahik'],
        'kategori-mustahik.edit'                  => ['title' => 'Edit Kategori Mustahik',       'subtitle' => 'Kategori Mustahik'],
        'harga-emas-perak.index'                  => ['title' => 'Harga Emas & Perak',           'subtitle' => 'Kelola harga emas dan perak'],
        'harga-emas-perak.create'                 => ['title' => 'Tambah Harga Emas & Perak',    'subtitle' => 'Harga Emas & Perak'],
        'harga-emas-perak.edit'                   => ['title' => 'Edit Harga Emas & Perak',      'subtitle' => 'Harga Emas & Perak'],

        // Superadmin - Pengguna
        'pengguna.index'                          => ['title' => 'Pengguna',                     'subtitle' => 'Kelola data pengguna'],
        'pengguna.create'                         => ['title' => 'Tambah Pengguna',              'subtitle' => 'Pengguna'],
        'pengguna.edit'                           => ['title' => 'Edit Pengguna',                'subtitle' => 'Pengguna'],

        // Superadmin - Bulletin
        'superadmin.kategori-bulletin.index'      => ['title' => 'Kategori Bulletin',            'subtitle' => 'Kelola kategori bulletin'],
        'superadmin.bulletin.index'               => ['title' => 'Kelola Bulletin',              'subtitle' => 'Manajemen artikel bulletin'],
        'superadmin.bulletin.create'              => ['title' => 'Tambah Bulletin',              'subtitle' => 'Bulletin'],
        'superadmin.bulletin.edit'                => ['title' => 'Edit Bulletin',                'subtitle' => 'Bulletin'],
        'superadmin.bulletin.show'                => ['title' => 'Detail Bulletin',              'subtitle' => 'Bulletin'],

        // Superadmin - Lembaga
        'lembaga.index'                           => ['title' => 'Lembaga',                      'subtitle' => 'Kelola data lembaga'],
        'lembaga.create'                          => ['title' => 'Tambah Lembaga',               'subtitle' => 'Lembaga'],
        'lembaga.edit'                            => ['title' => 'Edit Lembaga',                 'subtitle' => 'Lembaga'],
        'lembaga.show'                            => ['title' => 'Detail Lembaga',               'subtitle' => 'Lembaga'],

        // Superadmin - Amil
        'superadmin.amil.index'                   => ['title' => 'Data Amil',                    'subtitle' => 'Kelola data amil'],
        'superadmin.amil.show'                    => ['title' => 'Detail Amil',                  'subtitle' => 'Data Amil'],

        // Superadmin - Mustahik
        'superadmin.mustahik.index'               => ['title' => 'Data Mustahik',                'subtitle' => 'Kelola data mustahik'],
        'superadmin.mustahik.show'                => ['title' => 'Detail Mustahik',              'subtitle' => 'Data Mustahik'],

        // Superadmin - Muzaki
        'muzaki.index'                            => ['title' => 'Data Muzaki',                  'subtitle' => 'Kelola data muzaki'],
        'muzaki.show'                             => ['title' => 'Detail Muzaki',                'subtitle' => 'Data Muzaki'],

        // Superadmin - Transaksi
        'superadmin.transaksi-penerimaan.index'   => ['title' => 'Transaksi Penerimaan',         'subtitle' => 'Data seluruh transaksi penerimaan'],
        'superadmin.transaksi-penerimaan.show'    => ['title' => 'Detail Transaksi Penerimaan',  'subtitle' => 'Transaksi Penerimaan'],
        'superadmin.transaksi-penyaluran.index'   => ['title' => 'Transaksi Penyaluran',         'subtitle' => 'Data seluruh transaksi penyaluran'],
        'superadmin.transaksi-penyaluran.show'    => ['title' => 'Detail Transaksi Penyaluran',  'subtitle' => 'Transaksi Penyaluran'],

        // Superadmin - Lainnya
        'superadmin.testimoni.index'              => ['title' => 'Kelola Testimoni',             'subtitle' => 'Moderasi testimoni muzakki'],
        'laporan-konsolidasi.index'               => ['title' => 'Keuangan Seluruh Lembaga',     'subtitle' => 'Laporan keuangan konsolidasi'],
        'log-aktivitas.index'                     => ['title' => 'Log Aktivitas',                'subtitle' => 'Riwayat aktivitas sistem'],
        'konfigurasi-global.show'                 => ['title' => 'Konfigurasi Aplikasi',         'subtitle' => 'Pengaturan global aplikasi'],
        'superadmin.kontak.index'                 => ['title' => 'Pesan Masuk',                  'subtitle' => 'Pesan dari pengguna'],
        'superadmin.kontak.show'                  => ['title' => 'Detail Pesan',                 'subtitle' => 'Pesan Masuk'],
        'superadmin.profil.show'                  => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun superadmin'],

        // Admin Lembaga - Data Master
        'program-zakat.index'                     => ['title' => 'Program Zakat',                'subtitle' => 'Kelola program zakat lembaga'],
        'program-zakat.create'                    => ['title' => 'Tambah Program Zakat',         'subtitle' => 'Program Zakat'],
        'program-zakat.edit'                      => ['title' => 'Edit Program Zakat',           'subtitle' => 'Program Zakat'],
        'rekening-lembaga.index'                  => ['title' => 'Rekening Lembaga',             'subtitle' => 'Kelola rekening lembaga'],
        'rekening-lembaga.create'                 => ['title' => 'Tambah Rekening',              'subtitle' => 'Rekening Lembaga'],
        'rekening-lembaga.edit'                   => ['title' => 'Edit Rekening',                'subtitle' => 'Rekening Lembaga'],

        // Admin Lembaga - Bulletin
        'admin-lembaga.bulletin.index'            => ['title' => 'Bulletin Saya',                'subtitle' => 'Artikel bulletin lembaga'],
        'admin-lembaga.bulletin.create'           => ['title' => 'Tulis Bulletin',               'subtitle' => 'Bulletin Saya'],
        'admin-lembaga.bulletin.edit'             => ['title' => 'Edit Bulletin',                'subtitle' => 'Bulletin Saya'],
        'admin-lembaga.bulletin.show'             => ['title' => 'Detail Bulletin',              'subtitle' => 'Bulletin Saya'],

        // Admin Lembaga - Amil
        'amil.index'                              => ['title' => 'Data Amil',                    'subtitle' => 'Kelola amil lembaga'],
        'amil.create'                             => ['title' => 'Tambah Amil',                  'subtitle' => 'Data Amil'],
        'amil.edit'                               => ['title' => 'Edit Amil',                    'subtitle' => 'Data Amil'],
        'amil.show'                               => ['title' => 'Detail Amil',                  'subtitle' => 'Data Amil'],

        // Admin Lembaga - Mustahik
        'mustahik.index'                          => ['title' => 'Data Mustahik',                'subtitle' => 'Kelola data mustahik'],
        'mustahik.create'                         => ['title' => 'Tambah Mustahik',              'subtitle' => 'Data Mustahik'],
        'mustahik.edit'                           => ['title' => 'Edit Mustahik',                'subtitle' => 'Data Mustahik'],
        'mustahik.show'                           => ['title' => 'Detail Mustahik',              'subtitle' => 'Data Mustahik'],

        // Admin Lembaga - Muzaki
        'admin-lembaga.muzaki.index'              => ['title' => 'Data Muzaki',                  'subtitle' => 'Kelola data muzaki lembaga'],
        'admin-lembaga.muzaki.show'               => ['title' => 'Detail Muzaki',                'subtitle' => 'Data Muzaki'],

        // Admin Lembaga - Penyaluran & Kas
        'transaksi-penyaluran.index'              => ['title' => 'Penyaluran',                   'subtitle' => 'Kelola transaksi penyaluran'],
        'transaksi-penyaluran.create'             => ['title' => 'Tambah Penyaluran',            'subtitle' => 'Penyaluran'],
        'transaksi-penyaluran.show'               => ['title' => 'Detail Penyaluran',            'subtitle' => 'Penyaluran'],
        'admin-lembaga.setor-kas.pending'         => ['title' => 'Setor Kas Amil',               'subtitle' => 'Konfirmasi setoran kas amil'],
        'admin-lembaga.setor-kas.index'           => ['title' => 'Setor Kas Amil',               'subtitle' => 'Riwayat setoran kas amil'],

        // Admin Lembaga - Lainnya
        'laporan-keuangan.index'                  => ['title' => 'Keuangan',                     'subtitle' => 'Laporan keuangan lembaga'],
        'konfigurasi-integrasi.show'              => ['title' => 'Konfigurasi Lembaga',          'subtitle' => 'Pengaturan integrasi lembaga'],
        'admin-lembaga.profil.show'               => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun admin lembaga'],

        // Amil - Transaksi
        'pemantauan-transaksi.index'              => ['title' => 'Pemantauan Transaksi',         'subtitle' => 'Pantau semua transaksi'],
        'transaksi-datang-langsung.index'         => ['title' => 'Transaksi Datang Langsung',    'subtitle' => 'Kelola transaksi tatap muka'],
        'transaksi-datang-langsung.create'        => ['title' => 'Catat Transaksi Langsung',     'subtitle' => 'Transaksi Datang Langsung'],
        'transaksi-datang-langsung.show'          => ['title' => 'Detail Transaksi Langsung',    'subtitle' => 'Transaksi Datang Langsung'],
        'transaksi-daring.index'                  => ['title' => 'Transaksi Daring',             'subtitle' => 'Kelola transaksi online'],
        'transaksi-daring.show'                   => ['title' => 'Detail Transaksi Daring',      'subtitle' => 'Transaksi Daring'],
        'transaksi-dijemput.index'                => ['title' => 'Transaksi Dijemput',           'subtitle' => 'Kelola transaksi penjemputan'],
        'transaksi-dijemput.show'                 => ['title' => 'Detail Transaksi Dijemput',    'subtitle' => 'Transaksi Dijemput'],

        // Amil - Kas & Kunjungan
        'kas-harian.index'                        => ['title' => 'Kas Harian',                   'subtitle' => 'Rekap kas harian amil'],
        'amil.setor-kas.index'                    => ['title' => 'Setor Kas',                    'subtitle' => 'Riwayat setoran kas'],
        'amil.setor-kas.create'                   => ['title' => 'Buat Setor Kas',               'subtitle' => 'Setor Kas'],
        'amil.kunjungan.index'                    => ['title' => 'Kunjungan Mustahik',           'subtitle' => 'Riwayat kunjungan mustahik'],
        'amil.kunjungan.create'                   => ['title' => 'Catat Kunjungan',              'subtitle' => 'Kunjungan Mustahik'],
        'amil.kunjungan.show'                     => ['title' => 'Detail Kunjungan',             'subtitle' => 'Kunjungan Mustahik'],
        'profil.show'                             => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun amil'],

        // Muzakki
        'transaksi-daring-muzakki.index'          => ['title' => 'Bayar Zakat',                  'subtitle' => 'Pilih metode pembayaran zakat'],
        'transaksi-daring-muzakki.create'         => ['title' => 'Bayar Zakat',                  'subtitle' => 'Formulir pembayaran zakat'],
        'transaksi-daring-muzakki.show'           => ['title' => 'Detail Transaksi',             'subtitle' => 'Bayar Zakat'],
        'riwayat-transaksi-muzakki.index'         => ['title' => 'Riwayat Zakat',                'subtitle' => 'Histori pembayaran zakat Anda'],
        'muzakki.testimoni.index'                 => ['title' => 'Testimoni Saya',               'subtitle' => 'Ulasan dan testimoni'],
        'muzakki.testimoni.create'                => ['title' => 'Tulis Testimoni',              'subtitle' => 'Testimoni Saya'],
        'muzakki.profil.show'                     => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun muzakki'],
    ];

    $currentRouteName = request()->route()?->getName() ?? '';
    $pageInfo = $routePageMap[$currentRouteName] ?? ['title' => 'Dashboard', 'subtitle' => ''];
@endphp
<div class="flex items-center">
    <div class="hidden lg:block">
        <h1 class="text-base font-bold text-gray-900 leading-tight" style="letter-spacing: -0.025em;">
            @yield('page-title', $pageInfo['title'])
        </h1>
        @if (!empty($pageInfo['subtitle']))
            <p class="text-xs text-gray-400 mt-0.5">@yield('page-subtitle', $pageInfo['subtitle'])</p>
        @endif
    </div>
</div>

            {{-- Right: Actions & User --}}
            <div class="flex items-center gap-1">

                {{-- ════════════════════════════════════════════════════════
                     IKON PESAN MASUK — Khusus Superadmin
                     ════════════════════════════════════════════════════════ --}}
                @if ($isSuperadmin)
                    <a href="{{ route('superadmin.kontak.index') }}" title="Pesan Masuk dari Pengguna"
                        class="relative flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-200 text-gray-500 hover:text-[#1f5c1f] hover:bg-[#f0f7f0] {{ request()->routeIs('superadmin.kontak.*') ? 'bg-[#e8f5e8] text-[#1f5c1f]' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                        </svg>
                        @if ($kontakBelumDibaca > 0)
                            <span
                                class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none"
                                style="background: #ef4444;">
                                {{ $kontakBelumDibaca > 99 ? '99+' : $kontakBelumDibaca }}
                            </span>
                        @endif
                    </a>

                    <div class="w-px h-5 mx-1" style="background: #e8e8e8;"></div>
                @endif

                {{-- ════════════════════════════════════════════════════════
     NOTIFIKASI — Hanya tampil untuk Amil & Muzakki
     ════════════════════════════════════════════════════════ --}}
                @if ($isAmil || $isMuzakki)
                    <div class="relative">
                        <button onclick="toggleNotifications()" id="notif-btn"
                            class="relative flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-gray-100"
                            title="Notifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($notifUnread > 0)
                                <span id="notif-badge"
                                    class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
                                    style="background: #ef4444;">
                                    {{ $notifUnread > 9 ? '9+' : $notifUnread }}
                                </span>
                            @endif
                        </button>

                        {{-- Notifications Dropdown --}}
                        <div id="notifications-dropdown"
                            class="fixed sm:absolute left-4 right-4 sm:left-auto sm:right-0 top-[60px] sm:top-auto sm:mt-3 w-auto sm:w-[400px] bg-white rounded-2xl hidden overflow-hidden z-50"
                            style="box-shadow: 0 20px 40px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;">

                            <div class="px-5 py-4 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                            @if ($isAmil)
                                                Tindakan yang perlu ditinjau
                                            @elseif($isMuzakki)
                                                Update transaksi (7 hari terakhir)
                                            @endif
                                        </p>
                                    </div>
                                    @if ($notifUnread > 0)
                                        <span
                                            class="text-[10px] font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                            {{ $notifUnread }} baru
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div id="notif-list" class="max-h-[420px] overflow-y-auto divide-y divide-gray-50">
                                @forelse($notifItems as $notif)
                                    <a href="{{ $notif['url'] }}" data-id="{{ $notif['id'] }}"
                                        onclick="markNotifRead(event, this)"
                                        class="notif-item block px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex gap-3">
                                            {{-- Icon --}}
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div
                                                    class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-100">
                                                    @if ($notif['icon'] === 'transfer')
                                                        <svg class="w-4 h-4 text-gray-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    @elseif($notif['icon'] === 'pickup')
                                                        <svg class="w-4 h-4 text-gray-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    @elseif($notif['icon'] === 'check')
                                                        <svg class="w-4 h-4 text-gray-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @elseif($notif['icon'] === 'x')
                                                        <svg class="w-4 h-4 text-gray-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @elseif($notif['icon'] === 'truck')
                                                        <svg class="w-4 h-4 text-gray-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.75"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 mb-0.5">
                                                    {{ $notif['title'] }}</p>
                                                <p class="text-[13px] text-gray-500 leading-relaxed">
                                                    {{ $notif['body'] }}</p>
                                                <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $notif['time'] }}
                                                </p>
                                            </div>

                                            {{-- Arrow --}}
                                            <svg class="w-4 h-4 text-gray-300 flex-shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-12 text-center">
                                        <div
                                            class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Tidak ada notifikasi</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            @if ($isAmil)
                                                Semua transaksi sudah tertangani
                                            @elseif($isMuzakki)
                                                Belum ada update dalam 7 hari terakhir
                                            @endif
                                        </p>
                                    </div>
                                @endforelse
                            </div>

                            @if ($notifItems->isNotEmpty())
                                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                                    @if ($isAmil)
                                        <a href="{{ route('pemantauan-transaksi.index') }}"
                                            class="flex items-center justify-center gap-1.5 text-xs font-medium text-gray-600 hover:text-gray-900 transition-colors py-1">
                                            <span>Lihat semua transaksi</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @elseif($isMuzakki)
                                        <a href="{{ route('transaksi-daring-muzakki.index') }}"
                                            class="flex items-center justify-center gap-1.5 text-xs font-medium text-gray-600 hover:text-gray-900 transition-colors py-1">
                                            <span>Lihat riwayat transaksi</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-px h-5 mx-1 bg-gray-200"></div>
                @endif

                {{-- USER MENU --}}
                <div class="relative">
                    <button onclick="toggleUserMenu()" id="user-menu-btn"
                        class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl hover:bg-[#f5faf5] transition-all duration-200 group">
                        {{-- Avatar --}}
                        <div class="w-8 h-8 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center"
                            style="background: #2d6a2d;">
                            @if ($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $authUser->username }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span
                                    class="text-white font-bold text-sm">{{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}</span>
                            @endif
                        </div>
                        {{-- Name + Role --}}
                        <div class="hidden lg:block text-left">
                            <p class="text-[12.5px] font-semibold text-gray-800 leading-tight">
                                {{ $isAdminLembaga && $authUser?->lembaga?->admin_nama ? $authUser->lembaga->admin_nama : $authUser->username ?? 'Pengguna' }}
                            </p>
                            <p class="text-[10.5px] text-gray-400 leading-tight mt-0.5">{{ $roleLabel }}</p>
                        </div>
                        <svg class="hidden lg:block w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 transition-all duration-200 group-hover:translate-y-0.5"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- User Dropdown --}}
                    <div id="user-menu-dropdown"
                        class="absolute right-0 mt-3 w-60 bg-white rounded-2xl hidden overflow-hidden"
                        style="box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.06);">

                        {{-- Header --}}
                        <div class="px-4 py-3" style="background: #fafcfa;">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center"
                                    style="background: #2d6a2d;">
                                    @if ($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="{{ $authUser->username }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span
                                            class="text-white font-bold text-sm">{{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <p class="text-[12.5px] font-bold text-gray-900 truncate leading-tight">
                                            {{ $isAdminLembaga && $authUser?->lembaga?->admin_nama ? $authUser->lembaga->admin_nama : $authUser->username ?? 'Pengguna' }}
                                        </p>
                                        <span
                                            class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9.5px] font-bold {{ $badgeClass }}">{{ $roleLabel }}</span>
                                    </div>
                                    <p class="text-[10.5px] text-gray-400 truncate leading-tight mt-0.5">
                                        {{ $authUser->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Menu Items --}}
                        <div class="py-1.5">
                            @if ($isSuperadmin)
                                <a href="{{ route('superadmin.profil.show') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('superadmin.profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('konfigurasi-global.show') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('konfigurasi-global.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span>Konfigurasi Global</span>
                                </a>
                                <a href="{{ route('superadmin.kontak.index') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('superadmin.kontak.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                                        </svg>
                                    </div>
                                    <span class="flex-1">Pesan Masuk</span>
                                    @if ($kontakBelumDibaca > 0)
                                        <span
                                            class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[9px] font-bold text-white rounded-full"
                                            style="background: #ef4444;">
                                            {{ $kontakBelumDibaca > 99 ? '99+' : $kontakBelumDibaca }}
                                        </span>
                                    @endif
                                </a>
                            @endif

                            @if ($isAdminLembaga)
                                <a href="{{ route('admin-lembaga.profil.show') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('admin-lembaga.profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('konfigurasi-integrasi.show') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('konfigurasi-integrasi.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span>Konfigurasi Integrasi</span>
                                </a>
                            @endif

                            @if ($isAmil)
                                <a href="{{ route('profil.show') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span>Profil Saya</span>
                                </a>
                            @endif

                            @if ($isMuzakki)
                                <a href="{{ route('muzakki.profil.show') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('muzakki.profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('transaksi-daring-muzakki.create') }}"
                                    class="flex items-center space-x-3 px-4 py-1.5 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('transaksi-daring-muzakki.create') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <span>Bayar Zakat</span>
                                </a>
                            @endif
                        </div>

                        {{-- Logout --}}
                        <div class="px-3 py-2">
                            <button type="button" onclick="openLogoutModal()"
                                class="w-full flex items-center space-x-3 px-3 py-2 rounded-xl text-[12.5px] font-medium text-red-600 hover:bg-red-50 transition-all duration-200 group">
                                <div
                                    class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 bg-red-50 group-hover:bg-red-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span>Keluar</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

<script>
    function openLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const backdrop = document.getElementById('logout-backdrop');
        const card = document.getElementById('logout-card');
        document.getElementById('user-menu-dropdown')?.classList.add('hidden');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            backdrop.style.opacity = '1';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        });
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const backdrop = document.getElementById('logout-backdrop');
        const card = document.getElementById('logout-card');
        backdrop.style.opacity = '0';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLogoutModal();
    });

    function toggleNotifications() {
        const dropdown = document.getElementById('notifications-dropdown');
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden');
        document.getElementById('user-menu-dropdown')?.classList.add('hidden');
        if (isHidden) {
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'scale(0.95) translateY(-4px)';
            dropdown.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
            requestAnimationFrame(() => {
                dropdown.style.opacity = '1';
                dropdown.style.transform = 'scale(1) translateY(0)';
            });
        }
    }

    function toggleUserMenu() {
        const dropdown = document.getElementById('user-menu-dropdown');
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden');
        document.getElementById('notifications-dropdown')?.classList.add('hidden');
        if (isHidden) {
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'scale(0.95) translateY(-4px)';
            dropdown.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
            requestAnimationFrame(() => {
                dropdown.style.opacity = '1';
                dropdown.style.transform = 'scale(1) translateY(0)';
            });
        }
    }

    function markNotifRead(event, el) {
        event.preventDefault();
        const id = el.dataset.id;
        const url = el.getAttribute('href');
        el.style.transition = 'opacity 0.2s, max-height 0.3s, padding 0.3s';
        el.style.overflow = 'hidden';
        el.style.maxHeight = el.offsetHeight + 'px';
        el.style.opacity = '0';
        setTimeout(() => {
            el.style.maxHeight = '0';
            el.style.padding = '0';
        }, 50);
        setTimeout(() => el.remove(), 350);
        const badge = document.getElementById('notif-badge');
        const headerBadge = document.getElementById('notif-header-badge');
        if (badge) {
            let count = Math.max(0, (parseInt(badge.textContent) || 0) - 1);
            if (count <= 0) {
                badge.classList.add('hidden');
                if (headerBadge) headerBadge.classList.add('hidden');
            } else {
                badge.textContent = count > 9 ? '9+' : count;
                if (headerBadge) headerBadge.textContent = count + ' baru';
            }
        }
        fetch('{{ route('notif.mark-read') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: id
            }),
        }).catch(() => {});
        setTimeout(() => {
            window.location.href = url;
        }, 300);
    }

    document.addEventListener('click', function(e) {
        const notifBtn = document.getElementById('notif-btn');
        const userBtn = document.getElementById('user-menu-btn');
        const notifDrop = document.getElementById('notifications-dropdown');
        const userDrop = document.getElementById('user-menu-dropdown');
        if (notifBtn && notifDrop && !notifBtn.contains(e.target) && !notifDrop.contains(e.target)) notifDrop
            .classList.add('hidden');
        if (userBtn && userDrop && !userBtn.contains(e.target) && !userDrop.contains(e.target)) userDrop
            .classList.add('hidden');
    });
</script>
