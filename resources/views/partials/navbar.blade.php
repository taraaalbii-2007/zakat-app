<header class="sticky top-0 z-40 bg-white border-b border-neutral-200 shadow-soft">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left: Mobile Menu Button & Search -->
            <div class="flex items-center space-x-4">
               
                <!-- Page Title -->
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
                            class="p-2 rounded-lg text-neutral-600 hover:bg-neutral-100 hover:text-primary-600 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full"></span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown" 
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-modal border border-neutral-200 hidden animate-scale-in">
                        <div class="p-4 border-b border-neutral-200">
                            <h3 class="font-semibold text-neutral-900">Notifikasi</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <!-- Notification Item -->
                            <a href="#" class="block p-4 hover:bg-neutral-50 border-b border-neutral-100 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-nz flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-neutral-900">Zakat Baru</p>
                                        <p class="text-xs text-neutral-600 mt-0.5">Anda menerima zakat dari Ahmad</p>
                                        <p class="text-xs text-neutral-400 mt-1">2 jam yang lalu</p>
                                    </div>
                                </div>
                            </a>
                            <!-- More notifications... -->
                        </div>
                        <div class="p-4 border-t border-neutral-200">
                            <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                Lihat semua notifikasi
                            </a>
                        </div>
                    </div>
                </div>

        
                <!-- User Menu -->
                <div class="relative">
                    <button onclick="toggleUserMenu()" 
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-secondary flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </span>
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-neutral-600">{{ auth()->user()->role ?? 'Pengguna' }}</p>
                        </div>
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- User Menu Dropdown -->
                    <div id="user-menu-dropdown" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-modal border border-neutral-200 hidden animate-scale-in">
                        <div class="p-4 border-b border-neutral-200">
                            <p class="font-medium text-neutral-900">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-sm text-neutral-600 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                        </div>
                        <div class="py-2">
                            <a href="" 
                               class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Profil Saya</span>
                            </a>
                            <a href="" 
                               class="flex items-center space-x-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Pengaturan</span>
                            </a>
                        </div>
                        <div class="py-2 border-t border-neutral-200">
                            <form method="POST" action="">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-danger hover:bg-danger/10 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
        const dropdown = document.getElementById('notifications-dropdown');
        dropdown.classList.toggle('hidden');
        
        // Close other dropdowns
        document.getElementById('user-menu-dropdown')?.classList.add('hidden');
    }

    function toggleUserMenu() {
        const dropdown = document.getElementById('user-menu-dropdown');
        dropdown.classList.toggle('hidden');
        
        // Close other dropdowns
        document.getElementById('notifications-dropdown')?.classList.add('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const notificationsBtn = document.querySelector('[onclick="toggleNotifications()"]');
        const userMenuBtn = document.querySelector('[onclick="toggleUserMenu()"]');
        const notificationsDropdown = document.getElementById('notifications-dropdown');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');

        if (!notificationsBtn.contains(event.target) && !notificationsDropdown.contains(event.target)) {
            notificationsDropdown.classList.add('hidden');
        }
        
        if (!userMenuBtn.contains(event.target) && !userMenuDropdown.contains(event.target)) {
            userMenuDropdown.classList.add('hidden');
        }
    });
</script>