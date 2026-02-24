{{-- resources/views/partials/navbar.blade.php --}}

@php
    $authUser      = auth()->user();
    $isSuperadmin  = $authUser?->isSuperadmin();
    $isAdminMasjid = $authUser?->isAdminMasjid();
    $isAmil        = $authUser?->isAmil();
    $isMuzakki     = $authUser?->isMuzakki();

    // ════════════════════════════════════════════════════════
    // NOTIFIKASI DINAMIS BERDASARKAN PERAN
    // ════════════════════════════════════════════════════════

    $notifItems  = collect();
    $notifUnread = 0;

    /**
     * AMIL — Notifikasi transaksi baru dari muzakki
     * - Transaksi DARING  : status = pending, konfirmasi_status = menunggu_konfirmasi
     * - Transaksi DIJEMPUT: status = pending, status_penjemputan = menunggu
     *
     * FIX BUG: ->map() menghasilkan Collection of plain arrays (bukan Eloquent models).
     * ->merge()->sortByDesc('time') gagal dengan "getKey() on array" karena:
     *   1. 'time' adalah string diffForHumans, bukan nilai sortable
     *   2. Laravel internal memanggil getKey() saat mengira item adalah model
     * SOLUSI: tambah key 'timestamp' (integer unix), merge via toArray(), sortByDesc pakai 'timestamp'
     */
    if ($isAmil && isset($authUser->amil)) {
        $amilId   = $authUser->amil->id;
        $masjidId = $authUser->amil->masjid_id;

        // Transaksi DARING menunggu konfirmasi amil
        $daringPending = \App\Models\TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('metode_penerimaan', 'daring')
            ->where('status', 'pending')
            ->where('konfirmasi_status', 'menunggu_konfirmasi')
            ->with('jenisZakat')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'icon'      => 'transfer',
                'color'     => 'emerald',
                'title'     => 'Transaksi Daring Baru',
                'body'      => $t->muzakki_nama . ' — ' . ($t->jenisZakat->nama ?? 'Zakat') . ' Rp ' . number_format($t->jumlah, 0, ',', '.'),
                'time'      => $t->created_at->diffForHumans(),
                'timestamp' => $t->created_at->timestamp,
                'url'       => route('pemantauan-transaksi.show', $t->uuid),
                'no'        => $t->no_transaksi,
            ]);

        // Transaksi DIJEMPUT menunggu penjemputan
        $dijemputPending = \App\Models\TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('metode_penerimaan', 'dijemput')
            ->where('status', 'pending')
            ->where('status_penjemputan', 'menunggu')
            ->with('jenisZakat')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'icon'      => 'pickup',
                'color'     => 'amber',
                'title'     => 'Request Penjemputan',
                'body'      => $t->muzakki_nama . ' — Menunggu dijemput di ' . \Str::limit($t->muzakki_alamat ?? 'lokasi terdaftar', 35),
                'time'      => $t->created_at->diffForHumans(),
                'timestamp' => $t->created_at->timestamp,
                'url'       => route('pemantauan-transaksi.show', $t->uuid),
                'no'        => $t->no_transaksi,
            ]);

        // FIX: toArray() dulu agar tidak ada Eloquent object, lalu collect() ulang sebelum sortByDesc
        $notifItems  = collect(array_merge(
                $daringPending->values()->toArray(),
                $dijemputPending->values()->toArray()
            ))
            ->sortByDesc('timestamp')
            ->values();
        $notifUnread = $daringPending->count() + $dijemputPending->count();
    }

    /**
     * MUZAKKI — Notifikasi status transaksi yang diverifikasi/ditolak amil
     */
    if ($isMuzakki && isset($authUser->muzakki)) {
        $muzakkiId = $authUser->muzakki->id;

        $verifiedTransaksi = \App\Models\TransaksiPenerimaan::where('muzakki_id', $muzakkiId)
            ->whereIn('status', ['verified', 'rejected', 'dijemput'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->with('jenisZakat')
            ->latest('updated_at')
            ->take(10)
            ->get()
            ->map(function ($t) {
                $statusMap = [
                    'verified' => [
                        'icon'  => 'check',
                        'color' => 'emerald',
                        'title' => 'Transaksi Dikonfirmasi',
                        'body'  => 'Transaksi ' . $t->no_transaksi . ' telah diverifikasi oleh amil.',
                    ],
                    'rejected' => [
                        'icon'  => 'x',
                        'color' => 'red',
                        'title' => 'Transaksi Ditolak',
                        'body'  => 'Transaksi ' . $t->no_transaksi . ' ditolak. Silakan hubungi amil.',
                    ],
                    'dijemput' => [
                        'icon'  => 'truck',
                        'color' => 'blue',
                        'title' => 'Amil Dalam Perjalanan',
                        'body'  => 'Amil sedang menuju lokasi untuk ' . $t->no_transaksi . '.',
                    ],
                ];
                $info = $statusMap[$t->status] ?? [
                    'icon'  => 'info',
                    'color' => 'gray',
                    'title' => 'Update Transaksi',
                    'body'  => 'Ada pembaruan pada transaksi ' . $t->no_transaksi,
                ];
                return array_merge($info, [
                    'time'      => $t->updated_at->diffForHumans(),
                    'timestamp' => $t->updated_at->timestamp,
                    'url'       => route('transaksi-daring-muzakki.show', $t->uuid),
                    'no'        => $t->no_transaksi,
                ]);
            });

        // FIX: sama, toArray() dulu sebelum collect untuk jaga konsistensi
        $notifItems  = collect($verifiedTransaksi->values()->toArray())
            ->sortByDesc('timestamp')
            ->values();
        $notifUnread = $notifItems->count();
    }

    // Label & badge peran
    $roleLabel = match(true) {
        $isSuperadmin  => 'Super Admin',
        $isAdminMasjid => 'Admin Masjid',
        $isAmil        => 'Amil',
        $isMuzakki     => 'Muzakki',
        default        => 'Pengguna',
    };
    $badgeClass = match(true) {
        $isSuperadmin  => 'bg-indigo-100 text-indigo-700',
        $isAdminMasjid => 'bg-emerald-100 text-emerald-700',
        $isAmil        => 'bg-amber-100 text-amber-700',
        $isMuzakki     => 'bg-sky-100 text-sky-700',
        default        => 'bg-gray-100 text-gray-700',
    };

    // Avatar
    $avatarUrl = null;
    if ($isAdminMasjid && $authUser?->masjid?->admin_foto) {
        $avatarUrl = $authUser->masjid->admin_foto_url;
    } elseif (isset($authUser->foto) && $authUser->foto && \Storage::disk('public')->exists($authUser->foto)) {
        $avatarUrl = \Storage::url($authUser->foto);
    }
@endphp

<header class="sticky top-0 z-40 bg-white border-b border-neutral-200 shadow-soft">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Left: Page Title -->
            <div class="flex items-center space-x-4">
                <div class="hidden lg:block">
                    <h1 class="text-xl font-bold text-neutral-900">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <p class="text-sm text-neutral-600 mt-0.5">@yield('page-subtitle')</p>
                    @endif
                </div>
            </div>

            <!-- Right: Notifications & User Menu -->
            <div class="flex items-center space-x-3">

                {{-- ════════════════════════════════════════════════════════
                     NOTIFIKASI — Hanya tampil untuk Amil & Muzakki
                     ════════════════════════════════════════════════════════ --}}
                @if($isAmil || $isMuzakki)
                <div class="relative">
                    <button onclick="toggleNotifications()"
                            class="p-2 rounded-lg text-neutral-600 hover:bg-neutral-100 hover:text-emerald-700 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>

                        {{-- Badge jumlah notifikasi --}}
                        @if($notifUnread > 0)
                            <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                {{ $notifUnread > 9 ? '9+' : $notifUnread }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-neutral-200 hidden">

                        {{-- Header --}}
                        <div class="p-4 border-b border-neutral-200 flex items-center justify-between">
                            <h3 class="font-semibold text-neutral-900">Notifikasi</h3>
                            @if($notifUnread > 0)
                                <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-0.5 rounded-full">
                                    {{ $notifUnread }} baru
                                </span>
                            @endif
                        </div>

                        {{-- Konteks label peran --}}
                        <div class="px-4 py-2 bg-neutral-50 border-b border-neutral-100">
                            <p class="text-xs text-neutral-500">
                                @if($isAmil)
                                    Menampilkan transaksi yang perlu tindakan Anda
                                @elseif($isMuzakki)
                                    Menampilkan update transaksi Anda (7 hari terakhir)
                                @endif
                            </p>
                        </div>

                        {{-- List notifikasi --}}
                        <div class="max-h-96 overflow-y-auto divide-y divide-neutral-100">
                            @forelse($notifItems as $notif)
                                <a href="{{ $notif['url'] }}"
                                   class="flex items-start space-x-3 p-4 hover:bg-neutral-50 transition-colors">

                                    {{-- Icon berdasarkan tipe --}}
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $notif['color'] === 'emerald' ? 'bg-emerald-100' :
                                           ($notif['color'] === 'amber'   ? 'bg-amber-100'  :
                                           ($notif['color'] === 'red'     ? 'bg-red-100'    :
                                           ($notif['color'] === 'blue'    ? 'bg-blue-100'   : 'bg-gray-100'))) }}">

                                        @if($notif['icon'] === 'transfer')
                                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        @elseif($notif['icon'] === 'pickup')
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        @elseif($notif['icon'] === 'check')
                                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif($notif['icon'] === 'x')
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif($notif['icon'] === 'truck')
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-neutral-900 truncate">{{ $notif['title'] }}</p>
                                        <p class="text-xs text-neutral-600 mt-0.5 line-clamp-2">{{ $notif['body'] }}</p>
                                        <p class="text-xs text-neutral-400 mt-1">{{ $notif['time'] }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="p-8 text-center">
                                    <div class="w-12 h-12 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-neutral-700">Tidak ada notifikasi</p>
                                    <p class="text-xs text-neutral-400 mt-1">
                                        @if($isAmil)
                                            Belum ada transaksi yang perlu ditindaklanjuti
                                        @elseif($isMuzakki)
                                            Belum ada update transaksi dalam 7 hari terakhir
                                        @endif
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        <div class="p-3 border-t border-neutral-200">
                            @if($isAmil)
                                <a href="{{ route('pemantauan-transaksi.index') }}"
                                   class="flex items-center justify-center space-x-1.5 text-sm font-medium text-emerald-700 hover:text-emerald-800 transition-colors">
                                    <span>Lihat semua transaksi</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @elseif($isMuzakki)
                                <a href="{{ route('transaksi-daring-muzakki.index') }}"
                                   class="flex items-center justify-center space-x-1.5 text-sm font-medium text-emerald-700 hover:text-emerald-800 transition-colors">
                                    <span>Lihat riwayat transaksi</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
                @endif

                {{-- User Menu — semua peran --}}
                <div class="relative">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-100 transition-colors">

                        {{-- Avatar --}}
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-emerald-700 flex items-center justify-center flex-shrink-0">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}"
                                     alt="{{ $authUser->username }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-semibold text-sm">
                                    {{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-medium text-neutral-900 leading-tight">
                                {{ $isAdminMasjid && $authUser?->masjid?->admin_nama
                                    ? $authUser->masjid->admin_nama
                                    : ($authUser->username ?? 'Pengguna') }}
                            </p>
                            <p class="text-xs text-neutral-500">{{ $roleLabel }}</p>
                        </div>

                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    <div id="user-menu-dropdown"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-neutral-200 hidden">

                        {{-- Header dropdown --}}
                        <div class="p-4 border-b border-neutral-200">
                            <p class="font-semibold text-neutral-900 text-sm truncate">
                                {{ $isAdminMasjid && $authUser?->masjid?->admin_nama
                                    ? $authUser->masjid->admin_nama
                                    : ($authUser->username ?? 'Pengguna') }}
                            </p>
                            <p class="text-xs text-neutral-500 truncate mt-0.5">{{ $authUser->email ?? '' }}</p>
                            <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $roleLabel }}
                            </span>
                        </div>

                        {{-- Menu Items berdasarkan peran --}}
                        <div class="py-2">

                            {{-- SUPERADMIN --}}
                            @if($isSuperadmin)
                                <a href="{{ route('superadmin.profil.show') }}"
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('superadmin.profil.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('konfigurasi-global.show') }}"
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('konfigurasi-global.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Konfigurasi Global</span>
                                </a>
                            @endif

                            {{-- ADMIN MASJID --}}
                            @if($isAdminMasjid)
                                <a href="{{ route('admin-masjid.profil.show') }}"
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('admin-masjid.profil.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('konfigurasi-integrasi.show') }}"
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('konfigurasi-integrasi.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Konfigurasi Integrasi</span>
                                </a>
                            @endif

                            {{-- AMIL --}}
                            @if($isAmil)
                                <a href="{{ route('profil.show') }}"
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('profil.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>
                            @endif

                            {{-- MUZAKKI --}}
                            @if($isMuzakki)
                                <a href=""
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('muzakki.profil.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('transaksi-daring-muzakki.create') }}"
                                   class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                          {{ request()->routeIs('transaksi-daring-muzakki.create') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Bayar Zakat</span>
                                </a>
                            @endif

                        </div>

                        {{-- Logout --}}
                        <div class="py-2 border-t border-neutral-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

<script>
    function toggleNotifications() {
        document.getElementById('notifications-dropdown').classList.toggle('hidden');
        document.getElementById('user-menu-dropdown')?.classList.add('hidden');
    }
    function toggleUserMenu() {
        document.getElementById('user-menu-dropdown').classList.toggle('hidden');
        document.getElementById('notifications-dropdown')?.classList.add('hidden');
    }
    document.addEventListener('click', function (e) {
        const notifBtn  = document.querySelector('[onclick="toggleNotifications()"]');
        const userBtn   = document.querySelector('[onclick="toggleUserMenu()"]');
        const notifDrop = document.getElementById('notifications-dropdown');
        const userDrop  = document.getElementById('user-menu-dropdown');

        if (notifBtn && notifDrop && !notifBtn.contains(e.target) && !notifDrop.contains(e.target)) {
            notifDrop.classList.add('hidden');
        }
        if (userBtn && userDrop && !userBtn.contains(e.target) && !userDrop.contains(e.target)) {
            userDrop.classList.add('hidden');
        }
    });
</script>