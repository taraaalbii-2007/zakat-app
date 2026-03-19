@extends('layouts.app')

@section('title', 'Data Muzaki Lembaga')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ===== TABEL UTAMA ===== --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Data Muzaki per Amil</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ number_format($summary['total_amil']) }} Amil</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('search') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('search') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            {{-- Search inline (client-side filter) --}}
                            <div id="search-form" class="{{ request('search') ? '' : 'hidden' }}">
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" id="cari-amil" placeholder="Cari nama amil..."
                                            oninput="filterAmil(this.value)"
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    <button type="button" onclick="toggleSearch()"
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('admin-lembaga.muzaki.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Amil</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti"     {{ request('status') == 'cuti'     ? 'selected' : '' }}>Cuti</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    @if (request()->hasAny(['status']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('admin-lembaga.muzaki.index') }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @if($amils->count() > 0)

                {{-- Desktop View --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amil</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Muzaki</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tbody-amil">
                            @foreach($amils as $amil)
                                <tr class="amil-row hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                    data-target="detail-{{ $amil->id }}"
                                    data-amil-id="{{ $amil->id }}"
                                    data-nama="{{ strtolower($amil->nama_lengkap) }} {{ strtolower($amil->kode_amil) }}">
                                    <td class="px-4 py-4">
                                        <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon amil-chevron"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($amil->foto)
                                                <img src="{{ asset('storage/' . $amil->foto) }}" alt="{{ $amil->nama_lengkap }}"
                                                    class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-sm font-semibold text-primary">{{ strtoupper(substr($amil->nama_lengkap, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $amil->nama_lengkap }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $amil->kode_amil }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($amil->status === 'aktif')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>Aktif
                                            </span>
                                        @elseif($amil->status === 'cuti')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1"></span>Cuti
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1"></span>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ number_format($amil->jumlah_muzakki) }} Muzaki
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm text-gray-700 font-medium">{{ number_format($amil->jumlah_transaksi) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($amil->total_nominal ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>

                                <tr id="detail-{{ $amil->id }}" class="hidden amil-content-row expandable-content">
                                    <td colspan="6" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-1 h-5 bg-primary rounded-full"></div>
                                                        <h3 class="text-sm font-semibold text-gray-800">
                                                            Muzaki diinput oleh <span class="text-primary">{{ $amil->nama_lengkap }}</span>
                                                        </h3>
                                                    </div>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                            </svg>
                                                        </div>
                                                        <input type="search" placeholder="Cari muzaki..."
                                                            oninput="searchMuzaki({{ $amil->id }}, this.value)"
                                                            onclick="event.stopPropagation()"
                                                            class="pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary w-48 transition-all">
                                                    </div>
                                                </div>
                                                <div id="muzaki-container-{{ $amil->id }}">
                                                    <div class="text-center py-8 text-sm text-gray-400">
                                                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                        </svg>
                                                        Memuat data muzaki...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View --}}
                <div class="md:hidden divide-y divide-gray-200" id="tbody-amil-mobile">
                    @foreach($amils as $amil)
                        <div class="expandable-card amil-row-mobile"
                            data-nama="{{ strtolower($amil->nama_lengkap) }} {{ strtolower($amil->kode_amil) }}"
                            data-amil-id="{{ $amil->id }}">

                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $amil->id }}"
                                data-amil-id="{{ $amil->id }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0 gap-3">
                                        @if($amil->foto)
                                            <img src="{{ asset('storage/' . $amil->foto) }}" alt="{{ $amil->nama_lengkap }}"
                                                class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-semibold text-primary">{{ strtoupper(substr($amil->nama_lengkap, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $amil->nama_lengkap }}</h3>
                                            <div class="flex items-center flex-wrap gap-1.5 mt-1">
                                                <span class="text-[10px] text-gray-400">{{ $amil->kode_amil }}</span>
                                                <span class="text-[10px] text-gray-300">•</span>
                                                @if($amil->status === 'aktif')
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800">
                                                        <span class="w-1 h-1 rounded-full bg-green-500 mr-0.5"></span>Aktif
                                                    </span>
                                                @elseif($amil->status === 'cuti')
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-yellow-100 text-yellow-800">
                                                        <span class="w-1 h-1 rounded-full bg-yellow-500 mr-0.5"></span>Cuti
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800">
                                                        <span class="w-1 h-1 rounded-full bg-red-500 mr-0.5"></span>Nonaktif
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-800">
                                                    {{ number_format($amil->jumlah_muzakki) }} Muzaki
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center ml-2">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile amil-chevron-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 mt-3 pl-12">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">Transaksi</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ number_format($amil->jumlah_transaksi) }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">Total Nominal</p>
                                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($amil->total_nominal ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div id="detail-mobile-{{ $amil->id }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1 h-4 bg-primary rounded-full"></div>
                                            <h4 class="text-xs font-semibold text-gray-800">Daftar Muzaki</h4>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                            </div>
                                            <input type="search" placeholder="Cari muzaki..."
                                                oninput="searchMuzaki({{ $amil->id }}, this.value)"
                                                onclick="event.stopPropagation()"
                                                class="pl-7 pr-3 py-1 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary w-36 transition-all">
                                        </div>
                                    </div>
                                    <div id="muzaki-container-mobile-{{ $amil->id }}">
                                        <div class="text-center py-6 text-xs text-gray-400">
                                            <svg class="w-6 h-6 mx-auto mb-1.5 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Memuat data muzaki...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Amil</h3>
                    <p class="text-sm text-gray-500">Belum ada data amil yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const loadedAmil = {};
        const loadedAmilMobile = {};
        let searchTimers = {};

        // ============================================================
        // DESKTOP: EXPANDABLE ROWS
        // ============================================================
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('input')) return;
                const targetId  = this.getAttribute('data-target');
                const targetRow = document.getElementById(targetId);
                const icon      = this.querySelector('.expand-icon');
                const amilId    = this.getAttribute('data-amil-id');
                const isHidden  = targetRow.classList.contains('hidden');
                if (isHidden) {
                    targetRow.classList.remove('hidden');
                    icon.classList.add('rotate-90');
                    if (!loadedAmil[amilId]) fetchMuzaki(amilId, '');
                } else {
                    targetRow.classList.add('hidden');
                    icon.classList.remove('rotate-90');
                }
            });
        });

        // ============================================================
        // MOBILE: EXPANDABLE CARDS
        // ============================================================
        document.querySelectorAll('.expandable-row-mobile').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('input')) return;
                const targetId      = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon          = this.querySelector('.expand-icon-mobile');
                const amilId        = this.getAttribute('data-amil-id');
                const isHidden      = targetContent.classList.contains('hidden');
                if (isHidden) {
                    targetContent.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                    if (!loadedAmilMobile[amilId]) fetchMuzakiMobile(amilId, '');
                } else {
                    targetContent.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        });

        // ============================================================
        // FETCH MUZAKI — DESKTOP
        // ============================================================
        function fetchMuzaki(amilId, search = '', page = 1) {
            const container = document.getElementById(`muzaki-container-${amilId}`);
            container.innerHTML = renderLoading();
            const params = new URLSearchParams({ search, page });
            fetch(`/admin-lembaga-muzaki/amil/${amilId}/muzaki?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error('Gagal memuat data');
                loadedAmil[amilId] = true;
                container.innerHTML = renderMuzakiTable(data.muzakkis, amilId, false);
            })
            .catch(() => { container.innerHTML = renderError(amilId); });
        }

        // ============================================================
        // FETCH MUZAKI — MOBILE
        // ============================================================
        function fetchMuzakiMobile(amilId, search = '', page = 1) {
            const container = document.getElementById(`muzaki-container-mobile-${amilId}`);
            container.innerHTML = renderLoadingSmall();
            const params = new URLSearchParams({ search, page });
            fetch(`/admin-lembaga-muzaki/amil/${amilId}/muzaki?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error('Gagal memuat data');
                loadedAmilMobile[amilId] = true;
                container.innerHTML = renderMuzakiCards(data.muzakkis, amilId);
            })
            .catch(() => { container.innerHTML = renderError(amilId); });
        }

        // ============================================================
        // SEARCH MUZAKI (debounce)
        // ============================================================
        function searchMuzaki(amilId, keyword) {
            clearTimeout(searchTimers[amilId]);
            searchTimers[amilId] = setTimeout(() => {
                const mobileContainer  = document.getElementById(`muzaki-container-mobile-${amilId}`);
                const desktopContainer = document.getElementById(`muzaki-container-${amilId}`);
                if (desktopContainer && document.getElementById(`detail-${amilId}`) &&
                    !document.getElementById(`detail-${amilId}`).classList.contains('hidden')) {
                    fetchMuzaki(amilId, keyword, 1);
                }
                if (mobileContainer && document.getElementById(`detail-mobile-${amilId}`) &&
                    !document.getElementById(`detail-mobile-${amilId}`).classList.contains('hidden')) {
                    fetchMuzakiMobile(amilId, keyword, 1);
                }
            }, 400);
        }

        // ============================================================
        // FILTER AMIL — client-side
        // ============================================================
        function filterAmil(keyword) {
            const q = keyword.toLowerCase().trim();
            document.querySelectorAll('.amil-row').forEach(row => {
                const nama = row.getAttribute('data-nama') || '';
                const show = !q || nama.includes(q);
                row.style.display = show ? '' : 'none';
                const next = row.nextElementSibling;
                if (next && next.classList.contains('amil-content-row')) {
                    next.style.display = show ? '' : 'none';
                }
            });
            document.querySelectorAll('.amil-row-mobile').forEach(card => {
                const nama = card.getAttribute('data-nama') || '';
                card.style.display = (!q || nama.includes(q)) ? '' : 'none';
            });
        }

        // ============================================================
        // SEARCH TOGGLE
        // ============================================================
        function toggleSearch() {
            const searchButton    = document.getElementById('search-button');
            const searchForm      = document.getElementById('search-form');
            const searchInput     = document.getElementById('cari-amil');
            const searchContainer = document.getElementById('search-container');
            if (searchForm.classList.contains('hidden')) {
                searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput && searchInput.focus(), 50);
            } else {
                if (searchInput) searchInput.value = '';
                filterAmil('');
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        // ============================================================
        // TOGGLE FILTER PANEL
        // ============================================================
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // ── ESC menutup search ────────────────────────────────────────────
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const searchForm      = document.getElementById('search-form');
                const searchButton    = document.getElementById('search-button');
                const searchContainer = document.getElementById('search-container');
                const searchInput     = document.getElementById('cari-amil');
                if (!searchForm.classList.contains('hidden')) {
                    if (searchInput) searchInput.value = '';
                    filterAmil('');
                    searchForm.classList.add('hidden');
                    searchButton.classList.remove('hidden');
                    searchContainer.style.minWidth = 'auto';
                }
            }
        });

        // ============================================================
        // RENDER: DESKTOP TABLE
        // ============================================================
        function renderMuzakiTable(pagination, amilId) {
            const data = pagination.data;
            if (!data || data.length === 0) {
                return `<div class="text-center py-10 bg-white rounded-xl border border-gray-100">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <p class="text-sm text-gray-400">Belum ada muzaki yang diinput</p>
                </div>`;
            }
            const rows = data.map(m => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-blue-700">${(m.muzakki_nama || '-').charAt(0).toUpperCase()}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${escHtml(m.muzakki_nama || '-')}</div>
                                ${m.muzakki_email ? `<div class="text-xs text-gray-400">${escHtml(m.muzakki_email)}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="text-sm text-gray-600">${escHtml(m.muzakki_telepon || '-')}</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-xs text-gray-500">${escHtml(m.muzakki_alamat ? m.muzakki_alamat.substring(0,40) + (m.muzakki_alamat.length > 40 ? '...' : '') : '-')}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">${m.total_transaksi}x</span>
                    </td>
                    <td class="px-4 py-3 text-right hidden lg:table-cell">
                        <span class="text-sm font-semibold text-gray-900">Rp ${formatRupiah(m.total_nominal || 0)}</span>
                    </td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">
                        <span class="text-xs text-gray-500">${m.transaksi_terakhir ? formatDate(m.transaksi_terakhir) : '-'}</span>
                    </td>
                </tr>
            `).join('');
            return `<div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Nama Muzaki</th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Telepon</th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Alamat</th>
                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Total Nominal</th>
                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">${rows}</tbody>
                </table>
            </div>
            ${renderPagination(pagination, amilId, false)}`;
        }

        // ============================================================
        // RENDER: MOBILE CARDS
        // ============================================================
        function renderMuzakiCards(pagination, amilId) {
            const data = pagination.data;
            if (!data || data.length === 0) {
                return `<div class="text-center py-6 text-xs text-gray-400">Belum ada muzaki yang diinput</div>`;
            }
            const cards = data.map(m => `
                <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-blue-700">${(m.muzakki_nama || '-').charAt(0).toUpperCase()}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-medium text-gray-900 truncate">${escHtml(m.muzakki_nama || '-')}</div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] text-gray-400">${escHtml(m.muzakki_telepon || '-')}</span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-purple-100 text-purple-800">${m.total_transaksi}x</div>
                        <div class="text-[10px] text-gray-500 mt-0.5">Rp ${formatRupiah(m.total_nominal || 0)}</div>
                    </div>
                </div>
            `).join('');
            return `<div class="bg-white rounded-xl border border-gray-200 overflow-hidden px-3 py-1">${cards}</div>
            ${renderPagination(pagination, amilId, true)}`;
        }

        // ============================================================
        // RENDER: PAGINATION
        // ============================================================
        function renderPagination(pagination, amilId, isMobile) {
            if (pagination.last_page <= 1) return '';
            const currentPage = pagination.current_page;
            const lastPage    = pagination.last_page;
            const from        = pagination.from;
            const to          = pagination.to;
            const total       = pagination.total;
            const fn          = isMobile ? `fetchMuzakiMobile` : `fetchMuzaki`;
            let pages = '';
            for (let p = 1; p <= lastPage; p++) {
                if (p === currentPage) {
                    pages += `<span class="px-3 py-1 text-xs font-semibold text-white bg-primary rounded-md">${p}</span>`;
                } else {
                    pages += `<button onclick="${fn}(${amilId},'',${p})" class="px-3 py-1 text-xs text-gray-600 hover:bg-gray-100 rounded-md transition-colors">${p}</button>`;
                }
            }
            return `<div class="flex items-center justify-between mt-3">
                <p class="text-xs text-gray-500">Menampilkan ${from}–${to} dari ${total} muzaki</p>
                <div class="flex items-center gap-1">${pages}</div>
            </div>`;
        }

        // ============================================================
        // RENDER: LOADING & ERROR
        // ============================================================
        function renderLoading() {
            return `<div class="text-center py-8">
                <svg class="w-6 h-6 mx-auto animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-xs text-gray-400 mt-2">Memuat data muzaki...</p>
            </div>`;
        }
        function renderLoadingSmall() {
            return `<div class="text-center py-4">
                <svg class="w-5 h-5 mx-auto animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>`;
        }
        function renderError(amilId) {
            return `<div class="text-center py-8 text-sm text-red-400">
                Gagal memuat data.
                <button onclick="fetchMuzaki(${amilId},'')" class="underline ml-1">Coba lagi</button>
            </div>`;
        }

        // ============================================================
        // HELPERS
        // ============================================================
        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function formatRupiah(num) {
            return Number(num).toLocaleString('id-ID');
        }
        function formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }
    </script>
@endpush