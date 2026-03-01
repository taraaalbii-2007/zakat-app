{{-- resources/views/partials/navbar.blade.php --}}

@php
    $authUser      = auth()->user();
    $isSuperadmin  = $authUser?->isSuperadmin();
    $isAdminMasjid = $authUser?->isAdminMasjid();
    $isAmil        = $authUser?->isAmil();
    $isMuzakki     = $authUser?->isMuzakki();

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
    $notifItems  = collect();
    $notifUnread = 0;

    $notifSessionKey = 'notif_read_ids_' . $authUser->id;
    $readIds         = session($notifSessionKey, []);

    if ($isAmil && isset($authUser->amil)) {
        $masjidId = $authUser->amil->masjid_id;

        $daringPending = \App\Models\TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('metode_penerimaan', 'daring')->where('status', 'pending')
            ->where('konfirmasi_status', 'menunggu_konfirmasi')
            ->with('jenisZakat')->latest('created_at')->take(10)->get()
            ->map(fn($t) => [
                'icon' => 'transfer', 'color' => 'emerald', 'title' => 'Transaksi Daring Baru',
                'body' => $t->muzakki_nama . ' — ' . ($t->jenisZakat->nama ?? 'Zakat') . ' Rp ' . number_format($t->jumlah, 0, ',', '.'),
                'time' => $t->created_at->diffForHumans(), 'timestamp' => $t->created_at->timestamp,
                'url'  => route('transaksi-daring.show', $t->uuid), 'id' => $t->uuid,
            ]);

        $dijemputPending = \App\Models\TransaksiPenerimaan::where('masjid_id', $masjidId)
            ->where('metode_penerimaan', 'dijemput')->where('status', 'pending')
            ->where('status_penjemputan', 'menunggu')
            ->with('jenisZakat')->latest('created_at')->take(10)->get()
            ->map(fn($t) => [
                'icon' => 'pickup', 'color' => 'amber', 'title' => 'Request Penjemputan',
                'body' => $t->muzakki_nama . ' — Menunggu dijemput di ' . \Str::limit($t->muzakki_alamat ?? 'lokasi terdaftar', 35),
                'time' => $t->created_at->diffForHumans(), 'timestamp' => $t->created_at->timestamp,
                'url'  => route('transaksi-dijemput.show', $t->uuid), 'id' => $t->uuid,
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
                    'verified' => ['icon' => 'check', 'color' => 'emerald', 'title' => 'Transaksi Dikonfirmasi', 'body' => 'Transaksi ' . $t->no_transaksi . ' telah diverifikasi oleh amil.'],
                    'rejected' => ['icon' => 'x',     'color' => 'red',     'title' => 'Transaksi Ditolak',      'body' => 'Transaksi ' . $t->no_transaksi . ' ditolak. Silakan hubungi amil.'],
                    'dijemput' => ['icon' => 'truck',  'color' => 'blue',   'title' => 'Amil Dalam Perjalanan',  'body' => 'Amil sedang menuju lokasi untuk ' . $t->no_transaksi . '.'],
                ];
                $info = $statusMap[$t->status] ?? ['icon' => 'info', 'color' => 'gray', 'title' => 'Update Transaksi', 'body' => 'Ada pembaruan pada transaksi ' . $t->no_transaksi];
                return array_merge($info, [
                    'time' => $t->updated_at->diffForHumans(), 'timestamp' => $t->updated_at->timestamp,
                    'url'  => route('transaksi-daring-muzakki.show', $t->uuid), 'id' => $t->uuid,
                ]);
            });

        $notifItems  = collect($allMuzakkiNotif->values()->toArray())
            ->filter(fn($n) => !in_array($n['id'], $readIds))->sortByDesc('timestamp')->values();
        $notifUnread = $notifItems->count();
    }

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

    $avatarUrl = null;
    if ($isAdminMasjid && $authUser?->masjid?->admin_foto) {
        $avatarUrl = $authUser->masjid->admin_foto_url;
    } elseif (isset($authUser->foto) && $authUser->foto && \Storage::disk('public')->exists($authUser->foto)) {
        $avatarUrl = \Storage::url($authUser->foto);
    }
@endphp

{{-- ═══════════════════════════════════════════════════════════════
     LOGOUT CONFIRMATION MODAL
     ═══════════════════════════════════════════════════════════════ --}}
