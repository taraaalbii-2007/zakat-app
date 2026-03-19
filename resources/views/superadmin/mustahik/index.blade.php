@extends('layouts.app')

@section('title', 'Data Mustahik Semua Lembaga')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Data Mustahik Semua Lembaga</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ $totalMustahik }} Mustahik dari {{ $lembagas->count() }} Lembaga
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('mustahik.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id'] as $filter)
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
                                    @if (request()->hasAny(['q', 'status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']))
                                        <a href="{{ route('mustahik.index') }}"
                                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('mustahik.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lembaga</label>
                            <select name="lembaga_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Lembaga</option>
                                @foreach ($lembagas as $lembaga)
                                    <option value="{{ $lembaga->id }}" {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                        {{ $lembaga->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Verifikasi</label>
                            <select name="status_verifikasi"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="pending"  {{ request('status_verifikasi') == 'pending'  ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Keaktifan</label>
                            <select name="is_active"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        @if (isset($kategoriList))
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="kategori_id"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    @if (request()->hasAny(['status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('mustahik.index', request('q') ? ['q' => request('q')] : []) }}"
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

            {{-- Outer Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lembaga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Alamat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Mustahik</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($lembagas as $lembaga)
                            {{-- Baris Lembaga --}}
                            <tr class="lembaga-row cursor-pointer hover:bg-primary/5 transition-colors"
                                data-nama="{{ strtolower($lembaga->nama) }}"
                                onclick="toggleLembaga('mustahik-lembaga-{{ $lembaga->id }}', this)">
                                <td class="px-4 py-3">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat mustahik</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 hidden md:table-cell">
                                    <div class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        {{ $lembaga->mustahiks->count() }} Mustahik
                                    </span>
                                </td>
                            </tr>

                            {{-- Expandable Row: Tabel Mustahik dengan JS Pagination --}}
                            <tr id="mustahik-lembaga-{{ $lembaga->id }}" class="hidden lembaga-content-row">
                                <td colspan="4" class="p-0">
                                    <div class="bg-gradient-to-b from-green-50/50 to-gray-50 border-y border-green-200/50 px-6 py-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Daftar Mustahik — {{ $lembaga->nama }}
                                            </h3>
                                        </div>

                                        @if ($lembaga->mustahiks->isEmpty())
                                            <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                Belum ada data mustahik untuk lembaga ini
                                            </div>
                                        @else
                                            {{-- Serialize data mustahik ke JSON untuk JS pagination --}}
                                            @php
                                                $mustahikData = $lembaga->mustahiks->map(function ($m) {
                                                    return [
                                                        'no_registrasi'    => $m->no_registrasi ?? '-',
                                                        'tanggal'          => $m->tanggal_registrasi
                                                                                ? $m->tanggal_registrasi->format('d M Y')
                                                                                : '-',
                                                        'nama'             => $m->nama_lengkap,
                                                        'initial'          => strtoupper(substr($m->nama_lengkap, 0, 1)),
                                                        'nik'              => $m->nik ?? null,
                                                        'kategori'         => $m->kategoriMustahik->nama ?? null,
                                                        'status_verifikasi'=> $m->status_verifikasi,
                                                        'is_active'        => (bool) $m->is_active,
                                                    ];
                                                });
                                            @endphp

                                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                {{-- Tabel --}}
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-white">
                                                        <tr>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">No. Registrasi</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Mustahik</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kategori</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="mustahik-tbody-{{ $lembaga->id }}"
                                                           class="bg-white divide-y divide-gray-100">
                                                        {{-- Diisi oleh JavaScript --}}
                                                    </tbody>
                                                </table>

                                                {{-- Pagination bar --}}
                                                <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                                                    <span id="mustahik-info-{{ $lembaga->id }}"
                                                          class="text-xs text-gray-500"></span>
                                                    <div class="flex items-center gap-1"
                                                         id="mustahik-pagination-{{ $lembaga->id }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Data JSON untuk JS --}}
                                            <script>
                                                window.mustahikData = window.mustahikData || {};
                                                window.mustahikData[{{ $lembaga->id }}] = @json($mustahikData);
                                            </script>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">
                                    Belum ada data lembaga
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
    const MUSTAHIK_PER_PAGE = 10;
    const mustahikPages     = {};

    // ── Render baris mustahik ke tbody ────────────────────────────────────
    function renderMustahikPage(lembagaId, page) {
        const data       = window.mustahikData?.[lembagaId] ?? [];
        const total      = data.length;
        const totalPages = Math.ceil(total / MUSTAHIK_PER_PAGE);
        page = Math.max(1, Math.min(page, totalPages));
        mustahikPages[lembagaId] = page;

        const start = (page - 1) * MUSTAHIK_PER_PAGE;
        const end   = Math.min(start + MUSTAHIK_PER_PAGE, total);
        const slice = data.slice(start, end);

        // ── Render rows ───────────────────────────────────────────────────
        const tbody = document.getElementById(`mustahik-tbody-${lembagaId}`);
        tbody.innerHTML = slice.map(m => {
            // Badge status verifikasi
            const verifikasiMap = {
                verified: 'bg-green-100 text-green-800',
                pending:  'bg-yellow-100 text-yellow-800',
                rejected: 'bg-red-100 text-red-800',
            };
            const verifikasiLabel = { verified: 'Verified', pending: 'Pending', rejected: 'Rejected' };
            const vClass = verifikasiMap[m.status_verifikasi] ?? 'bg-gray-100 text-gray-800';
            const vLabel = verifikasiLabel[m.status_verifikasi] ?? m.status_verifikasi;

            // Badge keaktifan
            const activeHtml = m.is_active
                ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>`
                : `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>`;

            // Badge kategori
            const kategoriHtml = m.kategori
                ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${escHtml(m.kategori)}</span>`
                : `<span class="text-xs text-gray-400">-</span>`;

            // NIK
            const nikHtml = m.nik
                ? `<div class="text-xs text-gray-400">NIK: ${escHtml(m.nik)}</div>`
                : '';

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">${escHtml(m.no_registrasi)}</div>
                    <div class="text-xs text-gray-400">${escHtml(m.tanggal)}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-semibold text-primary">${escHtml(m.initial)}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${escHtml(m.nama)}</div>
                            ${nikHtml}
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 hidden sm:table-cell">${kategoriHtml}</td>
                <td class="px-4 py-3">
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${vClass}">${vLabel}</span>
                        ${activeHtml}
                    </div>
                </td>
            </tr>`;
        }).join('');

        // ── Info ──────────────────────────────────────────────────────────
        document.getElementById(`mustahik-info-${lembagaId}`).textContent =
            `Menampilkan ${start + 1}–${end} dari ${total} mustahik`;

        // ── Pagination ────────────────────────────────────────────────────
        document.getElementById(`mustahik-pagination-${lembagaId}`).innerHTML =
            buildMustahikPagination(lembagaId, page, totalPages);
    }

    // ── Buat tombol pagination ────────────────────────────────────────────
    function buildMustahikPagination(lembagaId, current, total) {
        if (total <= 1) return '';

        const btnBase     = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
        const btnActive   = `${btnBase} bg-primary text-white`;
        const btnNormal   = `${btnBase} text-gray-600 hover:bg-gray-100`;
        const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;

        const prevSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const nextSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;

        let html = '';

        // Prev
        html += current > 1
            ? `<button onclick="renderMustahikPage(${lembagaId}, ${current - 1})" class="${btnNormal}">${prevSvg}</button>`
            : `<button disabled class="${btnDisabled}">${prevSvg}</button>`;

        // Nomor halaman
        mustahikPageRange(current, total).forEach(p => {
            if (p === '...') {
                html += `<span class="${btnBase} text-gray-400">…</span>`;
            } else {
                const cls = p === current ? btnActive : btnNormal;
                html += `<button onclick="renderMustahikPage(${lembagaId}, ${p})" class="${cls}">${p}</button>`;
            }
        });

        // Next
        html += current < total
            ? `<button onclick="renderMustahikPage(${lembagaId}, ${current + 1})" class="${btnNormal}">${nextSvg}</button>`
            : `<button disabled class="${btnDisabled}">${nextSvg}</button>`;

        return html;
    }

    // ── Rentang nomor halaman ─────────────────────────────────────────────
    function mustahikPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        if (current <= 4)          return [1, 2, 3, 4, 5, '...', total];
        if (current >= total - 3)  return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
        return [1, '...', current - 1, current, current + 1, '...', total];
    }

    // ── HTML escape ───────────────────────────────────────────────────────
    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Toggle expandable lembaga rows ────────────────────────────────────
    function toggleLembaga(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.lembaga-chevron');
        const isHidden = content.classList.contains('hidden');

        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);

        // Render halaman 1 pertama kali dibuka
        if (isHidden) {
            const lembagaId = parseInt(id.replace('mustahik-lembaga-', ''));
            if (window.mustahikData?.[lembagaId] && !mustahikPages[lembagaId]) {
                renderMustahikPage(lembagaId, 1);
            }
        }
    }

    // ── Toggle Search ─────────────────────────────────────────────────────
    function toggleSearch() {
        var btn       = document.getElementById('search-button');
        var form      = document.getElementById('search-form');
        var input     = document.getElementById('search-input');
        var container = document.getElementById('search-container');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            container.style.minWidth = '280px';
            setTimeout(function() { input.focus(); }, 50);
        } else {
            form.classList.add('hidden');
            btn.classList.remove('hidden');
            container.style.minWidth = '';
        }
    }

    // ── Toggle Filter Panel ───────────────────────────────────────────────
    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

    // ── ESC menutup search form ───────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var form      = document.getElementById('search-form');
            var btn       = document.getElementById('search-button');
            var container = document.getElementById('search-container');
            if (!form.classList.contains('hidden')) {
                form.classList.add('hidden');
                btn.classList.remove('hidden');
                container.style.minWidth = '';
            }
        }
    });
</script>
@endpush