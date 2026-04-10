{{-- resources/views/partials/navbar.blade.php --}}

@php
    $authUser = auth()->user();
    $isSuperadmin = $authUser?->isSuperadmin();
    $isAdminLembaga = $authUser?->isAdminLembaga();
    $isAmil = $authUser?->isAmil();
    $isMuzakki = $authUser?->isMuzakki();

    $kontakBelumDibaca = 0;
    if ($isSuperadmin) {
        $kontakBelumDibaca = \App\Models\Kontak::whereNull('dibaca_at')->count();
    }

    $notifItems = collect();
    $notifUnread = 0;
    $notifSessionKey = 'notif_read_ids_' . $authUser->id;
    $readIds = session($notifSessionKey, []);

    if ($isAmil && isset($authUser->amil)) {
        $lembagaId = $authUser->amil->lembaga_id;
        $daringPending = \App\Models\TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('metode_penerimaan', 'daring')->where('status', 'pending')
            ->where('konfirmasi_status', 'menunggu_konfirmasi')
            ->with('jenisZakat')->latest('created_at')->take(10)->get()
            ->map(fn($t) => [
                'icon' => 'transfer', 'title' => 'Transaksi Daring Baru',
                'body' => $t->muzakki_nama . ' — ' . ($t->jenisZakat->nama ?? 'Zakat') . ' Rp ' . number_format($t->jumlah, 0, ',', '.'),
                'time' => $t->created_at->diffForHumans(), 'timestamp' => $t->created_at->timestamp,
                'url' => route('transaksi-daring.show', $t->uuid), 'id' => $t->uuid,
            ]);
        $amilId = $authUser->amil->id;
        $dijemputPending = \App\Models\TransaksiPenerimaan::where('lembaga_id', $lembagaId)
            ->where('metode_penerimaan', 'dijemput')->where('status', 'pending')
            ->where('status_penjemputan', 'menunggu')
            ->where(fn($q) => $q->where('amil_id', $amilId)->orWhereNull('amil_id'))
            ->with('jenisZakat')->latest('created_at')->take(10)->get()
            ->map(fn($t) => [
                'icon' => 'pickup',
                'title' => $t->amil_id === $amilId ? 'Request Penjemputan (Anda Dipilih)' : 'Request Penjemputan',
                'body' => $t->muzakki_nama . ' — Menunggu dijemput di ' . \Str::limit($t->muzakki_alamat ?? 'lokasi terdaftar', 35),
                'time' => $t->created_at->diffForHumans(), 'timestamp' => $t->created_at->timestamp,
                'url' => route('transaksi-dijemput.show', $t->uuid), 'id' => $t->uuid,
            ]);
        $notifItems = collect(array_merge($daringPending->values()->toArray(), $dijemputPending->values()->toArray()))
            ->filter(fn($n) => !in_array($n['id'], $readIds))->sortByDesc('timestamp')->values();
        $notifUnread = $notifItems->count();
    }

    if ($isMuzakki && isset($authUser->muzakki)) {
        $muzakkiId = $authUser->muzakki->id;
        $allMuzakkiNotif = \App\Models\TransaksiPenerimaan::where('muzakki_id', $muzakkiId)
            ->whereIn('status', ['verified', 'rejected', 'dijemput'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->with('jenisZakat')->latest('updated_at')->take(10)->get()
            ->map(function ($t) {
                $statusMap = [
                    'verified' => ['icon' => 'check', 'title' => 'Transaksi Dikonfirmasi', 'body' => 'Transaksi ' . $t->no_transaksi . ' telah diverifikasi oleh amil.'],
                    'rejected' => ['icon' => 'x',     'title' => 'Transaksi Ditolak',      'body' => 'Transaksi ' . $t->no_transaksi . ' ditolak. Silakan hubungi amil.'],
                    'dijemput' => ['icon' => 'truck',  'title' => 'Amil Dalam Perjalanan',  'body' => 'Amil sedang menuju lokasi untuk ' . $t->no_transaksi . '.'],
                ];
                $info = $statusMap[$t->status] ?? ['icon' => 'info', 'title' => 'Update Transaksi', 'body' => 'Ada pembaruan pada transaksi ' . $t->no_transaksi];
                return array_merge($info, [
                    'time' => $t->updated_at->diffForHumans(), 'timestamp' => $t->updated_at->timestamp,
                    'url' => route('transaksi-daring-muzakki.show', $t->uuid), 'id' => $t->uuid,
                ]);
            });
        $notifItems = collect($allMuzakkiNotif->values()->toArray())
            ->filter(fn($n) => !in_array($n['id'], $readIds))->sortByDesc('timestamp')->values();
        $notifUnread = $notifItems->count();
    }

    $roleLabel = match (true) {
        $isSuperadmin   => 'Super Admin',
        $isAdminLembaga => 'Admin Lembaga',
        $isAmil         => 'Amil',
        $isMuzakki      => 'Muzakki',
        default         => 'Pengguna',
    };

    $roleBadgeColor = match (true) {
        $isSuperadmin   => 'role-superadmin',
        $isAdminLembaga => 'role-admin',
        $isAmil         => 'role-amil',
        $isMuzakki      => 'role-muzakki',
        default         => 'role-default',
    };

    $avatarUrl = null;
    if ($isAdminLembaga && $authUser?->lembaga?->admin_foto) {
        $avatarUrl = $authUser->lembaga->admin_foto_url;
    } elseif ($isAmil && isset($authUser->amil) && $authUser->amil->foto) {
        if (\Storage::disk('public')->exists($authUser->amil->foto)) $avatarUrl = \Storage::url($authUser->amil->foto);
    } elseif ($isMuzakki && isset($authUser->muzakki) && $authUser->muzakki->foto) {
        if (\Storage::disk('public')->exists($authUser->muzakki->foto)) $avatarUrl = \Storage::url($authUser->muzakki->foto);
    } elseif (isset($authUser->foto) && $authUser->foto && \Storage::disk('public')->exists($authUser->foto)) {
        $avatarUrl = \Storage::url($authUser->foto);
    }

    $displayName = $isAdminLembaga && $authUser?->lembaga?->admin_nama
        ? $authUser->lembaga->admin_nama
        : ($authUser->username ?? 'Pengguna');

    $avatarInitial = strtoupper(substr($displayName, 0, 1));

    $routePageMap = [
        'dashboard'                               => ['title' => 'Dashboard',                    'subtitle' => 'Ringkasan aktivitas'],
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
        'pengguna.index'                          => ['title' => 'Pengguna',                     'subtitle' => 'Kelola data pengguna'],
        'pengguna.create'                         => ['title' => 'Tambah Pengguna',              'subtitle' => 'Pengguna'],
        'pengguna.edit'                           => ['title' => 'Edit Pengguna',                'subtitle' => 'Pengguna'],
        'superadmin.kategori-bulletin.index'      => ['title' => 'Kategori Bulletin',            'subtitle' => 'Kelola kategori bulletin'],
        'superadmin.bulletin.index'               => ['title' => 'Kelola Bulletin',              'subtitle' => 'Manajemen artikel bulletin'],
        'superadmin.bulletin.create'              => ['title' => 'Tambah Bulletin',              'subtitle' => 'Bulletin'],
        'superadmin.bulletin.edit'                => ['title' => 'Edit Bulletin',                'subtitle' => 'Bulletin'],
        'superadmin.bulletin.show'                => ['title' => 'Detail Bulletin',              'subtitle' => 'Bulletin'],
        'lembaga.index'                           => ['title' => 'Lembaga',                      'subtitle' => 'Kelola data lembaga'],
        'lembaga.create'                          => ['title' => 'Tambah Lembaga',               'subtitle' => 'Lembaga'],
        'lembaga.edit'                            => ['title' => 'Edit Lembaga',                 'subtitle' => 'Lembaga'],
        'lembaga.show'                            => ['title' => 'Detail Lembaga',               'subtitle' => 'Lembaga'],
        'superadmin.amil.index'                   => ['title' => 'Data Amil',                    'subtitle' => 'Kelola data amil'],
        'superadmin.amil.show'                    => ['title' => 'Detail Amil',                  'subtitle' => 'Data Amil'],
        'superadmin.mustahik.index'               => ['title' => 'Data Mustahik',                'subtitle' => 'Kelola data mustahik'],
        'superadmin.mustahik.show'                => ['title' => 'Detail Mustahik',              'subtitle' => 'Data Mustahik'],
        'muzaki.index'                            => ['title' => 'Data Muzaki',                  'subtitle' => 'Kelola data muzaki'],
        'muzaki.show'                             => ['title' => 'Detail Muzaki',                'subtitle' => 'Data Muzaki'],
        'superadmin.transaksi-penerimaan.index'   => ['title' => 'Transaksi Penerimaan',         'subtitle' => 'Data seluruh transaksi penerimaan'],
        'superadmin.transaksi-penerimaan.show'    => ['title' => 'Detail Transaksi Penerimaan',  'subtitle' => 'Transaksi Penerimaan'],
        'superadmin.transaksi-penyaluran.index'   => ['title' => 'Transaksi Penyaluran',         'subtitle' => 'Data seluruh transaksi penyaluran'],
        'superadmin.transaksi-penyaluran.show'    => ['title' => 'Detail Transaksi Penyaluran',  'subtitle' => 'Transaksi Penyaluran'],
        'superadmin.testimoni.index'              => ['title' => 'Kelola Testimoni',             'subtitle' => 'Moderasi testimoni muzakki'],
        'laporan-konsolidasi.index'               => ['title' => 'Keuangan Seluruh Lembaga',     'subtitle' => 'Laporan keuangan konsolidasi'],
        'log-aktivitas.index'                     => ['title' => 'Log Aktivitas',                'subtitle' => 'Riwayat aktivitas sistem'],
        'konfigurasi-global.show'                 => ['title' => 'Konfigurasi Aplikasi',         'subtitle' => 'Pengaturan global aplikasi'],
        'superadmin.kontak.index'                 => ['title' => 'Pesan Masuk',                  'subtitle' => 'Pesan dari pengguna'],
        'superadmin.kontak.show'                  => ['title' => 'Detail Pesan',                 'subtitle' => 'Pesan Masuk'],
        'superadmin.profil.show'                  => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun superadmin'],
        'program-zakat.index'                     => ['title' => 'Program Zakat',                'subtitle' => 'Kelola program zakat lembaga'],
        'program-zakat.create'                    => ['title' => 'Tambah Program Zakat',         'subtitle' => 'Program Zakat'],
        'program-zakat.edit'                      => ['title' => 'Edit Program Zakat',           'subtitle' => 'Program Zakat'],
        'rekening-lembaga.index'                  => ['title' => 'Rekening Lembaga',             'subtitle' => 'Kelola rekening lembaga'],
        'rekening-lembaga.create'                 => ['title' => 'Tambah Rekening',              'subtitle' => 'Rekening Lembaga'],
        'rekening-lembaga.edit'                   => ['title' => 'Edit Rekening',                'subtitle' => 'Rekening Lembaga'],
        'admin-lembaga.bulletin.index'            => ['title' => 'Bulletin Saya',                'subtitle' => 'Artikel bulletin lembaga'],
        'admin-lembaga.bulletin.create'           => ['title' => 'Tulis Bulletin',               'subtitle' => 'Bulletin Saya'],
        'admin-lembaga.bulletin.edit'             => ['title' => 'Edit Bulletin',                'subtitle' => 'Bulletin Saya'],
        'admin-lembaga.bulletin.show'             => ['title' => 'Detail Bulletin',              'subtitle' => 'Bulletin Saya'],
        'amil.index'                              => ['title' => 'Data Amil',                    'subtitle' => 'Kelola amil lembaga'],
        'amil.create'                             => ['title' => 'Tambah Amil',                  'subtitle' => 'Data Amil'],
        'amil.edit'                               => ['title' => 'Edit Amil',                    'subtitle' => 'Data Amil'],
        'amil.show'                               => ['title' => 'Detail Amil',                  'subtitle' => 'Data Amil'],
        'mustahik.index'                          => ['title' => 'Data Mustahik',                'subtitle' => 'Kelola data mustahik'],
        'mustahik.create'                         => ['title' => 'Tambah Mustahik',              'subtitle' => 'Data Mustahik'],
        'mustahik.edit'                           => ['title' => 'Edit Mustahik',                'subtitle' => 'Data Mustahik'],
        'mustahik.show'                           => ['title' => 'Detail Mustahik',              'subtitle' => 'Data Mustahik'],
        'admin-lembaga.muzaki.index'              => ['title' => 'Data Muzaki',                  'subtitle' => 'Kelola data muzaki lembaga'],
        'admin-lembaga.muzaki.show'               => ['title' => 'Detail Muzaki',                'subtitle' => 'Data Muzaki'],
        'transaksi-penyaluran.index'              => ['title' => 'Penyaluran',                   'subtitle' => 'Kelola transaksi penyaluran'],
        'transaksi-penyaluran.create'             => ['title' => 'Tambah Penyaluran',            'subtitle' => 'Penyaluran'],
        'transaksi-penyaluran.show'               => ['title' => 'Detail Penyaluran',            'subtitle' => 'Penyaluran'],
        'admin-lembaga.setor-kas.pending'         => ['title' => 'Setor Kas Amil',               'subtitle' => 'Konfirmasi setoran kas amil'],
        'admin-lembaga.setor-kas.index'           => ['title' => 'Setor Kas Amil',               'subtitle' => 'Riwayat setoran kas amil'],
        'laporan-keuangan.index'                  => ['title' => 'Keuangan',                     'subtitle' => 'Laporan keuangan lembaga'],
        'konfigurasi-integrasi.show'              => ['title' => 'Konfigurasi Lembaga',          'subtitle' => 'Pengaturan integrasi lembaga'],
        'admin-lembaga.profil.show'               => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun admin lembaga'],
        'pemantauan-transaksi.index'              => ['title' => 'Pemantauan Transaksi',         'subtitle' => 'Pantau semua transaksi'],
        'transaksi-datang-langsung.index'         => ['title' => 'Transaksi Datang Langsung',    'subtitle' => 'Kelola transaksi tatap muka'],
        'transaksi-datang-langsung.create'        => ['title' => 'Catat Transaksi Langsung',     'subtitle' => 'Transaksi Datang Langsung'],
        'transaksi-datang-langsung.show'          => ['title' => 'Detail Transaksi Langsung',    'subtitle' => 'Transaksi Datang Langsung'],
        'transaksi-daring.index'                  => ['title' => 'Transaksi Daring',             'subtitle' => 'Kelola transaksi online'],
        'transaksi-daring.show'                   => ['title' => 'Detail Transaksi Daring',      'subtitle' => 'Transaksi Daring'],
        'transaksi-dijemput.index'                => ['title' => 'Transaksi Dijemput',           'subtitle' => 'Kelola transaksi penjemputan'],
        'transaksi-dijemput.show'                 => ['title' => 'Detail Transaksi Dijemput',    'subtitle' => 'Transaksi Dijemput'],
        'kas-harian.index'                        => ['title' => 'Kas Harian',                   'subtitle' => 'Rekap kas harian amil'],
        'amil.setor-kas.index'                    => ['title' => 'Setor Kas',                    'subtitle' => 'Riwayat setoran kas'],
        'amil.setor-kas.create'                   => ['title' => 'Buat Setor Kas',               'subtitle' => 'Setor Kas'],
        'amil.kunjungan.index'                    => ['title' => 'Kunjungan Mustahik',           'subtitle' => 'Riwayat kunjungan mustahik'],
        'amil.kunjungan.create'                   => ['title' => 'Catat Kunjungan',              'subtitle' => 'Kunjungan Mustahik'],
        'amil.kunjungan.show'                     => ['title' => 'Detail Kunjungan',             'subtitle' => 'Kunjungan Mustahik'],
        'profil.show'                             => ['title' => 'Profil Saya',                  'subtitle' => 'Informasi akun amil'],
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

{{-- ═══════════════════════════════════════════════════════════════
     STYLES
     ═══════════════════════════════════════════════════════════════ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    :root {
        --nav-h: 64px;
        --nav-bg: rgba(255,255,255,0.92);
        --nav-border: rgba(0,0,0,0.06);
        --surface: #ffffff;
        --surface-hover: #f8f8fa;
        --surface-active: #f2f2f6;
        --text-primary: #0f0f14;
        --text-secondary: #6b6b80;
        --text-muted: #a0a0b0;
        --accent: #16a34a;
        --accent-light: #dcfce7;
        --accent-mid: #22c55e;
        --danger: #ef4444;
        --warning: #f59e0b;
        --ring: rgba(22,163,74,0.18);
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.05);
        --shadow-lg: 0 12px 40px rgba(0,0,0,0.10), 0 4px 12px rgba(0,0,0,0.06);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;
        --font-main: 'Plus Jakarta Sans', -apple-system, sans-serif;
    }

    * { box-sizing: border-box; }

    #main-navbar {
        font-family: Poppins;
    }

    /* ── Navbar Shell ── */
    .navbar-shell {
        position: sticky;
        top: 0;
        z-index: 40;
        height: var(--nav-h);
        background: var(--nav-bg);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-bottom: 1px solid var(--nav-border);
    }

    .navbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        padding: 0 24px;
        gap: 12px;
    }

    /* ── Page Title Area ── */
    .page-title-area {
        display: none;
    }
    @media (min-width: 1024px) {
        .page-title-area { display: flex; flex-direction: column; justify-content: center; }
    }

    .page-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.025em;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 11.5px;
        font-weight: 500;
        color: var(--text-muted);
        margin-top: 2px;
        letter-spacing: 0.01em;
    }

    /* ── Actions Bar ── */
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: auto;
    }

    .nav-divider {
        width: 1px;
        height: 20px;
        background: var(--nav-border);
        margin: 0 6px;
        opacity: 0.8;
    }

    /* ── Icon Buttons ── */
    .nav-icon-btn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        border: none;
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease, transform 0.1s ease;
        outline: none;
    }

    .nav-icon-btn:hover {
        background: var(--surface-hover);
        color: var(--text-primary);
        transform: translateY(-1px);
    }

    .nav-icon-btn:active {
        transform: translateY(0);
        background: var(--surface-active);
    }

    .nav-icon-btn.active {
        background: var(--surface-active);
        color: var(--text-primary);
    }

    .nav-icon-btn svg {
        width: 18px;
        height: 18px;
        transition: transform 0.2s ease;
    }

    .nav-icon-btn:hover svg {
        transform: scale(1.08);
    }

    /* ── Notification Badge ── */
    .notif-dot {
        position: absolute;
        top: 7px;
        right: 7px;
        width: 8px;
        height: 8px;
        background: var(--danger);
        border-radius: 50%;
        border: 2px solid white;
        animation: pulse-dot 2.5s ease infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.8; }
    }

    /* ── User Menu Button ── */
    .user-menu-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 10px 5px 5px;
        border-radius: var(--radius-md);
        border: 1.5px solid transparent;
        background: transparent;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.1s ease;
        outline: none;
    }

    .user-menu-btn:hover {
        background: var(--surface-hover);
        border-color: var(--nav-border);
        transform: translateY(-1px);
    }

    .user-menu-btn:active {
        transform: translateY(0);
    }

    .user-menu-btn.open {
        background: var(--surface-active);
        border-color: rgba(22,163,74,0.2);
    }

    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #bbf7d0 0%, #86efac 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255,255,255,0.9);
        box-shadow: 0 0 0 1.5px rgba(22,163,74,0.15);
    }

    .user-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .user-avatar-initial {
        font-size: 13px;
        font-weight: 700;
        color: #15803d;
        font-family: Poppins;
    }

    .user-info { display: none; text-align: left; }
    @media (min-width: 1024px) { .user-info { display: block; } }

    .user-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .user-role {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-muted);
        margin-top: 1px;
    }

    .chevron-icon {
        display: none;
        width: 14px;
        height: 14px;
        color: var(--text-muted);
        transition: transform 0.2s ease, color 0.15s ease;
        flex-shrink: 0;
    }
    @media (min-width: 1024px) { .chevron-icon { display: block; } }
    .user-menu-btn.open .chevron-icon { transform: rotate(180deg); color: var(--text-secondary); }

    /* ══════════════════════════════════════════
       DROPDOWN BASE
    ══════════════════════════════════════════ */
    .nav-dropdown {
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        background: var(--surface);
        border: 1px solid var(--nav-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        z-index: 50;
        display: none;
        opacity: 0;
        transform: scale(0.96) translateY(-6px);
        transition: opacity 0.15s ease, transform 0.15s ease;
        transform-origin: top right;
    }

    .nav-dropdown.show {
        display: block;
        animation: dropdownIn 0.15s ease forwards;
    }

    @keyframes dropdownIn {
        from { opacity: 0; transform: scale(0.96) translateY(-6px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* ── Notification Dropdown ── */
    .notif-dropdown {
        width: 380px;
    }

    .dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--nav-border);
    }

    .dropdown-header-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .dropdown-header-sub {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 2px;
        font-weight: 400;
    }

    .badge-count {
        font-size: 10px;
        font-weight: 700;
        color: white;
        background: var(--danger);
        border-radius: 20px;
        padding: 2px 7px;
        letter-spacing: 0.01em;
        min-width: 22px;
        text-align: center;
    }

    .notif-list {
        max-height: 360px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb transparent;
    }

    .notif-item {
        display: flex;
        gap: 12px;
        padding: 12px 16px;
        text-decoration: none;
        transition: background 0.12s ease;
        border-bottom: 1px solid rgba(0,0,0,0.03);
    }

    .notif-item:hover { background: var(--surface-hover); }
    .notif-item:last-child { border-bottom: none; }

    .notif-icon-wrap {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background: var(--surface-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    }

    .notif-icon-wrap svg { width: 15px; height: 15px; color: var(--text-secondary); }

    .notif-icon-wrap.type-transfer { background: #eff6ff; }
    .notif-icon-wrap.type-transfer svg { color: #3b82f6; }
    .notif-icon-wrap.type-pickup { background: #fff7ed; }
    .notif-icon-wrap.type-pickup svg { color: #f97316; }
    .notif-icon-wrap.type-check { background: #f0fdf4; }
    .notif-icon-wrap.type-check svg { color: #16a34a; }
    .notif-icon-wrap.type-x { background: #fef2f2; }
    .notif-icon-wrap.type-x svg { color: #ef4444; }
    .notif-icon-wrap.type-truck { background: #f5f3ff; }
    .notif-icon-wrap.type-truck svg { color: #8b5cf6; }

    .notif-title {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.3;
        letter-spacing: -0.01em;
    }

    .notif-body {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 2px;
        line-height: 1.5;
    }

    .notif-time {
        font-size: 10.5px;
        color: var(--text-muted);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 3px;
        font-weight: 500;
    }

    .notif-time svg { width: 11px; height: 11px; }

    .notif-empty {
        padding: 40px 16px;
        text-align: center;
    }

    .notif-empty-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--surface-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }

    .notif-empty-icon svg { width: 22px; height: 22px; color: var(--text-muted); }

    .notif-empty-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        letter-spacing: -0.01em;
    }

    .notif-empty-sub {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 3px;
    }

    .dropdown-footer {
        padding: 10px 16px;
        border-top: 1px solid var(--nav-border);
    }

    .dropdown-footer-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        padding: 6px;
        border-radius: var(--radius-sm);
        transition: background 0.12s ease, color 0.12s ease;
        letter-spacing: -0.01em;
    }

    .dropdown-footer-link:hover { background: var(--accent-light); color: #15803d; }
    .dropdown-footer-link svg { width: 13px; height: 13px; }

    /* ── User Dropdown ── */
    .user-dropdown {
        width: 240px;
    }

    .user-dropdown-header {
        padding: 14px 16px;
        border-bottom: 1px solid var(--nav-border);
    }

    .user-dropdown-meta {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-dropdown-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #bbf7d0 0%, #86efac 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255,255,255,0.9);
        box-shadow: 0 0 0 1.5px rgba(22,163,74,0.15);
    }

    .user-dropdown-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .user-dropdown-avatar-initial { font-size: 15px; font-weight: 700; color: #15803d; font-family: Poppins; }

    .user-dropdown-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-dropdown-email {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 400;
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        margin-top: 5px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .role-superadmin { background: #fef3c7; color: #92400e; }
    .role-admin      { background: #eff6ff; color: #1d4ed8; }
    .role-amil       { background: #f0fdf4; color: #15803d; }
    .role-muzakki    { background: #f5f3ff; color: #6d28d9; }
    .role-default    { background: var(--surface-hover); color: var(--text-secondary); }

    /* ── Menu Items ── */
    .menu-section { padding: 6px 0; }
    .menu-section + .menu-section { border-top: 1px solid var(--nav-border); }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        transition: background 0.1s ease, color 0.1s ease;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        letter-spacing: -0.01em;
        font-family: Poppins;
    }

    .menu-item:hover { background: var(--surface-hover); color: var(--text-primary); }
    .menu-item.active { background: var(--surface-active); color: var(--text-primary); font-weight: 600; }

    .menu-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: var(--text-muted);
        transition: color 0.1s ease;
    }

    .menu-item:hover svg { color: var(--text-secondary); }
    .menu-item.active svg { color: var(--accent); }

    .menu-item-label { flex: 1; }

    .menu-badge {
        font-size: 9.5px;
        font-weight: 700;
        color: white;
        background: var(--danger);
        border-radius: 20px;
        padding: 1.5px 6px;
        min-width: 18px;
        text-align: center;
    }

    .menu-item-danger { color: var(--danger) !important; }
    .menu-item-danger:hover { background: #fff1f1 !important; }
    .menu-item-danger svg { color: var(--danger) !important; }

    /* ══════════════════════════════════════════
       LOGOUT MODAL
    ══════════════════════════════════════════ */
    #logout-modal {
        font-family: Poppins;
    }

    .logout-card {
        background: white;
        border-radius: var(--radius-xl);
        width: 100%;
        max-width: 360px;
        margin: 0 16px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0,0,0,0.14), 0 8px 20px rgba(0,0,0,0.08);
    }

    .logout-card-body { padding: 28px 28px 24px; }

    .logout-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-md);
        background: #fff1f1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
    }

    .logout-icon-wrap svg { width: 24px; height: 24px; color: var(--danger); }

    .logout-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        letter-spacing: -0.03em;
        margin-bottom: 5px;
    }

    .logout-desc {
        font-size: 12.5px;
        color: var(--text-muted);
        text-align: center;
        line-height: 1.6;
        margin-bottom: 20px;
        font-weight: 400;
    }

    .logout-user-card {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 13px;
        background: var(--surface-hover);
        border-radius: var(--radius-md);
        border: 1px solid var(--nav-border);
        margin-bottom: 18px;
    }

    .logout-user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #bbf7d0 0%, #86efac 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logout-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .logout-user-avatar-initial { font-size: 14px; font-weight: 700; color: #15803d; }

    .logout-user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); letter-spacing: -0.01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .logout-user-email { font-size: 11px; color: var(--text-muted); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .logout-user-role { font-size: 10px; color: var(--text-muted); font-weight: 500; flex-shrink: 0; }

    .logout-actions { display: flex; gap: 10px; }

    .btn-cancel {
        flex: 1;
        padding: 10px 16px;
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        background: var(--surface-hover);
        border: 1.5px solid var(--nav-border);
        cursor: pointer;
        transition: background 0.12s ease, color 0.12s ease;
        font-family: Poppins;
        letter-spacing: -0.01em;
    }

    .btn-cancel:hover { background: var(--surface-active); color: var(--text-primary); }

    .btn-logout {
        flex: 1;
        padding: 10px 16px;
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        color: white;
        background: var(--danger);
        border: 1.5px solid var(--danger);
        cursor: pointer;
        transition: background 0.12s ease, transform 0.1s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-family: Poppins;
        letter-spacing: -0.01em;
    }

    .btn-logout:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-logout:active { transform: translateY(0); }
    .btn-logout svg { width: 14px; height: 14px; }
</style>

{{-- ═══════════════════════════════════════════════════════════════
     LOGOUT MODAL
     ═══════════════════════════════════════════════════════════════ --}}
<div id="logout-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden" aria-modal="true" role="dialog">
    <div id="logout-backdrop"
        class="absolute inset-0 bg-black/30 opacity-0 transition-opacity duration-300"
        style="backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);"
        onclick="closeLogoutModal()"></div>

    <div id="logout-card" class="logout-card relative scale-95 opacity-0 transition-all duration-300">
        <div class="logout-card-body">
            <div class="logout-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <h2 class="logout-title">Keluar dari Akun?</h2>
            <p class="logout-desc">Sesi Anda akan diakhiri. Pastikan semua<br>pekerjaan sudah tersimpan.</p>

            <div class="logout-user-card">
                <div class="logout-user-avatar">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $displayName }}">
                    @else
                        <span class="logout-user-avatar-initial">{{ $avatarInitial }}</span>
                    @endif
                </div>
                <div style="min-width:0; flex:1;">
                    <p class="logout-user-name">{{ $displayName }}</p>
                    <p class="logout-user-email">{{ $authUser->email ?? '' }}</p>
                </div>
                <span class="logout-user-role">{{ $roleLabel }}</span>
            </div>

            <div class="logout-actions">
                <button onclick="closeLogoutModal()" class="btn-cancel">Batal</button>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-logout" style="width:100%;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     NAVBAR UTAMA
     ═══════════════════════════════════════════════════════════════ --}}
<header id="main-navbar" class="navbar-shell">
    <div class="navbar-inner">

        {{-- Page Title --}}
        <div class="page-title-area">
            <h1 class="page-title">@yield('page-title', $pageInfo['title'])</h1>
            @if (!empty($pageInfo['subtitle']))
                <p class="page-subtitle">@yield('page-subtitle', $pageInfo['subtitle'])</p>
            @endif
        </div>

        {{-- Actions --}}
        <div class="nav-actions">

            {{-- Superadmin: Pesan Masuk --}}
            @if ($isSuperadmin)
                <a href="{{ route('superadmin.kontak.index') }}"
                    class="nav-icon-btn {{ request()->routeIs('superadmin.kontak.*') ? 'active' : '' }}"
                    title="Pesan Masuk">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                    </svg>
                    @if ($kontakBelumDibaca > 0)
                        <span class="notif-dot"></span>
                    @endif
                </a>
                <div class="nav-divider"></div>
            @endif

            {{-- Amil / Muzakki: Notifikasi --}}
            @if ($isAmil || $isMuzakki)
                <div class="relative">
                    <button onclick="toggleNotifications()" id="notif-btn"
                        class="nav-icon-btn" title="Notifikasi">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($notifUnread > 0)
                            <span id="notif-badge" class="notif-dot"></span>
                        @endif
                    </button>

                    {{-- Notifications Dropdown --}}
                    <div id="notifications-dropdown" class="nav-dropdown notif-dropdown">
                        <div class="dropdown-header">
                            <div>
                                <p class="dropdown-header-title">Notifikasi</p>
                                <p class="dropdown-header-sub">
                                    @if ($isAmil) Tindakan yang perlu ditinjau
                                    @elseif($isMuzakki) Update transaksi (7 hari terakhir)
                                    @endif
                                </p>
                            </div>
                            @if ($notifUnread > 0)
                                <span id="notif-header-badge" class="badge-count">{{ $notifUnread }}</span>
                            @endif
                        </div>

                        <div id="notif-list" class="notif-list">
                            @forelse($notifItems as $notif)
                                <a href="{{ $notif['url'] }}" data-id="{{ $notif['id'] }}"
                                    onclick="markNotifRead(event, this)"
                                    class="notif-item">
                                    <div class="notif-icon-wrap type-{{ $notif['icon'] }}">
                                        @if ($notif['icon'] === 'transfer')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        @elseif($notif['icon'] === 'pickup')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        @elseif($notif['icon'] === 'check')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @elseif($notif['icon'] === 'x')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @elseif($notif['icon'] === 'truck')
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" />
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <p class="notif-title">{{ $notif['title'] }}</p>
                                        <p class="notif-body">{{ $notif['body'] }}</p>
                                        <p class="notif-time">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $notif['time'] }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="notif-empty">
                                    <div class="notif-empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="notif-empty-title">Tidak ada notifikasi</p>
                                    <p class="notif-empty-sub">
                                        @if ($isAmil) Semua transaksi sudah tertangani
                                        @elseif($isMuzakki) Belum ada update dalam 7 hari terakhir
                                        @endif
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @if ($notifItems->isNotEmpty())
                            <div class="dropdown-footer">
                                @if ($isAmil)
                                    <a href="{{ route('pemantauan-transaksi.index') }}" class="dropdown-footer-link">
                                        Lihat semua transaksi
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @elseif($isMuzakki)
                                    <a href="{{ route('transaksi-daring-muzakki.index') }}" class="dropdown-footer-link">
                                        Lihat riwayat transaksi
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="nav-divider"></div>
            @endif

            {{-- User Menu --}}
            <div class="relative">
                <button onclick="toggleUserMenu()" id="user-menu-btn" class="user-menu-btn">
                    <div class="user-avatar">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $displayName }}">
                        @else
                            <span class="user-avatar-initial">{{ $avatarInitial }}</span>
                        @endif
                    </div>
                    <div class="user-info">
                        <p class="user-name">{{ $displayName }}</p>
                        <p class="user-role">{{ $roleLabel }}</p>
                    </div>
                    <svg class="chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- User Dropdown --}}
                <div id="user-menu-dropdown" class="nav-dropdown user-dropdown">
                    <div class="user-dropdown-header">
                        <div class="user-dropdown-meta">
                            <div class="user-dropdown-avatar">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $displayName }}">
                                @else
                                    <span class="user-dropdown-avatar-initial">{{ $avatarInitial }}</span>
                                @endif
                            </div>
                            <div style="min-width:0; flex:1;">
                                <p class="user-dropdown-name">{{ $displayName }}</p>
                                <p class="user-dropdown-email">{{ $authUser->email ?? '' }}</p>
                            </div>
                        </div>
                        <span class="role-pill {{ $roleBadgeColor }}" style="margin-top:10px;">{{ $roleLabel }}</span>
                    </div>

                    <div class="menu-section">
                        @if ($isSuperadmin)
                            <a href="{{ route('superadmin.profil.show') }}"
                                class="menu-item {{ request()->routeIs('superadmin.profil.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="menu-item-label">Profil Saya</span>
                            </a>
                            <a href="{{ route('konfigurasi-global.show') }}"
                                class="menu-item {{ request()->routeIs('konfigurasi-global.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="menu-item-label">Konfigurasi Global</span>
                            </a>
                            <a href="{{ route('superadmin.kontak.index') }}"
                                class="menu-item {{ request()->routeIs('superadmin.kontak.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                                </svg>
                                <span class="menu-item-label">Pesan Masuk</span>
                                @if ($kontakBelumDibaca > 0)
                                    <span class="menu-badge">{{ $kontakBelumDibaca > 99 ? '99+' : $kontakBelumDibaca }}</span>
                                @endif
                            </a>
                        @endif

                        @if ($isAdminLembaga)
                            <a href="{{ route('admin-lembaga.profil.show') }}"
                                class="menu-item {{ request()->routeIs('admin-lembaga.profil.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="menu-item-label">Profil Saya</span>
                            </a>
                            <a href="{{ route('konfigurasi-integrasi.show') }}"
                                class="menu-item {{ request()->routeIs('konfigurasi-integrasi.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="menu-item-label">Konfigurasi Integrasi</span>
                            </a>
                        @endif

                        @if ($isAmil)
                            <a href="{{ route('profil.show') }}"
                                class="menu-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="menu-item-label">Profil Saya</span>
                            </a>
                        @endif

                        @if ($isMuzakki)
                            <a href="{{ route('muzakki.profil.show') }}"
                                class="menu-item {{ request()->routeIs('muzakki.profil.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="menu-item-label">Profil Saya</span>
                            </a>
                            <a href="{{ route('transaksi-daring-muzakki.create') }}"
                                class="menu-item {{ request()->routeIs('transaksi-daring-muzakki.create') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="menu-item-label">Bayar Zakat</span>
                            </a>
                        @endif
                    </div>

                    <div class="menu-section">
                        <button type="button" onclick="openLogoutModal()" class="menu-item menu-item-danger">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="menu-item-label">Keluar</span>
                        </button>
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
        document.getElementById('user-menu-dropdown')?.classList.remove('show');
        document.getElementById('user-menu-btn')?.classList.remove('open');
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
        card.style.transform = 'scale(0.95)';
        setTimeout(() => modal.classList.add('hidden'), 250);
    }

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeLogoutModal(); });

    function toggleNotifications() {
        const dd = document.getElementById('notifications-dropdown');
        const userDd = document.getElementById('user-menu-dropdown');
        const userBtn = document.getElementById('user-menu-btn');
        const isOpen = dd.classList.contains('show');
        userDd?.classList.remove('show');
        userBtn?.classList.remove('open');
        if (isOpen) {
            dd.classList.remove('show');
        } else {
            dd.classList.add('show');
        }
    }

    function toggleUserMenu() {
        const dd = document.getElementById('user-menu-dropdown');
        const btn = document.getElementById('user-menu-btn');
        const notifDd = document.getElementById('notifications-dropdown');
        const isOpen = dd.classList.contains('show');
        notifDd?.classList.remove('show');
        if (isOpen) {
            dd.classList.remove('show');
            btn.classList.remove('open');
        } else {
            dd.classList.add('show');
            btn.classList.add('open');
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
        setTimeout(() => { el.style.maxHeight = '0'; el.style.padding = '0'; }, 50);
        setTimeout(() => el.remove(), 350);
        document.getElementById('notif-badge')?.classList.add('hidden');
        document.getElementById('notif-header-badge')?.classList.add('hidden');
        fetch('{{ route('notif.mark-read') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ id }),
        }).catch(() => {});
        setTimeout(() => { window.location.href = url; }, 300);
    }

    document.addEventListener('click', function(e) {
        const notifBtn  = document.getElementById('notif-btn');
        const userBtn   = document.getElementById('user-menu-btn');
        const notifDrop = document.getElementById('notifications-dropdown');
        const userDrop  = document.getElementById('user-menu-dropdown');
        if (notifBtn && notifDrop && !notifBtn.contains(e.target) && !notifDrop.contains(e.target)) {
            notifDrop.classList.remove('show');
        }
        if (userBtn && userDrop && !userBtn.contains(e.target) && !userDrop.contains(e.target)) {
            userDrop.classList.remove('show');
            userBtn.classList.remove('open');
        }
    });
</script>