<div id="logout-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden" aria-modal="true" role="dialog">
    <div id="logout-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeLogoutModal()"></div>
    <div id="logout-card" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden scale-90 opacity-0 transition-all duration-300" style="border: 1px solid #eef0f2;">
        <div class="h-1.5 w-full" style="background: #2d6a2d;"></div>
        <div class="p-7">
            <div class="flex items-center justify-center mb-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: #fee2e2;">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-[17px] font-bold text-gray-900 text-center mb-1.5" style="letter-spacing: -0.02em;">Keluar dari Akun?</h2>
            <p class="text-sm text-gray-500 text-center leading-relaxed mb-7">Sesi Anda akan diakhiri. Pastikan semua pekerjaan sudah tersimpan sebelum keluar.</p>
            <div class="flex items-center space-x-3 px-4 py-3 rounded-xl mb-6" style="background: #f9fafb; border: 1px solid #f3f4f6;">
                <div class="w-9 h-9 rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center" style="background: #2d6a2d;">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $authUser->username }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-white text-sm font-bold">{{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $isAdminMasjid && $authUser?->masjid?->admin_nama ? $authUser->masjid->admin_nama : ($authUser->username ?? 'Pengguna') }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $authUser->email ?? '' }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0 {{ $badgeClass }}">{{ $roleLabel }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 active:scale-95" style="border: 1.5px solid #e5e7eb;">Batal</button>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 active:scale-95" style="background: #dc2626;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
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
<header class="sticky top-0 z-40" style="background: #ffffff; border-bottom: 1px solid #eef0f2;">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between" style="height: 60px;">

            <!-- Left: Page Title -->
            <div class="flex items-center space-x-4">
                <div class="hidden lg:block">
                    <h1 class="text-[17px] font-bold text-gray-900 leading-tight" style="letter-spacing: -0.025em;">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <p class="text-xs text-gray-400 mt-0.5">@yield('page-subtitle')</p>
                    @endif
                </div>
            </div>

            <!-- Right: Actions & User -->
            <div class="flex items-center space-x-1.5">

                {{-- ════════════════════════════════════════════════════════
                     IKON PESAN MASUK — Khusus Superadmin
                     Link langsung ke halaman inbox kontak
                     ════════════════════════════════════════════════════════ --}}
                @if($isSuperadmin)
                    <a href="{{ route('superadmin.kontak.index') }}"
                       title="Pesan Masuk dari Pengguna"
                       class="relative flex items-center justify-center w-9 h-9 rounded-xl transition-all duration-200 text-gray-500 hover:text-[#1f5c1f] hover:bg-[#f0f7f0] {{ request()->routeIs('superadmin.kontak.*') ? 'bg-[#e8f5e8] text-[#1f5c1f]' : '' }}">
                        {{-- Ikon chat bubble --}}
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                        </svg>
                        {{-- Badge merah jumlah pesan belum dibaca --}}
                        @if($kontakBelumDibaca > 0)
                            <span class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-1 text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none" style="background: #ef4444;">
                                {{ $kontakBelumDibaca > 99 ? '99+' : $kontakBelumDibaca }}
                            </span>
                        @endif
                    </a>
                    <div class="w-px h-5 mx-0.5" style="background: #eef0f2;"></div>
                @endif

                {{-- ════════════════════════════════════════════════════════
                     NOTIFIKASI — Hanya tampil untuk Amil & Muzakki
                     ════════════════════════════════════════════════════════ --}}
                @if($isAmil || $isMuzakki)
                <div class="relative">
                    <button onclick="toggleNotifications()" id="notif-btn"
                            class="relative flex items-center justify-center w-9 h-9 rounded-xl transition-all duration-200 text-gray-500 hover:text-[#1f5c1f] hover:bg-[#f0f7f0]"
                            title="Notifikasi">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($notifUnread > 0)
                            <span id="notif-badge" class="absolute -top-0.5 -right-0.5 min-w-[17px] h-[17px] px-1 text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none" style="background: #ef4444">
                                {{ $notifUnread > 9 ? '9+' : $notifUnread }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown" class="absolute right-0 mt-2.5 w-[340px] bg-white rounded-2xl hidden overflow-hidden" style="border: 1px solid #eef0f2">
                        <div class="px-5 py-4" style="border-bottom: 1px solid #f3f4f6;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: #e8f5e8;">
                                        <svg class="w-4 h-4 text-[#2d6a2d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-[13px] font-bold text-gray-900">Notifikasi</h3>
                                        <p class="text-[10px] text-gray-400 leading-none mt-0.5">@if($isAmil) Tindakan diperlukan @elseif($isMuzakki) Update transaksi (7 hari) @endif</p>
                                    </div>
                                </div>
                                @if($notifUnread > 0)
                                    <span id="notif-header-badge" class="text-[10px] font-bold px-2.5 py-1 rounded-full" style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca;">{{ $notifUnread }} baru</span>
                                @else
                                    <span id="notif-header-badge" class="hidden text-[10px] font-bold px-2.5 py-1 rounded-full" style="background: #fef2f2; color: #ef4444; border: 1px solid #fecaca;"></span>
                                @endif
                            </div>
                        </div>

                        <div id="notif-list" class="max-h-[380px] overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #e5e7eb transparent;">
                            @forelse($notifItems as $notif)
                                <a href="{{ $notif['url'] }}" data-id="{{ $notif['id'] }}" onclick="markNotifRead(event, this)"
                                   class="notif-item flex items-start space-x-3 px-5 py-3.5 hover:bg-[#f9fbf9] transition-all duration-200 group" style="border-bottom: 1px solid #f9fafb;">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 {{ $notif['color'] === 'emerald' ? 'bg-emerald-50' : ($notif['color'] === 'amber' ? 'bg-amber-50' : ($notif['color'] === 'red' ? 'bg-red-50' : ($notif['color'] === 'blue' ? 'bg-blue-50' : 'bg-gray-50'))) }}">
                                        @if($notif['icon'] === 'transfer')
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        @elseif($notif['icon'] === 'pickup')
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @elseif($notif['icon'] === 'check')
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($notif['icon'] === 'x')
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($notif['icon'] === 'truck')
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[12.5px] font-semibold text-gray-900 truncate group-hover:text-[#1f5c1f] transition-colors">{{ $notif['title'] }}</p>
                                        <p class="text-[11.5px] text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">{{ $notif['body'] }}</p>
                                        <p class="text-[10.5px] text-gray-300 mt-1.5 flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $notif['time'] }}</span>
                                        </p>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0 mt-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @empty
                                <div id="notif-empty" class="py-12 text-center px-5">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: #f0f7f0;">
                                        <svg class="w-7 h-7 text-[#4a9b4a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-[13px] font-semibold text-gray-700">Semua beres!</p>
                                    <p class="text-[11.5px] text-gray-400 mt-1 leading-relaxed">@if($isAmil) Belum ada transaksi yang perlu ditindaklanjuti @elseif($isMuzakki) Belum ada update transaksi dalam 7 hari terakhir @endif</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="px-5 py-3" style="border-top: 1px solid #f3f4f6; background: #fafafa;">
                            @if($isAmil)
                                <a href="{{ route('pemantauan-transaksi.index') }}" class="flex items-center justify-center space-x-1.5 text-[12px] font-semibold text-[#1f5c1f] hover:text-[#2d6a2d] transition-colors py-1">
                                    <span>Lihat semua transaksi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @elseif($isMuzakki)
                                <a href="{{ route('transaksi-daring-muzakki.index') }}" class="flex items-center justify-center space-x-1.5 text-[12px] font-semibold text-[#1f5c1f] hover:text-[#2d6a2d] transition-colors py-1">
                                    <span>Lihat riwayat transaksi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Divider --}}
                <div class="w-px h-6 mx-1" style="background: #eef0f2;"></div>

                {{-- USER MENU --}}
                <div class="relative">
                    <button onclick="toggleUserMenu()" id="user-menu-btn"
                            class="flex items-center space-x-2.5 pl-1.5 pr-3 py-1.5 rounded-xl hover:bg-[#f5faf5] transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center" style="background: #2d6a2d;">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $authUser->username }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-[12.5px] font-semibold text-gray-800 leading-tight">{{ $isAdminMasjid && $authUser?->masjid?->admin_nama ? $authUser->masjid->admin_nama : ($authUser->username ?? 'Pengguna') }}</p>
                            <p class="text-[10.5px] text-gray-400 leading-tight mt-0.5">{{ $roleLabel }}</p>
                        </div>
                        <svg class="hidden lg:block w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 transition-all duration-200 group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-xl hidden overflow-hidden" style="border: 1px solid #ddeedd">
                        <div class="px-4 py-3" style="border-bottom: 1px solid #f0f7f0; background: #fafcfa;">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center" style="background: #2d6a2d;">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="{{ $authUser->username }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($authUser->username ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <p class="text-[12px] font-bold text-gray-900 truncate leading-tight">{{ $isAdminMasjid && $authUser?->masjid?->admin_nama ? $authUser->masjid->admin_nama : ($authUser->username ?? 'Pengguna') }}</p>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9.5px] font-semibold {{ $badgeClass }}">{{ $roleLabel }}</span>
                                    </div>
                                    <p class="text-[10.5px] text-gray-400 truncate leading-tight mt-0.5">{{ $authUser->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="py-1">
                            @if($isSuperadmin)
                                <a href="{{ route('superadmin.profil.show') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('superadmin.profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('konfigurasi-global.show') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('konfigurasi-global.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <span>Konfigurasi Global</span>
                                </a>
                                {{-- Shortcut Pesan Masuk di dropdown juga --}}
                                <a href="{{ route('superadmin.kontak.index') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('superadmin.kontak.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/></svg>
                                    </div>
                                    <span class="flex-1">Pesan Masuk</span>
                                    @if($kontakBelumDibaca > 0)
                                        <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[9px] font-bold text-white rounded-full" style="background: #ef4444;">
                                            {{ $kontakBelumDibaca > 99 ? '99+' : $kontakBelumDibaca }}
                                        </span>
                                    @endif
                                </a>
                            @endif

                            @if($isAdminMasjid)
                                <a href="{{ route('admin-masjid.profil.show') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('admin-masjid.profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;"><svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('konfigurasi-integrasi.show') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('konfigurasi-integrasi.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;"><svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                    <span>Konfigurasi Integrasi</span>
                                </a>
                            @endif

                            @if($isAmil)
                                <a href="{{ route('profil.show') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;"><svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                    <span>Profil Saya</span>
                                </a>
                            @endif

                            @if($isMuzakki)
                                <a href="" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('muzakki.profil.*') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;"><svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                    <span>Profil Saya</span>
                                </a>
                                <a href="{{ route('transaksi-daring-muzakki.create') }}" class="flex items-center space-x-3 px-4 py-2 text-[12.5px] text-gray-700 hover:bg-[#f5faf5] hover:text-[#1f5c1f] transition-colors {{ request()->routeIs('transaksi-daring-muzakki.create') ? 'bg-[#f0f7f0] text-[#1f5c1f] font-semibold' : '' }}">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #f3f4f6;"><svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                                    <span>Bayar Zakat</span>
                                </a>
                            @endif
                        </div>

                        <div class="px-3 py-2" style="border-top: 1px solid #f0f7f0;">
                            <button type="button" onclick="openLogoutModal()" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-[12.5px] font-medium text-red-600 hover:bg-red-50 transition-all duration-200 group">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 bg-red-50 group-hover:bg-red-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
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
        requestAnimationFrame(() => { backdrop.style.opacity = '1'; card.style.opacity = '1'; card.style.transform = 'scale(1)'; });
    }
    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const backdrop = document.getElementById('logout-backdrop');
        const card = document.getElementById('logout-card');
        backdrop.style.opacity = '0'; card.style.opacity = '0'; card.style.transform = 'scale(0.9)';
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeLogoutModal(); });

    function toggleNotifications() {
        const dropdown = document.getElementById('notifications-dropdown');
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden');
        document.getElementById('user-menu-dropdown')?.classList.add('hidden');
        if (isHidden) {
            dropdown.style.opacity = '0'; dropdown.style.transform = 'scale(0.95) translateY(-4px)'; dropdown.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
            requestAnimationFrame(() => { dropdown.style.opacity = '1'; dropdown.style.transform = 'scale(1) translateY(0)'; });
        }
    }
    function toggleUserMenu() {
        const dropdown = document.getElementById('user-menu-dropdown');
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden');
        document.getElementById('notifications-dropdown')?.classList.add('hidden');
        if (isHidden) {
            dropdown.style.opacity = '0'; dropdown.style.transform = 'scale(0.95) translateY(-4px)'; dropdown.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
            requestAnimationFrame(() => { dropdown.style.opacity = '1'; dropdown.style.transform = 'scale(1) translateY(0)'; });
        }
    }
    function markNotifRead(event, el) {
        event.preventDefault();
        const id = el.dataset.id;
        const url = el.getAttribute('href');
        el.style.transition = 'opacity 0.2s, max-height 0.3s, padding 0.3s';
        el.style.overflow = 'hidden'; el.style.maxHeight = el.offsetHeight + 'px'; el.style.opacity = '0';
        setTimeout(() => { el.style.maxHeight = '0'; el.style.padding = '0'; }, 50);
        setTimeout(() => el.remove(), 350);
        const badge = document.getElementById('notif-badge');
        const headerBadge = document.getElementById('notif-header-badge');
        if (badge) {
            let count = Math.max(0, (parseInt(badge.textContent) || 0) - 1);
            if (count <= 0) { badge.classList.add('hidden'); if (headerBadge) headerBadge.classList.add('hidden'); }
            else { badge.textContent = count > 9 ? '9+' : count; if (headerBadge) headerBadge.textContent = count + ' baru'; }
        }
        fetch('{{ route("notif.mark-read") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ id: id }),
        }).catch(() => {});
        setTimeout(() => { window.location.href = url; }, 300);
    }
    document.addEventListener('click', function (e) {
        const notifBtn = document.getElementById('notif-btn');
        const userBtn = document.getElementById('user-menu-btn');
        const notifDrop = document.getElementById('notifications-dropdown');
        const userDrop = document.getElementById('user-menu-dropdown');
        if (notifBtn && notifDrop && !notifBtn.contains(e.target) && !notifDrop.contains(e.target)) notifDrop.classList.add('hidden');
        if (userBtn && userDrop && !userBtn.contains(e.target) && !userDrop.contains(e.target)) userDrop.classList.add('hidden');
    });
</script>