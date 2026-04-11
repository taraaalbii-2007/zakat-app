{{-- resources/views/admin-lembaga/setor-kas/pending.blade.php --}}
@extends('layouts.app')
@section('title', 'Setoran Kas Pending')

@section('content')
    <div class="space-y-6">

        {{-- ── Alert: Ada setoran menunggu review ── --}}
        @if (($summary['pending']->total ?? 0) > 0)
            <div class="flex items-center gap-3 px-5 py-3 bg-primary-50 border border-primary-200 rounded-xl">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-primary-800">
                        {{ number_format($summary['pending']->total ?? 0) }} setoran kas menunggu review
                    </p>
                    <p class="text-xs text-primary-600 mt-0.5">Total nominal pending: Rp
                        {{ number_format($summary['pending']->jumlah ?? 0, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('admin-lembaga.setor-kas.pending') }}"
                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-primary-100 hover:bg-primary-200 text-primary-800 text-xs font-medium rounded-lg transition-all">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $statPending = $summary['pending'] ?? null;
                $statDiterima = $summary['diterima'] ?? null;
                $statDitolak = $summary['ditolak'] ?? null;
            @endphp

            {{-- Total Pending --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 transition-all hover:shadow-lg">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Menunggu Review</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">
                            {{ number_format($statPending->total ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">Rp
                            {{ number_format($statPending->jumlah ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Diterima --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 transition-all hover:shadow-lg">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Total Diterima</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">
                            {{ number_format($statDiterima->total ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">Rp
                            {{ number_format($statDiterima->jumlah ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Ditolak --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 transition-all hover:shadow-lg">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Ditolak</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900">
                            {{ number_format($statDitolak->total ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">Rp
                            {{ number_format($statDitolak->jumlah ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Riwayat Lengkap --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-4 transition-all hover:shadow-lg">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">Riwayat Lengkap</p>
                        <a href="{{ route('admin-lembaga.setor-kas.riwayat') }}"
                            class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">Semua
                            Setoran →</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Setoran Kas Menunggu Review</h1>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $setorans->total() }} setoran perlu ditindaklanjuti</p>
                    </div>

                    <div class="flex gap-2">
                        {{-- Tombol Filter --}}
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-primary-500 hover:bg-primary-50 text-primary-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        {{-- Tombol Riwayat --}}
                        <a href="{{ route('admin-lembaga.setor-kas.riwayat') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-5 py-3 bg-gradient-to-r from-primary-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($setorans->total()) }}</span>
                        <span class="text-sm text-gray-500">Setoran</span>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($statPending->total ?? 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Diterima:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($statDiterima->total ?? 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-xs text-gray-500">Ditolak:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($statDitolak->total ?? 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal Pending:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp
                                {{ number_format($statPending->jumlah ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['q', 'tanggal_dari', 'tanggal_sampai', 'periode']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-primary-50/30">
                <form method="GET" action="{{ route('admin-lembaga.setor-kas.pending') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Setoran</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari no. setor / amil..."
                                    class="pl-8 pr-3 py-1.5 w-full text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Periode (Bulan)</label>
                            <input type="month" name="periode" value="{{ request('periode') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'tanggal_dari', 'tanggal_sampai', 'periode']))
                            <a href="{{ route('admin-lembaga.setor-kas.pending') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-primary-500 hover:bg-primary-50 text-primary-600 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            {{-- Active Filters Tags --}}
            @if (request()->hasAny(['q', 'tanggal_dari', 'tanggal_sampai', 'periode']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('q'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('tanggal_dari'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Dari: {{ request('tanggal_dari') }}
                                <button onclick="removeFilter('tanggal_dari')"
                                    class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('tanggal_sampai'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Sampai: {{ request('tanggal_sampai') }}
                                <button onclick="removeFilter('tanggal_sampai')"
                                    class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('periode'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Periode: {{ request('periode') }}
                                <button onclick="removeFilter('periode')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($setorans->count() > 0)

                {{-- ── DESKTOP VIEW ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-3 text-center w-10"></th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">AMIL &amp; SETORAN</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-28">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($setorans as $setor)
                                @php
                                    $amilNama = $setor->amil->nama_lengkap ?? ($setor->amil->pengguna->username ?? '-');
                                    $canReview = $setor->status === 'pending';
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-primary-50/20 transition-colors cursor-pointer expandable-row
                                {{ $canReview ? 'bg-primary-50/10' : '' }}"
                                    data-target="detail-{{ $setor->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800">
                                                {{ $amilNama }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $setor->tanggal_setor->format('d/m/Y') }}
                                                &middot; <span
                                                    class="font-mono text-gray-600">{{ $setor->no_setor }}</span>
                                                &middot; <span
                                                    class="font-semibold text-gray-700">{{ $setor->jumlah_disetor_formatted }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                <span
                                                    class="px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-800 border border-primary-200">
                                                    Menunggu Review
                                                </span>
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                                                    {{ $setor->periode_dari->format('d M') }} s/d
                                                    {{ $setor->periode_sampai->format('d M Y') }}
                                                </span>
                                                @if ($setor->bukti_foto)
                                                    <span
                                                        class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">
                                                        Ada foto bukti
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if ($canReview)
                                                <div class="relative group/tooltip">
                                                    <button type="button" data-uuid="{{ $setor->uuid }}"
                                                        data-no="{{ $setor->no_setor }}" data-amil="{{ $amilNama }}"
                                                        data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                                        data-periode="{{ $setor->periode_formatted }}"
                                                        data-foto="{{ $setor->bukti_foto }}"
                                                        onclick="openReviewModal(this)"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Review
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="relative group/tooltip">
                                                <a href="{{ route('admin-lembaga.setor-kas.show', $setor->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Detail
                                                    <div
                                                        class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $setor->uuid }}"
                                    class="hidden expandable-content border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-primary-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Setoran —
                                                    {{ $setor->no_setor }}</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Data Amil --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Data Amil</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama Amil</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $amilNama }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">No. Setoran</p>
                                                            <p class="text-sm font-medium font-mono text-gray-800">
                                                                {{ $setor->no_setor }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Dibuat</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $setor->created_at->format('d M Y, H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Detail Setoran --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Detail Setoran</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal Setor</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $setor->tanggal_setor->format('d F Y') }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Periode</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $setor->periode_dari->format('d M Y') }} &mdash;
                                                                {{ $setor->periode_sampai->format('d M Y') }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Jumlah Disetor</p>
                                                            <p class="text-sm font-semibold text-green-600">
                                                                {{ $setor->jumlah_disetor_formatted }}</p>
                                                        </div>
                                                        @if ($setor->catatan)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Catatan</p>
                                                                <p class="text-sm text-gray-700">{{ $setor->catatan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status & Bukti --}}
                                                <div>
                                                    <h4
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Status &amp; Bukti</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status</p>
                                                            <div class="mt-1">
                                                                <span
                                                                    class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-800 border border-primary-200">
                                                                    Menunggu Review
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400 mb-1.5">Foto Bukti</p>
                                                            @if ($setor->bukti_foto)
                                                                <a href="{{ Storage::url($setor->bukti_foto) }}"
                                                                    target="_blank" class="inline-block">
                                                                    <div
                                                                        class="w-16 h-16 rounded-lg border border-gray-200 overflow-hidden bg-gray-100 hover:opacity-80 transition-opacity">
                                                                        <img src="{{ Storage::url($setor->bukti_foto) }}"
                                                                            alt="Bukti Setor"
                                                                            class="w-full h-full object-cover">
                                                                    </div>
                                                                </a>
                                                                <p class="text-xs text-blue-600 mt-1">Klik untuk perbesar
                                                                </p>
                                                            @else
                                                                <p class="text-xs text-gray-400">Tidak ada foto</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Action footer di expandable --}}
                                            @if ($canReview)
                                                <div class="pt-3 border-t border-gray-200 flex justify-end gap-2">
                                                    <button type="button" data-uuid="{{ $setor->uuid }}"
                                                        data-no="{{ $setor->no_setor }}" data-amil="{{ $amilNama }}"
                                                        data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                                        data-periode="{{ $setor->periode_formatted }}"
                                                        data-foto="{{ $setor->bukti_foto }}"
                                                        onclick="openReviewModal(this)"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-100 hover:bg-primary-200 text-primary-800 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Review Setoran
                                                    </button>
                                                    <a href="{{ route('admin-lembaga.setor-kas.show', $setor->uuid) }}"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat Detail
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── MOBILE VIEW ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($setorans as $setor)
                        @php
                            $amilNama = $setor->amil->nama_lengkap ?? ($setor->amil->pengguna->username ?? '-');
                            $canReview = $setor->status === 'pending';
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                        {{ $canReview ? 'bg-primary-50/10' : '' }}"
                            data-target="detail-mobile-{{ $setor->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $amilNama }}</h3>
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-800 border border-primary-200 whitespace-nowrap">
                                            Menunggu Review
                                        </span>
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span
                                            class="text-xs text-gray-500">{{ $setor->tanggal_setor->format('d/m/Y') }}</span>
                                        <span
                                            class="text-xs font-semibold text-gray-700">{{ $setor->jumlah_disetor_formatted }}</span>
                                        <span class="text-xs text-gray-400 font-mono">{{ $setor->no_setor }}</span>
                                    </div>
                                    @if ($setor->bukti_foto)
                                        <div class="mt-1 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-xs text-blue-600">Ada foto bukti</span>
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk detail</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $setor->uuid }}"
                                class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data
                                            Setoran</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">No. Setoran:</span> <span
                                                    class="font-mono">{{ $setor->no_setor }}</span></p>
                                            <p><span class="text-gray-500">Tanggal:</span>
                                                {{ $setor->tanggal_setor->format('d F Y') }}</p>
                                            <p><span class="text-gray-500">Amil:</span> {{ $amilNama }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Periode &amp; Nominal</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Periode:</span>
                                                {{ $setor->periode_dari->format('d M Y') }} &mdash;
                                                {{ $setor->periode_sampai->format('d M Y') }}
                                            </p>
                                            <p><span class="text-gray-500">Jumlah:</span>
                                                <span
                                                    class="font-semibold text-green-600">{{ $setor->jumlah_disetor_formatted }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    @if ($setor->bukti_foto)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                                Foto Bukti</h4>
                                            <a href="{{ Storage::url($setor->bukti_foto) }}" target="_blank"
                                                class="block">
                                                <div
                                                    class="w-full h-32 rounded-lg border border-gray-200 overflow-hidden bg-gray-100">
                                                    <img src="{{ Storage::url($setor->bukti_foto) }}" alt="Bukti Setor"
                                                        class="w-full h-full object-contain">
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    {{-- Mobile Action Buttons --}}
                                    @if ($canReview)
                                        <div class="pt-2 flex items-center gap-2 flex-wrap">
                                            <button type="button" data-uuid="{{ $setor->uuid }}"
                                                data-no="{{ $setor->no_setor }}" data-amil="{{ $amilNama }}"
                                                data-jumlah="{{ $setor->jumlah_disetor_formatted }}"
                                                data-periode="{{ $setor->periode_formatted }}"
                                                data-foto="{{ $setor->bukti_foto }}" onclick="openReviewModal(this)"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-xs ml-1">Review</span>
                                            </button>
                                            <a href="{{ route('admin-lembaga.setor-kas.show', $setor->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span class="text-xs ml-1">Detail</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($setorans->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $setorans->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    @if (request()->hasAny(['q', 'tanggal_dari', 'tanggal_sampai', 'periode']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('admin-lembaga.setor-kas.pending') }}"
                            class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm font-medium text-gray-700 mb-1">Semua setoran sudah diproses</p>
                        <p class="text-sm text-gray-500">Tidak ada setoran kas yang menunggu konfirmasi.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── Modal: Review Setoran ── --}}
    <div id="review-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col"
            style="max-height:85vh;">

            {{-- Modal Header --}}
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Review Setoran Kas</h3>
                    <p class="text-xs text-gray-400 font-mono mt-0.5" id="modal-no-setor"></p>
                </div>
            </div>

            {{-- Scrollable Body --}}
            <div class="overflow-y-auto flex-1">
                <form id="review-form" method="POST">
                    @csrf
                    <div class="p-5 space-y-4">

                        {{-- Alert error --}}
                        <div id="modal-alert"
                            class="hidden px-3 py-2.5 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700">
                        </div>

                        {{-- Info Setoran --}}
                        <div class="bg-primary-50 border border-primary-100 rounded-xl p-3.5 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Amil</span>
                                <span class="text-sm font-semibold text-gray-900" id="modal-amil"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Jumlah Disetor</span>
                                <span class="text-base font-bold text-primary-700" id="modal-jumlah"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Periode</span>
                                <span class="text-xs text-gray-700 font-medium" id="modal-periode"></span>
                            </div>
                        </div>

                        {{-- Foto Bukti --}}
                        <div>
                            <p class="text-xs font-medium text-gray-600 mb-1.5">Foto Bukti Setoran</p>
                            <div id="modal-foto-bukti"
                                class="rounded-xl border border-gray-200 overflow-hidden bg-gray-50">
                                <div class="flex items-center justify-center p-6 text-center text-gray-400">
                                    <div>
                                        <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs">Tidak ada foto bukti</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hitung Fisik --}}
                        <div>
                            <label for="jumlah_dihitung_fisik" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Jumlah Dihitung Fisik
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">Rp</span>
                                <input type="number" name="jumlah_dihitung_fisik" id="jumlah_dihitung_fisik"
                                    placeholder="0" min="0" step="1000"
                                    class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all">
                            </div>
                        </div>

                        {{-- Radio: Terima / Tolak --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2">
                                Keputusan <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <label id="label-diterima"
                                    class="flex items-center gap-2.5 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-green-300">
                                    <input type="radio" name="aksi" value="diterima"
                                        class="w-3.5 h-3.5 text-green-600 flex-shrink-0" onchange="toggleAksi(this)">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900">✓ Terima</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Konfirmasi setoran</p>
                                    </div>
                                </label>
                                <label id="label-ditolak"
                                    class="flex items-center gap-2.5 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-red-300">
                                    <input type="radio" name="aksi" value="ditolak"
                                        class="w-3.5 h-3.5 text-red-600 flex-shrink-0" onchange="toggleAksi(this)">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900">✕ Tolak</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Kembalikan ke amil</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Alasan Penolakan --}}
                        <div id="alasan-container" class="hidden">
                            <label for="alasan_penolakan" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3"
                                placeholder="Jelaskan alasan penolakan kepada amil..."
                                class="block w-full px-3 py-2 text-sm border border-red-200 bg-red-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400 transition-all resize-none"></textarea>
                        </div>

                        {{-- Tanda Tangan Penerima --}}
                        <div id="ttd-container" class="hidden">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-medium text-gray-600">
                                    Tanda Tangan Penerima
                                    <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <button type="button" onclick="clearSignaturePenerima()"
                                    class="text-[10px] text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                            <div
                                class="relative rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 overflow-hidden">
                                <canvas id="signature-pad-penerima" class="block w-full cursor-crosshair touch-none"
                                    style="height:110px;"></canvas>
                                <div id="ttd-placeholder"
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="text-center">
                                        <svg class="w-6 h-6 text-gray-300 mx-auto mb-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <p class="text-[10px] text-gray-400">Tanda tangan di sini</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="tanda_tangan_penerima" id="ttd_penerima_input">
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div
                        class="px-5 py-3.5 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-2 sticky bottom-0">
                        <button type="button" onclick="closeModal('review-modal')"
                            class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-review"
                            class="px-4 py-2 bg-primary-500 hover:bg-primary-600 rounded-xl text-sm font-medium text-white transition-all shadow-sm inline-flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Keputusan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon = this.querySelector('.expand-icon');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-90');
                    }
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon = this.querySelector('.expand-icon-mobile');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });

            // ── Filter toggle ──
            const filterBtn = document.getElementById('filterButton');
            if (filterBtn) {
                filterBtn.addEventListener('click', toggleFilter);
            }

            // ── Modal backdrop & ESC ──
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeModal('review-modal');
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal('review-modal');
            });

            // ── Validasi & submit form review ──
            const reviewForm = document.getElementById('review-form');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = this;
                    const aksi = form.querySelector('input[name="aksi"]:checked');

                    if (!aksi) {
                        showModalAlert('Pilih keputusan terlebih dahulu (Terima atau Tolak).');
                        return;
                    }

                    if (aksi.value === 'ditolak') {
                        const alasan = document.getElementById('alasan_penolakan').value.trim();
                        if (!alasan) {
                            const alasanField = document.getElementById('alasan_penolakan');
                            alasanField.focus();
                            alasanField.classList.add('ring-2', 'ring-red-400');
                            showModalAlert('Alasan penolakan harus diisi.');
                            return;
                        }
                    }

                    if (!form.action || !form.action.includes('/proses')) {
                        showModalAlert(
                            'Terjadi kesalahan konfigurasi form. Coba tutup dan buka modal kembali.');
                        return;
                    }

                    // Simpan TTD ke hidden field
                    const ttdInput = document.getElementById('ttd_penerima_input');
                    if (window.sigCanvas && ttdInput && ttdInput.value.length < 100) {
                        try {
                            ttdInput.value = window.sigCanvas.toDataURL('image/png');
                        } catch (_) {}
                    }

                    const btnSubmit = document.getElementById('btn-submit-review');
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML =
                        '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Menyimpan...';

                    fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json, text/html'
                            },
                            credentials: 'same-origin',
                        })
                        .then(res => {
                            if (res.redirected) {
                                window.location.href = res.url;
                                return;
                            }
                            if (res.ok) {
                                window.location.reload();
                                return;
                            }
                            throw new Error('Server error: ' + res.status);
                        })
                        .catch(err => {
                            console.error(err);
                            btnSubmit.disabled = false;
                            btnSubmit.innerHTML =
                                '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Keputusan';
                            showModalAlert('Gagal menyimpan. Silakan coba lagi.');
                        });
                });
            }

            const alasanPenolakan = document.getElementById('alasan_penolakan');
            if (alasanPenolakan) {
                alasanPenolakan.addEventListener('input', function() {
                    this.classList.remove('ring-2', 'ring-red-400');
                });
            }
        });

        // ── Modal helpers ──
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        };

        function showModalAlert(msg) {
            var el = document.getElementById('modal-alert');
            if (el) {
                el.textContent = msg;
                el.classList.remove('hidden');
                setTimeout(function() {
                    el.classList.add('hidden');
                }, 4000);
            } else {
                alert(msg);
            }
        }

        // ── Filter toggle ──
        function toggleFilter() {
            const panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        }

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        // ── Review Modal ──
        function openReviewModal(btn) {
            document.getElementById('modal-no-setor').textContent = btn.dataset.no || '-';
            document.getElementById('modal-amil').textContent = btn.dataset.amil || '-';
            document.getElementById('modal-jumlah').textContent = btn.dataset.jumlah || 'Rp 0';
            document.getElementById('modal-periode').textContent = btn.dataset.periode || '-';
            document.getElementById('review-form').action = '/admin-setor-kas/' + btn.dataset.uuid + '/proses';

            // Reset semua field
            const radioButtons = document.querySelectorAll('input[name="aksi"]');
            radioButtons.forEach(function(r) {
                r.checked = false;
            });

            const alasanContainer = document.getElementById('alasan-container');
            const ttdContainer = document.getElementById('ttd-container');
            if (alasanContainer) alasanContainer.classList.add('hidden');
            if (ttdContainer) ttdContainer.classList.add('hidden');

            const alasanField = document.getElementById('alasan_penolakan');
            if (alasanField) {
                alasanField.value = '';
                alasanField.classList.remove('ring-2', 'ring-red-400');
            }

            const jumlahHitung = document.getElementById('jumlah_dihitung_fisik');
            if (jumlahHitung) jumlahHitung.value = '';

            const ttdInput = document.getElementById('ttd_penerima_input');
            if (ttdInput) ttdInput.value = '';

            // Reset label radio
            ['label-diterima', 'label-ditolak'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) {
                    el.classList.remove('border-green-400', 'bg-green-50', 'border-red-400', 'bg-red-50');
                    el.classList.add('border-gray-200');
                }
            });

            // Foto bukti
            var fotoContainer = document.getElementById('modal-foto-bukti');
            var foto = btn.dataset.foto;
            if (fotoContainer) {
                if (foto && foto !== 'null' && foto !== '') {
                    var fotoUrl = '/storage/' + foto;
                    fotoContainer.innerHTML = '<div class="relative"><img src="' + fotoUrl +
                        '" alt="Foto Bukti" class="w-full h-auto max-h-64 object-contain bg-gray-100"><a href="' + fotoUrl +
                        '" target="_blank" class="absolute bottom-2 right-2 bg-white rounded-lg p-2 shadow-md hover:bg-gray-50 transition-colors"><svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a></div>';
                } else {
                    fotoContainer.innerHTML =
                        '<div class="flex items-center justify-center p-8 text-center text-gray-400"><div><svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-sm">Tidak ada foto bukti</p></div></div>';
                }
            }

            // Reset submit button
            var btnSubmit = document.getElementById('btn-submit-review');
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML =
                    '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Keputusan';
            }

            openModal('review-modal');
            setTimeout(initSignaturePad, 120);
        }

        // ── Toggle Aksi Radio ──
        function toggleAksi(radio) {
            var alasanBox = document.getElementById('alasan-container');
            var ttdBox = document.getElementById('ttd-container');

            ['label-diterima', 'label-ditolak'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) {
                    el.classList.remove('border-green-400', 'bg-green-50', 'border-red-400', 'bg-red-50');
                    el.classList.add('border-gray-200');
                }
            });

            if (radio.value === 'diterima') {
                const labelDiterima = document.getElementById('label-diterima');
                if (labelDiterima) {
                    labelDiterima.classList.remove('border-gray-200');
                    labelDiterima.classList.add('border-green-400', 'bg-green-50');
                }
                if (alasanBox) alasanBox.classList.add('hidden');
                if (ttdBox) ttdBox.classList.remove('hidden');
                setTimeout(initSignaturePad, 80);
            } else {
                const labelDitolak = document.getElementById('label-ditolak');
                if (labelDitolak) {
                    labelDitolak.classList.remove('border-gray-200');
                    labelDitolak.classList.add('border-red-400', 'bg-red-50');
                }
                if (alasanBox) alasanBox.classList.remove('hidden');
                if (ttdBox) ttdBox.classList.add('hidden');
                setTimeout(function() {
                    const alasanField = document.getElementById('alasan_penolakan');
                    if (alasanField) alasanField.focus();
                }, 50);
            }
        }

        // ── Signature Pad ──
        var sigCanvas, sigCtx, sigDrawing = false,
            sigLastX = 0,
            sigLastY = 0;

        function initSignaturePad() {
            sigCanvas = document.getElementById('signature-pad-penerima');
            if (!sigCanvas) return;
            window.sigCanvas = sigCanvas;
            sigCtx = sigCanvas.getContext('2d');

            var container = sigCanvas.parentElement;
            var rect = container.getBoundingClientRect();
            var dpr = window.devicePixelRatio || 1;

            sigCanvas.width = rect.width * dpr;
            sigCanvas.height = 140 * dpr;
            sigCanvas.style.width = rect.width + 'px';
            sigCanvas.style.height = '140px';
            sigCtx.scale(dpr, dpr);
            sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

            const placeholder = document.getElementById('ttd-placeholder');
            if (placeholder) placeholder.style.display = 'flex';

            const ttdInput = document.getElementById('ttd_penerima_input');
            if (ttdInput) ttdInput.value = '';

            var newCanvas = sigCanvas.cloneNode(true);
            sigCanvas.parentNode.replaceChild(newCanvas, sigCanvas);
            sigCanvas = newCanvas;
            window.sigCanvas = sigCanvas;
            sigCtx = sigCanvas.getContext('2d');
            sigCtx.scale(dpr, dpr);

            function getPos(e) {
                var r = sigCanvas.getBoundingClientRect();
                if (e.touches) return {
                    x: e.touches[0].clientX - r.left,
                    y: e.touches[0].clientY - r.top
                };
                return {
                    x: e.clientX - r.left,
                    y: e.clientY - r.top
                };
            }

            function startDraw(e) {
                e.preventDefault();
                sigDrawing = true;
                const placeholder = document.getElementById('ttd-placeholder');
                if (placeholder) placeholder.style.display = 'none';
                var p = getPos(e);
                sigLastX = p.x;
                sigLastY = p.y;
            }

            function draw(e) {
                e.preventDefault();
                if (!sigDrawing) return;
                var p = getPos(e);
                sigCtx.beginPath();
                sigCtx.moveTo(sigLastX, sigLastY);
                sigCtx.lineTo(p.x, p.y);
                sigCtx.strokeStyle = '#1e293b';
                sigCtx.lineWidth = 2;
                sigCtx.lineCap = 'round';
                sigCtx.lineJoin = 'round';
                sigCtx.stroke();
                sigLastX = p.x;
                sigLastY = p.y;
            }

            function endDraw() {
                if (!sigDrawing) return;
                sigDrawing = false;
                const ttdInput = document.getElementById('ttd_penerima_input');
                if (ttdInput) ttdInput.value = sigCanvas.toDataURL('image/png');
            }

            sigCanvas.addEventListener('mousedown', startDraw);
            sigCanvas.addEventListener('mousemove', draw);
            sigCanvas.addEventListener('mouseup', endDraw);
            sigCanvas.addEventListener('mouseleave', endDraw);
            sigCanvas.addEventListener('touchstart', startDraw, {
                passive: false
            });
            sigCanvas.addEventListener('touchmove', draw, {
                passive: false
            });
            sigCanvas.addEventListener('touchend', endDraw);
        }

        function clearSignaturePenerima() {
            if (!window.sigCanvas || !sigCtx) return;
            var dpr = window.devicePixelRatio || 1;
            sigCtx.clearRect(0, 0, window.sigCanvas.width / dpr, window.sigCanvas.height / dpr);
            const ttdInput = document.getElementById('ttd_penerima_input');
            if (ttdInput) ttdInput.value = '';
            const placeholder = document.getElementById('ttd-placeholder');
            if (placeholder) placeholder.style.display = 'flex';
        }
    </script>
@endpush
