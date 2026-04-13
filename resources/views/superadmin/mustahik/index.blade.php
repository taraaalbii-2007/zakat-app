@extends('layouts.app')

@section('title', 'Data Mustahik Semua Lembaga')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

           <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Data Mustahik Semua Lembaga</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi data mustahik dari seluruh lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']) ? 'bg-green-50' : '' }}">
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
                        <span class="text-sm font-semibold text-gray-800">{{ $totalMustahik }}</span>
                        <span class="text-sm text-gray-500">Mustahik dari</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $lembagas->count() }}</span>
                        <span class="text-sm text-gray-500">Lembaga</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.mustahik.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Lembaga</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari nama lembaga..."
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

                            <!-- Filter Status Verifikasi -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status Verifikasi</label>
                                <select name="status_verifikasi"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="verified" {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="pending" {{ request('status_verifikasi') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ request('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Filter Status Keaktifan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status Keaktifan</label>
                                <select name="is_active"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status_verifikasi', 'is_active', 'lembaga_id', 'kategori_id']))
                            <a href="{{ route('superadmin.mustahik.index') }}"
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
            @if(request()->hasAny(['q', 'status_verifikasi', 'is_active', 'lembaga_id']))
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
                        @if(request('status_verifikasi'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Verifikasi: {{ request('status_verifikasi') == 'verified' ? 'Verified' : (request('status_verifikasi') == 'pending' ? 'Pending' : 'Rejected') }}
                                <button onclick="removeFilter('status_verifikasi')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('is_active'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ request('is_active') == '1' ? 'Aktif' : 'Nonaktif' }}
                                <button onclick="removeFilter('is_active')" class="hover:text-green-900 ml-1">×</button>
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
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500">JUMLAH MUSTAHIK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lembagas as $lembaga)
                                @php
                                    $mustahikArray = $lembaga->mustahiks->map(function($m) {
                                        return [
                                            'id' => $m->id,
                                            'no_registrasi' => $m->no_registrasi ?? '-',
                                            'tanggal' => optional($m->created_at)->format('d M Y') ?? '-',
                                            'nama' => $m->nama_lengkap ?? $m->user->name ?? '-',
                                            'nik' => $m->nik ?? null,
                                            'initial' => strtoupper(substr($m->nama ?? $m->user->name ?? 'M', 0, 1)),
                                            'kategori' => $m->kategoriMustahik->nama ?? '-',
                                            'status_verifikasi' => $m->status_verifikasi ?? 'pending',
                                            'is_active' => (bool) ($m->is_active ?? true),
                                        ];
                                    })->toArray();
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
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat mustahik</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            {{ $lembaga->mustahiks->count() }} Mustahik
                                        </span>
                                    </td>
                                </tr>

                                <!-- Expandable Row dengan Pagination -->
                                <tr id="detail-{{ $lembaga->id }}" class="hidden border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="3" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Daftar Mustahik — {{ $lembaga->nama }}</h3>
                                            </div>

                                            @if ($lembaga->mustahiks->isEmpty())
                                                <div class="text-center py-8 text-sm text-gray-400 bg-white rounded-xl border">Belum ada data mustahik</div>
                                            @else
                                                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">NO. REGISTRASI</th>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">NAMA</th>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">KATEGORI</th>
                                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">STATUS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="mustahik-tbody-{{ $lembaga->id }}"></tbody>
                                                    </table>
                                                </div>
                                                <div class="bg-gray-50 border border-gray-100 px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-lg">
                                                    <span id="mustahik-info-{{ $lembaga->id }}" class="text-xs text-gray-500"></span>
                                                    <div id="mustahik-pagination-{{ $lembaga->id }}" class="flex items-center justify-center gap-1"></div>
                                                </div>
                                                
                                                <script>
                                                    if (typeof window.mustahikData === 'undefined') window.mustahikData = {};
                                                    window.mustahikData[{{ $lembaga->id }}] = @json($mustahikArray);
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
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                                                {{ $lembaga->mustahiks->count() }} Mustahik
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
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Daftar Mustahik</h4>
                                        @if ($lembaga->mustahiks->isEmpty())
                                            <p class="text-sm text-gray-400 italic">Belum ada data mustahik</p>
                                        @else
                                            <div class="space-y-3" id="mobile-mustahik-container-{{ $lembaga->id }}">
                                                <!-- Akan diisi JS -->
                                            </div>
                                            <div class="mt-3 flex justify-center" id="mobile-pagination-{{ $lembaga->id }}"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @if(request('q') || request('status_verifikasi') || request('is_active') || request('lembaga_id'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('superadmin.mustahik.index') }}" class="text-sm text-green-600 hover:text-green-700">Reset semua filter</a>
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
const MUSTAHIK_PER_PAGE = 10;
const mustahikPages = {};

function renderMustahikPage(lembagaId, page) {
    const data = window.mustahikData?.[lembagaId] ?? [];
    const total = data.length;
    const totalPages = Math.ceil(total / MUSTAHIK_PER_PAGE) || 1;
    page = Math.max(1, Math.min(page, totalPages));
    mustahikPages[lembagaId] = page;

    const start = (page - 1) * MUSTAHIK_PER_PAGE;
    const slice = data.slice(start, start + MUSTAHIK_PER_PAGE);
    const end = Math.min(start + slice.length, total);

    // Desktop
    const tbody = document.getElementById(`mustahik-tbody-${lembagaId}`);
    if (tbody) {
        if (slice.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada data mustahik</td></tr>`;
        } else {
            tbody.innerHTML = slice.map(m => {
                const vMap = {
                    verified: { bg: 'bg-green-100 text-green-800', dot: 'bg-green-500', label: 'Verified' },
                    pending:  { bg: 'bg-yellow-100 text-yellow-800', dot: 'bg-yellow-500', label: 'Pending' },
                    rejected: { bg: 'bg-red-100 text-red-800', dot: 'bg-red-500', label: 'Rejected' }
                };
                const v = vMap[m.status_verifikasi] ?? vMap.pending;
                return `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">${escapeHtml(m.no_registrasi)}</div>
                        <div class="text-xs text-gray-400">${escapeHtml(m.tanggal)}</div>
                      </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-xs font-semibold text-green-600">${escapeHtml(m.initial)}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${escapeHtml(m.nama)}</div>
                                ${m.nik ? `<div class="text-xs text-gray-400">NIK: ${escapeHtml(m.nik)}</div>` : ''}
                            </div>
                        </div>
                      </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${escapeHtml(m.kategori)}</span>
                      </td>
                    <td class="px-4 py-3">
                        <div class="space-y-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${v.bg}">
                                <span class="w-1.5 h-1.5 rounded-full ${v.dot} mr-1"></span>${v.label}
                            </span>
                            ${m.is_active ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ml-1">Aktif</span>' : '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 ml-1">Nonaktif</span>'}
                        </div>
                      </td>
                  </tr>`;
            }).join('');
        }
    }

    const info = document.getElementById(`mustahik-info-${lembagaId}`);
    if (info) info.textContent = total > 0 ? `Menampilkan ${start + 1}–${end} dari ${total} mustahik` : 'Tidak ada data';

    const pag = document.getElementById(`mustahik-pagination-${lembagaId}`);
    if (pag) pag.innerHTML = buildPagination(lembagaId, page, totalPages);

    // Mobile view
    renderMobileMustahik(lembagaId, page, totalPages);
}

function renderMobileMustahik(lembagaId, page, totalPages) {
    const data = window.mustahikData?.[lembagaId] ?? [];
    const total = data.length;
    const start = (page - 1) * MUSTAHIK_PER_PAGE;
    const slice = data.slice(start, start + MUSTAHIK_PER_PAGE);
    const end = Math.min(start + slice.length, total);

    const container = document.getElementById(`mobile-mustahik-container-${lembagaId}`);
    if (container) {
        if (slice.length === 0) {
            container.innerHTML = `<p class="text-sm text-gray-400 italic">Tidak ada data mustahik</p>`;
        } else {
            container.innerHTML = slice.map(m => {
                const vMap = {
                    verified: { bg: 'bg-green-100 text-green-800', dot: 'bg-green-500', label: 'Verified' },
                    pending:  { bg: 'bg-yellow-100 text-yellow-800', dot: 'bg-yellow-500', label: 'Pending' },
                    rejected: { bg: 'bg-red-100 text-red-800', dot: 'bg-red-500', label: 'Rejected' }
                };
                const v = vMap[m.status_verifikasi] ?? vMap.pending;
                return `
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-sm font-semibold text-green-600">${escapeHtml(m.initial)}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between flex-wrap gap-1">
                                <p class="text-sm font-medium text-gray-900">${escapeHtml(m.nama)}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${v.bg}">
                                    <span class="w-1.5 h-1.5 rounded-full ${v.dot} mr-1"></span>${v.label}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">${escapeHtml(m.no_registrasi)}</p>
                            <p class="text-xs text-blue-600 mt-1">${escapeHtml(m.kategori)}</p>
                            <div class="mt-1">
                                ${m.is_active ? '<span class="inline-flex px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>' : '<span class="inline-flex px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>'}
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
    if (current > 1) html += `<button type="button" onclick="renderMustahikPage(${lembagaId}, ${current - 1})" class="${btnNormal}">‹</button>`;
    else html += `<button disabled class="${btnDisabled}">‹</button>`;
    
    const range = getPageRange(current, total);
    range.forEach(p => {
        if (p === '...') html += `<span class="${btnBase} text-gray-400">…</span>`;
        else html += `<button type="button" onclick="renderMustahikPage(${lembagaId}, ${p})" class="${p === current ? btnActive : btnNormal}">${p}</button>`;
    });
    
    if (current < total) html += `<button type="button" onclick="renderMustahikPage(${lembagaId}, ${current + 1})" class="${btnNormal}">›</button>`;
    else html += `<button disabled class="${btnDisabled}">›</button>`;
    return html;
}

function buildMobilePagination(lembagaId, current, total) {
    if (total <= 1) return '';
    const btnBase = 'inline-flex items-center justify-center w-7 h-7 rounded-md text-xs font-medium transition-colors';
    const btnNormal = `${btnBase} text-gray-600 hover:bg-gray-100`;
    const btnDisabled = `${btnBase} text-gray-300 cursor-not-allowed`;
    
    let html = '<div class="flex items-center gap-1">';
    if (current > 1) html += `<button type="button" onclick="renderMustahikPage(${lembagaId}, ${current - 1})" class="${btnNormal}">‹</button>`;
    else html += `<button disabled class="${btnDisabled}">‹</button>`;
    html += `<span class="text-xs text-gray-500 mx-2">Halaman ${current} dari ${total}</span>`;
    if (current < total) html += `<button type="button" onclick="renderMustahikPage(${lembagaId}, ${current + 1})" class="${btnNormal}">›</button>`;
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
                    if (window.mustahikData?.[lembagaId] && !mustahikPages[lembagaId]) {
                        renderMustahikPage(lembagaId, 1);
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
                    if (window.mustahikData?.[lembagaId] && !mustahikPages[lembagaId]) {
                        renderMustahikPage(lembagaId, 1);
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
            if (window.mustahikData?.[lembagaId] && !mustahikPages[lembagaId]) {
                renderMustahikPage(lembagaId, 1);
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