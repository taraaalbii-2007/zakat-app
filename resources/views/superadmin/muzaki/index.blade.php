@extends('layouts.app')

@section('title', 'Data Muzaki Semua Lembaga')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Data Muzaki Semua Lembaga</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi data muzaki dari seluruh lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'lembaga_id']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($stats['total_muzakki_unik'] ?? $totalMuzaki ?? 0) }}</span>
                        <span class="text-sm text-gray-500">Muzaki dari</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $lembagas->count() }}</span>
                        <span class="text-sm text-gray-500">Lembaga</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'lembaga_id']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('muzaki.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Lembaga/Muzaki</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari nama lembaga atau muzaki..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Lembaga -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Lembaga</label>
                                <select name="lembaga_id"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Lembaga</option>
                                    @foreach ($lembagas as $lembaga)
                                        <option value="{{ $lembaga->id }}" {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                            {{ $lembaga->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div></div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'lembaga_id']))
                            <a href="{{ route('muzaki.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Filter Tags -->
            @if(request()->hasAny(['q', 'lembaga_id']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if(request('q'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('lembaga_id'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Lembaga: {{ $lembagas->firstWhere('id', request('lembaga_id'))?->nama ?? request('lembaga_id') }}
                                <button onclick="removeFilter('lembaga_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($lembagas->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-4 text-center w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500">LEMBAGA</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 hidden lg:table-cell">ALAMAT</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500">JUMLAH MUZAKI</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 hidden lg:table-cell">TOTAL NOMINAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lembagas as $lembaga)
                                @php
                                    // Kumpulkan data muzaki dari lembaga ini
                                    $muzakiArray = collect($lembaga->muzakkis ?? [])->map(function($m) use ($lembaga) {
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
                                    })->toArray();
                                    
                                    $totalNominalLembaga = collect($muzakiArray)->sum('total_nominal');
                                @endphp
                                
                                <tr class="border-b border-gray-100 hover:bg-green-50/20 cursor-pointer expandable-row"
                                    data-target="detail-{{ $lembaga->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="text-sm font-medium text-gray-800 group-hover:text-green-700">
                                                {{ $lembaga->nama }}
                                            </span>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat muzaki</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            {{ collect($muzakiArray)->count() }} Muzaki
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center hidden lg:table-cell">
                                        <span class="text-sm font-semibold text-gray-700">
                                            Rp {{ number_format($totalNominalLembaga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>

                                <!-- Expandable Row dengan Pagination -->
                                <tr id="detail-{{ $lembaga->id }}" class="hidden border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="4" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Daftar Muzaki — {{ $lembaga->nama }}</h3>
                                            </div>

                                            @if (empty($muzakiArray))
                                                <div class="text-center py-8 text-sm text-gray-400 bg-white rounded-xl border">Belum ada data muzaki</div>
                                            @else
                                                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">NAMA</th>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">JENIS ZAKAT</th>
                                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">TOTAL</th>
                                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 hidden md:table-cell">TRANSAKSI</th>
                                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">AKSI</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="muzaki-tbody-{{ $lembaga->id }}"></tbody>
                                                    </table>
                                                </div>
                                                <div class="bg-gray-50 border border-gray-100 px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-lg">
                                                    <span id="muzaki-info-{{ $lembaga->id }}" class="text-xs text-gray-500"></span>
                                                    <div id="muzaki-pagination-{{ $lembaga->id }}" class="flex items-center justify-center gap-1"></div>
                                                </div>
                                                
                                                <script>
                                                    if (typeof window.muzakiData === 'undefined') window.muzakiData = {};
                                                    window.muzakiData[{{ $lembaga->id }}] = @json($muzakiArray);
                                                </script>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW (DIPERBAIKI) ==================== -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($lembagas as $lembaga)
                        @php
                            $muzakiArray = collect($lembaga->muzakkis ?? [])->map(function($m) use ($lembaga) {
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
                            })->toArray();
                            
                            $totalNominalLembaga = collect($muzakiArray)->sum('total_nominal');
                        @endphp
                        
                        <div class="p-4">
                            <!-- Header Card (klik untuk expand) - HANYA SATU ICON -->
                            <div class="expandable-row-mobile cursor-pointer" 
                                data-target="detail-mobile-{{ $lembaga->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-400">Lembaga</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words pr-2">
                                            {{ $lembaga->nama }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                                                {{ count($muzakiArray) }} Muzaki
                                            </span>
                                            <span class="text-xs font-semibold text-gray-600">
                                                Rp {{ number_format($totalNominalLembaga, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- HANYA SATU CHEVRON (tidak ada icon lain) -->
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile-chevron" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $lembaga->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    @if ($lembaga->alamat)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat</h4>
                                            <p class="text-sm text-gray-600">{{ $lembaga->alamat }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Daftar Muzaki</h4>
                                        @if (empty($muzakiArray))
                                            <p class="text-sm text-gray-400 italic">Belum ada data muzaki</p>
                                        @else
                                            <div class="space-y-3" id="mobile-muzaki-container-{{ $lembaga->id }}">
                                                <!-- Akan diisi JS -->
                                            </div>
                                            <div class="mt-3 flex justify-center" id="mobile-pagination-{{ $lembaga->id }}"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            if (typeof window.muzakiDataMobile === 'undefined') window.muzakiDataMobile = {};
                            window.muzakiDataMobile[{{ $lembaga->id }}] = @json($muzakiArray);
                        </script>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    @if(request('q') || request('lembaga_id'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('muzaki.index') }}" class="text-sm text-green-600 hover:text-green-700">Reset semua filter</a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data lembaga</p>
                        <a href="{{ route('lembaga.create') }}" class="inline-flex items-center gap-1 text-sm text-green-600">Tambah lembaga sekarang</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .rotate-90 { transform: rotate(90deg); }
        .rotate-180 { transform: rotate(180deg); }
    </style>
@endsection

@push('scripts')
<script>
const MUZAKI_PER_PAGE = 10;
const muzakiPages = {};

function renderMuzakiPage(lembagaId, page) {
    const data = window.muzakiData?.[lembagaId] ?? [];
    const total = data.length;
    const totalPages = Math.ceil(total / MUZAKI_PER_PAGE) || 1;
    page = Math.max(1, Math.min(page, totalPages));
    muzakiPages[lembagaId] = page;

    const start = (page - 1) * MUZAKI_PER_PAGE;
    const slice = data.slice(start, start + MUZAKI_PER_PAGE);
    const end = Math.min(start + slice.length, total);

    // Desktop
    const tbody = document.getElementById(`muzaki-tbody-${lembagaId}`);
    if (tbody) {
        if (slice.length === 0) {
            tbody.innerHTML = `<td><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada data muzaki</td></tr>`;
        } else {
            tbody.innerHTML = slice.map(m => {
                const jenisHtml = m.jenis_zakat && m.jenis_zakat.length > 0
                    ? m.jenis_zakat.map(j => `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">${escapeHtml(j)}</span>`).join(' ')
                    : '<span class="text-xs text-gray-400">-</span>';
                
                const teleponHtml = m.telepon ? `<div class="text-xs text-gray-400 mt-0.5">${escapeHtml(m.telepon)}</div>` : '';
                const emailShort = m.email && m.email.length > 28 ? m.email.substring(0, 28) + '…' : (m.email ?? '');
                const emailHtml = emailShort ? `<div class="text-xs text-gray-400">${escapeHtml(emailShort)}</div>` : '';
                
                const nominalFormatted = 'Rp ' + Number(m.total_nominal).toLocaleString('id-ID');

                return `
                <tr class="hover:bg-gray-50">
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
                    <td class="px-4 py-3 text-center">
                        <div>
                            <span class="text-sm font-semibold text-gray-800">${nominalFormatted}</span>
                            <div class="text-xs text-gray-400">${m.total_transaksi}x transaksi</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center hidden md:table-cell">
                        <span class="text-xs text-gray-600">${escapeHtml(m.transaksi_terakhir)}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="${escapeHtml(m.detail_url)}"
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
        }
    }

    const info = document.getElementById(`muzaki-info-${lembagaId}`);
    if (info) info.textContent = total > 0 ? `Menampilkan ${start + 1}–${end} dari ${total} muzaki` : 'Tidak ada data';

    const pag = document.getElementById(`muzaki-pagination-${lembagaId}`);
    if (pag) pag.innerHTML = buildPagination(lembagaId, page, totalPages);

    // Mobile view
    renderMobileMuzaki(lembagaId, page, totalPages);
}

function renderMobileMuzaki(lembagaId, page, totalPages) {
    const data = window.muzakiDataMobile?.[lembagaId] ?? [];
    const total = data.length;
    const start = (page - 1) * MUZAKI_PER_PAGE;
    const slice = data.slice(start, start + MUZAKI_PER_PAGE);
    const end = Math.min(start + slice.length, total);

    const container = document.getElementById(`mobile-muzaki-container-${lembagaId}`);
    if (container) {
        if (slice.length === 0) {
            container.innerHTML = `<p class="text-sm text-gray-400 italic">Tidak ada data muzaki</p>`;
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
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-sm font-semibold text-green-600">${escapeHtml(m.initial)}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">${escapeHtml(m.nama)}</p>
                                    ${teleponHtml}
                                    ${emailHtml}
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-800">${nominalFormatted}</p>
                                    <p class="text-xs text-gray-400">${m.total_transaksi}x transaksi</p>
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

    const mobilePag = document.getElementById(`mobile-pagination-${lembagaId}`);
    if (mobilePag) mobilePag.innerHTML = buildMobilePagination(lembagaId, page, totalPages);
}

function buildPagination(lembagaId, current, total) {
    if (total <= 1) return '';
    const btnBase = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
    const btnActive = `${btnBase} bg-green-600 text-white`;
    const btnNormal = `${btnBase} text-gray-600 hover:bg-gray-100`;
    const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;
    
    let html = '';
    if (current > 1) html += `<button type="button" onclick="renderMuzakiPage(${lembagaId}, ${current - 1})" class="${btnNormal}">‹</button>`;
    else html += `<button disabled class="${btnDisabled}">‹</button>`;
    
    const range = getPageRange(current, total);
    range.forEach(p => {
        if (p === '...') html += `<span class="${btnBase} text-gray-400">…</span>`;
        else html += `<button type="button" onclick="renderMuzakiPage(${lembagaId}, ${p})" class="${p === current ? btnActive : btnNormal}">${p}</button>`;
    });
    
    if (current < total) html += `<button type="button" onclick="renderMuzakiPage(${lembagaId}, ${current + 1})" class="${btnNormal}">›</button>`;
    else html += `<button disabled class="${btnDisabled}">›</button>`;
    return html;
}

function buildMobilePagination(lembagaId, current, total) {
    if (total <= 1) return '';
    const btnBase = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
    const btnNormal = `${btnBase} text-gray-600 hover:bg-gray-100`;
    const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;
    
    let html = '<div class="flex items-center gap-1">';
    if (current > 1) html += `<button type="button" onclick="renderMuzakiPage(${lembagaId}, ${current - 1})" class="${btnNormal}">‹</button>`;
    else html += `<button disabled class="${btnDisabled}">‹</button>`;
    html += `<span class="text-xs text-gray-500 mx-2">Halaman ${current} dari ${total}</span>`;
    if (current < total) html += `<button type="button" onclick="renderMuzakiPage(${lembagaId}, ${current + 1})" class="${btnNormal}">›</button>`;
    else html += `<button disabled class="${btnDisabled}">›</button>`;
    html += '</div>';
    return html;
}

function getPageRange(current, total) {
    if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
    if (current <= 4) return [1, 2, 3, 4, 5, '...', total];
    if (current >= total - 3) return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
    return [1, '...', current - 1, current, current + 1, '...', total];
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterPanel = document.getElementById('filterPanel');
    
    if (filterButton && filterPanel) {
        filterButton.addEventListener('click', () => filterPanel.classList.toggle('hidden'));
    }

    // Desktop expandable
    document.querySelectorAll('.expandable-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const targetId = this.getAttribute('data-target');
            const targetRow = document.getElementById(targetId);
            const icon = this.querySelector('.expand-icon');
            if (targetRow) {
                const isHidden = targetRow.classList.contains('hidden');
                targetRow.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-90');
                if (isHidden) {
                    const lembagaId = parseInt(targetId.replace('detail-', ''));
                    if ((window.muzakiData?.[lembagaId] || window.muzakiDataMobile?.[lembagaId]) && !muzakiPages[lembagaId]) {
                        renderMuzakiPage(lembagaId, 1);
                    }
                }
            }
        });
    });

    // Mobile expandable - HANYA SATU ICON (chevron)
    document.querySelectorAll('.expandable-row-mobile').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const chevron = this.querySelector('.expand-icon-mobile-chevron');
            if (targetContent) {
                const isHidden = targetContent.classList.contains('hidden');
                targetContent.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-90');
                if (isHidden) {
                    const lembagaId = parseInt(targetId.replace('detail-mobile-', ''));
                    if ((window.muzakiData?.[lembagaId] || window.muzakiDataMobile?.[lembagaId]) && !muzakiPages[lembagaId]) {
                        renderMuzakiPage(lembagaId, 1);
                    }
                }
            }
        });
    });

    // Render untuk lembaga yang sudah terbuka
    document.querySelectorAll('.expandable-content:not(.hidden)').forEach(content => {
        const id = content.getAttribute('id');
        if (id && id.startsWith('detail-')) {
            const lembagaId = parseInt(id.replace('detail-', ''));
            if ((window.muzakiData?.[lembagaId] || window.muzakiDataMobile?.[lembagaId]) && !muzakiPages[lembagaId]) {
                renderMuzakiPage(lembagaId, 1);
            }
        }
    });
});

function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}

function toggleFilter() {
    const filterPanel = document.getElementById('filterPanel');
    if (filterPanel) {
        filterPanel.classList.add('hidden');
    }
}
</script>
@endpush