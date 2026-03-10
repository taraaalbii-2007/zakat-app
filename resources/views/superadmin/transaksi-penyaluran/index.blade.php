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
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalTransaksi) }}</p>
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
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalDraft) }}</p>
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
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($totalDisalurkan) }}</p>
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
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Transaksi Penyaluran Semua Lembaga</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ $totalTransaksi }} Transaksi dari {{ $lembagas->count() }} Lembaga
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
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
                            <form method="GET" action="{{ route('transaksi-penyaluran.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
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
                                        <a href="{{ route('transaksi-penyaluran.index') }}"
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
                class="{{ request()->hasAny(['status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('transaksi-penyaluran.index') }}" id="filter-form">
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

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft (Menunggu)</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="disalurkan" {{ request('status') == 'disalurkan' ? 'selected' : '' }}>Disalurkan</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode Penyaluran</label>
                            <select name="metode_penyaluran"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Metode</option>
                                <option value="tunai" {{ request('metode_penyaluran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ request('metode_penyaluran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="barang" {{ request('metode_penyaluran') == 'barang' ? 'selected' : '' }}>Barang</option>
                            </select>
                        </div>

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

                    @if (request()->hasAny(['status', 'lembaga_id', 'metode_penyaluran', 'start_date', 'end_date']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('transaksi-penyaluran.index', request('q') ? ['q' => request('q')] : []) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Table --}}
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($lembagas as $lembaga)
                            @php
                                $transaksiLembaga = $lembaga->transaksiPenyaluran ?? collect();
                                $nominalTotal = $transaksiLembaga->sum('jumlah');
                                $draftCount = $transaksiLembaga->where('status', 'draft')->count();
                            @endphp
                            <tr class="lembaga-row cursor-pointer hover:bg-purple-50/50 transition-colors"
                                data-nama="{{ strtolower($lembaga->nama) }}"
                                onclick="toggleLembaga('trx-penyaluran-{{ $lembaga->id }}', this)">
                                <td class="px-4 py-3">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat penyaluran</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                        {{ $transaksiLembaga->count() }} Transaksi
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center hidden md:table-cell">
                                    <span class="text-sm font-semibold text-green-700">Rp {{ number_format($nominalTotal, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-3 text-center hidden lg:table-cell">
                                    @if($draftCount > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $draftCount }} Draft
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Expandable: Tabel Penyaluran --}}
                            <tr id="trx-penyaluran-{{ $lembaga->id }}" class="hidden lembaga-content-row">
                                <td colspan="5" class="p-0">
                                    <div class="bg-gradient-to-b from-purple-50/50 to-gray-50 border-y border-purple-200/50 px-6 py-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-1 h-5 bg-purple-500 rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Transaksi Penyaluran — {{ $lembaga->nama }}
                                            </h3>
                                        </div>

                                        @if ($transaksiLembaga->isEmpty())
                                            <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                Belum ada transaksi penyaluran untuk lembaga ini
                                            </div>
                                        @else
                                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-white">
                                                        <tr>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Mustahik</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Metode</th>
                                                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                                            <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-100">
                                                        @foreach ($transaksiLembaga->take(20) as $trx)
                                                            <tr class="hover:bg-gray-50 transition-colors">
                                                                <td class="px-4 py-3">
                                                                    <div class="text-sm font-medium text-gray-900">{{ $trx->mustahik->nama_lengkap ?? '-' }}</div>
                                                                    <div class="text-xs text-gray-400">{{ $trx->no_transaksi }}</div>
                                                                </td>
                                                                <td class="px-4 py-3 hidden sm:table-cell">
                                                                    <div class="text-sm text-gray-700">{{ $trx->tanggal_penyaluran->format('d/m/Y') }}</div>
                                                                </td>
                                                                <td class="px-4 py-3 hidden md:table-cell">
                                                                    <span class="text-sm text-gray-700 capitalize">{{ $trx->metode_penyaluran ?? '-' }}</span>
                                                                </td>
                                                                <td class="px-4 py-3 text-right">
                                                                    <div class="text-sm font-semibold text-green-700">
                                                                        {{ $trx->jumlah > 0 ? 'Rp '.number_format($trx->jumlah, 0, ',', '.') : '-' }}
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 text-center">
                                                                    @if($trx->status == 'disalurkan')
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disalurkan</span>
                                                                    @elseif($trx->status == 'disetujui')
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Disetujui</span>
                                                                    @elseif($trx->status == 'draft')
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                                                    @else
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @if ($transaksiLembaga->count() > 20)
                                                    <div class="px-4 py-2 bg-gray-50 text-xs text-gray-500 text-center border-t border-gray-100">
                                                        Menampilkan 20 dari {{ $transaksiLembaga->count() }} transaksi
                                                    </div>
                                                @endif
                                            </div>
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
    // ── Expandable lembaga rows ────────────────────────────────────────────
    function toggleLembaga(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.lembaga-chevron');
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);
    }

    // ── Toggle Search ─────────────────────────────────────────────────────
    function toggleSearch() {
        var btn = document.getElementById('search-button');
        var form = document.getElementById('search-form');
        var input = document.getElementById('search-input');
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
            var form = document.getElementById('search-form');
            var btn = document.getElementById('search-button');
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