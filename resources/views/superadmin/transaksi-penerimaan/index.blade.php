@extends('layouts.app')

@section('title', 'Transaksi Penerimaan Semua Lembaga')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- Toggle button untuk statistik di mode mobile --}}
        <div class="sm:hidden">
            <button type="button" onclick="toggleStatsMobile()"
                class="w-full flex items-center justify-between px-4 py-3 bg-white rounded-xl border border-gray-100 shadow-sm text-sm font-medium text-gray-700 transition-all hover:bg-gray-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Lihat Statistik</span>
                </div>
                <svg id="stats-chevron-mobile" class="w-5 h-5 text-gray-400 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        {{-- Stats Cards - Mobile Version (hidden by default, toggleable) --}}
        <div id="stats-mobile-panel" class="hidden space-y-3">
            {{-- Single column grid untuk mobile --}}
            <div class="grid grid-cols-1 gap-3">
                <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Transaksi</p>
                                <p class="text-xl font-bold text-gray-900" id="stat-total-transaksi-mobile">
                                    {{ number_format($totalTransaksi) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Terverifikasi</p>
                                <p class="text-xl font-bold text-gray-900" id="stat-total-verified-mobile">
                                    {{ number_format($totalVerified) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pending</p>
                                <p class="text-xl font-bold text-gray-900" id="stat-total-pending-mobile">
                                    {{ number_format($totalPending) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Nominal</p>
                                <p class="text-lg font-bold text-gray-900" id="stat-total-nominal-mobile">Rp
                                    {{ number_format($totalNominal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards - Desktop Version (always visible) --}}
        <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-slide-up">
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Total Transaksi</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-transaksi">
                            {{ number_format($totalTransaksi) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Terverifikasi</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-verified">
                            {{ number_format($totalVerified) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Pending</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-pending">
                            {{ number_format($totalPending) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Total Nominal</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-nominal">Rp
                            {{ number_format($totalNominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Transaksi Penerimaan Semua Lembaga
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1" id="total-info">
                            Total: {{ $totalTransaksi }} Transaksi dari {{ $lembagas->count() }} Lembaga
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()" id="filter-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="#" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}" onsubmit="applyFilters(); return false;">
                                @foreach (['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}"
                                            value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            id="search-input" placeholder="Cari nama masjid / muzakki..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']))
                                        <button type="button" onclick="resetFilters()"
                                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                                            Reset
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="#" id="filter-form" onsubmit="applyFilters(); return false;">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lembaga</label>
                            <select name="lembaga_id" id="filter-lembaga"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                                <option value="">Semua Lembaga</option>
                                @foreach ($lembagas as $lembaga)
                                    <option value="{{ $lembaga->id }}"
                                        {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                        {{ $lembaga->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="filter-status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        @if (isset($jenisZakatList))
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                                <select name="jenis_zakat_id" id="filter-jenis_zakat"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                    onchange="applyFilters()">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisZakatList as $jenis)
                                        <option value="{{ $jenis->id }}"
                                            {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="filter-start_date"
                                value="{{ request('start_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="filter-end_date"
                                value="{{ request('end_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                        </div>
                    </div>
                    @if (request()->hasAny(['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <button type="button" onclick="resetFilters()"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Filter
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Outer Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lembaga</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Transaksi</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Total Nominal</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                Pending</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tbody-lembaga">
                        @include('superadmin.transaksi-penerimaan.partials.table', [
                            'lembagas' => $lembagas,
                        ])
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const TRX_PER_PAGE = 10;
        const trxPages = {};

        // ── Fungsi untuk toggle statistik di mobile ───────────────────────────
        function toggleStatsMobile() {
            const panel = document.getElementById('stats-mobile-panel');
            const chevron = document.getElementById('stats-chevron-mobile');

            if (!panel) return;

            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                panel.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        // ── Fungsi untuk toggle lembaga (perbaikan dari toggleMasjid) ─────────
        window.toggleLembaga = function(id, row) {
            const content = document.getElementById(id);
            const chevron = row ? row.querySelector('.lembaga-chevron') : null;
            const isHidden = content ? content.classList.contains('hidden') : true;

            if (content) {
                content.classList.toggle('hidden', !isHidden);
            }
            if (chevron) {
                chevron.classList.toggle('rotate-90', isHidden);
            }

            if (isHidden && content) {
                const lembagaId = parseInt(id.replace('trx-penerimaan-', ''));
                if (window.trxData && window.trxData[lembagaId] && !trxPages[lembagaId]) {
                    renderTrxPage(lembagaId, 1);
                }
            }
        };

        // ── Fungsi untuk menerapkan filter dengan AJAX ────────────────────────
        function applyFilters() {
            const lembagaId = document.getElementById('filter-lembaga')?.value || '';
            const status = document.getElementById('filter-status')?.value || '';
            const jenisZakat = document.getElementById('filter-jenis_zakat')?.value || '';
            const startDate = document.getElementById('filter-start_date')?.value || '';
            const endDate = document.getElementById('filter-end_date')?.value || '';
            const searchQuery = document.querySelector('input[name="q"]')?.value || '';

            const params = new URLSearchParams();
            if (lembagaId) params.append('lembaga_id', lembagaId);
            if (status) params.append('status', status);
            if (jenisZakat) params.append('jenis_zakat_id', jenisZakat);
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (searchQuery) params.append('q', searchQuery);
            params.append('_', Date.now()); // Prevent cache

            showLoading();

            fetch(`{{ route('superadmin.transaksi-penerimaan.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateTableContent(data);
                        updateStats(data);
                        hideLoading();

                        const newUrl = `${window.location.pathname}?${params.toString()}`;
                        window.history.pushState({}, '', newUrl);
                        updateFilterActiveState(lembagaId, status, jenisZakat, startDate, endDate, searchQuery);
                    } else {
                        throw new Error(data.message || 'Gagal memuat data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                    showError('Gagal memuat data: ' + error.message);
                });
        }

        function resetFilters() {
            const lembagaSelect = document.getElementById('filter-lembaga');
            const statusSelect = document.getElementById('filter-status');
            const jenisZakatSelect = document.getElementById('filter-jenis_zakat');
            const startDateInput = document.getElementById('filter-start_date');
            const endDateInput = document.getElementById('filter-end_date');
            const searchInput = document.querySelector('input[name="q"]');

            if (lembagaSelect) lembagaSelect.value = '';
            if (statusSelect) statusSelect.value = '';
            if (jenisZakatSelect) jenisZakatSelect.value = '';
            if (startDateInput) startDateInput.value = '';
            if (endDateInput) endDateInput.value = '';
            if (searchInput) searchInput.value = '';

            applyFilters();
        }

        function updateTableContent(data) {
            const tbody = document.getElementById('tbody-lembaga');
            if (tbody) tbody.innerHTML = data.html;

            const totalInfo = document.getElementById('total-info');
            if (totalInfo) {
                totalInfo.innerHTML = `Total: ${data.totalTransaksi} Transaksi dari ${data.totalLembaga} Lembaga`;
            }

            if (data.trxData) {
                window.trxData = window.trxData || {};
                Object.assign(window.trxData, data.trxData);
            }

            Object.keys(trxPages).forEach(key => delete trxPages[key]);
        }

        function updateStats(data) {
            // Update desktop stats
            const totalTransaksi = document.getElementById('stat-total-transaksi');
            const totalVerified = document.getElementById('stat-total-verified');
            const totalPending = document.getElementById('stat-total-pending');
            const totalNominal = document.getElementById('stat-total-nominal');

            if (totalTransaksi) totalTransaksi.textContent = new Intl.NumberFormat('id-ID').format(data.totalTransaksi);
            if (totalVerified) totalVerified.textContent = new Intl.NumberFormat('id-ID').format(data.totalVerified);
            if (totalPending) totalPending.textContent = new Intl.NumberFormat('id-ID').format(data.totalPending);
            if (totalNominal) totalNominal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.totalNominal);

            // Update mobile stats
            const totalTransaksiMobile = document.getElementById('stat-total-transaksi-mobile');
            const totalVerifiedMobile = document.getElementById('stat-total-verified-mobile');
            const totalPendingMobile = document.getElementById('stat-total-pending-mobile');
            const totalNominalMobile = document.getElementById('stat-total-nominal-mobile');

            if (totalTransaksiMobile) totalTransaksiMobile.textContent = new Intl.NumberFormat('id-ID').format(data
                .totalTransaksi);
            if (totalVerifiedMobile) totalVerifiedMobile.textContent = new Intl.NumberFormat('id-ID').format(data
                .totalVerified);
            if (totalPendingMobile) totalPendingMobile.textContent = new Intl.NumberFormat('id-ID').format(data
                .totalPending);
            if (totalNominalMobile) totalNominalMobile.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data
                .totalNominal);
        }

        function updateFilterActiveState(lembagaId, status, jenisZakat, startDate, endDate, searchQuery) {
            const filterButton = document.getElementById('filter-button');
            const hasFilter = lembagaId || status || jenisZakat || startDate || endDate;

            if (filterButton) {
                if (hasFilter) {
                    filterButton.classList.add('ring-2', 'ring-primary');
                } else {
                    filterButton.classList.remove('ring-2', 'ring-primary');
                }
            }

            const filterPanel = document.getElementById('filter-panel');
            if (filterPanel && hasFilter && filterPanel.classList.contains('hidden')) {
                filterPanel.classList.remove('hidden');
            }

            const hasAnyFilter = lembagaId || status || jenisZakat || startDate || endDate || searchQuery;
            const resetButtons = document.querySelectorAll('button[onclick="resetFilters()"]');
            resetButtons.forEach(btn => {
                btn.style.display = hasAnyFilter ? 'inline-flex' : 'none';
            });
        }

        function showLoading() {
            const tbody = document.getElementById('tbody-lembaga');
            if (tbody) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex justify-center">
                            <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Memuat data...</p>
                    </td>
                </tr>
            `;
            }
        }

        function hideLoading() {}

        function showError(message) {
            const tbody = document.getElementById('tbody-lembaga');
            if (tbody) {
                tbody.innerHTML = `
                 <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <svg class="h-12 w-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-red-600">${escapeHtml(message)}</p>
                    </td>
                 </tr>
            `;
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        // ── Render transaksi page ─────────────────────────────────────────────
        function renderTrxPage(lembagaId, page) {
            const data = window.trxData?.[lembagaId] ?? [];
            const total = data.length;
            const totalPages = Math.ceil(total / TRX_PER_PAGE);
            page = Math.max(1, Math.min(page, totalPages));
            trxPages[lembagaId] = page;

            const start = (page - 1) * TRX_PER_PAGE;
            const end = Math.min(start + TRX_PER_PAGE, total);
            const slice = data.slice(start, end);

            const tbody = document.getElementById(`trx-tbody-${lembagaId}`);
            if (!tbody) return;

            tbody.innerHTML = slice.map(t => {
                const statusMap = {
                    verified: 'bg-green-100 text-green-800',
                    pending: 'bg-amber-100 text-amber-800',
                    rejected: 'bg-red-100 text-red-800',
                };
                const statusLabel = {
                    verified: 'Verified',
                    pending: 'Pending',
                    rejected: 'Rejected'
                };
                const sCls = statusMap[t.status] ?? 'bg-gray-100 text-gray-600';
                const sLabel = statusLabel[t.status] ?? t.status;

                const jumlahHtml = t.jumlah > 0 ?
                    `<span class="text-sm font-semibold text-green-700">Rp ${Number(t.jumlah).toLocaleString('id-ID')}</span>` :
                    `<span class="text-sm text-gray-400">-</span>`;

                const waktuHtml = t.waktu ? `<div class="text-xs text-gray-400">${escapeHtml(t.waktu)}</div>` : '';

                return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">${escapeHtml(t.muzakki_nama)}</div>
                    <div class="text-xs text-gray-400">${escapeHtml(t.no_transaksi)}</div>
                  </td>
                <td class="px-4 py-3 hidden sm:table-cell">
                    <div class="text-sm text-gray-700">${escapeHtml(t.tanggal)}</div>
                    ${waktuHtml}
                  </td>
                <td class="px-4 py-3 hidden md:table-cell">
                    <div class="text-sm text-gray-700">${escapeHtml(t.jenis_zakat)}</div>
                  </td>
                <td class="px-4 py-3 text-right">${jumlahHtml}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${sCls}">${sLabel}</span>
                  </td>
              </tr>`;
            }).join('');

            const info = document.getElementById(`trx-info-${lembagaId}`);
            if (info) info.textContent = `Menampilkan ${start + 1}–${end} dari ${total} transaksi`;

            const pag = document.getElementById(`trx-pagination-${lembagaId}`);
            if (pag) pag.innerHTML = buildTrxPagination(lembagaId, page, totalPages);
        }

        function buildTrxPagination(lembagaId, current, total) {
            if (total <= 1) return '';

            const btnBase =
                'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
            const btnActive = `${btnBase} bg-primary text-white`;
            const btnNormal = `${btnBase} text-gray-600 hover:bg-gray-100`;
            const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;
            const prevSvg =
                `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
            const nextSvg =
                `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;

            let html = '';

            html += current > 1 ?
                `<button onclick="renderTrxPage(${lembagaId}, ${current - 1})" class="${btnNormal}">${prevSvg}</button>` :
                `<button disabled class="${btnDisabled}">${prevSvg}</button>`;

            const range = trxPageRange(current, total);
            range.forEach(p => {
                if (p === '...') {
                    html += `<span class="${btnBase} text-gray-400">…</span>`;
                } else {
                    const cls = p === current ? btnActive : btnNormal;
                    html += `<button onclick="renderTrxPage(${lembagaId}, ${p})" class="${cls}">${p}</button>`;
                }
            });

            html += current < total ?
                `<button onclick="renderTrxPage(${lembagaId}, ${current + 1})" class="${btnNormal}">${nextSvg}</button>` :
                `<button disabled class="${btnDisabled}">${nextSvg}</button>`;

            return html;
        }

        function trxPageRange(current, total) {
            if (total <= 7) return Array.from({
                length: total
            }, (_, i) => i + 1);
            if (current <= 4) return [1, 2, 3, 4, 5, '...', total];
            if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
            return [1, '...', current - 1, current, current + 1, '...', total];
        }

        function toggleSearch() {
            const btn = document.getElementById('search-button');
            const form = document.getElementById('search-form');
            const input = document.getElementById('search-input');
            const container = document.getElementById('search-container');

            if (form.classList.contains('hidden')) {
                btn.classList.add('hidden');
                form.classList.remove('hidden');
                container.style.minWidth = '280px';
                setTimeout(() => input?.focus(), 50);
            } else {
                form.classList.add('hidden');
                btn.classList.remove('hidden');
                container.style.minWidth = '';
            }
        }

        function toggleFilter() {
            document.getElementById('filter-panel')?.classList.toggle('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const form = document.getElementById('search-form');
                const btn = document.getElementById('search-button');
                const container = document.getElementById('search-container');
                if (form && !form.classList.contains('hidden')) {
                    form.classList.add('hidden');
                    btn?.classList.remove('hidden');
                    container.style.minWidth = '';
                }
            }
        });
    </script>
@endpush
