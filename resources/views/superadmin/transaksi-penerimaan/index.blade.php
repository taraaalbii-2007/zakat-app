@extends('layouts.app')

@section('title', 'Transaksi Penerimaan Semua Lembaga')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

             <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Transaksi Penerimaan Semua Lembaga</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi transaksi penerimaan dari seluruh lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar - DIPERBAIKI -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($totalTransaksi) }}</span>
                        <span class="text-sm text-gray-500">Transaksi dari</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $lembagas->count() }}</span>
                        <span class="text-sm text-gray-500">Lembaga</span>
                    </div>

                    <!-- Stats Ringkasan Desktop -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Verified:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($totalVerified) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($totalPending) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.transaksi-penerimaan.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Transaksi</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari no transaksi / muzakki..."
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

                            <!-- Filter Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status Verifikasi</label>
                                <select name="status"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Filter Jenis Zakat -->
                            @if(isset($jenisZakatList))
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Zakat</label>
                                <select name="jenis_zakat_id"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisZakatList as $jenis)
                                        <option value="{{ $jenis->id }}" {{ request('jenis_zakat_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Tanggal Mulai -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']))
                            <a href="{{ route('superadmin.transaksi-penerimaan.index') }}"
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

            <!-- Active Filter Tags - DIPERBAIKI -->
            @if(request()->hasAny(['q', 'status', 'lembaga_id', 'jenis_zakat_id', 'start_date', 'end_date']))
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
                        @if(request('status'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ request('status') }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('lembaga_id'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Lembaga: {{ $lembagas->firstWhere('id', request('lembaga_id'))?->nama ?? request('lembaga_id') }}
                                <button onclick="removeFilter('lembaga_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('jenis_zakat_id') && isset($jenisZakatList))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Jenis Zakat: {{ $jenisZakatList->firstWhere('id', request('jenis_zakat_id'))?->nama ?? request('jenis_zakat_id') }}
                                <button onclick="removeFilter('jenis_zakat_id')" class="hover:text-green-900 ml-1">×</button>
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
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500">TOTAL TRANSAKSI</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 hidden lg:table-cell">TOTAL NOMINAL</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach ($lembagas as $lembaga)
                                @php
                                    $transaksiArray = collect($lembaga->transaksiPenerimaan ?? [])->map(function($t) {
                                        return [
                                            'id' => $t->id,
                                            'no_transaksi' => $t->no_transaksi ?? '-',
                                            'tanggal' => optional($t->tanggal_transaksi)->format('d M Y') ?? '-',
                                            'waktu' => optional($t->created_at)->format('H:i') ?? '-',
                                            'muzakki_nama' => $t->muzakki_nama ?? '-',
                                            'jenis_zakat' => $t->jenisZakat->nama ?? '-',
                                            'jumlah' => $t->jumlah ?? 0,
                                            'status' => $t->status_verifikasi ?? 'pending',
                                        ];
                                    })->toArray();
                                    
                                    $totalNominalLembaga = collect($transaksiArray)->sum('jumlah');
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
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat transaksi</div>
                                        </div>
                                     </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</span>
                                     </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            {{ count($transaksiArray) }} Transaksi
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
                                                <h3 class="text-sm font-semibold text-gray-800">Daftar Transaksi — {{ $lembaga->nama }}</h3>
                                            </div>

                                            @if (empty($transaksiArray))
                                                <div class="text-center py-8 text-sm text-gray-400 bg-white rounded-xl border">Belum ada data transaksi</div>
                                            @else
                                                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">NO. TRANSAKSI</th>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">MUZAKKI</th>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">JENIS ZAKAT</th>
                                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">JUMLAH</th>
                                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">STATUS</th>
                                                             </tr>
                                                        </thead>
                                                        <tbody id="transaksi-tbody-{{ $lembaga->id }}"></tbody>
                                                     </table>
                                                </div>
                                                <div class="bg-gray-50 border border-gray-100 px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-lg">
                                                    <span id="transaksi-info-{{ $lembaga->id }}" class="text-xs text-gray-500"></span>
                                                    <div id="transaksi-pagination-{{ $lembaga->id }}" class="flex items-center justify-center gap-1"></div>
                                                </div>
                                                
                                                <script>
                                                    if (typeof window.transaksiData === 'undefined') window.transaksiData = {};
                                                    window.transaksiData[{{ $lembaga->id }}] = @json($transaksiArray);
                                                </script>
                                            @endif
                                        </div>
                                     </td>
                                 </tr>
                            @endforeach
                        </tbody>
                     </table>
                </div>

                <!-- MOBILE VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($lembagas as $lembaga)
                        @php
                            $transaksiArray = collect($lembaga->transaksiPenerimaan ?? [])->map(function($t) {
                                return [
                                    'id' => $t->id,
                                    'no_transaksi' => $t->no_transaksi ?? '-',
                                    'tanggal' => optional($t->tanggal_transaksi)->format('d M Y') ?? '-',
                                    'waktu' => optional($t->created_at)->format('H:i') ?? '-',
                                    'muzakki_nama' => $t->muzakki_nama ?? '-',
                                    'initial' => strtoupper(substr($t->muzakki_nama ?? 'T', 0, 1)),
                                    'jenis_zakat' => $t->jenisZakat->nama ?? '-',
                                    'jumlah' => $t->jumlah ?? 0,
                                    'status' => $t->status_verifikasi ?? 'pending',
                                ];
                            })->toArray();
                            
                            $totalNominalLembaga = collect($transaksiArray)->sum('jumlah');
                        @endphp
                        
                        <div class="p-4">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $lembaga->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile" 
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Lembaga</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800">{{ $lembaga->nama }}</h3>
                                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded-full">
                                                {{ count($transaksiArray) }} Transaksi
                                            </span>
                                            <span class="text-xs font-semibold text-gray-600">
                                                Rp {{ number_format($totalNominalLembaga, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile-chevron" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                            <div id="detail-mobile-{{ $lembaga->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    @if ($lembaga->alamat)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500">Alamat</h4>
                                            <p class="text-sm text-gray-600">{{ $lembaga->alamat }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 mb-2">Daftar Transaksi</h4>
                                        @if (empty($transaksiArray))
                                            <p class="text-sm text-gray-400 italic">Belum ada data transaksi</p>
                                        @else
                                            <div class="space-y-3" id="mobile-transaksi-container-{{ $lembaga->id }}">
                                                <!-- Akan diisi JS -->
                                            </div>
                                            <div class="mt-3 flex justify-center" id="mobile-pagination-{{ $lembaga->id }}"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            if (typeof window.transaksiDataMobile === 'undefined') window.transaksiDataMobile = {};
                            window.transaksiDataMobile[{{ $lembaga->id }}] = @json($transaksiArray);
                        </script>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @if(request('q') || request('status') || request('lembaga_id') || request('jenis_zakat_id') || request('start_date') || request('end_date'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('superadmin.transaksi-penerimaan.index') }}" class="text-sm text-green-600 hover:text-green-700">Reset semua filter</a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data lembaga</p>
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
const TRANSAKSI_PER_PAGE = 10;
const transaksiPages = {};

function renderTransaksiPage(lembagaId, page) {
    const data = window.transaksiData?.[lembagaId] ?? [];
    const total = data.length;
    const totalPages = Math.ceil(total / TRANSAKSI_PER_PAGE) || 1;
    page = Math.max(1, Math.min(page, totalPages));
    transaksiPages[lembagaId] = page;

    const start = (page - 1) * TRANSAKSI_PER_PAGE;
    const slice = data.slice(start, start + TRANSAKSI_PER_PAGE);
    const end = Math.min(start + slice.length, total);

    // Desktop
    const tbody = document.getElementById(`transaksi-tbody-${lembagaId}`);
    if (tbody) {
        if (slice.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada data transaksi</td></tr>`;
        } else {
            tbody.innerHTML = slice.map(t => {
                const statusMap = {
                    verified: { bg: 'bg-green-100 text-green-800', dot: 'bg-green-500', label: 'Verified' },
                    pending: { bg: 'bg-yellow-100 text-yellow-800', dot: 'bg-yellow-500', label: 'Pending' },
                    rejected: { bg: 'bg-red-100 text-red-800', dot: 'bg-red-500', label: 'Rejected' }
                };
                const s = statusMap[t.status] ?? statusMap.pending;
                const jumlahFormatted = 'Rp ' + Number(t.jumlah).toLocaleString('id-ID');
                
                return `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">${escapeHtml(t.no_transaksi)}</div>
                        <div class="text-xs text-gray-400">${escapeHtml(t.tanggal)} • ${escapeHtml(t.waktu)}</div>
                     </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-xs font-semibold text-green-600">${escapeHtml(t.muzakki_nama.charAt(0).toUpperCase())}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-900">${escapeHtml(t.muzakki_nama)}</div>
                        </div>
                     </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${escapeHtml(t.jenis_zakat)}</span>
                     </td>
                    <td class="px-4 py-3 text-right">
                        <span class="text-sm font-semibold text-gray-800">${jumlahFormatted}</span>
                     </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${s.bg}">
                            <span class="w-1.5 h-1.5 rounded-full ${s.dot} mr-1"></span>${s.label}
                        </span>
                     </td>
                 </tr>`;
            }).join('');
        }
    }

    const info = document.getElementById(`transaksi-info-${lembagaId}`);
    if (info) info.textContent = total > 0 ? `Menampilkan ${start + 1}–${end} dari ${total} transaksi` : 'Tidak ada data';

    const pag = document.getElementById(`transaksi-pagination-${lembagaId}`);
    if (pag) pag.innerHTML = buildPagination(lembagaId, page, totalPages);

    // Mobile view
    renderMobileTransaksi(lembagaId, page, totalPages);
}

function renderMobileTransaksi(lembagaId, page, totalPages) {
    const data = window.transaksiDataMobile?.[lembagaId] ?? [];
    const total = data.length;
    const start = (page - 1) * TRANSAKSI_PER_PAGE;
    const slice = data.slice(start, start + TRANSAKSI_PER_PAGE);
    const end = Math.min(start + slice.length, total);

    const container = document.getElementById(`mobile-transaksi-container-${lembagaId}`);
    if (container) {
        if (slice.length === 0) {
            container.innerHTML = `<p class="text-sm text-gray-400 italic">Tidak ada data transaksi</p>`;
        } else {
            container.innerHTML = slice.map(t => {
                const statusMap = {
                    verified: { bg: 'bg-green-100 text-green-800', dot: 'bg-green-500', label: 'Verified' },
                    pending: { bg: 'bg-yellow-100 text-yellow-800', dot: 'bg-yellow-500', label: 'Pending' },
                    rejected: { bg: 'bg-red-100 text-red-800', dot: 'bg-red-500', label: 'Rejected' }
                };
                const s = statusMap[t.status] ?? statusMap.pending;
                const jumlahFormatted = 'Rp ' + Number(t.jumlah).toLocaleString('id-ID');
                
                return `
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-green-600">${escapeHtml(t.initial)}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">${escapeHtml(t.muzakki_nama)}</p>
                                    <p class="text-xs text-gray-400">${escapeHtml(t.no_transaksi)}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-800">${jumlahFormatted}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-2 mt-2">
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${escapeHtml(t.jenis_zakat)}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${s.bg}">
                                        <span class="w-1.5 h-1.5 rounded-full ${s.dot} mr-1"></span>${s.label}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400">${escapeHtml(t.tanggal)} • ${escapeHtml(t.waktu)}</p>
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
    if (current > 1) html += `<button type="button" onclick="renderTransaksiPage(${lembagaId}, ${current - 1})" class="${btnNormal}">‹</button>`;
    else html += `<button disabled class="${btnDisabled}">‹</button>`;
    
    const range = getPageRange(current, total);
    range.forEach(p => {
        if (p === '...') html += `<span class="${btnBase} text-gray-400">…</span>`;
        else html += `<button type="button" onclick="renderTransaksiPage(${lembagaId}, ${p})" class="${p === current ? btnActive : btnNormal}">${p}</button>`;
    });
    
    if (current < total) html += `<button type="button" onclick="renderTransaksiPage(${lembagaId}, ${current + 1})" class="${btnNormal}">›</button>`;
    else html += `<button disabled class="${btnDisabled}">›</button>`;
    return html;
}

function buildMobilePagination(lembagaId, current, total) {
    if (total <= 1) return '';
    const btnBase = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
    const btnNormal = `${btnBase} text-gray-600 hover:bg-gray-100`;
    
    let html = '<div class="flex items-center gap-1">';
    if (current > 1) html += `<button type="button" onclick="renderTransaksiPage(${lembagaId}, ${current - 1})" class="${btnNormal}">‹</button>`;
    html += `<span class="text-xs text-gray-500 mx-2">Halaman ${current} dari ${total}</span>`;
    if (current < total) html += `<button type="button" onclick="renderTransaksiPage(${lembagaId}, ${current + 1})" class="${btnNormal}">›</button>`;
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
    const closeBtn = document.getElementById('closeFilterPanelBtn');
    
    if (filterButton && filterPanel) {
        filterButton.addEventListener('click', () => filterPanel.classList.toggle('hidden'));
    }
    if (closeBtn && filterPanel) {
        closeBtn.addEventListener('click', () => filterPanel.classList.add('hidden'));
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
                    if ((window.transaksiData?.[lembagaId] || window.transaksiDataMobile?.[lembagaId]) && !transaksiPages[lembagaId]) {
                        renderTransaksiPage(lembagaId, 1);
                    }
                }
            }
        });
    });

    // Mobile expandable
    document.querySelectorAll('.expandable-row-mobile').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const icon = this.querySelector('.expand-icon-mobile');
            const chevron = this.querySelector('.expand-icon-mobile-chevron');
            if (targetContent) {
                const isHidden = targetContent.classList.contains('hidden');
                targetContent.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-90');
                if (chevron) chevron.classList.toggle('rotate-90');
                if (isHidden) {
                    const lembagaId = parseInt(targetId.replace('detail-mobile-', ''));
                    if ((window.transaksiData?.[lembagaId] || window.transaksiDataMobile?.[lembagaId]) && !transaksiPages[lembagaId]) {
                        renderTransaksiPage(lembagaId, 1);
                    }
                }
            }
        });
    });
});

function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}
</script>
@endpush