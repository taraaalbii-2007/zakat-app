@extends('layouts.app')

@section('title', 'Data Muzaki')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ===== HEADER STATS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        {{-- Total Amil --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Amil</p>
                <p class="text-lg font-bold text-gray-900">{{ number_format($summary['total_amil']) }}</p>
            </div>
        </div>

        {{-- Total Muzaki --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Muzaki</p>
                <p class="text-lg font-bold text-gray-900">{{ number_format($summary['total_muzakki']) }}</p>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Transaksi</p>
                <p class="text-lg font-bold text-gray-900">{{ number_format($summary['total_transaksi']) }}</p>
            </div>
        </div>

        {{-- Total Nominal --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Nominal</p>
                <p class="text-base font-bold text-gray-900">Rp {{ number_format($summary['total_nominal'], 0, ',', '.') }}</p>
            </div>
        </div>

    </div>

    {{-- ===== TABEL UTAMA ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Data Muzaki per Amil</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        Klik nama amil untuk melihat daftar muzaki yang diinput
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">

                    {{-- Expand / Collapse All --}}
                    <button type="button" onclick="expandAll()"
                        class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary/90 text-white text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        Buka Semua
                    </button>
                    <button type="button" onclick="collapseAll()"
                        class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                        Tutup Semua
                    </button>

                    {{-- Search Amil --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="search" id="cari-amil" placeholder="Cari nama amil..."
                            oninput="filterAmil(this.value)"
                            class="pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all w-full sm:w-52">
                    </div>

                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-10 px-4 py-3"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amil</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Muzaki</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Transaksi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Total Nominal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tbody-amil">
                    @forelse ($amils as $amil)

                        {{-- ROW AMIL --}}
                        <tr class="amil-row cursor-pointer hover:bg-primary/5 transition-colors"
                            data-nama="{{ strtolower($amil->nama_lengkap) }} {{ strtolower($amil->kode_amil) }}"
                            data-amil-id="{{ $amil->id }}"
                            onclick="toggleAmil({{ $amil->id }}, this)">
                            <td class="px-4 py-3">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 amil-chevron"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar --}}
                                    @if ($amil->foto)
                                        <img src="{{ asset('storage/' . $amil->foto) }}"
                                            alt="{{ $amil->nama_lengkap }}"
                                            class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-semibold text-primary">
                                                {{ strtoupper(substr($amil->nama_lengkap, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $amil->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-400">{{ $amil->kode_amil }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center hidden sm:table-cell">
                                @if ($amil->status === 'aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>Aktif
                                    </span>
                                @elseif ($amil->status === 'cuti')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1"></span>Cuti
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1"></span>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    {{ number_format($amil->jumlah_muzakki) }} Muzaki
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center hidden md:table-cell">
                                <span class="text-sm text-gray-700 font-medium">
                                    {{ number_format($amil->jumlah_transaksi) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right hidden lg:table-cell">
                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($amil->total_nominal ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>

                        {{-- EXPANDABLE ROW: Tabel Muzaki --}}
                        <tr id="amil-content-{{ $amil->id }}" class="hidden amil-content-row">
                            <td colspan="6" class="p-0">
                                <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">

                                    {{-- Sub-header --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Muzaki yang diinput oleh
                                                <span class="text-primary">{{ $amil->nama_lengkap }}</span>
                                            </h3>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                            </div>
                                            <input type="search"
                                                placeholder="Cari muzaki..."
                                                oninput="searchMuzaki({{ $amil->id }}, this.value)"
                                                class="pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary w-48"
                                                onclick="event.stopPropagation()">
                                        </div>
                                    </div>

                                    {{-- Konten muzaki (diisi via AJAX) --}}
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
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada data amil</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // ── State ──────────────────────────────────────────────────────────────
    const loadedAmil  = {};
    const openAmil    = {};
    let   searchTimers = {};

    // ── Toggle Row ─────────────────────────────────────────────────────────
    function toggleAmil(amilId, row) {
        const contentRow = document.getElementById(`amil-content-${amilId}`);
        const chevron    = row.querySelector('.amil-chevron');
        const isHidden   = contentRow.classList.contains('hidden');

        contentRow.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);
        openAmil[amilId] = isHidden;

        // Load AJAX hanya sekali
        if (isHidden && !loadedAmil[amilId]) {
            fetchMuzaki(amilId, '');
        }
    }

    // ── Fetch Muzaki via AJAX ──────────────────────────────────────────────
    function fetchMuzaki(amilId, search = '', page = 1) {
        const container = document.getElementById(`muzaki-container-${amilId}`);
        container.innerHTML = renderLoading();

        const params = new URLSearchParams({ search, page });

        fetch(`/admin-masjid/muzaki/amil/${amilId}?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Gagal memuat data');
            loadedAmil[amilId] = true;
            container.innerHTML = renderMuzakiTable(data.muzakkis, amilId);
        })
        .catch(() => {
            container.innerHTML = renderError(amilId);
        });
    }

    // ── Search Debounce ────────────────────────────────────────────────────
    function searchMuzaki(amilId, keyword) {
        clearTimeout(searchTimers[amilId]);
        searchTimers[amilId] = setTimeout(() => {
            fetchMuzaki(amilId, keyword, 1);
        }, 400);
    }

    // ── Filter Amil (client-side) ──────────────────────────────────────────
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
    }

    // ── Expand All ─────────────────────────────────────────────────────────
    function expandAll() {
        document.querySelectorAll('.amil-row').forEach(row => {
            const amilId = row.getAttribute('data-amil-id');
            if (!amilId) return;
            const contentRow = document.getElementById(`amil-content-${amilId}`);
            const chevron    = row.querySelector('.amil-chevron');
            if (contentRow && contentRow.classList.contains('hidden')) {
                contentRow.classList.remove('hidden');
                chevron.classList.add('rotate-90');
                if (!loadedAmil[amilId]) fetchMuzaki(amilId, '');
            }
        });
    }

    // ── Collapse All ───────────────────────────────────────────────────────
    function collapseAll() {
        document.querySelectorAll('.amil-content-row').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.amil-chevron').forEach(el => el.classList.remove('rotate-90'));
    }

    // ── Render Helpers ─────────────────────────────────────────────────────
    function renderLoading() {
        return `<div class="text-center py-8">
            <svg class="w-6 h-6 mx-auto animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-xs text-gray-400 mt-2">Memuat data muzaki...</p>
        </div>`;
    }

    function renderError(amilId) {
        return `<div class="text-center py-8 text-sm text-red-400">
            Gagal memuat data.
            <button onclick="fetchMuzaki(${amilId}, '')" class="underline ml-1">Coba lagi</button>
        </div>`;
    }

    function renderMuzakiTable(pagination, amilId) {
        const data = pagination.data;
        if (!data || data.length === 0) {
            return `<div class="text-center py-8 bg-white rounded-xl border border-gray-100">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p class="text-sm text-gray-400">Belum ada muzaki yang diinput</p>
            </div>`;
        }

        const rows = data.map(m => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-blue-700">
                                ${(m.muzakki_nama || '-').charAt(0).toUpperCase()}
                            </span>
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
                    <span class="text-xs text-gray-500">
                        ${escHtml(m.muzakki_alamat ? m.muzakki_alamat.substring(0, 40) + (m.muzakki_alamat.length > 40 ? '...' : '') : '-')}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                        ${m.total_transaksi}x
                    </span>
                </td>
                <td class="px-4 py-3 text-right hidden lg:table-cell">
                    <span class="text-sm font-semibold text-gray-900">
                        Rp ${formatRupiah(m.total_nominal || 0)}
                    </span>
                </td>
                <td class="px-4 py-3 text-center hidden sm:table-cell">
                    <span class="text-xs text-gray-500">
                        ${m.transaksi_terakhir ? formatDate(m.transaksi_terakhir) : '-'}
                    </span>
                </td>
            </tr>
        `).join('');

        const thead = `
            <thead class="bg-white">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Nama Muzaki</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Telepon</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Alamat</th>
                    <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                    <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Total Nominal</th>
                    <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Terakhir</th>
                </tr>
            </thead>`;

        return `
            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    ${thead}
                    <tbody class="bg-white divide-y divide-gray-100">${rows}</tbody>
                </table>
            </div>
            ${renderPagination(pagination, amilId)}`;
    }

    function renderPagination(pagination, amilId) {
        if (pagination.last_page <= 1) return '';
        const { current_page, last_page, from, to, total } = pagination;

        let pages = '';
        for (let p = 1; p <= last_page; p++) {
            if (p === current_page) {
                pages += `<span class="px-3 py-1 text-xs font-semibold text-white bg-primary rounded-md">${p}</span>`;
            } else {
                pages += `<button onclick="fetchMuzaki(${amilId},'',${p})"
                    class="px-3 py-1 text-xs text-gray-600 hover:bg-gray-100 rounded-md transition-colors">${p}</button>`;
            }
        }

        return `<div class="flex items-center justify-between mt-3">
            <p class="text-xs text-gray-500">Menampilkan ${from}–${to} dari ${total} muzaki</p>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
    }

    // ── Utilities ──────────────────────────────────────────────────────────
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatRupiah(num) {
        return Number(num).toLocaleString('id-ID');
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        return new Date(dateStr).toLocaleDateString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
    }
</script>
@endpush