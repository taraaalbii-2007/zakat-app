@extends('layouts.app')

@section('title', 'Kelola Muzaki')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Data Muzaki Semua Lembaga</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5" id="total-info">
                            Total: {{ number_format($stats['total_muzakki_unik']) }} Muzaki dari {{ $lembagas->count() }} Lembaga
                        </p>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()" id="filter-button"
                            class="inline-flex items-center justify-center p-2 sm:px-3 sm:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['lembaga_id']) ? 'ring-2 ring-primary' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container" class="flex-1 sm:flex-none transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 200px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="inline-flex items-center justify-center p-2 sm:px-3 sm:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2">Cari</span>
                            </button>
                            <form method="GET" action="#" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}" onsubmit="applyFilters(); return false;">
                                @if (request('lembaga_id'))
                                    <input type="hidden" name="lembaga_id" value="{{ request('lembaga_id') }}">
                                @endif
                                <div class="flex items-center gap-1.5">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            id="search-input" placeholder="Cari lembaga/muzaki..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    <button type="button" onclick="toggleSearch()"
                                        class="sm:hidden p-2 text-gray-400 hover:text-gray-600 rounded-lg flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    @if (request()->hasAny(['q', 'lembaga_id']))
                                        <button type="button" onclick="resetFilters()"
                                            class="hidden sm:inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
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
                class="{{ request()->hasAny(['lembaga_id']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="#" id="filter-form" onsubmit="applyFilters(); return false;">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 gap-2 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lembaga</label>
                            <select name="lembaga_id" id="filter-lembaga"
                                class="block w-full px-2 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                onchange="applyFilters()">
                                <option value="">Semua Lembaga</option>
                                @foreach ($lembagas as $lembaga)
                                    <option value="{{ $lembaga->id }}" {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                        {{ $lembaga->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if (request()->hasAny(['lembaga_id']))
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

            {{-- ── DESKTOP: outer table ─────────────────────────────────── --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lembaga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Alamat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Muzaki</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tbody-lembaga">
                        @include('superadmin.muzaki.partials.table', ['lembagas' => $lembagas])
                    </tbody>
                </table>
            </div>

            {{-- ── MOBILE: card list ────────────────────────────────────── --}}
            <div class="sm:hidden divide-y divide-gray-100" id="mobile-lembaga-list">
                @forelse ($lembagas as $lembaga)
                    @php
                        // Gunakan properti muzakkis dari controller, bukan method muzakki()
                        $muzakiData = collect($lembaga->muzakkis ?? [])->map(function($m) use ($lembaga) {
                            $jenisZakat = collect(
                                array_filter(explode(',', $m->jenis_zakat_list ?? ''))
                            )->unique()->values();
                            
                            return [
                                'id' => md5($m->muzakki_nama . $lembaga->id),
                                'nama' => $m->muzakki_nama ?? '-',
                                'initial' => strtoupper(substr($m->muzakki_nama ?? 'M', 0, 1)),
                                'telepon' => $m->muzakki_telepon ?? null,
                                'email' => $m->muzakki_email ?? null,
                                'jenis_zakat' => $jenisZakat->toArray(),
                                'total_transaksi' => $m->total_transaksi ?? 0,
                                'total_nominal' => (int) ($m->total_nominal ?? 0),
                                'transaksi_terakhir' => isset($m->transaksi_terakhir) 
                                    ? \Carbon\Carbon::parse($m->transaksi_terakhir)->format('d M Y') 
                                    : '-',
                                'detail_url' => route('muzaki.show', [
                                    'nama' => $m->muzakki_nama,
                                    'lembaga_id' => $lembaga->id,
                                ]),
                            ];
                        });
                        
                        $count = $muzakiData->count();
                        $totalNominal = $muzakiData->sum('total_nominal');
                    @endphp

                    <div class="lembaga-card">
                        {{-- Lembaga row header --}}
                        <button type="button"
                            data-lembaga-id="{{ $lembaga->id }}"
                            data-muzaki='@json($muzakiData)'
                            onclick="toggleMobileLembaga(this)"
                            class="w-full flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 active:bg-gray-100 transition-colors text-left">
                            <svg class="lembaga-chevron w-4 h-4 text-gray-400 transform transition-transform duration-200 flex-shrink-0"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $lembaga->nama }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Tap untuk lihat muzaki</p>
                            </div>
                            <div class="flex flex-col items-end flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    {{ number_format($count) }}
                                </span>
                                <span class="text-xs font-semibold text-gray-600 mt-1">
                                    Rp {{ number_format($totalNominal, 0, ',', '.') }}
                                </span>
                            </div>
                        </button>

                        {{-- Expandable muzaki list --}}
                        <div class="hidden lembaga-expand-content" id="expand-{{ $lembaga->id }}">
                            <div class="bg-gray-50 border-t border-gray-100">
                                {{-- Sub-header --}}
                                <div class="px-4 py-2.5 border-b border-gray-200 flex items-center gap-2">
                                    <div class="w-1 h-4 bg-green-600 rounded-full flex-shrink-0"></div>
                                    <p class="text-xs font-semibold text-gray-700 truncate">{{ $lembaga->nama }}</p>
                                </div>

                                {{-- Pagination info --}}
                                <div class="px-4 pt-2 pb-0">
                                    <span class="mobile-page-info-{{ $lembaga->id }} text-xs text-gray-400"></span>
                                </div>

                                {{-- Cards container --}}
                                <div class="mobile-muzaki-cards-{{ $lembaga->id }} divide-y divide-gray-100">
                                    {{-- filled by JS --}}
                                </div>

                                {{-- Pagination --}}
                                <div class="mobile-muzaki-pagination-{{ $lembaga->id }} px-4 py-3 flex flex-wrap items-center justify-center gap-1 border-t border-gray-100">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-sm text-gray-500">Tidak ada data lembaga</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ── Mobile per-lembaga muzaki state ─────────────────────────────────
    const MOBILE_PER_PAGE = 10;
    const mobileMuzakiData  = {};   // lembagaId -> array of muzaki objects
    const mobileMuzakiPages = {};   // lembagaId -> current page

    // ── Desktop muzaki state ───────────────────────────────────────────
    const MUZAKI_PER_PAGE = 10;
    const muzakiPages = {};

    /**
     * Toggle expand/collapse for a lembaga on mobile.
     * Data is read from data-muzaki attribute (injected by Blade).
     */
    function toggleMobileLembaga(btn) {
        const lembagaId = parseInt(btn.dataset.lembagaId);
        const expand    = document.getElementById(`expand-${lembagaId}`);
        const chevron   = btn.querySelector('.lembaga-chevron');

        const isHidden = expand.classList.contains('hidden');
        expand.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-90', isHidden);

        if (isHidden && !mobileMuzakiData[lembagaId]) {
            // Parse data from button attribute on first open
            try {
                mobileMuzakiData[lembagaId] = JSON.parse(btn.dataset.muzaki || '[]');
            } catch (e) {
                mobileMuzakiData[lembagaId] = [];
            }
            renderMobileMuzakiPage(lembagaId, 1);
        }
    }

    function renderMobileMuzakiPage(lembagaId, page) {
        const data  = mobileMuzakiData[lembagaId] ?? [];
        const total = data.length;
        const totalPages = Math.ceil(total / MOBILE_PER_PAGE) || 1;
        page = Math.max(1, Math.min(page, totalPages));
        mobileMuzakiPages[lembagaId] = page;

        const start = (page - 1) * MOBILE_PER_PAGE;
        const slice = data.slice(start, start + MOBILE_PER_PAGE);
        const end   = start + slice.length;

        // ── Render cards ──────────────────────────────────────────────────
        const container = document.querySelector(`.mobile-muzaki-cards-${lembagaId}`);
        if (container) {
            if (slice.length === 0) {
                container.innerHTML = `<div class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada data muzaki</div>`;
            } else {
                container.innerHTML = slice.map(m => {
                    const jenisHtml = m.jenis_zakat && m.jenis_zakat.length > 0
                        ? m.jenis_zakat.map(j => `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">${escapeHtml(j)}</span>`).join(' ')
                        : '<span class="text-xs text-gray-400">-</span>';
                    
                    const teleponHtml = m.telepon
                        ? `<p class="text-xs text-gray-400 mt-0.5">${escapeHtml(m.telepon)}</p>`
                        : '';
                    
                    const emailHtml = m.email
                        ? `<p class="text-xs text-gray-400">${escapeHtml(m.email.length > 30 ? m.email.substring(0, 30) + '…' : m.email)}</p>`
                        : '';
                    
                    const nominalFormatted = 'Rp ' + Number(m.total_nominal).toLocaleString('id-ID');

                    return `
                    <div class="px-4 py-3 bg-white hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-green-700">${escapeHtml(m.initial)}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 leading-snug">${escapeHtml(m.nama)}</p>
                                        ${teleponHtml}
                                        ${emailHtml}
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xs font-semibold text-gray-600">${nominalFormatted}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">${m.total_transaksi}x transaksi</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center justify-between gap-2 mt-2">
                                    <div class="flex flex-wrap gap-1">
                                        ${jenisHtml}
                                    </div>
                                    <p class="text-xs text-gray-400">Terakhir: ${escapeHtml(m.transaksi_terakhir)}</p>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <a href="${escapeHtml(m.detail_url)}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors w-full justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>`;
                }).join('');
            }
        }

        // ── Page info ─────────────────────────────────────────────────────
        const info = document.querySelector(`.mobile-page-info-${lembagaId}`);
        if (info) info.textContent = total > 0 ? `${start + 1}–${end} dari ${total} muzaki` : '';

        // ── Pagination ────────────────────────────────────────────────────
        const pag = document.querySelector(`.mobile-muzaki-pagination-${lembagaId}`);
        if (pag) pag.innerHTML = buildMobilePagination(lembagaId, page, totalPages);
    }

    function buildMobilePagination(lembagaId, current, total) {
        if (total <= 1) return '';
        const base     = 'inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-medium transition-colors';
        const active   = `${base} bg-green-600 text-white`;
        const normal   = `${base} text-gray-600 hover:bg-gray-100 bg-white border border-gray-200`;
        const disabled = `${base} text-gray-300 cursor-not-allowed bg-white border border-gray-100`;
        const prevIcon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const nextIcon = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;

        let html = '';
        html += current > 1
            ? `<button onclick="renderMobileMuzakiPage(${lembagaId},${current-1})" class="${normal}">${prevIcon}</button>`
            : `<button disabled class="${disabled}">${prevIcon}</button>`;

        muzakiPageRange(current, total).forEach(p => {
            html += p === '...'
                ? `<span class="${base} text-gray-400 border-0">…</span>`
                : `<button onclick="renderMobileMuzakiPage(${lembagaId},${p})" class="${p===current?active:normal}">${p}</button>`;
        });

        html += current < total
            ? `<button onclick="renderMobileMuzakiPage(${lembagaId},${current+1})" class="${normal}">${nextIcon}</button>`
            : `<button disabled class="${disabled}">${nextIcon}</button>`;
        return html;
    }

    // ── Desktop muzaki inner pagination ─────────────────────────────────
    function renderMuzakiPage(lembagaId, page) {
        const data = window.muzakiData?.[lembagaId] ?? [];
        const total = data.length;
        const totalPages = Math.ceil(total / MUZAKI_PER_PAGE) || 1;
        page = Math.max(1, Math.min(page, totalPages));
        muzakiPages[lembagaId] = page;

        const start = (page - 1) * MUZAKI_PER_PAGE;
        const slice = data.slice(start, start + MUZAKI_PER_PAGE);
        const end   = start + slice.length;

        const tbody = document.getElementById(`muzaki-tbody-${lembagaId}`);
        if (!tbody) return;

        tbody.innerHTML = slice.map(m => {
            const teleponHtml = m.telepon ? `<div class="text-xs text-gray-400 mt-0.5">${escapeHtml(m.telepon)}</div>` : '';
            const emailShort = m.email && m.email.length > 28 ? m.email.substring(0, 28) + '…' : (m.email ?? '');
            const emailHtml = emailShort ? `<div class="text-xs text-gray-400">${escapeHtml(emailShort)}</div>` : '';

            let jenisHtml = '<span class="text-xs text-gray-400">-</span>';
            if (m.jenis_zakat && m.jenis_zakat.length > 0) {
                jenisHtml = m.jenis_zakat.map(j => `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">${escapeHtml(j)}</span>`).join(' ');
            }

            const trxHtml = `<span class="text-sm font-semibold text-gray-700">${m.total_transaksi}x</span>`;
            const nominalFormatted = 'Rp ' + Number(m.total_nominal).toLocaleString('id-ID');

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-green-700">${escapeHtml(m.initial)}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${escapeHtml(m.nama)}</div>
                            ${teleponHtml}
                            ${emailHtml}
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 hidden sm:table-cell">
                    <div class="flex flex-wrap gap-1">${jenisHtml}</div>
                </td>
                <td class="px-4 py-3 text-center">${trxHtml}</td>
                <td class="px-4 py-3 text-right hidden md:table-cell">
                    <p class="text-sm font-semibold text-gray-800">${nominalFormatted}</p>
                </td>
                <td class="px-4 py-3 text-center hidden lg:table-cell">
                    <p class="text-xs text-gray-700">${escapeHtml(m.transaksi_terakhir)}</p>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="${m.detail_url}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
                </td>
              </tr>`;
        }).join('');

        const info = document.getElementById(`muzaki-info-${lembagaId}`);
        if (info) info.textContent = `${start + 1}–${end} dari ${total} muzaki`;

        const pag = document.getElementById(`muzaki-pagination-${lembagaId}`);
        if (pag) pag.innerHTML = buildMuzakiPagination(lembagaId, page, totalPages);
    }

    function buildMuzakiPagination(lembagaId, current, total) {
        if (total <= 1) return '';
        const base = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
        const active = `${base} bg-green-600 text-white`;
        const normal = `${base} text-gray-600 hover:bg-gray-100`;
        const disabled = `${base} text-gray-300 cursor-not-allowed`;
        const prev = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const next = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;
        let html = '';
        html += current > 1
            ? `<button onclick="renderMuzakiPage(${lembagaId},${current-1})" class="${normal}">${prev}</button>`
            : `<button disabled class="${disabled}">${prev}</button>`;
        muzakiPageRange(current, total).forEach(p => {
            html += p === '...'
                ? `<span class="${base} text-gray-400">…</span>`
                : `<button onclick="renderMuzakiPage(${lembagaId},${p})" class="${p===current?active:normal}">${p}</button>`;
        });
        html += current < total
            ? `<button onclick="renderMuzakiPage(${lembagaId},${current+1})" class="${normal}">${next}</button>`
            : `<button disabled class="${disabled}">${next}</button>`;
        return html;
    }

    function muzakiPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        if (current <= 4) return [1,2,3,4,5,'...',total];
        if (current >= total-3) return [1,'...',total-4,total-3,total-2,total-1,total];
        return [1,'...',current-1,current,current+1,'...',total];
    }

    // ── Desktop: toggle lembaga row (called from partial table) ───────────
    function toggleLembaga(id, row) {
        const content = document.getElementById(id);
        const chevron = (row?.querySelector ? row.querySelector('.lembaga-chevron') : null);
        if (!content) return;
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-90', isHidden);
        if (isHidden) {
            const lembagaId = parseInt(id.replace('lembaga-', ''));
            if (window.muzakiData?.[lembagaId] && !muzakiPages[lembagaId]) {
                renderMuzakiPage(lembagaId, 1);
            }
        }
    }

    // ── AJAX filter ───────────────────────────────────────────────────────
    function applyFilters() {
        const params = new URLSearchParams();
        const li = document.getElementById('filter-lembaga')?.value || '';
        const sq = document.querySelector('input[name="q"]')?.value || '';
        if (li) params.append('lembaga_id', li);
        if (sq) params.append('q', sq);
        params.append('_', Date.now());

        showLoading();

        fetch(`{{ route('muzaki.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            credentials: 'same-origin'
        })
        .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Gagal memuat data');
            // Update desktop table
            const tbody = document.getElementById('tbody-lembaga');
            if (tbody) tbody.innerHTML = data.html;
            // Update total info
            const ti = document.getElementById('total-info');
            if (ti) ti.innerHTML = `Total: ${new Intl.NumberFormat('id-ID').format(data.totalMuzaki)} Muzaki dari ${data.totalLembaga} Lembaga`;
            if (data.muzakiData) {
                window.muzakiData = window.muzakiData || {};
                Object.assign(window.muzakiData, data.muzakiData);
            }
            Object.keys(muzakiPages).forEach(k => delete muzakiPages[k]);
            window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
            // Update filter active ring
            const fb = document.getElementById('filter-button');
            if (fb) { fb.classList.toggle('ring-2', !!(li)); fb.classList.toggle('ring-primary', !!(li)); }
        })
        .catch(err => { console.error(err); showError('Gagal memuat data: ' + err.message); });
    }

    function resetFilters() {
        const lembagaSelect = document.getElementById('filter-lembaga');
        const searchInput = document.querySelector('input[name="q"]');
        
        if (lembagaSelect) lembagaSelect.value = '';
        if (searchInput) searchInput.value = '';
        
        applyFilters();
    }

    function showLoading() {
        const tbody = document.getElementById('tbody-lembaga');
        if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center">
            <svg class="animate-spin h-8 w-8 text-green-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-gray-500 mt-2">Memuat data...</p>
        </td></tr>`;
    }

    function showError(msg) {
        const tbody = document.getElementById('tbody-lembaga');
        if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center">
            <p class="text-sm text-red-600">${escapeHtml(msg)}</p>
        </td></tr>`;
    }

    function escapeHtml(str) {
        return str ? String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') : '';
    }

    // ── Search / filter UI ────────────────────────────────────────────────
    function toggleSearch() {
        const btn  = document.getElementById('search-button');
        const form = document.getElementById('search-form');
        const input = document.getElementById('search-input');
        const container = document.getElementById('search-container');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            container.style.minWidth = window.innerWidth < 640 ? '0' : '280px';
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

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            const form = document.getElementById('search-form');
            if (form && !form.classList.contains('hidden')) toggleSearch();
        }
    });
</script>
@endpush