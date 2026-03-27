@extends('layouts.app')

@section('title', 'Transaksi Penyaluran Semua Lembaga')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 animate-slide-up">
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Total</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-transaksi">{{ number_format($totalTransaksi) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Menunggu</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-draft">{{ number_format($totalDraft) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Disalurkan</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-disalurkan">{{ number_format($totalDisalurkan) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Total Nominal</p>
                        <p class="text-xl font-semibold text-gray-900" id="stat-total-nominal">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Transaksi Penyaluran Semua Lembaga</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1" id="total-info">
                            Total: {{ $totalTransaksi }} Transaksi dari {{ $lembagas->count() }} Lembaga
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()" id="filter-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
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
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="#" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}" onsubmit="applyFilters(); return false;">
                                @foreach (['status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            id="search-input" placeholder="Cari nama lembaga / mustahik..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date']))
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
                class="{{ request()->hasAny(['status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
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
                                    <option value="{{ $lembaga->id }}" {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
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
                                <option value="draft"      {{ request('status') == 'draft'      ? 'selected' : '' }}>Draft (Menunggu)</option>
                                <option value="disetujui"  {{ request('status') == 'disetujui'  ? 'selected' : '' }}>Disetujui</option>
                                <option value="disalurkan" {{ request('status') == 'disalurkan' ? 'selected' : '' }}>Disalurkan</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Penyaluran</label>
                            <select name="metode_penyaluran" id="filter-metode"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                                <option value="">Semua Metode</option>
                                <option value="tunai"    {{ request('metode_penyaluran') == 'tunai'    ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ request('metode_penyaluran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="barang"   {{ request('metode_penyaluran') == 'barang'   ? 'selected' : '' }}>Barang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="filter-start_date" value="{{ request('start_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="filter-end_date" value="{{ request('end_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="applyFilters()">
                        </div>
                    </div>
                    @if (request()->hasAny(['status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <button type="button" onclick="resetFilters()"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lembaga</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Total Nominal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Menunggu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tbody-lembaga">
                        @include('superadmin.transaksi-penyaluran.partials.table', ['lembagas' => $lembagas])
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    const PENYALURAN_PER_PAGE = 10;
    const penyaluranPages = {};

    // ── Fungsi untuk menerapkan filter dengan AJAX ────────────────────────
    function applyFilters() {
        const lembagaId = document.getElementById('filter-lembaga')?.value || '';
        const status = document.getElementById('filter-status')?.value || '';
        const metode = document.getElementById('filter-metode')?.value || '';
        const startDate = document.getElementById('filter-start_date')?.value || '';
        const endDate = document.getElementById('filter-end_date')?.value || '';
        const searchQuery = document.querySelector('input[name="q"]')?.value || '';
        
        const params = new URLSearchParams();
        if (lembagaId) params.append('lembaga_id', lembagaId);
        if (status) params.append('status', status);
        if (metode) params.append('metode_penyaluran', metode);
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);
        if (searchQuery) params.append('q', searchQuery);
        params.append('_', Date.now()); // Prevent cache
        
        showLoading();
        
        fetch(`{{ route('superadmin.transaksi-penyaluran.index') }}?${params.toString()}`, {
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
                updateFilterActiveState(lembagaId, status, metode, startDate, endDate, searchQuery);
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
        const metodeSelect = document.getElementById('filter-metode');
        const startDateInput = document.getElementById('filter-start_date');
        const endDateInput = document.getElementById('filter-end_date');
        const searchInput = document.querySelector('input[name="q"]');
        
        if (lembagaSelect) lembagaSelect.value = '';
        if (statusSelect) statusSelect.value = '';
        if (metodeSelect) metodeSelect.value = '';
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
        
        if (data.penyaluranData) {
            window.penyaluranData = window.penyaluranData || {};
            Object.assign(window.penyaluranData, data.penyaluranData);
        }
        
        Object.keys(penyaluranPages).forEach(key => delete penyaluranPages[key]);
    }
    
    function updateStats(data) {
        const totalTransaksi = document.getElementById('stat-total-transaksi');
        const totalDraft = document.getElementById('stat-total-draft');
        const totalDisalurkan = document.getElementById('stat-total-disalurkan');
        const totalNominal = document.getElementById('stat-total-nominal');
        
        if (totalTransaksi) totalTransaksi.textContent = new Intl.NumberFormat('id-ID').format(data.totalTransaksi);
        if (totalDraft) totalDraft.textContent = new Intl.NumberFormat('id-ID').format(data.totalDraft);
        if (totalDisalurkan) totalDisalurkan.textContent = new Intl.NumberFormat('id-ID').format(data.totalDisalurkan);
        if (totalNominal) totalNominal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.totalNominal);
    }
    
    function updateFilterActiveState(lembagaId, status, metode, startDate, endDate, searchQuery) {
        const filterButton = document.getElementById('filter-button');
        const hasFilter = lembagaId || status || metode || startDate || endDate;
        
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
        
        const hasAnyFilter = lembagaId || status || metode || startDate || endDate || searchQuery;
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
                  </tr>
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

    // ── Render penyaluran page ─────────────────────────────────────────────
    function renderPenyaluranPage(lembagaId, page) {
        const data = window.penyaluranData?.[lembagaId] ?? [];
        const total = data.length;
        const totalPages = Math.ceil(total / PENYALURAN_PER_PAGE);
        page = Math.max(1, Math.min(page, totalPages));
        penyaluranPages[lembagaId] = page;

        const start = (page - 1) * PENYALURAN_PER_PAGE;
        const end = Math.min(start + PENYALURAN_PER_PAGE, total);
        const slice = data.slice(start, end);

        const tbody = document.getElementById(`penyaluran-tbody-${lembagaId}`);
        if (!tbody) return;
        
        tbody.innerHTML = slice.map(t => {
            const statusMap = {
                disalurkan: { cls: 'bg-green-100 text-green-800', label: 'Disalurkan' },
                disetujui: { cls: 'bg-blue-100 text-blue-800', label: 'Disetujui' },
                draft: { cls: 'bg-yellow-100 text-yellow-800', label: 'Draft' },
                dibatalkan: { cls: 'bg-red-100 text-red-800', label: 'Dibatalkan' },
            };
            const s = statusMap[t.status] ?? { cls: 'bg-gray-100 text-gray-600', label: t.status };

            const jumlahHtml = t.jumlah > 0
                ? `<span class="text-sm font-semibold text-green-700">Rp ${Number(t.jumlah).toLocaleString('id-ID')}</span>`
                : `<span class="text-sm text-gray-400">-</span>`;

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">${escapeHtml(t.mustahik)}</div>
                    <div class="text-xs text-gray-400">${escapeHtml(t.no_transaksi)}</div>
                </td>
                <td class="px-4 py-3 hidden sm:table-cell">
                    <div class="text-sm text-gray-700">${escapeHtml(t.tanggal)}</div>
                </td>
                <td class="px-4 py-3 hidden md:table-cell">
                    <span class="text-sm text-gray-700 capitalize">${escapeHtml(t.metode)}</span>
                </td>
                <td class="px-4 py-3 text-right">${jumlahHtml}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${s.cls}">${s.label}</span>
                </td>
              </tr>`;
        }).join('');

        const info = document.getElementById(`penyaluran-info-${lembagaId}`);
        if (info) info.textContent = `Menampilkan ${start + 1}–${end} dari ${total} transaksi`;

        const pag = document.getElementById(`penyaluran-pagination-${lembagaId}`);
        if (pag) pag.innerHTML = buildPenyaluranPagination(lembagaId, page, totalPages);
    }

    function buildPenyaluranPagination(lembagaId, current, total) {
        if (total <= 1) return '';

        const btnBase = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
        const btnActive = `${btnBase} bg-primary text-white`;
        const btnNormal = `${btnBase} text-gray-600 hover:bg-gray-100`;
        const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;
        const prevSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const nextSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;

        let html = '';

        html += current > 1
            ? `<button onclick="renderPenyaluranPage(${lembagaId}, ${current - 1})" class="${btnNormal}">${prevSvg}</button>`
            : `<button disabled class="${btnDisabled}">${prevSvg}</button>`;

        const range = penyaluranPageRange(current, total);
        range.forEach(p => {
            if (p === '...') {
                html += `<span class="${btnBase} text-gray-400">…</span>`;
            } else {
                const cls = p === current ? btnActive : btnNormal;
                html += `<button onclick="renderPenyaluranPage(${lembagaId}, ${p})" class="${cls}">${p}</button>`;
            }
        });

        html += current < total
            ? `<button onclick="renderPenyaluranPage(${lembagaId}, ${current + 1})" class="${btnNormal}">${nextSvg}</button>`
            : `<button disabled class="${btnDisabled}">${nextSvg}</button>`;

        return html;
    }

    function penyaluranPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        if (current <= 4) return [1, 2, 3, 4, 5, '...', total];
        if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
        return [1, '...', current - 1, current, current + 1, '...', total];
    }

    function toggleLembaga(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.lembaga-chevron');
        const isHidden = content.classList.contains('hidden');

        content.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-90', isHidden);

        if (isHidden) {
            const lembagaId = parseInt(id.replace('trx-penyaluran-', ''));
            if (window.penyaluranData?.[lembagaId] && !penyaluranPages[lembagaId]) {
                renderPenyaluranPage(lembagaId, 1);
            }
        }
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