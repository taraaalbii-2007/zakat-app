{{-- resources/views/superadmin/partials/header.blade.php --}}

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

                <!-- Notifications -->
                <div class="relative">
                    <button onclick="toggleNotifications()"
                            class="p-2 rounded-lg text-neutral-600 hover:bg-neutral-100 hover:text-emerald-700 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-neutral-200 hidden">
                        <div class="p-4 border-b border-neutral-200">
                            <h3 class="font-semibold text-neutral-900">Notifikasi</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <a href="#" class="block p-4 hover:bg-neutral-50 border-b border-neutral-100 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-700 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900">Masjid Baru Terdaftar</p>
                                        <p class="text-xs text-neutral-600 mt-0.5">Ada masjid baru yang menunggu verifikasi</p>
                                        <p class="text-xs text-neutral-400 mt-1">Baru saja</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="p-4 border-t border-neutral-200">
                            <a href="#" class="text-sm font-medium text-emerald-700 hover:text-emerald-800 transition-colors">
                                Lihat semua notifikasi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="relative">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-100 transition-colors">

                        {{-- Avatar: foto superadmin atau initial --}}
                        @php $authUser = auth()->user(); @endphp
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-emerald-700 flex items-center justify-center flex-shrink-0">
                            @if($authUser->foto && Storage::disk('public')->exists($authUser->foto))
                                <img src="{{ Storage::url($authUser->foto) }}"
                                     alt="{{ $authUser->username }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-semibold text-sm">
                                    {{ strtoupper(substr($authUser->username ?? 'S', 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-medium text-neutral-900 leading-tight">
                                {{ $authUser->username ?? 'Super Admin' }}
                            </p>
                            <p class="text-xs text-neutral-500">Super Admin</p>
                        </div>

                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    <div id="user-menu-dropdown"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-neutral-200 hidden">

                        {{-- Header dropdown --}}
                        <div class="p-4 border-b border-neutral-200">
                            <p class="font-semibold text-neutral-900 text-sm truncate">
                                {{ $authUser->username ?? 'Super Admin' }}
                            </p>
                            <p class="text-xs text-neutral-500 truncate mt-0.5">{{ $authUser->email ?? '' }}</p>
                            <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                Super Admin
                            </span>
                        </div>

                        {{-- Menu items --}}
                        <div class="py-2">
                            <a href="{{ route('superadmin.profil.show') }}"
                               class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                      {{ request()->routeIs('superadmin.profil.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Profil Saya</span>
                            </a>

                            <a href="{{ route('konfigurasi-global.show') }}"
                               class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-emerald-700 transition-colors
                                      {{ request()->routeIs('konfigurasi-global.*') ? 'bg-emerald-50 text-emerald-700 font-medium' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Konfigurasi</span>
                            </a>
                        </div>

                        {{-- Logout --}}
                        <div class="py-2 border-t border-neutral-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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