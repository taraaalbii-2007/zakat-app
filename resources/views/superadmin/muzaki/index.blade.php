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
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ number_format($stats['total_muzakki_unik']) }} Muzaki dari {{ $lembagas->count() }} Lembaga
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['lembaga_id']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('muzaki.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if (request('lembaga_id'))
                                    <input type="hidden" name="lembaga_id" value="{{ request('lembaga_id') }}">
                                @endif
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}"
                                            id="search-input" placeholder="Cari nama lembaga / muzaki..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'lembaga_id']))
                                        <a href="{{ route('muzaki.index') }}"
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
                class="{{ request()->hasAny(['lembaga_id']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('muzaki.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
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
                    </div>
                    @if (request()->hasAny(['lembaga_id']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('muzaki.index', request('q') ? ['q' => request('q')] : []) }}"
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
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Muzaki</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tbody-lembaga">
                        @forelse ($lembagas as $lembaga)
                            {{-- Baris Lembaga --}}
                            <tr class="lembaga-row cursor-pointer hover:bg-primary/5 transition-colors"
                                data-nama="{{ strtolower($lembaga->nama) }}"
                                onclick="toggleLembaga('lembaga-{{ $lembaga->id }}', this)">
                                <td class="px-4 py-3">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        {{-- Hapus ikon lembaga, langsung teks --}}
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat muzaki</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 hidden md:table-cell">
                                    <div class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        {{ $lembaga->muzakkiCount }} Muzaki
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center hidden lg:table-cell">
                                    <span class="text-sm font-semibold text-gray-700">
                                        Rp {{ number_format($lembaga->totalNominal ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>

                            {{-- Expandable Row: Tabel Muzaki dengan JS Pagination --}}
                            <tr id="lembaga-{{ $lembaga->id }}" class="hidden lembaga-content-row">
                                <td colspan="5" class="p-0">
                                    <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Daftar Muzaki — {{ $lembaga->nama }}
                                            </h3>
                                        </div>

                                        @if ($lembaga->muzakkis->isEmpty())
                                            <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                Belum ada data muzaki untuk lembaga ini
                                            </div>
                                        @else
                                            @php
                                                $muzakiData = $lembaga->muzakkis->map(function ($m) use ($lembaga) {
                                                    // Jenis zakat dari GROUP_CONCAT di controller
                                                    $jenisZakat = collect(
                                                        array_filter(explode(',', $m->jenis_zakat_list ?? ''))
                                                    )->unique()->values();
                                                    return [
                                                        'nama'              => $m->muzakki_nama,
                                                        'initial'           => strtoupper(substr($m->muzakki_nama, 0, 1)),
                                                        'nik'               => $m->muzakki_nik ?? null,
                                                        'telepon'           => $m->muzakki_telepon ?? null,
                                                        'email'             => $m->muzakki_email ?? null,
                                                        'jenis_zakat'       => $jenisZakat->toArray(),
                                                        'total_transaksi'   => $m->total_transaksi,
                                                        'total_nominal'     => (int) $m->total_nominal,
                                                        'transaksi_terakhir'=> $m->transaksi_terakhir
                                                            ? \Carbon\Carbon::parse($m->transaksi_terakhir)->translatedFormat('d M Y')
                                                            : '-',
                                                        'detail_url'        => route('muzaki.show', [
                                                            'nama'       => $m->muzakki_nama,
                                                            'lembaga_id' => $lembaga->id,
                                                        ]),
                                                    ];
                                                });
                                            @endphp

                                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                {{-- Tabel --}}
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-white">
                                                        <tr>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Muzaki</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Jenis Zakat</th>
                                                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                                                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Total Nominal</th>
                                                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Terakhir</th>
                                                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="muzaki-tbody-{{ $lembaga->id }}"
                                                           class="bg-white divide-y divide-gray-100">
                                                        {{-- Diisi oleh JavaScript --}}
                                                    </tbody>
                                                </table>

                                                {{-- Pagination bar --}}
                                                <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                                                    <span id="muzaki-info-{{ $lembaga->id }}"
                                                          class="text-xs text-gray-500"></span>
                                                    <div class="flex items-center gap-1"
                                                         id="muzaki-pagination-{{ $lembaga->id }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Data JSON untuk JS --}}
                                            <script>
                                                window.muzakiData = window.muzakiData || {};
                                                window.muzakiData[{{ $lembaga->id }}] = @json($muzakiData);
                                            </script>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
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
    const MUZAKI_PER_PAGE = 10;
    const muzakiPages     = {};

    // ── Render baris muzaki ke tbody ──────────────────────────────────────
    function renderMuzakiPage(lembagaId, page) {
        const data       = window.muzakiData?.[lembagaId] ?? [];
        const total      = data.length;
        const totalPages = Math.ceil(total / MUZAKI_PER_PAGE);
        page = Math.max(1, Math.min(page, totalPages));
        muzakiPages[lembagaId] = page;

        const start = (page - 1) * MUZAKI_PER_PAGE;
        const end   = Math.min(start + MUZAKI_PER_PAGE, total);
        const slice = data.slice(start, end);

        const tbody = document.getElementById(`muzaki-tbody-${lembagaId}`);
        tbody.innerHTML = slice.map(m => {

            // ── 1. Kolom Muzaki: nama + telepon + email di bawah nama ────
            const teleponHtml = m.telepon
                ? `<div class="text-xs text-gray-400 mt-0.5">${escHtml(m.telepon)}</div>`
                : '';
            const emailShort  = m.email && m.email.length > 28
                ? m.email.substring(0, 28) + '…'
                : (m.email ?? '');
            const emailHtml   = emailShort
                ? `<div class="text-xs text-gray-400">${escHtml(emailShort)}</div>`
                : '';

            // ── 2. Kolom Jenis Zakat ──────────────────────────────────────
            let jenisHtml = '<span class="text-xs text-gray-400">-</span>';
            if (m.jenis_zakat && m.jenis_zakat.length > 0) {
                jenisHtml = m.jenis_zakat
                    .map(j => `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary">${escHtml(j)}</span>`)
                    .join(' ');
            }

            // ── 3. Kolom Transaksi: plain text "2x" ──────────────────────
            const trxHtml = `<span class="text-sm font-semibold text-gray-700">${m.total_transaksi}x</span>`;

            // ── Total nominal ─────────────────────────────────────────────
            const nominalFormatted = 'Rp ' + Number(m.total_nominal).toLocaleString('id-ID');

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-green-700">${escHtml(m.initial)}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${escHtml(m.nama)}</div>
                            ${teleponHtml}
                            ${emailHtml}
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 hidden sm:table-cell">
                    <div class="flex flex-wrap gap-1">${jenisHtml}</div>
                </td>
                <td class="px-4 py-3 text-center">
                    ${trxHtml}
                </td>
                <td class="px-4 py-3 text-right hidden md:table-cell">
                    <p class="text-sm font-semibold text-gray-800">${nominalFormatted}</p>
                </td>
                <td class="px-4 py-3 text-center hidden lg:table-cell">
                    <p class="text-xs text-gray-700">${escHtml(m.transaksi_terakhir)}</p>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="${m.detail_url}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#2d6a2d] bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
                </td>
            </tr>`;
        }).join('');

        // ── Info ──────────────────────────────────────────────────────────
        document.getElementById(`muzaki-info-${lembagaId}`).textContent =
            `Menampilkan ${start + 1}–${end} dari ${total} muzaki`;

        // ── Pagination ────────────────────────────────────────────────────
        document.getElementById(`muzaki-pagination-${lembagaId}`).innerHTML =
            buildMuzakiPagination(lembagaId, page, totalPages);
    }

    // ── Buat tombol pagination ────────────────────────────────────────────
    function buildMuzakiPagination(lembagaId, current, total) {
        if (total <= 1) return '';

        const btnBase     = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
        const btnActive   = `${btnBase} bg-primary text-white`;
        const btnNormal   = `${btnBase} text-gray-600 hover:bg-gray-100`;
        const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;

        const prevSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const nextSvg = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;

        let html = '';

        html += current > 1
            ? `<button onclick="renderMuzakiPage(${lembagaId}, ${current - 1})" class="${btnNormal}">${prevSvg}</button>`
            : `<button disabled class="${btnDisabled}">${prevSvg}</button>`;

        muzakiPageRange(current, total).forEach(p => {
            if (p === '...') {
                html += `<span class="${btnBase} text-gray-400">…</span>`;
            } else {
                const cls = p === current ? btnActive : btnNormal;
                html += `<button onclick="renderMuzakiPage(${lembagaId}, ${p})" class="${cls}">${p}</button>`;
            }
        });

        html += current < total
            ? `<button onclick="renderMuzakiPage(${lembagaId}, ${current + 1})" class="${btnNormal}">${nextSvg}</button>`
            : `<button disabled class="${btnDisabled}">${nextSvg}</button>`;

        return html;
    }

    function muzakiPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        if (current <= 4)         return [1, 2, 3, 4, 5, '...', total];
        if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
        return [1, '...', current - 1, current, current + 1, '...', total];
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function toggleLembaga(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.lembaga-chevron');
        const isHidden = content.classList.contains('hidden');

        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);

        if (isHidden) {
            const lembagaId = parseInt(id.replace('lembaga-', ''));
            if (window.muzakiData?.[lembagaId] && !muzakiPages[lembagaId]) {
                renderMuzakiPage(lembagaId, 1);
            }
        }
    }

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

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }

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