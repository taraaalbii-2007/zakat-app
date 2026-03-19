@extends('layouts.app')

@section('title', 'Transaksi Penerimaan Semua Masjid')

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
                        <p class="text-xs font-medium text-gray-500">Total Transaksi</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalTransaksi) }}</p>
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
                        <p class="text-xs font-medium text-gray-500">Terverifikasi</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalVerified) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Pending</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalPending) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">Total Nominal</p>
                        <p class="text-xl font-semibold text-gray-900">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
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
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Transaksi Penerimaan Semua Masjid</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ $totalTransaksi }} Transaksi dari {{ $lembagas->count() }} Masjid
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']) ? 'ring-2 ring-primary' : '' }}">
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
                            <form method="GET" action="{{ route('pemantauan-transaksi.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date'] as $filter)
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
                                            id="search-input" placeholder="Cari nama masjid / muzakki..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']))
                                        <a href="{{ route('pemantauan-transaksi.index') }}"
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
                class="{{ request()->hasAny(['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('pemantauan-transaksi.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Masjid</label>
                            <select name="lembaga_id"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Masjid</option>
                                @foreach ($lembagas as $lembaga)
                                    <option value="{{ $lembaga->id }}" {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                        {{ $lembaga->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        @if (isset($jenisZakatList))
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Zakat</label>
                                <select name="jenis_zakat_id"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisZakatList as $jenis)
                                        <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                        </div>
                    </div>
                    @if (request()->hasAny(['status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('pemantauan-transaksi.index', request('q') ? ['q' => request('q')] : []) }}"
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masjid</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Total Nominal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Pending</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($lembagas as $lembaga)
                            @php
                                $transaksiMasjid = $lembaga->transaksiPenerimaan ?? collect();
                                $nominalTotal    = $transaksiMasjid->sum('jumlah');
                                $pendingCount    = $transaksiMasjid->where('status', 'pending')->count();
                            @endphp

                            {{-- Baris Lembaga (ikon masjid dihapus) --}}
                            <tr class="lembaga-row cursor-pointer hover:bg-amber-50/50 transition-colors"
                                data-nama="{{ strtolower($lembaga->nama) }}"
                                onclick="toggleMasjid('trx-penerimaan-{{ $lembaga->id }}', this)">
                                <td class="px-4 py-3">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-3">
                                    {{-- Ikon dihapus, langsung teks --}}
                                    <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat transaksi</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                        {{ $transaksiMasjid->count() }} Transaksi
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center hidden md:table-cell">
                                    <span class="text-sm font-semibold text-green-700">Rp {{ number_format($nominalTotal, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-3 text-center hidden lg:table-cell">
                                    @if($pendingCount > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ $pendingCount }} Pending
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Expandable: Tabel Transaksi dengan JS Pagination --}}
                            <tr id="trx-penerimaan-{{ $lembaga->id }}" class="hidden lembaga-content-row">
                                <td colspan="5" class="p-0">
                                    <div class="bg-gradient-to-b from-blue-50/50 to-gray-50 border-y border-blue-200/50 px-6 py-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-1 h-5 bg-primary-500 rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Transaksi Penerimaan — {{ $lembaga->nama }}
                                            </h3>
                                        </div>

                                        @if ($transaksiMasjid->isEmpty())
                                            <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                Belum ada transaksi penerimaan untuk lembaga ini
                                            </div>
                                        @else
                                            {{-- Serialize data transaksi ke JSON untuk JS pagination --}}
                                            @php
                                                $trxData = $transaksiMasjid->map(function ($trx) {
                                                    return [
                                                        'muzakki_nama'    => $trx->muzakki_nama ?? '-',
                                                        'no_transaksi'    => $trx->no_transaksi ?? '-',
                                                        'tanggal'         => optional($trx->tanggal_transaksi)->format('d/m/Y') ?? '-',
                                                        'waktu'           => optional($trx->waktu_transaksi)->format('H:i') ?? '',
                                                        'jenis_zakat'     => $trx->jenisZakat->nama ?? '-',
                                                        'jumlah'          => (float) ($trx->jumlah ?? 0),
                                                        'status'          => $trx->status ?? '-',
                                                    ];
                                                });
                                            @endphp

                                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-white">
                                                        <tr>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Muzakki</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Jenis Zakat</th>
                                                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="trx-tbody-{{ $lembaga->id }}"
                                                           class="bg-white divide-y divide-gray-100">
                                                        {{-- Diisi oleh JavaScript --}}
                                                    </tbody>
                                                </table>

                                                {{-- Pagination bar --}}
                                                <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                                                    <span id="trx-info-{{ $lembaga->id }}" class="text-xs text-gray-500"></span>
                                                    <div class="flex items-center gap-1" id="trx-pagination-{{ $lembaga->id }}"></div>
                                                </div>
                                            </div>

                                            {{-- Data JSON untuk JS --}}
                                            <script>
                                                window.trxData = window.trxData || {};
                                                window.trxData[{{ $lembaga->id }}] = @json($trxData);
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
    const TRX_PER_PAGE = 10;
    const trxPages     = {};

    // ── Render baris transaksi ke tbody ───────────────────────────────────
    function renderTrxPage(lembagaId, page) {
        const data       = window.trxData?.[lembagaId] ?? [];
        const total      = data.length;
        const totalPages = Math.ceil(total / TRX_PER_PAGE);
        page = Math.max(1, Math.min(page, totalPages));
        trxPages[lembagaId] = page;

        const start = (page - 1) * TRX_PER_PAGE;
        const end   = Math.min(start + TRX_PER_PAGE, total);
        const slice = data.slice(start, end);

        const tbody = document.getElementById(`trx-tbody-${lembagaId}`);
        tbody.innerHTML = slice.map(t => {
            // Badge status
            const statusMap = {
                verified: 'bg-green-100 text-green-800',
                pending:  'bg-amber-100 text-amber-800',
                rejected: 'bg-red-100 text-red-800',
            };
            const statusLabel = { verified: 'Verified', pending: 'Pending', rejected: 'Rejected' };
            const sCls   = statusMap[t.status]   ?? 'bg-gray-100 text-gray-600';
            const sLabel = statusLabel[t.status] ?? t.status;

            // Jumlah
            const jumlahHtml = t.jumlah > 0
                ? `<span class="text-sm font-semibold text-green-700">Rp ${Number(t.jumlah).toLocaleString('id-ID')}</span>`
                : `<span class="text-sm text-gray-400">-</span>`;

            // Waktu (opsional)
            const waktuHtml = t.waktu
                ? `<div class="text-xs text-gray-400">${escHtml(t.waktu)}</div>`
                : '';

            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">${escHtml(t.muzakki_nama)}</div>
                    <div class="text-xs text-gray-400">${escHtml(t.no_transaksi)}</div>
                </td>
                <td class="px-4 py-3 hidden sm:table-cell">
                    <div class="text-sm text-gray-700">${escHtml(t.tanggal)}</div>
                    ${waktuHtml}
                </td>
                <td class="px-4 py-3 hidden md:table-cell">
                    <div class="text-sm text-gray-700">${escHtml(t.jenis_zakat)}</div>
                </td>
                <td class="px-4 py-3 text-right">${jumlahHtml}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${sCls}">${sLabel}</span>
                </td>
            </tr>`;
        }).join('');

        // ── Info ──────────────────────────────────────────────────────────
        document.getElementById(`trx-info-${lembagaId}`).textContent =
            `Menampilkan ${start + 1}–${end} dari ${total} transaksi`;

        // ── Pagination ────────────────────────────────────────────────────
        document.getElementById(`trx-pagination-${lembagaId}`).innerHTML =
            buildTrxPagination(lembagaId, page, totalPages);
    }

    // ── Buat tombol pagination ────────────────────────────────────────────
    function buildTrxPagination(lembagaId, current, total) {
        if (total <= 1) return '';

        const btnBase     = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
        const btnActive   = `${btnBase} bg-primary text-white`;
        const btnNormal   = `${btnBase} text-gray-600 hover:bg-gray-100`;
        const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;
        const prevSvg     = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>`;
        const nextSvg     = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;

        let html = '';

        html += current > 1
            ? `<button onclick="renderTrxPage(${lembagaId}, ${current - 1})" class="${btnNormal}">${prevSvg}</button>`
            : `<button disabled class="${btnDisabled}">${prevSvg}</button>`;

        trxPageRange(current, total).forEach(p => {
            if (p === '...') {
                html += `<span class="${btnBase} text-gray-400">…</span>`;
            } else {
                const cls = p === current ? btnActive : btnNormal;
                html += `<button onclick="renderTrxPage(${lembagaId}, ${p})" class="${cls}">${p}</button>`;
            }
        });

        html += current < total
            ? `<button onclick="renderTrxPage(${lembagaId}, ${current + 1})" class="${btnNormal}">${nextSvg}</button>`
            : `<button disabled class="${btnDisabled}">${nextSvg}</button>`;

        return html;
    }

    // ── Rentang nomor halaman ─────────────────────────────────────────────
    function trxPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        if (current <= 4)         return [1, 2, 3, 4, 5, '...', total];
        if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
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

    // ── Toggle expandable masjid rows ─────────────────────────────────────
    function toggleMasjid(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.lembaga-chevron');
        const isHidden = content.classList.contains('hidden');

        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);

        // Render halaman 1 pertama kali dibuka
        if (isHidden) {
            const lembagaId = parseInt(id.replace('trx-penerimaan-', ''));
            if (window.trxData?.[lembagaId] && !trxPages[lembagaId]) {
                renderTrxPage(lembagaId, 1);
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